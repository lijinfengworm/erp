<?php

/**
 * 识货 后台Menu Auth验证
 * @author 梁天
 */
class AuthMenu {

	/**
	 * 验证权限
	 */
	public static function AccessDecision() {
		//判断是否开启权限验证
		if(!sfConfig::get('app_codeConfig_user_auth_on'))  return true;
		//判断当前模块是否是公共模块
		if(!self::checkPublic()) {
			//开始验证模块
			self::checkAuth();
		}
		return true;
	}


	/**
	 * 判断是否登录
	 */
	public static function CheckLogin() {
		//如果登录 直接返回true
		if(UserService::getInstance()->isLogin())  return true;
		$controller = self::getController();
		$action = self::getAction();
			//检测当前ontroller是否要认证
		if (in_array($controller . '/*', sfConfig::get('app_codeConfig_no_login_action'))) return true;
			//检测当前action是否需要认证
		if (in_array($controller . '/' .$action, sfConfig::get('app_codeConfig_no_login_action'))) return true;
		throw new sfException('未登录！');
	}



	/**
	 * 判断模块是否无需验证
	 */
	private static function  checkPublic($controller = '',$action_name = '') {
        $controller = self::getController($controller);
        $action = self::getAction($action_name);
		//检测当前ontroller是否要认证
		if (in_array($controller . '/*', sfConfig::get('app_codeConfig_public_auth_action'))) return true;
         //检测当前action是否需要认证
        if (in_array($controller . '/' .$action, sfConfig::get('app_codeConfig_public_auth_action'))) return true;
        // 盘点是否公共模块
        if (in_array($controller . '/' .$action, sfConfig::get('app_codeConfig_no_login_action'))) return true;
        //TODO  根据 getAccessList 判断模块是否要验证 待完善
		return false;
	}


	public static function getController($_module = '') {
		if(empty($_module)) {
			$_module = sfContext::getInstance()->getRequest()->getParameterHolder()->get('module');
		}
		return strtolower($_module);
	}

	public static function getAction($_action = '') {
		if(empty($_action)) {
			$_action = sfContext::getInstance()->getRequest()->getParameterHolder()->get('action');
		}
		return strtolower($_action);
	}



	/**
	 * 验证模块
	 */
	public static function checkAuth() {
		//如果是管理组  直接通过
		if(UserService::getInstance()->isSuper()) return true;
		//存在认证识别号，则进行进一步的访问决策
		$accessGuid = md5(self::getController() . self::getAction());
		//认证类型 1 登录认证 2 实时认证
		if (sfConfig::get('app_codeConfig_user_auth_type') == 2) {
			//加强验证和即时验证模式 更加安全 后台权限修改可以即时生效  通过数据库进行访问检查
			$accessList = self::getAccessList(sfContext::getInstance()->getUser()->getTrdRole('role_id'));
		} else {
			// 如果是管理员或者当前操作已经认证过，无需再次认证
			if (sfContext::getInstance()->getUser()->getAccessIdCache($accessGuid)) return true;
			//登录验证模式，登录后保存的可访问权限列表
			$accessList = sfContext::getInstance()->getUser()->getAllAccess();

		}
		if (!isset($accessList[strtolower(self::getController())][strtolower(self::getAction())])) {
			//验证登录
			if (UserService::getInstance()->isLogin()) {
				//如果是public_开头的验证通过。
				if (substr(self::getAction(), 0, 7) == 'public_') {
					sfContext::getInstance()->getUser()->setAccessIdCache($accessGuid,1);
					return true;
				}
			}
			sfContext::getInstance()->getUser()->setAccessIdCache($accessGuid,0);
			throw new sfException("您没有访问权限！");
		} else {
			//保存权限
			sfContext::getInstance()->getUser()->setAccessIdCache($accessGuid,1);
		}
		return true;
	}

	

	
	/**
	 * 验证单个规则
	 */
 	static function checkRule($item) {
 		//判断是否是无需验证模块
 		if(!self::checkPublic($item['controller'],$item['action_name'])) {
 			//认证类型 1 登录认证 2 实时认证
			if (sfConfig::get('app_codeConfig_user_auth_type') == 2) {
 				//加强验证和即时验证模式 更加安全 后台权限修改可以即时生效  通过数据库进行访问检查
				$accessList = self::getAccessList(sfContext::getInstance()->getUser()->getTrdRole('role_id'));
 			} else {
 				//登录验证模式，登录后保存的可访问权限列表
				$accessList = sfContext::getInstance()->getUser()->getAllAccess();
 			}
 			if (!isset($accessList[strtolower($item['controller'])][strtolower($item['action_name'])])) {
 				return false;
 			}
 		}
 		return true;
 	}


