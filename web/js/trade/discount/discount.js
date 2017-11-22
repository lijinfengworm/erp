
var messageFnTip = {
    init: function () {
        var _this = this;
        this.$elem = $(".J_message_fn_tip");

        // 判断元素不存在，不执行return
        if (!this.$elem.length) return false;

        var $btnRecommend = $(".J_message_btn_recommend"),
            $btnOppose = $(".J_message_btn_oppose"),
            SOURCE_TYPE = 1;

        // 判断是专题
        if (typeof pageType !== "undefined") {
            if (pageType == "topic") {
                SOURCE_TYPE = 2;
            }
        }
        // 支持
        $btnRecommend.live("click", function () {
            _this.setMessageRecommendData($(this), SOURCE_TYPE, 1);
        });

        // 反对
        $btnOppose.live("click", function () {
            _this.setMessageRecommendData($(this), SOURCE_TYPE, 2);
        });

    },
    // 用户信息
    isLogin: function () {
        var ua = document.cookie.match(new RegExp("(^| )ua=([^;]*)(;|$)")), data;
        if (ua && ua[2]) return true;
        return;
    },
    setMessageRecommendData: function (elem, sourceType, type) {
        if (!this.isLogin()) {
            commonLogin('hupu');
            return false;
        }
        ;

        var parent = elem.parents(".J_message_fn_tip"),
            $fnA = parent.find(".recommend-box a"),
            $recommendNum = parent.find(".recommend-num"),
            $opposeNum = parent.find(".oppose-num"),
            id = parent.attr("data-message-id");

        var data = {
            'id': id,
            'type': type,
            'source': sourceType
        };

        $.getJSON("http://www.shihuo.cn/message_support_agaist", data, function (data) {
            if (data.status == 200) {
                $recommendNum.text(data.data.snum);
                $opposeNum.text(data.data.anum);

                if (type == 1) {
                    $fnA.removeClass("btn-oppose-on");
                    elem.toggleClass("btn-recommend-on");
                } else {
                    $fnA.removeClass("btn-recommend-on");
                    elem.toggleClass("btn-oppose-on");
                }
            }
        });

    }
};
messageFnTip.init();

$(function () {
    if ($(".content-bg").hasClass("indexPage")) {
        var ajaxUrl = "http://www.shihuo.cn/shihuo/ajaxYouhui";
        //首页滚屏
        $(".J_ui_picSwitch").slide({
            css: {"width": 606, "height": 275},
            config: {
                "time": 5000,
                "type": "left",
                "speed": 600,
                "button": true,
                "butArr": ".J_ui_picSwitch .J_ui_a li"
            },
            completes: function (o) {//初始化完成执行动作
                $(".J_ui_picSwitch").hover(function () {
                    $(".J_ui_butPost_a,.J_ui_butPost_b").show();
                }, function () {
                    $(".J_ui_butPost_a,.J_ui_butPost_b").hide();
                });
            }
        });

        //首页精品专题
        var type = 0, root_type = "", cur_page = 1, top = 0;
        var ajaxLoad = true;
        function resetUrl() {
            var page = $("#ajax-list").children('li').length / 30;
            if(0 < page && page < 1) {
                page = 1;
            }else if(1 <= page && page < 2) {
                page = 2;
            }else if(2 <= page && page < 3) {
                page = 3;
            }
            $("#moreYouhui").attr("href", "http://www.shihuo.cn/youhui/list?type=" + type + "&page=" + page);
        }

        $(".select-bar .sub>a").on("click", function () {
            cur_page = 1;
            ajaxLoad = true;
            $(".loading-more .link-more").hide();

            if ($(this).hasClass("checked")) {
            } else {
                $(this).addClass("checked");
                var data_type = $(this).attr('data-type');
                if (data_type == 1) {
                    type = 0;
                    top = 1;
                } else if (data_type == 2) {
                    type = 1;
                    top = 0;
                } else if (data_type == 3) {
                    type = 2;
                    top = 0;
                }
                $(this).siblings().each(function () {
                    $(this).removeClass("checked");
                });

                $.post(ajaxUrl, {type: type, page: cur_page, top: top}, function (data) {
                    if (data) {
                        $("#ajax-list").html(data);
                        //messageFnTip.init();
                    }
                });
            }
        });

        //加载更多
        $(".loading-more .link-more").hide();
        var loadMore = {
            init: function () {
                this.fixedTop = $(".loading-more .load");
                this.fixedTop.hide();
                var that = this;
                $(window).bind("scroll", function () {
                    //当内容滚动到底部时加载新的内容
                    if ($(this).scrollTop() + $(window).height() + 20 >= $(document).height() && $(this).scrollTop() > 20 && ajaxLoad) {
                        //当前要加载的页码
                        if (cur_page < 3) {
                            that.fixedTop.fadeIn();
                            ajaxLoad = false;
                            $.post(ajaxUrl, {
                                type: type,
                                root_type: root_type,
                                page: cur_page + 1,
                                top: 0,
                            }, function (data) {
                                if ($.trim(data)) {
                                    cur_page++;
                                    ajaxLoad = true;
                                    $("#ajax-list").append(data);
                                    that.fixedTop.fadeOut();
                                } else {
                                    $(".loading-more .load").hide();
                                    $(".loading-more .link-more").show();
                                    ajaxLoad = false;
                                }
                            });
                        } else {
                            if (cur_page >= 3) {
                                $(".loading-more .link-more").show();
                            }
                        }
                        //messageFnTip.init();
                    }
                    resetUrl();
                });
            }
        };
        loadMore.init();
    }

});

