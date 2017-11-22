var bjQushi = {
	init:function(o){
        var str = '<style type="text/css">\
            body{_background-image:url(about:blank);_background-attachment:fixed; margin:0;padding:0;}\
            .bj-links{position:fixed;_position:absolute;left:'+o.left+'px; top:'+o.top+'px;_top:expression(documentElement.scrollTop+'+o.top+'+"px");}\
        </style>\
         <div class="bj-links">\
             <img src="/images/trade/baoliao/bijia.jpg" />\
         </div>';
         $(str).appendTo('body');
	}
}