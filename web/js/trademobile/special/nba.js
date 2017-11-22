var shareTitle = "";
var sharePic   = "";
var shareLink  = "";
var _type      = 0;
$(function(){
  if($.vui.isWeixin){
     $("#yongshi").on("click",function(){
        _type = 1;
        __dace.sendEvent("shihuo_support_cavs");
        shareWx();
    });
    $("#qishi").on("click",function(){
        _type = 2;
        __dace.sendEvent("shihuo_support_gsw");
        shareWx();
    });
  }else if(browser.is_app){
    // $.vui.alert(browser.version_number);
    // if( parseInt(browser.version_number) >= parseInt(220)){
    //   $("#yongshi").on("click",function(){
    //     _type = 1;
    //     __dace.sendEvent("shihuo_support_cavs");
    //     var data = {
    //       url: document.URL,
    //       title:"",
    //       img: "http://m.shihuo.cn/images/trademobile/special/ys.jpg",
    //       content: "我就是勇士球迷！你们快来帮我为勇士攒人气！还有PS4抢抢抢！！！"
    //     };
    //     shareApp(data);
    //   });  
    //   $("#qishi").on("click",function(){
    //        _type = 2;
    //        __dace.sendEvent("shihuo_support_gsw");
    //       var data = {
    //         url: document.URL,
    //         title:"",
    //         img: "http://m.shihuo.cn/images/trademobile/special/qs.jpg",
    //         content: "我就是骑士球迷！你们快来帮我为骑士攒人气！还有PS4抢抢抢！！！"  
    //       };
    //       shareApp(data);
    //   });
    // }else{
        $("#yongshi").on("click",function(){
            _type = 1;
            shareTitle = "我就是勇士球迷！你们快来帮我为勇士攒人气！还有PS4抢抢抢！！！";
            sharePic   =  encodeURIComponent("http://m.shihuo.cn/images/trademobile/special/ys.jpg");
            __dace.sendEvent("shihuo_support_cavs");
            share();
        });
        $("#qishi").on("click",function(){
            _type = 2;
            shareTitle = "我就是骑士球迷！你们快来帮我为骑士攒人气！还有PS4抢抢抢！！！";
            sharePic   =  encodeURIComponent("http://m.shihuo.cn/images/trademobile/special/qs.jpg");
            __dace.sendEvent("shihuo_support_gsw");
            share();
        });
   // }
  }else{
    $("#yongshi").on("click",function(){
        _type = 1;
        shareTitle = "我就是勇士球迷！你们快来帮我为勇士攒人气！还有PS4抢抢抢！！！";
        sharePic   =  encodeURIComponent("http://m.shihuo.cn/images/trademobile/special/ys.jpg");
        __dace.sendEvent("shihuo_support_cavs");
        share();
    });
    $("#qishi").on("click",function(){
        _type = 2;
        shareTitle = "我就是骑士球迷！你们快来帮我为骑士攒人气！还有PS4抢抢抢！！！";
        sharePic   =  encodeURIComponent("http://m.shihuo.cn/images/trademobile/special/qs.jpg");
        __dace.sendEvent("shihuo_support_gsw");
        share();
    });
  }
  $(".qishi>.shihuo").on("click",function(){
      __dace.sendEvent("shihuo_download");
  });
  $("#js_share_hide,.js_share_hide").on("click",function(){
      $(".js_share_box,.js_share_hide").removeClass('show');
      $(".shareWeixin").hide();
  });
  $(".shareWeixin img").on("click",function(){
    $(".shareWeixin").hide();
  });
  $(".share_tsina").on("click",function(){
    if(kanqiu && kanqiu == "kanqiu"){
        $(".shareWeixin").show();
        $(".js_share_box,.js_share_hide").removeClass('show');
    }else{
      var _url  = encodeURIComponent(document.URL);
      var _link = "http://service.weibo.com/share/share.php?status=1&pic=" + sharePic + "&url=" + _url + "&title="+encodeURIComponent(shareTitle)+"&appkey=3445570739";
      $(".js_share_box,.js_share_hide").removeClass('show');
      window.open(_link,"_blank");
      return false;
    }

  });
  $(".share_tqq").on("click",function(){
     if(kanqiu && kanqiu == "kanqiu"){
        $(".shareWeixin").show();
        $(".js_share_box,.js_share_hide").removeClass('show');
      }else{
        var _content = " ";
        var _url  = encodeURIComponent(document.URL);
        var _link = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title='+shareTitle+'&summary='+encodeURIComponent(_content)+'&url=' + _url +'&pics='+sharePic;  
        $(".js_share_box,.js_share_hide").removeClass('show');
        window.open(_link,"_blank");
        return false;
      }
  });
  $(".share_trr").on("click",function(){
      var _url    = encodeURIComponent(window.location.href);
      var _srcUrl = window.location.href;
      var _link   = "http://widget.renren.com/dialog/share?resourceUrl=" + _url + "&srcUrl=" + _srcUrl + "&title=" + shareTitle; 
      $(".js_share_box,.js_share_hide").removeClass('show');
      window.open(_link,"_blank");
      return false;
  });
  function share(){
     $.ajax({
        type: 'POST',
        url: "http://m.shihuo.cn/activity/support",
        data: {
            type:_type,
        },
        dataType: 'json',
        success: function(data) {
            if(data.status == 0){
              $(".js_share_box,.js_share_hide").addClass('show');
              if(_type == 1){
                $("#qishi").addClass("gray");
              }else{
                $("#yongshi").addClass("gray");
              }
            }else if(data.status == 1 ){
               $.vui.remind("请先登录");
               var url = window.location.href;
               setTimeout(function(){
                   location.href = $.ui.loginUrl();
               }, 2000)
            }else{
              $.vui.remind(data.msg);
            }
        },
        error: function() {
            $.vui.remind("分享失败");
        }
    });
  }
  // 微信分享回调
   function shareWx(){
     $.ajax({
        type: 'POST',
        url: "http://m.shihuo.cn/activity/support",
        data: {
            type:_type,
        },
        dataType: 'json',
        success: function(data) {
            if(data.status == 0){
              getSupNum();
            }else if(data.status == 1 ){
               $.vui.remind("请先登录");
               var url = window.location.href;
               setTimeout(function(){
                    location.href = $.ui.loginUrl();
               }, 2000)
            }else{
              $.vui.remind(data.msg);
            }
        },
        error: function() {
            $.vui.remind("分享失败");
        }
    });
  }
  // APP分享
   function shareApp(shareData){
     $.ajax({
        type: 'POST',
        url: "http://m.shihuo.cn/activity/support",
        data: {
            type:_type,
        },
        dataType: 'json',
        success: function(data) {
            if(data.status == 0){
              $.vui.remind("为主队攒人气 赢海淘现金礼包 100%有奖");
              Jockey.send('share',shareData);
            }else if(data.status == 1 ){
               $.vui.remind("请先登录");
               var url = window.location.href;
               setTimeout(function(){
                    location.href = $.ui.loginUrl();
               }, 2000)
            }else{
              $.vui.remind(data.msg);
            }
        },
        error: function() {
            $.vui.remind("分享失败");
        }
    });
  }
  getSupNum();
  function getSupNum (){
    var ajaxLink = "http://m.shihuo.cn/activity/getSupportNum";
    $.post(ajaxLink,function(data) {
      console.log(data.data.hero_num);
      if(data.status == 0){
        var yongshi = parseInt(data.data.hero_num);
        var qishi   = parseInt(data.data.knight_num);
        $("#ys_num").text(yongshi);
        $("#qs_num").text(qishi);
        var num = qishi*100/(yongshi+qishi);
        $(".line .qishi").css("width",num+"%");
      }
    },"json");
    setTimeout(function(){
      getSupNum();
    },10000);
  }
  //微信分享
  if($.vui.isWeixin) {
      shareTitle = "NBA总决赛 我为主队攒人气！更有百万奖品等你拿！！！";
      sharePic   = "http://m.shihuo.cn/images/trademobile/special/top.jpg";
      _url    = window.location.href;
      wx.ready(function () {
          wx.onMenuShareTimeline({
              title: shareTitle, // 分享标题
              link: _url, // 分享链接
              imgUrl:sharePic, // 分享图标
              success: function () {
               // shareWx();
              },
              cancel: function () {
                  console.log("error");
              }
          });
          wx.onMenuShareAppMessage({
              title: shareTitle, // 分享标题
              desc: '', // 分享描述
              link: _url, // 分享链接
              imgUrl: sharePic, // 分享图标
              success: function () {
               // shareWx();
              },
              cancel: function () {
                  console.log("error");
              }
          });
          //获取“分享到QQ”
          wx.onMenuShareQQ({
              title: shareTitle, // 分享标题
              desc: "", // 分享描述
              link: _url, // 分享链接
              imgUrl: sharePic, // 分享图标
              success: function () { 
                 // 用户确认分享后执行的回调函数
                // shareWx();
              },
              cancel: function () { 
                 // 用户取消分享后执行的回调函数
              }
          });
          //获取“分享到腾讯微博”
          wx.onMenuShareWeibo({
              title: shareTitle, // 分享标题
              desc: "", // 分享描述
              link: _url, // 分享链接
              imgUrl: sharePic, // 分享图标
              success: function () { 
                 // 用户确认分享后执行的回调函数
                 // shareWx();
              },
              cancel: function () { 
                  // 用户取消分享后执行的回调函数
              }
          });
    });
  }
});