<?php
/**
 *
 *
 * PHP Version 5
 *
 * @category  Class
 * @file      JointPayController.php
 * @package Ehking/Controller
 * @author    chao.ma <chao.ma@ehking.com>

 */

//namespace Ehking/Controller;
//use Ehking/Excation/ExceptionInterface;
//use Ehking/FormProcess/JointPay/OrderBuilder;
//use Ehking/FormProcess/JointPay/QueryBuilder;
//use Ehking/ResponseHandle/JointPay/NotifyHandle;

$dir=dirname(__FILE__);

require_once $dir.'/../Excation/ExceptionInterface.php';
require_once $dir.'/../FormProcess/JointPay/OrderBuilder.php';
require_once $dir.'/../FormProcess/JointPay/QueryBuilder.php';
require_once $dir.'/../../FormProcess/JointPay/TransferBuilder.php';
require_once $dir.'/../../FormProcess/JointPay/PayBuilder.php';
require_once $dir.'/../../FormProcess/JointPay/RechargeBuilder.php';
require_once $dir.'/../../ResponseHandle/JointPay/NotifyHandle.php';

/**
 * 账户联名
 * Class JointPayController
 * @package Ehking/Controller
 */
class JointPayController {

    /**
     * 下单
     */
    public function orderAction()
    {
        $builder = new OrderBuilder();
        try{
            return json_encode($builder->builder($_POST));
        }catch (ExceptionInterface $e)
        {
            return json_encode(unserialize($e->getMessage()));
        }
    }

    /**
     * 查询
     */
    public function queryAction()
    {
        $builder = new QueryBuilder();
        try{
            return json_encode($builder->builder($_POST));
        }catch (ExceptionInterface $e)
        {
            return json_encode(unserialize($e->getMessage()));
        }
    }

    /**
     * 转账
     */
    public function transferAction()
    {
        $builder = new TransferBuilder();
        try{
            return json_encode($builder->builder($_POST));
        }catch (ExceptionInterface $e)
        {
            return json_encode(unserialize($e->getMessage()));
        }
    }
    /**
     * 充值
     */
    public function rechargeAction()
    {
        $builder = new RechargeBuilder();
        try{
            return json_encode($builder->builder($_POST));
        }catch (ExceptionInterface $e)
        {
            return json_encode(unserialize($e->getMessage()));
        }
    }
    /**
     * 支付
     */
    public function payAction()
    {
        $builder = new PayBuilder();
        try{
            return json_encode($builder->builder($_POST));
        }catch (ExceptionInterface $e)
        {
            return json_encode(unserialize($e->getMessage()));
        }
    }

    /**
     * 通知处理
     */
    public function notifyAction()
    {
        $raw_post_data = file_get_contents('php://input', 'r');

        $post = json_decode($raw_post_data, true);

        $handle = new NotifyHandle();
        $handle->handle($post);
    }

    /**
     * 回调处理
     */
    public function callbackAction()
    {
        $this->notifyAction();
    }
} 