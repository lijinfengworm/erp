var _dace_sh_biz = (function() {
    var sid = 'WL_biz_sh_m';
    var vid3 = '__dacevid3';
    var vst = '__dacemvst';

    /**
     * 判断浏览器类型和版本
     * @name hp.browser
     *
     */
    var _userAgent = navigator.userAgent.toLowerCase();
    var browser = {
        version: (_userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
        chrome: /chrome/.test(_userAgent),
        safari: /webkit/.test(_userAgent),
        opera: /opera/.test(_userAgent),
        msie: /msie/.test(_userAgent) && !/opera/.test(_userAgent),
        mozilla: /mozilla/.test(_userAgent) && !/(compatible|webkit)/.test(_userAgent),
        mobile: /Mobile/i.test(_userAgent),
        ios: /\(i[^;]+;( U;)? CPU.+Mac OS X/i.test(_userAgent),
        iphone: /iphone/i.test(_userAgent),
        ipad: /ipad/i.test(_userAgent),
        android: /android/i.test(_userAgent) || /Linux/i.test(_userAgent)
    };

    var He = function(a, b) {
        return a.onload = b;
    }

    var sendImage = function(uri) {
        var host = '//ccdace.hupu.com/_dace.gif?';
        var d = new Image(1, 1);
        d.src = host + uri;
        He(d, function() {
            He(d, '');
        });
    }

    var CookieUtil = {
        /**
         * 获取cookie的值,不对值进行解码
         * @name hp.cookie.getRaw
         * @param {string}   key       目标参数
         */
        getRaw: function(key) {
            if (CookieUtil._isValidKey(key)) {
                var reg = new RegExp("(^| )" + key + "=([^;]*)(;|\x24)"),
                        result = reg.exec(document.cookie);
                if (result) {
                    return result[2] || null;
                }
            }
            return null;
        },
        _isValidKey: function(key) {
            return (new RegExp("^[^\\x00-\\x20\\x7f\\(\\)<>@,;:\\\\\\\"\\[\\]\\?=\\{\\}\\/\\u0080-\\uffff]+\x24")).test(key);
        }
    };
    //事件处理
    var _win = window, _doc = document;
    var EventUtil = {
        /**
         * DOM就绪时执行的函数
         * @name hp.ready
         * @param {object} func
         */
        ready: function(func) {
            var _readyList = [];
            _readyList.push(func);
            if (browser.msie) {
                EventUtil.removeHandler(_doc, "readystatechange", _DOMContentLoaded);
                EventUtil.addHandler(_doc, "readystatechange", _DOMContentLoaded);
            } else {
                EventUtil.addHandler(_win, "DOMContentLoaded", _DOMContentLoaded);
            }

            function _DOMContentLoaded() {
                if (browser.msie) {
                    if (_doc.readyState === "complete" || _doc.readyState === "interactive") {
                        EventUtil.removeHandler(_doc, "readystatechange", _DOMContentLoaded);
                        _startReady();
                    }
                } else {
                    EventUtil.removeHandler(_win, "DOMContentLoaded", _DOMContentLoaded);
                    _startReady();
                }

            }

            function _startReady() {
                for (var i = 0, len = _readyList.length; i < len; i++) {
                    setTimeout(_readyList[i], 25);
                }

            }
            ;
        },
        addHandler: function(elem, type, handler) {
            var guid = 1;
            if (window.addEventListener) {
                elem.addEventListener(type, handler, false);
                return;
            }
            if (!guid)
                handler.guid = guid++;
            if (!elem.events)
                elem.events = {};
            var handlers = elem.events[type];
            if (!handlers) {
                handlers = elem.events[type] = {};
                if (elem["on" + type]) {
                    handlers[0] = elem["on" + type];
                }
            }
            handlers[handler.guid] = handler;
            elem["on" + type] = _handleEvent;
            /**
             * 执行事件
             * @param {Object} event
             */
            function _handleEvent(event) {
                var event = event || window.event;
                var handles = this.events[event.type];
                for (var i in handles) {
                    handles[i].call(this, event);
                }
            }
        },
        removeHandler: function(element, type, handler) {
            if (element.removeEventListener) {
                element.removeEventListener(type, handler, false);
            } else if (element.detachEvent) {
                element.detachEvent("on" + type, handler);
            } else {
                element["on" + type] = null;
            }
        },
        getEvent: function(event) {
            return event ? event : window.event;
        },
        getTarget: function(event) {
            return event.target || event.srcElement;
        }
    };

    var generateUrl = function(act, link) {
        var date = new Date();
        var url = 'et=wap_event&act=' + act + '&sid=' + sid + '&vid=' + CookieUtil.getRaw(vid3) + '&vst=' + CookieUtil.getRaw(vst) + '&link=' + encodeURIComponent(link) + '&url=' + encodeURIComponent(window.location.href) + '&vtm=' + date.getTime() + '&q=' + Math.random() * 1000000;
        return url;
    }

    var clickRequestFun = function() {
        EventUtil.ready(function() {
            EventUtil.addHandler(document.body, "click", function(event) {
                var e = EventUtil.getEvent(event);
                var target = EventUtil.getTarget(e);
                var nodeName = target.nodeName.toLowerCase();
                while ('a' != nodeName && 'body' != nodeName) {
                    target = target.parentElement;
                    if (!target) {
                        break;
                    }
                    nodeName = target.nodeName.toLowerCase();
                }
                if (target != undefined && target) {
                    var a = target.nodeName.toLowerCase();
                    var link = target.href;
                    //判断是否是链接，是发送请求
                    if (a === 'a' && link != undefined && link != '') {
                        var uri = generateUrl('c', link);
                        if (uri)
                            sendImage(uri);
                    }
                }
            });
        });
    }

    clickRequestFun();

})(window)