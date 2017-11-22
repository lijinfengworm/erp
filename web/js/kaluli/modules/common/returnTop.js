/**
 * Created by PhpStorm.
 * User: songxiaoqiang
 * DATA: 2016/1/4
 */
define(["getW"],function(getW){
    var returnTop = {
        init:function(){
            if($(".scrollNav").length > 0){
                $(".right-nav").remove();
            }
            this.rightnav = $(".right-nav");
            this.returnTop = $("#returnTop");

            var that = this;
            that.rightnav.show();
            $(window).bind("scroll",function(){
                var w_t = getW().s,
                    w_w = getW().w,
                    w_h = getW().h,
                    top = Math.round((w_h-50)/2+180),
                    left = Math.round((w_w-1080)/2+1100);
                if(w_t>600){
                    that.returnTop.css("visibility","visible");
                }else{
                    that.returnTop.css("visibility","hidden"); //返回按钮
                }
            });

            this.returnTop.on("click",function(){
                $(window).scrollTop(0);
            })
        }
    };
    return returnTop;
});