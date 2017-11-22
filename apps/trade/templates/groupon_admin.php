<?php
$redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
$hupu_uid = sfContext::getInstance()->getUser()->getAttribute('uid');
$key = 'shihuo_groupon_notice_time_'.$hupu_uid;
$isNew = false;
$visit_time = $redis->get($key);
$end_time = $redis->get('shihuo_groupon_notice_endtime');

if(!empty($end_time) && (empty($visit_time) || $end_time>$visit_time))
{
    $isNew = true;
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<?php include_http_metas() ?>
	<?php include_title() ?>
	<?php include_metas() ?>
	<?php include_stylesheets() ?>
	<script type="text/javascript" src="http://b1.hoopchina.com.cn/common/jquery-1.8.js"></script>
</head>
<body>
<style>
    .msg-badge {
        position: absolute;
        top: -10px;
        right: -5px;
        width: 12px;
        height: 12px;
        color: #fff;
        border-radius: 50%;
        background-color: #ff640f;

    }
</style>
<div class="navs">
	<div class="inner clearfix">
		<div class="area-mian">
			<a href="/groupon_admin"><img src="/images/trade/groupon_admin/logo.png" /></a><a style="position: relative; top:-1px;" href="http://www.shihuo.cn/groupon_admin/treasure" ><img src="http://www.shihuo.cn/images/trade/groupon_admin/icon2.jpg" alt=""/></a>
        </div>
		<div class="area-sub">
			<span class="t1">您好，<?php echo sfContext::getInstance()->getUser()->getAttribute('username')?>，欢迎使用识货团购商家版。</span><span class="t2"><a href="/business/information"><img src="/images/trade/groupon_admin/rrx.jpg" /></a>&nbsp;<a href="/groupon_admin/notice/" style="position:relative"><img src="/images/trade/groupon_admin/mail.jpg" /><?php if($isNew === true){?><span class="msg-badge"></span><?php }?></a>&nbsp;<a href="http://www.shihuo.cn"><img src="/images/trade/groupon_admin/out.jpg" width="30" /></a></span>
		</div>
	</div>
</div>
<?php echo $sf_content ?>
<?php include_javascripts() ?>
</body>
</html>
