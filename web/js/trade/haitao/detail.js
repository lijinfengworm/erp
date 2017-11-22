$(function(){
   $(".change-title").find("li").click(function(){   
       if($(this).hasClass('cart-area')){
          return false
       }    
       var index = $(this).index();
       $(window).scrollTop($("#offset_post_"+index).offset().top - 50);
   });

   function checkScroll(){

        if($(window).scrollTop() > $("#offset_post_2").offset().top - $(window).height() && !commentList.ajaxBefor){
            commentList.init();
            commentList.ajaxBefor = true;
        }

        if($(window).scrollTop() > $("#offset_post_1").offset().top - 60){
            $(".change-title li").removeClass('on');
            $(".change-title li").eq(1).addClass('on');
            if($(window).scrollTop() > $("#offset_post_2").offset().top - 60){
                $(".change-title li").removeClass('on');
                $(".change-title li").eq(2).addClass('on');
                if($(window).scrollTop() > $("#offset_post_3").offset().top - 60){
                    $(".change-title li").removeClass('on');
                    $(".change-title li").eq(3).addClass('on');
                }
            }
        }else{
           $(".change-title li").removeClass('on');
            $(".change-title li").eq(0).addClass('on');
        }
   }

   checkScroll();

   $(window).scroll(function(){
        checkScroll();
   });

   $(".comment-num").click(function(){
      $(".change-title li:eq(1)").trigger('click');
      $(window).scrollTop($(".goods-msg").offset().top);
   });

   var a = new tinyScroll();

   $(".sizebox").each(function(i){
      var $t = $(this);
      $t.find("li").click(function(){        
        var index = $(this).index();
        $t.find("li").attr("class","");
        $(this).attr('class','on');
        $t.find(".switch-size").hide();
        $t.find(".switch-size").eq(index).show();        
        a.initScroll();
        positionSizebox.sizeboxResize(i,index);
      })
   });

   updateGoods.init();
   collect.init();
   goodsInfo.init();
   picChange.init();
   positionSizebox.init();
   //positionTitle.init();
   shaiwuComment.init();
   cart.init("#buy_cart");
   cart.init("#fixed_buy_cart");
   suspension.init();
   popup.init();
   promotionalInfo.init();
});

var positionSizebox={
  defaults:{
    ele:".size-content",
    ele1:'.switch-content'
  },
  init:function(){
    this.bindFun();
  },
  tinyScroll:function(){
    return new tinyScroll()
  },
  bindFun:function(){
    var t = this;
    t.doresize();
    $(window).resize(function(){
      t.doresize();
      t.tinyScroll().resizeHandle();
    })
  },
  doresize:function(){
    var t = this,
        wh = $(window).height(),
        eh = $(t.defaults.ele).height()+20;        

    if(eh < wh){
      var mt = Math.round((wh-eh)/2)
      $(t.defaults.ele).css("margin-top",mt+"px");
    }else{
      $(t.defaults.ele).css("margin-top","10px");
      t.tinyScroll().root();
      t.tinyScroll().initScroll();
    }
    t.sizeboxResize();
    
  },
  sizeboxResize:function(i,index){
    var t = this,
        sw = $(t.defaults.ele1).width(),
        ss = i === void(0) ? 1 : i;
        s  = index === void(0) ? 0 : index;       

    var tableW = $(t.defaults.ele1).eq(ss).find(".switch-size:eq("+s+")").find("table").width();    
    tableW > sw ? $(t.defaults.ele1).css("overflow-x","scroll") : $(t.defaults.ele1).css("overflow-x","hidden");
  }
}
var tinyScroll=function(){
    var t = this,
        track = 0;
    t.s_obj = $(".switch-content",".size-content");
    t.s_name = "s-viewport";
    t.s_objclass = "s-scroll";
    var ele = '<div class="scrollbar" style="position:absolute;right:10px;top:0px;width:10px;background-color:#FFF;">';
      ele +=  '<div class="track">';
      ele +=    '<div class="thumb" style="width:10px;height20px;background-color:#000;position:absolute;z-index:10;"></div>';
      ele +=      '<i style="width:1px;height:100%;background-color:#b7b7b7;left:5px;top:0px;position:absolute;z-index:5px;"></i>';
      ele +=  '</div>';
      ele += '</div>';
    //添加tinyscroll插件必要的HTML元素
    t.root=function(){
      $("."+t.s_objclass).length == 0 && (
        t.s_obj.wrap('<div id='+t.s_name+' class="viewport '+t.s_name+'" style="width:100%;height:100%;position:relative;"></div>'),
        $("."+t.s_name).wrap('<div class='+t.s_objclass+' style="overflow:hidden;width:100%;position:relative;"></div>'),
        $("."+t.s_objclass).append(ele),
        t.s_obj.addClass("overview")
      ) 
    };
    t.resizeHandle=function(){
      var wh = $(window).height(),
        headerh = 160;      
      var sh = wh - headerh;
      t.s_obj.height() > sh ? (
        $("."+t.s_objclass).css("height",sh+"px"),
        $(".scrollbar","."+t.s_objclass).show(),
        track = 1
      ):(     
        $("."+t.s_objclass).css("height","auto"),
        $(".scrollbar","."+t.s_objclass).hide(),
        track = 0
      )   
    };
    //超过可视区域高度后初始化tinyscroll插件
    t.initScroll=function(){      
      t.resizeHandle();
      var cli = $("."+t.s_objclass);
      if(cli.length>0 && track == 1){        
        if(jQuery.browser.msie&&jQuery.browser.version==6){
          $(".scrollbar","."+t.s_objclass).remove();
          t.s_obj.css({"position":"static"});
          $(".t.s_name","."+t.s_objclass).css({"overflowY":"scroll"});  
        }else{
          $(".t.s_name","."+t.s_objclass).css("overflow","hidden");
          $(".search-result",".search-content").css("padding-bottom","15px");
          //  自定义滚动条
          cli.tinyscrollbar();
        }
      }
    };
}

