<?php 
    $res =  json_encode($sf_data->getRaw('res'));
    $uname = sfContext::getInstance()->getUser()->getAttribute('username');//当前操作用户id
?>
<div class="pagewrap">
    <div class="pagecontent"> 
        <div class="topkv">
            <a target="_blank" href="http://www.shihuo.cn/haitao#qk=daohang&order=2"><img width="460" height="375" src="/images/trade/activity/qixi/toptitle.png" /></a>
        </div> 
        <script id="tpl" type="text/template">
            <@ var i = 0; @>
            <@ for(var datalist=0; datalist < qixiData.data.length; datalist++){@>
                <div id="<@= qixiData.data[datalist].id @>" class="grid grid<@= i+1 @> fixClear">
                    <div class="message">
                        <div class="title">互动留言</div>
                        <div class="scrollwrap">
                            <div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
                            <div class="viewport">
                                <div class="overview">
                                    <ul class="wrap1">                                        
                                        <@ for(var s=0;s<qixiData.data[datalist].comment.length;s++){@>
                                            <li>
                                                <p><@= qixiData.data[datalist].comment[s].username@></p>  
                                                <p><@= qixiData.data[datalist].comment[s].content@></p>
                                            </li>
                                        <@}@>
                                    </ul>
                                    <ul class="wrap2"></ul>   
                                </div>                               
                            </div>                                                
                        </div>                
                        <div class="comment bottom fixClear">
                            <textarea></textarea>
                            <div data-sid="<@= qixiData.data[datalist].id @>" class="sendbtn">
                                <div class="cover">发送</div>
                                <input type="button" />
                            </div>                    
                        </div>
                    </div>
                    <div class="section">
                        <a class="link1" target="_blank" href="<@= staticData[i].link @>"><@= staticData[i].linkname @><span>>></span></a>
                        <div class="section-wrap">
                            <div class="top fixClear">
                                <div class="fleft">
                                    <div data-mediaSrc="<@= staticData[i].mediaSrc @>" class="img videoplayBtn"><i class="icon-playbtn"></i><img width="100%" src="/images/trade/activity/qixi/img<@= i+1 @>.png" ></div>
                                    <div data-issupport="<@= qixiData.data[datalist].isSupport @>" data-sid="<@= qixiData.data[datalist].id @>" class="vote-btn icon-section-btn<@= i+1 @>">
                                        <span class="unvote">支持她</span>
                                        <span class="votednum"><@= qixiData.data[datalist].support @></span>
                                    </div>
                                </div>
                                <div class="fright">  
                                    <div class="title"><@= staticData[i].title @></div>
                                    <div class="intro"><@=staticData[i].content@></div>
                                </div>
                            </div>
                            <div class="pro-slide">
                                <div class="tc">
                                    <ul class="slidecontent fixClear">
                                        <@ for (var m=0,goodslist=qixiData.data[datalist].goodsInfo;m<goodslist.length;m++){@>
                                        <li>
                                            <a target="_blank" href="http://www.shihuo.cn/haitao/buy/<@= goodslist[m].id @>-<@= goodslist[m].goods_id @>.html">
                                                <img src="<@= goodslist[m].img_path @>" />
                                                <i>¥<@= goodslist[m].price @></i>
                                            </a>
                                        </li>  
                                        <@}@>                          
                                    </ul>
                                </div>
                                <div class="arrow icon-arrow-left"></div>
                                <div class="arrow icon-arrow-right"></div>
                            </div>
                            <a class="link2" target="_blank" href="<@= staticData[i].link @>">去专场>></a>
                        </div>                
                    </div>
                </div>
                <@ i++; @>
             <@ } @>
        </script>      
    </div>   
    <div class="rule-grid">
        <img width="100%" src="/images/trade/activity/qixi/rule-title.png">
        <p><i>1.</i>8月19日10:00-8月25日23:59期间，点击心仪女王照片下的“支持她”，即可为她投票，并获赠一张10元无门槛海淘礼品卡。</p>
        <p><i>2.</i>每个ID每日可投票一次，每次都可获得一张礼品卡，礼品卡不可互相叠加使用。</p>        
        <p><i>3.</i>活动结束后，根据网友支持的票数，选出本次活动的七夕女王，获得相应奖品。</p>
        <p><i>4.</i>使用礼品卡的订单若发生取消订单、拒收或退货等行为，将会退还支付金额和礼品卡，礼品卡使用有效期不变。</p>
        <p><i>5.</i>活动中领取的礼品卡不折现、不退换、敬请谅解。</p>
        <p><i>6.</i>礼品卡只限于海淘代购商品使用。</p>
        <p><i>7.</i>本活动最终解释权归识货所有。</p>
    </div>
    <div class="videowrap">
        <div class="videocontent">
            <div id="mediaTarget" class="projekktor" ></div>
            <div class="icon-close-video"></div>
        </div>    
        <div class="bg"></div>
    </div>     
    <div class="background">
        <img width="1600" height="562" src="/images/trade/activity/qixi/bg-top.jpg" />    
        <img width="1600" height="510" src="/images/trade/activity/qixi/bg1.jpg" />
        <img width="1600" height="560" src="/images/trade/activity/qixi/bg2.jpg" />
        <img width="1600" height="517" src="/images/trade/activity/qixi/bg3.jpg" />
        <img width="1600" height="1363" src="/images/trade/activity/qixi/bg4.jpg" />
        <img width="1600" height="425" src="/images/trade/activity/qixi/bg5.jpg" />
    </div>
