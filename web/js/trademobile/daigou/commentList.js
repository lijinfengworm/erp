var _pageNum   = 2;
var _pageTotal = 10;
var _ajaxG     = true;
var _pageSize  = 5;
var ajaxLink   = "http://m.shihuo.cn/daigou/commentListAjax";

$(function(){
     _pageTotal = $("#commentList").attr("data-totals");
     loadMore.init();

    $(".imgsBox").click(function(){
         loadMore.slideImg($(this));
    });
    $("#mySwipe .swipe-wrap").click(function(){
        $(".showImgs").hide();
        $("body").removeClass("noscroll");
    });
});
var loadMore  ={
    init: function () {
        $(window).scroll(function () {
            if ($(window).scrollTop() >= $(document).height() - $(window).height() && _ajaxG && _pageNum <= _pageTotal) {
                $("#loadding").show();
                _ajaxG = false;
                var _dataStr = {
                    "pid": pid,
                    "page": _pageNum,
                    "tag": "",
                    "pagesize": _pageSize,
                    "imgWidth": 320,
                    "imgHeight": 320
                };
                var _html = "";
                $.post(ajaxLink, _dataStr, function (data) {
                    if(data.status == 0){
                        $("#loadding").hide();
                        _pageNum += 1;
                        _ajaxG = true;
                        _html = loadMore.getHtml(data.data);
                        $("#commentList").append(_html);
                        $("#commentList").find(".imgsBox").click(function(){
                            loadMore.slideImg($(this));
                        });
                    }
                }, 'json');
            }
        });
    },
    getHtml: function (data) {
        var _html = "";
        $.each(data, function (index, val) {
            var tags = "";
            var imgs = "";
            var attr = "";
            $.each(val.tags, function(i, v){
                tags += '<span>'+ v +'</span>';
            });
            $.each(val.imgs, function(i, v){
                imgs += '<img src="' + v + '">';
            });

            $.each(val.attr, function(index, val){
                attr += '<span>'+ index + ': '+ val +'</span>';
            });
            _html += '<li>\
                    <div class="imgs">\
                        <img src="' + val.user_head + '" alt="">\
                        <p>' + val.user_name + '</p>\
                    </div>\
                    <div class="details">\
                        <div class="tags">' + tags + '</div>\
                        <div class="txt">' + val.content + '</div>\
                        <div class="imgsBox">' + imgs + '</div>\
                        <p class="time">\
                            <span>' + val.create + '</span>\
                            '+ attr + '\
                         </p>\
                    </div>\
                </li>';
        });
        return _html;
    },
    slideImg:function(a){
        var _html="",_i ="";
        a.find("img").each(function(i){
            var _img = $(this).attr("src");
            _html += "<div><img src="+_img+"></div>";
            _i += "<i></i>";
        });
        $("body").addClass("noscroll");
        $(".showImgs").show();
        $("#mySwipe .swipe-wrap").html(_html);
        $("#position").html(_i);
        window.mySwipe = Swipe(document.getElementById('mySwipe'), {
            continuous: false,
            callback: function(index) {
                var i = bullets.length;
                while (i--) {
                    bullets[i].className = ' ';
                }
                bullets[index].className = 'on';
            }
        });
        var bullets = document.getElementById('position').getElementsByTagName('i');
        bullets[0].className = 'on';
        var i = bullets.length;
        if(i==1){
            $("#position").hide();
        }else{
            $("#position").show();
        }
    }
};