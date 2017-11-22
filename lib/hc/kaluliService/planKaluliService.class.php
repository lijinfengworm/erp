<?php
/**
 * Created by PhpStorm.
 * User: libin
 * Date: 2016/6/2
 * Time: 20:52
 */

class planKaluliService extends kaluliService {

    /**
     * 获取首页顶部导航栏
     */
    public function executeGetIndexNav() {
        $data = KllCategoryTable::getIndexNav();
        if($data != false) {
            //组装导航栏数据
            $navs = array();
            //拼接最新导航
            $navs[] = array(
                'id'=>'',
                'name'=>'最新',
                'url'=>'/plan/index'
            );
            foreach($data as $k =>$v) {
              $nav['id'] = $v['id'];
              $nav['name'] = $v['name'];
              $nav['url'] = "/plan/list?category=".$v['id'];
              $navs[] = $nav;
            }
            return $this->success($navs);
        } else {
            return $this->error(500,"获取导航栏失败");
        }
    }

    /**
     * 获取首页楼层数据
     * @return array
     */
    public function executeGetIndexFloor(){
        $navs = $this->getRequest()->getParameter("navs",array());
        if(empty($navs)) return $this->error(500,"类目数据为空");
        //分别查出每个分类的楼层数据
        $_floors = array();
        foreach($navs as $k=>$v) {
            if($v['id']) {
                $transData = KllTrainingprogramTable::getTransByCategory($v['id']);
                $_floor = array();
                if (!empty($transData)) {
                    foreach ($transData as $data) {
                        $_floor[] = array(
                            'id' => $data['id'],
                            'pic' => $data['cover'],
                            'summary' => $data['title'],
                            'time' => $this->mdate($data['public_time']),
                            'url' => '/plan/detail?id='.$data['id']
                        );
                    }

                    $_floors[] = array(
                        'name' => $v['name'],
                        'urlid' => $v['id'],
                        'item_data' => $_floor
                    );
                }
            }
        }
        if(empty($_floors)) return $this->error(500,"楼层数据为空");

        return $this->success($_floors);

    }
    /**
     * 格式化内容详情页
     */
    public function executeTrainDetail(){
        $id = $this->getRequest()->getParameter("id", 0);
        if($id){
            try{
                $collection = KllTrainingprogramTable::getTransCollection($id);
                if(!empty($collection)){
                    $bar = $this->getBar($collection['category']);
                    $collection['tree'] = $bar;
                    $article = $collection['articles'];
                    $collection['title'] = html_entity_decode($collection['title'], ENT_QUOTES, 'UTF-8');
                    $collection['content'] = html_entity_decode($collection['content'], ENT_QUOTES, 'UTF-8');
                    $collection['public_time'] = $this->mdate($collection['public_time']);
                    $art = explode(",", $article);

                    foreach($art as $val){
                        $tmp = KllArticlesTable::getAll([ "where" => [ "id" => 'id = "'.$val.'"', "platform" =>  'platform >=  2' ] ]);

                        if(!empty($tmp) && isset($tmp[0])) {

                            $content = self::_formatContent($tmp[0]['content']);

                            if(!empty($content)) {
                                $collection['article'][$val]['title'] = $tmp[0]['title'];
                                $collection['article'][$val]['content'] = self::_formatContent($tmp[0]['content']);
                            }
                        }
                    }
                    return $this->success($collection);
                }

            }catch(Exception $e){
                throw new Exception();
            }
        }
    }
    /**
     * 获得百科的内容详情页
     */
    public function executeNewsDetail(){
        $aid = $this->getRequest()->getParameter("aid", 0);

        if($aid){
            try{
                $bind["where"]['id'] = "id = ".$aid;
                //$bind["where"]['is_use'] = "is_use = 1";

                $bind["where"]["platform"] = "platform > 1";
                $article = KllArticlesTable::getAll($bind);

                if(!empty($article)){
                    $article[0]['talent_name'] = '';
                    $article[0]['talent_pic'] = 'http://shihuo.hupucdn.com/uploads/kaluli/item/detail/201609/1915/f7920129d616275d9fd4acc910c74fb4.jpg';//默认图片地址
                    if(!empty($article[0]['talent_id'])){
                        $talent = KllTalentTable::getInstance()->findOneById($article[0]['talent_id']);
                        !empty($talent) && ($article[0]['talent_name'] = $talent->getName());

                        if(!empty($talent)){
                            $att_id = $talent->getAttId();
                            if($att_id != 0){
                                $att = KllAttachmentTable::getInstance()->findOneById($att_id);
                                !empty($att) && ($article[0]['talent_pic'] = $att->getOriginal());
                            }
                        }
                    }
                    
                    return $this->success($article[0]);
                }
                return $this->error("0", "文章不存在");

            }catch(Exception $e){
                throw new Exception();
            }
        }
    }
    /**
     * 获取导航栏数据
     */
    public function executeGetBar() {
        $category = $this->getRequest()->getParameter("category");
        if(empty($category)) return $this->error(500,"类目id为空");
        $bar = $this->getBar($category);
        return $this->success($bar);

    }

