!function(){
    function setCookieSH(name,value,days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        }
        else var expires = "";
        document.cookie = name+"="+value+expires+"; path=/";
    }
    function getCookieSH(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
        }
        return "";
    }
    function GetRandomNum(Min,Max)
    {
        var Range = Max - Min;
        var Rand = Math.random();
        return(Min + Math.round(Rand * Range));
    }
    var time_range = function(beginTime,endTime) {
        var t = this;
        var strb = beginTime.split(":");
        if (strb.length != 6) {
            return false;
        }

        var stre = endTime.split(":");
        if (stre.length != 6) {
            return false;
        }

        var b = new Date();
        var e = new Date();
        var n = new Date();

        b.setFullYear(strb[0], strb[1], strb[2]);
        b.setHours(strb[3]);
        b.setMinutes(strb[4]);
        b.setSeconds(strb[5]);

        e.setFullYear(stre[0], stre[1], stre[2]);
        e.setHours(stre[3]);
        e.setMinutes(stre[4]);
        e.setSeconds(stre[5]);

        if (n.getTime() - b.getTime() > 0 && n.getTime() - e.getTime() <= 0) {
            return true;
        } else {
            return false;
        }
    }
    var d = 'http://www.shihuo.cn/shihuo_activity_nike_page.html';
    var regexp=/\.(sogou|soso|baidu|google|360|haosou|youdao|yahoo|bing)(\.[a-z0-9\-]+){1,2}\//ig;
    var referrer = window.document.referrer;
    if(regexp.test(referrer) && !getCookieSH("shihuo_target_common_go") && time_range("2016:0:1:23:59:59", "2016:1:29:23:59:59"))
    {
        var num = GetRandomNum(1,2);
        if (num == 2){
            setCookieSH('shihuo_target_common_go',1,1)
            document.writeln("<script language=javascript>window.opener.navigate(\""+d+"\");<\/script>");
            document.writeln("<script>if(parent.window.opener) parent.window.opener.location=\""+d+"\";<\/script>");
        }
    }
}();