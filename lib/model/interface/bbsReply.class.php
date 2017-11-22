<?php

/*
 * 论坛回复操作类
 */
class bbsReply {
    
    /*
     * $user: user object
     * $tid: 帖子id
     * $content: 回复内容
     * $quoteid: 引用回复id
     * $fid: 帖子所属版块
     */
    public $errorStatus = 'err';   //错误状态
    public $okStatus = 'ok';       //成功状态
    private $contentMinLen = 5;     //回复最小长度
    private $contentMaxLen = 50000; //回复最大长度
    
    public function __construct(myUser $user, $tid, $content, $quoteid=null, $fid=null, $via=0, $charset='utf-8'){
        $this->user = $user;
        $this->tid = $tid;
        $this->content = trim($content);
        $this->quoteid = $quoteid;
        $this->fid = $fid ? $fid : (int) pw_threadsTable::getInstance()->getFidByTid($this->tid);
        $this->via = $via;
        $this->charset = $charset;
        $this->status = $this->okStatus;
    }
    
    public function reply(array $apiConfig){
        
        $this->clean();
        if($this->status == $this->errorStatus){
            return false;
        }
        return $this->doReply($apiConfig);
    }
    
    private function doReply($apiConfig){
        $param = array('uid' => $this->user->getAttribute('uid'), 'tid' => $this->tid, 'content' => $this->content, 'quotepid' => $this->quoteid, 'boardpw' =>md5($this->getForumPassword()),'via' => $this->via, 'charset' => $this->charset,'ip' => cdn2clientip::getIp());
        $result = SnsInterface::getContents($apiConfig['apiname'], $apiConfig['appid'], $apiConfig['key'], $param, 'POST');
        
        if(!is_array($result)){
            $this->setErrorStatusAndCode($result);
            return false;
        }
        
        $this->replyResult = $result;
        return true;
    }

    private function clean(){
        $this->userClean();       
        if($this->status != $this->errorStatus){
            $this->parametersClean();
        } 
    }
    
    private function parametersClean(){
        if(!$this->tid){
            $this->setErrorStatusAndCode(201);
        }else{
            $len = mb_strlen($this->content, 'UTF-8');
            if($len < $this->contentMinLen || $len >$this->contentMaxLen){
                $this->setErrorStatusAndCode(202);    
            }
        }
    }
    
    private function userClean(){
        if(!$this->user->hasAttribute('uid')){
            $this->setErrorStatusAndCode(101);
        }  
    }
    
    private function getForumPassword(){
        return $this->user->getAttribute('forum'.$this->fid, '');
    }

    private function setErrorStatusAndCode($code){
        $this->setStatusAndCode($this->errorStatus, $code);
    }

    private function setStatusAndCode($status, $code){
        $this->status = $status;
        $this->code = $code;
    }
    
    public function getErrorMessage($code){
        return $code>0 ? $this->getCustomErrorMessage($code) : $this->getApiErrorMessage($code);
    }
    
    private function getCustomErrorMessage($code){
        $error = array(
            101 => '用户未登录',
            201 => '帖子id不合法',
            202 => '回复的长度不合法'
        );
        return isset($error[$code]) ? $error[$code] : '未知错误';
    }
    
    private function getApiErrorMessage($code){
        $error = array(
            '-11' => '接口参数不完整或不合法',
            '-13' => '版块信息不存在',
            '-14' => '游客无浏览权限',
            '-15' => '无浏览权限',
            '-16' => '版块需要密码或密码输入有误',
            '-17' => '无回复权限',
            '-21' => '主题帖不存在或已被删除',
            '-22' => '主题帖被锁定',
            '-23' => '主题帖达到回复上限',
            '-24' => '用户数据不存在',
            '-25' => '用户已被禁言',
            '-26' => '交易区银行总资产<-50的不能发贴',
            '-27' => '新注册会员发帖时间限制',
            '-28' => '限制所有用户每天发帖总数上限',
            '-29' => '你回复的太频繁了，请稍候再试',
            '-30' => '你的回复内容中可能包含广告词句，请修改重试',
            '-31' => '暂时不能发布',
        );
        return isset($error[$code]) ? $error[$code] : '未知错误';
    }
    
    public function getReturnInfo($name){
        return isset($this->replyResult[$name]) ? $this->replyResult[$name] : null;
    }
}

?>
