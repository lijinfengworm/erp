define(["modules/order/priceValue","orderConfirm","getPrice"],function(priceValue,orderConfirm,getPrice){
    function chooseAddress(){
       
    }
    chooseAddress.prototype={
        defaults:{
            wrap:".a-l-box",
            ele:".a-l-box li",
            checkon:"icon-check-nosprite",
            newAddressBtn:"newAddress",
            editaddress:".address-editor",            
            editorBtn:".editor",
            deleteBtn:".delete",
            defaultAddressBtn:'.set-default-address',
            province:".sel-1",
            city:".sel-2",
            area:".sel-3",
            success: 0
        },
        init:function(){
            var t = this;
            /*orderConfirm.callback = function(){                
                t.selectAddress();
                t.deleteAddress();
                t.editnewAddress();   
            }*/
            orderConfirm.init();
            t.selectAddress();
            t.deleteAddress();
            t.editnewAddress();
            t.setDefaultAddress();
        },
        selectAddress:function(){
            var t = this;
            var s = $(t.defaults.wrap).find("li").length-1;            
            $(t.defaults.wrap).find("li").each(function(o){            
                eachhandle(this); 
                if(s == o) t.defaults.success = 1                                                              
            });

            $("i", t.defaults.ele).live("click",function(){
                selectHandle(this);
            });

            $("p", t.defaults.ele).live("click",function(){
                $(this).siblings("i").trigger("click");
            });
            function newaddressHandle(obj){                   
                var $that = $(obj);
                if($that.find("i").hasClass(t.defaults.checkon)){
                    $that.find("i").removeClass(t.defaults.checkon);
                    $(t.defaults.editaddress).hide();
                }else{                           
                    $(t.defaults.ele).find("i").removeClass(t.defaults.checkon);
                    $that.find("i").addClass(t.defaults.checkon);       
                    $(t.defaults.editaddress).attr("data-value","");                     
                    $(t.defaults.editaddress).show();                           
                } 
                orderConfirm.clearAddress();    
            }
            function editaddressHandle(obj){
                var $that = $(obj);
                var $li = $(obj).parent();
                if(!$that.hasClass(t.defaults.checkon) || !$that.siblings('span')){
                    $(t.defaults.ele).find("i").removeClass(t.defaults.checkon);                            
                    $that.addClass(t.defaults.checkon);

                    $(t.defaults.ele).removeClass('active');
                    $li.addClass('active');


                }      
                if(!$that.parent().hasClass('newAddress')){
                    $(t.defaults.editaddress).hide();
                }      
            }
            function eachhandle(obj){                
                var $that = $(obj);  
                if(t.defaults.success ==1 ){
                    $("."+t.defaults.newAddressBtn).unbind("click");       
                    $that.find("i").unbind("click");  
                }
            }
            function selectHandle(obj){
                var $li = $(obj).parent(),
                    $this = $(obj);
                if($li.hasClass(t.defaults.newAddressBtn)){
                    newaddressHandle($li);//新增收货地址
                }else{
                    editaddressHandle(obj);
                    getPrice.getJson();
                }
            }
        },
        editnewAddress:function(){
            var t = this,
                url = "//www.kaluli.com/api/getAddress";
            $(t.defaults.editorBtn).live("click",function(e){
                $(t.defaults.ele).removeClass('active');
                var $that = $(this).parents("li"),
                    id= $that.attr("data-value");
                var index = $that.index();                                      
                $(t.defaults.ele).find("i").removeClass(t.defaults.checkon);
                $that.find("i").addClass(t.defaults.checkon);
                $that.addClass('active');


                $.post(url,{id:id},function(data){                        
                    var thisdata = $.parseJSON(data).data,
                        addressdata = {
                            'name' : thisdata.name,                                
                            'card' : thisdata.identity_number,
                            'phone' : thisdata.mobile,
                            'phonesection' : thisdata.phonesection,
                            'phoneext' : thisdata.phoneext,
                            'phonecode' : thisdata.phonecode,
                            'province' : thisdata.province,
                            'city' : thisdata.city,
                            'area' :thisdata.area,
                            'detailed' : thisdata.street,
                            'postal' : thisdata.postcode
                        };                          
                    for(var i in addressdata){                            
                        $("input[name="+i+"]").val(addressdata[i]);
                    }                                                
                    if(thisdata.defaultflag == 1){
                        $("input[name=defaultflag]").attr("checked","checked");
                    }
                    var provinceindex = $("option[value="+addressdata.province+"]",t.defaults.province).index();
                    $(t.defaults.province)[0].selectedIndex = provinceindex;                    
                    $.get("//www.kaluli.com/api/getNextRegionById?id="+addressdata.province,function(data){                                                                                  
                        t.cityAddstr(data,$(".select_city").find(".sel-2"));
                        var cityindex = $("option[value="+addressdata.city+"]",t.defaults.city).index();      
                        $(t.defaults.city)[0].selectedIndex = cityindex;                                
                        $.get("//www.kaluli.com/api/getNextRegionById?id="+addressdata.city,function(data){                                  
                               t.cityAddstr(data,$(".select_city").find(".sel-3"));
                               var areaindex = $("option[value="+addressdata.area+"]",t.defaults.area).index();
                               $(t.defaults.area)[0].selectedIndex = areaindex;
                        },"json");
                    },"json");

                    $(t.defaults.editaddress).attr({"data-value":id,"data-index":index}).show();
                });
                e.stopPropagation();
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
        deleteAddress:function(){
            var t = this,
                url = "//www.kaluli.com/api/delAddress";
            $(t.defaults.ele).each(function(){
                var $that = $(this), 
                    id =  $that.data("value");          
                $(this).find(t.defaults.deleteBtn).click(function(){
                    $.post(url,{id:id},function(data){
                        $that.remove();
                    })
                })
            })
        },
        setDefaultAddress:function () {
            var t = this,
                url = "//www.kaluli.com/api/setDefaultAddress";
            $(t.defaults.ele).each(function(idx){
                var $li = $(this),
                    $i = $(this).find('i'),
                    id =  $li.data("value");
                $li.find('.init').click(function(){
                    var _t = $(this);
                    $.post(url,{id:id},function(data){
                       $('.init').removeClass('chosen').addClass('set-default-address').text('设置默认地址');
                        _t.removeClass('set-default-address').addClass('chosen').text('默认地址');
                    })
                })
            });
        }
    }
    return chooseAddress
})