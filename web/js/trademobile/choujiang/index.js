var _title = "识货幸运大转盘";
var _url   = document.URL;
var _pic   =  "http://kaluli.hoopchina.com.cn/images/trademobile/choujiang/share.jpg" ;
var _sharedesc = "识货幸运大转盘，分享赢大奖，赶快来参与吧！";
//微信分享
if(GV.browser.is_weixin) {
    wx.ready(function () {
        wx.onMenuShareTimeline({
            title: _title, // 分享标题
            link: _url, // 分享链接
            imgUrl:_pic, // 分享图标
            success: function () {
                $.ajax({
                    type: 'POST',
                    url: "http://m.shihuo.cn/choujiang/share",
                    data: {
                        id:GV.lottery_id,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.status == 1){
                            location.reload();
                            Game.canPlay = true;
                        }else{
                           $.vui.remind(data.info);
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
                    url: "http://m.shihuo.cn/choujiang/share",
                    data: {
                        id:GV.lottery_id,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if(data.status == 1){
                            location.reload();
                            Game.canPlay = true;
                        }else{
                           $.vui.remind(data.info);
                        }
                    },
                    error: function() {
                        console.log("error");
                    }
                });
            },
            cancel: function () {
                console.log("error");
            }
        });
    });
}
if(GV.is_share == 0 && GV.lottery_num ==0){
    $("#showShare").show();
}
$.cxSelect.defaults.url = 'http://m.shihuo.cn/js/trademobile/choujiang/cityData.min.json';
$('#city_china_val').cxSelect({
    selects: ['province', 'city', 'area'],
    nodata: 'none'
});
$(function(){
   
    var history_id="";
    if(GV.user_phone){
        $("#awardNum").show();
        $("#awardNum .num").html(GV.lottery_num);
    }else{
        $("#regTel").show();
    }
    $('#send').on($.vui.click, function() {
        if ($.vui.touchmoved) {
            return false;
        }
        $("#phone").blur();
        var tel = $('#phone').val();
        if (!tel) {
            $.vui.remind('请输入您的手机号码！');
        } else if (!$.vui.isPhone(tel)) {
            $.vui.remind('请输入正确的手机号码！');
        } else {
            var _t = new Date();
            _t = Math.ceil(Date.parse(_t) / 1000);
            localStorage.setItem("codeSend", _t);
            countDown();

            //发送验证码post
            $.ajax({
                type: 'POST',
                url: "http://m.shihuo.cn/choujiang/reg",
                data: {
                    phone: tel,
                    id:GV.lottery_id,
                    source:GV.source
                },
                dataType: 'json',
                success: function(data) {
                    if(!data.status){
                        $.vui.remind(data.info);
                    }
                },
                error: function() {
                    console.log("error");
                }
            });

        }
        return false;
    });

    $("#sendCode").on($.vui.click, function() {
        var tel = $('#zhongjiang .tel').val();
        $.ajax({
            type: 'POST',
            url: "http://m.shihuo.cn/choujiang/SendPhone",
            data: {
                send_phone: tel,
                history_id:GV.history_id,
                id:GV.lottery_id,
                source:GV.source
            },
            dataType: 'json',
            success: function(data) {
                $('#boxAlert').removeClass("show");
                setTimeout(function() {
                    $('#boxAlert .i').removeClass("show");
                    $('#boxAlert .zhongjiang').removeClass("show");
                });
            },
            error: function() {
                console.log("error");
            }
        });
    });

    $("#yanzhengBtn").on($.vui.click, function() {
         $("#phone").blur();
        var tel     = $('#phone').val();
        var captcha = $('#captcha').val();
        if (!tel) {
            $.vui.remind('请输入您的手机号码！');
        } else if (!$.vui.isPhone(tel)) {
            $.vui.remind('请输入正确的手机号码！');
        } else if (!captcha) {
            $.vui.remind('请输入验证码！');
        } else {
            //手机号注册
            $.ajax({
                type: 'POST',
                url: "http://m.shihuo.cn/choujiang/login",
                data: {
                    phone: tel,
                    code: captcha,
                    id:GV.lottery_id,
                    source:GV.source
                },
                dataType: 'json',
                success: function(data) {
                    if(data.status == 1){
                        $(window).scrollTop(0);
                        window.location.reload();
                    }else{
                        $.vui.remind(data.info);
                    }
                },
                error: function() {
                    $.vui.remind("登录失败，请稍候重试");
                }
            });
        }
    });
    $('#btnAddress').on($.vui.click, function() {
        var address ={};
        address.name = $("#address .name").val();
        address.tel  = $("#address .tel").val();
        address.province  = $("#address .province").val();
        address.city      = $("#address .city").val();
        address.area      = $("#address .area").val();
        address.street    = $("#address .street").val();
        if(!address.name){
            $.vui.remind("请填写您的姓名");
        }else if(address.tel && !$.vui.isPhone(address.tel)){
            $.vui.remind('请输入正确的手机号码！');
        }else if(!address.street){
            $.vui.remind("请填写收货详细地址");
        }else{
            $.ajax({
                type: 'POST',
                url: "http://m.shihuo.cn/choujiang/saveAddress",
                data: {
                    id:GV.lottery_id,
                    history_id:GV.history_id,
                    address:address.name+'-'+address.tel+'-'+address.province+'-'+address.city+'-'+address.area+'-'+address.street
                },
                dataType: 'json',
                success: function(data) {
                    if(!data.status){
                        $.vui.remind(data.info);
                    }else{
                        $('#boxAlert').removeClass("show");
                        setTimeout(function() {
                           $('#boxAlert .i').removeClass("show");
                           $('#boxAlert .address').removeClass("show");
                        });
                    }
                },
                error: function() {
                   $.vui.remind("网络故障，请联系客服");
                }
            });
        }
    });
    //点击分享按钮
    $("#showShare").on($.vui.click,function(){
        __dace.sendEvent('shihuo_choujiang_share_'+GV.lottery_id);
        if(GV.browser.is_weixin){
             $('.shareWeixin').show();
        }else if(GV.browser.is_app && parseInt(GV.browser.version_number) >= parseInt(220)){
            var data = {
                url: _url,
                title:_title,
                img: _pic,
                content: _sharedesc
            };
            Jockey.send('share',data);
            Jockey.on('js-share',function(data){
                if(data.status == 0){
                     $.ajax({
                        type: 'POST',
                        url: "http://m.shihuo.cn/choujiang/share",
                        data: {
                            id:GV.lottery_id,
                        },
                        dataType: 'json',
                        success: function(data) {
                            if(data.status == 1){
                                Game.canPlay = true;
                            }
                        },
                        error: function() {
                            console.log("error");
                        }
                    });
                }
            });
        }else{
            $(".wrapper").css({"pointer-events": "none"});
            $('#boxAlert').addClass("show");
            setTimeout(function() {
               $('#boxAlert .i').addClass("show");
               $('#boxAlert .share').addClass("show");
            }, 10);
        }
    });
    $(".shareWeixin>a").on($.vui.click,function(){
        $('.shareWeixin').hide();
    });
    function countDown() {
        var _t = new Date();
        _t = Math.ceil(Date.parse(_t) / 1000);
        var _t2 = localStorage.getItem("codeSend");
        _t = 60 - (_t - _t2);

        if (_t > 0) {
            $('#send').addClass("gray");
            $('#send').text('('+_t+')').css({"pointer-events": "none"});
            setTimeout(function() {
                countDown()
            }, 1000);
        } else {
            localStorage.removeItem("codeSend");
            $('#send').removeClass("gray");
            $('#send').text("重新获取").css({"pointer-events": "auto"});
        }
    }
    //分享到微博
    $("#shareSina").on($.vui.click,function(){
        var _content = "识货幸运大转盘";
        var _url  = encodeURIComponent(document.URL);
        var _pic  =  encodeURIComponent("http://m.shihuo.cn/images/trademobile/choujiang/share.jpg") ;
        var _link = "http://service.weibo.com/share/share.php?status=1&pic=" + _pic + "&url=" + _url + "&title="+encodeURIComponent(_content)+"&appkey=3445570739";
        this.href=_link;
        //分享成功次数加一
        $.ajax({
            type: 'POST',
            url: "http://m.shihuo.cn/choujiang/share",
            data: {
                id:GV.lottery_id,
            },
            dataType: 'json',
            success: function(data) {
                if(data.status == 1){
                   $('#boxAlert').removeClass("show");
                    setTimeout(function() {
                       $('#boxAlert .i').removeClass("show");
                       $('#boxAlert .share').removeClass("show");
                    }, 10);
                    Game.canPlay = true;
                    location.reload();
                }else{
                   $.vui.remind(data.info);
                }
            },
            error: function() {
                console.log("error");
            }
        });
    });
    $("#shareQq").on($.vui.click,function(){
        var _content = "识货幸运大转盘，分享赢大奖，赶快来参与吧！";
        var _url  = encodeURIComponent(document.URL);
        var _pic  =  encodeURIComponent("http://m.shihuo.cn/images/trademobile/choujiang/share.jpg") ;
        var _link = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=识货幸运大转盘&summary='+encodeURIComponent(_content)+'&url=' + _url +'&pics='+_pic;
        this.href=_link;
        //分享成功次数加一
        $.ajax({
            type: 'POST',
            url: "http://m.shihuo.cn/choujiang/share",
            data: {
                id:GV.lottery_id,
            },
            dataType: 'json',
            success: function(data) {
                if(data.status == 1){
                   $('#boxAlert').removeClass("show");
                    setTimeout(function() {
                       $('#boxAlert .i').removeClass("show");
                       $('#boxAlert .share').removeClass("show");
                    }, 10);
                    Game.canPlay = true;
                    location.reload();
                }else{
                   $.vui.remind(data.info);
                }
            },
            error: function() {
                console.log("error");
            }
        });
    });
    $("#boxAlert .close").on($.vui.click,function(){
        $('#boxAlert').removeClass("show");
        setTimeout(function() {
            $('#boxAlert .i').removeClass("show");
            $('#boxAlert .showBox').removeClass("show");
        }, 10);
    });
});