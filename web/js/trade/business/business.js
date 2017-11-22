var  obj = {
    //发送验证码
    sendyzm:function(ts){
        var that = this;
        that.sendObject = $(ts);
		if($('#phone').val()&& !$("#phone").attr('disabled')){
         var reg = /^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/;
   		 if(!reg.test($('#phone').val())){
   		 	$("#phone").next(".error").html('<em></em>'+"请输入正确的手机号码");
   		 	return false;
   		 }else{
   		 	$("#phone").next(".error").html('');
   		 }
        }
        if(!that.sendObject.data('close')){
            $.post('/business/ajaxSendYZM',{'phone':$('#phone').val()},function(msg){
                if(msg.status == 500)
                    location.href = 'http://passport.hupu.com/login?jumpurl='+msg.current_url;
                else if(msg.status == 501)
                    that.sendObject.next().html('<em></em>'+msg.msg);
                else{
                    that.sendObject.next().html('');
                    that.sendObject.attr('data-close', true);
                    that.sendObject.data('close', true);

                    that.countDown();
                }
            },'json')
        }
    },
    //倒计时
    countDown:function(){
        var that = this;
        var startNum = 60;
        this.sendObject.html(startNum);

        var si = setInterval(function(){
            if(startNum <= 0){
                clearInterval(si);
                that.sendObject.attr('data-close', false);
                that.sendObject.data('close',false);
                that.sendObject.html('点击获取');
            }else{
                --startNum;
                that.sendObject.html(startNum);
            }
        },1000);
    },
    //提交
    submit:function(){
        var hasEmptyVal = 0;
		$(".error").html('');
        if($('#phone').val()&& !$("#phone").attr('disabled')){
         var reg = /^0{0,1}(13[0-9]|15[0-9]|18[0-9])[0-9]{8}$/;
   		 if(!reg.test($('#phone').val())){
   		 	$("#phone").next(".error").html('<em></em>'+"请输入正确的手机号码");
   		 	
   		 }else{
   		 	$("#phone").next(".error").html('');
   		 }
        }
		if($("#email").val()){
			var reg = /^\w+([-+.]\w+)*\@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
			 if(!reg.test($('#email').val())){
				$("#email").next(".error").html('<em></em>'+"请输入正确的邮箱");
				
			 }else{
				$("#email").next(".error").html('');
			 }
		}
		if($("#passwd").val()){
			var reg = /^(?![^a-zA-Z]+$)(?!\D+$).{6,20}$/;
			 if(!reg.test($('#passwd').val())){
				$(".tips").hide();
				$("#passwd").nextAll(".error").html('<em></em>'+"支付密码为6-20位且必须包含数字和字母.");
				
			 }else{  
				$("#passwd").nextAll(".error").html('');
			 }
		}
		if($("#repasswd").val()){
			 if($("#repasswd").val()!= $("#passwd").val()){
				$("#repasswd").nextAll(".error").html('<em></em>'+"确认密码不正确.");
				
			 }else{  
				$("#repasswd").nextAll(".error").html('');
			 }
		}
       
        $('li').each(function(){
            if($(this).find('input').val() == ''){
                $(this).find('.error').html($(this).find('.t1').html()+'不能为空.');
                if($(this).find('input').attr('name') == 'passwd'){
                    $(this).find('.tips').hide();
                }
                ++hasEmptyVal;
               
            }
        })

        if(!hasEmptyVal){
        	$(".error").html('');
            $('form').submit();
        }
    }
}