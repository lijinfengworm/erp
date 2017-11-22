<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<?php 
    $res =  json_encode($sf_data->getRaw('res'));
    $uname = sfContext::getInstance()->getUser()->getAttribute('username');//当前操作用户id
?>
<html>
    <head>
        <meta name="apple-mobile-web-app-title" content="虎扑识货">        
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />        
        <meta name="format-detection" content="telephone=no">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="title" content="识货 - 高性价比正品运动鞋导购">
        <title>识货七夕女王评选，来就送券，更有全场满减。赶紧来参加吧 - 识货</title>
        <link rel="stylesheet" href="/css/trade/activity/qixiMobile.css?v=<?php echo tradeCommon::getVersionByEnv(); ?>" />
        <script type="text/javascript" src="/js/shihuoapp/modules/common/flexible.js?v=<?php echo tradeCommon::getVersionByEnv(); ?>"></script>
        <script type="text/javascript" src="/js/trademobile/lib/zepto.js?v=<?php echo tradeCommon::getVersionByEnv(); ?>"></script>
        <script type="text/javascript" src="/js/lib/require.js" data-main="/js/trademobile/qixi/mobileQixi.min.js?v=<?php echo tradeCommon::getVersionByEnv(); ?>"></script>
    </head>
    <body>
        <section class="pagewrap">
            <div class="kv"><img width="100%" src="/images/trademobile/activity/qixi/kv.jpg" /></div>                            
        </section>
        <div class="rule-grid">
            <h3>活动规则</h3>
            <p><i>1</i><span>8月19日10:00-8月25日23:59期间，点击心仪女王照片下的“支持她”，即可为她投票，并获赠一张10元无门槛海淘礼品卡。</span></p>
            <p><i>2</i><span>每个ID每日可投票一次，每次都可获得一张礼品卡，礼品卡不可互相叠加使用。</span></p>            
            <p><i>3</i><span>活动结束后，根据网友支持的票数，选出本次活动的七夕女王，获得相应奖品。</span></p>
            <p><i>4</i><span>使用礼品卡的订单若发生取消订单、拒收或退货等行为，将会退还支付金额和礼品卡，礼品卡使用有效期不变。</span></p>
            <p><i>5</i><span>活动中领取的礼品卡不折现、不退换、敬请谅解。</span></p>
            <p><i>6</i><span>礼品卡只限于海淘代购商品使用。</span></p>
            <p><i>7</i><span>本活动最终解释权归识货所有。</span></p>
        </div>
        <script id="tpl" type="text/template">
            <@ var i = 0; @>
            <@ for(var datalist in qixiData.data ){@>
                <section id="grid-<@= i+1@>" class="grid">
                    <div class="intro">
                        <div class="top fn-clearfix">
                            <h3><@= staticData[i].linkname @></h3>
                            <a href="<@= staticData[i].link @>">去专场&nbsp;>></a>
                        </div>
                        <div class="txt">
                            <div class="title"> - <@= staticData[i].title @></div>
                            <p><@= staticData[i].content @></p>
                        </div>
                    </div>
                    <div class="video">
                        <i class="playbtn"></i>
                        <div class="videowrap">
                            <div class="holdImg"><img src="/images/trademobile/activity/qixi/img<@= i+1@>.png" /></div>
                             <video id="video<@= i+1 @>" class="videoObj" preload="none" src="<@= staticData[i].mediaSrc @>.mp4" controls="controls"></video> 
                         </div>                                 
                    </div>
                    <div class="comment">
                        <div class="comment-grid">
                            <div class="title">互动留言</div>
                            <div id="scrollWrap<@= i+1 @>" class="scrollWrap">
                                <ul class="wrap1">
                                    <@ for(var s=0;s<qixiData.data[datalist].comment.length;s++){@>
                                        <li>
                                            <p><@= qixiData.data[datalist].comment[s].username @></p>  
                                            <p><@= qixiData.data[datalist].comment[s].content @></p>
                                        </li>
                                    <@}@>
                                </ul>
                            </div>                        
                        </div>
                        <div data-issupport="<@= qixiData.data[datalist].isSupport @>" data-sid="<@= qixiData.data[datalist].id @>" class="voteBtn"><span class="unvote">支持她</span><span class="votenum"><@= qixiData.data[datalist].support @></span></div>
                    </div>
                </section>
                <@ i++; @>
            <@ } @>
        </script>      
        <script type="text/javascript">
            var username = '<?php echo $uname; ?>';
            var qixiData = <?php echo $res; ?>;
            var staticData = [{
                "link" : "http://www.shihuo.cn/special/index?id=273#from=qixi",
                "linkname" : "运动装备专场",
                "mediaSrc":"http://shihuo.hupucdn.com/2015qixi_1",
                "title":"运动装备代言女王：虎扑安妮",
                "content":"虎扑看球超人气主播，简单粗暴的女汉子一枚。最大的兴趣爱好就是和朋友们打篮球！球场上个性霸气的她，私下却逗逼有趣。这次女王大赛，喜欢安妮的要给她投票哦！"
            },{
                "link" : "http://www.shihuo.cn/special/index?id=274#from=qixi",
                "linkname" : "休闲鞋服专场",
                "mediaSrc":"http://shihuo.hupucdn.com/2015qixi_2",
                "title":"服装服饰代言女王：陈美男呐",
                "content":"装备区超人气女JR，正在新西兰读大学。兴趣爱好广泛，喜欢打篮球、打桌球、打保龄球，三国杀之类的桌游也是最爱！平时爱看NBA，最喜欢的球队是马刺！"
            },{
                "link" : "http://www.shihuo.cn/special/index?id=275#from=qixi",
                "linkname" : "电脑数码专场",
                "mediaSrc":"http://shihuo.hupucdn.com/2015qixi_3",
                "title":"数码电器代言女王：jocelyn15",
                "content":"参赛者中最氧气的网友之一，网友jocelyn15曝出照片，并称“拉妹妹来凑数”后，引发了一阵“大舅哥”的认亲风潮。这位妹妹喜爱篮球、健身，还是典型的撸妹子哦！"
            },{
                "link" : "http://www.shihuo.cn/special/index?id=276#from=qixi",
                "linkname" : "钟表首饰专场",
                "mediaSrc":"http://shihuo.hupucdn.com/2015qixi_4",
                "title":"钟表首饰代言女王：KissMeyoki",
                "content":"超萌超嗲的yoki女王，热爱动漫、瑜伽、健身，同时  还是一枚吃货！奉行边吃边健身，吃饱了再锻炼的基本原则！平时会和闺蜜打打麻将，时不时的也撸上一把！"
            },{
                "link" : "http://www.shihuo.cn/special/index?id=277#from=qixi",
                "linkname" : "箱包手袋专场",
                "mediaSrc":"http://shihuo.hupucdn.com/2015qixi_5",
                "title":"箱包手袋代言女王：萌萌呆子",
                "content":"吃货！呆萌！坑货！同样是刺迷，最爱呆子！虽然智商是公认的捉急，但为人坦诚，性格活泼，有她的地方就充满欢笑！平胸而论，她就是开心果！"
            },{
                "link" : "http://www.shihuo.cn/special/index?id=278#from=qixi",
                "linkname" : "营养食品专场",
                "mediaSrc":"http://shihuo.hupucdn.com/2015qixi_6",
                "title":"营养食品代言女王：甜Melody",
                "content":"她是在虎扑直播间每晚都讲sneaker历史故事的人！目前没有男朋友，四海之内皆朋友！摄影音乐体育游戏样样耍的来，不仅名字甜性格也甜，做个萌女汉没什么不好的。"
            }];
            
        </script>
    </body>
</html>