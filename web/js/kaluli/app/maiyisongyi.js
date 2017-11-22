requirejs.config({
    baseUrl:"//c1.hoopchina.com.cn/js/kaluli/",
    paths:{
        underscore:"lib/underscore",
        groupData:"modules/activity/maiyisongyi/groupData"
    }
});

require(['groupData','underscore'],function(groupData){
    var tpl = _.template($("#tpl").html());
    _.each(groupData,function(obj){
        $(".pagecontent").append(tpl(obj));
    });

    var length = groupData.length,
        navEle = "<ul class='scrollNav'><li class='backtoTop'>TOP</li></ul>";
    $(".pagecontent").append(navEle);
    for(var i=0;i<groupData.length;i++){
        length--;
        var title = groupData[length].title;
        $(".scrollNav").prepend("<li>"+title+"</li>");        
    }   

    scrollNav();
    $(window).scroll(scrollNav);
    $(window).resize(scrollNav);
    function scrollNav(){
        var w = $(window).width(),
            st = $(window).scrollTop(),
            ot = $(".pagecontent").offset().top,
            pw = $(".pagecontent").width(),
            or = +Math.round((w-pw)/2)-171;                   
        if(st > ot){
            $(".scrollNav").css({position:"fixed",right:or+"px",top:"30px"});
            if(w < 1424 ){
                $(".scrollNav").css("right","0");
            }
        }else{
            $(".scrollNav").css({position:"absolute",right:"-166px",top:"105px"});
            if(w < 1424 && w > 1080){                        
                $(".scrollNav").css("right","-65px");
            }else if(w <= 1080){
                $(".scrollNav").css("right","0");
            }
        }        
    }

    $(".scrollNav li").click(function(){        
        var index = $(this).index();
        if(index > 2){
            return false
        }
        var st = $(".grid").eq(index).offset().top;       
        $("html,body").animate({scrollTop:st}, 350);
    });

    $(".backtoTop").click(function(){
        $("body,html").animate({scrollTop:0}, 350);
    });
});