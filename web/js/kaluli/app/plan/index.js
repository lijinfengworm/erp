/**
 * Created by jiangyanghe on 16/8/15.
 */

requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        dialog:"modules/common/dialog",
        tips:"modules/common/tips"
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});

require(["submit", "tips", "validateUtil","clock"],
    function(dialog,tips){

    });