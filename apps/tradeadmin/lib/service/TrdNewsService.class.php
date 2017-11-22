<?php
/**
 * 优惠信息逻辑服务
 */
class TrdNewsService  {


    //审核理由
    public static  $auditMessage = array(
        1=>'重复',
        2=>'价格过高',
        3=>'品牌太小众',
        4=>'品类不合适',
        0=>'其他理由',
    );



    /**
     * 连接后台用户服务
     * @staticvar \Admin\Service\Cache $systemHandier
     */
    static public function getInstance() {
        static $handier = NULL;
        if (empty($handier)) {
            $handier = new self();
        }
        return $handier;
    }

    /**
     * 更新优惠新闻
     */
    public function saveNews($form,$request) {
        $post = $request->getParameter('trd_news');
        $form->bind($post, $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $item = $form->save();
            //ping 百度 服务
            if (empty($post['id'])){
                $prefix_url = sfConfig::get('app_trade');
                $url = $prefix_url['base_url']['frontend'].'/youhui/'.$form->getObject()->getId().'.html';
                $pingbaidu = new tradePingBaidu($url,$post['title']);
                $pingbaidu->pingBaidu();
            }
            $this->setDatetime($item);//每次改变trd_news表时将记录每次更改条目的发布时间和更新时间
            return true;
        }else{
            throw new sfException('有错误，内容保存失败。');
        }
    }





    /**
     * 更新修改时间
     */
    private function setDatetime($item){
        $newsDt = new TrdNewsDatetime();
        $newsDt->setNewsid($item->getId());
        $newsDt->setPublishDate($item->getPublishDate());
        $newsDt->setUpdateDate(date('Y-m-d H:i:s',time()));
        $newsDt->save();
    }

}