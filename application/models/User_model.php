<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
	public function get($username){
		$this->db->where('userid', $username);
		$result = $this->db->get('sec_users')->row();
		return $result;
	}

}

/* End of file .php */
