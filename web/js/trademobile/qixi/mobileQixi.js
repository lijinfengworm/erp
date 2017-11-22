requirejs.config({
    baseUrl:"/js/",
    paths:{
        "underscore":"lib/underscore",
        "fx":"trademobile/lib/zepto.fx",
        "alertbox": "trade/activity/qixi/modules/alertbox",
        "qixiMain":"trademobile/qixi/qixiMain"
    }
});

requirejs(['qixiMain'],function(qixiMain){  
    $(function(){
        qixiMain.init();
    });      
})    