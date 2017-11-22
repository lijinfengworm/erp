var autoround;
(function(){
    "use strict";
    var thisUrl = window.location.href,
        hasRound = false;
    $(function(){
        randomEvent();

        if(window.location.hash.indexOf("rotate") > 0){
               scrollTo(2);
        }

        $(".page-nav li").click(function(){
           var index = $(this).attr("data-href");
            scrollTo(index);
        });

        $(".scrollNav-content li").each(function(){
            $(this).click(function(){
                var i = $(this).attr("data-href");
                if("undefined" != typeof i){
                    scrollTo(i);
                }
            });
        });


        scrollNavAnim();

        $(window).scroll(function(){
            scrollNavAnim();
        });

        $(window).resize(function(){
            scrollNavAnim();
        });


        var timeOut = function(){  //超时函数
            $(".round-img").rotate({
                angle:0,
                duration: 10000,
                animateTo: 2160, //这里是设置请求超时后返回的角度，所以应该还是回到最原始的位置，2160是因为我要让它转6圈，就是360*6得来的
                callback:function(){
                    //alert('网络超时')
                }
            });
        };
        var rotateFunc = function(awards,angle,text,url,fn){  //awards:奖项，angle:奖项对应的角度
            $('.round-img').stopRotate();
            $(".round-img").rotate({
                angle:0,
                duration: 5000,
                animateTo: angle+1440, //angle是图片上各奖项对应的角度，1440是我要让指针旋转4圈。所以最后的结束的角度就是这样子^^
                callback:function(){
                    //console.log(text,url)
                    popup().show(text,url);
                    fn && fn();
                }
            });
        };

        var hasclick=false;
        $(".round-btn").click(function(e){
            autoRound().stop();
            if(hasclick){
                return false;
            }
            hasclick = true;
            var c = getCookie("rotatefirst"),
                angle = [240,180,120];

            if (c != null) {
                $.post("http://www.shihuo.cn/api/luckyDraw20151111",{act:"luckyDraw"},function(data){
                    if("undefined" != typeof data){
                        var datas = $.parseJSON(data);
                        if(!datas.status){
                            popup().show(datas.msg,"undefined");
                            hasclick = false;
                        }else{
                                var i = datas.data.code- 1,
                                title=datas.data.title,url;
                            i == 0 ? url="undefined": url=datas.data.link;
                            rotateFunc(i,angle[i],title,url,function(){
                                hasclick = false;
                            });
                        }
                    }
                });
                return;
            }else{
                register("true");
                $.post("http://www.shihuo.cn/api/luckyDraw20151111",{act:"luckyDraw"},function(data){
                    rotateFunc(1,angle[0],"嘿咻嘿咻，再来一次",thisUrl+"#rotate",function(){
                        hasclick = false;
                    });
                });
            }
        });

        popup().init();

        var daceid = getCookie('_dacevid3');
        var qrUrl = "http://m.shihuo.cn/api/share20151111?dacevid="+daceid;

        if($.browser.msie && $.browser.version < 9){
            $('#qrCode').qrcode({render:"table",width:130,height:130,correctLevel:0,text:qrUrl});
        }else{
            $('#qrCode').qrcode(qrUrl);
        }

        if($(".part-5").length > 0){
            $.getJSON("http://www.shihuo.cn/activity/getCheapTop?type=pc&num=50",function(data){
                appendTpl(1,data);

            });

            $.getJSON("http://www.shihuo.cn/activity/getHotTop?type=pc&num=50",function(data){
                appendTpl(2,data);
            });
        }

        $(".lazyload").lazyload({
            "effect":"fadeIn"
        });
        //autoRound().start();
    });


    function autoRound(){
        var rotate = 10;

        return {
            start:function(){
                autoround = setInterval(function(){
                    rotate += 10;

                    $('.round-img').css("transform","rotate("+rotate+"deg)");
                },60);
            },
            stop:function(){
                clearTimeout(autoround);
            }
        }
    }

    function randomEvent(){
        var arr=[];
        $(".part-1 li").each(function(){
           arr.push($(this).html());
        });

        var arr2= arr.sort(randomsort);
        $(".part-1 ul").html("");
        $.each(arr2,function(index){
            $(".part-1 ul").append("<li>"+arr2[index]+"</li>");
        });
        $(".part-1 ul").css("visibility","visible");
    }

    function randomsort(a,b){
        return Math.random() > .5 ? -1 : 1;
    }

    function appendTpl(index,data){
        var list  = _.template($("#tpl"+index).html());
        _.each(data,function(a,i){
            $("#tpllist"+index).append(list(a));
            $(".icon i","#tpllist"+index+" li:eq("+i+")").text(parseInt(i+1));
        });
        $("#tpl"+index).remove();
        $("#scrollbar"+index).tinyscrollbar();

        for(var i=0;i<3;i++){
            $(".icon","#tpllist"+index+" li:eq("+i+")").addClass("top");
        }
    }

    function popup(){
        var dom = "<div class='activity-popup'>\
                      <div class='blackBg'></div>\
                      <div class='popupcontent'>\
                            <div class='txt'></div>\
                            <a href='###' isconvert='1' target='_blank'>马上领取</a>\
                            <div class='close'></div>\
                      </div>\
                   </div>";
        return {
            init:function(){
                var t = this;
                $("body").append(dom);
                $(".activity-popup .close").on("click",function(){
                     t.hide();
                });
            },
            show:function(txt,url){
                var t=this;
                $(".activity-popup").fadeIn(200,function(){
                    $(".popupcontent").fadeIn();
                });
                $(".activity-popup .txt").text(txt);
                //console.log(url);
                if(url != "undefined"){
                    if(url.indexOf("rotate") >0 ){
                        $(".activity-popup a").attr({"href":"javascript:void(0)","target":"_self"}).text("确定");
                        $(".activity-popup a").on("click",function(){
                            window.open(url);
                            window.location.href="http://s.click.taobao.com/jJmDElx";
                        });
                    }else{
                        $(".activity-popup a").attr({"href":url,"target":"_blank"}).text("马上领取");
                    }
                }else{
                    $(".activity-popup a").attr({"href":"javascript:void(0)","target":"_self"}).text("确定");
                    $(".activity-popup a").on("click",function(){
                        t.hide();
                    });
                }
            },
            hide:function(){
                $(".popupcontent").fadeOut();
                $(".activity-popup").fadeOut();
                autoRound().start();
            }
        }
    }

    function scrollTo(i){
        var sltop = $(".part-"+i+"").offset().top-60;
        $("html,body").animate({"scrollTop":sltop+"px"},700,"swing");
    }

    function scrollNavAnim(){
        var wt = $(window).scrollTop(),
            pt = $(".part-1").offset().top,
            wh = $(window).height(),
            thisH = $(".scrollNav").height(),
            totalH = $(".pagecontent").offset().top + $(".pagecontent").height(),
            ww = $(window).width(),
            mleft = 697,
            roundT = $(".part-2").length > 0 ? $(".part-2").offset().top : '',
            pt3 = $(".part-3").length > 0 ? $(".part-3").offset().top : '';

        if(wt > pt){
            var top = Math.floor((wh-thisH)/2)+(wt-pt);
            if(wt + wh > totalH){
                $(".scrollNav").css({"top":"auto","bottom":"10px"});
                return false
            }
            $(".scrollNav").css({"top":top+"px","bottom":"auto"});
            if(ww < 1433){
                $(".scrollNav").css("margin-left","-697px");
                if(ww<1386){
                    $(".scrollNav").addClass("fixleft");
                    $(".scrollNav-bottom").css("margin-top","-1px");
                }else{
                    $(".scrollNav-bottom").css("margin","0px");
                    $(".scrollNav").removeClass("fixleft");
                }
            }else{
                $(".scrollNav").css("margin-left","-720px");
            }
        }else{
            $(".scrollNav").css({"top":"137px","bottom":"auto"});
            $(".scrollNav").removeClass("fixleft");
        }

        if(wt+wh > roundT && wt < pt3){
            !hasRound && (autoRound().start(),hasRound=true);
        }else{
            autoRound().stop();
            hasRound = false;
        }

    }

    function setCookie(key, value, expire)
    {
        window.document.cookie = key + "=" + escape(value) + ((expire == null) ? "" : ("; expires=" + expire.toGMTString()));
    }
    function getCookie(key)
    {
        var search = key + "=";
        if (window.document.cookie.length > 0)
        { // if there are any cookies
            var offset = window.document.cookie.indexOf(search);
            if (offset != -1)
            { // if cookie exists
                offset += search.length;
                // set index of beginning of value
                var end = window.document.cookie.indexOf(";", offset)
                // set index of end of cookie value
                if (end == -1)
                    end = window.document.cookie.length;
                return unescape(window.document.cookie.substring(offset, end));
            }
        }
        return null;
    }
    function register(key) {
        var today = new Date();
        var expires = new Date();
        expires.setTime(today.getTime() + 1000*60*60*24);
        setCookie("rotatefirst",key, expires);
    }

})();