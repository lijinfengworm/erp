$(function(){
   $(".J_ui_picSwitch").slide({
        css: {"width": 608, "height": 273},
        config: {"time": 5000, "type": "left", "speed": 600,"button":true,"butArr":".J_ui_picSwitch .J_ui_a li"},
        completes:function(o){//初始化完成执行动作
          $(".J_ui_picSwitch").hover(function(){
               $(".J_ui_butPost_a,.J_ui_butPost_b").show();
          },function(){
               $(".J_ui_butPost_a,.J_ui_butPost_b").hide();
          });
        }
    });


    returnTop.init();
   giftsSlide.init();
   timeOut.init();
   getDataList.init();
});

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

//returnTop、意见
var returnTop = {
    init:function(){
        this.returnTop = $("#returnTop");
        var that = this;
        $(window).bind("scroll",function(){
            var w_t = getW().s;
            w_t>600?that.returnTop.fadeIn():that.returnTop.fadeOut(); //返回按钮
        });
        this.bindFun();
    },
    bindFun:function(){
        //意见反馈
        $("#returnTop .show_feedback").click(function(){
                $("#ajaxFeedback").show();
        });
        $("#ajaxFeedback .close").click(function(){
                $("#ajaxFeedback").hide();
        });
        $("#okFeedback .close").click(function(){
                $("#okFeedback").hide();
        });
        $("#ajaxFeedback .submit").click(function(){
            var email = $("#email").val()?" 邮箱:"+$("#email").val():"";
            $("#ajaxFeedback").hide();
            $("#okFeedback").show();
            var _content = $("#feed_content").val()+email,
                _email = 'tuangou@hupu.com';
            $.post("http://www.shihuo.cn/feedback/create",{email: _email,content: _content,type:'shihuo_tuangou'},function(data){
                    if(data.status.code == 200){
                        $("#feed_content").val("");
                        setTimeout(function(){
                            $("#okFeedback").hide();
                        }, 1400);
                        $.post("http://www.shihuo.cn/feedback/sendEmail",{email: _email,content: _content,type:'shihuo_tuangou'});
                    }
            },"json");
        });
        $("#feed_content").on("focus",function(){
            $("#ajaxFeedback .submit").addClass('focus');
        });
    }
};

var giftsSlide = {
    leftObj:808,
    init:function(){
        this.bindFun();
        $("#jinpinUl").css({
            width:$("#jinpinUl li:first").outerWidth() * $("#jinpinUl li").length
        });
    },
    bindFun:function(){
        var list = 0,
            allList = $("#jinpinUl li").length/4,
            that= this,
            animateLoding = false;
        $(".jinpin_js .right-btn").click(function(){
              if(animateLoding){
                  return false;
              }
              animateLoding = true;
              if(-allList+1 < list){
                  list--;
                  $("#jinpinUl").animate({
                       left:that.leftObj * list
                  },500,function(){
                      animateLoding = false;
                  });
              }else{
                  list--;
                  $("#jinpinUl li").eq(0).css("left",$("#jinpinUl").width());
                  $("#jinpinUl li").eq(1).css("left",$("#jinpinUl").width());
                  $("#jinpinUl li").eq(2).css("left",$("#jinpinUl").width());
                  $("#jinpinUl li").eq(3).css("left",$("#jinpinUl").width());
                  $("#jinpinUl").animate({
                       left:that.leftObj * list
                  },500,function(){
                      list  = 0;
                      $("#jinpinUl li,#jinpinUl").css("left",0);
                      animateLoding = false;
                  });
              }
        });

        $(".jinpin_js .left-btn").click(function(){
              if(animateLoding){
                  return false;
              }
              animateLoding = true;
              if(list == 0){
                  list++;

                  $("#jinpinUl li").eq($("#jinpinUl li").length-1).css("left",-$("#jinpinUl").width());
                  $("#jinpinUl li").eq($("#jinpinUl li").length-2).css("left",-$("#jinpinUl").width());
                  $("#jinpinUl li").eq($("#jinpinUl li").length-3).css("left",-$("#jinpinUl").width());
                  $("#jinpinUl li").eq($("#jinpinUl li").length-4).css("left",-$("#jinpinUl").width());
                  $("#jinpinUl").animate({
                       left:that.leftObj * list
                  },500,function(){
                      list  = -($("#jinpinUl li").length/4 - 1);
                      $("#jinpinUl li").css("left",0);
                      $("#jinpinUl").css("left",-($("#jinpinUl").width()-$("#jinpinUl li:last").width())+607);
                      animateLoding = false;
                  });
              }else{
                  list++;
                  $("#jinpinUl").animate({
                       left:that.leftObj * list
                  },500,function(){
                      animateLoding = false;
                  });
              }
        });
    }
}

