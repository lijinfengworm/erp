var _ajaxG     = true;
var _pageSize  = 30; //每页个数
var _category  = 0;
var _child     = 0;
var _order     = 2;
var _keywords  = "";//搜索关键字
var _price     = "";
var _brand     = "";
var _key       = "";
var _locals    = 0;
var _recommend = 1;//是否有推荐，0下拉滚动禁止
var myScroll_child,myScroll; 
  
var toggleMenu = {
    selectli : ".top_menu li",
    menu: "#menuSlide",
    topmenu : "#topmenuSlide",  
    now_menu :"filtermenu",  
    init:function(obj){
        var that = this,
            _type = $(obj).attr("data-type"),
            $menuList = $(this.menu).find("ul"),
            $menuinner = $(this.menu).find(".inner"),
            $topmenuList = $(this.topmenu).find("ul"),
            $topmenuinner = $(this.topmenu).find(".inner");           

        $(that.selectli).removeClass('on');   
        $(obj).addClass('on');
                        
        if(_type == "menu"){
            $topmenuinner.removeClass('show');                  
            if($menuinner.hasClass('show')){
                $(obj).removeClass('on');
                $menuinner.removeClass('show');
                $(that.menu).hide();
                return false
            }   
            $topmenuList.hide();             
            $(window).scrollTop(0);
            $(that.topmenu).hide(); 
            $(that.menu).show();
            $("#"+_type+"list").show();
            setTimeout(function(){
               $menuinner.addClass('show');
                that.hideScroll(); 
            }, 100);        
        }else{
            $menuinner.removeClass('show');
            if($topmenuinner.hasClass('show')){  
                $topmenuinner.removeClass('show');
                $(that.topmenu).hide();
                $topmenuList.hide(); 
                if(this.now_menu == _type){
                    $(obj).removeClass('on');
                    return false;
                }
            }               
            $(window).scrollTop(0);
            $(that.menu).hide();      
            $(that.topmenu).show();
            this.now_menu = _type;
            $("#"+_type+"list").show();
            setTimeout(function(){                
                $topmenuinner.addClass('show'); 
                that.hideScroll();              
            }, 100);
        }
               
    },
    hideScroll:function(){
        var that = this;                        
        $(window).scroll(function(){                       
            var st = $(window).scrollTop(),
                $vScroll = $(that.menu).find(".inner").hasClass('show') ?  $(that.menu) : $(that.topmenu),                
                vHeight = $vScroll.find(".right").height();                
            if(st > vHeight){                
                $vScroll.hide().find(".inner").removeClass("show");
                $("#topmenuSlide .top ul").hide();
                $("#tabBox li").removeClass("on");
            }   
        })
    }
}
$(function () { 
    var str = 1;
    $(".top_menu li").on('click',  function(event) {
        toggleMenu.init(this); 
        if(str){
            myScroll = new IScroll('#menuwrapper', {
                click:true
            }); 
            myScroll_child = new IScroll('#menuwrapper_child', {
                click:true,
                mouseWheel: true
            });  
            str = 0;
        }           	
        event.preventDefault();
    });
    //隐藏导航
    $("#hideMenu").on('click',function(event) {
         loadMore.hideSlide();
    });
    $("#tophideMenu").on('click',function(event) {
        $("#topmenuSlide .top ul").hide();
        $(".top_menu li").removeClass('on');  
        $("#menuSlide .inner").removeClass('show');
        setTimeout(function(){
            $("#menuSlide").hide();
            $(window).scrollTop(0);
        }, 300);
    });
    // loadMore.htmlData();
    loadMore.ajaxScroll(); 


    $(".searchBox").addClass("focus");
    $(".search").addClass("focus");
    $(".search .submit").show();

    $(".searchBox .input").focus(function(){
        // if($(".searchBox").hasClass('focus')){
        //     $(".reset,.submit").show();
        // }else{
            $(".searchBox .search").addClass("focus"); 
            $(".searchBox .me").hide();
            $(".reset,.submit,.cancel").show();
            $(".searchBox .input").css("color","#ffe2e2");
            if($(".top_bar").hasClass("kanqiu")){
                $(".searchBox .input").css("color","#444");
            }

            $(".searchBox").removeClass("focus"); 
            $(".top_bar .show_more").hide(); 
        // }
            
        $("#history-page").show();
        $("#haitaoList").hide();

        var history = localStorage["haitao_history"];
        if(history){
            var arr = history.split("|");//将字符串转化为数组
            var _html ="";
             for(i=0;i<arr.length;i++){
                var searchKey = unescape(arr[i]);
                _html += '<li><a href="javascript:void(0);">'+ searchKey + '</a></li>';
             }
             $("#history").html(_html);
        }else{
            $("#history").html("");
        }
    });
    $(".searchBox .cancel").click(function(){
         $(".searchBox").addClass("focus"); 
         $(".reset,.submit,.cancel").hide();
         $(".top_bar .show_more").show(); 
         $(".searchBox .input").blur();
         $(".searchBox .input").css("color","#d75353");
         $(".kanqiu .search .submit").show(); 

        $(".kanqiu .show_more").hide();
        $("#history-page").hide();
        $("#haitaoList").show();
    });
    $(".searchBox .submit").click(function(){
        _keywords = $(".searchBox .input").val();
        searchHis(_keywords);
        localStorage.lastest_daigou_time = 0;
        window.location = "http://m.shihuo.cn/daigou?keywords="+_keywords;
        // $("#list_daigou").html("");
        // loadMore.htmlData();

        // $("#haitaoList").show();
        // $("#history-page").hide();
        // $("#cateSlide").hide();

        // $(".searchBox").addClass("focus"); 
        // $(".top_bar .show_more").show(); 
        // $(".reset,.cancel").hide();
    });
    $(".searchBox .reset").click(function(){
         $(".searchBox .input").val("");
   });
   $("#clear_history").click(function(){
        localStorage["haitao_history"]="";
        $("#history").html("");
   });
    $("#history a").live("click",function(){
        localStorage.lastest_daigou_time = 0;
        _keywords = $(this).html();
        window.location = "http://m.shihuo.cn/daigou?keywords="+_keywords;
        // _ajaxG     = true;
        // _pageSize  = 30; //每页个数
        // _category  = 0;
        // _child     = 0;
        // _order     = 2;
        // _price     = "";
        // _brand     = "";
        // _key       = "";

        // localStorage.daigou_category = 0;
        // localStorage.daigou_child    = 0;
        // $("#menulist li").removeClass("on");
        // $("#menulist li:first-child").addClass("on");
        // $("#tabBox .menu a").html("类型<span></span>");
        // $("#menulist_child").html("");
        // _keywords = $(this).html();
        // $(".searchBox .input").val(_keywords);
        // $("#list_daigou").html("");
        // loadMore.htmlData();

        // $("#cateSlide").hide();
        // $("#history-page").hide();
        // $("#menuSlide").hide();
        // $("#menuSlide .inner").removeClass('show');
        // $("#haitaoList").show();
        // $(".searchBox").addClass("focus"); 
        // $(".top_bar .show_more").show(); 
        // $(".reset,.cancel").hide();
    });
    function searchHis(_searchVal){
        if(_searchVal){
            if(localStorage["haitao_history"]){
               var history = localStorage["haitao_history"];
                Array.prototype.in_array = function(e){
                for(i=0;i<this.length && this[i]!=e;i++);
                  return !(i==this.length);
                }
                var arr = history.split("|");//将字符串转化为数组
                if(!arr.in_array(escape(_searchVal))){
                 arr.unshift(escape(_searchVal));
                 if(arr.length>10){
                    arr.pop();
                 }
                }
                localStorage['haitao_history'] = arr.join("|");
            }else{
                localStorage["haitao_history"] = escape(_searchVal);
            }
       }
    }

});
//下拉加载更多
var loadMore = {
    ajaxLink: "http://m.shihuo.cn/daigou/getDaigouData",
    ajaxScroll: function () {
        var that = this;//页面滚动   
        //如果下一条没有的话 localStorage.lastest_daigou_time为空            
        $(window).scroll(function () {    
        	$("#menuSlide").css({"height":$(document).height()});                
        	$("#topmenuSlide").css({"height":$(document).height()-$(".top_menu").height()-$(".top_bar").height()});              
            if ($(window).scrollTop() >= $(document).height() - $(window).height() && _ajaxG && _recommend) {   
                _key = $("#list_daigou li:last-child").attr("data-key") || "";
                localStorage.lastest_daigou_time = "0";
                that.htmlData();
            }
        });
    },
    hideSlide:function(){
        $("#topmenuSlide .top ul").hide();
    	$(".top_menu li").removeClass('on');    	
    	$("#topmenuSlide .inner").removeClass('show');
        setTimeout(function(){
            $("#topmenuSlide").hide();
            $(window).scrollTop(0);
        }, 300);
    },htmlData:function(){
        $("#loadding").show();
        var _html="";
        if(_ajaxG){
            _ajaxG = false;
            var _dataStr = "?order="+_order+"&price="+_price+"&brand="+_brand+"&r="+_category+"&c="+_child+"&pagesize="+_pageSize+"&key="+_key+"&keywords="+_keywords;
            $.get(this.ajaxLink+_dataStr, function (data) {  
                if (data.status == "0") {
                    $("#loadding").hide();
                    if(_key){
                       __dace.sendEvent('shihuo_m_dace_daigou_page_' + _key);
                    }
                    var filter = data.data.filter;
                   
                   if(_price==""){
                        var price = '<li data-price="" class="on"><a href="javascript:void(0);">全部</a></li>';
                        $(".pricemenu a").html("价格<span></span>");
                   }else{
                       var price = '<li data-price=""><a href="javascript:void(0);">全部</a></li>';
                       $(".pricemenu a").html(_price+"<span></span>");
                   }
                    if(filter.price_type){
                        $.each(filter.price_type, function (index, val) {
                            price += '<li data-price="'+val+'"><a href="javascript:void(0);">'+val+'</a></li>';
                        }); 
                    }
                    $("#pricemenulist").html(price);
                    $("#pricemenulist>li[data-price='"+_price+"']").addClass('on');

                    if(_brand==""){
                        var brand = '<li data-brand="" class="on"><a href="javascript:void(0);">全部</a></li>';
                        $(".brandmenu a").html("品牌<span></span>");
                    }else{
                        var brand = '<li data-brand=""><a href="javascript:void(0);">全部</a></li>';
                        $(".brandmenu a").html(_brand+"<span></span>");
                    }
                    if(filter.brand_type){
                       $.each(filter.brand_type, function (index, val) {
                            brand += "<li data-brand='"+val+"'><a href='javascript:void(0);'>"+val+"</a></li>";
                        });
                    }
                    $("#brandmenulist").html(brand);
                    $("#brandmenulist>li[data-brand='"+_brand+"']").addClass('on');
                    if(_order!=0){
                        $("#filtermenulist>li[data-order='"+_order+"']").addClass('on');
                        if(_order == 3){
                             $(".filtermenu a").html("价格升序"+"<span></span>");
                        }else if(_order == 4){
                             $(".filtermenu a").html("价格降序"+"<span></span>");
                        }else{
                            $(".filtermenu a").html($("#filtermenulist>li.on a").html()+"<span></span>");
                        }
                    }
                    $.each(data.data.info, function (index, val) {
                        var title = val.title,activity="";
                        if(!title) {
                            title = val.name;
                        }
                        if (val.goods_id) {
                            var daigou_url = 'http://m.shihuo.cn/daigou/' + val.id + '-' + val.goods_id + '.html';
                        } else {
                            var daigou_url = 'http://m.shihuo.cn/daigou/' + val.id + '.html';
                        }
                        var activity_array = val.activity;
                        for(var i = 0; i<activity_array.length;i++){
                            activity +='<span>'+activity_array[i].detail.mode_short_name+'</span>';
                        }
                        _html += '<li data-key='+val.data_key+'><a href="'+ daigou_url +'" class="link-a">' +
                        '<div class="imgs"><img src="'+ val.img_path +'" alt=""></div>' +
                        '<div class="message_box"> <h2>' + title + '</h2><p class="money"><b><s>¥</s>' + val.price + '</b>'+activity+'</p><div class="from"> <div class="guanzhu">' + val.hits + '已关注</div>商家：'+ val.business +'</div></div></a></li>';
                    });
                    $("#list_daigou").append(_html);
                    $("#list_recommend").hide();
                    _ajaxG = true;
                    _recommend = 1;
                    if(data.data.recommend.length>0){
                        _recommend = 0;var _html2 = "";
                        $("#list_recommend").show();
                        $.each(data.data.recommend, function (index, val) {
                            var title = val.title;
                            if(!title) {
                                title = val.name;
                            }
                            if (val.goods_id) {
                                var daigou_url = 'http://m.shihuo.cn/daigou/' + val.id + '-' + val.goods_id + '.html';
                            } else {
                                var daigou_url = 'http://m.shihuo.cn/daigou/' + val.id + '.html';
                            }
                            _html2 += '<li data-key='+val.data_key+'><a href="'+ daigou_url +'" class="link-a">' +
                            '<div class="imgs"><img src="'+ val.img_path +'" alt=""></div>' +
                            '<div class="message_box"> <h2>' + title + '</h2><p class="money"><b><s>¥</s>' + val.price + '</b></p><div class="from"> <div class="guanzhu">' + val.hits + '已关注</div>商家：'+ val.business +'</div></div></a></li>';
                        });
                        $("#ul_recommend").html(_html2); 
                    } 
                    var href = "http://m.shihuo.cn/daigou"+_dataStr;
                    window.history.replaceState(null, null, href);
                    localStorage.lastest_daigou_time = backRefresh.timeInterval();
                    localStorage.daigouList_all = $("#list_daigou").html();
                    if($("#list_daigou li").length == 0){
                        $(".daigou_list .null").show();
                    }else{
                        $(".daigou_list .null").hide();
                    }
                }                                        
            }, "json");
        }
    },bindClick:function(){
         //选择导航  -- 人气最高
        $("#filtermenulist li").live('click',function() {
            if(_order == $(this).attr("data-order")){
                return false;
            }
            _order =  $(this).attr("data-order");
            var $topmenu = $("#tabBox .filtermenu");
            $("#filtermenulist li").removeClass('on');
            $(this).addClass('on');
            if(_order == 3){
                $topmenu.find("a").html("价格升序"+"<span></span>");
            }else if(_order == 4){
                $topmenu.find("a").html("价格降序"+"<span></span>");
            }else {
                $topmenu.find("a").html($(this).find("a").text()+"<span></span>");
            }
            $("#filtermenulist").hide();
            loadMore.hideSlide();

            if(_order){   
                _key = "";
                localStorage.daigou_order = _order;
                $("#list_daigou").html("");
                loadMore.htmlData();
                return false;
            }
        });
        //选择导航  -- 价格
        $("#pricemenulist li").live('click',function() {
            if(_price ==  $(this).attr("data-price")){
                return false;
            }
            _price = $(this).attr("data-price");
            var $topmenu = $("#tabBox .pricemenu");
            $("#pricemenulist li").removeClass('on');
            $(this).addClass('on');
            var text = $(this).find("a").text().replace(/[^\x00-\xff]/g,"aa").length>8?$(this).find("a").text().substr(0,4)+".." : $(this).find("a").text();
            if(_price != ""){
                $topmenu.find("a").html(text+"<span></span>");
            }else{
                $topmenu.find("a").html("价格<span></span>");
            }
            $("#pricemenulist").hide();
            loadMore.hideSlide();
            localStorage.daigou_price = _price;
            _key = "";
            $("#list_daigou").html("");
            loadMore.htmlData();

            return false;
            
        });
        //选择导航  -- 品牌
        $("#brandmenulist li").live('click',function() {
            if(_brand == $(this).attr("data-brand")){
                return false;
            }
            _brand =  $(this).attr("data-brand");

            var $topmenu = $("#tabBox .brandmenu");
            $("#brandmenulist li").removeClass('on');
            $(this).addClass('on');
            var text = $(this).find("a").text().replace(/[^\x00-\xff]/g,"aa").length>8?$(this).find("a").text().substr(0,4)+".." : $(this).find("a").text();
             if(_brand != ""){
                $topmenu.find("a").html(text+"<span></span>");
            }else{
                $topmenu.find("a").html("品牌<span></span>");
            }
            $("#brandmenulist").hide();
            loadMore.hideSlide();

            localStorage.daigou_brand = _brand;
            _key = "";
            $("#list_daigou").html("");
            loadMore.htmlData();
            return false;
            
        });
        //选择类型
        $("#menulist li").live('click',function(event) {
            var $topmenu;
            if($(this).attr("data-category") == 0){
               // $("#menulist_child").hide();
            }else if(_category == $(this).attr("data-category")){
                return false;
            }else{
                $("#menulist_child").show();
            }
            _category = $(this).attr("data-category");
            var str = "";
            if(_category != 0){
                str ='<li data-child="0"><a href="javascript:void(0);"><span></span>全部</a></li>';
                $("#menulist_child").html(str+_childstr[_category]);
            }else{
                $("#menulist_child").html("");  
                //隐藏
                $(".top_menu li").removeClass('on');  
                $("#menuSlide .inner").removeClass('show');
                setTimeout(function(){
                    $("#menuSlide").hide();
                    $(window).scrollTop(0);
                }, 400);
            }
            myScroll_child.refresh();//滚动条刷新高度
            myScroll_child.scrollTo(0, 0);
            _child    = 0;
            $topmenu = $("#tabBox .menu");
            $("#menulist li").removeClass('on');
            if(_category != 0){
                var text = $(this).find("a").text().replace(/[^\x00-\xff]/g,"aa").length>8?$(this).find("a").text().substr(0,4)+".." : $(this).find("a").text() ;
                $topmenu.find("a").html(text+"<span></span>");
            }else{
                $topmenu.find("a").html("类型<span></span>");
            }
           
            $(this).addClass("on");
            localStorage.daigou_category = _category;
            localStorage.daigou_child    = 0;
            _key = "";
            $("#list_daigou").html("");
            loadMore.htmlData();
            return false;
        });
        //选择子类
        $("#menulist_child li").live('click',function(event) {
            var $topmenu;
            // if(_child == $(this).attr("data-child")){
            //     return false;
            // }
            _child = $(this).attr("data-child");
            $topmenu = $("#tabBox .menu");
            $("#menulist_child li").removeClass('on');
            $(this).addClass('on');   
            if(_child != 0){
                var text = $(this).find("a").text().replace(/[^\x00-\xff]/g,"aa").length>8?$(this).find("a").text().substr(0,4)+".." : $(this).find("a").text() ;
                $topmenu.find("a").html(text+"<span></span>");
            }else{
                var text = $("#menulist li.on a").text();
                text = text.replace(/[^\x00-\xff]/g,"aa").length>8?text.substr(0,4)+".." :text;
                $topmenu.find("a").html(text+"<span></span>");
            }
            
            //隐藏导航
            $(".top_menu li").removeClass('on');  
            $("#menuSlide .inner").removeClass('show');
            setTimeout(function(){
                $("#menuSlide").hide();
                $(window).scrollTop(0);
            }, 300);
            localStorage.daigou_child  = _child;
            _key = "";
            $("#list_daigou").html("");
            loadMore.htmlData();
            return false;
        });
    }
}
var backRefresh = {
    init:function(){ 
        var search = backRefresh.getUrlParam('search');
        if(search == "all"){
            localStorage.daigouList_all = "";
            _ajaxG     = true;
            _pageSize  = 30; //每页个数
            _category  = 0;
            _child     = 0;
            _order     = 2;
            _price     = "";
            _brand     = "";
            _key       = "";
            loadMore.htmlData();
        }else{
            var t = localStorage.lastest_daigou_time;
            if(t>0){
                var time = this.timeInterval()-localStorage.lastest_daigou_time;
                if(time>=10000*60){//如果超时
                    localStorage.daigouList_all = "";
                    _ajaxG     = true;
                    _pageSize  = 30; //每页个数
                    _category  = 0;
                    _child     = 0;
                    _order     = 2;
                    _price     = "";
                    _brand     = "";
                    _key       = "";
                    //从第一条开始
                     // 搜索初始化
                    var category = backRefresh.getUrlParam('r');
                    var child    = backRefresh.getUrlParam('c');
                    var keywords = backRefresh.getUrlParam('keywords');
                    if(category>0){
                        _category = category;
                    }
                    if(child>0){
                        _child = child;
                    }
                    if($.trim(keywords)){
                        _keywords = keywords;
                        $(".searchBox .input").val(_keywords);
                    }
                    $("#menulist_child").html("");  
                    $("#list_daigou").html(""); 
                    loadMore.htmlData();
                }else{
                    _locals  =  1;//如果走缓存
                    _order   = localStorage.daigou_order || 2;
                    _price   = localStorage.daigou_price || 0;
                    _brand   = localStorage.daigou_brand || 0;
                    _category = localStorage.daigou_category || 0;
                    _child    = localStorage.daigou_child || 0;
                     // 搜索初始化
                    var category = backRefresh.getUrlParam('r');
                    var child    = backRefresh.getUrlParam('c');
                    var keywords = backRefresh.getUrlParam('keywords');
                    if(category>0){
                        _category = category;
                    }
                    if(child>0){
                        _child = child;
                    }
                    if($.trim(keywords)){
                        _keywords = keywords;
                        $(".searchBox .input").val(_keywords);
                    }
                      var str = "";
                    if(_category != 0){
                        str ='<li data-child="0"><a href="javascript:void(0);"><span></span>全部</a></li>';
                        $("#menulist_child").html(str+_childstr[_category]);
                        if(_child>0){
                            $("#menulist_child>li[data-child='"+_child+"']").addClass('on');
                        }
                    }else{
                        $("#menulist_child").html("");  
                    }
                    $("#list_daigou").html(localStorage.daigouList_all); 
                    loadMore.htmlData();
                }
            }else{
                $("#menulist_child").html(""); 
                // 搜索初始化
                var category = backRefresh.getUrlParam('r');
                var child    = backRefresh.getUrlParam('c');
                var keywords = backRefresh.getUrlParam('keywords');
                if(category>0){
                    _category = category;
                }
                if(child>0){
                    _child = child;
                }
                if($.trim(keywords)){
                    _keywords = keywords;
                    $(".searchBox .input").val(_keywords);
                }
                var str = "";
                if(_category != 0){
                    str ='<li data-child="0"><a href="javascript:void(0);"><span></span>全部</a></li>';
                    $("#menulist_child").html(str+_childstr[_category]);
                    $("#menulist li").removeClass("on");
                    $("#menulist>li[data-category='"+_category+"']").addClass('on');
                    if(_child>0){
                        $("#menulist_child>li[data-child='"+_child+"']").addClass('on');
                        $("#tabBox .menu a").html($("#menulist_child>li[data-child='"+_child+"']").text()+"<span></span>");
                    }else{
                        $("#menulist_child>li[data-child='0']").addClass('on');
                        $("#tabBox .menu a").html($("#menulist>li[data-category='"+_category+"']").text()+"<span></span>");
                    }

                }else{
                    $("#menulist_child").html("");  
                }
                loadMore.htmlData();
            }
        }
        loadMore.bindClick();
    },newInit:function(){
        _order    = backRefresh.getUrlParam('order') || 2;
        _price    = backRefresh.getUrlParam('price') || "";
        _brand    = backRefresh.getUrlParam('brand') || "";
        _category = backRefresh.getUrlParam('r') || 0;
        _child    = backRefresh.getUrlParam('c') || 0;
        _keywords = backRefresh.getUrlParam('keywords') || "";
        _key      = this.getUrlParam("key") || "";
        if(_key != ""){
           $("#list_daigou").html(localStorage.daigouList_all);
        }else{
            loadMore.htmlData();
        }
        if(_category != 0){
            str ='<li data-child="0"><a href="javascript:void(0);"><span></span>全部</a></li>';
            $("#menulist_child").html(str+_childstr[_category]);
            $("#menulist li").removeClass("on");
            $("#menulist>li[data-category='"+_category+"']").addClass('on');
            if(_child>0){
                $("#menulist_child>li[data-child='"+_child+"']").addClass('on');
                $("#tabBox .menu a").html($("#menulist_child>li[data-child='"+_child+"']").text()+"<span></span>");
            }else{
                $("#menulist_child>li[data-child='0']").addClass('on');
                $("#tabBox .menu a").html($("#menulist>li[data-category='"+_category+"']").text()+"<span></span>");
            }
        }else{
            $("#menulist_child").html("");  
        }
        $(".search .input").val(_keywords);
        loadMore.bindClick();
    },timeInterval:function(){
        var timestamp = new Date().getTime();
        return timestamp;
    },getUrlParam:function(name){
       // console.log(window.location.search.substr(1));
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r!=null) return decodeURIComponent(r[2]); return null;
    }
}; 

// 浏览器前进后退事件
if (history.pushState) {
    // 默认载入
    backRefresh.newInit();
}
