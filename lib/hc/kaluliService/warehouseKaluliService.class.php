<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 16/10/14
 * Time: 下午2:33
 */

class warehouseKaluliService extends kaluliService {


    /**
     * 获取税费
     * 计算逻辑:1.单仓库征税商品总价<起征点 不征税
     *         2.单仓库征税商品总价>起征点  税金=商品总价 * 税率
     */
    public function executeGetTax() {
        $wareHouseId = $this->getRequest()->getParameter("wareHouseId");
        $total_price  = $this->getRequest()->getParameter("price",0);

        $dutyFee = 0;//税金为0

        if(empty($wareHouseId)) {
            return $this->error(501, '仓库id不存在');
        }

        $tax = KllWarehousesTaxTable::getInstance()->findOneByWarehouseId($wareHouseId);
        //1.税费没有录入 2.税费不启用 3.总价小于起征点 不征税
        if(empty($tax) || $tax->getStatus() == 0 || $total_price < $tax->getTaxStart()) {
            return $this->success(array('dutyFee'=>$dutyFee));
        }

        $dutyFee = number_format(($total_price)/100 * $tax->getTaxRate(),2,'.','');

        return $this->success(['dutyFee' => $dutyFee]);


    }

    /**
     * 获取税费基本信息
     */

    public function executeGetTaxInfo(){
        $wareHouseId = $this->getRequest()->getParameter("wareHouseId");
        if(empty($wareHouseId)) {
            return $this->error(501,"仓库id不存在");
        }
        $taxInfo = KllWarehousesTaxTable::getInstance()->findOneByWarehouseId($wareHouseId);
        if(empty($taxInfo)) {
            return $this->error(502,"税费信息不存在");
        }

        return $this->success($taxInfo);
    }

    /**
     * 计算订单运费
     * @return array
     */
    public function executeGetExpressFee() {
        $provinceId  = $this->getRequest()->getParameter("provinceId");
        $weight     = $this->getRequest()->getParameter("weight");
        $expressType = $this->getRequest()->getParameter("expressType");
        $wareHouseId= $this->getRequest()->getParameter("wareHouseId");
        $isDefault  = $this->getRequest()->getParameter("isDefault",0);//是否拿当前仓库选择快递公司的默认区域

        $fee = 0;
        if(empty($weight)  || empty($wareHouseId)){
            return $this->error(501,"参数错误");
        }
        $expressInfos = KllWarehousesExpressTable::getInstance()->createQuery()->where("warehouse_id = ?",$wareHouseId)->andWhere("status = 1")->fetchArray();

        //$expressInfo = KllWarehousesExpressTable::getInstance()->findOneByWarehouseIdAndExpressId($wareHouseId, $expressType);
        if(empty($expressType)) {
            //获取当前仓库默认快递公司
            $express = KllWarehousesExpressTable::getInstance()->findOneByWarehouseIdAndIsDefault($wareHouseId,1);
            if(empty($express)) {
                return $this->error(502, "仓库默认快递公司不存在,仓库id" . $wareHouseId);
            }
            $expressType = $express->getExpressId();
        }

        if(empty($expressInfos)) {
            return $this->error(502,"仓库快递数据不存在.仓库id".$wareHouseId);
        }

        $feeArray = array();
        foreach($expressInfos as $k => $v) {
            $isCheck = 0; // 没有选中的为0,选中的为1
            $fee    = 0;
            $feeInfo = array();
            if($isDefault) {
                if($v['is_default'] == 1){
                    $isCheck = 1;
                }
                $expressInfoArea = KllWarehousesExpressAreaTable::getInstance()->findOneByWareExpressIdAndIsDefault($v["id"],$isDefault);
            } else {
                if($expressType == $v['express_id']) {
                    $isCheck = 1;
                }
                $expressInfoAreaProvince = KllWarehousesExpressAreaProvinceTable::getInstance()->findOneByWareExpressAndProvince($v['id'],$provinceId);
                if(empty($expressInfoAreaProvince)) {
                    return $this->error(502,"区域数据不存在wareExpress:".$v['id'].",provinceId".$provinceId);
                }
                $expressInfoArea = KllWarehousesExpressAreaTable::getInstance()->findOneById($expressInfoAreaProvince->getWareExpressAreaId());
            }
            if(empty($expressInfoArea)) {
                return $this->error(502,"快递数据不存在wareExpress=".$v['id']);
            }
            $expressFee = $this->getExpressFee($expressInfoArea->getFirstPrice(),$expressInfoArea->getadditionalPrice(),$weight,$v['radio']);
            $fee += $expressFee;
            //计算服务费

            $serviceFee = $this->getServiceFee($v['warehouse_id']);
            $fee += $serviceFee;
            $feeInfo['fee'] = $fee;
            $feeInfo['isCheck'] = $isCheck;
            $feeInfo['express_type']=$v['express_id'];
            $feeInfo['express_name']=KaluliOrder::$EXPRESS_TYPE[$v['express_id']];
            $feeArray[] = $feeInfo;
        }

        return $this->success($feeArray);

    }

