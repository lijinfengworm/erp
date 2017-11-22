<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2016/3/22
 * Time: 15:56
 */

class loginKaluliService extends kaluliService {

    const SYNCNAME_ERROR_CODE = 201;
    const ACTIONTIME_ERROR_CODE = 202;
    const CODE_ERROR_CODE = 203;

    /**
     * @return array 检查返利网数据参数
     */
    public function executeVerifyFanliLogin() {
        $params = $this->getRequest()->getParameter('params',array());
        if(empty($params)) {
            return $this->error("401","验证参数不存在");
        }
        //    //判断syncname参数
        if(!isset($params['syncname']) || $params['syncname'] == false ) {
            return $this->success(array(),self::SYNCNAME_ERROR_CODE,"syncname校验失败");  //参数校验没有成功直接返回
        }
        //如果当前服务器的时间和action_time参数的时间差距在300秒内，返回true，否则返回false
        if(abs(time()-$params['action_time']) > 300) {
            return $this->success(array(),self::ACTIONTIME_ERROR_CODE,"actionTime校验失败"); //大于300秒参数校验失败直接返回
        }
        //校验加密code参数
        if(isset($params['code']) && isset($params['username']) && isset($params['shop_key'])) {
            $verifyCode = md5($params['username'].$params['shop_key'].$params['action_time']);
            if($params['code'] != $verifyCode) {
                return $this->success(array(),self::CODE_ERROR_CODE,"code校验失败");   //code校验失败直接返回
            }
        }
        //todo 联合登录相关开发


    }


}