<?php

class newsTradeService extends tradeService{

    public function executeDetailGet()
    {
        $ids = $this->getRequest()->getPostParameter('ids',array());
        $v = $this->getRequest()->getParameter('v');
        $count = 0;
        foreach($ids as $id)
        {
            $count += $id;
        }
        if($this->getRequest()->getParameter('id') == 1)
        {
            return $this->error(500,'无');
        }
        return $this->success(array('hello'=>'world','id'=>$this->getRequest()->getParameter('id'),'count'=>$count,'uid'=>$this->getUser()->getAttribute('uid')));
    }


    # 新闻点赞和反对
    public function executeSupportAgaist()
    {
        try
        {
            $uid = $this->user->getAttribute('uid');
            if(empty($uid))
            {
                throw new Exception('未登陆',501);
            }

            $source = (int)$this->request->getParameter('source', 1);
            $id = (int)$this->request->getParameter('id',null);
            $type = $showstatu = (int)$this->request->getParameter('type',null);

            if( empty($source) || empty($id) || empty($type) || !in_array($source,array(1,2)) || !in_array($type,array(1,2)) )
            {
                throw new Exception('参数有误',401);
            }

            $news = trdNewsTable::getInstance()->findOneById($id);//获取该条message
            if( empty($news) )
            {
                throw new Exception('新闻不存在',502);
            }

            $myRedisDb = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
            $message_key = 'n';

            $userRecommend = trdUserRecommendTable::getInstance()->getHasRecommend($uid,'trd_news',$id);//判断是否已存在该操作记录
            if( $source == 1 )
            {
                if( !empty($userRecommend))
                {
                    $supportOrAgaist = $userRecommend->getRecommendContent();//获取操作的类型 支持 or 反对
                    # 操作相同
                    if($supportOrAgaist == $type)
                    {
                        $showstatu = 3;
                        $myRedisDb->SETEX($message_key . '_' . dechex($id) . '_' . dechex($uid), 864000, $showstatu);

                        try
                        {
                            $userRecommend->delete();

                            # 更新新闻支持反对数量
                            if ($type == 1)
                            {
                                $num = $news->getSupport() - 1;
                                $news->setSupport( ($num > 0) ? $num : 0);
                            }
                            else
                            {
                                $num = $news->getAgainst() - 1;
                                $news->setAgainst( ($num > 0) ? $num : 0);
                            }
                            $news->save();//取消累计数
                        }
                        catch(Exception $db)
                        {
                            throw new Exception('数据库错误',500);
                        }
                    }
                    #  相反操作
                    else
                    {
                        try
                        {
                            $userRecommend->setRecommendContent($type);
                            $userRecommend->save();

                            $myRedisDb->SETEX($message_key.'_'.dechex($id).'_'.dechex($uid),864000,$showstatu);

                            if( $type == 1)
                            {
                                $news->setSupport($news->getSupport()+1);
                                $num = $news->getAgainst()-1;
                                $news->setAgainst( ($num>0) ? $num : 0);
                            }else
                            {
                                $news->setAgainst($news->getAgainst()+1);
                                $num = $news->getSupport()-1;
                                $news->setSupport( ($num>0) ? $num : 0);
                            }
                            $news->save();//取消累计数
                        }
                        catch(Exception $db)
                        {
                            throw new Exception('数据库错误',500);
                        }
                    }
                }
                # 未点击过
                else
                {
                    $myRedisDb->SETEX($message_key.'_'.dechex($id).'_'.dechex($uid),864000,$showstatu);
                    try
                    {
                        $userRecommend = new trdUserRecommend();
                        $userRecommend->fromArray(array(
                            'user_id' => $uid,
                            'recommend_content'  => $type,
                            'recommend_id' => $id,
                            'create_time' => date('Y-m-d H:i:s'),
                        ));
                        $userRecommend->setRecommendType('trd_news');
                        $userRecommend->save();//记录用户操作

                        if( $type == 1 )
                        {
                            $news->setSupport($news->getSupport()+1);
                        }else
                        {
                            $news->setAgainst($news->getAgainst()+1);
                        }
                        $news->save();

                    }
                    catch (Exception $e)
                    {
                        throw new Exception('数据库错误',500);
                    }
                }

                $returnData = array(
                    'type'   => $type,
                    'snum'   => $news->getSupport(),
                    'anum'   => $news->getAgainst(),
                );
                # 成功，返回数据
                return $this->success($returnData);
            }
        }
        catch(Exception $e)
        {
            return $this->error($e->getCode(), $e->getMessage());
        }
    }
} 