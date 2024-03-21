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

body #cuDialogButton {

    /* new custom width */
    width: 950px;
    /* must be half of the width, minus scrollbar on the left (30px) */
   /** margin-left: 20px;  **/
      height: 35%;
   //** width: 100%;  **/

}

</style>

<script>
var mID;
var dID;
var oTable;
var oTable2;
var oTable3;

var glo_module = "";
var glo_grup_id = "";
var glo_modul_id = "";
var glo_modules_btn_id = "";

function btn_tambah_subdtl() {
	if (glo_module == '') {
		alert("Module belum di Pilih...!");
    } else {
		document.getElementById('cuDialogButtonLabel').innerHTML ='Tambah Button'; 
		document.getElementById('dtl_module').value = glo_module; 
		document.getElementById('dtl_modul_id').value = glo_modul_id; 

		document.getElementById('dtl_nama').value = ''; 
		document.getElementById('dtl_kode').value = ''; 
		document.getElementById('dtl_btn_no').value = ''; 

        $('#cuDialogButton').modal('show');    	
    }
};
 
function tambah_btn_detil() {

  	var nama      = document.getElementById('dtl_nama').value;
  	var module_id = glo_modul_id;
  	var kode      = document.getElementById('dtl_kode').value;
  	var btn_no    = document.getElementById('dtl_btn_no').value;

	var params = {
		nama: nama,
		module_id: module_id,
		kode: kode, 
		btn_no: btn_no,
	};
	var data_params = decodeURIComponent($.param(params));
 
	$.ajax({
		url: "<?php echo active_module_url()?>privileges/tambah_btn_detil/?"+data_params,
		async: false,
		success: function (data) {
			//$('#pajak_id').html(data);
			$('#cuDialogButton').modal('hide'); 
		},
		error: function (xhr, desc, er) {
			alert(er);
		}
	});	
 
}
 
