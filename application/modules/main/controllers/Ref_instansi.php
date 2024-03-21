<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ref_instansi extends CI_Controller
{
	private $filename = "import_data"; // Kita tentukan nama filenya
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Ref_instansi_model");
        $this->load->library('form_validation');
		$this->load->model("apps_model");
		
    }

    public function index()
    {
        $data["instansi"] = $this->Ref_instansi_model->getAll();
		$data['apps']    = $this->apps_model->get_active_only();
        $this->load->view("instansi/lihat_instansi", $data);
    }

    public function add()
    {
        $instansi = $this->Ref_instansi_model;
        $validation = $this->form_validation;
        $validation->set_rules($instansi->rules());

        if ($validation->run()) {
            $instansi->save();
            $this->session->set_flashdata('success', 'Tambah Instansi '.$instansi->nama.' Berhasil Disimpan');
            redirect('main/Ref_instansi/index');
        }
		
		$data['apps']    = $this->apps_model->get_active_only();
        $this->load->view("instansi/tambah_instansi");
    }

    public function edit($id){

    	$instansi = $this->Ref_instansi_model; //object model
        $validation = $this->form_validation; //object validasi
        $validation->set_rules($instansi->rules()); //terapkan rules di Ref_instansi_model.php

        if ($validation->run()) { //lakukan validasi form
            $instansi->update($id); // update data
            $this->session->set_flashdata('success', 'Data Instansi '.$instansi->getById($id)->nama.' Berhasil Diubah');
            redirect('main/Ref_instansi/index');
        }

        $data['instansi'] = $this->Ref_instansi_model->getById($id);
		$data['apps']    = $this->apps_model->get_active_only();
        $this->load->view('instansi/edit_instansi', $data);
    }

    public function delete($id){
	    $this->Ref_instansi_model->delete($id); // Panggil fungsi delete() yang ada di SiswaModel.php
	    $this->session->set_flashdata('success', 'Data Instansi Berhasil Dihapus');
	    redirect('main/Ref_instansi/index');
	}

	public function form(){
		$data = array(); // Buat variabel $data sebagai array

		if(isset($_POST['preview'])){ // Jika user menekan tombol Preview pada form
			// lakukan upload file dengan memanggil function upload yang ada di SiswaModel.php
			$upload = $this->Ref_instansi_model->upload_file($this->filename);

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
		$this->load->view('instansi/validasi_import', $data);
	}


}
