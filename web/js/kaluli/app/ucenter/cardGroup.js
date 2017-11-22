requirejs.config({
    baseUrl:"//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        "alertbox":"modules/common/alertbox"
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});
require(["alertbox"],function(alertbox){
   $(".cancelBtn").click(function(){
       $("input[name='couponid'],input[name='identify']").val('');
   });
   $(".refresh").click(function(){
       $(".verify_img").attr("src",$(".verify_img").attr("src")+ "?"+new Date().getTime());
       $(this).addClass("rotate");
       setTimeout(function(){$(".refresh").removeClass("rotate")},400);
       return false
   });

   $(".getBtn").click(function(){
       var data = {
           code:$("input[name='identify']").val(),
           card:$("input[name='couponid']").val()
       };

       if((data.code || data.card) == ""){
           return false
       }
       $.post("//www.kaluli.com/ucenter/bindCardGroup",data,function(res){
           var da = "string" == typeof res ? $.parseJSON(res) : res;
           if (da.status == 1 ){
               alertbox().show({
                   title:"领取成功"
               });
           }else{
               alertbox().show({
                   title:da.info
               });
           }
       });
   });
});