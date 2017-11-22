var _ajaxG     = true;
var _pageSize  = 6; //每页个数
var _PageTotal = parseInt($("#js-box").attr("data-pageTotal")/6)+1;//总页数
var _pageNum   = 1;
var _toName    = "";
var _sendCom   = 0;
var _isLiang   = 0;
$(function(){
    //底部晒物点赞和评论
    $("#footer .lv,#footer .wa").on("click",function(){
        var icon = $(this).find("i"),
        num = $(this).find("span"),
        _typeN = $(this).parent(".footer").find("."+$(this).attr("data-typeN"));
        var type = $(this).attr("data-type");

        var ajaxLink  = "http://m.shihuo.cn/shaiwu/supportAgainst";
        var _dataStr  = {"id": productId,"type":type};
        var that = this;
        $.post(ajaxLink,_dataStr,function(data) {
            if(data.status == 0){
                if(icon.hasClass("on")){
                    icon.removeClass("on");
                    num.html(parseInt(num.text())-1);
                    $(that).find(".num").html("-1").addClass("move");
                }else{
                    icon.addClass("on");
                    num.html(parseInt(num.text())+1);
                    $(that).find(".num").html("+1").addClass("add");
                    if(_typeN.find("i").hasClass('on')){
                        _typeN.find('span').text(_typeN.find("span").text()-1);
                        _typeN.find("i").removeClass('on');
                    }
                }
                setTimeout(function(){
                    $(".num").removeClass("add");
                    $(".num").removeClass("move");
                }, 1000);
            }else if(data.status == 1){
                $.ui.tips("请先登录",function(){
                    location.href = $.ui.loginUrl();
                });
                return false;
            }else{
                $.ui.tips(data.msg);
            }
        },"json");
        
     });
    //这些评论亮了（更多）
    $("#comment-liang .say>.more>a").live("click",function(){
        var div_reply  = $(this).parent(".more").next(".reply");
        div_reply.show();
        $(this).parent(".more").hide();

        var curpage   = 1;
        var total     = $(this).attr("data-total");
        var totalPage = parseInt(total/5)+1;
        var commentId = $(this).attr("data-commentId");
        var ajaxLink = "http://m.shihuo.cn/shaiwu/replyAjax";
        var _dataStr = {"commentId": commentId,"replyPage": curpage,"productId": productId,"replySize":5};
        var that = this;
        $.post(ajaxLink,_dataStr,function(data) {
         if(data.status == 0){
            var _html ="";
            $.each(data.data.reply,function(index,val){
                _html += '<li>\
                        <div class="user">\
                            <div class="face"><img src="'+val.user_avatar+'"></div>\
                            <a class="text toAndCom"  href="javascript:void(0);" data-commentid="'+commentId+'" data-name="'+val.user_name+'">\
                                <div><span>'+val.user_name+'</span>'+val.content+'</div>\
                                <p class="time">'+val.created_at+'</p>\
                            </a>\
                        </div>\
                    </li>';
            });
            div_reply.find("ul").append(_html);
            var num = parseInt(total-(curpage*5));
            if(num>0){
                curpage ++;
                div_reply.find(".more").find('a').text("还有"+num+"条回复"); 
                $(that).attr("data-curpage",curpage);
            }else{
                div_reply.find(".more").hide();
            }
         }
        },"json");

    });
    //这些评论亮了  显示更多
    $("#comment-liang .reply .more>a").live("click",function(){
        var curpage   = $(this).attr("data-curpage");
        var total     = $(this).attr("data-total");
        var totalPage = parseInt(total/5)+1;
        
        var commentId = $(this).attr("data-commentId");
        var ajaxLink = "http://m.shihuo.cn/shaiwu/replyAjax";
        var _dataStr = {"commentId": commentId,"replyPage": curpage,"productId": productId,"replySize":5};
        var that = this;
        $.post(ajaxLink,_dataStr,function(data) {
         if(data.status == 0){
            var _html ="";
            $.each(data.data.reply,function(index,val){
                _html += '<li>\
                        <div class="user">\
                            <div class="face"><img src="'+val.user_avatar+'"></div>\
                            <a class="text toAndCom"  href="javascript:void(0);" data-commentid="'+commentId+'" data-name="'+val.user_name+'">\
                                <div><span>'+val.user_name+'</span>'+val.content+'</div>\
                                <p class="time">'+val.created_at+'</p>\
                            </a>\
                        </div>\
                    </li>';
            });
            $(that).parent().prev("ul").append(_html);
            var num = parseInt(total-(curpage*5));
            if(num>0){
                curpage ++;
                $(that).text("还有"+num+"条回复"); 
                $(that).attr("data-curpage",curpage);
            }else{
                $(that).parent().hide();
            }
         }
        },"json");
     });


    $("#footer .pl").on("click",function(){
       var isIOS = (/iphone|ipad/gi).test(navigator.appVersion);
       if(isIOS){
            var offset = $("#comment").offset().top-100;
            $(window).scrollTop(offset);
       }
       $("#comment").focus();
    });
    $(".toComment").live("click",function(){ 
        if($(this).parents("dl").attr("id") == "comment-liang"){
             _isLiang = 1;
        }else{
            _isLiang = 0;
        }
        var isIOS = (/iphone|ipad/gi).test(navigator.appVersion);
        if(isIOS){
            var offset = $("#comment").offset().top-100;
            $(window).scrollTop(offset);
        }
        $("#comment").focus();
        $("#comment").val("");
        $("#saveCom").attr("data-commentId",$(this).attr("data-commentId"));
        _toName = "";
    }); 
    $(".toAndCom").live("click",function(){
        if($(this).parents("dl").attr("id") == "comment-liang"){
             _isLiang = 1;
        }else{
            _isLiang = 0;
        }
        var isIOS = (/iphone|ipad/gi).test(navigator.appVersion);
        if(isIOS){
            var offset = $("#comment").offset().top-100;
            $(window).scrollTop(offset);
        }
        $("#comment").focus();
        $("#saveCom").attr("data-commentId",$(this).attr("data-commentId"));
        _toName = "回复 : "+$(this).attr("data-name")+" ";
        $("#comment").val(_toName);
    });
    $("#saveCom").on("click",function(){
        var content = $("#comment").val();
        var comment = $(this).attr("data-commentId");
        if($.trim(content).length > _toName.length){
            if(comment){
                var ajaxLink = "http://m.shihuo.cn/shaiwu/commentReply";
                var _dataStr = {"commentId": comment,"content": content,"productId": productId};
            }else{
                var ajaxLink = "http://m.shihuo.cn/shaiwu/addComment";
                var _dataStr = {"content": content,"productId": productId};
            }
          $.post(ajaxLink,_dataStr,function(data) {
            if(data.status == 0){
                 $(".sendOK").css("top",$(document).height/2);
                 $(".sendOK").show();
                 $("#comment").val("");
                 setTimeout(function(){
                    $(".sendOK").hide();
                 }, 2000);
                 _sendCom  = 1;
                 _pageNum  = 1;
                 loadMore.init();
                 if(_isLiang){
                     loadMore.liangData();
                 }
            }else if(data.status == 1){
                var url = window.location.href;
                $.ui.tips("请先登录",function(){
                    location.href = $.ui.loginUrl();
                });
                return false;
            }else{
                $.ui.tips(data.msg);
            }
          },"json");
        }else{
            $.ui.tips("请填写回复内容");
        }
    });

    
    loadMore.init();
    loadMore.liangData();
    loadMore.ajaxScroll();
    //最新评论显示更多
    $("#js-box .reply .more>a").live("click",function(){
        var curpage   = $(this).attr("data-curpage");
        var total     = $(this).attr("data-total");
        var totalPage = parseInt(total/5)+1;
        
        if(curpage == 1){
            var num     = parseInt(total-5);
            $(this).parent().prev("ul").find("li").show();
            if(totalPage == curpage || total<=5 ){
                $(this).parent().hide();
            }else{
                curpage++;
                $(this).text("还有"+num+"条回复");
                $(this).attr("data-curpage",curpage);
            }
        }else{
            var commentId = $(this).attr("data-commentId");
            var ajaxLink = "http://m.shihuo.cn/shaiwu/replyAjax";
            var _dataStr = {"commentId": commentId,"replyPage": curpage,"productId": productId,"replySize":5};
            var that = this;
            $.post(ajaxLink,_dataStr,function(data) {
             if(data.status == 0){
                var _html ="";
                $.each(data.data.reply,function(index,val){
                    _html += '<li>\
                            <div class="user">\
                                <div class="face"><img src="'+val.user_avatar+'"></div>\
                                <a class="text toAndCom"  href="javascript:void(0);" data-commentid="'+commentId+'" data-name="'+val.user_name+'">\
                                    <div><span>'+val.user_name+'</span>'+val.content+'</div>\
                                    <p class="time">'+val.created_at+'</p>\
                                </a>\
                            </div>\
                        </li>';
                });
                $(that).parent().prev("ul").append(_html);
                var num = parseInt(total-(curpage*5));
                if(num>0){
                    curpage ++;
                    $(that).text("还有"+num+"条回复"); 
                    $(that).attr("data-curpage",curpage);
                }else{
                    $(that).parent().hide();
                }
             }
            },"json");
        }
     });
     //评论点赞
     $("#js-box .ding,#comment-liang .ding").live("click",function(){
        var commentId = $(this).attr("data-commentId");
        var ajaxLink  = "http://m.shihuo.cn/shaiwu/commentPraise";
        var _dataStr  = {"commentId": commentId,"productId": productId};
        var that = this;
        $.post(ajaxLink,_dataStr,function(data) {
            if(data.status == 0){
                $(that).addClass('checked');
                $(that).text(data.data.num);
            }else if(data.status == 1){
                var url = window.location.href;
                $.ui.tips("请先登录",function(){
                     location.href = $.ui.loginUrl();
                });
                return false;
            }else{
                $.ui.tips(data.msg);
            }
        },"json");
     });

    $("#share").on("click",function(){
        $(".js_share_box,.js_share_hide").addClass('show');
    });
    $("#js_share_hide,.js_share_hide").on("click",function(){
        $(".js_share_box,.js_share_hide").removeClass('show');
    });
    $(".share_tsina").on("click",function(){
        var _title = "#识货晒单#"+share_title+"[@识货 不只是消费，更有态度]";
        var _url  = encodeURIComponent(document.URL);
        var _pic  =  encodeURIComponent(share_pic) ;
        var _link = "http://service.weibo.com/share/share.php?status=1&pic=" + _pic + "&url=" + _url + "&title="+encodeURIComponent(_title)+"&appkey=3445570739";
         $(".js_share_box,.js_share_hide").removeClass('show');
        window.open(_link,"_blank");
        return false;
    });
    $(".share_tqq").on("click",function(){
        var _title = encodeURIComponent("#识货晒单#"+share_title+"[@识货 不只是消费，更有态度]");
        var _content = " ";
        var _url  = encodeURIComponent(document.URL);
        var _pic  =  encodeURIComponent(share_pic) ;
        var _link = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title='+_title+'&summary='+encodeURIComponent(_content)+'&url=' + _url +'&pics='+_pic;  
         $(".js_share_box,.js_share_hide").removeClass('show');
         window.open(_link,"_blank");
        return false;
    });
    $(".share_trr").on("click",function(){
        var _title  =  "【识货】"+share_title;
        var _url    = encodeURIComponent(window.location.href);
        var _srcUrl = window.location.href;
        var _pic  =  encodeURIComponent(share_pic) ;
        var _link   = "http://widget.renren.com/dialog/share?resourceUrl=" + _url + "&srcUrl=" + _srcUrl + "&title=" + _title +'&pic='+_pic; 
         $(".js_share_box,.js_share_hide").removeClass('show');
        window.open(_link,"_blank");
        return false;
    });
});

