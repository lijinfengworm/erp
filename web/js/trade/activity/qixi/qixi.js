requirejs.config({
    baseUrl:"/js/",
    paths:{
        "underscore":"lib/underscore",
        "projekktor":"lib/videoPlugin/projekktor-1.3.09.min",
        "gallerySlider":"lib/slide/gallery_slider",        
        "alertbox":"trade/activity/qixi/modules/alertbox",
        "videoModule":"trade/activity/qixi/modules/videoModule",
        "tinyscrollbar":"lib/jquery.tinyscrollbar",
        "commentModule":"trade/activity/qixi/modules/commentModule",
        "jqueryColor":"lib/jquery.color"
    }
});

require(["underscore","projekktor","gallerySlider","alertbox","videoModule","commentModule"],function(underscore,projekktor,gallerySlider,alertbox,videoModule,commentModule){    

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
    $(".pagecontent").append(qixiDom);
    $(".grid").fadeIn();
    $("#tpl").remove();    

    alertbox.init();
    commentModule.init();

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

    var qixi={
        init:function(){
            this.bindFun();
            this.vote();
            this.initSlide();                    
        },
        bindFun:function(){
            var t = this;
            t.doResize();
            $(window).resize(function(){
                t.doResize();
            });                   
            $(".videoplayBtn").on('click',function(){                
                var mediasrc = $(this).attr("data-mediaSrc");
                t.initVideo(mediasrc).init();
                $(".videowrap").fadeIn();
            });               
            $(".videowrap .bg,.icon-close-video").on('click',function(){    
                $("#player").remove();
                $(".videowrap").fadeOut();
            });   
        },  
        doResize:function(){
            var w = $(window).width();
            w > 1600 ? $(".background").css({"width":"100%","left":"0","margin-left":"0"}) : $(".background").css({"width":"1600px","left":"50%","margin-left":"-800px"})            
        },
        vote:function(){        
            var voted = false,supported;  
            $(".vote-btn").each(function(){
                var isSupport = $(this).attr("data-issupport");    
                if(isSupport == "true"){
                    supported = true;                                                        
                }else if(undefined == typeof supported && isSupport == "false"){
                    supported = false;
                }
            });
            if(supported){
                $(".votednum").show();
                $(".unvote").hide();
            }else{
                $(".votednum").hide();
                $(".unvote").show();
            }
            $(".vote-btn").live("click",function(){     
                if(voted){
                    return false
                }               
                voted = true;
                var sid = $(this).attr("data-sid"),
                    $this = $(this);
                $.post("http://www.shihuo.cn/api/qixi?act=ajaxSupport&sid="+sid,function(data){
                    datas = $.parseJSON(data);
                    if(datas.status == 410 || datas.status == 405){
                        alertbox.show({
                            title:datas.msg,
                            confirmTxt:"确定",
                            content:"<img src='/images/trade/activity/qixi/coupon.jpg'>",
                            confirm:function(){
                                $this.find(".votednum").text(datas.data.num);
                                $(".votednum").show();
                                $(".unvote").hide();
                            }
                        });                        
                    }else if(datas.status == 411){
                        alertbox.show({
                            title:datas.msg,
                            confirmTxt:"确定",
                            content:""
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
                            content:""
                        });
                    }     
                    voted =false;               
                });                
            });
        },
        initSlide:function(){
            $(".grid").each(function(){
                var length = $(this).find(".slidecontent li").length,
                    index = $(this).index();
                if(length > 3){
                    //轮播
                    new gallerySlider({
                        tc: ".grid"+index+" .slidecontent",
                        img : ".grid"+index+" li img",
                        prev: ".grid"+index+" .icon-arrow-left",
                        next : ".grid"+index+" .icon-arrow-right",
                        cellmargin : 30
                    }).init();
                }else{
                    $(this).find(".arrow").hide();
                }                
            });
        },
        initVideo:function(mediasrc){                                        
            var qixiVideo = new videoModule({
                akamaiBaseDirectory: "http://shihuo.hupucdn.com/",
                audioOnly: !1,
                autoplay: 1,
                controls: 1,
                disablePause: !1,
                displayWidth: "912",
                displayHeight: "512",
                fadeOutTime: 1,
                iPadPlayBtnID: "",
                loop: !1,
                mediaSrc: mediasrc,
                mediaSrciPad : mediasrc,
                mediaTargetID: "mediaTarget",
                playerName: "player",
                projectDirectory: "http://c1.hoopchina.com.cn/css/video/",
                skipBtnID: "",
                soundBtnID: "",
                thereCanBeOnlyOne: !1,
                volume: .8
            });            
            return {
                init:function(){                                                       
                    qixiVideo.init();
                    qixiVideo.play();  
                },
                kill:function(){
                    qixiVideo.kill();
                }
            }
        }

    };   
    qixi.init(); 
});