
/*
 * @Description:阵营
 * @Author:金大海
 * @Update:2013/4/10创建
*/

//初始化
$(function(){
	//侧栏初始化
	zy.slideCon.init();

	//返回头部按钮	
	zy.returnTop.init();

	//阵营初始化
	zy.fnComment.init();

	//提交发布按钮
	zy.respond();

	//详细页阵营展示
	zy.detailTeams();

	//话题初始化
	addPopCamp.init();

	$("a").focus(function(){
		$(this).blur();
	});
});

/*
 * Expanding array methods
 * */
Array.prototype.indexOf = function (val) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == val) return i;
    }
    return -1;
};
Array.prototype.remove = function (val) {
    var index = this.indexOf(val);
    if (index > -1) {
        this.splice(index, 1);
    }
};

var zy = {};
var number_url = 'http://zy.hupu.com/notification/numbers',
    my_msg_url = 'http://zy.hupu.com/notification/person',
    zy_msg_url = 'http://zy.hupu.com/notification/object';

zy.ajaxing = null;
ie6 =  ($.browser.msie&&$.browser.version==6)?true:false;
//侧栏
zy.slideCon = {
	init:function(){
		var that= this;
		this.slideBox = $("#j-slideFn");
		if(this.slideBox.length<=0)return false;
		this.slideBox_t = this.slideBox.offset().top;
		this.box = $("#j-message-box");
		this.box.isShow = false;
		this.jWrapBox1 = "j-wrapBox1";
		this.jWrap1 = "j-wrap1";
		this.jScrollTool1 = "j-scrollTool1";
		that.timer = null;
		//that.isScroll = false;
		that.menuScroll = null;
		this.menu = $("#j-message-menu");
		this.messageTip = this.box.find(".message-tip-wrap");

		//我的全部阵营提示
		this.myTeams = $(".ico-myTeams");
		this.myTeams.hover(function(){
			$(this).find("div").addClass("on");
		},function(){
			$(this).find("div").removeClass("on");
		});

		//初始化侧栏高度
		slilderBarInit();
		function slilderBarInit(){
			var topWrap_h = 0;
			if(that.slideBox.find(".topWrap").length){
				topWrap_h = that.slideBox.find(".topWrap").outerHeight(true)
			}else if(that.slideBox.find(".gcSlide").length){
				topWrap_h = that.slideBox.find(".gcSlide").outerHeight(true)
			};
			
			if(getW().h>=(getW().s_h-that.slideBox.offset().top)){
				$("#"+that.jWrapBox1).height(getW().h-topWrap_h-$(".hp-footer").outerHeight(true)-30);
				return false;
			};
			$("#"+that.jWrapBox1).height(getW().h-topWrap_h-10);
			if(ie6){
				$("head").append("<style type='text/css'>.zy-wrap .slideFn{width:"+getW().w+"px;}</style>");
			};
			that.timerSlide = null;
			//绑滚动条(侧栏)
			$(window).bind("scroll",function(){
				that.setSlideBar();
			});
			that.setSlideBar();
		};
		
		//绑定ajax操作链接
		this.ajaxBtn = $(".ajaxBtn");
	   	 this.ajaxBtn.bind("click",function(e){	    
	    	if(!zy_login_uid){
	    		e.preventDefault();
	    		_common.popLogin();
	    		return false;
	    	};
	    	var _url ="http://zy.hupu.com"+$(this).attr("href") ;
	    	var me = $(this);
	    	if(me.hasClass("outBtn")){
	    		 var r=confirm("确定退出该阵营？")
				 if (!r)return false;
	    	};
	    	if(zy.ajaxing){
    	  	zy.ajaxing.abort();
    	  };
    	 zy.ajaxing = $.ajax({
	                url: _url,
	                success:function(d){
	                	if(me.hasClass("outBtn")||me.hasClass("add")){
	                		window.location.reload();
	                	};
	                    var d = $.parseJSON(d);	                  	
	                  	zy.fnTipMessage(d.msg);
	                }});
	    	e.preventDefault();
	    	return false;
	    });
	    //消息数
	    getMessage();
	   // that.menuLoad = false;
		that.menu.bind("click",function(){
			$("#j-messagePerson").html(" ").hide();
			clearTimeout(that.timer);
			if(that.box.isShow){
				that.messageTip.slideUp(250);
				$(".message-tip-arrow").hide();
			}else{
				getOtherMessage(my_msg_url,'#j-messageInner');
				that.messageTip.slideDown(250,function(){
					 if(zy.slideCon.menuScroll){
	                	zy.slideCon.menuScroll.getData();
	                };
				});				
				$(".message-tip-arrow").show();
			};
			that.box.isShow = !that.box.isShow;
			return false;
		});
		that.box.bind("mouseover",function(){
			clearTimeout(that.timer);
		});
		that.box.bind("mouseleave",function(){
			that.timer = setTimeout(function(){
				that.messageTip.slideUp();	
				that.box.isShow = false;
				$(".message-tip-arrow").hide();
			},500)
		});
	
		//阵营滚动条
		var Scroll_1 = new Scroll({wrapBox:that.jWrapBox1,wrap:that.jWrap1,scrollTool:that.jScrollTool1});		
		$("#"+this.jScrollTool1).hide();

		//如果存在滚动条
		if($("#"+that.jScrollTool1).attr("d")!="no"){
			$("#"+this.jWrapBox1).hover(function(){
				$("#"+that.jScrollTool1).show();	
			},function(){
				$("#"+that.jScrollTool1).hide();				
			});
		};

		//历史消息数组
		//this.oldMg = [];
		this.mgLi = $("#"+this.jWrap1).find("li");
		this.mgLiL = this.mgLi.length;
	
		//消息更新的数组
		this.differentMg = [];
		
		if(zy_login_uid){
			that.adMsTimer = null;
			//定时刷新数据
			that.timerMg = setInterval(function(){
				that.f5Message();
			},60*1000);
		};
	},
	f5Message:function(){
			//重置消息更新的数组
			this.differentMg = [];
			var that = this;
			//更新消息数
			getMessage();
			$.getJSON("http://zy.hupu.com/object/status", function (object) {
            	var object = object;
            	//测试数据
            	//var  object = [{"object_id":"25","msg_count":18},{"object_id":"12","msg_count":100},{"object_id":"30","msg_count":15},{"object_id":"18","msg_count":33},{"object_id":"470","msg_count":2},{"object_id":"482","msg_count":1},{"object_id":"442","msg_count":0},{"object_id":"457","msg_count":0},{"object_id":"506","msg_count":0},{"object_id":"453","msg_count":0},{"object_id":"443","msg_count":0},{"object_id":"496","msg_count":0},{"object_id":"426","msg_count":0},{"object_id":"430","msg_count":0},{"object_id":"433","msg_count":0},{"object_id":"444","msg_count":0},{"object_id":"498","msg_count":0},{"object_id":"458","msg_count":0},{"object_id":"505","msg_count":0}];
            	for(var i = 0,l = object.length;i<l;i++){
            		var msg_count = object[i]["msg_count"],object_id =  object[i]["object_id"];
            		for(var n = 1,nl = that.mgLiL;n<nl;n++){
            			if(object_id==that.mgLi.eq(n).attr("data-object-id")&&that.mgLi.eq(n).attr("data-msg-count")!=msg_count){
            				object[i].oNum = n;
            				object[i].nNum = i;
            				that.mgLi.eq(n).attr("data-msg-count",msg_count);
            				that.differentMg.push(object[i]);
            			};
            		};

            		//如果当前消息数为0，跳出循环。若有更新，更新排序
            		if(i!=l&&object[i+1]["msg_count"]==0){
            			if(that.differentMg.length>0){
            				that.promptMessage();
            			};
            			break;
            		};            		
            	};
      		}, 'json');
	},//更新排序
	promptMessage:function(){
		var that= this;
		that.differentMg.reverse();
		for(var i = 0,l=that.differentMg.length;i<l;i++){
			var nLi = $("#"+this.jWrap1).find("li");
			var o = this.differentMg[i];
			var c = o.nNum,oc=o.oNum,msg_count = o.msg_count<100?o.msg_count:"99+";
			if(this.mgLi.eq(oc).find(".message").length<=0){
				var message = '<span class="message"><em class="zy-ico ico-message2"></em><em class="text">'+msg_count+'</em></span>'
				this.mgLi.eq(oc).find(".item").append(message);
				this.mgLi.eq(oc).find(".gray").removeClass("gray");
			}else{
				this.mgLi.eq(oc).find(".message .text").html(msg_count);
			};
			nLi.eq(0).after(this.mgLi.eq(oc).addClass("addMs"));
		};
		var addMs = $("#"+this.jWrap1).find(".addMs"),opacityN = 0,t=3;

		that.adMsTimer = setInterval(function(){
			addMs.animate({opacity:opacityN},300);
			opacityN = opacityN?0:1;
			t--;
			if(t<0){
				clearInterval(that.adMsTimer);
			};
		},300);
		
	},
	setSlideBar:function(){
		var that= this;
		var t = getW().s;
			if(t>=that.slideBox_t){
				if(ie6){
					clearTimeout(that.timerSlide);
					that.slideBox.stop();
								
					that.timerSlide = setTimeout(function(){
						that.slideBox.animate({top:t},250);
						//that.slideBox.css({top:t});
					},100);
				};
				that.slideBox.addClass("slideFn");
			}else if(that.slideBox.hasClass("slideFn")){
				if(ie6){
					that.slideBox.animate({top:that.slideBox_t},250,function(){
						that.slideBox.removeClass("slideFn");	
					});
					return false;
				};
				that.slideBox.removeClass("slideFn");	
		};
	}
}

