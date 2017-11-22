define(["modules/order/priceValue","modules/common/CookieUtil"],function(priceValue,CookieUtil){

    var submit = {
       ajaxLoding:false,
       identifyingLoding:false,
       uid:0,
       init:function(){
          this.getVerify();
          this.getPassportIdentifyingCode();
          this.bindFun();
          this.remark();
       },
       remark:function(){
          $("#textatea-shuoming").focus(function(){
                if($(this).val() == $(this).attr("data")){
                   $(this).val(""); 
                }
          });
          $("#textatea-shuoming").blur(function(){
                if($(this).val() == $(this).attr("data") || $.trim($(this).val()) == ""){
                   $(this).val($(this).attr("data")); 
                   priceValue.remark = "";
                }else{
                   priceValue.remark = $(this).val();
                }
          });
      },
      getVerify:function(){
          var val = 0;
          $("#if_login").click(function(){
             val += 1;
             $(this).prev().attr("src","//www.kaluli.com/api/verify?v="+val);
          });

          $("#verify_img").click(function(event) {
            $("#if_login").click();
          });
      },
      getPassportIdentifyingCode:function(){
         var thaF = this;
         $("#identifying_Code").click(function(){
             var that = $(this);
             if(thaF.identifyingLoding){
                return false;
             }
             thaF.identifyingLoding = true;
             if(that.hasClass('false')){
                return false;
             }
             $.post("//www.kaluli.com/passport/sendAuthCode",{mobile:$("#identifyingCode_1").val(),verify:$('#identifyingCode_2').val(), check:3,_csrf_token:$('#_csrf_token').val()},function(data){
                      if(data.status == 0){
                          that.tipsFun(data.msg);
                          $("#if_login").click();
                      }else{
                          var t = 60,
                              intval;
                          $(".security-info").show();
                          that.addClass('false');
                          that.html("60秒后重发");
                          intval = setInterval(function(){
                              if(t>0){
                                 t-=1;
                                 that.html(t+"秒后重发");
                              }else{
                                 that.removeClass('false');
                                 that.html("获取短信验证码");
                                 thaF.identifyingLoding = false;
                                 $(".security-info").hide();
                                 clearInterval(intval);
                              }
                          },1000);
                      }
                      thaF.identifyingLoding = false;
             },"json");
         });
           
      },
       bindFun:function(){
          var that = this;      
          $(".submit-btn").click(function(){
              var $this = $(this),x;
              if(that.ajaxLoding){
                  return false;
              }
              that.ajaxLoding = true;
              var arrayAtr = {
                 name : $("input[name='name']").val(),
                 identity_number : $("input[name=card]").val(),
                 mobile : $("input[name='phone']").val(),
                 phonesection : $("input[name='phonesection']").val(),
                 phonecode : $("input[name='phonecode']").val(),
                 phoneext : $("input[name='phoneext']").val(),
                 province : $(".sel-1").val(),
                 city : $(".sel-2").val(),
                 area : $(".sel-3").val(),
                 street : $("input[name='detailed']").val(),
                 postcode : '000000'
              }
              if($("#identifyingCode_1").val() == "" || $("#identifying_Code_post").val() == ""){
                   $this.tipsFun("请填手机号和验证码");
                   that.ajaxLoding = false;
                   return false;
              }
              for(x in arrayAtr){
                  if(x != "phonesection" && x != "phonecode" && x != "phoneext"){
                       if(arrayAtr[x] == ""){
                           $this.tipsFun("请填写完整的收货信息");
                           that.ajaxLoding = false;
                           return false;
                           break;
                       }
                  }
              }
              $.post("//www.kaluli.com/passport/login_active",{mobile:$("#identifyingCode_1").val(),authcode:$("#identifying_Code_post").val(), activity:2, auto_user: 1},function(data){
                    if(data.status == 0){
                          $this.tipsFun(data.msg);
                      }else{
                             $.post("//www.kaluli.com/api/editAddress",{address:arrayAtr},function(data){
                                   if(data.code*1 == 0){
                                         priceValue.address_id = data.data.data.id;
                                         that.orderSubmit();
                                   }else{
                                       $this.tipsFun(data.msg);
                                       that.ajaxLoding = false;
                                   }
                             },"json");
                      }
              },"json");
         });

           $('.icon-wenhao').hover(function () {
               $('.J-explain-pop').show();
           }, function () {
               $('.J-explain-pop').hide();
           });
       },
       orderSubmit:function(){
           //TODO 判断南沙仓是否超过2000元
           var shipAddress = $('.order-list').find('h4').text().trim(),
               tPrice = $('#total-price').text(),
               purchaser = '',card_number = '';

           if(shipAddress == '宁波保税仓'){
               purchaser = $("input[name='purchaser']").val();
               card_number = $("input[name='card_number']").val();
           }
           if(shipAddress == '南沙保税仓' && tPrice >= 2000 || shipAddress == '宁波保税仓' && tPrice >= 2000){
               $('#over_price_tip').show();
           }else{
               var list = [];
               $('.shipping-btn > .icon-check-nosprite').each(function () {
                   var expressType={};
                   expressType["houseId"] = $(this).attr('houseId');
                   expressType["dataType"] = $(this).attr('data-type');
                   list.push(expressType);
               });
               var jsonString = JSON.stringify(list);//获取当前选中的仓库对应的快递

              var datas = {
                  product_id :priceValue.productId,
                  goods_id:priceValue.goodsId,
                  address_id:priceValue.address_id,
                  number:priceValue.number,
                  remark :priceValue.remark,
                  card_id : $(".icon-check-nosprite","#coupon-table").parents("tr").attr("data-couponid") || null,
                  card_type : $(".icon-check-nosprite","#coupon-table").parents("tr").attr("data-coupontype") || 1,
                  express_type:$('.shipping-btn > .icon-check-nosprite').attr('data-type'),
                  express_types:jsonString,
                  purchaser:purchaser,
                  card_number:card_number
                  },
              that = this;
              $.post("//www.kaluli.com/api/submitOrder",{data:datas},function(data){
                  var data_callback = typeof data == "string" ? $.parseJSON(data) : data;
                  if(data_callback.status  == 1){
                      window.location.href = data_callback.data.pay_url;
                  }else{
                      $(".submit-btn").removeClass('submit-btnCC');
                      $(".submit-btn").tipsFun(data_callback.info);
                      that.ajaxLoding = false;
                      return;
                  }
              },"json");
           }
       },
       cartSubmit:function(){
          var datas = {
              cart_data: $("#cart_data").attr("value"),
              remark : priceValue.remark,
              address_id : priceValue.address_id,
              card_id : $(".icon-check-nosprite","#coupon-table").parents("tr").attr("data-couponid") || null,
              card_type : $(".icon-check-nosprite","#coupon-table").parents("tr").attr("data-coupontype") || 1,
              express_type:priceValue.express_type
              },
          that = this;

          $.post("//www.kaluli.com/order/cartSubmit",datas,function(res){
              var data = typeof res == "string" ? $.parseJSON(res) : res;
              var data_callback = typeof data == 'string' ? $.parseJSON(data) : data;
              if(data_callback.status == 1){
                  window.location.href = data_callback.data.pay_url;
              }else{
                  $(".submit-btn").removeClass('submit-btnCC');
                  $(".submit-btn").tips(data_callback.info);
                  that.ajaxLoding = false; 
              }                                       
          },"json");
       }
    }
    return submit
})