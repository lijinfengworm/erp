//初始化分类信息
var SPECIAL_VAR = {
    cateid:$('#trd_special_cateid').val(),
    change_cateid:[$('#trd_special_cateid').val()],
    show_journal:$('#trd_special_cateid').find("option:selected").attr('show_journal'),
    is_journal:$('#trd_special_cateid').find("option:selected").attr('is_journal'),
    tmpid:$('#trd_special_template').val(),
    tmp_one:"<option value='1' selected='selected'>模板1</option><option value='2'>模板2</option>",
    tmp_two:'<option value="4" selected="selected">模板4</option>'
};


function initSpecial() {
    var _this = this;
    this.current_tpl_id = $('#trd_special_template').val();
    this.ajax_url = $('#special-box').attr('ajax-url');
    this.init(this.current_tpl_id);
    $('#trd_special_template').on('change',function(){
        _this.init($(this).val());
    });
}

initSpecial.prototype = {
    init:function(tpl_id){
        this.showMask();
        var _this = this;
        $.ajax({
            type: "GET",
            url: _this.ajax_url,
            async: false,
            data: {tpl_id:tpl_id},
            dataType: "html",
            timeout: 5000,
            success: function(result){
                _this.hideMask();
                if(result != '') {
                    $('#special-box').html(result);
                }
            },
            error:function(msg){
                _this.hideMask();
                toast.error('加载专题模板出错，请联系程序猿。');
            }
        });
    },
    showMask:function(){
        if($('#modal-backdrop').length <= 0 ) {
            var mask_box = '<div  id="modal-backdrop" class="modal-backdrop fade in"></div>';
            $("body").append(mask_box);
        } else {
            $('#modal-backdrop').show();
        }
    },
    hideMask:function(){
        $('#modal-backdrop').remove();
    }


}



//楼层
var floor_html_one = '<div class="item_tit mT10">\
<label class="c-999 ">楼层标题：</label> <input name="catetitle[]" type="text" class="mR10" />\
<button type="button" class="J_color_pick gwyy_btn ">楼层标题颜色</button> <input name="catebarcolor[]" value="93633F"  type="text" class="mR10 w60 J_hidden_color" />\
<button type="button" class="J_color_pick gwyy_btn ">楼层按钮颜色</button> <input name="catebuttoncolor[]" value="93633F" type="text" class="mR10 w60 J_hidden_color" />\
<label class="c-999">商品数量：</label> <input type="text" name="cateitemnum[]" class="w60 " />\
<div class="fR">\
 <button type="button" class="SP_up_move  gwyy_btn">上移</button> \
 <button type="button" class="SP_down_move gwyy_btn">下移</button> \
 <button type="button" class="SP_del_line gwyy_btn gwyy_btn_red">删除</button>\
</div></div>';

//楼层
var floor_html_two = '<div class="item_tit mT10">\
<label class="c-999 ">楼层标题：</label> <input name="catetitle[]" type="text" class="mR10" />\
<button type="button" class="J_color_pick gwyy_btn ">楼层标题颜色</button> <input value="ffffff" name="catebarcolor[]"  type="text" class="mR10 w60 J_hidden_color" />\
<button type="button" class="J_color_pick gwyy_btn ">楼层按钮颜色</button> <input  value="ffffff" name="catebuttoncolor[]" type="text" class="mR10 w60 J_hidden_color" />\
<div class="fR">\
 <button type="button" class="SP_up_move  gwyy_btn">上移</button> \
 <button type="button" class="SP_down_move gwyy_btn">下移</button> \
 <button type="button" class="SP_del_line gwyy_btn gwyy_btn_red">删除</button>\
</div></div>';

