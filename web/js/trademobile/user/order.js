/* 
 * @Author: annyshen
 * @Date:   2015-04-20 14:25:48
 */
var _pageNum = 2;
var _pageTotal = 10;
var _ajaxG = true;
var _pageSize = 5;
var _tab = "";
var ajaxLink = "http://m.shihuo.cn/user/myOrderAjax";
var weChat = false;
$(function () {
    _tab = $("#user-coupon-tag").attr("data-tabNow");
    _pageTotal = $("#user-coupon-tag").attr("data-totals");
    var loadMore = {
        init: function () {
            $(window).scroll(function () {
                if ($(window).scrollTop() >= $(document).height() - $(window).height() && _ajaxG && _pageNum <= _pageTotal) {
                    $("#loadding").show();
                    _ajaxG = false;
                    var _dataStr = {"page": _pageNum, "pagesize": _pageSize, "type": _tab};
                    var _html = "";
                    $.post(ajaxLink, _dataStr, function (data) {
                        if (data.status == 0) {
                            $("#loadding").hide();
                            _pageNum += 1;
                            _ajaxG = true;
                            _html = loadMore.getHtml(data.data);
                            $("#ajaxScroll").append(_html);
                        }

                    }, 'json');
                }
            });
        },
        getHtml: function (data) {
            var _html = "";
            $.each(data, function (index, val) {
                var main_order = val.main_order;
                var order = val.order;
                var li = "";
                var attr,
                    ord_btn,
                    order_btn = '',
                    order_link;
                $.each(order, function (i, v) {
                    if (main_order.order_status == 0) {
                        order_link = '<a href="javascript:void(0);" class="link-block" >';
                    } else {
                        order_link = '<a href="/daigou/orderDetail?id=' + v.id + '&order_number=' + main_order.order_number + '&type=' + _tab + '" data="' + main_order.order_number + '" class="link-block link-order-detail">';
                    }
                    attr = '';
                    ord_btn = '<div class="order-btn" style="display: none;"></div>';
                    if (v.status == '待收货' && v.mart_express_time > 0) {
                        ord_btn = '<div class="order-btn">';
                        if (v.status == '待收货' && main_order.tax_status != 1)
                            ord_btn += '<a class="confirm_b red" href="javascript:void(0);" hF="/daigou/confirmReceiveGoods?order_number=' + main_order.order_number + '&id=' + v.id + '&type=1">确认收货</a>';
                        if (v.mart_express_number)
                            ord_btn += '<a class="link-order-express" data-orderNum="' + main_order.order_number + '" data-martExpressNumber="' + v.mart_express_number + '" href="/daigou/orderLogistics?number=' + v.mart_express_number + '&order_number=' + main_order.order_number + '">查看物流</a>';
                        ord_btn += '</div>';
                    }
                    if (v.status == '交易完成' && !v.is_comment) {
                        ord_btn = '<div class="order-btn">';
                        ord_btn += '<a class="link-order-express" data-orderNum="' + main_order.order_number + '" data-martExpressNumber="' + v.mart_express_number + '" href="/daigou/orderLogistics?number=' + v.mart_express_number + '&order_number=' + main_order.order_number + '">查看物流</a>';
                        ord_btn += '<a href="/daigou/orderComment/?order_number=' + main_order.order_number + '&pid=' + v.product_id + '&gid=' + v.gid + '">评价订单</a>';
                        ord_btn += '</div>';
                    }
                    if (v.status == '交易完成' && v.is_comment) {
                        ord_btn = '<div class="order-btn">';
                        ord_btn += '<a class="link-order-express" data-orderNum="' + main_order.order_number + '" data-martExpressNumber="' + v.mart_express_number + '" href="/daigou/orderLogistics?number=' + v.mart_express_number + '&order_number=' + main_order.order_number + '">查看物流</a>';
                        ord_btn += '<a href="/daigou/commentList/?pid=' + v.product_id + '">查看评价</a>';
                        ord_btn += '</div>';
                    }

                    $.each(v.attr, function (a_i, i_v) {
                        attr += '<span>' + a_i + ': ' + i_v + '</span>';
                    });

                    li += '<li>\
                        <div class="business">\
                            <div class="bus">商家：' + v.business + '</div>\
                            <div class="status">' + v.status + '</div>\
                        </div>\
                            ' + order_link + '\
                            <div class="imgs">\
                                <img src="' + v.img + '" alt="">\
                            </div>\
                            <div class="details_box">\
                            <h2>' + v.title + '</h2>\
                            ' + attr + '</div>\
                            <div class="price">\
                                <p><i>￥</i>' + v.price + '</p>\
                                <p><span>x1</span></p>\
                            </div>\
                        </a>\
                        ' + ord_btn + '\
                    </li>';
                });
                if (main_order.status == 0) {
                    var payBtn;
                    if (weChat) {
                        payBtn = '<a class="red callPay" data-orderType="order" data-orderNum="' + main_order.order_number + '">去付款</a>';
                    } else {
                        payBtn = '<a class="red" href="/daigou/orderPay?order_number=' + main_order.order_number + '">去付款</a>'
                    }
                    order_btn =
                        '<div class="order-btn all">\
                            <a class="cancel" href="javascript:void(0);" hF="/daigou/cancelOrderResult?order_number=' + main_order.order_number + '&type=1" >取消订单</a>'
                        + payBtn + '\
                    </div>';
                }

                if (main_order.status == 2) {
                    if(main_order.tax_status == 1) {
                        var payBtn;
                        if (weChat) {
                            payBtn = '<a class="red callPay" data-orderType="tax" data-orderNum="' + main_order.order_number + '">付关税</a>';
                        } else {
                            payBtn = '<a class="red" href="/daigou/orderPayTax?order_number=' + main_order.order_number + '">付关税</a>'
                        }
                        order_btn = '<div class="order-btn all">' + payBtn + '</div>';
                    }
                }

                _html += '<div class="orderMain">\
                <div class="order-number">\
                    <div class="num fl">订单号：' + main_order.order_number + '</div>\
                    <div class="date fr">' + main_order.order_time + '</div>\
                </div>\
                <ul class="product-list">\
                    ' + li + '\
                </ul>\
                <div class="total">\
                    共 <span>' + main_order.number + '</span> 件商品 运费 <s>￥' + main_order.total_express_fee + '</s>  合计: <s>￥' + main_order.total_price + '</s>\
                </div>\
                ' + order_btn + '\
            </div>';
            });
            return _html;
        }
    };
    loadMore.init();
});