    public function executeGetPlanList() {
        $category = $this->getRequest()->getParameter("category");
        $page = $this->getRequest()->getParameter("page");
        $pageSize = $this->getRequest()->getParameter("pageSize");
        $data = KllTrainingprogramTable::getTransByCategory($category,$page,$pageSize);
        //构造列表页数据
        $list = array();
        if(empty($data)) return $this->error(500,"数据不存在");
       for($i=0;$i<count($data);$i++) {
            $info['id'] = $data[$i]['id'];
            $info['pic'] =  $data[$i]['cover'];
            $info['summary'] = $data[$i]['title'];
            $info['time'] = $this->mdate( $data[$i]['public_time']);
            $info['url'] = "/plan/detail?id=".$data[$i]['id'];
           if($page==1 && $i==0) {
               $info['isbanner'] = 1;
           }
            $list[] = $info;
           unset($info);
        }
        return $this->success($list);

    }


    //获得导航
    public function getBar($category){
        $newData = [
            'fa' => ['id' => '', 'name' => ''],
            'child' => ['id' => '', 'name' => '']
        ];
        if($category){
            $cate = KllCategoryTable::getInstance()->findOneById($category);
            if(!empty($cate) && $cate->getFa()){
                $fa = KllCategoryTable::getInstance()->findOneById($cate->getFa());
                $newData['fa'] = ['id' => $fa->getId(), 'name' => $fa->getName()];
                $newData['child'] = ['id' => $cate->getId(), 'name' => $cate->getName() ];
            } else {
                $newData['fa'] = ['id' => $cate->getId(), 'name' => $cate->getName() ];
            }

        }
        return $newData;
    }

    public static function _formatContent($content){
        $newData = [];
        preg_match_all("/.*?<block>(.*?)<\/block>.*?/", $content, $block);

        if(!empty($block) && $block[1]){
            foreach($block[1] as $k => $v) {

                preg_match_all("/.*?<icon>(.*?)<\/icon>.*?/", $v, $icon);
                if (!empty($icon) && isset($icon[1][0])) {
                    preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i' ,$icon[1][0], $icons);

                    $newData[$k]['icon'] = $icons[2][0];
                }
                preg_match_all("/.*?<title>(.*?)<\/title>.*?/", $v, $title);
                if (!empty($title) && isset($title[1][0])) {
                    $newData[$k]['title'] = html_entity_decode($title[1][0], ENT_QUOTES, 'UTF-8');
                }
                preg_match_all("/.*?<content>(.*?)<\/content>.*?/", $v, $content);

                if (!empty($content) && isset($content[1][0])) {
                    $newData[$k]['content'] = html_entity_decode($content[1][0], ENT_QUOTES, 'UTF-8');
                }
                preg_match_all("/.*?<mg>(.*?)<\/mg>.*?/", $v, $image);
                if (!empty($image) && isset($image[1][0])) {
                    //return $image[1][0];

                    preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i' ,$image[1][0], $images);

                    $newData[$k]['image'] = $images[2];
                }
            }
        }
        return $newData;
    }

    function mdate($time = NULL) {
        $text = '';
        $time = $time === NULL || $time > time() ? time() : intval($time);
        $t = time() - $time; //时间差 （秒）
        $y = date('Y', $time)-date('Y', time());//是否跨年
        switch($t){
            case $t == 0:
                $text = '刚刚';
                break;
            case $t < 60:
                $text = $t . '秒前'; // 一分钟内
                break;
            case $t < 60 * 60:
                $text = floor($t / 60) . '分钟前'; //一小时内
                break;
            case $t < 60 * 60 * 24:
                $text = floor($t / (60 * 60)) . '小时前'; // 一天内
                break;
            case $t < 60 * 60 * 24 * 3:
                $text = floor($time/(60*60*24)) ==1 ?'昨天 ' . date('H:i', $time) : '前天 ' . date('H:i', $time) ; //昨天和前天
                break;
            case $t < 60 * 60 * 24 * 30:
                $text = date('m月d日 H:i', $time); //一个月内
                break;
            case $t < 60 * 60 * 24 * 365&&$y==0:
                $text = date('m月d日', $time); //一年内
                break;
            default:
                $text = date('Y年m月d日', $time); //一年以前
                break;
        }

        return $text;
    }

}