var promotionalInfo={
  defaults:{
    list:".activity-info ul",
    more:".more-activity",
    wrap:".activity-wrap",
    slidewrap:".slidewrap"
  },
  init:function(){
    var t= this,
        length = $(t.defaults.list).children().length;   
    if(length > 1){
      $(t.defaults.wrap).css("height","21px");
      if(length >2){
        $(t.defaults.more).show();      
        t.bindFun();
      }
    }else{
      $(t.defaults.wrap).css("height","23px");
      $(t.defaults.more).hide();
    }
  },
  bindFun:function(){   
    var t = this;
    $(t.defaults.slidewrap).hover(function(event) {
        var heg = $(t.defaults.list).children().length * 23;        
          $(t.defaults.more).addClass('expand');
          $(t.defaults.wrap).css({"overflow":"visible"});
          $(t.defaults.slidewrap).css({"border":"1px solid #e5e5e5"});               
    },function(){
        $(t.defaults.more).removeClass('expand');
        $(t.defaults.wrap).css({"overflow":"hidden"});
        $(t.defaults.slidewrap).css({"border":"1px solid #FFFFFF"});
    });     
  }
}
var updateGoods_param= '?productId='+product_id+'&goodsId='+goods_id+'&status='+status;
var updateGoods = {
  defaults:{
    updateURL:"http://www.shihuo.cn/haitao/updateProductInfo"+updateGoods_param,
    updateImg:".updateImg",
    submit:"input[name=submit]",
    buycart:"#buy_cart,#fixed_buy_cart",
    cart_unavailable:".cart-unavailable"
  },
  init:function(){
    this.checkGoods();
  },
  checkGoods:function(){
    var t = this,
    isUpdate = $(t.defaults.submit).attr("is-update-goods");
    isUpdate != 1 ? t.available() : t.update();
  },
  available:function(){
    var t=this;
    $(t.defaults.submit).show();
    $(t.defaults.updateImg).hide();
    $(t.defaults.buycart).css("display","inline-block");
    $(t.defaults.cart_unavailable).hide();
  },
  unavailable:function(){
    var t=this;
    $(t.defaults.submit).hide();
    $(t.defaults.updateImg).show();
    $(t.defaults.buycart).hide();
    $(t.defaults.cart_unavailable).show();    
  },
  update:function(){
    var t = this;
    $.getJSON(t.defaults.updateURL,function(json){
        if(json.status == 0){
          if(json.update_flag != 0){
              (
                json.update_flag == 1 && $(".buy-area").html('<div class="soldout"></div>')
              ) || (
                json.update_flag == 2 && window.location.reload()
              );
              return false
          }
          $(t.defaults.submit).attr("is-update-goods","0"); 
          t.available();       
        }else if(json.status == 1){
          $(t.defaults.submit).attr("is-update-goods","1"); 
          t.unavailable();
          var imgsrc= $(t.defaults.updateImg).find("img").attr("src").replace("btn4.gif","btn5.jpg");
          $(t.defaults.updateImg).find("img").attr("src",imgsrc);
        }
    });
  }
}
var collect={
  defaults:{
    btn:'.collect',
    url:'http://www.shihuo.cn/api/collection/id/ID/type/daigou'
  },
  init:function(){
    this.checkCollect();
    this.bindFun();
  },
  bindFun:function(){
    var t = this;
    $(t.defaults.btn).click(function(){
        if($(t.defaults.btn).attr("is_collect") == 1){
          $(t.defaults.btn).tips("已收藏",{
                    left:$(t.defaults.btn).offset().left + 00,
                    top:$(t.defaults.btn).offset().top + 30
                });
          return false
        }
        $.getJSON("http://www.shihuo.cn/api/collection/id/"+product_id+"/type/daigou",function(json){
             if(json.status == 0){
                commonLogin('hupu');
                /*$(t.defaults.btn).tips("出错啦！",{
                    left:$(t.defaults.btn).offset().left + 00,
                    top:$(t.defaults.btn).offset().top + 30
                });*/
             }else if(json.status == 1){              
                $(t.defaults.btn).attr("is_collect","1");
                var num = Math.round($(t.defaults.btn).find("span").text())+1;
                $(t.defaults.btn).find("span").text(num);
                t.checkCollect();
             }else if(json.status == 2){
                $(t.defaults.btn).tips("已收藏",{
                    left:$(t.defaults.btn).offset().left + 00,
                    top:$(t.defaults.btn).offset().top + 30
                });
             }
        })        
    });
  },
  checkCollect:function(){
    var t = this;
    var isCollect = $(t.defaults.btn).attr("is_collect");
    isCollect == 1 ? $(t.defaults.btn).removeClass('unselect').addClass('oncollect') : $(t.defaults.btn).removeClass('oncollect').addClass('unselect');
  }
}
var popup={
  defaults:{
    popupObj:".popup-wrap",
    popupBtn:".size-chart"
  },
  init:function(){
    this.showPopup();
    this.bindFun();
  },
  bindFun:function(){
    var t = this;
    var $popup = $(t.defaults.popupObj);
    $popup.find(".bg").click(function(){
      $popup.css({"left":"100%","visibility":"hidden"});   
    });
    $popup.find(".closebtn").click(function(){
      $popup.css({"left":"100%","visibility":"hidden"});   
    })
  },
  showPopup:function(){
    var t=this;
    var $popupBtn = $(t.defaults.popupBtn);
    $popupBtn.click(function(){
        $(t.defaults.popupObj).css({"left":"0px","visibility":"visible"});        
    });
  }
}
var suspension = {
  defaults:{
    suspensioner:".change-title",
    fixed:".goods-msg",
    buyarea:".buy-area"
  },  
  init:function(){  
    var t = this;
    t.bindFun();
    $(window).scroll(function(){
      t.bindFun();
    })
  },
  bindFun:function(){
    var t = this;
        st = $(window).scrollTop(),
        sh = $(t.defaults.suspensioner).height(),
        ct = $(t.defaults.buyarea).offset().top + $(t.defaults.buyarea).height(),
        ft = $(t.defaults.fixed).offset().top;
 
    st > ft ? $(t.defaults.suspensioner).css({"position":"fixed","top":"0px"}) : $(t.defaults.suspensioner).css({"position":"relative"});   
    st > ct ? $(t.defaults.suspensioner).find(".cart-area").stop(true,true).fadeIn() : $(t.defaults.suspensioner).find(".cart-area").stop(true,true).fadeOut();
  }
}

