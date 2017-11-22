<?php

/**
 * TrdAdminUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdAdminUser extends BaseTrdAdminUser
{


    public function  getRole() {
        $role = $this->_get('role');
        $_arr =  array_filter(explode('-',$role));
        return $_arr;
    }





    public function getUserStatusHtml(){
        switch($this->getUserStatus()){
            case 1:
                return '正常';
                break;
            case 2:
                return '禁用';
                break;
            case 3:
                return "删除";
                break;
            default:
                return '未知';
                break;
        }
    }


    public function preInsert($event)
    {
        parent::preInsert($event);


    }



}
