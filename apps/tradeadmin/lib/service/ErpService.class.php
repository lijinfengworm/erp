<?php
/**
 * ERP服务类
 * 梁天  2015-05-22
 */
class ErpService  {


    /**
     * 操作句柄
     * @var string
     * @access protected
     */
    protected $handler;

    /**
     * 缓存连接参数
     * @var integer
     * @access protected
     */
    protected $options = array();
    //错误信息
    public $error = null;

    /**
     * 魔术方法，获取配置
     * @param type $name
     * @return type
     */
    public function __get($name) {
        return isset($this->options[$name]) ? $this->options[$name] : NULL;
    }

    /**
     *  魔术方法，设置options参数
     * @param type $name
     * @param type $value
     */
    public function __set($name, $value) {
        $this->options[$name] = $value;
    }


    /**
     * 连接后台用户服务
     * @staticvar \Admin\Service\Cache $systemHandier
     */
    static public function connect($name = '', $options = array()) {
        if (empty($name)) throw new sfException('请声明驱动类型！');
        $class_name =  $name.'Driver';
         if (class_exists($class_name)) {
             $connect = new $class_name($options);
         } else {
             throw new sfException("附件驱动 {$class_name} 不存在！");
         }
        return $connect;
    }
















}