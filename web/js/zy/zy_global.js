/**
 * Description hupu camp global
 * User: guwei
 * Update: 13-2-27 10:22
 */
(function (window, undefined) {
    var G = window.guwei = function (selector, context) {
            return new $.fn.constructor(selector, context);
        },
        quickExpr = /^(?:[^<]*(<[\w\W]+>)[^>]*G|#|$([\w\-]+)G)/,
        rclass = /[\n\t]/g;
    if (window.G === undefined)window.G = G;
    G.fn = G.prototype = {};
    G.fn.constructor.prototype = G.fn;
    G.$ = function (id) {
        return typeof id === 'object' ? id : document.getElementById(id)
    };
    G.$$ = function (t, p) {
        return p.getElementsByTagName(t)
    };
    G.$$$ = function (c, p) {
        p = p || document;
        if (document.getElementsByClassName) {
            return p.getElementsByClassName(c)
        } else {
            var results = [],
                nodes = document.getElementsByTagName('*'),
                l = nodes.length,
                pattern = new RegExp('(^|\\s)' + c + '(\\s|$)');
            while (--l >= 0)pattern.test(nodes[l].className) && results.unshift(nodes[l]);
            return results;
        }
    };
    G.hasClass = function (n, c) {
        return new RegExp('(^|\\s)' + c + '(\\s|$)').test(n.className)
    };
    G.addClass = function (n, c) {
        if(!G.hasClass(n,c))!n.className ? n.className = c : n.className += ' ' + c
    };
    G.removeClass = function (n, c) {
        var reg = RegExp('(^|\\s)' + c + '(\\s|$)');
        reg.test(n.className) && (n.className = n.className.replace(reg, ''))
    };
    G.getStyle = function (obj, name) {
        if (obj.currentStyle) {
            return obj.currentStyle[name];
        } else {
            return getComputedStyle(obj, false)[name];
        }
    };
    G.startMove = function (obj, json, fnEnd) {
        clearInterval(obj.timer);
        obj.timer = setInterval(function () {
            var bStop = true;
            for (var attr in json) {
                var cur = 0;
                if (attr == 'opacity') {
                    cur = Math.round(parseFloat(G.getStyle(obj, attr)) * 100);
                } else {
                    cur = parseInt(G.getStyle(obj, attr));
                }
                var speed = (json[attr] - cur) / 3;
                speed = speed > 0 ? Math.ceil(speed) : Math.floor(speed);
                if (cur != json[attr])bStop = false;
                if (attr == 'opacity') {
                    obj.style.filter = 'alpha(opacity=' + (cur + speed) + ')';
                    obj.style.opacity = (cur + speed) / 100;
                } else {
                    obj.style[attr] = cur + speed + 'px';
                }
            }
            if (bStop) {
                clearInterval(obj.timer);
                if (fnEnd)fnEnd();
            }
        }, 30)
    };
    G.listenEvent = function (t, y, h) {
        t.addEventListener ? t.addEventListener(y, h, false) : t.attachEvent('on' + y, h);
    };
    G.preventDefault = function (event) {
        event.preventDefault ? event.preventDefault() : event.returnValue = false;
    };
    return G
}(window));
/*
 * DOMReady
 * */
var DOM = (function () {
    var readyBound = false;
    var _DOM = {
        fn:[],
        bReady:false,
        push:function (fn) {
            _DOM.bind();
            if (_DOM.bReady) {
                fn.call(document);
            } else {
                _DOM.fn.push(fn);
            }
        },
        ready:function () {
            var fn, i = 0, fns;
            _DOM.bReady = true;
            fns = _DOM.fn;
            while ((fn = fns[ i++ ])) {
                fn.call(document);
            }
            fns = null;
        },
        bind:function () {
            if (readyBound) return;
            readyBound = true;
            if (/loaded|complete/.test(this.readyState)) {
                return setTimeout(_DOM.ready, 0);
            }
            if (document.addEventListener) {
                document.addEventListener("DOMContentLoaded", function () {
                    document.removeEventListener("DOMContentLoaded", arguments.callee, false);
                    _DOM.ready();
                }, false);
            } else if (document.attachEvent) {
                var bTop = false;
                try {
                    bTop = window.frameElement == null;
                } catch (e) {
                }
                if (document.documentElement.doScroll && bTop) {
                    (function () {
                        try {
                            document.documentElement.doScroll('left');
                        } catch (e) {
                            return setTimeout(arguments.callee, 0);
                        }
                        _DOM.ready();
                    })();
                }
            }
        }
    };
    return _DOM.push;
})();
/*
 * lazy load img
 * */
GDelay_img = (function () {
    var f = [],
        w = window,
        d = document,
        showTime = null,
        intTop,
        intLen,
        _attr = 'data-src',
        index = 0;

    function offset(o) {
        var y = o.offsetTop;
        if (o.style.position == 'absolute') return y;
        while (o = o.offsetParent) {
            y += o.offsetTop
        }
        return y
    }

    function scroll() {
        return d.body.scrollTop || d.documentElement.scrollTop
    }

    if (w.addEventListener) {
        w.attach = function (e, a, b, c) {
            e.addEventListener(a, b, c)
        }
    } else if (w.attachEvent) {
        w.attach = function (e, a, b, c) {
            e.attachEvent('on' + a, b)
        }
    }
    function wh() {
        return (w.innerHeight) ? w.innerHeight : (d.documentElement && d.documentElement.clientHeight) ? d.documentElement.clientHeight : d.body.offsetHeight
    }

    function isIMG(s) {
        var a = /\.jpg$|\.jpeg$|\.png$|\.bmp$|\.gif$/i,
            _s;
        _s = s.split('?')[0];
        return a.test(_s)
    }

    attach(w, 'scroll',
        function () {
            if (showTime === null) showTime = setTimeout(function () {
                    GDelay_img.lazyShow()
                },
                150)
        });
    attach(w, 'resize',
        function () {
            if (showTime === null) showTime = setTimeout(function () {
                    GDelay_img.lazyShow()
                },
                250)
        });
    attach(w, 'unload',
        function () {
            f = objTag = intLen = intTop = showTime = null
        });
    return {
        init:function (o) {
            var a = ((typeof(o) == 'object') ? o : d.getElementById(o)) || d,
                objTag = a.getElementsByTagName('*'),
                intLen = objTag.length,
                _a;
            while (intLen--) {
                _a = objTag[intLen];
                if (_a.nodeType != '1' || !(_a.attributes[_attr])) {
                    continue
                }
                var g = _a.attributes[_attr].nodeValue;
                if (g != null && g != undefined && g != '') {
                    intTop = offset(_a);
                    f[index] = {};
                    f[index].a = _a;
                    f[index].b = g;
                    f[index].c = intTop;
                    index++
                }
            }
            showTime = null;
            GDelay_img.lazyShow()
        },
        lazyShow:function () {
            if (showTime != null) {
                clearTimeout(showTime);
                showTime = null
            }
            var i = f.length;
            if (i == 0) return;
            while (i--) {
                var d = f[i];
                if (d == null || d == undefined) {
                    continue
                }
                var a = d.a,
                    b = d.b,
                    c = d.c,
                    h = wh(),
                    sh = scroll(),
                    it = parseInt(h) + parseInt(sh);
                if (isNaN(it)) {
                    d = null;
                    continue
                }
                if (c < it) {
                    if ((b.indexOf('(') != -1) && b.indexOf(')') != -1) {
                        (function () {
                            var a = b;
                            setTimeout(function () {
                                    (new Function('return ' + a))()
                                },
                                250)
                        })()
                    } else {
                        isIMG(b) ? (a.nodeName == 'IMG' ? (a.src = b) : (a.style.backgroundImage = 'url(' + b + ')')) : (a.className == '' ? (a.className = b) : a.className += ' ' + b)
                    }
                    a.removeAttribute(_attr);
                    if (i >= 0) {
                        f = f.slice(0, i).concat(f.slice(i + 1, f.length))
                    }
                    index = i
                }
            }
        }
    }
})();
/*
 * dialog
 * */
var Dialog = {
    init:function (config) {
        var dialog = {};
        config = config || {};
        var content = G.$(config.con) ? G.$(config.con) : config.con,
            time = config.time,
            b = document.body,
            e = document.documentElement,
            $window = window,
            pageH = Math.max(e.scrollHeight, b.clientHeight, b.clientHeight),
            mask,
            wrap
            ;
        if (time) {
            clearTimeout(dialog._timer);
            dialog._timer = setTimeout(function () {
                dialog.hidden();
            }, time);
        } else {
            b.onclick = function () {
                evt = arguments[0] || window.event;
                var target = evt.target || evt.srcElement;
                G.hasClass(target, 'hide-pop') && dialog.hidden();
            };
            e.onkeydown = function () {
                evt = arguments[0] || window.event;
                var keyCode = evt.keyCode;
                keyCode === 27 && dialog.hidden();
            };
        }
        dialog.show = function () {
            iframe = document.createElement('iframe');
            iframe.style.cssText = 'position:absolute; z-index:-1; width:100%;border:0;height:'+pageH+';height:e颅xpression(this.nextSibling.offsetHeight);top:0; top:e颅xpression(this.nextSibling.offsetTop);left:0; left:e颅xpression(this.nextSibling.offsetLeft);" frameborder="0"';
            b.appendChild(iframe);
            mask = document.createElement('div');
            b.appendChild(mask);
            mask.className = 'dialog-mask';
            wrap = document.createElement('div');
            b.appendChild(wrap);
            wrap.className = 'dialog-wrap';
            G.$(config.con) ? content.style.cssText = "" || wrap.appendChild(content) : wrap.innerHTML = '<div class="pro-msg">'+ content + '</div>';
            mask.style.cssText += 'position:absolute;top:0;left:0;display:block;height:' + pageH + 'px;width:100%;';
            wrap.style.cssText += 'position:absolute;';
            G.addClass(content, 'scale-visible');
            dialog.size();
            $window.onresize = function () {
                dialog.size();
            };
            $window.onscroll = function () {
                dialog.size();
            };
        };
        dialog.hidden = function () {
            G.$(config.con) ? content.style.cssText = "display:none;" : wrap.innerHTML = '';
            G.removeClass(content, 'scale-visible');
            G.$(config.con) && b.appendChild(content);
            b.removeChild(iframe);
            b.removeChild(mask);
            b.removeChild(wrap);
        };
        dialog.size = function () {
            var wrapH = content.offsetHeight || 116,
                wrapW = content.offsetWidth || 460,
                clientH = e.clientHeight || b.clientHeight,
                clientW = e.clientWidth || b.clientWidth,
                sTop = e.scrollTop || b.scrollTop;
            wrap.style.top = ((clientH - wrapH) * 382 / 1000 + sTop) + 'px';
            wrap.style.left = ((clientW - wrapW) / 2) + 'px';
        };
        return dialog;
    }
};
function addload(func) {
    var old = window.onload;
    if (typeof window.onload != "function") {
        window.onload = func;
    } else {
        window.onload = function () {
            old();
            func();
        }
    }
}
/*
 * Expanding array methods
 * */
Array.prototype.indexOf = function (val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) return i;
    }
    return -1;
};
Array.prototype.remove = function (val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};
/*
 *  publish topic
 * */
