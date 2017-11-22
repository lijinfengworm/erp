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
            updated_at: ""
        }
    });

    var AddressCollection = Backbone.Collection.extend({
        model: AddressModel,
        url: 'http://m.shihuo.cn/haitao/address?is_list=1',
        parse: function(response) {
            return response.data.address;
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
            for (var i = 0, size = this.collection.size(); i < size; i++) {
                this.$el.append(new AddressItemView({model: this.collection.at(i)}).$el);
            }

            $('body').append(_.template($('#tpl-btn-add').html())({noAddress: false, jumpurl: encodeURIComponent(window.location.href)}));

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
            'click #js-btn-identity': 'editAddress',
            'click #js-edit-address': 'editAddress',
            'click #js-delete-address': 'deleteAddress'
        },

        deleteAddress: function() {
            var _this = this;
            alertbox.show({
                title: '确定删除收货地址吗',
                cancel: function() {
                    alertbox.hide();
                },
                confirm: function() {
                    window.location.href = 'http://m.shihuo.cn/daigou/cancelUserDeliveryAddress?id=' + _this.model.get('id');
                }
            });
        },

        editAddress: function() {
             window.location.href = "http://m.shihuo.cn/haitao/editAddress?address_id=" + this.model.get('id') + '&jumpurl=' + encodeURIComponent(window.location.href);
        },

        render: function() {
            this.$el.html(this.template(this.model.toJSON()));
            return this;
        }
    });

    return {
        init: function() {
            var addressCollection = new AddressCollection();
            var addressView = new AddressView({collection: addressCollection});



            FastClick.attach(document.body);
        }
    }
});