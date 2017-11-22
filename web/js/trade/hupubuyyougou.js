$(function(){
   content.init();
   postRight.init();
   buy.init();
   post.init();
   new youhui_project();
   move.init({
      dom:$(".ad_js_scroll"),
      interval:50,
        pixel:1
    });
});

var post = {
    init:function(){
        if($(".huoubuy_goodslist_title").length == 0){
            return false;
        }
        this.po = $(".huoubuy_goodslist_title");
        this.top = this.po.offset().top;
        this.bindFun();
    },
    bindFun:function(){
     var that = this;
     $(window).scroll(function(){
         if(that.getpageScroll() > that.top){
             that.po.addClass("huoubuy_goodslist_title_post");
         }else{
             that.po.removeClass("huoubuy_goodslist_title_post");
         };
     })
    },
    getpageScroll: function() {
        var yScrolltop;
        if (self.pageYOffset) {
            yScrolltop = self.pageYOffset;
        } else if (document.documentElement && document.documentElement.scrollTop) {
            yScrolltop = document.documentElement.scrollTop;
        } else if (document.body) {
            yScrolltop = document.body.scrollTop;
        }
        return yScrolltop;
    }
}

var content = {//倒计时
    init:function(){
        this.li = $(".huoubuy_goodslist_content").find("li");
        this.time = this.li.find(".time");
        this.bindClick();
        this.times();
    },
    bindClick:function(){
         this.li.hover(function(){
             $(this).css("border-color","#9d0009");
         },function(){
             $(this).css("border-color","#eeeeee");
         })
    },
    times:function(){
        var that = this;
        this.time.each(function(i){
            var time = $(this).attr("atr");
            that.SetRemainTime($(this),time*60);
        })
        that.SetRemainTime($(".goods_times"),$(".goods_times").attr("atr")*60);
    },
    SetRemainTime:function (obj,time){//倒计时
        var that = obj;
        var SysSecond = parseInt(time);
        var InterValObj = setInterval(function(){//计算秒 分 时 天
            if (SysSecond > 0) {
                SysSecond = SysSecond - 1;
                var second = Math.floor(SysSecond % 60);
                var minite = Math.floor((SysSecond / 60) % 60);
                var hour = Math.floor((SysSecond / 3600) % 24);
                var day = Math.floor((SysSecond / 3600) / 24);
                that.html("剩余"+day+"天"+hour + "小时" + minite + "分" + second + "秒");

            } else {
                that.html("已到期");
                clearInterval(InterValObj);
            }
        }, 1000)
    }
};

var buy = {
    init:function(){
        this.obj = $(".huoubuy_buy");
        this.bindClick();
    },
    bindClick:function(){
        this.obj.click(function(){
            $.Jui._showMasks(0.5);
            if(!!$.browser.msie && parseInt($.browser.version) <= 6){
                $(".buy_layer").css({
                    "left": (($.Jui._getpageSize()[0] - parseInt($(".buy_layer").width())) / 2)
                }).fadeIn();
            }else{
                $(".buy_layer").css({
                    "left": (($.Jui._getpageSize()[0] - parseInt($(".buy_layer").width())) / 2),
                    "top": (($.Jui._getpageSize()[1] - parseInt($(".buy_layer").height())) / 2)-100
                }).fadeIn();
            }
        })
        $(".buy_layer_title span").click(function(){
            $.Jui._closeMasks();
            $(".buy_layer").fadeOut();
        })
    }
}

var postRight = {//右侧浮动
   init:function(){
       this.a = $(".post_right");
       this.bindClick();
   },
   bindClick:function(){
       var that = this;
       $(window).scroll(function(){
           if($.Jui._getpageScroll() > 200){
               that.a.find(".c_d").css("display","block");
               if(!$.Jui.isie6){
                   that.a.css({
                       marginTop:$.Jui._getpageSize()[1] - 190
                   });
               }
           }else{
               that.a.find(".c_d").hide();
           }
       });
       that.a.find(".c_d").click(function(){
           $('html, body').animate({
               scrollTop:0
           });
       });

       $(".post_erwei .erwei_close").click(function(){
                $(".post_erwei").remove();
       });
   }
};

var move = {
    moveInt:0,
    timeInt:null,
    init:function(o){
      if(o.dom.length > 0){
         var allw = o.dom.outerWidth(),
                w1 = o.dom.find(".title").outerWidth();
                 o.dom.find(".font_txt").css("width",allw-w1-10);
               o.dom.find(".content").css("width",this.checkWidth(o.dom));
             if((allw-w1-10) < this.checkWidth(o.dom)){
              this.getMove(o);
              this.bindFun(o);
             }
      }
    },
    checkWidth:function(o){
      var obj = o.find(".list"),i,allOuterWidth=0;
          obj.each(function(){
               allOuterWidth += $(this).outerWidth()+15;
          });
          return allOuterWidth;
    },
    bindFun:function(o){
       var that = this;
           o.dom.find(".list").hover(function(){
               clearInterval(that.timeInt);
           },function(){
               that.getMove(o);
           }); 
    },
    getMove:function(o){
      var that = this,
          obj = o.dom.find(".list:first"),
          leftValue = obj.outerWidth() + 10;
          that.timeInt = setInterval(function(){
            that.moveInt -= o.pixel;
            if(that.moveInt > -leftValue){
              obj.css("margin-left",that.moveInt);
            }else{
              clearInterval(that.timeInt);
              obj.removeAttr("style");
              o.dom.find(".content").append(obj);
              that.moveInt = 0;
              that.getMove(o);
            }
          },o.interval);
    }
}

