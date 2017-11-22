define(["address"],function(address){
    function chooseAddress(){
       
    }
    chooseAddress.prototype={
        defaults:{
            wrap:".user-detail",
            ele:".user-detail tbody tr",
            checkon:"icon-check-nosprite",
            newAddressBtn:"newAddress",
            editaddress:".address-editor",            
            editorBtn:".editor",
            deleteBtn:".delete",
            province:".sel-1",
            city:".sel-2",
            area:".sel-3",
            defaultflag:".defaultflag",
            success: 0
        },
        init:function(){
            var t = this;
            //t.selectAddress();
            address.callback = function(){
                t.deleteAddress();
                t.editnewAddress();
                t.defaultAddress();
            };
            address.init();
            t.deleteAddress();
            t.editnewAddress();
            t.defaultAddress();
        },
        selectAddress:function(){
            var t = this;
            var s = $(t.defaults.wrap).find("li").length-1;            
            $(t.defaults.wrap).find("li").each(function(o){            
                eachhandle(this); 
                if(s == o) t.defaults.success = 1                                                              
            })
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
                if(!$that.hasClass(t.defaults.checkon) || !$that.siblings('span')){
                    $(t.defaults.ele).find("i").removeClass(t.defaults.checkon);                            
                    $that.addClass(t.defaults.checkon);                       
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
                if($that.hasClass(t.defaults.newAddressBtn)){                    
                    $("."+t.defaults.newAddressBtn).bind("click",function(){
                        newaddressHandle(this)   
                    });
                }else{                            
                    $that.find("i").click(function(){  
                        editaddressHandle(this)    
                        getPrice.getJson();                                                                                                                  
                    });
                    $that.find("p").click(function(){
                        $that.find("i").trigger("click");
                    })
                }      
            }
        },
        editnewAddress:function(){
            var t = this,
                url = "//www.kaluli.com/api/getAddress";        
            $(t.defaults.ele).each(function(){
                var $that = $(this),
                    id= $that.data("value");  
                $(this).find(t.defaults.editorBtn).click(function(){
                    address.clearAddress();
                    var index = $that.index();                                      
                    $(t.defaults.ele).find("i").removeClass(t.defaults.checkon);
                    $that.find("i").addClass(t.defaults.checkon);                    
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
                    })
                })
            })   
        },
        cityAddstr:function(o,d){
           var str = [];           
           for(var i=0,len=o.data.list.length;i<len;i++){                   
               str.push('<option value="'+o.data.list[i].region_id+'">'+o.data.list[i].region_name+'</option>');
               str.join('');
           }
           d.append(str);
        },
        defaultAddress:function(){
            var t = this;
            $(t.defaults.defaultflag).click(function(){
                var $thattr = $(this).parents("tr");
                var id = $thattr.attr("data-value");
                $.post("//www.kaluli.com/api/setDefaultAddress",{id:id},function(data){
                    $(".user-detail tbody .td6").text("否");
                    $thattr.find(".td6").text("是");
                })
            })
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
                        location.reload();
                    })
                })
            })
        }
    }
    return chooseAddress
})