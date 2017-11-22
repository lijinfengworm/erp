<?php

/*
 * 适用场景：每次请求都从缓存获取数据，但是该缓存的源是不可靠的（不稳定），如api接口。
 * 如果直接设置缓存的过期时间，在缓存过期直接从数据源获取数据可能失败。
 * 所以应该让该缓存数据永不失效，通过判断缓存的存在时间来判断是否需要更新缓存
 * 
 */

abstract class hpExpireDataCache {
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
            'lifetime' => 86400,
            'prefix' => 'hp_expire_data_cache',
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

    /*
     * 是否过期
     * 是:返回true，否:返回false 
     */

    abstract public function isExpired($key);

    public function getOption($name, $default = null) {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    public function setOption($name, $value) {
        return $this->options[$name] = $value;
    }

//    /*
//     * 源获取数据
//     */
//    abstract public function getSourceData();
//    
//    /*
//     * 源数据是否合法
//     */
//    abstract public function sourceDataIsValid($sourcedata);
//    
//    /*
//     * 更新缓存数据 
//     */
//    abstract public function update();
}
