/**
 * Created by jiangyanghe on 16/12/16.
 */
requirejs.config({
    baseUrl: "//kaluli.hoopchina.com.cn/js/kaluli/",
    paths:{
        initsku:"modules/product/xBuyInitsku",
        changenum: "modules/product/xBuyChangenum",//限时抢购的数量
        submit:"modules/product/xBuySubmit",
        anchor:"modules/product/anchor",
        commentList:"modules/product/commentList",
        salesInfo:"modules/product/salesInfo",
        gallery_slider:"lib/gallery_slider",
        suspensionlayer:"modules/product/suspensionlayer",
        countDown:"modules/common/countDown",
        specificate:"modules/product/specifications"
    },
    urlArgs: 'v='+ ("undefined" != typeof GV ? GV.VERSION : new Date().getTime())
});
require(["initsku",
        "changenum",
        "submit",
        "anchor",
        "gallery_slider",
        "suspensionlayer",
        "countDown",
        "commentList",
        "salesInfo",
        "specificate"],
    function(initsku,changenum,submit,anchor,gallery_slider,suspensionlayer,countDown,commentList,salesInfo,specificate){
        //规格选取
        specificate.init();

        //sku组合算法
        if(undefined == sku.length){
            var a = new initsku(sku);
            a.init();
        }else{
            $(".buy-btn").hide();
            $(".cart-btn").hide();
            $(".none-btn").show();
        }


        //更改购买数量
        var b = new changenum();
        b.init();

        //购买提交
        var c = new submit();
        c.init();

        //商品详情锚点滚动
        var d = new anchor();
        d.init();

        //商品小图滚动效果
        var e = new gallery_slider({
            tc: "#tc_container",
            img : "#tc_container img",
            prev: "#tc_l",
            next : "#tc_r",
            cellmargin : 10
        });
        e.init();

        //主图切换
        $(".small-image li").mouseenter(function(){
            var index = $(this).data("index");
            $(".small-image li").removeClass('show');
            $(this).addClass('show');
            $(".main-image span").removeClass('show');
            $(".main-image span:eq("+index+")").addClass('show');
            $(".main-image span:eq("+index+")").find("img").trigger('appear');
        });

        //产品介绍、商品评价等 菜单悬浮效果
        suspensionlayer.init();

        //限时抢购
        new countDown({//倒计时
            $timebox:$("#count_box"),
            class_time_d:".time_d",
            class_time_h:".time_h",
            class_time_m:".time_m",
            class_time_s:".time_s",
            time_attribute:"leftSec"
        },function(){
            window.location.reload();
        });


        listenScroll();
        function listenScroll(){
            //初始化评论
            if($(window).scrollTop() > $(".switchbox[data-index=0]").offset().top - $(window).height() && !commentList.ajaxBefor){
                commentList.init();
                commentList.ajaxBefor = true;
            }

            if($(window).scrollTop() > $(".switchbox[data-index=1]").offset().top - $(window).height() && !salesInfo.ajaxBefor){
                salesInfo.init();
                salesInfo.ajaxBefor = true;
            }
        };

        $(window).scroll(function(){
            listenScroll();
        });


    });