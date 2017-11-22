<td>
    <ul class="sf_admin_td_actions">
        <?php if($trd_admin_role->getRoleStatus() != 1): ?>
            <span class="c-999">权限设置</span> &nbsp;
        <?php else: ?>
            <a href="<?php echo url_for('@default?module=trd_admin_role&action=access&role_id='.$trd_admin_role->getId()); ?>">权限设置 </a>&nbsp;
        <?php endif; ?>
        <?php echo $helper->linkToEdit($trd_admin_role, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => '修改信息',)) ?>

        <a href="<?php echo url_for('@default?module=trd_admin_role&action=delete&id='.$trd_admin_role->getId()); ?>" class="J_ajax_del">删除用户组</a>

    </ul>
</td>

