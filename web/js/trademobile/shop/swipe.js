;(function() {
    
    var win = window;
    var winWidth = win.innerWidth, winHeight = win.innerHeight;
    var supportTransform3D = false;
    
    (function() {
        
        var doc = document, head = doc.head, docElem = doc.documentElement;
        var testElem = doc.createElement('div'), style = testElem.style;
        var supportTransform = style['webkitTransform'] != undefined;
        var has3D = style['webkitPerspective'] != undefined;
        if (has3D) {
            testElem.id = 'test3d';
            style = doc.createElement('style');
            style.textContent = '@media(-webkit-transform-3d){#test3d{height:3px}}';
            head.appendChild(style);
            
            docElem.appendChild(testElem);
            has3D = testElem.offsetHeight == 3;
            head.removeChild(style);
            docElem.removeChild(testElem);
        }
        ;
        
        supportTransform3D = has3D;
    
    })();
    
    var SwipeCard = win.SwipeCard = function(config) {
        this.init(config);
    };
    
    SwipeCard.prototype = {
        container: null,
        cards: null,
        noBackSwipePage: 1,
        
        cardWidth: 0,
        cardHeight: 0,
        showMaxCount: 3,
        showIndex: 0,
        pageNumber: 0,
        
        isAndroid: false,
        
        offsetY: 5,
        offsetScale: 0.06,
        transitionDuration: 300,
        swipeOutDistance: 80,
        swipeInDistance: 10,
        
        init: function(config) {
            var me = this;
            
            for (var p in config)
                me[p] = config[p];
            
            if (me.isAndroid) {
                me.swipeInDistance = 5;
            }
            //init container
            var container = me.container;
            if (typeof (container) === 'string')
                container = $(container);
            me.container = container;
            container.addEventListener('touchstart', function(e) {
                var obj = getTouch(e);
                
                me.startX = obj.x;
                me.startY = obj.y;
                
                me.touchEnd = false;
            }, false);
            
            container.addEventListener('touchmove', function(e) {
                var obj = getTouch(e);
                
                var dx = obj.x - me.startX;
                var dy = obj.y - me.startY;
                var isHorizontal = Math.abs(dx) > Math.abs(dy);
                //开始为水平，此次touch记为一直水平
                if (isHorizontal) {
                    this.isHorizontal = true;
                }
                
                if (isHorizontal && dx < 0) {
                    me._moveCard(dx);
                }
                if (this.isHorizontal) {
                    e.preventDefault();
                }
            }, false);
            
            container.addEventListener('touchend', function(e) {
                var obj = getTouch(e);
                
                var dx = obj.x - me.startX;
                var dy = obj.y - me.startY;
                var isHorizontal = me.isAndroid && Math.abs(dx) > 0 ? true : Math.abs(dx) >= Math.abs(dy);
                if (isHorizontal && dx <= -me.swipeInDistance) {
                    me.swipeLeft = true;
                    me._swipeOut();
                } else if (isHorizontal && dx >= me.swipeOutDistance) {
                    me.swipeLeft = false;
                    if (me.pageNumber !== me.noBackSwipePage) { //&& me.showIndex !== 0
                        me._swipeIn();
                    }
                } else {
                    me._bounceBack();
                }
                //reset
                this.isHorizontal = false;
            }, false);

            //init card card list
            me.cards = [];
            var cards = config.cards;
            if (cards)
                me.addCard(cards);

            //init card position states
            var states = me._cardstates = [], count = me.showMaxCount;
            for (var i = 0; i < count; i++) {
                states[i] = {
                    x: (i * me.offsetScale * me.cardWidth) * 0.5,
                    y: -i * me.offsetScale * me.cardHeight + me.offsetY * (count - 1 - i),
                    scaleX: 1 - i * me.offsetScale,
                    scaleY: 1 - i * me.offsetScale
                };
            //                console.info(states[i]);
            }
            if (me.showIndex >= 0 && me.cards.length > 0)
                me.showCardAt(me.showIndex);
        },
        
        addCard: function(cards) {
            cards = cards instanceof Array ? cards : [cards];
            
            var me = this;
            for (var i = 0, len = cards.length; i < len; i++) {
                var card = cards[i];
                me.cards.push(card);
                //                console.info(i,card.getElementsByClassName("shop-name")[0] && card.getElementsByClassName("shop-name")[0].innerHTML);
                
                me._setCardTransition(card);
            }
        },
        
        beforeAddCard: function(cards) {
            cards = cards instanceof Array ? cards : [cards];
            
            var me = this;
            for (var len = cards.length - 1, i = len; i >= 0; i--) {
                var card = cards[i];
                me.cards.splice(0, 0, card);
                
                me._setCardTransition(card);
            }
        },
        
        disableSwipe: function() {
            this.container.style.pointerEvents = "none";
        },
        
        enableSwipe: function() {
            this.container.style.pointerEvents = "auto";
        },
        
        removeCard: function(card) {
            var me = this, cards = me.cards;
            
            for (var i = 0; i < cards.length; i++) {
                var c = cards[i];
                if (c === card) {
                    cards.splice(i, 1);
                    if (c.parentNode)
                        c.parentNode.removeChild(c);
                    return;
                }
            }
        },
        
        removeAllCards: function() {
            var me = this;
            
            me.showIndex = -1;
            me.cards.length = 0;
            me.container.innerHTML = '';
        },
        
        showCardAt: function(index, withAnimation, dofireEvent) {
            var me = this, cards = me.cards, len = cards.length, container = me.container;
            var count = Math.min(me.showMaxCount, len), i = 0, card, showCards = [];
            
            index = index >= len ? 0 : index;
            me.showIndex = index;
            me.showCount = count;
            while (i < count) {
                card = cards[index];
                
                card.style.zIndex = count - i;
                card.style.opacity = 1 - i * 0.2;
                
                if (!card.parentNode)
                    container.appendChild(card);
                card.offsetWidth;
                
                card.style.webkitTransition = withAnimation ? '-webkit-transform ' + me.transitionDuration + 'ms ease-out' : '';
                setTransformCSS(card, me._cardstates[i]);
                setTransformOrigin(card, false);
                showCards[i] = card;
                
                index = index + 1 >= len ? 0 : index + 1;
                i++;
            }
            container.offsetWidth;
            //remove old cards
            i = container.childNodes.length - count; //最后三张不需要检查，所以减去
            while (--i >= 0) {
                var child = container.childNodes[i];
                if (showCards.indexOf(child) === -1 && child !== me._swipeOutCard) {
                    container.removeChild(child);
                }
            }
            if (!dofireEvent) {
                fireEvent(me.container, me, 'cardChange', {
                    cardIndex: index,
                    swipeLeft: me.swipeLeft,
                    touchCard: me._swipeOutCard
                });
            }
        },
        
        _setCardTransition: function(card) {
            var me = this;
            card.style.webkitTransformOrigin = '0 100% 0';
            card.addEventListener('webkitTransitionEnd', function(e) {
                var target = e.target, parent = target.parentNode;
                target.style.webkitTransition = '';
                var zIndex = target.style.zIndex;
                //target.tagName !== "SECTION",card本身在界面的缩放会触发这个事件
                if (parent && target.tagName !== "SECTION" && (target === me._swipeOutCard || zIndex < 1 || zIndex > me.showCount)) {
                    parent.removeChild(target);
                }
            });
        },
        
        _moveCard: function(deltaX, withAnimation) {
            var me = this, cards = me.cards, len = cards.length;
            var count = me.showCount, index = me.showIndex, i = 0, card, state;
            while (i < count) {
                card = cards[index];
                state = me._cardstates[i];
                card.style.webkitTransition = withAnimation ? '-webkit-transform ' + me.transitionDuration + 'ms ease-out' : '';
                var rotation = (count - i) * (count - 1 - i) * Math.min(deltaX * 0.01, 20);
                setTransformCSS(card, {
                    x: -(state.x + Math.max(0, (count - i) * (count - 1 - i) * Math.min(deltaX * 0.01, 20))),
                    y: state.y,
                    scaleX: state.scaleX,
                    scaleY: state.scaleY,
                    rotation: rotation
                });
                
                setTransformOrigin(card, true);

                //save last rotation of first card
                if (i == count - 1)
                    me._lastRotation = rotation;
                
                index = index + 1 >= len ? 0 : index + 1;
                i++;
            }
        },
        
        _bounceBack: function() {
            var me = this, cards = me.cards, len = cards.length;
            var count = me.showCount, index = me.showIndex, i = 0, card, state;
            
            while (i < count) {
                card = cards[index];
                state = me._cardstates[i];
                card.style.webkitTransition = '-webkit-transform ' + me.transitionDuration + 'ms ease-out';
                setTransformCSS(card, {
                    x: state.x,
                    y: state.y,
                    scaleX: state.scaleX,
                    scaleY: state.scaleY
                });
                setTransformOrigin(card, false);
                index = index + 1 >= len ? 0 : index + 1;
                i++;
            }
        },
        
        _swipeOut: function() {
            var me = this, cards = me.cards, len = cards.length;
            var count = me.showCount, index = me.showIndex, i = 0, card, state;

            //move out first card
            card = me._swipeOutCard = cards[index];
            
            state = me._cardstates[0];
            card.style.zIndex = count + 1;
            card.style.webkitTransition = '-webkit-transform ' + me.transitionDuration + 'ms ease-out';
            setTransformCSS(card, {
                x: -winWidth,
                y: state.y,
                rotation: me._lastRotation || 0
            });
            
            me.showCardAt(index + 1, true);
        },
        
        _swipeIn: function() {
            var me = this, cards = me.cards, len = cards.length;
            var count = me.showCount, index = me.showIndex, i = 0, card, state;

            //move in last card
            //            i = (index + me.showCount - 1) % len;
            //            card = cards[i];
            //
            //            state = me._cardstates[me.showCount - 1];
            //            card.style.zIndex = 0;
            //            card.style.webkitTransition = '-webkit-transform '+ me.transitionDuration +'ms ease-out';
            //            setTransformCSS(card, {
            //                x: winWidth,
            //                y: state.y,
            //                scaleX: state.scaleX,
            //                scaleY: state.scaleY
            //            });
            me._swipeOutCard = null; //FIXME: 不加这个会造成第一条被删除
            //move out previous card
            index = index > 0 ? index - 1 : len - 1;
            card = cards[index];
            state = me._cardstates[0];
            setTransformCSS(card, {
                x: -winWidth,
                y: state.y,
                rotation: me._lastRotation || 10
            });
            me.showCardAt(index, true);
        },
        
        _onTouchStart: function(e) {
            var me = this, obj = getTouch(e);
            
            me.startX = obj.x;
            me.startY = obj.y;
            
            me.touchEnd = false;
        },
        
        _onTouchMove: function(e) {
            var me = this, obj = getTouch(e);
            
            var dx = obj.x - me.startX;
            var dy = obj.y - me.startY;
            var isHorizontal = Math.abs(dx) > Math.abs(dy);
            //开始为水平，此次touch记为一直水平
            if (isHorizontal) {
                this.isHorizontal = true;
            }
            
            if (isHorizontal && dx < 0) {
                me._moveCard(dx);
            }
            if (this.isHorizontal) {
                e.preventDefault();
            }
        },
        
        _onTouchEnd: function(e) {
            var me = this, obj = getTouch(e);
            
            var dx = obj.x - me.startX;
            var dy = obj.y - me.startY;
            var isHorizontal = Math.abs(dx) > Math.abs(dy);
            if (isHorizontal && dx <= -me.swipeInDistance) {
                me.swipeLeft = true;
                me._swipeOut();
            } else if (isHorizontal && dx >= me.swipeOutDistance) {
                me.swipeLeft = false;
                if (me.pageNumber !== me.noBackSwipePage) { //&& me.showIndex !== 0
                    me._swipeIn();
                }
            } else {
                me._bounceBack();
            }
            //reset
            this.isHorizontal = false;
        },
        
        updatePageIndex: function(pageNumber) {
            this.pageNumber = pageNumber;
        },
        
        addEventListener: function(type, listener, useCapture) {
            this.container.addEventListener(type, listener, useCapture);
        },
        
        removeEventListener: function(type, listener, useCapture) {
            this.container.removeEventListener(type, listener, useCapture);
        }
    };
    
    function getTouch(e) {
        var touches = e.touches, changedTouches = e.changedTouches;
        var touch = (touches && touches.length) ? touches[0] : 
        (changedTouches && changedTouches.length) ? changedTouches[0] : null;
        var x = touch.pageX || touch.clientX, y = touch.pageY || touch.clientY;
        return {touch: touch,x: x,y: y};
    }
    
    function $(selector, selectAll, owner) {
        owner = owner || document;
        return selectAll ? owner.querySelectorAll(selector) : owner.querySelector(selector);
    }
    
    function setTransformCSS(elem, obj, swipe) {
        elem.style.webkitTransform = getTransformCSSValue(obj);
    }
    
    function setTransformOrigin(elem, swipeLeft) {
        elem.style.webkitTransformOrigin = swipeLeft ? '100% 100% 0' : '0 100% 0';
    
    }
    
    function getTransformCSSValue(obj) {
        obj.x = obj.x || 0;
        obj.y = obj.y || 0;
        obj.pivotX = obj.pivotX || 0;
        obj.pivotY = obj.pivotY || 0;
        obj.rotation = obj.rotation || 0;
        obj.scaleX = obj.scaleX || 1;
        obj.scaleY = obj.scaleY || 1;
        
        var use3d = supportTransform3D, str3d = use3d ? '3d' : '';
        return 'translate' + str3d + '(' + (obj.x - obj.pivotX) + 'px, ' + (obj.y - obj.pivotY) + (use3d ? 'px, 0)' : 'px)') 
        + 'rotate' + str3d + (use3d ? '(0, 0, 1, ' : '(') + obj.rotation + 'deg)' 
        + 'scale' + str3d + '(' + obj.scaleX + ', ' + obj.scaleY + (use3d ? ', 1)' : ')');
    }
    
    function fireEvent(owner, target, type, data) {
        var evt = document.createEvent('HTMLEvents');
        evt.initEvent(type, false, true);
        evt.data = data;
        evt.eventTarget = target || owner;
        owner.dispatchEvent(evt);
    }

})();
