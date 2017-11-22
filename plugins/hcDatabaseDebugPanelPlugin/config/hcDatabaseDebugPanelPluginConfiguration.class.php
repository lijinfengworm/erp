<?php


class hcDatabaseDebugPanelPluginConfiguration extends sfPluginConfiguration {

    public function initialize() {

        if (sfConfig::get('sf_web_debug')) {
            require_once dirname(__FILE__) . '/../lib/hcDatabaseDebugPanel.class.php';

            $this->dispatcher->connect('debug.web.load_panels', array('hcDatabaseDebugPanel', 'listenToAddPanelEvent'));
        }
    }

}
