<?php

class myUser extends sfBasicSecurityUser
{

    private $_prefix = 'trdadmin_';
    //缓存cache
    private $_cache_key = "shihuo.tradeadmin.user_";

    // 渠道

    //write  session
    public function getTrdChannel()
    {
        return $this->getAttribute($this->_prefix.'channel');
    }

    //write  session
    public function setTrdChannel($channel)
    {
        $this->setAttribute($this->_prefix.'channel', $channel);
    }
    //write  session
    public function getTrdUsername()
    {
        return $this->getAttribute($this->_prefix.'username');
    }

    //write  session
    public function setTrdUsername($username)
    {
        $this->setAttribute($this->_prefix.'username', $username);
    }

    //write  session
    public function getTrdUserHuPuId() {
        return $this->getAttribute($this->_prefix.'hupu_uid');
    }

    //write  session
    public function setTrdUserHuPuId($uid) {
        $this->setAttribute($this->_prefix.'hupu_uid', $uid);
    }

    //write  session
    public function getTrdUserId()
    {
        return $this->getAttribute($this->_prefix.'uid');
    }

    //write  session
    public function setTrdUserId($userId)
    {
        return $this->setAttribute($this->_prefix.'uid', $userId);
    }

    //write  session
    public function setTrdRole($role)
    {
        return $this->setAttribute($this->_prefix.'role', $role);
    }

    //获取用户组  write  session
    public function getTrdRole($field = '')
    {
        $_role = $this->getAttribute($this->_prefix . 'role');
        if(empty($field)) {
            return $_role;
        } else  {
            return $_role[$field];
        }
    }


    //插入所有权限  write redis
    public function setAllAccess($access_list) {
        return $this->setOne($this->_prefix.'access_list', $access_list);
    }

    //获取所有权限  write redis
    public function getAllAccess() {
        return $this->getOne($this->_prefix.'access_list');
    }



    //插入所有栏目   write redis
    public function setTrdMenu($val) {
        return $this->setOne($this->_prefix.'nav_menu', $val);
    }

    //获取所有栏目   write redis
    public function getTrdMenu() {
        return $this->getOne($this->_prefix.'nav_menu');
    }



    //插入所有子栏目   write redis
    public function setTrdChildMenu($key,$val) {
        return $this->setOne($this->_prefix.'child_menu_'.$key, $val);
    }

    //获取所有子栏目   write redis
    public function getTrdChildMenu($key) {
        return $this->getOne($this->_prefix.'child_menu_'.$key);
    }



    //获取权限缓存
    public function getAccessIdCache($menu_cache_gid) {
        return $this->getOne($this->_prefix.$menu_cache_gid);
    }

    //插入权限缓存
    public function setAccessIdCache($menu_cache_gid,$value) {
        return $this->setOne($this->_prefix.$menu_cache_gid, $value);
    }


    /**
     * 获取单独的缓存
     * @param string $key 权限缓存 key
     * @param boolean $is_real  是否实时获取
     * @return int  返回具体的值
     */
    public function getCurrentAuth($key = '',$is_real = false) {

        if(UserService::getInstance()->isSuper()) return NULL;
        $child_attr = null;
        /* 如果是实时获取 */
        if($is_real) {
            $_role = $this->getTrdRole();
            $_access = TrdAdminAccessTable::getOneAccess($_role['role_id'],AuthMenu::getController(),AuthMenu::getAction());
            if(!empty($_access['child_attr'])) {
                $child_attr = $_access['child_attr'];
            }
        } else { //缓存获取
            $_access_list = $this->getAllAccess();
            $child_attr = $_access_list[AuthMenu::getController()][AuthMenu::getAction()]['child'];
        }
        if (empty($child_attr)) return false;
        if (empty($key)) {
            return $child_attr;
        } else {
            return isset($child_attr[$key]) ? $child_attr[$key] : '';
        }
    }


    /**
     * 获取指定控制器的子权限
     * $this->getUser()->getOneAuth('product_attr_audit','recommend','auth')
     */
    public function getOneAuth($controller,$action,$key = '',$is_real = false) {
        if(UserService::getInstance()->isSuper()) return NULL;
        $child_attr = null;
        /* 如果是实时获取 */
        if($is_real) {
            $_role = $this->getTrdRole();
            $_access = TrdAdminAccessTable::getOneAccess($_role['role_id'],$controller,$action);
            if(!empty($_access['child_attr'])) {
                $child_attr = $_access['child_attr'];
            }
        } else { //缓存获取
            $_access_list = $this->getAllAccess();
            $child_attr = $_access_list[$controller][$action]['child'];
        }
        if (empty($child_attr)) return false;
        if (empty($key)) {
            return $child_attr;
        } else {
            return $child_attr[$key];
        }
    }


    /**
     * 插入单条记录
     */
    public function  setOne($key = '',$val = '') {
        $_id = $this->getTrdUserId();
        if(empty($key) || empty($_id)) return false;
        $_redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $_redis->select(1);
        $_redis->hset($this->_cache_key.$_id,$key,json_encode($val));
        return true;
    }


    /**
     * 获取单条记录
     */
    public function getOne($key = '') {
        $_id = $this->getTrdUserId();
        if(empty($key) || empty($_id)) return false;
        $_redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $_redis->select(1);
        $line = $_redis->hget($this->_cache_key.$_id, $key);
        return json_decode($line,true);
    }







    public function logout($user_id) {
        if(empty($user_id)) return false;
        $_redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $_redis->select(1);
        $_redis->del($this->_cache_key.$user_id);
        return true;
    }



}
