<?php

/**
 * 抽奖核心类
 */
class lotteryRand {

    private $lotteryKey = 'v';  //中奖数据 中奖率 key名称

    private $lotteryRate = array();  //中奖率

    private $lotteryData = array();  //中奖所有数据


    private $maxRate = 10000; //最大中奖率

    public function __construct($data,$lotteryKey = 'v') {
        if(empty($data)) return false;
        $this->lotteryData = $data;
        $this->lotteryKey = $lotteryKey;

    }




    /**
     *  设置 中奖 最大几率
     */
    public function setMaxRate($rate = 10000) {
        $this->maxRate = $rate;
    }



    /*
     * 设置中奖概率，
     * @param Array,中奖概率，以数组形式传入
     */

    public function setRate() {
        foreach($this->lotteryData as $k=>$v) {
            if($v[$this->lotteryKey] > 0) {
                $this->lotteryRate[] = $v[$this->lotteryKey];
            }
        }
        if (array_sum($this->lotteryRate) > $this->maxRate)//检测概率设置是否有问题
            return false;
        if (array_sum($this->lotteryRate) < $this->maxRate)  //如果概率不满100  那么还要定义 未得奖的概率
            //定义未中奖情况的概率，用户给的概率只和为100时，则忽略0
            $this->lotteryRate[] = $this->maxRate - array_sum($this->lotteryRate);

    }

    /*
     * 随机生成一个1-10000的整数种子，提交给中奖判断函数
     * @return int,按传入的概率排序，返回中奖的项数
     */

    public function runOnce() {
        $this->setRate();
        return $this->judge(mt_rand(1, $this->maxRate));
    }

    /*
     * 按所设置的概率，判断一个传入的随机值是否中奖
     * @param int,$seed 10000以内的随机数
     * @return int,$i 按传入的概率排序，返回中奖的项数
     */

    protected function judge($seed) {

        //初始随机因子
        $_rand = mt_rand(6789,23456);
        foreach ($this->lotteryRate as $key => $value) {
            $tmpArr[$key + 1] = $value;
        }
        //组装结果段
        $tmpArr[0] = 0;
        foreach ($tmpArr as $key => $value) {
            if($key == 1) {
                $tmpArr[$key] = (int)$tmpArr[$key] + (int)$_rand;
            }
            if ($key > 0) {
                $tmpArr[$key] += $tmpArr[$key - 1];
            }
        }

        $seed = $_rand + $seed;
        for ($i = 1; $i < count($tmpArr); $i++) {
            if ($tmpArr[$i - 1] < $seed && $seed <= $tmpArr[$i]) {
                 if(isset($this->lotteryData[$i-1]) && !empty($this->lotteryData[$i-1])) {
                     return $this->lotteryData[$i-1];
                 } else {
                     return 0;
                 }
            }
        }


    }

}
?>