<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tradeGetUserInfo
 *
 * @author Administrator
 */
class tradeLog {

    static function debug($key,$attributes = array())
    {
        self::log('debug',$key,$attributes);
    }
    static function info($key,$attributes = array())
    {
        self::log('info',$key,$attributes);
    }
    static function waring($key,$attributes = array())
    {
        self::log('waring',$key,$attributes);
    }
    static function error($key,$attributes = array())
    {
        self::log('error',$key,$attributes);
    }
    static function log($level,$key,$attributes = array())
    {
        $time = time();
        $res = array(
            'evt'=>'shihuo',
            'vtm'=>$time,
            'body'=>array(
                'body.level'=>$level,
                'body.group_key'=>$key,
            ),
        );
        if(!empty($attributes)){
            foreach($attributes as $key=>$val)
            {
                $res['body']['body.'.$key] = $val;
            }
        }
        $res['body']['body.called_at'] = $time;
        if (!is_dir('/data0/log-data/')) mkdir('/data0/log-data/', 0777);
        file_put_contents("/data0/log-data/shihuo.log", json_encode($res)."\n", FILE_APPEND);
    }


    private  $baseUrl = "http://192.168.1.197:9200";
    private  $index   = "shihuolog";
    private  $type    = "index";
    private  $elUrl   = "";

    function __construct()
    {
        $this->elUrl = $this->baseUrl."/".$this->index."/".$this->type;
    }

    function addLog($key,$vals,$remarks = "")
    {
        if(empty($vals))
        {
            return false;
        }

        $vals = array_slice($vals,0,9);
        $postinfo = array();
        $postinfo['key'] 		= $key;
        foreach($vals AS $key => $val)
        {
            $tag = $key +1;
            $postinfo["val".$tag] = $val;
        }
        $postinfo['time'] 		= time();
        if($remarks)
        {
            $postinfo['remarks']  = $remarks;
        }

        $data_string = json_encode($postinfo);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->elUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        $json_response = curl_exec($ch);
        $response = json_decode($json_response,true);

        if($response['created'])
        {
            return true;
        }else{
            return false;
        }
    }

    public function getLog($key="",$vals= array(),$stime=0,$etime=0,$remarks = "",$page = 1,$size=20)
    {
        $from = ($page - 1)*20;

        $postinfo = array();
        $postinfo['from'] = $from;
        $postinfo['size'] = $size;

        if($remarks)
        {
            $postinfo['query']['match']['remarks'] = $remarks;
        }

        $filters = array();

        if($key)
        {
            $node  = array();
            $node['term']['key'] = $key;
            $postinfo['post_filter']['and']['filters'][] = $node;
        }

        if($vals)
        {
            foreach($vals AS $key => $val)
            {
                $tag = $key +1;
                $node  = array();
                $node['term']['val'.$tag] = $val;
                $postinfo['post_filter']['and']['filters'][] = $node;
            }
        }

        if($stime|| $etime)
        {
            $node  = array();
            if($stime)
            {
                $node['range']['time']['gte'] = $stime;
            }

            if($etime)
            {
                $node['range']['time']['lte'] = $etime;
            }
            $postinfo['post_filter']['and']['filters'][] = $node;
        }

        $data_string = json_encode($postinfo);
        $url = $this->elUrl."/_search";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        $json_response = curl_exec($ch);
        $response = json_decode($json_response,true);
        $reinfo =  $response['hits']['hits'];

        return $reinfo;
    }

}

?>
