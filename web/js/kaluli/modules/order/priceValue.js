define(function(){
    return {
         productId:$("#item_id").val(),
         goodsId:$("#sku_id").val(),
         address_id:$(".address-list .defaults").attr("data-value"),                  
         number:$("#numbox-text").text(),
         remark:"",
         express_type:$("#sh .icon-check-nosprite").attr("data-type"),
         express_types:""
    }
})