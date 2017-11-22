/** 
 * @Description: shihuo 
 * @author: lanyang 
 * @Update: 2014-05-21 17:05 
 */ 

(function($){

	// 检测是否支持css2.1 max-width属性
	var isMaxWidth = 'maxWidth' in document.documentElement.style,
		// 检测是否IE7浏览器
		isIE7 = !-[1,] && !('prototype' in Image) && isMaxWidth;
	
	$.fn.autoIMG = function ( options ) {
		var opt = options || {},
			maxWidth = opt.width || this.width();
		
		return this.find('img').each(function (i, img) {
			// 如果支持max-width属性则使用此，否则使用下面方式
			// if (isMaxWidth) return img.style.maxWidth = maxWidth + 'px';
			var $this = this,	
				src = img.src;
			
			// 隐藏原图
			img.style.display = 'none';
			img.removeAttribute('src');
			
			// 获取图片头尺寸数据后立即调整图片
			imgReady(src, function (width, height) {
				// 等比例缩小
				if (width > maxWidth) {
					height = maxWidth / width * height,
					width = maxWidth;
					img.style.width = width + 'px';
					img.style.height = height + 'px';
				};
				// 显示原图
				img.style.display = '';
				img.setAttribute('src', src);
			});
			
		});
	};
	
	// IE7缩放图片会失真，采用私有属性通过三次插值解决
	isIE7 && (function (c,d,s) {s=d.createElement('style');d.getElementsByTagName('head')[0].appendChild(s);s.styleSheet&&(s.styleSheet.cssText+=c)||s.appendChild(d.createTextNode(c))})('img {-ms-interpolation-mode:bicubic}',document);

	/**
	 * 图片头数据加载就绪事件
	 * @see		http://www.planeart.cn/?p=1121
	 * @param	{String}	图片路径
	 * @param	{Function}	尺寸就绪 (参数1接收width; 参数2接收height)
	 * @param	{Function}	加载完毕 (可选. 参数1接收width; 参数2接收height)
	 * @param	{Function}	加载错误 (可选)
	 */
	var imgReady = (function () {
		var list = [], intervalId = null,

		// 用来执行队列
		tick = function () {
			var i = 0;
			for (; i < list.length; i++) {
				list[i].end ? list.splice(i--, 1) : list[i]();
			};
			!list.length && stop();
		},

		// 停止所有定时器队列
		stop = function () {
			clearInterval(intervalId);
			intervalId = null;
		};

		return function (url, ready, load, error) {
			var check, width, height, newWidth, newHeight,
				img = new Image();
			
			img.src = url;

			// 如果图片被缓存，则直接返回缓存数据
			if (img.complete) {
				ready(img.width, img.height);
				load && load(img.width, img.height);
				return;
			};
			
			// 检测图片大小的改变
			width = img.width;
			height = img.height;
			check = function () {
				newWidth = img.width;
				newHeight = img.height;
				if (newWidth !== width || newHeight !== height ||
					// 如果图片已经在其他地方加载可使用面积检测
					newWidth * newHeight > 1024
				) {
					ready(newWidth, newHeight);
					check.end = true;
				};
			};
			check();
			
			// 加载错误后的事件
			img.onerror = function () {
				error && error();
				check.end = true;
				img = img.onload = img.onerror = null;
			};
			
			// 完全加载完毕的事件
			img.onload = function () {
				load && load(img.width, img.height);
				!check.end && check();
				// IE gif动画会循环执行onload，置空onload即可
				img = img.onload = img.onerror = null;
			};

			// 加入队列中定期执行
			if (!check.end) {
				list.push(check);
				// 无论何时只允许出现一个定时器，减少浏览器性能损耗
				if (intervalId === null) intervalId = setInterval(tick, 40);
			};
		};
	})();

})(jQuery);
(function($){
	var countGa = {
		defaults:{
			type : "data-track"
		},
		init: function(){
			var _this = countGa,
				arg = arguments;
			
			return this.each(function(){
				var elem = this;

				// 合并自定义参数和默认参数
				this.defaults = $.extend(true,{}, _this.defaults, arg[0] || {});

				$(this).bind("click", function(){
					_this.getAttr($(this), elem.defaults.type);
				})
			});

		},
		/**
		 * 发送数据
		 */
		getAttr: function(elem, type){
			var data = elem.attr(type);
			commonGa(data);
		}
	}

	$.fn.extend({
        countGa: countGa.init
    });
	
})(jQuery);
function tab(m,c,n,t){;
    for(i=1;i<=t;i++){
        document.getElementById(m+i).className = "";
        document.getElementById(c+i).style.display = "none";
    }
    document.getElementById(m+n).className = "cur";
    document.getElementById(c+n).style.display = "block";
};