var addPopCamp = {
    arryCamp:[],
    init:function (detail) {
        var that = this,
            campOut = this.campOut = G.$('JaddForm'),
            campWrap = this.campWrap = G.$('JAddCamp'),
            AddedBox = this.AddedBox = G.$('JCampAdded'),
            campInput = this.campInput = G.$('JAddCampInput'),
            listBox = this.listBox = G.$$$('list-box', campWrap)[0],
            hiddenInput = this.hiddenInput = document.getElementsByName('object_ids')[0],
            oGuide = this.oGuide = G.$$$('add-post-guide', campOut)[0],
            oAdd = this.oAdd = G.$$$('command-add', campOut)[0],
            handel = this.handel = G.$$$('form-text-input',campOut)[0],
            iNow = 0, bAdd = true
            ;
        //detail.isIn=false;
        if(detail)that.selectItem(detail.objectFullname,detail.objectId,detail.objectName );
        //that.selectItem('濮氭槑', detail.objectId);
        that.changeInput(function () {
            that.onInput();
        });
        G.listenEvent(listBox, 'mouseover', function () {
            that.mouseSelect();
        });
        G.listenEvent(AddedBox, 'click', function () {
            var e = arguments[0] || window.event, target = e.srcElement || e.target;
            if (target.nodeName.toLowerCase() == 'a' && target.className == 'tag-close') {
                AddedBox.removeChild(target.parentNode);
                var id = target.parentNode.getAttribute('data-id');
                addPopCamp.arryCamp.remove(id);
            }
            hiddenInput.value = addPopCamp.arryCamp;
        });
        G.listenEvent(campWrap, 'keydown', function () {
            var e = arguments[0] || window.event, li = G.$$('li', that.listBox), len = li.length, i = 0;
            switch (e.keyCode) {
                case 38:
                    iNow--;
                    if (iNow < 0) {
                        iNow = len - 1;
                    }
                    that.each(iNow);
                    break;
                case 40:
                    iNow++;
                    if (iNow > len - 1) {
                        iNow = 0;
                    }
                    that.each(iNow);
                    break;
                case 13:
                    var bNull = campInput.value;
                    G.preventDefault(e);
                    if (!bNull&&!listBox.childNodes.length)return;
                    var name, id,fullName;
                    for (; i < len; i++) {
                        fullName = li[iNow].getAttribute('data-fullName');
                        id = li[iNow].getAttribute('data-id');
                        name = li[iNow].getAttribute('data-name');
                    }
                    that.selectItem(fullName, id,name);
                    break;
            }
        });
        //campInput
        G.listenEvent(campWrap, 'mouseout', function () { listBox.style.display = 'none' });
        G.listenEvent(campWrap, 'mouseover', function () { listBox.style.display = 'block' });
        G.listenEvent(oAdd, 'click', function () {
            var value = $("#JaddForm").find('form').serialize(),bZy=AddedBox.childNodes.length,action = G.$$('form',campOut)[0].getAttribute('action');
            if (bAdd&&bZy!==1) {
                $.post(action, value, function (data) {
                    // $(".dialog-wrap").attr("")
                    if (data.status == 0) {
                        window.location.href = "/new";
                        bAdd = false;
                    } else {
                        oGuide.innerHTML = data.msg;
                        oGuide.style.display = 'block';
                    }
                }, 'json');
            }else if(bZy===1){
                G.addClass(G.$$$('dialog-msg',campOut)[0],'h');
            }
        });
    },
    changeInput:function (fn) {
        var that = this;
        if (/msie/i.test(navigator.userAgent)) {
            that.campInput.attachEvent('onpropertychange', function () {
                fn();
            });
        } else {
            that.campInput.addEventListener("input", function (e) {
                fn();
            }, false);
        }
    },
    mouseSelect:function () {
        var that = this, cItem = G.$$('li', that.listBox),
            len = cItem.length,
            i = 0;
        for (; i < len; i++) {
            (function (j) {
                var fullName ,id,name;
                cItem[j].onclick = function () {
                    fullName = cItem[j].getAttribute('data-fullName');
                    id = cItem[j].getAttribute('data-id');
                    name = cItem[j].getAttribute('data-name');
                    that.selectItem(fullName, id,name);
                }
            })(i);
            G.listenEvent(cItem[i], 'mouseover', function () {
                e = arguments[0]||window.event;
                var target =e.target || e.srcElement ;
                if(target.nodeName.toLowerCase()=='li'){
                    target.className = "select-row hover";
                }
            });
            G.listenEvent(cItem[i], 'mouseout', function () {
                e = arguments[0]||window.event;
                var target =e.target || e.srcElement ;
                if(target.nodeName.toLowerCase()=='li'){
                    target.className = "select-row";
                }
            })
        }
    },
    onInput:function () {
        var that = this;
        var sInput = encodeURIComponent(that.campInput.value) || 1;
        var url = '/object/get?name=' + sInput + '';
        G.removeClass(G.$$$('dialog-msg',that.campOut)[0],'h');
        $.get(url, function (object) {
            that.listBox.innerHTML = ' ';
            var data = eval('(' + object.data + ')');
            var l = data.length, i = 0;
            if (l > 0) {
                for (; i < l; i++) {
                    (function (j) {
                        var li = document.createElement('li');
                        li.className = 'select-row';
                        li.setAttribute('data-name', data[j].name);
                        li.setAttribute('data-id', data[j].object_id);
                        li.setAttribute('data-fullName', data[j].fullname);
                        li.innerHTML = '<img class="camp-avatar" src="' + data[j].logo + '" alt="logo"> <span class="camp-name"><i class="h">' + data[j].fullname + '</i><small>(@'+data[j].name+')</small></span>';
                        that.listBox.appendChild(li);
                    })(i);
                }
            }
        }, 'json');
    },
    selectItem:function (itemFullname, iteId,iteName) {
        var that = this;
        (function(){
            var tag = document.createElement('span');
            tag.className = 'addedTag';
            tag.setAttribute('data-id', iteId);
            tag.innerHTML = '<b class="tit">' + iteName + '</b><a href="javascript:" class="tag-close">x</a>';
            that.AddedBox.appendChild(tag);
            that.campInput.value = '';
        })();
        that.listBox.innerHTML = '';
        addPopCamp.arryCamp.push(iteId);
        that.hiddenInput.value = addPopCamp.arryCamp;
    },
    each:function (iNow) {
        var that = this, item = that.item = G.$$('li', that.listBox), l = item.length, i = 0;
        for (; i < l; i++) {
            for (; i < l; i++) {
                item[i].className = 'select-row';
            }
            item[iNow].className = 'select-row hover';
        }
    }
};
/*
 * article respond method
 * */
