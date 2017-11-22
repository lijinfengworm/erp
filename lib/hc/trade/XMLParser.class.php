<?php

class XMLParser{

    /**读取xml文件
    * @param  String  $xmlfile
    * @return Array
    */
    public function loadXmlFile($xmlfile){

        // get xmlfile content
        $xmlstring = file_exists($xmlfile)? file_get_contents($xmlfile) : '';

        // parser xml
        list($flag, $data) = $this->parser($xmlstring);

        return $this->response($flag, $data);

    }

    /**读取xmlstring
    * @param  String $xmlstring
    * @return Array
    */
    public function loadXmlString($xmlstring){
        // parser xml
        list($flag, $data) = $this->parser($xmlstring);

        return $this->response($flag, $data);

    }

    /**解释xml内容
    * @param   String $xmlstring
    * @return  Array
    */
    private function parser($xmlstring){
        $flag = false;
        $data = array();

        // check xml format
        if($this->checkXmlFormat($xmlstring)){
            $flag = true;
            
            // xml to object
            $data = simpleXML_load_string($xmlstring, 'SimpleXMLElement', LIBXML_NOCDATA);
            
            // object to array
            $this->objectToArray($data);
        }

        return array($flag, $data);

    }

    /**检查xml格式是否正确
    * @param  String $xmlstring
    * @return boolean
    */
    private function checkXmlFormat($xmlstring){
        
        if($xmlstring==''){
            return false;
        }

        $xml_parser_obj = xml_parser_create();

        if(xml_parse_into_struct($xml_parser_obj, $xmlstring, $vals, $indexs)===1){ // 1:success 0:fail
            return true;
        }else{
            return false;
        }

    }

    /**object 转 Array
    * @param  object $object
    * @return Array
    */
    private function objectToArray(&$object){
        
        $object = (array)$object;
        
        foreach($object as $key => $value){
            if($value === ''){
                $object[$key] = "";
            }else{
                if(is_object($value) || is_array($value)){

                    $this->objectToArray($value);
                    
                    $object[$key] = $value;
                }
            }
        }
    
    }

    /**输出返回
    * @param  boolean $flag true:false
    * @param  Array   $data 转换后的数据
    * @return Array
    */
    private function response($flag=false, $data=array()){
    
        return array($flag, $data);
    
    }

}

?>