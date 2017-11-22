define(['CookieUtil'],function(CookieUtil){     
    function setCookie(key, value, expire)
    {
        window.document.cookie = key + "=" + escape(value) + ((expire == null) ? "" : ("; expires=" + expire.toGMTString()));
    } 
    function getCookie(key)
    {
        var search = key + "=";
        if (window.document.cookie.length > 0)
        { // if there are any cookies
          offset = window.document.cookie.indexOf(search);
            if (offset != -1)
            { // if cookie exists
                offset += search.length;
            // set index of beginning of value
              end = window.document.cookie.indexOf(";", offset)
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
        setCookie("everydayfirstView",key, expires);
    }

    function activity(opt){
        this.option =  void 0 !== opt ? opt : {};  
        this.iebrowser =  $.browser.msie && $.browser.version < 9 ? true :false,
        this.ipad = function(){
            var ua = navigator.userAgent.toLowerCase(); 
            if(ua.match(/iPad/i)=="ipad") { 
               return true; 
            } else { 
               return false; 
            } 
        };      
    }
    activity.prototype={
        defaults:{
           activityImg : "/images/kaluli/activity.png",
           fullactivityImg : "/images/kaluli/fullactivity.png",
           width : 174,
           height: 250,
           top:505
        },
        init:function(){
            var t = this;
            $.extend(t.defaults,t.option);                                   
            t.time_range("2015:5:4:23:59:59", "2015:5:12:23:59:59");     
        },
        time_range:function(beginTime,endTime){
            var t = this;
            var strb = beginTime.split (":");
            if (strb.length != 6) {
                return false;
            } 

            var stre = endTime.split (":");
            if (stre.length != 6) {
                return false;
            }

            var b = new Date ();
            var e = new Date ();
            var n = new Date ();

            b.setFullYear (strb[0],strb[1],strb[2]);           
            b.setHours (strb[3]);
            b.setMinutes (strb[4]);
            b.setSeconds (strb[5]);

            e.setFullYear (stre[0],stre[1],stre[2]);          
            e.setHours (stre[3]);
            e.setMinutes (stre[4]);
            e.setSeconds (stre[5]);

            if (n.getTime() - b.getTime() > 0 && n.getTime() - e.getTime() <= 0) {  
                t.creatElm();
                t.roll();
                t.bindFun();     
                t.checkCookie();           
                return true;
            } else {              
                return false;
            }
        },
        bindFun:function(){
            var t = this;
            t.doresize();
            $(window).scroll(function(){
                t.roll();
                t.doresize();
            });
            $(window).resize(function(){
                t.roll();
                t.doresize();
            })
        },
        creatElm:function(){
            var t = this,
                ele = '<div class="activity-wrap"><a href="//www.kaluli.com/news/99.html#tc" target="_blank"><img width='+t.defaults.width+' src='+t.defaults.activityImg+' /></a></div>';
            $("body").append(ele);
             if($(".fullScreenActivity").length == 0){
               $(".activity-wrap").find("img").addClass('shake');  
            }
        },
        roll:function(){           
            var t = this;
            var winW = $(window).width(),
                winH = $(window).height(),
                thisW = $(".activity-wrap").width(),
                left = !this.ipad() ? Math.round((winW-1080)/2)+1090 : Math.round(winW-thisW*0.8),
                thisH = $(".activity-wrap img").height() == 0 ? 250 : $(".activity-wrap img").height(),
                st = $(window).scrollTop();
            if(st > t.defaults.top){                
                var top = st+Math.round((winH - t.defaults.height)/2);   
                if(t.iebrowser){
                    top = Math.round((winH - t.defaults.height)/2);
                    $(".activity-wrap").css({"top":top,"left":left+"px","position":"fixed"}).show().find("img").removeClass('shake');
                }else{
                    $(".activity-wrap").css({"top":top,"left":left+"px"}).show().find("img").removeClass('shake');
                }                            
            }else{                
                if(winH < (250 + t.defaults.top)){
                    t.defaults.top = winH - thisH -20;                    
                }else{
                    t.defaults.top = 505;
                }
                $(".activity-wrap").css({"top":t.defaults.top+"px","left":left+"px","position":"absolute"}).show();                               
            }
            if(!this.ipad()){
                if(winW < 1376){
                var scaleW = Math.round((winW-1008)/2-40);
                    $(".activity-wrap").css({"width":scaleW+"px"});
                }else{
                    $(".activity-wrap").css("width","174px");
                }
            }            
        },      
        checkCookie:function(){   
            var t = this;
            var c = getCookie("everydayfirstView");
            if (c != null) {
              return;
            }
            register("true");
            t.fullScreenActivity();        
        }, 
        fullScreenActivity:function(){
            var t = this,
                ele = '<div class="fullScreenActivity">';
                ele +=     '<div class="wrap">';
                ele +=          '<img src='+t.defaults.fullactivityImg+' />';
                ele +=          '<div class="closeBtn"><img src="/images/kaluli/activity-close.png" /></div>';
                ele +=          '<div class="animation animation1"><img src="/images/kaluli/animation1.png" /></div>';
                ele +=          '<div class="animation animation2"><img src="/images/kaluli/animation2.png" /></div>';    
                ele +=          '<div class="animation animation3"><a href="//www.kaluli.com/news/99.html#tc"><img src="/images/kaluli/animation3.png" /></a></div>';    
                ele +=      '</div>';
                ele += '</div>';                
            $("body").append(ele);             
            $(".fullScreenActivity").fadeIn(300,function(){
                $(this).find(".wrap").fadeIn(300,"swing",function(){                    
                    setTimeout(function(){$(".animation1").addClass('end')},1000);
                    setTimeout(function(){$(".animation2").addClass('end')},2000);
                })
            });   
            t.closeActivity();          
        },
        doresize:function(){
            var wh = $(window).height(),
                ww = $(window).width(),                
                left = Math.round((ww-1080)/2);                
            if(wh < 823){
                var ratioW = (1004*1/823)*wh;
                $(".fullScreenActivity .wrap").css({"width":ratioW+"px"});
            }else{
                $(".fullScreenActivity .wrap").css({"width":"1004px"});
            }
        },
        closeActivity:function(){
            $(".fullScreenActivity .closeBtn").live("click",function(){
                $(".fullScreenActivity").fadeOut(300,function(){
                    $(this).remove();
                });
            })
        }
    }
    return activity
});