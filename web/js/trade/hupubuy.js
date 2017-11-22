$(function(){
   content.init();
   postRight.init();
   buy.init();
   post.init();
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
                day>=3?that.html("还剩 3 天以上"):that.html("剩余"+day+"天"+hour + "小时" + minite + "分" + second + "秒");


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
                       marginTop:$.Jui._getpageSize()[1] - 210
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
   }
};

/*animate*/
(function($){
    $.Jui = $.Jui || {};
    $.extend($.Jui, {
        // version: "1.0",
        _$: function(a, b) {
            a.siblings().removeClass(b);
            a.addClass(b);
        },
        _showMasks:function(a){
            var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:"+this._getpageSize()[0]+"px; background-color:#000;  z-index:998;'></div>";
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
