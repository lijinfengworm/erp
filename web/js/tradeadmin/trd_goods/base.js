  $(function(){
      $("#title_tag_list li").click(function(){
            $(this).addClass('on');
            $(this).siblings().removeClass("on");
            $(".tag-area-list").hide();
            $(".tag-area-list").eq($(this).index()).show();
        });

        $(document).on("click",".check-more",function(){
            if($(this).hasClass('on')){
                $(this).prev().height(120);
                $(this).removeClass('on');
                $(this).text('查看更多图片');
            }else{
                $(this).prev().height("auto");
                $(this).addClass('on');
                $(this).text('收起');
            }

        });   
        $("#iframe_click").click(function(){
           iframe_loading.init();
        });

        $("#title_tag_list_pic").one("click", function(){
           img_uploding.init();//图片上传
        });
        table_Js.init();//基本信息
        ajaxEditTag.init();//设置标签
        ajaxEditContent.init();//设置详情
        comment.init();//评论
});

var img_uploding = {//图片上传模块
    list:0,
    init:function(){
        this.getAjaxLoding();
        this.bindFun();
    },
    bindFun:function(){
      var that = this;
      $(document).on("click","#error_close,#error_close2,#error_close3",function(){
              $(".comment-layer").remove();
              $.Jui._closeMasks();
        });
      
      $(document).on("click",".photo_list .close",function(){
          var id = $(this).parents(".add-color-area").attr("idstr"),
              obj = $(this).parents(".photo_list");
          $(this).parents(".imgbox").remove();
          that.ajaxEditPic(obj,id);
      });
      
      $(document).on("click",".photo_list .allbtn .left",function(){
          var id = $(this).parents(".add-color-area").attr("idstr"),
              obj = $(this).parents(".photo_list");
          $(this).parents(".imgbox").after($(this).parents(".imgbox").prev());
          $(this).parents(".photo_list").find(".allbtn").show();
          $(this).parents(".photo_list").find(".allbtn:first").hide();
          that.ajaxEditPic(obj,id);
      });
      $(document).on("click",".photo_list .allbtn .right",function(){
          var id = $(this).parents(".add-color-area").attr("idstr"),
              obj = $(this).parents(".photo_list");
          $(this).parents(".imgbox").before($(this).parents(".imgbox").next());
          $(this).parents(".photo_list").find(".allbtn").show();
          $(this).parents(".photo_list").find(".allbtn:first").hide();
          that.ajaxEditPic(obj,id);
      });
      $(document).on("click",".photo_list .allbtn .fade",function(){
          var id = $(this).parents(".add-color-area").attr("idstr"),
              obj = $(this).parents(".photo_list");
          $(this).parents(".imgbox").prependTo($(this).parents(".photo_list"));
          $(this).parents(".photo_list").find(".allbtn").show();
          $(this).parents(".photo_list").find(".allbtn:first").hide();
          that.ajaxEditPic(obj,id);
      });

      $(document).on("click",".edit_color_style",function(){
            var id = $(this).parents(".add-color-area").attr("idstr"),
                name = $(this).prev().val();
                if($.trim(name) != ""){
                    that.ajaxEditColorStyle(goodsId,id,name);
                }else{
                    alert("请填写配色名称");
                }
      });

      $(document).on("click",".add_new_color",function(){
          var name = $(this).prev().val();
          if(goodsId == ""){
              alert("请先填写基本信息");
              return false;
          }
          if($.trim(name) != ""){
              that.ajaxEditColorStyle(goodsId,"",name);
          }else{
              alert("请填写配色名称");
          }
          
      });
      
      $(document).on("click",".edit_color_default",function(){
          var id = $(this).parents(".add-color-area").attr("idstr");
          that.ajaxEditDefaultStyle(id,$(this));
      });
      
     /*
      $(document).on("mousemove",".photo_list .imgbox",function(){
          $(this).find(".allbtn").show();
      });

      $(document).on("mouseout",".photo_list .imgbox .allbtn",function(){
         $(this).hide();
      });
  */
    },
    getAjaxLoding:function(){//获取原始数据
        var that = this;
        //that.appendHtml();
        $(".photo_list").html("");
        if(goodsId == ""){
            return false;
        }
        $.post(ajaxTag3,{goods_id:goodsId},function(data){
            var str = '';
            for(var i=0;i<data.data.length;i++){
               that.appendHtml(data.data[i]);
               if(data.data[i].value != null){
                  for(var x=0;x<data.data[i].value.length;x++){
                  str += '<div class="imgbox list">\
                  <img class="get_img_src" data_src="'+data.data[i].value[x]+'" src="'+data.data[i].value[x]+'">\
                  <div class="close">\
                  <input type="hidden" value="'+data.data[i].value[x]+'" name="pictures"><a href="javascript:void(0);">&nbsp;</a>\
                  </div>\
                  <div '+(x==0?'style="display:none;"':'')+' class="allbtn">\
                  <div class="fade">置顶</div><div class="left"></div><div class="right"></div></div></div>';
                 }
               }
               $(".photo_list").eq(i).append(str);
               str = '';
            }
            $(".pic-up-list-on").each(function(){
                $(this).find(".allbtn").eq(0).hide();
               if($(this).find(".photo_list").height() > 150){
                   $(this).next().show();
               }
            });
        },"json");
    },
    appendHtml:function(data){//添加DM
      var that = this;
       var str = '<div class="add-color-area" idStr="'+data.id+'">\
          <div class="pic-color clearfix">\
              <div class="t1">\
                  <input type="text" value="'+data.name+'" /> <a href="javascript:void(0);" class="btn edit_color_style">修改</a><a href="javascript:void(0);" class="btn edit_color_default" '+(data.is_default*1==1?'style="display:none;"':'')+'>设为默认</a>\
              </div>\
              <div class="t2">*支持批量上传</div>\
          </div>\
          <div class="pic-up-list-on pic-up-list" class="clearfix">\
              <div class="photo_list clearfix"></div>\
              <div id="divFileProgressContainer-'+that.list+'" style="display:none;"></div>\
          </div>\
          <div class="check-more" style="display:none;">查看更多图片</div>\
          <div class="up-btn-flash" atr="'+that.list+'">\
              <span id="spanButtonPlaceholder-'+that.list+'"></span>\
          </div>\
          <div class="clearfix click_falsh_btn_uplod"></div>\
      </div>';
      $(str).appendTo('.tga-area-2 .img-up-area');
      that.lodingFlash();
    },
    ajaxEditPic:function(obj,id){//编辑图片
        var that = this,
            imgArray = [];

        $(obj.find(".get_img_src")).each(function(){
            imgArray.push($(this).attr("src"));
        });
        $.post(ajaxTag3_1,{imgs:imgArray,id:id},function(data){
            if(data.code*1 == 0){
               obj.find(".allbtn").eq(0).hide();
            }else{
                 alert(data.msg); 
            }
        },"json");
    },
    ajaxEditColorStyle:function(goodsId,id,name){//管理配色
        var that = this;
        $.post(ajaxTag3_2,{goods_id:goodsId,id:id,name:name},function(data){
            if(data.code*1 == 0){
                if(id == ""){
                   that.appendHtml(data);
                }else{
                   alert("修改成功");
                }
            }else{
                 alert(data.msg); 
            }
        },"json");
    },
    ajaxEditDefaultStyle:function(id,obj){//默认配色
        var that = this;
        $.post(ajaxTag3_3,{id:id},function(data){
            if(data.code*1 == 0){
                $(".edit_color_default").show();
                obj.hide();
                alert("设置成功");
            }else{
                 alert(data.msg); 
            }
        },"json");
    },
    lodingFlash:function(){//上传图片组建
        var that = this;
        window.pic_collect='';
          window.swfu = new SWFUpload({
            // Backend Settings
            upload_url: "http://www.shihuo.cn/api/swfImageUpload",
            post_params:{"type" : "trade/goods/style"},

            // File Upload Settings
            file_size_limit : "2 MB", // 1MB
            file_types : "*.jpg;*.png;*.jpeg",
            file_types_description : "JPG Images",
            file_upload_limit : "0",
            file_dialog_start_handler:fileDialogStart,
            // Event Handler Settings - these functions as defined in Handlers.js
            //  The handlers are not part of SWFUpload but are part of my website and control how
            //  my website reacts to the SWFUpload events.
            file_queue_error_handler : fileQueueError,
            file_dialog_complete_handler : fileDialogComplete,
            upload_progress_handler : uploadProgress,
            upload_error_handler : uploadError,
            upload_success_handler : uploadSuccess,
            upload_complete_handler : uploadComplete,
            //upload_start_handler:uploadStart,
            // Button Settings
            button_placeholder_id : "spanButtonPlaceholder-"+that.list,
            button_width: 100,
            button_height: 100,
            button_image_url:"http://www.shihuo.cn/images/tradeadmin/trd_goods/xj.png",
            button_text : '',
            button_text_top_padding: 0,
            button_text_left_padding: 0,
            button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
            button_cursor: SWFUpload.CURSOR.HAND,
            swfupload_loaded_handler : loaded,  
            // Flash Settings
            flash_url : "http://www.shihuo.cn/flash/trade/swfupload.swf",
            prevent_swf_caching: true,
            custom_settings : {//自定义参数   获取：this.customSettings
              upload_target : "divFileProgressContainer-"+that.list,
              upload_list : that.list
            },
            // Debug Settings
            debug: false
          });
          that.list++;
    }
}

