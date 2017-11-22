<?php

/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2017/5/23
 * Time: 下午8:29
 */
class taskKaluliService extends kaluliService
{

    //获取任务产生的礼物数量
    public function executeGetGiftNumber()
    {
        $userId = $this->getRequest()->getParameter("userId");
        if(empty($userId))  {
            return $this->error(500,"用户id不存在");
        }
        $bind = array();
        $bind['select'] = "count(id),task";
        $bind['where']['user_id'] = "user_id = ".$userId;
        $bind['group'] = "task";
        $data = KllTaskGiftTable::getAll($bind);
        if(!$data) {
            return $this->error(500,"礼物数据不存在");
        }
        //构造优惠券数据
        $taskCount = array();
        foreach ($data as $k => $v) {
            $taskCount[$v['task']] = $v['count'];
        }
        return $this->success($taskCount);
    }

    //根据状态获取用户

    public function executeGetTaskBySection() {
        $section = $this->getRequest()->getParameter("section");
        $userId = $this->getRequest()->getParameter("userId");
        if(empty($userId) || empty($section)) {
            return $this->error(500,"参数错误");
        }
        //获取记录总数
        if(is_array($section)){
            $_count_map['whereIn']['section'] = $section;
            $_count_map['select'] = 'count(Distinct(user_id)) as num';

        } else {
            $_count_map['where']['section'] = " section = " . $section;
            $_count_map['select'] = 'count(id) as num';
        }
        $_count_map['where']['invitor'] = "invitor = ".$userId;
        $count = KllNewUserTaskTable::getAll($_count_map);

        $_page_now = $this->getRequest()->getParameter("page", 1);
        $_page_num = $this->getRequest()->getParameter("pageSize", 10);
        //获取记录总数
        if(is_array($section)){
            $bind['whereIn']['section'] = $section;
            $bind['group'] = "user_id";
        } else {
            $bind['where']['section'] = " section = " . $section;

        }
        $bind['where']['invitor'] = " invitor =".$userId;
        $bind['limit'] = $_page_num;
        $bind['offset'] = (($_page_now - 1) * $_page_num) . ',' . $_page_num;
        $data = KllNewUserTaskTable::getAll($bind);
        if (!$data) {
            return $this->error(500, "没有更多数据了");
        }
        foreach ($data as $k => &$v) {
            $v['content'] = json_decode($v['content'],true);
        }
        //构造返回值*/
        return $this->success(array('data' => $data, 'count' => $count), 200, 'ok');
    }

}