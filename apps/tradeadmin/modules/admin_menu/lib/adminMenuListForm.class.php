<?php
/**
 * 批量添加菜单Form
 * User: liangtian
 * Date: 2015/2/25
 * Time: 18:15
 */
class adminMenuListForm extends BaseForm {


    private $_status = array(
        '1'=>'正常',
        '2'=>'禁用',
    );

    public static  $auto_action_name = array('new','create','delete','edit','filter','batch','update');
    public static  $auto_name = array('添加','添加  动作','删除','修改','搜索','批处理','修改 动作');




    public static $_num = 7;


    public function configure() {


        $_validator = $_widgets = $_schema = array();

        for($i=0;$i<self::$_num;$i++) {
            //循环生成表单
            $_widgets[$i."[name]"] =new sfWidgetFormInput(array(), array('class'=>'w180'));
            $_widgets[$i."[controller]"] =new sfWidgetFormInput(array(), array('class'=>'w240'));
            $_widgets[$i."[action_name]"] =new sfWidgetFormInput(array(), array('class'=>'w240'));
            $_widgets[$i."[menu_group]"] =new sfWidgetFormInput(array(), array('class'=>'w240'));
            $_widgets[$i."[is_public]"] =new sfWidgetFormChoice(array('default'=>0, "choices" =>array(1=>'是',0=>'否')));
            $_widgets[$i."[is_hide]"] =new sfWidgetFormChoice(array( "choices" =>array(1=>'是',0=>'否')));
            $_widgets[$i."[menu_status]"] =new sfWidgetFormChoice(array( "choices" => $this->_status));
            $_widgets[$i."[listorder]"] =new sfWidgetFormInput(array(), array('class'=>'w20'));


            //循环生成label
            $_schema[$i."[name]"] = "菜单名称";
            $_schema[$i."[controller]"] = "控制器";
            $_schema[$i."[action_name]"] = "action名";
            $_schema[$i."[menu_group]"] = "分组";
            $_schema[$i."[is_public]"] = "是否公开";
            $_schema[$i."[is_hide]"] = "是否隐藏";
            $_schema[$i."[menu_status]"] = "状态";
            $_schema[$i."[listorder]"] = "排序";


            /* 循环验证 */

            /*
            $_validator[$i."[name]"] = new sfValidatorString(array('required' => true, 'trim' => true, 'max_length' => 32),
                array('required' => '栏目名称必填',  'max_length' => '不大于32个字'));

            $_validator[$i."[controller]"] = new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 64),
                array('required' => '控制器名称必填',  'max_length' => '不大于64个字'));


            $_validator[$i."[action_name]"] = new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 64),
                array('required' => 'action名称必填',  'max_length' => '不大于64个字'));

            $_validator[$i."[menu_group]"] = new sfValidatorString(array('required' => false), array());
            */


        }



        /* 设置字段 */
        $this->setWidgets($_widgets);
        /* 设置label名字 */
        $this->widgetSchema->setLabels($_schema);
        /* 设置表单前缀 */
        $this->widgetSchema->setNameFormat('admin_menu[%s]');
        /* 设置验证 */
        $this->setValidators($_validator);




    }





}
