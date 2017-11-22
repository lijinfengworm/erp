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

/*点赞、踩功能*/
var messageFnTip = {
    init: function () {
        var _this = this,
            time1,time2;
        this.$elem = $(".l-w-grid .left");

        // 判断元素不存在，不执行return
        if (!this.$elem.length) return false;

        var $btnRecommend = $(".l-w-grid .J_message_btn_recommend,.commondup"),
            $btnOppose = $(".l-w-grid .J_message_btn_oppose,.commondown"),
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
        
        var parent = elem.parents(".left").length > 0 ?   elem.parents(".left") : elem.parent(),
            $fnA = parent.find(".recommend-box a"),
            $recommendNum = parent.find(".recommend-num").length > 0 ? parent.find(".recommend-num") : parent.find(".commondup span"),
            $opposeNum = parent.find(".oppose-num").length > 0  ? parent.find(".oppose-num") : parent.find(".commondown span"),
            id = parent.attr("data-message-id");
     
        var data = {
            'id': id,
            'type': type            
        };

        $.getJSON("http://www.shihuo.cn/shaiwu/AjaxSupportAgaist", data, function (data) {
            if (data.status == 200) {
                $('.l-w-grid .recommend-num,.commondup span').text(data.data.snum);
                $('.l-w-grid .oppose-num,.commondown span').text(data.data.anum);

                /*if (type == 1) {
                    $fnA.removeClass("btn-oppose-on");
                    elem.toggleClass("btn-recommend-on");
                } else {
                    $fnA.removeClass("btn-recommend-on");
                    elem.toggleClass("btn-oppose-on");
                }*/
            }
        });

    }
};
messageFnTip.init();

function scrollHandle(){
    var st = $(window).scrollTop(),
        wh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        ot = $(".left-wrapper").offset().top,
        lh = $(".left-wrapper").height(),
        ol = $(".content").offset().left + $(".left-wrapper").width() + 20,
        rt = $(".right-wrapper").height(),
        ft = $(".foot-aboutus").length > 0 ? $(".foot-aboutus").offset().top -74 : $("body").height();
    
    if(st > ot && lh > rt  ){
        if(st + rt >= ft){
            var rt2 = rt - (ft - st);
            $(".right-wrapper").css({"position":"fixed","top":"-"+rt2+"px","left":ol+"px"});  
            $(".scrollwrapper,.right-wrapper").height("auto");
        }else{
            $(".right-wrapper").css({"position":"fixed","top":"0px","left":ol+"px"});  
            if(rt + 10 > wh){
                $(".scrollwrapper,.right-wrapper").height(wh);
            }else{
                $(".scrollwrapper,.right-wrapper").height("auto");
            } 
        }                     
    }else{
        $(".right-wrapper").css({"position":"relative","top":"0px","left":"0px"});
    }
}

$(function(){
    /*显示评论*/
    var hash = window.location.hash;
    if(hash == "message_replies"){
        $("body,html").scrollTop($(".comment").offset().top);
    }

    $(".left-wrapper .say").click(function(){
        $("body,html").animate({"scrollTop":$(".comment").offset().top},400);
    });

	var oldie = $.browser.msie && $.browser.version < 9;
	/*判断旧版本ie兼容清除浮动的伪类*/
	oldie && $('.clearfix').append('<div class="clear"></div>');

    var coverHeader = function(){
        var ws = $(window).scrollTop(),
            wh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
            $frame = $(".frame"),
            ft = $frame.offset().top,
            fh = $frame.height(),
            fl = $frame.offset().left,
            scrollw = $(".bottombox").width();                    
        if(ws >= ft && ws < $(".comment").offset().top){
            $(".coverbox").css({"left":fl+"px","top":"0px"}).fadeIn();    
            var moveratio = (ws - ft)/fh; 
            $(".scrollline").css({"width":Math.round(moveratio*scrollw)+"px"});
        }else if(ws < ft){
            $(".coverbox").fadeOut();
        }
    }

	$(window).scroll(function(event) {
		//scrollHandle();
        coverHeader();
	});
	$(window).resize(function(){
        //scrollHandle();
        coverHeader(); 
    });

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
