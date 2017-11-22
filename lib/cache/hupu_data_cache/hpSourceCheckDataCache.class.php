<?php

abstract class hpSourceCheckDataCache{
    const SEPARATOR = ':';

    public function __construct($options = array()) {
        $this->initialize($options);
    }

    /**
     * Initializes this sfCache instance.
     *
     * @param array $options An array of options.
     *
     * * lifetime : 默认缓存失效时间 (default value: 86400)
     *
     */
    public function initialize($options = array()) {
        $this->options = array_merge(array(
            'lifetime' => 0,
            'prefix' => 'hp_source_check_data_cache',
                ), $options);

        $this->options['prefix'] .= self::SEPARATOR;
    }

    /*
     * 保存缓存数据
     */

    abstract public function set($key, $value, $lifetime = null);

    /*
     * 获取数据
     * $default: 失败时返回的默认数据
     */

    abstract public function get($key, $default = null);
}