//新声
zy.voice = {
	init:function(box){
		var that = this;
		var boxid = box || ".voiceBox";
		this.box = $(boxid);
		this.sImg = this.box.find(".sImg img");
		
		this.bImg = this.box.find(".bigImg");
		this.readHideImg = this.box.find(".read-hideImg");
		this.hideImg = this.box.find(".hide-img");
		this.readBtn = this.box.find(".readBtn");

		//全文阅读
		this.readBtn.live("click",function(){
			var me = $(this).parent("a");
				that.loadText(me);
				return false;	
		});

		//大图缩小
		this.bImg.live("click",function(){
			var parent = $(this).parents(".bd"),sImgBox = parent.find(".sImg"),detailBox = parent.find(".detailBox");
				detailBox.hide();
				sImgBox.show();
				return false;
		});

		//收起
		this.readHideImg.live("click",function(){
			var parent = $(this).parents(".bd"),sImgBox = parent.find(".sImg"),detailBox = parent.find(".detailBox");
				detailBox.hide();
				sImgBox.show();
				return false;
		});

		//收起
		this.hideImg.live("click",function(){
			var parent = $(this).parents(".bd"),sImgBox = parent.find(".sImg"),detailBox = parent.find(".detailBox");
				detailBox.hide();
				sImgBox.show();
				return false;
		});


		//放大图和查看详情
		this.sImg.live("click",function(){		
			if($(this).parent()[0].nodeName.toLocaleUpperCase()=="A"){			
				var me = $(this).parent("a");
				that.loadText(me);
				return false;	
			};
			that.loadImg(this);
			return false;
		});

		//首页
		this.commentOpen = $(".comment-open");
		this.commentHide = $(".comment-hide");
		this.commentOpen.live("click",function(){
			$(this).parents(".textThumb").hide();
			$(this).parents(".textThumb").siblings(".textDetail").show();
		});
		this.commentHide.live("click",function(){
			$(this).parents(".textDetail").hide();
			$(this).parents(".textDetail").siblings(".textThumb").show();
		});

	},
	loadImg:function(o){ //加载图片
		var box = $(o).parents(".bd");
		var detailBox = box.find(".detailBox"),sImgBox = box.find(".sImg"),loading =box.find(".zy-loading"),img = new Image(),_src = $(o).attr("data-bigsrc");
		if(detailBox.isLoad){
			detailBox.show();
			sImgBox.hide();
			return false;
		};
		loading.show();
		$(img).addClass("bigImg");
		$(img).bind("load",function(){
			loading.hide();
			sImgBox.hide();
			detailBox.show();
			detailBox.isLoad = 1;
			detailBox.html(img);
		});
		img.src = _src;
	},
	loadText:function(o){ //加载全文
		var o = $(o)
		var box = o.parents(".bd");
		var detailBox = box.find(".detailBox"),sImgBox = box.find(".sImg");
		if(detailBox.isLoad){
			sImgBox.hide();
			detailBox.show();
			return false;
		};
		var moreLink = o.attr("href");
		var innerText = o.attr("data-content");
		var innerTitle =o.attr("data_title");
		var imgSrc = o.find(".pic").attr("data-bigsrc");
		var innerhtml ='<div class="hd">';
			innerhtml += '<a title="收起" class="hide-img" href="javascript:void(0)"><i class="zy-ico ico-hide"></i>收起</a><a title="查看全文" class="viewBig-img" target="_blank" href="'+moreLink+'"><i class="zy-ico ico-view"></i>查看全文</a>';
			innerhtml += '</div>';
			innerhtml += '<div class="bd">';
			//innerhtml += '<h4>'+innerTitle+'</h4>';
			innerhtml += '<div class="picBox"><img src="'+imgSrc+'" alt="'+innerTitle+'" /></div>';
			innerhtml += innerText;
			innerhtml += '</div>';
			innerhtml += '<a title="收起" class="read-hideImg" href="javascript:void(0)"><i class="zy-ico ico-hide"></i>收起</a>';
			detailBox.html(innerhtml);
			detailBox.isLoad = 1;
			sImgBox.hide();
			detailBox.show();			
	}
}

//阵营列表
zy.teams = {
	init:function(box){
		this.box = $("#"+box);
		this.item = this.box.find(".item");

		this.item.hover(function(){
			$(this).addClass("on");
		},function(){
			$(this).removeClass("on");
		});
	}
}

