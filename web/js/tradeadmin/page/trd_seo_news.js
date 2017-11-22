
var   currentAjax = false,
      isClickClose  = false;

$(function(){

    $('#filter_title').on('change',function(){
        var _this = $(this).val();
        $('#filter_title_uid').val('');
        if(_this == 1) {
            $('#filter_title_uid').attr('placeholder','请输入标题');
            $('#filter_title_uid').attr('name','trd_seo_news_filters[title][text]');
        } else {
            $('#filter_title_uid').attr('placeholder','请输入数字ID');
            $('#filter_title_uid').attr('name','trd_seo_news_filters[author_id][text]');
        }
    });


    //关闭窗口
    $('.popup-close').on('click',function(){
        isClickClose = true;
        hide_popup();
    });



    //转代购
    $('.daigou_conv').on('click',function(e){
        //取消事件的默认动作
        e.preventDefault();
        //终止事件 不再派发事件
        e.stopPropagation();
        //显示等待弹出层
        $('.popup-mask').show();
        $('.popup-message-small').show();
        var _url = $(this).attr('ajax-url');
        var currentAjax = $.ajax({
            type: "GET",
            url: _url,
            dataType: "json",
            success: function(data){
                hide_popup();
                if(data.status == 1) {
                    if(data.url) {
                        window.location = data.url;
                    } else {
                        Wind.use('artDialog', function () {
                            dialog({
                                title: '提示',
                                content: data.info,
                                okValue: '确定',
                                cancelValue: '关闭',
                                fixed:true,
                                cancel:function(){
                                    reloadPage(window);
                                },
                                ok:function(){
                                    reloadPage(window);
                                }
                            }).show();
                        });
                    }
                } else {
                    toast.error(data.info);
                }
            },error: function () {
                hide_popup();
                if(!isClickClose) {
                    toast.error('查询失败，请联系开发人员。。。。');
                }
            }
        });






    });





    //首页 二级 分类搜索
    $('#trd_news_index_filters_type').on('change',function(){
        var  _val =  $(this).val();
        var _url = $(this).data('url');
        if (_val == 0 || _val == ''){
            $('#trd_seo_news_index_child_type').empty();
            $('#trd_seo_news_index_child_type').append("<option selected=\"selected\" value=\"\">二级分类</option>");
            return true;
        }
        $.ajax({
            type: "GET",
            url: _url,
            data: {root_id:_val},
            dataType: "json",
            success: function(data){
                if (!data.status){
                    $('#trd_seo_news_index_child_type').empty();
                    $('#trd_seo_news_index_child_type').append(data.data);
                }
            }
        });
        return true;

    });
});



//隐藏遮罩层
function  hide_popup(){
    if(currentAjax) {currentAjax.abort();}
    $('.popup-mask').hide();
    $('.popup-message-small').hide();
}



//init_child_type();

function  init_child_type() {
    var  _val =  $('#trd_seo_news_index_filters_type').val();
    if (_val == 0 || _val == '') return true;
    var _url = $('#trd_seo_news_index_filters_type').data('url');
    var _current_id = $('#trd_seo_news_index_child_type').attr('data-child-id');
    $.ajax({
        type: "GET",
        url: _url,
        data: {root_id:_val,children_id:_current_id},
        dataType: "json",
        success: function(data){
            if (!data.status){
                $('#trd_seo_news_index_child_type').empty();
                $('#trd_seo_news_index_child_type').append(data.data);
            }
        }
    });
}