var goodsInfo = {
   init:function(){
   	   this.bindFun();
   },
   bindFun:function(){
   	   var goods_num = $("#goods_num");
   	   goods_num.find(".n1").click(function(){
            var num = goods_num.find(".num_value").val()*1;
            if(!$(this).hasClass('false') && num > 1){
            	num--;
                goods_num.find(".n2").html(num);
                goods_num.find(".num_value").val(num);
                if(num == 1){
                	$(this).addClass('false');
                }
                if(num < limit){
                	goods_num.find(".n3").removeClass('false');
                }
            }
   	   });

   	   goods_num.find(".n3").click(function(){
   	   	    var num = goods_num.find(".num_value").val()*1;
            if(!$(this).hasClass('false') && num < limit){
            	num++;
                goods_num.find(".n2").html(num);
                goods_num.find(".num_value").val(num);
                if(num == limit){
                	$(this).addClass('false');
                }
                if(num > 1){
                	goods_num.find(".n1").removeClass('false');
                }
            }
   	   });

   	   $("#zan").click(function(){
   	   	    var $this = $(this);
   	   	    if(loginflag*1 == 1){
   	   	    	$.getJSON("http://www.shihuo.cn/haitao/saveHaitaoDaigouPraise?product_id="+product_id,{},function(data){
                            if(data.status*1 == 0){
                                $this.find(".tag").html('<i class="ic ic-head on"></i>已赞');
                                $this.find("s").html(data.data.num);
                                $this.attr('id',"");
                            } 

                            if(data.status*1 == 1){
                                commonLogin('hupu');
                            }
                            if(data.status*1 == 4){
                                $this.attr('id',"");
                            }
                },"json");
   	   	    }else{
   	   	    	commonLogin('hupu');
   	   	    }
   	   });

   	   /*$(".buy-area").find(".sub").click(function(){
           if(loginflag*1 == 0){
           	   commonLogin('hupu');
           	   return false;
           }
   	   });*/
   }
}

