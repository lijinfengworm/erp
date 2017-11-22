$(function(){
    $(".J_ui_picSwitch").slide({
        css: {"width": 693, "height": 200},
        config: {"time": 4000, "type": "left", "speed": 600,"button":true,"butArr":".J_ui_picSwitch .J_ui_a li"},
        completes:function(o){//初始化完成执行动作
          $(".J_ui_picSwitch").hover(function(){
               $(".J_ui_butPost_a,.J_ui_butPost_b").show();
          },function(){
               $(".J_ui_butPost_a,.J_ui_butPost_b").hide();
          });
        }
    });
    
    $(".goods-floor").find(".area-sub li").hover(function(){
        $(this).addClass('on');
        $(this).find(".submit").addClass('on');
    },function(){
        $(this).removeClass('on');
        $(this).find(".submit").removeClass('on');
    });

    $(".daigou-area").find("li").hover(function(){
        $(this).addClass('on');
    },function(){
        $(this).removeClass('on');
    });

    times.init();
    upData.init();
    gotoLink.init();
});

var times = {
    init:function(){
       this.SetRemainTime($("#last-time"),$("#last-time").attr("data-time"));
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
                that.first().html("离结束仅剩<b>"+(hour>9?hour:"0"+hour)+"</b><s>:</s><b>"+(minite>9?minite:"0"+minite)+"</b><s>:</s><b>"+(second>9?second:"0"+second)+"</b>");
            } else {
                that.first().html("已到期");
                clearInterval(t);
            }
        },1000)
    }
}
var gotoLink = {
    lodingJson:false,
    init:function(){
        this.binFun();
    },
    binFun:function(){
        var that = this;
        $(".submit-gou").click(function(){
            //按钮点击统计
            __dace.sendEvent('shihuo_yijiangou_haitao_index');
             var val = $(".test-input").val(),
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
                            //$this.attr("href",data.data.buy_url);
                            /*
                            var aHtml = document.createElement("a");
                            aHtml.setAttribute("href", data.data.buy_url);
                            aHtml.setAttribute("target", "_blank");
                            aHtml.setAttribute("id", "openwin");
                            document.body.appendChild(aHtml);
                            aHtml.click();
                            */
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

        $(".test-input").focus(function(){
           if($.trim($(this).val()) == "输入海外商品链接，点击搜索即可直接通过识货购买"){
               $(this).val("");
           }
        });

        $(".test-input").blur(function(){
           if($.trim($(this).val()) == ""){
               $(this).val("输入海外商品链接，点击搜索即可直接通过识货购买");
           }
        });
    }
}

var upData = {
    pages:1,
    list:1,
    infoWidth:324,
    timeWidth:165,
    ajaxLoding:false,
    init:function(){
        this.obj = $(".move-goods-list-1 ul");
        this.timeObj = $("#time_list");
        this.getJson(false);
        this.bindFun();
    },
    getJson:function(o){
        var that = this;
        $.post("http://www.shihuo.cn/groupon/GetAmazonNews",{page:that.pages},function(data){
            if(data.status){
               that.pages++;
               that.obj.eq(0).css({
                  width:that.list * that.infoWidth * 3
               }).append(that.getHtmlA({
                  data:data.msg,
                  num:1
               }));
               that.obj.eq(1).css({
                  width:that.list * that.infoWidth * 4
               }).append(that.getHtmlA({
                  data:data.msg,
                  num:0
               }));

               that.timeObj.append(that.getHtmlB(data.msg)).css({
                  width:that.timeWidth*that.timeObj.find("li").length
               });
               if(o){
                  that.move();
               }
            }
        },"json");
    },
    getHtmlA:function(o){
        var str = [],
            link;
        for(var i=0;i<o.data.length;i++){
            if(o.num==0 && i%2!=0){
                continue;
            }
            if(o.num==1 && i%2==0){
                continue;
            }


            link = '<a href="'+o.data[i].amazon_url+'" target="_blank">';

            str.push('<li>\
               <div class="clearfix">\
                    <div class="imgs">\
                         <a href="'+o.data[i].amazon_url+'" target="_blank"><img src="'+o.data[i].img_url+'"></a>\
                    </div>\
                    <div class="msg">\
                        <div class="title"><a href="'+o.data[i].amazon_url+'" target="_blank">'+o.data[i].title+'</a></div>\
                        <p>'+o.data[i].subtitle+'</p>\
                        <div class="buy-btn-x '+(o.data[i].is_daigou?"true":"")+'" style="display:none;">'+link+'<s>'+(o.data[i].is_daigou?"去代购":"直达链接")+'></s></a></div>\
                    </div>\
                </div>\
                <div class="icon-s"></div>\
           </li>');
        }
        //<div class="buy-btn-x" style="display:none;">'+link+'<s>'+(o.data[i].is_daigou?"去代购":"直达链接")+'></s></a></div>\
        return str.join("");
    },
    getHtmlB:function(o){
        var str = [],
            str2;
        for(var i=0;i<o.length;i++){
            if(i%2==0){
                str2 = '<s></s><span class="ti">'+o[i].created_at+'</span>';
            }else{
                str2 = '<span class="ti">'+o[i].created_at+'</span><s class="top"></s>';
            }
            str.push('<li>'+str2+'</li>');
        }
        return str.join("");
    },
    bindFun:function(){
        var that = this,
            objA = $(".time-area").find(".left"),
            objB = $(".time-area").find(".right");

        objA.click(function(){
            if(that.list > 1){
                if(that.ajaxLoding){
                    return false;
                }
                that.ajaxLoding = true;
                that.rerurnMove();
                that.list--;
            }
        });

        objB.click(function(){
            if(that.ajaxLoding){
                return false;
            }
            that.ajaxLoding = true;
            that.list++;
            if(that.list == that.pages){
                that.getJson(true); 
            }else{
                that.move();
            }
        });

        $(".move-goods-list-1").delegate("li","mouseover",function(){
             $(this).addClass('on');
            $(this).find(".buy-btn-x s").addClass('onClass');
            $(this).find(".icon-s").addClass('icon-s2');
            if($(this).find(".buy-btn-x").hasClass('true')){
                $(this).find(".buy-btn-x").show();
            }
        });

        $(".move-goods-list-1").delegate("li","mouseout",function(){
            $(this).removeClass('on');
            $(this).find(".buy-btn-x s").removeClass('onClass');
            $(this).find(".icon-s").removeClass('icon-s2');
            $(this).find(".buy-btn-x").hide();
        });
    },
    move:function(){
        var that = this;
        that.obj.eq(0).animate({
            left:-that.infoWidth * 2 + parseInt(that.obj.eq(0).css("left") == "auto"?0:that.obj.eq(0).css("left"))
        },500);
        that.obj.eq(1).animate({
            left:-that.infoWidth * 3 + parseInt(that.obj.eq(1).css("left") == "auto"?0:that.obj.eq(1).css("left"))
        },500);
        that.timeObj.animate({
            left:-that.timeWidth * 6 + 18 + parseInt(that.timeObj.css("left") == "auto"?0:that.timeObj.css("left"))
        },500)
        setTimeout(function(){
            that.ajaxLoding = false;
        },500);
    },
    rerurnMove:function(){
        var that = this;
        that.obj.eq(0).animate({
            left:parseInt(that.obj.eq(0).css("left") == "auto"?0:that.obj.eq(0).css("left")) + that.infoWidth * 2
        },500);
        that.obj.eq(1).animate({
            left:parseInt(that.obj.eq(1).css("left") == "auto"?0:that.obj.eq(1).css("left")) + that.infoWidth * 3
        },500);
        that.timeObj.animate({
            left:parseInt(that.timeObj.css("left") == "auto"?0:that.timeObj.css("left")) + that.timeWidth * 6  - 18
        },500)
        setTimeout(function(){
            that.ajaxLoding = false;
        },500);
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