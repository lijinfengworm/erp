$(function(){
    goodsList.init();
    rightLayer.init();
});

var goodsList = {
	 ajaxLoding:false,
	 list:0,
	 init:function(){
	 	this.obj = $("#Js_goods");
	 	this.bindFun();
	 },
	 bindFun:function(){
	 	var that = this;
	 	that.obj.delegate(".imgs","mouseover",function(){
	 		if($(this).next().find("span").html() != ""){
	 			$(this).next().show();
	 		}
	 	});

	 	that.obj.delegate(".imgs","mouseout",function(){
	 	   $(".show-tips,.show-tips-bgs").hide();
	 	});

	 	that.obj.delegate(".show-tips","mouseover",function(){
             $(this).show();
             $(this).parent("li").find(".show-tips-bgs").show();
	 	});

	 	that.obj.delegate(".show-tips","mouseout",function(){
             $(this).hide();
             $(".show-tips-bgs").hide();
	 	});

	 	$(window).scroll(function(event) {
	 		var totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop()) +100;
	 		if ($(document).height() <= totalheight) {
	 		   if(that.ajaxLoding || that.list > items.length-1){
	 		   	   return false;
	 		   }
	 		   that.ajaxLoding = true;
	 		   $(that.writeDom()).appendTo('#Js_goods');
	 		}
	 	});
	 },
	 writeDom:function(){
	 	var str = [];
	 	for(var i=0;i<12;i++){
	 		str.push('<li>\
	 			'+(items[this.list].is_hot == 1?'<div class="hots"></div>':'')+'\
	 			<div class="show-tips-bgs"></div>\
				<h2><a href="'+items[this.list].detail_url+'" target="_blank">'+items[this.list].title+'</a></h2>\
				<div class="imgs">\
					<img width="224" src="http://shihuo.hupucdn.com'+items[this.list].img_url+'-S253A.jpg" />\
				</div>\
				<div class="show-tips">\
					<s></s><span>'+(items[this.list].hupu_username != ""?items[this.list].hupu_username+"：":"")+items[this.list].memo+(items[this.list].edit_url != ""?'&nbsp;&nbsp;&nbsp;&nbsp;<a href="'+items[this.list].edit_url+'" target="_blank">编辑</a>':'')+'</span>\
				</div>\
				<div class="prices">￥'+items[this.list].price+'</div>\
				<div class="link-buy">\
					<a href="'+items[this.list].url+'" target="_blank">去购买 ></a>\
				</div>\
			</li>');
			this.list += 1;
			if(this.list > items.length-1){
				break;
			}
	 	}
	 	this.ajaxLoding = false;
	 	return str;
	 }
}

var rightLayer = {
   init:function(){
   	   this.offTop = $(".groupon-i-content").offset().top;
   	   this.bindFun();
   },
   bindFun:function(){
   	   var that = this;
   	   $(window).scroll(function(event) {
   	   	    if($(this).scrollTop() >= that.offTop){
   	   	    	$(".groupon-i-content").css("position","static");
   	   	    	$(".post-layer-right").addClass('post-layer-rightA');
   	   	    }else{
   	   	    	$(".groupon-i-content").css("position","relative");
   	   	    	$(".post-layer-right").removeClass('post-layer-rightA');
   	   	    }
   	   });

   	   $(".click-top").click(function(){
             $("html,body").animate({
                 scrollTop:0
             });
   	   });
   }
}