$(document).ready(function() {

	oTable = $('#table1').dataTable({
		/* "sScrollY": "380px", */
		"bScrollCollapse": true,
		"bPaginate": false,
		"bJQueryUI": true,
		"sDom": '<"toolbar">frtip',

		"aaSorting": [[ 0, "asc" ]],
		"aoColumnDefs": [
			{ "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] }
		],
		"aoColumns": [
			null,
			{ "sWidth": ""},
			{ "sWidth": "" }
            
		],
		"fnRowCallback": function (nRow, aData, iDisplayIndex) {
			$(nRow).on("click", function (event) {
				if ($(this).hasClass('row_selected')) {
					/* 
					mID = '';
					$(this).removeClass('row_selected');
					oTable2.fnReloadAjax("<?php echo active_module_url();?>privileges/grid/");
					 */
					 glo_module = ""; glo_modul_id = ""; glo_modules_btn_id = "";

				} else {
					var data = oTable.fnGetData( this );
					mID = data[0];
					glo_grup_id = data[0];
					dID = '';
					glo_module = ""; glo_modul_id = ""; glo_modules_btn_id = "";

					oTable.$('tr.row_selected').removeClass('row_selected');
					$(this).addClass('row_selected');
					
					oTable2.fnReloadAjax("<?php echo active_module_url();?>privileges/grid/"+$('#mod_id').val()+'/'+glo_grup_id);

					oTable3.fnReloadAjax("<?php echo active_module_url();?>privileges/grid_btn/"+$('#mod_id').val()+'/'+glo_grup_id+'/'+glo_modul_id);

				}
			})
		},
		"fnInitComplete": function(oSettings, json) {
			if (!glo_grup_id) $('#mod_id').trigger('change');
		},
		"bSort": true,
		"bInfo": false,
		"bFilter": false,
		"bProcessing": false,
		"sAjaxSource": "<?php echo active_module_url();?>groups/grid"
	});

	oTable2 = $('#table2').dataTable({
		/* "sScrollY": "380px", */
		"bScrollCollapse": true,
		"bPaginate": false,
		"bJQueryUI": true,
		"sDom": '<"toolbar2x">frtip',

		"aoColumnDefs": [
			{ "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] }
		],
		"aoColumns": [
			null,
			null,
			null,
			{ "sWidth": "41px" },
			{ "sWidth": "55px" },
			{ "sWidth": "41px" },
			{ "sWidth": "46px" }
		],
		"fnRowCallback": function (nRow, aData, iDisplayIndex) {
			$(nRow).on("click", function (event) {
				if ($(this).hasClass('row_selected')) {
					/* dID = '';
					$(this).removeClass('row_selected'); */
					glo_module = ""; glo_modul_id = ""; glo_modules_btn_id = "";

				} else {
					var data = oTable2.fnGetData( this );
					dID = data[0];
					glo_module = data[2];
					glo_modul_id = data[0];
					glo_modules_btn_id = "";
					
					oTable2.$('tr.row_selected').removeClass('row_selected');
					$(this).addClass('row_selected');

					oTable3.fnReloadAjax("<?php echo active_module_url();?>privileges/grid_btn/"+$('#mod_id').val()+'/'+glo_grup_id+'/'+glo_modul_id);
				}
			
			})
		},
		"bSort": true,
		"bInfo": false,
		"bProcessing": false,
		"bFilter": false,
		"sAjaxSource": "<?php echo active_module_url();?>privileges/grid/"
	});

	var tb2_array = [
		<?php //if(is_super_admin()) { 
			if ( $this->session->userdata('userid') == 1 ) {
		?>
		'<div class="btn-group">',
		'	<button id="btn_tambah" class="btn btn-info" type="button">Tambah</button>',
		'	<button id="btn_edit" class="btn btn-warning" type="button">Edit</button>', 
		'	<button id="btn_delete" class="btn btn-danger" type="button">Hapus</button>',
		'</div>', 
		<?php } ?>
	];
	var tb2 = tb2_array.join(' ');	
	$("div.toolbar2").html(tb2);

	oTable3 = $('#table3').dataTable({
		/* "sScrollY": "380px", */
		"bScrollCollapse": true,
		"bPaginate": false,
		"bJQueryUI": true,
		"sDom": '<"toolbar3x">frtip',
		"aaSorting": [[ 1, "asc" ]],
		"aoColumnDefs": [
			{ "bSearchable": false, "bVisible": false, "aTargets": [ 0 ] }
		],
		"aoColumns": [
			null,
			{ "sWidth": "16%" },
			{ "sWidth": "24%" },
			null,
			{ "sWidth": "17%" }

		],
		"fnRowCallback": function (nRow, aData, iDisplayIndex) {
			$(nRow).on("click", function (event) {
				if ($(this).hasClass('row_selected')) {
					/* dID = '';
					$(this).removeClass('row_selected'); */
				} else {
					var data = oTable3.fnGetData( this );
					//dID = data[0];
					glo_modules_btn_id = data[0];
					
					oTable3.$('tr.row_selected').removeClass('row_selected');
					$(this).addClass('row_selected');
				}
			})
		},
		"bSort": true,
		"bInfo": false,
		"bProcessing": false,
		"bFilter": false,
		"sAjaxSource": "<?php echo active_module_url();?>privileges/grid_btn/"
	});
 
	var tb3_array = [
		<?php if(is_super_admin()) { ?>
		'<div class="btn-group">',
		'	<button id="btn_tambah_dtl" class="btn btn-info" onclick="btn_tambah_subdtl()" type="button">Tambah</button>',
		'	<button id="btn_delete_dtl" class="btn btn-danger" type="button">Hapus</button>',
		'</div>',
		<?php } ?> 
	];
	var tb3 = tb3_array.join(' ');	
	$("div.toolbar3").html(tb3);

	$('#btn_tambah').click(function() {

		// begin parameter  
		var app_id       = $('#mod_id').val();
		var app_selected = $("#mod_id option:selected").text();

		window.location = '<?php echo active_module_url();?>privileges/add/'+$('#mod_id').val()+'/'+app_selected; 

	});

	$('#btn_edit').click(function() {
		
		var app_selected = $("#mod_id option:selected").text();

		if(glo_modul_id) {
			window.location = '<?php echo active_module_url();?>privileges/edit/'+glo_modul_id+'/'+app_selected; 
		}else{
			alert('Silahkan pilih data yang akan diedit');
		}
	});

	$('#btn_delete').click(function() {
		if(glo_modul_id) {
			var hapus = confirm('Hapus data ini?');
			if(hapus==true) {
				window.location = '<?php echo active_module_url();?>privileges/delete/'+glo_modul_id;
			};
		}else{
			alert('Silahkan pilih data yang akan dihapus');
		}
	});
	
	$('#mod_id').change(function() {
		if (!glo_grup_id) selecttopRow();
		dID = ''; glo_modul_id = ''; glo_modules_btn_id = "";

		oTable2.fnReloadAjax("<?php echo active_module_url();?>privileges/grid/"+$('#mod_id').val()+'/'+glo_grup_id);

		oTable3.fnReloadAjax("<?php echo active_module_url();?>privileges/grid_btn/"+$('#mod_id').val()+'/'+glo_grup_id+'/'+glo_modul_id);

	});
		
	function selecttopRow() {
		var nTop = $('#table1 tbody tr')[0];
		var iPos = oTable.fnGetPosition( nTop );

		/* Use iPos to select the row */
		var data = oTable.fnGetData(iPos);
		mID = data[0];
		glo_grup_id = data[0];
					
		$('#table1 tbody tr:eq(0)').addClass('row_selected');
	}

    $('#btn_dtl_simpan').click( function (e) {

		var dtl_nama = $('#dtl_nama').val();  
		var dtl_kode = $('#dtl_kode').val();  
		var dtl_btn_no = $('#dtl_btn_no').val();  // document.getElementById('dtl_btn_no').value ;

		if (dtl_kode == '') {	alert("Kode Button harus di isi...!"); return;}
		if (dtl_nama == '') {	alert("Keterangan harus di isi...!"); return;}
		if (dtl_btn_no == '') {	alert("No. Button harus di isi...!"); return;}
		if (dtl_btn_no == '0') {	alert("No. Button harus di isi...!"); return;}

		tambah_btn_detil();
		oTable3.fnReloadAjax("<?php echo active_module_url();?>privileges/grid_btn/"+$('#mod_id').val()+'/'+glo_grup_id+'/'+glo_modul_id);

    });

	$('#btn_delete_dtl').click(function(e) {
		if(glo_modules_btn_id && glo_modul_id) {
			var hapus = confirm('Hapus data ini?'+glo_modules_btn_id);
			if(hapus==true) {
				//window.location = '<?php echo active_module_url();?>privileges/hapus_btn_detil/'+glo_modules_btn_id;
				delete_btn_detil(glo_modules_btn_id);
				oTable3.fnReloadAjax("<?php echo active_module_url();?>privileges/grid_btn/"+$('#mod_id').val()+'/'+glo_grup_id+'/'+glo_modul_id);
			};

		} else{
			alert('Silahkan pilih data yang akan dihapus');
		}
	});

	$("#cuDialogButton").draggable({
         handle: ".modal-header"
    });

});

function update_stat(gid, grup_id, fld, a) {

	var val = Number(a);
	$.ajax({
	  url: '<?php echo active_module_url()?>privileges/update_stat/' + gid +'/' + grup_id +'/' + fld + '/' + val,
	  success: function(data) {
	  }
	});
}

function update_stat_role_btn(group_id, modules_id, modules_btn_id, flg) {

	var val = Number(flg);
	$.ajax({
	  url: '<?php echo active_module_url()?>privileges/upd_stat_role_btn/'+group_id+'/'+modules_id+'/'+modules_btn_id+'/'+val,
	  success: function(data) {
	  }
	});
}

function delete_btn_detil(modules_btn_id) {
	$.ajax({
	  url: '<?php echo active_module_url()?>privileges/hapus_btn_detil/'+modules_btn_id,
	  success: function(data) {
	  }
	});
}

</script>

<div class="content-wrapper">
    <div class="container-fluid">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#"><strong>GROUP PRIVILEGES</strong></a>
			</li>
		</ul>
		
		<?php echo msg_block();?>
		
		<div class="row-fluid">
			<div class="span3">
				<strong>Aplikasi : </strong><select name="mod_id" id="mod_id"><?php echo $app_data;?></select>
			</div>

			<div class="span5" style="width:550px">
				<div class="toolbar2"></div>
			</div>

			<div class="span2">
				<div class="toolbar3"></div>
			</div>			

		</div>
		
		<div class="row-fluid">
			<div class="span3">
				<table class="table table-bordered" id="table1">
					<thead>
						<tr>
							<th>Index</th>
							<th>Kode</th>
                            <th>Nama Group</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div class="span9">
                <div class="row-fluid">
                	<div class="span7">
                        <table class="table table-bordered" id="table2">
                            <thead>
                                <tr>
                                    <th>Index</th>
                                    <th>Kode</th>
                                    <th>Module</th>
                                    <th>Baca</th>
                                    <th>Tambah</th>
                                    <th>Edit</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
					</div>
					<div class="span5">
                        <table class="table table-bordered" id="table3">
                            <thead>
                                <tr>
                                    <th>Index</th>
                                    <th>No.</th>
                                    <th>Kd.Button</th>
                                    <th>Keterangan</th>
                                    <th>Hak</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
					</div>			
				</div>
       		</div>
	</div>

    <!-- Begin Modal Dialog entry Detail -->
    <div id="cuDialogButton" class="modal" style="width:600px" tabindex="-1" role="dialog" aria-labelledby="cuDialogButtonLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 id="cuDialogButtonLabel"></h3>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">

                     <div class="control-group">
                        <label style="width:135px;" class="control-label" for="dtl_module">Module</label>
                        <div class="controls">
                            <input style="width:200px;" type="text" name="dtl_module" id="dtl_module" readonly/>
                        </div>
                    </div>

                     <div class="control-group">
                        <label style="width:135px;" class="control-label" for="dtl_kode">Kode Button</label>
                        <div class="controls">
                            <input style="width:150px;" type="text" maxlength="20" name="dtl_kode" id="dtl_kode" value="" required/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label style="width:135px;" class="control-label" for="dtl_nama">Keterangan</label>
                        <div class="controls">
                            <input class="input" style="width:400px"  type="text" maxlength="50" name="dtl_nama" id="dtl_nama" value="" required/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label style="width:135px;" class="control-label" for="dtl_btn_no">No. Button</label>
                        <div class="controls">
                            <input class="input" style="width:50px" type="number" min="1" max="9" step="1" name="dtl_btn_no" id="dtl_btn_no" value=1 required/>
                        </div>
                    </div>

                    <!---Detail hide modal -->
                    <input class="input-medium" type="hidden" name="dtl_id" id="dtl_id" value="99"/>
                    <input class="input-medium" type="hidden" name="dtl_modul_id" id="dtl_modul_id" value="0"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_dtl_simpan">Simpan</button>
                <button type="button" class="btn btn-default" id="btn_dtl_batal" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
    <!-- end Modal Dialog entry Detail -->

</div>
<?php $this->load->view('_foot'); ?>