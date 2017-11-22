/**
 * Created by jiangyanghe on 16/7/4.
 */
$(function(){
    var check = function(){
        if($('#kaluli_user').val() == ""){
            $('.petNameTip').show();
            $('.petNameTip').text('用户名不能为空');
            return false;
        }
        if($('#kaluli_id').val() == ""){
            $('.petNameTip').show();
            $('.petNameTip').text('用户ID未生成');
            return false;
        }

    };

    $('.normalSubmit').click(function(){
        if(check() == false){
            return;
        }
        var data = {
            'user_name' : $('#kaluli_user').val(),
            'user_id' : $('#kaluli_id').val(),
            'jumpurl' : $('#jumpUrl').val()
        };
        $.ajax({
            type: "post",
            url: "/passport/bind_username",
            data: data,
            success:function(s){
                var dataObj = $.parseJSON(s);
                if(dataObj.status == 200){
                    window.location.href = dataObj.data.jumpUrl;
                }else{
                    $('.petNameTip').show();
                    $('.petNameTip').text(dataObj.msg);
                    return;
                }
            }
        });
    });


});