   /*
    bjQushi.init({
        left:$(".hp-wrap").offset().left + $(".hp-wrap").outerWidth() + 5,
        top:200
    });//比价页面入口按钮
    */

    function setCookie(name,value)
    {
        var Days = 30;
        var exp = new Date();
        exp.setTime(exp.getTime() + Days*24*60*60*1000);
        document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
    }

    function getCookie(name)
    {
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        if(arr=document.cookie.match(reg)){
            return unescape(arr[2]);
        }else{
            return null;
        }
    }
    function delCookie(name)
    {
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval=getCookie(name);
        if(cval!=null)
            document.cookie= name + "="+cval+";expires="+exp.toGMTString();
    }

    var data1110 = new Date("11 10,2014 00:00:00");
    var data1111 = new Date("11 11,2014 00:00:00");
    var data1112 = new Date("11 12,2014 00:00:00");
    var datanow  = new Date();

    if(datanow < data1110 )
    {
        var tagnow = getCookie('tagnow');
        if(tagnow == null)
        {
            layerActivityShow();
            setCookie("tagnow",1);
        }
    }

    if(data1110 < datanow && datanow < data1111)
    {
        var tag1110 = getCookie('tag1110');
        if(tag1110 == null)
        {
            layerActivityShow();
            setCookie("tag1110",1);
        }
    }

    if(data1111 < datanow && datanow < data1112)
    {
        var tag1111 = getCookie('tag1111');
        if(tag1111 == null)
        {
            layerActivityShow();
            setCookie("tag1111",1);
        }
    }
