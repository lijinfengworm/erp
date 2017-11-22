$(function(){
$.vui ={};
$.vui.alert = function(msg,o){
    if(!msg || typeof msg !== 'string'){
        return false;
    }
    var _html = "<div id='box-alert'><div class='inner' style='position:fixed;'><div class='box-con'><a class='btn close' href='javascript:void(0);'></a><div class='con clearfix'> <p class='icon'> <span></span> </p>";
    if(o&&o.btnHide){
        _html +="<p>"+msg+"</p>";
    }else{
        _html +="<p>"+msg+"</p><div class='btn-p'><a href='javascript:void(0);' class='btn fl yes'>确定</a> <a href='javascript:void(0);' class='btn fl no' >取消</a> </div>";
    }
    _html += "</div></div></div></div>";
    $(".content-bg").append(_html);
    var _alert = $("#box-alert");
    var btns = _alert.find("a.btn");
    btns.click(function(){
        var _this = $(this);
        _alert.remove();
        $(".content-bg").css({"pointer-events": "auto"});
        if (_this.hasClass("yes")) {
            if (o && o.yesFn) {
                o.yesFn();
            }
        }else {
            if (o && o.noFn) {
                o.noFn();
            }
        }
    });
}

  //我的收藏
  $("#dianpu").hide();
  $("#my-collection .tab").click(function() {
    $("#my-collection .tab>span").removeClass('checked');
    $(this).find("span").addClass('checked');
    var id = $(this).attr("data");
    if (id == "dianpu") {
      $("#dianpu").show();
      $("#shangpin").hide();
    } else {
      $("#shangpin").show();
      $("#dianpu").hide();
    }
  });
  //收藏
  $("#shangpin .p2,#dianpu .p2").click(function() {
    if (!$(this).hasClass('checked')) {
      $(this).addClass('checked');
      $(this).find("span").text("已收藏");
    } else {
      $(this).removeClass('checked');
      $(this).find("span").html("收藏");
    }
  });
  $("#shangpin li,#dianpu li").hover(function() {
    $(this).find(".delete").show();
  }, function() {
    $(this).find(".delete").hide();
  });

  //积分明细tab
  $("#score .tab").click(function() {
    $("#score .tab>span").removeClass('checked');
    $(this).find("span").addClass('checked');
    var id = $(this).attr("data-score");
    $(".special-jfmx table").hide();
    $("." + id).show();
  });
  //我的优惠券
  $("#coupon li").click(function() {
    $("#coupon li").removeClass('on');
    $(this).addClass('on');
  });
   //个人中心
  $("#info li").click(function() {
    $("#info li").removeClass('on');
    $(this).addClass('on');
  });
  //我的优惠券
  $("#coupon li").click(function() {
    $("#coupon li").removeClass('on');
    $(this).addClass('on');
    var id = $(this).data("tab");
    $(".coupon-list").hide();
    $("." + id).show();
  });
  if($(".special").hasClass('special-address')){
     $('#checkBlist').hcheckbox();
  }
  if($(".special").hasClass('special-info')){
      $('#checkBlist2 label').click(function () {
              $('#checkBlist2 label').removeClass("checked");
              $(this).addClass("checked");
              var sex =$(this).attr("data-sex");
              $("#check-sex").val(sex);
          }
      );
      $.divselect("#divselect","#inputselect");
      $.divselect("#divselect2","#inputselect2");
	  $("#divselect3 cite").click(function(){
		  var year = $("#inputselect").val();
		  var month = $("#inputselect2").val();
		  var max = (new Date(year,month, 0)).getDate();
				var ul =$("#divselect3 ul");
			    if (ul.css("display") == "none") {
					ul.slideDown("fast");
				} else {
					ul.slideUp("fast");
				}
		  var _html="";
		  for (var i=1; i <= max; i++) {
			_html +="<li><a href='javascript:hideThist();' selectid="+i+">"+i+"</a></li>";
		  }
		  $("#divselect3 ul").show().html(_html);
		  $("#divselect3").find("li>a").bind("click",function () {
				 var day = $(this).attr("selectid");
				$("#divselect3 cite").text(day);
				$("#inputselect3").val(day);
				ul.slideUp("fast");
		   });
	  });


  }




});