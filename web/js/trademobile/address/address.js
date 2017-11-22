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
            edit_flag: false,
            selected: false
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
            //被选择的地址id
            var id = window.location.href.split("?")[1].split("#")[0].split("=")[1];

            for (var i = 0, size = this.collection.size(); i < size; i++) {
                var item = this.collection.at(i);
                if (item.get('id') == id) {
                    item.set('selected', true);
                } else {
                    item.set('selected', false);
                }

                this.$el.append(new AddressItemView({model: item}).$el);
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
            this.listenTo(this.model, 'change:edit_flag', this.render);
            this.render();
        },

        events: {
            'click': 'clickRoute'
        },

        clickRoute: function() {
            if (this.model.get('edit_flag')) {
                this.goEditPage();
            } else {
                this.chooseAddress();
            }
        },

        goEditPage: function() {
            window.location.href = "http://m.shihuo.cn/haitao/editAddress?address_id=" + this.model.get('id') + '&jumpurl=' + encodeURIComponent(window.location.href);
        },

        chooseAddress: function() {
            var reg = /http:\/\/m.shihuo.cn\/user/;
            if (reg.test(document.referrer)) {
                return;
            }

            var jumpurl = decodeURIComponent(window.location.hash.slice(1));

            if (/address_id/.test(jumpurl)) {
                 jumpurl = jumpurl.slice(0, jumpurl.lastIndexOf("&"));
            }

            window.location.href = jumpurl + '&address_id=' + this.model.get('id');
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

            $('#js-edit').on('click', function() {
                if ($(this).html() == '编辑') {
                    $(this).html('完成');
                    addressCollection.forEach(function(model) {
                        model.set('edit_flag', true);
                    });
                } else {
                    $(this).html('编辑');
                    addressCollection.forEach(function(model) {
                        model.set('edit_flag', false);
                    });
                }
            });

            FastClick.attach(document.body);
        }
    }
});