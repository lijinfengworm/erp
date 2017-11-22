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

            /**
             * 选项卡的显示隐藏
             */
            $('.navs>span').click(function(){
                $('.navs>span').removeClass('active');
                $(this).addClass('active');
                $('.error-tip').hide();
                if($(this).attr('name') == 'mobileLogin'){
                    $('#codeImg').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
                    $('.hpMobileLogin').show();
                    $('.hpAccountLogin').hide();
                }else{
                    $('.hpMobileLogin').hide();
                    $('.hpAccountLogin').show();
                }
            });


            function isMobile(){
                if(validateUtil.testMobile($('#mobile').val())){
                }else{
                    $('.error-tip').show();
                    $('#normalError').text('号码格式错误');
                    return false;
                };
            }
            function isCheacked(id){
                if(validateUtil.testChecked(id)){
                    $('.error-tip').hide();
                }else{
                    $('.error-tip').show();
                    $('#normalError').text('请同意协议并勾选');
                    return false;
                }
            }

            $('#mobile').blur(function(){
                isMobile();
            });
            /**
             * 更换验证码
             */
            $('#changeCode').click(function(){
                $('#codeImg').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
            });

            /**
             * 手机获取动态密码
             */
            $('#getPwd').click(function(){
                var _csrf_token = $('#_csrf_token').val();
                var mobile = $('#mobile').val();
                var verify = $('#code').val();
                if(validateUtil.testMobile(mobile) == false){
                    $('.error-area').removeClass('visible-hidden');
                    $('#normalError').text('号码格式错误');
                    return;
                }
                if($('#code').val().length == 0){
                    $('.error-area').removeClass('visible-hidden');
                    $('#dynamicError').text('验证码不能为空');
                    return;
                }
                $.ajax({
                        url: '/api/getPassportIdentifyingCode',
                        dataType: 'json',
                        data: {mobile: mobile, "hupuLogin": 1, "check" : 1, "verify" : verify,_csrf_token:_csrf_token},
                        success: function (response){
                            if (response.status == 1) {
                                clock.count_down(60,$('#getPwd'));
                            }else{
                                $('.error-tip').show();
                                $('.error-tip span').text(response.msg);
                            }
                        },
                        error:function(){
                            $('#codeError').show().text('系统错误，请稍后再试');
                        }
                    }
                );
            });

            $('#checkbox').click(function(){
                isCheacked($('#checkbox'));
            });

            /**
             * 动态密码登录
             */
            $('#submit').click(function(){
                var mobile = $('#mobile').val();
                var Obtn = $(this);
                var buttonText = Obtn.text();
                if(isMobile() == false){
                    return;
                };
                if($('#dynamicCode').val().length !=6){
                    $('.error-area').removeClass('visible-hidden');
                    $('#normalError').text('动态密码不正确');
                    return;
                }
                isCheacked($('#checkbox'));
                $(this).text('正在'+buttonText+'...');
                submit.normalSubmit('/api/getPassportUserInfo',1,mobile,'',$('#code').val(),$('#jumpUrl').val(),$('#dynamicCode').val(),Obtn,buttonText);

            });
            /**
             * 账号和密码登陆
             */
            $('#hpAccountSubmit').click(function(){

                if($('#hpUsername').val() == ""){
                    $('.error-tip').show();
                    $('.error-tip span').text("用户名不能为空");
                    return;
                }
                if($('#hpPassword').val() == ""){
                    $('.error-tip').show();
                    $('.error-tip span').text("密码不能为空");
                    return;
                }
                var Obtn = $(this);
                var buttonText = Obtn.text();
                $(this).text('正在'+buttonText+'...');
                submit.normalSubmit('/api/userPassportToHuPu',5,$('#hpUsername').val(), $('#hpPassword').val(),'',$('#jumpUrl').val(),'',Obtn,buttonText);
            });

            $('#closePop').click(function(){
                $('.xieyiPop').hide(200);
            });
            $('#openPop').click(function(){
                $('.xieyiPop').show(200);
            });
            $('.hpback').click(function(){
                window.location.href= $('#fBack').val();
            });
        });
    });