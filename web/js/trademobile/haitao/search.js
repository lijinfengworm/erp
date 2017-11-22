var _ajaxG     = true;
var _pageSize  = 30; //每页个数
var _category  = "";
var _child     = "";
var _order     = 2;
var _price     = "";
var _brand     = "";
var _key       = "";
var _keywords  = "";
var _recommend = 1;//是否有推荐，0下拉滚动禁止
var myScroll_child,myScroll,rightScroll; 

$(function(){
    myScroll = new IScroll('#cateWrapper', {
        click:true
    });   
    //left分类导航
    $("#catelist li").eq(0).addClass('on');
    _category = $("#catelist li").eq(0).attr("data-category");
    getRightTags($("#catelist li").eq(0).attr("data-category"));

    $("#catelist li").on('click',function(event) {
        $("#catelist li").removeClass('on');
        $(this).addClass('on');
        _category = $(this).attr("data-category");
        getRightTags(_category);
    });
    $(".searchBox .input").focus(function(){
        $(".searchBox .search").addClass("focus"); 
        $(".searchBox .me").hide();
        $(".reset,.submit,.cancel").show();
        $(".searchBox .input").css("color","#ffe2e2");
        if($(".top_bar").hasClass("kanqiu")){
            $(".searchBox .input").css("color","#444");
        }

        $(".searchBox").removeClass("focus"); 
        $(".top_bar .show_more").hide(); 
        $("#cateSlide").hide();
        $("#history-page").show();

        var history = localStorage["haitao_history"];
        if(history){
            var arr = history.split("|");//将字符串转化为数组
            var _html ="";
             for(i=0;i<arr.length;i++){
                var searchKey = unescape(arr[i]);
                _html += '<li><a href="javascript:void(0);">'+ searchKey + '</a></li>';
             }
             $("#history").html(_html);
        }else{
            $("#history").html("");
        }
    });
    $(".searchBox .cancel").click(function(){
         $(".searchBox .search").removeClass("focus"); 
         $(".reset,.submit,.cancel").hide();
         $(".searchBox .input").blur();
         $(".searchBox .input").css("color","#d75353");
        $(".kanqiu .show_more").hide();
        $("#history-page").hide();
        $("#cateSlide").show();
    });
    $(".searchBox .submit").click(function(){
        _keywords = $(".searchBox .input").val();
        loadMore.searchHis(_keywords);
        loadMore.htmlData();
    });
    $(".searchBox .reset").click(function(){
         $(".searchBox .input").val("");
   });
   $("#clear_history").click(function(){
        localStorage["haitao_history"]="";
        $("#history").html("");
   });

    loadMore.init();
});
//下拉加载更多
var loadMore = {
    hideSlide:function(){
        $("#topmenuSlide .top ul").hide();
        $(".top_menu li").removeClass('on');        
        $("#topmenuSlide .inner").removeClass('show');
        setTimeout(function(){
            $("#topmenuSlide").hide();
            $(window).scrollTop(0);
        }, 300);
    },resetData:function(){
        _pageSize  = 30; //每页个数
        _category  = "";
        _child     = "";
        _order     = 2;
        _price     = "";
        _brand     = "";
        _key       = "";
        _keywords  = "";
    },init:function(){
        $("#tags a").live("click",function(){
            _keywords = $(this).html();
            loadMore.searchHis(_keywords);
            loadMore.htmlData();

        });
        $("#category_child li").live("click",function(){
          //  loadMore.resetData();
            _child = $(this).attr("data-id");
            _category = $("#catelist li.on").attr("data-category");
            loadMore.htmlData();
        });
        $("#history a").live("click",function(){
         //   loadMore.resetData();
            localStorage.lastest_daigou_time = 0;
            _keywords = $(this).html();
            window.location = "http://m.shihuo.cn/daigou?keywords="+_keywords;
        });
    },htmlData:function(){
         var link = "http://m.shihuo.cn/daigou?r="+_category+"&c="+_child+"&keywords="+encodeURIComponent(_keywords);
        localStorage.lastest_daigou_time = 0;
        window.location.href = link;
    },searchHis:function(_searchVal){
        if(_searchVal){
            if(localStorage["haitao_history"]){
               var history = localStorage["haitao_history"];
                Array.prototype.in_array = function(e){
                for(i=0;i<this.length && this[i]!=e;i++);
                  return !(i==this.length);
                }
                var arr = history.split("|");//将字符串转化为数组
                if(!arr.in_array(escape(_searchVal))){
                 arr.unshift(escape(_searchVal));
                 if(arr.length>10){
                    arr.pop();
                 }
                }
                localStorage['haitao_history'] = arr.join("|");
            }else{
                localStorage["haitao_history"] = escape(_searchVal);
            }
       }
    }
}

function getRightTags(cid){
    // $("#rigthWrapper .inner").hide();
    // $("#rigthWrapper .category_"+cid).show();
    var ajaxLink = "http://m.shihuo.cn/haitao/getCategoryInfo?cid="+cid;
    $.get(ajaxLink, function (data) {  
        if (data.code == "0") {
            var _tags = "",_category= "";
            if(data.data.list){
                $.each(data.data.list, function (index, val) {
                    _category += '<li data-id="'+index+'"><a href="javascript:void(0);"><p class="pic"><img src="http://m.shihuo.cn/images/trademobile/haitao/category_'+index+'.png" alt=""></p><p class="nm">'+val+'</p></a></li>';
                }); 
            }
            if(data.data.tags){
                $.each(data.data.tags, function (index, val) {
                    _tags += '<a href="javascript:void(0);">'+val.tag+'</a>';
                }); 
            }
            if(_tags==""){
                $(".sear_hot").hide();
            }else{
                $(".sear_hot").show();
                $("#tags").html(_tags);
            }
            $("#category_child").html(_category);
        }
    },"json");
}