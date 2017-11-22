$(function(){
     $choujiang = {}
    //提示框
    $choujiang.remind = function(msg) {
       if (!msg || typeof msg !== 'string') {
           return false;
       }
       var _html = "<div id='_vvalert_'><div class='box'><span>" + msg + " </span></div></div>";
       
       if($("#_vvalert_").length > 0){
           $("#_vvalert_").remove();
       }
       $(".choujiang").append(_html);
       var _alert = $("#_vvalert_");
       setTimeout(function() {
           _alert.addClass("show");
       }, 0);
       _alert.removeClass("show");
       setTimeout(function() {
           _alert.remove();
       }, 2000);
    }
});