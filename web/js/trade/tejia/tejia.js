requirejs.config({
    baseUrl:"http://c1.hoopchina.com.cn/js/",
    paths:{
        "index":"trade/tejia/modules/index",
        "royalSlider":"lib/slide/royalSlider.min",
        "Modernizr":"lib/videoPlugin/Modernizr.min",
        "jquery-easing":"lib/jquery.easing.min",
        "CountDown":"trade/tejia/modules/countdown",
        "paracurve":"trade/tejia/modules/paracurve",
        "cart":"trade/tejia/modules/cart",
        "tips":"trade/tejia/modules/tips"
    }
});

require(["index"],function(index){
    index.init();
});