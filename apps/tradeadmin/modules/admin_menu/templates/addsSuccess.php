<div class="cf mB20">
    <div class="fl">
        <a class="btn btn_gray  " href="<?php echo url_for('@default?module=admin_menu&action=index'); ?>">后台菜单管理</a>
        <a class="btn btn_gray nav_current" href="<?php echo url_for('@default?module=admin_menu&action=adds'); ?>">批量添加菜单</a>
    </div>
</div>
<form action="" method="post" class="form-horizontal">

<div class="clearfix">


        <?php for($i = 0;$i<adminMenuListForm::$_num;$i++) { ?>
    <ul class="menu_adds_ul">
        <li>

            <select name="<?php echo $form->getName().'['.$i.']';?>[pid]">
                <option value="0">作为一级菜单</option>
                <?php echo $select_categorys; ?>
            </select>

        </li>


            <li >

                <?php  echo $form[$i."[name]"]->renderLabel(); ?>
                <?php  echo $form[$i."[name]"]->render(array('value'=>adminMenuListForm::$auto_name[$i])); ?>
                <?php  echo $form[$i."[name]"]->renderError(); ?>
            </li>
        <li>
                <?php  echo $form[$i."[controller]"]->renderLabel(); ?>
                <?php  echo $form[$i."[controller]"]->render(); ?>
                <?php  echo $form[$i."[controller]"]->renderError(); ?>
        </li>
        <li>
                <?php  echo $form[$i."[action_name]"]->renderLabel(); ?>
                <?php  echo $form[$i."[action_name]"]->render(array('value'=>adminMenuListForm::$auto_action_name[$i])); ?>
                <?php  echo $form[$i."[action_name]"]->renderError(); ?>
        </li>
        <li>
                <?php  echo $form[$i."[menu_group]"]->renderLabel(); ?>
                <?php  echo $form[$i."[menu_group]"]->render(); ?>
                <?php  echo $form[$i."[menu_group]"]->renderError(); ?>
        </li>
        <li>
                <?php  echo $form[$i."[is_public]"]->renderLabel(); ?>
                <?php  echo $form[$i."[is_public]"]->render(); ?>
                <?php  echo $form[$i."[is_public]"]->renderError(); ?>
        </li>
        <li>
                <?php  echo $form[$i."[is_hide]"]->renderLabel(); ?>
                <?php  echo $form[$i."[is_hide]"]->render(); ?>
                <?php  echo $form[$i."[is_hide]"]->renderError(); ?>
        </li>

        <li>
            <?php  echo $form[$i."[menu_status]"]->renderLabel(); ?>
            <?php  echo $form[$i."[menu_status]"]->render(); ?>
            <?php  echo $form[$i."[menu_status]"]->renderError(); ?>
        </li>

        <li>
            <?php  echo $form[$i."[listorder]"]->renderLabel(); ?>
            <?php  echo $form[$i."[listorder]"]->render(); ?>
            <?php  echo $form[$i."[listorder]"]->renderError(); ?>
        </li>


    </ul>
        <?php  }  ?>
</div>







    <div class="form-item ml90 pB10 mT20" >

        <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
        <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
    </div>
</form>
<script> highlight_subnav("<?php  echo url_for('@default?module=admin_menu&action=index'); ?>");</script>
<script type="text/javascript" src="/js/tradeadmin/page/menu_access.js"></script>