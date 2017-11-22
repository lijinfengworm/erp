
/*
 * angular module start
 */

angular.module('app',[])
    .controller("secKill",function($scope,$sce){
        $scope.goods=res.goods;

        $scope.hotlist = res.hot_goods;

        $scope.text = $sce.trustAsHtml(res.goods.text);

        $scope.hot_topics = res.hot_topic;

        $scope.not_pay = res.not_pay;

        $scope.user_info = res.user_info;
    });

/*
 * angular module end
 */


var validata_interval;
;(function(){
    "use strict";
    if(res.goods.status == "end"){
        $(".killBtn").text("已结束").attr("class","killBtn-end");
        return false
    }
    if(!res.user_info.phone){
        $(".killBtn").text("登录秒杀");
    }else{
        if(res.not_pay != ""){
            $(".killBtn").text("立即购买");
        }else{
            $(".killBtn").text("限量秒杀");
        }
    }
    $(function(){
        if(res.not_pay != ""){
            var not_pay = '<div class="not_pay_popup show" >\
                                <div class="black-bg"></div>\
                                <div class="not_pay_info" style="display: block;">\
                                    <h3>您有秒杀商品<br />'+res.not_pay.title+'&nbsp;未支付</h3>\
                                    <a href="http://www.shihuo.cn/haitao/blackFriday/act/secKillPayAjax?id='+res.not_pay.id+'" class="close-btn">去支付</a>\
                                    <div class="close-x">x</div>\
                                </div>\
                            </div>';
            $("body").append(not_pay);
        }
        $(".close-x").live("click",function(){
            $(".not_pay_popup").removeClass("show");
            $(".popop-content").removeClass("show");
        });
        seckill.init();
        slide.init();
        validaa.init();
    });

    var ele = '<div class="popop-content">\
                        <div class="black-bg"></div>\
                        <div class="error-popup"><h3>秒杀时间还未到</h3><div class="close-x">x</div></div>\
                        <div class="sec-popup">\
                            <h3>速速输入手机号,速速秒杀商品</h3>\
                            <label class="phonebox"><span>+86</span><input type="text" class="userphone" name="userphone" placeholder="请输入正确手机号"/></label>\
                            <div class="fixClear">\
                                <input class="validate" name="validate" placeholder="输入验证码" type="text">\
                                <div class="validata-btn"><span class="validata-btn-text">发送验证码</span><span class="validata-info">发送成功</span></div>\
                            </div>\
                            <div class="buy-btn">登录</div>\
                            <div class="close-x">x</div>\
                        </div>\
                        <div class="validata-grid">\
                            <h3>请输入图片中的数字</h3>\
                            <div class="fixClear">\
                                <input class="img-validate-num" name="img-validate-num" type="text" placeholder="请输入验证码"/>\
                                <div class="img-validate"><img src="http://www.shihuo.cn/haitao/blackFriday/act/seckillCaptcha"></div>\
                            </div>\
                            <p class="error-info">验证码错误</p>\
                            <div class="pay-btn">立即秒杀</div>\
                            <div class="close-x">x</div>\
                        </div>\
                   </div>';


    var validaa = {
        init:function(){
            $("body").append(ele);
            this.bindFun();
        },
        bindFun:function(){
            var sending1 = false,sending2 = false,sending3 = false,sec=60;

            $(".phonebox input").on("click",function(){
                $(".phonebox").removeClass("error");
                $(".buy-btn").text("登录");
            });

            $(".phonebox input").on("change",function(){
                $(".validate").val("");
            });

            $(".validate").on("click",function(){
                $(this).removeClass("error");
                $(".buy-btn").text("登录");
            });

            $(".img-validate-num").on("click",function(){
                $("pay-btn").text("立即秒杀");
            });

            $(".img-validate img").on("click",function(){
                var time = new Date().getTime(),$this = $(this);
                $this.attr("src",$this.attr("src")+"?v="+time);
            });

            $(".validata-btn").on("click",function(){
                var uid = $(".killBtn").attr("data-uid"),
                    dataphone = $(".killBtn").attr("data-phone");
                var hasphone = uid == "false"  ? true : false;
                var phoneinput = $("input[name=userphone]").val(),
                    api = !hasphone ?  "http://www.shihuo.cn/haitao/blackFriday/act/secKillYzm" : "http://www.shihuo.cn/api/getPassportIdentifyingCode" ;

                $(".buy-btn").text("登录");
                if(phoneinput != ""){
                    $(".validata-btn-text").text("发送中...");
                    if(sending1){
                        return false
                    }
                    sending1 = true;
                    $.post(api,{mobile:phoneinput},function(data){
                        var datas = $.parseJSON(data);
                        if(datas.status == 0){
                            $(".validata-btn-text").hide().text("发送验证码");
                            $(".validata-info").text("发送成功").show();
                            validata_interval=setInterval(function(){
                                if(sec == 0){
                                    sending1 = false;
                                    $(".validata-info").hide();
                                    $(".validata-btn-text").show();
                                    clearInterval(validata_interval);
                                    sec=60;
                                }
                                $(".validata-info").text(sec+"秒重发");
                                sec--;
                            },1000);
                        }else{
                            $(".validata-btn-text").show().text("发送验证码");
                            $(".buy-btn").text(datas.msg);
                            $(".validata-info").hide();
                            sending1 = false;
                        }

                    });
                }else{
                    $(".phonebox").addClass("error");
                }
            });

            $(".buy-btn").on("click",function(){
                var uid = $(".killBtn").attr("data-uid"),
                    dataphone = $(".killBtn").attr("data-phone");
                var hasphone = uid == "false"  ? true : false;
                var val = $("input[name=validate]").val(),
                    phoneinput = $("input[name=userphone]").val(),
                    $this = $(this),
                    api = !hasphone ? "http://www.shihuo.cn/haitao/blackFriday/act/secKillYzmValidate" : "http://www.shihuo.cn/api/getPassportUserInfo";


                if(val != ""){
                    if(sending2){
                        return false
                    }
                    sending2 = true;
                    $.post(api,{mobile:phoneinput,authcode:val},function(data){
                        var datas = $.parseJSON(data);

                        if(datas.status == 0){
                            $(".killBtn").attr("data-phone",phoneinput);
                            $(".killBtn").attr("data-uid","true");
                            $(".killBtn").text("限量秒杀");
                            $.post("http://www.shihuo.cn/haitao/blackFriday/act/savePhone?mobile="+phoneinput);
                            if($("#count_box").attr("data-status") == "not_start"){
                                $(".popop-content").removeClass("show");
                                sending2 = false;
                                return false
                            }
                            $(".validata-grid").show();
                            $(".sec-popup").hide();
                        }else{
                            $this.text(datas.msg);
                        }

                        sending2 = false;
                    });
                }else{
                    $("input[name=validate]").addClass("error");
                }
            });

            $(".pay-btn").on("click",function(o){
                if(o.target.nodeName == "A"){
                    window.location.href= $("a",this).attr("href");
                    return
                }
                var phone = $(".killBtn").attr("data-phone"),
                    id = $(".seckill-product").attr("data-id"),
                    yzm = $("input[name=img-validate-num]").val(),
                    $this = $(this);

                $(".error-info").hide();

                if(yzm != ""){
                    $this.text("提交中...");
                    if(sending3){
                        return false
                    }
                    sending3 = true;

                    $.post("http://www.shihuo.cn/haitao/blackFriday/act/secKillGou",{id:id,phone:phone,yzm:yzm},function(data){
                        var datas = $.parseJSON(data);
                        if(datas.status == 0){
                            $(".validata-grid h3").css("visibility","hidden");
                            $(".img-validate-num").parent().css("visibility","hidden");
                            $this.html('<a href="http://www.shihuo.cn/haitao/blackFriday/act/secKillPayAjax?id='+id+'">去支付</a>');
                        }else{
                            $(".error-info").text(datas.msg).show();
                            var time = new Date().getTime();
                            $(".img-validate img").attr("src",$(".img-validate img").attr("src")+"?v="+time);
                            $this.text("立即秒杀");
                        }

                        sending3 = false;
                    });
                }else{
                    $("input[name=img-validate-num]").addClass("error");
                }
            });
        }
    }

    var seckill = {
        init:function(){
            this.countdown();
            this.bindFun();
        },
        bindFun:function(){
            if($("#count_box").attr("data-status") == "end"){
                $(".killBtn").attr("class","killBtn-end");
            }
            $(".killBtn").click(function(){
                   if( $(this).attr("data-phone") == "false"){
                       $(".sec-popup").show();
                   }else{
                       $(".sec-popup").hide();
                       if($("#count_box").attr("data-status") == "not_start"){
                           $(".error-popup").show();
                       }else{
                           $(".validata-grid").show();
                       }
                   }
                   $(".popop-content").addClass("show");
            });

            $(".black-bg").live("click",function(){
                $(".popop-content").removeClass("show");
            });

            $(".not_pay_popup .black-bg").live("click",function(){
                $(".not_pay_popup").remove();
            });

        },
        countdown:function(){
            var $timebox = $("#count_box"),
                max_time = $timebox.attr("leftSec");
            var timer = setInterval(function(){
               countEvent(max_time)
            },1000);

            function countEvent(){
                if(max_time >=0){
                    var seconds = max_time % 60;
                    var minutes = Math.floor((max_time  / 60)) > 0? Math.floor((max_time  / 60) % 60) : "0";
                    var hours = Math.floor((max_time  / 3600)) > 0? Math.floor((max_time  / 3600) % 24) : "0";
                    var day = Math.floor((max_time  / 86400)) > 0? Math.floor((max_time  / 86400) % 30) : "0";

                    if(day>0){
                        hours = hours + day *24;
                    }else{
                        hours = hours>=10?hours:'0'+hours;
                    }

                    minutes = minutes>=10?minutes:'0'+minutes;
                    seconds = seconds>=10?seconds:'0'+seconds;
                    $timebox.find(".time_h").text(hours);
                    $timebox.find(".time_m").text(minutes);
                    $timebox.find(".time_s").text(seconds);
                    --max_time;
                }else{
                    clearInterval(timer);
                    $("#count_box > s").text("秒杀进行中");
                    $timebox.find(".time_m").text("00");
                    $timebox.find(".time_s").text("00");
                    $("#count_box").attr("data-status","start");
                }

            }
        }
    };

    var slide = {
        init:function(){
            this.bindFun();
        },
        bindFun:function(){
            var length = $(".scrollwrap li").length;
            if(length > 5){
                $(".icon-arrowLeft-on").click(function(){
                    $(".scrollwrap").animate({"margin-left":"-1030px"},600,"swing");
                    $(this).attr("class","icon-arrowLeft");
                    $(".icon-arrowRight").attr("class","icon-arrowRight-on");

                    return false
                });

                $(".icon-arrowRight-on").live("click",function(){
                    $(".scrollwrap").animate({"margin-left":"0px"},600,"swing");
                    $(this).attr("class","icon-arrowRight");
                    $(".icon-arrowLeft").attr("class","icon-arrowLeft-on");
                    return false
                });
            }else{
                $(".arrow").css("visibility","hidden");
            }

        }
    }



})();