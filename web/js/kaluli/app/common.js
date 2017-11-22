requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
      lazy_load:"lib/lazy_load",
      EventUtil:"modules/common/EventUtil",
      getW:"modules/common/getW",
      returnTop:'modules/common/returnTop',
      navHandle:'modules/common/navHandle',
      coupon:'modules/common/coupon',
      tips:"modules/common/tips"
    },
    waitSeconds:200,
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});
require([
    "lazy_load",
    "returnTop",
    "navHandle",
    "coupon"
],function(lazy_load,returnTop,navHandle,coupon){
   $(function(){
       $(".lazyload").lazyload({
            effect : "fadeIn",
            placeholder:"/images/kaluli/noneImg.png"
       });

       if($(".recommond-list .refresh").length > 0 ){
          var num = 5;
          if($(".return-content").length > 0){
            num =4;
          }
          $.post('//www.kaluli.com/api/getHotItem',{num:num},function(data) {
                 $(".recommond-list .r-l-grid").html(data);
          });

          $(".recommond-list .refresh").click(function(){
             $.post('//www.kaluli.com/api/getHotItem',{num:num},function(data) {
                 $(".recommond-list .r-l-grid").html(data);
             });
          });
       }   

        $(".login").hover(function(){
          $(".login-menu").stop(true,true).fadeIn();

        },function(){
          $(".login-menu").stop(true,true).fadeOut();
        });

        navHandle.init();

        returnTop.init();

        coupon.init();
       
  });
});