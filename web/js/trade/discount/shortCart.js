var short_cart = {
   defaults:{
        product_id:null,//32709多  37847color
        from:""
   },
   colorA:[],
   sizeA:[],
   colorArray:{},
   sizeArray:{},
   init:function(id){
       if(id != 0){
           $(".short-box").show();
           this.defaults.product_id = id;
           this.defineColor = null;
           this.defineSize = null;
           this.parameter = null;
           this.skuAttr = [];
           this.getData();
       }
   },
   getData:function(){
       var that = this;
       $.post("http://www.shihuo.cn/haitao/youhuiBuy",{id:this.defaults.product_id,act:"detail"},function(data){
           if(data.status){
                if(data.data.sku.attr.attr.length < 2 || (data.data.sku.attr.attr.length == 2 && $.inArray("Color",data.data.sku.attr.attr) != -1)){
                     if(data.data.isUpdateGoods){
                         that.update({
                            id:data.data.result.product_id,
                            goodsId:data.data.result.goods_id,
                            datas:data.data
                         });
                      }else{
                         that.writeDom(data.data);
                         if(data.data.sku.attr.attr == ""){
                            that.buy(data.data);
                         }else{
                            that.pushArray(data.data);
                            that.bindFun(data.data); 
                         }
                         $(".join-uploding").hide();
                         $("#join_cart").show();
                      }
               }       
           }else{
               $(".short-box").remove();
           }  
       },"json");
   },
   update:function(val){
       var that = this;
       $.post("http://www.shihuo.cn/haitao/youhuiBuy",{act:"UpdateGoods",id:val.id,goods_id:val.goodsId},function(data){
           $.post("http://www.shihuo.cn/haitao/youhuiBuy",{id:val.id,act:"UpdateSku"},function(data){
                 that.writeDom(val.datas);
                 if(data.data.sku.attr.attr == ""){
                    that.buy(data.data);
                 }else{
                    that.pushArray(val.datas,data.data);
                    that.bindFun(data.data);
                 }
                 $(".join-uploding").hide();
                 $("#join_cart").show();
           },"json");
       },"json");
   },
   pushArray:function(data,updata){
        var sku;
        if(updata){
            sku = updata.sku;
        }else{
            sku = data.sku;
        }
        function addArray(obj,arr,val,val2){
           for(var i=0; i<obj.length;i++){
              arr[obj[i]] = {};
              for(var y=0;y<sku.attr.content.length;y++){
                   if(sku.attr.content[y][val] == obj[i]){
                       arr[obj[i]][sku.attr.content[y][val2]] = sku.attr.content[y];
                   }
              }
           }  
        }
        addArray(this.colorA,this.colorArray,sku.attr.attr[0] == "Color"?sku.attr.attr[0]:sku.attr.attr[1],sku.attr.attr[0] == "Color"?sku.attr.attr[1]:sku.attr.attr[0]);
        addArray(this.sizeA,this.sizeArray,sku.attr.attr[0] == "Color"?sku.attr.attr[0]:sku.attr.attr[0],sku.attr.attr[0] == "Color"?sku.attr.attr[0]:sku.attr.attr[1]);  
   },
   writeDom:function(data){
        var str = '',
            str2 = '',
            modeName = '',
            x,
            y;
        for(y in data.result.attr){
            for(x in data.result.attr[y]){
                if(isNaN(x)){
                    break;
                }
                if(y == "Color"){
                    str += '<span class="g1" atrs="'+data.result.attr[y][x][y]+'"><s></s><img src="'+data.result.attr[y][x].img+'?imageView2/1/w/50" alt="'+data.result.attr[y][x][y]+'" title="'+data.result.attr[y][x][y]+'" /></span>';
                    this.colorA.push(data.result.attr[y][x][y]);
                }else{
                    str2 += '<span class="c1" atrs="'+data.result.attr[y][x][y]+'"><s></s>'+data.result.attr[y][x][y]+'</span>';
                    this.sizeA.push(data.result.attr[y][x][y]);
                }
            }
        }
        for(var i=0; i<data.marketInfo.length; i++){
           function marketInfo(mark){
              var intro = "";
              for(var i=0;i<mark.length; i++){
                  intro+=mark[i]+"<br/>";
              }
              return intro;
           }
           modeName += '<span class="s1">'+data.marketInfo[i].modeName+'<div class="post-tips"><span class="jx"></span>'+marketInfo(data.marketInfo[i].intro)+'</div></span>'
        }
        $(".goods-check-color").html(str);
        $(".goods-check-size").html(str2);
        if($.trim(modeName) == ""){
            $(".short-msg").hide();
        }else{
            $("#shore_modeName").html(modeName);
        }
        if(data.sku.attr.attr.length == 0){
           $(".short-color").hide();
        }
        $("#priceId").html(data.result.price);
   },
   buy:function(datas){
       $("#join_cart").click(function(){
           cartJoin.init(datas.sku.attr.content[0],$(this));
       });
   },
   bindFun:function(datas){
      var that = this,x,single = false,singleArr;
      $("#join_cart").click(function(){
           if(that.parameter != null || single){
               cartJoin.init(single?singleArr:that.parameter,$(this));
           }else{
               $("#join_cart").tips("请选择颜色/尺寸",{
                    left:$("#join_cart").offset().left + 30,
                    top:$("#join_cart").offset().top*1 - 30
                });
           }
      });

      $("#shore_modeName .s1").hover(function(){
          $(this).find(".post-tips").show();
      },function(){
           $(this).find(".post-tips").hide();
      });

      $(".short-color .color-box").click(function(){
            if($(this).hasClass('on')){
               $(this).removeClass('on');
               $(".goods-check-color,.goods-check-size").hide();
            }else{
               $(this).addClass('on');
               $(".goods-check-color,.goods-check-size").show(); 
            }
      });

      $(".goods-check-color .g1").click(function(){
            var str = $(this).attr("atrs");
            if($(this).hasClass('fade')){
                return false;
            }
            that.defineColor = str;
            if(that.sizeChoose){
                $(this).addClass('on');
                $(this).find("s").show();
                $(this).siblings().removeClass("on");
                $(this).siblings().find('s').hide();
                that.parameter = that.sizeArray[that.defineSize][that.defineColor];
                $("#color_size_name").html(that.parameter[datas.sku.attr.attr[1]]+","+that.parameter[datas.sku.attr.attr[0]]);
                $("#priceId").html(that.parameter.Price);
            }else{
                if(!$(this).hasClass('on')){
                    $(this).addClass('on');
                    $(this).find("s").show();
                    $(this).siblings().removeClass("on");
                    $(this).siblings().find('s').hide();
                    $(".goods-check-size .c1").removeClass('fade');
                    $(".goods-check-size .c1").removeClass('on');
                    $(".goods-check-size .c1").find("s").hide();
                    if(datas.sku.attr.attr.length == 1){
                          single = true;
                          for(var i=0;i<datas.sku.attr.content.length;i++){
                              if(datas.sku.attr.content[i][datas.sku.attr.attr[0]] == str){
                                  singleArr = datas.sku.attr.content[i];
                                  break;
                              }
                          }
                          $("#priceId").html(singleArr.Price);
                    }else{
                       for(var i=0;i<that.sizeA.length;i++){
                          if(that.colorArray[str][that.sizeA[i]] == undefined){
                              $(".goods-check-size .c1").eq(i).addClass('fade');
                          }
                       }
                    }
                    that.colorChoose = true;
                }else{
                    $(this).removeClass('on');
                    $(this).find("s").hide();
                    $(".goods-check-size .c1").removeClass('fade');
                    $(".goods-check-size .c1").removeClass('on');
                    $(".goods-check-size .c1").find("s").hide();
                    that.colorChoose = false;
                    single = false;
                }
                that.parameter = null;
            }
            
      });

      $(".goods-check-size .c1").click(function(){
            var str = $(this).attr("atrs");
            if($(this).hasClass('fade')){
                return false;
            }
            that.defineSize = str;
            if(that.colorChoose){
                $(this).addClass('on');
                $(this).find("s").show();
                $(this).siblings().removeClass("on");
                $(this).siblings().find('s').hide();
                that.parameter = that.colorArray[that.defineColor][that.defineSize];
                $("#color_size_name").html(that.parameter[datas.sku.attr.attr[1]]+","+that.parameter[datas.sku.attr.attr[0]]);
                $("#priceId").html(that.parameter.Price);
            }else{
                if(!$(this).hasClass('on')){
                    $(this).addClass('on');
                    $(this).find("s").show();
                    $(this).siblings().removeClass("on");
                    $(this).siblings().find('s').hide();
                    $(".goods-check-color .g1").removeClass('fade');
                    $(".goods-check-color .g1").removeClass('on');
                    $(".goods-check-color .g1").find("s").hide();
                    if(datas.sku.attr.attr.length == 1){
                          single = true;
                          for(var i=0;i<datas.sku.attr.content.length;i++){
                              if(datas.sku.attr.content[i][datas.sku.attr.attr[0]] == str){
                                  singleArr = datas.sku.attr.content[i];
                                  break;
                              }
                          }
                          $("#color_size_name").html(singleArr[datas.sku.attr.attr[0]]);
                          $("#priceId").html(singleArr.Price);
                    }else{
                        for(var i=0;i<that.colorA.length;i++){
                            if(that.sizeArray[str][that.colorA[i]] == undefined){
                                $(".goods-check-color .g1").eq(i).addClass('fade');
                            }
                        }
                    }
                    that.sizeChoose = true;
                }else{
                    $(this).removeClass('on');
                    $(this).find("s").hide();
                    $(".goods-check-color .g1").removeClass('fade');
                    $(".goods-check-color .g1").removeClass('on');
                    $(".goods-check-color .g1").find("s").hide();
                    that.sizeChoose = false;
                    single = false;
                }
                that.parameter = null;
            }
      });
   }
}

