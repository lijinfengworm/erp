<?php
/**
 * Created by PhpStorm.
 * User: 韩晓林
 * Date: 2014/11/25
 * Time: 13:54
 */

Class productWidget{
    private $content;
    //private $pattern  = '/\<widget_product.*?id=\"(\d*?)\".*?title=\"(.*?)\".*?price=\"(\S*?)\".*?widget_product\>/si';
    private $pattern = '/\<widget_product.*?title=\"(.*?)\".*?widget_product\>/si';
    public function __construct($content){
        $this->content = $content;
    }

    public function pattern(){
        preg_match_all($this->pattern, $this->content , $match);
        if(!empty($match[1])){
            foreach($match[1] as $k=>$v){
                if(strpos($v, '_shihuoflag_') !== false){
                    $res = explode('_shihuoflag_', $v);//新版本以_shihuoflag_分隔
                }else{
                    $res = explode('_f_', $v);
                }
                $compare['<widget_product title="'.$v.'"></widget_product>']
                    =  $this->getText($res);
            }

            $this->content = strtr($this->content, $compare);
            return $this->content;
        }else{
            return $this->content;
        }
    }

    public function getText($res){
        if(!$res || count($res) < 2) return '';
        $title = $res[0];
        if(strpos($res[1] ,'#') !== false){
            $url = strstr($res[1], '#', true);
        }else{
            $url = $res[1];
        }
        if(strpos($res[2] ,'?') !== false){
            $img = strstr($res[2], '?', true);
        }else{
            $img = $res[2];
        }
        $price = $res[3];
        $from  = $res[4];

        return <<<EOF
           <div class="zhida-link clearfix">
                <div class="t1">
                      <a href="{$url}#qk=service"  target="_blank">
                         <img src="{$img}?imageView2/1/w/100/h/100" />
                      </a>
                </div>
                <div class="t2">
                    <h2> <a href="{$url}#qk=service"  target="_blank">{$title}</a></h2>
                    <div class="p2">来自：{$from}</div>
                    <div class="p3">价格：<i>{$price}</i></div>
                </div>
                <div class="t3">
                <a href="{$url}#qk=service" target="_blank">
                直达链接</a>
                </div>
            </div>
EOF;

    }


}