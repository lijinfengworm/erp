var _title = "识货幸运大转盘";
var _url   = document.URL;
var _pic   =  "images/share.png" ;
var _sharedesc = "识货幸运大转盘，分享赢大奖，赶快来参与吧！";

wx.ready(function () {
    wx.onMenuShareTimeline({
        title: _title, // 分享标题
        link: _url, // 分享链接
        imgUrl:_pic, // 分享图标
        success: function () {
            $.ajax({
                type: 'POST',
                url: "http://www.shihuo.cn/api/luckyDraw20151111",
                data: {
                    act:  "share",
                    dacevid: $("#dacevid").val()||""
                },
                dataType: 'json',
                success: function(data) {
                    if(data.status == true){
                       $.vui.remind(data.msg);
                    }else{
                       $.vui.remind(data.msg);
                    }
                },
                error: function() {
                    $.vui.remind("分享失败");
                }
            });

        },
        cancel: function () {
            console.log("error");
        }
    });
    wx.onMenuShareAppMessage({
        title: _title, // 分享标题
        desc: _sharedesc, // 分享描述
        link: _url, // 分享链接
        imgUrl: _pic, // 分享图标
        success: function () {
             $.ajax({
                type: 'POST',
                url: "http://www.shihuo.cn/api/luckyDraw20151111",
                data: {
                    act:  "share",
                    dacevid: $("#dacevid").val() || ""
                },
                dataType: 'json',
                success: function(data) {
                    if(data.status == true){
                       $.vui.remind(data.msg);
                    }else{
                       $.vui.remind(data.msg);
                    }
                },
                error: function() {
                    $.vui.remind("分享失败");
                }
            });
        },
        cancel: function () {
            console.log("error");
        }
    });
});