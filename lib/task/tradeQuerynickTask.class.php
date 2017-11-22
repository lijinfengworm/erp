<?php

class tradeQuerynickTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'trade'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'trade'),
      // add your own options here
    ));

    $this->namespace        = 'trade';
    $this->name             = 'Querynick';
    $this->briefDescription = '昵称修改记录';
    $this->detailedDescription = <<<EOF
The [trade:test|INFO] task does things.
Call it with:

  [php symfony trade:test|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
      sfContext::createInstance($this->configuration);

      $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
      while(1){
      $query_id = $redis->get('trade_task_querynick') ? $redis->get('trade_task_querynick3') : 0;
      $url = "http://passport.hupu.com/ucenter/queryNicknUpdateLog.api";
      $key = '9ccc3f21a7987267301b1e5427dff898';
          //传入参数
          $appid = 10002;
          $timeline = time();
          $method = 'POST';
          $id = $query_id + 1;
          $data = array(
              'id' => $id,
              'appid' => $appid,
              'timeline' => $timeline,
              'method' => $method

          );
          $sign = $this->getSign($data, $key);


          $post_data = array(
              'id' => $id,
              'appid' => $appid,
              'timeline' => $timeline,
              'method' => $method,
              'sign' => $sign,


          );


          $res = json_decode($this->querybyid($url, $post_data), true);

          if ($res['msg'] != '') {

              foreach ($res['msg'] as $val) {
                  $list = TrdAccountTable::getInstance()->createQuery()->where("hupu_uid=?", $val['uid'])->fetchOne();
                  if ($list) {
                      $list['hupu_username'] = $val['afterNickname'];
                      $list->save();
                      print_r($val);
                      $this->log($val['id'] . '已经更改');
                  }

                  $redis->set('trade_task_querynick3', $val['id']);
                  $this->log('更新到' . $val['id']);
              }

          } else {
              $this->log('暂无更新');
              sleep(20);
          }
      }
  }


    protected function querybyid($url,$post_data){
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;

    }




    /**
     * 生成签名
     * @param array $params
     * @param string $key
     * @return string
     */
    function getSign(array $params, $key)
    {
        $sign = NULL;
        ksort($params);
        $str = $this->arrayToString($params);
        if(function_exists('hash_hmac'))
        {
            $sign = hash_hmac("sha1", $str, $key);
        }
        else
        {
            $blocksize = 64;
            $hashfunc = 'sha1';
            strlen($key) > $blocksize && $key = pack('H*', $hashfunc($key));
            $key = str_pad($key, $blocksize, chr(0x00));
            $ipad = str_repeat(chr(0x36), $blocksize);
            $opad = str_repeat(chr(0x5c), $blocksize);
            $hmac = pack('H*', $hashfunc(($key ^ $opad) . pack('H*', $hashfunc(($key ^ $ipad) . $str))));
            $sign = $hmac;
        }
        return $sign;
    }

    /**
     * 将一维数组转为字符串 (array('a' => 1, 'b' => 2, 'c => '+1') To: a=1, b=2, c=c+1)
     * @param array 需要转换的数组对象
     * @return string
     */
    function arrayToString(array $datas = array())
    {
        $str = NULL;
        if(!empty($datas))
        {
            $i = 1;
            $dataCount = count($datas);
            foreach($datas as $key => $data)
            {
                $str .= $key . ($data && in_array($data, array('?+1', '?-1', '?+2', '?-2')) ? '=' . $key . strtr($data, array('?' => NULL)) : '=\'' . $data . '\'') . ($i < $dataCount ? ', ' : NULL);
                $i++;
            }
        }
        return $str;
    }








}
