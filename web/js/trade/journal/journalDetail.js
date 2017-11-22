
var messageFnTip = {
    init: function () {
        var _this = this;

        var $btnRecommend = $(".supportBtn"),
            $btnOppose = $(".agaistBtn");

        $btnRecommend.live("click", function () {
            _this.setMessageRecommendData($(this), 1);
        });

        $btnOppose.live("click", function () {
            _this.setMessageRecommendData($(this),2);
        });

    },
    isLogin: function () {
        var ua = document.cookie.match(new RegExp("(^| )ua=([^;]*)(;|$)")), data;
        if (ua && ua[2]) return true;
        return;
    },
    setMessageRecommendData: function (elm, type) {
        if (!this.isLogin()) {
            commonLogin('hupu');
            return false;
        }
        var id = elm.parent().attr('data-id');
        var data = {
            'id': id,
            'type': type
        };
        $.getJSON("http://www.shihuo.cn/shiwuzhi/AjaxSupportAgaist", data, function (data) {
            if (data.status == 200) {
                $(".supportBtn").html("<s>"+data.data.snum+"</s>");
                $(".agaistBtn").html("<s>"+data.data.anum+"</s>");
            }
        });
    }
};
messageFnTip.init();




var coverHeader = function(){
    var ws = $(window).scrollTop(),
        wh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
        $frame = $(".content-area"),
        ft = $frame.offset().top,
        fh = $frame.height(),
        fl = $frame.offset().left,
        scrollw = $(".content-box-title .bgs").width();
    if(ws >= ft && ws < $(".journal-comment").offset().top){
        $(".content-box-title").css({"left":fl-120,"top":"0px"}).fadeIn();
        var moveratio = (ws - ft)/fh;
        $(".content-box-title .bgs2").css({"width":Math.round(moveratio*scrollw)+"px"});
    }else if(ws < ft){
        $(".content-box-title").fadeOut();
    }
}

$(window).scroll(function(event) {
    coverHeader();
});
$(window).resize(function(){
    coverHeader();
});