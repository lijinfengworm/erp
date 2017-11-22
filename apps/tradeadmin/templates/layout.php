<!doctype html>
<html>
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <script>
      var GV = {
        DIMAUB: "<?php echo sfContext::getInstance()->getController()->genUrl("@homepage"); ?>",
        JS_ROOT: "/js/<?php echo sfContext::getInstance()->getConfiguration()->getApplication();?>/"
      };
    </script>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <?php
    /* 所有菜单  */
    $__MENU__ = AuthMenu::getMenus();
    /*  当前菜单ID */
    $__TOP__ID = AuthMenu::getTopId();
  ?>
 


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">财务</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>卡路里后台</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <?php if(isset($__MENU__)) : foreach($__MENU__['main']  as $k => $menu): ?>
            <?php if($k > 5) : ?>
            <li class="<?php if($__TOP__ID == $menu['id']){ echo 'current';} ?>">
              <a href="javascript:;" class="J_top_nav" data-id="<?php echo $menu['id'];?>" ><?php echo $menu['name']; ?></a>
            </li>
          <?php endif; ?>
          <?php  endforeach; endif; ?>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/adminlte/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $sf_user->getattribute("trdadmin_username");?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                <p>
                  <small><?=$sf_user->getattribute("trdadmin_username");?></small>
                </p>
              </li>
             
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat" style="display: none;">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo url_for('@logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
   <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $sf_user->getattribute("trdadmin_username");?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
    
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree" id="subnav">
      <?php if(isset($__MENU__['child'][$__TOP__ID])) : foreach($__MENU__['child'][$__TOP__ID] as $key=>$sub_menu) :   ?>
       <li class="treeview">
          <a href="#">
            <i class="fa fa-th"></i>
            <span><?php echo $key; ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <?php
            $_menu = '';
            if(isset($sub_menu)) {
              foreach($sub_menu as $k=>$_child_menu) {
                if(AuthMenu::getController() == $_child_menu['controller'] && AuthMenu::getAction() == $_child_menu['action_name']) {
                  $_menu = 'style="display:block"';
                }
              }
            }
          ?>
          <ul class="treeview-menu menu-open" <?=$_menu;?>>
            <?php if(isset($sub_menu)) : foreach($sub_menu as $k=>$_child_menu) :   ?>

            <li><a <?php
                if(AuthMenu::getController() == $_child_menu['controller'] && AuthMenu::getAction() == $_child_menu['action_name']) {
                  echo 'style="color:#fff;"';
                }
              ?> href="<?php  echo $_child_menu['url']; ?>" ><i class="fa fa-circle-o"></i> <?php echo $_child_menu['name']; ?></a></li>
            <?php endforeach; endif; ?>
          </ul>
        </li> 
      <?php endforeach; endif;?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php echo $sf_content ?>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>卡路里后台</b>
    </div>
    <strong>Copyright &copy; 2017-2027 .</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
  <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px; display: none;"></div>
  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px; display: none;"></div>
  <div class="chart" id="line-chart" style="height: 250px; display: none;"></div>

</div>
  <!-- /内容区 -->
  <script type="text/javascript" src="//www.kaluli.com/js/tradeadmin/common/init.js"></script>
  <script type="text/javascript" src="//www.kaluli.com/js/tradeadmin/common/layout.js"></script>
  <script>
    var SUBMENU_CONFIG = <?php echo json_encode($__MENU__['child']); ?>; /*菜单JSON*/
    var _current_id = $('#current-id').attr('current-id');
    //一级导航点击
    $('.J_top_nav').on('click',function(e){

      $('#subnav').empty();
      //取消事件的默认动作
      e.preventDefault();
      //终止事件 不再派发事件
      e.stopPropagation();
      $(this).parent().addClass('current').siblings().removeClass('current');
      var data_id = $(this).attr('data-id'),
      data_list = SUBMENU_CONFIG[data_id];
      var _current = _html = '';
      for (var attr in data_list) {
        _html += '<li class="treeview"><a href="#"><i class="fa fa-th"></i>';
        _html += '<span>' + attr + '</span>';
        _html += '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>';
        _html += '<ul class="treeview-menu">';
        $.each(data_list[attr], function (i, v) {
          if (v.current) {
            _current = 'current';
          } else {
            if(v.id == _current_id)  _current = 'current';
          }
          _html += '<li data-id="'+ v.id+'" class="' + _current + '"><a class="item" href="' + v.url + '"><i class="fa fa-circle-o"></i>' + v.name + '</a></li>';
          _current = '';

        });
        _html += '</ul>';
        _html += '</li>';
      }
      $('#subnav').html(_html);
    });

          


  </script>
  </body>
</html>
