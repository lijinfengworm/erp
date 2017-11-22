var shihuoPop = {//弹窗
        cExpires: false,
        cName: 'shihuoPopup',
        init:function(cExpires){
            /*this.cExpires = cExpires;
            if((__daceDataNameOfChannel == 'sh_haitao' || __daceDataNameOfChannel == 'sh_home' )
                && (document.location.href.indexOf('qixi') == -1)
            ){
                if(!document.cookie.match(this.cName)){
                    this.show();
                    this.setCookie();
                }
            }*/
            if(document.location.href.indexOf('xinshoudali') == -1 ){
                this.show();
            }
        },
        show:function(){
          /*  var str2 = '<div id="xinshoudali" style="width:768px; height:585px; position:fixed; left:0; top:0; z-index:95;">' +
                    '<img src="/images/trade/activity/songquan/qixi.png" />' +
                    '<a href="http://www.shihuo.cn/special/index?id=293#from=pctanchuang" style="position: absolute; width:230px; height:60px; background-color:#000; opacity:0; filter:alpha(opacity=0); top:435px; left:307px; cursor:pointer;"></a>' +
                    '<div class="cl-e" style="position: absolute; top:0px; right:100px; cursor:pointer;">' +
                    '<img src="/images/trade/activity/songquan/showdayClose.png" id="popClose"/>' +
                    '</div>' +
                    '</div>',
            str3 = "<div class='body-mask' style='position:fixed; top:0; left:0; width:100%; height:" + ($(window).height()) + "px; background-color:#000;  z-index:91;'></div>";*/
           /* $(str2).appendTo('body');
            $(str3).appendTo('body');
            $("#xinshoudali").css({
            left:(($(window).width() - parseInt($("#xinshoudali").outerWidth())) / 2),
            top:(($(window).height() - parseInt($("#xinshoudali").outerHeight())) / 2)
            });
            $("body").append(str3);
            $(".body-mask").css("opacity", 0);
            $(".body-mask").animate({
            "opacity": 0.8
            });

            this.close();*/

            var str = '<div id="shai-wu" style="position: absolute; top:310px; left:-110px;"><a style="position:fixed;" href="http://www.shihuo.cn/haitao/xinshoudali#icon" target="_blank"><img src="/images/trade/activity/songquan/xinren.png" width="100" /></a></div>';

            $(str).appendTo('.nav-area .area-min');
        },
       close:function(){
            $('#popClose').live('click',function(){
                $('#xinshoudali').remove();
                $('.body-mask').remove();
            })
       },
       setCookie:function(){
            //加入cookie 至0点
            var exdate=new Date();
            exdate.setDate(exdate.getDate()+this.cExpires);
            document.cookie=this.cName+"=1;expires="+exdate.toGMTString();
            }
       }
