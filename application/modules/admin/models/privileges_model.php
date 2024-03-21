<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class privileges_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}
		
	function get_by_app($app_id, $grp_id)
	{	$sql = "select a.id app_id, a.nama app_nm, m.id as module_id, m.kode, m.nama as module_nm, 
				gm.group_id, gm.reads, gm.inserts, gm.writes, gm.deletes
				from modules m 
				inner join apps a on a.id=m.app_id
				inner join group_modules gm on gm.module_id=m.id 
				where a.id=".$app_id." and gm.group_id=".$grp_id."
				union
				select a.id appid, a.nama appnm, m.id as moduleid, m.kode, m.nama as modulenm, 
				".$grp_id.", null, null, null, null
				from modules m 
				inner join apps a on a.id=m.app_id
				where a.id=".$app_id." 
				and m.id not in (
					select module_id 
					from group_modules 
					where group_id=".$grp_id.")";

				//log_message('info', "SSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSSS : ".$sql);

		$query = $this->db->query($sql);
		if($query->num_rows()!==0)
		{
			return $query->result();
		}
		else
			return FALSE;
	}

	function get_by_app_btn($app_id, $grp_id,$modul_id) {

		if($app_id == ''){$app_id = '0';}
		if($grp_id == ''){$grp_id = '0';}
		if($modul_id == ''){$modul_id = '0';}
		// button yg sdh di alokasi
		$sql  = " select app_id, group_id, module_id, modul_btn_id, kode_btn, btn_no, buttons, nama_btn ";
		$sql .= " from ( ";
		$sql .= " select app.id as app_id,  b2.group_id, mdl.id as module_id,  ";
		$sql .= " btn.id as modul_btn_id, btn.kode_btn, btn.btn_no, btn.nama_btn, b2.flg_button as buttons ";
		$sql .= " from modules mdl ";
		$sql .= " join apps app on app.id=mdl.app_id "; 
		$sql .= " join modules_btn btn on btn.module_id=mdl.id ";
		$sql .= " join group_roles_btn b2 on b2.modules_btn_id=btn.id and b2.modules_id=mdl.id ";
		$sql .= " where app.id=".$app_id." and b2.group_id=".$grp_id." and b2.modules_id=".$modul_id;
		$sql .= " union ";
		// default button kosong
		$sql .= " select app.id as app_id, ".$grp_id." as group_id, mdl.id as module_id, ";
		$sql .= " btn.id as modul_btn_id, btn.kode_btn, btn.btn_no, btn.nama_btn, null as buttons ";
		$sql .= " from modules mdl "; 
		$sql .= " join modules_btn btn on btn.module_id=mdl.id ";
		$sql .= " join apps app on app.id=mdl.app_id ";
		$sql .= " where app.id=".$app_id." and mdl.id=".$modul_id;
		$sql .= " and btn.id not in (select d1.modules_btn_id ";
        $sql .= " from group_roles_btn d1 where d1.group_id=".$grp_id." and d1.modules_id=mdl.id and d1.modules_btn_id=btn.id)";
		$sql .= " ) z1 order by btn_no ";

		//log_message('info', "BBBBBBBBBBBBBBBBBBBBBBBBBBBBBB : ".$sql);

		$query = $this->db->query($sql);
 		return $query->result();
	}

	function upd_auth($a,$b,$c,$d){
		$group = $this->db->query("select count(group_id) as jml 
			from group_modules 
			where group_id=$a and module_id=$b")->row();
		if($group->jml > 0) {
			$this->db->where('group_id', $a);
			$this->db->where('module_id', $b);
			$this->db->update('group_modules',array($c=>$d));
		} else {
			$this->db->insert('group_modules',array('group_id'=>$a, 'module_id'=>$b, $c=>$d));
		}
	}

	function upd_auth_role_btn($group_id,$modules_id,$modules_btn_id,$flg) {

		if($group_id == ''){$group_id = '0';}
		if($modules_id == ''){$modules_id = '0';}
		if($modules_btn_id == ''){$modules_btn_id = '0';}

		if($group_id == '0' || $modules_id == '0' || $modules_btn_id == '0'){
			// nothing to do
		}
		else {
		$role_btn = $this->db->query("select count(modules_btn_id) as jml 
		from group_roles_btn where group_id=$group_id and modules_id=$modules_id and modules_btn_id=$modules_btn_id")->row();

			if($role_btn->jml > 0) {
				$this->db->where('group_id', $group_id);
				$this->db->where('modules_id', $modules_id);
				$this->db->where('modules_btn_id', $modules_btn_id);
				$this->db->update('group_roles_btn',array('flg_button'=>$flg));
			} else {
				$this->db->insert('group_roles_btn',array('group_id'=>$group_id, 'modules_id'=>$modules_id,
					'modules_btn_id'=>$modules_btn_id, 'flg_button'=>$flg));
			}
		}
	}

	function tambah_btn_detil($a,$b,$c,$d){	 
		$this->db->insert('modules_btn',array('nama_btn'=>$a, 'kode_btn'=>$b, 'module_id'=>$c, 'btn_no'=>$d));
	}

	function delete_btn_detil($modules_btn_id){	 

		$this->db->where('id', $modules_btn_id);
		$this->db->delete('modules_btn');
 
	}

}

/* End of file _model.php */