<?php $this->load->view('_head'); ?>

<style>
.nav-tabs > .active > a, .nav-pills > .active > a:hover {
    color: blue;
}
.form-horizontal .controls {
  margin-left: 120px;  /* changed from 180px to 140px */
}
.form-horizontal .control-group {
    margin-bottom: 1px;
}
.form-horizontal .control-label{
	text-align:left;
	width: 120px; /* changed from 160px to 120px */
}
.form-horizontal input  {
	height: 14px !important;
	border-radius: 2px 2px 2px 2px !important;
	margin-bottom: 1px !important;
}
.form-horizontal select  {
	height: 24px !important;
	padding: 2px !important;
	border-radius: 2px 2px 2px 2px !important;
	margin-bottom: 1px !important;
}

button {
	height: 24px !important;
	padding: 4px 8px !important;
	border-radius: 2px 2px 2px 2px !important;
	margin-bottom: 1px !important;
}

hr {
  border: 0;
  border-bottom: 1px solid #dddddd;
}

.table.dataTable {
	margin-bottom: 8px;
	font-size: 10px;
}
.dataTables_wrapper {
    min-height: 100px; !important;
}
.modal {
    top: 10%;
	width: 800px;
	margin-left: -400px;
	--max-height: 250px;
}
.modal-body {
	--padding: 5px;
}
</style>

<script>
$(document).ready(function() {
	$('#btn_cancel').click(function() {
		window.location = "<?php echo active_module_url();?>";
  /*window.location = "<?php echo $this->uri->segment(2)=='users_chgpwd' ? active_module_url() : active_module_url('users');?>";*/
	});

    $('#passwd_old').change(function() {
		var passwd_old = $('#passwd_old').val();
		passwd_old = passwd_old.replace(/ /g,''); // replace all space
		$('#passwd_old').val(passwd_old);
    });

    $('#nama').change(function() {
		var nama = $('#nama').val();
        nama = nama.replace(/^\s+|\s+$/g,'');  // trim spasi depan blakang
		$('#nama').val(nama);
    });

    $('#passwd').change(function() {
		var passwd = $('#passwd').val();
		passwd = passwd.replace(/ /g,''); // replace all space
		$('#passwd').val(passwd);
    });

    $('#passconf').change(function() {
		var passconf = $('#passconf').val();
		passconf = passconf.replace(/ /g,''); // replace all space
		$('#passconf').val(passconf);
    }); 

});
</script>

<div class="content-wrapper">
    <div class="container-fluid">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#"><strong>UBAH PASSWORD</strong></a>
			</li>
		</ul>
		
		<?php
		if(validation_errors()){
			echo '<blockquote><strong>Harap melengkapi data berikut :</strong>';
			echo validation_errors('<small>','</small>');
			echo '</blockquote>';
		} ?>
		
		<?php echo form_open($faction, array('id'=>'myform','class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
			<input type="hidden" name="id" value="<?php echo $dt['id']?>"/>
			<div class="control-group">
				<label class="control-label">User ID</label>
				<div class="controls">
					<input class="input-medium" type="text" name="userid" id="userid" value="<?php echo $dt['userid']?>" readonly>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Nama</label>
				<div class="controls">
					<input class="input-xlarge" type="text" name="nama" id="nama" value="<?php echo $dt['nama']?>">
				</div>
			</div>

            <div class="control-group">
                <label class="control-label">Password (old)</label>
                <div class="controls">
                    <input class="input-medium" type="password" name="passwd_old" id="passwd_old" value="<?php echo $dt['passwd_old']?>" placeholder="old password">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Password (new)</label>
                <div class="controls">
                    <input class="input-medium" type="password" name="passwd" id="passwd" value="<?php echo $dt['passwd']?>" placeholder="new password">
                </div>
            </div>	

			<div class="control-group">
				<label class="control-label">Password (Confirm)</label>
				<div class="controls">
					<input class="input-medium" type="password" name="passconf" id="passconf" value="<?php echo $dt['passconf']?>" placeholder="new password (Confirm)">
				</div>
			</div>    

			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn" id="btn_cancel">Batal</button>
				</div>
			</div>
		</form>
    </div>
</div>
<?php $this->load->view('_foot'); ?>