var timeOut = {
    init:function(){
        var obj = $("#time_out"),
            time = obj.attr("atr"),
            that = this;
        setInterval(function(){
            if(time > 0){
               time-=1;
            }
            obj.find("s").eq(0).html(that.MillisecondToDate(time)[0]);
            obj.find("s").eq(1).html(that.MillisecondToDate(time)[1]);
            obj.find("s").eq(2).html(that.MillisecondToDate(time)[2]);
         },1000);
    },
    MillisecondToDate:function(msd) {
        var theTime = parseInt(msd);// 秒
        var theTime1 = 0;// 分
        var theTime2 = 0;// 小时
        if(theTime > 60) {
            theTime1 = parseInt(theTime/60);
            theTime = parseInt(theTime%60);
                if(theTime1 > 60) {
                    theTime2 = parseInt(theTime1/60);
                    theTime1 = parseInt(theTime1%60);
                }
        }
        var result = [];
        if(theTime2 > 0) {
           result[0] = parseInt(theTime2) < 10?"0"+parseInt(theTime2):parseInt(theTime2);
        }else{
           result[0] = "00"
        }

        if(theTime1 > 0) {
           result[1] = parseInt(theTime1) < 10?"0"+parseInt(theTime1):parseInt(theTime1);
        }else{
           result[1] = "00"
        }

        result[2] = parseInt(theTime)< 10?"0"+parseInt(theTime):parseInt(theTime);

        return result;
    }
}

