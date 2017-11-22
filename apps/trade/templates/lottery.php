<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <?php include_http_metas() ?>
 <?php include_metas() ?>
 <?php include_stylesheets() ?>
	<!--#include virtual="/global_navigator/utf8/css.html" --> 
 <script type="text/javascript" src="http://b3.hoopchina.com.cn/common/jquery-1.8.js"></script>
 <?php include_title() ?>
</head>
<body>
    <!--topbarNav star-->
                        <div id="hp-topbarNav">
                            <div class="hp-topbarNav-bd">
                                <ul class="hp-quickNav">
                                    <li class="mobileWeb"><a href="http://m.hupu.com/" target="_blank"><i class="ico-mobile"></i>手机虎扑</a></li>
                                    <li class="line">|</li>
                                    <li class="mobileclientDown"><a class="red" href="http://mobile.hupu.com/" target="_blank">虎扑手机客户端</a></li>
                                    <li class="line">|</li>
                                    <li class="hp-dropDownMenu topFollowBlog">
                                        <a href="javascript:void(0)" class="hp-set">关注虎扑<s class="setArrow"></s></a>
                                        <div class="hp-drapDown followLayer">
                                            <a class="weibo" target="_blank" rel="nofollow" href="http://e.weibo.com/liangle4u"><i class="hp-ico-weibo"></i>新浪微博</a>
                                            <a class="qq" target="_blank" rel="nofollow" href="http://e.t.qq.com/the_real_hoopchina"><i class="hp-ico-qq"></i>腾讯微博</a>
                                            <a class="renren" target="_blank" rel="nofollow" href="http://page.renren.com/699131720"><i class="hp-ico-renren"></i>人人网</a>
                                            <a class="qzone" target="_blank" rel="nofollow" href="http://user.qzone.qq.com/1624355655"><i class="hp-ico-qzone"></i>QQ空间</a>
                                        </div>
                                    </li>
                                </ul>
                                <div class="hp-topLogin-info"></div>
                            </div>
                        </div>
                        <!--topbarNav ent-->
                           
<?php echo $sf_content ?>
<div class="clear"></div>
<script>
_common.init({project:"nba"});
</script>
<script src="http://www.shihuo.cn/js/trade/lottery.js" type="text/javascript"></script>
</body>
</html>