//下拉加载更多
var loadMore = {
    ajaxLink: "http://m.shihuo.cn/shaiwu/commentAjax",
    ajaxScroll: function() {
        var that = this;//页面滚动
        $(window).scroll(function() {
          if($(window).scrollTop() >= $(document).height()-$(window).height() && _ajaxG && _pageNum <= _PageTotal) {
            $("#loadding").show();
            loadMore.ajaxData();
          }
        });
    },
    init:function(){
       this.ajaxData();
    },
    ajaxData:function(){
        var _dataStr ={"page": _pageNum, "pagesize": _pageSize,"productId": productId};
        var _html ="";
        var element = $('#js-box'),  
        tpl = $('#tpl').html();
        _ajaxG = false;
        $.post(this.ajaxLink,_dataStr, function(data) {
            if(data.status == "0"){
                $("#loadding").hide();
                _pageTotal = data.pages;
                if(!_pageTotal){
                   $(".null").addClass("show");
                }else{
                    $(".null").removeClass("show");
                }
                _pageNum += 1;
                _ajaxG = true;
                var html = _.template(tpl);  
                // 将解析后的内容填充到渲染元素  
                // _sendCom 发送评论更新ajax填充方式
                if(_sendCom == 1){
                     element.html(html(data));
                     _sendCom = 0;
                }else{
                    element.append(html(data));
                }
            }
        },"json");
    },
    liangData:function(){
        var _ajaxLink = "http://m.shihuo.cn/shaiwu/lightComment";
        var _dataStr  = {"id": productId};
        var _html     = "";
        var tpl = $('#tpl_liang').html();

        $.post(_ajaxLink,_dataStr, function(data) {
            if(data.status == "0"){
                if(!data.data.length){
                    $("#comment-liang,h2.liang").hide();
                }else{
                    $("#comment-liang,h2.liang").show();
                }
                var html = _.template(tpl);  
                $("#comment-liang").html(html(data));
            }
        },"json");
    }
}
