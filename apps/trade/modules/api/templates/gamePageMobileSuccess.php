<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> 识货 - 天猫年中大促 </title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="format-detection" content="telephone=no" />

	<link rel="stylesheet" href="http://kaluli.hoopchina.com.cn/css/trade/1111/mobile/style.css?v=2014110401">
</head>
<body>
<input type="hidden" id="uid" value="<?php echo $uid ? $uid : ''; ?>">
<input type="hidden" id="isOver" value="<?php echo $isOver; ?>">
<input type="hidden" id="chance" value="<?php echo $chanceInfo; ?>">
<input type="hidden" id="temp" value="<?php echo $temp?>"/>
<input type="hidden" id="slide-index" value="0"/>
<header>
	<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/header.png?s=618">
</header>
<script>
	window.onerror = function(msg, url, line) {
		alert("ERROR: " + msg + "\n" + url + ":" + line);
		return true;
	}
</script>
<div class="container">
<section class="section-1">
	<h2>
		奖品预告
	</h2>
	<div class="section-1-body">
		<div class="slider">
			<div class="scroller">
				<ul class="slide" id="js-slide">
					<li class="slide-item">
						<!-- Day1 -->
						<div class="day day1">
							<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/item0.png?s=618">
						</div>
					</li>

					<li class="slide-item">
						<!-- Day1 -->
						<div class="day day1">
							<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/item1.png?s=618">
						</div>
					</li>
				</ul>
			</div>

			<div class="slider-next" id="js-slider-next">
				<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/arrow-right.png?s=618">
			</div>
		</div>
</section>

<section class="section-2 mt-12">
	<h2 class="section-2-title">
		活动规则
	</h2>
	<div class="section-2-body">
		<img src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/bg-section-2.png?s=618">
		<ul class="section-2-desc">
			<li>1. 喜迎 618，识货推出翻牌游戏，high“翻”618</li>
			<li>2. 每场用户都有最高18次翻牌机会（PC5次，分享5次，app内8次）</li>
			<li>3. 场次开始后，你可以在以下的9张牌中任意翻3张牌，翻出的牌价格总和越低，你的排名越靠前，相同价格情况下，优先翻出价格的用户排名靠前</li>
			<li>4. 奖品将在活动结束后发放，活动最终解释权归识货所有</li>
		</ul>
	</div>
</section>

<?php if($isLogin): ?>
	<section class="section-3 mt-12">
		<h2 class="section-3-title">
			用户信息
		</h2>
		<div class="section-3-body">
			<div class="section-3-row clearfix">
				<div class="col-6">
					<label>识货账号：</label>
					<span class="username"><?php echo $uname ?></span>
				</div>

				<div class="col-6">
					<label>当前最低价总和：</label>
					<span id="js-my-price"><?php echo $dayRoundUserLowScore ? $dayRoundUserLowScore : '/'; ?></span>
				</div>
			</div>

			<div class="section-3-row clearfix">
				<div class="col-6">
					<label>当前排名：</label>
					<span id="js-my-rank"><?php echo $myRank ? $myRank : '/'; ?></span>
				</div>

				<div class="col-6">
					<label>剩余翻牌次数：</label>
					<span id="js-my-chance"><?php echo $chanceInfo ?></span>
				</div>
			</div>

		</div>
	</section>
<?php else: ?>

	<section class="section-3 mt-12 login">
		<h2 class="section-3-title">
			登陆后查看用户信息
		</h2>
		<div class="section-3-body">
			<div class="section-3-row">
				<div class="login-inner clearfix">
                    <?php if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'shihuo') && strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'iphone')):?>
                        <a class="btn pull-left" href="http://passport.shihuo.cn/m/login">登录</a>
                        <a class="btn pull-right" href="http://passport.shihuo.cn/m/register">注册</a>
				    <?php else:?>
                        <a class="btn pull-left" href="http://passport.hupu.com/m/2?from=m&project=shihuo&appid=10017<?php echo $jumpurl ? '&jumpurl=' . $jumpurl : ''; ?>">登录</a>
                        <a class="btn pull-right" href="http://passport.hupu.com/m/2/register?from=m&project=shihuo&appid=10017<?php echo $jumpurl ? '&jumpurl=' . $jumpurl : ''; ?>r">注册</a>
                    <?php endif;?>
                </div>
			</div>
		</div>
	</section>
<?php endif; ?>

