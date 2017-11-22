define(["alertbox","jqueryColor","tinyscrollbar"],function(alertbox,jqueryColor){
    var comment = {
        init:function(){
            this.bindFun();
            this.ajaxHandle();
        },
        bindFun:function(){
            var t = this;
            $.fn.upSlide=t.upSlide;
            $(".scrollwrap").each(function(){
                if($(this).find(".overview").height() > $(this).height()){
                    $(this).find(".scrollbar").show();
                    $(this).tinyscrollbar();                    
                    $(this).upSlide();
                }else{
                    $(this).find(".scrollbar").hide();
                }
            });
            //this.upSlide();
        },
        upSlide:function(){            
            var speed=50;
            var $wrap1 = $(this).find(".wrap1"),
            $wrap2 = $(this).find(".wrap2"),            
            $wrap = $(this).find(".overview");

            //$wrap2.html($wrap1.html());
            //demo2.innerHTML=demo1.innerHTML
            function Marquee(){
                var scrollBarData = $wrap.parents(".scrollwrap").data("plugin_tinyscrollbar");
                var wrapScrollTop = scrollBarData.contentPosition;   
                var wrap2T =  $wrap1.height() -  scrollBarData.viewportSize;                         
                if(wrap2T - wrapScrollTop <= 0){                    
                    scrollBarData.update(0);                    
                }else if($wrap1.height()-100 > $wrap.parents(".scrollwrap").height()){
                    wrapScrollTop++;
                    scrollBarData.update(wrapScrollTop);
                }               
            }
            var MyMar;
            var MyMar=setInterval(Marquee,speed);
            $wrap[0].onmouseover=function() {clearInterval(MyMar)}
            $wrap[0].onmouseout=function() {MyMar=setInterval(Marquee,speed)}
        },
        ajaxHandle:function(){
            $(".sendbtn").on("click",function(){
                var $textarea = $(this).parent().find("textarea"),
                    content =$textarea.val(),
                    sid = $(this).attr("data-sid"),
                    $this = $(this);
                $.post("http://www.shihuo.cn/api/qixi",{act:"ajaxComment",sid:sid,content:content},function(data){
                    var datas = $.parseJSON(data);
                    if(datas.status == 200){
                        var li = '<li class="shake"><p>'+username+'</p><p>'+content+'</p></li>',
                            $ul = $this.parents(".message").find(".wrap1"),
                            scrollBarData = $("#"+sid).find(".scrollwrap").data("plugin_tinyscrollbar");                                            
                        $ul.prepend(li);   
                        if("undefined" != typeof scrollBarData){
                            scrollBarData.update(0);
                            scrollBarData.update("relative");   
                        }                                                             
                        $ul.find(".shake").animate({"background-color":"transparent","color":"#000000","opacity":"1"},500);
                        $textarea.val('');
                    }else if(datas.status == 501){
                        alertbox.show({
                            title:datas.msg,
                            confirmTxt:"确定",
                            confirm:function(){
                                window.location.href = datas.data.jumpUrl; 
                            }                                                      
                        });
                    }else{
                        alertbox.show({
                            title:datas.msg,
                            confirmTxt:"确定"                                                      
                        });
                    }                    
                }); 
            });
        }
    }

    return comment
});