var tryCourse = {
    isLoding:false,
    init:function(){
        this.newObj = $("#haitao_new_user");
        $.getScript('/js/trade/discount/jquery.zclip.js',function(){});
        this.bindFun();
    },
    bindFun:function(){
        var that = this;
        this.newObj.click(function(){
             that._showMasks(0.8);
             that.courseA();
        });

        $("#course_class1").live("click",function(){
             that.courseClsoe();
             that.courseB();
        });

        $("#course_class2").live("click",function(){
             that.courseClsoe();
             that.tryOutA();
        });

        $("#tryout_class1").live("click",function(){
             that.courseClsoe();
             that.tryOutB();
        });

        $("#tryout_class2").live("click",function(){
             that.courseClsoe();
             that.tryOutC();
        });

        $("#tryout_class3").live("click",function(){
             that.ajaxLodingFun(1);
        });

        $("#see_algin").live("click",function(){
             that.ajaxLodingFun(2);
        });

        $(".close-course-try2").live("click",function(){
             that.ajaxLodingFun(1);
        });

        $(".close-course-try,.close-course-try2").live("click",function(){
             that.courseClsoe();
             that._closeMasks();
        });
    },
    ajaxLodingFun:function(o){
        var that = this;
        if(that.isLoding){
            return false;
         }
         that.isLoding = true;
         $.post("http://www.shihuo.cn/ucenter/noviceFinish",{},function(data){
               if(data.status*1 == 1){
                   if(o == 1){
                       that.courseClsoe();
                       that._closeMasks();
                   }else{
                       that.courseClsoe();
                       that.courseA();
                   }
                   $.post("http://www.shihuo.cn/shihuo/userTask",{type:1},function(data){
                          $('#user-task').html(data);
                          setTimeout(function(data){
                               $.post("http://www.shihuo.cn/shihuo/userTask",{},function(data){
                                    $('#user-task').html(data);
                               },"html");
                          },"3000");
                   },"html");
               }else{
                   that.courseClsoe();
                   if(o == 2){
                       that.courseA();
                   }else{
                      that._closeMasks();
                   }
               }
               that.isLoding = false;
         },"json");
    },
    courseA:function(){
           var str = '<div class="course-class1" style="display:none; position:absolute; left:'+($(".logos-box .search").offset().left-140)+'px; top:4px; z-index:95;">\
                             <img src="/images/trade/discount/course/j1.png" />\
                             <div class="next" style="text-align: center; color:#fff; margin-top:40px;"><span id="course_class1" style="font-size:24px; border-radius:8px; background-color:#e24c52; padding:8px 25px; display:inline-block; cursor: pointer;">下一步</span></div>\
                      </div><div class="close-course-try" style="position:absolute; right:20px; top:20px; z-index:96; cursor: pointer;"><img src="/images/trade/discount/course/close.png" /></div>';
            $(str).appendTo("body");
            $(".course-class1").fadeIn();       
    },
    courseB:function(){
           var str = '<div class="course-class1" style="display:none; position:absolute; left:'+($(".logos-box .search").offset().left-80)+'px; top:4px; z-index:95;">\
                             <img src="/images/trade/discount/course/j2.png" />\
                             <div class="next" style="text-align: center; color:#fff; margin-top:40px;"><span id="course_class2" style="font-size:24px; border-radius:8px; background-color:#e24c52; padding:8px 25px; display:inline-block; cursor: pointer;">立即试用</span></div>\
                      </div><div class="close-course-try" style="position:absolute; right:20px; top:20px; z-index:96; cursor: pointer;"><img src="/images/trade/discount/course/close.png" /></div>';
            $(str).appendTo("body");     
            $(".course-class1").fadeIn();     
    },
    tryOutA:function(){
         var str = '<div class="course-class1" style="display:none; position:absolute; left:'+($(".logos-box .search").offset().left-80)+'px; top:144px; z-index:95;">\
                             <img src="/images/trade/discount/course/s1.png" />\
                             <p style="font-size:24px; color:#fff; text-align: center; margin-top:30px;">“ http://www.amazon.com/dp/B00RD5G3RE/ ”</p>\
                             <div class="next" style="text-align: center; color:#fff; margin-top:40px;"><span id="tryout_class1" style="font-size:24px; border-radius:8px; background-color:#e24c52; padding:8px 25px; display:inline-block; cursor: pointer;">复制</span></div>\
                      </div><div class="close-course-try" style="position:absolute; right:20px; top:20px; z-index:96; cursor: pointer;"><img src="/images/trade/discount/course/close.png" /></div>';
            $(str).appendTo("body");
            $(".course-class1").fadeIn();   
            $('#tryout_class1').zclip({
               path:'/js/trade/discount/ZeroClipboard.swf',
               copy:" http://www.amazon.com/dp/B00RD5G3RE/",
               afterCopy:function(){
                return;
               }
            });
    },
    tryOutB:function(){
         var str = '<div class="course-class1" style="display:none; position:absolute; left:'+($(".logos-box .search").offset().left)+'px; top:35px; z-index:95;">\
                             <div class="try-seach"><input type="text" style="width:364px; height:36px; line-height:36px; padding-left:10px; border:2px #ad0007 solid; background-color:#fff; vertical-align: middle;" /><span style="width:86px; height:40px; display:inline-block; text-align: center; color:#fff; line-height:40px; background-color:#ad0007; font-size:18px; vertical-align: middle; cursor: pointer;" id="tryout_class2">搜索</span></div>\
                             <img src="/images/trade/discount/course/s2.png" />\
                      </div><div class="close-course-try" style="position:absolute; right:20px; top:20px; z-index:96; cursor: pointer;"><img src="/images/trade/discount/course/close.png" /></div>';
            $(str).appendTo("body");
            $(".course-class1").fadeIn();   
    },
    tryOutC:function(){
         var str = '<div class="course-class1" style="display:none; position:absolute; left:'+($(".logos-box .search").offset().left-80)+'px; top:44px; z-index:95;">\
                             <img src="/images/trade/discount/course/s3.png" />\
                             <div class="next" style="text-align: center; color:#fff; margin-top:18px;"><span id="tryout_class3" style="font-size:24px; border-radius:8px; background-color:#e24c52; padding:8px 25px; display:inline-block; cursor: pointer;">立即体验</span><span style="color:#c9c9c9; font-size:18px; margin-left:10px; cursor:pointer;" id="see_algin">再看一遍</span></div>\
                      </div><div class="close-course-try2" style="position:absolute; right:20px; top:20px; z-index:96; cursor: pointer;"><img src="/images/trade/discount/course/close.png" /></div>';
            $(str).appendTo("body");
            $(".course-class1").fadeIn();   
    },
    courseClsoe:function(){
          $(".course-class1,.close-course-try,.close-course-try2").remove();
    },
    _showMasks: function(a) {
        var str = "<div class='body-mask' style='position:fixed; top:0; left:0; width:100%; height:" + $(window).height() + "px; background-color:#000;  z-index:91;'></div>";
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
    }
}

