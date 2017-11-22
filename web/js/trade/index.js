$(document).ready(function() {
    //搜索框统计
    $("#top_search_form").submit(function(e) {
        var keyword = $("#zbq-searchSubmit").val();
        if(!keyword || keyword == "产品名、品牌") {
            e.preventDefault();
        } else {
            commonGa("search-" + keyword);
        }
    });

    $("#top_search_all_form").submit(function(e) {
        var keyword = $("#zbq-searchSubmit").val();
        if(!keyword || keyword == "产品名") {
            e.preventDefault();
        } else {
            commonGa("search-" + keyword);
        }
    });
});


//首页焦点图
function IndexFocus(o){	
		this.box = $("#"+o["box"]);	
		this.box.css({left:0,top:0});
		this.num = $("#"+o["num"]);
		this.numLi = this.num.find("li");		
		this.l = this.numLi.length;
		var bigHtml = "";
		
		for(var i = 0;i<this.l;i++){
			this.numLi.eq(i).attr("num",i);
			var o = {};
				o.text = this.numLi.eq(i).attr("title");
				o.bsrc = this.numLi.eq(i).attr("bsrc");
				o.link = this.numLi.eq(i).attr("link");
				bigHtml += '<li><a href="'+o.link+'" target="_blank"><img src="'+o.bsrc+'" alt="'+o.text+'"></a><div class="info">'+o.text+'</div><div class="bg"></div></li>';
		};
		var that = this;	
		this.box.html(bigHtml);
		this.li = this.box.find("li");
		this.w = this.li.outerWidth(true);
		this.box.width(this.w*this.l);
		this.c = 0;
		this.move();
		this.numLi.bind("mouseover",function(){
			that.c = $(this).attr("num");
			clearInterval(that.timer);
			that.move(true);
		});
		this.autoPlay();
};
IndexFocus.prototype={
	autoPlay:function(){
		var that = this;
		this.timer = setInterval(function(){
			that.c < that.l-1?that.c++:that.c=0;
			that.move();
		},5000);
	},
	move:function(isClick){
		var that= this;
		var pos = -this.c*this.w;
		this.numLi.removeClass("on");
		this.numLi.eq(this.c).addClass("on");
		this.box.stop();
		this.box.animate({left:pos},function(){
			if(isClick){
				that.autoPlay();
			};
		});		
	}
};




//movePro
function ProMove(o){
	var that = this;
	this.ul = $("#"+o["ul"]);
	this.li =  this.ul.find("li");
	this.btnP = $("#"+o["btnP"]);
	this.btnN = $("#"+o["btnN"]);
	this.v = o["v"] || 4;
	this.w  = this.li.outerWidth(true)*this.v;
	this.maxL = Math.ceil(this.li.length/this.v);
	for(var n = 0;n<this.v;n++){
		this.ul.append(this.li.eq(n).clone(true));
	};
	this.ul.css({left:0});
	this.c = 0;
	
	this.btnP[0].onclick = function(){
		if(that.c <= 0) {		
			that.c=that.maxL;
			that.ul.css({left:-that.maxL*that.w});
		};
		that.c--;			
		that.move();
		
	};
	this.btnN[0].onclick = function(){
		if(that.c >= that.maxL) {
			that.c = 0;
			that.ul.css({left:0});				
		};
			that.c++;			
			that.move();
	};
};
ProMove.prototype = {
	move:function(){
		var that= this;
		var pos = this.c * this.w;
		this.ul.stop();
		this.ul.animate({left:-pos});
	}
};

//
var shihuoPro = {
	init:function(id){
		var that = this;
		this.li = $("#"+id).find("li");
		this.li1 = this.li.eq(0);
		this.li2 = this.li.eq(1);
		this.li3 = this.li.eq(2);
		this.li4 = this.li.eq(3);
		this.pos = [{fx:"top",p:-135},{fx:"left",p:330},{fx:"left",p:-135},{fx:"top",p:330}];		
		this.time = 4000;
		this.c = 0;	
	
		this.li1.hover(function(){
			that.pos[0]["p"] = -135;
			$(this).find(".mask").stop();				
			$(this).find(".mask").animate({top:that.pos[0]["p"]},200);
		},function(){
			$(this).find(".mask").stop();
			$(this).find(".mask").animate({top:0},200);
		});
		this.li4.hover(function(){
			that.pos[3]["p"] = 135;
			$(this).find(".mask").stop();
			$(this).find(".mask").animate({top:that.pos[3]["p"]},200);
		},function(){
			$(this).find(".mask").stop();
			$(this).find(".mask").animate({top:0},200);
		});
		this.li2.hover(function(){
			that.pos[1]["p"] = 330;
			var fx = "left";
			$(this).find(".mask").stop();
			$(this).find(".mask").animate({left:that.pos[1]["p"]},200);
		},function(){
			$(this).find(".mask").stop();
			$(this).find(".mask").animate({left:0},200);
		});
		this.li3.hover(function(){
			that.pos[2]["p"] = -330;
			$(this).find(".mask").stop();
			$(this).find(".mask").animate({left:that.pos[2]["p"]},200);
		},function(){
			$(this).find(".mask").stop();
			$(this).find(".mask").animate({left:0},200);
		});
		that.li.eq(0).find(".mask").animate({top:that.pos[0]["p"]},500);
		this.timer1 = setInterval(function(){
			that.c < 3 ? that.c++ : that.c = 0;
				that.li.each(function(i){				
					if(that.c==i){
						switch(i){
							case 0 :
								that.li.eq(0).find(".mask").animate({top:that.pos[0]["p"]},500);
							break;
							case 1 :
								that.li.eq(1).find(".mask").animate({left:that.pos[1]["p"]},500);
							break;
							case 2 :
								that.li.eq(2).find(".mask").animate({left:that.pos[2]["p"]},500);
							break;
							case 3 :
								that.li.eq(3).find(".mask").animate({top:that.pos[3]["p"]},500);
							break;						
						};						
					}else{
						switch(i){
							case 0 :
								that.li.eq(0).find(".mask").animate({top:0},500);
							break;
							case 1 :
								that.li.eq(1).find(".mask").animate({left:0},500);
							break;
							case 2 :
								that.li.eq(2).find(".mask").animate({left:0},500);
							break;
							case 3 :
								that.li.eq(3).find(".mask").animate({top:0},500);
							break;						
						};			
					};
				
			});
		},that.time);
		
	}
};












