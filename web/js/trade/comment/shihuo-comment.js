!(function($,win,doc){
   function ShihuoComment(opts){
       return new ShihuoComment.prototype.init(opts,this);
   }

	ShihuoComment.prototype = {
	   constructor:ShihuoComment,
	   allPage:16,
	   flag:false,
	   postCommentAjax:false,
	   getReplyAjax:false,
	   defaults:{
	       typeId:1,//评论类型
	       productId:1,//商品评论ID
	       pageSize:20,//每页显示条数
	       page:1,//当前第几页
	       light:true,//是否显示亮
	       replyPage:1,//评论的评论页面
	       replySize:5,//评论的评论显示条数
	       msgCommentId:0,//需要定位的评论ID
	       msgReplyId:0//需要定位的评论的评论ID
	   },
	   //初始化
	   init:function(){
	   	   this.defaults = $.extend(true,{}, this.defaults, arguments[0] || {});
	   	   this.obj = arguments[1];
	       this.getJson();
	       //this.addCommentJs(['/js/trade/ueditor/ueditor.comment.config.js','/js/trade/ueditor/ueditor.all.min.js']);//评论框架
           this.addSWFUploadJs(['http://www.shihuo.cn/js/trade/comment/swfupload.js','http://www.shihuo.cn/js/trade/comment/handlers.js']);//上传图片框架
	       this.bindFun();
	   },
	   //获取数据
	   getJson:function(){
           var that = this;
           that.lodingCss();
	   },
	   //加载CSS
	   addCSS:function(cssURL,lnkId,charset) {
		    var head = document.getElementsByTagName('head')[0],
		        linkTag = null;
			 if(!cssURL){
			     return false;
			 }
			 linkTag = document.createElement('link');
			 linkTag.setAttribute('id',(lnkId || 'shihuoComment-style'));
			 linkTag.setAttribute('rel','stylesheet');
			 linkTag.setAttribute('charset',(charset || 'utf-8'));
			 linkTag.setAttribute('type','text/css');
			 linkTag.href = cssURL;
			 head.appendChild(linkTag);
			 return linkTag;
	    },
	    //加载评论JS
	   addCommentJs:function(jsURL,lnkId,charset) {
	   	   /*
		     if(!jsURL){
			     return false;
			 }
              $.getScript(jsURL[0],function(){
              	  $.getScript(jsURL[1],function(){
              	  	   setTimeout(function(){
                             UE.getEditor('shihuo-comment-edit-frame',{
							        toolbars:[["bold"]],
							        initialContent: '',    //初始化编辑器的内容
							        initialFrameWidth: "100%",
							        initialFrameHeight: 160,
							        autoClearinitialContent:true,
							        fontsize:[10, 11, 12, 14, 16, 18, 20, 24, 36,48],
							        emotionLocalization:true,
							        maximumWords:100,
							        autoHeightEnabled:false,
							        elementPathEnabled:false,
							        //enableAutoSave:false,
							        zIndex:90
							 });
              	  	   },1500);
              	  });
		 	  });
*/
	    },
	    //加载上传图片JS
	   addSWFUploadJs:function(jsURL,lnkId,charset) {
		     if(!jsURL){
			     return false;
			 }
              $.getScript(jsURL[0],function(){
              	  $.getScript(jsURL[1],function(){
              	  	  win.pic_collect = '';
					  	  setTimeout(function(){
                               win.swfu = new SWFUpload({
							      // Backend Settings
							      upload_url: "http://www.shihuo.cn/api/swfImageUpload",
							      post_params: {"type" : "comment.admin.cover"},
							      // File Upload Settings
							      file_size_limit : "2 MB",	// 1MB
							      file_types : "*.jpg;*.png;*.jpeg",
							      file_types_description : "JPG Images",
							      file_upload_limit : "10",
							      // Event Handler Settings - these functions as defined in Handlers.js
							      //  The handlers are not part of SWFUpload but are part of my website and control how
							      //  my website reacts to the SWFUpload events.
							      file_queue_error_handler : fileQueueError,
							      file_dialog_complete_handler : fileDialogComplete,
							      upload_progress_handler : uploadProgress,
							      upload_error_handler : uploadError,
							      upload_success_handler : uploadSuccess,
							      upload_complete_handler : uploadComplete,
							      // Button Settings
							      button_placeholder_id : "spanButtonPlaceholder",
							      button_width: 85,
							      button_height: 30,
							      button_image_url:"http://www.shihuo.cn/images/trade/haitao/comment/sc.jpg",
							      button_text : '',
							      button_text_top_padding: 0,
							      button_text_left_padding: 0,
							      button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
							      button_cursor: SWFUpload.CURSOR.HAND,
							      swfupload_loaded_handler : loaded,  
							      // Flash Settings
							      flash_url : "http://www.shihuo.cn/flash/trade/swfupload.swf",
							      prevent_swf_caching: true,
							      custom_settings : {
							        upload_target : "divFileProgressContainer"
							      },
							      // Debug Settings
							      debug: false
							    });
					  	  },2000);
              	  });
		 	  });
	    },
	    //判断CSS是否加载完成
	    lodingCss:function(){
              var styleNode = this.addCSS('http://www.shihuo.cn/css/trade/shihuoComment/shihuoComment.css?v=2015033004'),
                  that = this;
			  styleOnload(styleNode,function(){
			  	  that.getComment();
			  });
	    },
	    //获取评论内容
	    getComment:function(){
	    	 var that = this;
             $.post("http://www.shihuo.cn/comment/getComment",that.defaults,function(data){
             	if(data.status*1 == 200){
             		if(!that.flag){
                          var str = '<div class="shihuo-comment-area" name="sh-comment-a">\
						          <div class="sh-com-tit clearfix">\
						                <div class="sh-com-tit-main">用户评论</div>\
						                <div class="sh-com-tit-sub"><i></i>立即评价</div>\
						          </div>\
						          <div id="shihuo-all-comment-conent"></div>\
						          <div id="shihuo-comment-edit">\
						              <div class="shihuo-comment-edit-head"><img src="'+(data.data.user.status?data.data.user.userhead:'/images/trade/haitao/comment/api_new_image.jpg')+'" /></div>\
						              <div id="shihuo-comment-edit-txt">\
						                  <div id="shihuo-comment-edit-frame"><textarea style="width:100%; height:150px; border:0px; padding:5px;"></textarea></div>\
						                  <div class="up-loding-img clearfix">\
						                         <div class="left">\
		                                              <div id="thumbnails" class="clearfix">\
							                                <div id="up-btn">\
							                                    <span id="spanButtonPlaceholder"></span>\
							                                </div>\
							                                <div id="divFileProgressContainer" style="display:none;"></div>\
							                            </div>\
						                         </div>\
						                         <ul id="shihuo-comment-img-ul"></ul>\
						                         <input type="submit" class="shihuo-comment-edit-submit" value="评论" />\
						                  </div>\
						              </div>\
						          </div>\
						    </div>';
			  	            that.obj.append(str);//默认dom
			  	            that.flag = true;
             		}
             		$("#shihuo-all-comment-conent").html('');
             		that.writeHtml(that.getDom(data.data),data.data);
             	}
             },"json");
	    },
	   //拼接评论内容DOM
	   getDom:function(arr){
	   	   var str = [],
	   	       that = this;
	   	   if(arr.light.length > 0){//亮了
                str.push('<div class="sh-com-light-area">\
				              <div class="sh-com-li-tit">这些评论亮了</div>\
				              <ul>');
		   	   for(var i=0;i<arr.light.length;i++){
	               str.push('<li>\
		                        <div class="sh-com-list-main"><img src="http://bbs.hupu.com/bbskcy/api_new_image.php?uid='+arr.light[i].user_id+'" /></div>\
		                        <div class="sh-com-list-sub">\
		                             <div class="sh-com-lis-name clearfix">\
		                                   <div class="name">'+arr.light[i].user_name+'</div>\
		                             </div>\
		                             <div class="sh-com-lis-txt">'+arr.light[i].content.replace('<a', '<a style="color:#0078b6"')+'</div>\
		                             <div class="sh-com-lis-tips clearfix">\
		                                   <div class="time-box">'+arr.light[i].created_at+'</div>\
		                                   <div class="tips-box">\
		                                       <span class="t1" commentId="'+arr.light[i].id+'"><i></i>'+arr.light[i].praise+'</span><span class="t2" commentId="'+arr.light[i].id+'"><i></i>'+arr.light[i].against+'</span><span class="t3 lightReply" commentId="'+arr.light[i].id+'" productId="'+arr.light[i].product_id+'" typeId="'+arr.light[i].type_id+'"><i></i>'+arr.light[i].reply_count+'</span>\
		                                   </div>\
		                             </div>\
		                             <div class="sh-com-lis-reply"  style="display:none;">\
		                                 <div class="reply-btn">收起</div>\
		                                 <ul></ul>\
		                                 <div class="pages-reply clearfix" style="display:none;">\
		                                    <div class="area-main" allPage="'+Math.ceil(arr.light[i].reply_count/that.defaults.replySize)+'" commentId="'+arr.light[i].id+'" productId="'+arr.light[i].product_id+'" typeId="'+arr.light[i].type_id+'">'+that.writePage(Math.ceil(arr.light[i].reply_count/that.defaults.replySize),that.defaults.replyPage)+'</div>\
		                                    <a href="javascript:void(0);" class="my-reply">我要回复</a>\
		                                 </div>\
		                                 <div class="reply-input clearfix" style="display:block;">\
		                                     <div class="textarea">\
		                                        <textarea></textarea>\
		                                     </div>\
		                                     <div class="reply-submit" commentId="'+arr.light[i].id+'" productId="'+arr.light[i].product_id+'" typeId="'+arr.light[i].type_id+'">发布</div>\
		                                 </div>\
		                             </div>\
		                        </div>\
		                   </li>');
		   	   }
		   	   str.push('</ul></div>');
	   	   }
	   	   
	   	   str.push('<div class="sh-com-all-area">\
				               <div class="sh-com-all-tit">已有'+arr.num+'条评论</div>\
				               <ul id="shihuo-comment-content">');
           for(var s=0;s<arr.res.length;s++){
           	    function reply(){
	           	     if(arr.res[s].reply.length > 0){
	           	     	 var str2 = '<div class="sh-com-lis-reply">\
	                                 <div class="reply-btn">收起</div><ul>';
			   	   	    for(var p=0;p<arr.res[s].reply.length;p++){
		                 	str2 += '<li>\
		                        <div class="sh-com-lis-reply-area"><img src="http://bbs.hupu.com/bbskcy/api_new_image.php?uid='+arr.res[s].reply[p].user_id+'" /></div>\
		                        <div class="sh-com-lis-reply-sub">\
		                              <div class="reply-name-txt">'+arr.res[s].reply[p].user_name+'：<s>'+arr.res[s].reply[p].content.replace('<a', '<a style="color:#0078b6"')+'</s></div>\
		                              <div class="reply-time clearfix">\
		                                  <div class="time-box">'+arr.res[s].reply[p].created_at+'</div>\
		                                   <div class="reply-btn-d" pid="'+arr.res[s].reply[p].id+'" name="'+arr.res[s].reply[p].user_name+'"><a href="javascript:void(0);">回复</a></div>\
		                              </div>\
		                        </div>\
		                    </li>';
		                 }
		                 str2 += '</ul><div class="pages-reply clearfix">\
	                                    <div class="area-main" allPage="'+Math.ceil(arr.res[s].reply_count/that.defaults.replySize)+'" commentId="'+arr.res[s].id+'" productId="'+arr.res[s].product_id+'" typeId="'+arr.res[s].type_id+'">'+that.writePage(Math.ceil(arr.res[s].reply_count/that.defaults.replySize),that.defaults.replyPage)+'</div>\
	                                    <a href="javascript:void(0);" class="my-reply">我要回复</a>\
	                                 </div>\
	                                 <div class="reply-input clearfix">\
	                                     <div class="textarea">\
	                                        <textarea></textarea>\
	                                     </div>\
	                                     <div class="reply-submit" commentId="'+arr.res[s].id+'" productId="'+arr.res[s].product_id+'" typeId="'+arr.res[s].type_id+'">发布</div>\
	                                 </div>\
	                             </div>';
		                 return str2;
	           	     }else{
	           	     	 var str2 = '<div class="sh-com-lis-reply"  style="display:none;">\
	                                 <div class="reply-btn">收起</div>\
	                                 <ul></ul>\
	                                 <div class="pages-reply clearfix" style="display:none;">\
	                                    <div class="area-main" allPage="'+Math.ceil(arr.res[s].reply_count/that.defaults.replySize)+'" commentId="'+arr.res[s].id+'" productId="'+arr.res[s].product_id+'" typeId="'+arr.res[s].type_id+'">'+that.writePage(Math.ceil(arr.res[s].reply_count/that.defaults.replySize),that.defaults.replyPage)+'</div>\
	                                    <a href="javascript:void(0);" class="my-reply">我要回复</a>\
	                                 </div>\
	                                 <div class="reply-input clearfix" style="display:block;">\
	                                     <div class="textarea">\
	                                        <textarea></textarea>\
	                                     </div>\
	                                     <div class="reply-submit" commentId="'+arr.res[s].id+'" productId="'+arr.res[s].product_id+'" typeId="'+arr.res[s].type_id+'">发布</div>\
	                                 </div>\
	                             </div>';
	           	     	 return str2;
	           	     }
		   	   }
		   	   function imgs_attr(){
		   	   	  if(arr.res[s].imgs_attr != null){
		   	   	  	  var imgStr = '<div class="sh-com-list-imgs clearfix">';
			   	   	  for(var n=0;n<arr.res[s].imgs_attr.length; n++){
			   	   	  	  imgStr  += '<div class="ig"><img src="'+arr.res[s].imgs_attr[n]+'?imageView2/1/w/80" /></div>';
			   	   	  }
			   	   	  imgStr += '</div><div class="sh-com-list-imgsBig"></div>';
			   	   	  return imgStr;
		   	   	  }else{
		   	   	  	  return "";
		   	   	  }
		   	   }
                str.push('<li comId="'+arr.res[s].id+'">\
	                        <div class="sh-com-list-main"><img src="http://bbs.hupu.com/bbskcy/api_new_image.php?uid='+arr.res[s].user_id+'" /></div>\
	                        <div class="sh-com-list-sub">\
	                             <div class="sh-com-lis-name clearfix">\
	                                <div class="name">'+arr.res[s].user_name+'</div><div class="floor">'+((s+1)+(this.defaults.page-1)*this.defaults.pageSize)+'楼</div>\
	                              </div>\
	                             <div class="sh-com-lis-txt">'+arr.res[s].content.replace('<a', '<a style="color:#0078b6"')+'</div>\
	                             '+imgs_attr()+'\
	                             <div class="sh-com-lis-tips clearfix">\
	                                   <div class="time-box">'+arr.res[s].created_at+'</div>\
	                                   <div class="tips-box">\
	                                       <span class="t1" commentId="'+arr.res[s].id+'"><i></i>'+arr.res[s].praise+'</span><span class="t2" commentId="'+arr.res[s].id+'"><i></i>'+arr.res[s].against+'</span><span class="t3"><i></i>'+arr.res[s].reply_count+'</span>\
	                                   </div>\
	                             </div>\
	                             '+reply()+'\
	                        </div>\
	                   </li>');
           }
           str.push('</ul></div><div class="shihuo-comment-pages lastCommentPages" allPage="'+arr.page+'">'+this.writePage(arr.page,this.defaults.page)+'</div>');
			return str.join("");
	   },
	   //翻页
	   writePage:function(apage,page,dom){
	         var allPage = apage,
	         	 islastpage = !1;
	             str = '<a class="shihuo-comment-pageUp '+(page < 2 ? "nocur" : "")+'" href="'+(dom?dom:"javascript:;")+'" '+(page > 1?'style="cursor:pointer"':'')+'>&lt; 上一页</a>';
	             if(allPage <= 1){
	             	  return "";
	             }else{	             			             	 
	             	  if(page < 6){
			              for(var i=0; i<allPage; i++){
			              	  if(i > 5){
			              	  	  break;
			              	  }			              	  
			                  str+='<a class="a1'+(i==(page-1)?" on":"")+'" href="'+(dom?dom:"javascript:;")+'">'+(i+1)+'</a>';
			              }
			              if(allPage > 6){
			              	if(allPage !== 7){
			              		str+='<span>...</span>';	
			              	}			              				              	  
		              	  	str+='<a class="a1 '+(page==allPage ? "on":"")+'" href="'+(dom?dom:"javascript:;")+'">'+allPage+'</a>'; 
			              }	             		              	     
			          }else{
			              for(var i=0; i<2; i++){
			                  str+='<a class="a1" href="'+(dom?dom:"javascript:;")+'">'+(i+1)+'</a>';
			              }
			              str+='<span>...</span>';			              		              		              			            	              		  					       
				          	if(Math.round(page*1+2) == allPage){
				          		for(var i= 0;i<4;i++){
				          			str+='<a class="a1'+(page==(allPage-3+i)?" on":"")+'" href="'+(dom?dom:"javascript:;")+'">'+(allPage-3+i)+'</a>';
				          		}	
				          	}else if(Math.round(page*1+2) > allPage){
				          		for(var i= 0;i<3;i++){
				          			str+='<a class="a1'+(page==(allPage-2+i)?" on":"")+'" href="'+(dom?dom:"javascript:;")+'">'+(allPage-2+i)+'</a>';
				          		}	
				          	}else{
				          		for(var i=0,s = -1; i<3; i++){			              	  
			                    	if(page*1+s+i > allPage){	
			                    		islastpage = 1;			                  
				                    	break;
				                	}
				                	str+='<a class="a1'+(page==(page*1+s+i)?" on":"")+'" href="'+(dom?dom:"javascript:;")+'">'+(page*1+s+i)+'</a>';
				          		}	
				          		str+='<span>...</span>';
				          		str+='<a class="a1 '+(page==allPage ? "on":"")+'" href="'+(dom?dom:"javascript:;")+'">'+allPage+'</a>';		
				          	}	
				                  				                     			          	  
			          }			                     	  
			          str+='<a class="shihuo-comment-pageDown '+(page == allPage ? "nocur" : "")+'" href="'+(dom?dom:"javascript:;")+'" '+(page == allPage?'style="cursor:auto"':'')+'>下一页 &gt;</a>';
			          str+='<span class="allpage">共'+allPage+'页</span>';
			          str+='<span class="goto">跳转到<input id="gopagenum" type="text" /><span id="submit-commentpage" class="sub">确定</span>页</span>';	
			          return str;
	             }
	          
	    },
	   //载入到页面
	   writeHtml:function(str,data){
	   	   var that = this;
	   	   that.obj.find("#shihuo-all-comment-conent").append(str);
	   	   if(typeof data.msgCommentPage == "number" && data.msgCommentPage != 0){
	   	   	    $("#shihuo-comment-content li").each(function(){
	               if($(this).attr("comid") == data.msgCommentId){
	               	    var top = $(this).offset().top - 130;
	               	    if(typeof data.msgReplyPage == "number" && data.msgReplyPage != 0){
	               	    	$(this).find(".pages-reply .a1").eq(data.msgReplyPage-1).click();
	               	    }
	               	    $(".lastCommentPages .a1").removeClass('on');
	               	    $(".lastCommentPages .a1").eq(data.msgCommentPage-1).addClass('on');
	               	    that.defaults.page = data.msgCommentPage;
	               	    that.defaults.msgCommentId = 0;
	               	    that.defaults.msgReplyId = 0;
	               	    if(that.defaults.page > 1){
	               	    	$(".lastCommentPages .shihuo-comment-pageUp").removeClass('nocur');
	               	    }
	               	    if(that.defaults.page == $(".lastCommentPages").attr("allpage")*1){
	               	    	$(".lastCommentPages .shihuo-comment-pageDown ").addClass('nocur');
	               	    }
	               	    $(window).scrollTop(top);
	               }
	           });
	   	   }
	   },
	   //提交评论
	   postComment:function(obj){
	   	    var content = $('#shihuo-comment-edit-frame textarea').val(), //UE.getEditor('shihuo-comment-edit-frame').getContent(),
	   	        imgsAttr = [],
	   	        that = this;
   	        if($("#shihuo-comment-img-ul .get_img_src").length > 0){
   	        	 $("#shihuo-comment-img-ul .get_img_src").each(function(){
                       imgsAttr.push($(this).attr("src"));  
   	        	 });
   	        }
	   	        
            $.post("http://www.shihuo.cn/comment/comment",{typeId:that.defaults.typeId,productId:that.defaults.productId,content:content,imgsAttr:imgsAttr},function(data){
                  if(data.status*1 == 200){     
                      //Math.ceil(data.data.num/that.defaults.pageSize);
                      that.defaults.page = Math.ceil(data.data.num/that.defaults.pageSize);
                      that.getComment();
                      $('#shihuo-comment-edit-frame textarea').val("");//UE.getEditor('shihuo-comment-edit-frame').setContent("");//清空编辑器
	                  $(window).scrollTop($("#shihuo-all-comment-conent").offset().top+$("#shihuo-all-comment-conent").outerHeight()-$("#shihuo-comment-content").find("li:last").outerHeight());
                      //shihuo-comment-edit-frame
                      $("#shihuo-comment-img-ul").find(".close-img").each(function(o){
		                    delPic($(this).attr("list"));
			          });

                  }else if(data.status*1 == 501){
                       commonLogin('hupu');
	              }else if(data.status*1 == 403){
	              	   errorLayer(data.msg,'<a class="tz" href="http://passport.hupu.com" target="_blank">立即跳转</a>');
	              }else{
	              	   obj.tips(data.msg);
	              }

	              that.postCommentAjax = false;
	              obj.removeClass('send');
            },"json");
	   },
	   //获取回复的回复数据
	   getReplyAll:function(arr,obj){
           var that = this;
            $.post("http://www.shihuo.cn/comment/getReply",arr,function(data){
                   if(data.status*1 == 200){
                   	   if(data.data.res.length > 0){
                   	   	   var str = '';
	                   	   for(var i=0;i<data.data.res.length;i++){
	                   	   	   str += '<li>\
					                        <div class="sh-com-lis-reply-area"><img src="http://bbs.hupu.com/bbskcy/api_new_image.php?uid='+data.data.res[i].user_id+'" /></div>\
					                        <div class="sh-com-lis-reply-sub">\
					                              <div class="reply-name-txt">'+data.data.res[i].user_name+'：<s>'+data.data.res[i].content+'</s></div>\
					                              <div class="reply-time clearfix">\
					                                  <div class="time-box">'+data.data.res[i].created_at+'</div>\
					                                   <div class="reply-btn-d" pid="'+data.data.res[i].id+'" name="'+data.data.res[i].user_name+'"><a href="javascript:void(0);">回复</a></div>\
					                              </div>\
					                        </div>\
					                    </li>';
	                   	   }
	                       obj.parents(".sh-com-lis-reply").find("ul").html(str);
	                       //$(window).scrollTop(obj.parents(".sh-com-list-sub").find(".sh-com-lis-tips").offset().top);
	                       obj.parents(".sh-com-lis-reply").find(".pages-reply .area-main").attr("allPage",Math.ceil(data.data.num*1/that.defaults.replySize));
	                       obj.parents(".sh-com-lis-reply").find(".pages-reply .area-main").html(that.writePage(Math.ceil(data.data.num*1/that.defaults.replySize),arr.replyPage));
	                       obj.parent().prev().show();
                   	   }
                   }
            },"json");
	   },
	   //回复的回复
	   getReply:function(arr,obj){
           var that = this;
            $.post("http://www.shihuo.cn/comment/reply",arr,function(data){
                   if(data.status*1 == 200){
                   	    obj.parents(".reply-input").hide();
                   	    that.getReplyAll({
                            commentId:arr.commentId,
                   	        productId:arr.productId,
                   	        replyPage:Math.ceil(data.data.num/that.defaults.replySize),
                   	        replySize:that.defaults.replySize
                   	    },obj);
                   	    obj.parents(".sh-com-list-sub").find(".sh-com-lis-tips .t3").html("<i></i>"+data.data.num);
                   	    obj.parents(".reply-input").find("textarea").val("");
                   	    that.defaults.replyPage = Math.ceil(data.data.num/that.defaults.replySize);
                   }else if(data.status*1 == 501){
                       commonLogin('hupu');
                   }else if(data.status*1 == 403){
	              	   errorLayer(data.msg,'<a class="tz" href="http://passport.hupu.com" target="_blank">立即跳转</a>');
	               }else{
                   	   obj.tips(data.msg);
                   }
                   that.getReplyAjax = false;
                   obj.removeClass('send');
            },"json");
	   },
	   //赞和踩
	   praiseAgainst:function(commentId,type,obj){
             $.post("http://www.shihuo.cn/comment/praiseOrAgainst",{commentId:commentId,productId:this.defaults.productId,type:type},function(data){
                  if(data.status*1 == 200){
                      obj.html("<i></i>"+data.data.num);
                  }else if(data.status*1 == 501){
                      commonLogin('hupu');
                  }else{
                  	 obj.tips(data.msg);
                  }
             },"json");
	   },
	   bindFun:function(){
	   	   var that = this;
	   	   //赞和踩
	   	   that.obj.find(".sh-com-lis-tips .t1").live("click",function(){
                 that.praiseAgainst($(this).attr("commentid"),"praise",$(this));
	   	   });
	   	   that.obj.find(".sh-com-lis-tips .t2").live("click",function(){
                 that.praiseAgainst($(this).attr("commentid"),"against",$(this));
	   	   });
	   	   //关闭图片上传提示
	   	   $("#error_close,#error_close2,#error_close3").live("click",function(){
                $(".comment-layer").remove();
                $.Jui._closeMasks();
          });
          //评论底部翻页
   	      $(".lastCommentPages .a1").live("click",function(){
	            var tr = $(this).html();
	            that.defaults.page = tr;
	            $(window).scrollTop(that.obj.offset().top);
	            that.getComment();
	        });

	        $(".lastCommentPages .shihuo-comment-pageUp").live("click",function(){
	            var tr = that.defaults.page*1-1;
	            if(tr > 0){
	              that.defaults.page = tr;
	              $(window).scrollTop(that.obj.offset().top);
	              that.getComment();
	            }
	        });

            $(".lastCommentPages .shihuo-comment-lastpage").live("click",function(){	
	              that.defaults.page = $(this).parent().attr("allpage");
	              $(window).scrollTop(that.obj.offset().top);
	              that.getComment();	 
	        });

	        $(".lastCommentPages .shihuo-comment-pageDown").live("click",function(){
	            var tr = that.defaults.page*1+1;
	            if(tr <= $(this).parent().attr("allpage")){
	              that.defaults.page = tr;
	              $(window).scrollTop(that.obj.offset().top);
	              that.getComment();
	            }
	        });

	        $(".lastCommentPages #submit-commentpage").live("click",function(event){	      
	        	var tr = parseInt($(".lastCommentPages #gopagenum").val()),
	        		allpagenum = $(".lastCommentPages .allpage").text().replace(/[^0-9]/ig, "");	        		
	        	if(tr > allpagenum || tr < 1 || isNaN(tr)){
	        		return false
	        	}else{
	        		that.defaults.page = tr;
	        		$(window).scrollTop(that.obj.offset().top);
	              	that.getComment();
	        	}
	        });

	        $(".lastCommentPages #gopagenum").live({
	        	"focus":function(){
	        		$(".lastCommentPages #submit-commentpage").addClass("show");
	        	},
	        	"blur":function(){	 
        			//$(".lastCommentPages #submit-commentpage").removeClass("show");       	   		
	        	}
	        });

             //提交评论
	        $(".shihuo-comment-edit-submit").live("click",function(){
	        	  if(that.postCommentAjax){
	        	  	 return false;
	        	  }
	        	  that.postCommentAjax = true;
	        	  $(this).addClass('send');
                  that.postComment($(this));
	        });
            
            //评论的回复翻页
	        function getReplyAllFun(objs){
	        	that.getReplyAll({
                    commentId:objs.parent().attr("commentId"),
           	        productId:objs.parent().attr("productId"),
           	        replyPage:that.defaults.replyPage,
           	        replySize:that.defaults.replySize
           	    },objs);
	        }	        

	        that.obj.find(".pages-reply .area-main .a1").live("click",function(){
	            var tr = $(this).html();
	            that.defaults.replyPage = tr;
	            getReplyAllFun($(this));
	        });

	        that.obj.find(".pages-reply .area-main .shihuo-comment-pageUp").live("click",function(){
           	    var tr = that.defaults.replyPage*1-1;
	            if(tr > 0){
		              that.defaults.replyPage = tr;
		              getReplyAllFun($(this));
	            }
	        });

	        that.obj.find(".pages-reply .area-main .shihuo-comment-pageDown").live("click",function(){
           	    var tr = that.defaults.replyPage*1+1;
	            if(tr <= $(this).parent().attr("allPage")){
		              that.defaults.replyPage = tr;
		              getReplyAllFun($(this));
	            }
	        });
            
            //显示评论的回复输入框
	        that.obj.find(".my-reply").live("click",function(){
	        	  var $this = $(this);
                  $(this).parent().next().show();
                  try{
                    $this.parents(".sh-com-lis-reply").find(".reply-input textarea").focus();
                  }catch(e){}
	        });
	        that.obj.find(".reply-btn-d").live("click",function(){
	        	  var str = "回复 : "+$(this).attr("name")+" ",
	        	      obj = $(this).parents(".sh-com-lis-reply").find(".reply-input");
	        	   $(this).parents(".sh-com-lis-reply").data("replayPid",$(this).attr("pid"));
                  obj.show();
                  try{
                  	obj.find("textarea").blur();
                  	obj.find("textarea").focus();
                  }catch(e){}
                    obj.find("textarea").val(str);
                  return false;
	        });
            //提交评论的回复
	        that.obj.find(".reply-submit").live("click",function(){
	        	   if(that.getReplyAjax){
	        	   	   return false;
	        	   }
	        	   that.getReplyAjax = true;
                   that.getReply({
                   	   commentId:$(this).attr("commentId"),
                   	   productId:$(this).attr("productId"),
                   	   typeId:$(this).attr("typeId"),
                   	   content:$(this).parent().find("textarea").val(),
                   	   replyId:$(this).parents(".sh-com-lis-reply").data("replayPid")
                   },$(this));
                   $(this).addClass('send');
                   $(this).parents(".sh-com-lis-reply").removeData("replayPid");
                   return false;
	        });
            //显示和隐藏评论的回复框
	        that.obj.find(".sh-com-lis-tips .t3").live("click",function(){
	        	   if($(this).hasClass('lightReply')){
	        	   	    that.getReplyAll({
		                    commentId:$(this).attr("commentId"),
		           	        productId:$(this).attr("productId"),
		           	        replyPage:that.defaults.replyPage,
		           	        replySize:that.defaults.replySize
		           	    },$(this).parents(".sh-com-lis-tips").next().find(".reply-submit"));
	        	   }
                   $(this).parents(".sh-com-list-sub").find(".sh-com-lis-reply").show();
                   return false;
	        });

	        that.obj.find(".sh-com-lis-reply .reply-btn").live("click",function(){
                   $(this).parents(".sh-com-list-sub").find(".sh-com-lis-reply").hide();
	        });

	        //显示大图片
	        that.obj.find(".sh-com-list-imgs .ig").live("click",function(){
	        	  var src = $(this).find("img").attr("src");
                  $(this).parents(".sh-com-list-sub").find(".sh-com-list-imgsBig").html("<img src='"+src.split("?")[0]+"?imageView2/2/w/370' style='display:none;' />");
                  $(this).parents(".sh-com-list-sub").find(".sh-com-list-imgsBig img").show();
	        });

	        //隐藏放大图片
	        that.obj.find(".sh-com-list-imgsBig img").live("click",function(){
                  $(this).hide("slow");
	        });

	        //立即评价锚点
	        that.obj.find(".sh-com-tit-sub").live("click",function(){
                  $(window).scrollTop($("#shihuo-comment-edit").offset().top);
	        });
	   }
	};

	ShihuoComment.prototype.init.prototype = ShihuoComment.prototype;

    //判断加载是否完成
	function styleOnload(node, callback) {
	    if (node.attachEvent) {
	      node.attachEvent('onload', callback);
	    }else {// polling for Firefox, Chrome, Safari
	      setTimeout(function() {
	        poll(node, callback);
	      }, 0); // for cache
	    }
	}
	function poll(node, callback) {
	    if (callback.isCalled) {
	      return;
	    }
	    var isLoaded = false;
	    if (/webkit/i.test(navigator.userAgent)) {//webkit
	      if (node['sheet']) {
	        isLoaded = true;
	      }
	    }else if (node['sheet']) {// for Firefox
	      try {
	        if (node['sheet'].cssRules) {
	          isLoaded = true;
	        }
	      } catch (ex) {
	        if (ex.code === 1000) {
	          isLoaded = true;
	        }
	      }
	    }
	    if (isLoaded) {
	      setTimeout(function() {
	        callback();
	      }, 1);
	    }else {
	      setTimeout(function() {
	        poll(node, callback);
	      }, 1);
	    }
	}

	  function tips(a,arr) {
	      return this.each(function() {
	          var $this = $(this),
	              str = '<div class="tips_layer" style="position: absolute; border-radius:5px; background-color:#ff6600; display:none; z-index:991">\
	                <div class="tips-text" style="padding:2px 5px; line-height:18px; color:#fff;">'+a+'</div>\
	                <div class="diamond"></div>\
	            </div>';
	           if($(".tips_layer")){
	              $(".tips_layer").remove();
	           }
	          $(str).appendTo("body");
	          var $tips_text = $(".tips-text"),
	                  $tips_layer = $(".tips_layer");
	          if(arr){
	             $tips_layer.css({
	                "top": arr.top,
	                "left": arr.left
	              }).show();
	          }else{
	            $tips_layer.css({
	              "top": $this.offset().top - parseInt($this.height())-10,
	              "left": $this.offset().left
	            }).show();
	          }
	          setTimeout(function(){
	             $tips_layer.remove();
	          },2000);
	      });
	  }

	$.fn.extend({
	    shihuoComment:ShihuoComment,
	    tips:tips
	});
})(jQuery,window,document);