function respond() {
    var resWrap = G.$('JRespond');
    if (!resWrap)return;
    var resBtn = G.$$$('comment-reply', resWrap)[0];
    G.listenEvent(resBtn, 'click', function () {
        e = arguments[0] || window.event;
        G.preventDefault(e);
        var value = $("#JRespond").find('form').serialize(),action = G.$$('form',resWrap)[0].getAttribute('action');
        $.post(action, value, function (data) {
            if (data.status == 0) {
                window.location.reload();
            } else {
                alert(data.msg)
            }
        }, 'json');
    })
}
/*
 * in && out group
 * */
var CampItem = {
    init : function(){
        var that = this,campWrap = this.campWrap= G.$('JCampWrap');
        that.hoverCamp();
        that.inOut();
    },
    inOut: function(){
        var that = this,inOutBtn = G.$$$('turn-btn',that.campWrap),l=inOutBtn.length,i=0;
        if(!inOutBtn)return;
        for(;i<l;i++){
            G.listenEvent(inOutBtn[i],'click',function(){
                e = arguments[0]||window.event;
                var target =e.target || e.srcElement ;
                if(target.getAttribute('onclick'))return;
                var link = target+'',turnLink;
                G.preventDefault(e);
                $.get(link,function(data){
                    if(data.status==0){
                        if(G.hasClass(target,'d')){
                            G.startMove(target.parentNode,{opacity:0},function(){
                                target.parentNode.parentNode.removeChild(target.parentNode);
                                that.hoverCamp();
                            });
                        }else{
                            target.className == 'j turn-btn'?turnLink = link.replace(/quit/,'join'):turnLink = link.replace(/join/,'quit');
                            target.className == 'q turn-btn'?target.className = 'j turn-btn':target.className = 'q turn-btn';
                            target.href = turnLink;
                        }
                    }
                },'json');
            });
        }
    },
    /*verifyPass:function(){
        var that = this,passBtn = G.$$$('pass-btn',that.campWrap),l=passBtn.length,i=0;
        if(!passBtn)return;
        for(;i<l;i++){
            G.listenEvent(passBtn[i],'click',function(){
                    e = arguments[0]||window.event;
                    var target =e.target || e.srcElement ;
                    if(target.getAttribute('onclick'))return;
                var link = target+'';
                G.preventDefault(e);
                $.get(link,function(data){
                    if(data.status==0){
                        G.startMove(target.parentNode,{opacity:0},function(){
                            target.parentNode.parentNode.removeChild(target.parentNode);
                            that.hoverCamp();
                        });
                        var msg =  Dialog.init({con:data.msg,time:2000});
                        msg.show();
                    }
                },'json')
            });
        }
    },*/
    hoverCamp:function(){
        var aCampItem =  G.$$$('item');
        if(!aCampItem)return;
        var l = aCampItem.length,
            i=0;
        for(;i<l;i++){
            aCampItem[i].index = i;
            aCampItem[i].onmouseover=function(){
                aCampItem[this.index].className = 'item hover'
            };
            aCampItem[i].onmouseout=function(){
                aCampItem[this.index].className = 'item'
            };
        }
    }
};

