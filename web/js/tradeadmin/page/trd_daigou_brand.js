var root_id = $('select[name="root_id"]').find('option:selected').val();
var children_id = $('select[name="children_id"]').find('option:selected').val();


$('.new').click(function () {
    var tip = '填写标签名称';
    var html = '<div id="editnew"><input class="w120" name="mname" value="" placeholder="'+tip+'">&nbsp;&nbsp;&nbsp;&nbsp;<a class="saveit">保存</a>&nbsp;&nbsp;&nbsp;&nbsp;<a class="cancelit">取消</a></div>';
    var type = $(this).attr('type');
    if ($('#editnew').get(0)) {
        alert('请先编辑');
    } else {
        $(this).after(html);
    }
})

$(document).on('click','.cancelit', function () {
    $('#editnew').remove();
})

$(document).on('click','.saveit', function () {
    var val = $(this).prev().val();
    var self = $(this);
    if (val) {
        var url = $('#foo').data('saveit');
        $.post(url, {'root_id':root_id,'children_id':children_id,'brandName':val},function (data) {
            if (data.status == 200) {
                self.parents('.h1').next().append('<span class="m1"><s></s>' + val + '</span>');
                self.prev().val('');
            }else if (data.status == 300){
                alert('已存在相同记录！');
            }else{
                alert('添加失败！');
            }
        },'json')
    } else {
        alert('请先填写内容');
    }
})

$('select[name="root_id"]').change(function () {
    var url = window.location.href.replace('index', '');
    root_id = $('select[name="root_id"]').find('option:selected').val();
    if (root_id > 0) {
        var url = $('#foo').data('changeroot') +"?root_id=" + root_id;
        $.getJSON(url, function (data) {
            var sel = '';
            $.each(data, function (i, v) {
                sel += '<option value="' + i + '">' + v + '</option>';
            })
            $('select[name="children_id"]').html(sel);
            children_id = $('select[name="children_id"]').find('option:selected').val();

            getTags();
        })
    }
})

/*删除标签*/

$(document).on('click','.m1 s', function () {
    var that =  $(this);
    var val = $(this).parent().text();
    if(confirm('确定删除吗？')){
        var url = $('#foo').data('delete');
        $.post(url, {'root_id':root_id,'children_id':children_id,'brandName':val},function (data) {
            if (data.status == 200) {
                that.parent().remove();
            }else{
                alert('删除失败！');
            }
        },'json')
    }
})

//二级分类切换
$('select[name="children_id"]').change(function(){
    children_id = $('select[name="children_id"]').find('option:selected').val();
    getTags();
});



//按顺序保存tag
$('#save').click(function(){
    var brandNames = new Array();
    $('.h2 span').each(function(){
        brandNames.push($(this).data('name'));
    });
    var url =  $('#foo').data('listorder');
    $.post(url, {'root_id':root_id,'children_id':children_id,'brandNames':brandNames},function (data) {
        if (data.status == 200) {
            alert('保存成功！')
        }else{
            alert('保存失败！');
        }
    },'json')
})

//tag切换
function  getTags(){
    var url = $('#foo').data('tagschange')+"?root_id=" + root_id;
    $.post(url, {'root_id':root_id,'children_id':children_id,'is_all':1},function (data) {
        var sel = '';
        $.each(data, function (i, v) {
            var _url = $('#foo').data('tags').replace('000', v.id);
            sel += '<span class="m1 no_select" data-name="'+ v.brand_name+'"><s>X</s>' + v.brand_name + '<a class="set_size" href="'+_url+'">设置尺码</a></span>';
        })
        $('.h2').html(sel);
    },'json')
}


//移动
(function (){
    var console = window.console;
    if( !console.log ){
        console.log = function (){
           // alert([].join.apply(arguments, ' '));
        };
    }
    new Sortable(foo, {
        group: "words",
        onAdd: function (evt){ console.log('onAdd.foo:', evt.detail); },
        onUpdate: function (evt){ console.log('onUpdate.foo:', evt.detail); },
        onRemove: function (evt){ console.log('onRemove.foo:', evt.detail); }
    });
})();


