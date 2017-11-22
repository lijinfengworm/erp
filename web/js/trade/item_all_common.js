/**
 * Created by JetBrains WebStorm.
 * User: k
 * Date: 12-6-1
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
    obj.style.top = win.s + (win.h / 2) - parseInt(getStyle(obj).height) * 0.5 + "px";
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
    //this.mask.style.position = "absolute";
    //this.mask.style.left = "0px";
    //this.mask.style.top = "0px";
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


var selectBox = {
    init:function(){
        this.li = $("#selectBox .selectList");
        this.li.hover(function(){
             $(this).find("ul").addClass("hover");
        },function(){
             $(this).find("ul").removeClass("hover");
        });
        this.li_chima = $(".chima_li");
        this.li_chima.bind("mouseover",function(){
            $("#chimaName").html($(this).html());
            var sex = $(this).attr("data");
            $("#chimaSelectBox").show();
            $("#chimaSelectBox .hc").hide();
            $("#hc_" + sex).show();
        });
        $("#chimaBox").bind("mouseleave",function(){
           $("#chimaSelectBox").hide();
        });
    }
};



//瀑布流

/*  
  	name:产品名字
	price:价格
  	url:链接地址
	img_url:图片地址
	is_verified:是否鉴定
	Brand:品牌对象
	Brand.BrandName:品牌名字
	Brand.BrandUrl:品牌名字
	Category:分类对象
  	styles:风格数组
	detail_url:详细页地址
 */
