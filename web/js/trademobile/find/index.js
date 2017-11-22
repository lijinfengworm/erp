var _ajaxG = true;
var _pageSize = 30; //每页个数
var _tab = "new";
$(function() {
    $(".con ul").hide();
    $("#list_new").show();
    $(".top_menu li").on('click', function(event) {
        $(window).scrollTop(0);
        _tab = $(this).attr("data-type");
        $(".top_menu li").removeClass('on');
        $(".con ul").hide();
        $("#list_" + _tab).show();
        $(this).addClass('on');
        localStorage.findList_tab = _tab;
    });
    loadMore.ajaxScroll();
    if(localStorage.findList_tab){
        _tab = localStorage.findList_tab;
        $(".con ul").hide();
        $("#list_" + _tab).show();
        $("#tabBox li").removeClass('on');
        $("#tabBox ."+_tab).addClass('on');
        if(localStorage.findList_all){
            var time = new Date().getTime()-localStorage.lastest_find_time;
            if(time<10000*60){
                $("#list_" + _tab).html(localStorage.findList_all);
            }else{
              localStorage.findList_all  = "";
              localStorage.findList_tab = "new";
            }
        }

    }
});
//下拉加载更多
var loadMore = {
    ajaxLink: "http://m.shihuo.cn/find/getFindData",
    ajaxScroll: function() {
        var that = this; //页面滚动
        $(window).scroll(function() {
            var _pageNum = parseInt($("#list_"+_tab).attr("data-pageCur"));
            if ($(window).scrollTop() >= $(document).height() - $(window).height() && _ajaxG ) {
                $("#loadding").show();
                _ajaxG = false;
                var _html="",_key;
                _key = $("#list_"+_tab+" li:last-child").attr("data-key");
                var _dataStr = {"key": _key, "type": _tab,"pagesize":_pageSize}; 
                $.post(that.ajaxLink, _dataStr, function(data) {
                    if (data.status == "0") {
                        $("#loadding").hide();
                        _ajaxG = true;
                        __dace.sendEvent('shihuo_m_dace_find_page_' + _key);
                        $.each(data.data, function(index, val) {
                            var title = val.title;
                            if (!title) {
                                title = val.name;
                            }
                            _html += '<li data-key="'+val.data_key+'"><a href="http://m.shihuo.cn/detail/' + val.id + '.html#qk=find_list&type='+ _tab +'&order='+ (index + 1) +'&page =' + _pageNum + '" class="link-a">' + '<div class="imgs"><img src="'+ val.img_path +'" alt=""></div>' + '<div class="details_box"> <h2>' + title + '</h2><p class="tags">';
                            if (val.tags) $.each(val.tags, function(index, val) {
                                _html += '<span>' + val + '</span>';
                            });
                            _html += '</p><div class="price"><i>￥</i><span>' + val.price + '</span>' + '<div class="fr">' + val.pub_time + '</div></div>' + '</div> </a> </li>';
                        });
                        $("#list_" + _tab).append(_html);
                        localStorage.findList_all  = $("#list_" + _tab).html();
                        localStorage.findList_tab = _tab;
                        localStorage.lastest_find_time = new Date().getTime();
                    }
                }, "json");
            }
        });
    }
}