<?php

/**
 * Class searchTradeService
 * version:1.0
 */
class searchTradeService extends tradeService
{

    /*
     *获取搜素数据
     **/
    public function executeSearchGet()
    {
        $channel = $this->getRequest()->getParameter('channel', '');
        $date = $this->getRequest()->getParameter('date', '');
        $keywords = $this->getRequest()->getParameter('keywords', '');
        $page = $this->getRequest()->getParameter('page', 1);
        $pagesize = $this->getRequest()->getParameter('pagesize', 30);
        $version = $this->getRequest()->getParameter('version');

        //数据检查
        if(empty($version))  return $this->error(401,'版本不为空');
        if(!is_array($channel))  return $this->error(401,$channel.'不为数组类型');
        if(!empty($date) && !is_array($date))  return $this->error(401,$channel.'不为数组类型');
        if(!is_numeric($page) || $page < 1)  return $this->error(401,$page.'不为数字类型或小于1');
        if(!is_numeric($pagesize) || $pagesize < 1)  return $this->error(401,$pagesize.'不为数字类型或小于1');

        //拼凑查询语句
        $types = $array = $data = $filters = $must = $not_must = $highlight = $filters_not_must = array();

        //分页
        $data['from'] = ($page - 1) * $pagesize;
        $data['size'] = $pagesize;

        //排序
        $data['sort']['point']['order'] = 'desc';

        //表过滤
        if (in_array('news', $channel) || in_array('haitao', $channel)) $types[] = 'news';
        if (in_array('groupon', $channel)) $types[] = 'groupon';
        if (in_array('find', $channel) || in_array('shoe', $channel)) $types[] = 'find';
        if (in_array('daigou', $channel)) $types[] = 'daigou';


        //表具体类型过滤
        if (in_array('news', $channel) && !in_array('haitao', $channel)) {
            $not_must[] = $this->typeFilter('news', 2);
        } else if (!in_array('news', $channel) && in_array('haitao', $channel)) {
            $not_must[] = $this->typeFilter('news', 1);
        }

        if (in_array('find', $channel) && !in_array('shoe', $channel)) {
            $not_must[] = $this->typeFilter('find', 5);
        } else if (!in_array('find', $channel) && in_array('shoe', $channel)) {
            $not_must[] = $this->typeFilter('find', 4);
        }

        //关键字过滤
        if ($keywords) {
            $must[] = array(
                'match' => array(
                    'title' => array(
                        'query' => $keywords,
                        'operator' => 'and',
                    )
                )
            );
        }

        //时间点过滤
        $from_date = null;
        $to_date = date('Y-m-d H:i:s');
        if (isset($date['from'])) $from_date = $date['from'];
        if (isset($date['to'])) $to_date = $date['to'];

        $date_range = array(
            'range' => array(
                'createTime' => array(
                    'from' => $from_date,
                    'to' => $to_date,
                    'include_lower' => true,
                    'include_upper' => true,
                )
            )
        );
        $filters[] = $date_range;


        //团购时间点过滤[只显示正在团购商品]
        if (in_array('groupon', $types)) {
            $filters_not_must = array(
                'bool' => array(
                    'must_not' => array(
                        'range' => array(
                            'groupon.endTime' => array(
                                'from' => null,
                                'to' => $to_date,
                                'include_lower' => true,
                                'include_upper' => true,
                            )
                        )
                    )
                )
            );

            $filters[] = $filters_not_must;
        }

        if (!empty($must)) $data['query']['bool']['must'] = $must;
        if (!empty($not_must)) $data['query']['bool']['must_not'] = $not_must;
        if (!empty($highlight)) $data['highlight'] = $highlight;

        $data['post_filter']['and']['filters'] = $filters;
        $data['fields'] = array('id');
        $array['_type'] = $types;
        $array['data'] = $data;

        //搜索
        $es = new tradeElasticSearch();
        $searchData = $es->search($array);
        $searchData = json_decode($searchData, true);
        //处理返回数据
        $res = $this->checkData($searchData);
        if ($res['status']) {
            if ($res['num'] > 0) {
                return $this->success(array('num' => $res['num'], 'data' => $res['result']));
            } else {
                return $this->error(402, '无查询结果');
            }
        } else {
            return $this->error(403, '查询失败');
        }
    }

    /*
     *获取热门搜素
     **/
    public function executeHotSearchGet()
    {
        $version = $this->getRequest()->getParameter('version');

        //数据检查
        if (empty($version)) return $this->error(401, '版本不为空');

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $search_index_hotSearch_key = 'trade_search_index_hotSearch';                          //热搜
        $search_index_hotSearch_arr = unserialize($redis->get($search_index_hotSearch_key));

        return $this->success($search_index_hotSearch_arr, 200, '获取热搜词成功');
    }

    /*处理返回数据*/
    private function checkData($searchData)
    {
        $result = $return = $types = $brands = $prices = array();
        if ($searchData['status']) {
            $data_hits = isset($searchData['data']['hits']) ? $searchData['data']['hits'] : array();

            if(!empty($data_hits['hits']) && is_array($data_hits['hits'])){
                foreach($data_hits['hits'] as $k=>$v){
                    $result['id'][] =  $v['fields']['id'][0];
                }
            }

            $return['status'] = true;
            $return['num'] = $data_hits['total'];
            $return['result'] = $result;

        } else {
            $return['status'] = false;
        }

        return $return;
    }

    /*type过滤*/
    private function typeFilter($type, $id)
    {
        //news表
        if ($type == 'news') {
            if ($id == 1) {//过滤国内
                return array(
                    'terms' => array(
                        'news.channelType' => array(1)
                    )
                );
            } else if ($id == 2) {//过滤海淘
                return array(
                    'terms' => array(
                        'news.channelType' => array(2)
                    )
                );
            }
        }

        //find
        if ($type == 'find') {
            if ($id == 4) {//过滤发现好货
                return array(
                    'terms' => array(
                        'find.channelType' => array(4)
                    )
                );
            } else if ($id == 5) {//过滤运动鞋
                return array(
                    'terms' => array(
                        'find.channelType' => array(5)
                    )
                );
            }
        }
    }


}