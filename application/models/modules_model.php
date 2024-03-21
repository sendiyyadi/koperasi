<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class modules_model extends CI_Model {
	private $tbl = 'sec_modules';
	
	function __construct() {
		parent::__construct();
	}
		
	function get_all()
	{	$sql = "select *
				from sec_modules
				order by id";
				
		$query = $this->db->query($sql);
		if($query->num_rows()!==0)
		{
			return $query->result();
		}
		else
			return FALSE;
	}
		
	function get($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get($this->tbl);
		if($query->num_rows()!==0)
		{
			return $query->row();
		}
		else
			return FALSE;
	}
	
	//-- admin
	function save($data) {
		$this->db->insert($this->tbl,$data);
		return $this->db->insert_id();
	}
	
	function update($id, $data) {
		$this->db->where('id', $id);
		$this->db->update($this->tbl,$data);
	}
	
	function delete_old($id) {
		$this->db->where('id', $id);
		$this->db->delete($this->tbl);
	}

	function delete($id) {
		// delete referensi dulu 
        $this->db->where('module_id', $id);
        $this->db->delete('sec_group_modules');

		$this->db->where('id', $id);
		$this->db->delete($this->tbl);
	}

}

/* End of file _model.php */