var mainList = {
	init:function(){
		var that = this;			
		this.box = $("#mianWrap");
		this.li = $("#mianWrap .list");
		this.item = this.box.find(".item");
		this.item.hover(function(){$(this).addClass("item_hover");},function(){$(this).removeClass("item_hover");});
		this.l = items.length;    
		this.c = 0;
		this.boxT = this.box.offset().top;
		this.w_h = getW().h;	
		that.timer = 0;
		this.boxH = this.box.outerHeight(true);	
		that.dd = 0;	
		this.tip = null;	
		
		this.loading = document.createElement("div");
		this.loading.className = "loading";
		this.loading = $(this.loading);
		this.box.after(this.loading);
		
		this.li.find(".btnLike").live("click",function(){
            if(!user_id)
            {
                commonLogin(); return false;
            }
			var item = $(this).parents('.item');
            var shoe_id = item.attr('shoe_id'),all_id = item.attr('all_id');
            if(shoe_id > 0)
            {
                var like_type = 'shoe';
                var like_id = shoe_id;
            }else{
                var like_type = 'all';
                var like_id = all_id;
            }
            var this_btnLike = $(this);
            $.post(addlikeUrl,{like_type : like_type,id : like_id}, function(data){
                if(parseInt(data.status) == 1)
                {
                    var pos = {l:this_btnLike.find('span').offset().left,t:this_btnLike.find('span').offset().top};			
                    if(!that.tip){
                        that.tip = document.createElement("span");
                        that.tip.className="like_tip";
                        that.tip.innerHTML = " +1 ";
                        document.body.appendChild(that.tip);
                    };
                    $(that.tip).css({left:pos["l"],top:pos["t"]-10,opacity:1});			
                    //document.title = pos["t"]+50;
                    $(that.tip).show();
                    $(that.tip).animate({top:pos["t"]-20},250);
                    $(that.tip).fadeOut(250);   
                    var newlikeNum = (this_btnLike.find('span').html())?this_btnLike.find('span').html():0;
                    this_btnLike.find('span').html(parseInt(newlikeNum)+1);
                    setTimeout(function(){this_btnLike.attr('class','btnLike_ok')},400);
                }
                if(parseInt(data.status) == -5)
                {
                    setTimeout(function(){this_btnLike.attr('class','btnLike_ok')},400);
                }
                if(parseInt(data.status) == -2)
                {
                    commonLogin(); return false;
                }
            }, 'json')
			return false;
		});
		this.maxT = this.boxT + this.boxH-this.w_h;		
		//this.create();
		that.loadOver = false;
		this.scrollFn();
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
		for(var n = 1;n<=3;n++){
			for(var i = 0;i<4;i++){				
				if(this.c>=this.l) {
					if(!that.loadOver){
						var boxH = this.box.outerHeight(true);
						for(var t = 0;t<4;t++){
							var bgHtml = document.createElement("div");
							bgHtml.style.background = "#f0f0f0";
							bgHtml.style.height = boxH-this.li.eq(t).outerHeight(true) + "px";							
							this.li.eq(t).append(bgHtml);
						};			
							
						that.loadOver = true;
					};					
					break;					
					return false;
				};        
                o.id = items[this.c]["id"];
                o.shoe_id = items[this.c]["shoe_id"];
				o.name = items[this.c]["title"];
				o.price = items[this.c]["price"];
				o.url = items[this.c]["detail_url"];
				o.img_url = items[this.c]["img_url"];
				o.is_verified = items[this.c]["is_verified"];
				o.brand_name = items[this.c]["brand_name"];			
				o.brand_id = items[this.c]["brand_id"];			
				o.title = items[this.c]["title"];			
				o.category_name =items[this.c]["category_name"];
				o.styles = items[this.c]["styles"];
				o.edit_url = items[this.c]['edit_url'];
				o.give_money = items[this.c]['give_money'];
				o.freight_payer  = items[this.c]['freight_payer'];
				o.sold_count = +items[this.c]['sold_count'];
                o.like_count = items[this.c]['like_count'];
                o.like = items[this.c]['like'];
				
				var $item = $(this.item_html(o));
				this.li.eq(i).append($item);
				$item.hover(function(){$(this).addClass("item_hover");},function(){$(this).removeClass("item_hover");});
				addTrackEvent($item);
					this.c ++;				
				};
		};
		this.boxH = this.box.outerHeight(true);
		this.maxT = this.boxT + this.boxH-this.w_h;		
		that.loading.hide();
	},
	item_html:function(o){
		var price_int = o["price"].indexOf(".");
		var html = "";
		html+='<div class="item" all_id="'+o.id+'" shoe_id="'+(o.shoe_id ? o.shoe_id : 0)+'" >';
        html+='<div class="inner">';
        html+='  <div class="photo"> <a data-track="index-pic-'+ o.name + '-' + (o.brand_name ? o.brand_name : '') + '-' + o.is_verified + '" target="_blank" href="'+o["url"]+'"> <img src="'+o["img_url"]+'" class="errorPic1" alt="' + o.title + '" title="'+o.title+' "/></a> ';
		if(o["is_verified"]){
			html+='<img src="/images/trade/icon_jd.png" alt="经虎扑鉴定团鉴定为正品" title="经虎扑鉴定团鉴定为正品" class="jd" />';
		};
		html+='<div class="jg">¥<span>'+o["price"].substring(0,price_int)+'</span>'+o["price"].substring(price_int)+'</div>';
		html+='<div class="bg"></div>';
		if(o.edit_url){
		  html+='<a target="_blank" href="' + o["edit_url"] + '" class="bj">编辑</a>';
		};	
	if(hasCredential&&o.give_money != "0.00"){
		 html+='<div class="give_money">佣金：'+o.give_money+'</div>';
	};    
    html+='</div><div class="info">';
    html+='<div class="pinpai">';
	html+='<a class="'+(o.like?'btnLike_ok':'btnLike')+'" href="javascript:;"><em>收藏<span>'+(o.like_count > 0 ? o.like_count : '')+'</span></em></a>';	
    html+='    <div class="btnBox">';
	if(o.freight_payer==1){
		html+='<img src="/images/trade/transparent.gif" alt="包邮" title="包邮" width="51" height="23" class="icon_by fl" />';
	};
    html+='<em class="c_000">' + o.name + '</em>'; 
    html+='</div>';
    html+='  </div>';
    html+='</div>';
	html+=' </div>';    
		return html;
	}	
};

//发布成功
function submitRight(){
	var t = 2;
	var timer = setInterval(function(){
		t--;
		if(t===0){
			clearInterval(timer);
            window.location.href= homepage_url;
		};
	},1000);
	openBox.show('fbzg_box');	
  openBox.mask.style.display = "none";
};

$(function(){
  $("a").focus(function(){
    $(this).blur();    
  });  
  
  addTrackEvent();
  addEventForFeedbackBtn();
  addEventForSendFeedbackBtn();
});

