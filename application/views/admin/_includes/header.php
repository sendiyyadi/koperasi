 <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>K</b>MS</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Koperasi</b>Makmur</span>
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
          <!-- <li class="dropdown messages-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li>
                <!-- inner menu: contains the actual data --
                <ul class="menu">
                  <li><!-- start message --
                    <a href="<?php echo base_url(); ?>">
                      <div class="pull-left">
                        <img src="<?php echo base_url('assetAdmin/dist/img/user1-128x128.jpg');?>" class="img-circle" alt="User sip">
                      </div>
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <p>Why not buy a new awesome theme?</p>
                    </a>
                  </li>
                  <!-- end message --
                </ul>
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li> -->

          <?php if(is_super_admin() || $this->session->userdata('canchangemod')) :?>
          <li class="dropdown messages-menu">
          <form class="btn-group pull-right" style="padding-top: 15px; padding-bottom: 15px; line-height: 20px;">
            <select name="app_id" id="app_id" <?//if(!$app_enabled) echo 'disabled';?>>
              <?php if( isset($apps) && $apps): ?>
                          <?php //if(is_super_admin()): ?>
                              <!-- <option value="admin">ADMIN</option> -->
                          <?php //endif; ?>

                          <?php foreach($apps as $data): ?>
                              <option value="<?php echo $data->app_path;?>" <?php if(active_module()==$data->app_path) echo 'selected';?>><?php echo $data->nama;?></option>
                          <?php endforeach;?>
              <?php else:?>
                          <option value="">Not configured!</option>
              <?php endif; ?>
            </select>
          </form>
          </li>
          <?php endif?>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo base_url('assetAdmin/dist/img/user2-160x160.jpg'); ?>" class="user-image" alt="User Image">
              <span class="hidden-xs">Admin Koperasi</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo base_url('assetAdmin/dist/img/user2-160x160.jpg')?>" class="img-circle" alt="User sipo">

                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('Auth/logout') ?>" class="btn btn-default btn-flat">Sign out</a>
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


  <script>
    
    $(document).ready(function() {
        $('#app_id').change( function() {
          window.location = '<?php echo base_url();?>change_module/'+$('#app_id').val();
        });

    });

  </script>