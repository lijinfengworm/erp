$(function(){

  //如果有购物车，获取购物车数量
  var cartBadge = $('#js-cart-badge');

  if (cartBadge.length) {
    $.ajax({
        url: 'http://m.shihuo.cn/haitao/cartCount',
        type: 'get',
        dataType: 'json',
        success: function(response) {
            if (response.status == 0) {
                if (response.data.count != 0) {
                    cartBadge.html(response.data.count).show();
                }
            }
        }
    })
  }
});

var nav_list = {
	get_num:2,
	//get_type:channelType,//channelType
	getjson:false,
	offsetArr:[],
	init:function(){
		this.bindFun();
		this.dataSrc();
	},
	bindFun:function(){
		var obj = $(".top_bar"),
		    box = $(".js_show_layer"),
		    more = $(".js_show_more"),
		    that = this;
		obj.find(".ico_list").click(function(event){
			 if(!that.show){
			 	if($(".menuSlide").css("display") == "block" || $(".topmenuSlide").css("display") == "block"){
			 		$(".tabBox .menu,.tabBox .filtermenu").removeClass("on");
			 		$(".menuSlide,.topmenuSlide").hide().find(".inner").removeClass('show');
			 	}
			 	box.show();
                $("body").addClass('noscroll');
			 	that.show = true;
                if(that.show_more){
                    more.hide();
                    that.show_more = false;
                }
			 }else{
            $("body").removeClass('noscroll');
    			 	box.hide();
    			 	that.show = false;
			 }
		});

		$("#shihuo-goods-list").find("li").each(function(){
              that.offsetArr.push(parseInt($(this).offset().top));
		});
    $(window).scroll(function(){
    	that.dataSrc();
    });
	},
	dataSrc:function(){
      var that = this,
          obj = $("#shihuo-goods-list").find("li");
        $(that.offsetArr).each(function(i){
			  if((that.getpageScroll()+screen.height) > that.offsetArr[i] && obj.eq(i).find(".get_imgs img").attr("get") == "false"){
			  	var src = obj.eq(i).find(".get_imgs img").attr("data-src");
			  	obj.eq(i).find(".get_imgs img").attr("get","true").attr("src",src);
				obj.eq(i).find(".loadding").hide();
			  }
		});
	},
	getJson:function(o){
		var str = '',i = 0,
		    that = this;
		$.getJSON('http://m.shihuo.cn/youhui/getYouhuiNews?type='+o.type+'&page='+o.page,function(data){
			if(data.data.length > 0){
				for(;i<data.data.length; i++){
	              	 str += '<li>\
			               <div class="imgs">\
			                    <a href="'+data.data[i].detail_url+'"><img src="'+data.data[i].img_path+'" /></a>\
			               </div>\
			               <div class="message_box">\
			                  <h2><a href="'+data.data[i].detail_url+'">'+data.data[i].title+'</a></h2>\
			                  <p class="money">'+data.data[i].subtitle+'</p>\
			                  <p class="from">'+data.data[i].go_website+'</p>\
			               </div>\
			            </li>';
	              }
	              $(str).appendTo(".shihuo-list");
	              that.get_num+=1;
			}else{
                $(".tips-end").show();
			}
			$(".loding-list").hide();
            that.getjson = false;
		});
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
      },
      _getpageSize: function() {
            var de = document.documentElement, arrayPageSize,
                    w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth,
                    h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
            arrayPageSize = [w, h]
            return arrayPageSize;
      }
}

nav_list.init();

var timTips;
$.ui = $.ui || {};
$.extend($.ui,{
    confirm:function(str,fun){
    	var str = '<div class="confirm">\
					    <div class="confirm-tit">'+str+'</div>\
					    <span class="b1">确定</span><span class="b2">取消</span>\
					</div>\
					<div class="opacityBox"></div>';
        $(str).appendTo("body");
        $(".confirm").show().css({
          left:$(window).width()/2 - $(".confirm").width()/2,
          top:$(window).height()/2 - $(".confirm").height()/2
       });
       $(".opacityBox").show().css({
          height:$(window).height() > $(document).height()?$(window).height():$(document).height()
       });

       $(".confirm").find(".b2").click(function(){
	      $(".confirm").remove();
	      $(".opacityBox").remove();
	   });

	   $(".confirm").find(".b1").click(function(){
	      !!fun && fun();
	      $(".confirm").remove();
	      $(".opacityBox").remove();
	   });
    },
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
	},loginUrl:function(){
        var urlTo="";
        var isAndroid = (/android/gi).test(navigator.appVersion);
        var isIOS     = (/iphone|ipad/gi).test(navigator.appVersion);
        var kanqiu_version = this.getCookie("kanqiu_version");
        if(isAndroid){
            if(kanqiu_version >= "7.0.0"){
                urlTo = "kanqiu://account/account";
            }else{
                urlTo = "http://passport.shihuo.cn/m/2?from=m&project=shihuo&appid=10017&jumpurl="+ encodeURI(window.location.href);
            }
        }else if(isIOS){
            if(kanqiu_version >= "7.0.0"){
                urlTo = "prokanqiu://account/login";
            }else{
                urlTo = "http://passport.shihuo.cn/m/2?from=m&project=shihuo&appid=10017&jumpurl="+ encodeURI(window.location.href);
            }
        }
        return urlTo;
    },getCookie:function(NameOfCookie) {
        if (document.cookie.length > 0) {
            var begin = document.cookie.indexOf(NameOfCookie + "=");
            if (begin != -1) {
                begin += NameOfCookie.length + 1;//cookie值的初始位置
                var end = document.cookie.indexOf(";", begin);//结束位置
                if (end == -1) end = document.cookie.length;//没有;则end为字符串结束位置
                return unescape(document.cookie.substring(begin, end));
            }
        }
        return null;
    }
});

