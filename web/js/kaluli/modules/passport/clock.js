/**
 * Created by jiangyanghe on 16/6/30.
 */
define(function(){
    "use strict";
    var clock = {
        /**
         * 倒计时插件
         * @param count 总的倒计时插件
         * @param o 倒计时显示的对象，
         */
        count_down:function(count,o) {
            var wait = count;
            time(o);
            function time(o) {
                if (wait == 0) {
                    o.removeAttr("disabled");
                    o.css({'color':'#ae7c74','background-color':'#ffe3d6'});
                    o.text('点击获取验证码');
                    wait = 60;
                } else {
                    o.attr("disabled", true);
                    o.css({'color':'#999','background-color':'#ebebeb'});
                    o.text("重新发送(" + wait + ")");
                    wait--;
                    setTimeout(function () {
                            time(o)
                        },
                        1000)
                }
            }
        }
    };

    return clock;
});