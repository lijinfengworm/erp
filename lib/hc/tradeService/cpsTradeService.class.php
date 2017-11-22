<?php

/**
 * Class cpsTradeService
 * version: 1.0
 */
class cpsTradeService extends tradeService
{

    /**
     * 我的订列表
     *
     */
    public function executeOrderList()
    {
        $union_id = trim($this->getRequest()->getParameter('union_id'));//推广用户
        $stime = trim($this->getRequest()->getParameter('stime'));//起始时间
        $etime = trim($this->getRequest()->getParameter('etime'));//结束时间
        $sn = trim($this->getRequest()->getParameter('sn'));//单号

        if (empty($union_id)) {
            return $this->error(400, '参数有误');
        }
        if ((empty($stime) || empty($etime)) && empty($sn)) {
            return $this->error(400, '参数有误');
        }
        if ((!empty($stime) || !empty($etime)) && !empty($sn)) {
            return $this->error(400, '参数有误');
        }
        if (!empty($stime) && !empty($etime)){
            if ($etime - $stime > 3600*24*3) {
                return $this->error(401, '时间跨度不超过3天');
            }
        }

        $cpsorderObj = CpsOrderTable::getInstance()->createQuery()->where('union_id = ?', $union_id)->andWhere('valid = ?', 0);
        if ($sn) {
            $cpsorderObj->andWhere('order_number = ?', $sn);
        } else {
            $cpsorderObj->andWhere('order_time >= ?', $stime)->andWhere('order_time <= ?', $etime);
        }
        $cpsorder = $cpsorderObj->execute();
        $list = array();
        if (count($cpsorder) > 0) {
            foreach ($cpsorder as $k=>$v){
                $list[$k]['euid'] = $v->get('euid');
                $list[$k]['mid'] = $v->get('mid');
                $list[$k]['order_sn'] = $v->get('order_number');
                $list[$k]['suborder_sn'] = $v->get('sub_order_number');
                $list[$k]['order_time'] = date('Y-m-d H:i:s', $v->get('order_time'));
                $list[$k]['click_time'] =  date('Y-m-d H:i:s', $v->get('click_time'));
                $list[$k]['orders_price'] = $v->get('total_price');
                $list[$k]['discount_amount'] = $v->get('discount_amount');
                $list[$k]['is_new_custom'] = 0;
                if ($v->get('status') == 0) {
                    $status = 0;
                } elseif ($v->get('status') == 1) {
                    $status = '已支付';
                } elseif ($v->get('status') == 2) {
                    $status = '已发货';
                } elseif ($v->get('status') == 3) {
                    $status = '有效';
                } elseif ($v->get('status') == 4) {
                    $status = -1;
                }
                $list[$k]['order_status'] = $status;
                $list[$k]['referer'] = $v->get('referer');
                $details = array(
                    'goods_id'=>$v->get('goods_id'),
                    'goods_ta'=>$v->get('goods_ta'),
                    'goods_price'=>$v->get('goods_price'),
                    'goods_name'=>$v->get('title'),
                    'goods_cate'=>$v->get('goods_cate'),
                    'goods_cate_name'=>$v->get('goods_cate_name'),
                    'commission'=>$v->get('commission'),
                    'totalPrice'=>$v->get('total_price'),
                );
                $list[$k]['details'][0] = $details;
            }
        }
        return $this->success(array('list' => $list));
    }
}