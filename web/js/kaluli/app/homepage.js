requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        scrollNav:"modules/homepage/scrollNav",
        picScroll:"lib/picScroll",
        tips:"modules/common/tips",
        countDown:"modules/common/countDown",
        newUserCoupon:"modules/homepage/newUserCoupon"
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});

require([
    "scrollNav",
    "picScroll",
    "countDown",
    "tips",
    "newUserCoupon"],
function(scrollNav,picScroll,countDown,newUserCoupon){

    $(function(){

        //限时抢购
        new countDown({//倒计时
            $timebox:$("#count_box"),
            class_time_d:".time_d",
            class_time_h:".time_h",
            class_time_m:".time_m",
            class_time_s:".time_s",
            time_attribute:"leftSec"
        },function(){
            //callback
            if($("#count_box").length>0){
                window.location.reload();
            }
        });

        var a = new scrollNav();
        a.init();
 
        var sildnum = $(".kv-images > li").length,ison;
        if(sildnum == 1){
            $(".kv-main .menu").hide();
            return false;
        }

        for(var i = 0;i < sildnum;i++){
            if(i == 0) ison = "on"
            else ison = "";
            $(".J_ui_a ul").append('<li class='+ison+'></li>');
        }       

        $(".slider").slide({
            css: {"width": 1216, "height": 336},
            config: {
                "time": 5000,
                "type": "fade",
                "speed": 600,
                "button": true,
                "butArr": ".J_ui_a li"
            },
            completes: function (o) {//初始化完成执行动作  
                $(".kv-background li:eq(0)").fadeIn(300);          
            },
            before:function(i){
                $(".kv-background li").fadeOut(500);     
                $(".kv-background li:eq("+i+")").fadeIn(500);          
            }  
        });

        $(".getcoupon").on('click',function(){
            if(!$(this).hasClass("getcoupon")){
                return false
            }
           var id = $(this).attr("data-couponid"),
               $this = $(this);
           if($(".login").length>0){
                $.getJSON('//www.kaluli.com/api/receiveCoupon?id='+id+'&callback=?',function(data){
                    if(data.status ==0){
                        $this.removeClass("getcoupon").addClass('active').text("已经领取");
                    }else{
                        $this.tips(data.msg,{
                            left:$this.offset().left,
                            top:$this.offset().top+$this.outerHeight()+10
                        });
                        $this.removeClass("getcoupon").addClass('active');
                    }
                });
           }else{
                var loginurl = $(".unlogin a:eq(1)").attr("href");
                window.location.href= loginurl;
           }

           return false
        });
    });
})