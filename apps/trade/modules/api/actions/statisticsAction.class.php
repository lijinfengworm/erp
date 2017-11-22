<?php
/**
 * 各种统计
 * @author: 韩晓林
 * @date: 2015/5/6  11:54
 */
Class statisticsAction extends sfAction{
    public function execute($request){
        sfConfig::set('sf_web_debug', false);
        $this->setLayout(false);
        $type = $request->getParameter('type');
        $sTime = $request->getParameter('sTime');
        $eTime = $request->getParameter('eTime');

        if($type == 'comment'){
           $this->_comment($sTime,$eTime);
        }else if($type == 'newsPraise'){
            $this->_newsPraise($sTime,$eTime);
        }else if($type == 'findPraise'){
            $this->_findPraise($sTime,$eTime);
        }else if($type == 'shaiwuPraise'){
            $this->_shaiwuPraise($sTime,$eTime);
        }

        return sfView::NONE;
    }

    #评论统计
    private function _comment($sTime,$eTime){
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition:attachment;filename=comment.xls");

        $data =  <<<EOF
       <table border="1" cellspacing="0">
            <tr>
                <td>日期</td>
                <td>总评论数</td>
                <td>优惠信息</td>
                <td>晒物</td>
            </tr>
EOF;

        $all = $youhuiCountAll = $shaiwuCountAll = 0;
        while(strtotime($sTime) <= strtotime($eTime)){
            $sTimeAdd = date('Y-m-d',(strtotime($sTime)+(3600*24)));
            $count = trdCommentTable::getInstance()->createQuery()->where('created_at >= ?',$sTime)->andWhere('created_at <= ?',$sTimeAdd)->andWhere('status = 1')->count();
            $youhuiCount = trdCommentTable::getInstance()->createQuery()->where('created_at >= ?',$sTime)->andWhere('created_at <= ?',$sTimeAdd)->andWhere('type_id = 1')->andWhere('status = 1')->count();
            $shaiwuCount = $count - $youhuiCount;

            $all   += $count;
            $youhuiCountAll += $youhuiCount;
            $shaiwuCountAll += $shaiwuCount;
            $data .=  <<<EOF
            <tr>
                <td>{$sTime}</td>
                <td>{$count}</td>
                <td>{$youhuiCount}</td>
                <td>{$shaiwuCount}</td>
            </tr>
EOF;
            $sTime = $sTimeAdd;
            usleep(200);
        }

        $data .=  <<<EOF
                <td>总数</td>
                 <td>{$all}</td>
                <td>{$youhuiCountAll}</td>
                <td>{$shaiwuCountAll}</td>
            </tr></table>
EOF;
        echo $data;
    }

    #赞统计 (news)
    private function _newsPraise($sTime,$eTime){
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition:attachment;filename=11.xls");

        $data =  <<<EOF
       <table border="1" cellspacing="0">
             <tr>
                <td colspan="3">优惠信息频道</td>
            </tr>
            <tr>
                <td>日期</td>
                <td>赞数</td>
                <td>反对数</td>
            </tr>
EOF;
        $supportAll = $agaistAll = 0;
        while(strtotime($sTime) <= strtotime($eTime)){
            $sTimeAdd = date('Y-m-d',(strtotime($sTime)+(3600*24)));
            $support = trdUserRecommendTable::getInstance()->createQuery()->where('create_time >= ?',$sTime)->andWhere('create_time <= ?',$sTimeAdd)->andWhere('recommend_content = 1')->count();
            $agaist = trdUserRecommendTable::getInstance()->createQuery()->where('create_time >= ?',$sTime)->andWhere('create_time <= ?',$sTimeAdd)->andWhere('recommend_content = 2')->count();

            $supportAll += $support;
            $agaistAll += $agaist;
            $data .=  <<<EOF
            <tr>
                <td>{$sTime}</td>
                <td>{$support}</td>
                <td>{$agaist}</td>
            </tr>
EOF;
            $sTime = $sTimeAdd;

            usleep(200);
        }

        $data .=  <<<EOF
                <td>总数</td>
                <td>{$supportAll}</td>
                <td>{$agaistAll}</td>
            </tr></table>
EOF;

        echo $data;
    }

    #赞统计 (find)
    private function _findPraise($sTime,$eTime){
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition:attachment;filename=11.xls");

        $data =  <<<EOF
       <table border="1" cellspacing="0">
             <tr>
                <td colspan="2">发现频道</td>
            </tr>
            <tr>
                <td>日期</td>
                <td>赞数</td>
            </tr>
EOF;

        $supportAll = $agaistAll = 0;
        while(strtotime($sTime) <= strtotime($eTime)){
            $sTimeAdd = date('Y-m-d',(strtotime($sTime)+(3600*24)));

            $support = trdFindPraiseTable::getInstance()->createQuery()->where('create_time >= ?',$sTime)->andWhere('create_time <= ?',$sTimeAdd)->andWhere('is_delete = 0')->count();

            $supportAll += $support;
            $data .=  <<<EOF
            <tr>
                <td>{$sTime}</td>
                <td>{$support}</td>
            </tr>
EOF;
            $sTime = $sTimeAdd;
            usleep(200);
        }

        $data .=  <<<EOF
                <td>总数</td>
                <td>{$supportAll}</td>
            </tr></table>
EOF;

        echo $data;
    }

    #赞统计 (shaiwu)
    private function _shaiwuPraise($sTime,$eTime){
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition:attachment;filename=11.xls");

        $data =  <<<EOF
       <table border="1" cellspacing="0">
             <tr>
                <td colspan="3">晒物频道</td>
            </tr>
            <tr>
                <td>日期</td>
                <td>赞数</td>
                <td>反对数</td>
            </tr>
EOF;

        $supportAll = $agaistAll = 0;
        while(strtotime($sTime) <= strtotime($eTime)){
            $sTimeAdd = date('Y-m-d',(strtotime($sTime)+(3600*24)));
            $support = trdShaiwuUserRecommendTable::getInstance()->createQuery()->where('created_at >= ?',$sTime)->andWhere('created_at <= ?',$sTimeAdd)->andWhere('recommend_type = 1')->count();
            $agaist = trdShaiwuUserRecommendTable::getInstance()->createQuery()->where('created_at >= ?',$sTime)->andWhere('created_at <= ?',$sTimeAdd)->andWhere('recommend_type = 2')->count();

            $supportAll += $support;
            $agaistAll += $agaist;

            $data .=  <<<EOF
            <tr>
                <td>{$sTime}</td>
                <td>{$support}</td>
                <td>{$agaist}</td>
            </tr>
EOF;
            $sTime = $sTimeAdd;
            usleep(200);
        }

        $data .=  <<<EOF
                <td>总数</td>
                <td>{$supportAll}</td>
                <td>{$agaistAll}</td>
            </tr></table>
EOF;

        echo $data;
    }

}