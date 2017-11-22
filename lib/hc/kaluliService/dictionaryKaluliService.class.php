<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2016/3/15
 * Time: 16:39
 */
class dictionaryKaluliService extends kaluliService {

    /**
     * 根据字典表type获取字典表内容
     */
    public function executeGetDictionary(){

        $kllType = $this->getRequest()->getParameter('type');
        $arrayType = $this->getRequest()->getParameter("arrayType",0); // 1返回kll_code对应str_value,2.返回kll_code对应int_value,3返回str_value对应kll_code，4返回int_value 对应kll_code  0为返回原始数据

        if(empty($kllType)) return $this->error("402","字典表type为空");

        #查询
        $docInfo = KllDictionaryTable::getInstance()->createQuery()->andWhere("kll_type = ?",$kllType)->fetchArray();
        if(empty($docInfo)) return $this->error("402","字典表type不存在");
        if(empty($arrayType)) return $this->success($docInfo);

        //根据arrayType组装拼接数据
        $return =array();
        switch($arrayType){
            case 1:
                foreach($docInfo as $v) {
                    $return[$v['kll_code']] = $v['str_value'];
                }
                break;
            case 2:
                foreach($docInfo as $v) {
                    $return[$v['kll_code']] = $v['int_value'];
                }
                break;
            case 3:
                foreach($docInfo as $v) {
                    $return[$v['str_value']] = $v['kll_code'];
                }
                break;
            case 4:
                foreach($docInfo as $v) {
                    $return[$v['int_value']] = $v['kll_code'];
                }
                break;
        }
        return $this->success($return);
    }
}