	/**
	 * 获取当前顶级菜单
	 */
	public static function getTopId() {
		$nav = sfContext::getInstance()->getUser()->getTrdChildMenu(self::getController());
		//$nav = null;
		if(empty($nav)) {
			//查找当前子集菜单的 主目录
			$child_pid_args['where']['pid'] = 'pid <> 0 ';
			$child_pid_args['where']['controller'] = "controller = '" . self::getController() . "'";
			$child_pid_args['where']['action_name'] = "action_name = '" . self::getAction() . "'";
			$child_pid_args['field'] = 'pid';
			$pid = TrdAdminMenuTable::getInstance()->getOneMenu($child_pid_args);
			//判断当前主分支是否有子类
			if (!empty($pid)) {
				// 查找当前主菜单
				$nav = TrdAdminMenuTable::getInstance()->getOne($pid);
				if ($nav['pid'] > 0) {  //如果还有父级 那么继续查找
					$nav = TrdAdminMenuTable::getInstance()->getOne($nav['pid']);
				}
			}
			if(!empty($nav)) {
				sfContext::getInstance()->getUser()->setTrdChildMenu(self::getController(), $nav['id']);
				$nav = $nav['id'];
			}
		}
		return $nav;
	}


	/**
	 * 获取所有菜单
	 */
	public static  function getMenus() {
		$menus = sfContext::getInstance()->getUser()->getTrdMenu();
		//$menus = null;
		if(empty($menus)) {
			$child_pid_args = array();
			// 获取所有主菜单
			$_where = array(
				'is_hide'=>'is_hide = 0',
				'pid' => 'pid = 0'
			);
			$menus['main'] = TrdAdminMenuTable::getInstance()->getAllMenu(array('where'=>$_where,'select'=>'id,name,pid,controller,action_name'));
			//子类
			$menus['child'] =   array(); //设置子节点
			//检测是否允许
			foreach ($menus['main'] as $key => $item) {

				//如果不是超级组 或者没有权限 就删掉
				if (!UserService::getInstance()->isSuper()  && !self::checkRule($item)) {
					unset($menus['main'][$key]);
					continue;//继续循环
				}
			}



			//循环子类
			foreach ($menus['main'] as $key => $item) {
				//生成child树  二级   分组  名
				$groups = TrdAdminMenuTable::getInstance()->getChildGroup($item['id']);
				foreach ($groups as $g) {
				//三级必须等于当前分组
					$three_arg['where']['pid'] = 'pid ='.$item['id'];
					$three_arg['where']['menu_group'] = "menu_group = '".$g."'";
					$three_arg['where']['is_hide'] = "is_hide = 0";
					$three_arg['order'] = "listorder asc";
					$three_arg['select'] = "id,name,pid,controller,action_name";
					$_menuList = TrdAdminMenuTable::getInstance()->getAllMenu($three_arg);
					$menuList = array();
					if(!UserService::getInstance()->isSuper()){
						foreach($_menuList as $menu_key => $menu_val) {
							if(self::checkRule($menu_val)) {
								$menuList[] = $menu_val;
							}
						}
					} else {
						$menuList = $_menuList;
					}
					foreach($menuList as $k=>$v) {
						$menuList[$k]['url'] = sfContext::getInstance()->getController()->genUrl('@default?module='.$v['controller'].'&action='.$v['action_name']);
					}
					if(empty($menuList)) continue;
					$menus['child'][$item['id']][$g] = FunBase::list_to_tree($menuList, 'id', 'pid', 'operater', $item['id']);
				}
			}
			sfContext::getInstance()->getUser()->setTrdMenu($menus);
		}
		return $menus;
	}



	
	
	/**
	 * 检测用户权限的方法,并保存到Session中，登陆成功以后，注册有权限
	 * @param string $role_ids  1,2,3
	 */
	static function saveAccessList($role_ids) {
		// 如果使用普通权限模式，保存当前用户的访问权限列表 对管理员开放所有权限
		if (sfConfig::get('app_codeConfig_user_auth_type') != 2 && !UserService::getInstance()->isSuper()) {
			sfContext::getInstance()->getUser()->setAllAccess(self::getAccessList($role_ids));
		}
		return;
	}
	
	/**
	 * 获取用户所有权限
	 */
	private static function getAccessList($role_ids) {
		$access = TrdAdminAccessTable::getInstance()->getAccessList($role_ids);
		$accessList = array();
		foreach ($access as $acc) {
			$controller = strtolower($acc['controller']);
			$action = strtolower($acc['action_name']);
			$accessList[$controller][$action] = array();
			$accessList[$controller][$action]['child'] = $acc['child_attr'];
		}
		return $accessList;
	}
	
	
	
  
}
