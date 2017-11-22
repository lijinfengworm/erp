define(function(){
    "use strict";
    var value;
    function changenum(opt){
        this.opt = opt !== void 0 ? opt : "";
    }
    changenum.prototype={
        defaults:{
            wrap:".numbox",
            subtract:".icon-subtract-nosprite",
            add:".icon-add--nosprite",
            textbox:"#numbox-text"
        },
        init:function(){  
            var t = this;   
            $.extend(t.defaults,t.opt); 
            t.inputchange(t.callback);           
            t.subtract(t.callback);
            t.add(t.callback);
        },
        inputchange:function(fun){
            var t = this;
            $(t.defaults.textbox).each(function(){
                $(this).bind('input propertychange', function() {
                    var maxNum = $("#stock").length > 0 ? parseInt($(".numbox-text").attr("maxNum")) : maxstock;//限时抢购的数量;
                    var self = $(this),
                        buysnum = self.val(),
                        maxstock = $("#stock").length > 0 ? parseInt($("#stock").text()) : parseInt($(this).parent().find(".numbox-text").attr("maxstock"));
                    if(buysnum <=0 || "undefined" == typeof buysnum){
                        $(this).siblings(t.defaults.subtract).addClass('nodrop');
                        $(".error-msg .busnum").addClass("show");
                        $(".error-msg .busnum").text("请填写正确的购买数量！");
                    }else if(buysnum > maxstock || buysnum-maxNum>0){
                        $(this).siblings(t.defaults.add).addClass('nodrop');                        
                        $(".error-msg .busnum").addClass("show");
                        $(".error-msg .busnum").text("您选择的数量超过最大库存");
                        t.overstock(this);
                    }else{                    
                        $(this).siblings(t.defaults.subtract).removeClass('nodrop');
                        $(this).siblings(t.defaults.add).removeClass('nodrop');
                        $(".error-msg .busnum").removeClass("show");
                        $(".error-msg .busnum").text("请填写正确的购买数量！");
                    }   
                    fun(this);                             
                });       
            });      
        },
        subtract:function(fun){  
            var t=this;
            // console.log(JSON.stringify(fun));
            $(t.defaults.subtract).each(function(){
                $(this).click(function(){

                    var self = $(this);
                    value = self.siblings(t.defaults.textbox).val();
                    if(value == 1){
                        self.addClass('nodrop');
                        return false
                    }else{
                        self.siblings(t.defaults.add).removeClass('nodrop');
                        self.removeClass('nodrop');
                        $(".error-msg .busnum").removeClass("show");
                    }
                     t.roll(self.parent(),value,"down");
                    value--;
                    var s = Math.round(0+parseInt(value));               
                    self.siblings(t.defaults.textbox).val(s); 
                    fun(this);    
                    return false              
                })
            });
        },
        add:function(fun){
            var t=this;
            $(t.defaults.add).each(function(){
                $(this).click(function(){
                    // var maxNum = $(".numbox-text").attr("maxNum");
                    console.log(maxNum)
                    var self = $(this),
                    maxstock = $("#stock").length > 0 ? parseInt($("#stock").text()) : parseInt($(this).parent().find(".numbox-text").attr("maxstock"));
                    // value = self.siblings(t.defaults.textbox).val();
                    var maxNum = $("#stock").length > 0 ? parseInt($(".numbox-text").attr("maxNum")) : maxstock;//限时抢购的数量;
                    value = self.siblings(t.defaults.textbox).val();
                    // alert(maxstock);
                    // if(value >= maxstock){
                    if(value >= maxstock || value >= maxNum){
                        self.addClass('nodrop');
                        t.overstock(this);
                        return false
                    }else{
                        self.siblings(t.defaults.subtract).removeClass('nodrop');
                        self.removeClass('nodrop');
                        $(".error-msg .busnum").removeClass("show");
                    }
                    t.roll(self.parent(),value,"up");
                    value++;                
                    var s = Math.round(0+parseInt(value));                 
                    self.siblings(t.defaults.textbox).val(s);
                    fun(this);
                    return false   
                })
            })
        },
        roll:function(obj,v1,s){  
            var t = this,v2,v1top,v2top,vst;
            if(s == "up"){
                v2 = parseInt(v1)+1;
                v1top = -22;
                v2top = 1;
                vst = "vbottom";
            }else if(s == "down"){
                v2 = parseInt(v1)-1;
                v1top = 27;
                v2top = 1;
                vst = "vtop";
            }else{
                return false
            } 
            var dom = '<div class="rollstocknum"><div class="v v1">'+v1+'</div><div class="v v2 '+vst+'">'+v2+'</div></div>';
            $(obj).append(dom);
            $(".rollstocknum").show();
            $(".v1").stop().animate({top:v1top+"px"},150);
            $(".v2").stop().animate({top:v2top+"px"},150,function(){
                $(".rollstocknum").remove();
            });
        },
        callback:function(obj){
            //return obj
        },
        overstock:function(obj){

        }
    }
    return changenum
})