/*
 * compatible html5 placeholder
 * */
var PlaceHolder = {
    _support:(function () {
        return 'placeholder' in document.createElement('input');
    })(),
    init:function () {
        if (!PlaceHolder._support) {
            var inputs = document.getElementsByTagName('input');
            var textarea = document.getElementsByTagName('textarea');
            PlaceHolder.create(inputs);
            PlaceHolder.create(textarea);
        }
    },
    create:function (inputs) {
        var input;
        if (!inputs.length) {
            inputs = [inputs];
        }
        for (var i = 0, length = inputs.length; i < length; i++) {
            input = inputs[i];
            if (!PlaceHolder._support && input.attributes && input.attributes.placeholder) {
                PlaceHolder._setValue(input);
                input.attachEvent('onfocus', function () {
                    var target = window.event.srcElement;
                    if (target.value === target.attributes.placeholder.nodeValue) {
                        target.value = '';
                        G.addClass(target, 'input-focus');
                    }
                });
                input.attachEvent('onblur', function () {
                    var target = window.event.srcElement;
                    if (target.value === '') {
                        PlaceHolder._setValue(target);
                    }
                });
            }
        }
    },
    _setValue:function (input) {
        input.value = input.attributes.placeholder.nodeValue;
        G.removeClass(input, 'input-focus');
    }
};
/*
 * article select avatar
 * */
