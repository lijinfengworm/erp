define(function(){
    function backtoTop(){

    }
    backtoTop.prototype = {
        init:function(){
            $(window).scrollTop(0);
        }
    }
    return backtoTop
})