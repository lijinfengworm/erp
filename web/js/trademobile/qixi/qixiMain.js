define(['underscore','fx','alertbox'],function(underscore,fx,alertbox){
    function getHashStringArgs() {

       //取得查询的hash，并去除开头的#号

        var hashStrings = (window.location.hash.length > 0 ? window.location.hash.substring(1) : ""),

        //保持数据的对象

       hashArgs = {},



       //取得每一项hash对

       items = hashStrings.length > 0 ? hashStrings.split("&") : [],

       item = null,

       name = null,

       value = null,

       i = 0,

       len = items.length;



       //逐个将每一项添加到hashArgs中

       for (i = 0; i < len; i++) {

           item = items[i].split("=");

           name = decodeURIComponent(item[0]);

           value = decodeURIComponent(item[1]);

           if (name.length > 0) {

               hashArgs[name] = value;

           }

       }

    return hashArgs;

    }
    var qixiMain={
        init:function(){ 
            var hash = getHashStringArgs(),
            from = hash.from;
            if(from == "zhanqi"){
                staticData[0].content = "爱看战旗TV爱玩lol，简单粗暴的女汉子一枚。最大的兴趣爱好就是和朋友们打篮球！球场上个性霸气的她，私下却逗逼有趣。这次女王大赛，喜欢安妮的要给她投票哦！";
                staticData[2].content = "参赛者中最氧气的网友之一，网友jocelyn15曝出照片，并称“拉妹妹来凑数”后，引发了一阵“大舅哥”的认亲风潮。这位妹妹喜爱篮球、健身，还是典型的爱看lol直播的撸妹子哦！";
                staticData[5].content = "爱运动爱游戏的萌女汉！目前没有男朋友，四海之内皆朋友！摄影音乐体育游戏样样耍的来，不仅名字甜性格也甜，做个萌女汉没什么不好的。";
            }           
            //js 模板初始化
            var tpl = $("#tpl").html();
            var qixiDom = _.template(tpl);
            $(".pagewrap").append(qixiDom());
            $(".grid").show();
            $("#tpl").remove();

            this.bindFun();
            this.vote();
            this.playVideo();
        },
        bindFun:function(){      
            $.fn.upSlide = this.upSlide;    
            $(".scrollWrap").each(function(){                
                if($(this).find("ul").height() > $(this).height()){                  
                    $(this).upSlide();
                }
            });
        },
        upSlide:function(){            
            var speed=50,
                $ul = $(this).find("ul"),
                $wrap = $(this);
                                                        
            function Marquee(){         
                var wrapScrollTop = $wrap.scrollTop(),
                    $ulH = $ul.height();                                 
                if($ulH-$wrap.scrollTop()<=$wrap.height())
                    $wrap.scrollTop(0);                       
                else{
                    wrapScrollTop++;
                    $wrap.scrollTop(wrapScrollTop);
                }
            }
            var MyMar=setInterval(Marquee,speed);
            $wrap.on("touchmove",function(){
                clearInterval(MyMar);
            });
            $wrap.on("touchstart",function(){
                clearInterval(MyMar);
            });
            $wrap.on("touchend",function(){
                MyMar=setInterval(Marquee,speed);
            });
            $wrap.on("touchcancel",function(){
                MyMar=setInterval(Marquee,speed);
            });
        },
        vote:function(){        
            var voted = false,supported;  
            if($(".loadingbar").length == 0){
                $("body").append("<div class='loadingbar'></div>");
            } 
            $(".voteBtn").each(function(){
                var isSupport = $(this).attr("data-issupport");    
                if(isSupport == "true"){
                    supported = true;                                                        
                }else if(undefined == typeof supported && isSupport == "false"){
                    supported = false;
                }
            });
            if(supported){
                $(".votenum").show();
                $(".unvote").hide();
            }else{
                $(".votenum").hide();
                $(".unvote").show();
            }
            $(".voteBtn").on("tap",function(){    
                if(voted){
                    return false
                }           
                var date = new Date().getTime();
                $(".loadingbar").fadeIn();
                voted = true;              
                var sid = $(this).attr("data-sid"),
                    $this = $(this);
                $.post("http://www.shihuo.cn/api/qixi?"+date,{act:"ajaxSupport",sid:sid},function(data){
                    $(".loadingbar").hide();
                    datas = $.parseJSON(data);
                    if(datas.status == 410 || datas.status == 405){
                        alertbox.show({
                            title:datas.msg,
                            confirmTxt:"确定",
                            content:"<img src='/images/trademobile/activity/qixi/coupon.png'>",
                            confirm:function(){                   
                                $this.find(".votenum").text(datas.data.num);
                                $(".votenum").show();
                                $(".unvote").hide();
                            }
                        });
                    }else if(datas.status == 411){
                        alertbox.show({
                            title:datas.msg,
                            confirmTxt:"确定",
                            content:"",
                            confirm:function(){                   
                                //$this.find(".votenum").text(datas.data.num);
                                //$(".votenum").show();
                                //$(".unvote").hide();
                            }
                        });
                    }else if(datas.status == 501){
                        alertbox.show({
                            title:datas.msg,
                            confirmTxt:"确定",
                            content:"",
                            confirm:function(){                           
                                window.location.href= datas.data.jumpUrl;
                            }
                        });                        
                    }else{
                        alertbox.show({
                            title:datas.msg,
                            confirmTxt:"再试一次",
                            content:"",
                            confirm:function(){                   
                                //$this.find(".votenum").text(datas.data.num);
                                //$(".votenum").show();
                                //$(".unvote").hide();
                            }
                        });
                    }
                    voted = false;                           
                });                
            });
        },
        playVideo:function(){
            $(".video").on('tap',function(){
                $(this).find("video").fadeIn();
                var video = $("video",this)[0];               
                video.play();
                $(this).find(".holdImg").fadeOut();               
            });
            $("video").each(function(){
                var $this = $(this);
                $(this).bind('ended',function(){
                    $this.fadeOut();
                    $this.parent().find(".holdImg").fadeIn();
                });
                $(this).bind('pause',function(){                    
                    $this.fadeOut();
                    $this.parent().find(".holdImg").fadeIn();
                });
                $(this).bind('playing',function(){
                    $this.fadeIn();
                    $this.parent().find(".holdImg").fadeOut();
                })
            });
        }
    }

    return qixiMain
});