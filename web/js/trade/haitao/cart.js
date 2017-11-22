$(function(){
   cartJsong.init();
   if($(document).height() > 800){
   	   $(".go-buy").addClass('fixed-post').css({
	         left:$(".shihuo-content-wrap").offset().left
	   }).show();
   }
});

var cartJsong = {
   changeFlag:true,
   updataLength:null,
   submitUp:false,
   updataListNum:0,
   goodCheck:[],
   goodList:[],
   goodGid:null,
   init:function(){
   	   this.updataLength = updateList.length;
   	   this.upLoding();
       this.bindFun();
   },
   upLoding:function(){
   	   var that = this;
   	   if(this.updataLength-1 >= this.updataListNum){
   	   	   $(".shops-goods-id-"+updateList[this.updataListNum].data.goods_id).find(".price").hide();
   	   	   $(".shops-goods-id-"+updateList[this.updataListNum].data.goods_id).find(".loding-js").show();
   	   	   $.post("http://www.shihuo.cn/haitao/cartUpdateProductInfo",{data:updateList[this.updataListNum].data,productId:updateList[this.updataListNum].productId},function(data){
                if(data.status*1 == 0){
                		$(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".price p").eq(0).html("金额：￥"+data.data.price);
                		$(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".price").show();
                		$(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".loding-js").hide();
                    if(typeof(data.data.weight) && data.data.weight != ""){
                        //$(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".price p:last").html("运费：￥"+data.data.freight);
                        //$(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).attr("choose","false");
                    }else{
                       $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".price p:last").html('<font color="red">没有获取到运费，请联系客服修改</font>');
                       $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).attr("choose","true");
                    }
                		if(data.data.flag){
                			$(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".price .change s").html(data.data.change);
                			$(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".price .change").show();
                		}else{
                			$(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".price .change").hide();
                		}

                		if(data.data.status){
                            $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).addClass('shixiao-class'); 
                            $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".t1 input").attr("disabled","disabled").hide();
                            $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".t1 .shixiao").show();
                            $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".t3 .n1").hide();
                            $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".t3 .n2").show();
                		}else{
                			$(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).removeClass('shixiao-class'); 
                            $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".t1 input").removeAttr("disabled").show();
                            $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".t1 .shixiao").hide();
                            $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".t3 .n1").show();
                            $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".t3 .n2").hide();
                		}
                }else{                    
                    $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".price").show();
                    $(".shops-goods-id-"+updateList[that.updataListNum].data.goods_id).find(".loding-js").hide();
                }
                that.updataListNum++;
                that.upLoding();
   	   	   },"json");
   	   }else{
   	   	   that.changeFlag = false;
   	   }
   },
   bindFun:function(){
   	   var that = this;
   	   $(".shihuo-content-wrap input").click(function(){
           if(that.changeFlag || $(this).parents("li").attr("choose") == "true"){
           	   return false;
           }
   	   });
       
       $(".check_all").change(function(){
       	   var check = $(this).attr("checked");
           if(check == "checked"){
           	   $("input[name='check-shop']").attr("checked","checked");
           	   $("input[name='check-all']").attr("checked","checked");
               $("input[name='check-goods']").each(function(){
                       if($(this).parents("li").attr("choose") == "true"){
                           $(this).attr("checked",false);
                           $(this).parents("li").removeClass('select');
                       }else{
                           $(this).attr("checked","checked");
                           $(this).parents("li").addClass('select');
                       }
               });
           }else{
           	   $("input[name='check-shop']").attr("checked",false);
           	   $("input[name='check-goods']").attr("checked",false);
           	   $("input[name='check-all']").attr("checked",false);
           }
           that.getGoodsIdPost();
       });

       $("input[name='check-shop']").change(function(){
             var check = $(this).attr("checked");
             if(check == "checked"){
             	  $(this).parents(".good-from").find("input[name='check-goods']").each(function(){
                         if($(this).parents("li").attr("choose") == "true"){
                             $(this).attr("checked",false);
                             $(this).parents("li").removeClass('select');
                         }else{
                             $(this).attr("checked","checked");
                             $(this).parents("li").addClass('select');
                         }
                 });
             }else{
             	$(this).parents(".good-from").find("input[name='check-goods']").attr("checked",false);
             }
             that.checkAllFun();
             that.getGoodsIdPost();
       });

       $("input[name='check-goods']").change(function(){
       	   var checkAll = true;
       	   $(this).parents(".goods-list").find("input[name='check-goods']").each(function(i,d){
               if(!d.checked){
                  checkAll = false;
               }
       	   });

       	   if(checkAll){
       	   	   $(this).parents(".good-from").find("input[name='check-shop']").attr("checked","checked");
       	   }else{
               $(this).parents(".good-from").find("input[name='check-shop']").attr("checked",false);
       	   }
       	   that.checkAllFun();
       	   that.getGoodsIdPost();
       });

       $(".goods-add-btn").find(".a1").click(function(){
          if(that.changeFlag || $(this).parents("li").attr("choose") == "true"){
               return false;
           }
          var addObj2 = $(this).next(),
             gid = $(this).parents(".goods-add-btn").attr("gid"),
             val = addObj2.html()* 1;
	          if(val > 1){
                  addObj2.html(--val);
                  that.addGoods({
                  	  goods_id:gid,
                  	  type:0
                  },$(this).parents(".goods-add-btn").next());
                  $(".goods-add-btn").find(".a3").removeClass("bgs");
                  if(val == 1){
                      $(this).addClass('bgs');
                  }
                  if(val <= addObj2.attr("max")){
                      $(this).parent().find(".maxnumtips").hide();
                  }else{
                      $(this).parent().find(".maxnumtips").show();
                  }
	          }
       });

       $(".goods-add-btn").find(".a3").click(function(){
          if(that.changeFlag || $(this).parents("li").attr("choose") == "true"){
               return false;
           }
          var addObj2 = $(this).prev(),
             gid = $(this).parents(".goods-add-btn").attr("gid"),
             val = addObj2.attr("max")*1,
             val2 = addObj2.html()*1;
	          if(val2 < val){
                  addObj2.html(++val2);
                  that.addGoods({
                  	  goods_id:gid,
                  	  type:1
                  },$(this).parents(".goods-add-btn").next());
                  $(".goods-add-btn").find(".a1").removeClass("bgs");
                  $(this).next().hide();
                  if(val2 >= val){
                      $(this).addClass('bgs');
                      $(this).next().show().find(".tips-text").text("限购 "+val+" 件");
                  }
	          }
       });

       $("#delete_id").click(function(){
            that.deleteConfirm(that.goodCheck);
       });

       $(".good-from .delete").click(function(){
            that.goodGid = $(this).attr("gid");
            that.deleteConfirm([that.goodGid]);
       });

       $(".write-card i,.write-card .btn2").live("click",function(){
            $.Jui._closeMasks();
            $(".write-card").remove();  
       });

       $(".write-card .btn1").live("click",function(){
            $.Jui._closeMasks();
            that.deleteGoods($(".write-card").data("gid"));
            $(".write-card").remove(); 
       });

       $("#submit-all,#submit-all2").click(function(){
           if(that.goodList.length > 0){
              that.fromSubmit();
           }else{
           	  alert("请勾选商品");
           }
           return false;
       });
   },
   checkAllFun:function(){
   	   var check = false;
       $("input[name='check-goods']").each(function(i,d){
              if(!d.checked){
              	  check = true;
                  return false;
              }
       });

       if(check){
       	  $("input[name='check-all']").attr("checked",false);
       }else{
       	  $("input[name='check-all']").attr("checked","checked");
       }
   },
   getGoodsIdPost:function(){
   	   var that = this;
   	   that.goodCheck = [];
   	   that.goodList = [];
   	   $("input[name='check-goods']:enabled").each(function(i,d){
	        if(d.checked){
	            that.goodCheck.push(d.getAttribute("gid"));
	            that.goodList.push(d.getAttribute("gilist"));
              $(this).parents("li").addClass('select');
	        }else{
              $(this).parents("li").removeClass('select');
          }
	    });
   	    $(".activity-grid").attr("data-onactivity","0");
   	    $.post("http://www.shihuo.cn/haitao/getCartAllPrice",{data:that.goodCheck},function(data){          
   	    	if(data.status*1 == 0){
                var cartInfo = data.data;
                var dataArr = [
                        cartInfo.total_product_price,//商品总价格
                        cartInfo.total_product_freight,//国际运费
                        cartInfo.total_count,//总计商品数量
                        cartInfo.total_price,//实付款
                        cartInfo.save_freight,//节省运费
                        cartInfo.original_total_price || 0,//总计
                        cartInfo.usa_freight || 0//美国运费
                ];

                //绑定购物车结算数据
                for(var i =0;i<=dataArr.length;i++){
                    $(".price-id-"+(i+1)).html((i==2 ? dataArr[i]:"￥"+dataArr[i]));
                }

                //初始化活动信息
                $(".activity-info li").removeClass('on');
                $(".activity-grid span").removeClass('on');
                $(".activity-salebox").hide();


                if("undefined" != typeof cartInfo.activity){
                  //判断活动优惠为0时隐藏结算信息中的“活动优惠”文案
                  cartInfo.activity.activity_save == 0 ? $(".activity-salebox").hide() : ($("#activity-sale").html("￥"+cartInfo.activity.activity_save), $(".activity-salebox").show());

                  var allactivity = cartInfo.activity.activity,//活动列表
                      goodsactivity =cartInfo.activity.goods_info;//活动商品列表

                  //遍历优惠活动类型(美亚,6PM)
                  for(var i in allactivity){
                    var index = i-1,
                        $ul = $("ul",".activity-info:eq("+index+")");
                    //遍历不同类型活动下的的活动列表
                    for(var s=0;s< allactivity[i].list.length;s++){
                      var thisli = $(".activity-info:eq("+index+")").find("#market_"+allactivity[i].list[s].id);
                      //判断是否满足活动,满足活动添加高亮样式,不满足变灰并且显示“去凑单”链接
                      if(allactivity[i].list[s].flag == true ){
                        thisli.addClass('on').find("a:eq(0)").attr({"href":"javascript:void(0)","target":"_self"});
                        thisli.find(".link").attr({"href":"javascript:void(0)","target":"_self"}).hide();
                      }else{
                        thisli.removeClass('on').find("a:eq(0)").attr({"href":thisli.find("a:eq(0)").attr("more-url"),"target":"_blank"});
                        thisli.find(".link").attr({"href":thisli.find("a:eq(0)").attr("more-url"),"target":"_blank"}).show();
                      }
                    }
                  }
                  //遍历存在活动的商品
                  for(var i in goodsactivity){
                    var goods_id =  goodsactivity[i].goods_id,
                        //根据ID匹配存在活动的商品
                        $activity_grid = $(".shops-goods-id-"+goods_id+" .activity-grid");
                    $activity_grid.attr("data-onactivity","1");
                    //判断是否满足活动,不满足显示“去凑单”链接
                    if(!goodsactivity[i].collectFlag){
                        $activity_grid.find("a:last-child").hide();
                    }else{
                        $activity_grid.find("a:last-child").show();
                    }
                    //遍历每个商品下的活动
                    for(var s = 0;s<goodsactivity[i].activity.length;s++){
                      //判断商品是否满足活动,不满足对应活动文案可点击跳转
                      if(goodsactivity[i].activity[s].platformFlag == true || goodsactivity[i].activity[s].shihuoFlag  == true){
                        $(".shops-goods-id-"+goods_id+" .activity-grid").find("a").eq(s).attr({"href":"javascript:void(0)","target":"_self"}).addClass('on');
                      }else if((goodsactivity[i].activity[s].platformFlag == false && "undefined" == typeof goodsactivity[i].activity[s].shihuoFlag) || (goodsactivity[i].activity[s].shihuoFlag == false && "undefined" == typeof goodsactivity[i].activity[s].platformFlag) ||(goodsactivity[i].activity[s].shihuoFlag == false && goodsactivity[i].activity[s].platformFlag ==false) || ("undefined" == typeof goodsactivity[i].activity[s].platformFlag && "undefined" == typeof goodsactivity[i].activity[s].shihuoFlag)){
                        $(".shops-goods-id-"+goods_id+" .activity-grid").find("a").eq(s).attr({"href":$(".shops-goods-id-"+goods_id+" .activity-grid").attr("more-url"),"target":"_blank"}).removeClass('on');
                      }
                    }
                  }
                  $(".activity-grid").each(function(){
                    if($(this).attr("data-onactivity")==0){
                       $("a",this).each(function(){
                        $(this).attr("more-url") ? $(this).removeClass('on').attr({"href":$(this).attr("more-url"),"target":"_blank"}).show():$(this).show();
                      })
                    }
                  })
                }else{
                  $(".activity-info li").each(function(){
                    $(this).removeClass('on').find("a").attr({"href":$(this).find("a").attr("more-url"),"target":"_blank"}).show();
                  });
                  $(".activity-grid a").each(function(){
                    $(this).attr("more-url") ? $(this).removeClass('on').attr({"href":$(this).attr("more-url"),"target":"_blank"}).show():$(this).show();

                  })
                }
   	    	}else{
   	    		alert(data.msg);
   	    	}
   	    },"json");
   },
   sortArray:function(){

   },
   addGoods:function(datas,obj){
   	   var that = this;
       $.post("http://www.shihuo.cn/haitao/addCartNumber",datas,function(data){
             if(data.status*1 == 0){
             	obj.find("p").eq(0).html("金额：￥"+data.data.total_price);
             	//obj.find("p").eq(1).html("运费：￥"+data.data.freight);
             	that.getGoodsIdPost();
             }
       },"json");
   },
   deleteConfirm:function(gid){
         var str = '<div class="write-card">\
                  <div class="title"><i></i>提示</div>\
                  <div class="inner">\
                      <div class="inner-html clearfix">\
                           <div class="left">\
                              <img src="/images/trade/ucenter/gt.jpg" />\
                           </div>\
                           <div class="right">\
                              <div class="h2">删除商品?</div>\
                              <div class="btn-span">\
                                  <span class="btn1">确定</span><span class="btn2">取消</span>\
                              </div>\
                           </div>\
                      </div>\
                  </div>\
              </div>';
         $.Jui._showMasks(0.6);
         $(str).appendTo('body');
         $(".write-card").css({
              left:$.Jui._position($(".write-card"))[0],
              top:$.Jui._position($(".write-card"))[1] -$.Jui._getpageScroll()
         }).show();
         $(".write-card").data("gid",gid);
   },
   deleteGoods:function(da){
   	   var that = this;
       $.post("http://www.shihuo.cn/haitao/deleteCart",{data:da},function(data){
             if(data.status*1 == 0){
               	$("#cart_num").html(data.data);
               	for(var i=0;i<da.length; i++){
      		   	   	   var setObj = $(".shops-goods-id-"+da[i]).parents(".good-from");
      		   	   	   if(setObj.find("li").length == 1){
      		                 setObj.remove();
      		   	   	   }else{
      		                 $(".shops-goods-id-"+da[i]).remove();
      		   	   	   }

                     if($(".good-from").find("li").length < 1){
                         location.reload();
                     }
      		   	   }
               	that.getGoodsIdPost();
                $("#cart-right-area .goods-num").html(data.data);
                $("#cart_num_nva").html(data.data);
             }
       },"json");
   },
   fromSubmit:function(){
       var str = '';
       if(this.submitUp){
           return false;
       }
       this.submitUp = true;
       $("#submit-all").addClass('input-false');
       $("#submit-all").val("提交中...");
       for(var i=0; i<this.goodList.length; i++){
            str += (i==0?"":",")+this.goodList[i];
       }
       $("#goods-form").html('<input type="hidden" value="'+str+'" name="data" />');
       $("#goods-form").submit();
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
})(jQuery);