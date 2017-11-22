var _ajaxG     = true;
var _pageSize  = 30; //每页个数
var _tab       = "new";
var _PageTotal = $("#list_" + _tab).attr("data-totals"); //总页数
var _searchVal = $("#search_val").attr("data-searchInit");
var ajaxLink   = "http://m.shihuo.cn/search/SearchAjax";
var first      = $("#tabIndex").val();//_tab索引

$(function () {
    TouchSlide({
        defaultIndex:first,
        slideCell: "#tabBox1",
        endFun: function (i) { //高度自适应
            var bd = document.getElementById("tabBox1");
            if(i>0)bd.parentNode.style.transition="200ms";//添加动画效果
            _ajaxG = true;
            var _tabNew = $("#tabBox1 li.on>a").attr("data-type");
            first = i;
            if ( _tab != _tabNew) {
                $(window).scrollTop(0);
                $("#list_"+_tab).html("");
                _tab =_tabNew;
                $("#list_"+_tab).addClass("load");
                searchAjax.btnTab();
            }

        }
    });
    searchAjax.loadMore();
    searchAjax.searHistory();
    $("#search_btn").click(function(event) {
        $("#tabIndex").val(first);
    });
});
var searchAjax = {
    searHistory:function(){
       if(_searchVal){
         if(localStorage["history"]){
               var history = localStorage["history"];
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
               localStorage['history'] = arr.join("|");
        }else{
            localStorage["history"] = escape(_searchVal);
        }
       }
    },
    btnTab: function () {
        _pageNum = 1;
        var _dataStr = {"page": "1", "pagesize": _pageSize, "channel": _tab, "keywords": _searchVal};
        $.post(ajaxLink, _dataStr, function (data) {
            $("#list_"+_tab).removeClass("load");
            _pageNum += 1;
            $("#tabBox1-bd .con ul").attr("data-pageCur", _pageNum);
            _PageTotal = data.page;//总页数
            _ajaxG = true;
            if (data.data) {
                var _html = searchAjax.getHtml(data.data);
                $("#list_" + _tab).html(_html);
                $("#list_" + _tab).removeClass('null');
            } else {
                $("#list_" + _tab).addClass('null');
            }  
            
        }, 'json');

    },
    loadMore: function () {
        $(window).scroll(function () {
            var _pageNum = parseInt($("#list_" + _tab).attr("data-pageCur"));

            if ($(window).scrollTop() >= $(document).height() - $(window).height() && _ajaxG && _pageNum <= _PageTotal) {
                $("#loadding").show();
                _ajaxG = false;
                if (_searchVal) {
                    var _dataStr = {"page": _pageNum, "pagesize": _pageSize, "channel": _tab, "keywords": _searchVal};
                } else {
                    var _dataStr = {"page": _pageNum, "pagesize": _pageSize, "channel": _tab};
                }
                var _html = "";
                $.post(ajaxLink, _dataStr, function (data) {
                    $("#loadding").hide();
                    _pageNum += 1;
                    $("#list_" + _tab).attr("data-pageCur", _pageNum);
                    _ajaxG = true;
                    _html = searchAjax.getHtml(data.data);
                    $("#list_" + _tab).append(_html);
                   
                }, 'json');
            }
        });
    },
    getHtml: function (data) {
        var _html = "";
        if (_tab == "news" || _tab == "haitao") {
            $.each(data, function (index, val) {
                _html += '<li><a href="' + val.detail_url + '" class="link-a">' +
                '<div class="imgs"><img src="' + val.img_path + '" alt=""></div>' +
                '<div class="details_box"> <h2>' + val.title + '</h2>' +
                '<p class="money"><span>' + val.subtitle + ' </span></p> ' +
                '<div class="remain_time">' + val.go_website +
                '<div class="fr">' + val.remain_time + '</div></div>' +
                '</div> </a> </li>';
            });
        } else if (_tab == "daigou") {
            $.each(data, function (index, val) {
                var title = val.title;
                if (!title) {
                    title = val.name;
                }
                if (val.goods_id) {
                    var daigou_url = 'http://m.shihuo.cn/daigou/' + val.id + '-' + val.goods_id + '.html';
                } else {
                    var daigou_url = 'http://m.shihuo.cn/daigou/' + val.id + '.html';
                }
                _html += '<li><a href="' + daigou_url + '" class="link-a">' +
                '<div class="imgs"><img src="' + val.img_path + '" alt=""></div>' +
                '<div class="details_box"> <h2>' + title + '</h2><p class="money">¥' + val.price + '</p>' +
                '<div class="from"> <div class="guanzhu">' + val.hits + '已关注</div>商家：'+val.business+'</div></div></a></li>';
            });
        } else if (_tab == "shoe") {
            $.each(data, function (index, val) {
                var title = val.title;
                if (!title) {
                    title = val.name;
                }
                _html += '<li><a href="http://m.shihuo.cn/shoe/detail/' + val.id + '.html" class="link-a">' +
                '<div class="imgs"><img src="http://shihuo.hupucdn.com/' + val.img_url + '-S253A.jpg" alt=""></div>' +
                '<div class="details_box"> <h2>' + title + '</h2><p class="tags">';
                if (val.tags)
                    $.each(val.tags, function (index, val) {
                        _html += '<span>' + val + '</span>';
                    });
                _html += '</p><div class="price"><i>￥</i><span>' + val.price + '</span>' +
                '<div class="fr">热度：' + val.heat + '</div></div>' +
                '</div> </a> </li>';
            });
        } else if (_tab == "groupon") {
            $.each(data, function (index, val) {
                SysSecond = parseInt(val.time);
                if (SysSecond > 0) {
                    SysSecond = SysSecond - 1;
                    var second = Math.floor(SysSecond % 60);
                    var minite = Math.floor((SysSecond / 60) % 60);
                    var hour = Math.floor((SysSecond / 3600) % 24);
                    var day  = Math.floor((SysSecond / 3600) / 24);
                    var time  = [day, hour, minite, second];
                    if (typeof time == "object") {
                       var timearr =time[0] + '天' + time[1] + '小时'+ time[2] + '分';
                    } else {
                        var timearr ="00分";
                    }
                } 
                _html += '<li><a href="' + val.go_url + '" class="link-a">' +
                '<div class="imgs"><img src="' + val.img_path + '" alt=""></div>' +
                '<div class="details_box"> <h2>' + val.title + '</h2>' +
                '<p class="money">￥<span>' + val.price + ' </span><i>￥' + val.original_price +'</i></p> ' +
                '<div class="remain_time">剩余：<span class="time_num" data-time="' + val.time +'">' + timearr +'</span>' +
                '<div class="fr">' + val.attend_count + '人已买</div></div>' +
                '</div><div class="dz_num">' + val.discount + '折</div> </a> </li>';
            });
        } else if (_tab == "find") {
            $.each(data, function (index, val) {
                var title = val.title;
                if (!title) {
                    title = val.name;
                }
                _html += '<li><a href="http://m.shihuo.cn/detail/' + val.id + '.html" class="link-a">' + '<div class="imgs">' +
                '<img src="' + val.img_path + '" alt=""></div>' + '<div class="details_box"> <h2>' + title + '</h2><p class="tags">';
                if (val.tags) $.each(val.tags, function (index, val) {
                    _html += '<span>' + val + '</span>';
                });
                _html += '</p><div class="price"><i>￥</i><span>' + val.price + '</span>' + '<div class="fr">' + val.pub_time + '</div></div>' + '</div> </a> </li>';
            });

        }
        return _html;
    }
}