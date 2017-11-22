define(['backbone', 'fastclick', 'md5', 'fx', 'alertbox', 'tip', 'clamp'], function(Backbone, FastClick, md5, fx, alertbox, Tip, clamp) {
var Util = {
        loginUrl:function loginUrl(){
            var urlTo="";
            var isAndroid = (/android/gi).test(navigator.appVersion);
            var isIOS     = (/iphone|ipad/gi).test(navigator.appVersion);
            var kanqiu_version = this.getCookie("kanqiu_version");
            if(isAndroid){
                if(kanqiu_version >= "7.0.0"){
                    urlTo = "kanqiu://account/account";
                }else{
                    urlTo = "http://passport.shihuo.cn/m/2?from=m&project=shihuo&appid=10017&jumpurl="+ encodeURI(window.location.href);
                }
            }else if(isIOS){
                if(kanqiu_version >= "7.0.0"){
                    urlTo = "prokanqiu://account/login";
                }else{
                    urlTo = "http://passport.shihuo.cn/m/2?from=m&project=shihuo&appid=10017&jumpurl="+ encodeURI(window.location.href);
                }
            }
            return urlTo;
        },
        getCookie:function getCookie(NameOfCookie) {
            if (document.cookie.length > 0) {
                var begin = document.cookie.indexOf(NameOfCookie + "=");
                if (begin != -1) {
                    begin += NameOfCookie.length + 1;//cookie值的初始位置
                    var end = document.cookie.indexOf(";", begin);//结束位置
                    if (end == -1) end = document.cookie.length;//没有;则end为字符串结束位置
                    return unescape(document.cookie.substring(begin, end));
                }
            }
            return null;
        }

    };
    var AddressModel = Backbone.Model.extend({
        defaults: {
            id: "",
            hupu_uid: "",
            hupu_username: "",
            name: "",
            postcode: "",
            province: "",
            city: "",
            area: "",
            mobile: "",
            phonesection: "",
            phonecode: "",
            phoneext: "",
            region: "",
            street: "",
            identity_number: "",
            defaultflag: "",
            created_at: "",
            updated_at: "",
            jumpurl: encodeURIComponent(window.location.href)
        }
    });

    var AddressCollection = Backbone.Collection.extend({
        model: AddressModel,
        parse: function(response) {
            if (response.data.address) {
                return response.data.address;
            } else {
                return [];
            }
        }
    });

    var AddressView = Backbone.View.extend({

        el: "#js-address-view",

        initialize: function() {
            var _this = this;
            this.collection.fetch({
                success: function(collection, response) {
                    _this.render();
                }
            })
        },

        render: function() {
            this.$el.html(new AddressCollectionView({collection: this.collection}).$el);
            return this;
        }
    });

    var AddressCollectionView = Backbone.View.extend({
        tagName: 'ul',

        className: 'address-list',

        initialize: function() {
            this.render();
        },

        render: function() {
            if (this.collection.size() > 0) {
                for (var i = 0, size = this.collection.size(); i < size; i++) {
                    this.$el.append(new AddressItemView({model: this.collection.at(i)}).$el);
                }
            } else {
                this.$el.html(_.template($('#tpl-no-address').html())({jumpurl: encodeURIComponent(window.location.href)}));
            }

            return this;
        }
    });

    var AddressItemView = Backbone.View.extend({

        tagName: 'li',

        className: 'address-item',

        template: _.template($('#tpl-address-item').html()),

        initialize: function() {
            this.render();
        },

        events: {
            'click': 'goAddressList',
            'click #js-btn-identity': 'inputIdentity',
            'click #js-btn-indentity-submit': 'finishIdentity'
        },

        goAddressList: function(e) {
            var nodeName = e.target.nodeName.toLowerCase();
            if(nodeName != "a" && nodeName != "input") {
                window.location.href = 'http://m.shihuo.cn/haitao/addressList?id=' + this.model.get('id') + '#' + this.model.get('jumpurl');
            }
        },

        inputIdentity: function(e) {
            $(e.target).hide();
            $('#js-input-identity').show().focus();
            $('#js-btn-indentity-submit').show();
            return false;
        },

        finishIdentity: function(e) {

            var input = $('#js-input-identity');

            var reg = /^((1[1-5])|(2[1-3])|(3[1-7])|(4[1-6])|(5[0-4])|(6[1-5])|71|(8[12])|91)\d{4}((19\d{2}(0[13-9]|1[012])(0[1-9]|[12]\d|30))|(19\d{2}(0[13578]|1[02])31)|(19\d{2}02(0[1-9]|1\d|2[0-8]))|(19([13579][26]|[2468][048]|0[48])0229))\d{3}(\d|X|x)?$/;

            //校验
            if ($.trim(input.val()).length == 0) {
                new Tip({msg: "身份证号码不能为空"}).show();
            } else if (!reg.test(input.val())){
                new Tip({msg: "身份证号码格式不正确"}).show();
            } else {

                $.ajax({
                    url: 'http://m.shihuo.cn/haitao/saveIdentityNumber',
                    type: 'post',
                    dataType: 'json',
                    data: {identity_number: input.val(), address_id: this.model.get('id')},
                    success: function(response) {
                        input.attr("readonly", true);
                        $('#js-btn-indentity-submit').hide();
                    }
                })

            }

            return false;
        },

        render: function() {
            window.address_id = this.model.get('id');
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });

    var CartModel = Backbone.Model.extend({
        url: 'http://m.shihuo.cn/haitao/cartList'
    });

    var CartCollectionView = Backbone.View.extend({

        el: '#js-cart',

        initialize: function() {

            if (location.hash.slice(1).indexOf('&') != -1) {
                var gidsStr = location.hash.slice(1).split('&')[0];

                var gidsData = JSON.parse(decodeURIComponent(gidsStr));

            } else {
                if (location.hash.slice(1).indexOf('#') != -1) {
                    var gidsData = JSON.parse(decodeURIComponent(location.hash.slice(1, location.hash.lastIndexOf('#'))));
                } else {
                    var gidsData = JSON.parse(decodeURIComponent(location.hash.slice(1)));
                }

            }

            var _this  = this;

            this.model.fetch({
                data: gidsData,
                processData: true,
                success: function(model, response) {
                    if (response.status == 0) {
                        if (Object.keys(response.data.result).length) {
                             _this.render(response);

                            $('.sku-attr-inner .title').each(function(index, title) {
                                $clamp(title, {clamp: 2});
                            });
                        } else {
                            window.location.href = 'http://m.shihuo.cn';
                        }
                    }
                }
            })

        },

        render: function(response) {

            //先请求地址数据，更新地址数据

            var resultRaw = response.data.result,
                result = [];

            for(var key in resultRaw) {
                result.push(resultRaw[key]);
            }

            var validResult = [];

            _.each(result, function(bmodel, index) {

                var bmodelDataRaw = bmodel.data,
                    bmodelData = [];

                for(var key in bmodel.data) {
                    bmodelData.push(bmodelDataRaw[key]);
                }

                //未下架的
                bmodel.valid = _.where(bmodelData, {"invalid": false});
                validResult.push({
                    valid: bmodel.valid,
                    business: bmodel.business
                });

            });

            window.cart = validResult;

            var bcollection = new BusinessCollection(validResult, {model: BusinessModel});

            this.$el.append(new BusinessCollectionView({collection: bcollection}).$el);

            if (window.location.href.indexOf('#') != -1) {
                if (window.location.hash.slice(1).indexOf('#') != -1) {
                    var hashData = window.location.hash.slice(1).split('#')[1].split('-'),
                        lipinka_id = hashData[0],
                        lipinka_amount = hashData[1];
                    window.coupon = lipinka_amount;
                }
            }
            var _this = this;
            $.ajax({
                url: 'http://m.shihuo.cn/coupon/getLipinkaList',
                type: 'get',
                dataType: 'json',
                success: function(resp) {
                    if (resp.status == 0) {
                        _this.$el.append(_.template($('#tpl-fee-remark').html())(response.data.total_data));

                        $('#js-lipinka').on('click', function() {
                            if (window.location.href.indexOf('#') != -1) {
                                if (window.location.href.split('#').length > 2) {
                                    window.location.href = 'http://m.shihuo.cn/lipinka#' + encodeURIComponent(window.location.href.slice(0, window.location.href.lastIndexOf('#')));
                                } else {
                                    window.location.href = 'http://m.shihuo.cn/lipinka#' + encodeURIComponent(window.location.href);
                                }
                            } else {
                                window.location.href = 'http://m.shihuo.cn/lipinka#' + encodeURIComponent(window.location.href);
                            }
                        });

                        if (resp.data.list.length > 0) {
                            if (!lipinka_id) {
                                $('#js-lipinka-num').html(resp.data.list.length + '张可用').show();
                            } else {
                                $('#js-lipinka-num').html((resp.data.list.length - 1) + '张可用').show();

                                $('#js-amount').html('－￥' + lipinka_amount);
                                $('#js-lipinka-id').val(lipinka_id);

                                window.coupon = lipinka_amount;

                                Backbone.trigger('update-total');
                            }
                        } else {
                            $('#js-lipinka-num').html('0张可用').show();

                        }

                    }
                }
            });


            //购物车总计
            this.$el.append(new CartSummaryView({model: new CartSummaryModel(response.data.total_data)}).$el);

            //这里也要更新，礼品卡结果是异步的
            if ($('#js-lipinka-id').val()) {
                var totalPrice = (parseFloat($('#js-total').html())*1000 - parseFloat(window.coupon)*1000)/1000;
                $('#js-total').html(totalPrice);
            }

        }
    });


    /**
     * 购物车
     */
    var BusinessModel = Backbone.Model.extend({
        defaults: {
            valid: [],
            business: ""
        }
    });

    var BusinessCollection = Backbone.Collection.extend({
        model: BusinessModel
    });

    //商家列表
    var BusinessCollectionView = Backbone.View.extend({

        tagName: 'ul',

        className: 'ui-cart',

        initialize: function() {
            this.render();
        },

        render: function() {

            for (var i = 0, size = this.collection.size(); i < size; i++) {
                var businessView = new BusinessView({model: this.collection.at(i)});
                this.$el.append(businessView.$el);
            }

            return this;
        }
    })

    //商家
    var BusinessView = Backbone.View.extend({

        tagName: 'li',

        className: 'ui-cart-item',

        model: BusinessModel,

        template: _.template($('#tpl-business').html()),

        initialize: function() {
            this.render();
        },

        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            var skuCollection = new SkuCollection(this.model.get('valid'));

            var skuListView = new SkuListView({collection: skuCollection});
            this.$el.append(skuListView.$el);

            return this;
        }
    });

    var SkuModel = Backbone.Model.extend({
        defaults: {
            id: 0,
            number: 0,
            goods_id: 0,
            price: 0,
            img_path: "",
            attr: [],
            invalid: false,
            updateFlag: true,
            product_id: 0,
            freight: 0,
            title: "",
            limits: 0,
            weight: 0,
            restriction: false
        }
    });

    var SkuCollection = Backbone.Collection.extend({
        model: SkuModel
    });

    //商家内 购物车中的商品列表
    var SkuListView = Backbone.View.extend({

        tagName: 'ul',

        className: 'sku-list',

        collection: SkuCollection,

        initialize: function() {
            this.render();
        },

        render: function() {

            for (var i = 0, size = this.collection.size(); i < size; i++) {
                var skuView = new SkuView({model: this.collection.at(i)});
                this.$el.append(skuView.$el);
            }

            return this;
        }
    });

    var SkuView = Backbone.View.extend({

        tagName: 'li',

        className: 'sku',

        model: SkuModel,

        template: _.template($('#tpl-sku').html()),

        initialize: function() {
            this.render();
        },

        render: function() {

            var sku = this.model.toJSON();
            this.$el.html(this.template(sku));

            return this;
        }
    });

    var CartSummaryModel = Backbone.Model.extend({
        defaults: {
            total_price: 0
        }
    });

    var CartSummaryView = Backbone.View.extend({

        tagName: 'div',

        className: 'cart-bar',

        template: _.template($('#tpl-tabbar').html()),

        initialize: function() {
            this.listenTo(Backbone, 'update-total', this.updateTotal);
            this.render();
        },

        events: {
            "click .btn-bill": "submitOrder",
        },

        updateTotal: function updateTotal() {
            var totalPrice = (parseFloat($('#js-total').html())*1000 - parseFloat(window.coupon)*1000)/1000;
            $('#js-total').html(totalPrice);
        },

        submitOrder: function(e) {

            // 如果商品全部库存不足
            if ($('.sku-inner').length == $('.js-restriction').length) {
                new Tip({msg: "商品库存不足，无法提交"}).show();
                return;
            }

            if (!$(e.currentTarget).data('is_processing')) {

                var input = $('#js-input-identity');

                var reg = /^((1[1-5])|(2[1-3])|(3[1-7])|(4[1-6])|(5[0-4])|(6[1-5])|71|(8[12])|91)\d{4}((19\d{2}(0[13-9]|1[012])(0[1-9]|[12]\d|30))|(19\d{2}(0[13578]|1[02])31)|(19\d{2}02(0[1-9]|1\d|2[0-8]))|(19([13579][26]|[2468][048]|0[48])0229))\d{3}(\d|X|x)?$/;

                if (!window.address_id) {
                    new Tip({msg: "请填写收货地址"}).show();
                    return;
                }

                if (input.length) {
                    if ($.trim(input.val()).length == 0) {
                        new Tip({msg: "身份证号码不能为空"}).show();
                        return;
                    } else if (!reg.test(input.val())){
                        new Tip({msg: "身份证号码格式不正确"}).show();
                        return;
                    } else if ($('#js-input-identity').attr('readonly') != "true") {
                        new Tip({msg: "请先提交身份证号码，点击完成按钮"}).show();
                        return;
                    }
                }

                var gids = [];

                window.cart.forEach(function(item) {
                    item.valid.forEach(function(v) {
                        gids.push(v.goods_id);
                    })
                });

                $(e.currentTarget).data('is_processing', true);

                $.ajax({
                    url: 'http://m.shihuo.cn/haitao/cartSubmit',
                    type: 'post',
                    dataType: 'json',
                    data: {gid: gids, address_id: window.address_id, lipinka_id: $('#js-lipinka-id').val(), remark: $('#js-remark').val()},
                    success: function(data) {
                        $(e.currentTarget).data('is_processing', false);
                        //支付成功页面
                        if (data.status == 0) {
                            window.location.href = 'http://m.shihuo.cn/haitao/orderResult/?order_number=' + data.data.order_num;
                        } else if (data.status == 1) {
                            window.location.href = Util.loginUrl();
                        } else {
                            new Tip({msg: data.msg}).show();
                            return;
                        }
                    }
                });

            }
        },


        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });

    return {
        init: function() {
            var addressCollection = new AddressCollection();

            if (location.hash.slice(1).indexOf('&') != -1) {
                var addressStr = location.hash.slice(1).split('&')[1];
                addressCollection.url = 'http://m.shihuo.cn/haitao/address?address_id=' + addressStr.split('=')[1];
            } else {
                addressCollection.url = 'http://m.shihuo.cn/haitao/address';
            }

            new AddressView({collection: addressCollection});

            new CartCollectionView({model: new CartModel()});

        }
    }
});