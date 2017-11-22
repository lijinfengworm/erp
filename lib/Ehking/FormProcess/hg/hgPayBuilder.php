<?php

$dir=dirname(__FILE__);
require_once $dir.'/../../Configuration/ConfigurationUtils.php';
require_once $dir.'/../../FormProcess/Process.php';

// require_once $dir . '/../../ResponseHandle/hg/hgOrderHandle.php';

class hgPayBuilder extends Process{

    public $merchantId;
    public $requestId;
    public $payerMember;
    public $amount;
    public $currency;
    public $productDetails;


    public function builder($params)
    {
        $this->merchantId = $params['merchantId'];
        $this->requestId = $params['requestId'];
        $this->payerMember = $params['payerMember'];
        $this->amount = $params['amount'];
        $this->currency = $params['currency'];
        //商品信息

        if (!empty($params['product'])){
            $postProduct = $params['product'];
            
            foreach($postProduct as $val){

                $product = new ProductDetail();
                $product->setDescription($val['description'])
                    ->setAmount($val['productAmount'])
                    ->setName($val['productName'])
                    ->setQuantity($val['quantity'])
                    ->setReceiver($val['receiver']);

                array_push($products,$product);
            }

            $this->productDetails = $products;
        }

        if(!empty($params['details'])){
            $arr=str_replace("'",'"',$params['details']);

            $arr=stripslashes($arr);
            $details = json_decode($arr,true);
            $products = array();
            foreach($details as $val){
                $product = new ProductDetail();
                $product->setDescription($val['description'])
                    ->setAmount($val['amount'])
                    ->setName($val['name'])
                    ->setQuantity($val['quantity'])
                    ->setReceiver($val['receiver']);

                array_push($products,$product);
            }
            $this->productDetails = $products;
        }


        $handle = new hgOrderHandle();
        return $this->execute(
            ConfigurationUtils::getInstance()->gethgPayUrl(),
            $this->buildJson(),
            $handle
        );
    }

    /**
     * 生成认证串
     * @return mixed
     */
    function generateHmac()
    {
        $hmacSource = "";
        $hmacSource .= $this->merchantId;
        $hmacSource .= $this->requestId;
        $hmacSource .= $this->payerMember;
        $hmacSource .= $this->amount;
        $hmacSource .= $this->currency;
        if (!empty($this->productDetails)) {
            foreach($this->productDetails as $productDetail){

                $hmacSource .=$productDetail->getName();
                $hmacSource .=$productDetail->getQuantity();
                $hmacSource .=$productDetail->getAmount();
                $hmacSource .=$productDetail->getReceiver();
                $hmacSource .=$productDetail->getDescription();
            }
        }
   //     print_r($hmacSource);

 //       $aa=$this->encipher( $hmacSource, ConfigurationUtils::getInstance()->getHmacKey($this->merchantId));
 //       print_r($aa);
 //       exit;
        return $this->encipher( $hmacSource, ConfigurationUtils::getInstance()->getHmacKey($this->merchantId));
    }

} 