//返回顶部
zy.returnTop ={
	init:function(){
		var that= this;
		if(typeof notReturnTop != "undefined"){return false;}
		this.createBtn();
		this.timer = null;
		this.btn=$("#j_returnTop");
		if(getW().s > 0){
			that.btn.fadeIn();
		};
		this.feedBtn = $("#btn-feedback");
		this.btnFeedback = $("#btn-send-feedback");
		var testMail = "^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$";
		this.btnFeedback.bind("click",function(){
			//alert($.trim($("#fk_openBox #content").val()).length);
			if($.trim($("#fk_openBox #content").val()).length<=0||$("#fk_openBox #content").val()=="给虎扑阵营一些建议"){
				zy.fnTipMessage("反馈内容不能为空!");

				$("#fk_openBox #content").focus();
				return false;
			};
	
			if(!new RegExp(testMail).test($("#fk_openBox #email").val())){
				zy.fnTipMessage("邮箱格式不正确!");
				$("#fk_openBox #email").focus();
				return false;
			};
			var _value = $("#fk_openBox form").serialize(),action =$("#fk_openBox").find("form").attr('action');
                 $.post(action, _value, function (data) {
	
                    if (data.status == 0) {
                    	zy.fnTipMessage("发送成功！");
                    	openBox.hide('fk_openBox');
                    }else{
                    	zy.fnTipMessage("发送失败，请重新检查输入内容是否正确！");
                    }
                },"json"); 
		});

		$(window).bind("scroll",function(){
			if(ie6){
					if(that.timer){
						clearTimeout(that.timer);
					};
					that.btn.hide();
					that.timer = setTimeout(function(){
						var win = getW();
						if(getW().s > 0){
							that.btn.css({top:win.s+win.h-100});
							that.btn.show();
						};
					},250);
				return false;
			};
			
			if(getW().s > 0){
				that.btn.fadeIn();
			}else{
				that.btn.hide();				
			};
		});
	},
	createBtn:function(){
		var btnHtml = '<a href="javascript:void(0);" onclick="window.scrollTo(0,0);" class="returnTop" id="j_returnTop" style="display:none;">';
			btnHtml += '<span>返回顶部</span>';
			btnHtml += '<em class="zy-ico ico-returnTop"></em>';
			btnHtml += '</a>';
		$("body").append(btnHtml);
	}
};



//滚动条 start
//获取窗口
function getW() {
	var client_h, client_w, scrollTop;
	client_h = document.documentElement.clientHeight || document.body.clientHeight;
	client_w = document.documentElement.clientWidth || document.body.clientWidth;
	scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
	screen_h = document.documentElement.scrollHeight || document.body.scrollHeight;
	return o = {
		w: client_w,
		h: client_h,
		s: scrollTop,
		s_h: screen_h
	};
}

//滚动类           
function Scroll(o){
			var that = this;
			if(!o["wrapBox"]||!o["wrap"]||!o["scrollTool"]||!document.getElementById(o["wrapBox"])||!document.getElementById(o["wrap"])||!document.getElementById(o["scrollTool"])){
				return false;
			};
			this.wrapBox = $("#"+o["wrapBox"]);			
			this.wrap = $("#"+o["wrap"]);
			this.scrollTool = $("#"+o["scrollTool"]);
			this.force = false || o["force"];
			if(this.wrapBox.offsetHeight>=this.wrap.offsetHeight){
				this.scrollTool.hide();
				this.scrollTool.attr("d","no");
				if(!this.force)return false;
			};
			that.scrollTool.bind("mousedown",function(e){
				that.scrollTool.lastY = e.clientY - that.scrollTool.offset().top	
				that.scrollTool.down = true;
				//设置拖动必备条件
				that.setMove(that.scrollTool,e);
				document.onmousemove = function(e){
					var e = e||window.event;
					var posT =  e.clientY -that.scrollTool.lastY;
					if(posT<=0){
						posT = 0;	
					}else if(posT>=that.maxScrollT){
						posT = that.maxScrollT;
					};				
					//setStyle(that.scrollTool, {t:posT});
					that.scrollTool.css({top:posT});
					that.scrollMove();				
				};
				
				document.onmouseup = function(){
					that.scrollTool.stopFn();
				}	
			});
			this.wrapBox.bind("mousewheel DOMMouseScroll",function(e){
				that.wheelScroll(e);
				  e.preventDefault();
				  e.stopPropagation();
				
				 return false;
			});
		that.resizeTimer = 0;		
		that.wrapTtimer = null;
		that.getData();
}
Scroll.prototype = {
	getData:function(test){
		var that = this;
			var client = getW();				
			this.wrapH = this.wrap.outerHeight(true);
			//if(test)
			//this.scrollTool[0].style.display="block";

			this.scrollToolH =this.scrollTool.outerHeight(true);
			that.maxScrollT = this.wrapBox.outerHeight(true) - that.scrollToolH;
			//alert(this.wrapBox[0].offsetHeight);
			//滚动比例
			this.scrollScale = (this.wrapH - this.wrapBox.outerHeight(true))/that.maxScrollT;	
			this.toolT = that.scrollTool.offsetTop;	
			if(this.wrapBox.outerHeight(true)>=this.wrap.outerHeight(true)){
				this.scrollTool.hide();
				this.scrollTool.attr("d","no");
				that.wrap.css({top:0});		
			}else{
				this.scrollTool.attr("d","bl");
				this.scrollTool.show();								
			};		
		},
		wheelScroll:function(e){
			var that = this;
			if(e.wheelDelta){
				var wheelDelta = e.wheelDelta;
			}else{
				var wheelDelta =  -e.detail * 40;
			}
			var nowToolT = that.scrollTool[0].offsetTop;		
			if(wheelDelta>0){
				nowToolT-=15;	
			}else if(wheelDelta<0){
				nowToolT+=15;	
			};
			if(nowToolT<=0){
				nowToolT = 0;
			};		
			if(nowToolT >= that.maxScrollT){
				nowToolT = that.maxScrollT;
			};
			that.scrollTool.css({top:nowToolT});
			that.scrollMove();
		},
		move: function(num){
			var that = this;
			var tNum = num;
			var b,t_b;
			clearInterval(that.moveTimer);
			if(arguments.length===2){	
				 var posT = tNum/that.scrollScale;
				 clearInterval(that.moveScrollTimer);
				 that.moveScrollTimer = setInterval(function() {
					//滚动条
					t_b = Math.floor(Math.abs(parseInt(that.scrollTool.offset().top)));
					t_b += (posT - t_b) / 5;
					if (Math.abs((Math.abs(t_b)-Math.abs(posT)))<4) {
						t_b = posT;
						clearInterval(that.moveScrollTimer);
					};
					that.scrollTool.css({top:t_b});
					//内容
					b = Math.floor(Math.abs(parseInt(that.wrap.offset().top)));
					b += Math.floor((tNum - b) /5);
					if (Math.abs((Math.abs(b)-Math.abs(tNum)))<5) {
						b = tNum;
						clearInterval(that.moveTimer);
					};
					that.wrap.css({top:-b});
				},
				30);
			}else{
				that.wrap.css({top:-tNum});
			};
		},
		scrollMove:function(){
			var that = this;
			that.toolT = that.scrollTool[0].offsetTop;	
			var posT = Math.floor(that.toolT*that.scrollScale);	

			that.move(posT);			
		},
		setMove:function(obj,event){
			//清除选择
			 window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty();
			 if(document.all){ //is IE
				//焦点丢失
				obj.onlosecapture = function(){obj.stopFn();}
				//设置鼠标捕获
				obj[0].setCapture();
			}else{
				//焦点丢失
				window.onblur =function(){obj.stopFn();}
				//阻止默认动作
				
				event.preventDefault();
			};
			obj.stopFn = function(){
				if(obj.releaseCapture){
					obj.releaseCapture();
				};
				document.onmousemove = null;
				document.onmouseup = null;
				window.onblur = null;
			}
		}
}
/*滚动条end*/
//tab
function tabShow(m,c,id,l){
	for(var i =1;i<=l;i++){
		$("#"+m+i).removeClass("on");
		$("#"+c+i).hide();
		if(i==id){
			$("#"+c+i).show();			
			$("#"+m+i).addClass("on");
		};
	};
};

//消息
// 点击判断登录

