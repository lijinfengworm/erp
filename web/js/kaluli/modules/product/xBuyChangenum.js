/**
 * Created by jiangyanghe on 16/12/19.
 */
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
            t.colsePop();
        },
        inputchange:function(fun){
            var t = this;
            $(t.defaults.textbox).each(function(){
                $(this).bind('input propertychange', function() {
                    var self = $(this),
                        buysnum = self.val(),
                        maxstock = $("#stock").length > 0 ? parseInt($("#stock").text()) : parseInt($(this).parent().find(".numbox-text").attr("maxstock"));

                    if(buysnum <=0 || "undefined" == typeof buysnum){
                        $(this).siblings(t.defaults.subtract).addClass('nodrop');
                        $(".error-msg .busnum").addClass("show");
                    }else if(buysnum > maxstock || buysnum-maxNum>0){
                        $(this).siblings(t.defaults.add).addClass('nodrop');
                        $(".error-msg .busnum").addClass("show");
                        t.overstock(this);
                    }else{
                        $(this).siblings(t.defaults.subtract).removeClass('nodrop');
                        $(this).siblings(t.defaults.add).removeClass('nodrop');
                        $(".error-msg .busnum").removeClass("show");
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
                    console.log(JSON.stringify(self));
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
                    var self = $(this),
                        purchase_limits_num = $('#purchase_limits_num').length > 0 ? parseInt($("#purchase_limits_num").text()) : parseInt($(this).parent().find(".numbox-text").attr("maxstock")),//限时抢购的数量
                        maxstock = $("#stock").length > 0 ? parseInt($("#stock").text()) : parseInt($(this).parent().find(".numbox-text").attr("maxstock"));
                    var maxNum = $("#stock").length > 0 ? parseInt($(".numbox-text").attr("maxNum")) : maxstock;//限时抢购的数量;
                    value = self.siblings(t.defaults.textbox).val();
                    if(value >= maxstock || value >= purchase_limits_num){
                        self.addClass('nodrop');
                        t.overstock(this);
                        $('.textMsg').html('限购商品,活动期间每人限购买'+purchase_limits_num+'件');
                        $('.error-pop').show();
                        return false
                    }else if(value >= maxstock || value >= maxNum){
                        self.addClass('nodrop');
                        t.overstock(this);
                        $('.textMsg').html('您选择的数量超过最大库存');
                        $('.error-pop').show();
                        return false
                    }else{
                        t.roll(self.parent(),value,"up");
                        value++;
                        $.post('//www.kaluli.com/api/checkXbuyNumber',{id:product_id,num:value},function (data) {
                            if(data.status == 1){
                                self.siblings(t.defaults.subtract).removeClass('nodrop');
                                self.removeClass('nodrop');
                                $(".error-msg .busnum").removeClass("show");
                            }else if(data.status == 0){
                                $('.textMsg').html('超过最大购买数量');
                                $('.error-pop').show();
                                t.roll(self.parent(),value,"down");
                                value--;
                            }else {
                                $('.textMsg').html('很抱歉，该活动已结束，<br>下次赶早哦~');
                                $('.error-pop').show();
                                t.roll(self.parent(),value,"down");
                                value--;
                                return false
                            }
                        },'json');
                    }

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

        },
        colsePop:function () {
            $('.close-pop-btn').click(function () {
                window.location.reload();
                $('.error-pop').hide();
            });
        }


    }
    return changenum
})