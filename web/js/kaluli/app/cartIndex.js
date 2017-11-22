requirejs.config({
    baseUrl:"//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        "changenum":"modules/product/changenum",
        "cartUtil" : "modules/order/cartUtil",
        "tips" : "modules/common/tips"        
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});
require(["changenum","cartUtil"],function(changenum,cartUtil){
    var b = new changenum();//product 初始化数量加减
    b.callback=function(obj){
        var gid = $(obj).parent().attr("gid"),//goods_id
            objclass = $(obj).attr("class"),
            num = parseInt($(obj).parent().find("input").val()),
            max_length = parseInt($(obj).parent().find("input").attr("maxstock")),
            type,
            name = $(obj).attr("attr");
        if(num <= max_length){
            $(obj).parent().siblings(".maxnumtips").remove();           
        }
        if(objclass == "icon-subtract-nosprite"){
            type = 0; 
        }else if(objclass == "icon-add--nosprite"){
            type = 1;
        }
        cartUtil.addGoods({sku_id:gid,type:type,isNanSha:name},$(obj).parents(".td4").next());
    }
    b.overstock = function(obj){
        if($(obj).parent().siblings(".maxnumtips").length > 0){
          return false
        }
        var max_length = parseInt($(obj).parent().find("input").attr("maxstock"));
        var str = '<div class="maxnumtips" style="width:140px;margin:10px auto 0;border-radius:5px; background-color:#ff6600; display:block; z-index:995">\
                        <div class="tips-text" style="padding:2px 5px; line-height:18px; color:#fff;">最多只能购买 '+max_length+' 件</div>\
                  </div>';
        $(obj).parents(".td4").append(str);
    }
    b.init();
    cartUtil.init();
});   