<?php
/**
 *
 *
 * PHP Version 5
 *
 * @category  Class
 * @file      hgController.php
 * @package Ehking/Controller
 * @author    chao.ma <chao.ma@ehking.com>

 */

//namespace Ehking/Controller;
//use Ehking/Excation/ExceptionInterface;
//use Ehking/FormProcess/hg/OrderBuilder;
//use Ehking/FormProcess/hg/QueryBuilder;
//use Ehking/ResponseHandle/hg/NotifyHandle;

$dir=dirname(__FILE__);
require_once $dir.'/../Excation/ExceptionInterface.php';
require_once $dir . '/../FormProcess/hg/hgOrderBuilder.php';
require_once $dir . '/../FormProcess/hg/hgQueryBuilder.php';
require_once $dir . '/../../FormProcess/hg/hgMemberQueryBuilder.php';
require_once $dir.'/../../FormProcess/hg/hgTransferBuilder.php';
require_once $dir.'/../../FormProcess/hg/hgPayBuilder.php';
require_once $dir.'/../../FormProcess/hg/hgPayQueryBuilder.php';
require_once $dir.'/../../FormProcess/hg/hgRechargeBuilder.php';
require_once $dir.'/../../ResponseHandle/hg/hgNotifyHandle.php';

/**
 * 账户联名
 * Class JointPayController
 * @package Ehking/Controller
 */
class hgController {

    /**
     * 下单
     */
    public function orderAction()
    {
        $builder = new hgOrderBuilder();
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
        $builder = new hgQueryBuilder();
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
        $builder = new hgTransferBuilder();
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
        $builder = new hgRechargeBuilder();
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
        $builder = new hgPayBuilder();
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
    public function payqueryAction()
    {
        $builder = new hgPayQueryBuilder();
        try{
            return json_encode($builder->builder($_POST));
        }catch (ExceptionInterface $e)
        {
            return json_encode(unserialize($e->getMessage()));
        }
    }
    /**
     * 账户查询
     */
    public function memberqueryAction()
    {
        $builder = new hgMemberQueryBuilder();
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

        $handle = new hgNotifyHandle();
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