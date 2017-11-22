/**
 * Created by jiangyanghe on 16/6/30.
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
            /**
             * 选项卡的显示隐藏
             */
            $('.navs>span').click(function(){
                $('.navs>span').removeClass('active');
                $('.nav-item').attr('flag','0');
                $(this).addClass('active');
                $('.error-tip').hide();
                if($(this).attr('name') == 'normal'){
                    $('#codeImg').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
                    $('.normallogin').show();
                    $('.dynamiclogin').hide();
                    $(this).attr('flag','1');

                }else{
                    $('#codeImg2').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
                    $('#nav2').attr('flag','1');
                    $('.normallogin').hide();
                    $('.dynamiclogin').show();
                }
            });

            function normalTipHide(){
                $('.normallogin  .error-tip').hide();
            }
            function account(){
                if(validateUtil.testMobile($('#username').val())){
                }else{
                    $('.normallogin  .error-tip').show();
                    $('#normalError').text('手机号格式错误');
                    return false;
                };
            }
            function password(){
                if(validateUtil.testPassword($('#password').val())== false){
                    $('.normallogin  .error-tip').show();
                    $('#normalError').text('密码不足6位，或使用中文字符及特殊字符');
                    return false;
                }
            }

            $('#username').blur(function(){
                account();
            });
            $('#username').focus(function(){
                normalTipHide();
            });
            $('#password').blur(function(){
                password();
            });
            $('#password').focus(function(){
                normalTipHide();
            });
            $('#dynamicUsername').focus(function(){
                $('.dynamiclogin  .error-tip').hide();
            });
            $('#code2').focus(function(){
                $('.dynamiclogin  .error-tip').hide();
            });
            $('#dynamicCode').focus(function(){
                $('.dynamiclogin  .error-tip').hide();
            });

            /**
             * 账号密码登录
             */
            $('#normalSubmit').click(function(){
                var Obtn = $(this);
                var buttonText = Obtn.text();
                if (account()==false){
                    return;
                }
                if( password() ==false){
                    return;
                }
                if($('.authcodearea').data("check") == 1){
                    if($('#code').val().length == 0){
                        $('.error-tip').show();
                        $('#normalError').text('验证码不能为空');
                        return;
                    }
                }
                $(this).text('正在'+buttonText+'...');
                var pwd = submit.md5($('#password').val());
                submit.normalSubmit('/passport/login_active',3,$('#username').val(), pwd, $('#code').val(), $('#jumpUrl').val(), '',Obtn,buttonText)

            });
            /**
             * 更换验证码
             */
            $('#changeCode').click(function(){
                $('#codeImg').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
            });
            $('#changeCode2').click(function(){
                $('#codeImg2').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
            });
            /**
             * 手机获取动态密码
             */
            $('#getPwd').click(function(){
                $('.dynamiclogin  .error-tip').hide();
                var mobile = $('#dynamicUsername').val();
                var verify = $('#code2').val();
                var _csrf_token = $('#_csrf_token').val();
                if(verify == ""){
                    $('.dynamiclogin  .error-tip').show();
                    $('#dynamicError').text('图片验证码不能为空');
                    return;
                }
                if(validateUtil.testMobile(mobile) == false){
                    $('.dynamiclogin > .error-tip').show();
                    $('#dynamicError').text('手机号格式错误');
                    return;
                }
                $.ajax({
                        url: '/passport/sendAuthCode',
                        dataType: 'json',
                        data: {mobile: mobile, verify:verify,_csrf_token:_csrf_token},
                        success: function (response){
                            if (response.status != 0) {
                                clock.count_down(60,$('#getPwd'));
                            }else{

                                $('#codeImg2').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));

                                $('.dynamiclogin  .error-tip').show();
                                $('#dynamicError').text(response.msg);
                            }
                        },
                        error:function(){
                            $('.dynamiclogin > .error-tip').show();
                            $('#dynamicError').text('系统暂不可用，请稍后再试');
                            $('#codeImg2').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
                        }
                    }
                );
            });
            /**
             * 动态密码登录
             */
            $('#dynamicSubmit').click(function(){
                var mobile = $('#dynamicUsername').val();
                var Obtn = $(this);
                var buttonText = Obtn.text();
                if(validateUtil.testMobile(mobile) == false){
                    $('.dynamiclogin  .error-tip').show();
                    $('#dynamicError').text('手机号格式错误');
                    return;
                }
                if($('#dynamicCode').val().length !=6){
                    $('.dynamiclogin  .error-tip').show();
                    $('#dynamicError').text('验证码有误');
                    return;
                }
                if($('#code2').val() == ""){
                    $('.dynamiclogin  .error-tip').show();
                    $('#dynamicError').text('验证码不能为空');
                    return;
                }
                $(this).text('正在'+buttonText+'...');
                submit.normalSubmit('/passport/login_active',2,mobile,'',$('#code2').val(),$('#jumpUrl').val(),$('#dynamicCode').val(),Obtn,buttonText)

            });


            document.onkeydown = function (e) {
                var theEvent = window.event || e;
                var code = theEvent.keyCode || theEvent.which;
                if (code == 13) {
                    if($('#nav2').attr('flag') == 1){
                        $('#dynamicSubmit').click();
                    }else{
                        $('#normalSubmit').click();
                    }
                }
            };

        });
    });