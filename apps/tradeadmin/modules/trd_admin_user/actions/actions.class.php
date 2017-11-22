<?php

require_once dirname(__FILE__).'/../lib/trd_admin_userGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/trd_admin_userGeneratorHelper.class.php';

/**
 * 后台用户action
 */
class trd_admin_userActions extends autoTrd_admin_userActions
{


    /**
     * 重写删除
     */
    public function executeDelete(sfWebRequest $request) {
        $request->checkCSRFProtection();
        $this->dispatcher->notify(new sfEvent($this, 'admin.delete_object', array('object' => $this->getRoute()->getObject())));
        $flag = TrdAdminUserTable::getInstance()->changeStatus($this->getRoute()->getObject()->getId(),3);
        if ($flag) {
            $this->getUser()->setFlash('notice', '已标记删除.');
        }
        $this->redirect('@trd_admin_user');
    }


    /**
     * ajax 通过用户组获取用户
     */
    public function executeAjaxRoleToUser(sfWebRequest $request) {
        $role_id = $request->getParameter('role_id');
        $user_id = $request->getParameter('user_id');
        if(empty($role_id)) $this->ajaxError('未知用户组');
        $userData = TrdAdminUserTable::getInstance()->getRoleToIds($role_id,'id,hupu_uid,username');
        if(!empty($user_id)) {
            foreach ($userData as $k => $v) {
                if($user_id == $v['hupu_uid']) $userData[$k]['current'] = 1;
            }
        }
        $this->ajaxSuccess('ok',$userData);
    }


}
