$(function(){
    var qixi      = {
    init:function(){ 
        $("#support").on("click",function(){
            $.get("http://m.shihuo.cn/qixi/zhuli?openid="+masterid, function (data) { 
                if(data.status == 0){
                    $(".number").html(data.data.point).addClass("show");
                    $(".yun1,.yun2").addClass("support");
                    $(".number").addClass("show");
                    setTimeout(function(){
                        $(".yun1,.yun2").removeClass("support");
                        $(".number").removeClass("show");
                    }, 3000);
                    $("#point").html(data.data.total_point);
                    var _html = '<li><div class="imgs">\
                            <img src="'+data.data.add_user.headimgurl+'" alt="">\
                        </div>\
                        <div class="details">\
                            <p class="n"><span>'+data.data.add_user.nickname+'</span> '+data.data.add_user.date+'</p>\
                            <p class="z">'+data.data.add_user.randtext+'</p>\
                        </div>\
                        <div class="num">'+data.data.add_user.points+'点</div>\
                    </li>';
                    $(".listSup").before(_html);
                }else{
                    $.ui.tips(data.msg);
                }
            },"json");
        });
        $(".meto").on("click",function(){
             $.get("http://m.shihuo.cn/qixi/selfUrl?openid="+openid, function (data) { 
                if(data.status == 0){
                    if(data.data.url){
                        window.location.href = data.data.url;
                        return false;
                    }
                }else{
                    $(".boxAlert").show();
                    $("body,html").addClass("noScroll");
                    $(".formXs").show();
                }
            },"json");
        });
        $(".share").on("click",function(){
            $(".boxAlert").hide();
            $("body,html").removeClass("noScroll");
            $(".formXs").hide();
            $(".share").hide();
        });
        $(".shareTo").on("click",function(){
            var ta  = "";
            if(!$(this).hasClass("nm")){
                var taName = $("#taName").val();
                if(taName.length>0){
                    ta = taName;
                    $(".yun2 .meto").html(ta);
                }else{
                    ta = "Ta";
                }
            }else{
                ta = "Ta";
            }

            var _title = "鹊桥点金术";
            var _pic   =  "http://kaluli.hoopchina.com.cn/images/trademobile/qixi/wx.png" ;
            _shareLink = "http://m.shihuo.cn/qixi/activity?openid="+openid+"&ta="+encodeURIComponent(ta);
            _sharedesc = masterName+"和"+ta+"恋爱啦，赶紧来支持他们吧～";
            $(".formXs").hide();
            $(".share").show();
                   //微信分享
                wx.ready(function () {
                    wx.onMenuShareTimeline({
                        title: _sharedesc, // 分享标题
                        link: _shareLink, // 分享链接
                        imgUrl:_pic, // 分享图标
                        success: function () {
                            $(".boxAlert").hide();
                            $(".share").hide();
                            $("body,html").removeClass("noScroll");
                            window.location.href= _shareLink;
                        },
                        cancel: function () {
                            console.log("error");
                        }
                    });
                    wx.onMenuShareAppMessage({
                        title: _title, // 分享标题
                        desc: _sharedesc, // 分享描述
                        link: _shareLink, // 分享链接
                        imgUrl: _pic, // 分享图标
                        success: function () {
                            $(".boxAlert").hide();
                            $(".share").hide();
                            window.location.href= _shareLink;
                        },
                        cancel: function () {
                            console.log("error");
                        }
                    });
                });
        });
        $(".join").on("click",function(){
            $("body,html").addClass("noScroll");
            $(".boxAlert").show();
            $(".formXs").show();
        });
        $(".btnChange").on("click",function(){
            if($("#point").html() <= 0){
                $.ui.tips("恋爱指数不足，加油哦");
                return false;
            }
           $(this).hide();
           $("#formChange").show();
        });
         $("#changeName").on("click",function(){
            $(".boxAlert").show();
            $("body,html").addClass("noScroll");
            $(".formXs").show();
         });
        $("#formChange .btnchg").on("click",function(){
           var tel = $("#formChange .phone").val();
           var reg = /^1[1-9]\d{9}$/;
           if(reg.test(tel)){
              $.ajax({
                type: 'GET',
                url: "http://m.shihuo.cn/qixi/duihuan?openid="+openid+"&mobile="+tel,
                dataType: 'json',
                success: function(data) {
                    if(data.status == 0){
                        $("#formChange").hide();
                        $("#boxChange,.btnChange").show();
                        $(".btnChange").addClass("gray");
                        $(".btnChange").html("兑换码发送中...");
                    }else{
                       tips(data.msg);
                    }
                },
                error: function() {
                    $.vui.tips("兑换失败,请稍候重试");
                }
             });
           }else{
            $.ui.tips("请输入正确的手机号码");
           }
        });
        $(".downApp").on("click",function(){
          //  var isAndroid = (/android/gi).test(navigator.appVersion);
            var url="http://a.app.qq.com/o/simple.jsp?pkgname=com.hupu.shihuo";
            window.open(url);
        });
    }
   }
   qixi.init(); 
   var len = $(".listSup li").length;
   if(len>6){
        for(var i = 6;i<len;i++){
            $(".listSup li").eq(i).hide();
        }
        var page =2;
        $(".listBlock .more").on("click",function(){
            // alert("aa");
            if(page == 0){
                for(var i = 6;i<len;i++){
                    $(".listSup li").eq(i).hide();
                }
                page = 2;
                $(this).removeClass('up');
            }else{
                if(len<page*6){
                    for(var i = 6*(page-1);i<len;i++){
                        $(".listSup li").eq(i).show();
                    }
                    page=0;
                    $(this).addClass('up');
                }else{
                    for(var i = 6*(page-1);i<(page*6);i++){
                        $(".listSup li").eq(i).show();
                    }
                    page++;
                }
            }
        });
   }else{
     $(".listBlock .more").hide();
   }


    //alert(openid +""+ openid);
    var _title = "鹊桥点金术";
    var _pic   =  "http://kaluli.hoopchina.com.cn/images/trademobile/qixi/wx.png" ;
    _shareLink = "http://m.shihuo.cn/qixi/activity?openid="+masterid+"&ta="+taName;
    _sharedesc = openName+"和"+taName+"恋爱啦，赶紧来支持他们吧～";
    //微信分享
    wx.ready(function () {
        wx.hideMenuItems({
            menuList: ["menuItem:share:qq","menuItem:share:weiboApp","menuItem:share:facebook","menuItem:share:QZone","menuItem:openWithQQBrowser","menuItem:openWithSafari","menuItem:copyUrl"], // 要隐藏的菜单项，只能隐藏“传播类”和“保护类”按钮，所有menu项见附录3
             success: function (res) {
               // alert('已隐藏“阅读模式”，“分享到朋友圈”，“复制链接”等按钮');
              },
              fail: function (res) {
               // alert(JSON.stringify(res));
              }
        }); 
        wx.onMenuShareTimeline({
            title: _sharedesc, // 分享标题
            link: _shareLink, // 分享链接
            imgUrl:_pic, // 分享图标
            success: function () {
                $(".boxAlert").hide();
                $(".share").hide();
                $("body,html").removeClass("noScroll");
                window.location.href= _shareLink;
            },
            cancel: function () {
                console.log("error");
            }
        });
        wx.onMenuShareAppMessage({
            title: _title, // 分享标题
            desc: _sharedesc, // 分享描述
            link: _shareLink, // 分享链接
            imgUrl: _pic, // 分享图标
            success: function () {
                $(".boxAlert").hide();
                $(".share").hide();
                $("body,html").removeClass("noScroll");
                window.location.href= _shareLink;
            },
            cancel: function () {
                console.log("error");
            }
        });
    });

}); 
function tips(o){
   var str = '<div class="tips-box">'+o+'</div>';
    if(timTips){
       clearTimeout(timTips);
       $(".tips-box").remove();
    }
    $(str).appendTo('body');
    $(".tips-box").css({
        left:$(window).width()/2 - $(".tips-box").width()/2,
        top:$(window).height()/2 - 10
    });

    timTips = setTimeout(function(){
       $(".tips-box").remove();
    },2000);
}