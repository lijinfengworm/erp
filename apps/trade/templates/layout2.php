<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
 <?php //echo $_SERVER['REQUEST_URI'];die; ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <?php include_http_metas() ?>
 <?php include_title() ?>
 <?php include_metas() ?>
 <?php include_stylesheets() ?>
 <?php include_partial('shihuo/sina_zan_meta'); //新浪赞组件的内容设置?>
    <script type="text/javascript">
    <?php $routname = sfContext::getInstance()->getRouting()->getCurrentRouteName();?>
    <?php if (preg_match("/\/haitao/",$_SERVER['REQUEST_URI'])):?>
        var __daceDataNameOfChannel = 'sh_haitao';
    <?php elseif (preg_match("/\/find\/1-8/",$_SERVER['REQUEST_URI']) || preg_match("/\/shoe/",$_SERVER['REQUEST_URI'])):?>
        var __daceDataNameOfChannel = 'sh_xie';
    <?php elseif (preg_match("/\/find/",$_SERVER['REQUEST_URI'])):?>
        var __daceDataNameOfChannel = 'sh_faxian';
    <?php elseif (preg_match("/\/detail\/(\d+).html/",$_SERVER['REQUEST_URI'])):?>
        <?php include_partial('all/shiHuodaceStatistic'); //dace 判断是运动鞋还是其他?>
    <?php elseif ($routname == 'collection_list'):?>
        var __daceDataNameOfChannel = 'sh_collection';
    <?php elseif (preg_match("/\/tuangou/",$_SERVER['REQUEST_URI']) || preg_match("/\/t-*/",$_SERVER['REQUEST_URI'])):?>
        var __daceDataNameOfChannel = 'sh_tuangou';
    <?php elseif (preg_match("/\/shop/",$_SERVER['REQUEST_URI'])):?>
        var __daceDataNameOfChannel = 'sh_dianpu';
    <?php elseif (preg_match("/\/youhui\/(\d+).html/",$_SERVER['REQUEST_URI'])):?>
        <?php include_partial('shihuo/shiHuodaceStatistic'); //dace 判断是海淘还是优惠?>
    <?php elseif ($routname == 'shihuo_homepage' || preg_match("/\/guonei/",$_SERVER['REQUEST_URI']) || preg_match("/\/(\d+)/",$_SERVER['REQUEST_URI'])):?>
        var __daceDataNameOfChannel = 'sh_home';
    <?php endif;?>
    </script>
	<!--#include virtual="/global_navigator/utf8/shihuo/css.html" --> 
 <script type="text/javascript" src="http://b1.hoopchina.com.cn/common/jquery-1.8.js"></script>
