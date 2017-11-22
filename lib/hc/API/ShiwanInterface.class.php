<?php
class ShiwanInterface
{
  private $uid = '';
  private $gname = '';
  private $sname = '';
  //http://gamely.hupu.com/shiwanInterface/getUid?uid=17809906&gid=89&sid=1
  private $url = 'http://gamely.hupu.com/shiwanInterface/getGameInfo?';
  
  public function __construct($uid,$gname,$sname)
  {
    $this->uid = $uid;
	$this->gname = urlencode($gname);
	$this->sname = urlencode($sname);
  }
  
  public function setQueryUrl($url)
  {
    $this->url = $url;

    return $this;
  }
    
  /**
   * Check if user exists
   * 
   * @return Boolean
   */ 
  public function exists()
  {
    return (bool)$this->getUserId();
  }
  
  public function getUserId()
  {
    $contents = $this->requestInterface($this->url.'uid='.$this->uid.'&gname='.$this->gname.'&sname='.$this->sname);
      $checkLogin=$contents;#todo sunke json JSON_ERROR_SYNTAX json_last_error()
      for ($i = 0; $i <= 31; ++$i) {
          $checkLogin = str_replace(chr($i), "", $checkLogin);
      }
      $checkLogin = str_replace(chr(127), "", $checkLogin);

      if (0 === strpos(bin2hex($checkLogin), 'efbbbf')) {
          $checkLogin = substr($checkLogin, 3);
      }

//      $checkLogin = json_decode( $checkLogin );

    // file_get_contents fialed
    if (empty($contents))
    {
      return false;
    }
  	$return = json_decode($checkLogin,true);
  	if(!empty($return)){
  		return $return['tid'];   
  	}	
  }

  public function requestInterface($url, $timeout = 5)
	{
	$curlHandle =   curl_init();
	$curlOption =   array(
						CURLOPT_URL             =>  $url,
						CURLOPT_HEADER          =>  0,
						CURLOPT_FOLLOWLOCATION  =>  1,
						CURLOPT_RETURNTRANSFER  =>  1,
						CURLOPT_TIMEOUT         =>  $timeout,
						CURLOPT_AUTOREFERER     =>  1,
						CURLOPT_USERAGENT       =>  'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 1.7; MAXTHON 2.0)',
					);

	curl_setopt_array($curlHandle, $curlOption);
	$return         =   curl_exec($curlHandle);
	$httpStatusCode =   curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
	curl_close($curlHandle);

	return $return;
	}  
}