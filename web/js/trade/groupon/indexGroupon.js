$(function(){
    $(".J_ui_picSwitch").slide({
        css: {"width": 754, "height": 300},
        config: {"time": 5000, "type": "left", "speed": 600,"button":true,"butArr":".J_ui_picSwitch .J_ui_a li"},
        completes:function(o){//初始化完成执行动作
          $(".J_ui_picSwitch").hover(function(){
               $(".J_ui_butPost_a,.J_ui_butPost_b").show();
          },function(){
               $(".J_ui_butPost_a,.J_ui_butPost_b").hide();
          });
        }
    });
    
    $(".js_time_list2").find("li").hover(function(){
        $(this).addClass('on');
        $(this).css('z-index',10);
    },function(){
        $(this).removeClass('on');
        $(this).css('z-index',1); 
    });
    times.init();
    yugaoList.init();
	goodsList.init();
    postRight.init();  
});
  
var times = {
    init:function(){
       var obj = $(".js_time_list").find(".time"),
           obj2 = $(".js_time_list2").find(".t1"),
           that = this;
       for(var i=0; i<obj.length; i++){
           that.SetRemainTime(obj.eq(i),obj.eq(i).attr("atr"));
       }

       for(var s=0; s<obj2.length; s++){
           that.SetRemainTime(obj2.eq(s),obj2.eq(s).attr("atr"));
       }

       that.SetRemainTime2($("#last-time"),$("#last-time").attr("atr"));
    },
    SetRemainTime:function (obj,time){//倒计时
        var that = obj;
        var SysSecond = parseInt(time);
        var t = setInterval(function(){//计算秒 分 时 天
            if (SysSecond > 0) {
                SysSecond = SysSecond - 1;
                var second = Math.floor(SysSecond % 60);
                var minite = Math.floor((SysSecond / 60) % 60);
                var hour = Math.floor((SysSecond / 3600) % 24);
                var day = Math.floor((SysSecond / 3600) / 24);
                that.first().html("剩余"+day+"天"+hour + "小时" + minite + "分");
            } else {
                that.first().html("已到期");
                clearInterval(t);
            }
        },1000)
    },
    SetRemainTime2:function (obj,time){//倒计时
        var that = obj;
        var SysSecond = parseInt(time);
        var t = setInterval(function(){//计算秒 分 时 天
            if (SysSecond > 0) {
                SysSecond = SysSecond - 1;
                var second = Math.floor(SysSecond % 60);
                var minite = Math.floor((SysSecond / 60) % 60);
                var hour = Math.floor((SysSecond / 3600) % 24);
                var day = Math.floor((SysSecond / 3600) / 24);
                that.first().html("距离开抢还剩<b>"+(hour>9?hour:"0"+hour)+"</b><s>:</s><b>"+(minite>9?minite:"0"+minite)+"</b><s>:</s><b>"+(second>9?second:"0"+second)+"</b>");
            } else {
                that.first().html("已到期");
                clearInterval(t);
            }
        },1000)
    }
}

var goodsList = {
    list:0,
    all:$("#good-ul").find("ul").length-1,
    init:function(){
        this.bindFun();
    },
    bindFun:function(){
        var left = 816,
            that = this;
        $(".prev").click(function(){
            if(that.list>0){
              that.list--; 
               $("#good-ul").animate({
                    left:-that.list*left
                },500); 
                if(that.list == 0){
                    $(this).css("opacity",0.5);
                }
                if(that.list <= that.all){
                    $(".next").css("opacity",1);
                }
            }
        });

        $(".next").click(function(){
            if(that.list<that.all){
               that.list++;
                $("#good-ul").animate({
                    left:-that.list*left
                },500); 
                if(that.list == that.all){
                    $(this).css("opacity",0.5);
                }

                if(that.list > 0){
                    $(".prev").css("opacity",1);
                }
            } 
        });
    }
}


