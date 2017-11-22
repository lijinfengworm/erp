$(function(){
   getDatas.init();
   addAddress.init();
   express.init();
   submit.init();
   $("#textatea-shuoming").focus(function(){
        if($(this).val() == $(this).attr("data")){
           $(this).val("").addClass('textareaColor'); 
        }
   });

   $("#textatea-shuoming").blur(function(){
        if($(this).val() == $(this).attr("data") || $.trim($(this).val()) == ""){
           $(this).val($(this).attr("data")).removeClass('textareaColor'); 
           priceValue.remark = "";
        }else{
           priceValue.remark = $(this).val();
        }
   });
});

var getDatas = {
	init:function(){
          var that = this;
          that.obj = $(".goods-box");
          if(maxcount == 1){
             $(".select2").find(".jia").addClass('onColor');
          }
          if(updateFlag == 1){
               $(".style-select1,.style-select2").hide();
               $.Jui._showMasks(0.5);
               $(".loding-box").css({
                  left:$(".goods-box").offset().left+250,
                  top:$(".goods-box").offset().top+20
               }).show();

               $.post("http://www.shihuo.cn/haitao/updateProductInfo",{news_id:newsId,item_url:item_url},function(data){
                if(data.status*1 == 0){
                   json_content = data.data;
                   that.getDataFun();
                   that.bindFun();
                }
                if(data.status*1 == 1){
                   that.getDataFun();
                   that.bindFun();
                }
               },"json");
          }else{
             that.getDataFun();
             that.bindFun();
          }
	},
	getDataFun:function(){
		var arr = json_content,
            that = this,
            endEach,
            key1 = arr.attr[0]?arr.attr[0]:null,
            key2 = arr.attr[1]?arr.attr[1]:null;
    if(key1 != null || key2 != null){
       if(key2 != null){
            this.jArray1 = {};
            this.jArray2 = {};
            for(var i=0; i<arr[key1].length; i++){
                for(var n=0; n<arr.content.length; n++){
                  if(arr.content[n][key1] == arr[key1][i]){
                    typeof this.jArray1[arr[key1][i]] == "undefined"?this.jArray1[arr[key1][i]] = []:"";
                    this.jArray1[arr[key1][i]].push(arr.content[n]);
                  }
                }
            }
             for(var s=0; s<arr[key2].length; s++){
                for(var m=0; m<arr.content.length; m++){
                  if(arr.content[m][key2] == arr[key2][s]){
                    typeof this.jArray2[arr[key2][s]] == "undefined"?this.jArray2[arr[key2][s]] = []:"";
                    this.jArray2[arr[key2][s]].push(arr.content[m]);
                  }
                }
            } 
            
            $(arr[key2]).each(function(i){
                $(that.jArray1[arr[key1][0]]).each(function(n){
                     if(that.jArray1[arr[key1][0]][n][key2] == arr[key2][i]){
                         endEach = true;
                         that.getSelectB(that.jArray1[arr[key1][0]][n][key2],arr[key1][0]);
                         that.change2 = that.jArray1[arr[key1][0]][n][key2];
                         return false;
                     } 
                });
                if(endEach){
                    return false;
                }
            });
            that.getSelectA(arr[key1][0]);
        }else{
            var str = [];
            for(var i=0;i<arr[key1].length; i++){
              str.push('<option atr="'+arr[key1][i]+'">'+arr[key1][i]+'</option>')
            }
            this.obj.find(".style-select1").html(str.join(''));
            priceValue.attr.key.push(arr.attr[0]);
            priceValue.attr.value.push(arr[key1][0]);
            $.Jui._closeMasks();
            $(".loding-box").hide();
            $(".style-select1,.style-select2").show();
        } 
    }else{
       this.setHtml2(arr.content[0]);
    }
	},
	getSelectA:function(o,ts){
          var that = this,
              arr = json_content,
              key1 = arr.attr[0],
              key2 = arr.attr[1],
              str=[],
              retuFalse;
          for(var i=0;i<arr[key2].length; i++){
                for(var s=0; s<that.jArray1[o].length;s++){
                	if(that.jArray1[o][s][key2] == arr[key2][i]){
                		var cor = true;
                		break;
                	}else{
                		var cor = false;
                	}
                }
                if(cor){
                	str.push('<option atr="'+arr[key2][i]+'">'+arr[key2][i]+'</option>');
                }else{
                	str.push('<option atr="'+arr[key2][i]+'" class="color-1">'+arr[key2][i]+'</option>');
                }
          }
          that.obj.find(".style-select2").html(str.join(''));

          if(ts == undefined){
             $(that.jArray2[that.change2]).each(function(i){
                   if(that.jArray2[that.change2][i][key1] == that.change1){
                       retuFalse = true;
                       return false;
                   }
              });
          }
         
           var keyTs = ts?ts:that.jArray1[o][0][key2];
           that.obj.find(".style-select2").each(function(){
              for(var n=0;n<this.options.length; n++){
                 if(retuFalse){
                     if($(this).find("option").eq(n).attr("atr") ==  that.change2){
                        this.options[n].selected = that.change2;
                        keyTs = that.change2
                     }
                 }else{
                      if(ts){
                         if($(this).find("option").eq(n).attr("atr") == keyTs){
                                this.options[n].selected = keyTs;
                                break;
                          }
                      }else{
                         if(!$(this).find("option").eq(n).hasClass('color-1')){
                              this.options[n].selected = $(this).find("option").eq(n).attr("atr");
                              break;
                          }
                      }  
                 }      
			         }
               that.change2 = keyTs;
          });
          //this.setHtml(that.jArray1[o][0]);
	},
	getSelectB:function(o,ts){
          var that = this,
              arr = json_content,
              key1 = arr.attr[0],
              key2 = arr.attr[1],
              str=[],
              retuFalse;
          for(var i=0;i<arr[key1].length; i++){
                for(var s=0; s<that.jArray2[o].length;s++){
                	if(that.jArray2[o][s][key1] == arr[key1][i]){
                		var cor = true;
                		break;
                	}else{
                		var cor = false;
                	}
                }
                if(cor){
                	str.push('<option atr="'+arr[key1][i]+'">'+arr[key1][i]+'</option>');
                }else{
                	str.push('<option atr="'+arr[key1][i]+'" class="color-1">'+arr[key1][i]+'</option>');
                }
          }
          that.obj.find(".style-select1").html(str.join(''));
          if(ts == undefined){
             $(that.jArray1[that.change1]).each(function(i){
                   if(that.jArray1[that.change1][i][key2] == that.change2){
                       retuFalse = true;
                       return false;
                   }
              });
          }

             var keyTs = ts?ts:that.jArray2[o][0][key1];
             that.obj.find(".style-select1").each(function(){
	              for(var n=0;n<this.options.length; n++){
                  if(retuFalse){
                      if($(this).find("option").eq(n).attr("atr") ==  that.change1){
                          this.options[n].selected = that.change1;
                          keyTs = that.change1;
                       }
                  }else{
                      if(ts){
                         if($(this).find("option").eq(n).attr("atr") == keyTs){
                                this.options[n].selected = keyTs;
                                break;
                          }
                      }else{
                         if(!$(this).find("option").eq(n).hasClass('color-1')){
                              keyTs = $(this).find("option").eq(n).attr("atr");
                              this.options[n].selected = keyTs;
                              break;
                          }
                      }  
                  }   
	              }
                that.change1 = keyTs;
	          });
            setTimeout(function(){
                var tex = that.obj.find(".style-select2").find("option:selected").text();
                 $(that.jArray2[tex]).each(function(i){
                    if(that.jArray2[tex][i][key1] == keyTs){
                       that.setHtml(that.jArray2[tex][i]);
                       return false;
                    }
                 });
            },30);
	},
	setHtml:function(o){
		 var num = this.obj.find(".num").html()*1,
         arr = json_content;
         this.obj.find(".imgs").find("img").attr("src",o.img);
         priceValue.attr.key = [];
         priceValue.attr.value = [];
         for(var i=0; i<arr.attr.length; i++){
            priceValue.attr.key.push(arr.attr[i]);
            priceValue.attr.value.push(o[arr.attr[i]]);
         }
         getPrice.getJson(function(d){
            $(".good-price").html(d.data.price);
            $(".good-all-price").html(d.data.product_total);
         });
         $.Jui._closeMasks();
         $(".loding-box").hide();
         $(".style-select1,.style-select2").show();
	},
  setHtml2:function(o){
     this.obj.find(".imgs").find("img").attr("src",o.img);
     getPrice.getJson(function(d){
        $(".good-price").html(d.data.price);
        $(".good-all-price").html(d.data.product_total);
     });
     $.Jui._closeMasks();
     $(".loding-box").hide();
     $(".style-select1,.style-select2").show();
  },
	bindFun:function(){
		var select1 = $(".style-select1"),
		    select2 = $(".style-select2"),
        selectAdd = $(".select2"),
		    that = this,
		    arr = json_content,
              key1 = arr.attr[0]?arr.attr[0]:null,
              key2 = arr.attr[1]?arr.attr[1]:null;
      if(key1 != null || key2 != null){
            if(key2 != null){
                select1.live("change",function(){
                   var s= $(this).val();
                   that.change1 = s;
                   that.getSelectA(s);
                   setTimeout(function(){
                      that.getSelectB(that.change2,s);
                   },30);
                });

                select2.live("change",function(){
                   var s= $(this).val();
                   that.change2 = s;
                   that.getSelectB(s);
                   setTimeout(function(){
                      that.getSelectA(that.change1,s);
                   },30);
                });
            }else{
               select1.live("change",function(){
                   var s= $(this).val();
                  for(var i=0; i<arr.content.length; i++){
                      if(arr.content[i][key1] == s){
                          that.setHtml(arr.content[i]);
                          return false;
                      }
                   }
                });
            }
     }
    selectAdd.find(".jian").click(function(){
        var num = selectAdd.find(".num").html()*1;
         if($(this).hasClass('onColor')){
            return false;
         }
         if(num-1 == 1){
             $(this).addClass('onColor');
         }
         selectAdd.find(".jia").removeClass('onColor');
         selectAdd.find(".num").html(num-1);
         priceValue.number = num-1;
         getPrice.getJson(function(d){
            $(".good-price").html(d.data.price);
            $(".good-all-price").html(d.data.product_total);
         });
    });

    selectAdd.find(".jia").click(function(){
          var num = selectAdd.find(".num").html()*1;
         if($(this).hasClass('onColor')){
            return false;
         }
         if(num+1 == maxcount){
             $(this).addClass('onColor');
         }
         selectAdd.find(".jian").removeClass('onColor');
         selectAdd.find(".num").html(num+1);
         priceValue.number = num+1;
         getPrice.getJson(function(d){
            $(".good-price").html(d.data.price);
            $(".good-all-price").html(d.data.product_total);
         });
    });
	}
}