</head>
<body>

    <?php

        $redis = sfContext::getInstance()->getDatabaseConnection('kaluliRedis');
        $this->categoryPrifixUrl =  "http://www.shihuo.cn/youhui/";
        $categoryKey = "trade.index.categoryKey.".date("Ymd");
        $categoryJson =  $redis->get($categoryKey);
        if($categoryJson)
        {
            $category = json_decode($categoryJson,true);
        }else{

            $menuTable   = TrdMenuTable::getInstance();
            $root_menus = $menuTable->getRootMenu(1,"");

            $category =array();
            $roots     = array();
            $childrens = array();
            foreach($root_menus as $k=>$root) {

                $roots[$root->getId()] = $root->getName();
                $children_menus = $menuTable->getChildrenMenu(1,$root->getId());
                if (!empty($children_menus)){
                    foreach($children_menus as $kk=>$children) {
                        $childrennode = array();
                        $childrennode['id']     = $children->getId();
                        $childrennode['name']   = $children->getName();
                        $childrennode['rootid'] = $children->getRootId();
                        $childrens[$children->getRootId()][] = $childrennode;
                    }
                } else {
                    $childrens[$children->getRootId()] = array();
                }
            }

            $category['roots']     = $roots;
            $category['childrens'] = $childrens;
            $redis->setex($categoryKey,60*60,json_encode($category));
        }

        //$this->category   =  $category;
    ?>

    <div id="hp-topbarNav">
        <div class="hp-topbarNav-bd clearfix">
            <ul class="hp-quickNav">
                <li class="hp-dropDownMenu shihuo-quickNav-link">
                    <a href="javascript:void(0)" class="hp-set">虎扑旗下网站<s class="setArrow"></s></a>
                    <div class="hp-drapDown">
                        <a href="http://nba.hupu.com">虎扑篮球</a>
                        <a href="http://soccer.hupu.com">虎扑足球</a>
                        <a href="http://zb.hupu.com">虎扑装备</a>
                        <a href="http://bbs.hupu.com/bxj">步行街</a>
                    </div>
                </li>
            </ul>
            <div class="hp-topLogin-info"></div>
        </div>
    </div>

    <div class="logos-box clearfix">
        <div class="log">
            <img src="/images/trade/shihuologo.png" />
        </div>
        <div class="seachs clearfix">
            <div class="tags">
                <span id="tag_font" urls="http://www.shihuo.cn/?w=">优惠信息</span><span id="seach_s"><s></s></span>
                <div class="show_list_area" style="display:none;">
                    <a href="javascript:void(0);" urls="http://www.shihuo.cn/haitao?w=">海淘信息</a>
                    <a href="javascript:void(0);" urls="http://www.shihuo.cn/find?w=">发现好货</a>
                    <a href="javascript:void(0);" urls="http://www.shihuo.cn/find/1-8?w=">运动鞋</a>
                </div>
            </div>
            <div class="input">
                <input id="submit_nav" type="text" value="请输入产品名或品牌名" onfocus="if(this.value=='请输入产品名或品牌名')this.value='';this.style.color='#676767';" onblur="if(this.value=='')this.value='请输入产品名或品牌名';this.style.color='#b7b7b7';" autocomplete="off" name="w" />
            </div>
            <div class="btn" id="seach_sub">搜索</div>
            <script type="text/javascript">
                    !(function($){
                         var listObj = $(".show_list_area"),
                             listObj2 = $("#tag_font");
                         $("#seach_s").click(function(){
                              listObj.show();
                              return false;
                         });

                         listObj.find("a").live("click",function(){
                            var str = '<a href="javascript:void(0);" urls="'+listObj2.attr("urls")+'">'+listObj2.html()+'</a>';
                            listObj.prepend(str);
                            listObj2.html($(this).html());
                            listObj2.attr("urls",$(this).attr("urls"));
                            $(this).remove();
                            listObj.hide();
                         });

                          $("#seach_sub").click(function(){
                              location.href = listObj2.attr("urls")+$("#submit_nav").val();
                          });

                          $(window).click(function(){
                               listObj.hide();
                          }); 
                           var seach_layer = {
                           init:function(){
                               this.bindFun();
                           },
                           bindFun:function(){
                               var that = this,
                                   submit_nav = $("#submit_nav");
                                   submit_nav.keyup(function(event){
                                      if (event.keyCode == 13) {  //判断是否单击的enter按键(回车键)
                                          location.href = listObj2.attr("urls")+submit_nav.val();
                                          return false;
                                      }
                                    });
                               $(window).click(function(){
                                  that.obj.hide();
                               });
                           }
                       }
                       seach_layer.init();
                    })(jQuery);
            </script>
        </div>
        <div class="imgs fr"><img src="/images/trade/pic.png" alt="" /></div>
    </div>


    <div class="nav-area">
        <div class="area-min">
            <ul class="menu_nav">
                <li>
                    <a target="_blank" href="http://www.shihuo.cn/shihuo/newIndex#qk=daohang">首页</a><s></s>
                </li>
                <li>
                    <a target="_blank" href="http://www.shihuo.cn/guonei#qk=daohang">优惠</a><s></s>
                </li>
                <li>
                    <a target="_blank" href="http://www.shihuo.cn/haitao#qk=daohang">海淘专区</a><s></s>
                </li>
                <li>
                    <a target="_blank" href="http://www.shihuo.cn/find#qk=daohang">发现好货</a><s></s>
                </li>
                <li>
                    <a target="_blank" href="http://www.shihuo.cn/shoe#qk=daohang">运动鞋</a><s></s>
                </li>
                <li>
                    <a target="_blank" href="http://www.shihuo.cn/tuangou#qk=daohang">团购</a><s></s>
                </li>
                <li>
                    <a target="_blank" href="http://www.shihuo.cn/shop#qk=daohang">推荐店铺</a><s></s>
                </li>
                <li>
                    <a target="_blank" href="http://ask.shihuo.cn/explore/#qk=daohang">问答</a>
                </li>
            </ul>
        </div>
    </div>


    <?php echo $sf_content ?>
<div class="clear"></div>
<script>
var homepage_url = "<?php echo url_for("@shihuo_homepage"); ?>";
var submit_url = "<?php echo url_for("@all_url_submit?type=shoe"); ?>";
</script>
<!--#include virtual="/global_navigator/utf8/shihuo/footer-main.html" -->  
<?php include_javascripts() ?>
<script>
_common.init({project:"nba"});
</script>
<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_30089914'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "w.cnzz.com/c.php%3Fid%3D30089914' type='text/javascript'%3E%3C/script%3E"));</script>
<script type="text/javascript" src="http://goto.hupu.com/js/c/77.js"></script> 
</body>
</html>
