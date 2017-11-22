$(function(){
   check_link.init();
   shares.init();
   $(".ul_box").find("ul").eq(0).animateTop({
       time:10000,
       num:5
   });

   $("#postbox_hong").animateTop({
       time:3000,
       num:1
   });
});

var check_link = {
    defaults:['newegg.com.cn','yixun.com','sfbest.com','dangdang.com','vip.com','dianping.com','vipshop.com','gome.com.cn','keede.com','360buy.com','jd.com','suning.com','yougou.com','amazon.com','amazon.cn','taobao.com','tmall.com'],
    ajaxLoding:false,
    init:function(){
        this.arrays = {};
        for(var i=0;i<this.defaults.length; i++){
            this.arrays[this.defaults[i]] = this.defaults[i];
        }
        this.bindFun();
    },
    bindFun:function(){
      var that = this,
          inputs_fours = $(".inputs_fours");
      $(".checking").click(function(){
         var s = that.parseURL($(this).parent().find("input").val()).host.split("."),
             m = s[1]+'.'+s[2];
             if(that.arrays[m]){
                $(this).parent().find(".result2").hide();
                $(this).parent().find(".result").show();
                $.getJSON("http://www.shihuo.cn/lottery/verification",{url:$(this).parent().find("input").val()},function(data){
                       if(data.status*1 == 0){
                          $(".start_btn").find(".nums").html('<span>X</span>'+data.data.lottery_num);
                       }
                });
                //$.cookie('winner',true,{expires:1});
                window.open('http://go.hupu.com/u?url='+encodeURIComponent($(this).parent().find("input").val()));
             }else{
                $(this).parent().find(".result").hide();
                $(this).parent().find(".result2").show();
             }
      });

      $(".checking").hover(function(){
          $(this).addClass("bgs");
      },function(){
          $(this).removeClass("bgs");
      });

      inputs_fours.find("input").focus(function(){
           $(this).parent().addClass("bgs");
           if(!$(this).data("fouses")){
              $(this).val("");
              $(this).data("fouses",true);
           }
      });

      inputs_fours.find("input").blur(function(){
           $(this).parent().removeClass("bgs");
           if($.trim($(this).val()) == ""){
              $(this).val($(this).attr("data-val"));
              $(this).data("fouses",null);
           }
      });

      $(".start_btn").click(function(){
          if(that.ajaxLoding){
             return false;
          }
          that.ajaxLoding = true;
          that.getAnimate();//开始抽奖
      });

      $(".start_btn").hover(function(){
          $(this).addClass("bgs");
      },function(){
          $(this).removeClass("bgs");
      });

      $(".copy_btn").click(function(){
         that.copyToClipboard($(this).prev().val(),$(this));
      });

      $(".winning").find(".close").click(function(){
        $(".winning").hide();
        $.ui._closeMasks();
      }); 

      $(".yanzheng").find(".more_goods").click(function(){
          $(".winning").hide();
          $.ui._closeMasks();
          $(".inputs_fours").find("input").focus();
      });
    },
    copyToClipboard:function(copy,obj){   
        if($.ui.isie){
           if (window.clipboardData){
            window.clipboardData.setData("Text", copy);}
            else if (window.netscape){
            try {   
                 netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");   
            } catch (e) {   
                 obj.tips("非IE浏览器请ctrl+c复制");  
                 obj.prev().select(); 
                 return false;
            }
            var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
            if (!clip) return;
            var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
            if (!trans) return;
            trans.addDataFlavor('text/unicode');
            var str = new Object();
            var len = new Object();
            var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
            var copytext=copy;
            str.data=copytext;
            trans.setTransferData("text/unicode",str,copytext.length*2);
            var clipid=Components.interfaces.nsIClipboard;
            if (!clip) return false;
            clip.setData(trans,null,clipid.kGlobalClipboard);}
            obj.tips("复制成功");
            return false;
        }else{
           obj.tips("非IE浏览器请ctrl+c复制");  
           obj.prev().select(); 
        }
    },
    getAnimate:function(){
        var that=this,
            showAni = $(".showAni"),
            k=500,
            s = 0,
            f = confuseArray1([30,35,40])[0];
            function confuseArray1(array){//随机数字方法
                  var arrlength=array.length;
                  var tmparr;
                  var tmparray= new Array;
                  for(var i=0;i<arrlength;i++){
                      var tid = parseInt(array.length*Math.random());
                      tmparr = array[tid];
                      array[tid] = array[array.length-1];
                      array[array.length-1] = tmparr;
                      array.pop();
                      tmparray.push(tmparr);
                  }
                    return tmparray;
              }
              function setTimes(tm,object){
                   that.t = setTimeout(function(){
                        setTimes(k,object);
                        showAni.hide();
                        showAni.eq(s).show();
                        s+=1;
                        s>2?s=0:"";
                        if(k<10){
                           clearTimeout(that.t);
                           setTimeout(function(){
                              that.showEnd(object);
                           },500);
                        }
                   },tm);
                   k-=f;
               }
            $.getJSON("http://www.shihuo.cn/lottery/start?token="+token,{},function(data){
              switch(data.status*1){
                  case 0:
                    $(".start_btn").find(".nums").html('<span>X</span>'+data.data.lottery_num);
                    setTimes(k,$(".win"));
                    $(".win").find(".right").html('<p class="tit">恭喜你！获得'+data.data.type+'元红包！</p>\
                    <p>卡号：'+data.data.account+'</p>\
                    <p>密码：'+data.data.pass+'</p>\
                    <div class="bottom">\
                         <a href="https://hongbao.alipay.com/coupon/getbyno.htm" target="_blank" class="a">去支付宝兑换</a>\
                         <a href="'+data.data.url+'" target="_blank" class="b">购买心愿宝贝</a>\
                    </div>');
                    $(".win").find(".left").html('<s>¥</s>'+data.data.type);
                    return false;
                    break;
                  case 1:
                    that.showEnd($(".yanzheng"));
                    break;
                  case 2:
                    commonLogin();
                    break;
                  case 3:
                    that.showEnd($(".endlist"));
                    break;
                  case 4:
                    $(".start_btn").find(".nums").html('<span>X</span>'+data.data.lottery_num);
                    setTimes(k,$(".lost"));
                    $(".lost").find(".opt-html").html('<h2>OOPS~没中~</h2>\
               <p class="listp">'+(data.data.lottery_num>0?'您还有<s>'+data.data.lottery_num+'次机会</s>哦~':'您已用完今天抽奖机会!')+'</p>')
                    return false;
                    break;
                  default:
                }
                that.ajaxLoding = false;
            });
    },
    showEnd:function(o){
       var obj = o;
       obj.css({
          left:$.ui._position(obj)[0],
          top:$.ui._position(obj)[1]
       }).show();
       $.ui._showMasks();
       this.ajaxLoding = false;
    },
    parseURL:function (url){//URL解析函数
        var a =  document.createElement('a');
        a.href = url;
        return {
            source: url,
            protocol: a.protocol.replace(':',''),
            host: a.hostname,
            port: a.port,
            query: a.search,
            params: (function(){
                var ret = {},
                seg = a.search.replace(/^\?/,'').split('&'),
                len = seg.length, i = 0, s;
                for (;i<len;i++) {
                    if (!seg[i]) {
                        continue;
                    }
                    s = seg[i].split('=');
                    ret[s[0]] = s[1];
                }
                return ret;
            })(),
            file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],
            hash: a.hash.replace('#',''),
            path: a.pathname.replace(/^([^\/])/,'/$1'),
            relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],
            segments: a.pathname.replace(/^\//,'').split('/')
        };
    }
}

