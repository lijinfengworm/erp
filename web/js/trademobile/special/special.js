$(function(){
   	loadMore.init();
    loadMore.clickEvent();   
});
var _ajaxG = true;
//下拉加载更多
var loadMore = {
    ajaxLink: "http://www.shihuo.cn/app3/",
    init:function(){
        var _html ="";
        var element1 = $('#product_list'),tpl = $('#tpl').html();
        var _dataStr = {token: hex_md5("123456")};
        $.getJSON(this.ajaxLink +"getZhuanxiangList",_dataStr,function(data) {
            if(data.status == "0"){
                var html = _.template(tpl); 
                // 将解析后的内容填充到渲染元素  
                element1.html(html(data));
                loadMore.changeTime();
            }else{
               element1.addClass("null"); 
            }
        });
    },
    changeTime:function(){
        var that = this;//页面倒计时1分钟1次
        var timeC = false;
        $('#product_list').find("li").each(function() {
            var _remain_time = parseInt($(this).find(".timestamp").attr("data-timestamp"));
            if (_remain_time > 0) {
                var project = new Timeval(_remain_time),
                  time = project.setTime();
                if (typeof time == "object") {
                    timeC = true;
                    if( _remain_time>1800){
                        $(this).find(".time_num").show();
                        $(this).find(".account_num").hide();
                        $(this).find(".time_num").html("距开抢还剩<span>"+time[0] + '</span>天<span>' + time[1] + '</span>小时<span>' + time[2] + '</span>分<span>'+time[3]+"</span>秒");
                        $(this).find(".btn").html("抢码提醒");
                    }else if(0<_remain_time<30 ){
                        $(this).find(".time_num").show();
                        $(this).find(".account_num").hide();
                        $(this).find(".time_num").html("距开抢还剩<span>"+time[0] + '</span>天<span>' + time[1] + '</span>小时<span>' + time[2] + '</span>分<span>'+time[3]+"</span>秒");
                        $(this).find(".btn").html("抢码提醒");
                        $(this).find(".btn").addClass('gray');
                    }else{
                        $(this).find(".btn").removeClass('gray');
                        $(this).find(".time_num").hide();
                        $(this).find(".account_num").show();
                        $(this).find(".btn").html("点击领券");
                    }
                    $(this).find(".timestamp").attr("data-timestamp", _remain_time - 1);
                }else {
                  $(this).find(".time_num").hide();
                }
            }else{
                 $(this).find(".btn").removeClass('gray');
                 $(this).find(".time_num").hide();
                 $(this).find(".account_num").show();
                 $(this).find(".btn").html("点击领券");
            }
        });
        if(timeC){
            setTimeout(function() {
              that.changeTime();
            }, 1000);
        }

    },
    clickEvent:function(){
        $("#product_list").on("click",".btn",function(){
           var _type  = $(this).attr("data-type"); //1抢吗提醒 2 点击领券
           var _id    = $(this).attr("data-id"); //专享ID
           var _title = $(this).attr("data-title"); 
           var _gobuy = $(this).attr("data-gobuy"); 
     
            if(_type == "1"){
                $(".regTel .tel").val("");
                $("#_valert_").addClass("show");
                $(".regTel").show();
                $("body,html").addClass('noScroll');
                $(".regTel .error").removeClass('show');
                loadMore._id  = _id;
            }else{
                if(_ajaxG){
                    _ajaxG = false;
                    var _dataStr = {id:_id,token:hex_md5(_id+"123456")};
                  $.ajax({type:"POST",url:loadMore.ajaxLink +"receiveZhuanxiang",dataType:'json',data:_dataStr,xhrFields: {withCredentials: true },crossDomain: true,success:function(data) {
                        _ajaxG = true;
                        var data = JSON.parse(data); 
                        if(data.status == "0"){
                           loadMore.yhm = data.data.account;
                           $(".copyYhm .title").html(_title);
                           $(".copyYhm .yhm span").html(loadMore.yhm);
                           $(".copyYhm .btnBuy").attr("href",_gobuy);
                           $("#_valert_").addClass("show");
                           $(".copyYhm").show();
                           $("body,html").addClass('noScroll');
                        }else if(data.status == 1){
                             $("#_valert_").addClass("show");
                             $(".login").show();
                             $("body,html").addClass('noScroll');
                              return false;
                        }else{
                            $.vui.remind("<span class='icon_w'></span>"+data.msg);
                        }
                        
                  }});
                }
                
            }
        });
       $(".login .btn").on("click",function(){
              location.href = $.ui.loginUrl();
       });
        // 抢码提醒
        $(".regTel .btn").on("click",function(){
          var _tel = $(".regTel .tel").val();
            if(_tel && $.vui.isPhone(_tel)){
              $(".regTel .error").removeClass('show');

              if(_ajaxG){
                _ajaxG = false;
                var _dataStr = {id:loadMore._id,mobile:_tel,token:hex_md5(loadMore._id+_tel+"123456")};
                $.ajax({type:"POST",url:loadMore.ajaxLink +"zhuanxiangRemind",data:_dataStr,success:function(data) {
                    var data = JSON.parse(data);
                    if(data.status == "0"){
                         //$(".item"+loadMore._id).find(".btn").addClass("gray");
                         $("#_valert_").removeClass("show");
                         $(".regTel").hide();
                         $("body,html").removeClass('noScroll');
                        setTimeout(function(){
                           $.vui.remind("<span class='icon_r'></span>"+data.msg);
                        }, 2000);
                    }else{
                       $.vui.remind("<span class='icon_w'></span>"+data.msg);
                    }
                     _ajaxG = true;
                  }});
              }
            }else{
              $(".regTel .error").addClass('show');
              return false;
            }
        });
        $('.btnCopy').on('tap', function() {

             var appVersion =0,s="";var userAgent = navigator.userAgent;
             s = userAgent.match(/shihuo\/([\d.]+)/);
             
             if(s){
               appVersion = s[1];
               if(appVersion<"2.2.0"){
                 $.vui.remind("复制失败，请手动复制！");
               }else{
                Jockey.send("copy", {
                 content: loadMore.yhm
                });
                $.vui.remind("复制成功！");
               }
             }else{
                $.vui.remind("复制失败，请手动复制！");
             }
              
            // $("#_valert_").removeClass("show");
            // $(".copyYhm").hide();
            // $("body,html").removeClass('noScroll');
        });
        $('#_valert_ .close').on('click', function() {
            $("#_valert_").removeClass("show");
            $("#_valert_ .box .login,#_valert_ .box .regTel,#_valert_ .box .copyYhm").hide();
            $("body,html").removeClass('noScroll');
        });
    }
};

function Timeval(o) {
  this.times = o;
  this.setTime = function() {
    return this.SetRemainTime();
  }
}
Timeval.prototype = {
  SetRemainTime: function() { //倒计时
    var that = this,
      timeArr,
      SysSecond = parseInt(this.times);
    if (SysSecond > 0) {
      SysSecond = SysSecond - 1;
      var second = Math.floor(SysSecond % 60);
      var minite = Math.floor((SysSecond / 60) % 60);
      var hour = Math.floor((SysSecond / 3600) % 24);
      var day = Math.floor((SysSecond / 3600) / 24);
      timeArr = [day, hour, minite, second];
    } else {
      timeArr = false;
    }
    return timeArr
  }
}
