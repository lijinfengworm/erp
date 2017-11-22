<div class="cf mB10" >
    <div class="fl">
        <a class="btn btn_gray nav_current" href="####">你好，<?php echo $sf_user->getattribute("trdadmin_username");?> 动词大慈 黑喂狗！！</a>
    </div>
</div>


<div class=" sf_show_bar pS8H4 clearfix">
    <div class="d-i-b mR10">
        <?php foreach($nav as $k=>$v) : ?>
        <a style="margin-top:4px;" href="
        <?php echo url_for("@default?module=public&action=bfauth&go=1&category=".$v['name']); ?>
        " class="gwyy_btn  gwyy_btn_cyan"><?php  echo $v['name'];?> (剩余：<?php  echo $v['count']; ?>)</a>
        <?php  endforeach;  ?>
    </div>
</div>


<!--
中文标题
图片
品牌
重量
以及分类
二级分类
-->

<?php  $i = 0; foreach($mydata as $k=>$v) : ?>


    <div class="group_list mT20">
        <h3 class="group_list_title">
            <span style="font-size:14px;">第 </span><span class="c-red"><?php  echo ++$i; ?></span> <span style="font-size:14px;">个</span>
            <span style="font-size:14px;">识货id </span><span class="c-red"><?php  echo $v['shid']; ?></span>
            <span style="font-size:14px;">美亚页数 </span><span class="c-red"><?php  echo $v['page']; ?></span> <span style="font-size:14px;">页</span>
            <span style="font-size:14px;">美亚标题：</span><span  style="font-size:16px;" class="c-blue"><?php echo $v['title'];   ?></span>
            <button data-info="<?php echo base64_encode($v['old_data']);  ?>"  class="fR bf_del   gwyy_btn gwyy_btn_red">X </button>
        </h3>

        <div class="group_content">
            <ul class="group_ul_item">
                <li class="fL">
                    <a target="_blank" href="<?php echo 'http://www.shihuo.cn/haitao/buy/'.$v['shid'].'.html'; ?>"><img width="100" height="100" src="<?php  echo $v['.']['img_path'];  ?>" /></a>
                </li>
                <li class="fL">
                    <span>标题：</span>
                    <input type="text"   value="<?php echo $v['title'];    ?>" class="w160" name="title" >
                    <span>重量：</span>
                    <input type="text"   value="<?php echo $v['.']['business_weight'];   ?>" class="w100" name="weight" >
                </li>

                <li class="fL">
                    <span>分类：</span>
                    <?php echo $v['form']['root_id']; ?> <?php echo $v['form']['children_id']; ?>
                    <?php if($v['form']['root_id']->hasError()) echo $v['form']['root_id']->renderError() ?>
                </li>

                <li class="fL">
                    <span>品牌：</span>
                     <?php echo $v['form']['brand_id']; ?>
                    <input type="brand_name" value="<?php echo $v['brand'];  ?>" class="brand_name w100"  id="">
                    <input type="hidden" value="<?php echo $v['shid'] ?>" name="id">
                    <?php
                       //如果没有栏目
                        if(empty($v['.']['root_id']) &&  empty($v['.']['children_id'])) {
                        //if(!empty($v['.']['root_id']) &&  !empty($v['.']['children_id'])) {
                            //如果没有栏目  但是偏偏有品牌  就眼逆向去推了
                            if(!empty($v['brand'])) {
                                echo  '<input type="hidden" class="ajaxbrand"  data-ajaxbrand="1"  value="'.base64_encode($v['brand']).'"       />';
                            }
                        }
                    ?>
                    <input type="hidden" value="<?php echo base64_encode($v['old_data']);?>" name="redis_value">
                    <button onclick="addBrand(this)" class="gwyy_btn" type="button">添加品牌</button>
                </li>

                <li class="fL">
                   <button class="gwyy_btn gwyy_btn_green" onclick="sub(this)">提交</button>
                </li>



                    <a target="_blank" class="gwyy_btn" href="<?php echo 'http://www.shihuo.cn/tradeadmin.php/product_attr_audit/edit/id/'.$v['shid'] ?>">修改</a>

            </ul>
        </div>
    </div>

<?php endforeach;  ?>


