var ajaxEditContent = {
  init:function(){
      this.bindFun();
  },
  bindFun:function(){
     $(document).on("click","#tga_4",function(){
           var fields = UE.getEditor("js_editor_content");
           if(goodsId == ""){
                alert("请先填写基本信息");
                return false;
            }
           if(fields.getContent() == ""){
              alert("请输入内容");
           }else{
              $.post(ajaxTag5,{content:fields.getContent(),goods_id:goodsId},function(data){
                        if(data.code*1 == 0){

                             alert("保存成功");
                        }else{
                             alert(data.msg); 
                        }
              },"json");
           }
     });
  }
}

var ajaxEditTag = {
    init:function(){
        this.bindFun();
    },
    bindFun:function(){
       $(document).on("click","#tga_3",function(){
             var fields = $("#tag_form_3").serializeArray();
             fields.push({name:"goods_id",value:goodsId})
             if(goodsId == ""){
                  alert("请先填写基本信息");
                  return false;
              }
             if(fields.length == 0){
                alert("请选择");
             }else{
                $.post(ajaxTag4,fields,function(data){
                          if(data.code*1 == 0){
                               alert("保存成功");
                          }else{
                               alert(data.msg); 
                          }
                },"json");
             }
       });
    }
}

var table_Js = {//基本信息、价格信息
      init:function(){
          $.post(ajaxPriceInfoList, {goods_id:goodsId},function(data){

              var str = '<table class="players_table">\
                        <tbody>\
                        <tr class="title bg_a style-font" class="left">\
                            <td width="160">渠道名</td>\
                            <td width="160">描述</td>\
                            <td width="160">价格</td>\
                            <td width="300">链接</td>\
                            <td width="150">来源</td>\
                            <td width="150">操作</td>\
                        </tr>';
              if(data.status){
                  for(var i=0; i<data.data.info.length; i++){
                     str += '<tr class="edit" data-flag="'+i+'">\
                            <td><span class="txt">'+data.data.info[i].name+'</span><input type="text" name="name" value="'+data.data.info[i].name+'"></td>\
                            <td><span class="txt">'+data.data.info[i].description+'</span><input type="text" name="description"  value="'+data.data.info[i].description+'"></td>\
                            <td><span class="txt">'+data.data.info[i].price+'</span><input type="text"name="price" value="'+data.data.info[i].price+'"></td>\
                            <td><span class="txt">'+data.data.info[i].url+'</span><input type="text" name="url" value="'+data.data.info[i].url+'"  /></td>\
                            <td>'+data.data.init.from_type[data.data.info[i].from_type]+'<input type="hidden" name="from_type" value="'+data.data.info[i].from_type+'" /></td>';
                    if(data.data.info[i].status == 0){
                        str += '<td>\
                                    <a href="javascript:void(0);" class="table_edit color-1">编辑</a>  \
                                    <a href="javascript:void(0)" data-status="1" class="price_info_edit_status">无效</a>\
                                    <input type="hidden" name="status" value="'+data.data.info[i].status+'" class="price_info_status"> \
                                    <input type="hidden" name="id" value="'+data.data.info[i].id+'" class="price_info_id">\
                                </td>'
                    }else{
                        str += '<td>\
                                    <a href="javascript:void(0)" data-status="0" class="price_info_edit_status">有效</a>\
                                    <input type="hidden" name="status" value="'+data.data.info[i].status+'" class="price_info_status"> \
                                    <input type="hidden" name="id" value="'+data.data.info[i].id+'" class="price_info_id">\
                                </td>';
                    }
                    str +='</tr>';
                  }
              }
              str += '<tr id="add_list">\
                        <td><input type="text" id="input_val_1" placeholder="请填写" /></td>\
                        <td><input type="text" id="input_val_2" placeholder="请填写" /></td>\
                        <td><input type="text" id="input_val_3" placeholder="请填写" /></td>\
                        <td><input type="text" id="input_val_4" placeholder="请填写" style="width:350px;" /></td>\
                        <td>手动添加</td>\
                        <td><a href="javascript:void(0);" id="add_table" class="color-2">添加</a></td>\
                    </tr>\
                    </tbody>\
                </table>';
              $(str).prependTo('.tga-area-1');
          },"json");
          this.bindFun();
      },
      bindFun:function(){
          var that = this;
          $(document).on("click","#add_table",function(){
              var t = $(this);
              if(that.addCheck()){
                  $.post(ajaxPriceInfoAdd,{
                      name:$("#input_val_1").val(),
                      description:$("#input_val_2").val(),
                      price:$("#input_val_3").val(),
                      url:$("#input_val_4").val(),
                      from_type: 6,//手动添加
                      goods_id:goodsId
                  },function(msg){
                     if(msg.status){
                         var str = '<tr class="edit">\
                            <td><span class="txt">'+$("#input_val_1").val()+'</span><input type="text" name="name" value="'+$("#input_val_1").val()+'"></td>\
                            <td><span class="txt">'+$("#input_val_2").val()+'</span><input type="text" name="description" value="'+$("#input_val_2").val()+'"></td>\
                            <td><span class="txt">'+$("#input_val_3").val()+'</span><input type="text" name="price" value="'+$("#input_val_3").val()+'"></td>\
                            <td><span class="txt">'+$("#input_val_4").val()+'</span><input type="text" name="url" value="'+$("#input_val_4").val()+'"></td>\
                            <td>手动添加</td>\
                            <td>\
                                <a href="javascript:void(0);" class="table_edit">编辑</a>  <a href="#">无效</a>\
                                    <input type="hidden" name="status" value="0" class="price_info_status"> \
                                    <input type="hidden" name="id" value="'+msg.data.id+'" class="price_info_id">\
                            </td>\
                            </tr>';
                            t.parents("tr").before(str);
                            t.parents("tr").find('input').val('');
                     }else{
                           alert(msg.msg);
                     }
                  },'json');


              }else{
                  alert('请把内容填完整');
              }
          });

          $(document).on("click",".table_edit",function(){
                $(".edit").find("input").hide();
                $(".edit").find(".txt").show();
                $(this).parents("tr").find("input").show();
                //$(this).parents("tr").find("input").eq(0).focus();
                $(this).parents("tr").find(".txt").hide();
          });

          $(document).on("blur",".edit input",function(){
              var t = $(this);
              var dataInfo = 'goods_id='+goodsId;
              t.parent().parent().find('input').each(function(){
                  dataInfo +='&'+$(this).attr('name')+'='+encodeURIComponent($(this).val());
              })

              $.post(ajaxPriceInfoEdit,dataInfo,function(msg){
                  if(msg.status) {
                      //隐藏
                      t.prev().text(t.val()).show();
                      t.hide();
                  }else{
                      alert(msg.msg)
                  }
              },'json');
          });

          $("#tag_0").click(function(){
              var fields = $("#tag_form_0").serializeArray();
              $.post(ajaxTag1,fields,function(data){
                        if(data.code*1 == 0){
                             goodsId = data.goods_id;
                             alert("保存成功");
                             location.href = data.url;
                        }else{
                             alert(data.msg); 
                        }
              },"json");
          });

          //价格信息无效 有效
          $(document).on("click",".price_info_edit_status",function(){
              if(!confirm('你确定修改状态?')) return ;
              var t = $(this);
              t.parentsUntil('tr').find('.price_info_status').val($(this).attr('data-status'));

              var dataInfo = 'goods_id='+goodsId;
              t.parent().parent().find('input').each(function(){
                  dataInfo +='&'+$(this).attr('name')+'='+$(this).val();
              })

              $.post(ajaxPriceInfoEdit,dataInfo,function(msg){
                  if(msg.status){
                      if(msg.data.status == 0){
                          var  str = '  <a href="javascript:void(0);" class="table_edit color-1">编辑</a>  \
                                        <a href="javascript:void(0)" data-status="1" class="price_info_edit_status">无效</a>\
                                        <input type="hidden" name="status" value="'+msg.data.status+'" class="price_info_status"> \
                                        <input type="hidden" name="id" value="'+msg.data.id+'" class="price_info_id">';
                      } else{
                          var  str = '  <a href="javascript:void(0)" data-status="0" class="price_info_edit_status">有效</a>\
                                        <input type="hidden" name="status" value="'+msg.data.status+'" class="price_info_status"> \
                                  <input type="hidden" name="id" value="'+msg.data.id+'" class="price_info_id">';

                          //隐藏
                          t.parent().parent().find('.txt').show();
                          t.parent().parent().find('input').hide();
                      }
                      t.parent().html(str);
                  }else{
                      alert(msg.msg)
                  }
              },'json');

          })
      },
      addCheck:function(){
             var ret = true;
             for(var i=0;i<$("#add_list input").length;i++){
                 if($.trim($("#add_list input").eq(i).val()) == ""){
                   // $("#add_list input").eq(i).addClass("red");
                    ret = false;
                 }
              }
             return ret;
      }
 }

