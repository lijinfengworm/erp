<?php

class DataReturn{	// class start

	private $type;
	private $xmlroot;
	private $callback;
	private $returnData;

	public function __construct($param=array()){
		$this->type = $this->exists($param,'type')? strtoupper($param['type']) : 'JSON';		// 類型 JSON,XML,CALLBACK,ARRAY
		$this->xmlroot = $this->exists($param,'xmlroot')? $param['xmlroot'] : 'xmlroot';		// xml root dom name
		$this->callback = $this->exists($param,'callback')? $param['callback'] : '';			// JS callback function name

		$format = array();
		$format['retcode'] = $this->exists($param,'format.retcode')? $param['format']['retcode'] : 'retcode';//retcode 對應名稱
		$format['msg'] = $this->exists($param,'format.msg')? $param['format']['msg'] : 'msg';                //msg 對應名稱
		$format['data'] = $this->exists($param,'format.data')? $param['format']['data'] : 'data';            //data 對應名稱

		$result = array();
		$result[$format['retcode']] = $this->exists($param,'retcode')? $param['retcode'] : 0;
		$result[$format['msg']] = $this->exists($param,'msg')? $param['msg'] : '';
		$result[$format['data']] = $this->exists($param,'data')? $param['data'] : '';

		$this->returnData = $result;
	}

	//輸出數據
	public function data_return(){
		ob_clean();
		switch($this->type){
			case 'JSON':
				$this->json_return();
				break;
			case 'XML':
				$this->xml_return();
				break;
			case 'CALLBACK':
				$this->callback_return();
				break;
			case 'ARRAY':
				$this->array_return();
				break;
			default:
				$this->json_return();
		}
		exit();
	}

	//輸出JSON格式數據,如有callback參數則返回JSONP格式
	private function json_return(){
		header('content-type:text/html;charset=utf-8');
		if(empty($this->callback)){
			echo json_encode($this->returnData);
		}else{
			echo $this->callback.'('.json_encode($this->returnData).');';
		}
	}

	//輸出XML格式數據
	private function xml_return(){
		header('content-type:text/xml;charset=utf-8');
		echo $this->xml_encode($this->returnData,$this->xmlroot);
	}

	//輸出JSON格式數據，并調用callback方法
	private function callback_return(){
		header('content-type:text/html;charset=utf-8');
		$this->callback = empty($this->callback)? 'callback' : $this->callback;
		echo "<script type=\"text/javascript\">\r\n";
		echo $this->callback."(".json_encode($this->returnData).");\r\n";
		echo "</script>";
	}

	//輸出數組格式數據
	private function array_return(){
		header('content-type:text/html;charset=utf-8');
		echo '<pre>';
		print_r($this->returnData);
		echo '</pre>';
	}

	//XML編碼
	private function xml_encode($data, $root='xmlroot', $encoding='utf-8') {
		$xml = "<?xml version=\"1.0\" encoding=\"" . $encoding . "\"?>\n";
		$xml.= "<" . $root . ">\n";
		$xml.= $this->data_to_xml($data);
		$xml.= "</" . $root . ">";
		return $xml;
	}

	//數組轉XML格式
	private function data_to_xml($data) {
		if (is_object($data)) {
			$data = get_object_vars($data);
		}
		$xml = '';
		foreach ($data as $key => $val) {
			is_numeric($key) && $key = "item id=\"$key\"";
			$xml.="<$key>";
			$xml.= ( is_array($val) || is_object($val)) ? $this->data_to_xml($val) : $this->cdata($val);
			list($key, ) = explode(' ', $key);
			$xml.="</$key>\n";
		}
		return $xml;
	}

	//判斷數據是否存在
	private function exists($obj,$key=''){
		if($key==''){
			return isset($obj) && !empty($obj);
		}else{
			$keys = explode('.',$key);
			for($i=0,$max=count($keys); $i<$max; $i++){
				if(isset($obj[$keys[$i]])){
					$obj = $obj[$keys[$i]];
				}else{
					return false;
				}
			}
			return isset($obj) && !empty($obj);
		}
	}

	//判斷是否需要加上<![CDATA[]]>標記
	private function cdata($val){
		if(!empty($val) && !preg_match('/^[A-Za-z0-9+$]/',$val)){
			// return iconv("utf-8","gb2312//IGNORE",$val);
			return $val;
		}
		return $val;
	}

}	// class end
?>