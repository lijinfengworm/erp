$(function(){


    //首页 二级 分类搜索
    $('#trd_news_index_filters_type').on('change',function(){
        var  _val =  $(this).val();
        var _url = $(this).data('url');
        if (_val == 0 || _val == ''){
            $('#trd_news_index_child_type').empty();
            $('#trd_news_index_child_type').append("<option selected=\"selected\" value=\"\">二级分类</option>");
            return true;
        }
        $.ajax({
            type: "GET",
            url: _url,
            data: {root_id:_val},
            dataType: "json",
            success: function(data){
                if (!data.status){
                    $('#trd_news_index_child_type').empty();
                    $('#trd_news_index_child_type').append(data.data);
                }
            }
        });
        return true;
    });


    $('#role_id').on('change',function(){
        init_author();
    });


});  //jquery end




//初始化发布人
function init_author() {
    //获取选择组
    var role_id = $('#role_id').val();
    if(role_id == 0 || role_id == '') {
        $('#author_id').html('<option value="">--用户名--</option>');
        return true;
    }
    //获取选中人
    var _url = $('#author_id').attr('select-url');
    $.ajax({
        type: "GET",
        url: _url,
        data: {role_id:role_id},
        dataType: "json",
        success: function(result){
            if(result.status == 1) {
                var _html = '';
                _html += '<option value="">--用户名--</option>';
                if(result.data) {
                    $.each(result.data, function (i, v) {
                        _html += '<option value="' + v.hupu_uid + '">' + v.username + '</option>';
                    });
                }
                $('#author_id').html(_html);
            }
        }
    });
}





function  init_child_type() {
    var  _val =  $('#trd_news_index_filters_type').val();
    if (_val == 0 || _val == '') return true;
    var _url = $('#trd_news_index_filters_type').data('url');
    var _current_id = $('#trd_news_index_child_type').attr('data-child-id');
    $.ajax({
        type: "GET",
        url: _url,
        data: {root_id:_val,children_id:_current_id},
        dataType: "json",
        success: function(data){
            if (!data.status){
                $('#trd_news_index_child_type').empty();
                $('#trd_news_index_child_type').append(data.data);
            }
        }
    });
}