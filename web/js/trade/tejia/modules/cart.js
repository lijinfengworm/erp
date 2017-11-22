define(["paracurve","tips"],function(){
    var cart = {
        ajaxLoding:false,
        init:function(btn){
            if($(btn).length > 0){
                this.bindFun(btn);
            }
        },
        bindFun:function(btn){
            var obj = $(btn),
                that = this;
            obj.on("click",function(e){
                var product_id = $(this).attr("data-productId"),
                    goods_id = $(this).attr("data-goodsId"),
                    $this = $(this);
                if(that.ajaxLoding){
                    return false;
                }
                that.ajaxLoding = true;
                $.post("http://www.shihuo.cn/haitao/addCart",{product_id:product_id,goods_id:goods_id,number:1,from:""},function(data){
                    if(data.status*1 == 0){
                        $("#cart-right-area .goods-num").html(data.data.count);
                        $("#cart_num_nva").html(data.data.count);
                        that.animate(data.data.img_path,$this);
                    }

                    if(data.status*1 == 1){
                        var top = btn == "#fixed_buy_cart" ?  50 : -30;
                        $this.tips(data.msg,{
                            left:$this.offset().left + 30,
                            top:$this.offset().top + top*1
                        });
                        that.ajaxLoding = false;
                    }

                    if(data.status*1 == 2){
                        commonLogin('hupu');
                        that.ajaxLoding = false;
                    }
                },"json");
            });
        },
        animate:function(data,btn){
            var str = '<img id="cart-img-box" style="position: absolute; left:'+($(btn).offset().left*1+10)+'px;top:'+$(btn).offset().top+'px; width:45px; height:45px;" src="'+data+'" />',
                that = this;
            $(str).appendTo('body');
            btn == "#fixed_buy_cart" ? $("#cart-img-box").animate({left:$("#cart-right-area").offset().left,top:$("#cart-right-area").offset().top},500,function(){
                $("#cart-img-box").remove();
                that.ajaxLoding = false;
            }) : $("#cart-img-box").paracurve({
                end:[$("#cart-right-area").offset().left,$("#cart-right-area").offset().top],
                step:10,
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
    };

    return cart
});