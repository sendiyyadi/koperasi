<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ref_bidang extends CI_Controller
{
	private $filename = "import_data"; // Kita tentukan nama filenya
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Ref_bidang_model");
        $this->load->library('form_validation');
		$this->load->model("apps_model");
		
    }

    public function index()
    {
        $data["bidang"] = $this->Ref_bidang_model->getAll();
		$data['apps']    = $this->apps_model->get_active_only();
        $this->load->view("bidang/lihat_bidang", $data);
    }

    public function add()
    {
        $bidang = $this->Ref_bidang_model;
        $validation = $this->form_validation;
        $validation->set_rules($bidang->rules());

        if ($validation->run()) {
            $bidang->save();
            $this->session->set_flashdata('success', 'Tambah Bidang '.$bidang->nama.' Berhasil Disimpan');
            redirect('main/Ref_bidang/index');
        }

        $select_data = $this->Ref_bidang_model->get_select_instansi();
		$options     = array();
		foreach ($select_data as $row) {
			$options[$row->id] = $row->nama;
		}
		$js                        = 'id="id_instansi" class="form-control" required ';
		$data['sel_instansi'] = form_dropdown('id_instansi', $options, '', $js);
		
		$data['apps']    = $this->apps_model->get_active_only();
        $this->load->view("bidang/tambah_bidang", $data);
    }

    public function edit($id){

    	$bidang = $this->Ref_bidang_model; //object model
        $validation = $this->form_validation; //object validasi
        $validation->set_rules($bidang->rules()); //terapkan rules di Ref_bidang_model.php

        if ($validation->run()) { //lakukan validasi form
            $bidang->update($id); // update data
            $this->session->set_flashdata('success', 'Data Bidang '.$bidang->getById($id)->nama.' Berhasil Diubah');
            redirect('main/Ref_bidang/index');
        }

        $dt = $this->Ref_bidang_model->getById($id);

        $data['bidang'] = $dt;
        $select_data = $this->Ref_bidang_model->get_select_instansi();
		$options     = array();
		foreach ($select_data as $row) {
			$options[$row->id] = $row->nama;
		}
		$js                        = 'id="id_instansi" class="form-control" required ';
		$data['sel_instansi'] = form_dropdown('id_instansi', $options, $dt->id_instansi, $js);

		$data['apps']    = $this->apps_model->get_active_only();
        $this->load->view('bidang/edit_bidang', $data);
    }

    public function delete($id){
	    $this->Ref_bidang_model->delete($id); // Panggil fungsi delete() yang ada di SiswaModel.php
	    $this->session->set_flashdata('success', 'Data Bidang Berhasil Dihapus');
	    redirect('main/Ref_bidang/index');
	}

	public function form(){
		$data = array(); // Buat variabel $data sebagai array

		if(isset($_POST['preview'])){ // Jika user menekan tombol Preview pada form
			// lakukan upload file dengan memanggil function upload yang ada di SiswaModel.php
			$upload = $this->Ref_bidang_model->upload_file($this->filename);

			if($upload['result'] == "success"){ // Jika proses upload sukses
				// Load plugin PHPExcel nya
				include APPPATH.'third_party/PHPExcel/PHPExcel.php';

				$excelreader = new PHPExcel_Reader_Excel2007();
				$loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang tadi diupload ke folder excel
				$sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);

				// Masukan variabel $sheet ke dalam array data yang nantinya akan di kirim ke file form.php
				// Variabel $sheet tersebut berisi data-data yang sudah diinput di dalam excel yang sudha di upload sebelumnya
				$data['sheet'] = $sheet;
			}else{ // Jika proses upload gagal
				$data['upload_error'] = $upload['error']; // Ambil pesan error uploadnya untuk dikirim ke file form dan ditampilkan
			}
		}

		$data['apps']    = $this->apps_model->get_active_only();
		$this->load->view('bidang/validasi_import', $data);
	}


}
