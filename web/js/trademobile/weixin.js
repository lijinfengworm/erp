var preData;
var url = '';
var orderNum = '';
var inPay = false;
function jsApiCall() {
    if (preData) {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            preData,
            function (res) {
                if (res.err_msg == "get_brand_wcpay_request:ok") {
                    location.href = 'http://m.shihuo.cn/haitao/orderPayResult/' + orderNum;
                }
            }
        );
    }
}

function bind() {
    $('.weChatPay').on("click", ".callPay", function () {
        var orderType = $(this).attr('data-orderType');
        if (orderType == 'order') {
            url = 'http://m.shihuo.cn/daigou/weixinOrderPay';
        } else if (orderType == 'tax') {
            url = 'http://m.shihuo.cn/daigou/weixinOrderPayTax';
        }
        orderNum = $(this).attr('data-orderNum');
        var data = {openId: openId, code: code, order_number: orderNum};

        if(!inPay) {
            inPay = true;
            if(preData && preData.appId) {
                jsApiCall();
                inPay = false;
            } else {
                $.post(url, data, function (data) {
                    if (data && data.appId) {
                        preData = data;
                        if (typeof WeixinJSBridge == "undefined") {
                            if (document.addEventListener) {
                                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                            } else if (document.attachEvent) {
                                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                            }
                        } else {
                            jsApiCall();
                        }
                    } else if (data && data.status == '1') {
                        $.ui.tips("服务器异常,请联系管理员");
                    } else {
                        $.ui.tips("服务器异常,请联系管理员");

                    }
                    inPay = false;
                }, 'json');
            }
        } else {
            $.ui.tips("支付请求中，请稍后");
        }
    });
}


$(function () {
    bind();
});


