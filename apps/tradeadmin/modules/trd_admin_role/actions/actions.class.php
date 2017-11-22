<?php

/**
 * trd_admin_role actions.
 *
 * @package    HC
 * @subpackage trd_admin_role
 * @author     HoopChina.com Dev Team
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class trd_admin_roleActions extends autoTrd_admin_roleActions {


    /**
     * 权限设置
     */
    public function  executeAccess(sfWebRequest $request)
    {
        $role_id = $request->getParameter('role_id');
        $role = TrdAdminRoleTable::getInstance()->getNormalRole();
        if (empty($role[$role_id])) $this->showError('该用户组不存在，或被禁用！');
        if (empty($role_id)) $this->showError('请选择需要设置权限的角色！');
        //获取权限节点
        $node_list = TrdAdminMenuTable::getInstance()->returnNodes(true,true);
        //FunBase::myDebug($node_list);
        $useRoleId = TrdAdminAccessTable::getInstance()->getRoleAccess($role[$role_id]['id']);
        //FunBase::myDebug($useRoleId);
        //获取用户组
        $this->setVar('role', $role, true);
        if (!empty($role[$role_id])) {
            $this->setVar('this_role', $role[$role_id], true);
            $this->setVar('this_role_id', $role_id);
        }
        $this->setVar('node_list',  $node_list,true);
        //FunBase::myDebug($node_list);
        $this->setVar('useRoleIds',  FunBase::get_current_array($useRoleId, 'menu_id'),true);
        $this->setVar('useRole',$useRoleId,true);
    }


    /**
     * 添加权限
     */
    public function executeSaveRole(sfWebRequest $request) {
        $roleid = $request->getParameter('roleid');
        if (empty($roleid)) $this->ajaxError("需要授权的角色不存在！");
        $menuidAll =  $request->getParameter('menuid');
        if (is_array($menuidAll) && count($menuidAll) > 0) {
            $menu_info = TrdAdminMenuTable::getInstance()->returnNodes(false);
            $addauthorize = array();
            //检测数据合法性
            $_child = '';
            foreach ($menuidAll as $menuid) {
                if (empty($menuid['id'])) continue;
                if (empty($menu_info[$menuid['id']])) continue;
                if(!empty($menuid['child'])) {
                    $_child = serialize($menuid['child']);
                }
                $info = array(
                    'controller' => $menu_info[$menuid['id']]['controller'],
                    'action_name' => $menu_info[$menuid['id']]['action_name'],
                    'menu_id' => $menu_info[$menuid['id']]['id'],
                    'child_attr' => isset($_child) ? $_child : '',
                );
                $info['role_id'] = $roleid;
                $addauthorize[] = $info;
                $_child = '';
            }
            if (TrdAdminAccessTable::getInstance()->batchAuthorize($addauthorize, $roleid)) {
                $this->ajaxSuccess('授权成功！','',
                    $this->getController()->genUrl("@default?module=trd_admin_role&action=index"));
            } else {
                $error = TrdAdminAccessTable::getInstance()->getError();
                $this->ajaxError($error ? $error : '授权失败！');
            }
        } else {
            $this->ajaxError("没有接收到数据！");
        }
    }


    /**
     * 删除用户组
     */
    public function executeDelete(sfWebRequest $request) {
        $_id = $request->getParameter('id');
        $_count = TrdAdminUserTable::getInstance()->getOnRoleUserCount($_id);
        if ($_count > 0) {
            $this->ajaxError('该组正在使用，无法删除！');
        }
        if(TrdAdminRoleTable::getInstance()->del_role($_id)) {
            $this->ajaxSuccess('删除会员组成功！');
        } else {
            $this->ajaxError('删除会员组失败！');
        }
    }








}
