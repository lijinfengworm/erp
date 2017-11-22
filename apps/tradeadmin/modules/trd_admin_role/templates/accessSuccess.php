<style type="text/css">
    .auth_rules, .checkbox input, .radio input, checkbox, input[type=checkbox] {
        width:auto !important;
        display: inline !important;
    }
    .tab-wrap .checkmod .bd{
        padding-left: 30px;
        
    }
    .tab-wrap .checkmod .bd label{
        font-weight: normal;
    }
    .tab-wrap .checkmod .bd .rule_check .child_row{
        padding-left: 30px;
        display: inline-block;
    }
    .tab-wrap .checkmod .bd .rule_check .child_row .child_access_icon, .tab-wrap .checkmod .bd .rule_check .child_row .access_close{
        color: #fff;
        display: inline-block;
        float: right;
        border: 1px solid;
        height: 20px;
        width: 20px;
        text-align: center;
        background: #94b9ac;

    }
</style>
<div class="cf">
    <div class="fl">
        <a class="btn btn_gray  " href="<?php echo url_for('@default?module=trd_admin_role&action=index'); ?>">角色列表</a>
        <a class="btn btn_gray  " href="<?php echo url_for('@default?module=trd_admin_role&action=new'); ?>">添加角色</a>
        <a class="btn btn_gray  nav_current" href="">权限设置</a>
    </div>

    <select class="fR mR10" name="group">
        <?php  foreach($role as $k=>$v): ?>
        <option <?php  if($this_role_id == $v['id']) { echo 'selected';}  ?>    value="<?php echo url_for('@default?module=trd_admin_role&action=access&role_id='.$v['id']);  ?>"><?php  echo $v['name']; ?></option>
        <?php  endforeach; ?>
    </select>

</div>
<!-- access item  -->
<div class="tab-wrap">
    <div class="tab-content">
        <!-- 访问授权 -->
        <div class="tab-pane in">
            <form action="<?php echo url_for('@default?module=trd_admin_role&action=saveRole');   ?>"  method="POST" class="form-horizontal auth-form">
                <?php foreach ($node_list as $key=>$node): ?>
                    <div class="box box-info" style="padding-left: 30px;">
                        <dl class="checkmod">
                            <dt class="hd box-header with-border" >
                                <label class="checkbox"><input class="auth_rules rules_all" type="checkbox" name="menuid[<?php echo $node['id'] ?>][id]" value="<?php echo $node['id'] ?>"   ><?php echo $node['name'] ?>管理</label>
                            </dt>
                            <dd class="bd">
                                <?php  if(isset($node['child'])): foreach ($node['child'] as $k=>$child): ?>
                                        <div class="rule_check">
                                            <div>
                                                <label class="checkbox" title='<?php  echo $child['name']; ?>' >
                                                <input class="auth_rules rules_row" type="checkbox" name="menuid[<?php  echo $child['id']; ?>][id]" value="<?php  echo $child['id']; ?>"/>
                                                    <?php  echo $child['name']; ?>
                                                </label>
                                                    <?php  if(!empty($child['child_attr'])) {
                                                        echo '<span data-title="'.$child['name'].'" data-id="'.$child['id'].'" class="mR10 child_access_icon">子</span>';
                                                        echo '<div class="d-hidden access_box"  id="access_box_'.$child['id'].'">';
                                                        echo '<div class="access_box_tit">'.$child['name'].' - 子权限配置</div><span class="access_close">X</span>';
                                                                foreach($child['child_attr'] as $k=>$v) {
                                                                    $_default = $v['default'];
                                                                    if(!empty($useRole[$child['id']]['child_attr'])) {
                                                                        if(!empty($useRole[$child['id']]['child_attr'][$v['sign']]))
                                                                        $_default = $useRole[$child['id']]['child_attr'][$v['sign']];
                                                                    }
                                                                    echo  '<label class="access_inner_label">'.$v['name'].'：<select name="menuid['.$child['id'].'][child]['.$v['sign'].']">';
                                                                            echo FunBase::AccessItemToFormat($v['value'],'html_select',$_default);
                                                                    echo '</select></label>';
                                                                }
                                                        echo '</div>';
                                                    } ?>
                                            </div>
                                                <span class="divsion">&nbsp;</span>
                                               <span class="child_row">
                                                <?php  if(isset($child['operator'])): foreach ($child['operator'] as $ck=>$op): ?>
                                                       <label class="checkbox" title="<?php  echo $op['name']; ?>">
                                                       <input class="auth_rules" type="checkbox" name="menuid[<?php  echo $op['id']; ?>][id]"
                                                              value="<?php  echo $op['id']; ?>"/>
                                                               <?php  echo $op['name']; ?>
                                                            </label>
                                                           <?php  if(!empty($op['child_attr'])) {
                                                                echo '<span data-title="'.$op['name'].'" data-id="'.$op['id'].'" class="mR10 child_access_icon">子</span>';
                                                                echo '<div class="d-hidden access_box"  id="access_box_'.$op['id'].'">';
                                                                echo '<div class="access_box_tit">'.$op['name'].' - 子权限配置</div><span class="access_close">X</span>';
                                                                foreach($op['child_attr'] as $k=>$v) {
                                                                    $_default = $v['default'];
                                                                    if(!empty($useRole[$op['id']]['child_attr'])) {
                                                                        if(!empty($useRole[$op['id']]['child_attr'][$v['sign']]))
                                                                        $_default = $useRole[$op['id']]['child_attr'][$v['sign']];
                                                                    }
                                                                    echo  '<label class="access_inner_label">'.$v['name'].'：<select name="menuid['.$op['id'].'][child]['.$v['sign'].']">';
                                                                    echo FunBase::AccessItemToFormat($v['value'],'html_select',$_default);
                                                                    echo '</select></label>';
                                                                }
                                                                echo '</div>';
                                                            } ?>
                                                <?php endforeach; endif; ?>
                                               </span>
                                        </div>
                                  <?php endforeach; endif; ?>
                            </dd>
                        </dl>
                    </div>
                <?php endforeach;?>
                <input type="hidden" name="roleid" value="<?php echo $this_role['id']; ?>" />
                <button type="submit" class="btn submit-btn ajax-post" target-form="auth-form">确 定</button>
                <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
            </form>
        </div>
    </div>
</div>
<div class="popup-mask"></div>
<script type="text/javascript" charset="utf-8">
        $('.child_access_icon').on('click',function(){
            $('.popup-mask').show();
            var _id = '#access_box_'+$(this).attr('data-id');
            $(_id).show().removeClass('d-hidden');
        });
        $('.access_close').on('click',function(){
            $('.popup-mask').hide();
            $('.access_box').hide();
        });



        +function($){

        var rules = [<?php echo $useRoleIds;?>];
        $('.auth_rules').each(function(){
            if( $.inArray( parseInt($(this).val(),10),rules )>-1 ){
                $(this).prop('checked',true);
            }
            if(this.value==''){
                $(this).closest('span').remove();
            }
        });
        //全选节点
        $('.rules_all').on('change',function(){
            $(this).closest('dl').find('dd').find('input').prop('checked',this.checked);
        });
        $('.rules_row').on('change',function(){
            $(this).closest('.rule_check').find('.child_row').find('input').prop('checked',this.checked);
        });
        $('select[name=group]').change(function(){
            location.href = this.value;
        });

    }(jQuery);
</script>