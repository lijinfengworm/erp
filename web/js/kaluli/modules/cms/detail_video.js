/**
* Created by jiangyanghe on 16/4/26.
*/


requirejs.config({
    baseUrl:"//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        sewisejs:"lib/sewise"
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});

require(["sewisejs"],function(){
    $(function(){
        SewisePlayer.setup({
            server: "vod",
            type: "mp4",
            videourl: "//vjs.zencdn.net/v/oceans.mp4",
            //skin: "vodWhite",
            title: "卡路里生活",
            buffer:10,
            lang: 'zh_CN',
            fallbackurls:{
                ogg: "//jackzhang1204.github.io/materials/mov_bbb.ogg",
                webm: "//jackzhang1204.github.io/materials/mov_bbb.webm"
            }
        }, "player");

    });

});


