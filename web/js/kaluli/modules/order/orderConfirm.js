define(["modules/order/priceValue","getPrice","modules/order/redeem"],function(priceValue,getPrice,redeem){
    /**
     * 付款页面
     * @type {{Verification: {name: boolean, phone: boolean, tell: boolean, detailed: boolean, postal: boolean}, init: orderConfirm.init, bindFun: orderConfirm.bindFun, callback: orderConfirm.callback, clearAddress: orderConfirm.clearAddress, cityAdd: orderConfirm.cityAdd, cityAddstr: orderConfirm.cityAddstr, cupon: orderConfirm.cupon, logistics: orderConfirm.logistics}}
     */
    var orderConfirm = {
        Verification:{
           name:false,
           phone:false,
           tell:false,
           //address:false,
           detailed:false,
           postal:true
        },
        init:function(fn){            
            this.bindFun();
            this.cityAdd();
            this.cupon();
            this.logistics();
            redeem.init();
        },
        bindFun:function(){

            $('.shuifeipop').hover(function () {
                $(this).find('.shuifeipop-content').show();
            },function () {
                $(this).find('.shuifeipop-content').hide();
            });

           var phone = /^1[34578][0-9]{9}$/,
               phonesection = /^[0-9]{3,6}$/,
               phonecode = /^[0-9]{5,10}$/,
               phoneext = /^[0-9]{0,5}$/,
               that = this,
               iserror = false;
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

           $("input[name='name']").blur(function(){            
                var val = $(this).val();     
                if($.trim(val) == ""){
                    $(this).tips("请填写姓名",{
                        left:$(this).offset().left + $(this).outerWidth() + 10,
                        top:$(this).offset().top
                    });
                    that.Verification["name"] = false;
                    iserror = true;
                }else{                    
                   that.Verification["name"] = true;
                   iserror = false;
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
                    iserror = true;
                    that.Verification["card"] = false;
                }else{
                   that.Verification["card"] = true;
                   iserror = false;
                }
           });

           $("input[name='phone']").blur(function(){
                var val = $(this).val();  
                if(!phone.test(val)){
                    $(this).tips("请填写正确的手机号码",{
                        left:$(this).offset().left + $(this).outerWidth() + 10,
                        top:$(this).offset().top
                    });
                    iserror = true;
                    that.Verification["phone"] = false;
                }else{
                    that.Verification["phone"] = true;
                    iserror = false;
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
                    iserror = true;
                }else{
                   $(this).data("check",true);
                   iserror = false;
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
                    iserror = true;
                }else{
                   $(this).data("check",true);
                   iserror = false;
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
                    iserror = true;
                }else{
                   $(this).data("check",true);
                   iserror = false;
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
                    iserror = true;
                    that.Verification["detailed"] = false;
                }else{
                   that.Verification["detailed"] = true;
                   iserror = false;
                }
           });

           $("input[name='address']").change(function(event) {
               //priceValue.address_id = $(this).val()*1;
               getPrice.getJson();
           });

            //新增收货地址保存
           $(".address-editor").find(".save").click(function(event){
                $(".address-editor").find("input[type=text]").trigger('blur');
               //event.stopPropagation();   
               var $this = $(this),
                   id = $this.parent().attr("data-value"),
                   nameVal = $("input[name='name']").val(),
                   identity_number = $("input[name=card]").val(),
                   mobileVal = $("input[name='phone']").val(),
                   phonesectionVal = $("input[name='phonesection']").val(),
                   phonecodeVal = $("input[name='phonecode']").val(),
                   phoneextVal = $("input[name='phoneext']").val(),
                   provinceVal = $(".sel-1").val(),
                   cityVal = $(".sel-2").val(),
                   areaVal = $(".sel-3").val(),
                   streetVal = $("input[name='detailed']").val(),
                   postcodeVal = '000000',//邮编默认是000000
                   is_identify =  $("input[name='is_identify']").val();
                   if($("input[name='defaultflag']").attr("checked") == "checked"){//默认收货地址
                      defaultflagVal = 1
                   }else{
                      defaultflagVal = 0
                   }      
                   
                   if($(".a-l-box li").length < 3 || $(".defaultsAddress").length == 0){
                      defaultflagVal = 1
                   }  

               if(!that.Verification["name"]){                    
                   $("input[name='name']").blur();
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

               var address={
                  id: id,
                  identity_number:identity_number,
                  name:nameVal,
                  mobile:mobileVal,
                  phonesection:phonesectionVal,
                  phonecode:phonecodeVal,
                  phoneext:phoneextVal,
                  province:provinceVal,
                  city:cityVal,
                  area:areaVal,
                  street:streetVal,
                  postcode:postcodeVal,
                  is_identify:is_identify,
                  defaultflag:defaultflagVal
               }          
          
               $.post("//www.kaluli.com/api/editAddress",{address:address},function(data){
                    if((-1)*data.code == 10){
                        $.each(data.data.data,function(i,item){
                          $this.tips(item);
                        });
                    }else{                                   
                      if(id != ""){  //编辑的逻辑
                        var index = $(".address-editor").attr("data-index");
                        $(".a-l-box li:eq("+index+")").find("p").html(data.data.data.data);
                          if(is_identify == 1){
                              // console.log("222@@");
                              console.log($(".a-l-box li:eq("+index+")"));
                              $(".a-l-box li:eq("+index+") .add-cart").hide();
                          }
                        if(defaultflagVal == 1 ){
                           $(".defaultsAddress").remove();
                           $(".defaults").removeClass("defaults");
                            // console.log($(".a-l-box li:eq("+index+")").hasClass('chosen') +"@@");
                            if($(".a-l-box li:eq("+index+")").hasClass('chosen')){
                                $(".a-l-box li:eq("+index+")").addClass('defaults').append('<span class="chosen defaultsAddress">默认地址1</span>').addClass('defaults');
                            }
                        }else{
                          $(".a-l-box li:eq("+index+")").removeClass("defaults").find(".defaultsAddress").remove();                          
                        }
                      }else{       //新增的逻辑
                        var defaultsflag =  defaultflagVal == 1 ? "defaults"  : "";             
                        var str = '<li data-value='+data.data.data.id+' class='+defaultsflag+' >';
                        str +=   '<i class="icon-check-nosprite"></i>';
                        str +=   '<p>'+data.data.data.data+'</p>';
                        str +=   '<div class="editorBtn"><div class="editor">修改</div>|<div class="delete">删除</div></div>';
                        if(defaultflagVal ==1 ){
                          $(".a-l-box li").find(".defaultsAddress").remove();
                          str += '<span class="chosen defaultsAddress">默认地址</span>';                           
                        }
                        str +='</li>';
                        $(".a-l-box ul").prepend(str);                        
                      }
                        $(".address-editor").hide();
                        $(".newAddress i").attr("class","");
                        that.clearAddress();
                        getPrice.getJson();
                    }
                },"json");
               // window.location.reload();
           });

           $(".add_new_address").change(function() {
                $(".address-ul").show();
           });
        },   
        callback:function(){

        },           
        clearAddress:function(){
             $("input[name='name']").val("");
             $("input[name='card']").val("");
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
                 $.get("//www.kaluli.com/api/getNextRegionById?id="+val,function(data){
                      that.cityAddstr(data,$(".select_city").find(".sel-2"));
                 },"json");
                 // getPrice.getJson();
            });

            obj.find(".sel-2").change(function(event) {
                 var val = $(this).val();
                 $.get("//www.kaluli.com/api/getNextRegionById?id="+val,function(data){
                       that.cityAddstr(data,$(".select_city").find(".sel-3"));
                 },"json");
                 // getPrice.getJson();
            });
        },
        cityAddstr:function(o,d){
           var str = ['<option>请选择</option>'];           
           for(var i=0,len=o.data.list.length;i<len;i++){                   
               str.push('<option value="'+o.data.list[i].region_id+'">'+o.data.list[i].region_name+'</option>');
               str.join('');
           }
           d.html(str);
        },
        cupon:function(){
            var t = this;
        },
        logistics:function(){
            
            $(".shipping-btn > i").click(function(){
                var address_id = $(".icon-check-nosprite").parent().attr("data-value");
                if($(this).hasClass("icon-check-nosprite")){
                       return false
                }
                if(address_id == undefined || address_id ==null){
                    $.Jui._showMasks(0.6);
                    var dom = "<div id='error-info' style='position:fixed;width:200px;line-height:50px;left:50%;top:50%;margin:-15px 0 0 -50px;z-index:101;background-color:#FFFFFF;font-size:16px;text-align: center;color:red;border-radius: 10px;'>请先勾选地址</div>";
                    $("body").append(dom);
                    setTimeout(function(){
                        $("#error-info").remove();
                        $.Jui._closeMasks(0.6);
                    },1000);
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
                $this.parents('.houseName').find('i').removeClass("icon-check-nosprite");
                $this.parents('.houseName').find('span').removeClass("red");
                // $(".shipping-btn > i").removeClass("icon-check-nosprite");
                // $(".shipping-btn > span").removeClass("red");
                $(this).addClass("icon-check-nosprite");
                $(this).parent().find('span').addClass('red');
                priceValue.express_type = $(this).data("type");
                if($("#cart_data").length > 0 ){
                    //TODO
                    var list = [];
                    $('.shipping-btn > .icon-check-nosprite').each(function () {
                        var expressType={};
                        expressType["houseId"] = $(this).attr('houseId');
                        expressType["dataType"] = $(this).attr('data-type');
                        list.push(expressType);
                    });
                    var jsonString = JSON.stringify(list);
                    priceValue.express_types = jsonString;
                    
                    getPrice.cartJson();
                }else{
                    getPrice.orderJson();
                }

            });
        }
    };

    return orderConfirm
})