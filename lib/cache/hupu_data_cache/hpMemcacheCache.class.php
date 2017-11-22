<?php
/*
 * set函数多了是否压缩的选项
 */
class hpMemcacheCache extends sfMemcacheCache {

    public function set($key, $data, $lifetime = null, $flag = MEMCACHE_COMPRESSED) {
        $lifetime = null === $lifetime ? $this->getOption('lifetime') : $lifetime;

        // save metadata
        $this->setMetadata($key, $lifetime);

        // save key for removePattern()
        if ($this->getOption('storeCacheInfo', false)) {
            $this->setCacheInfo($key);
        }

        if (false !== $this->memcache->replace($this->getOption('prefix') . $key, $data, false, time() + $lifetime)) {
            return true;
        }

        return $this->memcache->set($this->getOption('prefix') . $key, $data, $flag, time() + $lifetime);
    }

}
