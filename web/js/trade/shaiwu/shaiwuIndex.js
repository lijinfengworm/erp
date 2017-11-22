/*点赞、踩功能*/
var messageFnTip = {
    init: function () {
        var _this = this,
            time1,time2;
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
        
        this.$elem.find(".share").live("mousemove",function(){
               var src = $(this).attr("atr");
               $(this).next().show();
               if($(this).next().data("erwei") != true){
                    if($.browser.webkit){
                        $(this).next().find(".erwei_code").qrcode({  
                            text: "http://www.shihuo.cn"+src,
                            width: 145,
                            height: 145
                       });
                    }else{
                        $(this).next().find(".erwei_code").qrcode({  
                            render: "table", //table方式 
                            text: "http://www.shihuo.cn"+src,
                            width: 145,
                            height: 145
                       });
                    }
                   $(this).next().data("erwei",true);
               }
               clearTimeout(time1);
               clearTimeout(time2);
        });

        this.$elem.find(".share").live("mouseout",function(){
            var that = $(this);
            time1 = setTimeout(function(){
                that.next().hide();
            },100);
        });

        this.$elem.find(".share-layer").live("mousemove",function(){
               clearTimeout(time1);
               clearTimeout(time2);
        });

        this.$elem.find(".share-layer").live("mouseout",function(){
             var that = $(this);
               time2 = setTimeout(function(){
                    that.hide();
               },100);
        });

        this.$elem.find(".share-layer .s-icon span").live("mousemove",function(){
               clearTimeout(time2);
               return false;
        });
        
        this.$elem.find(".share-layer .s-icon span").live("click",function(){
             var str = $(this).attr("atr");
             $.shareAPI(str,{
                title:encodeURIComponent($(this).parents(".share-layer").attr("tit")),
                url:"http://www.shihuo.cn"+$(this).parents(".share-layer").attr("atr"),
                pic:$(this).parents(".share-layer").attr("atimg"),
                pics:$(this).parents(".share-layer").attr("atimg"),
                searchPic:true
            });
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
            id = parent.attr("filter");

        var data = {
            'id': id,
            'type': type            
        };

        $.getJSON("http://www.shihuo.cn/shaiwu/AjaxSupportAgaist", data, function (data) {
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

/*页面右侧list悬停*/
function scrollHandle(){
    var st = $(window).scrollTop(),
        wh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        ot = $(".shaiwu-index-wrapper").offset().top,
        rl1 = $(".r-list").eq(0).height(),
        ol = $(".wrapper-area").offset().left + $(".wrapper-area-left").width() + 18,
        rt = $(".wrapper-area-right").height(),
        ft = $(".foot-aboutus").length > 0 ? $(".foot-aboutus").offset().top - 43 : $("body").height();
    
    if(st > ot + rl1){
        /*$(".scrollwrapper").css("overflow-y","scroll");
        if(st + rt >= ft){
            var rt2 = rt - (ft - st);
            $(".wrapper-area-right").css({"position":"fixed","top":"-"+rt2+"px","left":ol+"px"});  
            $(".scrollwrapper,.wrapper-area-right").height("auto");
        }else{
            $(".wrapper-area-right").css({"position":"fixed","top":"-20px","left":ol+"px"});  
            if(rt + 10 > wh){
                $(".scrollwrapper,.wrapper-area-right").height(wh);
            }else{
                $(".scrollwrapper,.wrapper-area-right").height("auto");
            } 
        }              */       
        $(".jinghuashaiwu").css({"position":"fixed","top":"-20px","left":ol+"px"});
    }else{
        $(".jinghuashaiwu").css({"position":"relative","top":"0px","left":"0px"});
        //$(".wrapper-area-right").css({"position":"relative","top":"0px","left":"0px"});
    }
}

/*判断url所带参数*/
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}

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

//获取字节数
function len(s) { 
    var l = 0; 
    var a = s.split(""); 
    for (var i=0;i<a.length;i++) { 
    if (a[i].charCodeAt(0)<299) { 
    l++; 
    } else { 
    l+=2; 
    } 
    } 
    return l; 
}

//时间转时间戳
function get_unix_time(dateStr)
{
    var newstr = dateStr.replace(/-/g,'/'); 
    var date =  new Date(newstr); 
    var time_str = date.getTime().toString();
    return time_str.substr(0, 10);
}

$(function(){
    /*只看精华晒物*/
    $(".shaiwu-index-wrapper .filter").click(function(){
        var url = $(this).find("span").attr("attr");
        window.location.href = "http://www.shihuo.cn"+url;
    });

    var oldie = $.browser.msie && $.browser.version < 9;
	/*判断旧版本ie兼容清除浮动的伪类*/
	oldie && $('.clearfix').append('<div class="clear"></div>');

     /*首页顶部轮播*/
    var shaiwuSlider = function(){       
        var sildnum = $(".J_ui_postFloat > li").length,ison;
        if(sildnum == 1){
            $(".J_ui_butPost_a,.J_ui_butPost_b").hide();
            return false;
        }
        for(var i = 0;i < sildnum;i++){
            if(i == 0) ison = "on"
            else ison = "";
            $(".J_ui_a").append('<li class='+ison+'></li>');
        }           
        $(".J_ui_picSwitch").slide({
            css: {"width": 790, "height": 300},
            config: {
                "time": 5000,
                "type": "left",
                "speed": 600,
                "button": true,
                "butArr": ".J_ui_picSwitch .J_ui_a li"
            },
            completes: function (o) {//初始化完成执行动作            
            }
        });
    }
	shaiwuSlider();

	/*列表筛选精华晒物按钮*/
    $(".filter").click(function(){
    	$(this).toggleClass('check');
    });

    /*列表展开全部点击事件*/
    $(".expandall",".s-l-grid").live({
        click:function(){
            var thisid = $(this).attr("filter");            
            __dace.sendEvent('shihuo_shaiwu_detail_'+thisid+'');

            var $parents = $(this).parents('.s-l-right'),
                $parentsli = $(this).parents('li'),
                thisid = $parents.find('.J_message_fn_tip').attr("filter"),
                loaded =  $.trim($parents.find('.showdetail').text()) ? true : false,
                loadurl = "http://www.shihuo.cn/shaiwu/AjaxProductContent";
            if(!loaded){
                $.getJSON(loadurl,{id:thisid,type:1},function(data){
                    if(data.status == 200){
                        var dom = data.data.shaiwu.content,
                            urls = data.data.shaiwu.urls;
                        $parents.find(".showdetail").html(dom);
                        urls.length > 0 ? $parents.find(".fleft").show() : $parents.find(".fleft").hide();
                        for(var i=0;i<urls.length;i++){
                            $parents.find(".linkwrap").append('<a target="_blank" href='+urls[i]+'>'+urls[i]+'</a>');
                        }
                    }
                })
            }
            $parents.find(".s-l-grid").fadeOut(200,function(){                
                $parents.find(".showdetail,.slideUP,.showUrl").fadeIn(400);
            });            
            $(window).scrollTop($parentsli.offset().top);
        }
    });

    /*列表收起点击事件*/
    $(".slideUP").live({
        click:function(){
            var $parentsli = $(this).parents('li');
            $(this).parent().find(".slideUP").hide();
            $(this).siblings('.showdetail,.showUrl').fadeOut(200,function(){
                $(this).siblings('.s-l-grid').fadeIn(400);
            });
            $(window).scrollTop($parentsli.offset().top);
        }
    })      

    $(window).scroll(function(event) {
        //scrollHandle();  
    });
    $(window).resize(function(){
        //scrollHandle(); 
    });

    $(window).keydown(function(event) {
        /* Act on the event */        
        if($(event.target).attr("class") == "pagelink" && event.keyCode==13){
            var pagenum = parseInt($(event.target).val()),
                thisurl = $(event.target).attr("r"),
                allpagenum = $(".inputbox span:eq(0)").text().replace(/[^0-9]/ig, "");            
            if(pagenum > allpagenum || pagenum < 1 ){
                return false
            }else{                                              
                window.location.href = thisurl+"&page="+$(event.target).val();
            }            
        }
    });

    $(".pagelink").live({
        focus:function(){
            $(".pagelink-btn").css("display","inline-block");
        },
        blur:function(e){
            //console.log(e)
            //$(".pagelink-btn").hide();
        }
    });

    $(".pagelink-btn").live({
        click:function(e){
            //e.stopPropagation();
            var $pagelink = $(".pagelink"),
                pagenum = parseInt($pagelink.val()),
                thisurl = $pagelink.attr("r"),
                allpagenum = $(".inputbox span:eq(0)").text().replace(/[^0-9]/ig, "");            
            if(pagenum > allpagenum || pagenum < 1 ){
                return false
            }else{                                              
                window.location.href = thisurl+"&page="+$pagelink.val();
            }        
        }
    });

    var ajaxUrl = "http://www.shihuo.cn/shaiwu/AjaxList";

    //首页晒物列表
    var type = 0, root_type = "", cur_page = 1, top = 0;
    var ajaxLoad = true;

    //加载更多
    var loadMore = {
        init: function () {
            this.fixedTop = $(".loading-more .load");
            this.fixedTop.hide();
            var that = this,
                type = $(".shaiwu-list").attr("type"),
                isHot = $(".shaiwu-list").attr("ishot");

            if(getQueryString("page")){
                $(".pageselectbox").show();
                return false
            }
            $(window).bind("scroll", function () {
                var lasttime = $(".shaiwu-list ul li").last().find(".publish_time").attr("date-time");                 
                //当内容滚动到底部时加载新的内容
                if ($(this).scrollTop() + $(window).height() + 20 >= $(document).height() && $(this).scrollTop() > 20 && ajaxLoad) {                    
                    //当前要加载的页码
                    if (cur_page < 3) {
                        that.fixedTop.fadeIn();
                        ajaxLoad = false;
                        $.getJSON(ajaxUrl, {
                            type: type,
                            page: cur_page+1,
                            pageSize:30,
                            display:1,
                            isHot:isHot,  
                            lastTime: lasttime
                        }, function (data) {                                                                                  
                             if(data.status == 200){
                                cur_page++;                                
                                ajaxLoad = true;     
                                var jsondata = data.data.list;                                                                                                                             
                                for (var i =0;i<jsondata.length;i++){                                                                       
                                    var $datadetail =  jsondata[i];                                    
                                    var isHot = $datadetail.isHot == 1 ? "boutique" : "";                                                                            
                                    var ajaxdom = '<li class="clearfix '+isHot+'" >';
                                    var intro = $datadetail.intro;
                                    var subtime =  $datadetail.publish_time.substring(5,16);   
                                    var index = i+1; 
                                    if(len($datadetail.intro)>290){
                                        intro = $datadetail.intro.substring(0,140);
                                    }                                                                              
                                    ajaxdom += '<div class="s-l-left">';
                                    ajaxdom +=      '<img width="40" src="http://bbs.hupu.com/bbskcy/api_new_image.php?uid='+$datadetail.author_id+'" />'; 
                                    ajaxdom += '</div>';
                                    ajaxdom += '<div class="s-l-right">';
                                    ajaxdom +=      '<p class="username">'+$datadetail.author_name+'</p>'; 
                                    ajaxdom +=      '<p class="publish_time" date-time="'+$datadetail.publish_time+'">'+subtime+'</p>';
                                    ajaxdom +=      '<a class="title" target="_blank" href="http://www.shihuo.cn/shaiwu/detail/'+$datadetail.id+'.html#qk=detail&order='+index+'&page='+cur_page+'"><h2>'+$datadetail.title+'</h2></a>';
                                    ajaxdom +=      '<div class="s-l-grid clearfix">';
                                    ajaxdom +=          '<a target="_blank" href="http://www.shihuo.cn/shaiwu/detail/'+$datadetail.id+'.html#qk=detail&order='+index+'&page='+cur_page+'"><img width="140" src='+$datadetail.front_pic+' /></a>';
                                    ajaxdom +=          '<p>'+intro+'...<a class="expandall" filter='+$datadetail.id+' href="javascript:void(0)">展开全部</a></p>';
                                    ajaxdom +=      '</div>'; 
                                    ajaxdom +=      '<div class="showdetail"></div>';
                                    ajaxdom +=      '<div class="left J_message_fn_tip" filter='+$datadetail.id+'>';
                                    ajaxdom +=          '<a class="z btn-recommend J_message_btn_recommend" href="javascript:" rel="nofollow">赞(<span class="recommend-num">'+$datadetail.support+'</span>)</a>';
                                    ajaxdom +=          '<a class="d btn-oppose J_message_btn_oppose" href="javascript:" rel="nofollow">踩(<span class="oppose-num">'+$datadetail.agaist+'</span>)</a>';
                                    ajaxdom +=          '<a target="_blank" class="say" href="http://www.shihuo.cn/shaiwu/detail/'+$datadetail.id+'.html#comment">评论('+$datadetail.comment_count+')</a>';
                                    ajaxdom +='<a class="share" atr="http://www.shihuo.cn/shaiwu/detail/'+$datadetail.id+'.html#qk=detail&order=9" href="javascript:void(0);">分享到</a>';
                                    ajaxdom +='<div class="share-layer" tit="'+$datadetail.title+'" atr="http://www.shihuo.cn/shaiwu/detail/'+$datadetail.id+'.html#qk=detail&order='+index+'&page='+cur_page+'" atimg="'+$datadetail.front_pic+'"><div class="s-icon"><span atr="sina_weibo" class="sina_weibo"></span><span atr="qzone" class="qzone"></span><span atr="renren" class="renren"></span></div><div class="erwei_code"></div></div>';
                                    ajaxdom +=      '</div>';    
                                    ajaxdom +=      '<div class="slideUP top"><i></i>收起</div>';                    
                                    ajaxdom +=      '<div class="slideUP down"><i></i>收起</div>';  
                                    ajaxdom += '</div>';
                                    ajaxdom += '</li>';  
                                    $(".shaiwu-list ul").append(ajaxdom);                                                                        
                                }  
                                $(".pageselectbox .wrapper").html(data.data.pageHtml);                                  
                                that.fixedTop.fadeOut();
                            } else {                         
                                $(".loading-more .load").addClass("hide");
                                $(".pageselectbox").show();
                                ajaxLoad = false;
                            }
                        });
                    } else {
                        if (cur_page >= 3) {
                            $(".pageselectbox").show();
                        }
                    }
                    //messageFnTip.init();
                }
            });
        }
    };
    loadMore.init();    

    //returnTop
    var returnTop = {
        init:function(){
            this.returnTop = $("#returnTop");
            this.returnTop.hide();
            var that = this;
            $(window).bind("scroll",function(){
                var w_t = getW().s;
                w_t>800?(
                    that.returnTop.fadeIn(),
                    that.returnTop.addClass("show")
                ):(
                    that.returnTop.fadeOut(),
                    that.returnTop.removeClass("show")
                ); //返回按钮
            });
        }
    };
    returnTop.init();
});

/*图片切换效果*/
!(function ($) {
   var share = {
        defaults:{
           sina_weibo:"http://v.t.sina.com.cn/share/share.php?appkey=&",
           qq_weibo:"http://v.t.qq.com/share/share.php/?appkey=&",
           douban:"http://www.douban.com/recommend/?",
           qzone:"http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?",
           kaixin:"http://www.kaixin001.com/repaste/share.php?",
           renren:"http://widget.renren.com/dialog/share?"
        },
        init:function(){
             var that = share,arg = arguments,x;
                 that.url = that.defaults[arguments[0]];
                 this.num = 0;
             for(x in arguments[1]){
                 that.url += ((this.num==0?"":"&") + x + "=" +arguments[1][x]);
                 this.num++;
             }
             that.window_open(arguments[0]);
        },
        window_open:function(k){
           window.open(this.url, "分享到", this.getParamsOfShare([600,560]));
        },
        getParamsOfShare:function(arr){
           return ['toolbar=0,status=0,resizable=1,width=' + arr[0] + ',height=' + arr[1] + ',left=',(screen.width-arr[0])/2,',top=',(screen.height-arr[1])/2].join('');
        }
    }

    $.extend({
        shareAPI:share.init
    });

    var picScroll = function () {
        var arg = arguments,
            defaults = {// css{盒子的宽高};config{每次滑动/淡进淡出间隔时间time、滑动类型("top/left/fade")、滑动/淡进淡出的速度speed、是否加载左右按钮button}。注：如不自定义参数则采用默认值
                css: {"width": 490, "height": 170},
                config: {
                    "time": 3000,
                    "type": "fade",
                    "speed": 600,
                    "button": true,
                    "butArr": ".J_ui_picSwitch .J_ui_a li"
                },
                before: function (data) {//图片切换前执行动作
                },
                after: function (data) {//图片切换完成执行动作
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