//楼层
function floor_html_six(name){
    return '<div class="item_tit mT10">\
<label class="c-999 ">楼层标题：</label> <input name="catetitle[]" type="text" class="mR10" />\
<button type="button" class="J_color_pick gwyy_btn ">楼层标题颜色</button> <input name="catebarcolor[]" value=""  type="text" class="mR10 w60 J_hidden_color" />\
<button type="button" class="J_color_pick gwyy_btn ">楼层按钮颜色</button> <input name="catebuttoncolor[]" value="" type="text" class="mR10 w60 J_hidden_color" />\
<label class="c-999">商品数量：</label> <input type="text" name="cateitemnum[]" class="w60 " />\
<input name="catename[]" type="hidden" class="mR10" value="'+name+'"/>\('+name+')\
<div class="fR">\
 <button type="button" class="SP_up_move  gwyy_btn">上移</button> \
 <button type="button" class="SP_down_move gwyy_btn">下移</button> \
 <button type="button" class="SP_del_line gwyy_btn gwyy_btn_red">删除</button>\
</div></div>';
}

//楼层
function floor_html_theme_1(name){
    return '<div class="item_tit mT10">\
    <input value="<?php echo $key+1;?>" name="trd_special[info][sort][]" type="hidden" class="mR10" />\
<label class="c-999 ">楼层标题：</label> <input name="trd_special[info][floor_title][]" type="text" class="mR10" />\
<label class="c-999">商品数量：</label> <input type="text" name="trd_special[info][floor_goods_num][]" class="w60 " />\
<div class="fR">\
 <button type="button" class="SP_up_move  gwyy_btn">上移</button> \
 <button type="button" class="SP_down_move gwyy_btn">下移</button> \
 <button type="button" class="SP_del_line gwyy_btn gwyy_btn_red">删除</button>\
</div></div>';
}




var   currentAjax = false,
    isClickClose  = false;


function  initIsShowJournal(_val) {
    if(_val == 0) {
        $('#journal_box').hide();
    } else {
        $('#journal_box').show();
    }
}

initIsShowJournal($("input:radio[name='trd_special[show_journal]']:checked").val());