function changeAvatar() {
    var list = G.$('JcampSelect');
    if (!list)return;
    linkSrc();
    G.listenEvent(list, 'change', function () {
        linkSrc();
    });
    function linkSrc(){
        var avatar = G.$('JCampAvatar');
        var avatarSrc = list.options[list.selectedIndex].getAttribute('data');
        avatar.src = avatarSrc;
    }
}

/*
 * async window load
 * */
addload(function () {
    GDelay_img.init(document);
});
/*
 * async domload
 * */
DOM(function () {
    PlaceHolder.init();
    respond();
    changeAvatar();
    CampItem.init();
    /*Determine whether the login*/
    if (G.$('JPubPost') && !(G.$('JPubPost').getAttribute('onclick'))) {
        G.listenEvent(G.$('JPubPost'), 'click', function () {
            e = arguments[0] || window.event;
            var target = e.target || e.srcElement,fullname =target.getAttribute('data_object_fullname'),name =target.getAttribute('data_object_name'),id=target.getAttribute('data_object_id');
            var addFrom = Dialog.init({con:JaddForm});
            addFrom.show();
            if(id){
                addPopCamp.init({isIn:true,objectFullname:fullname,objectId:id,objectName:name});
            }else{
                addPopCamp.init();
            }
        });
    }
  // goTop.init({obj:'goTopBtn'});
    /*thirdNav searcg*/
    (function(){
        var thirdNav = G.$('thirdNav');
        var sInput =  G.$$$('it-search',thirdNav)[0];
        if(!sInput)return;
        G.listenEvent(sInput,'focus',function(){
            e = arguments[0] || window.event;
            var target = e.target || e.srcElement;
            G.addClass(target.parentNode,'camp-search-hover')
        });
        G.listenEvent(sInput,'blur',function(){
            e = arguments[0] || window.event;
            var target = e.target || e.srcElement;
            G.removeClass(target.parentNode,'camp-search-hover')
        })
    })()
});


