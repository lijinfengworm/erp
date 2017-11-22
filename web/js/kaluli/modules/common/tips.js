define(function(){
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
    function tipsFun(a,arr){
        var $this = $(this);
        return this.each(function(){
            var str = '<div class="tips-base-layer" style="position: absolute; padding:5px 7px 7px 7px; font-size:14px; color:#fff; border-radius:5px; background-color:#000; opacity:0.8; display:none; z-index:995"><img style="position: relative; top:-1px;" src="//kaluli.hoopchina.com.cn/images/kaluli/order/oder-in.png" /> '+a+'<div style="position: absolute; right:45%; top:32px;"><img src="//kaluli.hoopchina.com.cn/images/kaluli/order/oder-in2.png" /></div></div>';
            if($(".tips-base-layer")){
                $(".tips-base-layer").remove();
             }

             $(str).appendTo("body");
             $(".tips-base-layer").css({
                "top": $this.offset().top - parseInt($(".tips-base-layer").height())-20,
                "left": $this.offset().left - ($this.width()>$(".tips-base-layer").width()?($this.width()-$(".tips-base-layer").width())/2:($(".tips-base-layer").width()-$this.width())/2)
             }).show();
             setTimeout(function(){
               $(".tips-base-layer").remove();
            },2000);
        });
    }
    $.fn.tipsFun = tipsFun;
    (function(){
      $.fn.tips = tips;
      $.Jui = $.Jui || {};
      $.extend($.Jui, {
          // version: "1.0",
          _$: function(a, b) {
              a.siblings().removeClass(b);
              a.addClass(b);
          },
          _showMasks: function(a) {
              var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:" + $(document).height() + "px; background-color:#000;  z-index:101;'></div>";
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
    })(jQuery);    
})