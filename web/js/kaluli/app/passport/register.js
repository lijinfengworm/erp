/**
 * Created by jiangyanghe on 16/7/1.
 */
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
            function normalTipHide(){
                $('.normallogin > .error-tip').hide();
            }
            function isMobile(){
                if(validateUtil.testMobile($('#mobile').val())){
                    $('#mobileTip').show().html('<img src="//kaluli.hoopchina.com.cn/images/kaluli/passport/check.png">');
                }else{
                    $('#mobileTip').show().text('号码格式错误');
                    return;
                };
            }
            function password(){
                if(validateUtil.testPassword($('#password').val())== false){
                    $('.normallogin > .error-tip').show();
                    $('#normalError').text('密码不足6位，或使用中文字符及特殊字符');
                    return;
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
                if(validateUtil.testMobile(mobile) == false){
                    $('#mobileTip').show().text('号码格式错误');
                    return;
                }
                $.ajax({
                        url: '/passport/sendAuthCode',
                        dataType: 'json',
                        data: {mobile: mobile,_csrf_token:_csrf_token},
                        success: function (response){
                            if (response.status == 1) {
                                clock.count_down(60,$('#getPwd'));
                            }else{
                                $('#codeError').show().text(data.msg);
                            }
                        },
                        error:function(){
                            $('#codeError').show().text('系统错误，请稍后再试');
                        }
                    }
                );
            });

            $('#checkbox').click(function(){
                if($('#checkbox').attr('checked') !== 'checked'){
                    $('#xieyiTip').text('请同意协议并勾选').css({'color':'#ce0012','margin-left':'5px'});
                }else{
                    $('#xieyiTip').hide();
                }
            });

            /**
             * 动态密码登录
             */
            $('#register').click(function(){
                var mobile = $('#mobile').val();
                var Obtn = $(this);
                var buttonText = Obtn.text();
                if(validateUtil.testMobile(mobile) == false){
                    $('#mobileTip').show().text('号码格式错误');
                    return;
                }
                if($('#dynamicCode').val().length !=6){
                    $('#codeError').show().text('动态密码不正确');
                    return;
                }
                if($('#password').val().length<6){
                    $('.pwd').text('请输入6位数以上的密码').css('color','#ce0012');
                }
                if($('#checkbox').attr('checked') !== 'checked'){
                    $('#xieyiTip').text('请同意协议并勾选').css({'color':'#ce0012','margin-left':'5px'});
                }
                var pwd = submit.md5($('#password').val());
                var level = validateUtil.checkStrong($('#password').val());
                $(this).text('正在'+buttonText+'...');
                submit.normalSubmit('/passport/login_active',1,mobile, pwd+'-'+level, '',$('#jumpUrl').val(), $('#dynamicCode').val(),Obtn,buttonText)
            });


            document.onkeydown = function (e) {
                var theEvent = window.event || e;
                var code = theEvent.keyCode || theEvent.which;
                if (code == 13) {
                    $('#register').click();
                }
            };


            $('#kalulixieyi').click(function(){
                $('.xieyiPop').show(100);
            });
            $('#closePop').click(function(){
                $('.xieyiPop').hide(100);
            })
        });
    });