define(["royalSlider","CountDown","cart","Modernizr","jquery-easing"],function(royalSlider,CountDown,cart){
    var index = {
        init:function(){
            this.initSlide();
            this.bindFun();
        },
        bindFun:function(){
            $(".previews").each(function(){
                var length = $(".procontent",this).find("li").length;
                if(length < 20){
                     $(this).append('<img style="width:190px;height:169px;margin:35px auto;display:block;" src="http://c1.hoopchina.com.cn/images/trade/tejia/more.jpg" />');
                }
            });

            //倒计时
            var countdown = new CountDown();
            countdown.timeboxClass = ".countdown";
            countdown.slideClass = ".start";
            countdown.init();
            $(".previews a").on("click",function(e){
                if($(e.target).hasClass("addcart") || $(e.target).hasClass("cartBtn")){
                    return false;
                }
            });
            //加入购物车
            cart.init(".previews .addcart");
            cart.init(".previews .cartBtn");
        },
        initSlide:function(){
            var a = ($.support.opacity ? 800 : 0, Modernizr.touch ? !0 : !1),
                b = Modernizr.touch ? !0 : !1;
                $.rsCSS3Easing.easeInOutCubic = "cubic-bezier(0.645, 0.045, 0.355, 1.000)",
                $(".royalSlider").royalSlider({
                    width: "1080px",
                    allowCSS3: b,
                    arrowsNav: !0,
                    autoScaleSlider: !0,
                    autoScaleSliderWidth: 1080,
                    autoHeight: !0,
                    easeInOut: "easeInOutCubic",
                    easeOut: "linear",
                    controlNavigation: "bullets",
                    controlsInside: !0,
                    imageAlignCenter: !1,
                    keyboardNavEnabled: !0,
                    loop: !0,
                    minSlideOffset: 0,
                    navigateByClick: !1,
                    numImagesToPreload: 3,
                    sliderDrag: a,
                    sliderTouch: !0,
                    slidesSpacing: 0,
                    startSlideId:2,
                    transitionType: "move",
                    autoPlay: {
                        enabled: !1
                    }
                });
            //var q = $(".royalSlider").data("royalSlider");
            var _container = ".royalSlider";
            var _slider = $(_container).data("royalSlider");
            _slider.ev.on("rsAfterSlideChange",function(event){
                $(".menu li").attr("class","");
                $(".menu li:eq("+_slider.currSlideId+")").attr("class","select");
            });
            $(".menu li").click(function(){
                var i = $(this).index();
                $(".menu li").attr("class","");
                $(this).attr("class","select");
                _slider.goTo(i);
            })
            $(".wrap").css("visibility","visible");
        }
    };
    return index
});
