/**
 * Created by jiangyanghe on 16/12/12.
 */
requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        scrollNav:"modules/activity_template/activity_template",
        picScroll:"lib/picScroll"
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});

require([
        "scrollNav",
        "picScroll"],
    function(scrollNav,picScroll){
        var a = new scrollNav();
        a.init();

        $('.goods-list li:nth-child(4n)').css('margin-right','0');
        $('.hot-activety li:last-child').css('margin-right','0');
    }
);