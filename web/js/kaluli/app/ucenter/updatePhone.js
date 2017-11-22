/**
 * Created by jiangyanghe on 16/7/5.
 */
/**
 * Created by jiangyanghe on 16/7/4.
 */
requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        validateUtil:"modules/passport/validate",
        clock:"modules/passport/clock"

    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});

require(["validateUtil","clock"],
    function(validateUtil,clock){
        $(function(){
            function isMobile(){//判断是否是手机
                if(validateUtil.testMobile($('#mobile').val())){
                    $('#mobileTip').show().html('<img src="//kaluli.hoopchina.com.cn/images/kaluli/passport/check.png">');
                }else{
                    $('#mobileTip').show().text('号码格式错误').css({"color":"#E0112C","margin-left":"10px","margin-top":"22px"});
                    return;
                };
            }
            /**
             * 手机获取动态密码
             */
            $('#getPwd').click(function(){
                var mobile = $("#mobileValue").val();
                $.ajax({
                        url: '/passport/sendAuthCode',
                        dataType: 'json',
                        data: {
                            mobile: mobile,
                            _csrf_token: $('#_csrf_token').val()
                        },
                        success: function (response){
                            if (response.status == 1) {
                                clock.count_down(60,$('#getPwd'));
                            }else{
                                $('#codeError').show().text(response.msg);
                            }
                        },
                        error:function(){
                            $('#codeError').show().text('系统错误，请稍后再试');
                        }
                    }
                );
            });

            /**
             * 修改手机第一步 提交
             */
            $('#stepOneSubmit').click(function(){
                if($('#dynamicCode').val().length != 6){
                    $('#codeError').show().text('验证码不正确');
                    return;
                }
                $.ajax({
                        url: '/passport/auth_check',
                        dataType: 'json',
                        data: {mobile: $("#mobileValue").val(),authcode:$("#dynamicCode").val(),check:1},
                        success: function (response){
                            if (response.status == 1) {
                                $('.stepOne').hide();
                                $('.stepTwo').show();
                                $('.stepThree').hide();
                            }else{
                                $('#codeError').show().text(response.msg).css("color","#E1364E");
                            }
                        }
                    }
                );
            });
/***********************************修改手机第二步  开始**************************************************************/
            $('#mobile').blur(function(){
                isMobile();

            });
            $('#getPwdTwo').click(function(){
                var mobile = $('#mobile').val();
                if(mobile == $("#mobileValue").val()) {
                    $('#codeErrorTwo').show().text('新手机号码必须和旧手机号码不同');
                    $('#mobile').focus();
                } else if($.trim(mobile) == ""){
                    $('#codeErrorTwo').show().text('新手机不能为空');
                    $('#mobile').focus();
                } else {
                    $.ajax({
                            url: '/passport/sendAuthCode',
                            dataType: 'json',
                            data: {mobile: mobile,phoneCheck:1,_csrf_token: $('#_csrf_token').val()},
                            success: function (response) {
                                if (response.status == 1) {
                                    clock.count_down(60, $('#getPwdTwo'));
                                } else {
                                    $('#codeErrorTwo').show().text(response.msg);
                                }
                            },
                            error: function () {
                                $('#codeErrorTwo').show().text('系统错误，请稍后再试');
                            }
                        }
                    );
                }
            });


            /**
             * 修改密码第二步，提交密码
             */
            $('#stepTwoSubmit').click(function(){
                isMobile();
                var jumpurl = $("#jumpurl").val();
                var mobile = $('#mobile').val();
                var dynamicCodeTwo = $('#dynamicCodeTwo').val();
                if(dynamicCodeTwo.length!=6){
                    $('#codeErrorTwo').show().text('验证码不正确');
                }
                $.ajax({
                        url: '/passport/updateMobile',
                        dataType: 'json',
                        data: {mobile: mobile,code:dynamicCodeTwo,userId:$("#uid").val()},
                        success: function (response){
                            if (response.status == 1) {
                                $('.stepOne').hide();
                                $('.stepTwo').hide();
                                $('.stepThree').show();
                                showTime(jumpurl);//跳转的url
                            }else{
                                $('#pwdTip').text(data.msg).css("color","#E1364E");
                            }
                        }
                    }
                );
            });
            var t = 6;
            function showTime(jumpurl){
                t --;
                $('#second').text(t);
                if(t==0){
                    window.location.href=jumpurl;
                }else{
                    //setTimeout(showTime(jumpurl),1000);
                    setTimeout(function(){
                        showTime(jumpurl)
                    },1000);
                }

            }


        });
    });