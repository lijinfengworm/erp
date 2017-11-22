//focus 
var focusFn = {
	init:function(o,num){
		this.box = $("#"+o);
		this.li = this.box.find("li");
		this.l = this.li.length;
		this.w = this.li.outerWidth(true);
		this.c = 0;
		var numHtml = "";
		for(var i =0;i<this.l;i++){
			numHtml+="<a href='javascript:void(0);' i="+i+"></a>";
		};		
		this.box.width(this.w*this.l);
		this.num = $("#"+num);
		this.num.html(numHtml);
		this.numLi = this.num.find("a");
		var that= this;
		
		this.numLi.bind("click",function(){
			that.c = $(this).attr("i");
			that.move(1);
		});
		this.move();
		this.auto();
	},
	auto:function(){
		var that= this;
		clearInterval(this.timer);
		this.timer = setInterval(function(){
			that.c<that.l-1?that.c++:that.c = 0;
			that.move();
		},4000);
	},
	move:function(test){
		var that= this;
		this.box.stop();
		var pos = this.c*this.w;
		this.numLi.removeClass("on");
		this.numLi.eq(this.c).addClass("on");
		this.box.animate({left:-pos},function(){
			if(test){
				that.auto();
			};
		});
	}
		
};

//获取窗口高宽
function getW() {
  var client_h, client_w, scrollTop;
  client_h = document.documentElement.clientHeight || document.body.clientHeight;
  client_w = document.documentElement.clientWidth || document.body.clientWidth;
  screen_h = document.documentElement.scrollHeight || document.body.scrollHeight;
  scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
  return o = {
    w: client_w,
    h: client_h,
    s: scrollTop,
    s_h: screen_h
  };
}


//抽奖
var cjFn = {
	init:function(){
		var that= this;		
		this.jf = [0,-147,-294,-441,-587,-733];		
		this.box = $("#kll_img");
		this.btn = $("#choujia");
		this.openBox = $("#ddBox");
		this.jpText = $("#jpText");
		this.timer  = null;
		this.isOk = false;
        this.cjtype = 1;
		this.btn.bind("click",function(){
                        if(!user_id)
                        {
                            commonLogin(); return false;
                        }
			that.start();
                        $.post(award_url,{match_id:match_id,type:cjFn.cjtype},function(data){
                        if(data.code < 0) 
                            {
                                tipBox(data.msg)
                                that.isOk = true;
                            }else{
                                if(data.last_change == 3){
                                    cjFn.complete((data.award_id),data.msg,3);
                                }else{
                                    cjFn.complete((data.award_id),data.msg,1);
                                }
                            }
                    },"json");
		});
        if(can_award_frist){
            this.show(1);
        };
	},
	show:function(type){
		var win = getW();			 
		this.openBox.css({top:win.s + (win.h / 2) - this.openBox.outerHeight(true) * 0.5});
		this.openBox.show();
		this.btn.show();
		this.jpText.hide();
		this.timer2 = 0;
		this.isOk = false;
        this.cjtype = type;
		that.box.css({"background-position":'0px 0px'});	
		$("#btnVancl").hide();
	},
	start:function(){
		var that=  this;		
		this.pos = 0;		
		this.btn.hide();
		this.timer = setInterval(function(){
		
			if(that.isOk){				
				clearInterval(that.timer);				
			}else{				
				
				that.pos<5?that.pos++:that.pos=1;
				that.box.css({"background-position":'0 '+that.jf[that.pos]+'px'});	
			};					
		},200);		
	},
	complete:function(c,t,b){
		var that = this;				
		
		if(b==3){
			$("#btnVancl").show();
		};
		that.timer2 = setTimeout(function(){			
			that.box.css({"background-position":'0 '+that.jf[c]+'px'});
			$("#jpTextInner").html(t);
			that.jpText.show();			
			that.isOk = true;
		},2000);
		
	}
};

//tipBox
function tipBox(t){
	 var win = getW();		
	$("#tipBox").css({top:win.s + (win.h / 2) - $("#tipBox").outerHeight(true) * 0.5});
	$("#tipBox").html(t);
	$("#tipBox").show();
	var timer = setTimeout(function(){
		$("#tipBox").fadeOut();
	},3000);
};

//zjInfo
var zjInfo = {
	init:function(o){
		this.box = $("#"+o);
		this.tInner = this.box.find(".tInner");
		this.box.append(this.tInner.clone(true));
		this.w = this.tInner.outerWidth(true);
		this.p = 0;
		var that = this;
		this.box.bind("mouseover",function(){
			clearInterval(that.timer);
		});
		this.box.bind("mouseleave",function(){
			that.play();
		});
		that.play();
	},
	play:function(){
		var that = this;
		clearInterval(that.timer);
		this.timer = setInterval(function(){
			that.p<that.w?that.p+=2:that.p=0;
			that.box.css({left:-that.p});
		},50);
	}
};
focusFn.init("focusImg","focusNum");
zjInfo.init("zjInner");
$("#dtBox dd").bind("click",function(){				
        $("#dtBox dd").removeClass("on");
        $(this).addClass("on");
});
cjFn.init();
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function vancl_sustain(type,e){
    if(!user_id)
    {
        commonLogin(); return false;
    }
    $.post($(e).attr('href'), {match_id:match_id,type:type}, function(data){
            if(data.code < 0)
            {
                tipBox(data.msg)
            }else{
                if(data.away && data.home){
                    $("#away_sustain_num").html(data.away); 
                    $("#home_sustain_num").html(data.home);
                    var away = parseInt(data.away),home = parseInt(data.home); 
                    $("#away_sustain_img").attr('height',(away/(away+home)*100)+'%');
                    $("#home_sustain_img").attr('height',(home/(away+home)*100)+'%');                    
                }
                //tipBox(data.msg)
                $("#ddbox_title").html("竞猜成功！再抽一次吧");
                cjFn.show(2);
            }
   }, "json");
}
   
function submit_answer(question_id){
    if(!user_id)
    {
        commonLogin(); return false;
    }
    
    var key = $("#answer_"+question_id).find(".on").attr('value');
    if(!key && key != 0){
        tipBox('请选择一个答案');return;
    }
    $.post(answer_url, {question_id:question_id,key:key}, function(data){
            
            if(data.code < 0)
            {
                
                if(data.code == -6){
                    
                    if($("#answer_"+(question_id+1)).length != 0){
                        tipBox(data.msg)
                        $("#answer_"+question_id).hide();
                        $("#answer_"+(question_id+1)).show();
                    }else{
                        tipBox('又错了,机会已经用光，散卡攒点人品吧!');
                    }
                }else{
                    tipBox(data.msg)
                }
            }else{
               $("#ddbox_title").html("答对啦！我来抽大奖");
               
               cjFn.show(3);
            }
   }, "json");    
}

