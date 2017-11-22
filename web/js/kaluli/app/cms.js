/**
 * Created by jiangyanghe on 16/4/14.
 */
requirejs.config({
    baseUrl:"//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        picScroll:"lib/jquery.SuperSlide.2.1.1"
        //videojs:"modules/cms/detail_video.js"
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});

require(["picScroll"],function(){
    $(function(){
        /**
         * 经验心得首页banner 的循环
         */
        $('#slider').slide({
                mainCell:".bd ul",
                autoPlay:true
            }
        );

    });

});