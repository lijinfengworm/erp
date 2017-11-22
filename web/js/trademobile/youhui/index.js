var _ajaxG     = true;
var _category  = 0;
var _pageSize  = 30; //每页个数
var _type      = "new";

var toggleMenu = {
    selectli : ".top_menu li",
    menu: "#menuSlide",  
    init:function(obj){
        var that = this,
            $menuList  = $(this.menu).find("ul"),
            $menuinner = $(this.menu).find(".inner");
           // $(window).scrollTop(0);        
        if($(obj).attr("data-type") == "menu"){                  
            if($menuinner.hasClass('show')){
                $(obj).removeClass('on');
                $menuinner.removeClass('show');
                $(that.menu).hide();
                return false
            }               
            $(window).scrollTop(0);
            $(that.menu).show();
            that.scaleHeight();
            that.hideScroll();      
            setTimeout(function(){
                $menuinner.addClass('show');
            }, 100);        
        }else{    
            $(".top_menu li").removeClass('on');
            _type = $(obj).attr("data-type");  
            $(".con ul").hide();
            if(isHaitao == 1){
                 localStorage.youhui_order = _type;
                if(localStorage["youhuiList_"+_type]){
                   $("#list_"+_type).html(localStorage["youhuiList_"+_type]);
                }else{
                    loadMore.ajaxData(_type);
                }
            }else{
                 localStorage.haitao_order = _type;
                if(localStorage["haitaoList_"+_type]){
                   $("#list_"+_type).html(localStorage["haitaoList_"+_type]);
                }else{
                    loadMore.ajaxData(_type);
                }
            }
           
             $("#list_"+_type).show();
        }       
        $(obj).addClass('on'); 
        that.hideScroll();        
    },
    scaleHeight: function(){
         var that = this,
            wh = $(window).height(),
            liH = $(that.topmenu).find("li").height();
            menuH = $(that.menu).find(".inner").height()+$(".youhui_list").offset().top;
        var coverH = menuH -wh;        
        if(coverH > 0){
            $(that.menu).find("#menuwrapper").css({"height":"240px"});
            that.initScroll();                
        }else{
            $(that.menu).find("ul").css({"height":"auto","overflow":"visible"});
        }       
    },
    initScroll:function(){
        var myScroll = new IScroll('#menuwrapper', {
                click:true,
                scrollbars: true,
                mouseWheel: true,
                interactiveScrollbars: true,
                shrinkScrollbars: 'scale',
                fadeScrollbars: true
            });       
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
        });
    }

}

