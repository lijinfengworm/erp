window.$ = Zepto; 
$.extend($.fn, {
       /*页面很长，弹出浮层的时候，禁止页面滚动，取消浮层的时候，再次可以滚动*/ 
       preventScroll : function(e){ 
           this.off('touchmove').on('touchmove', function(e){
                e.preventDefault();
           });
       }
 });

(function($){
	$.fn.hover = function(){
		if (this && this[0] && this[0].nodeName.toUpperCase() != "A") {
			this.on("touchstart", touchHover.start);
			this.on("touchmove", touchHover.move);
			this.on("touchend", touchHover.end);
		}
        return this;
	}

	$.fn.removeHover = function(){
		this.off("touchstart", touchHover.start);
		this.off("touchmove", touchHover.move);
		this.off("touchend", touchHover.end);
        return this;
    }

    var touchHover = {
        start: function(e){
            var _this = $(this);
            var _t = setTimeout(function(){
                _this.attr("hover", "on");
            }, 100);
            _this.attr("hoverTimeout", _t);
        },
        move: function(e) {
            var _this = $(this);
            clearTimeout( _this.attr("hoverTimeout") );
            _this.removeAttr("hoverTimeout");
            _this.removeAttr("hover");
        },
        end: function(e){
            var _this = $(this);
            clearTimeout( _this.attr("hoverTimeout") );
            var _t = setTimeout(function(){
                _this.removeAttr("hover");
            }, 100);
            _this.attr("hoverTimeout", _t);
        }
    };
    
	$.vui = {}
	$.vui.click = "click";
	$.vui.isAndroid	= (/android/gi).test(navigator.appVersion);
	$.vui.isIOS = (/iphone|ipad/gi).test(navigator.appVersion);
	$.vui.isWeixin = (/MicroMessenger/gi).test(navigator.userAgent);

	$.vui.isPhone = function(str){//手机
		 var reg = /^0{0,1}(13[0-9]|15[0-9]|17[0-9]|14[0-9]|18[0-9])[0-9]{8}$/;
   		 return reg.test(str);
	}
	
	if ($.vui.isIOS) {
		$.vui.click = "touchend";
	}

	// resize事件，第二个参数为true，默认先执行一次
	$.vui.resize = function(fn, start) {
		if (start) {
			fn();
		}
		if (window.orientation != undefined) {
			$(window).on("orientationchange", fn);
		} else {
			$(window).on("resize", fn);
		}
	}

	// 显示loading框
	$.vui.loading = function(txt){
		txt = txt ? txt : "加载中...";
		var _html = "<div id='_vload_'><div class='inner'><div class='con'><div class='loading' type='12'></div><span>" + txt + "</span></div></div></div>";
		$("#_vload_").remove();
		$("body").append(_html);
		$("#_vload_").data("remove", "false")
		setTimeout(function(){
			$("#_vload_").addClass("show").on("touchstart", $.vui.stopDefault);
		},0);
	};

	// 隐藏loading框
	$.vui.unloading = function(){
		$("#_vload_").removeClass("show").data("remove", "true");
		setTimeout(function(){
			if ($("#_vload_").data("remove") == "true") {
				$("#_vload_").remove();
			}
		}, 200);
	}
    //提示框
    $.vui.remind = function(msg) {
       if (!msg || typeof msg !== 'string') {
           return false;
       }
       var _html = "<div id='_vvalert_'><div class='box'><p>" + msg + " </p></div></div>";
       
       if($("#_vvalert_").length > 0){
           $("#_vvalert_").remove();
       }
       $("body").append(_html);
       var _alert = $("#_vvalert_");
       setTimeout(function() {
           _alert.addClass("show");
       }, 0);
       _alert.removeClass("show");
       setTimeout(function() {
          _alert.remove();
       }, 2000);
    }
	//alert提示框
	$.vui.alert = function(msg, o){
		if(!msg || typeof msg !== 'string'){
			return false;
		}

		$.vui.inputBlur();

		if ($("#_valert_").length) {
			$("#_valert_").remove();
		}

		var _html = "<div id='_valert_'>";

		if (o) {
			o.title = o.title ? o.title : "提示";
			if(!o.btnN){
				o.btnY = o.btnY ? o.btnY : "确定";
				_html += "<div class='box'><h6 class='header'>" + o.title + "</h6><p class='text'>" + msg + "</p><div class='btns'><a class='btnY' href='javascript:void(0);'>" + o.btnY + "</a></div></div>";
			}else{
				o.btnY = o.btnY ? o.btnY : "是";
				o.btnN = o.btnN ? o.btnN : "否";

				_html += "<div class='box'><h6 class='header'>" + o.title + "</h6><p class='text'>" + msg + "</p><div class='btns'><a class='btnY yes' href='javascript:void(0);'>" + o.btnY + "</a><a class='btnN no' href='javascript:void(0);'>" + o.btnN + "</a></div></div>";
			}
			
		} else {
			o = {
				title: "提示",
				btnY: "确定"
			};
			_html += "<div class='box'><h6 class='header'>" + o.title + "</h6><p class='text'>" + msg + "</p><div class='btns'><a class='btnY' href='javascript:void(0);'>" + o.btnY + "</a></div></div>";
		}
		
		_html += "</div>";
		$("body").append(_html).css({"pointer-events": "none"});

		var _alert = $("#_valert_");
		setTimeout(function(){
			_alert.on("touchmove", $.vui.stopDefault).addClass("show");
		}, 0);
		var btns = _alert.find(".btns>a");
		btns.on($.vui.click, function(){
			var _this = $(this);
			if (!$.vui.touchmoved) {
				_alert.removeClass("show").one("webkitTransitionEnd", function(){
					if (!_alert.hasClass("show")) {
						_this.off();
						_alert.remove();
						$("body").css({"pointer-events": "auto"});

						
						if(!o.btnN){
							if(o.callback){
								o.callback();
							}
						}else{
							if (_this.hasClass("yes")) {
				                if (o.yesFn) {
				                    o.yesFn();
				                } 
			                }else {
			                    if (o.noFn) {
			                        o.noFn();
			                    }
			                }
						}
						
					}
					return false;
				});
			} else {
				$(this).removeAttr("hover");
			}
			return false;
		});
	}

	// input框失去焦点
	$.vui.inputBlur  = function(e){
		if ($.vui.blurInput) {
			$.vui.blurInput.blur();
		}
    }

	function init(){

		// 手指按下后有没有移动的判断
		$("body").on("touchstart", function(e){
			$.vui.touchmoved = false;
		});

		$("body").on("touchmove", function(e){
			$.vui.touchmoved = true;
		});
		

		// input获取和失去焦点
		$("body").delegate("input", "focus", function(e){
			$.vui.blurInput = $(this);
		});

		$("body").delegate("input", "blur", function(e){
			$.vui.blurInput = null;
		});

		// body添加是否为ios的标识
		if ($.vui.isIOS) {
			$("body").addClass("IOS");
		} else {
			$("body").addClass("notIOS");
		}

		// a标签点击高亮
		$("body").on("touchstart", "a:not([noHover])", touchHover.start);
		$("body").on("touchmove", "a:not([noHover])", touchHover.move);
		$("body").on("touchend", "a:not([noHover])", touchHover.end);

		// body定高
		$.vui.resize(function(){
			$("body").height($(window).height());
		}, false);
	}
	$(init);

})(Zepto); 


var timTips;
$.ui = $.ui || {};
$.extend($.ui,{
    tips:function(o,fun){
        var str = '<div class="tips-box">'+o+'</div>';
        if(timTips){
           clearTimeout(timTips);
           $(".tips-box").remove();
        }  
        $(str).appendTo('body');
        $(".tips-box").css({
            left:$(window).width()/2 - $(".tips-box").width()/2,
            top:$(window).height()/2 - 10
        });
        
        timTips = setTimeout(function(){
           $(".tips-box").remove();  
           !!fun && fun();
        },1000);
    }
});
