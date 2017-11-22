/**
 * author  : wangzhan
 * date    : 2015/6/24
 * version : 1.0
 */
(function($){
    var ic_nav = $('.ic-nav'),
        flag = false;
    ic_nav.click(function () {
        var that = $(this);
        if (!flag) {
            that.siblings('div').show();
            flag = true;
        } else {
            that.siblings('div').hide();
            flag = false;
        }
    });
}(Zepto));
