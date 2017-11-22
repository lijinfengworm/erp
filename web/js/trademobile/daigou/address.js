var orderConfirm = {
    Verification:{
       name:true,
       phone:true,
       tell:true,
       //address:false,
       detailed:true,
       postal:true
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

       $("input[name='address']").change(function(event) {
           priceValue.address_id = $(this).val()*1;
           getPrice.getJson();
       });

       $(".submit").click(function(){
           var $this = $(this);
           var dataArray = {};
           var formdata = $("#js-form-search").serializeArray();
           $.each(formdata, function() {
              if (dataArray[this.name] !== undefined) {
                if (!dataArray[this.name].push) {
                  dataArray[this.name] = [dataArray[this.name]];
                }
                dataArray[this.name].push(this.value || '');
              } else {
                dataArray[this.name] = this.value || '';
              }
            });

           $.post($("#js-form-search").attr("action"),formdata,function(data){
                if(data.status*1 == 500){
                    $this.tips(data.msg);
                }else{
                    window.location.href = data.data.jumpurl;
                }
           },"json");
       });

       $(".add_new_address").change(function() {
            $(".new-address").show();
       });
    },
    clearAddress:function(){
         $("input[name='name']").val("");
         $("input[name='phone']").val("");
         $("input[name='phonesection']").val("");
         $("input[name='phonecode']").val("");
         $("input[name='phoneext']").val("");
         $(".detailed").val("");
         $("input[name='postal']").val("");
         $("input[name='defaultflag']").removeAttr("checked");
    },
    cityAdd:function(){
        var obj = $(".select_city"),
            that = this;
        obj.find(".sel-1 select").change(function(event) {
             var val = $(this).val();
             $(".select_city").find(".sel-2 select").html('<option value="0">请选择</option>');
             $(".select_city").find(".sel-3 select").html('<option value="0">请选择</option>');
             $.get("http://m.shihuo.cn/daigou/getRegionByRegionId?region_id="+val,function(data){
                  that.cityAddstr(data,$(".select_city").find(".sel-2 select"));
             },"json");
        });

        obj.find(".sel-2 select").change(function(event) {
             var val = $(this).val();
             $.get("http://m.shihuo.cn/daigou/getRegionByRegionId?region_id="+val,function(data){
                   that.cityAddstr(data,$(".select_city").find(".sel-3 select"));
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

orderConfirm.init();

var timTips;
$.fn.tips = function(o){
    var str = '<div class="tips-box">'+o+'</div>';
    if(timTips){
       clearTimeout(timTips);
       $(".tips-box").remove();
    }
    $(str).appendTo('body');
    $(".tips-box").css({
        left:$(window).width()/2 - $(".tips-box").width()/2,
        top:$(window).height()/2 - 10
    });



    timTips = setTimeout(function(){
       $(".tips-box").remove();
    },2000);
}