// 消息
$(function(){
    // 点击判断登录
    var number_url = 'http://zy.hupu.com/notification/numbers',
        my_msg_url = 'http://zy.hupu.com/notification/person',
        zy_msg_url = 'http://zy.hupu.com/notification/object',
        msgLoadPos = 1;

    function getMessage(){
        $.ajax({
            url: number_url,
            // url:'test/numbers.json',
            success:function(data){
                //TODO 判断登录 未登录不显示消息窗口
                var data = $.parseJSON(data),
                    person_msg = data.person,
                    zy_msg = data.object,
                    total = person_msg + zy_msg;
                switch (true){
                    // 判断登录
                    case data.status == '-1':
                        break;
                    case total <= 99 && total > 0:
                        $('#J_msgs').html(total);
                        $('#J_msgs').show();
                        break;
                    case total > 99:
                        $('#J_msgs').html('99+');
                        $('#J_msgs').show();
                        break;         
                    default:
                        $('#J_msgs').hide();
                        break; 
                };
                if(person_msg <= 0 && zy_msg > 0){
                    msgLoadPos = 2
                };
                if(person_msg > 0){
                    if($('#J_mymsg span').length > 0){
                        $('#J_mymsg span').html('('+ person_msg +')');
                    }else{
                         $('#J_mymsg').append('<span>('+ person_msg +')</span>');
                    }
                }else{
                    $('#J_mymsg span').hide();
                };
                if(zy_msg > 0){
                    if($('#J_zymsg span').length > 0){
                        $('#J_zymsg span').html('('+ zy_msg +')');
                    }else{
                         $('#J_zymsg').append('<span>('+ zy_msg +')</span>');
                    }
                }else{
                    $('#J_zymsg span').hide();
                }

            }
        })
    }
    function getOtherMessage(url,elem){
        var html = '';
        getMessage();
        $(elem).html('<div class="fn-loading"></div>');
        $.ajax({
            url: url,
            // url:'test/mymsg.json',
            success:function(data){
                data = $.parseJSON(data);
                $('#J_msgbox .fn-loading').hide();
                if(data['new'].length > 0 && data['old'].length > 0){
                    html += '<div class="no-msg">对不起，还没有您的消息...</div>';
                }
                if(data['new'].length > 0){
                    $(data['new']).each(function(index, item){
                        html += '<p>'+ item.msg +'</p>';
                    })
                }
                if(data['old'].length > 0){
                    $(data['old']).each(function(index, item){
                        html += '<p class="old">'+ item.msg +'</p>';
                    })
                }
                $(elem).html(html);
            }
        });
    }
    getMessage();
    /**
     * 每30s 去接口请求数据 
     * TODO 当用户打开消息窗口就停止自动请求
     */
    setInterval(getMessage,30000);

    /**
     * 点击显示消息框体，点击外面则消息框消失
     */
    $('#J_zy-msg a').click(function(){
        $('#J_msgbox').fadeToggle('fast');
        if(msgLoadPos == 2){
            $('#J_zy-msg .tab-nav').removeClass('cur');
            $('#J_zy-msg .tab-nav').eq(0).addClass('cur');
            $('#J_zy-msg .msglist').hide();
            $('#J_zymsglist').show();
            getOtherMessage(zy_msg_url,'#J_zymsglist');
            return false
        };
        getOtherMessage(my_msg_url,'#J_mymsglit')
       
    });
    $('#JCampWrap, .hp-header').click(function(){
        $('#J_msgbox').fadeOut('fast');
    });



    /**
     * Tab切换
     * @type {[type]}
     */
    var msg_content = $('#J_zy-msg .msglist');
    $('#J_zy-msg .tab-nav').live('click', function(ev){
        $('#J_zy-msg .tab-nav').addClass('cur');
        $(this).removeClass('cur');
        if($(this).attr('tagName').toUpperCase() == 'DD'){
            $(msg_content[1]).show();
            $(msg_content[0]).hide();
            getOtherMessage(zy_msg_url,'#J_zymsglist')
        }else{
            $(msg_content[0]).show();
            $(msg_content[1]).hide();
            getOtherMessage(my_msg_url,'#J_mymsglit')
        }
    })
    
});


