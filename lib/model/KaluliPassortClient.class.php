<?php
/**
 * 卡路里新的用户登录类
 * -----------------------------------------------------------------------------------
 *
 * -----------------------------------------------------------------------------------
 * 使用说明：
 * iflogin() - 用户是否登录
 * userinfo() - 返回数组user，其中包含用户ID及用户名（用户名编码为utf-8）
 * -----------------------------------------------------------------------------------
 * last editor  by libin Rockee
 */
class KaluliPassortClient
{

    public $token;

    public $islogin = FALSE;
    public $userinfo = array();



    public function __construct($token)
    {
        $this->token = $token;

    }

    /**
     * 判断用户是否登录
     */
    public function iflogin()
    {
        !$this->islogin && $this->licit();

        return $this->islogin;
    }

    /**
     * 获取用户基本信息(uid,username)
     */
    public function userinfo()
    {

        if((empty($this->userinfo) && $this->islogin) || $this->iflogin())
        {
            if(strrpos($this->token, "|")){
                $h_ui = explode ( '|', $this->token);
                $this->userinfo['uid'] = 0;
                $this->userinfo['username'] = '';
                if(!empty($h_ui)){
                    $user_union = KllUserUnionTable::getInstance()->findOneByUnionId($h_ui[0]);
                    if(!empty($user_union)){
                        $kaluli_uid = $user_union->getUserId(); 
                        $this->userinfo['uid'] = $kaluli_uid;
                        $userInfo = KllUserTable::getInstance()->findOneByUserId($kaluli_uid);
                        if(!empty($userInfo)){
                            $this->userinfo['username'] = $userInfo->getUserName();
                        }
                    }
                    
                    
                }
            }else{
                $ui = explode ( '-', $this->token);
                $this->userinfo['uid'] = $ui[0];
                $this->userinfo['username'] = $ui[1];
            }
            
        }

        return $this->userinfo;
    }

    /**
     * 验证合法性
     */
    private function licit()
    {
        if(!empty($this->token))
        {
            //假设cookie合法且存在
            if(count(explode("-",$this->token)) >1 || count(explode("|",$this->token)) >1) {
                return $this->islogin = TRUE;
            }

        }
    }



}
