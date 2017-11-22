define(["../../lib/lazy_load"],function(){
    function initlazy(opt){
        this.opt = opt !== void 0 ? opt : "";
    }
    initlazy.prototype={
        defaults:{
            placeholder:"",
            ele: ".lazy",
            effect : "fadeIn",
            event:""
        },
        init:function(){
            var t = this;           
            t.defaults = $.extend({},t.defaults,t.opt,true); 
            $(t.defaults.ele).lazyload({
                placeholder:t.defaults.placeholder,
                effect: t.defaults.effect
            }); 
            setTimeout(function(){
                $(t.defaults.ele).trigger("scroll")
            },100);             
        }
    }
    return initlazy
})