var getDataList = {
    defaults:{
        page:0,
        brand:"",
        type:[],
        order:"default",
        orderType:"",
        keywords:""
    },
    ajaxLoding:false,
    offSet:null,
    init:function(){
        this.getJson();
        this.bindFun();
    },
    bindFun:function(){
         var typeObj = $("#type_list a"),
             brandObj = $("#brand_list a"),
             orderList = $("#order_list a"),
             that = this;
         function getDataFun(){
            $("#groupon_goods_list").html("");
            $(window).scrollTop($(".goods-screen-box").offset().top);
            $(".none-error").hide();
            that.ajaxLoding = false;
            that.defaults.page = 0;
            that.getJson();
         }
         typeObj.click(function(){
            if($(this).hasClass('on')){
                $(this).removeClass('on');
            }else{
                $(this).addClass('on');
            }
            that.defaults.type = [];
            typeObj.each(function(i){
                if(typeObj.eq(i).hasClass('on')){
                    that.defaults.type.push(typeObj.eq(i).text());
                }
            });
            that.defaults.page = 0;
            getDataFun();
         });

         brandObj.click(function(){
            if($(this).hasClass('on')){
                $(this).removeClass('on');
                that.defaults.brand = "";
            }else{
                $(this).addClass('on');
                that.defaults.brand = $(this).text();
            }
            $(this).siblings().removeClass('on');
            that.defaults.page = 0;
            getDataFun();
         });

         orderList.click(function(){
            if($(this).hasClass('on')){
                return false;
            }
            $(this).addClass('on');
            $(this).siblings().removeClass('on');
            if(!$(this).hasClass('order-type')){
                that.defaults.order = $(this).attr("atr");
                that.defaults.orderType = "";
            }else{
                that.defaults.orderType = $(this).attr("atr");
                that.defaults.order = "";
            }
            that.defaults.page = 0;
            getDataFun();
         });

         $("#seach_js").click(function(){
            that.defaults.page = 0;
            getDataFun();
         });

         var offsetScreen = $(".goods-screen-box");
        $(window).scroll(function(event) {
            if($(window).height() > 800){
                if($(window).scrollTop() > offsetScreen.offset().top){
                    offsetScreen.find(".inner").addClass('inner-fixed');
                }else{
                    offsetScreen.find(".inner").removeClass('inner-fixed');
                }
            }
             if($(window).scrollTop() > that.offSet){
                that.getJson();
             }
        });

        $("#seach_input").keyup(function(event) {
            that.defaults.keywords = $(this).val();
            $("#more_list").attr("href","http://www.shihuo.cn/tuangou/list?keywords="+$(this).val())
        });

        $("#seach_input").keydown(function(e){
             if(e.keyCode==13){
                  $("#seach_js").click();
             }
        });
    },
    getJson:function(){
        var that = this;
        if(that.ajaxLoding){
            return false;
        }
        that.ajaxLoding = true;
        that.defaults.page++;
        $.post("http://www.shihuo.cn/groupon/getGroupons",this.defaults,function(data){
            if(data.status*1 == 0){
                var str = '';
                for(var i=0;i<data.data.length;i++){
                    if(data.data[i].usp_logo*1 == 1){
                        var tips = '<div class="tips-box-1"></div>';
                    }else if(data.data[i].usp_logo*1 == 2){
                        var tips = '<div class="tips-box-2"></div>';
                    }else{
                        tips = "";
                    }
                    str+='<li>\
                        '+tips+'\
                        <div class="img">\
                            <a target="_blank" href="'+data.data[i].url+'#qk=goods&page='+that.defaults.page+'&order='+(i+1)+'"><img src="'+data.data[i].img_path+'" width="224" height="224" /></a>\
                        </div>\
                        <div class="h2"><a target="_blank" title="'+data.data[i].title+'" alt="'+data.data[i].title+'" href="'+data.data[i].url+'#qk=goods&page='+that.defaults.page+'&order='+(i+1)+'">'+data.data[i].title+'</a></div>\
                        <div class="price clearfix">\
                            <div class="t1">'+data.data[i].discount+'折</div>\
                            <div class="t2"><span class="s1">¥'+data.data[i].original_price+'</span><span class="s2"><s>¥</s>'+data.data[i].price+'</span></div>\
                        </div>\
                        <div class="time-pop">\
                            '+(that.defaults.orderType != "coming"?'<div class="t1"><s></s> '+that.MillisecondToDate(data.data[i].countdown)+'</div><div class="t2">已有'+data.data[i].attend_count+'参团</div>':'<div class="t1"><s></s> '+that.splitFun(data.data[i].start_time)+'</div>')+'\
                        </div>\
                    </li>';
                }
                if(data.data.length < 20){
                    $(".loding-more").hide();
                }
                if(data.data.length == 0 && that.defaults.page == 1){
                    var typeStr = "";
                    if(that.defaults.type.length > 0){
                        for(var n=0; n<that.defaults.type.length; n++){
                            typeStr+=that.defaults.type[n] + "，";
                        }
                    }
                    $(".none-error").show();
                    $(".none-error s").html("“"+typeStr+that.defaults.brand+(that.defaults.keywords!="" && that.defaults.brand != ""?"，":"")+that.defaults.keywords+"”")
                    $("#groupon_goods_list").hide();
                }

                if(data.data.length != 0){
                    $("#groupon_goods_list").show().append(str);
                    that.ajaxLoding = false;
                }

                that.offSet = $("#groupon_goods_list").offset().top+$("#groupon_goods_list").height()-1000;
            }

        },"json");

    },
    MillisecondToDate:function(msd) {
        var theTime = parseInt(msd);// 秒
        var theTime1 = 0;// 分
        var theTime2 = 0;// 小时
        var theTime3 = 0;//天
        if(theTime > 60) {
            theTime1 = parseInt(theTime/60);
            theTime = parseInt(theTime%60);
                if(theTime1 > 60) {
                    theTime2 = parseInt(theTime1/60);
                    theTime1 = parseInt(theTime1%60);
                }
        }
        var result = [];
        if(theTime2 > 24){
            theTime3 = parseInt(theTime2/24);
            theTime2 = parseInt(theTime2%24);
            result[0] = theTime3;
        }else{
            result[0] = 0;
        }
        if(theTime2 > 0) {
           result[1] = parseInt(theTime2) < 10?"0"+parseInt(theTime2):parseInt(theTime2);
        }else{
           result[1] = "00"
        }

        if(theTime1 > 0) {
           result[2] = parseInt(theTime1) < 10?"0"+parseInt(theTime1):parseInt(theTime1);
        }else{
           result[2] = "00"
        }

        result[3] = parseInt(theTime)< 10?"0"+parseInt(theTime):parseInt(theTime);

        return "剩余"+result[0]+"天"+result[1]+"小时"+result[2]+"分";
    },
    splitFun:function(o){
       var date = o.split(" ")[0].split("-");
       return date[1]+"月"+date[2]+"日开团";
    }
}

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