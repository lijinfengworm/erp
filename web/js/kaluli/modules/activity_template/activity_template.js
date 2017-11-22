/**
 * Created by jiangyanghe on 16/12/12.
 */
define(function(){
    function scrollNav(opt){
        this.defaults = {
            wrap: ".goods-area-content",
            grid:".goods-area-content .item-categary",
            scrollobj:".right-srcoll-navs",
            linkobj:".right-srcoll-navs li",
            backtotop:".backtoTop"
        };
        $.extend(this.defaults,opt);
        this.gridHeight = $(this.defaults.grid).height();
        this.gridArea = [];
    }
    scrollNav.prototype={
        init:function(){
            var t = this;
            t.moveto();
            t.scrollHandle();
            t.backtotop();
            t.pushArea();
            $(window).scroll(function(event){
                t.scrollHandle();
            });
            $(window).resize(function(event) {
                t.scrollHandle();
            });
            t.Anchorposition("down");
            t.scroll(function(direction){
                t.Anchorposition(direction)
            });
        },
        scrollHandle:function(){
            var t = this;
            var st = parseInt($(t.defaults.wrap).offset().top),
                fs = parseInt($(t.defaults.wrap).offset().left),
                ws = parseInt($(window).scrollTop());



            if(ws > st){
                console.log('fs right offset : ' + fs);
                $(t.defaults.scrollobj).css({"position":"fixed","left":fs + 1090});
            }else{
                $(t.defaults.scrollobj).css({"position":"absolute","right":"-110px"});
            }
        },
        pushArea:function(){
            var t = this;
            $(t.defaults.grid).each(function(){
                t.gridArea.push($(this).offset().top);
            });
        },
        Anchorposition:function(arrow){
            var t = this,
                wh = $(window).height(),
                ws = arrow == "up" || Math.round(wh-t.gridHeight) <= 0 ? parseInt($(window).scrollTop()+400) : parseInt($(window).scrollTop()) + Math.round((wh-t.gridHeight)/2+20);

            for(var i=0;i<t.gridArea.length;i++){
                if(i == t.gridArea.length-1 && ws >= parseInt(t.gridArea[i])){
                    $(t.defaults.linkobj).eq(i).addClass('scrollon');
                    return false
                }
                if(ws >= parseInt(t.gridArea[i]) && ws < parseInt(t.gridArea[i+1])){
                    $(t.defaults.linkobj).eq(i).addClass('scrollon');
                }else{
                    $(t.defaults.linkobj).eq(i).removeClass('scrollon');
                }
            }
        },
        scroll:function(fn){
            var beforeScrollTop = $(window).scrollTop(),
                fn = fn || function() {};
            $(window).scroll(function(){
                var afterScrollTop = $(window).scrollTop(),
                    delta = afterScrollTop - beforeScrollTop;
                if( delta === 0 ) return false;
                fn( delta > 0 ? "down" : "up" );
                beforeScrollTop = afterScrollTop;
            });
        },
        moveto:function(){
            var t = this;
            $(t.defaults.linkobj).click(function(){
                var index = $(this).index(),
                    ot = $(t.defaults.grid).eq(index).offset().top;
                $("body,html").animate({scrollTop:ot},600,"swing");
            })
        },
        backtotop:function(){
            var t = this;
            $(t.defaults.backtotop).click(function(){
                $("body,html").animate({scrollTop:"0px"},600,"swing")
            })
        }
    };
    return scrollNav
});

var scrollNav = new scrollNav();
scrollNav.init();