<?php
/*
 * elasticsearch http接口
 * @author  韩晓林
 * @date 2015/2/9
 * */
CLass elasticSearchAction extends sfAction
{
    public function preExecute(){
        function P($arr){
            echo "<pre>";
            print_r($arr);
            echo "</pre>";

        }
    }
    private $_index = 'shihuo';
    public function execute($request) {
        sfConfig::set('sf_web_debug', false);

        $_index = $request->getParameter('_index',$this->_index);
        $_type = $request->getParameter('_type','');
        $_action = $request->getParameter('_action','');
        $_id = $request->getParameter('_id','');
        $_data = $request->getParameter('_data','');
        $_environment = $request->getParameter('_env');

        if (sfConfig::get('sf_environment') == 'prod') {
            if(!$_environment){echo '必须输入环境编号';exit;}
        }


        if($_action == 'update'){//更新索引
            $this->update($_type,$_id);
            return sfView::NONE;
        }elseif($_action == 'create'){
            $this->create($_type,$_id);
            return sfView::NONE;
        }elseif($_action == 'delete'){
            $this->delete($_type,$_id);
            return sfView::NONE;
        }elseif($_action == 'reindex'){
            $this->reindex($_type,$_id);
            return sfView::NONE;
        }elseif($_action == 'index'){
            $this->index($_type);
            return sfView::NONE;
        }elseif($_action == 'mapping'){
            $this->mapping($_type);
            return sfView::NONE;
        }elseif($_action == 'deleteMapping'){
            $this->deleteMapping($_type);
            return sfView::NONE;
        }elseif($_action == 'publish'){
            $this->publish($_data);
            return sfView::NONE;
        }elseif($_action == 'analyze'){
            $this->analyze($_data);
            return sfView::NONE;
        }elseif($_action == 'extend'){
            $this->extend($_data);
            return sfView::NONE;
        }elseif($_action == 'p'){
            $this->p($_data);
            return sfView::NONE;
        }

}


    private function update($_type,$_id){
        $typeSearch = $_type.'Search';
        $es =new $typeSearch();

        $shoe_put = $es->update($_id);
        echo $shoe_put;
    }

    private function create($_type,$_id){
        $typeSearch = $_type.'Search';
        $es =new $typeSearch();

        $shoe_put = $es->create($_id);
        echo $shoe_put;
    }

    private function delete($_type,$_id){
        $typeSearch = $_type.'Search';
        $es =new $typeSearch();

        $shoe_put = $es->delete($_id);
        echo $shoe_put;
    }

    private function reindex($_type,$_id){
        set_time_limit(0);

        $typeSearch = $_type.'Search';
        $es =new $typeSearch();

        $shoe_put = $es->reindex($_id);
        P($shoe_put);
    }

    private function index($_type){
        $typeSearch = $_type.'Search';
        $es =new $typeSearch();

        $shoe_put = $es->index();
        echo $shoe_put;
    }

    private function mapping($_type){
        $typeSearch = $_type.'Search';
        $es =new $typeSearch();

        $shoe_put = $es->mapping();
        echo $shoe_put;
    }

    private function deleteMapping($_type){
        $typeSearch = $_type.'Search';
        $es =new $typeSearch();

        $shoe_put = $es->deleteMapping();
        echo $shoe_put;
    }

    private function publish($_data){
        $es =new tradeElasticSearch();
        $shoe_put = $es->publish($_data);
        echo $shoe_put;
    }


    private function analyze($_data){
        $es =new tradeElasticSearch();
        $shoe_put = $es->analyze($_data);

        echo $shoe_put;
    }

    private function p($_data){
        $es =new tradeElasticSearch();
        $shoe_put = $es->analyze($_data);
        $querySql = array(
            'query'=>array(
                 'term'=>array(
                     'attrs.type'=>$_data
                 )
            ),
            'from'=>0,
            'size'=>100
        );

        $array['_type'] = 'find';
        $array['data'] = $querySql;

        $indexData = $es->search($array);
        $indexData = json_decode($indexData,true);

        if(isset($indexData['data']['hits']['hits'])){
            $data = $indexData['data']['hits']['hits'];
            foreach($data as $val){
               $id = $val['_id'];

               echo self::update('find',$id);
            }
        }
    }

    private function extend(){
        $ext_str = 'Arcteryx
始祖鸟
JACK WOLFSKIN
狼爪
Klättermusen
攀山鼠
Mountain
Hardwear
山浩
Columbia
哥伦比亚
The North Face
乐斯菲斯（北脸/北面）
Marmot
土拨鼠
Patagonia
巴塔
哥尼亚
Toread
探路者
Ozark Gear
奥索卡
Kailas
凯乐石
NORTHLAND
诺诗兰
BLACK YAK
布来亚克
Acome
阿珂姆
Pureland
普尔兰德
Kolumb
哥仑步
THE FIRST OUTDOOR
美国第一户外
Makino
犸凯奴
Skywards
塞沃斯
Civitis
希维途
Salomon
萨洛蒙
Montrail
KingCamp
康尔健野
Udjat
无界
CLORTS
洛弛
Keen
Haglofs
火柴棍
Vaude
沃德 
Osprey
小鹰
Deuter
多特
gregory
格里高利
UVEX
优唯斯
NEO
PUMA
彪马
ASICS
亚瑟士
Mizuno
美津浓
Li Ning
李宁
ANTA
安踏
Kappa
卡帕
FILA
斐乐
ERKE
鸿星尔克
XTEP
特步
deerway
德尔惠
Onitsuka Tiger
鬼塚虎
Vans
万斯
Royal Elastic
皇家橡皮筋
Kawasaki
川崎
361度
中国乔丹
Camel Active
骆驼动感
Converse
匡威
Dickies
帝客
SKECHERS
斯凯奇
Lecoqsportif
乐卡克
Reebok
锐步
UMBRO
茵宝
Wilson
威尔胜
Odlo
奥递乐
Speedo
速比涛
arena
阿瑞娜
MLB
美职棒
AQ
TABATA
塔巴塔
Yonex
尤尼克斯
Head 海德
Victor
威克多
Xtep
特步
Joerex
祖迪斯
skins
思金斯
Spalding
斯伯丁
Ochirly
Five Plus
Zuczug
JNBY
Lily
NINE WEST
MANGO
JEEP
吉普
Nautica
诺帝卡
Tommy Hilfiger
GIORDANO
佐丹奴
Gant
Trendiano
GXG
Mark Fairwhale
马克华菲
海澜之家
罗蒙
Calvin Klein
卡尔文·克莱恩
Chevignon
Esprit
埃斯普利特
FRED PERRY
Emporio Armani
阿玛尼
Levi\'s
李维斯
lee
G-star Raw
7 For All Mankind
Joe\'s
Wrangler
izzue
5cm
B+ab
Balabala
巴拉巴拉
Disney
 迪士尼
Hanes
恒适
Jockey
居可衣
南极人
Oakley
欧克利
SEPTWOLVES
七匹狼
AOKANG
奥康
Base London
ROCKPORT
乐步
Clarks
奇乐
Crocs
Coach
蔻驰 
kate spade
凯特丝蓓
Michael Kors
迈克·科尔斯
LACOSTE
法国鳄鱼
Delsey
法国大使
Diplomat
外交官
Eminent
雅士
ACE
爱思
American Tourister
美旅箱包
Tuscarora
途斯卡洛拉
Goldlion
金利来
INUK
WENGER
威戈
carany
卡拉羊
Travalue
泰晤乐
Nucelle
纽芝兰
Montblanc
万宝龙
Playboy
花花公子
jansport
aj5
aj8
aj9
aj10
kobe9
kobe10';

        $ext = explode(PHP_EOL,$ext_str);
        $arr_non_existent = $arr_existent = array();

        $es = new tradeElasticSearch();
        foreach($ext as $ext_k=>$ext_v){
            $shoe_put = $es->analyze($ext_v);
            if(!empty($shoe_put['tokens'])){
                $token_count = count($shoe_put['tokens']);
                foreach($shoe_put['tokens'] as $k=>$v){
                    if(trim(strtolower($v['token'])) == trim(strtolower($ext_v))){
                        $arr_existent[] = $ext_v;
                        break;
                    }

                    if(($token_count - $k) <=1){
                        $es->publish(trim(strtolower($ext_v)));
                        $arr_non_existent[] = $ext_v;
                    }
                }
            }
        }

        echo '<pre>';
        print_r($arr_non_existent);
        echo '</pre>';
    }

}