$(function(){
   orderConfirm.init();
   submit.init();
   lodingData.init();
});

var lodingData = {
     init:function(){
         if(updateFlag*1 == 1){
             $.Jui._showMasks(0.8);
             $("#loding-box").css({
                  left:$.Jui._position($("#loding-box"))[0],
                  top:$.Jui._position($("#loding-box"))[1]
             }).show();
             this.postJson();
         }
     },
     postJson:function(){
         $.post("http://www.shihuo.cn/haitao/updateProductInfo",{productId:productId,goodsId:goodsId},function(data){
                if(data.status*1 == 0){
                    $("#up-data").submit();
                }else{
                    $.Jui._closeMasks();
                    $("#loding-box").hide();
                }
         },"json");
     }
}

var myCardLayer = {
     init:function(){
         $.Jui._showMasks(0.6);
         $(".write-card").css({
              left:$.Jui._position($(".write-card"))[0],
              top:$.Jui._position($(".write-card"))[1] -$.Jui._getpageScroll()
         }).show();
         this.bindFun();
     },
     bindFun:function(){
         var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/; 
         $(".write-card").find("i").click(function(){
             $(".write-card").hide();     
             $.Jui._closeMasks();
         });

         $(".write-card").find(".submit-btn-card").click(function(){
              var txt = $("#my_card").val(),
                  _this = $(this);
              if(reg.test(txt) === false){
                   $(this).tips("请填写正确的身份证号码",{
                        left:$(this).offset().left,
                        top:$(this).offset().top-30
                    })
              }else{
                   $.post("http://www.shihuo.cn/haitao/saveIdentityNumber",{identity_number:txt,address_id:priceValue.address_id},function(data){
                         if(data.status*1 == 0){
                            $(".write-card").hide();
                            $.Jui._closeMasks();
                         }else{
                            _this.tips(data.msg,{
                                left:_this.offset().left,
                                top:_this.offset().top-30
                            });
                         }
                   },"json");
              }
         });
     }
}


