<?php $this->load->view('_head'); ?>

<script>
var mID;
var oTable;

$(document).ready(function() {
	oTable = $('#table1').dataTable({
		/* "sScrollY": "380px", */
		"bScrollCollapse": true,
		"bPaginate": false,
		"bJQueryUI": true,
		"sDom": '<"toolbar">frtip',

		"aoColumnDefs": [
			{ "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] }
		],
		"aoColumns": [
			null,
			{ "bSearchable": true,  "sWidth": "10%" },
			{ "bSearchable": true,  "sWidth": "25%" },
			{ "bSearchable": false, "sWidth": "5%" },
			{ "bSearchable": true,  "sWidth": "15%" },
			{ "bSearchable": true,  "sWidth": "5%" , "sClass": "center"},
			{ "bSearchable": true,  "sWidth": "5%" },
		],

		"fnRowCallback": function (nRow, aData, iDisplayIndex) {
			$(nRow).on("click", function (event) {
				if ($(this).hasClass('row_selected')) {
					/* mID = '';
					$(this).removeClass('row_selected'); */
				} else {
					var data = oTable.fnGetData( this );
					mID = data[0];
					
					oTable.$('tr.row_selected').removeClass('row_selected');
					$(this).addClass('row_selected');
				}
			})
		},
		"bSort": true,
		"bInfo": false,
		"bProcessing": false,
		"sAjaxSource": "<?php echo active_module_url();?>users/grid"
	});

	// 	$("div.toolbar").html('<div class="btn-group pull-left"><button id="btn_tambah" class="btn btn-info" type="button">Tambah</button><button id="btn_edit" class="btn btn-warning" type="button">Edit</button> <button id="btn_delete" class="btn btn-danger" type="button">Hapus</button></div>');

	var btn  = '<div class="btn-group pull-left">';
	    btn += '<button id="btn_tambah" class="btn btn-info" type="button">Tambah</button>';
	    btn += '<button id="btn_edit" class="btn btn-warning" type="button">Edit</button>';
	    btn += '<button id="btn_delete" class="btn btn-danger" type="button">Hapus</button></div>';
	 	btn += '<div class="input-group pull-left">';
		btn +=	'<div class="btn-group pull-left">';
		btn +=	'	<div class="input-prepend"><span class="add-on"><i>LEVEL</i></span>';
		btn +=	'        <?php echo $select_level_id;?>';
		btn +=	'   </div>';
		btn +=	'</div>';	    
	$("div.toolbar").html(btn);

	$('#btn_tambah').click(function() {
		window.location = '<?php echo active_module_url();?>users/add/';
	});

	$('#btn_edit').click(function() {
		if(mID) {
			window.location = '<?php echo active_module_url();?>users/edit/'+mID;
		}else{
			alert('Silahkan pilih data yang akan diedit');
		}
	});

	$('#btn_delete').click(function() {
		if(mID) {
			var hapus = confirm('Hapus data ini?');
			if(hapus==true) {
				window.location = '<?php echo active_module_url();?>users/delete/'+mID;
			};
		}else{
			alert('Silahkan pilih data yang akan dihapus');
		}
	});

	$('#level_id').change(function() {
		oTable.fnReloadAjax("<?php echo active_module_url();?>users/grid/"+$('#level_id').val());
	});

});

function update_unit(id, a) {
	var val = Number(a);
	$.ajax({
	  url: '<?php echo active_module_url()?>users/update_unit/' + id + '/' + val,
	  success: function(data) {
		/*  */
	  }
	});
}

function disable_user(id, a) {
	var val = Number(a);
	$.ajax({
	  url: '<?php echo active_module_url()?>users/disable_user/' + id + '/' + val,
	  success: function(data) {
		/*  */
	  }
	});
}
</script>

<div class="content-wrapper" style="width:1200px;">
    <div class="container-fluid">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#"><strong>USERS</strong></a>
			</li>
		</ul>
		
		<?php echo msg_block();?>
		
		<table class="table table-bordered" id="table1">
			<thead>
				<tr>
					<th>Index</th>
					<th>User ID</th>
					<th>Nama</th>
                    <th>Level</th>
					<th>Jabatan</th>
					<th>Disabled</th>
					<th>Tgl. Input</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<?php $this->load->view('_foot'); ?>