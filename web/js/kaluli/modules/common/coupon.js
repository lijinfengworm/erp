/**
 * Created by PhpStorm.
 * User: songxiaoqiang
 * DATA: 2016/1/4
 */
define(['tips'],function(tips){
   var coupon = {
       init:function(){
            this.bindFun();
       },
       bindFun:function(){
           $(".block-btn").click(function(){
               $(this).hide();
               $(".fix-coupon-list").addClass("show");
               if($(window).height() < 700 && $(".block-content li").length == 5 ){
                   $(".fix-coupon-list,.right-nav").addClass("fixbottom");
               }
           });

           $(".collapse-btn").click(function(){
               $(".block-btn").show();
               $(".fix-coupon-list").removeClass("show fixbottom");
               $(".right-nav").removeClass("fixbottom");
           });

           $(".getcoupon").on('click',function(){
               if(!$(this).hasClass("getcoupon")){
                   return false
               }
               var id = $(this).attr("data-couponid"),
                   $this = $(this);
               $this.addClass('loading');
               if($(".login").length>0){
                   $.getJSON('//www.kaluli.com/api/receiveCoupon?id='+id+'',function(data){
                       $this.removeClass('loading');
                       if(data.status ==0){
                           $this.removeClass("getcoupon").addClass('active').text("已领取");
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
       }
   };
   return coupon
});