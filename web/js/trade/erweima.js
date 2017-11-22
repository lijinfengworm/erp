!(function($){
    function weixin_f(o){
    	this.str = '<div class="weixin-web-box-post">\
    	            <style>\
    	                 body{_background-image:url(about:blank);_background-attachment:fixed;}\
                         .weixin-web-box-post{position:fixed; margin-top: '+o.top+'px;  margin-left: '+o.left+'px;_position:absolute;_top:expression(documentElement.scrollTop+"px");z-index:90; width: 116px; overflow:hidden;}\
                          .weixin-web-box-post .wei-border-box{background: url('+o.img+') no-repeat left top; width: 116px; height:178px;}\
                          .weixin-web-box-post .wei-border-box2{background: url('+o.img2+') no-repeat left top; width: 116px; height:178px; margin-top:10px;}\
                          .weixin-web-box-post .erwei_close{ height: 21px; width: 21px; float: right; cursor: pointer;}\
                          .weixin-web-box-post .erwei_fonts{float:left; margin-top:110px; color:#A41F24; width:116px; text-align:center; line-height:18px;}\
    	            </style>\
                    <div class="wei-border-box">\
                        <div class="erwei_close"></div>\
                        <div class="erwei_fonts">\
                            <p>轻松扫一扫</p>\
                            <p>下载识货APP</p>\
                        </div></div>\
                        <div class="wei-border-box2">\
                        <div class="erwei_close"></div>\
                        <div class="erwei_fonts">\
                            <p>微信关注识货</p>\
                            <p>最好的导购号</p>\
                        </div></div>\
              </div>';
        this.obj = o.obj;      
        this.appendDom();
        this.bindFun();
    }

    weixin_f.prototype = {
    	constructor:weixin_f,
    	appendDom:function(){
            if(this.obj.css("position") != "relative" &&  this.obj.css("position") != "absolute"){
                this.obj.css("position","relative");
            }
    		$(this.str).prependTo(this.obj);
    	},
    	bindFun:function(){
    		var obj = $(".weixin-web-box-post");
    		obj.find(".erwei_close").click(function(){
                obj.hide();
    		});
    	}
    }

    $.extend({
    	weixin_comment:function(o){
    		new weixin_f(o);
    	}
    })
})(jQuery);