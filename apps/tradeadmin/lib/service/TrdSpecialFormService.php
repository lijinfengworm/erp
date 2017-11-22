<?php
/**
 * 网站前台普通配置form类
 * @author 韩晓林
 * @date  2015/12/23
 */
Class TrdSpecialFormService{
    public static function getInstance(){
        return new TrdSpecialFormService();
    }

    public function setting($settings, $obj){
        $obj->act = $act = sfContext::getInstance()->getRequest()->getParameter('act');
        $action = array_keys($settings);

        if(null === $act){
            $this->lists($settings, $obj);
        }elseif(in_array($act, $action)){
            $this->detail($settings, $obj, $act);
        }else{
            $obj->redirect404();
        }

        $obj->setting  = $settings;
    }

    /*
    *列表
    * */
    public function lists($settings, $obj){
        $obj->setTemplate('settingList', 'trd_api');
    }

    /*
    *详情
    * */
    public function detail($settings, $obj, $act){
        $redis  = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $redis->select(6);

        $params    = array_keys($settings[$act]['params']);
        $redis_key = $settings[$act]['key'];
        $data      = unserialize($redis->get($redis_key));
        $data_form = $this->arrayTwoToOne($data, $params);

        //条件
        $condition =  array_merge(
            array(
                'num'      => $settings[$act]['limit']['num'] ,
                'must_num' => isset($settings[$act]['limit']['must_num']) ? $settings[$act]['limit']['must_num'] : $settings[$act]['limit']['num'],
                'type'     => $act
            ),
            $settings[$act]['params']
        );

        //form
        $form  =  new specialForm(
            $data_form,
            $condition
        );

        $status = false;
        //post 提交
        if(sfContext::getInstance()->getRequest()->isMethod('post')){
            $post_data = sfContext::getInstance()->getRequest()->getParameter($act);

            $form->bind($this->arrayTwoToOne(
                    $post_data, $params
                )
            );

            if ($form->isValid()) {
                 array_filter($post_data , array(__CLASS__,'delEmpty'));
                 $data = array_filter($post_data);;
                $redis->set($redis_key, serialize($data));
                $status = 'success';
            }else{
                $status = 'error';
            }

        }

        $obj->form   = $form;
        $obj->status = $status;
        $obj->setTemplate('settingDetail', 'trd_api');
    }


    //转换数组
    private function arrayTwoToOne($oldArr, $params, $others = array()) {
        $newArr = array();
        if(empty($oldArr)){
            return array();
        }

        //其他数据
        if($others){
            foreach($others as $othersKey => $othersVal) {
                $newArr[$othersVal] = $oldArr[$othersVal];
                unset($oldArr[$othersVal]);
            }
        }

        $j = 0;
        foreach($oldArr as $oldArrKey=>$oldArrVal) {
            $length = count($params);
            for($i = 0; $i < $length; $i++) {
                if(isset($oldArrVal[$params[$i]])) {
                    $newArr[$j . '[' . $params[$i] . ']'] = $oldArrVal[$params[$i]];
                }
            }
            $j++;
        }
        return $newArr;
    }

    //去除空值
    private function delEmpty(&$val){
        foreach($val as $k=>$v){
            if(!$v) unset($val[$k]);
        }
    }
}