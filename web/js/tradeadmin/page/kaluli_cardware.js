Wind.use('My97DatePicker');
//卡号HTML

var _html = '<div><div class="txt-line b-t-d-333" ></div>\
                <div class="form-item">\
                <label class="item-label"> new</label>\
                <div class="controls">\
                &nbsp;&nbsp;&nbsp;<label>满金额 ：</label><input name="cdata[attr][]"  class="w40" type="text" />\
                &nbsp;&nbsp;&nbsp;<label>礼品卡面额 ：</label><input class="w40" name="cdata[amount][]" type="text" />\
                &nbsp;&nbsp;&nbsp;<label>警戒数量值 ：</label><input class="w40" name="cdata[alert_num][]" type="text" />\
                &nbsp;&nbsp;&nbsp;<label>剩余数量 ：</label> <span class="c-999">未导入</span>\
                &nbsp;&nbsp;&nbsp;<a class="gwyy_btn remove_card_line" href="javascript:void(0);">删除</a>\
                </div>\
            </div></div>';






$(function(){

    $('.error_list').click(function(){
        $(this).hide();
    });

    //新增一条
    $('#add-card').on('click',function(){
        $('#form-item-card-box').append(_html);
    });

    //删除一行
    $(document).on('click','.remove_card_line',function(){
        var _this = $(this);
        var $_this = this;
        Wind.use('artDialog', function () {
            dialog({
                title: false,
                content: '确定要删除吗？',
                okValue: '确定',
                ok: function () {
                    _this.parent().parent().parent().remove();
                },
                cancelValue: '取消',
                cancel: function () {}
            }).show($_this);
        });
    });












});




















