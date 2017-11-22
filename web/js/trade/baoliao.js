$(function(){
  if(typeof(crawler_flag) !== "undefined" && !crawler_flag){
    pubu.init();
  }
});

var pubu = {
  itemsNum:0,
  init:function(){
    $(".goods_list").Mymasonry({//初始化瀑布
          itemClass:'.list', //class 选择器
          resize:{
             isAnimated:true,
             speed:800,
             easing:"easeInOutCubic"//动画摩擦形式 目前支持 swing/linear/easeInOutCubic/easeInOutQuart/easeInOutQuint/easeInOutSine
          },//是否窗口自适应
          css:{
              left:20,
              top:15
          }, //排列间隔
          animateOptions: {
              animate: "fade",//fade,right_bottom,bottom
              speed: 800,
              easing:"easeInOutCubic"//动画摩擦形式 目前支持 swing/linear/easeInOutCubic/easeInOutQuart/easeInOutQuint/easeInOutSine
          }
      });
      this.getScroll();
  },
  getScroll:function(){
    var that = this,totalheight,x,y,
        obj = $(".goods_list");
        $(window).scroll(function(){
           if($(document).height() <= screen.height){
              return false;
           }
           totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()) + 200;
            if ($(document).height() <= totalheight) {
              that.t?clearTimeout(that.t):"";
              that.t = setTimeout(function(){
                   that.getjsongHtml();
              },30);
            }
        });

       obj.delegate(".btn","mousemove",function(){
           $(this).addClass("hover");
       });

       obj.delegate(".btn","mouseout",function(){
           $(this).removeClass("hover");
       });

       obj.delegate(".btn","click",function(){
           that.getjsongNum($(this));
       });
       obj.delegate(".imgs","mousemove",function(){
             $(this).parent().find(".edit").show();
             $(this).parent().find(".btn").show();
             $(this).parent().find(".recommend").show();
        });
        obj.delegate(".imgs","mouseout",function(){
            var $this = $(this);
            that.hov = setTimeout(function(){
              $this.parent().find(".edit").hide();
              $this.parent().find(".btn").hide();
              $this.parent().find(".recommend").hide();
            },100);
       });

        obj.delegate(".btn","mousemove",function(){
             clearTimeout(that.hov);
             $(this).show();
             $(this).parent().find(".edit").show();
        });
        obj.delegate(".edit","mousemove",function(){
             clearTimeout(that.hov);
             $(this).show();
             $(this).parent().find(".btn").show();
        });
        obj.delegate(".recommend","mousemove",function(){
             clearTimeout(that.hov);
             $(this).show();
             $(this).parent().find(".recommend").show();
        });
  },
  getjsongHtml:function(){//获取瀑布数据
    var str = "",arr = items,k=this.itemsNum+12;
    if(this.itemsNum > arr.length){
        return false;
    }
    for(var i=this.itemsNum;i<k; i++){
        var link = "";
        if(i >= arr.length){
          break;
        }
        if(arr[i].shoe_id){
          link = "/shihuo/admin?shoe_id="+arr[i].shoe_id+"&item_all_id="+arr[i].id+"&baoliao_id="+arr[i].baoliao_id+"&title="+encodeURI(arr[i].title)+"&name="+encodeURI(arr[i].name)+"&mart="+encodeURI(arr[i].mart)+"&url="+encodeURI(arr[i].go_url)+"&memo="+encodeURI(arr[i].memo)+"&from=find";
        }else{
          link = "/shihuo/admin?item_all_id="+arr[i].id+"&baoliao_id="+arr[i].baoliao_id+"&title="+encodeURI(arr[i].title)+"&name="+encodeURI(arr[i].name)+"&mart="+encodeURI(arr[i].mart)+"&url="+encodeURI(arr[i].go_url)+"&memo="+encodeURI(arr[i].memo)+"&from=find";
        }
        if(arr[i].is_taobao){
            var nofollow = '';
        }else{
            var nofollow = 'rel="nofollow"';
        }
        str+='<div class="list" shoe_id="'+(arr[i].shoe_id?arr[i].shoe_id:0)+'" all_id ="'+arr[i].id+'">\
          '+(hasCredential?'<div class="recommend">'+(arr[i].is_recommend == 1?'已推荐到首页':'<a href="'+link+'" target="_blank">推荐到首页</a>')+'</div><div class="edit"><a href="'+arr[i].edit_url+'" target="_blank">编辑</a></div>':'')+(!hasCredential && recommendlimit?'<div class="recommend">'+(arr[i].is_recommend == 1?'已推荐到首页':'<a href="'+link+'" target="_blank">推荐到首页</a>')+'</div>':'')+'\
          <div class="imgs" style="height:'+arr[i].height+'px">\
            <a '+nofollow+' href="'+(arr[i].is_taobao?arr[i].detail_url:arr[i].url)+'" target="_blank"><img src="http://shihuo.hupucdn.com'+arr[i].img_url+'-S253.jpg" width="208" height="'+arr[i].height+'" /></a>\
            <div class="btn">\
              <span class="wuxing"></span><span class="num">'+(arr[i].like_count==0?"收藏":arr[i].like_count)+'</span>\
            </div>\
          </div>\
          <h2 class="title">'+(arr[i].is_hot==1?'<font color="red">【热】</font>':'')+'<a href="'+arr[i].detail_url+'" target="_blank">'+arr[i].title+'</a> <span class="num">￥'+arr[i].price+'</span></h2>\
          <p class="tips">'+(arr[i].hupu_username?'<b>'+arr[i].hupu_username+'：</b>':'')+arr[i].memo+'</p>\
                  <div class="buy_box">\
                    <span class="time"><a ref="nofollow" href="'+arr[i].detail_url+'" target="_blank">'+arr[i].publish_date+'</a></span><a '+nofollow+' href="'+(arr[i].is_taobao?arr[i].detail_url:arr[i].url)+'" target="_blank" class="buy">去购买 ></a>\
                </div>\
        </div>';
    }
    this.itemsNum+=12;

    $(".goods_list").Mymasonry({
            dom:str
    },"addDom");
  },
  getjsongNum:function(obj){
     var top = obj.offset().top-10,
         left = obj.offset().left+20,
         addnum,
         shoe_id = obj.parents(".list").attr('shoe_id'),
         all_id = obj.parents(".list").attr('all_id'),
         str = '<div class="addnum" style="color:#fff; position: absolute; font-size:14px; color:#ffba00; left:'+left+'px; top:'+top+'px;">+1</div>',
         str2 = '<div class="addnum" style="color:#fff; position: absolute; font-size:14px; color:#ffba00; left:'+(left-50)+'px; top:'+top+'px;"></div>';
         if(!user_id){
              commonLogin(); return false;
          }
          if(shoe_id > 0){
              var like_type = 'shoe';
              var like_id = shoe_id;
          }else{
              var like_type = 'all';
              var like_id = all_id;
          }
     $.post(addlikeUrl,{like_type : like_type,id : like_id},function(data){
         var dataObj=eval("("+data+")");
         if(dataObj.status*1 == 1){
             $(obj).addClass("hover2");
             $(str).appendTo("body");
             addnum = $(".addnum");
             addnum.animate({
                  top:top-10
             },function(){
                setTimeout(function(){
                     addnum.remove();
                },1000);
             });
             $(obj).find(".num").html(dataObj.count);
         }else{
            $(str2).appendTo("body");
             addnum = $(".addnum");
             addnum.html(dataObj.message).animate({
                  top:top-10
             },function(){
                setTimeout(function(){
                     addnum.remove();
                },1000);
             });
         }
     });
  }
}

