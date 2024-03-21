<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class groups_model extends CI_Model {
	private $tbl = 'sec_groups';
	
	function __construct() {
		parent::__construct();
	}
		
	function get_all()
	{	$sql = "select *
				from sec_groups
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

	function get_users_by_group($group_id, $in_group=false) {	
		//arig 2018-07-13 pindah dari users model
        $sql = "select * from (
		select 1 in_group, u.id, u.userid, u.nama, u.upt_id, u.disabled, u.level, u.created,
		(case when u.level=1 then 'WP' when u.level=2 then 'UPT' else 'PAD' end) as level_nm, ".$group_id." group_id
		from sec_users u
		inner join sec_user_groups ug on ug.user_id=u.id
		where group_id=".$group_id."
		union
		select 0 as in_group, u.id, u.userid, u.nama, u.upt_id, u.disabled, u.level, u.created,
		(case when u.level=1 then 'WP' when u.level=2 then 'UPT' else 'PAD' end) as level_nm, ".$group_id." group_id
		from sec_users u where u.id not in (select user_id from sec_user_groups where group_id=".$group_id.")
		) as gu ".($in_group? " where in_group=1 ": "")." order by in_group desc, disabled desc, nama ";
		//
        $this->db->trans_start();
		$query = $this->db->query($sql);
        $this->db->trans_complete();
        
        if($this->db->trans_status() && $query->num_rows()>0)
            return $query->result();
        else
            return false;
	}
		
	function is_locked($id)
	{
		$this->db->where('id',$id);
		$this->db->where('locked',1);
		$query = $this->db->get($this->tbl);
		if($query->num_rows()!==0)
		{
			return TRUE;
		}
		else
			return FALSE;
	}
	
	function leave_one_super_admin() {
		$this->db->where('group_id ',1);  //--> id super admin
		$query = $this->db->get('sec_user_groups');
		if($query->num_rows==1)
		{
			return TRUE;
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
	
	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete($this->tbl);
	}
}

/* End of file _model.php */