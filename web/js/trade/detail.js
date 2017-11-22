/*瀑布加载图片*/
(function($,win,doc){
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
                 });
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
                         top = 0;//计算right值
                         itemClass.eq(n).data("list",n);
                    }else{
                        var k;
                        k = this.postTop(el,n);//计算left和TOP值
                        left = k[1];
                        top = k[0];
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
                                    animateFun({
                                        left: left,
                                        top: top,
                                        opacity: 0
                                    }, {
                                        opacity: 1
                                    });
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
           $(el).append('<div class="loding_img" style="left:'+($(el).width()/2-25)+'px"></div>');
           $(el).append(el.defaults.dom);
           $(el).imagesLoaded(function(){
               $(".loding_img").remove();
               if(el.defaults.resize){
                  that.postLeft([el,n,true,"addDom"]);
               }else{
                  that.postLeft([el,n]);
               }
           });
       },
       _getpageSize: function() {//获取窗口大小
            var de = doc.documentElement, arrayPageSize,
                    w = win.innerWidth || self.innerWidth || (de && de.clientWidth) || doc.body.clientWidth,
                    h = win.innerHeight || self.innerHeight || (de && de.clientHeight) || doc.body.clientHeight;
            arrayPageSize = [w, h];
            return arrayPageSize;
       }
   }
   $.fn.extend({
        imagesLoaded:function(callback) {//判断图片是否加载完成
            var elems = this.find('img'),
                    elems_src = [],
                    self = this,
                    len = elems.length;
            if (!elems.length) {
                callback.call(this);
                return this;
            }
            elems.one('load error', function() {
                if (--len === 0) {
                    len = elems.length;
                    elems.one('load error', function() {
                        if (--len === 0) {
                            callback.call(self);
                        }
                    }).each(function() {
                        this.src = elems_src.shift();
                    });
                }
            }).each(function() {
                elems_src.push(this.src);
                this.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
            });
            return this;
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

(function(){
     $(".left_content_list ul").Mymasonry({//初始化瀑布
      itemClass:'.list', //class 选择器
      resize:{
        isAnimated:true,
        speed:500,
        easing:"easeInOutCubic"//动画摩擦形式 目前支持 swing/linear/easeInOutCubic/easeInOutQuart/easeInOutQuint/easeInOutSine
      },//是否窗口自适应
      css:{
        left:11,
        top:19
      }, //排列间隔
      animateOptions: {
        animate: "fade",//fade,right_bottom,bottom
        speed: 500,
        easing:"easeInOutCubic"//动画摩擦形式 目前支持 swing/linear/easeInOutCubic/easeInOutQuart/easeInOutQuint/easeInOutSine
      }//动画效果
    });

   var bindClickFun = {
      init:function(){
          this.clicks();
          this.strfun();
      },
      strfun:function(){
            var k;
            function subString(str, len, hasDot){//字符串截取
              var newLength = 0,
              newStr = "",
              chineseRegex = /[^\x00-\xff]/g,
              bignumae = /[A-Z]/g,
              singleChar = "",
              strs = str.replace(chineseRegex,"**").replace(bignumae,"**"),
              strLength = strs.length;
              if(bignumae.test(strs)){
                  var strplace = strs.match(bignumae).length;
                  var strst = strs.replace(bignumae,"").length;
                  var strsts = parseInt(strst + parseInt(strplace/2));
                  if(strLength > len){
                      strLength = strsts;
                  }
              }
              for(var i = 0;i < strLength;i++){
                  singleChar = str.charAt(i).toString();
                  if(singleChar.match(chineseRegex) != null || singleChar.match(bignumae) != null){
                      newLength += 2;
                  }else{
                      newLength++;
                  }
                  if(newLength > len){
                      break;
                  }
                  newStr += singleChar;
              }
              if(hasDot && strLength > len){
                k = true;
                  newStr += "...";
              }
              return newStr;
         }
           
           var str = $(".goods_txt_right").find(".font").html(),obj=$(".goods_txt_right").find(".font"),obj2 = $(".goods_txt_right").find(".slid");
           obj.html(subString(str, 340, true));
           
         if(k){
             obj2.eq(0).click(function(){
                        obj.html(str);
                        $(this).hide();
                        obj2.eq(1).show();
                    
             });

             obj2.eq(1).click(function(){
                    $(this).hide();
                    obj2.eq(0).show();
                    obj.html(subString(str, 340, true));
             });
         }else{
             obj2.remove();
         }
      },
      clicks:function(){
          function hovers(obj){
              obj.hover(function(){
                  $(this).css({
                    "backgroundColor":"#e0effb"
                  });
              },function(){
                  $(this).css({
                    "backgroundColor":"#eeeeee"
                  });
              });
          }
          hovers($(".left_content_list").find("li"));
          hovers($(".hupubuy_content_box_right").find("li"));
      }
   }

   bindClickFun.init();

   var postRight = {//右侧浮动
     init:function(){
         this.a = $(".post_right");
		  this.a.remove();
         return false;
         if(!(!!$.browser.msie && parseInt($.browser.version) <= 6)){
             this.a.css({
                 top:this._getpageSize()[1] - 80
            });
         }
         this.bindClick();
     },
     bindClick:function(){
         var that = this;
         $(window).scroll(function(){
             if(that.getpageScroll() > 200){
                 that.a.find(".c_d").css("display","block");
             }else{
                 that.a.find(".c_d").hide();
             }
         });
         that.a.find(".c_d").click(function(){
             $('html, body').animate({
                 scrollTop:0
             });
         });
     },
     getpageScroll: function() {
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
      _getpageSize: function() {
            var de = document.documentElement, arrayPageSize,
                    w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth,
                    h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
            arrayPageSize = [w, h]
            return arrayPageSize;
      }
  };

  postRight.init();

  var sc_js = {
    init:function(){
         this.bindFun();
    },
    bindFun:function(){
      var $goods_txt_right = $(".goods_txt_right"),k,html = parseInt($goods_txt_right.find(".numhtml").html());
      $goods_txt_right.find(".btn").click(function(){
	    if(k){return false};
		k = true;
        var $this = $(this);
        $.post(addlikeUrl,{like_type :like_type,id : item_id}, function(data){
            var datas = eval("("+data+")");
             if(datas.status*1 < 0){
			    k = false;
                if(datas.status*1 == -2){
                    commonLogin();
                }
                if(datas.status*1 == -5){
                    k = true;
                }
                return false;
             }
             $this.html("已收藏");
             $goods_txt_right.find(".num").show();
             $goods_txt_right.find(".num").animate({
                top:-25
             });
             $goods_txt_right.find(".num").fadeOut();
             $goods_txt_right.find(".numhtml").html(html+=1);
        });
      });
    }
  }
  sc_js.init();
})(jQuery);

