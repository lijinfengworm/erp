/**
 * Created by jiangyanghe on 16/7/4.
 */
requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        submit:"modules/passport/submit",
        tips:"modules/common/tips",
        validateUtil:"modules/passport/validate",
        clock:"modules/passport/clock"

    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});

require(["submit", "tips", "validateUtil","clock"],
    function(submit,tips,validateUtil,clock){
        $(function(){
            function normalTipHide(){
                $('.normallogin > .error-tip').hide();
            }
            function isMobile(){
                if(validateUtil.testMobile($('#mobile').val())){
                    $('#mobileTip').show().html('<img src="//kaluli.hoopchina.com.cn/images/kaluli/passport/check.png">');
                    return true;
                }else{
                    $('#mobileTip').show().text('号码格式错误').css({"color":"#de132b","margin-left":"12px","margin-top":"22px"});
                    return false;
                };
            }
            function password(){
                if(validateUtil.testPassword($('#password').val())== false){
                    $('.normallogin > .error-tip').show();
                    $('#normalError').text('密码不足6位，或使用中文字符及特殊字符');
                    return false;
                }else{
                    return true;
                }
            }

            $('#mobile').blur(function(){
                isMobile();
            });

            /**
             * 手机获取动态密码
             */
            $('#getPwd').click(function(){
                var mobile = $('#mobile').val();
                var _csrf_token = $('#_csrf_token').val();
                if(!isMobile()){
                    return;
                }
                $.ajax({
                        url: '/passport/sendAuthCode',
                        dataType: 'json',
                        data: {mobile: mobile,check:1, _csrf_token: _csrf_token},
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
             * 忘记密码第一步 提交
             */
            $('#stepOneSubmit').click(function(){
                var mobile = $('#mobile').val();
                var stepJump = $('#stepJump').val();
                if(!isMobile()){
                    return;
                }
                var secCode = $('#dynamicCode').val();
                if(secCode.length !=6){
                    $('#codeError').show().text('动态密码不正确');
                    return;
                }

                $.ajax({
                        url: '/passport/auth_check',
                        dataType: 'json',
                        data: {mobile: mobile,authcode:secCode,check:1},
                        success: function (response){
                            if (response.status == 1) {
                                $('.stepOne').hide();
                                $('.stepTwo').show();
                                if(stepJump != undefined){
                                    window.location.href = stepJump+'/mobile/'+mobile;

                                }
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
             * 获取密码强度
             */
            $('#password').bind('input propertychange', function() {
                if( this.value.length >5){
                    var password = this.value;
                    $('#pwdTip').hide();
                    $('.pwdStrength .hidden').hide();
                    var index = validateUtil.checkStrong(password);
                    if(index == 1){
                        $('.strengthOne').show();
                    }else if(index == 2){
                        $('.strengthTwo').show();
                    }else if(index >2){
                        $('.strengthThree').show();
                    }else if(/^[\u4e00-\u9fa5],{0,}$/.test(password)){
                        alert('系统错误');
                    }
                }else{
                    $('.pwdStrength .hidden').hide();
                    $('#pwdTip').show();
                }
            });

            /**
             *校验两次密码是否一样
             */
            $('#confirmPassword').blur(function(){
                console.log($(this).val() +"==="+$('#password').val());
                if($(this).val() !== $('#password').val()){
                    $('#confirmError').text('两次输入密码不一样').css("color","#E1364E");
                }else{
                    console.log(1);
                }
            });
            /**
             * 修改密码第二步，提交密码
             */
            $('#stepTwoSubmit').click(function(){
                var jumpurl = $('#jumpurl').val();
                var mobile = $('#mobile').val();
                var password = $('#password').val();
                var confirmPwd = $('#confirmPassword').val();
                
                if(password.length <6){
                    $('#pwdTip').text('请输入6位数以上密码').css("color","#E1364E");
                    return;
                }
                if(password !== confirmPwd){
                    $('#confirmError').text('两次输入密码不一样').css("color","#E1364E");
                    return;
                }
                if(mobile == ""){
                    $('#confirmError').text('手机号码获取失败,请重试!').css("color","#E1364E");
                    return;
                }
                var pwd = submit.md5(password);
               

                var level = validateUtil.checkStrong(password);
                $.ajax({
                        url: '/passport/updatePassword',
                        dataType: 'json',
                        data: {password: pwd+'-'+level, mobile: mobile},
                        success: function (response){
                            if (response.status == 1) {
                                $('.stepOne').hide();
                                $('.stepTwo').hide();
                                $('.stepThree').show();
                                showTime(jumpurl);//跳转的url
                            }else{
                                $('.pwdStrength').hide();
                                $('#pwdTip').show();
                                $('#pwdTip').text(response.msg).css("color","#E1364E");
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