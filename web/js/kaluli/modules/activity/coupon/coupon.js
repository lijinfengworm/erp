/**
 * Created by jiangyanghe on 16/3/31.
 */
$(function(){
    //
    // // requirejs.config({
    // //     baseUrl:"//kaluli.hoopchina.com.cn/js/kaluli/",
    // //     paths:{
    // //         "alertbox":"modules/common/alertbox"
    // //     },
    // //     urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
    // // });
    // require(["alertbox"],function(alertbox){
    /**
     * 倒计时插件
     * @param count 总的倒计时插件
     * @param o 倒计时显示的对象，
     */
    $.count_down = function(count,o){
        var wait=count;
        time(o);
        function time(o) {
            if (wait == 0) {
                o.removeAttribute("disabled");
                o.value="获取验证码";
                wait = 60;
            } else {
                o.setAttribute("disabled", true);
                o.value = "重新发送(" + wait + ")";
                wait--;
                setTimeout(function() {
                        time(o)
                    },
                    1000)
            }
        }
    };
    $('#getCode').click(function(){

        var _this = this;
        var mobile = $('#mobile').val();
        if(/^(0|86|17951)?(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/.test(mobile)){

            $.ajax({
                type: "POST",
                url: "//www.kaluli.com/api/getPassportIdentifyingCode",
                data: {mobile:mobile,activity:'1'},
                dataType: "json",
                success: function(data){
                    console.log(JSON.stringify(data));
                    if(data.status == '1'){
                        if(data.msg == '手机号码已经注册过'){
                            $('.has-accound-pop').show();
                        }else{
                            alert(data.msg);
                        }
                    }else{
                        $.count_down(60,_this);
                    }
                }
            });
        }else{
            alert('请输入正确的手机号');
        }
    });

    $('#submit').click(function(){
        var authcode = $('#code').val();
        if(authcode.length != 6){
            alert('请填写正确的验证码');
            return;
        }else{
            $.ajax({
                type: "POST",
                url: "//www.kaluli.com/api/GetPassportUserInfo",
                data: {mobile:$('#mobile').val(),authcode:authcode},
                dataType: "json",
                success: function(data){
                    if(data.status == 1){
                        alert(data.msg);
                    }else{
                        location.reload();
                    }
                }
            });
        }
    });
    
    $('#getCoupon').click(function(){
        $.ajax({
            type: "POST",
            url: "//www.kaluli.com/api/GetNewUserCard",
            data: {},
            dataType: "json",
            success: function(data){
                console.log(data);
                if(data.status == 0){
                    alert(data.msg);
                }else{
                    location.reload();
                }
            }
        });
    });

    $('#cancle_pop').click(function(){
        $('.has-accound-pop').hide(200);
    });
    $('#comfirm_pop').click(function(){
        window.location.href="//www.kaluli.com/passport/login/from/pc/fback/true/jumpurl/http%3A%2F%2Fwww.kaluli.com%2Factivity%2Fcoupon"
    });
    // });

    var groupData = ["增强肌肉","提升机能"];
    var length = groupData.length,
        navEle = "<ul class='scrollNav'><li class='backtoTop'>TOP</li></ul>";
    $(".pagecontent").append(navEle);
    for(var i=0;i<groupData.length;i++){
        length--;
        var title = groupData[length];
        $(".scrollNav").prepend("<li>"+title+"</li>");
    }

    scrollNav();
    $(window).scroll(scrollNav);
    $(window).resize(scrollNav);
    function scrollNav(){
        var w = $(window).width(),
            st = $(window).scrollTop(),
            ot = $(".pagecontent").offset().top,
            pw = $(".pagecontent").width(),
            or = Math.round((w-pw)/2)-171;
        if(st > ot){
            $(".scrollNav").css({position:"fixed",right:or+"px",top:"30px"});
            if(w < 1424 ){
                $(".scrollNav").css("right","0");
            }
        }else{
            $(".scrollNav").css({position:"absolute",right:"-166px",top:"105px"});
            if(w < 1424 ){
                var right = Math.round((w-pw)/2*(-1));
                $(".scrollNav").css({position:"absolute",right:right+"px",top:"105px"});
            }
        }
    }

    $(".scrollNav li").click(function(){
        var index = $(this).index();
        if($(this).hasClass('backtoTop')){
            return false
        }
        var st = $(".grid").eq(index).offset().top-20;
        $("html,body").animate({scrollTop:st}, 350);
    });

    $(".backtoTop").click(function(){
        $("body,html").animate({scrollTop:0}, 350);
    });


});