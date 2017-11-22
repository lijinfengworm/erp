define(["","lib/paracurve"],function(){

    function submit(){
        if(console)console.log("submit模块已加载");
    }
    submit.prototype={
        ableajax:false,
        noerror:true,
        args:null,
        ajaxLoding:false,
        init:function(){
            var that = this;
            $(".buy-btn").click(function(){//立即购买
                var price = $('#kaluliPrice').text() * $('#numbox-text').val();

                if($('#delivery_area').text().indexOf('南沙保税仓') ==0  &&  price >= 2000){
                    $('.busnum').show().text('超过2000元,暂时无法购买').addClass('show');
                    setTimeout(function(){
                        $('.busnum').hide();
                    },2000);
                    return;
                }else if($('#delivery_area').text().indexOf('香港笨鸟仓') ==0  &&  price >= 1000){
                    $('.busnum').show().text('超过1000元,暂时无法购买').addClass('show');
                    setTimeout(function(){
                        $('.busnum').hide();
                    },2000);
                    return;
                }else{
                    var url = "//www.kaluli.com/order/confirm",
                        obj = ".buy-btn";
                    that.checkPro(obj,url,that.StandardPost);
                }
            });
            $(".cart-btn").click(function(){
                var url = "//www.kaluli.com/order/addCart",
                    obj = ".cart-btn";
                if(that.ajaxLoding){
                    return false;
                }
                that.ajaxLoding = true;
                that.checkPro(obj,url,that.cartpost);
            });
            $(".fixed-cart-btn").click(function(){
                var url = "//www.kaluli.com/order/addCart",
                    obj = ".fixed-cart-btn";
                if(that.ajaxLoding){
                    return false;
                }
                that.ajaxLoding = true;
                that.checkPro(obj,url,that.cartpost);
            })
        },
        checkPro:function(obj,url,fun){
            var that = this;
            if(+$("#numbox-text").val() <= 0){
                that.noerror = false;
                that.ajaxLoding = false;
                $(".error-msg .busnum").addClass("show");
            }


            if(login_flag != 1 && (obj == ".cart-btn" ||obj == ".fixed-cart-btn")){
                var loginurl = $(".unlogin a:eq(1)").attr("href");
                window.location.href= loginurl;
                return false
            }

            if($(".busnum").is(":visible")){
                that.noerror = false;
                that.ajaxLoding = false;
            }else{
                that.noerror = true;
            }
            $(".sort").each(function(){
                var self = $(this);
                if(self.find(".cur").length == 0 ){
                    if($(".error-msg-sku").length == 0){
                        $(".error-msg").append('<p class="error-msg-sku show">请选择您要购买的商品</p>');
                    }
                    if(obj == ".fixed-cart-btn"){
                        $(obj).tips("请选择您要购买的商品",{
                            left:$(obj).offset().left,
                            top:$(obj).offset().top + 55
                        });
                    }
                    that.ajaxLoding = false;
                    that.noerror = false;
                }
            });
            if(that.noerror){
                // alert($(".sort").length);
                if($(".sort").length>0){
                    var itemid = $(".sku-content").attr("data-itemid") == undefined ? $(".data-single").attr("data-single-itemid") : $(".sku-content").attr("data-itemid"),
                        skuid = $(".sku-content").attr("data-skuid") == undefined ? $(".data-single").attr("data-single-skuid") : $(".sku-content").attr("data-skuid"),
                        buysnum = $("#numbox-text").val();
                }else{
                    var itemid,skuid,buysnum = $("#numbox-text").val();
                    $.each(sku.detail,function(i,item){
                        itemid = sku.detail[i].itemId;
                        skuid = sku.detail[i].skuId;
                    })
                }
                if(obj == ".buy-btn"){
                    that.args = {
                        id:itemid,
                        skuId:skuid,
                        num:buysnum
                    }
                }else{
                    that.args = {
                        item_id:itemid,
                        sku_id:skuid,
                        number:buysnum
                    }
                }
                // console.log(that.args); return
                fun(url,that.args,that,obj);
            }
        },
        cartpost:function(url,args,obj,btn){
            $.getJSON(url+"?item_id="+args["item_id"]+"&sku_id="+args["sku_id"]+"&number="+args["number"],function(data){
                if(data.status == 0){
                    obj.animation(data.data.img_path,btn);
                    $(".cartnum,.right-nav-cart-num").html(data.data.count);
                }else{
                    $(btn).tips(data.msg,{
                        left:$(btn).offset().left,
                        top:$(btn).offset().top + 55
                    });
                }
                obj.ajaxLoding = false;
            })
        },
        StandardPost:function(url,args){
            var form = $("<form method='post'></form>");
            form.attr({"action":url});
            for (arg in args)
            {
                var input = $("<input type='hidden'>");
                input.attr({"name":arg});
                input.val(args[arg]);
                form.append(input);
            }
            $("body").append(form);
            form.submit();
        },
        animation:function(data,btn){
            var str = '<img id="cart-img-box" style="position: absolute; left:'+($(btn).offset().left*1+100)+'px;top:'+$(btn).offset().top+'px; width:45px; height:45px;" src="'+data+'" />',
                that = this;
            $(str).appendTo('body');
            btn == ".fixed-cart-btn" ? $("#cart-img-box").animate({left:$(".right-nav").offset().left,top:$(".right-nav").offset().top},500,function(){
                $("#cart-img-box").remove();
                that.ajaxLoding = false;
            }) : $("#cart-img-box").paracurve({
                end:[$(".right-nav").offset().left,$(".right-nav").offset().top],
                step:16,
                movecb:function(){
                    $("#cart-img-box").animate({
                        width:20,
                        height:20
                    });
                },
                moveendcb:function(){
                    $("#cart-img-box").remove();
                    that.ajaxLoding = false;
                }
            });
        }
    }

    return submit
})