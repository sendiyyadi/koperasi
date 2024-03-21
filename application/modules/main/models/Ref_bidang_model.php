<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Ref_bidang_model extends CI_Model
{
	
	private $_table= "ref_bidang";

	public $id;
	public $kode;
	public $nama;
	public $alamat;

	public function rules()
	{
		return [
			['field' => 'id_instansi',
			'label' => 'Instansi',
			'rules' => 'required'],

			['field' => 'kode',
			'label' => 'Kode Bidang',
			'rules' => 'required'],

			['field' => 'nama',
			'label' => 'Nama Bidang',
			'rules' => 'required'],

			['field' => 'alamat',
			'label' => 'Alamat Bidang',
			'rules' => '']
		];
	}

	public function getALL(){
		$qry = "select i.nama as nama_instansi, b.*
				from ref_bidang b 
				join ref_instansi i on i.id = b.id_instansi";
		return $this->db->query($qry)->result();
	}

	public function getById($id){
		return $this->db->get_where($this->_table, ["id" => $id])->row();
	}

	public function save(){
		$post = $this->input->post();
		// $this->id = uniqid();
		$this->id_instansi = $post["id_instansi"];
		$this->kode = $post["kode"];
		$this->nama = $post["nama"];
		$this->alamat = $post["alamat"];
		$this->db->insert($this->_table, $this);
	}
	
	public function update($id){
		$data = array(
			"id_instansi" => $this->input->post('id_instansi'),
			"kode" => $this->input->post('kode'),
			"nama" => $this->input->post('nama'),
			"alamat" => $this->input->post('alamat')
		);

		$this->db->where('id', $id);
	    $this->db->update('ref_bidang', $data); // Untuk mengeksekusi perintah update data
	}

	// Fungsi untuk melakukan menghapus data siswa berdasarkan NIS siswa
	public function delete($id){
		$this->db->where('id', $id);
    	$this->db->delete('ref_bidang'); // Untuk mengeksekusi perintah delete data
	}

	function get_select_instansi() {
		$this->db->select('id, nama');
		$this->db->order_by('nama'); 
		$query = $this->db->get('ref_instansi');
		if($query->num_rows()!==0) {
			return $query->result();
		}
		else
			return FALSE;
	}

}
?>