$(function(){

    $("input:radio[name='trd_special[show_journal]']").on('change',function(){
        initIsShowJournal($(this).val());
    });


    //自动匹配模板
    var _tpl_id = $('#tpl_label').attr('tpl-id');
    if(_tpl_id)  $('#trd_special_template').val(_tpl_id);

    //选择专题模板
    $('#trd_special_template').on('change',function(){
        var _id = $(this).val();
        var _url = $('#tpl_label').attr('data-url');
        if(_id)
        {
            _url = _url.replace('+++',_id);
            window.location = _url;
        }
    });

    $(document).on('change','.J_hidden_color',function(){
        var bg_color = $(this).val();
        $(this).css('background',bg_color);
    });


    //添加楼层
    $('#add_floor,#add_goods_floor,#add_nichtware_floor').on('click',function(){
        var type = $(this).attr('data-type');

        if(type == 'one') {
            var floor_html = floor_html_one;
        } else if(type == 'two'){
            var floor_html = floor_html_two;
        }else if(type == 'six'){
            var name = $(this).attr('data-name');
            var floor_html = floor_html_six(name);
        }else if(type == 'seven'){
            var floor_html = floor_html_one;
        }else if(type == 'theme_1' || type == 'theme_2' || type == 'theme_3'){
            var floor_html = floor_html_theme_1;
        }
        $('#special_floor_item').append(floor_html);
    });


    //判断往期回顾
    $("input:radio[name='one[foot_show]']").on('change',function(){
        is_show_outher();
    });
    $("input:radio[name='two[foot_show]']").on('change',function(){
        is_show_outher();
    });
    $("input:radio[name='four[foot_show]']").on('change',function(){
        is_show_outher();
    });
    $("input:radio[name='six[foot_show]']").on('change',function(){
        is_show_outher();
    });

    //删除标签
    $(document).on('click','.special_tag',function(){
        if(confirm('确定删除?')){
            $(this).parent().hide(1500).remove();
        }
    })

    //上移 下移
    $(document).on('click','.SP_up_move',function(){
        var is_edit = $(this).data('edit');
        if(is_edit == 1) {
            link_move_data($(this),'up');
        } else {
            var _parent = $(this).parent().parent();
            _parent.prev().insertAfter(_parent);
        }
    });
    $(document).on('click','.SP_down_move',function(){
        var is_edit = $(this).data('edit');
        if(is_edit == 1) {
            link_move_data($(this),'down');
        } else {
            var _parent = $(this).parent().parent();
            _parent.next().insertBefore(_parent);
        }
    });


    //内容 上移 下移
    $('.SP_data_up_move, .SP_data_down_move').on('click',function(){
        var _type = $(this).attr('data-type');
        var _key = $(this).attr('data-key');
        var _tag = $(this).attr('data-tag');
        var _parent = $('#item_box_inner_'+_key+'_'+_tag);
        var _index = _parent.index();
        var _max = _parent.attr('max-num') - 1;
        if((_type == 'up' && _index == 0) || (_type == 'down' && _index == _max)) return true;
        var cur_tit = $("#cateitemtitle"+_key+"_"+_tag).val();
        var cur_pic =$("#cateitemprice"+_key+"_"+_tag).val();
        var cur_file =$("#cateiteminputfile"+_key+"_"+_tag).val();
        var cur_url =$("#cateitemurl"+_key+"_"+_tag).val();
        var cur_img =$("#showimage"+_key+"_"+_tag).attr('src');
        if(_type == 'up') {
           var move_tag = parseInt(_tag) - 1;
        } else {
            var move_tag = parseInt(_tag) + 1;
        }
        $("#cateitemtitle"+_key+"_"+_tag).val($("#cateitemtitle"+_key+"_"+move_tag).val());
        $("#cateitemprice"+_key+"_"+_tag).val($("#cateitemprice"+_key+"_"+move_tag).val());
        $("#cateiteminputfile"+_key+"_"+_tag).val($("#cateiteminputfile"+_key+"_"+move_tag).val());
        $("#cateitemurl"+_key+"_"+_tag).val($("#cateitemurl"+_key+"_"+move_tag).val());
        $("#showimage"+_key+"_"+_tag).attr('src',$("#showimage"+_key+"_"+move_tag).attr('src'));

        $("#cateitemtitle"+_key+"_"+move_tag).val(cur_tit);
        $("#cateitemprice"+_key+"_"+move_tag).val(cur_pic);
        $("#cateiteminputfile"+_key+"_"+move_tag).val(cur_file);
        $("#cateitemurl"+_key+"_"+move_tag).val(cur_url);
        $("#showimage"+_key+"_"+move_tag).attr('src',cur_img);
    });

    //内容 上移 下移
    $('.SP_app_data_up_move, .SP_app_data_down_move').on('click',function(){
        var _type = $(this).attr('data-type');
        var _tag = $(this).attr('data-tag');
        var _parent = $('#item_box_inner_'+_tag);
        var _index = _parent.index();
        var _max = _parent.attr('max-num') - 1;
        if((_type == 'up' && _index == 0) || (_type == 'down' && _index == _max)) return true;
        var cur_tit = $("#itemtitle_"+_tag).val();
        var cur_intro = $("#itemintro_"+_tag).val();
        var cur_pic =$("#itemprice_"+_tag).val();
        var cur_discount =$("#itemdiscount_"+_tag).val();
        var cur_file =$("#iteminputfile_"+_tag).val();
        var cur_url =$("#itemurl_"+_tag).val();
        var cur_img =$("#showimage_"+_tag).attr('src');
        if(_type == 'up') {
            var move_tag = parseInt(_tag) - 1;
        } else {
            var move_tag = parseInt(_tag) + 1;
        }
        $("#itemtitle_"+_tag).val($("#itemtitle_"+move_tag).val());
        $("#itemintro_"+_tag).val($("#itemintro_"+move_tag).val());
        $("#itemprice_"+_tag).val($("#itemprice_"+move_tag).val());
        $("#itemdiscount_"+_tag).val($("#itemdiscount_"+move_tag).val());
        $("#iteminputfile_"+_tag).val($("#iteminputfile_"+move_tag).val());
        $("#itemurl_"+_tag).val($("#itemurl_"+move_tag).val());
        $("#showimage_"+_tag).attr('src',$("#showimage_"+move_tag).attr('src'));

        $("#itemtitle_"+move_tag).val(cur_tit);
        $("#itemintro_"+move_tag).val(cur_intro);
        $("#itemprice_"+move_tag).val(cur_pic);
        $("#itemdiscount_"+move_tag).val(cur_discount);
        $("#iteminputfile_"+move_tag).val(cur_file);
        $("#itemurl_"+move_tag).val(cur_url);
        $("#showimage_"+move_tag).attr('src',cur_img);
    });


    //内容 上移 下移
    $('.SP_up_move_7, .SP_down_move_7').on('click',function(){
        var _type = $(this).attr('data-type');
        var _tag = $(this).attr('data-tag');
        var _parent = $('#cateitem_box_inner_'+_tag);
        var _index = _parent.index();
        var _max = _parent.attr('max-num') - 1;
        if((_type == 'up' && _index == 0) || (_type == 'down' && _index == _max)) return true;
        var cur_tit = $("#cateitemtitle_"+_tag).val();
        var cur_intro = $("#cateitemintro_"+_tag).val();
        var cur_file =$("#cateiteminputfile_"+_tag).val();
        var cur_url =$("#cateitemurl_"+_tag).val();
        var cur_tags =$("#cateitemtags_"+_tag).html();
        var cur_img =$("#showimage_"+_tag).attr('src');
        if(_type == 'up') {
            var move_tag = parseInt(_tag) - 1;
        } else {
            var move_tag = parseInt(_tag) + 1;
        }
        $("#cateitemtitle_"+_tag).val($("#cateitemtitle_"+move_tag).val());
        $("#cateitemintro_"+_tag).val($("#cateitemintro_"+move_tag).val());
        $("#cateiteminputfile_"+_tag).val($("#cateiteminputfile_"+move_tag).val());
        $("#cateitemurl_"+_tag).val($("#cateitemurl_"+move_tag).val());
        $("#cateitemtags_"+_tag).html($("#cateitemtags_"+move_tag).html());
        $("#cateitemtags_"+_tag).find('input[type="hidden"]').attr('name','itemtags['+_tag+'][]');
        $("#showimage_"+_tag).attr('src',$("#showimage_"+move_tag).attr('src'));

        $("#cateitemtitle_"+move_tag).val(cur_tit);
        $("#cateitemintro_"+move_tag).val(cur_intro);
        $("#cateiteminputfile_"+move_tag).val(cur_file);
        $("#cateitemurl_"+move_tag).val(cur_url);
        $("#cateitemtags_"+move_tag).html(cur_tags);
        $("#cateitemtags_"+move_tag).find('input[type="hidden"]').attr('name','itemtags['+move_tag+'][]');
        $("#showimage_"+move_tag).attr('src',cur_img);
    });

    //内容 上移 下移
    $('.SP_up_move_6,.SP_down_move_6').on('click',function(){
        var type = $(this).attr('data-type');
        var ul = $(this).parents('ul');
        var upUl = $(this).parents('ul').prev() ;
        var downUl = $(this).parents('ul').next();

        if(type == 'up' && upUl){
            upUl.before(ul);
        }else if(type == 'down' && downUl){
            downUl.after(ul);
        }
    })

    //删除一行
    $(document).on('click','.SP_del_line',function(){
        var $_this = this,
            _this = $(this);
        Wind.use('artDialog', function () {
            dialog({
                title: false,
                content: '确定要删除这一行吗？',
                okValue: '确定',
                ok: function () {
                    //ajax 删除
                    /*var id = $('input[name="special_id"]').val();
                    if(id){
                        var url = $('input[name="special_del_line"]').val();
                        var line_id = _this.attr('data-type');

                        $.post(url,{id: id,line_id:line_id},function(msg){
                        },'json');
                    }*/

                    _this.parent().parent().remove();
                },
                cancelValue: '取消',
                cancel: function () {}
            }).show($_this);
        });
    });

    //清空一行
    $(document).on('click','.SP_empty',function(){
        var $_this = this,
            _this = $(this),
            _key = $(this).attr('data-key');
            _tag = $(this).attr('data-tag');
        Wind.use('artDialog', function () {
            dialog({
                title: false,
                content: '确定要清空这一行吗？',
                okValue: '确定',
                ok: function () {
                    clear(_key,_tag,_this);
                },
                cancelValue: '取消',
                cancel: function () {}
            }).show($_this);
        });
    });



    //清空一行
    $(document).on('click','.SP_app_empty',function(){
        var $_this = this,
            _this = $(this),
            _tag = $(this).attr('data-tag');
        Wind.use('artDialog', function () {
            dialog({
                title: false,
                content: '确定要清空这一行吗？',
                okValue: '确定',
                ok: function () {
                    clearApp(_tag);
                },
                cancelValue: '取消',
                cancel: function () {}
            }).show($_this);
        });
    });

    //抓取
    $('.SP_get').on('click',function(){
        var _key = $(this).attr('data-key');
        var _tag = $(this).attr('data-tag');
        var ajax_url = $(this).attr('ajax-url');
        var tagurl = $("#cateitemurl"+_key+"_"+_tag).val();
        //判断是抓取站内 还是 站外
        if(tagurl == '') {
            toast.error('请输入URL！');
            return false;
        }
        //如果不是站内 那么就是站外
        if(!/^[0-9]+$/.test(tagurl)){
            $.ajax({
                url: "http://ruyi.taobao.com/ext/productLinkSearch?link=" + encodeURIComponent(tagurl) + "&group=prices,item&pid=rc001",
                dataType: 'jsonp',
                success: function (data) {
                    if($.isEmptyObject(data)){
                        toast.error('没有获取到对应的商品信息，请手动填写！');
                        return false;
                    }
                    var price = parseInt(data.Item.Price);
                    var image = data.Item.LargeImageUrl;
                    var title = data.Item.Title;
                    $("#cateitemtitle"+_key+"_"+_tag).val(title);
                    $("#cateitemprice"+_key+"_"+_tag).val(price);
                    $("#cateiteminputfile"+_key+"_"+_tag).val(image);
                    $("#showimage"+_key+"_"+_tag).attr('src',image);
                },error: function () {
                    toast.error('抓取失败，请联系开发人员。。。。');
                }
            });
        } else {
            $.ajax({
                type: "POST",
                url: ajax_url,
                data: {id:tagurl},
                dataType: "json",
                success: function(result){
                    if(result.status = 1) {
                        $("#cateitemtitle"+_key+"_"+_tag).val(result.data.title);
                        $("#cateitemurl"+_key+"_"+_tag).val(result.data.url);
                        $("#cateitemprice"+_key+"_"+_tag).val(result.data.price);
                        $("#cateiteminputfile"+_key+"_"+_tag).val(result.data.image);
                        $("#showimage"+_key+"_"+_tag).attr('src',result.data.image);
                    } else {
                        toast.error(result.info);
                    }
                },error: function () {
                    toast.error('查询失败，请联系开发人员。。。。');
                }
            });
        }
    });




    //批量抓取
    $('.SP_get_all').on('click',function(){
        $('.item_box_inner').each(function(){
            var _key = $(this).attr('data-key');
            var _tag = $(this).attr('data-tag');
            var ajax_url = $(this).attr('ajax-url');
            var tagurl = $("#cateitemurl"+_key+"_"+_tag).val();
            if(tagurl){
                getDataByUrl(tagurl,_key,_tag,ajax_url);
            }
        });
    })


    // 模板6,7抓取
    $('.SP_get_6,.SP_get_7').on('click',function(){
        var _key = $(this).attr('data-key');
        var _tag = $(this).attr('data-tag');
        var ajax_url = $(this).attr('ajax-url');
        var tagurl = $("#cateitemurl"+_key+"_"+_tag).val() ? $("#cateitemurl"+_key+"_"+_tag).val() : $("#cateitemurl_"+_tag).val();

        //判断是抓取站内 还是 站外
        if(tagurl == '') {
            toast.error('请输入URL！');
            return false;
        }

        getDataByUrl(tagurl,_key,_tag,ajax_url);
    });


    // 模型1
    $('.theme1').on('click',function(){
        var _key = $(this).attr('data-key');
        var _tag = $(this).attr('data-tag');
        var ajax_url = $(this).attr('ajax-url');
        var tagurl = $("#floor_"+_key+"_"+_tag+'_url').val() ? $("#floor_"+_key+"_"+_tag+'_url').val() : '';
        var id = $("#floor_"+_key+"_"+_tag+'_id').val() ? $("#floor_"+_key+"_"+_tag+'_id').val() : '';

        //判断是抓取站内 还是 站外

        if(id == '') {
            toast.error('请输入商品Id！');
            return false;
        }
        getThemeDataByUrl(id,tagurl,_key,_tag,ajax_url);
    });

    // 模型2
    $('.theme2').on('click',function(){
        var _key = $(this).attr('data-key');
        var _tag = $(this).attr('data-tag');
        var ajax_url = $(this).attr('ajax-url');
        var tagurl = $("#floor_"+_key+"_"+_tag+'_url').val() ? $("#floor_"+_key+"_"+_tag+'_url').val() : '';

        //判断是抓取站内 还是 站外
        if(tagurl == '') {
            toast.error('请输入URL！');
            return false;
        }
        getTheme2DataByUrl(tagurl,_key,_tag,ajax_url);
    });





    //获取数据 by url
    function getThemeDataByUrl(id,tagurl,_key,_tag,ajax_url){
        $.post(ajax_url,{url:tagurl,id:id},function(msg){
            if(msg.status) {
                $("#floor_" + _key + "_" + _tag+"_title").val(msg.data.title);
                $("#floor_" + _key + "_" + _tag+"_url").val(msg.data.url);
                $("#floor_" + _key + "_" + _tag+"_img").attr('src',msg.data.image);
                $("#floor_" + _key + "_" + _tag+"_pic").val(msg.data.image);
                $("#floor_" + _key + "_" + _tag+"_price").val(msg.data.price);
                $("#floor_" + _key + "_" + _tag+"_subtitle").val(msg.data.sell_point);
            } else {
                toast.error(msg.info);
            }
        },'json');

    }

    //获取数据 by url
    function getTheme2DataByUrl(tagurl,_key,_tag,ajax_url){

        //如果不是站内 那么就是站外
        var regexp = /tuangou|youhui|buy|find|^[0-9]+$/;
        if(!tagurl.match(regexp)){
            $.ajax({
                url: "http://ruyi.taobao.com/ext/productLinkSearch?link=" + encodeURIComponent(tagurl) + "&group=prices,item&pid=rc001",
                dataType: 'jsonp',
                success: function (data) {
                    if($.isEmptyObject(data)){
                        toast.error('没有获取到对应的商品信息，请手动填写！');
                        return false;
                    }
                    var price = parseInt(data.Item.Price);
                    var image = data.Item.LargeImageUrl;
                    var title = data.Item.Title;

                    $("#floor_" + _key + "_" + _tag+"_title").val(title);
                    $("#floor_" + _key + "_" + _tag+"_img").attr('src',image);
                    $("#floor_" + _key + "_" + _tag+"_pic").val(image);
                    $("#floor_" + _key + "_" + _tag+"_price").val(price);
                    $("#floor_" + _key + "_" + _tag+"_discount_price").val(price);

                },error: function () {
                    toast.error('抓取失败，请联系开发人员。。。。');
                }
            });
        }else{
            $.post(ajax_url,{id:tagurl},function(msg){
                if(msg.status) {

                    $("#floor_" + _key + "_" + _tag+"_title").val(msg.info.title);
                    $("#floor_" + _key + "_" + _tag+"_url").val(msg.info.url);
                    $("#floor_" + _key + "_" + _tag+"_img").attr('src',msg.info.image);
                    $("#floor_" + _key + "_" + _tag+"_pic").val(msg.info.image);
                    $("#floor_" + _key + "_" + _tag+"_price").val(msg.info.price);
                    $("#floor_" + _key + "_" + _tag+"_discount_price").val(msg.info.price);
                    $("#floor_" + _key + "_" + _tag+"_from").val(msg.info.store);

                } else {
                    toast.error(msg.info);
                }
            },'json')
        }

    }





    $('.error_list').click(function(){
        $(this).hide();
    });


    if($('.J_hidden_color').length) {
        $('.J_hidden_color').each(function () {
            var bg_color = $(this).val();
            $(this).css('background', '#'+bg_color);
        });
    }



    //关闭窗口
    $('.popup-close').on('click',function(){
        isClickClose = true;
        hide_popup();
    });


});  //jquery end



