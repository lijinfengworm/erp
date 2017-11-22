$(function(){
    $("#slide").click(function(){
        if($(this).hasClass("show")){
            $(".slide_tit").hide();
            $(this).removeClass("show");
        }else{
            $(".slide_tit").show();
            $(this).addClass("show");
        }
    });
    var galleryThumbs = new Swiper('.fixed_tit', {
        spaceBetween: 0,
        slidesPerView: 'auto'
    });
    $("#fixed_tit .swiper-slide").on("click",function(){
        var index = $(this).index();
        galleryThumbs.slideTo(index-2);
        $("#fixed_tit .swiper-slide").removeClass('on');
        $(this).addClass('on');

        $(".slide_tit li").removeClass('on');
        $(".slide_tit ul li").eq(index).addClass('on');
    });
    $(".slide_tit li").on("click",function(){
        var index = $(this).index();
        galleryThumbs.slideTo(index-2);
        $(".slide_tit li").removeClass('on');
        $(this).addClass('on');

        $("#fixed_tit .swiper-slide").removeClass('on');
        $("#fixed_tit .swiper-slide").eq(index).addClass('on');
    });


    var height = $(".shiyiPage .banner").height();
    $(window).scroll(function() {
        if ($(window).scrollTop()>=height) {
            $(".menuList").addClass('fixed');
        }else{
            $(".menuList").removeClass('fixed');
        }
    });

    var $clickBtn = $('#clickBtn');
    var $plateBtn = $('#plateBtn');
    var $result   = $('#boxAlert');
    var $resultTxt = $('#resultTxt');
    var $resultBtn = $('#closeBtn');


    var gameStart = {
        ajaxLink:"http://www.shihuo.cn/api/luckyDraw20151111",
        getAward:function(){
            var _dataStr = {act:"luckyDraw"};
            $.post(this.ajaxLink,_dataStr, function(data) {
                if(data.status == true){
                    var title = data.data.title;
                    var link = data.data.link;
                    var code = data.data.code;
                    var $btn = $(".lqBtn");
                    if(code == 3){
                        $(".restart").hide();
                        $btn.show();
                        $btn.attr("href",link);
                        gameStart.swithData(4,title);
                    }else if(code == 2){
                        $(".restart").hide();
                        $btn.show();
                        $btn.attr("href",link);
                        gameStart.swithData(5,title);
                    }else{
                        gameStart.swithData(6,title);
                        $(".restart").show();
                        $btn.hide();
                    }
                }else{
                    $choujiang.remind(data.msg);
                    return false;
                }
            },"json");
        },swithData:function(data,text){
            switch(data){
                case 1: 
                    rotateFunc(1,330,'恭喜你中了 <em>一等奖</em>');//iphone 6s  1
                    break;
                case 2: 
                    rotateFunc(2,270,'恭喜你中了 <em>二等奖</em>');//亚瑟士      2
                    break;
                case 3: 
                    rotateFunc(3,30,'恭喜你中了 <em>三等奖</em>');//科比10     3
                    break;
                case 4: 
                    rotateFunc(4,210,text);//福袋       4
                    break;
                case 5: 
                    rotateFunc(5,150,text);//现金红包         5
                    break;
                default:
                    rotateFunc(0,90,text);//再来一次   6
            }
        }
    }
    $clickBtn.click(function(){
        var isweix = (/MicroMessenger/gi).test(navigator.userAgent);
        if(isweix){
            $("#openOther").show();
        }else{
            if(!$(this).hasClass("play")){
                $(this).addClass("play");
                gameStart.getAward();   
            }
        }
    });

    var rotateFunc = function(awards,angle,text){  //awards:奖项，angle:奖项对应的角度
        $plateBtn.stopRotate();
        $plateBtn.rotate({
            angle: 0,
            duration: 5000,
            animateTo: angle + 1440+30,  //angle是图片上各奖项对应的角度，1440是让指针固定旋转4圈
            callback: function(){
                $resultTxt.html(text);
                $clickBtn.removeClass('play');

                $('.shiyiPage').addClass("show");
                $('#boxAlert').addClass("show");
                setTimeout(function() {
                    $('#boxAlert .inner').addClass("show");
                    $('.showBox').addClass("show");
                }, 10);
            }
        });
    };
    $resultBtn.click(function(){
        $('.shiyiPage').removeClass("show");
        $('#boxAlert').removeClass("show");
        setTimeout(function() {
            $('#boxAlert .inner').removeClass("show");
            $('.showBox').removeClass("show");
        }, 10);
    });
    $(".btn.restart").live("click",function(){
        $('.shiyiPage').removeClass("show");
        $('#boxAlert').removeClass("show");
        setTimeout(function() {
            $('#boxAlert .inner').removeClass("show");
            $('.showBox').removeClass("show");
        }, 10);
    });
    $(".restart").hide();
    var loadMore ={
        init:function(){
            var ajaxLink2 = "http://www.shihuo.cn/activity/getHotTopM";
            var ajaxLink1 = "http://www.shihuo.cn/activity/getCheapTopM";
            var $baicaiList = $("#baicai");
            var $hotbangdan = $("#bangdan");
            $("#loadding_baicai").on("click",function(){
                if(!$(this).hasClass("ajax")){
                    $(this).addClass("ajax");
                    loadMore.ajaxList(ajaxLink1,$baicaiList); 
                }
            });
            $("#loadding_bangdan").on("click",function(){
                if(!$(this).hasClass("ajax")){
                    $(this).addClass("ajax");
                    loadMore.ajaxList(ajaxLink2,$hotbangdan);
                }
            });
            loadMore.ajaxList(ajaxLink1,$baicaiList);
            loadMore.ajaxList(ajaxLink2,$hotbangdan);
        },ajaxList:function(ajaxlink,$id){
                var _page = parseInt($id.find(".loadding").attr("data-page"));
                $.post(ajaxlink,{"page":_page}, function(data) {
                    if(data.length>0){
                        var _html ="";
                        $.each(data, function(index, val) {
                            if(_page == 0){
                                var list = _page+index+1;
                            }else{
                                var list = 5+10*(_page-1)+parseInt(index+1);
                            }
                            _html += '<li>\
                                <div class="lft">\
                                    <div class="imgs">\
                                       <a href="'+val.url+'" isconvert="1">  <img src="'+val.image+'" alt=""> </a>\
                                    </div>\
                                </div>\
                                <div class="rig">\
                                    <p class="name"> <a href="'+val.url+'" isconvert="1"> '+val.product_name+' </a></p>\
                                    <p class="price"><span class="new">¥<b>'+val.price+'</b></span><span class="old">¥'+val.original_price+'</span></p>\
                                </div>\
                                <div class="list-style">'+list+'</div>\
                            </li>';
                        });
                        $id.find("ul").append(_html);    
                        $id.find(".loadding").attr("data-page",parseInt(_page)+1);
                        $id.find(".loadding").removeClass('ajax');  
                        if(_page == 5){
                            $id.find(".loadding").hide();
                        }
                    }  
                    ajax = true;
                },"json");
            }
        }
    loadMore.init();
    
});