var picChange = {
	allImg:false,
	init:function(){
        this.bindFun();
	},
	bindFun:function(){
		var imgListObj = $("#img_list"),
		    imgArray = $("#img_array"),
		    imgNumberObj = $("#num_1"),
		    btnClickObj_a = $("#click_1"),
		    btnClickObj_b = $("#click_2");

		imgListObj.find(".img").click(function(){
             var list = $(this).index();
             $(this).addClass('on').siblings().removeClass('on');
             imgArray.find('img').hide();
             imgArray.find('img').eq(list).show();
             imgNumberObj.find('s').html(list+1);
		});
	}
}

var positionTitle = {
	allNum:$(".title_js_box").length,
	list:0,
	init:function(){
    var autoVal = parseInt($(".goods-msg").find(".area-main .goods-present").outerHeight());
		if(!!$.browser.msie && parseInt($.browser.version) <= 6 || autoVal < 2000){
			return false;
		}
		this.bindFun();
	},
	bindFun:function(){
		var baseTitleObj = $(".base-title"),
		    baseTitleBox = $(".title_js_box"),
		    that = this;
        $(window).scroll(function(event) {
        	 if($(this).scrollTop() > baseTitleBox.eq(that.list).offset().top){
        	 	baseTitleObj.eq(that.list).css({
        	 		left:baseTitleBox.eq(that.list).offset().left - 50,
        	 		top:that.list*53,
        	 		zIndex:1
        	 	}).addClass('post_js_tit');
        	 	that.list++;
        	 }
        	 if(that.list-1 >= 0 && $(this).scrollTop() < baseTitleBox.eq(that.list-1).offset().top){
                 baseTitleObj.eq(that.list-1).css({
        	 		left:-50,
        	 		top:0,
        	 		zIndex:0
        	 	}).removeClass('post_js_tit');
                 that.list--;
        	 }
        });

        $(".post_js_tit").live("click",function(){
             var top = $(this).parent(".title_js_box").offset().top - 30;
             $('html, body').animate({
	            scrollTop:top
	        },500);
        });
	}
}

