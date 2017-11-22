/**
 * Created by jiangyanghe on 16/12/19.
 */
define(["","lib/paracurve"],function(){

    function submit(){
        //if(console)console.log("xBuysubmit模块已加载");
    }
    submit.prototype={
        ableajax:false,
        noerror:true,
        args:null,
        ajaxLoding:false,
        ajaxDone:0,
        init:function(){
            var that = this;
            $(".buy-btn").click(function(){//立即抢购
                if($(this).hasClass('none-btn')){
                    return
                }
                // that.isDone();
                //TODO 输入值和最大值比较
                if(parseInt($('#numbox-text').val()) > parseInt($('#purchase_limits_num').text())){
                    return
                }

                var price = $('#kaluliPrice').text() * $('#numbox-text').val();
                if($('#delivery_area').text().indexOf('南沙保税仓') ==0  &&  price >= 2000){
                    $('.busnum').show().text('超过2000元,暂时无法购买').addClass('show');
                    setTimeout(function(){
                        $('.busnum').hide();
                    },2000);
                    return;
                }else if($('#delivery_area').text().indexOf('宁波保税仓') ==0  &&  price >= 2000){
                    $('.busnum').show().text('超过2000元,暂时无法购买').addClass('show');
                    setTimeout(function(){
                        $('.busnum').hide();
                    },2000);
                    return;
                }else{
                    $.post('//www.kaluli.com/api/checkXbuyNumber',{id:product_id,num:parseInt($('#numbox-text').val())},function (data) {
                        if(data.status == 1){
                            var url = "//www.kaluli.com/order/confirm",
                                obj = ".buy-btn";
                            that.checkPro(obj,url,that.StandardPost);
                        }else if(data.status == 0){
                            $('.textMsg').html('您已参加过本活动，感谢您的支持！');
                            $('.error-pop').show();
                        }else {
                            $('.textMsg').html('很抱歉，该活动已结束，<br>下次赶早哦~');
                            $('.error-pop').show();
                        }
                    },'json');
                }
            });
        },
        checkPro:function(obj,url,fun){
            var that = this;
            if(+$("#numbox-text").val() <= 0){
                that.noerror = false;
                that.ajaxLoding = false;
                $(".error-msg .busnum").addClass("show");
            }
            if(login_flag != 1){//判断是否登录
                var loginurl = $(".unlogin a:eq(1)").attr("href");
                window.location.href= loginurl;
                return false
            }

            if( parseInt($('#count_box').attr('leftSec')) <= 0){//判断活动是否过期
                $('.error-pop').show();
                $('.textMsg').html('很抱歉,该活动已结束,下次赶早哦~');
                return false
            }

            if($('#stock').text() == 0){
                $('.error-pop').show();
                $('.textMsg').html('活动商品已售罄,下次赶早哦~<br>您也可以逛逛其他的 <a href="://www.kaluli.com"></a>');
                return false
            }
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

            //TODO 有没有付款的本活动订单
            /**
             * 1是成功
             * 2是 X抢购有未付款订单
             * 3是 X元购活动结束
             * 4是 X元购商品已售罄
             * 0未知错误
             */
            $.ajax({
                url: '//www.kaluli.com/api/checkXbuyNotPay',
                dataType: 'json',
                data: {
                    id: product_id,
                    skuid:skuid
                },
                success: function (data) {
                    if (data.status == 1) {

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
                                that.ajaxLoding = false;
                                that.noerror = false;
                            }
                        });
                        if(that.noerror){
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
                            fun(url,that.args,that,obj);
                        }
                    } else if(data.status == 2){
                        $('.error-pop').show();
                        $('.textMsg').html('您有未付款的活动订单，<br><a style="color: #3b5cbd" href="//www.kaluli.com/ucenter/order">快去付款吧</a>');
                        return false
                    } else if(data.status == 3) {
                        $('.error-pop').show();
                        $('.textMsg').html('活动商品已结束，下次赶早哦~<br>您也可以逛逛其它的，<a href="//www.kaluli.com">商城首页 >></a>');
                        return false
                    }else if(data.status == 4){
                            $('.error-pop').show();
                            $('.textMsg').html('活动商品已售罄，下次赶早哦~<br>您也可以逛逛其它的，<a style="color: #3b5cbd" href="//www.kaluli.com">商城首页 >></a>');
                            return false
                    }else {
                        $('.error-pop').show();
                        $('.textMsg').html(data.msg);
                        return false
                    }
                }
            });

            //TODO 用户的活动额度是否用完

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
        },
    }

    return submit
})