function warning(type, errorCode){    
    var warningStr = getWarningString(type, errorCode);
    doWarning(warningStr)
};
function doWarning(str){
    $("#j_tip").remove();
    $("body").prepend('<div id="j_tip" class="tips_up_pop" style="display:none"><div id="j_tip_t"><div id="tips_pop">'+str+'</div></div></div>');
    var TL=popTL("#j_tip");
    $("#j_tip").css({
        top:TL.split("|")[0]+"px",
        left:TL.split("|")[1]+"px"
        });
    $('#j_tip').show();
    setTimeout("$('#j_tip').fadeOut(426);",2130);
};
function popTL(a){
    var b=document.body.scrollTop+document.documentElement.scrollTop, 
    sl=document.documentElement.scrollLeft,
    ch=document.documentElement.clientHeight,
    cw=document.documentElement.clientWidth,
    objH=300,objW=$(a).width(),objT=Number(b)+(Number(ch)-Number(objH))/2,objL=Number(sl)+(Number(cw)-Number(objW))/2;
    return objT+"|"+objL;
};
/*
 *     返回提示的数组
 */
function getWarningArray(type){
    switch(type){
        case 1:
            var lightError = {
                '0' : '回复不存在或已被删除',
                '-1' : '请选登录', 
                '-2' : '出错啦', 
                '-3' : '您已经点亮过了',
                '-4' : '您不能点亮自己',
                '-5' : '小黑屋住户，不能进行操作',
                '-6' : '点亮太频繁，请稍后再试'
            };
            return lightError;
        case 2:
            var replyError = {
                '-1' : '请选登录', 
                '-2' : '回复的内容请控制在1-5000个字之间', 
                '-3' : '该信息已经被锁定，无法回复啦',
                '-4' : '小黑屋住户，无法回复',
                '-5' : '全站封禁用户，无法回复',
                '-6' : '回复太频繁，请稍后再试',
                '-8' : '抱歉您等级太低了，不能发表评论-_-!!!',
                '-9' : '您在新声频道被禁言了，不能发表评论!'
            };
            return replyError;
         case 3:
            var opposeError = {
                '0' : '回复不存在或已被删除',
                '-1' : '请选登录', 
                '-2' : '出错啦', 
                '-3' : '您已经举报过了',
                '-4' : '您不能举报自己',
                '-5' : '小黑屋住户，不能进行操作',
                '-6' : '举报太频繁，请稍后再试'
            };
            return opposeError;
        default:
            return new Array();
    }
};
/*
 * 获取错误提示信息
 */
function getWarningString(type, errorCode){
    var warningArr =  getWarningArray(type);
    return warningArr[errorCode] ? warningArr[errorCode] : '出错啦~'
};

// 支持、反对
(function($,win,doc){
    var messageFnTip = {
        init: function(){
            var _this = this;
            this.$elem = $(".J_message_fn_tip");
            
            // 判断元素不存在，不执行return
            if( !this.$elem.length ) return false;
            
            var $btnRecommend = $(".J_message_btn_recommend"),
                $btnOppose = $(".J_message_btn_oppose"),
                SOURCE_TYPE = 1;

            // 判断是专题
            if(typeof pageType !== "undefined"){
                if( pageType == "topic" ) {
                    SOURCE_TYPE = 2;
                }
            }
            // 支持
            $btnRecommend.bind("click",function(){
                _this.setMessageRecommendData( $(this), SOURCE_TYPE, 1 );
            });

            // 反对
            $btnOppose.bind("click",function(){
                _this.setMessageRecommendData( $(this), SOURCE_TYPE, 2 );
            });    

        },
        // 用户信息
        isLogin: function(){
	        var ua = document.cookie.match(new RegExp("(^| )ua=([^;]*)(;|$)")),data; 
	        if(ua && ua[2]) return true;
	        return ;
	    },
        setMessageRecommendData: function( elem, sourceType, type) {
            if(!this.isLogin()){
                commonLogin('hupu');
                return false;
            };

            var parent = elem.parents(".J_message_fn_tip"),
                $fnA = parent.find(".recommend-box a"),
                // $fraction = parent.find(".fraction"),
                // $fractionNum = parent.find(".fraction-num"),
                $recommendNum = parent.find(".recommend-num"),
                $opposeNum = parent.find(".oppose-num"),
                id = parent.attr("data-message-id");

            var data = {
                    'id' : id,
                    'type' : type,
                    'source' : sourceType
                };

            $.getJSON("http://www.shihuo.cn/message_support_agaist", data, function(data){
                if( data.status == 200 ) {
                    $recommendNum.text( data.data.snum );
                    $opposeNum.text( data.data.anum );

                    if(type == 1) {
                        $fnA.removeClass("btn-oppose-on");
                        elem.toggleClass("btn-recommend-on");
                    } else {
                        $fnA.removeClass("btn-recommend-on");
                        elem.toggleClass("btn-oppose-on");
                    }
                };
                doWarning( data.msg );
            })

        }
        
    }
    messageFnTip.init();

})(jQuery,window,document);

