requirejs.config({
    baseUrl:"/js/kaluli/",
    paths:{
        "underscore":"lib/underscore"
    }
})
require(["underscore"],function(underscore){    
    var hpContent = $(".hpContent"),
        tpl = $("#tpl").html();

    var data = {
        list:[
            {
                gridname:"aboutkaluli",
                headImg:"faq-2-head.jpg",
                contentImg:["faq-2-image1"]
            },
            {
                gridname:"message",
                headImg:"faq-2-image2.jpg",
                contentImg:[]
            },
            {
                gridname:"merchants",
                headImg:"faq-3-head.jpg",
                contentImg:["faq-3-image1","faq-3-image2","faq-3-image3","faq-3-image4","faq-3-image5","faq-3-image6","faq-3-image7","faq-3-image8","faq-3-image9","faq-3-image10"]
            },
            {
                gridname:"",
                headImg:"faq-4-head.jpg",
                contentImg:["faq-4-image1","faq-4-image2"]
            },
            {
                gridname:"contact",
                headImg:"faq-5-head.jpg",
                contentImg:["faq-5-image1"]
            },
            {
                gridname:"commonProblem",
                headImg:"faq-6-head.jpg",
                contentImg:["faq-6-image1"]
            }
        ]
    };
    var template = _.template(tpl);
    var html = template(data);
    hpContent.html(html);
    

    var triggerEvent = function(){
        var st = $(window).scrollTop(),starr= [];
        $(".grid").each(function(){
            var thistop = $(this).offset().top;
            starr.push(thistop);
        });           
        for(var s=0;s<starr.length;s++){
            var i = s+1;
            if(st < starr[0]){
                $(".grid-0 img").trigger("appear");
                return false
            }   
            if(st > starr[s] && st < starr[i]){
                $(".grid-"+s+" img").trigger("appear");
                return false
            }         
        }
    }

    var scrolltopNav = function(){
        var st = $(window).scrollTop(),
            ot = $(".bg1").offset().top+85,
            ww = $(window).width(),
            thisw = $(".menu").width(),
            left = Math.round((ww-thisw)/2);
        if(st >= ot){
            $(".menu").css({"position":"fixed","top":'0px',"left":left+"px","width":'864px'});
        }else{
            $(".menu").css({"position":"absolute","top":'auto',"left":"108px"});
        }
    }
    function GetQueryString(name){
         var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
         var r = window.location.hash.substr(1).match(reg);
         if(r!=null)return  unescape(r[2]); return null;
    }
    $(function(){

        triggerEvent();
        $("#contact .grid-content").click(function(){
            window.open('//kefu.qycn.com/vclient/chat/?websiteid=103972&visitorid=462496732&m=pc&originPageTitle=%E5%8D%A1%E8%B7%AF%E9%87%8C-%E4%B8%93%E4%B8%9A%E7%9A%84%E8%BF%90%E5%8A%A8%E4%BF%9D%E5%81%A5%E5%93%81%E5%95%86%E5%9F%8E&originPageUrl=http%3A%2F%2Fkaluli.com%2F&sessionid=visitor-1432691898&winmode=0');
        });
        scrolltopNav();
        $(window).scroll(function(){
            scrolltopNav();
        })
    });
    $(window).load(function(){        

        $(".hpBackground img").trigger("appear"); 

        var anchorEvent = function(){
            var anchor = GetQueryString("anchor");
            if(anchor !=null && anchor.toString().length>1)
            {
               $(window).scrollTop($("#"+anchor).offset().top-50);
               triggerEvent();
            }            
        }        
        anchorEvent();
        $(".nav-wrapper .top,.footer").find("li").click(function(){
            setTimeout(anchorEvent,100);
        })
    });
})