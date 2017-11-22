<?php

/**
 * M站   WebResponse
 * Class tradeMobileWebResponse
 */
class  tradeWebResponse extends sfWebResponse
{
    public function initialize(sfEventDispatcher $dispatcher, $options = array()){
        parent::initialize($dispatcher, $options);
        $this->addCacheControlHttpHeader('no-store,no-cache');
    }
}
