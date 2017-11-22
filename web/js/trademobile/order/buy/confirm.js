define(['backbone', 'fastclick', 'md5', 'fx', 'alertbox', 'tip'], function(Backbone, FastClick, md5, fx, alertbox, Tip) {

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


    return {
        init: function() {
            var addressCollection = new AddressCollection();
            var href = window.location.href;
            if (href.indexOf('address_id') != -1) {
                var addressStr = href.slice(href.indexOf('address_id'), href.length);
                addressCollection.url = 'http://m.shihuo.cn/haitao/address?address_id=' + addressStr.split('=')[1];
            } else {
                addressCollection.url = 'http://m.shihuo.cn/haitao/address';
            }

            new AddressView({collection: addressCollection});

        }
    }
});