function getMessage(){
        if(zy.ajaxing){
    	  	zy.ajaxing.abort();
    	  };
    	 zy.ajaxing = $.ajax({
            url: number_url,
            // url:'test/numbers.json',
            success:function(data){

                //TODO 判断登录 未登录不显示消息窗口
                var data = $.parseJSON(data),
                    person_msg = data.person;
                    //zy_msg = data.object,
                   // total = person_msg + zy_msg;
                
                switch (true){
                    // 判断登录
                    case data.status == '-1':
                        break;
                    case person_msg <= 99 && person_msg > 0:
                        $('#j-messagePerson').html(person_msg);
                        $('#j-messagePerson').show();
                        break;
                    case person_msg > 99:
                        $('#j-messagePerson').html('99+');
                        $('#j-messagePerson').show();
                        break;         
                    default:
                        $('#j-messagePerson').hide();
                        
                        break; 
                };
            }
        })
}
function getOtherMessage(url,elem){
        var html = '';
        getMessage();
        $(elem).html('<div class="fn-loading"></div>');
        if(zy.ajaxing){
    	  	zy.ajaxing.abort();
    	  };
    	  var clearAll =  $("#j-message-box").find(".clearAll");
    	 zy.ajaxing =  $.ajax({
            url: url,
            // url:'test/mymsg.json',
            success:function(data){
                data = $.parseJSON(data);
                $('#j-messageInner .fn-loading').hide();
                if(data.length==0){
                    html += '<div class="noMessage">对不起，还没有您的消息...</div>';
                   clearAll.hide();
                };
             
                if(data.length > 0){
                    $(data).each(function(index, item){
                        html += '<li><em class="zy-ico ico-message2"></em>'+ item.msg +'<a data-id="'+item.id+'" href="javascript:void(0);" class="close" >×</a></li>';
                    });
                    clearAll.show();
                };
                $(elem).html(html);
                var closeBtn = $(elem).find(".close");
              //  var clearAll = $("#j-message-box").find(".clearAll");

                clearAll.bind("click",function(){
                	var _url = $(this).href;
                	 var closeAjax = $.post(_url,function(data) {
					 	  data = $.parseJSON(data);
					 	  if(data.status==0){
					 	  	$(elem).html('<div class="noMessage">对不起，还没有您的消息...</div>');
					 	  	clearAll.hide();
					 	  };
					 	  zy.fnTipMessage(data.msg);
					});
                	 return false;
                });

                closeBtn.bind("click",function(){
                	 var me = $(this).parents("li");
                	 var data_id = $(this).attr("data-id");
                	 var closeAjax = $.post("http://zy.hupu.com/notification/person_read",{id:data_id},function(data) {
					 	  data = $.parseJSON(data);
					 	  if(data.status==0){
					 	  	me.remove();
					 	  	if($(elem).find("li").length<=0){
					 	  		$(elem).html('<div class="noMessage">对不起，还没有您的消息...</div>');
					 	  		clearAll.hide();
					 	  	};
					 	  };
					 	  zy.fnTipMessage(data.msg);
					});
                });
                if(!zy.slideCon.menuScroll){
					zy.slideCon.menuScroll = new Scroll({wrapBox:"j-wrapBox2",wrap:"j-wrap2",scrollTool:"j-scrollTool2"});
				};
				zy.slideCon.menuScroll.getData();
               
            }
        });
}
    
//评论选择阵营
zy.fnComment = {
	init:function(){
	    var that =this;
		function putData(team_id){
			//if($("#JRespond").length<=0)return false;
	        var item_id = 'cm' + team_id;
	        item_img = $('#' + item_id + ' img').attr('data_img_big'),
	        item_fullname = $('#' + item_id + ' .fullname').html();
	        $('#JCampAvatar').attr('src', item_img);
	        $('#J_inputcamp').val(item_fullname);
	        // id 写入input
	        $('#J_object_id').val(team_id);
	    };
	    this.btnLiang = $(".btnLiang");
	    this.commentPos =  $("#commentPos");
	    this.commentPos.width(getW().w);
	    this.hideInner = this.commentPos.find(".hideInner");
	    this.textBtn = this.hideInner.find(".textBtn");
	    this.submitBtn = this.hideInner.find(".submitBtn");
	    this.textBtn.bind("focus",function(){
	    	showComment();
	    });
	     this.submitBtn.bind("click",function(){
	    	showComment();	     	
	     });
	    this.respondId = $("#JRespond");


	    function showComment(){
	    	that.respondId.addClass("respondHover");
	    	$("#reply_textarea").focus();
	    	if(that.respondId.attr("s")!=1){
	    		Scroll_camp = new Scroll({wrapBox:"j-camp-list-warp",wrap:"j-camp-list-ul",scrollTool:"j-camp-list-scroll"});
	    		 that.respondId.attr("s",1);
	    		$("#J_camp_list").hide();
                  $("#j-camp-list-ul li").hover(function(){
                     $(this).addClass("on");
                    },function(){
                        $(this).removeClass("on");                
                 });
	    	};
	    };

		 that.respondId.bind("click",function(e){
		 	e.stopPropagation()
		 });

	  	 $("body").bind("click",function(e){
	   		that.respondId.removeClass("respondHover");
	   		if(ie6){
	   			var win = getW();
	   			that.commentPos.css({top:win.s+win.h-that.commentPos.height()});
	   		};
	   	});
	  	 that.timer = null;
	  	if(ie6){
	  		$(window).bind("scroll",function(){
	  			if(that.timer){
					clearTimeout(that.timer);
				};
				that.commentPos.hide();
				that.timer = setTimeout(function(){
					var win = getW();
					that.commentPos.css({top:win.s+win.h-that.commentPos.height()});
					that.commentPos.fadeIn(200);
				},250);
	  		});
	  	};

	    this.btnLiang.live("click",function(e){
	    	if(!zy_login_uid){
	    		e.preventDefault();
	    		_common.popLogin();
	    		return false;
	    	};
	    	var _url ="http://zy.hupu.com"+$(this).attr("href") ;
	    	var me = $(this);
	    	if(zy.ajaxing){
    	  		zy.ajaxing.abort();
    	 	 };
    		 zy.ajaxing = $.ajax({
	                url: _url,
	                success:function(d){
	                    var d = $.parseJSON(d);
	                  	if(d.status==0){
	                  		if(me.hasClass("like")){
	                  			me.addClass("likeEd");	
	                  		};
	                  		me.find("span").html(d.light_count||d.good_count);
	                  	}
	                  	zy.fnTipMessage(d.msg);
	                }});
	    	return false;
	    });

	    $('#J_inputcamp').bind('focus blur', function(ev){
	        if(ev.type == 'focus'){
	            $('#J_camp-list').fadeIn('fast');
	        }
	        if(ev.type == 'blur'){
	            $('#J_camp-list').fadeOut('fast');
	        }
	    });
	    // 文字全选
	    try{
	        if(window.curr_select_object_id){
	            putData(curr_select_object_id);
	        }
	    }catch(e){};
	    $('#J_inputcamp').click(function(){
	        // 文字全选
	        $(this).select();
	        $('#J_camp_list').show();
	         Scroll_camp.getData();
	    });
	    $('#J_inputcamp').bind('blur',function(){
	    	 if($(this).val()==""){
	        	$(this).val("中立");
	        };
	    });
	    $('#J_inputcamp').bind('input propertychange', function(ev){
	        // 显示浮层
	        $('#J_camp_list').fadeIn('fast');
	        var input_value = encodeURIComponent($(this).val()),
	            // html = '';
	            html = '<li id="cm0"><img data_img_big="/images/zy/avatat_default.jpg" src="/images/zy/avatat_default.jpg" height="20" alt=""> <em><span class="fullname">中立</span></em></li>';
	       		if(input_value.length){
			      if(zy.ajaxing){
		    	  	zy.ajaxing.abort();
		    	  };
		    	  zy.ajaxing = $.ajax({
			                url: 'http://zy.hupu.com/object/get?name=' + input_value,
			                success:function(d){
			                    var d = $.parseJSON(d),
			                        teams = $.parseJSON(d.data);
			                    $(teams).each(function(index, item){
			                        html += '<li id="cm'+ item.object_id +'"><img data_img_big="' + item.big_logo + '" src="'+ item.logo +'" height="20" alt=""> <em><span class="fullname">'+ item.fullname + '</span> (@<span class="shortname">' + item.name +'</span>)</em></li>';
			                    });
			                    $('#J_cmlist').html(html);
			                    Scroll_camp.getData();
			              
			                    $('#J_cmlist li').bind('click', function(){
							       j_cmlist_li(this);
							    });
			               }
			      });
	        	}
	    });
		
	    $('#J_cmlist li').bind('click', function(){
	        j_cmlist_li(this);
	    });

	    function j_cmlist_li(o){
	    	var item_id = o.id,
	            team_id = (item_id.substr(2, (item_id.length - 1)) - 0);
	            putData(team_id);
	            // 隐藏浮层
	            $('#J_camp_list').fadeOut('fast');
	    }


	     $('body').bind("click",function(e){
	        if(e.target.id != 'J_inputcamp');
	        $('#J_camp_list').hide();
	     });
	}
}

