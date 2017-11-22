$(function(){

   $("#submit-btn").click(function(){
   	  setTimeout(function(){
         $.Jui._showMasks(0.5);
          $(".confirm-box").css({
             left:$.Jui._position($(".confirm-box"))[0],
             top:$.Jui._position($(".confirm-box"))[1]
          }).show();
   	  },500);
   });

   $(".confirm-box").find('.close').click(function(){
       $(".confirm-box").hide();
       $.Jui._closeMasks();
   });

   $(".confirm-box").find(".btn1").click(function(){
     var $this = $(this);
     $.post("http://www.shihuo.cn/haitao/orderPayResultCheck",{order_number:orderNumber},function(data){
         if(data.status*1 == 0){
         	 window.location.href = data.data.url;
         }else{
            $this.tips(data.msg);
         }
     },"json");
   }); 

   $("#canle-pay").click(function(){
       $.Jui._showMasks(0.5);
        $(".confirm-box2").css({
           left:$.Jui._position($(".confirm-box2"))[0],
           top:$.Jui._position($(".confirm-box2"))[1]
        }).show();
   });

   $(".confirm-box2 .close,.confirm-box2 .btn2").click(function(){
       $(".confirm-box2").hide();
       $.Jui._closeMasks();
   });

});

!(function($){
  function tips(a) {
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
          $tips_layer.css({
              "top": $this.offset().top - parseInt($this.height())-5,
              "left": $this.offset().left + parseInt($this.width()/2) - 50
          }).show();

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