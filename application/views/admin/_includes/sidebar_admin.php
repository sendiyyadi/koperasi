<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="<?php echo base_url('assetAdmin/dist/img/user2-160x160.jpg')?> " class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>Admin Koperasi</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- search form -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Navigation</li>\

        <?php if (is_super_admin()){?>

        <li><a href="<?php echo base_url('admin') ?>"><i class="fa fa-fw fa-book"></i> <span>Dashboard</span></a></li>
        <li><a href="<?php echo base_url('admin/apps') ?>"><i class="fa fa-fw fa-cube"></i> <span>Aplikasi</span></a></li>
        <li><a href="<?php echo base_url('admin/users') ?>"><i class="fa fa-fw fa-user-plus"></i> <span>Users</span></a></li>
        <li><a href="<?php echo base_url('admin/groups') ?>"><i class="fa fa-fw fa-child"></i> <span>Group Users</span></a></li>
        <li><a href="<?php echo base_url('admin/privileges') ?>"><i class="fa fa-fw fa-gear"></i> <span>Privileges</span></a></li>
        
        <?php } ?> 

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
