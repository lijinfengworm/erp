<meta http-equiv="X-UA-Compatible" content="IE=edge" >
<link type="text/css" rel="stylesheet" href="http://b3.hoopchina.com.cn/common/common-v1.css" />
<script src="http://b3.hoopchina.com.cn/common/common-v1.js"></script>
<input type="hidden" id="uid" value="<?php echo $uid ? $uid : ''; ?>">
<input type="hidden" id="isOver" value="<?php echo $isOver; ?>">
<input type="hidden" id="slide-index" value="0">
<input type="hidden" id="temp" value="<?php echo $temp?>"/>

<div class="wrapper">
<div class="container">
<!-- banner header-->
<div class="header">
	<h1 title="HIGH“翻”双11">
		<img class="title" src="http://kaluli.hoopchina.com.cn/images/trade/1111/title2.png?s=618" alt="HIGH“翻”双11">
	</h1>
	<div class="menus">
		<div class="menus-inner">
			<div class="menu-item">
				<a href="#game">
					<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-game.png?s=618" data-id="game">
				</a>
			</div>
			<div class="menu-item">
				<a href="#rules">
					<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-rules.png?s=618" data-id="rules">
				</a>
			</div>
			<div class="menu-item">
				<a href="#hot">
					<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-hot.png?s=618" data-id="hot">
				</a>
			</div>
			<div class="menu-item">
				<a href="#tmall">
					<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-tmall.png?s=618" data-id="tmall">
				</a>
			</div>
		</div>
	</div>
	<!-- 奖金预告 -->
	<div class="jiangjin">
		<h2 title="奖金预告">
			<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/title-jpyg.png?s=618">
		</h2>
		<div class="slider">
			<div class="scroller">
				<ul class="slide" id="js-slide" style="left:0px">
					<!-- 1, 2, 3 day -->
					<li class="slide-item">
						<!-- Day1 -->
						<div class="day day1">
							<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/day1.png?s=618">
						</div>
					</li>
					<!-- 4 day -->
					<li class="slide-item">
						<!-- Day1 -->
						<div class="day day1">
							<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/day4.png?s=618">
						</div>
					</li>
					<li class="slide-item">
						<!-- Day5 -->
						<div class="day day1">
							<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/day5.png?s=618?1111">
						</div>
					</li>
				</ul>
			</div>
			<!--
			<div class="slider-prev" id="js-slider-prev">
				<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/left-arrow.png?s=618">
			</div>
			<div class="slider-next" id="js-slider-next">
				<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/right-arrow-active.png?s=618">
			</div>
		    -->
		</div>
	</div>
	<!-- 双11秘笈 -->
	<!--
	<a class="table1" href="http://www.shihuo.cn/1111#all">
		<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-miji1.png?s=618">
	</a>
	<a class="table2" href="http://bbs.hupu.com/job.php?action=download&pid=tpc&tid=10859657&aid=2369390">
		<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-miji2.png?s=618">
	</a>
