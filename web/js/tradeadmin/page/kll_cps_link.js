$(function(){





    //生成连接
    $('#create_link').on('click',function(){
        var _link_url = $('#link_url').val();
        var _channel = $('#channel').val();
        if(_link_url == '') {
            toast.error('请填写要生成的url!');
            return false;
        }
        if(_channel == '') {
            toast.error('请填写要推广的唯一标识符!');
            return false;
        }
        var _url = $('#create-cps-link-form').data('ajax-url');
        $.post(_url,{link_url:_link_url,union_id:_channel},function(res){
            if(res.status == 1) {
                //$('#cps-link-table').prepend("<tr><td align='center' class='c-blue'>新</td><td align='center'>"+res.data.code+"</td>" +
                //"<td>"+res.data.title+"</td>"+
                //"<td align='center'><input type='text' value='"+res.data.url+"' class='w110' /></td>"+
                //"<td align='center'><a href='"+res.data.url+"'>"+res.data.a+"</a></td>"+
                //"<td align='center'>"+res.data.link_url+"</td>"+
                //"<td align='center'>"+res.data.channel+"</td>"+
                //"<td align='center'> - </td>"+
                //"</tr>");
                location.reload();
            } else {
                toast.error(res.info);
            }
        },"json");
    });





});