<script>
    //逆推品牌
    if($('.ajaxbrand').length > 0) {
        $('.ajaxbrand').each(function(k,v){
            var token = $(this).parent().find('.brand_id').data('id');
            var brandname = $(this).val();

            $.post('<?php echo url_for('@default?module=public&action=bfevent&event=brand') ?>', {
                brandname: brandname
            }, function (data) {
                if (data.status == 1) {
                    $('.b_' + token).empty();
                    $('.b_' + token).append(data.info.brand);

                    $('.r_' + token).empty();
                    $('.r_' + token).append(data.info.root);


                    $('.c_' + token).empty();
                    $('.c_' + token).append(data.info.child);
                }
            }, 'json');
        });
    }






    var root_id,children_id,brand_id ;
    function getSecondMenuById(id,token){
        var c_obj = $('.c_'+token);
        if (id == 0 || id == ''){
            c_obj.empty();
            c_obj.append("<option selected=\"selected\" value=\"0\">请选择二级分类</option>");
            return true;
        }
        $.ajax({
            type: "GET",
            url: "<?php echo url_for('@default?module=trd_menu&action=getChildrenMenusById&channel=daigou') ?>",
            data: {root_id:id},
            dataType: "json",
            success: function(data){
                if (!data.status){
                    c_obj.empty();
                    c_obj.append(data.data);
                }
            }
        });
        //品牌切换
        getBrands(token);
        return true;
    }

    //二级分类切换
    $('.children_id').change(function(){
        getBrands($(this).data('id'));
    })

    //获取品牌
    function getBrands(token){
        root_id =$('.r_'+token).val();
        children_id =$('.c_'+token).val();
        if((root_id == false)|| (children_id == false)){
            if(arguments[0] == 'false')
                return false
            else
                alert('获取品牌前请选择一二级分类');
        }else{
            $.ajax({
                type: "GET",
                url: "<?php echo url_for('@default?module=trd_daigou_brand&action=getBrand') ?>",
                data: {root_id:root_id,children_id:children_id, brand_id:brand_id},
                dataType: "json",
                success: function(data){
                    if (!data.status){
                        $('.b_'+token).empty();
                        $('.b_'+token).append(data.data);
                    }
                }
            });
        }
    }

    //添加品牌
    function addBrand(t) {
        var token = $(t).parent().find('.brand_id').data('id');
        var brand_name = $(t).parent().find('.brand_name').val();
        root_id = $('.r_'+token).val();
        children_id = $('.c_'+token).val();
        if ((root_id == false) || (children_id == false)) {
            toast.info('添加品牌前请选择一二级分类');
        } else {
            if (brand_name) {
                $.post('<?php echo url_for('@default?module=trd_daigou_brand&action=addBrands') ?>', {
                    root_id: root_id, children_id: children_id, brandName: brand_name
                }, function (data) {
                    if (data.status == 200) {
                        brand_id = data.brand_id;
                        getBrands(token);
                    } else {
                        $('.b_'+token).val(data.brand_id);
                        toast.info('添加失败,已存在');

                    }
                }, 'json');
            }
        }
    }

    //删除
    $('.bf_del').on('click',function(e){
        //取消事件的默认动作
        e.preventDefault();
        //终止事件 不再派发事件
        e.stopPropagation();
       var _this = $(this);
        var _info = $(this).data('info');

        Wind.use('artDialog', function () {
            dialog({
                title: false,
                content: '你确定真的要删除这条记录吗？',
                okValue: '确定',
                ok: function () {
                    $.post('<?php echo url_for('@default?module=public&action=bfevent&event=del') ?>', {
                        info: _info
                    }, function (data) {
                        _this.parent().parent().remove();
                    }, 'json');
                },
                cancelValue: '取消',
                cancel: function () {
                }
            }).showModal();
        });





    });








</script>

<script>
    function sub(obj){
        var id=$(obj).parents('ul').find('input[name=id]').val();
        var title=$(obj).parents('ul').find('input[name=title]').val();
        var weight=$(obj).parents('ul').find('input[name=weight]').val();
        var root_id=$(obj).parents('ul').find('.root_id option:selected').val();
        var children_id=$(obj).parents('ul').find('.children_id option:selected').val();
        var brand_id =$(obj).parents('ul').find('.brand_id  option:selected').val();
        var info=$(obj).parents('.group_list').find('.bf_del').data('info');
        var data={
            'id' : id,
            'title' : title,
            'weight' : weight,
            'brand_id' : brand_id,
            'root_id' : root_id,
            'children_id' : children_id,
            'info' : info
        };


        if(children_id == 0 || root_id == 0 || root_id == '' || children_id == '') {
            toast.error('请填写分类 ！');
            return false;
        }

        if(brand_id == 0 || brand_id == '') {
            toast.error('请填写品牌！');
            return false;
        }
        if(title == '' || weight  == '') {
            toast.error('请填写标题和重量！');
            return false;
        }




        $.ajax({
            type:'post',
            dataType: 'json',
            url:'<?php echo url_for('public/savepro');?>',
            data: data ,
            success:function(data){
                console.log(data.status);
                if (data.status==1){
                    toast.success('修改成功！');
                    $(obj).parents('.group_list').remove();
                }else{
                    alert('Orz--失败');
                }
            },
            error :function(data){
                console.log('Orz--失败');
            }
        })

    }


</script>









<script> highlight_subnav("<?php  echo url_for('@default?module=product_attr_audit&action=index'); ?>");</script>
<script type="text/javascript" src="/js/tradeadmin/page/bfauth.js"></script>