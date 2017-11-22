requirejs.config({
    baseUrl: "/js/kaluli/"
});
require([""],function(){
    $("input[name=filter_activity]").click(function(){
        if($(this).prop("checked")){
            window.location.href= $(this).parent().attr("data-actUrl");
        }else{
            window.location.href = $(this).parent().attr("data-originUrl");
        }
    });
})