function youhui_project(){
   this.youhuiClick();
}

youhui_project.prototype = {
     constructor:youhui_project,
     ajaxLding:false,
     youhuiClick:function(){
          var user_btn = $("#user-btn"),
              show_layer = $(".youhuiquan-show-layer"),
              close = $("#close-youhuiquan"),
              that = this;
          user_btn.click(function(){
              if(that.ajaxLding){
                 return false;
              }
              that.ajaxLding = true;
              that.getJson();
          });

          close.click(function(){
               show_layer.hide();
               $.Jui._closeMasks();
          });
     },
     getJson:function(){
         var show_layer = $(".youhuiquan-show-layer"),
             user_btn = $("#user-btn"),
             that = this;
         $.getJSON("http://www.shihuo.cn/coupons/receive?id=3",function(data){
             switch(data.status*1){
                case 0:
                  $.Jui._showMasks(0.6);
                  show_layer.find(".code").html(data.data.account);
                  show_layer.css({
                    left:$.Jui._position(show_layer)[0],
                    top:$.Jui._position(show_layer)[1] - 100
                  }).show(); 
                  if(data.data.flag == 2){
                    $("#user-btn").addClass("btn-box-2").html("今日已领用").removeAttr("id");
                    user_btn.unbind("click");
                  }
                  if(data.data.flag == 1){
                    $("#user-btn").addClass("btn-box-1").html("已领用完").removeAttr("id");
                    user_btn.unbind("click");
                  }
                 break;
                case 1:
                 commonLogin();
                 break;
                case 2:
                case 3:
                  $("#user-btn").addClass("btn-box-1").html("已领用完").removeAttr("id");
                  user_btn.unbind("click");
                case 4:
                  $("#user-btn").addClass("btn-box-2").html("今日已领用").removeAttr("id");
                  user_btn.unbind("click");
                default:
             }
             that.ajaxLding = false;
         },"json");

         /*
           <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="600" height="360">
                <param name="movie" value="http://player.youku.com/player.php/Type/Folder/Fid/22156881/Ob/1/sid/XNjk5ODcyMjMy/v.swf" />
                <param name="quality" value="high" />
                <param value="never" name="AllowScriptAccess">
                <param value="opaque" name="wmode">
                <param name="wmode" value="transparent" />
                <embed src="http://player.youku.com/player.php/Type/Folder/Fid/22156881/Ob/1/sid/XNjk5ODcyMjMy/v.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent" width="600" height="360"></embed>
            </object>
          */
     }
}

/*animate*/
!(function($){
    $.Jui = $.Jui || {};
    $.extend($.Jui, {
        // version: "1.0",
        _$: function(a, b) {
            a.siblings().removeClass(b);
            a.addClass(b);
        },
        _showMasks:function(a){
            var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:"+$(document).height()+"px; background-color:#000;  z-index:300;'></div>";
            $("body").append(str);
            $(".body-mask").css("opacity",0);
            $(".body-mask").animate({
                "opacity":a?a:"0.8"
            });
        },
        _closeMasks:function(){
           var close = $(".body-mask");
           close.fadeOut(function(){
               close.remove();
           });
        },
        _getpageSize: function() {
            var de = document.documentElement, arrayPageSize,
                    w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth,
                    h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
            arrayPageSize = [w, h]
            return arrayPageSize;
        },
        _position: function(obj) {//计算对象放置在屏幕中间的值   obj:需要计算的对象
            var left = ((this._getpageSize()[0] - parseInt(obj.outerWidth())) / 2);
            var top = ((this._getpageSize()[1] - parseInt(obj.outerHeight())) / 2) + $.Jui._getpageScroll();
            return [left, top];
        },
        _getpageScroll: function() {
            var yScrolltop;
            if (self.pageYOffset) {
                yScrolltop = self.pageYOffset;
            } else if (document.documentElement && document.documentElement.scrollTop) {
                yScrolltop = document.documentElement.scrollTop;
            } else if (document.body) {
                yScrolltop = document.body.scrollTop;
            }
            return yScrolltop;
        },
        isie: !!$.browser.msie,
        isie6: (!!$.browser.msie && parseInt($.browser.version) <= 6)
    });
    var defaults = {css: {"top": 20}, config: {speed: 800,easing: "swing",time:0}},
       textAnimation = function(a) {
        return this.each(function() {
            var $this = $(this),
                    settings = $.extend({}, defaults, a);
            $this.animate(settings.css, settings.config.speed, settings.config.easing, function() {
                !!settings.callback && settings.callback();
            });
        })
    };
    $.fn.textAnimation = textAnimation;
})(jQuery);