var yugaoList = {
    list:0,
    all:$("#all-ul").find("ul").length-1,
    init:function(){
        this.bindFun();
    },
    bindFun:function(){
        var left = 1080,
            that = this;
        $(".prev-btn").click(function(){
            if(that.list>0){
              that.list--; 
               $("#all-ul").animate({
                    left:-that.list*left
                },500); 
                if(that.list == 0){
                    $(this).css("opacity",0.5);
                }

                if(that.list <= that.all){
                    $(".next-btn").css("opacity",1);
                }
            }
        });

        $(".next-btn").click(function(){
            if(that.list<that.all){
               that.list++;
                $("#all-ul").animate({
                    left:-that.list*left
                },500); 
                if(that.list == that.all){
                    $(this).css("opacity",0.5);
                }

                if(that.list > 0){
                    $(".prev-btn").css("opacity",1);
                }
            } 
        });
    }
}

var postRight = {
    init:function(){
        this.bindFun();
    },
    bindFun:function(){ 
       var post_layer_box = $(".post_layer_box");
           left = $("#getPost").offset().left + $("#getPost").width() + 20;
           post_layer_box.css({
              left:left
           }).show();
        post_layer_box.find("li").click(function(){
            var top = $("#"+$(this).attr("atr")).offset().top;
             $('html, body').animate({
                scrollTop:top
            },500);
        });

        post_layer_box.find("li").hover(function() {
            $(this).addClass('on');
        }, function() {
            $(this).removeClass('on');
        });

        post_layer_box.find(".go-top").click(function(){
             $('html, body').animate({
                scrollTop:0
            },500);
        });
    }
}