var comment = {//评论tab
    init:function(){
        this.bindFun();
    },
    bindFun:function(){
        //列表
        $('#title_comment').on('click',function(){
            if($('.tga-area-5').find('tr').length < 1){
                $.post(ajaxCommentList, {goods_id:goodsId},function(data){
                    if(data.status){
                        var str =  '<tr class="title bg_a" class="left">\
                                        <td>评论内容</td>\
                                        <td>图片</td>\
                                        <td>展示时间</td>\
                                        <td>状态</td>\
                                        <td>操作</td>\
                                    </tr>';
                        for(var i=0; i<data.data.length; i++){
                                str +='<tr class="title bg_a" class="left" data-id="'+data.data[i].id+'">';
                                if(data.data[i].type == 0){
                                    str +=' <td>'+data.data[i].content+'</td>';
                                }else{
                                    str +=' <td><a href="http://www.shihuo.cn/shaiwu/detail/'+data.data[i].supplier_id+'.html" target="_blank">'+data.data[i].content+'</a></td>';
                                }
                                str +=  '<td>';
                                for(var j=0;j<data.data[i].img_attr.length;j++){
                                    str +='<img src="'+data.data[i].img_attr[j]+'" width="50" height="50" />';
                                }
                                str +='</td> <td>'+data.data[i].updated_at+'</td>';
                                if(data.data[i].status == 1){
                                    str +='<td>正常</td>';
                                }else{
                                    str +='<td>不展示</td>';
                                }
                                str +='<td class="comment-status"><a href="javascript:;" data-status="1">展示</a> <a href="javascript:;" data-status="3">不展示</a></td>';
                                str +='</tr>';
                        }

                        $('.tga-area-5').find('tbody').append(str);
                    }else{
                        alert(data.msg)
                    }
               },'json')
            }
        })

        //更改状态
        $(document).on('click','.comment-status a',function(){
            var t = $(this);
            var status = $(this).attr('data-status');
            var id = $(this).parent().parent().attr('data-id');

            $.post(ajaxCommentEdit,{status:status,id:id},function(data){
                if(data.status){
                    if(status == 1){
                        t.parent().prev().html('正常');
                    }else{
                        t.parent().prev().html('不展示');
                    }
                }else{
                    alert(data.msg)
                }
            },'json')
        })

        //增加晒物
        $('.shawu-btn').click(function(){
            var shaiwu_id = $(this).prev().val();
            if(isNaN(shaiwu_id)){
                alert('不是数字id');
                return ;
            }

            $.post(ajaxCommentAdd,{
                shaiwu_id:shaiwu_id,
                goods_id:goodsId
            },function(data){
                if(data.status){
                    var str ='<tr class="title bg_a" class="left" data-id="'+data.data.id+'">\
                                 <td><a href="http://www.shihuo.cn/shaiwu/detail/'+data.data.supplier_id+'.html" target="_blank">'+data.data.content+'</a></td>\
                                 <td></td>\
                                 <td>'+data.data.created_at+'</td>\
                                 <td>正常</td>\
                                 <td class="comment-status"><a href="javascript:;" data-status="1">展示</a> <a href="javascript:;" data-status="3">不展示</a></td>';
                    $('.tga-area-5').find('tbody').append(str);
                }else{
                    alert(data.msg)
                }
            },'json')
        })
    }
}

 var iframe_loading = {
     fid:null,
     init:function(){
        this.loadHtml();
        this.bindFun();
     },
     loadHtml:function(){
        $('<div class="iframe-area"></div>').appendTo('body').load(ajaxRelation,function(){
            ajaxRealtionList.callback = iframe_loading.realtionGoods;
        });
     },
     bindFun:function(){
        var that = this;
        $(".iframe-area .close-box").click(function(){
              $(".iframe-area").hide();
              $(".iframe-area .html").html("");
              $.Jui._closeMasks();
        });
       
        $("#relation_box .gl").click(function(){
            $.post(ajaxRelationGoods,{from_id:goodsId,to_id:that.fid},function(data){
                 if(data.code*1 != 0){
                     alert(data.msg);
                 }else{
                     location.href = data.url;
                 }
            },"json");
        });

        $("#relation_box .tl").click(function(){
             $("#relation_box").hide();
        });
     },
     realtionGoods:function(fid,str){
          iframe_loading.fid = fid;
          $("#relation_box").show();
          $("#gl_name").html(str);
     }
 }

 $.Jui = $.Jui || {};
    $.extend($.Jui, {
        // version: "1.0",
        _$: function(a, b) {
            a.siblings().removeClass(b);
            a.addClass(b);
        },
        _showMasks: function(a) {
            var str = "<div class='body-mask' style='position:absolute; top:0; left:0; width:100%; height:" + $(document).height() + "px; background-color:#000;  z-index:9998;'></div>";
            $("body").append(str);
            $(".body-mask").css("opacity", 0);
            $(".body-mask").animate({
                "opacity": a ? a : "0.8"
            });
        },
        _closeMasks: function() {
            var close = $(".body-mask");
            close.fadeOut(function() {
                close.remove();
            });
        },
        _getpageSize: function() {
            /*
             height:parseInt($(document).height()),
             width:parseInt($(document).width())
             */
            var de = document.documentElement, arrayPageSize,
                    w = window.innerWidth || self.innerWidth || (de && de.clientWidth) || document.body.clientWidth,
                    h = window.innerHeight || self.innerHeight || (de && de.clientHeight) || document.body.clientHeight;
            arrayPageSize = [w, h]
            return arrayPageSize;
        },
        _position: function(obj) {//计算对象放置在屏幕中间的值   obj:需要计算的对象
            var left = ((this._getpageSize()[0] - parseInt(obj.outerWidth())) / 2);
            var top = ((this._getpageSize()[1] - parseInt(obj.outerHeight())) / 2) + $.Jui._getpageScroll();
            return [left, top];
        },
        _getpageScroll: function() {
            var yScrolltop;
            if (self.pageYOffset) {
                yScrolltop = self.pageYOffset;
            } else if (document.documentElement && document.documentElement.scrollTop) {
                yScrolltop = document.documentElement.scrollTop;
            } else if (document.body) {
                yScrolltop = document.body.scrollTop;
            }
            return yScrolltop;
        },
        DOC: $(document),
        WIN: $(window),
        HEAD: $(document).find("head"),
        BODY: $(document).find("body")
    });