//楼层上下移动  联动内容上下移动
function link_move_data(move_dom,_type) {
    var _index = move_dom.data('key');
    var _index_num =  parseInt(_index+1);
    var _max = move_dom.data('max');
    var is_edit = move_dom.data('edit');
    if((_type == 'up' && _index == 0) || (_type == 'down' && _index_num == _max)) return true;
    if(_type == 'up') {
        var move_index = parseInt(_index) - 1;
    } else {
        var move_index = parseInt(_index) + 1;
    }
    //移动
    var curr_data = $('#item_move_inner_'+_index).children();
    var next_data = $('#item_move_inner_'+move_index).children();
    $('#item_move_inner_'+_index).prepend(next_data);
    $('#item_move_inner_'+move_index).prepend(curr_data);

    //判断要不要改变数据结构
    if(is_edit == 1) {
        var ajax_url = $('#special_form').data('move-url');
        var special_id = $('#special_form').data('special-id');
        //显示等待弹出层
        $('.popup-mask').show();
        $('.popup-message-small').show();
        currentAjax = $.ajax({
            type: "POST",
            url: ajax_url,
            data:{form:_index,to:move_index,id:special_id},
            dataType: "json",
            success: function(data){
                setTimeout(function(){
                    hide_popup();
                    toast.info(data.info);
                },1000);
            },error: function () {
                setTimeout(function(){
                    hide_popup();
                    if(!isClickClose) {
                        toast.error('转移失败，请联系开发人员。。。。');
                    }
                },1000);
            }
        });
    }// if end

}



