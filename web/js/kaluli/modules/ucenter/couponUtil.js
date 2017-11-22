/**
 * Created by PhpStorm.
 * User: songxiaoqiang
 * DATA: 2015/12/15
 */
requirejs.config({
    baseUrl:"//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        tips:"modules/common/tips"
    }
});
define(['tips'],function(tips){
    function couponUtil(options){
        var defaults={
            title:['领取优惠券','提示'],
            iconurl:["//kaluli.hoopchina.com.cn/images/kaluli/user/confirm-sign.png","//kaluli.hoopchina.com.cn/images/kaluli/user/error-sign.png"]
        };
        var date = new Date().getTime();
        var dom1 = '<p class="prompt"><i></i>优惠券领取后将会与您的账户关联，仅限该账户使用。</p>\
                <div class="col">请输入兑换码:<input type="text" name="coupon_text" /></div>\
                <div class="col">请输入验证码:<input type="text" name="validate_num" /><img class="validate_img" data-href="//www.kaluli.com/api/captcha" src="//www.kaluli.com/api/captcha"><span id="change_validate">换一张</span></div>\
                <div id="msg-list" class="row"></div>\
                <div class="row">\
                    <div id='+options.getId+' class="btn get-btn">领取</div>\
                    <div class="btn2 btn '+options.cancelClass+'">取消</div>\
                </div>';
        var dom2 = '<div class="left">\
                    <div class="h2"><img class="msg_icon" src="//kaluli.hoopchina.com.cn/images/kaluli/noneImg.png" />恭喜！领取成功！</div>\
                </div>\
                    <div class="right">\
                        <div class="btn-span">\
                            <span class="btn1 '+options.cancelClass+'">确定</span>\
                        </div>\
                    </div>';
        var util = {
            init:function(){
                var t = this;
                $("#"+options.getId).live("click",function(){
                    t.clearMsg();
                    t.submit();
                });

                $("."+options.cancelClass).live("click",function(){
                    t.closeBox();
                });

                $("#change_validate").live("click",function(){
                    t.refreshValidateImg();
                });
            },
            showBox:function(){
                $("#alert_title").text(defaults.title[0]);
                $("#alert_content").html(dom1);
                $.Jui._showMasks(0.6);
                setTimeout(function(){
                    $(".write-card").css({
                        left:$.Jui._position($(".write-card"))[0],
                        top:$.Jui._position($(".write-card"))[1] -$.Jui._getpageScroll()
                    }).show();
                },500);
            },
            refreshValidateImg:function(){
                var time = new Date().getTime();
                $(".validate_img").attr("src",$(".validate_img").attr("data-href")+"?t="+time);
            },
            closeBox:function(){
                $(".write-card").hide();
                $.Jui._closeMasks();
                $("#alert_content").html("");
            },
            clearMsg:function(){
                $("#msg-list").html('');
            },
            submit:function(){
                var that = this;
                var url = "//www.kaluli.com/ucenter/myCouponDuihuan";
                var datas = {
                    account:$("input[name=coupon_text]").val(),
                    code:$("input[name=validate_num]").val()
                };
                $.post(url,datas,function(res){
                    var callback = $.parseJSON(res);

                    if(callback.status == 0){
                         $("#alert_content").css("width","230px");
                         $("#alert_title").text(defaults.title[1]);
                         $("#alert_content").html(dom2);
                         $(".msg_icon").attr("src",defaults.iconurl[0]);
                        //TODO 刷新
                         setTimeout(window.location.reload(),1500);
                    }else{
                        $("#alert_content").css("width","auto");
                        that.refreshValidateImg();
                        $("#msg-list").append("<p class='error-msg'><i></i>"+callback.msg+"</p>");
                    }
                });
            }
        };

        return util
    }
    return couponUtil;
});