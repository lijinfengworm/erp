define(function(){
    var CountDown = function() {
        this.timeboxClass = ".timebox li";
        this.slideClass = ".tuangou-wrapper .grid li";

        this.init = function () {
            var $timebox = $(this.timeboxClass),
                $slideobj = $(this.slideClass),
                maxtimearr = [],
                dom;
            var that = this;
            var maxt = $timebox.attr("data-timer");

            if($slideobj.length==0){
                maxtimearr[0] = maxt;
                var timer0 =setInterval(function(){countEvent(that.timeboxClass,0)},1000);
                return false
            }
            for (var i = 0; i < $slideobj.length; i++) {
                //dom = '<li><span class="time"> £”‡ ±º‰:</span><i class="time_h">00</i><span>:</span><i class="time_m">00</i><span>:</span><i class="time_s">00</i></li>';
                //$(".timebox").append(dom);
                maxtimearr[i] = maxt;
                eval("var timer" + i + "=setInterval(function(){countEvent(\'" + this.timeboxClass + "\'," + i + ")},1000);");
            }
            function countEvent(obj, index) {
                var $thisobj = $(obj).eq(index);
                if (maxtimearr[index] >= 0) {
                    var seconds = maxtimearr[index] % 60;
                    var minutes = Math.floor((maxtimearr[index] / 60)) > 0 ? Math.floor((maxtimearr[index] / 60) % 60) : "0";
                    var hours = Math.floor((maxtimearr[index] / 3600)) > 0 ? Math.floor((maxtimearr[index] / 3600) % 24) : "0";
                    var day = Math.floor((maxtimearr[index] / 86400)) > 0 ? Math.floor((maxtimearr[index] / 86400) % 30) : "0";

                    if (day > 0) {
                        hours = hours + day * 24;
                    } else {
                        hours = hours >= 10 ? hours : '0' + hours;
                    }
                    minutes = minutes >= 10 ? minutes : '0' + minutes;
                    seconds = seconds >= 10 ? seconds : '0' + seconds;
                    $thisobj.find(".time_h").text(hours);
                    $thisobj.find(".time_m").text(minutes);
                    $thisobj.find(".time_s").text(seconds);
                    --maxtimearr[index];
                }
                else {
                    eval("clearInterval(timer" + index + ")");
                    $thisobj.find(".time_m").text("00");
                    $thisobj.find(".time_s").text("00");
                }
            }
        }
    };
    return CountDown;
});