/**
 * Created by jiangyanghe on 16/10/18.
 * 商品优惠券选取
 * 收货地址运费预计
 */
define(function () {
    "use strict";
    var specificate ={
        defaults:{
            selectCityPop:'.select-city-pop',//选择省市地址
            selectCityTabs: '.select-city-pop-header span',//选择省市的选项卡
            selectCityContent:'.pop-content',
            couponsPop:'.coupons-pop',//优惠券弹框
            provinceIndex:0

        },
      init:function () {
          this.popColeOpen();
          this.selectCityTab();
          this.getProvince();
          // this.getExpressFee();
      } ,
      popColeOpen:function () {//弹框的关闭打开
          var t = this;
          $('.coupons-txt').click(function () {//优惠券弹框显示影藏
              $(t.defaults.couponsPop).show();
          });
          $('.pop-title').click(function () {
              $(t.defaults.couponsPop).hide();
          });
          $('#colse_select_city_pop').click(function () {
              $(t.defaults.selectCityPop).hide();
          });
          $('#open_select_city_pop').click(function () {
              $(t.defaults.selectCityPop).show();
          });
          $('.openTaxPop').hover(function () {//税费弹框显示影藏
              $('.pull-shuifei-pop').addClass('border');
              $('.shuifeiPop').show();
          },function () {
              $('.pull-shuifei-pop').removeClass('border');
              $('.shuifeiPop').hide();
          });
      },
      selectCityTab:function () {
        var t = this;
        $(t.defaults.selectCityTabs).click(function () {
            $(t.defaults.selectCityTabs).removeClass('active');
            $(this).addClass('active');
            $(t.defaults.selectCityContent).hide();
            var v = $(this).attr('id');
            console.log(v);
            if(v == 'select_province'){
                $('.select-provice').show();
            }else if(v == 'select_city'){
                $('.select-city').show();
            }
        });
      },
        getProvince:function () {
            var t = this;
          $('.select-provice li').click(function () {
              $('.select-provice li').removeClass('active');
              var _this = $(this);
              _this.addClass('active');
              $('#select_province').text(_this.text());
              t.defaults.provinceIndex = $(this).attr('data-id');
              console.log(t.defaults.provinceIndex);
              $('.select-provice').hide();
              $('.select-city').show();
              $(t.defaults.selectCityTabs).removeClass('active');
              $('#select_city').addClass('active').text('请选择');
                t.getCity();
          });

        },
        getCity:function () {
            var t = this;
            var provinceindex = t.defaults.provinceIndex;
            $.get("//www.kaluli.com/api/getNextRegionById?type=1&id="+provinceindex,function(data){
                $('.select-city').html(data);
                t.getExpressFee();
            });
        },
        getExpressFee:function () {//城市点击获取运费
            console.log("product_id:"+product_id);//Url 上的ID
            console.log("warehouseId:"+warehouseId);//仓库ID
            var t = this;
            $('.select-city li').click(function () {
                $('.select-city li').removeClass('active');
                var _this = $(this);
                _this.addClass('active');
                $('#select_city').text(_this.text());
                $('.select-provice').hide();
                $('.select-city').show();
                $('#chooseProvince').text($('#select_province').text());
                $('#chooseCity').text($('#select_city').text());
                $(t.defaults.selectCityPop).hide();
                $.get("//www.kaluli.com/api/getExpressInDetail?warehouseId="+warehouseId+"&itemId="+product_id+"&provinceId="+t.defaults.provinceIndex+"", function(data){
                    if(data.status == 1){
                        $('#express_fee').text(data.data);
                    }else{
                        alert(data.msg);
                    }
                },"json")
            });
        }



    };
    return specificate;
});
