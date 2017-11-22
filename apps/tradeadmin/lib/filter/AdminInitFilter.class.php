<?php

/*
 * 后台初始化 init
 */

class AdminInitFilter extends sfFilter {

    public function initialize($context, $parameters = array()) {
        parent::initialize($context, $parameters);
    }

    public function execute($filterChain) {
        $filterChain->execute();
    }
    




}
