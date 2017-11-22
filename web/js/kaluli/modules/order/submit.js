define(["modules/order/priceValue", "modules/common/dialog", "modules/common/validate"], function (priceValue, dialog, validate) {
    var submit = {
        ajaxLoding: false,
        xBuyAjax: 1,
        init: function () {
            this.remark();
            this.bindFun();
            this.colsePop();
            this.authenticationFun();
        },
        colsePop: function () {
            $('.close-pop-btn').click(function () {
                $('.error-pop').hide();
            });
            $('.J-cancle-pop').click(function () {//宁波仓实名认证
                $('.J-background-bg').hide();
                $('#error_msg').hide();
            });
        },
        remark: function () {
            $("#textatea-shuoming").focus(function () {
                if ($(this).val() == $(this).attr("data")) {
                    $(this).val("");
                }
            });
            $("#textatea-shuoming").blur(function () {
                if ($(this).val() == $(this).attr("data") || $.trim($(this).val()) == "") {
                    $(this).val($(this).attr("data"));
                    priceValue.remark = "";
                } else {
                    priceValue.remark = $(this).val();
                }
            });
            $('.J-pop-title').hover(function () {
                $('.J-explain-pop').show();
            }, function () {
                $('.J-explain-pop').hide();
            });
        },
        authenticationFun: function () {
            var that = this,
                uid = $('input[name="uid"]').val();

            (function () {//获取实名认证
                $.get('//www.kaluli.com/api/purchaserAuth', {
                    _act: 'get',
                    _uid: uid
                }, function (data) {
                    var datas = typeof data == "string" ? $.parseJSON(data) : data;
                    if (datas.status == 1) {
                        if (datas.data.length == 0) {
                            $('#ware_name').attr('data-authentication', 0);//0 是未实名认证 1已实名认证
                        } else {
                            $('#ware_name').attr('data-authentication', 1);
                        }
                    } else {
                        alert('查询实名认证失败');
                    }
                });

                // $('.J-address-list .defaultsAddress').each(function () {
                //     if($(this).hasClass('')){}
                // });
                console.log($('.J-address-list').find('.defaultsAddress').parent().find('.J-address-name').text());
            })();
            //实名认证
            $('#authentication_btn').click(function () {
                var id_card = $('input[name="id_card"]').val(),
                    purchaser = $('input[name="purchaser_name"]').val();
                if (!validate.isChineseName(purchaser)) {
                    that.ajaxLoding = false;
                    $('#error_msg').text('请填写正确的姓名').show();
                    return
                }
                if (!validate.isIdCard(id_card)) {
                    that.ajaxLoding = false;
                    $('#error_msg').text('请填写正确的身份证').show();
                    return
                }
                that.ajaxLoding = true;
                if (that.ajaxLoding) {
                    $.post('//www.kaluli.com/api/purchaserAuth', {
                        _act: 'add',
                        _uid: uid,
                        _purchaser: purchaser,
                        _card_number: id_card
                    }, function (data) {
                        var datas = typeof data == "string" ? $.parseJSON(data) : data;
                        if (datas.status == 1) {
                            $('.J-background-bg').hide();
                            $('#ware_name').attr('data-authentication', 1);
                            $('.J-content-text').text('实名认证成功');
                            $(".successPop").show();
                            setTimeout(function () {
                                $(".successPop").hide();
                            },1500);
                            that.qualificateFun();
                        } else {
                            $('#error_msg').text('添加实名认证失败').show();
                        }
                    });
                }
            });
        },
        bindFun: function () {
            var that = this;
            $(".J-submit-btn").click(function () {
                if (that.ajaxLoding) {
                    return false;
                }
                var $this = $(this);
                priceValue.number = $("#numbox-text").text();
                priceValue.address_id = $(".icon-check-nosprite").parent().attr("data-value");
                if ("undefined" == typeof priceValue.address_id) {//设置收货地址
                    var st = $(".address-list").offset().top;
                    $(window).scrollTop(st - 50);
                    $(".address-list").tips("请设置一个地址", {
                        left: $(".newAddress").offset().left + 400,
                        top: $(".newAddress").offset().top + 26
                    });
                } else if ($(".defaultsAddress").length == 0) {
                    var id = priceValue.address_id;
                    $.post("//www.kaluli.com/api/setDefaultAddress", {id: id})
                }
                that.ajaxLoding = true;
                that.qualificateFun();
                // $this.addClass('submit-btnCC');
                __dace.sendEvent('kaluli_order_confirm_submit');
            });
        },
        qualificateFun: function () {
            var that = this;
            var shipAddress = $('.confirm').find('h4').text().trim();
            if(shipAddress == '宁波保税仓' && $('#ware_name').attr('data-authentication') == 0){
                $('.J-background-bg').show();
                $('input[name="purchaser_name"]').val($('.J-address-list').find('.chosen').parent().find('.J-address-name').text());
                $('input[name="id_card"]').val($('.J-address-list').find('.chosen').parent().find('.J-address-carid').attr('data-carid-num'));
                that.ajaxLoding = false;
                return
            }

            /**
             * Xbuy确认购买资格
             */
            if(that.ajaxLoding){
                if ($('input[name=isActivityPrice]').val() == 1) {
                    $.post('//www.kaluli.com/api/checkXbuyNotPay', {
                        id: priceValue.productId,
                        skuid: priceValue.goodsId
                    }, function (data) {
                        if (data.status == 1) {
                            if ($("#cart_data").length > 0) {
                                var list = [];
                                $('.shipping-btn > .icon-check-nosprite').each(function () {
                                    var expressType = {};
                                    expressType["houseId"] = $(this).attr('houseId');
                                    expressType["dataType"] = $(this).attr('data-type');
                                    list.push(expressType);
                                    // myMap.set($(this).attr('houseId'),$(this).attr('data-type'));
                                });
                                var jsonString = JSON.stringify(list);
                                priceValue.express_types = jsonString;
                                that.cartSubmit();
                            } else {
                                that.orderSubmit();
                            }
                        } else if (data.status == 2) {
                            that.xBuyAjax = 0;
                            $('.error-pop').show();
                            $('.textMsg').html('您有未付款的活动订单，<br><a style="color: #3b5cbd" href="//www.kaluli.com/ucenter/order">快去付款吧</a>');
                        } else if (data.status == 3) {
                            that.xBuyAjax = 0;
                            $('.error-pop').show();
                            $('.textMsg').html('活动商品已结束，下次赶早哦~<br>您也可以逛逛其它的，<a href="//www.kaluli.com">商城首页 >></a>');
                        } else if (data.status == 4) {
                            that.xBuyAjax = 0;
                            $('.error-pop').show();
                            $('.textMsg').html('活动商品已售罄，下次赶早哦~<br>您也可以逛逛其它的，<a style="color: #3b5cbd" href="//www.kaluli.com">商城首页 >></a>');
                        } else {
                            that.xBuyAjax = 0;
                            $('.error-pop').show();
                            $('.textMsg').html(data.msg);
                        }
                    }, 'json');
                } else {
                    if ($("#cart_data").length > 0) {
                        var list = [];
                        $('.shipping-btn > .icon-check-nosprite').each(function () {
                            var expressType = {};
                            expressType["houseId"] = $(this).attr('houseId');
                            expressType["dataType"] = $(this).attr('data-type');
                            list.push(expressType);
                            // myMap.set($(this).attr('houseId'),$(this).attr('data-type'));
                        });
                        var jsonString = JSON.stringify(list);
                        priceValue.express_types = jsonString;
                        that.cartSubmit();
                    } else {
                        that.orderSubmit();
                    }
                }
            }
        },
        orderSubmit: function () {
            //TODO 判断南沙仓是否超过2000元
            var shipAddress = $('.confirm').find('h4').text().trim(),
                tPrice = $('#total-price').text(),
                that = this;
            if (shipAddress == '南沙保税仓' && tPrice >= 2000 || shipAddress == '宁波保税仓' && tPrice >= 2000) {
                $('#over_price_tip').show();
                that.ajaxLoding = false;
                return
            }
            that.ajaxLoding = true;
            if (that.ajaxLoding) {
                var datas = {
                        product_id: priceValue.productId,
                        goods_id: priceValue.goodsId,
                        address_id: priceValue.address_id,
                        number: priceValue.number,
                        remark: priceValue.remark,
                        card_id: $(".icon-check-nosprite", "#coupon-table").parents("tr").attr("data-couponid") || null,
                        card_type: $(".icon-check-nosprite", "#coupon-table").parents("tr").attr("data-coupontype") || 1,
                        express_type: $('.shipping-btn > .icon-check-nosprite').attr('data-type')
                    },
                    that = this;
                $.post("//www.kaluli.com/api/submitOrder", {data: datas}, function (data) {
                    var data_callback = typeof data == "string" ? $.parseJSON(data) : data;
                    if (data_callback.status == 1) {
                        window.location.href = data_callback.data.pay_url;
                    } else {
                        $(".submit-btn").removeClass('submit-btnCC');
                        $('#over_price_tip').show().text(data_callback.info);

                        that.ajaxLoding = false;
                    }
                }, "json");
            }
        },
        cartSubmit: function () {
            //TODO 判断南沙仓是否超过2000元
            var shipAddress = $('.confirm').find('h4').text().trim(),
                tPrice = $('#total-price').text();
            if (shipAddress == '南沙保税仓' && tPrice >= 2000 || shipAddress == '宁波保税仓' && tPrice >= 2000) {
                $('#over_price_tip').show();
            } else {
                var datas = {
                        cart_data: $("#cart_data").attr("value"),
                        remark: priceValue.remark,
                        address_id: priceValue.address_id,
                        card_id: $(".icon-check-nosprite", "#coupon-table").parents("tr").attr("data-couponid") || null,
                        card_type: $(".icon-check-nosprite", "#coupon-table").parents("tr").attr("data-coupontype") || 1,
                        express_type: priceValue.express_type,
                        express_types: priceValue.express_types
                    },
                    that = this;

                $.post("//www.kaluli.com/order/cartSubmit", datas, function (res) {
                    var data = typeof res == "string" ? $.parseJSON(res) : res;
                    var data_callback = typeof data == 'string' ? $.parseJSON(data) : data;
                    if (data_callback.status == 1) {
                        window.location.href = data_callback.data.pay_url;
                    } else {
                        $(".submit-btn").removeClass('submit-btnCC');
                        $('#over_price_tip').show().text(data_callback.info);
                        that.ajaxLoding = false;
                    }
                }, "json");
            }
        },
        ajaxAble: function () {
            var that = this;

        }
    }
    return submit
})