var cartJoin = {
     ajaxLoding:false,
     init:function(data,btn){
          if($(btn).length > 0){
               this.bindFun(data,btn);
          }
     },
     bindFun:function(data,btn){
          var obj = $(btn),
              that = this;
             if(that.ajaxLoding){
                return false;
             }
             that.ajaxLoding = true;
             $.post("http://www.shihuo.cn/haitao/addCart",{product_id:data.pid,goods_id:data.gid,number:1,from:""},function(data){
                  if(data.status*1 == 0){
                      $("#cart-right-area .goods-num").html(data.data.count);
                      $("#cart_num_nva").html(data.data.count);
                      that.animate(data.data.img_path,btn);
                  }

                  if(data.status*1 == 1){
                      var top = -30
                      obj.tips(data.msg,{
                          left:obj.offset().left + 30,
                          top:obj.offset().top + top*1
                      });
                      that.ajaxLoding = false;
                  }

                  if(data.status*1 == 2){
                      commonLogin('hupu');
                      that.ajaxLoding = false;
                  }
             },"json");
     },
     animate:function(data,btn){
         var str = '<img id="cart-img-box" style="position: absolute; left:'+($(btn).offset().left*1+100)+'px;top:'+$(btn).offset().top+'px; width:45px; height:45px;" src="'+data+'" />',
         that = this;
         $(str).appendTo('body');
         $("#cart-img-box").paracurve({
             end:[$("#cart-right-area").offset().left,$("#cart-right-area").offset().top],
             step:10,
             movecb:function(){
                 $("#cart-img-box").animate({
                       width:20,
                       height:20
                 });
             },
             moveendcb:function(){
                 $("#cart-img-box").remove(); 
                 that.ajaxLoding = false;
             }
         });
     }
}