/*
 * 提交评论
 * */
zy.respond = function() {
    if($("#JRespond").length<=0)return false;
    var resWrap = $("#JRespond"),resBtn = resWrap.find(".comment-reply");
    $(".reply_to").live("click", function(e) {
		e.preventDefault();
	    var append_str = $(this).attr("data");
	    $("#reply_textarea").focus().html(append_str);
	});
    resBtn.bind("click",function(e){
    	if(resBtn.attr("submiting")){
    		zy.fnTipMessage("正在提交，请稍候...");
    		return false;
    	};
    	 var inputcampVal = $("#J_inputcamp").val();
    	 var li = $("#J_cmlist li");
    	 var li_l = li.length;
    	 for(var i = 0;i<li_l;i++){ 
    	 	if(li.eq(i).find(".fullname").html()==inputcampVal){
    	 		break;
    	 	}else if(i==li_l-1){
    	 		zy.fnTipMessage("立场错误! 请正确选择下拉菜单中的立场选项!");
    	 		return false;    	 		
    	 	}; 	 	
    	 };
    	 if($.trim($("#reply_textarea").val()).length<=0){
    	 	$("#reply_textarea").focus();
    	 	zy.fnTipMessage("内容不能为空！");
    	 	return false;
    	 };
    	 
    	  resBtn.attr("submiting",1);
    	 var value = resWrap.find('form').serialize();
    	 var action = resWrap.find("form").attr('action');
    	  e.preventDefault();
    	  if(zy.ajaxing){
    	  	zy.ajaxing.abort();
    	  };
    	 
    	 zy.ajaxing = $.post(action, value, function (data) {
            if (data.status == 0) {
            	 resBtn.attr("submiting",0);
                window.location.reload();
            } else {
                zy.fnTipMessage(data.msg);
            }
        }, 'json');0
    });

}

//公共提示
zy.fnTipMessageTimer = null;
zy.fnTipMessage = function(text,t){
	if($("#fn-tipMessage").length<=0){
		var tipHtml = "<div id='fn-tipMessage'></div>";
		$("body").append(tipHtml);
	};
	var t = t||2000;
	var box =$("#fn-tipMessage");
	box.html(text).css({"margin-left":-box.width()/2,top:getW().s+getW().h/2-15});
	box.show();
	clearTimeout(zy.fnTipMessageTimer);
	zy.fnTipMessageTimer = setTimeout(function(){
		box.fadeOut(250);
	},t)
	return false;
};

//弹出框
var openBox = {
  show: function (id) {
    if (!this.mask) {
      this.createMask();
    };
    var win = getW();
    var obj = document.getElementById(id);
    obj.style.top = win.s + (win.h / 2) - parseInt($(obj).height()) * 0.5 + "px";
    obj.style.display = "block";
    obj.style.zIndex = 99999;
    this.mask.style.display = "block";
  },
  createMask: function () {
    var win = getW();
    this.mask = document.createElement("div");
	if(win.s_h<win.h)win.s_h = win.h; //内容小于屏幕高度
    this.mask.style.height = win.s_h + "px";
    this.mask.style.width = win.w + "px";
    this.mask.style.zIndex = 9999;
    this.mask.style.background = "#000";
    this.mask.style.opacity = "0.8";
    this.mask.className = "mask_openBox";
    this.mask.style.filter = "alpha(opacity:80)";
    document.body.appendChild(this.mask);
  },
  hide: function (id) {
    if (this.mask) {
      this.mask.style.display = "none";
      if(zy.ajaxing){
      	zy.ajaxing.abort();
      };
    };
    var obj = document.getElementById(id);
    obj.style.display = "none";
  }
};

//发布新话题
/*
 *  publish topic
 * */
