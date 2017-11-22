/**
 * Created by jiangyanghe on 16/11/21.
 */
define(["modules/order/priceValue","getPrice"],function(priceValue,getPrice){
    var redeem = {
        ajaxLoding:false,
        init:function(){
            this.redeemSubmit();
            this.couponClick();
        },
        /**
         * 兑换优惠券
         * ajax URL //www.kaluli.com/api/getLipinka
         * account 优惠券卡号
         * code 验证码 订单页可以为空
         * item_ids 商品ID如[10,129]
         * total_price 商品总价,判读优惠券是都满减
         */
        redeemSubmit:function () {
            var item_ids = [];
            $('.progrid').each(function () {
                item_ids.push($(this).attr('data-id'));
            });
            $('input[name=redeemCode]').focus(function () {
                $('.error-tips').hide();
            });
            $('.redeem-btn').click(function () {
                $(this).text('正在提交...').css({'background-color':'#999'}).attr('disabled','disabled');
                var redeemCode = $('input[name=redeemCode]').val();
                var total_price = $('.price-id-1').attr('data-price');
                if(redeemCode == ''){
                    $('.redeem-btn').text('提交').removeAttr('disabled').css('background-color','#f35357');
                    return
                }
                $.ajax({
                    url: '//www.kaluli.com/api/getLipinka',
                    dataType: 'json',
                    data: {
                        account:redeemCode,
                        code:'',
                        item_ids:item_ids,
                        total_price:total_price
                    },
                    xhrFields: {
                        withCredentials: true
                    },
                    crossDomain: true,
                    success: function (data){
                        $('.redeem-btn').text('提交').removeAttr('disabled').css('background-color','#f35357');
                        if(data.status != 0){
                            $('.error-tips').show().text(data.msg);
                        }else {
                            $('#redeemCode').text(redeemCode);
                            $('.successReddem').show();
                            $('.normalReddem').hide();
                            setTimeout(function () {
                                $('.successReddem').hide();
                                $('.normalReddem').show();
                            },2000);


                            var line = '',
                                item=data.data,
                                flag='',
                                current='',
                                style='',
                                tips='',
                                is_simple='',
                                coupon_text='',
                                amount='';

                            $('#coupon-list tr').remove();
                            for (var i=0;i<item.length;i++){
                                if(!item[i].flag){
                                    flag = 'class = "coupon-getoff"';
                                    style = ' style="color:#cccccc;" ';
                                    tips = '<p>该订单中无符合条件商品</p>'
                                }else{
                                    style='';
                                    flag = '';
                                    tips=''
                                }
                                if(!item[i].is_simple){
                                    is_simple = "使用范围：指定商品可用";
                                }else{
                                    is_simple = "使用范围：卡路里商品";
                                }

                                if(item[i].current == 1){
                                    current = 'icon-check-nosprite'
                                }else{current = ''}
                                if( item[i].card_limit != '' && item[i].card_type == 1){
                                    if(item[i].card_limit ){
                                        amount = '满 '+ item[i].card_limit_parse.order_money+'元减'+item[i].amount+'元'
                                    }else{
                                        amount = item[i].amount + '元'
                                    }
                                }else{
                                    if(item[i].card_limit ){
                                        amount = '会员权益'+item[i].acoount_parse.account;
                                    }else{
                                        amount = item[i].amount + '元'

                                    }
                                }

                                if(item[i].card_type == 2){
                                    coupon_text = '最高优惠：'+item[i].top_limit+'元';
                                }else{
                                    coupon_text = '';
                                }

                                line += '<tr data-coupontype="'+item[i].card_type+'" data-couponid="'+item[i].id+'" '+flag +'>' +
                                    '<td class="td1"><i class="'+current+'"></i></td>' +
                                    '<td class="td2">' +
                                    '优惠券:<div class="coupon-text">'+amount+'</div>' +
                                    '</td>' +
                                    '<td class="td3">'+is_simple+'</td>' +
                                    '<td class="td4">有效期至：'+item[i].etime +'</td>' +
                                    '<td class="td5">'+coupon_text+'</td>' +
                                    '</tr>';
                            }

                            $('#coupon-list').append(line);

                            if($("#cart_data").length > 0 ){
                                getPrice.cartJson();
                            }else{
                                getPrice.orderJson();
                            }


                        }
                        redeemCode = $('input[name=redeemCode]').val('');
                    },
                    error:function(){
                        $('.redeem-btn').text('提交').removeAttr('disabled').css('background-color','#f35357');
                        $('.error-tips').show().text(data.msg);
                    }
                },'json');
            });
        },
        couponClick:function () {
            //优惠券
            $("#coupon-list").on('click','i',function(){
                if($(this).parents("tr").hasClass("coupon-getoff")){
                    return false
                }
                if($(".address-list li").length <=1 ){
                    $.Jui._showMasks(0.6);
                    var dom = "<div id='error-info' style='position:fixed;width:200px;line-height:50px;left:50%;top:50%;margin:-15px 0 0 -50px;z-index:101;background-color:#FFFFFF;font-size:16px;text-align: center;color:red;border-radius: 10px;'>请先添加地址</div>";
                    $("body").append(dom);
                    setTimeout(function(){
                        $("#error-info").remove();
                        $.Jui._closeMasks(0.6);
                    },1000);
                    return false
                }
                var $this = $(this);
                if($this.hasClass("icon-check-nosprite")){
                    $(this).removeClass("icon-check-nosprite");
                    $('.activity-list').show();
                }else{
                    $(".td1 i",".coupon-table").removeClass("icon-check-nosprite");
                    $(this).addClass("icon-check-nosprite");
                    //如果有会员权益 隐藏原来的打折活动
                    if($this.parents('tr').attr('data-coupontype') == 2){
                        $('.activity-list').hide();
                    }else{
                        $('.activity-list').show();
                    }
                }
                if($("#cart_data").length > 0 ){
                    getPrice.cartJson();
                }else{
                    getPrice.orderJson();
                }
            })
        }
    };

    return redeem
});