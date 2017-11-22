define(["modules/order/priceValue"],function(priceValue){
    var getPrice = {
      init:function(){
        
      },
      getJson:function(callback){
        if($("#cart_data").length > 0 ){
          this.cartJson(callback);
        }else{
          this.orderJson(callback);
        }        
      },
      orderJson:function(callback){
        var url = "//www.kaluli.com/api/getOrderPrice",
            item_id = $("#item_id").val(),
            sku_id = $("#sku_id").val(),
            buysnum = $("#numbox-text").text(),
            address_id = $(".icon-check-nosprite").parent().attr("data-value"),
            card_id = $(".icon-check-nosprite","#coupon-table").parents("tr").attr("data-couponid") || null,
            card_type = $(".icon-check-nosprite","#coupon-table").parents("tr").attr("data-coupontype") || 1,
            freight = $(".freight").text(),
            express_type= $('.shipping-btn .icon-check-nosprite').attr('data-type'),//快递公司
            t = this;

          $.post(
              url,
              {
                  id:item_id,
                  skuId:sku_id,
                  num:buysnum,
                  address_id:address_id,
                  card_id:card_id,
                  card_type: card_type,
                  express_type:express_type
              },function(data){
            var datas = $.parseJSON(data);

            callback && callback(datas);
            if(datas.code == 1 ){
                if(!card_id){
                    $(".numbox").tips(datas.msg,{
                        left:$(".numbox").offset().left + $(".numbox").outerWidth() + 10,
                        top:$(".numbox").offset().top
                    });
                    return
                }else{
                    $("#coupon-table").tips(datas.msg,{
                        left:$("#coupon-table").offset().left +130,
                        top:$("#coupon-table").offset().top-40
                    });
                }
                if(datas.data.activity){
                    $(".price-id-6").text(datas.data.activity.activity_save+"￥");
                }
            }else{

                // if(card_id && card_id == 1){
                //   $('.activity-list').show();
                //     $(".price-id-5").text("￥"+datas.data.coupon_fee);
                // }else{
                //     $(".price-id-5").text("￥0");
                //     $('.activity-list').hide();
                //
                // }
            }
            t.getLogistics(datas);
            $('.dutyFee').text('￥'+datas.data.total_tax_fee);//税费


            $(".price-id-1").text('￥'+datas.data.total_product_price);
            $(".price-id-2").text('-￥'+datas.data.activity.activity_save);
            $(".price-id-3").text('￥'+datas.data.total_express_fee);
            $(".price-id-4").text('￥'+datas.data.total_tax_fee);
            if(card_id && card_type == 1){//优惠券存在
                $(".price-id-5").text('-￥'+datas.data.coupon_fee);
            }else {
                $(".price-id-5").text('-￥0')
            }
            $(".price-id-6").text(datas.data.total_price);
              //单个商品的税费更改
              for(var item in datas.data.express_list){
                  $('.shipping-btn > i').each(function () {
                      if(datas.data.express_list[item]['express_type'] == $(this).attr('data-type')){
                          $(this).parent().find('.fee').text('￥'+datas.data.express_list[item]['fee']);
                      }

                  })
              }


        });
      },
      cartJson:function(callback){

          var list = [];
          $('.shipping-btn > .icon-check-nosprite').each(function () {
              var expressType={};
              expressType["houseId"] = $(this).attr('houseId');
              expressType["dataType"] = $(this).attr('data-type');
              list.push(expressType);
              // myMap.set($(this).attr('houseId'),$(this).attr('data-type'));
          });
          var jsonString = JSON.stringify(list);//获取当前选中的仓库对应的快递
        var url = "//www.kaluli.com/order/cartFreight",
            data = $("#cart_data").attr("value"),
            address_id = $(".icon-check-nosprite").parent().attr("data-value"),
            card_id = $(".icon-check-nosprite","#coupon-table").parents("tr").attr("data-couponid") || null,
            card_type = $(".icon-check-nosprite","#coupon-table").parents("tr").attr("data-coupontype") || 1,

            express_type= priceValue.express_type,
            express_types= jsonString,
            t = this;

        $.post(url,{data:data,address_id:address_id,card_id:card_id,card_type:card_type,express_type:express_type,express_types:express_types},function(datas){
            var da = $.parseJSON(datas);

            if(da.code == 1){
                $("#coupon-table").tips(da.msg,{
                    left:$("#coupon-table").offset().left +130,
                    top:$("#coupon-table").offset().top-40
                });
                if("undefined" != typeof da.data.activity && $("#product-discount").length > 0){
                    $("#product-discount i").text(da.data.activity.list.now.price);
                }
                if(da.data.activity){
                    $(".price-id-6").text(da.data.activity.activity_save+"￥");
                }
            }else{
                $(".price-id-1").text('￥'+da.data.total_data.total_product_price);
                $(".price-id-2").text('-￥'+da.data.total_data.activity.activity_save);
                $(".price-id-3").text('￥'+da.data.total_data.total_express_fee);
                $(".price-id-4").text('￥'+da.data.total_data.total_tax_fee);
                if(card_id && card_type == 1){//优惠券存在
                    $(".price-id-5").text('-￥'+da.data.total_data.coupon_fee);
                }else {
                    $(".price-id-5").text('-￥0')
                }
                $(".price-id-6").text(da.data.total_data.total_price);
                //单个商品的税费更改
                for(var item in da.data.wareHouseInfo){
                    $('.shipping-btn > i').each(function () {
                        if(item==$(this).attr('houseId')){
                            // console.log(JSON.stringify(da.data.wareHouseInfo[item])+'@@@@@');
                            // console.log(da.data.wareHouseInfo[item]['dutyFee']+'####');
                            $(this).parents('.houseName').find('.dutyFee').text('￥'+da.data.wareHouseInfo[item]['dutyFee']);
                            for(var i=0; i<da.data.wareHouseInfo[item]['expressList'].length;i++){
                                if(da.data.wareHouseInfo[item]['expressList'][i]['express_type'] == $(this).attr('data-type')){
                                    $(this).parent().find('.fee').text('￥'+da.data.wareHouseInfo[item]['expressList'][i]['fee']);
                                }
                            }
                        }
                    })
                }
            }


            t.getLogistics(da);
            callback && callback(da);
        });  
      },
      getLogistics:function(data){
          var shunfeng_freight = data.data.shunfeng_freight == 0 ? "免邮" : data.data.shunfeng_freight+"元";
          $("#shunfeng").text( data.data.shunfeng_freight);
          if($("#shunfeng_tips").length > 0)   $("#shunfeng_tips").text(shunfeng_freight);
      }
  }
  return getPrice
})