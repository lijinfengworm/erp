/**
 * Created by PhpStorm.
 * User: jiangyanghe
 * DATA: 2017/03/28
 */
define(["EventUtil"],function(EventUtil){
   var navHandle = {
       init:function(){
           if($(".homepage").length == 0 ){
               $(".nav-leve2").mouseleave(function(event){
                   $(this).fadeOut();
                   $(".nav-level1").find("a").removeClass('on');
               });
               $(".nav-level1").mouseleave(function(event) {
                   if($(EventUtil.getRelatedTarget(event)).parents(".nav-leve2").length > 0){
                       return false
                   }
                   $(".nav-leve2").stop(true,true).fadeOut();
                   $(this).find("a").removeClass('on');
               });
           }
           this.topNav();
           this.sideNav();
       },
       topNav:function(){
           $(".nav-level1").mouseover(function(){
               $(".nav-leve2").stop(true,true).fadeIn();
               $(this).find("a").addClass('on');
           });
       },
       sideNav:function(){
           $(".nav-leve2 .category1").hover(function(e){
               e.preventDefault();
               var index = $(this).index();

               $(".nav-leve2 .category1").removeClass('block');
               $(this).addClass('block');
               $(".list-item:eq("+index+")").css('border-bottom','1px solid #e5e5e5');

               if($(".section:eq("+index+")").length != 0){
                   $(".nav-grid").addClass('block');
               }else{
                   $(".nav-grid").removeClass('block');
               }
               $(".section").removeClass('block');
               $(".section:eq("+index+")").addClass('block');
               $(".arrow-icon",".nav-grid").attr("id","select-"+index);
           },function(e){
               $(".list-item").css('border-bottom','none');
               if("undefined" != typeof $(e.relatedTarget).parent().attr("class") && $(e.relatedTarget).parent().attr("class").indexOf("section") >= 0){
                   return false
               }
               if($(e.relatedTarget).parents(".nav-leve2").length == 0){
                   $(".nav-grid").removeClass('block');
                   $(".section",".nav-grid").removeClass('block');
               }
           });
           $(".nav-leve2").mouseleave(function(){
               $(".nav-leve2 .category1").removeClass('block');
           });
           $(".nav-grid").hover(function(e){
               e.preventDefault();
           },function(e){
               if($(e.relatedTarget).parents(".nav-leve2").length > 0){
                   return false
               }
               $(".nav-grid").removeClass('block');
           });

           $('.list-item').hover(function (e) {
               var index = $(this).index();
               $(".section").removeClass('block');
               $(".nav-leve2 .category1").removeClass('block');
               console.log(index);
               $(".section:eq("+index+")").addClass('block');
               $(".arrow-icon",".nav-grid").attr("id","select-"+index);
               $('.category1:eq('+index+')').addClass('block');
               $(".list-item:eq("+index+")").css('border-bottom','1px solid #e5e5e5');
               
           },function (e) {
               $(".list-item").css('border-bottom','none');
               if("undefined" != typeof $(e.relatedTarget).parent().attr("class") && $(e.relatedTarget).parent().attr("class").indexOf("section") >= 0){
                   return false
               }
               if($(e.relatedTarget).parents(".nav-leve2").length == 0){
                   $(".nav-grid").removeClass('block');
                   $(".section").removeClass('block');
               }
           })
       }
   };

    return navHandle;
});