$(function() {
    $(".con ul").hide();
    $("#list_new").show();
    $(".top_menu li").on('click',  function(event) {        
        toggleMenu.init(this);
        event.preventDefault();

    });
    //隐藏导航
    $("#hideMenu").on('click',function(event) {
         loadMore.hideSlide();
    });

    //选择导航
    $("#menuSlide .right li").on('click',function(event) {
        if(_category == $(this).attr("data-menuId")){
            return false;
        }
        _category = $(this).attr("data-menuId");        
        $("#tabBox .menu>a").html($(this).find("a").text()+"<span></span>");
        //显示分类数据
        $(".con ul").hide();
        $("#list_"+_type).show();
        loadMore.hideSlide();
        $("#menuSlide .right li").removeClass('on');
        $(this).addClass('on');      
        if(_category){
           loadMore.restart();
        }
    });    
    loadMore.ajaxScroll();
    loadMore.init();
    
});
//下拉加载更多
var loadMore = {
    ajaxLink: "http://m.shihuo.cn/youhui/getYouhuiNews",
    restart:function(){
        $("#list_hot").html("");
        $("#list_new").html("");
        $("#list_hot").attr("data-key","0");
        $("#list_new").attr("data-key","0");
        $("#list_"+_type).show();
         this.ajaxData("new");
         this.ajaxData("hot");
    },
    init:function(){
        if(isHaitao == "1"){
            var t       = localStorage.lastest_youhui_time || 0;
              _type     = localStorage.youhui_order || "new";
              _category = localStorage.youhui_r || 0;
            if(t && _type){
                var timestamp = new Date().getTime();
                var time = timestamp-localStorage.lastest_youhui_time;
                if(time>=10000*60 || localStorage["youhuiList_"+_type] ==""){
                    this.restart();
                }else{
                    $("#list_"+_type).html(localStorage["youhuiList_"+_type]); 
                    $("#tabBox li").removeClass("on");
                    $("#tabBox ."+_type).addClass("on");
                    if(_category){
                        //$("#tabBox .menu").addClass("on");
                        $("#menuwrapper li[data-menuid='"+_category+"']").addClass('on');
                        $("#tabBox .menu>a").html($("#menuwrapper li[data-menuid='"+_category+"'] a").text()+"<span></span>");
                    }
                }
            }else{
                 this.ajaxData(_type);
            }
            $("#list_"+_type).show();
        }else{
            var t     = localStorage.lastest_haitao_time || 0;
            _type     = localStorage.haitao_order || "new";
            _category = localStorage.haitao_r || 0;
            if(t && _type){
                var timestamp = new Date().getTime();
                var time = timestamp-localStorage.lastest_haitao_time;
                if(time>=10000*60 || localStorage["haitaoList_"+_type] ==""){
                    this.restart();
                }else{
                    $("#list_"+_type).html(localStorage["haitaoList_"+_type]); 
                    $("#tabBox li").removeClass("on");
                    $("#tabBox ."+_type).addClass("on");
                    if(_category){
                       // $("#tabBox .menu").addClass("on");
                        $("#menuwrapper li[data-menuid='"+_category+"']").addClass('on');
                        $("#tabBox .menu>a").html($("#menuwrapper li[data-menuid='"+_category+"'] a").text()+"<span></span>");
                    }
                }
            }else{
                 this.ajaxData(_type);
            }
            $("#list_"+_type).show();
        }
    },ajaxScroll: function() {
        var that = this;//页面滚动
        $(window).scroll(function() {
            $("#menuSlide").css({"height":$(document).height()});
          if ($(window).scrollTop()>=$(document).height()-$(window).height()&& _ajaxG) {
            $("#loadding").show();
            loadMore.ajaxData(_type);
          }
        });
    },hideSlide:function(){
        $(".top_menu .menu").removeClass('on');   
        $("#menuSlide .inner").removeClass('show');
        setTimeout(function(){
            $("#menuSlide").hide();
            $(window).scrollTop(0);
        }, 100);

    },ajaxData:function(_type){
        _ajaxG = false;
        var _key = $("#list_" + _type+" li:last-child").attr("data-key");
        var _dataStr ={"key": _key || "", "type": isHaitao,"order":_type,"r":_category,"pagesize":_pageSize};
        var _html ="";
        $.post(this.ajaxLink,_dataStr, function(data) {
            if(data.status == "0"){
                if(isHaitao == "2") {
                    __dace.sendEvent('shihuo_m_dace_haitao_page_' + _key);
                } else {
                    __dace.sendEvent('shihuo_m_dace_youhui_page_' + _key);
                }

                $("#loadding").hide();
                _ajaxG = true;
                var lastKey = "";
                $.each(data.data, function(index, val) {
                    _html +='<li data-key="'+val.data_key+'"><a href="'+ val.detail_url+'" class="link-a">' +
                    '<div class="imgs"><img src="'+ val.img_path +'" alt=""></div>' +
                    '<div class="details_box"> <h2>' + val.title + '</h2>' +
                    '<p class="money"><span>'+ val.subtitle +' </span></p> ' +
                    '<div class="remain_time">' + val.go_website +
                    '<div class="fr">' + val.remain_time +'</div></div>' +
                    '</div> </a> </li>';
                });
                $("#list_" + _type).append(_html);
                 if(isHaitao == "1") {
                    localStorage.lastest_youhui_time =  new Date().getTime();
                    localStorage.youhui_r     = _category;
                    localStorage["youhuiList_"+_type] = $("#list_" + _type).html();
                }else{
                    localStorage.lastest_haitao_time =  new Date().getTime();
                    localStorage.haitao_r     = _category;
                    localStorage["haitaoList_"+_type] = $("#list_" + _type).html();                  
                }

            }
        },"json");
    }
}
