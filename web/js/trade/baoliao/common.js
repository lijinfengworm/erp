$(function(){
    formCheck.init();
});

var formCheck = {
	getJson:false,
  imgSrc:"",
	init:function(){
		this.bindFun();
	},
	bindFun:function(){
		$(".area-main input,.area-main textarea").focus(function(){
            if($(this).val() == $(this).attr("data-val")){
            	$(this).val("");
            }
		});

		$(".area-main input,.area-main textarea").blur(function(){
            if($(this).val() == $(this).attr("data-val") || $.trim($(this).val()) == ""){
            	$(this).val($(this).attr("data-val"));
            }
		});

		$(".get-mesg").click(function(){
            var val = $(this).prev().children().val(),
                valData = $(this).prev().children().attr("data-val"),
                that = this,
                $this = $(this);
            if(val == valData){
            	$(".get-message-tips").html("请输入商品链接").css("visibility","visible");
            }else{
            	 if(that.getJson){
	             	return false;
	             }
	            that.getJson = true;
                $this.addClass('on').html("正在获取...");
            	$.post("http://www.shihuo.cn/crawler",{url:val},function(data){
                     $('#thumbnails .imgbox').remove();
                      if(data.status*1 == 1){
                      	  $(".get-message-tips").html(data.msg).css("visibility","visible");
                          $(".Js_i1").val("");
                          $(".Js_i2").val("");
                          $(".Js_i22").val("");
                      }
                      if(data.status*1 == 0){
                      	  $(".get-message-tips").html(data.msg).css("visibility","visible");;
                      	  $(".Js_i1").val(data.data.title);
                      	  $(".Js_i2").val(data.data.shihuo_price);
                          $(".Js_i22").val(data.data.price);
                          $.each(data.data.pic, function(n, value) {
                              if(value!=''){
                                  loadedImage(value);
                              }
                          });
                      }
                       that.getJson = false;
                       $this.removeClass('on').html("获取信息");
            	},"json");
            }
		});


        $(".sub-bl").click(function(){
              var url = $("input[name='commodity[url]']"),
                  title = $("input[name='commodity[title]']"),
                  shihuoPrice = $("input[name='commodity[shihuo_price]']"),
                  price = $("input[name='commodity[price]']"),
                  type = $("select[name='commodity[type]']").find("option:selected"),
                  reason = $("textarea[name='commodity[reason]']"),
                  commodity_goods_id = $("input[name='commodity[commodity_goods_id]']"),
                  commodity_goods_name = $("input[name='commodity[commodity_goods_name]']"),
                  commodity_desc = $("input[name='commodity[commodity_desc]']"),
                  pic = [];
                  $("input[name='pictures']").each(function(){
                      pic.push($(this).val());
                  });
              if($.trim(url.val()) == "" || $.trim(url.val()) == url.attr("data-val")){
              	  $(this).tips("请填写商品链接");
              	  return false;
              }
              if($.trim(title.val()) == "" || $.trim(title.val()) == title.attr("data-val")){
              	  $(this).tips("请填写商品标题");
              	  return false;
              }
              if($.trim(shihuoPrice.val()) == "" || $.trim(shihuoPrice.val()) == shihuoPrice.attr("data-val")){
              	  $(this).tips("请填写商品价格");
              	  return false;
              }
              if($.trim(type.val()) == ""){
                  $(this).tips("请填写商品分类");
                  return false;
              }
              if($.trim(reason.val()) == "" || $.trim(reason.val()) == reason.attr("data-val")){
              	  $(this).tips("请填写推荐理由");
              	  return false;
              }
              if($.trim(reason.val()).length < 20 || $.trim(reason.val()).length > 150){
                  $(this).tips("推荐理由不得少于20个字，最多150个字");
                  return false;
              }
              if(commodity_goods_id.val()
                  && ($.trim(commodity_desc.val()) == "" || $.trim(commodity_desc.val()) == commodity_desc.attr("data-val"))
              ){
                $(this).tips("请填写一句话描述");
                 return false;
              }

               $.post("http://www.shihuo.cn/submit",{
                   "commodity[url]":url.val(),
                   "commodity[title]":title.val(),
                   "commodity[shihuo_price]":shihuoPrice.val(),
                   "commodity[price]":price.val(),
                   "commodity[type]":type.val(),
                   "commodity[reason]":reason.val(),
                   "commodity[commodity_goods_id]":commodity_goods_id.val(),
                   "commodity[commodity_goods_name]":commodity_goods_name.val(),
                   "commodity[commodity_desc]":commodity_desc.val(),
                   "commodity[pic]":pic
               },function(data){
                    if(data.status * 1 == -1){
                        $(".sub-bl").tips(data.msg);
                    }else{
                        $.Jui._showMasks(0.5);
                        $('<div class="show-tips-success">'+data.msg+'</div>').appendTo('body');
                        $(".show-tips-success").css({
                            left:$.Jui._position($(".show-tips-success"))[0],
                            top:$.Jui._position($(".show-tips-success"))[1]
                        });
                        setTimeout(function(){
                           window.location.href = "http://www.shihuo.cn/find";
                        },2500);
                    }
               },"json");
              return false;
        });
	}
}


!(function($){
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
              "left": $this.offset().left + parseInt($this.width()/2) + ($tips_layer.width()/2)
            }).show();
          }
           setTimeout(function(){
             $tips_layer.remove();
          },2000);
      });
  }
  $.fn.tips = tips;
})(jQuery);