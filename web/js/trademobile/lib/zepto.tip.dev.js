(function(root, factory) {
  if(typeof define === 'function' && define.amd) {
    define(['jquery'], factory);
  } else if(typeof exports === 'object') {
    module.exports = factory(require('jquery'));
  } else {
    root.Tip = factory(root.$);
  }
}(this, function($) {

     var util = {
        insertStyles: function insertStyles(){
            var doc,
                cssCode=[],
                cssText;

            var head,
                style,
                firstStyle;

            var len = arguments.length;
            if(len == 1){
                doc = document;
                cssCode.push(arguments[0])
            }else if(len == 2){
                doc = arguments[0];
                cssCode.push(arguments[1]);
            }else{
                alert("函数最多接收两个参数！");
            }

            head = doc.getElementsByTagName("head")[0];
            styles= head.getElementsByTagName("style");

            if(styles.length == 0){
                if(doc.createStyleSheet){//ie
                    doc.createStyleSheet();
                }else{//FF
                    var tempStyle = doc.createElement("style");
                    tempStyle.setAttribute("type","text/css");
                    head.appendChild(tempStyle);
                }
            }

            firstStyle = styles[0];
            cssText=cssCode.join("\n");

            if(!+"\v1"){//opacity兼容
                var str = cssText.match(/opacity:(\d?\.\d+);/);
                if(str!=null){
                    cssText = cssText.replace(str[0],"filter:alpha(opacity="+pareFloat(str[1])*100+")");
                }
            }

            if(firstStyle.styleSheet){
                firstStyle.styleSheet.cssText += cssText;
            }else if(doc.getBoxObjectFor){
                firstStyle.innerHTML += cssText;
            }else{
                firstStyle.appendChild(doc.createTextNode(cssText));
            }
        }
    }


    var defaults = {
        msg: '默认提示信息',
        timeout: 1500,
        callback: function(){}
    };

    var cssText = '.ui-tip {position:fixed;top:50%;left:50%; \
                        -webkit-transform:translate(-50%, -50%);transform:translate(-50%, -50%);z-index:2000; \
                        width: 80%; \
                        padding: 8px 16px; \
                        color: #fff; \
                        font-size: 16px; \
                        text-align: center; \
                        border-radius: 5px; \
                        background-color: rgba(0, 0, 0, .8);} \
                  ';

    var domText = '<div class="ui-tip" id="js-ui-tip"></div>';

    function Tip(opts) {

        this.op = $.extend(defaults, opts || {}),
        this.timer = null;

        this.msg = this.op.msg;
        this.timeout = this.op.timeout;
        this.callback = this.op.callback;
    }

    Tip.prototype.init = function init() {
        this.render().bind();
    };

    Tip.prototype.bind = function bind() {
        var _this = this;
        this.timer = setTimeout(function() {
            _this.destroy();
            _this.callback && _this.callback();
        }, this.timeout);
        return _this;
    };

    Tip.prototype.destroy = function destroy() {
        clearTimeout(this.timer);
        if ($.fn.fadeOut) {
            $('.ui-tip').fadeOut().remove();
        } else {
            $('.ui-tip').remove();
        }

        return this;
    };

    Tip.prototype.render = function render() {
        if($('style').length) {
            $('style').each(function(index, item) {
                if ($(item).html().indexOf('.ui-tip') == -1) {
                    util.insertStyles(cssText);
                }
            });
        } else {
            util.insertStyles(cssText);
        }
        if (!$('.ui-tip').length) {
            $('body').append(domText);
            $('#js-ui-tip').html(this.msg);
        }
        return this;
    };

    Tip.prototype.show = function show() {
        this.init();
        return this;
    };

    return Tip;

}));