<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class apps_model extends CI_Model {
	private $tbl = 'sec_apps';
	
	function __construct() {
		parent::__construct();
	}
		
	function get_all()
	{	$sql = "select *
				from sec_apps
				order by id";
				
		$query = $this->db->query($sql);
		if($query->num_rows()!==0)
		{
			return $query->result();
		}
		else
			return FALSE;
	}
	
	function get_active_only() {
        $user_id = sipkd_user_id();
        $sql = "select distinct a.*
                from sec_user_groups ug 
                    inner join sec_groups g on g.id=ug.group_id 
                    inner join sec_group_modules gm on g.id=gm.group_id
                    inner join sec_modules m on gm.module_id=m.id
                    inner join sec_apps a on m.app_id=a.id
                where ug.user_id={$user_id} and (gm.reads=1 or gm.writes=1 or gm.deletes=1 or gm.inserts=1)
                    order by a.id";
                    
        if(is_super_admin())
            $sql = "select * from sec_apps where disabled <> 1 order by id";
				
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
	
	function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete($this->tbl);
	}
}

/* End of file _model.php */