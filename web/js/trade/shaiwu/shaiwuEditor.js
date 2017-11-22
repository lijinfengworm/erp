$(function(){
    var iseditor = front_pic != "" ? true : false;
    var url = window.location.href;
	var oldie = $.browser.msie && $.browser.version < 9;
	/*判断旧版本ie兼容清除浮动的伪类*/
	oldie && $('.clearfix').append('<div class="clear"></div>');

	$.extend(window.UEDITOR_CONFIG,{
		toolbars:[[
            'fontfamily',
            'bold',
            'emotion',
            'insertimage'
       ]],
        enableAutoSave: false
	});

	var editor = UE.getEditor('editor', {
	    initialFrameWidth: 728,
	    initialFrameHeight: 260
	});	    

	editor.addListener('afterSelectionChange',function(){        
		var imgobj = $("#ueditor_0").contents().find("img");         
		$(imgobj).each(function(index, el){            
			var isloaded = false,isrc = $(this).attr("src");                                                                                     
            $(this).load(function(){ 
                if(isrc.indexOf(".gif") < 0 && $(this).width() >= 300 && $(this).height() >= 300){                     
                    $(this).addClass('userImg').css("max-width","700px");                                                  
                    if(isloaded == false){                                                                    
                        $(".coverimg").append('<div class="imgbox"><div class="coverimg-wrapper"><i></i><img src='+isrc+' /><span>设为封面</span></div></div>');           
                    }   
                    isloaded = true;
                    return false
                }
            });                                			                   
		});                            
 	});    
    editor.addListener('afterSetContent',function(){
        var imgobj = $("#ueditor_0").contents().find("img"),coverincontent = false,length = imgobj.length -1; 
        $(imgobj).each(function(index, el){
            var cover = "",isrc = $(this).attr("src");
            if(front_pic != ""){
                isrc.indexOf(front_pic) >=0 ? (cover = "cover",coverincontent=true):cover = "";                                         
            } 
            if(isrc.indexOf(".gif") < 0 && $(this).width() >= 300 && $(this).height() >= 300 && iseditor){
                $(this).addClass('userImg').css("max-width","700px");
                $(".coverimg").append('<div class="imgbox '+cover+'"><div class="coverimg-wrapper"><i></i><img src='+isrc+' /><span>设为封面</span></div></div>');            
                if(front_pic != "" && index == length && !coverincontent && $(".cover").length == 0){
                    $(".coverimg").append('<div class="imgbox cover"><div class="coverimg-wrapper"><i></i><img src='+front_pic+' /><span>设为封面</span></div></div>');
                }    
            }
        })
    })
    /*本地保存监听*/
    UE.registerUI('autosave', function(editor) {
        var timer = null,uid = null;

        editor.on('afterautosave',function(){
            clearTimeout(timer);

            timer = setTimeout(function(){
                //放入本地存储
                if(window.localStorage){
                    var date = new Date().getTime();                    
                    localStorage[url+'_date'] = date;
                    localStorage[url] = editor.getContent();
                }

                if(uid){
                    editor.trigger('hidemessage',uid);
                }
                uid = editor.trigger('showmessage',{
                    content : editor.getLang('autosave.success'),
                    timeout : 2000
                });
            },2000)
        })

    });

    /*追加内容*/
    editor.ready(function() {               
        if(window.localStorage && window.localStorage.length > 0 && localStorage[url]){      
            var content = localStorage[url];
            var date = localStorage[url+'_date'];            
            if(content &&  ( new Date().getTime() - date) < 3600*1000 ){
                editor.setContent(content, true);
            }else{
                localStorage.removeItem(url);
                localStorage.removeItem(url+'_date');
            }
        }else if("undefined" != typeof userEditorContent ){
            editor.setContent(userEditorContent, true);
        }
        $("#edui1,#edui1_iframeholder").css("z-index",80);
    });

 	$(".imgbox").live({
 		click:function(){ 			
 			$(this).addClass('cover');
 			$(this).siblings().removeClass('cover');
 		}
 	});

 	//returnTop
    var returnTop = {
        init:function(){
            this.returnTop = $("#returnTop");
            this.returnTop.hide();
            var that = this;
            $(window).bind("scroll",function(){
                var w_t = getW().s;
                w_t>800?(
                    that.returnTop.fadeIn(),
                    that.returnTop.addClass("show")
                ):(
                    that.returnTop.fadeOut(),
                    that.returnTop.removeClass("show")
                ); //返回按钮
            });
        }
    };
    returnTop.init();


    var insertUrl = new productUrl();
    insertUrl.init();
});

//用户添加商品链接
var productUrl=function(){
    this.default = {
        wrapClass:".urlwrap",
        addBtn:".addBtn",
        delBtn:".del",
        confBtn:".confirm",
        label:".urllabel",
        errarea:".shaiwuurl .error-area",
        ele:'<div class="urllabel clearfix">\
                    <input type="text" />\
                    <div class="urlBtn"><div class="del">删除</div><div class="confirm">确定</div></div>\
                </div>'
    }
}
productUrl.prototype={
    init:function(){
        $.extend(this,this.default);
        this.add();
        this.delete();
        this.confirm();
    },
    add:function(){
        var t = this;
        $(t.addBtn).on('click',function(){
            if($(t.label).length > 2){
                $(t.errarea).text("最多输入3个链接").show();
                return false
            }else{
                $(t.errarea).hide().text("");
            }
            $(t.wrapClass).append(t.ele);
        });
    },
    delete:function(){
        var t = this;
        $(t.delBtn).live("click",function(){
            $(this).parents(t.label).remove();
        });
    },
    confirm:function(){
        var t = this;
        $(this.confBtn).live("click",function(){
            var $thisinput = $(this).parent().siblings();
            if($thisinput.val() != "" ){
                $thisinput.attr("readonly","readonly").attr("class","readonly");
                $(this).hide();
                $(this).siblings().show();
                $(t.errarea).hide().text("");
            }else{
                $(t.errarea).text("请输入合法的链接").show();
            }
        });
    }
}



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