var commentList = {
    defaults:{
       pages:1,
       is_img:0
    },
    ajaxBefor:false,
    ajaxLoding:false,
    init:function(){
        this.getAjax();
        this.bindClick();
    },
    getAjax:function(){
        var that = this;
        if(that.ajaxLoding){
           return false;
        }
        that.ajaxLoding = true;
        $.post("http://www.shihuo.cn/haitao/ajaxComment",{page:this.defaults.pages,is_img:this.defaults.is_img,product_id:product_id},function(data){
              if(data.status){
                 that.allPage = data.msg.num;
                 that.writePage();
                 that.writrTxt(data.msg.res);
                 $(".none-comment").hide();
              }else{
                 $(".none-comment").show();
                 $(".comment-list,.comment-pages").hide();
              }
              that.ajaxLoding = false;
        },"json");
    },
    writrTxt:function(data){
         var str = [],
             tag="",
             info="",
             img="",
             x,
             y;
          for(var i=0; i<data.length; i++){
            for(x in data[i].tags_attr){
               tag += '<span>'+data[i].tags_attr[x]+'</span>';
            }
            for(y in data[i].imgs){
               img += '<div class="imgs">\
                           <img src="'+data[i].imgs[y]+'?imageView2/1/w/50" / data_src="'+data[i].imgs[y]+'">\
                       </div>';
            }
            for(z in data[i].attr){
              info += '&nbsp;&nbsp;&nbsp;&nbsp;'+z+' : '+data[i].attr[z];
            }


            str.push('<li class="clearfix">\
                      <div class="comment-main">\
                          <img src="'+data[i].user_head+'" />\
                          <p>'+data[i].user_name+'</p>\
                      </div>\
                      <div class="comment-sub">\
                          <div class="tips-area">'+tag+'</div>\
                          <div class="txt">'+data[i].content+'</div>\
                          <div class="pic clearfix">'+img+'</div>\
                          <div class="picBig"></div>\
                          <div class="date">'+data[i].created_at+info+'</div>\
                      </div>\
                 </li>');
             tag="";
             info="";
             img="";
          }
          $(".comment-list").html(str.join("")).show();
    },
    writePage:function(){
         var allPage = this.allPage,
             str = '<a class="pre" href="#comment">&lt; 上一页</a>';
             if(allPage == 1){
                 $(".comment-pages").hide();
                 return false;
             }else{
                 $(".comment-list,.comment-pages").show();
             }

          if(allPage < 6 || this.defaults.pages<6){
             if(allPage < 6){
                 for(var i=0; i<allPage; i++){
                    str+='<a class="a1'+(i==(this.defaults.pages-1)?" on":"")+'" href="#comment">'+(i+1)+'</a>';
                }
             }else{
                for(var i=0; i<6; i++){
                    str+='<a class="a1'+(i==(this.defaults.pages-1)?" on":"")+'" href="#comment">'+(i+1)+'</a>';
                }
             }
              
          }else{
              for(var i=0; i<2; i++){
                  str+='<a class="a1" href="#comment">'+(i+1)+'</a>';
              }
              str+='<span>...</span>';

              for(var i=0; i<4; i++){
                  if(this.defaults.pages-2+i > allPage){
                      break;
                  }
                  str+='<a class="a1'+(this.defaults.pages==(this.defaults.pages-2+i)?" on":"")+'" href="#comment">'+(this.defaults.pages-2+i)+'</a>';
              }
          }
          str+='<a class="next" href="#comment">下一页 &gt;</a>';
          $(".comment-pages").html(str);
          if(this.defaults.pages > 1){
              $(".comment-pages .pre").css({
                  cursor:"pointer"
              });
          }
          if(this.defaults.pages == allPage){
              $(".comment-pages .next").css({
                  cursor:"auto"
              });
          }
    },
    bindClick:function(){
        var that = this;
        $(".comment-pages .a1").live("click",function(){
            var tr = $(this).html();
            that.defaults.pages = tr;
            that.getAjax();
            $(window).scrollTop($("#offset_post_2").offset().top);
        });

        $(".comment-pages .pre").live("click",function(){
            var tr = that.defaults.pages*1-1;
            if(tr > 0){
              that.defaults.pages = tr;
              that.getAjax();
              $(window).scrollTop($("#offset_post_2").offset().top); 
            }
        });

        $(".comment-pages .next").live("click",function(){
            var tr = that.defaults.pages*1+1;
            if(tr <= that.allPage){
              that.defaults.pages = tr;
              that.getAjax(); 
              $(window).scrollTop($("#offset_post_2").offset().top);
            }
        });

        $("input[name='comPic']").change(function(){
              that.defaults.is_img = $(this).attr("inf")*1;
              that.defaults.pages = 1;
              that.getAjax();
        });

        $(".comment-sub").find(".imgs").live("click",function(){
             var link = $(this).find("img").attr("data_src")+'?imageView2/1/w/350';
             $(this).parent(".pic").next().html("<img src='"+link+"' />");
        });

        $(".comment-sub").find(".picBig img").live("click",function(){
            $(this).parent(".picBig").html("");
        });
    }
}

