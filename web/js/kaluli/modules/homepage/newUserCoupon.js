define(function(){
    //新人组合优惠券
    $("body").append('<div class="alert_callback"><div></div><i></i><p></p></div>')
    var hasgetCard = false;
    if(!hasgetCard && $(".button").attr("data-received") == 1 ){
        $(".button").text("已领取").addClass("received");
        hasgetCard = true;
    }
    $("#freebies").click(function(){
        __dace.sendEvent('kaluli_newusercoupon_homepage');
        if(!hasgetCard && $('.button').attr("data-received") == 1 ){
            $(".button").text("已领取").addClass("received");
            hasgetCard = true;
            return false
        }
        // if($(".login").length > 0){
        //
        //     !hasgetCard && $.getJSON("//www.kaluli.com/api/getNewUserCard?card_group_number="+$("#cardNumber").text(),function(res){
        //         if(res.status == 1){
        //             $(".alert_callback").addClass("success");
        //             $("#cardNumber").text(Math.round($("#cardNumber").text()*1+1));
        //             $(".button").text("已领取").addClass("received");
        //             hasgetCard = true;
        //         }else{
        //             $(".alert_callback").addClass("fail");
        //         }
        //         $(".alert_callback p").text(res.msg);
        //         $(".alert_callback").show();
        //         setTimeout(function(){$(".alert_callback").hide()},1500);
        //     });
        // }else{
        //     window.location.href="//passport.kaluli.com/login?project=kaluli&from=pc&fback=true&jumpurl=http%3A%2F%2Fwww.kaluli.com%2F";
        // }
        window.location.href="//www.kaluli.com/activity/coupon";

    });

    $(".freebies-rule").click(function(){
        $(".freeies-rules-grid").fadeIn();
    });
    $(".freebies-rule-map").click(function(){
        $(".freeies-rules-grid").fadeOut();
    });
});