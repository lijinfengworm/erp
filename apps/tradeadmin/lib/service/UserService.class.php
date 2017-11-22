<?php
/**
 * 后台用户服务
 */
class UserService  {



    /**
     * 连接后台用户服务
     * @staticvar \Admin\Service\Cache $systemHandier
     */
    static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }


    /**
     * 后台登录
     */
    public function login($username, $password) {
        if (empty($username) || empty($password)) {
            throw new sfException("请填写用户名或密码！");
        }
        //验证用户
        $userInfo = $this->getUserInfo($username, $password);
        if (false == $userInfo) {
            throw new sfException("用户名或密码不正确！");
        }
        if ($userInfo['user_status'] == 2) {
            throw new sfException("该用户已被封禁，无法登录！");
        }
        //被删除的用户就不告诉他被删除了
        if ($userInfo['user_status'] != 1) {
            throw new sfException("用户名或密码不正确！");
        }
        //获取角色
        $role_arr = TrdAdminRoleTable::getInstance()->getFormatUserRole($userInfo['role']);
        if (empty($role_arr)) {
            throw new sfException("您所在的组可能被禁用，您无法登录！");
        }
        $flag = false;
        foreach($role_arr['role_item'] as $k=>$v) {
            if($v['role_status'] == 1) $flag = true;
        }
        if($flag == false) {
            throw new sfException("您所在的组被禁用，您无法登录！");
        }

        //注册登录状态
        $this->registerLogin($userInfo,$role_arr);
        return true;
    }


    /**
     * 判断当前用户是不是超级组
     */
    public function isSuper() {
        if($this->isLogin()) {
            if(sfContext::getInstance()->getUser()->getTrdRole('is_super') == 1) return true;
        }
        return false;
    }


    /**
     * 注册用户登录状态
     * @param array $userInfo 用户信息
     */
    private function registerLogin(array $userInfo,array $role_arr) {

        //写入本地化存储方案
        sfContext::getInstance()->getUser()->setTrdUserId($userInfo['id']);
        sfContext::getInstance()->getUser()->setTrdUserHuPuId($userInfo['hupu_uid']);
        sfContext::getInstance()->getUser()->setTrdUsername($userInfo['username']);
        sfContext::getInstance()->getUser()->setTrdRole($role_arr);
        sfContext::getInstance()->getUser()->setTrdChannel($userInfo['channel']);
        sfContext::getInstance()->getUser()->logout(sfContext::getInstance()->getUser()->getTrdUserId());

        //更新状态
        TrdAdminUserTable::getInstance()->loginStatus($userInfo['id']);
        //注册权限
        AuthMenu::saveAccessList($role_arr['role_id']);
        return true;
    }


    /**
     * 获取用户信息
     * @param type $identifier 用户名或者用户ID
     * @return boolean|array
     */
    private function getUserInfo($username, $password = NULL) {
        if (empty($username) || empty($password)) return false;
        return TrdAdminUserTable::getInstance()->getUserInfo($username,$password);
    }

    /**
     * 判断是否登录
     */
    public function isLogin() {
       return  sfContext::getInstance()->getUser()->getTrdUserId();
    }



    /**
     * 用户退出
     */
    public function logout() {
        sfContext::getInstance()->getUser()->logout(sfContext::getInstance()->getUser()->getTrdUserId());
        sfContext::getInstance()->getUser()->getAttributeHolder()->clear();
        return true;
    }










}