-->
</div>
<!-- 游戏版块 -->
<div class="game clearfix" id="game">
<!-- 游戏左边栏 -->
<div class="left-side">
    <?php if($isLogin): ?>
        <!-- 头像个人信息-->
        <input type="hidden" name="uname" value="<?php echo $uid ?>">
        <div class="avatar">
            <img src="http://bbs.hupu.com/bbskcy/api_new_image.php?uid=<?php echo $uid ? $uid : ''; ?>">
            <strong class="username"><?php echo $uname ?></strong>
                    <span class="desc">当前最低价格总和&nbsp;&nbsp;
                        <span id="js-lowest-price"><?php echo $dayRoundUserLowScore ? $dayRoundUserLowScore : '/'; ?>
                        </span>

        </div>
    <?php else: ?>
        <div class="avatar-default"><?php ?>
            <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/default-avatar.png?s=618">
            <a class="double11-login" href="javascript:void(0);" id="js-11-login" onclick="commonLogin();">登录</a>
        </div>
    <?php endif; ?>
	<!-- 5次机会-->
    <div class="chance">
        <?php if($platformStatus['onPC']): ?>
            <img class="cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/pc-5-chance-active.png?s=618" id="js-pc-share">
        <?php else: ?>
            <img class="cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/pc-5-chance.png?s=618" id="js-pc-share">
        <?php endif; ?>
        <?php if($platformStatus['onAPP']): ?>
            <img class="cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/app-8-chance-active.png?s=618" id="js-app-share">
        <?php else: ?>
            <img class="cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/app-8-chance.png?s=618" id="js-app-share">
        <?php endif; ?>
        <?php if($platformStatus['onSHARE']): ?>
            <img class="last cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/share-5-chance-active.png?s=618" id="js-share">
        <?php else: ?>
            <img class="last cursor" src="http://kaluli.hoopchina.com.cn/images/trade/1111/share-5-chance.png?s=618" id="js-share">
        <?php endif; ?>
    </div>
	<!-- 倒计时 -->
    <div class="timeleft">
        <input type="hidden" id="leftTime" value="<?php echo time()+$timeLeft; ?>">
        <img src="http://kaluli.hoopchina.com.cn/images/trade/1111/clock.png?s=618">
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
						<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heart.jpg?s=618" data-id="0">
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
						<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-meihua.jpg?s=618" data-id="1">
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
						<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heitao.jpg?s=618" data-id="2">
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
						<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heart.jpg?s=618" data-id="3">
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
						<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-meihua.jpg?s=618" data-id="4">
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
						<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heitao.jpg?s=618" data-id="5">
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
						<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heart.jpg?s=618" data-id="6">
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
						<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-meihua.jpg?s=618" data-id="7">
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
						<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/poker-heitao.jpg?s=618" data-id="8">
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
	<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/game-state.jpg?s=618">
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
		<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/icon-share.png?s=618">
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
					<img class="pull-left" data-type="weibo" src="http://kaluli.hoopchina.com.cn/images/trade/1111/weibo.png?s=618">
					<img class="pull-left" data-type="tqq" src="http://kaluli.hoopchina.com.cn/images/trade/1111/tweibo.png?s=618">
					<img class="pull-left" data-type="qzone" src="http://kaluli.hoopchina.com.cn/images/trade/1111/qzone.png?s=618">
					<img class="pull-left" data-type="renren" src="http://kaluli.hoopchina.com.cn/images/trade/1111/renren.png?s=618">
				</div>
			</div>
		</div>
	</div>
	<div class="right-side-inner app-add-chance" id="js-app-add-chance">
		<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/icon-phone.png?s=618">
		<span>APP额外机会</span>
		<div class="qr-app-chance" id="js-qr-app-chance">
			<div class="qr-app-chance-inner">
				<p>
					下载识货移动客户端，
					进入移动平台抽奖，可获得
					<span style="color: #2b1a04;font-size: 14px;font-weight: bold;">额外的8次</span>
					机会
				</p>
				<img style="margin: 16px 0 0 8px;" src="http://kaluli.hoopchina.com.cn/images/trade/1111/qr.png?s=618" alt="下载识货手机客户端">
			</div>
		</div>
	</div>
    <!-- 再玩一次按钮 -->
    <div class="play-onemore <?php echo $chanceInfo>0?cursor:''; ?>">
        <a href="http://www.shihuo.cn/618/game#game"   id="js-play-reset"  target="_blank">
            <img src="<?php echo $chanceInfo>0?'http://kaluli.hoopchina.com.cn/images/trade/1111/btn-playonemore.png?s=618':'http://kaluli.hoopchina.com.cn/images/trade/1111/btn-playonemore-disabled.png?s=618'; ?>">
        </a>
        <!--					<img src="--><?php //echo $chanceInfo>0?'http://kaluli.hoopchina.com.cn/images/trade/1111/btn-playonemore.png?s=618':'http://kaluli.hoopchina.com.cn/images/trade/1111/btn-playonemore-disabled.png?s=618'; ?><!--">-->
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
		<img class="btn-canyu btn-1" id="" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-lijicanyu.png?s=618" alt="立即参与">
	</a>
	<img class="btn-canyu btn-2" id="js-rules-app-share" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-yidongcanyu.png?s=618" alt="移动参与">
</div>
<!-- 商品展示 -->
<div class="gallery" id="hot">
<div class="gallery-header">
	<!--
	<a style="margin-right: 10px; font-size: 0;" href="http://www.shihuo.cn/1111#all">
		<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-download1.png?s=618" alt="下载表格1">
	</a>
	<a style="margin-right: 10px; font-size: 0;" href="http://bbs.hupu.com/job.php?action=download&pid=tpc&tid=10859657&aid=2369390">
		<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-download2.png?s=618" alt="下载表格2">
	</a>
	<img style="position: relative; top: 0;" src="http://kaluli.hoopchina.com.cn/images/trade/1111/text-download.png?s=618" alt="双11全部商品下载Download">
