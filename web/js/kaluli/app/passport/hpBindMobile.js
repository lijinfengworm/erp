/**
 * Created by jiangyanghe on 16/7/18.
 */
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
            $('#getPwd').click(function(){
                $('.error-tips').hide();
                var mobile = $('#mobile').val();
                if(mobile == ""){
                    $('.error-tips').show();
                    $('.error-tips .errormsg').text("手机号码不能为空");
                    return;
                }
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
                                $('.error-tips').show();
                                $('.error-tips .errormsg').text(response.msg);
                            }
                        },
                        error:function(){
                            $('.error-tips').show();
                            $('.error-tips .errormsg').text('系统错误，请稍后再试');
                        }
                    }
                );
            });

        });
        /**
         * 绑定操作
         */
        $('#stepOneSubmit').click(function(){
            var mobile = $('#mobile').val();
            var user_id = $('#user_id').val();
            var authcode =  $('#dynamicCode').val();
            var jumpurl = $('#jumpurl').val();
            $.ajax({
                    url: '/api/bindMobileToHuPu',
                    dataType: 'json',
                    data: {mobile: mobile, user_id: user_id, authcode: authcode, jumpurl:jumpurl},
                    success: function (response){
                        if (response.status == 200) {
                            $('.stepOne').hide();
                            $('.stepThree').show();
                            showTime(response.data.jumpurl);
                        }else{
                            $('.error-tips').show();
                            $('.error-tips .errormsg').text(response.msg);
                        }
                    },
                    error:function(){
                        $('.error-tips').show();
                        $('.error-tips .errormsg').text('系统错误，请稍后再试');
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
