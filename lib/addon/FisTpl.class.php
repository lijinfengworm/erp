<?php
/**
 * Fis模板类 继承 smarty
 * 梁天 2015-07-16
 */
class FisTpl extends Smarty {

    //用于存放实例化的对象
    static private $_instance;

    //公共静态方法获取实例化的对象
    static public function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }



    //由于使用了单例模式 所以不要new 此类 由于当前类继承了smarty 所以构造方法无法私有
    public  function __construct() {
        parent::__construct();
        $this->_checkDir();
        $this->setTemplateDir(sfConfig::get('sf_root_dir') . '/../kaluli_web/kaluli_mobile/fis_view/template/');
        $this->setCompileDir(sfConfig::get('sf_cache_dir') . '/fis_view/'.sfContext::getInstance()->getConfiguration()->getApplication().'/template_c/');
        $this->setConfigDir(sfConfig::get('sf_root_dir') . '/../kaluli_web/kaluli_mobile/fis_view/config/');
        $this->setCacheDir(sfConfig::get('sf_cache_dir') . '/fis_view/'.sfContext::getInstance()->getConfiguration()->getApplication().'/cache/');
        $this->addPluginsDir(array(sfConfig::get('sf_root_dir') . '/../kaluli_web/kaluli_mobile/fis_view/plugin'));
        $this->left_delimiter = '{%';
        $this->right_delimiter = '%}';
    }

    private function _checkDir() {
        //check cache
        $_cache_dir = sfConfig::get('sf_cache_dir') . '/fis_view/'.sfContext::getInstance()->getConfiguration()->getApplication().'/cache/';
        if(!file_exists($_cache_dir)) {
            if(!mkdir($_cache_dir, 0777, true)) {
                FunBase::myDebug('create dir:'.$_cache_dir.' error !');
            }
        }
        $_template_c_dir = sfConfig::get('sf_cache_dir') . '/fis_view/'.sfContext::getInstance()->getConfiguration()->getApplication().'/template_c/';
        if(!file_exists($_template_c_dir)) {
            if(!mkdir($_template_c_dir, 0777, true)) {
                FunBase::myDebug('create dir:'.$_template_c_dir.' error !');
            }
        }
    }






}