/*图片切换效果*/
!(function ($) {  
    var picScroll = function() {
        var arg = arguments,
        defaults = {// css{盒子的宽高};config{每次滑动/淡进淡出间隔时间time、滑动类型("top/left/fade")、滑动/淡进淡出的速度speed、是否加载左右按钮button}。注：如不自定义参数则采用默认值
            css: {"width": 637, "height": 255},
            config: {"time": 3000, "type": "fade", "speed": 800, "button": false,"butArr":".J_ui_picSwitch .J_ui_a li"},
            before:function(data){//图片切换前执行动作
            },
            after:function(data){//图片切换完成执行动作
            }
        };
        return this.each(function () {
            var $this = $(this),
                $$ = function (a) {
                    return $this.find(a)
                },
                animates = {
                    list: 0,//当前第几张
                    options: ["top", "left", "fade"],//动画类型
                    animated: false,
                    init: function () {
                        this.arrays = [];//预留参数位置以备用
                        this.arrays[0] = $.extend(true, {}, defaults, arguments[0] || {});//合并自定义参数和默认参数
                        this.ul = $$(".J_ui_post");
                        this.li = $$(".J_ui_post li");
                        this.but = this.arrays[0].config.butArr;
                        if (this.options.index(this.arrays[0].config.type) !== -1) {//参数是否正确
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
                            if (this.arrays[0].completes) {
                                this.arrays[0].completes($this);
                            }
                        } else {//如果参数不正确抛出错误
                            $.error = console.error;
                            $.error("参数格式不正确！");
                        }
                    },
                    config: function (str) {
                        var that = this, i = 0, butArr = that.but.split(","),
                            con = that.arrays[0].config,
                            arr = (con.type == "top" ? ["top"] : ["left"]);
                        if (con.type == "left" || con.type == "top") {//动画类型判断
                            if (con.type == "left") {
                                that.ul.addClass("J_ui_postFloat");
                                that.ul.width($this.width() * that.li.length);//计算图片列表总宽度
                            }
                            if (that.list == that.li.length) {//如果当前图片是第一张从最后循环
                                con.type == "top" ? that.li.first().css(arr[0], that.ul.height()) : that.li.first().css(arr[0], that.ul.width());//给第一张图片的position: relative;赋值以达到无限循环效果
                                that.callback = function () {//滚动完成后的回调函数  给position: relative;值还原为0 同时当前图片的位置是0
                                    that.li.first().css(arr[0], 0);
                                    that.ul.css(arr[0], 0);
                                    that.list = 0;
                                }
                            }
                            if (that.list == -1) {//如果当前图片是最后一张从第一张循环
                                con.type == "top" ? that.li.last().css(arr[0], -that.ul.height()) : that.li.last().css(arr[0], -that.ul.width());//给最后张图片的position: relative;赋值以达到无限循环效果
                                that.callback = function () {//滚动完成后的回调函数
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

                        for (; i < butArr.length; i++) {
                            $(butArr[i]).eq(that.list == that.li.length ? 0 : that.list).siblings().removeClass("on");//按钮样式变化
                            $(butArr[i]).eq(that.list == that.li.length ? 0 : that.list).addClass("on");//按钮样式变化
                        }
                    },
                    scrollA: function () {//滚动动画
                        this.animated = true;
                        var that = this, textCss,
                            con = that.arrays[0].config;//动画滚动参数获取
                        clearTimeout(that.t);//清除上一次的排队动画
                        that.rerurnFun(0);//滚动开始回调
                        con.type == "top" ? textCss = {"top": -parseInt($this.height()) * that.list} : textCss = {"left": -parseInt($this.width()) * that.list};//获取滚动值
                        that.ul.stop(true).textAnimation({
                            "css": textCss, "config": con, callback: function () {
                                if (that.callback) {//内部回调函数
                                    that.callback();
                                    that.callback = null;
                                }
                                that.animated = false;
                                that.rerurnFun(1);//滚动结束回调
                            }
                        });
                        that.setTime();//循环滚动
                    },
                    fadeFun: function () {//淡进淡出动画
                        var that = this;
                        clearTimeout(that.t);//清除上一次的排队动画
                        that.rerurnFun(0);//动画开始回调
                        that.li.css('opacity', 1)
                        that.li.eq(that.list).siblings().stop(true).fadeOut(that.arrays[0].config.speed);
                        that.li.eq(that.list).fadeIn(that.arrays[0].config.speed, function () {
                            that.rerurnFun(1);//动画结束回调
                        });
                        that.setTime();//循环动画
                    },
                    bindFun: function () {//绑定各种事件
                        var that = this;
                        $(that.but).hover(function () {
                            that.list = $(this).index();
                            that.config("stop");
                            clearTimeout(that.t);
                        }, function () {
                            that.setTime();
                        });

                        that.li.hover(function () {
                            clearTimeout(that.t);
                        }, function () {
                            that.setTime();
                        });

                        if (that.arrays[0].config.button) {
                            $$(".J_ui_butPost_a").click(function () {
                                if (that.animated) {
                                    return false;
                                } else {
                                    that.list -= 1;
                                    that.config("move");
                                }
                            });
                            $$(".J_ui_butPost_b").click(function () {
                                if (that.animated) {
                                    return false;
                                } else {
                                    that.list += 1;
                                    that.config("move");
                                }
                            });
                        } else {
                            $$(".J_ui_butPost_b").remove();
                            $$(".J_ui_butPost_a").remove();
                        }
                    },
                    rerurnFun: function (num) {//判断回调
                        if (num) {
                            !!this.returnAfter && this.returnAfter(this.list == this.li.length ? 0 : this.list);
                        } else {
                            !!this.returnBefore && this.returnBefore(this.list == this.li.length ? 0 : this.list);
                        }
                    },
                    setTime: function () {//循环动画
                        var that = this;
                        that.t = setTimeout(function () {
                            that.list += 1;
                            that.config("move");
                        }, that.arrays[0].config.time);
                    }
                }
            animates.init.apply(animates, arg);
        });
    }
    var defaults = {css: {"top": 0}, config: {speed: 800, easing: "swing", time: 0}},
        textAnimation = function (a) {
            return this.each(function () {
                var $this = $(this),
                    settings = $.extend(true, {}, defaults, a);
                $this.animate(settings.css, settings.config.speed, settings.config.easing, function () {
                    !!settings.callback && settings.callback();
                });
            })
        };
    $.extend(Array.prototype, {
        /*判断数组中是否包含指定的值*/
        has: function (value) {
            return this.index(value) !== -1;
        },
        /*判断数组中指定值的具体位置*/
        index: function (value) {
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
        textAnimation: textAnimation,
        slide: picScroll
    });
})(jQuery);
