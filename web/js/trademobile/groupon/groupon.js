var n           = 0;
var _type       = "new";
var _ajaxG      = true;
var _pageSize   = 30; //每页个数
var _key = 0;

$(function() {
  $("#tabBox1-bd .con ul").hide();
  $("#list_"+_type).show();
  if(localStorage.grouponList_type){
    _type = localStorage.grouponList_type;
    $(".top_menu li").removeClass("on");
    $("#tabBox1 li a[data-type='"+localStorage.grouponList_type+"']").parent().addClass("on");
    $("#tabBox1-bd .con ul").hide();
    $("#list_"+_type).show();
    if(localStorage.grouponList_all){
      var time = new Date().getTime()-localStorage.lastest_groupon_time;
      if(time<10000*60){
         _type = localStorage.grouponList_type;
         $("#tabBox1 li a[data-type='"+localStorage.grouponList_type+"']").parent().addClass("on");
         $("#list_"+_type).html(localStorage.grouponList_all);
      }
    }
  }else{
    localStorage.grouponList_type = "new";
  }
  $(".top_menu li").on("click",function(){
    $(".top_menu li").removeClass("on");
    $(this).addClass("on");
     _type = $(this).find("a").attr("data-type");
     $("#tabBox1-bd .con ul").hide();
     $("#list_"+_type).show();
     $(window).scrollTop(0);
      localStorage.grouponList_all  = "";
      localStorage.grouponList_type = _type;
      _ajaxG = true;
  });

  loadMore.ajaxData();
  loadMore.changeTime();
});

var loadMore = {
  ajaxLink: "http://m.shihuo.cn/tuangou_newAjax",
  ajaxData: function() {
    var that = this;//页面倒计时1分钟1次
    $(window).scroll(function() {
      event.preventDefault();
      var _key = $("#list_"+_type+" li:last-child").attr("data-key");
     // console.log($(window).scrollTop());
      if ($(window).scrollTop()+1000>=$(document).height()-$(window).height() && _ajaxG && _key) {
        $("#loadding").show();
        _ajaxG = false;
        
        $.post("http://m.shihuo.cn/tuangou_newAjax", {
          "key": _key,
          "type": _type,
          "pagesize":_pageSize
        }, function(data) {
          __dace.sendEvent('shihuo_m_dace_tuangou_page_' + _key);
          $("#loadding").hide();
          if ($.trim(data) && data.length>10) {
            _ajaxG = true;
            $("#list_" + _type).append(data);
            localStorage.grouponList_all= $("#list_" + _type).html();
            localStorage.lastest_groupon_time = new Date().getTime();
            $('#list_'+ _type).find("li").each(function() {
               that.setPagenum(this);
            });
          }
        });
      }
    });
  },
  changeTime: function() {
    var that = this;//页面倒计时1分钟1次
    setTimeout(function() {
      that.changeTime();
    }, 60000);
    $('#list_new').find("li").each(function() {
       that.setPagenum(this);
    });
    $('#list_hot').find("li").each(function() {
       that.setPagenum(this);
    });
    $('#list_end').find("li").each(function() {
       that.setPagenum(this);
    });
    $('#list_last').find("li").each(function() {
       that.setPagenum(this);
    });
  },
  setPagenum: function(dom){
    var _remain_time = parseInt($(dom).find(".time_num").attr("data-time"));
      if (_remain_time > 0) {
        var project = new Timeval(_remain_time),
          time = project.setTime();
        if (typeof time == "object") {
          $(dom).find(".time_num").html(time[0] + '天' + time[1] + '小时' + time[2] + '分');
          $(dom).find(".time_num").attr("data-time", _remain_time - 60);
        } else {
          $(dom).find(".time_num").html("00分");
        }
      }
  }
}
function Timeval(o) {
  this.times = o;
  this.setTime = function() {
    return this.SetRemainTime();
  }
}
Timeval.prototype = {
  constructor: Timeval,
  SetRemainTime: function(obj) { //倒计时
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
