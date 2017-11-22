<meta http-equiv="X-UA-Compatible" content="IE=edge" >
<link type="text/css" rel="stylesheet" href="http://b3.hoopchina.com.cn/common/common-v1.css" />
<script src="http://b3.hoopchina.com.cn/common/common-v1.js"></script>
<input type="hidden" id="uid" value="<?php echo $uid ? $uid : ''; ?>">
<input type="hidden" id="isOver" value="<?php echo $isOver; ?>">
<input type="hidden" id="slide-index" value="<?php if($gameDay==4)echo "1";elseif($gameDay==5)echo "2";else{echo "0";} ?>">
<input type="hidden" id="temp" value="<?php echo $temp?>"/>

<div class="wrapper">
    <div class="container">
        <!-- banner header-->
        <div class="header">
            <h1 title="HIGH“翻”双11">
            <img class="title" src="http://kaluli.hoopchina.com.cn/images/trade/1111/title.png" alt="HIGH“翻”双11">
            </h1>
            <img class="car" src="http://kaluli.hoopchina.com.cn/images/trade/1111/che.png">
            <div class="menus">
                <div class="menus-inner">
                    <div class="menu-item">
                        <a href="#game">
                            <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-game.png" data-id="game">
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="#rules">
                            <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-rules.png" data-id="rules">
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="#hot">
                            <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-hot.png" data-id="hot">
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="#tmall">
                            <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-tmall.png" data-id="tmall">
                        </a>
                    </div>
                </div>
            </div>
            <!-- 奖金预告 -->
            <div class="jiangjin">
                <h2 title="奖金预告">
                <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/title-jpyg.png">
                </h2>
                <div class="slider">
                    <div class="scroller">
                        <ul class="slide" id="js-slide" style="<?php if($gameDay==4) echo "left:-984px;";elseif($gameDay==5)echo "left:-1968px;";else{echo "left:0px";} ?>">
                            <!-- 1, 2, 3 day -->
                            <li class="slide-item">
                                <!-- Day1 -->
                                <div class="day day1">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/<?php $time = time(); // e.g.  1415258882
                                    if($time >= strtotime("2014-11-07 09:00") && $time < strtotime("2014-11-07 18:00")){
                                        echo 'day1-active';
                                    }elseif($time >= strtotime("2014-11-08 09:00") && $time < strtotime("2014-11-08 18:00")){
                                        echo 'day2-active';
                                    }elseif($time >= strtotime("2014-11-09 09:00") && $time < strtotime("2014-11-09 18:00")){
                                        echo 'day3-active';
                                    }else{
                                        echo 'day1';                                    
                                    }
                                    ?>.png">
                                </div>
                            </li>
                            <!-- 4 day -->
                            <li class="slide-item">
                                <!-- Day1 -->
                                <div class="day day1">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/<?php
                                    if($time >= strtotime("2014-11-10 09:00") && $time < strtotime("2014-11-10 14:00")){
                                        echo 'day4-1-active';
                                    }elseif($time >= strtotime("2014-11-10 14:00") && $time < strtotime("2014-11-10 19:00")){
                                        echo 'day4-2-active';
                                    }elseif($time >= strtotime("2014-11-10 19:00") && $time < strtotime("2014-11-11 00:00")){
                                        echo 'day4-3-active';
                                    }else{                                        
                                        echo 'day4';
                                    }
                                    ?>.png">
                                </div>
                            </li>                            
                            <li class="slide-item">
                                <!-- Day5 -->
                                <div class="day day1">
                                   <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/<?php
                                    if($time >= strtotime("2014-11-11 00:00") && $time <= strtotime("2014-11-11 24:00")){
                                        echo 'day5-active';
                                    }else{
                                        echo 'day5';
                                    } 
                                    ?>.png?1111">
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="slider-prev" id="js-slider-prev">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/left-arrow.png">
                    </div>
                    <div class="slider-next" id="js-slider-next">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/right-arrow-active.png">
                    </div>
                </div>
            </div>
            <!-- 双11秘笈 -->
            <a class="table1" href="http://www.shihuo.cn/1111#all">
                <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-miji1.png">
            </a>
            <a class="table2" href="http://bbs.hupu.com/job.php?action=download&pid=tpc&tid=10859657&aid=2369390">
                <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-miji2.png">
            </a>
        </div>
        <!-- 游戏版块 -->
        <div class="game clearfix" id="game">
            <!-- 游戏左边栏 -->
            <div class="left-side">
                <?php if($isLogin): ?>
                <!-- 头像个人信息-->
                <input type="hidden" name="uname" value="<?php echo $uid ?>">
                <div class="avatar">
                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/avatar.png">
                    <strong class="username"><?php echo $uname ?></strong>
                    <span class="desc">当前最低价格总和&nbsp;&nbsp;
                        <span id="js-lowest-price"><?php echo $dayRoundUserLowScore ? $dayRoundUserLowScore : '/'; ?>
                        </span>

                </div>
                <?php else: ?>
                <div class="avatar-default"><?php ?>
                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/default-avatar.png">
                    <a class="double11-login" href="javascript:void(0);" id="js-11-login" onclick="commonLogin();">登录</a>
                </div>
                <?php endif; ?>
                <!-- 5次机会-->
                <div class="chance">
                    <?php if($platformStatus['onPC']): ?>
                    <img class="cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/pc-5-chance-active.png" id="js-pc-share">
                    <?php else: ?>
                    <img class="cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/pc-5-chance.png" id="js-pc-share">
                    <?php endif; ?>
                    <?php if($platformStatus['onAPP']): ?>
                    <img class="cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/app-8-chance-active.png" id="js-app-share">
                    <?php else: ?>
                    <img class="cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/app-8-chance.png" id="js-app-share">
                    <?php endif; ?>
                    <?php if($platformStatus['onSHARE']): ?>
                    <img class="last cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/share-5-chance-active.png" id="js-share">
                    <?php else: ?>
                    <img class="last cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/share-5-chance.png" id="js-share">
                    <?php endif; ?>
                </div>
                <!-- 倒计时 -->
                <div class="timeleft">
                    <input type="hidden" id="leftTime" value="<?php echo time()+$timeLeft; ?>">
                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/clock.png">
                    <span class="timeleft-title">距离本场结束剩余</span>
                    <div class="timeleft-content" id="js-timeleft-content">
                        <span id="js-hour-left">0</span>小时
                        <span id="js-minute-left">0</span>分钟
                        <span id="js-second-left">0</span>秒
                    </div>
                </div>
                <div class="ranking">
                    <table>
                        <thead>
                            <th style="width: 48px;">
                                <div>排名</div>
                            </th>
                            <th style="width: 51px;">
                                <div>ID</div>
                            </th>
                            <th style="width: 83px;">
                                <div>最低价格</div>
                            </th>
                            <th class="last">
                                <div>奖品</div>
                            </th>
                        </thead>
                        <tbody id="js-rank">
                            <!-- 自己的排名 -->
                            <tr class="my-ranking bold <?php if(!$myRank) echo 'hidden'; ?>">
                                <td><?php echo $myRank; ?></td>
                                <td class="rank-name"><?php echo $uname; ?></td>
                                <td><?php echo $dayRoundUserLowScore; ?></td>
                                <td><?php echo $myPresent; ?></td>
                            </tr>
                            <?php if(isset($rankInfo['totalRank']))
                                foreach($rankInfo['totalRank'] as $r_k => $r_v): ?>
                            <!-- 前三名 -->
                            <tr class="<?php if($r_k%2==1) echo 'tr-strip'; ?> <?php if($r_k<=3) echo 'bold'; ?>">
                                <td style="font-size: 18px;"><?php echo $r_k ?></td>
                                <td class="rank-name"><?php echo $r_v['uname'] ?></td>
                                <td><?php echo $r_v['score'] ?></td>
                                <td><?php echo $r_v['present'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 游戏内容区域 -->
            <div class="main">
                <!-- ul 表示每一行. 每行3列-->
                <ul>
                    <li class="poker-item">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heart.jpg" data-id="0">
                                </div>
                                <div class="back">

                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="poker-item">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-meihua.jpg" data-id="1">
                                </div>
                                <div class="back">
                                    
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="poker-item last">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heitao.jpg" data-id="2">
                                </div>
                                <div class="back">
                                    
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="poker-item">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heart.jpg" data-id="3">
                                </div>
                                <div class="back">
                                    
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="poker-item">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-meihua.jpg" data-id="4">
                                </div>
                                <div class="back">
                                    
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="poker-item last">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heitao.jpg" data-id="5">
                                </div>
                                <div class="back">
                                    
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="poker-item mb-0">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heart.jpg" data-id="6">
                                </div>
                                <div class="back">
                                    
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="poker-item mb-0">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-meihua.jpg" data-id="7">
                                </div>
                                <div class="back">
                                    
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="poker-item last mb-0">
                        <div class="flip-container">
                            <div class="flipper">
                                <div class="front">
                                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heitao.jpg" data-id="8">
                                </div>
                                <div class="back">
                                    
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- 游戏右边栏 -->
            <div class="right-side">
                <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/game-state.jpg">
                <!-- 本次价格总和 -->
                <div class="price-total">
                    <div class="right-side-inner price-inner">
                        <h2>本次价格总和</h2>
                        <span class="price">
                        <span class="num" id="js-game-price">0.00</span><br/>
                        <span>元</span>
                        </span>
                    </div>
                </div>
                <!-- 剩余机会 -->
                <div class="chance-left">
                    <div class="right-side-inner chance-inner">
                        <h2>剩余机会</h2>
                        <span class="chance">
                        <span class="num" id="js-chance-left"><?php echo $chanceInfo; ?></span>次
                        </span>
                    </div>
                </div>
                <div class="right-side-inner share-add-chance" id="js-share-add-chance">
                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/icon-share.png">
                    <span>分享增加机会</span>
                    <div class="chance-share" id="js-chance-share">
                        <div class="chance-share-inner">
                            <p style="margin-bottom: 5px;">分享到新浪微博，<br/>
                            人人网等SNS平台<br/>
                            获得<span style="color: #2b1a04;font-size: 14px;font-weight: bold;">额外的5次</span><br/>
                            游戏机会
                            </p>
                            <p style="margin-bottom: 10px;">分享到：</p>
                            <div class="clearfix share-icons">
                                <img class="pull-left" data-type="weibo" src="http://kaluli.hoopchina.com.cn/images/trade/1111/weibo.png">
                                <img class="pull-left" data-type="tqq" src="http://kaluli.hoopchina.com.cn/images/trade/1111/tweibo.png">
                                <img class="pull-left" data-type="qzone" src="http://kaluli.hoopchina.com.cn/images/trade/1111/qzone.png">
                                <img class="pull-left" data-type="renren" src="http://kaluli.hoopchina.com.cn/images/trade/1111/renren.png">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="right-side-inner app-add-chance" id="js-app-add-chance">
                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/icon-phone.png">
                    <span>APP额外机会</span>
                    <div class="qr-app-chance" id="js-qr-app-chance">
                        <div class="qr-app-chance-inner">
                            <p>
                            下载识货移动客户端，
                            进入移动平台抽奖，可获得
                            <span style="color: #2b1a04;font-size: 14px;font-weight: bold;">额外的8次</span>
                            机会
                            </p>
                            <img style="margin: 16px 0 0 8px;" src="http://kaluli.hoopchina.com.cn/images/trade/1111/qr.png" alt="下载识货手机客户端">
                        </div>
                    </div>
                </div>
                <!-- 再玩一次按钮 -->
				<div class="play-onemore <?php echo $chanceInfo>0?cursor:''; ?>">
					<a href="http://www.shihuo.cn/1111/game#game"   id="js-play-reset"  target="_blank">
						<img src="<?php echo $chanceInfo>0?'http://kaluli.hoopchina.com.cn/images/trade/1111/btn-playonemore.png':'http://kaluli.hoopchina.com.cn/images/trade/1111/btn-playonemore-disabled.png'; ?>">
					</a>
<!--					<img src="--><?php //echo $chanceInfo>0?'http://kaluli.hoopchina.com.cn/images/trade/1111/btn-playonemore.png':'http://kaluli.hoopchina.com.cn/images/trade/1111/btn-playonemore-disabled.png'; ?><!--">-->
				</div>


            </div>
        </div>
        <!-- 参与规则 -->
        <div class="rules" id="rules">
            <ul>
                <li>
                    <em>1</em>
                   每场用户都有最高18次翻牌机会（PC5次，分享5次，app内8次）
                </li>
                <li>
                    <em>2</em>
                    场次开始后，你可以再以下的9张牌中任意翻3张牌，翻出的牌价格总和越低，<br/>
                    &nbsp;&nbsp;&nbsp;&nbsp;你的排名越靠前，相同价格情况下，优先翻出价格的用户排名靠前
                </li>
                <li>
                    <em>3</em>
                    奖品将在活动结束后发放，活动最终解释权归识货所有
                </li>
            </ul>
            <a href="#game">
                <img class="btn-canyu btn-1" id="" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-lijicanyu.png" alt="立即参与">
            </a>
            <img class="btn-canyu btn-2" id="js-rules-app-share" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-yidongcanyu.png" alt="移动参与">
        </div>
        <!-- 商品展示 -->
        <div class="gallery" id="hot">
            <div class="gallery-header">
                <a style="margin-right: 10px; font-size: 0;" href="http://www.shihuo.cn/1111#all">
                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-download1.png" alt="下载表格1">
                </a>
                <a style="margin-right: 10px; font-size: 0;" href="http://bbs.hupu.com/job.php?action=download&pid=tpc&tid=10859657&aid=2369390">
                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-download2.png" alt="下载表格2">
                </a>
                <img style="position: relative; top: 0;" src="http://kaluli.hoopchina.com.cn/images/trade/1111/text-download.png" alt="双11全部商品下载Download">
                <!-- 全部商品 类别 Tab Menu -->
                <ul class="gallery-menus">
                    <?php foreach($classification as $k => $v): ?>
                    <li>
                        <img class="<?php if($k==0)echo 'tab-active';?>" data-id="<?php echo $k+1; ?>" src="http://kaluli.hoopchina.com.cn/images/trade/1111/category-<?php echo $k+1; ?><?php if($k==0)echo '-active';?>.png">
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div class="search-box">
                    <a href="http://www.shihuo.cn/shihuo/showcvs?keyword=" class="search-box-trigger" id="js-search-box-trigger"></a>
                    <input type="text" id="js-search-box-input">
                </div>
            </div>
            <!-- 全部商品 -->
            <div class="gallery-content active" id="js-gallery-content">
                    <?php foreach($classifiedGoodsInfo as $k=>$v):
                        if($k+1%5==0 || $k==0)
                            echo '<div class="gallery-row">';
                        ?>
                    <div class="gallery-item">
                        <a href="<?php echo isset($v['link']) && $v['link'] ? $v['link']:'#'; ?>" target="_blank"> 
                            <img src="<?php echo isset($v['pic']) && $v['pic'] ? $v['pic']:'http://kaluli.hoopchina.com.cn/images/trade/1111/1.png'; ?>">
                        </a>
                        <div class="desc">
                            <h3><?php echo isset($v['name']) && $v['name'] ? $v['name']:'未知'; ?></h3>
                            <div>
                                <span class="promo-price">
                                <span class="symbol">¥</span>
                                <span><?php echo isset($v['price']) && $v['price'] ? $v['price']:'未知'; ?></span>
                                </span>
                                <span class="origin-price">¥<?php echo isset($v['ori_price'])&&$v['ori_price']?$v['ori_price']:'未知'; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php
                        if($k+1%5==0)
                            echo '</div>';
                    endforeach; ?>
            </div>
        </div>
            <div class="gallery-text-end">
                <a href="http://www.shihuo.cn/1111#all">
                    <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/text-gallery-end.png">
                </a>
            </div>
        </div>
        <!-- 生鲜 -->
        <div class="shengxian" id="tmall">
            <ul>
                <li>
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DwON5KJJdPVAcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMZmHL14Ncz0wxq3IhSJN6GRGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVaX4VWt66S4EJPwiig1bxLMnyi1UQ%2F17I9xSd4k%2BkojmUiXYJgFO%2BdU%3D" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-1.jpg">
                    </a>
                </li>
                <li>
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DBXq5qs0Fu6ocQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMcAj%2B82u%2BfgO79%2FTFaMDK6RGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2Fplpa1ptKO5BKn1GHnsRyADFdHbpaCeGZ4DEW%2Fua7mPcgp7rq7ceXPO0G6De7rlRE4omfkDJRs%2BhU%3D" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-2.jpg">
                    </a>
                </li>
                <li class="last">
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DLTDQrMSEUMgcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMYRLCT5XqzCqt4hWD5k2kjNGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2FpXzc3eQT%2FQvW8CYpAyrKo72LtskkhrsM53Z8tT6jmgDDcUneJPpKI5lIl2CYBTvnV" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-3.jpg">
                    </a>
                </li>

                <li>
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DAvJlq9ZCZyQcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMT23PJb98Sxilovu%2FCElQOtGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2Fplpa1ptKO5BKn1GHnsRyADEiUL4Y2Ll8l7%2BX4d0oroxsxYtO7Jt3tbFCCiwtodYQf" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-4.jpg">
                    </a>
                </li>
                <li>
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DgaQYpnJtYM4cQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMeYaYnxxnHTZ1aH1Hk3GeOhGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2Fplpa1ptKO5BKn1GHnsRyADGC16QsV5wJvDEW%2Fua7mPcgp7rq7ceXPO0G6De7rlRE4omfkDJRs%2BhU%3D" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-5.jpg">
                    </a>
                </li>
                <li class="last">
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DpnGslSoebJUcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMQ%2FzPTNA0tEBxq3IhSJN6GRGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2Fplpa1ptKO5BKn1GHnsRyADG4y0tbyEK81a2NO46FEhT8vtMLmxVpnwIKR2jgyr2od" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-6.jpg">
                    </a>
                </li>


                <li>
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DSSAgftLE53AcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMaU7DB6QMPwO8sviUM61dt1GtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2FpXzc3eQT%2FQvWBBt3wsR0C8THuI%2BI9hlgUqG3U8e9e7WDyi%2BuJoUwH9Q%3D%3D" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-7.jpg">
                    </a>
                </li>
                <li>
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DtmEQnUJWbiYcQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMQpYMZfLWFWx5x%2BIUlGKNpVGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2Fplpa1ptKO5BKn1GHnsRyADEgooKTtJVcla2NO46FEhT8vtMLmxVpnwIKR2jgyr2od" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-8.jpg">
                    </a>
                </li>
                <li class="last">
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3D%2FbBK%2F1DgPB0cQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMf7E1Oi9AAbhlovu%2FCElQOtGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2Fplpa1ptKO5BKn1GHnsRyADBoeKAJDBBHbQwobMO4Qxo8XL4JxMz%2F3prUvau7B20gBxF85u2XzuTI%3D" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-9.jpg">
                    </a>
                </li>


                <li>
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3DK9yxx8JPEZocQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMc4n7sDouvcblovu%2FCElQOtGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2Fplpa1ptKO5BKn1GHnsRyADGTS3tp3et0V7t1SaW5j%2B7CBhsrv4aPSLv%2F%2BOSgUhR0s" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-10.jpg">
                    </a>
                </li>
                <li>
                    <a href="http://s.click.taobao.com/t?e=m%3D2%26s%3Du7NwPR8Yj%2B8cQipKwQzePCperVdZeJviK7Vc7tFgwiFRAdhuF14FMdUq4E86mcjTt4hWD5k2kjNGtvS2t8YXqo2WrrQZ4Yz3%2BAIrA2QEmBTQPnXlJW95D6UuZxIcp9pfUIgVEmFmgnbDX0%2BHH2IEVeOhxeU7PAMffjxl6AIniNq2NuRO0YvGxWDg6Uw6J4%2Fplpa1ptKO5BKn1GHnsRyADIol0qNjddJba2NO46FEhT8vtMLmxVpnwIKR2jgyr2od" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-11.jpg">
                    </a>
                </li>
                <li class="last">
                    <a href="http://www.tmall.com/go/market/promotion-act/dnbg20141111.php?ali_trackid=2:mm_31576222_7290065_27178678:1415191746_2k1_2008884078" target="_blank">
                        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-12.jpg">
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="double11-modal" id="js-double11-modal">
    <img class="double11-modal-close" id="js-double11-modal-close" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-close.png">
    <div class="double11-modal-header"></div>
    
    <div class="double11-modal-body">
        <dl>
            <div style="margin-bottom: 11px;">
                <dt>您的最低价格：</dt>
                <dd id="js-modal-price">456.00元</dd>
            </div>
            <dt>当前排名：</dt>
            <dd id="js-modal-rank">22</dd>
        </dl>
    </div>
</div>
<div class="double11-modal" id="js-double11-modal-next-alert" style="display: none">
	<img class="double11-modal-close" onclick="$('#js-double11-modal-next-alert').hide()" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-close.png">
	<div class="double11-modal-header"></div>

	<div class="double11-modal-body">
		<dl>
			<div style="margin-bottom: 11px;">
				<dt>本次活动以结束</dt>
			</div>
			<?php
			if(!empty($nextTime))
			{
				?>
				下次活动时间 <?php echo date('m月d日 H点',$nextTime)?>
			<?PHP
			}
			?>
		</dl>
	</div>
</div>

<div class="double11-overlay" id="js-double11-overlay"></div>
<script>
	//结束了显示下一场
	if($("#isOver").val() == 1)
	{
		window.scrollTo(0,300);
		$("#js-double11-modal-next-alert").show();
	}
</script>

<div class="double11-modal" id="js-double11-modal-message-alert" style="display: none">
	<img class="double11-modal-close" onclick="$('#js-double11-modal-message-alert').hide()" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-close.png">
	<div class="double11-modal-header"></div>

	<div class="double11-modal-body">
		<dl>
			<div style="margin-bottom: 11px;text-align: center;line-height: 25px;"">
				<span id="js-double11-modal-message-alert-title"></span>
			</div>
		</dl>
	</div>
</div>

<!-- 纸牌模板 -->
<script id="card-back-tpl" type="text/x-handlebars-template">
    <a href="{{link}}" target="_blank">
        <img class="back-img" src="{{pic}}">
    </a>
    <div class="desc">
        <span class="name">{{name}}</span><br/>
        <span class="price">
        <span class="symbol">¥</span>
        <span class="num">{{price}}</span>
        </span>
        <a class="btn-look" href="{{link}}" target="_blank">
            <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-look.png">
        </a>
    </div>
</script>

<!-- 排名 -->
<script id="rank-tpl" type="text/x-handlebars-template">
    <tr class="my-ranking bold">
        <td>{{myRank}}</td>
        <td class="rank-name">{{myId}}</td>
        <td>{{myLowScore}}</td>
        <td>{{myPresent}}</td>
    </tr>
    <!-- 前三名 -->
    {{#each top3}}
    <tr class="{{cls}} bold">
        <td style="font-size: 18px;"> {{index}} </td>
        <td class="rank-name">{{uname}}</td>
        <td>{{score}}</td>
        <td>{{present}}</td>
    </tr>
    {{/each}}

    {{#each totalRank}}
        <tr class="{{cls}}">
            <td style="font-size: 18px;"> {{index}} </td>
            <td class="rank-name">{{uname}}</td>
            <td>{{score}}</td>
            <td>{{present}}</td>
        </tr>
    {{/each}}
</script>

<!-- 商品分类模板 -->
<script id="category-goods-tpl" type="text/x-handlebars-template">
   
    {{#each data}}
    <div class="gallery-row">
        {{#each goods}}
        <div class="gallery-item">
            <a href="{{link}}" target="_blank"> <img src="{{pic}}"></a>
            <div class="desc">
                <h3>{{name}}</h3>
                <div>
                    <span class="promo-price">
                    <span class="symbol">¥</span>
                    <span>{{price}}</span>
                    </span>
                    <span class="origin-price">¥{{ori_price}}</span>
                </div>
            </div>
        </div>
        {{/each}}
    </div>
    {{/each}}
</script>