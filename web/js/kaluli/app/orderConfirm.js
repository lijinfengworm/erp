requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
       "chooseAddress":"modules/order/chooseAddress",
       "orderConfirm":"modules/order/orderConfirm",
       "tips":"modules/common/tips",       
       "changenum":"modules/product/changenum",
       "getPrice":"modules/order/getPrice",
       "submit" :"modules/order/submit"
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});
require(["tips","chooseAddress","orderConfirm","changenum","getPrice","submit"],function(tips,chooseAddress,orderConfirm,changenum,getPrice,submit){        
    
    var a = new chooseAddress();
    a.init(); 

    var b = new changenum();
    b.callback=function(obj){
        var num = parseInt($(obj).parent().find("input").val()),
            max_length = parseInt($(obj).parent().find("input").attr("maxstock"));
        if(num <= max_length){ 
            $(obj).parent().siblings(".maxnumtips").remove();           
        }
        getPrice.getJson();
    };
    b.overstock = function(obj){      
        var max_length = parseInt($(obj).parent().find("input").attr("maxstock"));
        if($(obj).parent().siblings(".maxnumtips").length > 0){
          return false
        }
        var str = '<div class="maxnumtips" style="width:140px;margin:10px auto 0;border-radius:5px; background-color:#ff6600; display:block; z-index:995">\
                        <div class="tips-text" style="padding:2px 5px; line-height:18px; color:#fff;">最多只能购买 '+max_length+' 件</div>\
                  </div>';
        $(obj).parents("td").append(str);
    };
    b.init();
    submit.init();
})