<?php $this->load->view('_head'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success" role="alert">
        <?php echo $this->session->flashdata('success'); ?>
        <a href="<?php echo base_url('main/Ref_instansi/index') ?>">Ok</a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
      </div>
    <?php endif; ?>

    <section class="content-header">
      <h1>
        Kelola
        <small>Data Instansi</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-fw fa-user-plus"></i> Instansi</a></li>
        <li><a href="<?php echo base_url('main/Ref_instansi/index') ?>">Lihat Data Instansi</a></li>
        <li><a href="<?php echo base_url('main/Ref_instansi/add') ?>">Tambah Data Instansi</a></li>
      </ol>
    </section>
    

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-8">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Pengisian Form</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="<?php echo base_url('main/Ref_instansi/edit/'.$instansi->id) ?>" method="post">
              <input type="hidden" name="id" value="<?php echo $instansi->id?>" />
              <div class="box-body">
                <div class="form-group">
                  <label>Kode</label>
                  <input name="kode" class="form-control <?php echo form_error('kode') ? 'is-invalid':'' ?>" placeholder="Masukan Kode Instansi" value="<?php echo $instansi->kode?>" type="text"/>
                  <div class="invalid-feedback">
                    <?php echo form_error('kode') ?>
                  </div>
                </div>
                <div class="form-group">
                  <label>Nama</label>
                  <input name="nama" class="form-control <?php echo form_error('nama') ? 'is-invalid':'' ?>" placeholder="Masukan Nama Instansi" value="<?php echo $instansi->nama?>" type="text">
                  <div class="invalid-feedback">
                    <?php echo form_error('nama') ?>
                  </div>
                </div>
                <div class="form-group">
                  <label>Alamat</label>
                  <input name="alamat" class="form-control <?php echo form_error('alamat') ? 'is-invalid':'' ?>" placeholder="Masukan Alamat Instansi" value="<?php echo $instansi->alamat?>" type="text"/>
                  <div class="invalid-feedback">
                    <?php echo form_error('alamat')?>
                  </div>
                </div>
                
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button class="btn btn-success" name="submit" type="submit"><i class="fa fa-fw fa-plus"></i>Simpan</button>
                <button class="btn btn-danger" type="reset"><i style="margin-left: -3px;" class="fa fa-fw fa-times"></i>Batal</button>
              </div>
            </form>
          </div>
          <!-- /.box -->

        </div>
        <!--/.col (left) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view("admin/_includes/footer.php") ?>
  <?php $this->load->view("admin/_includes/control_sidebar.php") ?>

  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<?php $this->load->view("admin/_includes/bottom_script_view.php") ?>
<!-- page script -->
</body>
</html>