<section class="section section-4 mt-12 clearfix">
	<div class="row section-4-row clearfix">
		<div class="col-4 gutter-8">
			<div class="flip-container">
				<div class="flipper">
					<div class="front">
						<img data-src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/t-shirt.png?s=618" src="" data-id="0">
					</div>
					<div class="back">

					</div>
				</div>
			</div>
		</div>
		<div class="col-4 gutter-8">
			<div class="flip-container">
				<div class="flipper">
					<div class="front">
						<img data-src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/pants.png?s=618" src="" data-id="1">
					</div>
					<div class="back">

					</div>
				</div>
			</div>
		</div>
		<div class="col-4 gutter-8">
			<div class="flip-container">
				<div class="flipper">
					<div class="front">
						<img data-src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/shoes.png?s=618" src="" data-id="2">
					</div>
					<div class="back">

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row section-4-row mt-8 clearfix">
		<div class="col-4 gutter-8">
			<div class="flip-container">
				<div class="flipper">
					<div class="front">
						<img data-src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/pants.png?s=618" src="" data-id="3">
					</div>
					<div class="back">

					</div>
				</div>
			</div>
		</div>
		<div class="col-4 gutter-8">
			<div class="flip-container">
				<div class="flipper">
					<div class="front">
						<img data-src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/shoes.png?s=618" src="" data-id="4">
					</div>
					<div class="back">

					</div>
				</div>
			</div>
		</div>
		<div class="col-4 gutter-8">
			<div class="flip-container">
				<div class="flipper">
					<div class="front">
						<img data-src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/t-shirt.png?s=618" src="" data-id="5">
					</div>
					<div class="back">

					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row section-4-row mt-8 clearfix">
		<div class="col-4 gutter-8">
			<div class="flip-container">
				<div class="flipper">
					<div class="front">
						<img data-src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/shoes.png?s=618" src="" data-id="6">
					</div>
					<div class="back">

					</div>
				</div>
			</div>
		</div>
		<div class="col-4 gutter-8">
			<div class="flip-container">
				<div class="flipper">
					<div class="front">
						<img data-src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/t-shirt.png?s=618" src="" data-id="7">
					</div>
					<div class="back">

					</div>
				</div>
			</div>
		</div>
		<div class="col-4 gutter-8">
			<div class="flip-container">
				<div class="flipper">
					<div class="front">
						<img data-src="http://kaluli.hoopchina.com.cn/images/trade/1111/mobile/pants.png?s=618" src="" data-id="8">
					</div>
					<div class="back">

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<input type="hidden" id="leftTime" value="<?php echo time()+$timeLeft; ?>">
<section class="section-5 mt-12">
	<h2>
		距离本场结束剩余
					<span class="timeleft-content" id="js-timeleft-content">
						<span id="js-hour-left">0</span>小时
						<span id="js-minute-left">0</span>分钟
						<span id="js-second-left">0</span>秒
					</span>
	</h2>

	<table>
		<thead>
		<tr>
			<th>排名</th>
			<th>账号ID</th>
			<th>最低价格</th>
			<th>奖品</th>
		</tr>
		</thead>

		<tbody id="js-rank">
		<tr class="tr-me <?php if(!$myRank) echo 'hidden'; ?>">
			<td><?php echo $myRank; ?></td>
			<td><?php echo $uname; ?></td>
			<td><?php echo $dayRoundUserLowScore; ?></td>
			<td><?php echo $myPresent; ?></td>
		</tr>
		<?php  if(isset($rankInfo['totalRank']))
			foreach($rankInfo['totalRank'] as $r_k => $r_v): ?>
				<tr class="<?php if($r_k%2==1) echo 'tr-strip'; ?>">
					<td style="font-size: 18px;"><?php echo $r_k ?></td>
					<td><?php echo $r_v['uname'] ?></td>
					<td><?php echo $r_v['score'] ?></td>
					<td><?php echo $r_v['present'] ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</section>
</div>

<div class="modal" id="js-modal">
	<div class="modal-body">
		<p >
			本次翻牌价格总和
			<span id="js-price-sum">0.00元</span>
		</p>
		<p>
			剩余<span class="chance-num" id="js-chance-num"><?php echo $chanceInfo; ?></span>次翻牌机会
		</p>
	</div>
	<div class="modal-footer">
		<a class="btn-modal" id="js-play-again" href="javascript:void(0);">再玩一次</a>
		<a class="btn-modal" id="js-confirm" href="javascript:void(0);">确定</a>
	</div>
</div>
<div class="overlay" id="js-overlay"></div>

<div id="getting-started"></div>


<!-- 纸牌模板 

-->
<script id="card-back-tpl" type="text/x-handlebars-template">
	<a href="{{link}}" target="_blank">
		<img class="back-img" src="{{pic}}">
	</a>
	<div class="desc">
      <span class="price">
        <span class="symbol">¥</span>
        <span class="num">{{price}}</span>
      </span>
	</div>
</script>

<script id="rank-tpl" type="text/x-handlebars-template">
	<tr class="tr-me">
		<td>{{myRank}}</td>
		<td>{{myId}}</td>
		<td>{{myLowScore}}</td>
		<td>{{myPresent}}</td>
	</tr>
	{{#each top3}}
	<tr class="{{cls}} bold">
		<td style="font-size: 18px;">{{index}}</td>
		<td>{{uname}}</td>
		<td>{{score}}</td>
		<td>{{present}}</td>
	</tr>
	{{/each}}

	{{#each totalRank}}
	<tr class="{{cls}}">
		<td style="font-size: 18px;"> {{index}} </td>
		<td>{{uname}}</td>
		<td>{{score}}</td>
		<td>{{present}}</td>
	</tr>
	{{/each}}
</script>

<script src="http://kaluli.hoopchina.com.cn/js/trade/1111/mobile/jquery-2.1.1.min.js" onload=""></script>
<script src="http://kaluli.hoopchina.com.cn/js/trade/1111/mobile/jquery.countdown.min.js" onload=""></script>
<script src="http://kaluli.hoopchina.com.cn/js/trade/1111/mobile/handlebars-v2.0.0.js" onload=""></script>
<script src="http://kaluli.hoopchina.com.cn/js/trade/1111/mobile/double11.js?20141107" onload=""></script>