var shares = {
    init:function(){
       this.bindFun();
    },
    bindFun:function(){
      var arr = ["sina_weibo","qq_weibo","qzone","renren"],
          zhongjiang = $(".zhongjiang"),
          check_box = $(".check_box");
      for(var i=0; i<arr.length; i++){

         (function(i){
              $("."+arr[i]).click(function(){
                   var url,
                       title = encodeURIComponent(document.title);
                   url = "http://www.shihuo.cn/huodong/lottery";
                   $.share(arr[i],{
                      title:title,
                      url:url,
                      pic:'http://www.shihuo.cn/images/trade/lottery/bann1.jpg'
                      //pics:'http://meifusuba.hupu.com/images/shareimg.jpg'
                    });
               });
              $("."+arr[i]).hover(function(){
                  $(this).addClass("on");
              },function(){
                  $(this).removeClass("on");
              });
         })(i);
      }

      zhongjiang.click(function(){
          shares.checkBoxshow();
      });

      $(".shop_list").find("li").hover(function(){
        $(this).addClass("on");
      },function(){
         $(this).removeClass("on");
      });

    },
    checkBoxshow:function(){
        var check_box = $(".check_box"),str="";
        $.getJSON("http://www.shihuo.cn/lottery/getSelfHistory",{},function(data){
            switch(data.status*1){
               case 1:
                  commonLogin();
                  return false;
                  break;
              case 2:
                  $(".lost").find(".opt-html").html('<h2>OOPS~没中~</h2>\
               <p class="listp">'+(data.data.lottery_num>0?'您还有<s>'+data.data.lottery_num+'次机会</s>哦~':'您已用完今天抽奖机会!')+'</p>')
               check_link.showEnd($(".lost"));
                  return false;
              case 3:
                  check_link.showEnd($(".showtips"));
                  return false;
                default:
            }
           for(var i=0;i<data.data.data.length;i++){
              str += '<p class="tit">恭喜你！获得'+data.data.data[i].money+'元红包！</p>\
                    <p>卡号：'+data.data.data[i].account+'</p>\
                    <p>密码：'+data.data.data[i].pass+' <a href="https://hongbao.alipay.com/coupon/getbyno.htm" target="_blank" class="how">如何兑换？</a></p>\
                    <div class="borders"></div>';
           }
          check_box.find(".bottom").html('<a href="https://hongbao.alipay.com/coupon/getbyno.htm" target="_blank" class="a">去支付宝兑换</a><a href="'+data.data.url+'" target="_blank" class="b">去买心愿宝贝</a>');
          check_box.find(".right").html(str);
          if(check_box.find(".right").find(".borders").length > 3){
              check_box.find(".right").css({
                  height:410,
                  overflow:"auto",
                  position:"relative",
                  fontSize:16
              })
          }
          check_box.css({
             left:$.ui._position(check_box)[0],
             top:$.ui._position(check_box)[1]
          }).show();
          $.ui._showMasks();
        });
    }
}

