$(function(){
   $(".ul-list").find("li").hover(function(){
      $(this).addClass('on');
   },function(){
   	   $(this).removeClass('on');
   });

   times.init();
});


var times = {
    init:function(){
       var obj = $("#time_out").find(".time .t1"),
           that = this;
           for(var i=0; i<obj.length; i++){
	           that.SetRemainTime(obj.eq(i),obj.eq(i).attr("atr"));
	       }

       var obj2 = $("#time_out_two").find(".time .t1");
       for(var i=0; i<obj2.length; i++){
            that.SetRemainTime(obj2.eq(i),obj2.eq(i).attr("atr"));
        }


    },
    SetRemainTime:function (obj,time){//倒计时
        var that = obj;
        var SysSecond = parseInt(time);
        var t = setInterval(function(){//计算秒 分 时 天
            if (SysSecond > 0) {
                SysSecond = SysSecond - 1;
                var second = Math.floor(SysSecond % 60);
                var minite = Math.floor((SysSecond / 60) % 60);
                var hour = Math.floor((SysSecond / 3600) % 24);
                var day = Math.floor((SysSecond / 3600) / 24);
                that.first().html("剩余"+day+"天"+hour + "小时" + minite + "分");
            } else {
                that.first().html("已到期");
                clearInterval(t);
            }
        },1000)
    }
}