!(function($){
  $.Jui = $.Jui || {};
    $.extend($.Jui, {
        // version: "1.0",
        _$: function(a, b) {
            a.siblings().removeClass(b);
            a.addClass(b);
        },
        _showMasks: function(a) {
            var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:" + $(document).height() + "px; background-color:#000;  z-index:91;'></div>";
            $("body").append(str);
            $(".body-mask").css("opacity", 0);
            $(".body-mask").animate({
                "opacity": a ? a : "0.8"
            });
        },
        _closeMasks: function() {
            var close = $(".body-mask");
            close.fadeOut(function() {
                close.remove();
            });
        },
        _getpageSize: function() {
            /*
             height:parseInt($(document).height()),
             width:parseInt($(document).width())
             */
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
        isie6: (!!$.browser.msie && parseInt($.browser.version) <= 6),
        DOC: $(document),
        WIN: $(window),
        HEAD: $(document).find("head"),
        BODY: $(document).find("body")
    });

  function tips(a,arr) {
      return this.each(function() {
          var $this = $(this),
              str = '<div class="tips_layer" style="position: absolute; border-radius:5px; background-color:#ff6600; display:none; z-index:995">\
                <div class="tips-text" style="padding:2px 5px; line-height:18px; color:#fff;">'+a+'</div>\
                <div class="diamond"></div>\
            </div>';
           if($(".tips_layer")){
              $(".tips_layer").remove();
           }
          $(str).appendTo("body");
          var $tips_text = $(".tips-text"),
                  $tips_layer = $(".tips_layer");
          if(arr){
             $tips_layer.css({
                "top": arr.top,
                "left": arr.left
              }).show();
          }else{
            $tips_layer.css({
              "top": $this.offset().top - parseInt($this.height())-10,
              "left": $this.offset().left + parseInt($this.width()/2) - ($tips_layer.outerWidth()/2)
            }).show();
          }
          setTimeout(function(){
             $tips_layer.remove();
          },2000);
      });
  }
  $.fn.tips = tips;
})(jQuery);

/*图片切换效果*/
!(function($) {
    var picScroll = function() {
        var arg = arguments,
            defaults = {// css{盒子的宽高};config{每次滑动/淡进淡出间隔时间time、滑动类型("top/left/fade")、滑动/淡进淡出的速度speed、是否加载左右按钮button}。注：如不自定义参数则采用默认值
                css: {"width": 490, "height": 170},
                config: {"time": 3000, "type": "fade", "speed": 800, "button": false,"butArr":".J_ui_picSwitch .J_ui_a li"},
                before:function(data){//图片切换前执行动作
                },
                after:function(data){//图片切换完成执行动作
                }
            };
        return this.each(function() {
            var $this = $(this),
            $$ = function(a) {
                return $this.find(a)
            },
            animates = {
                list: 0,//当前第几张
                options: ["top", "left", "fade"],//动画类型
                animated:false,
                init: function() {
                    this.arrays = [];//预留参数位置以备用
                    this.arrays[0] = $.extend(true,{}, defaults, arguments[0] || {});//合并自定义参数和默认参数
                    this.ul = $$(".J_ui_post");
                    this.li = $$(".J_ui_post li");
                    this.but = this.arrays[0].config.butArr;
                    if(this.options.index(this.arrays[0].config.type) !== -1){//参数是否正确
                        for (var i = 0; i < this.arrays.length; i++) {//循环 保存参数值
                            switch (typeof this.arrays[i]) {
                                case 'object':
                                    $this.css(this.arrays[i].css);
                                    this.li.css(this.arrays[i].css)
                                    this.returnBefore = this.arrays[i].before;
                                    this.returnAfter = this.arrays[i].after;
                                    break;
                                default:
                            }
                        }
                        this.config("move");//配置开始
                        this.bindFun();//绑定方法
                        if(this.arrays[0].completes){
                           this.arrays[0].completes($this);
                        }
                    }else{//如果参数不正确抛出错误
                        $.error = console.error;
                        $.error("参数格式不正确！");
                    }
                },
                config: function(str) {
                    var that = this,i=0,butArr=that.but.split(","),
                        con = that.arrays[0].config,
                        arr = (con.type == "top" ? ["top"] : ["left"]);
                    if (con.type == "left" || con.type == "top") {//动画类型判断
                        if (con.type == "left") {
                            that.ul.addClass("J_ui_postFloat");
                            that.ul.width($this.width() * that.li.length);//计算图片列表总宽度
                        }
                        if (that.list == that.li.length) {//如果当前图片是第一张从最后循环
                            con.type == "top" ? that.li.first().css(arr[0], that.ul.height()) : that.li.first().css(arr[0], that.ul.width());//给第一张图片的position: relative;赋值以达到无限循环效果
                            that.callback = function() {//滚动完成后的回调函数  给position: relative;值还原为0 同时当前图片的位置是0
                                that.li.first().css(arr[0], 0);
                                that.ul.css(arr[0], 0);
                                that.list = 0;
                            }
                        }
                        if (that.list == -1) {//如果当前图片是最后一张从第一张循环
                            con.type == "top" ? that.li.last().css(arr[0], -that.ul.height()) : that.li.last().css(arr[0], -that.ul.width());//给最后张图片的position: relative;赋值以达到无限循环效果
                            that.callback = function() {//滚动完成后的回调函数
                                that.list = that.li.length - 1;
                                that.li.last().css(arr[0], 0);
                                con.type == "top" ? that.ul.css(arr[0], -parseInt($this.height()) * that.list) : that.ul.css(arr[0], -parseInt($this.width()) * that.list);
                            }
                        }
                        that.scrollA();//配置完成开始滚动
                    } else if (con.type == "fade") {//如果滚动类型为fade
                        if (!that.ul.hasClass("J_ui_postPost")) {
                            that.ul.addClass("J_ui_postPost")
                        }
                        if (that.list == that.li.length) {//如果为最后一张图
                            that.list = 0;
                        }
                        that.fadeFun();//开始淡进淡出动画
                    }

                    for(;i<butArr.length;i++){
                        $(butArr[i]).eq(that.list == that.li.length ? 0 : that.list).siblings().removeClass("on");//按钮样式变化
                        $(butArr[i]).eq(that.list == that.li.length ? 0 : that.list).addClass("on");//按钮样式变化
                    }
                },
                scrollA: function() {//滚动动画
                    this.animated = true;
                    var that = this, textCss,
                            con = that.arrays[0].config;//动画滚动参数获取
                    clearTimeout(that.t);//清除上一次的排队动画
                    that.rerurnFun(0);//滚动开始回调
                    con.type == "top" ? textCss = {"top": -parseInt($this.height()) * that.list} : textCss = {"left": -parseInt($this.width()) * that.list};//获取滚动值
                    that.ul.stop(true).textAnimation({"css": textCss, "config": con, callback: function() {
                            if (that.callback) {//内部回调函数
                                that.callback();
                                that.callback = null;
                            }
                            that.animated = false;
                            that.rerurnFun(1);//滚动结束回调
                        }});
                    that.setTime();//循环滚动
                },
                fadeFun: function() {//淡进淡出动画
                    var that = this;
                    clearTimeout(that.t);//清除上一次的排队动画
                    that.rerurnFun(0);//动画开始回调
                    that.li.css('opacity', 1)
                    that.li.eq(that.list).siblings().stop(true).fadeOut(that.arrays[0].config.speed);
                    that.li.eq(that.list).fadeIn(that.arrays[0].config.speed,function(){
                        that.rerurnFun(1);//动画结束回调
                    });
                   that.setTime();//循环动画
                },
                bindFun: function() {//绑定各种事件
                    var that = this;
                    $(that.but).hover(function() {
                        that.list = $(this).index();
                        that.config("stop");
                        clearTimeout(that.t);
                    },function(){
                        that.setTime();
                    });

                    that.li.hover(function(){
                       clearTimeout(that.t);
                    },function(){
                       that.setTime();
                    });

                    if (that.arrays[0].config.button) {
                        $$(".J_ui_butPost_a").click(function() {
                            if(that.animated){
                                return false;
                            }else{
                              that.list -= 1;
                              that.config("move");
                            }
                        });
                        $$(".J_ui_butPost_b").click(function() {
                            if(that.animated){
                                return false;
                            }else{
                              that.list += 1;
                              that.config("move");
                            }
                        });
                    } else {
                        $$(".J_ui_butPost_b").remove();
                        $$(".J_ui_butPost_a").remove();
                    }
                },
                rerurnFun: function(num) {//判断回调
                    if(num){
                      !!this.returnAfter && this.returnAfter(this.list == this.li.length?0:this.list);
                    }else{
                      !!this.returnBefore && this.returnBefore(this.list == this.li.length?0:this.list);
                    }
                },
                setTime: function() {//循环动画
                    var that = this;
                    that.t = setTimeout(function() {
                        that.list += 1;
                        that.config("move");
                    }, that.arrays[0].config.time);
                }
            }
            animates.init.apply(animates, arg);
        });
    }
    var defaults = {css: {"top": 0}, config: {speed: 800, easing: "swing", time: 0}},
    textAnimation = function(a) {
        return this.each(function() {
            var $this = $(this),
                    settings = $.extend(true,{}, defaults, a);
            $this.animate(settings.css, settings.config.speed, settings.config.easing, function() {
                !!settings.callback && settings.callback();
            });
        })
    };
    $.extend(Array.prototype, {
        /*判断数组中是否包含指定的值*/
        has: function(value) {
            return this.index(value) !== -1;
        },
        /*判断数组中指定值的具体位置*/
        index: function(value) {
            if (this.indexOf) {
                return this.indexOf(value);
            }
            for (var i = 0, l = this.length; i < l; i++) {
                if (value == this[i]) {
                    return i;
                }
            }
            return -1;
        }
    });
    $.fn.extend({
        textAnimation:textAnimation,
        slide: picScroll
    });
})(jQuery);