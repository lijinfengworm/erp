
<div class="cf">
    <div class="fl">
        <a class="btn btn_gray  nav_current" href="<?php echo url_for('@default?module=admin_menu&action=index'); ?>">后台菜单管理</a>
        <a class="btn btn_gray" href="<?php echo url_for('@default?module=admin_menu&action=add'); ?>">添加菜单</a>
        <a class="btn btn_gray" href="<?php echo url_for('@default?module=admin_menu&action=adds'); ?>">批量添加菜单</a>
        <a class="gwyy_btn show_all"  data-show="0" href="javascript:;">展开所有菜单</a>
    </div>
    <!-- 高级搜索 -->
    <div class="search-form fr cf">
        <div class="sleft">
            <input type="text" placeholder="请输入搜索名称" value="<?php echo $menu_like_name;?>" class="search-input" name="name">
            <a url="<?php echo url_for('@default?module=admin_menu&action=index'); ?>" id="search" href="javascript:;" class="sch-btn"><i class="btn-search"></i></a>
        </div>
    </div>
</div>
<form class="form-horizontal" action="<?php echo url_for('@default?module=admin_menu&action=listorder'); ?>" method="post">
    <div class="data-table table-striped">
        <table class="J_tree_table" id="menu_table">
            <thead>
            <tr>
                <th class="tCenter">排序</th>
                <th class="tCenter">ID</th>
                <th>菜单英文名称</th>
                <th class="tCenter">状态</th>
                <th class="tCenter">管理操作</th>
            </tr>
            </thead>
            <tbody>

            <?php if(empty($categorys)):  ?>
                <td colspan="9" class="text-center"> aOh! 暂时还没有内容! </td>
            <?php else:  echo $categorys;  ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="form-item " >

        <button class="btn submit-btn ajax-post" id="submit" type="submit" target-form="form-horizontal">排 序</button>
        <a class="gwyy_btn show_all"  data-show="0" href="javascript:;">展开所有菜单</a>
    </div>
</form>

<a href="#" class="fR">去顶部</a>
<br /><br />
<script>

    $('.show_all').on('click',function(){
        var show = $(this).attr('data-show');
        if(show == 0) {
            $('#menu_table').treetable("expandAll");
            $('.show_all').text('收缩所有菜单').attr('data-show', 1);
        } else {
            $('#menu_table').treetable("collapseAll");
            $('.show_all').text('展开所有菜单').attr('data-show', 0);
        }
    });



</script>