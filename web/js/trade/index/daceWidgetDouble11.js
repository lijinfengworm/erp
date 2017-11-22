/**
 * Created by guwei on 14/11/5.
 */
(function () {
    var daceWidget = {
        box: $('#dace-widget-double11'),
        loading: false,
        init: function () {
            var vid = daceWidget.getVid(),
                defaultUrl = 'http://115.29.202.27:9527/api/getDefaultGoods?vid=' + vid,
                changeUrl = 'http://115.29.202.27:9527/api/choiceGoods?vid=' + vid,
                goClickUrl = ' http://115.29.202.27:9527/api/goClick?vid=' + vid + '&id=',
                disLikeUrl = ' http://115.29.202.27:9527/api/disClick?vid=' + vid,
                $daceWidget = daceWidget.box,
                catLink = null,
                sexBox = null,
                sexLink = null,
                priceLink = null,
                itemList = null,
                dfd = $.Deferred(),
                dataJosn = {};

            // 初次载入
            dfd.resolve(daceWidget.appendHtml())
                .done(function () {
                    daceWidget.scrollFix($daceWidget);

                    daceWidget.changeGroup(defaultUrl, dataJosn, function (data) {
                        var _conditon = data.condition,
                            linkA = $('a[data-category],a[data-sex],a[data-price-type]');
                        catLink = $daceWidget.find('.cat-choose li a');
                        sexBox = $daceWidget.find('.sex-choose');
                        sexLink = sexBox.find('li a');
                        priceLink = $daceWidget.find('.price-choose li a');
                        itemList = $daceWidget.find('.item-list');

                        dataJosn.category = _conditon.category;
                        dataJosn.sex = _conditon.sex;
                        _conditon.price === true && (dataJosn.priceType = 2);

                        // 默认载入加高亮选项
                        $.each(dataJosn, function (key, value) {
                            linkA.each(function (index, item) {
                                if ($(item).data(key) == dataJosn[key]) {
                                    $(item).addClass('current')
                                }
                            });
                        });

                        //  分类选择
                        catLink.on('click', function () {
                            var foodArray = ['<20元','20~50元','>50元'],
                                priceArray = ['<100元','100~300元','>300元'],
                                digitArray = ['<200元','200~1000元','>1000元'],
                                sexArray = ['男','女'];
                            changeClick(catLink, $(this), 'category');
                            dataJosn.act = null;
                            if ( $(this).data('category') === 'digit' || $(this).data('category') === 'foods' ){
                                sexLink.each(function(key, item){
                                    $(item).text('')
                                });
                                dataJosn.sex = 'none';
                                sexBox.css('display','none');
                            } else {
                                sexLink.each(function(key, item){
                                    $(item).text(sexArray[key]);
                                    if ( $(item).hasClass('current') ){
                                        dataJosn.sex = $(item).data('sex');
                                    }
                                });
                                sexBox.css('display','block');
                            }
                            if ( $(this).data('category') === 'foods' ){
                                priceLink.each(function(key, item){
                                    $(item).text(foodArray[key])
                                });
                            } else if ( $(this).data('category') === 'digit'  ) {
                                priceLink.each(function(key, item){
                                    $(item).text(digitArray[key])
                                });
                            }else {
                                priceLink.each(function(key, item){
                                    $(item).text(priceArray[key])
                                });
                            }
//                            oldSex = dataJosn.sex;
                            daceWidget.changeGroup(changeUrl, dataJosn);
                        });
                        //  性别选择
                        sexLink.on('click', function () {
                            changeClick(sexLink, $(this), 'sex');
                            daceWidget.changeGroup(changeUrl, dataJosn);
                        });
                        // 价格选择
                        priceLink.on('click', function () {
                            changeClick(priceLink, $(this), 'priceType');
                            daceWidget.changeGroup(changeUrl, dataJosn);
                        });
                        // 换一组
                        $daceWidget.on('click', function (e) {
                            var $target = $(e.target).attr('class');
                            if ($target === 'btn btn-refresh') {
                                e.preventDefault();
                                dataJosn.act = 'batch';
                                daceWidget.changeGroup(changeUrl, dataJosn);
                            }
                        });
                        // 不喜欢
                        itemList.on('click', 'li', function (e) {
                            var $target = $(e.target).attr('class'),
                                isBuy = $(e.target).hasClass('go-buy'),
                                $this = $(this);
                            if (isBuy) {
                                $.ajax({
                                    type: 'get',
                                    url: goClickUrl + $this.data('id'),
                                    async: true,
                                    cache: false,
                                    contentType: "text/json; charset=utf-8",
                                    dataType: "jsonp",
                                    crossDomain: true
                                });
                            } else if ($target === 'btn not-like') {
                                e.preventDefault();
                                var itemIndex = $this.data('index');
                                dataJosn.id = $this.data('id');
                                if (itemIndex == 0) {
                                    daceWidget.changeItem(disLikeUrl, dataJosn, 0, $this);
                                }
                                if (itemIndex == 1) {
                                    daceWidget.changeItem(disLikeUrl, dataJosn, 1, $this);
                                }
                            }
                        });

                    });
                });


            // 参数改变函数
            function changeClick(allLink, el, key1) {
                if (el.hasClass('current') || daceWidget.loading === true)return;
                dataJosn[key1] = el.data(key1);
                // key2 && (dataJosn[key2] = el.data(key2));
                allLink.removeClass('current');
                el.addClass('current');
            }
        },
        // 获取 category sex min max 值
        getParam: function () {
            var _el = daceWidget.box,
                paramNode = _el.find('.current'),
                paramData = {category: '', sex: '', priceType: ''};
            $.each(paramData, function (key, value) {
                paramNode.each(function (index, item) {
                    if ($(item).data(key) !== undefined) {
                        paramData[key] = $(item).data(key)
                    }
                });
            });
            return paramData;
        },
        // 改变一个商品
        changeItem: function (url, dataJosn, index, the) {
            if (daceWidget.loading === true) return;
            daceWidget.box.find('.item-list ul li').eq(index).append('<div class="item-mask"></div>');
            daceWidget.loading = true;
            $.ajax({
                type: 'GET',
                url: url,
                data: dataJosn,
                dataType: 'jsonp',
                jsonp: 'callback',
                async: true,
                cache: false,
                contentType: "text/json; charset=utf-8",
                crossDomain: true,
                success: function (response) {
                    var _data = response.data,
                        result = '';

                    if (_data.length === 0 || _data[1].id === 0) {
                        $('.item-mask').eq(index).fadeOut('fast');
                        daceWidget.box.find('.item-list ul li').eq(index).html('<span class="null">没有更多了</span>');
                    } else {

                        the.data('id', _data[1].id);
                        // 数据处理
                        _data[1].discount = daceWidget.disposeDiscount(_data[1].discount);
                        _data[1].detailPageUrl = encodeURIComponent(_data[1].detailPageUrl);
                        result += daceWidget._tpl(daceWidget.itemTemplate(), _data[1]);
                        // 插入dom
                        daceWidget.box.find('.item-list ul li').eq(index).html(result)
                        daceWidget.imgLoad(_data[1].largeImageUrl, function (d) {
                            $('.item-mask').eq(index).hide().delay(2000).fadeOut('fast');
                        });
                        $.each(_data, function (key, value) {
                            key == 0 && (dataJosn.id1 = value.id);
                            key == 1 && (dataJosn.id2 = value.id);
                        });
                    }
                    daceWidget.loading = false;

                },
                error: function (msg) {
                    daceWidget.loading = false;
                }
            });
        },
        // 改变一组商品
        changeGroup: function (url, dataJosn, callBackFn) {
            if (daceWidget.loading === true) return;
            daceWidget.box.find('.bd').append('<div class="group-mask"></div>');
            daceWidget.loading = true;
            $.ajax({
                type: 'get',
                url: url,
                data: dataJosn,
                dataType: 'jsonp',
                jsonp: 'callback',
                async: true,
                cache: false,
                contentType: "text/json; charset=utf-8",
                crossDomain: true,
//                    jsonpCallback: 'loadData',
                success: function (response) {
                    var _data = response.data,
                        result = '';
                    if (_data.length === 0) {
                        $('.group-mask').fadeOut('fast').remove();
                        daceWidget.box.find('.item-list ul').html('<li class="null">没有更多了</li>');
                    } else {
                        // 数据处理
                        $.each(_data, function (key, value) {
                            if (value.id === 0) {
                                result += '<li class="null">没有更多了</li>';
                            } else {
                                // detailPageUrl
                                value.discount = daceWidget.disposeDiscount(value.discount);
                                value.detailPageUrl = encodeURIComponent(value.detailPageUrl);
                                result += '<li data-id="' + value.id + '" data-index="' + key + '">';
                                result += daceWidget._tpl(daceWidget.itemTemplate(), value);
                                result += '</li>';
                            }
                            // 插入dom
                            daceWidget.box.find('.item-list ul').html(result);
                            // 图片缓存
                            daceWidget.imgLoad(value.largeImageUrl, function (d) {
                                $('.group-mask').fadeOut('fast').remove();
                                daceWidget.loading = false;
                            });
                            key == 0 && (dataJosn.id1 = value.id);
                            key == 1 && (dataJosn.id2 = value.id);
                        });
                        callBackFn && callBackFn(response);
                    }
                    daceWidget.loading = false;
                },
                error: function (msg) {
                    daceWidget.loading = false;
                }
            });
        },
        loadCss: function (url) {
            var head = document.getElementsByTagName('head'),
                linkStyle = document.createElement('link');
//                css = document.createElement('link');
            linkStyle.href = url;
            linkStyle.rel = 'stylesheet';
            linkStyle.type = 'text/css';
            head[0].appendChild(linkStyle)
        },
        disposeDiscount: function (data) {
            var unit = String(data).slice(2, 3),
                decimal = String(Math.ceil(data * 100)).slice(0,1),
                result;
            result = unit + '.' + decimal;
            return result
        },
        // 图片加载方法
        imgLoad: function (url, callback) {
            var img = new Image();

            img.src = url;
            if (img.complete) {
                callback(img);
            } else {
                img.onload = function () {
                    callback(img);
                    img.onload = null;
                };
            }
        },
        // 固定位置
        scrollFix: function () {
            var _el = daceWidget.box,
                _inner = _el.find('.dace-widget-sh-11'),
                _classTopName = 'dace-widget-sh-top-fixed',
                _classBottomName = 'dace-widget-sh-bottom-fixed',
                $window = $(window),
                offsetTop = _el.position().top,
                offsetLeft = _el.position().left,
                offsetHeight = _el.outerHeight(),
                sideHeight = _el.outerHeight() - 4,
                pageHeight = $(document).height(),
                clientHeight = $window.innerHeight(),
                oldScrollTop;
            if (offsetTop+offsetHeight<pageHeight-120){
                scrollFn();
                $(window).scroll(function () {
                    scrollFn();
                });
                $(window).on('resize',function () {
                    clientHeight = $window.innerHeight();
                    scrollFn();
                });
            }
            function scrollFn() {

                if( $window.scrollTop() > pageHeight - clientHeight - 130 ){
                    _inner.removeClass(_classTopName + ' ' + _classBottomName)
                } else if ($window.scrollTop() > offsetTop + 257) {
                    if (clientHeight < sideHeight) {
                        if (oldScrollTop > $window.scrollTop()) {
                            _inner.addClass(_classTopName);
                            _inner.removeClass(_classBottomName);
                        } else {
                            _inner.addClass(_classBottomName);
                            _inner.removeClass(_classTopName);
                        }
                        oldScrollTop = $window.scrollTop();
                    } else {
                        _inner.addClass(_classTopName).removeClass(_classBottomName);
                    }
                } else {
                    _inner.removeClass(_classTopName + ' ' + _classBottomName)
                }
            }
        },
        // 外框html
        appendHtml: function () {
            var _html = '<div class="dace-widget-sh-11">' +
                '<div class="hd"><h3>双11商品精选</h3></div> ' +
                '<div class="bd">' +
                '<div class="cat-tab"> ' +
                '<ul class="cat-choose">' +
                '<li><a data-category="sports" href="javascript:;">运动</a></li> ' +
                '<li><a data-category="casual" href="javascript:;">服饰</a></li> ' +
                '<li><a data-category="digit" href="javascript:;">数码</a></li> ' +
                '<li><a data-category="foods" href="javascript:;">美食</a></li> ' +
                '</ul> ' +
                '</div> ' +
                '<div class="param-tab"> ' +
                '<ul class="sex-choose"> ' +
                '<li><a data-sex="man" href="javascript:;">男</a></li> ' +
                '<li><a data-sex="woman" href="javascript:;">女</a></li> ' +
                '</ul> ' +
                '<ul class="price-choose"> ' +
                '<li><a href="javascript:;" data-price-type="1"><100元</a></li>' +
                '<li><a href="javascript:;" data-price-type="2">100~300元</a></li>' +
                '<li><a href="javascript:;" data-price-type="3">>300元</a></li>' +
                '</ul> ' +
                '</div> ' +
                '<div class="item-list"> ' +
                '<ul>' +
                '</ul>' +
                '</div>' +
                '<div class="btn-all-group"> <a class="btn btn-download" href="http://www.shihuo.cn/1111#all" target="_blank"><i class="icon-download"></i>下载全部商品</a> <a class="btn btn-refresh" href="javascript:;"><i class="icon-refresh"></i>换一组看看</a> </div> ' +
                '</div>' +
                ' </div>';
            daceWidget.box.append(_html);
        },
        // 商品内部 html
        itemTemplate: function () {
            return '<p class="tit"><a class="go-buy" href="http://go.hupu.com/u?url={{detailPageUrl}}" title="去购买" target="_blank">{{title}}</a></p>' +
                '<div class="detail">' +
                '<div class="detail-info">' +
                '<p class="reduction"><span>{{discount}}</span>折</p>' +
                '<p class="vip-price">￥<span>{{priceNow}}</span></p>' +
                '<p class="original-price">原价：￥<span>{{priceOld}}</span></p>' +
                '<dl class="sell-count"> <dt>最近销量</dt> <dd><span>{{sales}}</span>件</dd> </dl> ' +
                '</div> ' +
                '<div class="detail-pic"> <a class="go-buy" href="http://go.hupu.com/u?url={{detailPageUrl}}" title="去购买" target="_blank"><img src="{{largeImageUrl}}" alt="{{title}}"/></a> </div> ' +
                '</div> ' +
                '<div class="btn-item-group"> ' +
                '<a class="btn go-buy" href="http://go.hupu.com/u?url={{detailPageUrl}}" title="去购买" target="_blank">去购买</a> ' +
                '<a class="btn not-like" title="不喜欢" href="javascript:;">不喜欢</a>' +
                '</div>';
        },
        // 模板函数
        _tpl: function (tpl, data) {
            return tpl.replace(/{{(.*?)}}/g, function ($1, $2) {
                var _result = data[$2] === undefined ? '0' : data[$2];
                return _result;
            });
        },
        // 通过cookie 获取dace的uid
        getVid: function () {
            var arr = document.cookie.match(new RegExp("(^| )_dacevid3=([^;]*)(;|$)"));
            if (arr != null) {
                return unescape(arr[2]);
            } else {
                return Math.floor(Math.random() * 1000000);
            }
        }
    };
    $.fn.fadeIn = function (speed, callback) {
        return this.animate({opacity: 'show'}, speed, function () {
            if (jQuery.browser.msie)
                this.style.removeAttribute('filter');
            if (jQuery.isFunction(callback))
                callback();
        });
    };

    $.fn.fadeOut = function (speed, callback) {
        return this.animate({opacity: 'hide'}, speed, function () {
            if (jQuery.browser.msie)
                this.style.removeAttribute('filter');
            if (jQuery.isFunction(callback))
                callback();
        });
    };
    daceWidget.loadCss('/css/trade/index/daceshuang11.css');
    $(function () {
        daceWidget.init();
    })

})();