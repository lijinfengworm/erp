requirejs.config({
    baseUrl:"//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        "birthday":"modules/passport/birthday",
        "address": "modules/ucenter/address"
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});

require(["birthday","address"],
    function(birthday,address){
        $(function(){
            //年月日封装
            birthday.initYear("#year");
            birthday.initMonth("#month");

            if($("#day").attr("rel") != "") {
                birthday.buildDay("#year","#month","#day");
            }

            $("#year").change(function(){
                birthday.buildDay("#year","#month","#day");
            });

            $("#month").change(function(){
                birthday.buildDay("#year","#month","#day");
            });
            //地址
            address.cityAdd();

            function uniquePetName(username){
                //校验重复昵称
                var exist = false;
                $.ajax({
                    url:"//www.kaluli.com/ucenter/checkUserName",
                    type:'post',
                    dataType:'json',
                    async: false,
                    data:{uid:$("#uid").val(),username:username},
                    success:function(response){
                        if(response.status ==1) {
                            exist = true;
                        } else {
                            exist = response.msg;
                        }
                    }
                });
                return exist;
            }

            function checkInfo(id,value,threshold) {
                if(!isNaN(value)) {
                    if(parseFloat(value) >threshold || parseFloat(value) <0) {
                        $(id).next("#petNameTip").html("请填写正确格式").show();
                        $(id).focus();
                        return false;
                    } else {
                        $(id).next("#petNameTip").html('').show();
                        return true;
                    }
                } else {
                    $(id).next("#petNameTip").html("请填写正确格式").show();
                    $(id).focus();
                    return false;
                }
            }

            //各种校验
            $("#username").blur(function(){
                var _that = $(this);
                if($.trim(_that.val()) == "") {
                    _that.next("#petNameTip").html("请输入昵称").show();
                    _that.focus();
                }
                if(uniquePetName(_that.val()) == true){
                    _that.next("#petNameTip").html('<img src="//kaluli.hoopchina.com.cn/images/kaluli/passport/check.png">').show();
                }else{
                    _that.next("#petNameTip").html(uniquePetName(_that.val())).show();
                    _that.focus();
                }
            });
            //身高校验
            $("#height").blur(function(){
                checkInfo("#height",$(this).val(),300);
            });
            //体脂校验
            $("#fat").blur(function(){
               checkInfo("#fat",$(this).val(),99);
            });
            //体重
            $("#weight").blur(function(){
                checkInfo("#weight",$(this).val(),10000);
            });

            //关闭浮层
            $('.closePop').click(function(){
                location.reload();
            });

            //提交更新
            $("#userSubMit").click(function(){
                if(uniquePetName($("#username").val()) != true){
                    $("#petNameTip").html(uniquePetName($("#username").val())).show();
                    return;
                }
                if(!checkInfo("#height",$("#height").val(),300)) {
                    return ;
                }
                if(!checkInfo("#fat",$("#fat").val(),99)) {
                    return ;
                }
                if(!checkInfo("#weight",$("#weight").val(),10000)) {
                    return ;
                }

                $.ajax({
                        url: "//www.kaluli.com/ucenter/userSet",
                        dataType: 'json',
                        type:'post',
                        data: {
                            uid:$("#uid").val(),
                            username: $("#username").val(),
                            year: $("#year").val(),
                            month: $("#month").val(),
                            day: $("#day").val(),
                            sex:$("input:radio:checked").val(),
                            province: $("#province").val(),
                            city:$("#city").val(),
                            job:$("#job").val(),
                            height:$("#height").val(),
                            weight:$("#weight").val(),
                            fat:$("#fat").val()
                        },
                        success: function (response){
                            if(response.status == 1) {
                                $(".successPop").show();
                            }

                        }
                    }
                );
            });
        });
    });