var addAddress = {
    Verification:{
       name:false,
       phone:false,
       tell:false,
       //address:false,
       detailed:false,
       postal:false
    },
    init:function(){
        this.bindFun();
        this.cityAdd();
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
       console.log(123)
       $("input[name='phonesection']").blur(function(){
            var val = $(this).val();  
            console.log(phonesection.test(val),$.trim(val))
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

       $(".address-ul").find(".save").click(function(){
           var $this = $(this),
               nameVal = $("input[name='name']").val(),
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

           $.get("http://www.shihuo.cn/haitao/saveDeliveryAddress",{name:nameVal,mobile:mobileVal,phonesection:phonesectionVal,phonecode:phonecodeVal,phoneext:phoneextVal,province:provinceVal,city:cityVal,area:areaVal,street:streetVal,postcode:postcodeVal,defaultflag:defaultflagVal},function(data){
                if(data.status*1 == 1){
                    $this.tips(data.msg);
                }else{
                    $(".default_address").prepend('<p><label><input name="address" type="radio" value="'+data.data.id+'" /> '+data.data.address+'</label></p>');
                    $("input[name='address']").eq(0).attr("checked","checked");
                    priceValue.address_id = $("input[name='address']:checked").val()*1;
                    $(".address-ul").hide();
                    getPrice.getJson();
                    that.clearAddress();
                }
           },"json");
       });

       $(".add_new_address").change(function() {
            $(".address-ul").show();
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
       d.append(str);
    }
}

var express = {
    init:function(){
        $("input[name='kuaidi']").change(function(){
           priceValue.type = $("input[name='kuaidi']:checked").val()*1;
           getPrice.getJson();
        });
    }
}

var priceValue = {
     newsId:newsId,
     address_id:$("input[name='address']:checked").val()*1,
     type:$("input[name='kuaidi']:checked").val()*1,
     number:1,
     remark:"",
     attr:{
        key:[],
        value:[]
     }
}

var getPrice = {
    init:function(){

    },
    getJson:function(callback){
        var submitBox = $(".submit-box"),
            str;
        $.post("http://www.shihuo.cn/haitao/getOrderTotalPrice",{news_id:priceValue.newsId,address_id:priceValue.address_id,type:priceValue.type,number:priceValue.number,remark:priceValue.remark,attr:priceValue.attr},function(data){
             str = '<span class="m1">商家：<s>'+data.data.mart+'</s></span><span class="m1">商家价格：<s>'+data.data.product_total+'RMB</s></span><span class="m1">国际运费：<s>'+data.data.intl_fee+'RMB</s></span><span class="m1">国内运费：<s>'+data.data.fee+'RMB</s></span>\
                       <div class="all-price clearfix">\
                           '+(flag == 1?'<div class="pr1">黑五活动，立减10元</div>':"")+'\
                           <div class="pr2">\
                              总计：<s><i>'+data.data.total_price+'</i>RMB</s>\
                          </div>\
                       </div>';
              submitBox.find(".message-all").html(str);
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
          $this.addClass('submit-btnCC');
          $.post("http://www.shihuo.cn/haitao/submitOrder",{news_id:priceValue.newsId,address_id:priceValue.address_id,type:priceValue.type,number:priceValue.number,remark:priceValue.remark,attr:priceValue.attr},function(data){
                if(data.status == 0){
                    window.location.href = data.data.url;
                }else{
                    $this.removeClass('submit-btnCC');
                    $this.tips(data.msg);
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
})(jQuery);