var addPopCamp = {
    init:function () {
    	/*Determine whether the login*/
    	if($("#j-createVote-btn").length<=0 || $("#j-createVote-btn").attr("onclick"))return false;
    	var createVoteBtn = $("#j-createVote-btn"); 
	    	createVoteBtn.bind("click",function(){
	    		e = arguments[0] || window.event;	
	    		var target = e.target || e.srcElement,fullname = target.getAttribute('data_object_fullname'),name =target.getAttribute('data_object_name'),id=target.getAttribute('data_object_id');
	    		if(createVoteBtn.attr("init")!=1){
	    			 if(id){
		                addPopCamp.going({isIn:true,objectFullname:fullname,objectId:id,objectName:name});
		            }else{
		                addPopCamp.going();
		            }
		            createVoteBtn.attr("init",1);	    			
	    		};
	    		openBox.show('zy-openBox-release');
	    		
	    	});
    },
    going:function(detail){
        var  that = this,
        	 iNow = 0, bAdd = true;
        	 this.JaddForm = $("#zy-openBox-release");
        	 this.boxreleaseForm = $("#zy-openBox-release-form");
        	 this.JPostTitle = $("#JPostTitle");
        	 this.JPostTitleNum = $("#JPostTitle-num");
        	 this.jaddText = $("#j-addText");
        	 this.JPostContent = $("#JPostContent");
        	 this.JCampAdded = $("#JCampAdded");
        	 this.JAddCampInput = $("#JAddCampInput");
        	 this.listBox = this.JaddForm.find(".list-box");
        	 this.JAddCamp = $("#JAddCamp");
        	 this.object_ids = $("#object_ids");
        	 this.CampSubmitBtn = $("#CampSubmitBtn");
        	 this.oGuide = this.JaddForm.find(".add-post-guide");
        	 this.scrollWrap = $("#j-list-box");
        	 this.scrollTool = $("#j-list-scroll");
        	 this.scrollBox = $("#j-list-wrap");
        	 this.submitEd = false; //避免重复提交
        	 this.arrayCamp = new Array();
        	 that.listBoxScroll = null;	
        	 this.JPostTitle.keyup(function(){       	 	
        	 	if($.trim($(this).val()).length>140){
        	 		that.JPostTitle.val(that.JPostTitle.val().slice(0,140));
        	 	};
        	 	that.JPostTitleNum.html(140-$.trim($(this).val()).length);
        	 });
        	 this.jaddText.bind("click",function(){
        	 	that.JPostContent.toggle();
        	 	that.JPostContent.focus();
        	 });
        if(detail)that.selectItem(detail.objectFullname,detail.objectId,detail.objectName );
         that.changeInput(function () {
             that.onInput();
        });
        that.mouseSelect();
        that.JCampAdded.bind("click",function(){
        	var e = arguments[0] || window.event, target = e.srcElement || e.target;
            if (target.nodeName.toLowerCase() == 'a' && target.className == 'tag-close') {
                that.JCampAdded[0].removeChild(target.parentNode);
                var id = target.parentNode.getAttribute('data-id');
                that.arrayCamp.remove(id);
            }
            that.object_ids[0].value = that.arrayCamp;
        });
     
       	that.JAddCamp.bind("keydown",function(){       	  	
            var e = arguments[0] || window.event, li=that.listBox.find("li"), len = li.length, i = 0;
            switch (e.keyCode) {
                case 38:
                    iNow--;
                    if (iNow < 0) {
                        iNow = len - 1;
                    }
                    that.each.call(that,iNow);
                    break;
                case 40:
                    iNow++;
                    if (iNow > len - 1) {
                        iNow = 0;
                    }
                    that.each.call(that,iNow);
                    break;
                case 13:
                    var bNull = that.JAddCampInput.val();
                    e.preventDefault();
                    if (!bNull&&!that.listBox[0].childNodes.length)return;
                    var name, id,fullName;
                    for (; i < len; i++) {
                        fullName = li.eq(iNow).attr('data-fullName');
                        id = li.eq(iNow).attr('data-id');
                        name = li.eq(iNow).attr('data-name');
                    }
                    that.selectItem(fullName, id,name);
                    break;
            }
        });

       	that.listBox.find("li").live("mouseover",function(){
       		that.listBox.find("li").removeClass("on");
       		$(this).addClass("on");
       	});
       	that.listBox.find("li").live("mouseleave",function(){
       		$(this).removeClass("on");
       	});
      
      	that.CampSubmitBtn.bind("click",function(e){
            var _value = that.boxreleaseForm.serialize(),bZy=that.JCampAdded.find(".addedTag").length,action = that.JaddForm.find("form").attr('action');
            e.preventDefault();
            if(that.submitEd){
            	that.oGuide.addClass("h");
            	return false;
            };
            if (bAdd&&bZy>0) {
            	 that.oGuide.show();  
            	 that.oGuide.removeClass("h");
            	 that.oGuide.html("正在提交，请稍候...<div class='h_10'></div>");
                 $.post(action, _value, function (data) {
                	   that.submitEd  = true;
                    if (data.status == 0) {
                    	that.submitEd  = false;
                        window.location.href = data.redirect_url;
                        bAdd = false;
                    }else{
                    	 that.oGuide.html(data.msg+"<div class='h_10'></div>");
                         that.oGuide.addClass("h");
                         that.submitEd  = false;
                    }
                },"json"); 
            }else if(bZy<=0){
            	that.JaddForm.find(".dialog-msg").addClass("h");
            	that.JaddForm.find(".dialog-msg").show();
            }
        });	
    },
    changeInput:function (fn) {
        var that = this;     
        if (/msie/i.test(navigator.userAgent)) {
            that.JAddCampInput[0].attachEvent('onpropertychange', function () {
                fn();
            });
        } else {
            that.JAddCampInput[0].addEventListener("input", function (e) {
                fn();
            }, false);
        }
    },
    mouseSelect:function () {
        var that = this, cItem = that.listBox.find("li"),
            len = cItem.length,
            i = 0;

            cItem.live("click",function(){
            		var fullName ,id,name;
            	    fullName = $(this).attr('data-fullName');
                    id =  $(this).attr('data-id');
                    name =  $(this).attr('data-name');
				    that.selectItem(fullName, id,name); 
            });
    },
    onInput:function () {
        var that = this;
        var sInput = encodeURIComponent(that.JAddCampInput[0].value);
        that.listBox[0].innerHTML = ' ';
        if(sInput==""){  
        	that.scrollWrap.hide();       
        	return false;
        };
        var url = '/object/get?name=' + sInput + '';
        that.JaddForm.find(".dialog-msg").removeClass("h");
        that.JaddForm.find(".dialog-msg").hide();
        
        $.get(url, function (object) {
            var data = eval('(' + object.data + ')');           
            var l = data.length, i = 0;

            if(l<=0){
                that.scrollWrap.hide();               
            	return false;
           	}
             that.scrollWrap.show();
                for (; i < l; i++) {                	
                     var li = document.createElement('li');
                      li.setAttribute('data-name', data[i].name);
                      li.setAttribute('data-id', data[i].object_id);
                      li.setAttribute('data-fullName', data[i].fullname);
                      li.innerHTML = '<img  src="' + data[i].logo + '" alt="logo"> <div>' + data[i].fullname + '(@'+data[i].name+')</div><div class="c-999">'+data[i].follow_count  +'人关注</div>';
                      that.listBox[0].appendChild(li);
                };
                //初始化滚动条
                if(!that.listBoxScroll){
              		that.listBoxScroll = new Scroll({wrapBox:"j-list-box",wrap:"j-list-wrap",scrollTool:"j-list-scroll",force:true});
                }else{
                	//更新滚动条范围
                	that.listBoxScroll.getData();

                }
        }, 'json');
    },
    selectItem:function (itemFullname, iteId,iteName) {    	
        var that = this;
        for(var i =0;i<that.arrayCamp.length;i++){
			if(that.arrayCamp[i]==iteId){
				return false;
				break ;	
			};
		 };
        (function(){
            var tag = document.createElement('span');
            tag.className = 'addedTag';
            tag.setAttribute('data-id', iteId);
            tag.innerHTML = '<b class="tit">' + iteName + '</b><a href="javascript:" class="tag-close">x</a>';
            that.JCampAdded[0].appendChild(tag);
            that.JAddCampInput[0].value = '';
        })();
        that.listBox[0].innerHTML = '';
        that.scrollWrap.hide();
        if(that.listBoxScroll){
        	that.listBoxScroll.getData();
        };
        that.arrayCamp.push(iteId);
        that.object_ids[0].value = that.arrayCamp;
    },
    each:function (iNow) {
        var item = this.listBox.find("li");
        item.removeClass("on");
        item.eq(iNow).addClass("on");
    }
};

//广场
zy.square = function(){
	var squareBtn = $("#j-squareBtn");
	var posT = squareBtn.offset().top;
	var toT = posT;
	squareBtn.bind("click",function(){
		window.scrollTo(0,toT);
		return false;
	});
	var s_h = getW().s_h,c_h = getW().h;
	$(window).bind("scroll",function(){
		var t = getW().s;
		if(t>=posT-50){
			squareBtn.html("24小时热门话题TOP10  ↑");
			toT = 0;
		}else{
			squareBtn.html("24小时最赞新声TOP10  ↓");		 
			toT = posT;
		};
	});	
};

