<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <?php include_http_metas() ?>
 <?php include_metas() ?>
 <?php include_stylesheets() ?>
	<!--#include virtual="/global_navigator/utf8/css.html" --> 
 <script type="text/javascript" src="http://b1.hoopchina.com.cn/common/jquery-1.6.js"></script>
 <?php include_title() ?>
 <?php include_javascripts() ?>
</head>
<body>
<!--#include virtual="/global_navigator/utf8/shihuo/level_1.html" -->    
<?php echo $sf_content ?>
<div class="clear"></div>

<?php
    $rout_name = sfContext::getInstance()->getRouting()->getCurrentRouteName();
    if($rout_name == 'homepage')
    {
        ?>
        <!--#include virtual="/global_navigator/utf8/shihuo/footer-main.html" -->  
        <?php
    }else{
        ?>
        <!--#include virtual="/global_navigator/utf8/footer.html" -->  
        <?php
    }
    ?>
<?php include_javascripts() ?>
<script>
_common.init({project:"shihuo"});
</script>
</body>
</html>