function addTrackEvent(context)
{
    if (!context)
    {
      $context = $(document);
    }
    else
    {
      $context = $(context);
    }
    
    if ($('#no-track').length)
    {
      return;        
    }    
    
    $('[data-track]', $context).click(
      function (e)
      {                      
        e.stopPropagation();
        
        var keyTemplate = $(this).data('track');
        var key = parseKeywords(keyTemplate, $(this));                                

        commonClickLog(key, 'shihuo');
        commonGa(key);
      }
    );
    
    $('[data-track-block]', $context).each(
      function (index, element)
      {                         
        var $element = $(element);
        var keyTemplate = $element.data('track-block');                
                
        $('a', $element).click(
          function (e)
          {
            e.stopPropagation();
            
            var key = parseKeywords(keyTemplate, $(this));
            
            commonClickLog(key, 'shihuo');
            commonGa(key);
          }
        );        
      }
    );    
}

function parseKeywords(key, element)
{  
  var $element = $(element);
  var innerText = $.trim($element.text().replace(' ', ''));  
  var innerText = key.replace('{{innerText}}', innerText);   
  
  return innerText;
}

//returnTop
var returnTop = {
	init:function(){
		this.returnTop = $("#returnTop");
		this.returnTop.hide();
		var that = this;
		$(window).bind("scroll",function(){
			var w_t = getW().s;
			w_t>0?that.returnTop.fadeIn():that.returnTop.fadeOut(); //返回按钮
		});
	}
};

//固定选项栏
var fixedSelect = {
	init:function(){
		var that = this;
		this.pos = $("#pos");
		this.selectWrap = $("#selectWrap");
		this.selectBox = $("#selectBox");	
		
		this.selectInner = $("#selectInner");
		this.btn = $("#pos_ts");
		this.pos_t = this.pos.offset().top;
		this.h_no = false;
		 
		$(window).bind("scroll",function(){
			//clearTimeout(that.timer);
			//that.timer = setTimeout(function(){that.scrollFn();},100);
			that.scrollFn();				
		});	
		that.timer2 = 0;
		this.isShow = false; 
		
	},
	scrollFn:function(){
		var w_t = getW().s;
		var that = this;
		if(!this.h_no){
			this.selectWrap.height(this.selectWrap.outerHeight(true));
			this.h_no = true;
		};
		
		if(w_t>this.pos_t){	
			that.selectBox.hide();		
			this.isShow = true;		
			this.selectInner.addClass("pos_fixed");
			this.btn.bind("click",function(){
				if(that.isShow){
					clearTimeout(that.timer2);
					//that.timer2 = setTimeout(function(){				
						that.selectBox.slideDown(200);	
					//},500);
				};
			});
			this.btn.bind("mouseover",function(){
				if(that.isShow){
					clearTimeout(that.timer2);
						that.timer2 = setTimeout(function(){				
						that.selectBox.slideDown(200);	
					},250);
				};
			});		
			that.selectWrap.bind("mouseleave",function(){
				if(that.isShow){
					clearTimeout(that.timer2);
					that.timer2 = setTimeout(function(){
						that.selectBox.slideUp(150);	
					},250);				
				};
			});				
		}else{
			this.isShow = false;
			this.selectInner.removeClass("pos_fixed");
			clearTimeout(that.timer2);
			//that.selectBox.stop();
			that.selectBox.show();	
		};
	}
}

function addEventForFeedbackBtn()
{
  $('#btn-feedback').click(
    function (e)
    {
      e.preventDefault();
      e.stopPropagation();
      
      openBox.show('fk_openBox');  
    }
  );
   $('#btn-feedback2').click(
    function (e)
    {
      e.preventDefault();
      e.stopPropagation();
      
      openBox.show('fk_openBox2');  
    }
  );
}

function addEventForSendFeedbackBtn()
{
  $('#btn-send-feedback').click(
    function (e)
    {
      e.stopPropagation();
      e.preventDefault();
            
      var content = $('#content').val();
      var placeholder = $('#content').attr('placeholder');
      
      if (!content || content == placeholder)
      {
        alert('内容不能为空');
        
        return;          
      }            
            
      var email = $.trim($('#email').val());
      var placeholder = $('#email').attr('placeholder');

      if (email && email != placeholder)
      {
        var pattern = /^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
        
        var validity = pattern.test(email);
        
        if (!validity)
        {
          alert('邮箱地址不正确');
          
          return;
        }      
      }
      
      feedback();
    }
  );
}

