/**
 * Created by PhpStorm.
 * User: songxiaoqiang
 * DATA: 2015/12/18
 */
define(function(){
   "use strict";
    var settings = {
        $timebox:$("#count_box"),
        class_time_d:".time_d",
        class_time_h:".time_h",
        class_time_m:".time_m",
        class_time_s:".time_s",
        time_attribute:"leftSec"
    };

    function countDown(options,callback){

        var $timebox = void 0 !== options.$timebox ? options.$timebox : settings.$timebox,
            class_time_d = void 0 !== options.class_time_d ? options.class_time_d: settings.class_time_d,
            class_time_h = void 0 !== options.class_time_h ? options.class_time_h: settings.class_time_h,
            class_time_m = void 0 !== options.class_time_m ? options.class_time_m: settings.class_time_m,
            class_time_s = void 0 !== options.class_time_s ? options.class_time_s: settings.class_time_s,
            time_attribute = void 0 !== options.time_attribute ? options.time_attribute : settings.time_attribute;


        var max_time = $timebox.attr(time_attribute);
        var timer = setInterval(function(){
            countEvent(max_time)
        },1000)

        function countEvent(){
            if(max_time >=0){
                var seconds = max_time % 60;
                var minutes = Math.floor((max_time  / 60)) > 0? Math.floor((max_time  / 60) % 60) : "0";
                var hours = Math.floor((max_time  / 3600)) > 0? Math.floor((max_time  / 3600) % 24) : "0";
                var day = Math.floor((max_time  / 86400)) > 0? Math.floor((max_time  / 86400) % 30) : "0";

                if(day<=0){
                    // $timebox.find(class_time_d).remove();
                    hours = hours>=10?hours:'0'+hours;
                }else{
                    $timebox.find(class_time_d).text(day);
                }

                minutes = minutes>=10?minutes:'0'+minutes;
                seconds = seconds>=10?seconds:'0'+seconds;

                $timebox.find(class_time_h).text(hours);
                $timebox.find(class_time_m).text(minutes);
                $timebox.find(class_time_s).text(seconds);
                --max_time;
            }else{
                clearInterval(timer);
                $timebox.find(class_time_d).text("0");
                $timebox.find(class_time_h).text("00");
                $timebox.find(class_time_m).text("00");
                $timebox.find(class_time_s).text("00");
                $timebox.attr("data-status","clearInterval");
                'function' == typeof callback && callback();
            }

        }
    };

    return countDown;
});