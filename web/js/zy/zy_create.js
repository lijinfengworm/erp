/**
 * zy create from
 * User: guwei
 * Update: 13-3-11 10:38
 */
var isIE = !!window.ActiveXObject;
var isIE6 = isIE&&!window.XMLHttpRequest;

var Class = {
    create: function() {
        return function() { this.initialize.apply(this, arguments); }
    }
};
var Extend = function(destination, source) {
    for (var property in source) {
        destination[property] = source[property];
    }
};
var Bind = function(object, fun) {
    return function() {
        return fun.apply(object, arguments);
    }
};
var BindAsEventListener = function(object, fun) {
    var args = Array.prototype.slice.call(arguments).slice(2);
    return function(event) {
        return fun.apply(object, [event || window.event].concat(args));
    }
};
var CurrentStyle = function(element){
    return element.currentStyle || document.defaultView.getComputedStyle(element, null);
};
function addEventHandler(oTarget, sEventType, fnHandler) {
    if (oTarget.addEventListener) {
        oTarget.addEventListener(sEventType, fnHandler, false);
    } else if (oTarget.attachEvent) {
        oTarget.attachEvent("on" + sEventType, fnHandler);
    } else {
        oTarget["on" + sEventType] = fnHandler;
    }
}
function removeEventHandler(oTarget, sEventType, fnHandler) {
    if (oTarget.removeEventListener) {
        oTarget.removeEventListener(sEventType, fnHandler, false);
    } else if (oTarget.detachEvent) {
        oTarget.detachEvent("on" + sEventType, fnHandler);
    } else {
        oTarget["on" + sEventType] = null;
    }
}
var Resize = Class.create();
Resize.prototype = {
    //缩放对象
    initialize: function(obj, options) {
        this._obj = G.$(obj);
        this._styleWidth = this._styleHeight = this._styleLeft = this._styleTop = 0;
        this._sideRight = this._sideDown = this._sideLeft = this._sideUp = 0;
        this._fixLeft = this._fixTop = 0;
        this._scaleLeft = this._scaleTop = 0;
        this._mxSet = function(){};
        this._mxRightWidth = this._mxDownHeight = this._mxUpHeight = this._mxLeftWidth = 0;
        this._mxScaleWidth = this._mxScaleHeight = 0;
        this._fun = function(){};
        var _style = CurrentStyle(this._obj);
        this._borderX = (parseInt(_style.borderLeftWidth) || 0) + (parseInt(_style.borderRightWidth) || 0);
        this._borderY = (parseInt(_style.borderTopWidth) || 0) + (parseInt(_style.borderBottomWidth) || 0);
        this._fR = BindAsEventListener(this, this.Resize);
        this._fS = Bind(this, this.Stop);
        this.SetOptions(options);
        this.Max = !!this.options.Max;
        this._mxContainer = G.$(this.options.mxContainer) || null;
        this.mxLeft = Math.round(this.options.mxLeft);
        this.mxRight = Math.round(this.options.mxRight);
        this.mxTop = Math.round(this.options.mxTop);
        this.mxBottom = Math.round(this.options.mxBottom);
        this.Min = !!this.options.Min;
        this.minWidth = Math.round(this.options.minWidth);
        this.minHeight = Math.round(this.options.minHeight);
        this.Scale = !!this.options.Scale;
        this.Ratio = Math.max(this.options.Ratio, 0);
        this.onResize = this.options.onResize;
        this._obj.style.position = "absolute";
        !this._mxContainer || CurrentStyle(this._mxContainer).position == "relative" || (this._mxContainer.style.position = "relative");
    },
    SetOptions: function(options) {
        this.options = {
            Max:		false,
            mxContainer:"",
            mxLeft:		0,
            mxRight:	9999,
            mxTop:		0,
            mxBottom:	9999,
            Min:		false,
            minWidth:	50,
            minHeight:	50,
            Scale:		false,
            Ratio:		0,
            onResize:	function(){}
        };
        Extend(this.options, options || {});
    },
    Set: function(resize, side) {
        var resize = G.$(resize), fun;
        if(!resize) return;
        switch (side.toLowerCase()) {
            case "up" :
                fun = this.Up;
                break;
            case "down" :
                fun = this.Down;
                break;
            case "left" :
                fun = this.Left;
                break;
            case "right" :
                fun = this.Right;
                break;
            case "left-up" :
                fun = this.LeftUp;
                break;
            case "right-up" :
                fun = this.RightUp;
                break;
            case "left-down" :
                fun = this.LeftDown;
                break;
            case "right-down" :
            default :
                fun = this.RightDown;
        };
        addEventHandler(resize, "mousedown", BindAsEventListener(this, this.Start, fun));
    },
    Start: function(e, fun, touch) {
        e.stopPropagation ? e.stopPropagation() : (e.cancelBubble = true);
        this._fun = fun;
        this._styleWidth = this._obj.clientWidth;
        this._styleHeight = this._obj.clientHeight;
        this._styleLeft = this._obj.offsetLeft;
        this._styleTop = this._obj.offsetTop;
        this._sideLeft = e.clientX - this._styleWidth;
        this._sideRight = e.clientX + this._styleWidth;
        this._sideUp = e.clientY - this._styleHeight;
        this._sideDown = e.clientY + this._styleHeight;
        this._fixLeft = this._styleLeft + this._styleWidth;
        this._fixTop = this._styleTop + this._styleHeight;
        if(this.Scale){
            this.Ratio = Math.max(this.Ratio, 0) || this._styleWidth / this._styleHeight;
            this._scaleLeft = this._styleLeft + this._styleWidth / 2;
            this._scaleTop = this._styleTop + this._styleHeight / 2;
        };
        if(this.Max){
            var mxLeft = this.mxLeft, mxRight = this.mxRight, mxTop = this.mxTop, mxBottom = this.mxBottom;
            if(!!this._mxContainer){
                mxLeft = Math.max(mxLeft, 0);
                mxTop = Math.max(mxTop, 0);
                mxRight = Math.min(mxRight, this._mxContainer.clientWidth);
                mxBottom = Math.min(mxBottom, this._mxContainer.clientHeight);
            };
            mxRight = Math.max(mxRight, mxLeft + (this.Min ? this.minWidth : 0) + this._borderX);
            mxBottom = Math.max(mxBottom, mxTop + (this.Min ? this.minHeight : 0) + this._borderY);
            this._mxSet = function(){
                this._mxRightWidth = mxRight - this._styleLeft - this._borderX;
                this._mxDownHeight = mxBottom - this._styleTop - this._borderY;
                this._mxUpHeight = Math.max(this._fixTop - mxTop, this.Min ? this.minHeight : 0);
                this._mxLeftWidth = Math.max(this._fixLeft - mxLeft, this.Min ? this.minWidth : 0);
            };
            this._mxSet();
            if(this.Scale){
                this._mxScaleWidth = Math.min(this._scaleLeft - mxLeft, mxRight - this._scaleLeft - this._borderX) * 2;
                this._mxScaleHeight = Math.min(this._scaleTop - mxTop, mxBottom - this._scaleTop - this._borderY) * 2;
            };
        };
        addEventHandler(document, "mousemove", this._fR);
        addEventHandler(document, "mouseup", this._fS);
        if(isIE){
            addEventHandler(this._obj, "losecapture", this._fS);
            this._obj.setCapture();
        }else{
            addEventHandler(window, "blur", this._fS);
            e.preventDefault();
        };
    },
    Resize: function(e) {
        window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty();
        this._fun(e);
        with(this._obj.style){
            width = this._styleWidth + "px"; height = this._styleHeight + "px";
            top = this._styleTop + "px"; left = this._styleLeft + "px";
        }
        this.onResize();
    },
    Up: function(e) {
        this.RepairY(this._sideDown - e.clientY, this._mxUpHeight);
        this.RepairTop();
        this.TurnDown(this.Down);
    },
    Down: function(e) {
        this.RepairY(e.clientY - this._sideUp, this._mxDownHeight);
        this.TurnUp(this.Up);
    },
    Right: function(e) {
        this.RepairX(e.clientX - this._sideLeft, this._mxRightWidth);
        this.TurnLeft(this.Left);
    },
    Left: function(e) {
        this.RepairX(this._sideRight - e.clientX, this._mxLeftWidth);
        this.RepairLeft();
        this.TurnRight(this.Right);
    },
    RightDown: function(e) {
        this.RepairAngle(
            e.clientX - this._sideLeft, this._mxRightWidth,
            e.clientY - this._sideUp, this._mxDownHeight
        );
        this.TurnLeft(this.LeftDown) || this.Scale || this.TurnUp(this.RightUp);
    },
    RightUp: function(e) {
        this.RepairAngle(
            e.clientX - this._sideLeft, this._mxRightWidth,
            this._sideDown - e.clientY, this._mxUpHeight
        );
        this.RepairTop();
        this.TurnLeft(this.LeftUp) || this.Scale || this.TurnDown(this.RightDown);
    },
    LeftDown: function(e) {
        this.RepairAngle(
            this._sideRight - e.clientX, this._mxLeftWidth,
            e.clientY - this._sideUp, this._mxDownHeight
        );
        this.RepairLeft();
        this.TurnRight(this.RightDown) || this.Scale || this.TurnUp(this.LeftUp);
    },
    LeftUp: function(e) {
        this.RepairAngle(
            this._sideRight - e.clientX, this._mxLeftWidth,
            this._sideDown - e.clientY, this._mxUpHeight
        );
        this.RepairTop(); this.RepairLeft();
        this.TurnRight(this.RightUp) || this.Scale || this.TurnDown(this.LeftDown);
    },
    RepairX: function(iWidth, mxWidth) {
        iWidth = this.RepairWidth(iWidth, mxWidth);
        if(this.Scale){
            var iHeight = this.RepairScaleHeight(iWidth);
            if(this.Max && iHeight > this._mxScaleHeight){
                iHeight = this._mxScaleHeight;
                iWidth = this.RepairScaleWidth(iHeight);
            }else if(this.Min && iHeight < this.minHeight){
                var tWidth = this.RepairScaleWidth(this.minHeight);
                if(tWidth < mxWidth){ iHeight = this.minHeight; iWidth = tWidth; }
            }
            this._styleHeight = iHeight;
            this._styleTop = this._scaleTop - iHeight / 2;
        }
        this._styleWidth = iWidth;
    },
    RepairY: function(iHeight, mxHeight) {
        iHeight = this.RepairHeight(iHeight, mxHeight);
        if(this.Scale){
            var iWidth = this.RepairScaleWidth(iHeight);
            if(this.Max && iWidth > this._mxScaleWidth){
                iWidth = this._mxScaleWidth;
                iHeight = this.RepairScaleHeight(iWidth);
            }else if(this.Min && iWidth < this.minWidth){
                var tHeight = this.RepairScaleHeight(this.minWidth);
                if(tHeight < mxHeight){ iWidth = this.minWidth; iHeight = tHeight; }
            }
            this._styleWidth = iWidth;
            this._styleLeft = this._scaleLeft - iWidth / 2;
        }
        this._styleHeight = iHeight;
    },
    RepairAngle: function(iWidth, mxWidth, iHeight, mxHeight) {
        iWidth = this.RepairWidth(iWidth, mxWidth);
        if(this.Scale){
            iHeight = this.RepairScaleHeight(iWidth);
            if(this.Max && iHeight > mxHeight){
                iHeight = mxHeight;
                iWidth = this.RepairScaleWidth(iHeight);
            }else if(this.Min && iHeight < this.minHeight){
                var tWidth = this.RepairScaleWidth(this.minHeight);
                if(tWidth < mxWidth){ iHeight = this.minHeight; iWidth = tWidth; }
            }
        }else{
            iHeight = this.RepairHeight(iHeight, mxHeight);
        }
        this._styleWidth = iWidth;
        this._styleHeight = iHeight;
    },
    RepairTop: function() {
        this._styleTop = this._fixTop - this._styleHeight;
    },
    RepairLeft: function() {
        this._styleLeft = this._fixLeft - this._styleWidth;
    },
    RepairHeight: function(iHeight, mxHeight) {
        iHeight = Math.min(this.Max ? mxHeight : iHeight, iHeight);
        iHeight = Math.max(this.Min ? this.minHeight : iHeight, iHeight, 0);
        return iHeight;
    },
    RepairWidth: function(iWidth, mxWidth) {
        iWidth = Math.min(this.Max ? mxWidth : iWidth, iWidth);
        iWidth = Math.max(this.Min ? this.minWidth : iWidth, iWidth, 0);
        return iWidth;
    },
    RepairScaleHeight: function(iWidth) {
        return Math.max(Math.round((iWidth + this._borderX) / this.Ratio - this._borderY), 0);
    },
    RepairScaleWidth: function(iHeight) {
        return Math.max(Math.round((iHeight + this._borderY) * this.Ratio - this._borderX), 0);
    },
    TurnRight: function(fun) {
        if(!(this.Min || this._styleWidth)){
            this._fun = fun;
            this._sideLeft = this._sideRight;
            this.Max && this._mxSet();
            return true;
        }
    },
    TurnLeft: function(fun) {
        if(!(this.Min || this._styleWidth)){
            this._fun = fun;
            this._sideRight = this._sideLeft;
            this._fixLeft = this._styleLeft;
            this.Max && this._mxSet();
            return true;
        }
    },
    TurnUp: function(fun) {
        if(!(this.Min || this._styleHeight)){
            this._fun = fun;
            this._sideDown = this._sideUp;
            this._fixTop = this._styleTop;
            this.Max && this._mxSet();
            return true;
        }
    },
    TurnDown: function(fun) {
        if(!(this.Min || this._styleHeight)){
            this._fun = fun;
            this._sideUp = this._sideDown;
            this.Max && this._mxSet();
            return true;
        }
    },
    Stop: function() {
        removeEventHandler(document, "mousemove", this._fR);
        removeEventHandler(document, "mouseup", this._fS);
        if(isIE){
            removeEventHandler(this._obj, "losecapture", this._fS);
            this._obj.releaseCapture();
        }else{
            removeEventHandler(window, "blur", this._fS);
        }
    }
};
var Drag = Class.create();
Drag.prototype = {
    initialize: function(drag, options) {
        this.Drag = G.$(drag);
        this._x = this._y = 0;
        this._marginLeft = this._marginTop = 0;
        this._fM = BindAsEventListener(this, this.Move);
        this._fS = Bind(this, this.Stop);
        this.SetOptions(options);
        this.Limit = !!this.options.Limit;
        this.mxLeft = parseInt(this.options.mxLeft);
        this.mxRight = parseInt(this.options.mxRight);
        this.mxTop = parseInt(this.options.mxTop);
        this.mxBottom = parseInt(this.options.mxBottom);
        this.LockX = !!this.options.LockX;
        this.LockY = !!this.options.LockY;
        this.Lock = !!this.options.Lock;
        this.onStart = this.options.onStart;
        this.onMove = this.options.onMove;
        this.onStop = this.options.onStop;
        this._Handle = G.$(this.options.Handle) || this.Drag;
        this._mxContainer = G.$(this.options.mxContainer) || null;
        this.Drag.style.position = "absolute";
        if(isIE && !!this.options.Transparent){
            with(this._Handle.appendChild(document.createElement("div")).style){
                width = height = "100%"; backgroundColor = "#fff"; filter = "alpha(opacity:0)"; fontSize = 0;
            }
        }
        this.Repair();
        addEventHandler(this._Handle, "mousedown", BindAsEventListener(this, this.Start));
    },
    SetOptions: function(options) {
        this.options = {
            Handle:			"",
            Limit:			false,
            mxLeft:			0,
            mxRight:		9999,
            mxTop:			0,
            mxBottom:		9999,
            mxContainer:	"",
            LockX:			false,
            LockY:			false,
            Lock:			false,
            Transparent:	false,
            onStart:		function(){},
            onMove:			function(){},
            onStop:			function(){}
        };
        Extend(this.options, options || {});
    },
    Start: function(oEvent) {
        if(this.Lock){ return; }
        this.Repair();
        this._x = oEvent.clientX - this.Drag.offsetLeft;
        this._y = oEvent.clientY - this.Drag.offsetTop;
        this._marginLeft = parseInt(CurrentStyle(this.Drag).marginLeft) || 0;
        this._marginTop = parseInt(CurrentStyle(this.Drag).marginTop) || 0;
        addEventHandler(document, "mousemove", this._fM);
        addEventHandler(document, "mouseup", this._fS);
        if(isIE){
            addEventHandler(this._Handle, "losecapture", this._fS);
            this._Handle.setCapture();
        }else{
            addEventHandler(window, "blur", this._fS);
            oEvent.preventDefault();
        };
        this.onStart();
    },
    Repair: function() {
        if(this.Limit){
            this.mxRight = Math.max(this.mxRight, this.mxLeft + this.Drag.offsetWidth);
            this.mxBottom = Math.max(this.mxBottom, this.mxTop + this.Drag.offsetHeight);
            !this._mxContainer || CurrentStyle(this._mxContainer).position == "relative" || CurrentStyle(this._mxContainer).position == "absolute" || (this._mxContainer.style.position = "relative");
        }
    },
    Move: function(oEvent) {
        if(this.Lock){ this.Stop(); return; };
        window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty();
        var iLeft = oEvent.clientX - this._x, iTop = oEvent.clientY - this._y;
        if(this.Limit){
            var mxLeft = this.mxLeft, mxRight = this.mxRight, mxTop = this.mxTop, mxBottom = this.mxBottom;
            if(!!this._mxContainer){
                mxLeft = Math.max(mxLeft, 0);
                mxTop = Math.max(mxTop, 0);
                mxRight = Math.min(mxRight, this._mxContainer.clientWidth);
                mxBottom = Math.min(mxBottom, this._mxContainer.clientHeight);
            };
            iLeft = Math.max(Math.min(iLeft, mxRight - this.Drag.offsetWidth), mxLeft);
            iTop = Math.max(Math.min(iTop, mxBottom - this.Drag.offsetHeight), mxTop);
        }
        if(!this.LockX){ this.Drag.style.left = iLeft - this._marginLeft + "px"; }
        if(!this.LockY){ this.Drag.style.top = iTop - this._marginTop + "px"; }
        this.onMove();
    },
    Stop: function() {
        removeEventHandler(document, "mousemove", this._fM);
        removeEventHandler(document, "mouseup", this._fS);
        if(isIE){
            removeEventHandler(this._Handle, "losecapture", this._fS);
            this._Handle.releaseCapture();
        }else{
            removeEventHandler(window, "blur", this._fS);
        };
        this.onStop();
    }
};
//图片切割
var ImgCropper = Class.create();
ImgCropper.prototype = {
    initialize: function(container, handle, url, options) {
        this._Container = G.$(container);
        this._layHandle = G.$(handle);
        this.Url = url;
        this._layBase = this._Container.appendChild(document.createElement("img"));
        this._layCropper = this._Container.appendChild(document.createElement("img"));
        this._layCropper.onload = Bind(this, this.SetPos);
        this._tempImg = document.createElement("img");
        this._tempImg.onload = Bind(this, this.SetSize);
        this.SetOptions(options);
        this.Opacity = Math.round(this.options.Opacity);
        this.Color = this.options.Color;
        this.Scale = !!this.options.Scale;
        this.Ratio = Math.max(this.options.Ratio, 0);
        this.Width = Math.round(this.options.Width);
        this.Height = Math.round(this.options.Height);
        var oPreview = G.$(this.options.Preview);
        if(oPreview){
            oPreview.style.position = "relative";
            oPreview.style.overflow = "hidden";
            this.viewWidth = Math.round(this.options.viewWidth);
            this.viewHeight = Math.round(this.options.viewHeight);
            this._view = oPreview.appendChild(document.createElement("img"));
            this._view.style.position = "absolute";
            this._view.onload = Bind(this, this.SetPreview);
        }
        var oPreview2 = G.$(this.options.Preview2);
        if(oPreview2){
            oPreview2.style.position = "relative";
            oPreview2.style.overflow = "hidden";
            this.viewWidth2 = Math.round(this.options.viewWidth2);
            this.viewHeight2 = Math.round(this.options.viewHeight2);
            this._view2 = oPreview2.appendChild(document.createElement("img"));
            this._view2.style.position = "absolute";
            this._view2.onload = Bind(this, this.SetPreview2);
        }
        this._drag = new Drag(this._layHandle, { Limit: true, onMove: Bind(this, this.SetPos), Transparent: true });
        this.Resize = !!this.options.Resize;
        if(this.Resize){
            var op = this.options, _resize = new Resize(this._layHandle, { Max: true, onResize: Bind(this, this.SetPos) });
            op.RightDown && (_resize.Set(op.RightDown, "right-down"));
            op.LeftDown && (_resize.Set(op.LeftDown, "left-down"));
            op.RightUp && (_resize.Set(op.RightUp, "right-up"));
            op.LeftUp && (_resize.Set(op.LeftUp, "left-up"));
            op.Right && (_resize.Set(op.Right, "right"));
            op.Left && (_resize.Set(op.Left, "left"));
            op.Down && (_resize.Set(op.Down, "down"));
            op.Up && (_resize.Set(op.Up, "up"));
            this.Min = !!this.options.Min;
            this.minWidth = Math.round(this.options.minWidth);
            this.minHeight = Math.round(this.options.minHeight);
            this._resize = _resize;
        }
        this._Container.style.position = "relative";
        this._Container.style.overflow = "hidden";
        this._layHandle.style.zIndex = 9;
        this._layCropper.style.zIndex = 8;
        this._layBase.style.position = this._layCropper.style.position = "absolute";
        this._layBase.style.top = this._layBase.style.left = this._layCropper.style.top = this._layCropper.style.left = 0;//对齐
        this.Init();
    },
    SetOptions: function(options) {
        this.options = {
            Opacity:	50,
            Color:		"",
            Width:		0,
            Height:		0,
            Resize:		false,
            Right:		"",
            Left:		"",
            Up:			"",
            Down:		"",
            RightDown:	"",
            LeftDown:	"",
            RightUp:	"",
            LeftUp:		"",
            Min:		false,
            minWidth:	50,
            minHeight:	50,
            Scale:		true,
            Ratio:		0,
            Preview:	"",
            viewWidth:	0,
            viewHeight:	0,
            Preview2:	"",
            viewWidth2:	0,
            viewHeight2:	0
        };
        Extend(this.options, options || {});
    },
    Init: function() {
        this.Color && (this._Container.style.backgroundColor = this.Color);
        this._tempImg.src = this._layBase.src = this._layCropper.src = this.Url;
        if(isIE){
            this._layBase.style.filter = "alpha(opacity:" + this.Opacity + ")";
        } else {
            this._layBase.style.opacity = this.Opacity / 100;
        }
        this._view && (this._view.src = this.Url);
        this._view2 && (this._view2.src = this.Url);
        if(this.Resize){
            with(this._resize){
                Scale = this.Scale; Ratio = this.Ratio; Min = this.Min; minWidth = this.minWidth; minHeight = this.minHeight;
            }
        }
    },
    SetPos: function() {
        if(isIE6){ with(this._layHandle.style){ zoom = .9; zoom = 1; }; };
        var p = this.GetPos();
        this._layCropper.style.clip = "rect(" + p.Top + "px " + (p.Left + p.Width) + "px " + (p.Top + p.Height) + "px " + p.Left + "px)";
        this.SetPreview();
        this.SetPreview2();
    },
    SetPreview: function() {
        if(this._view){
            var p = this.GetPos(), s = this.GetSize(p.Width, p.Height, this.viewWidth, this.viewHeight), scale = s.Height / p.Height;
            var pHeight = this._layBase.height * scale, pWidth = this._layBase.width * scale, pTop = p.Top * scale, pLeft = p.Left * scale;
            with(this._view.style){
                width = pWidth + "px"; height = pHeight + "px"; top = - pTop + "px "; left = - pLeft + "px";
                clip = "rect(" + pTop + "px " + (pLeft + s.Width) + "px " + (pTop + s.Height) + "px " + pLeft + "px)";
            }
        }
    },
    SetPreview2: function() {
        if(this._view2){
            var p = this.GetPos(), s = this.GetSize(p.Width, p.Height, this.viewWidth2, this.viewHeight2), scale = s.Height / p.Height;
            var pHeight = this._layBase.height * scale, pWidth = this._layBase.width * scale, pTop = p.Top * scale, pLeft = p.Left * scale;
            with(this._view2.style){
                width = pWidth + "px"; height = pHeight + "px"; top = - pTop + "px "; left = - pLeft + "px";
                clip = "rect(" + pTop + "px " + (pLeft + s.Width) + "px " + (pTop + s.Height) + "px " + pLeft + "px)";
            }
        }
    },
    SetSize: function() {
        var s = this.GetSize(this._tempImg.width, this._tempImg.height, this.Width, this.Height);
        this._layBase.style.width = this._layCropper.style.width = s.Width + "px";
        this._layBase.style.height = this._layCropper.style.height = s.Height + "px";
        this._drag.mxRight = s.Width; this._drag.mxBottom = s.Height;
        if(this.Resize){ this._resize.mxRight = s.Width; this._resize.mxBottom = s.Height; }
    },
    GetPos: function() {
        with(this._layHandle){
            return { Top: offsetTop, Left: offsetLeft, Width: offsetWidth, Height: offsetHeight }
        }
    },
    GetSize: function(nowWidth, nowHeight, fixWidth, fixHeight) {
        var iWidth = nowWidth, iHeight = nowHeight, scale = iWidth / iHeight;
        if(fixHeight){ iWidth = (iHeight = fixHeight) * scale; }
        if(fixWidth && (!fixHeight || iWidth > fixWidth)){ iHeight = (iWidth = fixWidth) / scale; }
        return { Width: iWidth, Height: iHeight }
    }
};
var ic;
var checkForm = {
    init: function(bPic){
        var that =this,
            oWrap = this.oWrap = G.$('JCFrom'),
            oSlect = this.oSlect = G.$('data_group_id'),
            oName = this.oName = G.$('data_fullname'),
            oSlug = this.oSlug = G.$('data_name'),
            oDesc = this.oDesc = G.$('data_memo'),
            oFile = this.oFile = G.$('data_hupu_file'),
            sBtn = this.sBtn = G.$$$('submit',oWrap)[0],
            cBtn = this.cBtn = G.$$$('cancel',oWrap)[0],
            fInput = this.finput = G.$$$('input_f',oWrap)[0],
            msgArea = this.finput = G.$$$('msg-area',oWrap),
            sh,sw,bPic=bPic,bPost = true,img_url
            ;
        ic = new ImgCropper("bgDiv", "dragDiv", "/images/zy/avatarDefault200x200.png", {
            Width: 200, Height: 200, Color: "#000",
            Resize: true,
            //Right: "rRight", Left: "rLeft", Up:	"rUp", Down: "rDown",
            RightDown: "rRightDown", LeftDown: "rLeftDown", RightUp: "rRightUp", LeftUp: "rLeftUp",
            Preview: "viewDiv", viewWidth: 60, viewHeight: 60,
            Preview2: "viewDiv2", viewWidth2: 30, viewHeight2: 30
        });
        G.listenEvent(fInput,'change',function(){
            e = arguments[0] || window.event;
             var target = e.srcElement || e.target,bImg = that.checkImg((target.value));
            if(bImg){
                $('#picForm').ajaxForm({
                    dataType:"json",
                    url:'/object/imgage_upload',
                    success:function (a) {
                        var sImg=a.show_url;
                        img_url =  a.img_url;
                        var img = new Image();img.onload=function(){sh =  img.height; sw = img.width; }; img.src = sImg;
                        "0" == a.status ? (ic.Url = sImg,ic.Init(),bPic=true) : alert(a.msg);
                    },
                    error:function(){
                        var errorI =  Dialog.init({con:'网络错误',time:2000});
                        errorI.show()
                    }
                }).submit();
            }else{
                var error =  Dialog.init({con:'请选择JPG,GIF或者PNG格式',time:2000});
                error.show();
            }
        });
        G.listenEvent(oSlect, 'change', function () { that.checkCat()&&G.removeClass(msgArea[0],'h'); });
        G.listenEvent(oName, 'change', function () { that.checkName()&&G.removeClass(msgArea[1],'h'); });
        G.listenEvent(oSlug, 'change', function () { that.checkSlug()&&G.removeClass(msgArea[2],'h'); });
        G.listenEvent(cBtn, 'click', function () {
            var cancel =  Dialog.init({con:'<div class="bd"><p>你确定要离开</p><div class="pop-handel"><a class="c-btn hide-pop sure" onclick="createSure()">确定</a><a class="c-btn hide-pop cancel">取消</a></div></div>'});
            cancel.show();
        });
        G.listenEvent(sBtn, 'click', function () {
            if(that.checkCat()&&that.checkName()&&that.checkSlug()&&bPic){
                var p = ic.Url, o = ic.GetPos(),
                x = o.Left, y = o.Top, w = o.Width, h = o.Height, pw = Math.round(ic._layBase.width),ph = Math.round(ic._layBase.height),scaleV =  sh/ph,scaleC= sw/pw;
                oFile.value=img_url+'&'+x*scaleC+'&'+y*scaleV+'&'+w*scaleC+'&'+h*scaleV;
                var value = $("#JCFrom").find('form').serialize(),action = G.$$('form',oWrap)[0].getAttribute('action');
                if(oFile.value&&bPost){
                    $.post(action, value, function (data) {
                        if(data.status=='0'){
                            var success =  Dialog.init({con:'创建成功，请等待审核',time:2000});
                            success.show();
                            window.location.href = '/object/all';
                            bPost = false;
                        }else{
                            var error =  Dialog.init({con:'提交错误',time:2000});
                            error.show();
                        }
                    }, 'json');
                }
            }else if(!that.checkCat()){
                G.addClass(msgArea[0],'h');
            }else if(!that.checkName()){
                G.addClass(msgArea[1],'h');
            }else if(!that.checkSlug()){
                G.addClass(msgArea[2],'h');
            }else if(!bPic){
                var error =  Dialog.init({con:'请选择图片',time:2000});
                error.show();
            }
        });
    },
    checkName :function(){
        var that = this,nameReg = /^[\u4e00-\u9fa5_a-zA-Z0-9_]{1,10}$/gi;
        return nameReg.test(that.oName.value)
    },
    checkSlug :function(){
        var that = this,slugeReg = /^[\u4e00-\u9fa5_a-zA-Z0-9_]{1,10}$/gi;
        return slugeReg.test(that.oSlug.value)
    },
    checkImg :function(value){
     var fReg= /\.jpg$|\.png$|\.gif$/i,fVal= value;
     return fReg.test(fVal);
     },
    checkCat:function(){
        var that = this,_sleect = that.oSlect,sCat = _sleect.options[_sleect.selectedIndex].getAttribute('value');
        if(sCat==='0'){
            return false;
        }else{
            return true;
        }
    }
};
function createSure(){
    window.location.href = '/object/all';
}



