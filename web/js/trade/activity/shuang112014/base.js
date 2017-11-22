function layerActivityShow(){
	   var str = '<div id="join-11">\
	      <style type="text/css">\
	           #join-11{position:fixed;_position:absolute;left:0px; top:0px;_top:expression(documentElement.scrollTop+'+($(window).height()/2 - 214)+'+"px"); left:0px; top:0px; z-index:998;}\
           #join-11 .layer-box-11{background: url("http://c1.hoopchina.com.cn/images/trade/activity/shuang112014/rukou.png") no-repeat left top; _background:none;_filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled="true", sizingMethod="corp", src="/images/trade/activity/shuang112014/rukou.png"); width:897px; height:428px;}\
           #join-11 .joins{display:block; width:260px; height:65px; background-color:#000; position: absolute; left:320px; top:270px; opacity:0;filter:alpha(opacity=0);}\
           #join-11 .close{width:60px; height:60px; background-color:#000; position: absolute; right:0px; top:15px; opacity:0;filter:alpha(opacity=0); cursor: pointer;}\
	      </style>\
	      <div class="layer-box-11"><a href="http://www.shihuo.cn/1111" target="_blank" class="joins"></a><div class="close"></div></div>\
	   </div>';
	   $(str).appendTo('body');

	   if(!$.uis.isie6){
	   	  $.uis._showMasks(0.6);
	   }

	   if($.uis.isie6){
	   	   $("#join-11").css({
	         left:$.uis._position($("#join-11"))[0]
		   });
	   }else{
	   	   $("#join-11").css({
	         left:$.uis._position($("#join-11"))[0],
	         top:$.uis._position($("#join-11"))[1]
		   });
	   }

	   $("#join-11").find(".close").click(function(){
            $("#join-11").remove(); 
           if(!$.uis.isie6){
		   	  $.uis._closeMasks(0.6);
		   }
	   });
}

function layerActivityShow2(o){

	var str = '<style type="text/css">\
            body{_background-image:url(about:blank);_background-attachment:fixed; margin:0;padding:0;}\
            .rukou-links{position:fixed;_position:absolute;left:'+o.left+'px; top:'+o.top+'px;_top:expression(documentElement.scrollTop+'+o.top+'+"px");}\
        </style>\
         <div class="rukou-links">\
             <a href="http://www.shihuo.cn/1111" target="_blank"><img src="/images/trade/activity/shuang112014/rukou2.png" /></a>\
         </div>';
         $(str).appendTo('body');
}

$.uis = $.uis || {};
$.extend($.uis, {
    _showMasks: function(a) {
        var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:" + ($(document).outerHeight()+100) + "px; background-color:#000;  z-index:91;'></div>";
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
        var de = document.documentElement, arrayPageSize,
                w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth,
                h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
        arrayPageSize = [w, h]
        return arrayPageSize;
    },
    _position: function(obj) {
        var left = ((this._getpageSize()[0] - parseInt(obj.outerWidth())) / 2);
        var top = ((this._getpageSize()[1] - parseInt(obj.outerHeight())) / 2);
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
    isie6: (!!$.browser.msie && parseInt($.browser.version) <= 6)
});