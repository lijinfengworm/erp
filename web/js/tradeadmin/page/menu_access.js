$(function(){

    //添加一条菜单
    $('#add_child_access').on('click',function(){
        $('.child_access_box').show();
        $('#child_access_item_box').append(getItemHTML);
    });

    //删除一条菜单
    $(document).on('click','.child_access_close',function(){
        var _id = $(this).attr('data-id');
        var $_this = this;
        Wind.use('artDialog', function () {
            dialog({
                title: false,
                content: '确定要删除吗？',
                okValue: '确定',
                ok: function () {
                    $('.child_access_item[data-id='+_id+']').remove();
                    if($('.child_access_item').length <= 0) {
                        $('.child_access_box').hide();
                    }
                },
                cancelValue: '取消',
                cancel: function () {}
            }).show($_this);
        });
    });





});
var _item_num = 10000;
//输出html
function getItemHTML() {
    _item_num++;
    return '<div class="child_access_item" data-id="'+_item_num+'">'+
        '<input type="text" class="w160 mR10" name="child_access[sign][]" />'+
    '<input type="text" class="w160 mR10" name="child_access[name][]"/>'+
    '<input type="text" class="w280 mR10" name="child_access[value][]"/>'+
    '<input type="text" class="w50" name="child_access[default][]"/>'+
    '<button type="button"  data-id="'+_item_num+'" class="child_access_close gwyy_btn fR"> - </button>'+
    '</div>';
}