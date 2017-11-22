var _ajaxG     = false;
var _pageSize  = 6; //每页个数
var _PageTotal = 10;//总页数
var _pageNum   = 1;

$(function(){
    loadMore.ajaxData();
    loadMore.ajaxScroll();
});

//下拉加载更多
var loadMore = {
    ajaxLink: "http://m.shihuo.cn/shaiwu/myListAjax",
    ajaxScroll: function() {
        var that = this;//页面滚动

        $(window).scroll(function() {
          if($(window).scrollTop() >= $(document).height()-$(window).height() && _ajaxG && _pageNum <= _PageTotal) {
            $("#loadding").show();
            loadMore.ajaxData();
          }
        });
    },
    init:function(){
        this.ajaxData();
    },
    ajaxData:function(){
        var _dataStr ={"page": _pageNum,"pagesize": _pageSize};
        var _html ="";
        var element = $('#js-box'),  
        tpl = $('#tpl').html();
        _ajaxG = false;
        $.post(this.ajaxLink,_dataStr, function(data) {
            if(data.status == "0"){
                $("#loadding").hide();
                _PageTotal = data.pages;
                _pageNum += 1;
                _ajaxG = true;
                var html = _.template(tpl);  
                // 将解析后的内容填充到渲染元素  
                element.append(html(data));
            }else if(data.status == '2'){
                element.addClass("null");
            }
        },"json");
    }
}
