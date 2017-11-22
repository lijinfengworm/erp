var _ajaxG     = true;
var _category  = "";//菜单-分类
var _brand     = "";//菜单-品牌
var _tab       = "new";
var _order = "";
var _pageSize = 30; //每页个数
$(function () {
    $(".con ul").hide();
    $("#list_new").show();
    $(".top_menu li").on('click', function (event) {
        $(window).scrollTop(0);
        if ($(this).attr("data-type") != "menu") {
            if($(this).attr("data-type") == _tab && _tab == "new"){
                return false;
            }
            _tab = $(this).attr("data-type");
            if (_tab == "price") {
                _pageNum = 1; //设置当前页数
                if (!($(this).hasClass('sort')) && $(this).hasClass('on')) {
                    _order = "desc";
                    $(this).addClass('sort');
                } else {
                    _order = "asc";
                    $(this).removeClass('sort');
                }
                var _dataStr = {
                    "order": _order,
                    "type": _category,
                    "brand": _brand,
                    "pagesize":_pageSize
                };
                loadMore.ajaxTab(_dataStr);
            } else {
                _order= "";
                var _dataStr = {
                    "order": _order,
                    "type": _category,
                    "brand": _brand,
                    "pagesize":_pageSize
                };
                loadMore.ajaxTab(_dataStr);
                $(".top_menu li").removeClass('sort');
            }
            $(".top_menu li").removeClass('on');
            $("#list_new").show();
            $(this).addClass('on');
        } else {
            //显示导航
            $("#menuSlide").show();
            setTimeout(function () {
                $("#menuSlide .inner").addClass('show');
            }, 100);
        }
        event.preventDefault();
    });
    //tab选项
    $("#menuHead span").on('click', function (event) {
        var id = $(this).attr("data-type");
        $("#menuHead span").removeClass('on');
        $(this).addClass('on');

        $("#one,#two").hide();
        $("#" + id).show();
    });
    //选择品牌
    var search =false;
    $("#one li").on('click', function (event) {
        _brand = $(this).find("a").text()!="全部"?$(this).find("a").text():"";
        $("#one li").removeClass('on');
        $(this).addClass('on');
        search =true;
    });
    //选择分类
    $("#two li").on('click',function (event) {
        _category = $(this).find("a").text()!="全部"?$(this).find("a").text():"";

        $("#two li").removeClass('on');
        $(this).addClass('on');
        search =true;
    });
    $("#hideSlide").on('click', function (event) {
        if (search) {
            var _dataStr = {
                "order": "",
                "type" : _category,
                "brand": _brand,
                "pagesize":_pageSize
            };
            if(_tab == "price"){
                _dataStr = {
                    "order": _order,
                    "type" : _category,
                    "brand": _brand,
                    "pagesize":_pageSize
                };
            }
            loadMore.ajaxTab(_dataStr);
        }
    });
    //隐藏导航
    $("#menuSlide .footer>a").on('click', function (event) {
        loadMore.hideSlide();
    });

    loadMore.ajaxScroll();
    loadMore.init();
});

//下拉加载更多
var loadMore = {
    ajaxLink: "http://m.shihuo.cn/shoe/getShoeAjax",
    ajaxScroll: function () {
        var that = this;//页面滚动

        $(window).scroll(function () {
            $("#menuSlide").css({"height": $(document).height()});
            var _key = $("#list_new li:last-child").attr("data-key");
            if ($(window).scrollTop() >= $(document).height() - $(window).height() && _ajaxG && _key ) {
                var _dataStr = {"order": _order, "type": _category, "brand": _brand,"key":_key,"pagesize":_pageSize};
                loadMore.ajaxGet(_dataStr);
            }
        });
    },
    hideSlide: function () {
        $("#menuSlide .inner").removeClass('show');
        setTimeout(function () {
            $("#menuSlide").hide();
            $(window).scrollTop(0);
        }, 100);

    },ajaxGet:function(_dataStr){
        _ajaxG = false;
        $("#loadding").show();
        $.post("http://m.shihuo.cn/shoe/getShoeAjax", _dataStr, function (data) {
            var _html = ""; 
            _ajaxG = true;
            if (data.status == "0") {
                if(data.data.length>0){
                    __dace.sendEvent('shihuo_m_dace_shoe_page_' + _dataStr._key);
                    $.each(data.data, function (index, val) {
                        var title = val.title;
                        if(!title) {
                            title = val.name;
                        }
                        _html += '<li data-key = "'+val.data_key+'"><a href="http://m.shihuo.cn/shoe/detail/' + val.id + '.html" class="link-a">' +
                        '<div class="imgs"><img src="http://shihuo.hupucdn.com' + val.img_url + '-S253A.jpg" alt=""></div>' +
                        '<div class="details_box"> <h2>' + title + '</h2><p class="tags">';
                        if (val.tags)
                            $.each(val.tags, function (index, val) {
                                _html += '<span>' + val + '</span>';
                            });
                        _html += '</p><div class="price"><i>￥</i><span>' + val.price + '</span>' +
                        '<div class="fr">热度：' + val.heat + '</div></div>' +
                        '</div> </a> </li>';
                    });
                }
                $("#loadding").hide();
                $("#list_new").append(_html);
                loadMore.localSt();
            }
        }, "json");
    },ajaxTab:function(_dataStr){
        $("#loadding").show();
        $.post("http://m.shihuo.cn/shoe/getShoeAjax", _dataStr, function (data) {
            var _html = ""; 
            if (data.status == "0") {
                if(data.data.length>0){
                    $.each(data.data, function (index, val) {
                        var title = val.title;
                        if(!title) {
                            title = val.name;
                        }
                        _html += '<li data-key = "'+val.data_key+'"><a href="http://m.shihuo.cn/shoe/detail/' + val.id + '.html" class="link-a">' +
                        '<div class="imgs"><img src="http://shihuo.hupucdn.com' + val.img_url + '-S253A.jpg" alt=""></div>' +
                        '<div class="details_box"> <h2>' + title + '</h2><p class="tags">';
                        if (val.tags)
                            $.each(val.tags, function (index, val) {
                                _html += '<span>' + val + '</span>';
                            });
                        _html += '</p><div class="price"><i>￥</i><span>' + val.price + '</span>' +
                        '<div class="fr">热度：' + val.heat + '</div></div>' +
                        '</div> </a> </li>';
                    });
                }
                $("#loadding").hide();
                if(_html == ""){
                    $("#list_new").addClass('null');
                }else{
                    $("#tabBox1-bd .con ul").removeClass('null');
                    $("#list_new").html(_html);
                    loadMore.localSt();
                }
            }
        }, "json");
    },localSt:function(){
        var html = $("#list_new").html();
        localStorage.shoeList = html;
        localStorage.shoeList_order = _order;
        localStorage.shoeList_type  = _category;
        localStorage.shoeList_brand = _brand;
        localStorage.lastest_shoe_time = new Date().getTime();
    },init:function(){
        if(localStorage.shoeList){
        var time = new Date().getTime()-localStorage.lastest_shoe_time;
        if(time<10000*60){
           $("#list_new").html(localStorage.shoeList);
           if(localStorage.shoeList_brand){
                _brand    = localStorage.shoeList_brand;
           }
           if(localStorage.shoeList_type){
                _category = localStorage.shoeList_type;
           }
           if(localStorage.shoeList_order){
                _order    = localStorage.shoeList_order;
                $("#tabBox .price").addClass("on");
                $("#tabBox .new").removeClass("on");
           }
        }else{
          localStorage.shoeList  = "";
        }
      }
    }
}