(function($,win,doc){
	var shareBlog = {
		init: function(){
			var _this = this;
			this.$elem = $(".J_shihuo_share");
		    if(!this.$elem.length) return false;

		    this.$item = this.$elem.find("a");

			this.$item.bind("click",function(){
				_this.getCon($(this));
			})		        		
		},
		getShare:function(opt){
	        var scrollW = 600,
	            scrollH = 450,
	            popNum = 0,
	            iTop = (window.screen.availHeight-30-scrollH)/2,
	            iLeft = (window.screen.availWidth-10-scrollW)/2,
	            weiboAppkey = "3033141272",
	            qqtAppKey = "801094981",
	            ralateUid = "2754272121",
	            qzoneSite = "虎扑识货",
	            websiteLink = "",
	            opt = $.extend({
	                element: "",
	                title:   "",
	                link:    "",
	                pic:     ""
	            },opt),
	            element = opt.element,
	            title = opt.title,
	            link = opt.link,
	            pic = opt.pic ? opt.pic : "";

	        switch(element){
	            case "weibo":
	                websiteLink = 'http://service.weibo.com/share/share.php?url='+encodeURIComponent(link)+'&title='+encodeURIComponent(title)+'&pic='+encodeURIComponent(pic)+'&appkey='+encodeURIComponent(weiboAppkey)+'&ralateUid='+encodeURIComponent(ralateUid);
	                break;
	            case "qq" :
	                websiteLink = 'http://v.t.qq.com/share/share.php?url='+encodeURIComponent(link)+'&title='+encodeURIComponent(title + ' （分享自 @hupuvoice)')+'&pic='+encodeURI(pic)+'&appkey='+encodeURIComponent(qqtAppKey);
	                break;
	            case "qzone":
	                websiteLink = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+encodeURIComponent(link)+'&title='+encodeURIComponent(title)+'&pics='+encodeURI(pic)+'&site='+encodeURIComponent(qzoneSite);
	                break;
	            case "renren":
	                websiteLink = 'http://widget.renren.com/dialog/share?link='+encodeURIComponent(link)+'&title='+encodeURIComponent(title)+'&pic='+encodeURIComponent(pic);
	                break;	
	            default:
	                break;		
	        }
	        if(popNum == 0){
	            popNum = 1;
	            window.open(''+websiteLink+'','_blank','scrollbars=no,width='+scrollW+',height='+scrollH+',left='+iLeft+',top='+iTop+',status=no,resizable=yes');
	        }
			
	    },
		getCon: function(elem){
			var parent = elem.parents(".shihuo-index-item"),
				shareName = elem.attr("data-shareName"),
				title = document.title,
				link = location.href,
				pic;

			if(parent.length){
				var itemHd = parent.find(".item-hd h2 a");
				title = itemHd.text();
				link = itemHd.attr("href");
				pic = parent.find(".item-bd-all img").attr("src");
			}else{
				title = $(".detail-title h1").text();
				pic = $(".detail-content-main img").attr("src");
			}
			
		    this.getShare({
                element:  shareName
,                title:   title,
                link:    link,
                pic:     pic
            })
		}
	}
	shareBlog.init();
})(jQuery,window,document); 

(function($){

    var addScroll = {
        init: function(callback){
            callback = callback || function(){};
            var _this = addScroll;

            this.each(function(){
                var $this = $(this);

                $this.bind("scroll",function(){
                    _this.scrollTop($this, callback);
                });
                $this.bind("resize",function(){
                    _this.scrollTop($this, callback);
                });
            })
        },
        scrollTop : function(elem, callback){
            data = {
                scrollTop : elem.scrollTop(),
                windowHeight : elem.width()
            }
            callback(data);
        }
    };
    $.fn.extend({
        addScroll: addScroll.init
    })
})(jQuery);

(function($,win,doc){
	var fixedBuyLink = {
		init: function(){
	        var _this = this;
	        this.elem = $("#J_fixed_buyLink");

	        if(!this.elem.length) return false;

	        this.rePos = $("#J_pos_buyLink");
	        this.timer = null;
	        this.elemTop = this.elem.offset().top;
	        this.commTop = $(".J_message_fn_tip").offset().top;
	        this.scrollTop = 0;

	        $(win).addScroll(function(data){
                _this.onScrollEvent(data);
            })
	    },
	    onScrollEvent: function(data){
	    	var _this = this,
	    		scrollTop = data.scrollTop;

	    	this.timer = setTimeout(function(){
	    		if(_this.elemTop <= scrollTop && _this.commTop >= scrollTop){
	    			if(hp.browser.msie && hp.browser.version == "6.0"){
						_this.elem.css({
							top:document.documentElement.scrollTop - _this.elemTop
						}); 
					}

	    			_this.elem.addClass("shihuo-fixed-buy-link"); 
	    			_this.rePos.addClass("detail-title-zIndex");
	    		}else{
	    			_this.elem.removeClass("shihuo-fixed-buy-link").css({top : 0});
	    			_this.rePos.removeClass("detail-title-zIndex");
	    		}
	    	},100);
	    	
	    	
	    }
	}

	fixedBuyLink.init();
})(jQuery,window,document);
// 图片自动适宜
$(".detail-content-main").autoIMG({
	width : 650
}); 

// ga点击统计
$("a[data-track]").countGa();

// 支持、反对，默认状态
// $.supportStatus();