function feedback()
{
  $.ajax($('#url-feedback').val(),    
    {
      data: 
      {
        email: $('#email').val() == $('#email').attr('placeholder') ? '' : $('#email').val(),
        content: $('#content').val() == $('#content').attr('placeholder') ? '': $('#content').val()
      },
      dataType: 'json',
      success: function (response)
      {
        alert(response.status.message);   
        
        if (response.status.code == 200)
        {
          openBox.hide('fk_openBox');
          
          $('#content').val('');
          $('#email').val('');
        }
      }
    }
  );  
}


//搜索下拉
var searchLink = {
		init:function(id){
			var that = this;
			if(!id){return false;}			
			this.input = $(id);		
			//this.input.val();
			this.zbqAboutSearch = $("#zbq-aboutSearch");
			this.zbqSearchSubmit = $("#zbq-searchSubmit");
			if(this.zbqSearchSubmit.val()==""){
				this.zbqSearchSubmit.val("产品名、品牌");				
			};
			this.zbqAboutSearch_inner = this.zbqAboutSearch.find("ul");
			this.l = 0;
			this.c = 0;
			this.old = [];
			
			this.input.focus(function(){				
				$(document).keyup(function(event){
					//alert(that.input.val());
					var event  = event || window.event;
					switch(event.keyCode){
						case 37: //左
							//alert("左");
						break;
						case 38: //上
							if(that.l>=1){
								that.c >1 ? that.c-- : that.c = that.l;
								that.setOn();
							};					
						break;
						case 39: //右
							//alert("右");
						break;
						case 40: //下
							if(that.l>=1){
								that.c < that.l ? that.c++ : that.c = 0;
								that.setOn();	
							};
						break;
						case 13 ://回车
							that.keyDownFn();							
							if(that.l>=1){	
								if($.trim(that.zbqSearchSubmit.val())!=""){
									location.href = that.zbqAboutSearch_li.eq(that.c-1).attr("href");
								};								
							};
						break;
						default:							
							that.keyDownFn();
						break;
					};	
				});
			});		
			this.input.blur(function(){
				clearInterval(that.timer);
				$(document).unbind("keyup");
			});	
		},
		keyDownFn:function(){
			var that = this;
			var text = that.input.val();				
				for(var i = 0;i<that.old.length;i++){
					if(text == that.old[i]["name"]){
						searchAbout = that.old[i]["text"];
						that.createHtml();								
						return false;
						break;
					};
				};
				if($.trim(text).length){	
					$.getJSON("/all/search", {q:text},function(data){									
						searchAbout = data;		
						var o = {};								
						o["name"] = text;
						o["text"] = searchAbout;	
						that.old.push(o);
						that.createHtml();
					});
				}else{
					that.zbqAboutSearch.hide();
				};	
		},
		setOn:function(){
			var that = this;
			that.zbqAboutSearch_li.removeClass("on");
			that.zbqAboutSearch_li.eq(that.c-1).addClass("on");		
		},
		bindA:function(){
			var that = this;
			this.zbqAboutSearch_li = this.zbqAboutSearch_inner.find("a");
			this.zbqAboutSearch_li.hover(function(){					
					that.zbqAboutSearch_li.removeClass("on");
					$(this).addClass("on");
				},function(){
					$(this).removeClass("on");
			});
		},
		createHtml:function(){
			var htmlText = "";			
			var brand_results = searchAbout["brand_results"],item_results = searchAbout["item_results"];
			var brand_results_l = brand_results.length || 0;
			var item_results_l = item_results.length || 0;
			this.l = brand_results_l + item_results_l;		
			for(var i = 0;i<brand_results_l;i++){
				var o = brand_results[i];
				htmlText+='<li><a href="'+o.link+'" data-track="search-{{'+o.name+'}}"><span class="text">'+o.name+'</span><span class="num">约'+o.count+'条结果</span></a></li>';
			};
			
			for(var t = 0;t<item_results_l;t++){
				var z = item_results[t];
				htmlText+='<li><a href="'+z.link+'" data-track="search-contains-{{'+z.name+'}}"><span class="text">'+z.name+'</span><span class="num">约'+z.count+'条结果</span></a></li>';
			};
		
			this.zbqAboutSearch_inner.html(htmlText);
			//var li = this.zb
			addTrackEvent(this.zbqAboutSearch_inner);
			if(this.l>0){
				this.zbqAboutSearch.show();
				this.bindA();
			}else{
				this.zbqAboutSearch.hide();			
			};			
		}
}


function showMsg(o){
	document.title = o;
};




































