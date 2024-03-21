<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Ref_instansi_model extends CI_Model
{
	
	private $_table= "ref_instansi";

	public $id;
	public $kode;
	public $nama;
	public $alamat;

	public function rules()
	{
		return [
			['field' => 'kode',
			'label' => 'Kode Instansi',
			'rules' => 'required'],

			['field' => 'nama',
			'label' => 'Nama Instansi',
			'rules' => 'required'],

			['field' => 'alamat',
			'label' => 'Alamat Instansi',
			'rules' => '']
		];
	}

	public function getALL(){
		return $this->db->get($this->_table)->result();
	}

	public function getById($id){
		return $this->db->get_where($this->_table, ["id" => $id])->row();
	}

	public function save(){
		$post = $this->input->post();
		// $this->id = uniqid();
		$this->kode = $post["kode"];
		$this->nama = $post["nama"];
		$this->alamat = $post["alamat"];
		$this->db->insert($this->_table, $this);
	}
	
	public function update($id){
		$data = array(
			"kode" => $this->input->post('kode'),
			"nama" => $this->input->post('nama'),
			"alamat" => $this->input->post('alamat')
		);

		$this->db->where('id', $id);
	    $this->db->update('ref_instansi', $data); // Untuk mengeksekusi perintah update data
	}

	// Fungsi untuk melakukan menghapus data siswa berdasarkan NIS siswa
	public function delete($id){
		$this->db->where('id', $id);
    	$this->db->delete('ref_instansi'); // Untuk mengeksekusi perintah delete data
	}

	function get_select() {
		$this->db->select('id, nama');
		$this->db->order_by('nama'); 
		$query = $this->db->get($this->_table);
		if($query->num_rows()!==0) {
			return $query->result();
		}
		else
			return FALSE;
	}

}
?>
