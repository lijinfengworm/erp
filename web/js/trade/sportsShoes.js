$(function(){
  $(".J_ui_picSwitch").slide({
        css: {"width": 760, "height": 400},
        config: {"time": 5000, "type": "left", "speed": 600,"button":true,"butArr":".J_ui_picSwitch .J_ui_a li"}
    });
});

/*图片切换效果*/
;(function($) {
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
                        this.config();//配置开始
                        this.bindFun();//绑定方法
                        if(this.arrays[0].completes){
                           this.arrays[0].completes($this);
                        }
                    }else{//如果参数不正确抛出错误
                        $.error = console.error;
                        $.error("参数格式不正确！");
                    }
                },
                config: function() {
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
                        $(this).siblings().removeClass("on");
                        $(this).addClass("on");
                        that.config();
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
                              that.config();
                            }
                        });
                        $$(".J_ui_butPost_b").click(function() {
                            if(that.animated){
                                return false;
                            }else{
                              that.list += 1;
                              that.config();
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
                        that.config();
                    }, that.arrays[0].config.time)
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