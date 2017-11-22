<link rel="stylesheet" href="/bootstrap3/css/bootstrap.min.css" />
<link rel="stylesheet" href="/bootstrap3/css/bootstrap-theme.min.css" />

<div class="col-xs-12 col-sm-12 text-center" >
    <h2>集合商品快速加入</h2>
    <form  method="post" id="set_form">
        <div class="form-group">
            <label for="exampleInputEmail1">集合ID</label>
            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="集合ID" name="set_id">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">商品ID</label>
            <input type="text" class="form-control" id="exampleInputPassword1" placeholder="商品ID,ID可用,号分隔" name="goods_id">
        </div>
        <div class="form-group">
            <label class="checkbox-inline">
                <input type="radio" id="inlineCheckbox1" value="add"   name="operate"> 添加
            </label>
            <label class="checkbox-inline">
                <input type="radio" id="inlineCheckbox2" value="delete" name="operate"> 删除
            </label>
        </div>
        <button type="button" class="btn btn-default" id="set_submit">提交</button>
    </form>
</div>
<script type="text/javascript" src="http://b1.hoopchina.com.cn/common/jquery-1.8.js"></script>
<script charset="utf-8" src="/bootstrap3/js/bootstrap.min.js"></script>
<script>
    $('#set_submit').click(function(){
        var data = $('#set_form').serialize();
        $.post('http://www.shihuo.cn/api/joinActivitySet',data,function(msg){
            alert(msg.msg);
        },'json')
    })
</script>