/* 
 * @start[] 起始位置，索引0为left的值，索引1为top值 
 * @end[] 终止位置，索引0为left的值，索引1为top值 
 * @step 步长 每次x轴方向移动的距离 
 * @movecb 移动中的回调函数 
 * @moveendcb 移动结束的回调函数 
 */  
!(function($) {  
    var old = $.fn.paracurve;  
  
    $.fn.paracurve = function(option) {  
        //默认的起点为物体的当前位置，终点为页面的右下角  
        var opt = {  
                start: [this.position().left, this.position().top],  
                end: [$(window).width()-this.width(), $(window).height()-this.height()],  
                step:1,  
                movecb:$.noop,  
                moveendcb:$.noop  
            },  
            that = this;  

        $.extend(opt, option);  

        //计算抛物线需要三点，起始和终止位置+未知  
        //未知位置：取起始和终止位置的x轴中间位置x=start.x+(end.x-start.x)/2  
        //y轴方向：取起始和终止点距离页面顶部最小的值-200，y=Math.max(start.y,end.y)-200,如果y<0，则=0  
        //未知位置的x，y确定后，则把该点视为原点，即网页的坐标原点由原来的左上角转移到该点，意念上转移  
        //重新计算起始点相对新原点的坐标,起始点：[x-start.x,y-start.y],终止点：[x-end.x,y-end.y],原点：[0,0]  
        //根据抛物线公式y=a*x*x+b*x+c,把三点坐标代入公式，得到a,b,c=0的值  
  
        //三点实际坐标值  
        var x1 = opt.start[0],  
            y1 = opt.start[1],  
            x2 = opt.end[0],  
            y2 = opt.end[1],  
            x = x1 + (x2 - x1) / 2,  
            y = Math.min(y1, y2) - 100;  
  
        //防止移出页面,x,y作为原点  
        x = x > 0 ? Math.ceil(x) : Math.floor(x);  
        y = y < 0 ? 0 : Math.ceil(y);  
  
        //三点相对坐标值  
        var X1 = x - x1,  
            Y1 = y - y1,  
            X2 = x - x2,  
            Y2 = y - y2,  
            X = 0,  
            Y = 0;  
  
        //根据三点相对坐标计算公式中的a,b,c=0不用计算  
        var a = (Y2 - Y1 * X2 / X1) / (X2 * X2 - X1 * X2),  
            b = (Y1 - a * X1 * X1) / X1;  
  
        return that.each(function(index, ele) {  
            //获得物体起始位置  
            var startPos = $(ele).data('startPos');  
            startPos=!!startPos?startPos:$(ele).position();  
  
            //检查当前物体是否正在运动中并且当前位置是否已在终点  
            if ($(ele).data('running') || startPos.left == x2) {  
                end();  
                  
                //复位  
                $(ele).css({  
                    left: startPos.left + 'px',  
                    top: startPos.top + 'px'  
                });  
            } else {  
                //记忆物体起始位置  
                $(ele).data('startPos',$(ele).position());  
                  
                var timer = setInterval(function() {  
                    var pos = $(ele).position();  
  
                    //如果物体已到达终点  
                    if (pos.left >= x2) {  
                        end();  
                    } else {  
                        //left,top实际位置，Left,Top相对位置  
                        var left = pos.left + opt.step,  
                            Left = x - left,  
                            Top = a * Left * Left + b * Left,  
                            top = y - Top;  
  
                        that.css({  
                            left: left + 'px',  
                            top: top + 'px'  
                        });  
  
                        $(ele).data('running', true);  
                          
                        if(opt.movecb&&$.isFunction(opt.movecb)){  
                            opt.movecb.call(ele);  
                        }  
                    }  
                }, 30);  
  
                $(ele).data('timer', timer);  
            }  
              
              
            //动画完成  
            function end(){  
                //标识是否正在运动中  
                $(ele).data('running', false).css({  
                    left:x2+'px',  
                    top:y2+'px'  
                });  
                  
                clearInterval(timer);  
                  
                //执行完执行回调函数  
                if(opt.moveendcb&&$.isFunction(opt.moveendcb)){  
                    opt.moveendcb.call(ele);  
                }  
            }  
        });  
    };  
  
    $.fn.paracurve.noConflict = function() {  
        $.fn.paracurve = old;  
        return this;  
    };  
})(jQuery);  


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

  $.fn.tips = tips;
})(jQuery);