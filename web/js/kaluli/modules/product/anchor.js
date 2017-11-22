define(['commentList','salesInfo'],function(commentList,salesInfo){
    function anchor(opt){
        this.opt = opt === void 0 ? "" : opt;
    }
    anchor.prototype={
        defaults:{
            menu : ".menu li",
            anchor: ".anchorwrap",
            link_comment:".link-comment",
            jump_comment:"#jump_comment",
            id_comment:"#comment"
        },
        init:function(){
            var t = this;
            $(t.defaults.menu).click(function(){
                $(t.defaults.menu).removeClass("cur");
                var self = $(this),
                    index =self.attr("data-link"),
                    proinfo = $("#proinfo").height(),
                    comment = $("#comment").height(),
                    FAQ = $(".FAQ").height(),
                    switchbox0 = $(".switchbox[data-index=0]").offset().top;
                if(index == "proinfo"){
                    $(window).scrollTop(switchbox0-60)
                }else if(index == "comment"){
                    $(window).scrollTop(switchbox0+proinfo-comment-60)
                }else{
                    $(window).scrollTop(switchbox0+proinfo)
                }
                // $('.switchwrap').removeClass('active');
                // $('#'+index).addClass('active');

                if(index == 'sales'){
                    commentList.init();
                    commentList.ajaxBefor = true;
                }
                // if(index == 'comment'){
                //     salesInfo.init();
                //     salesInfo.ajaxBefor = true;
                // }


                // if($("#"+index).length == 0){
                //     return false
                // }else{
                //     var sTop = $("#"+index).offset().top - 56;
                //
                //     console.log(sTop+'++++++++');
                //     $("body,html").animate({"scrollTop":sTop},0,"swing");
                // }
                if(!self.hasClass('on')){   
                    self.addClass('on');
                    self.siblings().removeClass('on');
                }                   
            });
            $(t.defaults.jump_comment).click(function () {
                $('.switchwrap').removeClass('active');
                $(t.defaults.id_comment).addClass('active');
                $(t.defaults.menu).removeClass('on');
                $(t.defaults.link_comment).addClass('on');
                var commentTop = $(t.defaults.id_comment).offset().top;
                console.log(commentTop);
                $("body,html").animate({"scrollTop":commentTop},0,"swing");
            });
        }        
    };
    return anchor
});