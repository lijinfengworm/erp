var app = angular.module('app',[]);

app.controller('inventoryIndex',function($scope,$filter){
    $scope.section = res;
});

(function(){
    $(function(){
        $(".scroll-nav li").click(function(){
            var i = $(this).index();
            $("body,html").stop().animate({scrollTop:$("#section"+i).offset().top},600,'swing');
        });
        $(".backto-top").click(function(){
            $("body,html").stop().animate({scrollTop:0},600,'swing');
        });

        $(window).resize(scrollNavAnim);

        $(window).scroll(scrollNavAnim);
        scrollNavAnim();
        autoSlide.init();


    });
    var autoSlide={
        init:function(){
            this.bindFun();
        },
        bindFun:function(){
            var t = this;
            $(".swiper-content").each(function(){
                var $this = $(this);
                var thisli = $("li",this),
                    index = $(this).attr("data-index"),
                    typeid = $(this).attr("data-typeid");
                if(thisli.length == 8){
                    t.ajax($this,index,typeid);
                }
            });
        },
        ajax:function(obj,index,typeid){
            var t =this,
                page = parseInt($(obj).attr("data-totalpage"))+1;
            var data = {
                typeid:typeid,
                page_size:8,
                page: page
            }
            $.post("http://www.shihuo.cn/haitao/inventory/act/getetNewInventory",data,function(d){
                var callback_data = typeof d == 'string' ? $.parseJSON(d) : d,
                    info = callback_data.data;
                if(callback_data.status == 0){
                    $(obj).attr("data-totalpage",page);
                    var $ul = $('<ul/>');
                    for(var i=0;i<info.length;i++){
                        $ul.append(t.ele_li(info[i]));
                    }
                    $(obj).append($ul);
                    $(obj).prev().unbind("click");
                    $(obj).next().unbind("click");
                    t.scroll(obj);
                }else{
                    if($(obj).find("ul").length == 1){
                        $(obj).prev().hide();
                        $(obj).next().hide();
                    }else{
                        $(obj).cycle({
                            fx:'scrollHorz',
                            speed:1500,
                            width: 968,
                            prev:$(obj).prev(),
                            next:$(obj).next(),
                            timeout:5000,
                            autostop:0
                        });
                    }
                }

            });
        },
        ele_li:function(info){
            return  '<li>\
                        <div class="clearfix">\
                            <div class="user-img"><a target="_blank" href="http://www.shihuo.cn/haitao/inventory/act/detail?id='+info.id+'"><img src="http://bbs.hupu.com/bbskcy/api_new_image.php?uid='+info.hupu_uid+'/'+info.hupu_uid+'_small_2.jpg"></a></div>\
                            <div class="info">\
                                <p class="p1">'+info.hupu_username+'&nbsp;&nbsp;'+info.created_at+'</p>\
                                <p class="p2">创建了<a target="_blank" href="http://www.shihuo.cn/haitao/inventory/act/detail?id='+info.id+'">'+info.title+'</a></p>\
                            </div>\
                        </div>\
                      </li>';
        },
        scroll:function(obj){
            var t = this;
            var index = $(obj).attr("data-index"),
                typeid = $(obj).attr("data-typeid"),
                page = parseInt($(obj).attr("data-totalpage"))+ 1,
                startpage = page == 3 ? 0 : page-3,
                stoppage = page == 3 ? 2: page+1;
            var scrolling = false;
            $(obj).cycle({
                fx:'scrollHorz',
                speed:1500,
                width: 968,
                timeout:5000,
                prev:$(obj).prev(),
                next:$(obj).next(),
                startingSlide:startpage,
                autostop: true,
                autostopCount:stoppage,
                prevNextClick:function(){
                    scrolling = true;
                },
                after:function(){
                    scrolling = false;
                },
                end:function(){
                    if(!scrolling){
                        page++;
                        t.ajax(obj,index,typeid,page);
                    }
                }
            });
        }
    }
    function scrollNavAnim(){
        var wt = $(window).scrollTop(),
            pt = 380,
            wh = $(window).height(),
            thisH = $(".scroll-nav").height(),
            totalH = $(".page-wrap").offset().top + $(".page-wrap").height(),
            ww = $(window).width(),
            mleft = 540;

        if(wt > pt){
            var top = Math.round((wh-thisH)/2)+wt;
            if(wt + wh > totalH){
                $(".scroll-nav").css({"top":"auto","bottom":"200px"});
                return false
            }
            $(".scroll-nav").css({"top":top+"px","bottom":"auto"});
            if(ww < 1388){
                var ml = Math.floor(ww/2)-154;
                $(".scroll-nav").css("margin-left",ml+"px");

            }else{
                $(".scroll-nav").css("margin-left","540px");
            }
        }else{
            $(".scroll-nav").css({"top":"380px","bottom":"auto"});
        }


    }
})();