function  hide_popup(){
    if(currentAjax) {currentAjax.abort();}
    $('.popup-mask').hide();
    $('.popup-message-small').hide();
}




is_show_outher();
function is_show_outher() {
    if($("input:radio[name='one[foot_show]']").length) {
        var _message_id = $("input:radio[name='one[foot_show]']:checked").val();
    } else if($("input:radio[name='two[foot_show]']").length) {
        var _message_id = $("input:radio[name='two[foot_show]']:checked").val();
    } else if($("input:radio[name='four[foot_show]']").length) {
        var _message_id = $("input:radio[name='four[foot_show]']:checked").val();
    } else {
        var _message_id = $("input:radio[name='six[foot_show]']:checked").val();
    }

    if(_message_id == 3) {
        $('#manual-fill').show();
    } else {
        $('#manual-fill').hide();
    }
}


//清空
function clear(key,tag,_this) {
    $("#cateitemtitle"+key+"_"+tag).val("");
    $("#cateitemprice"+key+"_"+tag).val("");
    $("#cateiteminputfile"+key+"_"+tag).val("");
    $("#cateitemurl"+key+"_"+tag).val("");
    $("#showimage"+key+"_"+tag).attr('src',"/images/tradeadmin/global/placeholder.png");

    _this.parents('ul').find('input[type="text"],input[type="hidden"]').val("");
    //模板6
    $("#showimagepic"+key+"_"+tag).attr('src',"/images/tradeadmin/global/placeholder.png");

    //模板七
    _this.parents('ul').find('textarea').val("");
    $('#cateitemtags_'+tag).find('li').remove();
    $("#showimage_"+tag).attr('src',"/images/tradeadmin/global/placeholder.png");

}

