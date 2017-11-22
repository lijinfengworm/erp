<?php

/**
 *   会员权益服务
 *   kworm
 *   最后更新时间  2017-01-04
 */
class benefitsKaluliService extends kaluliService {
	
	/**
	 * 检查会员权益
	 */
	public function executeCheckBenefits(){
		$account = $this->request->getParameter("account");
		$item_ids = $this->request->getParameter("item_ids");
		
		$nowtime = time();
		$check =  KllMemberBenefitsTable::getInstance()->createQuery()->where('code = ?',$account)->andWhere("status =?",1)->andWhere('start_time <= ?',$nowtime)->andWhere('end_time >= ?',$nowtime)->limit(1)->fetchOne();
		if($check){
			//检查是否可用
			$flag = 0;
			$id = $check->getId();
			$item = KllMemberBenefitsSkuTable::getInstance()->findByMbId($id);
			$items = [];
			if(!empty($item)){
				foreach ($item as $key => $val) {
					$items[] = $val->getSkuId();
				}
				$join = array_intersect($items, $item_ids);
				
				!empty($join) && $flag = 1;
			}

						
			$number = $check->getTimes();
			$range = $check->getRange();
			if($range == 1){
				$flag = 1;
			}
			if($number > 0){
				$_discount = 10-$check->getDiscount()/10;

				$data = [
					'id' => $check->getId(),
					'record_id' => '',
					'lipinka_id' => $check->getId(),
					'account' => $check->getCode(),
					'user_id' => '',
					'is_large' => '',
					'large_id' => '',
					'postpone_type' => 1,
					'postpone_day' => '',
					'overdue_time' => $check->getEndTime(),
					'stime' => $check->getStartTime(),
					'etime' => $check->getEndTime(),
					'status' => $range,
					'create_type' => '',
					'sync_status' => 1,
					'amount' => '',
					'card_limit' => 'order_money='.$check->getDiscount(),
					'created_at' => '',
					'updated_at' => '',
					'card_limit_parse'  => [
							'order_money' => $_discount.'折（最高优惠'.$check->getToplimit().'元）',
					],
				    'flag' => $flag,
					'top_limit'=>$check->getToplimit(),
				    'current' => 0,
				    'card_type' => 2,
					'acoount_parse' => [
						     'account' => $_discount.'折('.$account.')',
						     'order_money' => '(最高优惠'.$check->getToplimit().'元）'
					]
				];

				return $this->success($data);
			}else{
				return $this->error(404, '没有了');
			}
		}else{
			return $this->error(500, '不存在');
		}

	}
	public function executeGetActivity(){
		$activity_save = 0;
		$goods = $this->request->getParameter("goods");
		$card_id = $this->request->getParameter("card_id");
		$benefits = KllMemberBenefitsTable::getInstance()->findOneById($card_id);
        if(!empty($benefits)){
            $times = $benefits->getTimes();
            if($times != 0 ){
            	$discount_price = 0;
				$status = $benefits->getStatus();
				$range = $benefits->getRange();
				if($status == 1){
					//全场活动
					if($range == 1){
						foreach ($goods as $key => $item) {
							//设置排它活动，就是参加了这个就不再参与其他任何活动
							$goods[$key]['out'] = 1;
							$goods[$key]['save'] = 0;
							//开始逻辑
							if(!empty($card_id)){
								//商品在活动中
								$discount = $benefits->getDiscount();
								$toplimit = $benefits->getToplimit();
								$tmp_discount = $item['price']  * ($discount/100);
								if($discount_price == 0) { //第一次进入判断
									if((int)$tmp_discount > (int)$toplimit) {
										$activity_save = $toplimit;
										$goods[$key]['save'] = $toplimit;
									} else {
										$activity_save += $tmp_discount;
										$goods[$key]['save'] = $tmp_discount;
									}
								} else {
									if(($discount_price + (int)$tmp_discount) > (int)$toplimit) {
										$activity_save = $toplimit;
										$goods[$key]['save'] = ($toplimit > $discount_price)? $toplimit -$discount_price : 0;
									} else {
										$activity_save += $tmp_discount;
										$goods[$key]['save'] = $tmp_discount;
									}
								}
								$discount_price +=  (int)$tmp_discount;
							}

						}

					}else{
						//针对商品的活动
		            	foreach ($goods as $key => $item) {
							//设置排它活动，就是参加了这个就不再参与其他任何活动
							$goods[$key]['out'] = 1;
							$goods[$key]['save'] = 0;
							//开始逻辑
							if(!empty($card_id)){
								//活动进行中, 
								$goodsObj = KllMemberBenefitsSkuTable::getInstance()->findOneByMbIdAndSkuId($card_id, $item['product_id']);

								if(!empty($goodsObj)){
									//商品在活动中
									$discount = $benefits->getDiscount();
									$toplimit = $benefits->getToplimit();
									$tmp_discount = $item['price']  * ($discount/100);
									if($discount_price == 0) { //第一次进入判断
										if((int)$tmp_discount > (int)$toplimit) {
											$activity_save = $toplimit;
											$goods[$key]['save'] = $toplimit;
										} else {
											$activity_save += $tmp_discount;
											$goods[$key]['save'] = $tmp_discount;
										}
									} else {
										if(($discount_price + (int)$tmp_discount) > (int)$toplimit) {
											$activity_save = $toplimit;
											$goods[$key]['save'] = ($toplimit > $discount_price)? $toplimit -$discount_price : 0;
										} else {
											$activity_save += $tmp_discount;
											$goods[$key]['save'] = $tmp_discount;
										}
									}
									$discount_price +=  (int)$tmp_discount;
								}
							}
						}
					}
					return $this->success(['data' => ['activity_save' => $activity_save, 'goods_info' => $goods]]);
				}
				
				
            }
        }
        return $this->error(500, '错误');
	}

	public function executeGetItemsById(){
		$benefitsId = $this->getRequest()->getParameter("id");
		if(empty($benefitsId)) {
			return $this->error("500","参数错误");
		}
		$_page_now = $this->getRequest()->getParameter("page", 1);
		$_page_num = $this->getRequest()->getParameter("pageSize", 8);
		//获取记录总数
		$_count_map['select'] = 'count(id) as num';
		$_count_map['limit'] = $_count_map['is_count'] = 1;
		$_count_map['where']['mb_id'] = 'mb_id = ' . $benefitsId;
		$count = KllMemberBenefitsSkuTable::getInstance()->getAll($_count_map);

		$bind['where']['mb_id'] = 'mb_id = ' . $benefitsId;

		$page = new Core_Lib_Page(array('total_rows' => $count, 'list_rows' => $_page_num, 'now_page' => $_page_now));
		$bind['limit'] = $_page_num;
		$bind['offset'] = (($_page_now - 1) * $_page_num) . ',' . $page->list_rows;
		$list = KllMemberBenefitsSkuTable::getInstance()->getAll($bind);
		if (!$list) {
			return $this->error(500, "没有更多数据了");
		}
		//组装item数据
		foreach ($list as $k=>$v) {
			$itemInfo =KaluliItemTable::getOne($v['sku_id']);
			$list[$k]['title'] = $itemInfo['title'];
			$list[$k]['discount_price'] = $itemInfo['discount_price'];
			$list[$k]['pic'] = $itemInfo['pic'];
			$list[$k]['id'] = $v['sku_id'];
		}
		return $this->success(array("list"=>$list,"count"=>$count));
	}
}