//详细页阵营展示
zy.detailTeams = function(){
	var box  = $("#j-teams");
	if(box.length<=0)return false;
	var list = box.find(".teamsIco");
	var showBtn = box.find(".moreTeam");
	var hideBtn = box.find(".hideTeam");
		showBtn.click(function(){
			list.height("auto");
			list.width("180");
			showBtn.hide();
			hideBtn.show();
			return false;
		});
		hideBtn.click(function(){
			list.height("32px");
			list.width("110");
			showBtn.show();
			hideBtn.hide();
			return false;
		});
}

//弹窗
function openVideo(a)
{
var href = a;
var win = getW();
if(href.indexOf("sina.com") !== -1){//判断新浪打开窗口
	//alert(href+="#myflashBox")
	var openW = 640,openH = 525;//,href ="#myflashBox";
	var ttop = win.h/2-openH/2,lleft = win.w/2-openW/2;
    var newwin=window.open(href,"","toolbar=1,location=1,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,top="+ttop+",left="+lleft+",width="+openW+",height="+openH+"");
}else if(href.indexOf("qq.com") !== -1){//qq窗口
	var openW = 650,openH = 533;
	var ttop = win.s_h/2-openH/2,lleft = win.w/2-openW/2;
    var newwin=window.open(href+"#mod_player","","toolbar=1,location=1,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,top="+ttop+",left="+lleft+",width="+openW+",height="+openH+"");
}else if(href.indexOf("youku.com") !== -1){
	var openW = 860,openH = 524;
	var ttop = win.h/2-openH/2,lleft = win.w/2-openW/2;
    var newwin=window.open(href+="#player","","toolbar=1,location=1,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,top="+ttop+",left="+lleft+",width="+openW+",height="+openH+"");
}else if(href.indexOf("tudou") !== -1){
	var openW = 640,openH = 520;
	var ttop = win.s_h/2-openH/2,lleft = win.w/2-openW/2;
    var newwin=window.open(href+="#player","","toolbar=1,location=1,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,top="+ttop+",left="+lleft+",width="+openW+",height="+openH+"");
}else if(href.indexOf("56.com") !== -1){
	var openW = 600,openH = 493;
	var ttop = win.s_h/2-openH/2,lleft = win.w/2-openW/2;
    var newwin=window.open(href+="#player","","toolbar=1,location=1,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,top="+ttop+",left="+lleft+",width="+openW+",height="+openH+"");
}else if(href.indexOf("ku6.com") !== -1){
	var openW = 640,openH = 480;
	var ttop = win.s_h/2-openH/2,lleft = win.w/2-openW/2;
    var newwin=window.open(href+="#ku6player","","toolbar=1,location=1,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,top="+ttop+",left="+lleft+",width="+openW+",height="+openH+"");
}else if(href.indexOf("sohu.com") !== -1){
	var openW = 600,openH = 489;
	var ttop = win.s_h/2-openH/2,lleft = win.w/2-openW/2;
    var newwin=window.open(href+="#sohuplayer","","toolbar=1,location=1,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,top="+ttop+",left="+lleft+",width="+openW+",height="+openH+"");
}else if(href.indexOf("pptv.com") !== -1){
	var openW = 660,openH = 520;
	var ttop =win.s_h/2-openH/2,lleft = win.w/2-openW/2;
    var newwin=window.open(href+="#pptv_playpage_box","","toolbar=1,location=1,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,top="+ttop+",left="+lleft+",width="+openW+",height="+openH+"");
}else{
	var openW = 550,openH = 400;
	var ttop =win.s_h/2-openH/2,lleft = win.w/2-openW/2;
    var newwin=window.open(href,"","toolbar=1,location=1,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,top="+ttop+",left="+lleft+",width="+openW+",height="+openH+"");
}
}


//瀑布流
/*  
  	
 */
