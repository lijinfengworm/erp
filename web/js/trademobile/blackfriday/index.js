angular.module('app',[]).controller('inventoryIndex',function($scope){
    $scope.section = res;
});

(function(){
    $(function(){
        $(".imglazy").lazyload();
        inventoryIndex.init();
    });
    var inventoryIndex = {
        width:null,
        init:function(){
            this.bindFun();
        },
        bindFun:function(){
            var t = this;
            $(".swiper-content").each(function(){
                $(this).slide(t.ajax);
            })
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
        ajax:function(obj,page){
            var data = {
                typeid:$(obj).attr("data-typeid"),
                page_size:8,
                page: page
            }
            $.post("http://www.shihuo.cn/haitao/inventory/act/getetNewInventory",data,function(d){
                var data = typeof d == "string" ? $.parseJSON(d) : d;
                if(data.status == 0){
                    var info = data.data;
                    $(obj).attr("data-totalpage",page);
                    for(var i=0;i<info.length;i++){
                        $(obj).find("ul").append(inventoryIndex.ele_li(info[i]));
                    }
                }
            });
        }
    }
    $.fn.slide=function(fn){
        var t = this,
            w = -1*$("li",".swiper-content").width(),
            i = 1,
            page = $(t).attr("data-totalpage");
        (function(width,index){
            setInterval(function(){
                var length = $(t).find("li").length;
                if(index == length){
                    $(t).find("ul").css("margin-left","0");
                    page++;
                    inventoryIndex.ajax(t,page);
                }else{
                    $(t).find("ul").css("margin-left",width);
                    width=width+w;
                    index++;
                }
            },5000);
        })(w,i);
    }
})();