var shaiwuComment = {
    ajaxBefor:false,
    ajaxLoding:false,
    init:function(){
        this.getAjax();
        this.bindClick();
    },
    getAjax:function(){
        var that = this;
        if(that.ajaxLoding){
           return false;
        }
        that.ajaxLoding = true;
        $.post("http://www.shihuo.cn/haitao/ajaxShaiwu",{product_id:product_id},function(data){
            if(data.status*1 == 2){
                $(".shaiwu-comm-list").next().show();
            }else{
                that.writeHtml(data.data);
            }
            
        },"json");
    },
    writeHtml:function(o){
        var str = "";
        for(var i=0;i<o.length;i++){
            str += '<li class="clearfix">\
                            <div class="t1">\
                                <img src="http://bbs.hupu.com/bbskcy/api_new_image.php?type=big&uid='+o[i].author_id+'" width="45" />\
                            </div>\
                            <div class="t2">\
                                <div class="name">'+o[i].author_name+' '+(o[i].is_star==1?'<img src="/images/trade/shaiwu/shaiwuindex/rk.jpg" />':'')+'</div>\
                                <div class="time">'+o[i].publish_time+'</div>\
                                <div class="h2">'+(o[i].is_hot==1?'<img src="/images/trade/shaiwu/shaiwuindex/boutiqueicon.png" /> ':'')+''+o[i].activity_name+' <a target="_blank" href="http://www.shihuo.cn/shaiwu/detail/'+o[i].id+'.html">'+o[i].title+'</a></div>\
                                <div class="img-txt clearfix">\
                                    <div class="l-img">\
                                        <img src="'+o[i].front_pic+'" />\
                                    </div>\
                                    <div class="r-txt">\
                                        '+o[i].intro+'<a target="_blank" href="http://www.shihuo.cn/shaiwu/detail/'+o[i].id+'.html">查看详情</a>\
                                    </div></div>\
                            </div>\
                        </li>';
        }
        $(".shaiwu-comm-list").append(str);
    },
    bindClick:function(){

    }
}

var cart = {
     ajaxLoding:false,
     init:function(btn){
          if($(btn).length > 0){
               this.bindFun(btn);
          }
     },
     bindFun:function(btn){
          var obj = $(btn),
              that = this;
          obj.click(function(){
               var num = $("#goods_num").find(".n2").html();
               if(that.ajaxLoding){
                  return false;
               }
               that.ajaxLoding = true;
               $.post("http://www.shihuo.cn/haitao/addCart",{product_id:product_id,goods_id:goods_id,number:num,from:from},function(data){
                    if(data.status*1 == 0){
                        $("#cart-right-area .goods-num").html(data.data.count);
                        $("#cart_num_nva").html(data.data.count);
                        that.animate(data.data.img_path,btn);
                    }

                    if(data.status*1 == 1){
                        var top = btn == "#fixed_buy_cart" ?  50 : -30;
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
          });
     },
     animate:function(data,btn){
         var str = '<img id="cart-img-box" style="position: absolute; left:'+($(btn).offset().left*1+100)+'px;top:'+$(btn).offset().top+'px; width:45px; height:45px;" src="'+data+'" />',
         that = this;
         $(str).appendTo('body');
         btn == "#fixed_buy_cart" ? $("#cart-img-box").animate({left:$("#cart-right-area").offset().left,top:$("#cart-right-area").offset().top},500,function(){
            $("#cart-img-box").remove(); 
             that.ajaxLoding = false;
         }) : $("#cart-img-box").paracurve({
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