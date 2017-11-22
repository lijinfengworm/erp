<?php

/**
 * TrdComment
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    HC
 * @subpackage model
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class TrdComment extends BaseTrdComment
{
    public  $_status = array(
        0=>'待审核',
        1=>'正常',
        2=>'删除',
    );

    public  function getType($typeId){
        $type = trdCommentTypeTable::getInstance()->find($typeId);
        if($type){
            return $type->getName();
        }else{
            return '';
        }

    }
}
