var _ajaxG = true;
var _html  = "";
var _type  = "";
var _page  = 1;

$(function(){
    loadMore.init();
    loadMore.ajaxScroll();
    $(".tabtit a").on("click",function(){
        $(".tabtit a").removeClass("on");
        $(this).addClass("on");
        _type = $(this).attr("data-type");
        _page = 1;
        $('#js-show').html("");
        loadMore.init();
    });
});
var loadMore = {
    ajaxLink: "http://m.shihuo.cn/coupon/getCouponsList",
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
        $("#loadding").show();
        this.ajaxData();
    },ajaxData:function(){
        _ajaxG = false;
       $.post(this.ajaxLink,{"type":_type,"page":_page}, function(data) {
            if(data.status == "0" && data.data.length>0){
                var element1 = $('#js-show'); 
                tpl = $('#tpl').html();
                var html = _.template(tpl); 
                 _ajaxG = true;
                // 将解析后的内容填充到渲染元素  
                element1.append(html(data));

                $("#loadding").hide();
                _page += 1;
            }else{
               $("#loadding").hide();
            }
        },"json");
    }
};