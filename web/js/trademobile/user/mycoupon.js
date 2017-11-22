var _ajaxG     = true;
var _pageSize  = 30; //每页个数
var _category  = "all";
var _order     = "now";
var _page      = 1;

var toggleMenu = {
    selectli : ".top_menu li",
    menu :"allMenu",
    topmenu : "#topmenuSlide",    
    init:function(obj){
        var that = this,
            $topmenuList = $(this.topmenu).find("ul"),
            $topmenuinner = $(this.topmenu).find(".inner");           

        $(that.selectli).removeClass('on');        
        $(obj).addClass('on');
                        
        if($topmenuinner.hasClass('show')){                     
            $(obj).removeClass('on');
            $topmenuinner.removeClass('show');
            $(that.topmenu).hide();
            if($(obj).attr("data-type") != _type){
                if($(obj).attr("data-type") == "allMenu"){
                    _type = "allMenu";
                    that.menu = _type;
                    $("#tabBox .allMenu").addClass('on');
                }else{
                    _type = "typeMenu";
                    that.menu = _type;
                    $("#tabBox .typeMenu").addClass('on');
                }
            }else{
              return false;  
            }
            
        }else{
             _type = $(obj).attr("data-type") ;
             that.menu = _type;
        }           
        $(window).scrollTop(0);
        $topmenuList.hide();      
        $(that.topmenu).show();
        $("#"+_type+"list").show();
        setTimeout(function(){                
            $topmenuinner.addClass('show');  
            that.hideScroll();              
        }, 100);
    },
    hideScroll:function(){
        var that = this;                        
        $(window).scroll(function(){                       
            var st = $(window).scrollTop(),
                $vScroll = $(that.menu).find(".inner").hasClass('show') ?  $(that.menu) : $(that.topmenu),                
                vHeight = $vScroll.find(".right").height();                
            if(st > vHeight){                
                $vScroll.hide().find(".inner").removeClass("show");
                $("#tabBox li").removeClass("on");
            }   
        })
    }

}
$(function () {    
    $(".top_menu li").on('click',  function(event) {
        toggleMenu.init(this);      
    });
    //隐藏导航
    $("#hideMenu,#tophideMenu").on('click',function(event) {
         loadMore.hideSlide();
    });
    //选择导航
    $("#typeMenulist li").on('click',function(event) {
        var $topmenu;
         if(_order == $(this).attr("data-menuId")){
            return false;
        }
        _order =  $(this).attr("data-menuId"),              
        $topmenu =$("#tabBox li.on"),
        $("#typeMenulist li").removeClass('on'),
        $(this).addClass('on')
        $topmenu.find("a").html($(this).find("a").text()+"<span></span>");
        loadMore.hideSlide();
    });
    $("#allMenulist li").on("click",function(){
        if(_category == $(this).attr("data-menuId")){
            return false;
        }
        _category =  $(this).attr("data-menuId");             
        var $topmenu =$("#tabBox li.on");
        $("#allMenulist li").removeClass('on');
        $(this).addClass('on');
        $topmenu.find("a").html($(this).find("a").text()+"<span></span>");
        loadMore.hideSlide();
    });
    loadMore.ajaxScroll();    
    loadMore.init();    
});
//下拉加载更多
var loadMore = {
    ajaxLink:'http://m.shihuo.cn/user/myCouponAjax',
    ajaxScroll: function () {
        var that = this;//页面滚动   
        //如果下一条没有的话 localStorage.lastest_daigou_time为空            
        $(window).scroll(function () {    
            $("#menuSlide").css({"height":$(document).height()});                
            $("#topmenuSlide").css({"height":$(document).height()-$(".top_menu").height()-$(".top_bar").height()});              
            if ($(window).scrollTop() >= $(document).height() - $(window).height() && _ajaxG) {   
                that.htmlData();
            }
        });
    },
    hideSlide:function(){
        $(".top_menu li").removeClass('on');        
        $("#topmenuSlide .inner").removeClass('show');
        setTimeout(function(){
            $("#topmenuSlide").hide();
            $(window).scrollTop(0);
        }, 300);
        $('#js-show').html(" ");
        _page = 1;
        this.htmlData();
    },init:function(){
        this.htmlData();
    },htmlData:function(){
        $("#loadding").show();
        _ajaxG = false;
        var _html="";
        var _dataStr = {
            "type" : _category=="all"?"": _category,
            "status": _order, 
            "page":_page
        };
        $.post(this.ajaxLink, _dataStr, function (data) {
            if(data.status == "0" && data.data.length>0){
                $("#hasCouponLabel").hide();
                var element = $('#js-show'); 
                tpl = $('#tpl').html();
                var html = _.template(tpl); 
                 _ajaxG = true;
                // 将解析后的内容填充到渲染元素  
                element.append(html(data));
                $("#loadding").hide();
                _page += 1;
            }else{
               $("#loadding").hide();
            }                                      
        }, "json");
    }
}