//清空
function clearApp(tag) {
    $("#itemtitle_"+tag).val("");
    $("#itemintro_"+tag).val("");
    $("#itemprice_"+tag).val("");
    $("#itemdiscount_"+tag).val("");
    $("#iteminputfile_"+tag).val("");
    $("#itemurl_"+tag).val("");
    $("#showimage_"+tag).attr('src',"/images/tradeadmin/global/placeholder.png");

}


//获取数据 by url
function getDataByUrl(tagurl,_key,_tag,ajax_url){
    //如果不是站内 那么就是站外
    var regexp = /tuangou|youhui|buy|find|^[0-9]+$/;
    if(!tagurl.match(regexp)){
        $.ajax({
            url: "http://ruyi.taobao.com/ext/productLinkSearch?link=" + encodeURIComponent(tagurl) + "&group=prices,item&pid=rc001",
            dataType: 'jsonp',
            success: function (data) {
                if($.isEmptyObject(data)){
                    toast.error('没有获取到对应的商品信息，请手动填写！');
                    return false;
                }
                var price = parseInt(data.Item.Price);
                var image = data.Item.LargeImageUrl;
                var title = data.Item.Title;
                if(!$("#cateitemtitle"+_key+"_"+_tag).val())$("#cateitemtitle"+_key+"_"+_tag).val(title);
                if(!$("#cateitemprice"+_key+"_"+_tag).val())$("#cateitemprice"+_key+"_"+_tag).val(price);
                if(!$("#cateitempic"+_key+"_"+_tag).val())$("#cateitempic"+_key+"_"+_tag).val(image);
                if($("#showimagepic"+_key+"_"+_tag).length > 0 && image)$("#showimagepic"+_key+"_"+_tag).attr('src',image);
                if(!$("#cateiteminputfile"+_key+"_"+_tag).val())$("#cateiteminputfile"+_key+"_"+_tag).val(image);
                if($("#showimage"+_key+"_"+_tag).length > 0 && image)$("#showimage"+_key+"_"+_tag).attr('src',image);

                if(!$("#cateitemtitle_"+_tag).val())$("#cateitemtitle_"+_tag).val(title);
                if(!$("#cateiteminputfile_"+_tag).val())$("#cateiteminputfile_"+_tag).val(image);
                if($("#showimage_"+_tag).length > 0 && image)$("#showimage_"+_tag).attr('src',image);

            },error: function () {
                toast.error('抓取失败，请联系开发人员。。。。');
            }
        });
    }else{
        $.post(ajax_url,{id:tagurl},function(msg){
            if(msg.status) {
                if (!$("#cateitemtitle" + _key + "_" + _tag).val()) $("#cateitemtitle" + _key + "_" + _tag).val(msg.info.title);
                $("#cateitemurl" + _key + "_" + _tag).val(msg.info.url);
                if (!$("#cateitemprice" + _key + "_" + _tag).val()) $("#cateitemprice" + _key + "_" + _tag).val(msg.info.price);
                if (!$("#cateitemoriginalcost" + _key + "_" + _tag).val()) $("#cateitemoriginalcost" + _key + "_" + _tag).val(msg.info.originalCost);
                if (!$("#cateitempic" + _key + "_" + _tag).val()) $("#cateitempic" + _key + "_" + _tag).val(msg.info.image);
                if ($("#showimagepic" + _key + "_" + _tag).length > 0 && msg.info.image) $("#showimagepic" + _key + "_" + _tag).attr('src', msg.info.image);
                if(!$("#cateiteminputfile"+_key+"_"+_tag).val()) $("#cateiteminputfile"+_key+"_"+_tag).val(msg.info.image);
                if(!$("#showimage"+_key+"_"+_tag).attr('src')) $("#showimage"+_key+"_"+_tag).attr('src',msg.info.image);

                $("#cateitemurl_" + _tag).val(msg.info.url);
                if(typeof msg.info.intro != undefined) $("#cateitemintro_"+_tag).val($.trim(msg.info.intro));
                if(typeof msg.info.title != undefined)$("#cateitemtitle_"+_tag).val(msg.info.title);
                if(typeof msg.info.image != undefined)$("#cateiteminputfile_"+_tag).val(msg.info.image);
                if($("#showimage_"+_tag).attr('src'))$("#showimage_"+_tag).attr('src',msg.info.image);

                if(typeof msg.info.tags != undefined){
                    str = '';
                    for(var i in msg.info.tags.name){
                        str += ' <li  >\
                        <span class="special_tag"><i>x</i>'+msg.info.tags.name[i]+'</span>\
                            <input name="itemtags['+_tag+'][]" value="'+msg.info.tags.name[i]+'" type="hidden" >\
                            </li>';

                        $("#cateitemtags_"+_tag).html(str);
                    }
                }
            } else {
                toast.error(msg.info);
            }
        },'json')
    }
}

