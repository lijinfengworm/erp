!(function($){
   var tips = {
   	   init:function(){
   	   	  var that = tips;
   	   	  that.arg = arguments || {};
   	   	  that.getHtml();
   	   	  that.bindFun();
   	   },
   	   getHtml:function(){
   	   	   var that = this,
   	   	       str ='<div class="tips-error-box">\
               <style type="text/css">\
	                .tips-error-box{width:300px; border:1px #c6d1e3 solid; font-size:14px; background-color:#fff; position: absolute; left:0; top:0; z-index:999;}\
	                .tips-error-box .tips-title{text-align:center; background-color:#e0e0e0; padding:5px 0;font-weight:bold; position: relative;}\
	                .tips-error-box .tips-title .close{position: absolute; right:5px; top:5px; font-family: "Arial"; cursor: pointer;}\
	                .tips-error-box .content{padding:10px; text-align:center; line-height:20px;}\
	   	   	   </style>\
               <div class="tips-title">'+that.arg[0].title+'<div class="close">X</div></div>\
               <div class="content">'+that.arg[0].txt+'</div>\
            </div>';
            $(str).appendTo('body');
            $.uis._showMasks(0.6);
            $(".tips-error-box").css({
                left:$.uis._position($(".tips-error-box"))[0],
                top:$.uis._position($(".tips-error-box"))[1]
            });
   	   },
   	   bindFun:function(){
   	   	   var obj = $(".tips-error-box");
   	   	   obj.find(".close").click(function(){
                obj.remove();
                $.uis._closeMasks(0.6);
   	   	   });
   	   },
   	   tipsClose:function(){
            $(".tips-error-box").find(".close").click();
   	   }
   }
   
   var toolTips = {
       defaults:{
          width:"auto",
          live:"mouseover",
          time:1500
       },
       init:function(){
          var that = toolTips;
          that.arg = $.extend(true,{}, that.defaults, arguments[0] || {});
          that.obj = this;
          that.obj.on = true;
          that.bindFun();
       },
       bindFun:function(obj){
             var that = this,
                 obj = that.obj;
             obj.live(that.arg.live,function(){
                  if(!that.obj.on){
                    return false;
                  }
                  that.obj.on = false;
                  that.writeDom(obj.attr("tips"));
                  that.t = setTimeout(function(){
                       $(".tips_layer:last").remove();
                       that.obj.on = true;
                  },that.arg.time);
             });
       },
       writeDom:function(tips){
           var that = this,
               str = '<div class="tips_layer" style="position: absolute; width:'+that.arg.width+'; left:'+that.obj.offset().left+'px; top:'+(that.obj.offset().top - that.obj.height())+'px; border-radius:5px; background-color:#ff6600; display:none; z-index:995">\
                <div class="tips-text" style="padding:2px 5px; line-height:18px; color:#fff;">'+tips+'</div>\
                <div class="diamond"></div>\
            </div>';
            $(str).appendTo('body');
            $(".tips_layer:last").fadeIn();
       }
   }

   var tipped = {
      defaults:{
          width:"auto",
          txt:"HI",
          time:3000
      },
      init:function(){
          var that = tipped;
          that.arg = $.extend(true,{}, that.defaults, arguments[0] || {});
          that.on = true;
          that.writeDom();
      },
      writeDom:function(){
          var that = this,
              str = '<div class="tipped_layer" style="position: absolute; text-align:center; font-size:14px; background-color:#8ab0d7; color:#fff; padding:5px; width:'+that.arg.width+';  border-radius:5px;">'+that.arg.txt+'</div>';
          $(str).appendTo('body');
          $(".tipped_layer").css({
                left:$.uis._position($(".tipped_layer"))[0],
                top:$.uis._position($(".tipped_layer"))[1]+30,
                opacity:0
          });

          $(".tipped_layer").animate({
             top:$.uis._position($(".tipped_layer"))[1],
             opacity:1
          });
      }
   }

    $.fn.extend({
         toolTips:toolTips.init
    });

   $.extend({
       Tips:tips.init,
       TipsClose:tips.tipsClose,
       tipped:tipped.init
   });

    $.uis = $.uis || {};
    $.extend($.uis, {
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
            var de = document.documentElement, arrayPageSize,
                    w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth,
                    h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
            arrayPageSize = [w, h]
            return arrayPageSize;
        },
        _position: function(obj) {
            var left = ((this._getpageSize()[0] - parseInt(obj.outerWidth())) / 2);
            var top = ((this._getpageSize()[1] - parseInt(obj.outerHeight())) / 2) + $.uis._getpageScroll();
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
        }
    });
})(jQuery);