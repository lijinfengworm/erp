
$(function(){
  if(typeof errorType != 'undefined'){
      switch(errorType*1){
        case 1:
         $("input[name='username']").tips("姓名格式不正确");
         break;
        case 2:
         $("input[name='mobile']").tips("手机格式不正确");
         break
        case 3:
          $("input[name='email']").tips("邮箱格式不正确");
          break;
        case 4:
          $("input[name='mobile']").tips("该手机号码已经参加过抽奖了");
          break;
        default:  
      }
  }
});


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
            "top": $this.offset().top - parseInt($this.height()/2)-5,
            "left": $this.offset().left + parseInt($this.width()/2) - 50
        }).show();

        setTimeout(function(){
           $tips_layer.remove();
        },2000);
    });
}

$.fn.tips = tips;