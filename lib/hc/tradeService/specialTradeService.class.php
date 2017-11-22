<?php
//专题 期刊服务
class specialTradeService extends tradeService
{
    # 期刊分页数据
    public function executeJournalPageList() {
        try {
            $_count_map =  $_select_map = $_map = array();
            $page = $this->request->getParameter('page',1);
            $isHot = $this->request->getParameter('isHot',0);
            $_page_row = $this->request->getParameter('pageSize',30);
            $isNew = $this->request->getParameter('isNew',0);

            $_select_map['where']['show_journal'] = 'show_journal = 1';
            $_select_map['where']['special_status'] = 'special_status = 2';

            if(!empty($isNew)) $_map['order'] = 'id DESC';

            if(!empty($isHot)) $_map['order'] = 'click_count DESC';

            if(!empty($isNew) && !empty($isHot)) $_map['order'] = 'support DESC';

            $offset = ($page - 1) * $_page_row;
            $_map['offset'] = $offset;
            $_map['limit'] = $_page_row;

            //data
            if(!empty($_select_map['where'])) {
                if(!empty($_map['where'])) {
                    $_map['where'] = array_merge($_select_map['where'],$_map['where']);
                } else {
                    $_map['where'] = $_select_map['where'];
                }
            }
            $data['list'] = TrdSpecialTable::getSpecialAll($_map);

            //page
            $_count_map['select'] = 'count(id) as num';
            $_count_map['limit'] = $_count_map['is_count'] = 1;
            if(!empty($_select_map['where'])) {
                if(!empty($_count_map['where'])) {
                    $_count_map['where'] = array_merge($_select_map['where'],$_count_map['where']);
                } else {
                    $_count_map['where'] = $_select_map['where'];
                }
            }
            $data['count'] = TrdSpecialTable::getSpecialAll($_count_map);

            if( empty($data['list']) ) {
                throw new Exception('数据为空',502);
            }
            $data['pageSize'] = $_page_row;
            return $this->success($data);
        } catch (Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }


    /**
     * 热门期刊
     */
    public function executeHotJournal() {
        $_num = $this->request->getParameter('num',3);
        $isForce = $this->request->getParameter('isForce',false);
        $data = TrdSpecialTable::getHotData($isForce,$_num);
        if(empty($data)) {
            return $this->error('502','数据为空');
        } else {
            return $this->success($data);
        }
    }

    /**
     * 获取一条期刊记录
     */
    public function executeDetail() {
        $_id = $this->request->getParameter('id',0);
        if(empty($_id)) return $this->error('502','数据为空');
        $data = TrdSpecialTable::getInstance()->find($_id);
        if(empty($data) || $data->getSpecialStatus() != 2) return $this->error('502','数据为空');
        return $this->success($data);
    }


    /**
     * 支持 反对
     */
    public function executeSupportAgaist() {
        try  {
            $uid = $this->user->getAttribute('uid');
            if(empty($uid)) throw new Exception('未登陆',501);

            $id = (int)$this->request->getParameter('id',null);
            $type  = (int)$this->request->getParameter('type',null);

            if(  empty($id) || empty($type)  || !in_array($type,array(1,2)) ) {
                throw new Exception('参数有误',401);
            }

            $product = TrdSpecialTable::getInstance()->findOneById($id);//获取该条message
            if( empty($product) ) throw new Exception('物品不存在',502);
            //获取记录
            $userRecommend = TrdJournalUserRecommendTable::getInstance()->getRecommend($uid,$id);//判断是否已存在该操作记录

            if( !empty($userRecommend)) {
                $supportOrAgaist = $userRecommend->getRecommendType();//获取操作的类型 支持 or 反对
                # 操作相同
                if($supportOrAgaist == $type) {
                    try {
                        $userRecommend->delete();

                        # 更新新闻支持反对数量
                        if ($type == 1) {
                            $num = $product->getSupport() - 1;
                            $product->setSupport( ($num > 0) ? $num : 0);
                        } else {
                            $num = $product->getAgaist() - 1;
                            $product->setAgaist( ($num > 0) ? $num : 0);
                        }
                        $product->save();//取消累计数
                    } catch(Exception $db) {
                        throw new Exception('数据库错误',500);
                    }
                }
                #  相反操作
                else {
                    try {
                        $userRecommend->setRecommendType($type);
                        $userRecommend->save();

                        if( $type == 1) {
                            $product->setSupport($product->getSupport()+1);
                            $num = $product->getAgaist()-1;
                            $product->setAgaist( ($num>0) ? $num : 0);
                        } else {
                            $product->setAgaist($product->getAgaist()+1);
                            $num = $product->getSupport()-1;
                            $product->setSupport( ($num>0) ? $num : 0);
                        }
                        $product->save();//取消累计数
                    } catch(Exception $db) {
                        throw new Exception('数据库错误',500);
                    }
                }
            } else {  # 未点击过
                try {
                    $userRecommend = new trdjournalUserRecommend();
                    $userRecommend->fromArray(array(
                        'user_id' => $uid,
                        'recommend_type'  => $type,
                        'product_id' => $id,
                    ));
                    $userRecommend->save();//记录用户操作

                    if( $type == 1 ) {
                        $product->setSupport($product->getSupport()+1);
                    } else {
                        $product->setAgaist($product->getAgaist()+1);
                    }
                    $product->save();

                } catch (Exception $e) {
                    throw new Exception('数据库错误',500);
                }
            }

            $returnData = array(
                'type'   => $type,
                'snum'   => $product->getSupport(),
                'anum'   => $product->getAgaist(),
            );
            # 成功，返回数据
            return $this->success($returnData);
        } catch(Exception $e) {
            return $this->error($e->getCode(), $e->getMessage());
        }


    }




    # 调用晒物信息
    public function executeGetTheme()
    {
        try
        {
            $themeId = (int)$this->request->getParameter('id');
            $preView = $this->request->getParameter('preView');

            if (empty($themeId))
            {
                throw new Exception('缺少参数', 401);
            }



            $theme = TrdSpecialTable::getThemeData($themeId);
            
            if (empty($theme))
            {
                throw new Exception('数据不存在', 505);
            }

            if($theme->is_theme != 1)
            {
                throw new Exception('改专题不是主题类型哦', 506);
            }

            if( empty($theme->theme_id) )
            {
                throw new Exception('主题模板不存在', 512);
            }

            //如果专题状态不显示 并且不是预览状态 也跳到首页
            if($theme->special_status != TrdSpecialTable::$SHOW_FLAG && $preView != md5('shihuotheme2015'))
            {
                throw new Exception('状态有误', 508);
            }

            $appName = sfConfig::get('sf_app');
            if( ( in_array($appName, array('trade','trademobile'))  && $theme->type == 0) || ( in_array($appName, array('kaluli','kalulimobile')) && $theme->type == 1) )
            {
                $data['info'] = json_decode($theme->info, true);
                if( empty($data['info']) )
                {
                    throw new Exception('内容为空', 509);
                }

                $return['templateId'] = $theme->theme_id;

                if(in_array($appName, array('kalulimobile','trademobile')))
                {
                    $return['title'] = $theme->m_title;
                }
                else
                {
                    $return['title'] = $theme->name;
                }

                # 整理数据
                if( in_array($theme->template,array(1,2)) )
                {
                    if(!empty($data['info']['floor_title']))
                    {
                        foreach($data['info']['floor_title'] as $k=>$title)
                        {
                            # 去除空值
                            if(is_array($data['info']['floors'][$k]))foreach($data['info']['floors'][$k] as $k2=>&$v2)
                            {
                                $v2 = array_filter($v2);
                            }
                            unset($v2);
                            $tmp['floors'][$title] = array_filter($data['info']['floors'][$k]);
                        }
                    }
                    $tmp['top_image'] = $data['info']['top_image'];
                } else if($theme->template == 3) { //新主题模板数据
                    if(!empty($data['info']['floor_title']))
                    {
                        foreach($data['info']['floor_title'] as $k=>$title)
                        {
                            # 去除空值
                            if(is_array($data['info']['floors'][$k]))foreach($data['info']['floors'][$k] as $k2=>&$v2)
                            {
                                $v2 = array_filter($v2);
                            }
                            unset($v2);
                            $tmp['floors'][$title] = array_filter($data['info']['floors'][$k]);
                        }
                    }
                    foreach($data['info'] as $key => $val) {
                        if(!in_array($key,['floors','floor_title'])) {
                            $tmp[$key] = $data['info'][$key];
                        }
                    }
                } else if($theme->template ==4) { //文章推荐模板
                    foreach($data['info'] as $key => $val) {
                        if(!in_array($key,['floors','floor_title'])) {
                            $tmp[$key] = $data['info'][$key];
                        }
                    }
                }
                else
                {
                    throw new Exception('主题还没制作好哦 不要急', 510);
                }
                if(isset($tmp['floors']) ){
                    foreach ($tmp["floors"] as $key => $val) {
                        $tmp["titles"][] = $key;
                    }
                }
                $return['info'] = $tmp;
                return $this->success($return);
            }
            else
            {
                throw new Exception('没有权限获取信息', 507);
            }
        } catch (Exception $e)
        {
            return $this->error($e->getCode(), $e->getMessage());
        }

    }






} 