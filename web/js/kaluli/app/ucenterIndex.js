requirejs.config({
    baseUrl: "/js/kaluli/",
    paths:{
        "tips":"modules/common/tips",
        "address": "modules/ucenter/address",
        "chooseAddress": "modules/ucenter/chooseAddress"        
    }
});

require(["tips","chooseAddress"],function(tips,chooseAddress){

    if($(".prolist").length > 0){
      $.post('//www.kaluli.com/api/getHotItem',function(data) {
           $(".prolist").html(data);
       });
    }
    $(".refresh").click(function(){
       $.post('//www.kaluli.com/api/getHotItem',function(data) {
           $(".prolist").html(data);
       });
    });
    var a = new chooseAddress();
    a.init();

});