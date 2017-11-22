<td>
    <ul class="sf_admin_td_actions">
        <?php echo $helper->linkToEdit($trd_admin_user, array(  'params' =>   array(  ),  'class_suffix' => 'edit',  'label' => '修改信息',)) ?>
        <?php if ($trd_admin_user->getUserStatus() != 3):?>
        <?php echo $helper->linkToDelete($trd_admin_user, array(  'params' =>   array(  ),  'confirm' => '是否要删除 '.$trd_admin_user->getUsername().' ？',  'class_suffix' => 'delete',  'label' => '删除会员',)) ?>
        <?php endif;?>
    </ul>
</td>