</div>

<script type="text/javascript" src="/js/lib/require.js" data-main="/js/trade/activity/qixi/qixi.min.js"></script>
<script type="text/javascript">
    var username = '<?php echo $uname; ?>';
    var qixiData = <?php echo $res; ?>;
    var staticData = [{
        "link" : "http://www.shihuo.cn/special/index?id=273#from=qixi",
        "linkname" : "运动装备专场",
        "mediaSrc":"2015qixi_1",
        "title":"运动装备代言女王：虎扑安妮",
        "content":"虎扑看球超人气主播，简单粗暴的女汉子一枚。最大的兴趣爱好就是和朋友们打篮球！球场上个性霸气的她，私下却逗逼有趣。这次女王大赛，喜欢安妮的要给她投票哦！"
    },{
        "link" : "http://www.shihuo.cn/special/index?id=274#from=qixi",
        "linkname" : "休闲鞋服专场",
        "mediaSrc":"2015qixi_2",
        "title":"服装服饰代言女王：陈美男呐",
        "content":"装备区超人气女JR，正在新西兰读大学。兴趣爱好广泛，喜欢打篮球、打桌球、打保龄球，三国杀之类的桌游也是最爱！平时爱看NBA，最喜欢的球队是马刺！"
    },{
        "link" : "http://www.shihuo.cn/special/index?id=275#from=qixi",
        "linkname" : "电脑数码专场",
        "mediaSrc":"2015qixi_3",
        "title":"数码电器代言女王：jocelyn15",
        "content":"参赛者中最氧气的网友之一，网友jocelyn15曝出照片，并称“拉妹妹来凑数”后，引发了一阵“大舅哥”的认亲风潮。这位妹妹喜爱篮球、健身，还是典型的撸妹子哦！"
    },{
        "link" : "http://www.shihuo.cn/special/index?id=276#from=qixi",
        "linkname" : "钟表首饰专场",
        "mediaSrc":"2015qixi_4",
        "title":"钟表首饰代言女王：KissMeyoki",
        "content":"超萌超嗲的yoki女王，热爱动漫、瑜伽、健身，同时  还是一枚吃货！奉行边吃边健身，吃饱了再锻炼的基本原则！平时会和闺蜜打打麻将，时不时的也撸上一把！"
    },{
        "link" : "http://www.shihuo.cn/special/index?id=277#from=qixi",
        "linkname" : "箱包手袋专场",
        "mediaSrc":"2015qixi_5",
        "title":"箱包手袋代言女王：萌萌呆子",
        "content":"吃货！呆萌！坑货！同样是刺迷，最爱呆子！虽然智商是公认的捉急，但为人坦诚，性格活泼，有她的地方就充满欢笑！平胸而论，她就是开心果！"
    },{
        "link" : "http://www.shihuo.cn/special/index?id=278#from=qixi",
        "linkname" : "营养食品专场",
        "mediaSrc":"2015qixi_6",
        "title":"营养食品代言女王：甜Melody",
        "content":"她是在虎扑直播间每晚都讲sneaker历史故事的人！目前没有男朋友，四海之内皆朋友！摄影音乐体育游戏样样耍的来，不仅名字甜性格也甜，做个萌女汉没什么不好的。"
    }];

</script>