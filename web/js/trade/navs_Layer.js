;(function($){
   var navs = {
       defaults:{
            left:0,
            top:0,
            getJson_url:'http://www.shihuo.cn/shihuo/getAllMenu?from=shihuo'
       },
       arrList:[],
       init:function(){
            var that = navs,arg = arguments;
            if (screen.width >= 1280){
              that.defaults = $.extend(true,{}, that.defaults, arg[0] || {});
              that.addDom();
              that.getAjax();
            }
       },
       addDom:function(){
           var dom = '<style type="text/css">\
                     body{_background-image:url(about:blank);_background-attachment:fixed; margin:0;padding:0;}\
                     .shihuo_layer_nav{width:120px;  background-color:#fff; position:absolute; top:'+(typeof this.defaults.top == "function"?this.defaults.top():this.defaults.top)+'px; left:'+(typeof this.defaults.left == "function"?this.defaults.left():this.defaults.left)+'px; _top:expression('+(typeof this.defaults.top == "function"?this.defaults.top():this.defaults.top)+'+"px"); z-index:90; display:none;}\
                     .shihuo_layer_nav2{position:fixed;_position:absolute;left:'+(typeof this.defaults.left == "function"?this.defaults.left():this.defaults.left)+'px; top:0px;_top:expression(documentElement.scrollTop+"px");}\
                     .shihuo_layer_nav ul{border:1px #eaeaea solid;}\
                     .shihuo_layer_nav ul,.shihuo_layer_nav ol{list-style:none}\
                     .shihuo_layer_nav ol li{list-style-type:none;}\
                     .shihuo_layer_nav ul li{list-style-type:none; height:35px; border-bottom:1px #f3f3f3 solid; text-align:left;}\
                     .shihuo_layer_nav ul li a{display:block; height:27px; padding:8px 0 0 10px; position: relative;}\
                     .shihuo_layer_nav ul li a.on,.shihuo_layer_nav ul li a.onclass{ color:#444444; text-decoration: none; font-weight:bold;}\
                     .shihuo_layer_nav ul li a.onclass2{background-color:#f5f5f5; color:#444444; text-decoration: none;}\
                     .shihuo_layer_nav ul li a:hover{color:#444444; text-decoration: none; }\
                     .shihuo_layer_nav ul li a .icon_box{display:inline-block; width:25px; text-align:center;  margin-right:8px;}\
                     .shihuo_layer_nav ul li a .nav_name_box{display:inline-block; width:63px;}\
                     .shihuo_layer_nav ul li a s{display:inline-block; background:url(/images/trade/menu/icon_shihuo.png); vertical-align: middle;}\
                     .shihuo_layer_nav ul li a .bg{display:inline-block;background:url(/images/trade/menu/icon_shihuo.png) no-repeat -7px -235px; width:6px; height:12px;vertical-align: middle;}\
                     .shihuo_layer_nav ul li a i{position:absolute; display:inline-block; background:url(/images/trade/menu/icon_shihuo.png) no-repeat -0px -366px; width:9px; height:5px; overflow:hidden; left:17px; bottom:-1px; _bottom:-2px;}\
                     .shihuo_layer_nav ul li a s.a_1{background-position:0 -1px; width:21px; height:21px;}\
                     .shihuo_layer_nav ul li a s.a_2{background-position:0 -38px; width:20px; height:18px;}\
                     .shihuo_layer_nav ul li a s.a_3{background-position:-1px -71px; width:18px; height:20px;}\
                     .shihuo_layer_nav ul li a s.a_4{background-position:0 -109px; width:21px; height:16px;}\
                     .shihuo_layer_nav ul li a s.a_5{background-position:-1px -140px; width:18px; height:20px;}\
                     .shihuo_layer_nav ul li a s.a_6{background-position:0 -175px; width:20px; height:18px;}\
                     .shihuo_layer_nav ul li a s.a_7{background-position:-1px -209px; width:19px; height:20px;}\
                     .shihuo_layer_nav ul li a s.a_1001{background-position:-1px -261px; width:15px; height:21px;}\
                     .shihuo_layer_nav ul li a s.a_1002{background-position:-1px -292px; width:18px; height:19px;}\
                     .shihuo_layer_nav ul li a s.a_1003{background-position:0px -320px; width:20px; height:16px;}\
                     .shihuo_layer_nav ul li a s.a_1004{background-position:0px -345px; width:18px; height:12px;}\
                     .shihuo_layer_nav .show_layers{position:absolute; background-color:#fff; left:120px; top:0px; border:1px #ababab solid; width:98px; display:none;}\
                     .shihuo_layer_nav .show_layers a{display:block; height:35px; text-align:center; line-height:35px;}\
                     .shihuo_layer_nav .show_layers a:hover,.shihuo_layer_nav .show_layers a.onclass{ color:#7a7a7a; text-decoration: none; font-weight:bold;}\
                     .shihuo_layer_nav .shihuo-weixin-box{text-align:center; margin-top:8px; border:1px #eaeaea solid; padding:5px 0;}\
                     .shihuo_layer_nav .shihuo-weixin-box p{color:#a41f24; margin-top:3px;}\
                </style>\
               <div class="shihuo_layer_nav">\
                 <ul class="ul_list">\
                       <li><a href="#"><s class="a_1"></s>运动户外<span class="bg"></span></a></li>\
                       <li><a href="#"><s class="a_2"></s>休闲鞋服<span class="bg"></span></a></li>\
                       <li><a href="#"><s class="a_3"></s>电脑数码<span class="bg"></span></a></li>\
                       <li><a href="#"><s class="a_4"></s>家用电器<span class="bg"></span></a></li>\
                       <li><a href="#"><s class="a_5"></s>食品保健<span class="bg"></span></a></li>\
                       <li><a href="#"><s class="a_6"></s>家具百货<span class="bg"></span></a></li>\
                       <li style="border-bottom:0px;"><a href="#"><s class="a_7"></s>其它分类<span class="bg"></span></a></li>\
                 </ul>\
                 <div class="shihuo-weixin-box">\
                      <img src="/images/trade/menu/shihuoApp.jpg" />\
                      <p>轻松扫一扫</p>\
                      <p>下载识货APP</p>\
                 </div>\
                 <div class="shihuo-weixin-box">\
                      <img src="/images/trade/menu/shihuoweixin.jpg" />\
                      <p>微信关注虎扑识货</p>\
                      <p>最好的导购号</p>\
                 </div>\
                 <div class="show_layers">\
                    <ol>\
                        <li><a href="#">运动鞋</a></li>\
                        <li><a href="#">板鞋</a></li>\
                        <li><a href="#">篮球鞋</a></li>\
                        <li><a href="#">跑步鞋</a></li>\
                        <li><a href="#">户外健身服</a></li>\
                    </ol>\
                 </div>\
           </div>';
           $("body").append(dom);
       },
       getAjax:function(){
            var that = this,str1='',str2='',
                add = $(".shihuo_layer_nav"),
                onClassVal = "";
            $.getJSON(that.defaults.getJson_url,function(data){
                for(var i=0,len=data.data.length; i<len; i++){
                    if((typeof rid == "string" && rid == data.data[i].id) || (typeof did == "string" && did == data.data[i].id)){
                       onClassVal = 'class="onclass"';
                    }

                    if(data.data[i].id*1 == 1001 || data.data[i].id*1 == 1004){
                       onClassVal = 'class="onclass2"';
                    }
                    str1 += '<li '+(i==len-1?'style="border-bottom:0px;"':'')+' tit="'+i+'"><a '+(onClassVal != ""?onClassVal:'')+' href="'+data.data[i].url+'">'+(data.data[i].id*1 == 1001 || data.data[i].id*1 == 1004?'<i></i>':'')+'<span class="icon_box"><s class="a_'+(data.data[i].id)+'"></s></span><span class="nav_name_box">'+data.data[i].name+'</span>'+(data.data[i].children!=""?'<span class="bg"></span>':'')+'</a></li>';
                  if (typeof(data.data[i].children) != "undefined"){
                       for(var s=0,l=data.data[i].children.length;s<l;s++){
                            str2 += '<li><a '+(typeof cid == "string" && cid == data.data[i].children[s].id?'class="onclass"':'')+' href="'+data.data[i].children[s].url+'">'+data.data[i].children[s].name+'</a></li>';
                        }
                  }
                  that.arrList[i] = str2;
                  str2 = "";
                  onClassVal = "";
                }
                add.find(".ul_list").html(str1);
                add.show();
                that.getpost();
            });
       },
       getpost:function(){
            var that = this,
                obj = $(".shihuo_layer_nav"),
                offtop = obj.offset().top,
                show_layers = obj.find(".show_layers"),
                time,
                $this;
            function get_post(el){
                var top,
                    val = show_layers.offset().top + show_layers.outerHeight(),
                    win = $(window).height() + $(document).scrollTop();
                if(val > win){
                    top = el.position().top - (val - win);
                }else{
                    top = el.position().top-1;
                }
                return top;
            };

            obj.find(".ul_list").delegate("li","mouseover",function(){
                var getDom = that.arrList[$(this).attr("tit")*1];
                $this = $(this);
                time?clearTimeout(time):"";
                if($(this).find(".a_1004").length != 1 && $(this).find(".a_1001").length != 1){
                  $this.siblings().find("a").removeClass("on");
                  $this.find("a").addClass("on");
                }else{
                  obj.find("a").removeClass("on");
                }
                
                //$(this).find(".bg").css("visibility","hidden");
                if(getDom != ""){
                  show_layers.find("ol").html(getDom);
                  show_layers.css("top",$this.position().top);
                  show_layers.show().css({
                      top:get_post($this)
                  });
                }else{
                  show_layers.hide();
                }
            });

            obj.find(".ul_list").delegate("li","mouseout",function(){
                //$(this).find(".bg").css("visibility","visible");
            });

            obj.mouseout(function(){
                time = setTimeout(function(){
                   show_layers.hide();
                   obj.find(".ul_list").find("li").find("a").removeClass("on");
                },10);
            });
            
            show_layers.mouseover(function(){
               time?clearTimeout(time):"";
            });

            $(window).scroll(function(){
                if(that.getpageScroll() > offtop){
                    obj.addClass("shihuo_layer_nav2");
                }else{
                    obj.removeClass("shihuo_layer_nav2");
                }
            });
            $(window).resize(function(){
               $(".shihuo_layer_nav").css({
                  left:(typeof that.defaults.left == "function"?that.defaults.left():that.defaults.left)
               });
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
        }
   }

   $.extend({
        navsLayer:navs.init
    });
})(jQuery);