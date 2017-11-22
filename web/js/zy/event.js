/**
 * Created by JetBrains WebStorm.
 * User: DH.JIN
 * Date: 130511
 * Time: 上午10:08
 * To change this template use File | Settings | File Templates.
 */
 function getStyle(ele) {
    var style;
    if (document.defaultView && document.defaultView.getComputedStyle) {
      style = document.defaultView.getComputedStyle(ele, null);
    } else {
      style = ele.currentStyle;
    };
    return style;
}

//openBox
var openBox = {
  show: function (id) {
    if (!this.mask) {
      this.createMask();
    };
    var win = getW();
   
    var obj = document.getElementById(id);
    obj.style.top = win.s + (win.h / 2) - $(obj).outerHeight(true) * 0.5 + "px";
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
    };
    var obj = document.getElementById(id);
    obj.style.display = "none";
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

//获取url参数
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}


//瀑布流
/*  
  
 */
var mainList = {
	init:function(){
		var that = this;
		items = memory_list;
		if(typeof items=="undefined")return false;;
		this.box = $("#mianWrap");
		this.box2 = $("#mianWrap2");
		this.item = this.box.find("li");
		that.showTimer = null;
		this.pop = $("#misitePop");
		this.pop_liang = $("#pop_liang");
		this.pop.live("mouseover",function(){
			clearTimeout(that.showTimer);
			//clearInterval(that.playTimer);
		});
		this.pop.live("mouseleave",function(){
			clearTimeout(that.showTimer);
			that.showTimer = setTimeout(function(){
				that.pop.hide();
				that.item.removeClass("on");
			},50);
		});
		this.maskHeader = $(".minsiteAlex");
		this.masH = getW().h;

		//alert(getQueryString("release"));
		if(typeof login_uid != "undefined" && getQueryString("release")==login_uid){
			openBox.show("eventShareBox");
		};

		this.maskHeader.height(this.masH);
		$(".minsiteAlexWrap").height(this.masH);
		$(".scrollBox").height(this.masH);
		if(typeof is_join == "undefined"){
			is_join = false;
		};
		this.btnCy = $(".btnCy");
		this.btnCy.bind("click",function(){
			if(typeof login_uid == "undefined"){
				openBox.hide('eventOpenBox');
				_common.popLogin();
				return false;
			};
			if(is_join){
				openBox.show('eventError');
				return false;
			};
			openBox.show('eventOpenBox');
		});
		$(".eventPage").bind("mouseleave",function(){
			clearTimeout(that.showTimer);
			that.showTimer = setTimeout(function(){
				that.pop.hide();
				that.item.removeClass("on");
			},50);
		});
		//发布留言
		$("#addContent_submit").live("click",function(){
			if(typeof login_uid == "undefined"){
				openBox.hide('eventOpenBox');
				_common.popLogin();
				return false;
			};
			if($(this).attr("submitEd")){
				zy.fnTipMessage("正在提交请稍候...");
				return false;
			};
			if($.trim($("#addContent").val()).length>50){
				zy.fnTipMessage("请不要超过50个字！");
				return false;
			}else if($.trim($("#addContent").val()).length<=0){
				zy.fnTipMessage("输入不能为空");
				return false;
			};
			
			$(this).attr("submitEd",1);
			var boxreleaseForm = $("#addContent_form");
			var _value = boxreleaseForm.serialize(),action = boxreleaseForm.attr('action');
                 $.post(action, _value, function (data) {
                    if (data.status == 0) {
                    	var href = document.href;
                    	/*if(href.indexOf("?")){
                        	href+="&release=1";
                    	}else{
                        	href+="?release=1";                    		
                    	};*/
                    	window.location.href = "http://zy.hupu.com/memorywall/sir-retires?uid="+login_uid+"&release="+login_uid;
                        //window.location.reload();
                    }else{
                    	zy.fnTipMessage(data.msg);
                    }
                },"json"); 
		});

		//点亮
		this.pop_liang.bind("click",function(){
			if(typeof login_uid == "undefined"){
				_common.popLogin();
				return false;
			};
			$.post("http://zy.hupu.com/event/reply_light",{id:that.pop.find(".num").html()},function(data){
				var d = $.parseJSON(data);
				if(d.status==-1){
					zy.fnTipMessage(d.msg);
				}else{
					zy.fnTipMessage(d.msg);
					that.pop_liang.find("span").html(d.light_count);
				}
			},JSON);
		});

		this.loadeding = false;

		//鼠标经过
		this.item.live("mouseover",function(){
			var me = $(this);
			clearTimeout(that.showTimer);
			if(that.initUid_timer){
				clearTimeout(that.initUid_timer);
				that.item.eq(that.uid_c).removeClass("on").addClass("hover");
			};
			that.showTimer = setTimeout(function(){
				that.item.removeClass("on");
				//that.autoPlayMode = false;
				that.pop.show();
				that.showDetail(me);
				/*if(that.playTimer){
					clearInterval(that.playTimer);
				};*/
			},100);
		});
		//自动播放开关
		//that.autoPlayOn = true;
		this.item.live("mouseleave",function(){
			clearTimeout(that.showTimer);

			that.showTimer = setTimeout(function(){
				that.pop.hide();
				that.item.removeClass("on");
			},50);
		});

		this.showC = 0;
		this.l = items.length;    
		this.c = 0;
		this.boxT = this.box.offset().top;
		this.w_h = getW().h;	
		that.timer = 0;
		this.boxH = this.box.outerHeight(true);	
		this.loading = document.createElement("div");
		this.loading.className = "loading";
		this.loading = $(this.loading);
		$("#j-list-box").append(this.loading);
		this.maxT = this.boxT + this.boxH-this.w_h;		
		//this.create();
		this.minC = 0;//播放开始序号，由于滚动条存在，此序号为当前可见状态的最小序号
		that.showMax = that.l;//当前最大序号
		that.oldC = 0;
		that.loadOver = false;
		this.scrollFn();
		this.loadC = 1;
		
		that.timerLoading = null;
		//this.autoPlay();
		//$(window).bind("scroll",function(){that.scrollFn();});

		//返回顶部
		this.returnScroll = $("#returnScroll");
		this.returnScroll.bind("click",function(){
			listBoxScroll.wrap.animate({top:0})
			listBoxScroll.wrap2.animate({top:0})
			listBoxScroll.scrollTool.animate({top:0})
			that.returnScroll.hide();
		});
	},
	autoPlay:function(){
		var that = this;
		//that.autoPlayMode = true;
		
		that.playTimer = setInterval(function(){
			if(that.showC<that.showMax){that.showC++;}else{that.showC = that.minC;}
				that.showDetail();
		},5000);
	},
	setLoad:function(){
		var that= this;
		
		if(this.loadOver||that.loadeding)return false;
		that.loadC++;
		this.c = 0;
		//alert();
		that.loading.show();
		that.loadeding = true;
		$.getJSON("http://zy.hupu.com/event/get_by_page",{page:that.loadC},function(data){
			that.create(data);
			if(data.length<500){that.loadOver = false;}
		});
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
	create:function(t){
		var that =this;
		var  o = {};
		var d = t || items;
		for(var n = 0;n<d.length;n++){
			adItem(d[n]);
			this.c ++;
		};
		//添加元素
		function adItem(obj){
			o.id =obj["id"];
			o.name =obj["username"];
			o.img_url = obj["img_url"];
			o.hupu_uid = obj["hupu_uid"];
			o.title = obj["title"];
			o.content = obj["content"];
			o.light_count = obj["light_count"];
			o.num = obj["id"];
			o.cName = "";

			//找到自己所在的序号并高亮显示
			if((typeof curr_memory != "undefined") && o.light_count<5 && !that.initUid&&curr_memory["light_count"]<5){
				that.initUid = 1;
				o.cName = "on";
				that.initUid_timer = null;
				that.uid_c = that.c;
				o.id =curr_memory["id"];
				o.name =curr_memory["username"];
				o.img_url = curr_memory["img_url"];
				o.hupu_uid = curr_memory["hupu_uid"];
				o.title = curr_memory["title"];
				o.content = curr_memory["content"];
				o.light_count = curr_memory["light_count"];
				o.num = curr_memory["id"];
				this.c--;
				
			};

			//如果加载到自己的位置，高亮显示
			if(typeof login_uid != "undefined"&&login_uid==o.hupu_uid){
				o.cName = "hover";
			};

			var $item = $(that.item_html(o));
			var $item2 = $(that.item_html(o));
			that.box.append($item);
			that.box2.append($item2);

			//高亮显示以后隐藏
			if(that.initUid==1){ 
				that.showDetail($item2);
				that.initUid = 2;
				that.initUid_timer = setTimeout(function(){
					that.item.eq(that.uid_c).removeClass("on").addClass("hover");
					that.pop.fadeOut(200);
					//that.autoPlay();
				},3000);
			}
		};

		this.item = this.box.find("li");
		this.boxH = this.box.outerHeight(true);

		//this.maxT = this.boxT + this.boxH-this.w_h;	
		//listBoxScroll.

		if(typeof listBoxScroll != "undefined"){
			listBoxScroll.getData();
			if(that.setloadEd){
				listBoxScroll.setScroll();
			};
		}else{
			listBoxScroll = new ScrollEvent({wrapBox: "j-list-box", wrap: "j-list-wrap",wrap2: "j-list-wrap2", scrollTool: "j-list-scroll",  force: true});
		};
		
		that.setloadEd = true;
		that.loading.hide();
		that.loadeding = false;

	},
	item_html:function(o){
		var html = "";
		html+='<li class="'+o.cName+'" data-light_count="'+o.light_count+'" hupu_uid="'+o.hupu_uid+'" data-num="'+o.num+'" data-name="'+o.name+'" data-id= "'+o.id+'" data-content = "'+o.content+'"><img style="background:url('+o.img_url+') no-repeat 0 0;" src="/images/zy/blank.png" width="45" height="45" /></li>';
		return html;
	},
	showDetail:function(m){
		var me = null;

		if(m){
			me=m;
		}else{
			me = this.item.eq(this.showC);
		};

		var pos = me.offset(),l = pos.left+me.outerWidth(true),t=pos.top;
		if(l+this.pop.outerWidth(true)>getW().w){
			l = l-this.pop.outerWidth(true)-me.outerWidth(true);
		};
		if(t+this.pop.outerHeight(true)>getW().h){
			t = t-this.pop.outerHeight(true)+me.height();
		};
		
		
		this.pop.hide();
		this.pop.css({left:l,top:t});
		this.pop.fadeIn();
		
		this.pop.find("#pop_userName").html(me.attr("data-name"));
		this.pop.find(".num").html(me.attr("data-num"));
		this.pop.attr("hupu_uid",me.attr("hupu_uid"));
		this.pop.find("#pop_content").html(me.attr("data-content"));
		this.pop.find("#pop_liang span").html(me.attr("data-light_count"));
		this.item.removeClass("on");
		me.addClass("on");
	}
};


function showMsg(o){
	document.title = o;
};


//滚动类           
function ScrollEvent(o){
			var that = this;
			if(!o["wrapBox"]||!o["wrap"]||!o["scrollTool"]||!document.getElementById(o["wrapBox"])||!document.getElementById(o["wrap"])||!document.getElementById(o["scrollTool"])){
				return false;
			};
			this.wrapBox = $("#"+o["wrapBox"]);			
			this.wrap = $("#"+o["wrap"]);
			this.wrap2 = $("#"+o["wrap2"]);
			this.scrollTool = $("#"+o["scrollTool"]);
			this.force = false || o["force"];
			if(this.wrapBox.offsetHeight>=this.wrap.offsetHeight){
				this.scrollTool.hide();
				this.scrollTool.attr("d","no");
				if(!this.force)return false;
			};
			this.returnScroll = $("#returnScroll");
			this.loadTimer = null;
			that.scrollTool.bind("mousedown",function(e){
				that.scrollTool.lastY = e.clientY - that.scrollTool[0].offsetTop;	
				that.scrollTool.down = true;
				//设置拖动必备条件
				that.setMove(that.scrollTool,e);
				document.onmousemove = function(e){
					clearTimeout(that.loadTimer);
					var e = e||window.event;
					
					var posT =  e.clientY -that.scrollTool.lastY;
					if(posT>0){
						that.returnScroll.fadeIn();
						/*if(mainList.playTimer){
							clearInterval(mainList.playTimer);
							mainList.autoPlayOn = false;
							mainList.pop.hide();
						};*/
					};
					if(posT<=0){
						posT = 0;
						that.returnScroll.hide();
					}else if(posT>=that.maxScrollT){
						posT = that.maxScrollT;
						//mainList.setLoad();
						
						that.loadTimer = setTimeout(function(){
							mainList.setLoad();					
						},30);
					};
					
					//setStyle(that.scrollTool, {t:posT});
					that.scrollTool.css({top:posT});
					that.scrollMove();				
				};
				
				document.onmouseup = function(){
					that.scrollTool.stopFn();
				}	
			});
			/*
			this.wrapBox.bind("mousewheel DOMMouseScroll",function(e){
				that.wheelScroll(e);
				  e.preventDefault();
				  e.stopPropagation();
				 return false;
			});*/
		
		$(document).bind("mousewheel DOMMouseScroll",function(e){
				that.wheelScroll(e);
				  e.preventDefault();
				  e.stopPropagation();
				 return false;
		});
		that.resizeTimer = 0;		
		that.wrapTtimer = null;
		that.getData();
}
ScrollEvent.prototype = {
	getData:function(){
		var that = this;
			var client = getW();				
			this.wrapH = this.wrap.outerHeight(true);
			this.scrollToolH =this.scrollTool.outerHeight(true);
			that.maxScrollT = this.wrapBox.outerHeight(true) - that.scrollToolH;
			//滚动比例
			this.scrollScale = (this.wrapH - this.wrapBox.outerHeight(true))/that.maxScrollT;	
			this.toolT = that.scrollTool.offsetTop;	
			if(this.wrapBox.outerHeight(true)>=this.wrap.outerHeight(true)){
				this.scrollTool.hide();
				this.scrollTool.attr("d","no");
				that.wrap.css({top:0});		
				that.wrap2.css({top:0});		
			}else{
				this.scrollTool.attr("d","bl");
				this.scrollTool.show();								
			};		
		},
	setScroll:function(){
		this.scrollTool.animate({top:Math.abs(this.wrap.offset().top/this.scrollScale)},200);
	},
	wheelScroll:function(e){
			if(this.scrollTool.css("display")=="none")return false;
			var that = this;
			clearTimeout(that.loadTimer);
			if(e.wheelDelta){
				var wheelDelta = e.wheelDelta;
			}else{
				var wheelDelta =  -e.detail * 40;
			}
			var nowToolT = that.scrollTool.offset().top;		
			if(wheelDelta>0){
				nowToolT-=15;	
			}else if(wheelDelta<0){
				nowToolT+=15;	
			};
			if(nowToolT>0){
					that.returnScroll.fadeIn();
					//if(mainList.playTimer){
						//clearInterval(mainList.playTimer);
						//mainList.autoPlayOn = false;
					//	mainList.pop.hide();
					//}
			};
			if(nowToolT<=0){
				that.returnScroll.hide();
				nowToolT = 0;
			};		
			if(nowToolT >= that.maxScrollT){
				nowToolT = that.maxScrollT;
				that.loadTimer = setTimeout(function(){
					mainList.setLoad();					
				},30);
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
					that.wrap2.css({top:-b});
				},
				30);
			}else{
				that.wrap.css({top:-tNum});
				that.wrap2.css({top:-tNum});
			};
		},
		scrollMove:function(){
			var that = this;
			//that.toolT = that.scrollTool[0].offsetTop;	
			that.toolT = that.scrollTool.offset().top;	
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


function shareSina () {
			var e = {
				title: curr_memory.content+"——"+curr_memory.username+"留于 #虎扑阵营# “感谢弗格森”纪念墙。点击这里感谢弗爵爷：",
                url: "http://zy.hupu.com/memorywall/sir-retires?uid="+curr_memory.hupu_uid,
                pic: "http://i1.hoopchina.com.cn/u/1305/11/602/15669602/978f81d7big.jpeg",
                summary: "",
                type: ""
			};
               var o = 600,
                a = 500,
                n = (window.screen.availHeight - 30 - o) / 2,
                i = (window.screen.availWidth - 10 - a) / 2,
                s = encodeURIComponent(e.title),
                r = e.url,
                c = encodeURIComponent(e.pic),
                p = e.summary,
                h = e.type,
                l = "虎扑体育",
                d = "http://www.hupu.com",
                u = "@the_real_hoopchina",
                m = "abe3b0bfec0044ea852fbf1456497950",
                f = "372433789",
                b = "1937280734",
                g = r.indexOf("?") > 0 ? "&" : "?",
                v = "",
                y = "'scrollbars=no,width=" + o + ",height=" + a + ",left=" + i + ",top=" + n + ",status=no,resizable=yes'";
         window.open('http://service.weibo.com/share/share.php?title='+ encodeURIComponent(e.title) +'&url='+ e.url +"&pic="+ encodeURIComponent(e.pic) +"&appkey=372433789&ralateUid=1937280734",'newwindow','height='+a+',width='+o+',top='+n+',left='+i+',toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') 
}