// 点亮效果
$(function(){
    function tpl(el){
        var template = '<div id="j_tip" class="tips_up_pop" style="position:fixed;_position:absolute;left:50%;margin-left:-150px;top:100px;">' + el + '</div>';
        return template;
    }
    // 取消A标签默认效果
    $('.camp-main em a').click(function(ev){
        ev.preventDefault();
        var obj = ev.target,
            data_url = obj.href;
        $.ajax({
            url: data_url,
            // url: 'test/light.json',
            cache: false,
            success:function(d){
                var d = $.parseJSON(d),
                    status = d.status,
                    msg = d.msg,
                    msg_count = d.light_count,
                    html = '';
                // 如果点亮成功
                if(status == 0){
                    // 计数器+1
                    var num_obj = $(obj).parent('em').next('span');
                    $(num_obj).html('('+ msg_count +')');
                    // 消息提示成功
                    doWarning(msg)
                }else{
                    // 消息提示失败原因
                    doWarning(msg)
                }
            }
        });
    });
})

// 选择立场
$(function(){
    
    function putData(team_id){
        var item_id = 'cm' + team_id;
        item_img = $('#' + item_id + ' img').attr('data_img_big'),
        // item_img = $('#' + item_id + ' img').attr('src'),
        item_fullname = $('#' + item_id + ' .fullname').html();
        $('#JCampAvatar').attr('src', item_img);
        $('#J_inputcamp').val(item_fullname);
        // id 写入input
        $('#J_object_id').val(team_id);
    }

    $('#J_inputcamp').bind('focus blur', function(ev){
        if(ev.type == 'focus'){
            $('#J_camp-list').fadeIn('fast');
        }
        if(ev.type == 'blur'){
            $('#J_camp-list').fadeOut('fast');
        }
    })
    // 文字全选
    try{
        if(window.curr_select_object_id){
            putData(curr_select_object_id);
        }
    }catch(e){};
    $('#J_inputcamp').click(function(){
        // 文字全选
        $(this).select();
        $('#J_camp_list').show();
    });
    $('#J_inputcamp').bind('input propertychange', function(ev){
        // 显示浮层
        $('#J_camp_list').fadeIn('fast');
        var input_value = encodeURIComponent($(this).val()),
            // html = '';
            html = '<li id="cm0"><img data_img_big="/images/zy/avatat_default.jpg" src="/images/zy/avatat_default.jpg" height="20" alt=""> <em><span class="fullname">中立</span></em></li>';
        if(input_value.length){
            $.ajax({
                url: 'http://zy.hupu.com/object/get?name=' + input_value,
                // url:'test/camp.json?key=' + input_value,
                success:function(d){
                    var d = $.parseJSON(d),
                        teams = $.parseJSON(d.data);
                    $(teams).each(function(index, item){
                        html += '<li id="cm'+ item.object_id +'"><img data_img_big="' + item.big_logo + '" src="'+ item.logo +'" height="20" alt=""> <em><span class="fullname">'+ item.fullname + '</span> (@<span class="shortname">' + item.name +'</span>)</em></li>';
                    });
                    $('#J_cmlist').html(html);
                }
            })
        }
        
    })
    $('#J_cmlist li').live('click', function(){
        var item_id = this.id,
            team_id = (item_id.substr(2, (item_id.length - 1)) - 0);
            putData(team_id);
        
            // 隐藏浮层
            $('#J_camp_list').fadeOut('fast');
    })
    // TODO 不是选择的输入不予提交
    //if(parseInt($.browser.version) > 8){
        $('body').click(function(ev){
            if(ev.target.id != 'J_inputcamp')
            $('#J_camp_list').hide();
        });
   // }
    
    
})


function doWarning(str){
    $("#j_tip").remove();
    $("body").prepend('<div id="j_tip" class="tips_up_pop" style="display:none"><div id="j_tip_t"><div id="tips_pop">'+str+'</div></div></div>');
    var TL=popTL("#j_tip");
    $("#j_tip").css({
        top:TL.split("|")[0]+"px",
        left:TL.split("|")[1]+"px"
        });
    $('#j_tip').show();
    setTimeout("$('#j_tip').fadeOut(426);",2130);
};
function popTL(a){
    var b=document.body.scrollTop+document.documentElement.scrollTop, 
    sl=document.documentElement.scrollLeft,
    ch=document.documentElement.clientHeight,
    cw=document.documentElement.clientWidth,
    objH=300,objW=$(a).width(),objT=Number(b)+(Number(ch)-Number(objH))/2,objL=Number(sl)+(Number(cw)-Number(objW))/2;
    return objT+"|"+objL;
};