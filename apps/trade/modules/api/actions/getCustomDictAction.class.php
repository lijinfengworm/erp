<?php
/*
 *es 远程扩展词库更新
 **/
Class getCustomDictAction extends sfActions{
    private $_customDictUrl;
    private $_status;
    private $_request;
    private $_customDictArr;
    public function preExecute(){
        parent::preExecute();
        $this->_customDictUrl = $_SERVER['DOCUMENT_ROOT'].'/uploads/trade/es/getCustomDict';
    }
    public function executeGetCustomDict(sfWebRequest $request) {
        sfConfig::set('sf_web_debug', false);
        $this->_request = $request;
        $act = $this->_request->getParameter('act');

        $customDict =  file_get_contents( $this->_customDictUrl );
        $this->_customDictArr = explode( PHP_EOL, $customDict );

        $action = '_'.$act;
        if( method_exists( $this, $action ) ){
            $this->$action();
        }

        if($this->_status){
            $file = fopen( $this->_customDictUrl , "w+") or die("Unable to open file!");
            fwrite($file, implode( PHP_EOL, $this->_customDictArr ));
            fclose($file);
        }

        return  $this->renderText( json_encode(array( 'status'=>$this->_status )) );
    }

    /*
    增加
    **/
    private function _add(){
        $customData = $this->_request->getParameter('data');
        if($customData && !in_array( $customData, $this->_customDictArr)){
            $this->_customDictArr[] = $customData;
            $this->_status = true;
        }else{
            $this->_status = false;
        }
    }

    /*
    删除
    **/
    private function _del(){
        $customData = $this->_request->getParameter('data');
        if($customData && $customDataKey = array_search( $customData, $this->_customDictArr )){
            unset($this->_customDictArr[ $customDataKey ]);
            $this->_status = true;
        }else{
            $this->_status = false;
        }
    }
}