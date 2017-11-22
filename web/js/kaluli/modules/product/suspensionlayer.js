define(function(){
   var suspensionlayer = {
     defaults:{
        suspensioner:".menu",
        fixed:".fixed-cart-btn",
        buyarea:".cart-btn"
      }, 
     init:function(){
        var t = this;
        t.scrollhandle();
        t.changetitle();
        $(window).scroll(function(){
            t.scrollhandle();
            t.changetitle();
        });
     },
     scrollhandle:function(){
        var t = this,
            st = $(window).scrollTop(),
            suspension = $(".p-c-grid").offset().top+$(".p-c-grid .menu").height(),
            ct = $(t.defaults.buyarea).offset().top + $(t.defaults.buyarea).height(),
            proinfo = $("#proinfo").offset().top,
            comment = $("#comment").offset().top,
            FAQ = $(".FAQ").offset().top;
        if(st > suspension){
            $(".menu").css({"position":"fixed","top":"0px"});
        }else{
            $(".menu").css({"position":"relative","top":"0px"});
        }
        st > ct ? $(t.defaults.fixed).fadeIn() : $(t.defaults.fixed).stop(true,true).fadeOut();
     },
     changetitle:function(){
         var proinfo = $("#proinfo").height(),
             comment = $("#comment").height(),
             FAQ = $(".FAQ").height(),
             switchbox0 = $(".switchbox[data-index=0]").offset().top,
             li = $("#specification li");


         if($(window).scrollTop() < (switchbox0+proinfo-comment-60)){
             li.removeClass('on');
             li.eq(0).addClass('on');
         }else if($(window).scrollTop() < (switchbox0+proinfo-60)){
             li.removeClass('on');
             li.eq(1).addClass('on');
         }else if($(window).scrollTop() < (switchbox0+proinfo+comment-60)){
             li.removeClass('on');
             li.eq(2).addClass('on');
         }

         // if($(window).scrollTop() < ($(".switchbox[data-index=0]").offset().top)+proinfo){
         //     $("#specification li").removeClass('on');
         //     $("#specification li").eq(0).addClass('on');
         //     return false
         // }
         // for(var i = 0;i<$(".switchbox").length;i++){
         //     (function(thistop){
         //
         //        if($(window).scrollTop() > $(".switchbox[data-index="+i+"]").offset().top-60){
         //            $("#specification li").removeClass('on');
         //            $("#specification li").eq(i).addClass('on');
         //        }
         //     })($(".switchbox[data-index="+i+"]").offset().top);
         // }
     }
   };
    return suspensionlayer
})