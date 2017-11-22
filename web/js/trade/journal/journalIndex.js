var messageFnTip = {
    init: function () {
        var _this = this;

        var $btnRecommend = $(".clickSupport"),
            $btnOppose = $(".clickAgaist");

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
                $("#supportBtn_"+id).text(data.data.snum);
                $("#agaistBtn_"+id).text(data.data.anum);
            }
        });
    }
};
messageFnTip.init();