    /**
     * 获取当前仓库快递公司的运费
     */
    public function executeGetByWarehouseExpress() {
        $provinceId  = $this->getRequest()->getParameter("provinceId");
        $weight     = $this->getRequest()->getParameter("weight");
        $expressType = $this->getRequest()->getParameter("expressType");
        $wareHouseId= $this->getRequest()->getParameter("wareHouseId");
        $isDefault  = $this->getRequest()->getParameter("isDefault",0);//是否拿当前仓库选择快递公司的默认区域
        $fee = 0;
        if(empty($weight) || empty($expressType) || empty($wareHouseId)){
            return $this->error(501,"参数错误");
        }
        $expressInfo = KllWarehousesExpressTable::getInstance()->findOneByWarehouseIdAndExpressId($wareHouseId, $expressType);

        if(empty($expressInfo)) {
            return $this->error(502,"仓库快递数据不存在仓库id".$wareHouseId."快递公司id".$expressType);
        }

        if($isDefault) {
            $expressInfoArea = KllWarehousesExpressAreaTable::getInstance()->findOneByWareExpressIdAndIsDefault($expressInfo->getId(),$isDefault);
        } else {
            //根据省份获取区域
            $expressInfoAreaProvince = KllWarehousesExpressAreaProvinceTable::getInstance()->findOneByWareExpressAndProvince($expressInfo->getId(),$provinceId);
            if(empty($expressInfoAreaProvince)){
                return $this->error(502,"区域数据不存在wareExpress=".$expressInfo->getId());
            }
            $expressInfoArea = KllWarehousesExpressAreaTable::getInstance()->findOneById($expressInfoAreaProvince->getWareExpressAreaId());
        }
        if(empty($expressInfoArea)) {
            return $this->error(502,"快递数据不存在wareExpressId =".$expressInfo->getId());
        }
        $expressFee = $this->getExpressFee($expressInfoArea->getFirstPrice(),$expressInfoArea->getadditionalPrice(),$weight,$expressInfo->getRadio());
        $fee += $expressFee;
        //计算服务费

        $serviceFee = $this->getServiceFee($expressInfo->getWarehouseId());
        $fee += $serviceFee;
        return $this->success(['expressFee' => $fee]);

    }

    /**
     * 获取快递费
     * @param $firstFee
     * @param $additionFee
     * @param $weight
     * @param $firstWeight
     * @return float
     */
    public function getExpressFee($firstFee,$additionFee,$weight,$firstWeight) {
         $fee = $firstFee;
         if($weight > $firstWeight) { // 重量大于首重,算运费
             $exWeight = ceil($weight - $firstWeight);
             $fee += $exWeight*$additionFee;
         }

        return $fee;
    }

    /**
     * 获取服务费
     * @param $wareHouseId
     */
    public function getServiceFee($wareHouseId){
        $fee = 0;
        $serviceFee = KllWarehousesFeeTable::getInstance()->findOneByWarehouseId($wareHouseId);
        if(empty($serviceFee)) {
            return $fee;
        }
        $fee += $serviceFee->getTotalPrice();
        return $fee;
    }

}