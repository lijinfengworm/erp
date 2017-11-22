<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8">
    <title>识货预发布环境切换</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no,email=no,address=no">
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">
</head>
<body>
<?php
$host = $_SERVER['HTTP_HOST'];
$prefix = "http://".$host."/trade.php";
?>
<p>
    <br>
<div style="border: 1px solid #337ab7;">
<span id="helpBlock" class="help-block">
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $host;?>当前环境是<font color="red">
<?php
$cookie = isset($_COOKIE['prerelease']) ? $_COOKIE['prerelease'] : '';
if ($cookie){
    echo "预发布deploy";
} else {
    echo "预发布trunk";
}
?></font>
    </span>
<p>
<div class="center-block" style="margin:10px;">
    <a target="_blank" href="<?php echo $prefix;?>/shihuo/prereleaseBeta" class="btn btn-primary btn-lg" role="button">去预发布deploy</a>
    <a target="_blank" href="<?php echo $prefix;?>/shihuo/prereleaseStable" class="btn btn-default btn-lg" role="button">去预发布trunk</a>
</div>

</p>
</div>
</body>
</html>