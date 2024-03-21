<?php $this->load->view('_head'); ?>

<script>
$(document).ready(function() {
	$('#btn_cancel').click(function() {
		window.location = '<?php echo active_module_url();?>apps';
	});
});
</script>

<div class="content-wrapper">
    <div class="container-fluid">
		<div class="page-header" style="margin-bottom:8px;">
			<strong>:: Modules - Seting Tahun Anggaran dan Step</strong>
		</div>
    </div>

    <div class="container-fluid">
		<?php
		if(validation_errors()){
			echo '<blockquote><strong>Harap melengkapi data berikut :</strong>';
			echo validation_errors('<small>','</small>');
			echo '</blockquote>';
		} ?>
	</div>

    <div class="container-fluid">
		<?php echo form_open($faction, array('id'=>'myform','class'=>'form-horizontal','enctype'=>'multipart/form-data'));?>
			<input type="hidden" name="id" value="<?php echo $dt['id']?>"/>
			<input type="hidden" name="app_id" value="<?php echo $dt['app_id']?>"/>
			<div class="control-group">
				<label class="control-label">Tahun</label>
				<div class="controls">
					<input class="input-mini" type="text" name="tahun" value="<?php echo $dt['tahun']?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Step</label>
				<div class="controls">
					<input class="input" type="text" name="step" value="<?php echo $dt['step']?>">
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