-->

	<!-- 全部商品 类别 Tab Menu -->
    <ul class="gallery-menus">
        <li class="on" data-id="1">
            运动户外
        </li>
        <li data-id="2">
            家居用品
        </li>
        <li data-id="3">
            男装
        </li>
        <li data-id="4">
            家电
        </li>
        <li data-id="5">
            鞋包
        </li>
        <li data-id="6">
            食品
        </li>
        <li data-id="7">
            珠宝配饰
        </li>
    </ul>
</div>
    <!-- 全部商品 -->
    <div class="gallery-content active" id="js-gallery-content">
        <?php foreach($classifiedGoodsInfo as $k=>$v):
            if($k+1%5==0 || $k==0)
                echo '<div class="gallery-row">';
            ?>
            <div class="gallery-item">
                <a href="<?php echo isset($v['link']) && $v['link'] ? $v['link']:'#'; ?>" target="_blank">
                    <img src="<?php echo isset($v['pic']) && $v['pic'] ? $v['pic'].'_300x300.jpg?s=618':'http://kaluli.hoopchina.com.cn/images/trade/1111/1.png?s=618'; ?>">
                </a>
                <div class="desc">
                    <h3><?php echo isset($v['name']) && $v['name'] ? $v['name']:'未知'; ?></h3>
                    <div>
                                <span class="promo-price">
                                <span class="symbol">¥</span>
                                <span><?php echo isset($v['price']) && $v['price'] ? $v['price']:'未知'; ?></span>
                                </span>

                    </div>
                </div>
            </div>
            <?php
            if($k+1%5==0)
                echo '</div>';
        endforeach; ?>
    </div>
</div>
<!--
<div class="gallery-text-end">
	<a href="http://www.shihuo.cn/1111#all">
		<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/text-gallery-end.png?s=618">
	</a>
</div>
-->
</div>
<!-- 生鲜 -->
<div class="shengxian" id="tmall">
	<ul>
		<li>
			<a href="http://s.click.taobao.com/pPxas2y" target="_blank">
				<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-1.jpg?s=618">
			</a>
		</li>
		<li>
			<a href="http://s.click.taobao.com/ovIcs2y" target="_blank">
				<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-2.jpg?s=618">
			</a>
		</li>
		<li class="last">
			<a href="http://s.click.taobao.com/d8ycs2y" target="_blank">
				<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-3.jpg?s=618">
			</a>
		</li>

		<li>
			<a href="http://s.click.taobao.com/GUubs2y" target="_blank">
				<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-4.jpg?s=618">
			</a>
		</li>
		<li>
			<a href="http://s.click.taobao.com/siebs2y" target="_blank">
				<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-5.jpg?s=618">
			</a>
		</li>
		<li class="last">
			<a href="http://s.click.taobao.com/JCvas2y" target="_blank">
				<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/hc-6.jpg?s=618">
			</a>
		</li>
	</ul>
</div>
</div>
</div>
<div class="double11-modal" id="js-double11-modal">
	<img class="double11-modal-close" id="js-double11-modal-close" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-close.png?s=618">
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
	<img class="double11-modal-close" onclick="$('#js-double11-modal-next-alert').hide()" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-close.png?s=618">
	<div class="double11-modal-header"></div>

	<div class="double11-modal-body">
		<dl>
			<div style="margin-bottom: 11px;">
				<dt>本次活动以结束</dt>
			</div>
			<a href="http://bbs.hupu.com/10926044.html">查看红包领取方式</a></dl>
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
	<img class="double11-modal-close" onclick="$('#js-double11-modal-message-alert').hide()" src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-close.png?s=618">
	<div class="double11-modal-header"></div>

	<div class="double11-modal-body">
		<dl>
			<div style="margin-bottom: 11px;text-align: center;line-height: 25px;">
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
			<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/btn-look.png?s=618">
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
			<a href="{{link}}" target="_blank"> <img src="{{pic}}_300x300.jpg?s=618"></a>
			<div class="desc">
				<h3>{{name}}</h3>
				<div>
                    <span class="promo-price">
                    <span class="symbol">¥</span>
                    <span>{{price}}</span>
                    </span>
				</div>
			</div>
		</div>
		{{/each}}
	</div>
	{{/each}}
</script>