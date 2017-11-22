<?php

class noticesTradeService extends tradeService
{

    # 我的消息列表
    public function executeList()
    {
        try
        {
            $page = $this->request->getParameter("page",1);
            $type = $this->request->getParameter('type',null);
            $uid = $this->user->getAttribute('uid');
            if(empty($uid))
            {
                throw new Exception('未登陆',501);
            }
            $pageSize = (int)$this->request->getParameter('pageSize');
            if(empty($pageSize)) $pageSize = 30;
            $data['list'] = TrdNoticesTable::getList($page, $pageSize, $uid, $type);
            if( empty($data['list']) )
            {
                throw new Exception('数据为空',502);
            }
            $data['pageSize'] = $pageSize;

            return $this->success($data);
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }
    }

    # 我的消息 总数
    public function executeCount()
    {
        try
        {
            $uid = $this->user->getAttribute('uid');
            if(empty($uid))
            {
                throw new Exception('未登陆',501);
            }
            $data['type1'] = TrdNoticesTable::getCount($uid, 1);
            return $this->success($data);
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }
    }


    # 清理我的消息  type = 1 评论  2 系统  3私信
    public function executeClean()
    {
        try
        {
            $uid = $this->user->getAttribute('uid');
            if(empty($uid))
            {
                throw new Exception('未登陆',501);
            }
            $type = $this->request->getParameter('type',null);
            if(empty($type)) $type = 1;
            TrdNoticesCountTable::updateCount($uid, $type, true);
            return $this->success();
        }
        catch(Exception $ex)
        {
            return $this->error($ex->getCode(), $ex->getMessage());
        }
    }
} 