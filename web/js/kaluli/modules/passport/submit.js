/**
 * Created by jiangyanghe on 16/6/30.
 */
define(function(){
    "use strict";
    /**
     * activity：=1 注册 int
     * activity=2 手机登陆
     * activity=3  手机和密码登陆
     * verify 图片验证码
     * seccode 短信验证码
     *buttonText API接口不通时，按钮文字
     * @type {{normalSubmit: Function}}.

     */
    var submit = {
        md5:function(val){
            var hash=hex_md5(val);
            return hash;
        },
       normalSubmit: function(url,activity,mobile,password,verify,jumpurl,seccode,Obtn,buttonText){
           $.ajax({
                   url: url,
                   dataType: 'json',
                   data: {
                       activity: activity,
                       mobile: mobile,
                       password: password,
                       verify: verify,
                       jumpurl:jumpurl,
                       authcode: seccode
                   },

                   success: function (response){

                       if (response.status == 200) {
                            window.location.href= response.data.jumpUrl;
                       }else if(response.status == 201){
                           window.location.href = "//www.kaluli.com/passport/petName/from/pc/jumpurl/"+response.data.jumpUrl+"/user_id/"+response.data.user_id;
                       }else if(response.status == 202){

                           window.location.href = "//www.kaluli.com/passport/hpBindMobile/from/pc/step/0/jumpurl/"+response.data.jumpUrl+"/user_id/"+response.data.user_id;
                       }else if(response.status == 203){
                            $('.error-tip').show();
                            $('.error-tip').html('<img src="//kaluli.hoopchina.com.cn/images/kaluli/passport/error.png" alt="errorIcon"><span>用户不存在-跳转到<a style="color:#3b5cbd" href="//www.kaluli.com/passport/register/from/pc/jumpurl/'+response.data.jumpUrl+'">注册</a></span>');
                           Obtn.text(buttonText);
                       }else{
                           if(response.data.codeNum != "undefined" && parseInt(response.data['codeNum']) > 5){
                               $('.authcodearea').show();
                               $('.authcodearea').data("check", "1");
                           }
                           $('.error-tip').show();
                           var errormsg = response.msg;
                           $('.error-tip span').text(errormsg.replace(/<br>/g,''));
                           $('#codeImg').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
                           $('#codeImg2').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
                           Obtn.text(buttonText);
                       }
                   },
                   error: function () {
                       Obtn.text(buttonText);
                   }
               }
           );
           $('#codeImg2').attr('src','//www.kaluli.com/api/verify?t='+(new Date()));
       }



    };

    return submit;
});