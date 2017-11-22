<div id="shihuo-nav-area" style="background: #fff;">
<div class="logos-box clearfix">
<div class="logo">
<a href="http://www.shihuo.cn#qk=daohang&amp;order=10"><img src="http://www.shihuo.cn/images/trade/logo_sh.png"></a>
</div>
<div class="icon-link">
<a target="_blank" href="http://www.shihuo.cn/mobile#qk=daohang&amp;order=9" class="ph"></a><s></s>
<a target="_blank" href="http://weibo.com/hupushihuo" class="we"></a><s></s>
<a target="_blank" href="http://www.shihuo.cn/youhui/75447.html" class="xi"></a>
</div>
<div class="search clearfix">
<div class="input">
<input type="text" name="w" autocomplete="off" onblur="this.style.color='#333';" onfocus="this.style.color='#333';" value="" placeholder="输入海外商品链接或关键词，点击搜索一键购！" id="submit_nav">
</div>
<div class="shihuo-nav-shsug">
<ul id="shihuo-nav-shsug-ul">
</ul>
</div>
<div class="loading">
正在努力加载...<div class="loadingbar"><i></i></div>
</div>
<div id="seach_sub" class="btn">搜索</div>
<div id="cancelBtn" class="btn">取消</div>
<div class="fade-bg1"></div>
<div class="tips-bg1"><s></s>此商品暂不代购哦~</div>
<div class="tags">
<a href="http://www.shihuo.cn/search?keywords=双11预售">双11预售</a>
<a href="http://www.shihuo.cn/search?keywords=耐克">耐克</a>
<a href="http://www.shihuo.cn/search?keywords=篮球鞋">篮球鞋</a>
<a href="http://www.shihuo.cn/search?keywords=亚瑟士">亚瑟士</a>
<a href="http://www.shihuo.cn/search?keywords=New Balance">New Balance</a>
<a href="http://www.shihuo.cn/search?keywords=李维斯">李维斯</a>
<a href="http://www.shihuo.cn/search?keywords=AJ">AJ</a>
<a href="http://www.shihuo.cn/search?keywords=李宁">李宁</a>
<a style="color:#ad0007;float:right;padding:0px;margin:0px;" href="http://www.shihuo.cn/shihuo/haitaogou" target="_blank">一键购教程</a>
</div>
</div>
<div class="imgs fr"><img alt="" src="http://www.shihuo.cn/images/trade/pic.png?v=20150901"></div>
</div>
<div class="nav-area">
<div class="area-min clearfix">
<ul class="menu_nav">
<li>
<a  href="http://www.shihuo.cn#qk=daohang&amp;order=1">首页</a><s></s>
</li>
<li>
<a href="http://www.shihuo.cn/haitao#qk=daohang&amp;order=2">海淘</a><s></s>
</li>
<li>
<a href="http://www.shihuo.cn/shoe#qk=daohang&amp;order=3">运动鞋</a><s></s>
</li>
<li>
<a href="http://www.shihuo.cn/tuangou#qk=daohang&amp;order=4">团购</a><s></s>
</li>
<li>
<a href="http://www.shihuo.cn/shaiwu#qk=daohang&amp;order=9">晒物</a><s></s>
</li>
<li>
<a href="http://www.shihuo.cn/coupons/quan#qk=daohang&amp;order=6">优惠券</a><s></s>
</li>
<li  class="hot">
<a href="http://www.shihuo.cn/shop#qk=daohang&amp;order=7">推荐店铺</a><s></s>
<span></span>
</li>
<li>
<a href="http://ask.shihuo.cn/explore/#qk=daohang&amp;order=8">问答</a><s></s>
</li>
<li>
<a href="http://www.shihuo.cn/find#qk=daohang&amp;order=5">发现</a><s></s>
</li>
</ul>
<div class="focus_us clearfix">
<div class="focue-inner">
<div class="focus-us-1">
<a class="cart" href="/haitao/cart"><i></i>x<span id="cart_num_nva">{%$_User.cart_number|f_escape_xml%}</span></a>
</div>
<div class="focus-us-2">
{%if ($_User.notice_number>0)%}<div class="sj-box"><span class="num">{%$_User.cart_number|f_escape_xml%}</span><div class="sj"></div></div>{%/if%}
<a class="notice" href="/message"></a>
</div>
{%if (isset($_User.uid)&&!empty($_User.username))%}
<div class="name-show" id="ucenter-tips">
{%$_User.username|f_escape_xml%}<s></s>
<div class="name-show-list" style="display: none;">
<a class="list-li" href="http://www.shihuo.cn/ucenter" target="_blank" _hover-ignore="1"><span class="i1"></span>我的识货</a>
<a class="list-li" href="http://www.shihuo.cn/submit" target="_blank" _hover-ignore="1"><span class="i2"></span>我要爆料</a>
<a class="list-li" href="http://passport.hupu.com/logout" _hover-ignore="1"><span class="i3"></span>退出</a>
</div>
</div>
{%else%}
<div class="lo">
<a href="http://passport.shihuo.cn/login?project=shihuo&amp;from=pc">登录</a><s>|</s><a href="http://passport.shihuo.cn/register?project=shihuo&amp;from=pc">注册</a>
</div>{%/if%}
</div>
</div>
</div>
<div class="nav-shihuo-area"></div>
</div>
<script src="http://kaluli.hoopchina.com.cn/js/trade/search.js"></script>
</div>
<a href="javascript:void(0);" onclick="window.scrollTo(0,0);" class="returnTop" id="returnTop">返回顶部</a>
<script type="text/javascript">
     $("#ucenter-tips").hover(function(){
            $(this).find(".name-show-list").show();
     },function(){
           $(this).find(".name-show-list").hide();
     });
    var returnTop = {
        init:function(){
            this.returnTop = $("#returnTop");
            this.returnTop.hide();
            var that = this;
            $(window).bind("scroll",function(){
                var w_t = getW().s;
                w_t>800?that.returnTop.fadeIn():that.returnTop.fadeOut(); //返回按钮
            });
        }
    };
    returnTop.init();
    //获取窗口高宽
    function getW() {
      var client_h, client_w, scrollTop;
      client_h = document.documentElement.clientHeight || document.body.clientHeight;
      client_w = document.documentElement.clientWidth || document.body.clientWidth;
      screen_h = document.documentElement.scrollHeight || document.body.scrollHeight;
      scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
      return o = {
        w: client_w,
        h: client_h,
        s: scrollTop,
        s_h: screen_h
      };
    }
</script>