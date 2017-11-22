function  start_sync(ev) {
    ev.prop('disabled',1).text(ev.data('disabled-msg')).next().removeClass('icon-loading-hide').addClass('icon-loading');
}
function  end_sync(ev,res) {
    ev.prop('disabled',0).text(ev.data('msg')).next().removeClass('icon-loading').addClass('icon-loading-hide');
    ev.parent().find('.time_span').removeClass('timeout').addClass('timeout_target');
    setTimeout(function(){
        ev.parent().find('.time_span').removeClass('timeout_target').delay(200).addClass('timeout');
    },350);
    if(res && res.data.humanize_time) {
        $('#' + res.data.type + "_timeout").html(res.data.humanize_time + "    -    " + res.data.update_time);
    }
}


//同步创建订单
function sync_order_id(_this) {
    var _url = _this.data('sync-url');
    var _id = $('#sync_order_id').val();
    if(_id == '') {
        toast.error('请填写ID！');
        end_sync(_this);
        return;
    }
    var _url = _url.replace("000",_id);
    $.post(_url, function(res){
        if(res.status == 1) {
            toast.success(res.info);
        } else {
            toast.error(res.info);
        }
        end_sync(_this,res);
    },"json");
}


$(function(){

    /**
     * 同步按钮触发
     */
    $('.sync_btn').on('click',function(){
        var _this = $(this);
       start_sync($(this));
        //判断是否独立执行
        var sync_fun = $(this).data('sync-fun');
        //如果有自定义流程 那么执行自定义流程
        try {
            eval(sync_fun)($(this));
        } catch(e) { //否则执行默认流程
            var _url = $(this).data('sync-url');
            $.post($(this).data('sync-url'), function(res){
               if(res.status == 1) {
                   toast.success(_this.data('success-msg'));
               } else {
                   toast.error(res.info);
               }
               end_sync(_this,res);
            },"json");
        }
        return true;
    });







});