;(function($,doc,win){
    $.ui = $.ui || {};
    $.extend($.ui, {//合并方法到$.ui方便使用
        _showMasks: function(a) {//显示遮罩 a:遮罩透明度
            var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:" + $(document).height() + "px; background-color:#000;  z-index:980;'></div>";
            $("body").append(str);
            $(".body-mask").css("opacity", 0);
            $(".body-mask").animate({
                "opacity": a ? a : "0.8"
            });
        },
        _closeMasks: function() {//关闭遮罩
            var close = $(".body-mask");
            close.fadeOut(function() {
                close.remove();
            });
        },
        _getpageSize: function() {//获取窗口大小
            var de = doc.documentElement, arrayPageSize,
                    w = win.innerWidth || self.innerWidth || (de && de.clientWidth) || doc.body.clientWidth,
                    h = win.innerHeight || self.innerHeight || (de && de.clientHeight) || doc.body.clientHeight;
            arrayPageSize = [w, h]
            return arrayPageSize;
        },
        _getpageScroll: function() {//获取滚动条距离顶部的距离
            var yScrolltop;
            if (self.pageYOffset) {
                yScrolltop = self.pageYOffset;
            } else if (doc.documentElement && doc.documentElement.scrollTop) {
                yScrolltop = doc.documentElement.scrollTop;
            } else if (doc.body) {
                yScrolltop = doc.body.scrollTop;
            }
            return yScrolltop;
        },
        _position: function(obj) {//计算对象放置在屏幕中间的值   obj:需要计算的对象
            var left = ((this._getpageSize()[0] - parseInt(obj.outerWidth())) / 2);
            var top = ((this._getpageSize()[1] - parseInt(obj.outerHeight())) / 2) + $.ui._getpageScroll();
            return [left, top];
        },
        _offset:function(obj){//计算对象距离页面顶部和左侧的位置  obj:需要计算的对象
           var left = obj.offset().left;
           var top;
           if(jQuery.fn.jquery <=1.6){
                if(!!$.browser.msie && parseInt($.browser.version) <= 6){
                    top = obj.offset().top+$.ui._getpageScroll();
                }else{
                    top = obj.offset().top;
                }
           }else{
               top = obj.offset().top;
           };
           return [left, top];
        },
        isie: !!$.browser.msie,
        isie6: (!!$.browser.msie && parseInt($.browser.version) <= 6)
    });

    function tips(a,arrs) {
        return this.each(function() {
            var $this = $(this),
                str = '<div class="tips_layer" style="position: absolute; border-radius:5px; background-color:#ff6600; display:none; z-index:995">\
                  <div class="tips-text" style="padding:5px; color:#fff;">'+a+'</div>\
                  <div class="diamond"></div>\
              </div>';
             if($(".tips_layer")){
                $(".tips_layer").remove();
             }
            $(str).appendTo("body");
            var $tips_text = $(".tips-text"),
                    $tips_layer = $(".tips_layer");

            if(arrs && arrs.length == 2){
              $tips_layer.css({
                  "top": $this.offset().top - parseInt($this.height())-5 + arrs[0],
                  "left": $this.offset().left + arrs[1]
              }).show();
            }else{
                $tips_layer.css({
                  "top": $this.offset().top - parseInt($this.height())-5,
                  "left": $this.offset().left - ($tips_layer.width()/2-$this.width()/2)
              }).show();
            }

            setTimeout(function(){
               $tips_layer.remove();
            },1500);
        })
    }

    var share = {
        defaults:{
           sina_weibo:"http://v.t.sina.com.cn/share/share.php?appkey=2175967801&",
           qq_weibo:"http://v.t.qq.com/share/share.php/?appkey=2175967801&",
           douban:"http://www.douban.com/recommend/?",
           qzone:"http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?",
           kaixin:"http://www.kaixin001.com/repaste/share.php?",
           renren:"http://widget.renren.com/dialog/share?"
        },
        init:function(){
             var that = share,arg = arguments,x;
                 that.url = that.defaults[arguments[0]?arguments[0]:"sina_weibo"];
                 this.num = 0;
             for(x in arguments[1]){
                 that.url += ((this.num==0?"":"&") + x + "=" +arguments[1][x]);
                 this.num++;
             }
             that.window_open(arguments[0]);
        },
        window_open:function(k){
           window.open(this.url, "分享到", this.getParamsOfShare([600,560]));
        },
        getParamsOfShare:function(arr){
          return ['toolbar=0,status=0,resizable=1,width=' + arr[0] + ',height=' + arr[1] + ',left=',(screen.width-arr[0])/2,',top=',(screen.height-arr[1])/2].join('');
        }
     }

     var animateTop = {
         defaults:{//默认参数
              time:3000,//弹出的图片最大宽度
              num:5//弹出的图片最大高度
          },
         init:function(){
            var that = animateTop;
                args = arguments[0];
            return this.each(function(){
               var el = this;
               el.opt = $.extend(true,{}, that.defaults, args || {});//合并自定义参数和默认参数
               el.opt["tops"] = $(el).children().eq(0).outerHeight();
               that.aniMates(el);
            });
         },
         aniMates:function(el){
            setInterval(function(){
                $(el).animate({
                    top:-el.opt.tops*el.opt.num
                },800,function(){
                    for(var i=0;i<el.opt.num;i++){
                        $(el).children().eq(0).appendTo($(el));
                    }
                   $(el).css("top",0);
                });
            },el.opt.time);
         }
      }

    $.fn.extend({
        tips:tips,
        animateTop:animateTop.init
    });
    $.extend({
        share:share.init,
        cookie:function(name, value, options){//cookie
            if (typeof value != 'undefined') {
                options = options || {};
                if (value === null) {
                    value = '';
                    options.expires = -1;
                }
                var expires = '';
                if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                    var date;
                    if (typeof options.expires == 'number') {
                        date = new Date();
                        date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                    } else {
                        date = options.expires;
                    }
                    expires = '; expires=' + date.toUTCString();
                }
                var path = options.path ? '; path=' + options.path : '';
                var domain = options.domain ? '; domain=' + options.domain : '';
                var secure = options.secure ? '; secure' : '';
                document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
            } else {
                var cookieValue = null;
                if (document.cookie && document.cookie != '') {
                    var cookies = document.cookie.split(';');
                    for (var i = 0; i < cookies.length; i++) {
                        var cookie = $.trim(cookies[i]);
                        if (cookie.substring(0, name.length + 1) == (name + '=')) {
                            cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                            break;
                        }
                    }
                }
                return cookieValue;
            }
        }
    });
})(jQuery,document,window);