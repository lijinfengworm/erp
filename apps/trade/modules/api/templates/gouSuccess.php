<div class="gou-bgs" style="margin-top:-20px;">	
	<div class="shihuo-content-wrap">
          <div class="seach-buy">
          	   <div class="h2">识货海淘 - 值得信赖的海外商品购物网站</div>
          	   <div class="clearfix">
          	   	    <input class="seach-txt" type="text" value="输入海外商品链接，点击搜索即可直接通过识货购买" /><a href="javascript:void(0);" class="submit">立即购买</a>
                    <div class="loding-img">
                        <img src="/images/trade/haitao/gif.gif" />
                    </div>
                    <div class="fade-bg"></div>
                    <div class="tips-bg"><s></s>哎呀，此商品暂不开放代购</div>
          	   </div>
          	   <div class="tips">
          	   	  <s>目前支持：</s><a href="http://go.shihuo.cn/u?url=http://www.amazon.com" style="color:white" target="_blank">美国亚马逊平台</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://go.shihuo.cn/u?url=http://www.6pm.com/" style="color:white" target="_blank">6PM</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://store.nba.com/" style="color:white" target="_blank">Nbastore</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<s>即将入驻商家：</s>  Levi's&nbsp;&nbsp&nbsp;&nbspVitacost&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://www.shihuo.cn/shihuo/haitaogou" target="_blank" style="color:white">一键购教程</a>
          	   </div>
          </div>
          <div class="pople">
          	  <i></i>截止当前已有 <s><?php echo $useNum?></s> 人使用
          </div>
          <div class="hot-goods">
          	   <div class="title">热门代购商品：</div>
          	   <div class="box clearfix">
          	   	   <div class="left" id="btn_1"></div>
          	   	   <div class="center">
          	   	   	   <ul class="clearfix" id="hot_goods_ul">
                           <?php foreach($daigou_arr as $k=>$v):?>
          	   	   	   	   <li>
          	   	   	   	   	    <a href="http://www.shihuo.cn/haitao/buy/<?php echo $v['id']?>-<?php echo $v['goods_id']?>.html"><img src="<?php echo $v['img_path']?>" /></a>
          	   	   	   	   	    <div class="price">￥<?php echo $v['price']?></div>
          	   	   	   	   </li>
          	   	   	   	  <?php
                            endforeach;
                          ?>
          	   	   	   </ul>
          	   	   </div>
          	   	   <div class="right" id="btn_2"></div>
          	   </div>
          </div>
	</div>
</div>	
<script type="text/javascript">

$(function(){
  slid.init();
  gotoLink.init();
});

var gotoLink = {
    lodingJson:false,
    init:function(){
        this.binFun();
    },
    binFun:function(){
        var that = this;
        $(".submit").click(function(){
            __dace.sendEvent('shihuo_yijiangou_gou_index');
             var val = $(".seach-txt").val(),
                 $this = $(this);
             if($.trim(val) != "" && $.trim(val) != "输入海外商品链接，点击搜索即可直接通过识货购买"){
                  if(that.lodingJson){
                      return false;
                   }
                   that.lodingJson = true;
                  $(".loding-img").show();
                  $.ajax({
                     type: "POST",
                     url: "http://www.shihuo.cn/haitao/purchase",
                     data: "url="+encodeURIComponent(val),
                     dataType:"json",
                     success: function(data){
                       if(data.status*1 == 0){
                            location.href = data.data.buy_url;
                            that.lodingJson = false;
                        }else{
                            $(".fade-bg,.tips-bg").show();
                            setTimeout(function(){
                               $(".fade-bg,.tips-bg").hide();
                               that.lodingJson = false;
                            },3000);
                        }
                       $(".loding-img").hide();
                     }
                  });
             }
        });

        $(".seach-txt").focus(function(){
           if($.trim($(this).val()) == "输入海外商品链接，点击搜索即可直接通过识货购买"){
               $(this).val("");
           }
        });

        $(".seach-txt").blur(function(){
           if($.trim($(this).val()) == ""){
               $(this).val("输入海外商品链接，点击搜索即可直接通过识货购买");
           }
        });
    }
}


var slid = {
     list:0,
     width:157,
     init:function(){
          var w = $("#hot_goods_ul").find("li").length * this.width;
          $("#hot_goods_ul").width(w);
          this.bindFun();
     },
     bindFun:function(){
          var that = this,
              all = $("#hot_goods_ul").find("li").length%5 + parseInt($("#hot_goods_ul").find("li").length/5);
         $("#btn_2").click(function(){
            if(that.list-1 > -all){
               that.list -= 1;
                  $("#hot_goods_ul").animate({
                      left:that.width*that.list*5
                  });
            }   
         }); 

         $("#btn_1").click(function(){
           if(that.list < 0){
              that.list += 1;
             $("#hot_goods_ul").animate({
                 left:that.width*that.list*5
             }); 
           }
         });
     }
}
   
</script>