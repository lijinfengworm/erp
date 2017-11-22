/**
 * Created by jiangyanghe on 17/5/3.
 */
requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{lazy_load:"lib/lazy_load"},
    waitSeconds:200,
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});
require(["lazy_load"],function(lazy_load){
    $(function(){
        $(".lazyload").lazyload({
            effect : "fadeIn",
            placeholder:"/images/kaluli/noneImg.png"
        });
    });
});