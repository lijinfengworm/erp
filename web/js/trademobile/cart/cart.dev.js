define(['backbone', 'fastclick', 'md5', 'fx', 'alertbox', 'tip', 'clamp'], function(Backbone, FastClick, md5, fx, alertbox, Tip, clamp) {

    var Util = {
        getAllSkuChecked: function getAllSkuChecked(collection, cb) {

            var gids = [];

            collection.forEach(function(bmodel) {
                var ids = bmodel.skuCollection.map(function(model) {
                    if (model.get('checked')) {
                        return model.get('goods_id');
                    }
                });

                gids.push(_.without(ids, undefined));
            });

            gids = _.flatten(gids);

            $.ajax({
                url: 'http://m.shihuo.cn/haitao/goodPrice',
                type: 'post',
                dataType: 'json',
                data: {gid: gids},
                success: function(response) {
                    if (response.status == 0) {
                        cb && cb(response.data.total_product_price);
                    } else {
                        cb && cb(0.00);
                    }
                }
            });
        },

        isAllSkuChecked: function isAllSkuChecked(collection) {
            var allChecked = true;

            collection.forEach(function(bmodel) {
                bmodel.skuCollection.forEach(function(model) {
                    if (!model.get('checked')) {
                        allChecked = false;
                    }
                })
            })

            return allChecked;
        },

        isAllBiSkuChecked: function isAllBiSkuChecked(collection) {
            var allChecked = true;

            collection.forEach(function(model) {
                if (!model.get('checked')) {
                    allChecked = false;
                }
            })

            return allChecked;
        }
    };

    var CartModel = Backbone.Model.extend({
        url: 'http://m.shihuo.cn/haitao/cartList'
    });

    var CartSummaryModel = Backbone.Model.extend({
        defaults: {
            total_price: 0,
            total_product_freight: 0,
            total_product_price: 0,
            save_freight: 0,
            init_state: 1,
            checked: false
        }
    });

    var CartSummaryView = Backbone.View.extend({

        tagName: 'div',

        className: 'cart-bar',

        template: _.template($('#tpl-tabbar').html()),

        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
            this.render();
        },

        events: {
            "click .btn-bill": "checkout",
            "click .icon-check-block": "select"
        },

        select: function(e) {

            var _this = this;

            if (this.model.get('checked')) {

                this.model.set({checked: false});
                this.model.validCollection.forEach(function(bmodel) {
                    if (!bmodel.get('allChecked')) {
                        bmodel.set({allChecked: false});
                        bmodel.trigger('change:allChecked');
                    } else {
                        bmodel.set({allChecked: false});
                    }

                });

            } else {

                this.model.set({checked: true});
                this.model.validCollection.forEach(function(bmodel) {
                    if (bmodel.get('allChecked')) {
                        bmodel.set({allChecked: true});
                        bmodel.trigger('change:allChecked');
                    } else {
                        bmodel.set({allChecked: true});
                    }

                });

            }

        },

        checkout: function() {

            //如果没有选择商品，不让提交，提示
            if ($('.icon-block.active').length == 0) {
                new Tip({msg: "请先选择商品"}).show();
                return;
            }

            var gids = [];
            window.bcollection.forEach(function(bmodel) {
                var skuCollection = _.clone(bmodel.skuCollection);
                // var bmodelChecked = false;
                skuCollection.forEach(function(model, index) {
                    if (model.get('checked')) {
                        gids.push(model.get('goods_id'));
                    }
                })
            });

            var data = {
                gid: gids
            }

            window.location.href = 'http://m.shihuo.cn/haitao/cartConfirm/#' + encodeURIComponent(JSON.stringify(data));
        },

        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });

    var CartCollectionView = Backbone.View.extend({

        el: '#js-cart',

        model: CartModel,

        initialize: function() {
            var _this = this;
            this.model.fetch({
                success: function(model, data) {
                    //如果没有数据，显示购物车为空提示
                    if (data.status == 0 && data.data.result.length == 0) {
                        $('#js-cart-empty').show();
                        $('#js-cart-container').css('padding-bottom', '10px');
                    } else if (data.status == 0) {
                        _this.render(data);

                        $('.sku-attr-inner .title').each(function(index, title) {
                            $clamp(title, {clamp: 2});
                        });
                    }
                }
            });
        },

        render: function(response) {
            var resultRaw = response.data.result,
                result = [];

            for(var key in resultRaw) {
                result.push(resultRaw[key]);
            }

            var validResult = [],
                invalidResult = [];

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

                bmodel.invalid = _.where(bmodelData, {"invalid": true});
                invalidResult.push({
                    invalid: bmodel.invalid,
                    business: bmodel.business
                });

            });

            //未下架的
            var bcollection = new BusinessCollection(validResult, {model: BusinessModel});

            //cart summary 结算
            var cartSummaryModel = new CartSummaryModel(response.data.total_data);
            cartSummaryModel.validCollection = bcollection;

            window.cartSummaryModel = cartSummaryModel;

            bcollection.forEach(function(model) {
                model.cartSummary = cartSummaryModel;
            })

            this.$el.append(new BusinessCollectionView({collection: bcollection}).$el);

            //下架的
            var saleoffData = [];
            _.each(invalidResult, function(bmodel, index) {
                saleoffData.push(bmodel.invalid);
            });

            saleoffData = _.flatten(saleoffData);

            if (saleoffData.length == 0) {
                $('.offsale-list').hide();
            } else {
                var saleOffcollection = new SaleOffCollection(saleoffData);
                this.$el.append(new SaleOffCollectionView({collection: saleOffcollection}).$el);
            }

            this.$el.append(_.template($('#tpl-tax').html())({}));

            //购物车总计
            this.$el.append(new CartSummaryView({model: cartSummaryModel}).$el);
        }
    });

    /**
     * 下架
     */
    var SaleOffModel = Backbone.Model.extend({
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
            weight: 0
        }
    });

    var SaleOffCollection = Backbone.Collection.extend({
        model: SaleOffModel
    });

    var SaleOffCollectionView = Backbone.View.extend({

        tagName: 'ul',

        className: 'offsale-list',

        template: _.template($('#tpl-offsale').html()),

        collection: SaleOffCollection,

        initialize: function() {
            this.render();
        },


        render: function() {
            for (var i = 0, size = this.collection.size(); i < size; i++) {
                var saleOffView = new SaleOffView({model: this.collection.at(i)});
                this.$el.append(saleOffView.$el);
            }

            return this;
        }
    });

    var SaleOffView = Backbone.View.extend({

        tagName: 'li',

        template: _.template($('#tpl-offsale').html()),

        model: SaleOffModel,

        initialize: function() {
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.hide);
            this.render();
        },

        events: {
            "click": "delete"
        },

        delete: function() {
            var _this = this;
            var cartItem = this.$el.parents('.ui-cart-item'),
                list = this.$el.parents('.sku-list');
            this.model.destroy({
                url: 'http://m.shihuo.cn/haitao/cartDelete',
                data: {
                    gid: [this.model.get('goods_id')]
                },
                processData: true,
                success: function(model, response) {
                    if (response.status == 0) {
                        $('#js-cart-num').html('(' + response.data.count + ')');
                    }

                    setTimeout(function() {
                        if (list.find('li').length <= 0) {
                            cartItem.hide();
                        }
                    }, 10);
                }
            });
        },

        hide: function() {
            this.$el.hide();
        },

        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });

    /**
     * 购物车
     */
    var BusinessModel = Backbone.Model.extend({
        defaults: {
            valid: [],
            business: "",
            checked: false,
            allChecked: false
        }
    });

    var BusinessCollection = Backbone.Collection.extend({
        model: BusinessModel
    });

    //商家列表
    var BusinessCollectionView = Backbone.View.extend({

        tagName: 'ul',

        className: 'ui-cart',

        collection: BusinessCollection,

        initialize: function() {
            this.render();
        },

        render: function() {

            window.bcollection = this.collection;

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
            this.listenTo(this.model, 'change:checked', this.check);
            this.model.on('change:allChecked', this.allCheck, this);
            this.listenTo(this.model, 'change:isEditing', this.render);
            this.render();
        },

        events: {
            "click .btn-edit": "edit",
            "click .icon-check-block": "select"
        },

        edit: function(e) {

            if ($.trim($(e.currentTarget).html()) == "编辑") {

                $(e.currentTarget).html("完成");

                this.model.skuCollection.forEach(function(model) {
                    model.set('isEditing', true);
                });
            } else {

                $(e.currentTarget).html("编辑");
                this.model.skuCollection.forEach(function(model) {
                    model.set('isEditing', false);
                });
            }
        },

        allCheck: function(e) {

            var checkbox = this.$el.find('.icon-check-block'),
                cartSummaryModel = this.model.cartSummary;

            if (this.model.get('allChecked')) {

                this.model.set('checked', true, {silent: true});

                this.model.skuCollection.forEach(function(model) {
                    model.set('checked', true, {silent: true});
                });

                $('.icon-check-block, .icon-block').addClass('active');

                var allChecked = true;
                window.bcollection.forEach(function(bmodel) {
                    bmodel.skuCollection.forEach(function(model) {
                        if (!model.get('checked')) {
                            allChecked = false;
                        }
                    });
                })

                if (allChecked) {
                    Util.getAllSkuChecked(window.bcollection, function(data) {
                        cartSummaryModel.set({total_product_price: data, init_state: 0});
                    });
                }

            } else {

                this.model.set({checked: false}, {silent: true});

                this.model.skuCollection.forEach(function(model){
                    model.set('checked', false, {silent: true});
                });

                $('.icon-check-block, .icon-block').removeClass('active');

                var allNotChecked = true;
                window.bcollection.forEach(function(bmodel) {
                    bmodel.skuCollection.forEach(function(model) {
                        if (model.get('checked')) {
                            allNotChecked = false;
                        }
                    });
                })

                if (allNotChecked) {
                    Util.getAllSkuChecked(window.bcollection, function(data) {
                        cartSummaryModel.set({total_product_price: data, init_state: 0});
                    });
                }
            }
        },

        check: function(e) {

            var checkbox = this.$el.find('.icon-check-block'),
                cartSummaryModel = this.model.cartSummary;

            if (checkbox.hasClass('active')) {

                checkbox.removeClass('active');
                this.model.set('checked', false, {silent: true});

                if ($(e).currentTarget) {
                    Util.getAllSkuChecked(window.bcollection, function(data) {
                        cartSummaryModel.set({total_product_price: data, init_state: 0, checked: false});
                    });
                }

            } else {

                checkbox.addClass('active');
                this.model.set('checked', true, {silent: true});

                this.model.skuCollection.forEach(function(model){
                    model.set('checked', true);
                });

                if (Util.isAllSkuChecked(window.bcollection)) {
                    cartSummaryModel.set({checked: true});
                    if ($(e).currentTarget) {
                        Util.getAllSkuChecked(window.bcollection, function(data) {
                            cartSummaryModel.set({total_product_price: data, init_state: 0, checked: true});
                        });
                    }
                } else {
                    cartSummaryModel.set({checked: false});
                    if ($(e).currentTarget) {
                        Util.getAllSkuChecked(window.bcollection, function(data) {
                            cartSummaryModel.set({total_product_price: data, init_state: 0, checked: false});
                        });
                    }
                }
            }
        },

        select: function(e) {
            //只负责全选或者全不选
            var checkbox = this.$el.find('.icon-check-block'),
                cartSummaryModel = this.model.cartSummary;

            if (checkbox.hasClass('active')) {

                checkbox.removeClass('active');
                this.model.set('checked', false, {silent: true});

                if (Util.isAllBiSkuChecked(this.model.skuCollection)) {
                    this.model.skuCollection.forEach(function(model){
                        model.set('checked', false);
                    });
                }

                Util.getAllSkuChecked(window.bcollection, function(data) {
                    cartSummaryModel.set({total_product_price: data, init_state: 0, checked: false});
                });

            } else {

                checkbox.addClass('active');
                this.model.set('checked', true, {silent: true});

                this.model.skuCollection.forEach(function(model){
                    model.set('checked', true);
                });

                if (Util.isAllSkuChecked(window.bcollection)) {
                    Util.getAllSkuChecked(window.bcollection, function(data) {
                        cartSummaryModel.set({total_product_price: data, init_state: 0, checked: true});
                    });
                } else {
                    Util.getAllSkuChecked(window.bcollection, function(data) {
                        cartSummaryModel.set({total_product_price: data, init_state: 0});
                    });
                }
            }
        },

        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            var skuCollection = new SkuCollection(this.model.get('valid'));

            skuCollection.collection = this.model.collection;
            skuCollection.biModel = this.model;
            skuCollection.cartSummary = this.model.cartSummary;
            this.model.skuCollection = skuCollection;

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
            checked: false,
            isEditing: false
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
            this.listenTo(this.model, 'change', this.render);
            this.listenTo(this.model, 'destroy', this.hide);
            this.render();
        },

        events: {
            "click .trash": "deleteOne",
            "click .icon-block": "select",
            "click .jian": "jian",
            "click .jia": "jia"
        },

        jian: function(e) {

            var numberInput = $(e.currentTarget).next('input'),
                sku = this.model.toJSON();

            var _this = this;

            if (numberInput.val() == 1) {
                return;
            } else {
                $.ajax({
                    url: 'http://m.shihuo.cn/haitao/cartEdit',
                    type: 'post',
                    dataType: 'json',
                    data: {pid: sku.product_id, gid: sku.goods_id, type: 0},
                    success: function(response) {

                        if (response.status == 0) {
                            numberInput.val(parseInt(numberInput.val(), 10) - 1);
                            _this.model.set({number: numberInput.val()});

                            Util.getAllSkuChecked(window.bcollection, function(data) {
                                _this.model.collection.cartSummary.set({total_product_price: data, init_state: 0});
                            });

                        } else if (response.status == 1) {
                           //未登录
                           new Tip({
                              msg: "登录超时，正在跳转到登陆页",
                              callback: function() {
                                 location.href = $.ui.loginUrl();
                              }
                           }).show();
                        }
                    }
                });
            }
        },

        jia: function(e) {

            var numberInput = $(e.currentTarget).prev('input'),
                sku = this.model.toJSON();

            var _this = this;

            if (numberInput.val() == sku.limits) {
                return;
            } else {
                $.ajax({
                    url: 'http://m.shihuo.cn/haitao/cartEdit',
                    type: 'post',
                    dataType: 'json',
                    data: {pid: sku.product_id, gid: sku.goods_id, type: 1},
                    success: function(response) {

                        if (response.status == 0) {
                            numberInput.val(parseInt(numberInput.val(), 10) + 1);
                            _this.model.set({number: numberInput.val()});

                            Util.getAllSkuChecked(window.bcollection, function(data) {
                                _this.model.collection.cartSummary.set({total_product_price: data, init_state: 0});
                            });

                        } else if (response.status == 1) {
                            //未登录
                           new Tip({
                              msg: "登录超时，正在跳转到登陆页",
                              callback: function() {
                                 location.href = $.ui.loginUrl();
                              }
                           });
                        }
                    }
                });
            }
        },

        select: function(e) {
            var checkbox = $(e.currentTarget);
            var cartSummaryModel = this.model.collection.cartSummary,
                biModel = this.model.collection.biModel;

            if (cartSummaryModel.get('checked')) {
                if (checkbox.hasClass('active')) {

                    this.model.set('checked', false);

                    biModel.set('checked', false);

                    cartSummaryModel.set('checked', false);

                    Util.getAllSkuChecked(window.bcollection, function(data) {
                        cartSummaryModel.set({total_product_price: data, init_state: 0});
                    });

                }  else {

                    this.model.set('checked', true);

                    if (Util.isAllBiSkuChecked(this.model.collection)) {
                        biModel.set('checked', true);
                    } else {
                        biModel.set('checked', false);
                    }

                    if (Util.isAllSkuChecked(window.bcollection)) {
                        cartSummaryModel.set('checked', true);
                    }

                    Util.getAllSkuChecked(window.bcollection, function(data) {
                        cartSummaryModel.set({total_product_price: data, init_state: 0});
                    });
                }

            } else {

                if (checkbox.hasClass('active')) {

                    this.model.set('checked', false);

                    biModel.set('checked', false);

                    Util.getAllSkuChecked(window.bcollection, function(data) {
                        cartSummaryModel.set({total_product_price: data, init_state: 0});
                    });

                } else {

                    this.model.set('checked', true);

                    if (Util.isAllBiSkuChecked(this.model.collection)) {
                        biModel.set('checked', true);
                    } else {
                        biModel.set('checked', false);
                    }

                    if (Util.isAllSkuChecked(window.bcollection)) {
                        Util.getAllSkuChecked(window.bcollection, function(data) {
                            cartSummaryModel.set({total_product_price: data, init_state: 0, checked: true});
                        });
                    } else {
                        Util.getAllSkuChecked(window.bcollection, function(data) {
                            cartSummaryModel.set({total_product_price: data, init_state: 0});
                        });
                    }
                }
            }

            return false;
        },

        hide: function() {
            this.$el.hide();
        },

        deleteOne: function(e) {

            var cartSummaryModel = this.model.collection.cartSummary;

            var _this = this;

            alertbox.show({
                title: '确定将这个宝贝删除吗',
                cancel: function() {
                    alertbox.hide();
                },
                confirm: function() {
                    var skuCollection = _this.model.collection,
                        biCollection = skuCollection.collection,
                        cartItemSizes = 0;

                    _this.model.destroy({
                        url: 'http://m.shihuo.cn/haitao/cartDelete',
                        data: {
                            gid: [_this.model.get('goods_id')]
                        },
                        processData: true,
                        success: function(model, response) {
                            if (response.status == 0) {
                                $('#js-cart-num').html('(' + response.data.count + ')');
                            }

                            if (skuCollection.size() <= 0) {
                                _this.$el.parents('.ui-cart-item').hide();
                            }

                            //如果已经没有商品，显示 购物车为空提示
                            if (response.data.count == 0) {
                                $('#js-cart-empty').show();
                                $('.cart-bar').hide();
                                $('#js-cart-container').css('padding-bottom', '10px');
                            }

                             Util.getAllSkuChecked(window.bcollection, function(data) {
                                cartSummaryModel.set({total_product_price: data, init_state: 0});
                            });
                        }
                    });

                }
            });
        },

        render: function() {

            var sku = this.model.toJSON();
            this.$el.html(this.template(sku));

            $('.sku-attr-inner .title').each(function(index, title) {
                $clamp(title, {clamp: 2});
            });

            var _this = this;

            //更新价格
            if (sku.updateFlag) {
                $.ajax({
                    url: 'http://www.shihuo.cn/app2/updateDaigouPrice',
                    type: 'post',
                    dataType: 'json',
                    data: {pid: sku.product_id, gid: sku.goods_id, token: md5(sku.goods_id + '' + sku.product_id + '123456')},
                    success: function(data) {
                        if (data.status == 0) {
                            _this.model.set({
                                updateFlag: false,
                                price: data.data.price
                            });
                        } else {
                            _this.model.set({updateFlag:false});
                        }

                        $('.sku-attr-inner .title').each(function(index, title) {
                            $clamp(title, {clamp: 2});
                        });
                    }
                })
            }
            return this;
        }
    });


    /**
     * 大家都在买
     */

     var RecomModel = Backbone.Model.extend({
        defaults: {
            id: "",
            product_id: "",
            gid: "",
            img: "",
            url: ""
        }
     });

     var RecomCollection = Backbone.Collection.extend({
        model: RecomModel,
        url: 'http://m.shihuo.cn/haitao/mostPurchase',
        parse: function(response) {
            return response.data;
        }
     });

     var RecomView = Backbone.View.extend({

        el: '#js-recom',

        template: _.template($('#tpl-recom').html()),

        initialize: function() {
            this.render();
        },

        render: function() {
            this.$el.html(this.template({})).hide();
            var recomCollection = new RecomCollection();
            this.$el.find('.ui-recom').append(new RecomCollectionView({collection: recomCollection}).$el);

            return this;
        }
     });

     var RecomCollectionView = Backbone.View.extend({

        tagName: 'ul',

        className: 'ui-recom-list clearfix',

        initialize: function() {
            var _this = this;
            this.collection.fetch({
                success: function(collection, data) {
                    _this.render();
                },

                fail: function(err) {

                }
            })
        },

        render: function() {
            if (this.collection.size() == 0) {
                $('#js-recom').hide();
            } else {
                for (var i = 0, size = this.collection.size(); i < size; i++) {
                    this.$el.append(new RecomItemView({model: this.collection.at(i)}).$el);
                }

                $('#js-recom').show();
            }

            return this;
        }
     });

     var RecomItemView = Backbone.View.extend({

        tagName: 'li',

        className: 'ui-recom-item',

        template: _.template($('#tpl-recom-item').html()),

        initialize: function() {
            this.render();
        },

        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
     });


    return {
        init: function() {

            $.ajax({
                url: 'http://m.shihuo.cn/haitao/cartCount',
                type: 'get',
                dataType: 'json',
                success: function(response) {
                    if (response.status == 0) {
                        if (response.data.count != 0) {
                            $('#js-cart-num').html('(' + response.data.count + ')')
                        }
                    }
                }
            })

            var cartCollectionView = new CartCollectionView({model: new CartModel()});

            var recomView = new RecomView();

            FastClick.attach(document.body);

        }
    }

});