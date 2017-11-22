var _ajaxG     = true;
var _tab       = "all";
var _isHot     = 0;
var _curTab    = $("#js-all");
var _oldHeight = "";
var _newHeight = "";

$(function(){
    var tabBox = $("#tabBox li");
    tabBox.on("click",function(){
        $("#tabBox li").removeClass("on");
        $(this).addClass("on");
        
        if($(this).attr("data-tab") != _tab){
            _newHeight = $(window).scrollTop();
            $(window).scrollTop(_oldHeight);
            _oldHeight = _newHeight;
           _tab = $(this).attr("data-tab");
           $(".shaiwu-list .list").hide();
           _curTab = $("#js-"+_tab);
           _curTab.show();
           if(_tab == "all"){
                _isHot = 0;
            }else{
                _isHot = 1;
            } 
            localStorage.shaiwuList_isHot = _isHot;
        }        
    });

    loadMore.init();
    loadMore.ajaxScroll();
    loadMore.clickEvent();
});

 
//下拉加载更多
var loadMore = {
    ajaxLink: "http://m.shihuo.cn/shaiwu/listAjax",
    ajaxScroll: function() {
        var that = this;//页面滚动

       $(window).scroll(function() {
            var winHeight = window.innerHeight ? window.innerHeight : $(window).height(), // iphone fix
            closeToBottom = ($(window).scrollTop() + winHeight > $(document).height() - 100);
            if(closeToBottom && _ajaxG) {
                $("#loadding").show();
                loadMore.ajaxData();
            }
      });
    },
    init:function(){
        if(localStorage.shaiwuList_all){
            var time = new Date().getTime()-localStorage.lastest_shaiwu_time;
            if(time<10000*60){
                _isHot = localStorage.shaiwuList_isHot;
                if(_isHot == "1"){
                    $("#tabBox li").removeClass("on");
                    $("#tabBox .hot").addClass("on");
                    $(".shaiwu-list .list").hide();
                    _curTab = $("#js-hot");
                    _curTab.show();
                }else{
                    $("#tabBox li").removeClass("on");
                    $("#tabBox .all").addClass("on");
                    $(".shaiwu-list .list").hide();
                    _curTab = $("#js-all");
                    _curTab.show();
                }
                $("#js-all").html(localStorage.shaiwuList_all);
                $("#js-hot").html(localStorage.shaiwuList_hot);
                return false;
            }else{
              localStorage.shaiwuList_all  = "";
              localStorage.shaiwuList_hot  = "";
            }
        }

        var _dataStr1 ={"isHot": "0"};
        var _dataStr2 ={"isHot": "1"};
        var _html ="";
        var element1 = $('#js-all'), element2 = $('#js-hot'),  
        tpl = $('#tpl').html();
        _ajaxG = false;
        
        $("#loadding").show();
        $.post(this.ajaxLink,_dataStr1, function(data) {
            if(data.status == "0"){
                element1.attr("data-totals", data.pages);
                var html = _.template(tpl); 
                 _ajaxG = true;
                // 将解析后的内容填充到渲染元素  
                element1.append(html(data));
                $("#loadding").hide();
            }else{
               $("#loadding").hide();
               element1.addClass("null"); 
            }
        },"json");
        $.post(this.ajaxLink,_dataStr2, function(data) {
            if(data.status == "0"){
                element2.attr("data-totals", data.pages);
                var html = _.template(tpl);  
                // 将解析后的内容填充到渲染元素  
                element2.append(html(data));
            }else{
               element2.addClass("null"); 
            }
            loadMore.localSt();
        },"json");


    },ajaxData:function(){
        _ajaxG = false;
        _key = $("#js-"+_tab+" li:last-child").attr("data-key") || "";
        var _dataStr ={"key": _key, "isHot": _isHot};
        var _html ="";
        var tpl = $('#tpl').html();

        $.post(this.ajaxLink,_dataStr, function(data) {
            if(data.status == "0"){
                _ajaxG = true;
                var html = _.template(tpl);  
                // 将解析后的内容填充到渲染元素  
                _curTab.append(html(data));
                loadMore.localSt();
            }
            $("#loadding").hide();
        },"json");
    },localSt:function(){
        var html_hot = $("#js-hot").html();
        var html_all = $("#js-all").html();
        localStorage.shaiwuList_all       = html_all;
        localStorage.shaiwuList_hot       = html_hot;
        localStorage.shaiwuList_isHot     = _isHot;
        localStorage.lastest_shaiwu_time = new Date().getTime();
    },clickEvent:function(){
        $(".shaiwu-list .list .lv,.shaiwu-list .list .wa").live("click",function(){
            var id   = $(this).attr("data-id");
            var type = $(this).attr("data-type");

            var ajaxLink  = "http://m.shihuo.cn/shaiwu/supportAgainst";
            var _dataStr  = {"id": id,"type":type};
            var that = this;
            $.post(ajaxLink,_dataStr,function(data) {
                if(data.status == 0){
                     var icon = $(that).find("i"),num = $(that).find("span"),_typeN = $(that).parent(".other").find("."+$(that).attr("data-typeN"));
                    if(icon.hasClass("on")){
                        icon.removeClass("on");
                        num.html(parseInt(num.text())-1);
                    }else{
                        icon.addClass("on");
                        num.html(parseInt(num.text())+1);
                        if(_typeN.find("i").hasClass('on')){
                             _typeN.find('span').text(_typeN.find("span").text()-1);
                             _typeN.find("i").removeClass('on');
                        }
                    }
                }else if(data.status == 1){
                    var url = window.location.href;
                    $.ui.tips("请先登录",function(){
                         location.href = $.ui.loginUrl();
                    });
                    return false;
                }else{
                    $.ui.tips("网络异常");
                }
            },"json");
        });
    }
}