/*瀑布加载图片*/
;(function($,win,doc){
   var Mymasonry = {
       defaults:{
            itemClass:".list",//目标class
            css:{
                left:0,
                top:0
            }, //排列间隔
            animateOptions: {
                animate: false,//排列动画
                speed: 500,//动画速度
                easing:"easeInOutCubic"
            }
       },
       init:function(){
           var that = Mymasonry,arg = arguments;
           return this.each(function(){
              var el = this;
              el.defaults = $.extend(true,{}, that.defaults, arg[0] || {});//合并默认参数和自定义参数并为当前对象赋值
              el.defaults.allWidth = $(el).outerWidth();
              $(el).css({"position":"relative"});
              if(arg[1] == "addDom"){//是否为添加DOM
                  el.defaults = $.extend(true,{}, $(el).data("defaults"), arg[0] || {});
                  that.addDom(el);
              }else{
                 $(el).data("defaults",el.defaults);
                 $(el).find(el.defaults.itemClass).hide();
                 $(el).imagesLoaded(function(){//图片加载完成
                    
                 });
                 if(el.defaults.bindFun){
                        el.defaults.bindFun();
                    }
                    if(el.defaults.resize){//是否支持窗口自适应
                        $(win).resize(function(){
                            $(el).find(el.defaults.itemClass).removeClass("mymasonry");
                            that.postLeft([el,0,true]);
                        });
                        that.postLeft([el,0,true,"init"]);
                    }else{
                        that.postLeft([el,0]);//开始排列
                    }
              }
           });
       },
       postLeft:function(obj){//el对象自身，n从第几个开始排列,ani排列是否动画形式
           var el=obj[0],itemClass = $(el).find(el.defaults.itemClass),i = 0,n=obj[1],animates = el.defaults.animateOptions,left,top;
           if(obj[2]){
              var windoWidth;
              this._getpageSize()[0] < (el.defaults.allWidth+$(el).offset().left)?windoWidth=this._getpageSize()[0] - $(el).offset().left:windoWidth=el.defaults.allWidth;
              el.defaults.listNum = parseInt(windoWidth/(itemClass.eq(0).outerWidth()+el.defaults.css.left));//判断一列的排列个数
           }else{
              el.defaults.listNum = parseInt(el.defaults.allWidth/(itemClass.eq(0).outerWidth()+el.defaults.css.left));//判断一列的排列个数
           }
           itemClass.css({"position":"absolute"});
           while(n<itemClass.length){
               if(!itemClass.eq(n).hasClass("mymasonry")){//已经排列过的过滤
                    if(n<el.defaults.listNum){//如果是第一行
                         left = (itemClass.outerWidth()+el.defaults.css.left) * i;//计算left值
                         top = 0+el.defaults.css.top;//计算right值
                         itemClass.eq(n).data("list",n);
                    }else{
                        var k;
                        k = this.postTop(el,n);//计算left和TOP值
                        left = k[1];
                        top = k[0]+el.defaults.css.top;
                        itemClass.eq(n).data("list",k[2]);//保存DOM添加的位置
                    }
                    if(obj[2] && !obj[3]){//如果是窗口变化
                        if(el.defaults.resize.isAnimated){//如果是动画
                            itemClass.eq(n).data("style",{//保存top,left值以免动画时取得不真实的left和top
                                left: left,
                                top: top
                            });
                            itemClass.eq(n).show().stop(true).animate({
                                left: left,
                                top:top
                             },el.defaults.resize.speed,"easeInOutCubic");
                        }else{
                            itemClass.eq(n).show().css({
                                left: left,
                                top:top
                            });
                        }
                    }else{
                        if(animates.animate){//如果是动画排列
                            itemClass.eq(n).data("style",{//保存top,left值以免动画时取得不真实的left和top
                                left: left,
                                top: top
                            });
                            function animateFun(init,over){//动画方法
                                  itemClass.eq(n).addClass("mymasonry").css(init);
                                  itemClass.eq(n).show().stop(true).animate(over,animates.speed);
                            }
                            switch (animates.animate ? animates.animate : "fade") {//动画形式
                                case "fade":
                                    if(this.isie6){
                                       animateFun({
                                          left: left,
                                          top: top
                                      }, {
                                          opacity: 1
                                      });
                                    }else{
                                      animateFun({
                                          left: left,
                                          top: top,
                                          opacity: 0
                                      }, {
                                          opacity: 1
                                      });
                                    }
                                    break;
                                case "right_bottom":
                                    animateFun({
                                        left: left + 30,
                                        top: top + 30
                                    }, {
                                        left: left,
                                        top: top
                                    });
                                    break;
                                case "bottom":
                                    animateFun({
                                        left: left,
                                        top: top + 100
                                    }, {
                                        left: left,
                                        top: top
                                    });
                                    break;
                                default:
                                    animateFun({
                                        left: left,
                                        top: top,
                                        opacity: 0
                                    }, {
                                        opacity: 1
                                    });
                            }
                        }else{
                           itemClass.eq(n).addClass("mymasonry").css({
                                 left:left,
                                 top:top
                           }).show();
                        }
                    }
                    i == el.defaults.listNum -1?i = 0:i++;
                    n++;
               }
           }
           $(el).height(this.getHeight(itemClass));//排序完毕计算父元素高度
       },
       postTop:function(el,n){
           var itemClass = $(el).find(el.defaults.itemClass),i=0;
           function size(arr){//比较数组中的大小
                //arr = sorts(arr);
                var j = arr[0][0],m = 0,i=0;
                for (;i<arr.length ;i++){
                    (function(i){
                       if(arr[i][0]<j){
                            j = arr[i][0];
                            m = i;
                       }
                    })(i);
                }
                return [arr[m][0],arr[m][1],m];
           }
           el.defaults.postArr = [];
           for(;i<el.defaults.listNum;i++){//计算每一列的行高
               el.defaults.postArr[i] = [0,0];
               for(var s=0;s<n;s++){
                   if(itemClass.eq(s).data("list")*1 == i){
                      el.defaults.postArr[i] = [el.defaults.postArr[i][0]+=itemClass.eq(s).outerHeight()+el.defaults.css.top,!itemClass.eq(i).data("style")?el.defaults.postArr[i][1]=itemClass.eq(s).position().left:itemClass.eq(i).data("style").left];
                   }
               }
           }
           return size(el.defaults.postArr);
       },
       getHeight:function(itemClass){//取得父元素真实高度
           var lengths = itemClass.length,i = 0,n=0,t;
           for(;i<lengths;i++){
               t = itemClass.eq(i).outerHeight() + (itemClass.eq(i).data("style")?itemClass.eq(i).data("style").top:itemClass.eq(i).position().top);
               if(t > n){
                   n = t;
               }
           }
           return n;
       },
       addDom:function(el){//添加DOM
           var that = this,n = $(el).find(el.defaults.itemClass).length;
           if($(".loding_img").length == 0){
             $(el).append('<div class="loding_img" style="left:'+($(el).width()/2-25)+'px"></div>');
             $(el).append(el.defaults.dom);
            $(el).find(el.defaults.itemClass).not($(".mymasonry")).imagesLoaded(function(){
                 $(".loding_img").remove();
                 if(el.defaults.resize){
                    that.postLeft([el,n,true,"addDom"]);
                 }else{
                    that.postLeft([el,n]);
                 }
             });
           }
       },
       _getpageSize: function() {//获取窗口大小
            var de = doc.documentElement, arrayPageSize,
                    w = win.innerWidth || self.innerWidth || (de && de.clientWidth) || doc.body.clientWidth,
                    h = win.innerHeight || self.innerHeight || (de && de.clientHeight) || doc.body.clientHeight;
            arrayPageSize = [w, h];
            return arrayPageSize;
       },
       isie: !!$.browser.msie,
       isie6: (!!$.browser.msie && parseInt($.browser.version) <= 8)
   }
   $.fn.extend({
        imagesLoaded:function(a) {
            var b = $;
            function h() {
                a.call(c, d)
            }
            function i(a) {
                var c = a.target;
                c.src !== f && b.inArray(c, g) === -1 && (g.push(c), --e <= 0 && (setTimeout(h), d.unbind(".imagesLoaded", i)))
            }
            var c = this,
            d = c.find("img").add(c.filter("img")),
            e = d.length,
            f = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",
            g = [];
            return e || h(),
            d.bind("load.imagesLoaded error.imagesLoaded", i).each(function() {
                var a = this.src;
                this.src = f,
                this.src = a
            }),
            c
        },
        Mymasonry:function(){//初始化判断
            var method = arguments[0];
            if (Mymasonry[method]) {
                method = Mymasonry[method];
                arguments = Array.prototype.slice.call(arguments, 1);
            } else if (typeof(method) == 'object' || !method) {
                method = Mymasonry.init;
            } else {
                $.error('plugin ' + method + ' undefined');
                return this;
            }
            return method.apply(this, arguments);
        }
    });

    $.extend($.easing, {
        easeInOutCubic: function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t + 2) + b;
        },
        easeInOutQuart: function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t * t + b;
            return - c / 2 * ((t -= 2) * t * t * t - 2) + b;
        },
        easeInOutQuint: function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t * t * t + 2) + b;
        },
        easeInOutSine: function(x, t, b, c, d) {
            return - c / 2 * (Math.cos(Math.PI * t / d) - 1) + b;
        }
    });
})(jQuery,window,document);
