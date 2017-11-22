define(function(){
    function alertbox(){
        var defaults = {
            title: '这是一个标题',
            confirmTxt: '确定',
            content:"",
            cancel: function() {},
            confirm: function() {}
        };

        var util = {
            touchMoveHandle: function touchMoveHandle(e) {
                e.preventDefault();
                return false;
            },

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

        var hooks = {
            beforeShowMask: function(cb) {
                if(document.addEventListener){
                    document.addEventListener('click', util.touchMoveHandle, false);
                }else if(document.attachEvent){
                    document.attachEvent('onclick', util.touchMoveHandle);
                }else{
                    document["onclick"] = util.touchMoveHandle;
                }
                
                cb && cb();
            },

            afterHideMask: function(cb) {
                if(document.removeEventListener){
                    document.removeEventListener('click', util.touchMoveHandle);
                }else if(document.detachEvent){
                    document.detachEvent('onclick', util.touchMoveHandle);
                }else{
                    document["onclick"] = util.touchMoveHandle;
                }
                
                cb && cb();
            }
        };

        var cssText = " .ui-alert-mask { position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 1000; } \
                        .ui-alert-mask .ui-alert { z-index:100;position: absolute; top: 50%; left: 50%; margin: -75px 0 0 -175px;width: 350px; padding: 0 25px; border-radius: 8px; background-color: #fff; text-align: center; } \
                        .ui-alert-mask .ui-alert .ui-alert-hd { padding: 0; } \
                        .ui-alert-mask .ui-alert .ui-alert-hd h2 { position:relative;color:#f63852;line-height: 1.5; margin: 0; padding: 10px 0; font-size: 20px; font-weight: bold;} \
                        .ui-alert-mask .ui-alert .ui-alert-bd { width: 100%; display:block;padding: 15px 0; } \
                        .icon-close { position:absolute; right:18px ; top:18px; cursor:pointer;display:block;} \
                        .ui-btn-confirm { position:relative;width:140px;height:36px;margin:15px auto 15px;display:block;text-align:center;color:#FFFFFF;font-size:20px; line-height:35px;background-color:#f63852;border-radius:6px;font-weight:bold;} \
                        .ui-btn-confirm:hover{color:#FFFFFF;text-decoration:none;}\
                        .ui-alert-content{position:relative;width:270px;margin:0 auto;} \
                        .ui-alert-content img{width:100%;} \
                        .blackbg{z-index:99;width: 100%; height: 100%;position:absolute;top:0px;left:0px; background:rgba(0, 0, 0, 0.5) none repeat scroll 0 0 !important; filter:alpha(opacity=50);background:#000000; }\
                        ";

        var domText = '<div class="ui-alert-mask"> \
                            <div class="ui-alert"> \
                                <div class="ui-alert-hd"> \
                                    <h2 id="js-alert-title"></h2> \
                                </div> \
                                <div class="ui-alert-bd"> \
                                    <div id="js-alert-content" class="ui-alert-content"></div> \
                                    <a href="javascript:void(0)" class="ui-btn ui-btn-confirm" id="js-alert-confirm">确定</a> \
                                </div> \
                                <a href="javascript:void(0)" class="icon-close" id="js-alert-cancel"></a> \
                            </div> \
                            <div class="blackbg"></div>\
                        </div> \
                      ';

        return {
            init: function(opts) {
                this.op = $.extend(defaults, opts || {});
                this.title = this.op.title;
                this.confirmTxt = this.op.confirmTxt;
                this.content = this.op.content;
                this.cancelCallback = this.op.cancel;
                this.confirmCallback = this.op.confirm;
            },

            bind: function() {
                var _this = this;
                $('#js-alert-cancel').on('click', function(e) {
                    _this.cancelCallback && _this.cancelCallback();
                    _this.hide();
                });

                $('#js-alert-confirm').on('click', function(e) {                
                    _this.confirmCallback && _this.confirmCallback();
                    _this.hide();
                });
            },

            render: function() {
                if (!$('.ui-alert-mask').length) {
                    $('body').append(domText);
                    $('#js-alert-title').html(this.title);
                    $("#js-alert-confirm").html(this.confirmTxt);
                    $("#js-alert-content").html(this.content);
                }
                this.bind();
            },

            destroy: function() {
                $('#js-alert-cancel').off('click');
                $('#js-alert-confirm').off('click');
                $('.ui-alert-mask').remove();
            },

            show: function(opts) {
                this.init(opts);

                hooks.beforeShowMask(function() {
                    if($('style').length) {
                        $('style').each(function(index, item) {
                            if ($(item).html().indexOf('.ui-alert-mask') == -1) {
                                util.insertStyles(cssText);
                            }
                        });
                    } else {
                        util.insertStyles(cssText);
                    }
                });

                this.render();
            },

            hide: function() {
                var _this = this;
                hooks.afterHideMask(function() {
                    _this.destroy();
                });
            }
        };
    };

    return alertbox()
});