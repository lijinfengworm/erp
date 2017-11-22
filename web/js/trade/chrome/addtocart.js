function shihuo_go()
{ 
    var obj = JSON.parse(jQuery("script[data-a-state='{\"key\":\"twisterData\"}']").html());
    if (obj) {
        console.log(obj)
        var QueryString = function () {
            // This function is anonymous, is executed immediately and
            // the return value is assigned to QueryString!
            var query_string = {};
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i=0;i<vars.length;i++) {
                var pair = vars[i].split("=");
                // If first entry with this name
                if (typeof query_string[pair[0]] === "undefined") {
                    query_string[pair[0]] = pair[1];
                    // If second entry with this name
                } else if (typeof query_string[pair[0]] === "string") {
                    var arr = [ query_string[pair[0]], pair[1] ];
                    query_string[pair[0]] = arr;
                    // If third or later entry with this name
                } else {
                    query_string[pair[0]].push(pair[1]);
                }
            }
            return query_string;
        } ();
        if(!QueryString.sh_asin)
        {
            return;
        }
        if(QueryString.sh_asin != obj.data.stateData.current_asin)
        {
            alert('订单 '+QueryString.sh_order_id+ '  这个商品无货了');
            return;
        }
        var finalPrice = parseFloat(jQuery.trim(jQuery('#buybox_feature_div span[class*="a-color-price"]').text()).replace("$", ""));
        if (!finalPrice) {
            var finalPrice = parseFloat(jQuery.trim(jQuery("#priceblock_ourprice").text()).replace("$", ""));
        }
        if(QueryString.sh_price/100 != finalPrice)
        {
            alert('订单 '+QueryString.sh_order_id+ '  下单价'+QueryString.sh_price/100+' 当前价'+finalPrice);
            return;
        }
        $("#buybox_feature_div").before('处理成功 ' +decodeURIComponent(QueryString.sh_attr) +'  数量'+QueryString.sh_qty);
//        var url = 'http://www.amazon.com/gp/twister/ajaxv2?ptd=' + obj.productTypeName + '&json=1&dpxAjaxFlag=1&sCac=1&isUDPFlag=1&ee=2&pgid=' + obj.data.stateData.productGroupID + '&parentAsin=' + obj.data.stateData.parent_asin + '&enPre=1&auiAjax=1&psc=1&asinList=' + obj.data.stateData.current_asin + '&isFlushing=2&id=' + obj.data.stateData.current_asin + '&prefetchParam=0&mType=full';
//        jQuery.ajax({
//            type: "GET",
//            url: url,
//            dataType: "text",
//            success: function (data) {
//                console.log('get add-to-cart form');
//                var buyboxdiv = '';
//                var arr = data.split("&&&");
//                var buyboxobj = JSON.parse(arr[19]);
//                var buyboxdiv = buyboxobj.Value.content.buybox_feature_div;
//
//                if (!buyboxdiv) {
//                    var buyboxobj = JSON.parse(arr[13]);
//                    var buyboxdiv = buyboxobj.Value.content.buybox_feature_div;
//                }
//
//                if (!buyboxdiv) {
//                    var count = Object.keys(arr).length;
//                    var i = 0;
//                    while (!buyboxdiv && i < count) {
//                        var buyboxobj = JSON.parse(arr[i]);
//                        var buyboxdiv = buyboxobj.Value.content.buybox_feature_div;
//                        //console.log(buyboxdiv);
//                        i++;
//                    }
//                }
//                jQuery('#buybox_feature_div').html(buyboxdiv);
//                $("#buybox_feature_div").before('处理成功');
//            }
//        });
    }
}
$(document).ready(function(){
    shihuo_go();
})
