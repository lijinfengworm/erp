/**
 * Created by PhpStorm.
 * User: songxiaoqiang
 * DATA: 2015/12/15
 */

requirejs.config({
    baseUrl:"//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        "couponUtil":"modules/ucenter/couponUtil",
        "tips" : "modules/common/tips",
        "alertbox":"modules/common/alertbox"
    },
    urlArgs: 'v=20151215'
});

require(['tips','couponUtil','alertbox'],function(tips,couponUtil,alertbox){

    var util = new couponUtil({
       getId:"get_btn",
       cancelClass:"cancel-btn"
    });
    util.init();
    $(".pos-right").click(function(){
        util.showBox();
    });
});