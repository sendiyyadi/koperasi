<?php $this->load->view('_head'); ?>

<script>
$(document).ready(function() {
	$('#btn_cancel').click(function() {
		window.location = '<?php echo active_module_url();?>privileges';
	});

    $('#nama').change(function() {
		var nama = $('#nama').val();
        nama = nama.replace(/^\s+|\s+$/g,'');  // trim spasi depan blakang
		$('#nama').val(nama);
    });

    $('#kode').change(function() {
		var kode = $('#kode').val();
		kode = kode.replace(/ /g,''); // replace all space
		$('#kode').val(kode);
    });

	/* init page */
	var model_form  = parseInt($('#model_form').val());
	if(model_form == 2){
		document.getElementById("kode").readOnly = true; 
	}    

});

function ambil_nama(kode) {

  //  var v_nama = get_nama(kode);
  //  document.getElementById("nama").value = v_nama;
    var kode_selected = $("#kode option:selected").text();
    var kode = $("#kode").val();
    if (kode == '...'){kode_selected = '';}
    document.getElementById("nama").value = kode_selected;

}    

</script>

<div class="content-wrapper">
    <div class="container-fluid">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#"><strong>MODULE - <?php echo $modul_nm?></strong></a>
			</li>
		</ul>
		
		<?php
		if(validation_errors()){
			echo '<blockquote><strong>Harap melengkapi data berikut :</strong>';
			echo validation_errors('<small>','</small>');
			echo '</blockquote>';
		} ?>
		
		<?php echo form_open($faction, array('id'=>'myform','class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
			<input type="hidden" id="model_form" value="<?php echo $model_form;?>"/>
			<input type="hidden" name="id"     id="id"     value="<?php echo $dt['id']?>"/>
			<input type="hidden" name="app_id" id="app_id" value="<?php echo $dt['app_id']?>"/>
			<!--
			<div class="control-group">
				<label class="control-label" for="kode">Kode</label>
				<div class="controls">
                    <?php echo $select_module;?>
				</div>
			</div>
			-->
			<div class="control-group">
				<label class="control-label" for="nama">Kode</label>
				<div class="controls">
					<input class="input-xlarge" type="text" id="kode" name="kode" value="<?php echo $dt['kode']?>" required/>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="nama">Module</label>
				<div class="controls">
					<input class="input-xlarge" type="text" id="nama" name="nama" value="<?php echo $dt['nama']?>" required/>
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