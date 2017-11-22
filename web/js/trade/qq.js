!(function($){
    function qq_f(o){
    	this.str = '<style type="text/css">\
           .qq-ask{position:fixed;left:'+(o.left?o.left:0)+'px; top:'+(o.top?o.top:0)+'px; _position:absolute;_top:expression(documentElement.scrollTop+'+(o.top?o.top:0)+'+"px");z-index:90;}\
        </style>\
        <div class="qq-ask">\
           <a href="'+(o.qq?o.qq:"#")+'" target="_blank"><img src="/images/trade/haitao/qq.jpg?v=2" /></a>\
        </div>';
        $(this.str).appendTo("body");
    }

    $.extend({
    	qq_comment:function(o){
    		new qq_f(o);
    	}
    })
})(jQuery);