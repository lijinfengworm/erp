<div class="cf mB20">
    <div class="fl">
        <a class="btn btn_gray  " href="<?php echo url_for('@default?module=admin_menu&action=index'); ?>">后台菜单管理</a>
        <a class="btn btn_gray nav_current" href="<?php echo url_for('@default?module=admin_menu&action=add'); ?>">添加菜单</a>
    </div>
</div>
<form action="" method="post" class="form-horizontal">
    <div class="form-item">
        <label class="item-label">上级</label>
        <div class="controls">
            <select name="<?php echo $form->getName();?>[pid]">
                <option value="0">作为一级菜单</option>
                <?php echo $select_categorys; ?>
            </select>
        </div>
    </div>

    <?php foreach ($form as $name => $field): ?>
        <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
        <div class="form-item">
            <label class="item-label"> <?php echo $field->renderLabel() ?></label>
            <div class="controls">
            <?php echo $field->render() ?>
            <?php echo $field->renderError() ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="form-item mb10">
        <label class="item-label">权限集合</label>
        <div class="controls">
            <button type="button" id="add_child_access" class="gwyy_btn mB10" >添加一条子集权限</button>
            <div class="child_access_tips">
                权限标识：必填，程序是通过标识来查询权限，只能是A-Za-z_，最多30个字符，否则会忽略<br />
                权限名称：简短的名称描述权限<br />
                权限值：必填，用|分隔 每一个单独权限用=分隔 左侧表示标识 右侧代表当前值名字 比如说 1=全部|2=个人 <br />
                默认值：必填，权限值里面的某一个标识作为默认值<br />
                例子：   标识：index_show&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名称：首页显示权限  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;权限值：
                1=全部|2=个人  <select>
                    <option value="1">全部</option>
                    <option value="2" selected>个人</option>
                </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;默认值：2  （个人）<br />
                <input class="w160 mR10" type="text" disabled value="index_show" />
                <input class="w160 mR10" type="text" disabled value="首页显示权限" />
                <input class="w280 mR10" type="text" disabled value="1=全部|2=个人" />
                <input class="w50" type="text" disabled value="2" />
            </div>
        <div class="child_access_box" style="display: none;">
            <div class=" child_access">
                    <div class="child_access_title">
                        <div class="w180">权限标识 <span class="red">*</span></div>
                        <div class="w180">权限名称</div>
                        <div class="w310">权限值 <span class="red">*</span></div>
                        <div class="w120">默认值 <span class="red">*</span></div>
                    </div>

                    <div id="child_access_item_box"></div>
            </div>
        </div>
        </div>
    </div>

    <div class="form-item ml90 pB10" >
        <?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?>
            <input type="hidden" name="<?php echo $form->getCSRFFieldName() ?>" value="<?php echo $form->getCSRFToken() ?>" />
        <?php endif; ?>
        <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">确 定</button>
        <button class="btn btn-return" onclick="javascript:history.back(-1);return false;">返 回</button>
    </div>
</form>
<script> highlight_subnav("<?php  echo url_for('@default?module=admin_menu&action=index'); ?>");</script>
<script type="text/javascript" src="/js/tradeadmin/page/menu_access.js"></script>