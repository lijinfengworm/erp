!function(a,b){"function"==typeof define&&define.amd?define(["jquery"],b):"object"==typeof exports?module.exports=b(require("jquery")):a.alertbox=b(a.$)}(this,function(a){var b={title:"这是一个标题",cancel:function(){},confirm:function(){}},c={touchMoveHandle:function(a){return a.preventDefault(),!1},insertStyles:function(){var a,c,d,f,h,b=[],g=arguments.length;1==g?(a=document,b.push(arguments[0])):2==g?(a=arguments[0],b.push(arguments[1])):alert("函数最多接收两个参数！"),d=a.getElementsByTagName("head")[0],styles=d.getElementsByTagName("style"),0==styles.length&&(a.createStyleSheet?a.createStyleSheet():(h=a.createElement("style"),h.setAttribute("type","text/css"),d.appendChild(h))),f=styles[0],c=b.join("\n"),f.styleSheet?f.styleSheet.cssText+=c:a.getBoxObjectFor?f.innerHTML+=c:f.appendChild(a.createTextNode(c))}},d={beforeShowMask:function(a){document.addEventListener("touchmove",c.touchMoveHandle,!1),a&&a()},afterHideMask:function(a){document.removeEventListener("touchmove",c.touchMoveHandle),a&&a()}},e=" .ui-alert-mask { position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 1000; width: 100%; height: 100%; background-color: rgba(4, 0, 0, 0.5);  }                     .ui-alert-mask .ui-alert { position: absolute; top: 50%; left: 50%; -webkit-transform: translate(-50%, -50%); transform: translate(-50%, -50%); width: 75%; padding: 0 25px; border-radius: 8px; background-color: #fff; text-align: center; }                     .ui-alert-mask .ui-alert .ui-alert-hd { padding: 0; }                     .ui-alert-mask .ui-alert .ui-alert-hd:after {content: ''; display: block; width: 100%;height: 1px; background-color: #e6e6e6; -webkit-transform: scaleY(.5);transform: scaleY(.5);}                     .ui-alert-mask .ui-alert .ui-alert-hd h2 { line-height: 1.5; margin: 0; padding: 10px 0; font-size: 15px; font-weight: normal;}                     .ui-alert-mask .ui-alert .ui-alert-bd { width: 100%; display: -webkit-box; display: -webkit-flex; display: -ms-flexbox; display: flex; -webkit-box-pack: justify; -webkit-justify-content: space-between; -ms-flex-pack: justify; justify-content: space-between; padding: 15px 0; }                     .ui-btn { display:block;width: 45%; padding: 6px 0; font-size: 14px; border-radius: 4px; text-decoration: none;}                     .ui-btn-cancel { color: #666; border: 1px solid #e6e6e6; }                     .ui-btn-confirm { color: #ef4f4f; border: 1px solid #ef4f4f; }                     ",f='<div class="ui-alert-mask">                         <div class="ui-alert">                             <div class="ui-alert-hd">                                 <h2 id="js-alert-title"></h2>                             </div>                             <div class="ui-alert-bd">                                 <a href="javascript:void(0)" class="ui-btn ui-btn-cancel" id="js-alert-cancel">取消</a>                                 <a href="javascript:void(0)" class="ui-btn ui-btn-confirm" id="js-alert-confirm">确定</a>                             </div>                         </div>                     </div>                   ';return{init:function(c){this.op=a.extend(b,c||{}),this.title=this.op.title,this.cancelCallback=this.op.cancel,this.confirmCallback=this.op.confirm},bind:function(){var b=this;a("#js-alert-cancel").on("tap",function(){b.cancelCallback&&b.cancelCallback(),b.hide()}),a("#js-alert-confirm").on("tap",function(){console.log(b.confirmCallback),b.confirmCallback&&b.confirmCallback(),b.hide()})},render:function(){a(".ui-alert-mask").length||(a("body").append(f),a("#js-alert-title").html(this.title)),this.bind()},destroy:function(){a("#js-alert-cancel").off("tap"),a("#js-alert-confirm").off("tap"),a(".ui-alert-mask").remove()},show:function(b){this.init(b),d.beforeShowMask(function(){a("style").length?a("style").each(function(b,d){-1==a(d).html().indexOf(".ui-alert-mask")&&c.insertStyles(e)}):c.insertStyles(e)}),this.render()},hide:function(){var a=this;d.afterHideMask(function(){a.destroy()})}}});