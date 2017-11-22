/**
 * Created by PhpStorm.
 * User: jiangyanghe
 * DATA: 2015/12/21
 */
define(function(){
    "use strict";
    var commentList = {
        defaults:{
            pages:1,
            is_img:0//全部   1,有图列表
        },
        ajaxBefor:false,
        ajaxLoding:false,
        init:function(){
            this.getAjax();
            this.bindClick();
        },
        getAjax:function(){
            var that = this;
            if(that.ajaxLoding){
                return false;
            }
            that.ajaxLoding = true;
            $.post("//www.kaluli.com/item/ajaxComment",{page:this.defaults.pages,is_img:this.defaults.is_img,product_id:product_id},function(res){
                var data = "string" !== typeof res ? res : $.parseJSON(res);
                if(data.status){
                    that.allPage = data.msg.num;
                    that.writePage();
                    that.writrTxt(data.msg.res);
                }else{
                    $(".comment-list,.comment-pages").hide();
                }
                if(data.msg.res == ""){
                    $(".none-comment").show();
                }else{
                    $(".none-comment").hide();
                }
                that.ajaxLoding = false;
            },"json");
        },
        writrTxt:function(data){
            var str = [],
                tag="",
                info="",
                img="",
                tpl = '',
                x,
                y,
                z;
            for(var i=0; i<data.length; i++){
                for(x in data[i].tags_attr){
                    tag += '<span>'+data[i].tags_attr[x]+'</span>';
                }
                for(y in data[i].imgs){
                    img += '<div class="imgs">\
                           <img class="zoom" src="'+data[i].imgs[y]+'?imageView2/1/w/50" / data_src="'+data[i].imgs[y]+'">\
                       </div>';
                }
                for(z in data[i].attr){
                    info += ''+z+' : '+data[i].attr[z]+'<br/>';
                }

                if(data[i].reply.length > 2){
                    tpl = '<div class="kefuAnswer"><i class="iconfont icon-customer"></i>客服回复:'+data[i].reply+'</div>';
                }else{
                    tpl=''
                }

                str.push('<li class="clearfix">\
                      <div class="comment-main">\
                          <img src="'+data[i].user_head+'" />\
                          <p>'+data[i].user_name+'</p>\
                      </div>\
                      <div class="comment-sub">\
                          <div class="txt">'+data[i].content+'</div>\
                          <div class="pic clearfix">'+img+'</div>\
                          <div class="picBig"></div>\
                          '+ tpl +'\
                      </div>\
                      <div class="goods-data">\
                        <div class="data">'+info+data[i].created_at+'</div>\
                      </div>\
                 </li>');

                tag="";
                info="";
                img="";
            }
            $(".comment-list").html(str.join("")).show();
        },
        writePage:function(){
            var allPage = this.allPage,
                str = '<a class="pre" href="#comment">&lt; 上一页</a>';
            if(allPage == 1 || allPage==0){
                $(".comment-pages").hide();
                return false;
            }else{
                $(".comment-list,.comment-pages").show();
            }

            if(allPage < 6 || this.defaults.pages<6){
                if(allPage < 6){
                    for(var i=0; i<allPage; i++){
                        str+='<a class="a1'+(i==(this.defaults.pages-1)?" on":"")+'" href="#comment">'+(i+1)+'</a>';
                    }
                }else{
                    for(var i=0; i<6; i++){
                        str+='<a class="a1'+(i==(this.defaults.pages-1)?" on":"")+'" href="#comment">'+(i+1)+'</a>';
                    }
                }

            }else{
                for(var i=0; i<2; i++){
                    str+='<a class="a1" href="#comment">'+(i+1)+'</a>';
                }
                str+='<span>...</span>';

                for(var i=0; i<4; i++){
                    if(this.defaults.pages-2+i > allPage){
                        break;
                    }
                    str+='<a class="a1'+(this.defaults.pages==(this.defaults.pages-2+i)?" on":"")+'" href="#comment">'+(this.defaults.pages-2+i)+'</a>';
                }
            }
            str+='<a class="next" href="#comment">下一页 &gt;</a>';
            $(".comment-pages").html(str);
            if(this.defaults.pages > 1){
                $(".comment-pages .pre").css({
                    cursor:"pointer"
                });
            }
            if(this.defaults.pages == allPage){
                $(".comment-pages .next").css({
                    cursor:"auto"
                });
            }
        },
        bindClick:function(){
            var that = this;
            $(".comment-pages .a1").live("click",function(){
                var tr = $(this).html();
                that.defaults.pages = tr;
                that.getAjax();
                $(window).scrollTop($("#offset_post_2").offset().top);
            });

            $(".comment-pages .pre").live("click",function(){
                var tr = that.defaults.pages*1-1;
                if(tr > 0){
                    that.defaults.pages = tr;
                    that.getAjax();
                    $(window).scrollTop($("#offset_post_2").offset().top);
                }
            });

            $(".comment-pages .next").live("click",function(){
                var tr = that.defaults.pages*1+1;
                if(tr <= that.allPage){
                    that.defaults.pages = tr;
                    that.getAjax();
                    $(window).scrollTop($("#offset_post_2").offset().top);
                }
            });

            $("input[name='comPic']").change(function(){
                that.defaults.is_img = $(this).attr("inf")*1;
                that.defaults.pages = 1;
                that.getAjax();
            });

            $(".comment-sub").find(".imgs").live("click",function(){
                $('.pic_tag').remove();
                var link = $(this).find("img").attr("data_src")+'?imageView2/1/w/350';
                $(this).append('<img class="pic_tag" src="//kaluli.hoopchina.com.cn/images/kaluli/product/pic_tag.png">');
                $(this).parent(".pic").next().html("<img src='"+link+"' />");
            });

            $(".comment-sub").find(".picBig img").live("click",function(){
                $(this).parent(".picBig").html("");
                $('.pic_tag').remove();

            });
        }
    };

    return commentList;

});