var orderConfirm = {
    Verification:{
       name:false,
       card:false,
       phone:false,
       tell:false,
       //address:false,
       detailed:false,
       postal:false
    },
    init:function(){
        this.bindFun();
        this.cityAdd();
        this.remark();
        this.express();
    },
    bindFun:function(){
       var phone = /^1[34578][0-9]{9}$/,
           phonesection = /^[0-9]{3,6}$/,
           phonecode = /^[0-9]{5,10}$/,
           phoneext = /^[0-9]{0,5}$/,
           that = this;
       function checkPhone(){
          var valphone1 = $("input[name='phonesection']").data("check"),
              valphone2 = $("input[name='phonecode']").data("check"),
              valphone3 = $("input[name='phoneext']").data("check");
          if(valphone1 && valphone2){
              if(valphone3 == false){
                that.Verification.tell = false;
              }else{
                 that.Verification.tell = true;
              }
          }else{
               that.Verification.tell = false;
          }
       }

       $(".checkhaitao").click(function(){
              var checked = $(this).attr("checked");
              if(checked != "checked"){
                $(".submit-btn").addClass('submit-btnCC');
              }else{
                $(".submit-btn").removeClass('submit-btnCC');
              }
        });

       $("input[name='name']").blur(function(){
            var val = $(this).val();     
            if($.trim(val) == ""){
                $(this).tips("请填写姓名",{
                    left:$(this).offset().left + $(this).outerWidth() + 10,
                    top:$(this).offset().top
                });
            }else{
               that.Verification["name"] = true;
            }
       });

       $("input[name='card']").blur(function(){
            var val = $(this).val();
            var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;    
            if(reg.test(val) === false){
                $(this).tips("请填写正确的身份证",{
                    left:$(this).offset().left + $(this).outerWidth() + 10,
                    top:$(this).offset().top
                });
            }else{
               that.Verification["card"] = true;
            }
       });

       $("input[name='phone']").blur(function(){
            var val = $(this).val();  
            if(!phone.test(val)){
                $(this).tips("请填写正确的手机号码",{
                    left:$(this).offset().left + $(this).outerWidth() + 10,
                    top:$(this).offset().top
                });
            }else{
                that.Verification["phone"] = true;
            }
       });

       $("input[name='phonesection']").blur(function(){
            var val = $(this).val();  
            if(!phonesection.test(val) && $.trim(val) != ""){
                $(this).tips("区号必须为3到6位数字",{
                    left:$(this).offset().left + $(this).outerWidth() + 10,
                    top:$(this).offset().top
                });
                $(this).data("check",false);
            }else{
               $(this).data("check",true);
            }
            checkPhone();
       });

       $("input[name='phonecode']").blur(function(){
            var val = $(this).val();  
            if(!phonecode.test(val) && $.trim(val) != ""){
                $(this).tips("电话必须为5到10位数字",{
                    left:$(this).offset().left + $(this).outerWidth() + 10,
                    top:$(this).offset().top
                });
                $(this).data("check",false);
            }else{
               $(this).data("check",true);
            }
            checkPhone();
       });

       $("input[name='phoneext']").blur(function(){
            var val = $(this).val();  
            if(!phoneext.test(val) && $.trim(val) != ""){
                $(this).tips("电话分机必须少于6个数字",{
                    left:$(this).offset().left + $(this).outerWidth() + 10,
                    top:$(this).offset().top
                });
                $(this).data("check",false);
            }else{
               $(this).data("check",true);
            }
            checkPhone();
       });


       $("input[name='detailed']").blur(function(){
            var val = $(this).val();  
            if($.trim(val) == ""){
                $(this).tips("请填写详细地址",{
                    left:$(this).offset().left + $(this).outerWidth() + 10,
                    top:$(this).offset().top
                });
            }else{
               that.Verification["detailed"] = true;
            }
       });

       $("input[name='postal']").blur(function(){
            var val = $(this).val();  
            if($.trim(val) == ""){
                $(this).tips("请填写邮政编码",{
                    left:$(this).offset().left + $(this).outerWidth() + 10,
                    top:$(this).offset().top
                });
            }else{
               that.Verification["postal"] = true;
            }
       });

       $("input[name='address']").change(function(event) {
           priceValue.address_id = $(this).val()*1;
           getPrice.getJson();
       });

       $(".new-address").find(".save").click(function(){
           var $this = $(this),
               nameVal = $("input[name='name']").val(),
               cardVal = $("input[name='card']").val(),
               mobileVal = $("input[name='phone']").val(),
               phonesectionVal = $("input[name='phonesection']").val(),
               phonecodeVal = $("input[name='phonecode']").val(),
               phoneextVal = $("input[name='phoneext']").val(),
               provinceVal = $(".sel-1").val(),
               cityVal = $(".sel-2").val(),
               areaVal = $(".sel-3").val(),
               streetVal = $("input[name='detailed']").val(),
               postcodeVal = $("input[name='postal']").val();
               if($("input[name='defaultflag']").attr("checked") == "checked"){
                  defaultflagVal = 1
               }else{
                  defaultflagVal = 0
               }

           if(!that.Verification["name"]){
               $("input[name='name']").blur();
               return false;
           }
           if(!that.Verification["card"]){
               $("input[name='card']").blur();
               return false;
           }
           if(!that.Verification["phone"]){
               $("input[name='phone']").blur();
               return false;
           }
           if($.trim($("input[name='phonesection']").val()) != "" && $.trim($("input[name='phonecode']").val()) != "" && $.trim($("input[name='phoneext']").val()) != ""){
               if(!that.Verification["tell"]){
                   $("input[name='phonecode']").tips("请正确填写电话号码",{
                      left:$("input[name='phonecode']").offset().left + $(this).outerWidth() + 10,
                      top:$("input[name='phonecode']").offset().top
                   });
                   return false;
               }
           }
             
           if(!that.Verification["detailed"]){
               $("input[name='detailed']").blur();
               return false;
           }

           if(!that.Verification["postal"]){
               $("input[name='postal']").blur();
               return false;
           }

           $.get("http://www.shihuo.cn/haitao/saveDeliveryAddress",{name:nameVal,mobile:mobileVal,phonesection:phonesectionVal,phonecode:phonecodeVal,phoneext:phoneextVal,province:provinceVal,city:cityVal,area:areaVal,street:streetVal,postcode:postcodeVal,defaultflag:defaultflagVal,identity_number:cardVal},function(data){
                if(data.status*1 == 1){
                    $this.tips(data.msg);
                }else{
                    $(".addressBox").find("ul").eq(0).prepend('<li>\
                        <div class="clearfix">\
                            <div class="area-main">\
                                <input name="address" autocomplete="off" value="'+data.data.id+'" type="radio" /> '+data.data.address+'\
                            </div>\
                        </div>\
                    </li>');
                    $("input[name='address']").eq(0).attr("checked","checked");
                    priceValue.address_id = $("input[name='address']:checked").val()*1;
                    $(".new-address").hide();
                    getPrice.getJson();
                    that.clearAddress();
                }
           },"json");
       });

       $(".add_new_address").change(function() {
            $(".new-address").show();
       });

       $(".coupon-box").find("li").click(function(){
             var couponId = $(this).attr("coupon_id"),
               $this = $(this);
              if($this.hasClass('on')){
                  couponId = "";
              }
             $.post("http://www.shihuo.cn/haitao/orderDetailConfirmLipinka",{product_id:priceValue.productId,goods_id:priceValue.goodsId,num:priceValue.number,coupon_id:couponId},function(data){
                  if(data.status*1 == 0){
                      if($this.hasClass('on')){
                          $("#price_up").html("￥"+data.data.total_price);
                          $this.removeClass('on');
                      }else{
                          $this.attr("check") == "1";
                          $("#price_up").html("￥"+data.data.total_price);
                          $this.addClass('on');
                          $this.siblings().removeClass('on');
                      }
                      priceValue.coupon_id = couponId;
                  }
            },"json");
       });
    },
    clearAddress:function(){
         $("input[name='name']").val("");
         $("input[name='phone']").val("");
         $("input[name='phonesection']").val("");
         $("input[name='phonecode']").val("");
         $("input[name='phoneext']").val("");
         $("input[name='detailed']").val("");
         $("input[name='postal']").val("");
         $("input[name='defaultflag']").removeAttr("checked");
    },
    cityAdd:function(){
        var obj = $(".select_city"),
            that = this;
        obj.find(".sel-1").change(function(event) {
             var val = $(this).val();
             $(".select_city").find(".sel-2").html('<option value="0">请选择</option>');
             $(".select_city").find(".sel-3").html('<option value="0">请选择</option>');
             $.get("http://www.shihuo.cn/haitao/getRegionByRegionId?region_id="+val,function(data){
                  that.cityAddstr(data,$(".select_city").find(".sel-2"));
             },"json");
        });

        obj.find(".sel-2").change(function(event) {
             var val = $(this).val();
             $.get("http://www.shihuo.cn/haitao/getRegionByRegionId?region_id="+val,function(data){
                   that.cityAddstr(data,$(".select_city").find(".sel-3"));
             },"json");
        });
    },
    cityAddstr:function(o,d){
       var str = [];
       for(var i=0,len=o.data.length;i<len;i++){
           str.push('<option value="'+o.data[i].region_id+'">'+o.data[i].region_name+'</option>');
           str.join('');
       }
       d.html('<option value="0">请选择</option>'+str);
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
    express:function(){
         $("input[name='kuaidi']").change(function(){
           priceValue.type = $("input[name='kuaidi']:checked").val()*1;
           getPrice.getJson();
        });
    }
}

var priceValue = {
     productId:productId,
     goodsId:goodsId,
     address_id:$("input[name='address']:checked").val()*1,
     type:$("input[name='kuaidi']:checked").val()*1,
     order_number:$("input[name='order_number']").val(),
     number:num,
     from:from,
     coupon_id:"",
     remark:""
}

var getPrice = {
    init:function(){

    },
    getJson:function(callback){
        var submitBox = $(".submit-btn"),
            str;
        $.post("http://www.shihuo.cn/haitao/getOrderTotalPrice",{productId:priceValue.productId,goodsId:priceValue.goodsId,address_id:priceValue.address_id,type:priceValue.type,number:priceValue.number,from:priceValue.from,remark:priceValue.remark,attr:priceValue.attr},function(data){
              $("#change1").html(data.data.fee);
              $("#all").html(data.data.total_price);
              !!callback && callback(data);
        },"json");
    }
}

var submit = {
   ajaxLoding:false,
   init:function(){
      this.bindFun();
   },
   bindFun:function(){
      var that = this;
      if(that.ajaxLoding){
          return false;
      }
      that.ajaxLoding = true;
      $(".submit-btn").click(function(){
          var $this = $(this);
          if($this.hasClass('submit-btnCC')){
             return false
          }
          $this.addClass('submit-btnCC');
          $.post("http://www.shihuo.cn/haitao/submitOrder",{productId:priceValue.productId,goodsId:priceValue.goodsId,address_id:priceValue.address_id,type:priceValue.type,number:priceValue.number,from:priceValue.from,remark:priceValue.remark,order_number:priceValue.order_number,coupon_id:priceValue.coupon_id},function(data){
                if(data.status == 0){
                    window.location.href = data.data.url;
                }else if(data.status*1 == 2){
                     myCardLayer.init();
                     $this.removeClass('submit-btnCC');
                }else{
                    $this.removeClass('submit-btnCC');
                     
                    $this.tipsFun(data.msg);
                }
                that.ajaxLoding = false;
          },"json");
     });
   }
}



!(function($){
  function tips(a,arr) {
      return this.each(function() {
          var $this = $(this),
              str = '<div class="tips_layer" style="position: absolute; border-radius:5px; background-color:#ff6600; display:none; z-index:995">\
                <div class="tips-text" style="padding:2px 5px; line-height:18px; color:#fff;">'+a+'</div>\
                <div class="diamond"></div>\
            </div>';
           if($(".tips_layer")){
              $(".tips_layer").remove();
           }
          $(str).appendTo("body");
          var $tips_text = $(".tips-text"),
                  $tips_layer = $(".tips_layer");
          if(arr){
             $tips_layer.css({
                "top": arr.top,
                "left": arr.left
              }).show();
          }else{
            $tips_layer.css({
              "top": $this.offset().top - parseInt($this.height())-10,
              "left": $this.offset().left + parseInt($this.width()/2) -30
            }).show();
          }
          setTimeout(function(){
             $tips_layer.remove();
          },2000);
      });
  }

  function tipsFun(a){
      var $this = $(this);
      return this.each(function(){
          var str = '<div class="tips-base-layer" style="position: absolute; padding:5px 7px 7px 7px; font-size:14px; color:#fff; border-radius:5px; background-color:#000; opacity:0.8; display:none; z-index:995"><img style="position: relative; top:-1px;" src="/images/trade/haitao/oder-in.png" /> '+a+'<div style="position: absolute; right:60px; top:32px;"><img src="/images/trade/haitao/oder-in2.png" /></div></div>';
          if($(".tips-base-layer")){
              $(".tips-base-layer").remove();
           }

           $(str).appendTo("body");
           $(".tips-base-layer").css({
              "top": $this.offset().top - parseInt($(".tips-base-layer").height())-20,
              "left": $this.offset().left - parseInt($(".tips-base-layer").width()) + $this.width()
           }).show();
           setTimeout(function(){
             $(".tips-base-layer").remove();
          },2000);
      });
  }

  $.Jui = $.Jui || {};
    $.extend($.Jui, {
        // version: "1.0",
        _$: function(a, b) {
            a.siblings().removeClass(b);
            a.addClass(b);
        },
        _showMasks: function(a) {
            var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:" + $(document).height() + "px; background-color:#000;  z-index:91;'></div>";
            $("body").append(str);
            $(".body-mask").css("opacity", 0);
            $(".body-mask").animate({
                "opacity": a ? a : "0.8"
            });
        },
        _closeMasks: function() {
            var close = $(".body-mask");
            close.fadeOut(function() {
                close.remove();
            });
        },
        _getpageSize: function() {
            /*
             height:parseInt($(document).height()),
             width:parseInt($(document).width())
             */
            var de = document.documentElement, arrayPageSize,
                    w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth,
                    h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
            arrayPageSize = [w, h]
            return arrayPageSize;
        },
        _position: function(obj) {//计算对象放置在屏幕中间的值   obj:需要计算的对象
            var left = ((this._getpageSize()[0] - parseInt(obj.outerWidth())) / 2);
            var top = ((this._getpageSize()[1] - parseInt(obj.outerHeight())) / 2) + $.Jui._getpageScroll();
            return [left, top];
        },
        _getpageScroll: function() {
            var yScrolltop;
            if (self.pageYOffset) {
                yScrolltop = self.pageYOffset;
            } else if (document.documentElement && document.documentElement.scrollTop) {
                yScrolltop = document.documentElement.scrollTop;
            } else if (document.body) {
                yScrolltop = document.body.scrollTop;
            }
            return yScrolltop;
        },
        isie: !!$.browser.msie,
        isie6: (!!$.browser.msie && parseInt($.browser.version) <= 6),
        DOC: $(document),
        WIN: $(window),
        HEAD: $(document).find("head"),
        BODY: $(document).find("body")
    });


  $.fn.tips = tips;
  $.fn.tipsFun = tipsFun;
})(jQuery);