var mainList = {
	init:function(boxId,pagesId){
		var that = this;
		if(typeof data_json=="undefined")return false;
		var boxId = boxId ||"#mianWrap";
		this.box = $(boxId);
		this.l = data_json.length;    
		this.c = 0;
		this.boxT = this.box.offset().top;
		this.w_h = getW().h;	
		that.timer = 0;
		this.boxH = this.box.outerHeight(true);	
	//	that.dd = 0;
		this.loading = document.createElement("div");
		this.loading.className = "fn-loading";
		this.loading = $(this.loading);
		this.box.after(this.loading);
		this.maxT = this.boxT + this.boxH-this.w_h;		
		//this.create();
		that.loadOver = false;
		this.scrollFn();
		this.pages = $(pagesId);
		this.pages.hide();
		that.timerLoading = null;
		$(window).bind("scroll",function(){that.scrollFn();});
	},
	scrollFn:function(){
		var that =this;
		if(this.loadOver){return false;}
		that.timer = setTimeout(function(){				
			if((getW().s)>that.maxT){
				that.loading.show();
				clearTimeout(that.timerLoading);
				that.timerLoading = setTimeout(function(){
					that.create();				
				},500);
			};	
		},100);		
	},  
	create:function(){
		var that =this;
		var  o = {};
		for(var n = 0;n<20;n++){
			//for(var i = 0;i<4;i++){
				if(this.c>=this.l) {
					if(!that.loadOver){
						//var boxH = this.box.outerHeight(true);
						/*for(var t = 0;t<4;t++){
							var bgHtml = document.createElement("div");
							bgHtml.style.background = "#f0f0f0";
							bgHtml.style.height = boxH-this.li.eq(t).outerHeight(true) + "px";							
							this.li.eq(t).append(bgHtml);
						};		*/	
						that.loadOver = true;
					};					
					break;					
					return false;
				};

				//公用数据格式部分
				o.create_time=data_json[this.c]["create_time"];
				var d = data_json[this.c]["data"];
                o.publisher_avatar_url = d.publisher_avatar_url;
                o.good_count = d.good_count;  //赞
                o.reply_count = d.reply_count; //回复数
                o.light_count = d.light_count; //亮回复数
                o.good_link = d.good_link; //赞链接
                o.detail_link = d.detail_link; //详细页
                o.id = d["id"];
                o.title = d.title; //标题

                //新声
                if(data_json[this.c]["type"]=="voice"){
                   o.publisher_avatar_url = d.publisher_avatar_url;//发布者头像地址
              	    o.publisher_name = d.publisher_name;//发布者
                	o.url = d.url;//新闻内页地址
                	o.voice_id = d["voice_id"];
                	o.content = d.content; //新闻正文内容
                	o.detail_content = d.detail_content; //全文内容
                	o.image_thumb = d.image_thumb; //缩略图
                	o.image_bmiddle = d.image_bmiddle;//中等大小图片
                	//o.publish_date = d.publish_date;//新闻发布时间
                	o.video = d.video; //视频地址
                	o.video_cover_img = d.video_cover_img; //视频封面地址
                	o.publisher_name = d.publisher_name; //来自谁
                	o.publisher_url = d.publisher_url ;//来自作者地址
                	o.publisher_description = d.publisher_description;
                	var $item = this.voice_html(o);
                };

                 //视频
                if(data_json[this.c]["type"]=="video"){
                 	o.author = d.author;//作者
                 	o.localcover = d.localcover;//封面
                 	o.video = d.from_url;//视频地址
                 	o.playtime = d.playtime; //视频时长
                 	o.vid = d["vid"];//视频id
                 	var $item = this.video_html(o);
                };

                 //话题
                 if(data_json[this.c]["type"]=="topic"){
                 	o.content = d.content; //内容
                 	o.visit_count = d.visit_count;//浏览数量
                 	o.replies = d.replies;//评论
                 	o.tid = d.tid; 
                 	var $item = this.topic(o);
                 };

				  this.box.append($item);     
                 this.c ++;
                 if(this.c>=this.l) {
                 	that.loadOver = true;
                 	this.pages.show();	
                 };
		};
		this.boxH = this.box.outerHeight(true);
		this.maxT = this.boxT + this.boxH-this.w_h;	
		that.loading.hide();
	},
	voice_html:function(o){
		var html = "";
			html+='<div class="item_voice">';
			html+='<div class="head"><div class="photo"><span><img alt="'+o.publisher_name+'" src="'+o.publisher_avatar_url+'"></span></div><div class="h_10"></div><div class="data"><div class="arrow"></div>'+o.create_time+'</div></div>';
			html+='<div class="inner">';
                html+='	<div class="arrow"></div>';
                html+='     <div class="hd"><h4>';
                if(o.publisher_name){
                	html+=o.publisher_name;
            	};
                if(o.publisher_description){
                	html+='<span>'+o.publisher_description+'</span>';
                };
                html+='</h4>';
                if(o.publisher_name){
                	html+='<div class="time">来自&nbsp;<a href="'+o.publisher_url+'" target="_blank">'+o.publisher_name+'</a></div>';
                };
                html+='         </div>';
			 	html+=' 	<div class="bd">';
				html+='			<div class="text">'+o.content+'</div>';
				if(o.video){
					html += '<div class="sVideo">';
                    html += '       <a target="_blank" href="'+o.video+'" onclick="openVideo('+"'"+o.video+"'"+');return false;">';
                    html += '            <em class="voice-vPlay-ico"></em>';
                    html += '            <img src="'+o.video_cover_img+'" alt="'+o.title+'">';
                    html += '         </a>';
                    html += ' </div>';
				};
				if(o.image_bmiddle){
					html+='<div class="sImg" style="display: inline-block;">';
					if(o.detail_content){
	                	html+='    <a target="_blank" data-content="'+o.detail_content+'" data_title="'+o.title+'" href="'+o.url+'">';
	            	};
	                html+=' <img data-bigsrc="'+o.image_bmiddle+'" src="'+o.image_thumb+'" class="pic"> ';
	                html+=' <img class="zy-loading" src="http://b3.hoopchina.com.cn/images/loading01.gif">';
	                if(o.detail_content){
		               	 html+=' <em class="readBtn">全文阅读</em>';
	                	 html+='</a>';
	                }
	                html+='</div>';
            	};
                html+='<div class="detailBox" style="display: none;"></div>';
				html+='</div>';
				html+='<div class="fot">';
                html+='<div class="fr"><a class="commentLink" href="'+o.url+'">'+o.reply_count+'评论 </a> </div>';
                html+='<a class="like btnLiang" href="'+o.good_link+'"><em class="zy-ico ico-like"></em>赞(<span>'+o.good_count+'</span>)</a> </div>';
             
                html+='</div>';
                html+='</div>';
           return html;
	},
	video_html:function(o){
		var html = "";
			html+='<div class="item_voice">';
			html+=' <div class="head">';
                html+='  <h3> ';
                html+='  <em class="zy-ico ico-video"></em>视频</h3>';
                html+='     <div class="h_10"></div>';
                html+='     <div class="data">';
                html+='          <div class="arrow"></div>';
                html+='          '+o.create_time+'</div>';
                html+='  </div>';
                html+='<div class="inner">';
                html+='  <div class="arrow"></div>';
                html+='  <div class="bd">';
                html+='     <div class=" item_video">';
                html+='         <div class="inner">';
                html+='            <div class="hd">';
                html+='             <h4><a href="'+o.video+'" onclick="openVideo('+"'"+o.video+"'"+');return false;" >'+o.title+' </a></h4>';
                html+='              </div>';
                html+='               <div class="fot">';
                html+='               <a class="like btnLiang" href="'+o.good_link+'">';
                html+='              	<em class="zy-ico ico-like"></em>赞(<span>'+o.good_count+'</span>)';
                html+='                 </a>';
                html+='                 <div class="timeAuthor">';
                html+='                  <span>&nbsp;-@'+o.author+'</span>&nbsp;&nbsp;发表于'+o.create_time+'</div>';
                html+='              </div>';
                html+='          </div>';
                html+='          <div class="ph">';
                html+='       			 <a href="'+o.video+'" onclick="openVideo('+"'"+o.video+"'"+');return false;" >';
                html+='                  <img src="'+o.localcover+'"> ';
                html+='                    <span class="text">'+o.playtime+'</span>';
                html+='                   <span class="bg"></span>';
                html+='                   <span class="ico_v"></span>';
                html+='              </a>';
                html+='          </div>';
                html+='      </div>';
                html+='  </div>';
                html+='	</div>';
                html+='</div>';
            return html;
	},
	topic:function(o){
		var html = "";
			html+='<div class="item_voice">';
			html += '<div class="head">';
              html += '     <h3>';
              html += ' <em class="zy-ico ico-vote2"></em>话题</h3>';
              html += '               <div class="h_10"></div>';
              html += '               <div class="data">';
              html += '                   <div class="arrow"></div>';
              html += '                   '+o.create_time+'</div>';
              html += '            </div>';
              html += '       <div class="inner">';
              html += '           <div class="arrow"></div>';
              html += '           <div class="bd">';
              html += '               <div class="item_vote">';
              html += '                      <div class="hd">';
              html += '                      <h4> <a href="/topic/'+o.id+'">'+o.title+'</a></h4>';
              html += '                       </div>';
              if(o.replies.length>0){
              	  html += ' <div class="comment">';
              	  for(var i = 0;i<o.replies.length;i++){
              	  	 var repliesItem = o.replies[i];
              	  	 if(i==0){
              	 		 html += ' <div class="li first">';
              	  	 }else{
              	 		 html += ' <div class="li">';
              	  	 };
              	 	 html += '  <div class="sl">';
	             	 html += '  <span class="c-f46b21">[亮回复]</span>';
	             	 html+='<span class="textThumb">'+repliesItem.content.substr(0,50);
	             	 if(repliesItem.content.length>50){
	             	 	html+='&nbsp;<a class="comment-open" href="javascript:void(0);">展开</a>'
	             	 };
	             	 html+='</span>';
	             	 if(repliesItem.content.length>50){
	             	 	html+='<span class="textDetail">'+repliesItem.content+'<a class="comment-hide" href="javascript:void(0);">收起</a></span>'
	             	 };
	              	// html += '  <span data-content="'+repliesItem.content+'">'+repliesItem.content+'</span>&nbsp;&nbsp;';
	             	 html += '&nbsp;-@'+ repliesItem.username+'</div>';
	             	 html += ' </div>';
              	  };
	              html += '	<div class="more"><a href="'+o.detail_link+'">全部'+o.reply_count+'条回复》</a></div>';
	              html += '	</div>';
              };
              html += '		<div class="fot">';
              html += '				<a href="'+o.good_link+'" class="like btnLiang">';
              html += '                <em class="zy-ico ico-like"></em>赞(<span>'+o.good_count+'</span>)';
              html += '				</a>';
              html += '             <div class="fr">';
              html += '             	 <a class="'+o.detail_link+'"> '+o.light_count+'亮&nbsp;'+o.reply_count+'回复</a>';
              html += '             </div>';
              html += '       </div>';
              html += '                </div>';
              html += '            </div>';
              html += '        </div>';
              html += '    </div>';	
              return html;
	}
};

function showMsg(o){
	document.title = o;	
}