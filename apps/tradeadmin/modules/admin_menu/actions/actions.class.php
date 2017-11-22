<?php

/**
 * Menu模块
 * About 梁天
 */
class admin_menuActions extends AdminBaseAction
{

    /**
     * 首页
     */
    public function executeIndex(sfWebRequest $request){
        $menu_arr = $menu_html_arr = array();
        $menu_like_name = $request->getParameter('name');
        $result = TrdAdminMenuTable::getInstance()->getAllMenu(array('where'=>array('name'=>'name like "%'.$menu_like_name.'%"')));
        if(!empty($result)) {
            $tree = new Tree();
            $tree->icon = array('&nbsp;&nbsp;&nbsp;│ ', '&nbsp;&nbsp;&nbsp;├─ ', '&nbsp;&nbsp;&nbsp;└─ ');
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';
            foreach ($result as $r) {
                $r['str_manage'] = '<a href="' . $this->getController()->genUrl("@default?module=admin_menu&action=add&pid=" . $r['id']) . '">添加子</a>
            | <a href="' . $this->getController()->genUrl("@default?module=admin_menu&action=adds&pid=" . $r['id']) . '">批量加子</a>
            | <a href="' . $this->getController()->genUrl("@default?module=admin_menu&action=edit&id=" . $r['id']) . '">修改</a> |
            <a class="J_ajax_del" href="' . $this->getController()->genUrl("@default?module=admin_menu&action=del&id=" . $r['id']) . '">删除</a> ';
                $r['is_hide'] = $r['is_hide'] ? "不显示" : "显示";
                $menu_html_arr[] = $r;
            }
            $tree->init($menu_html_arr);
            $str = "<tr data-tt-id='\$id'  data-tt-parent-id='\$pid' >
	            <td  align='center'>
	                <input name='listorders[\$id]' type='text' size='3' value='\$listorder' class='input w20 f12 tCenter'>
	            </td>
	            <td align='center'>\$id</td>
	            <td >\$spacer\$name</td>
                <td align='center'>\$is_hide</td>
	            <td align='center'>\$str_manage</td>
	        </tr>";
            $tree->setPrintid('pid');
            $menu_arr = $tree->get_tree(0, $str);
        }
        $this->setVar('categorys',$menu_arr , true);
        $this->setVar('menu_like_name',$menu_like_name);
    }


    /**
     * 添加
     */
    public function executeAdd(sfWebRequest $request){
        $this->form = new TrdAdminMenuForm();
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
           if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect($this->getController()->genUrl("@default?module=admin_menu&action=index"));
               //$this->ajaxSuccess('添加菜单成功！','',$this->getController()->genUrl("@default?module=admin_menu&action=index"));
            } else {
               $_error = '';
               foreach ($this->form->getErrorSchema() as $name => $error) {
                   $_error .= '<p>'.$error->getMessage().'! </p>';
               }
               $this->ajaxError($_error);
            }
        }
        $select_categorys = $select_menu_arr = array();
        $tree = new Tree();
        $parentid = $request->getParameter('pid');
        $result = TrdAdminMenuTable::getInstance()->getAllMenu();
        if(!empty($result)) {
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $select_menu_arr[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($select_menu_arr);
            $tree->setPrintid('pid');
            $select_categorys = $tree->get_tree(0, $str);
        }
        $this->setVar('select_categorys',$select_categorys,true);
    }



    /**
     * 批量添加菜单
     */
    public function executeAdds(sfWebRequest $request) {
        $this->form = new adminMenuListForm();
        if ($request->isMethod('post')) {
            $_menu = $request->getParameter('admin_menu');
            foreach($_menu as $k=>$v) {
                if(!empty($v['name']) && !empty($v['controller'])  &&  !empty($v['action_name']) ) {
                    $menu_form = new TrdAdminMenuForm();
                    $menu_form->bind($v);
                    if ($menu_form->isValid()) {
                        $menu_form->save();
                    }
                }
            }
            $this->ajaxSuccess('添加菜单成功！',
               '',$this->getController()->genUrl("@default?module=admin_menu&action=index"));
        }
        $select_categorys = $select_menu_arr = array();
        $tree = new Tree();
        $parentid = $request->getParameter('pid');
        $result = TrdAdminMenuTable::getInstance()->getAllMenu();
        if(!empty($result)) {
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $select_menu_arr[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($select_menu_arr);
            $tree->setPrintid('pid');
            $select_categorys = $tree->get_tree(0, $str);
        }
        $this->setVar('select_categorys',$select_categorys,true);



    }





    /**
     * 修改
     */
    public function executeEdit(sfWebRequest $request){
        $_id = $request->getParameter('id');
        $menu_data = TrdAdminMenuTable::getInstance()->find($_id);
        if(empty($menu_data)) $this->redirect('admin_menu/index');
        $this->form = new TrdAdminMenuForm($menu_data);
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->ajaxSuccess('修改菜单成功！',
                    '',$this->getController()->genUrl("@default?module=admin_menu&action=index"));
            } else {
                $_error = '';
                foreach ($this->form->getErrorSchema() as $name => $error) {
                    $_error .= '<p>'.$error->getMessage().'! </p>';
                }
                $this->ajaxError($_error);
            }
        }
        $select_categorys = $select_menu_arr = array();
        $tree = new Tree();
        $parentid = $menu_data->getPid();
        $result = TrdAdminMenuTable::getInstance()->getAllMenu();
        if(!empty($result)) {
            foreach ($result as $r) {
                $r['selected'] = $r['id'] == $parentid ? 'selected' : '';
                $select_menu_arr[] = $r;
            }
            $str = "<option value='\$id' \$selected>\$spacer \$name</option>";
            $tree->init($select_menu_arr);
            $tree->setPrintid('pid');
            $select_categorys = $tree->get_tree(0, $str);
        }
        $this->setVar('select_categorys',$select_categorys,true);
        $child_attr = $menu_data->getChildAttr();
        if(!empty($child_attr)) $child_attr = unserialize($child_attr);
        $this->setVar('child_attr',$child_attr,true);
        $this->setVar('_id',$_id);
    }

    /**
     * 删除
     */
    public function executeDel(sfWebRequest $request){
        $_id = $request->getParameter('id');
        $_count = TrdAdminMenuTable::getInstance()->getAllMenuCount(array('where'=>array('pid'=>$_id)));
        if ($_count > 0) {
            $this->ajaxError('该菜单下还有子菜单，无法删除！');
        }
        $_is_use = TrdAdminAccessTable::getInstance()->isMenuUse($_id);
        if($_is_use) {
            $this->ajaxError('清先删除每个用户组对当前菜单的授权！');
        }
        if(TrdAdminMenuTable::getInstance()->del_menu($_id)) {
            $this->ajaxSuccess('删除成功！');
        } else {
            $this->ajaxError('删除失败！');
        }
    }


    /**
     * 排序
     */
    public function executeListorder(sfWebRequest $request) {
        $listorders = $request->getParameter('listorders');
        if (!empty($listorders)) {
            foreach ($listorders as $id => $v) {
                TrdAdminMenuTable::getInstance()->saveListOrder($id,$v);
            }
            $this->ajaxSuccess('排序成功！',null, $this->getController()->genUrl("@default?module=admin_menu&action=index"));
            exit();
        }
    }






}
