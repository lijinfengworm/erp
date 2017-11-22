<?php
class environment{
	/*
	 * 获取生成环境
	 * @param string $appName app's name
	 */
	public static function getEnvironment($appName){
		if (!empty($appName)){
			$environment    = sfConfig::get('sf_environment');
        	$no_script_name = sfConfig::get('sf_no_script_name');
			if ($no_script_name){
				return "";
			}else{
				if($environment!="prod"){
					return "/".$appName."_".$environment.".php";
				}else{
					return "";
				}
			}
		}
	}
}