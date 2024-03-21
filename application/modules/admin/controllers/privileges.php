<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class privileges extends CI_Controller {

	function __construct() {
		parent::__construct();
		if(!is_login() || !is_super_admin()) {
			$this->session->set_flashdata('msg_warning', 'Session telah kadaluarsa, silahkan login ulang.');
			redirect('login');
			exit;
		}
		
		$this->load->model(array('apps_model'));
		$this->load->model(array('privileges_model', 'modules_model', 'app_modules_model'));
	}

	public function index() {
		$data['current'] = 'pengaturan';
		$data['apps']    = $this->apps_model->get_active_only();

		$select = '';
		$rows   = $this->apps_model->get_active_only();
		if($rows) {
			foreach($rows as $row) {
				if ($this->session->userdata('selected_app') == $row->id)
					$select .= '<option value="'.$row->id.'" selected="selected">'.$row->nama.'</option>';
				else
					$select .= '<option value="'.$row->id.'">'.$row->nama.'</option>';
			}
		} else 
			$select = '<option value="">Tidak ada data!</option>';
		
		$data['app_data'] = $select;
		$this->load->view('vprivileges', $data);
	}
	
	function grid() {
		$app_id = $this->uri->segment(4);
		$grp_id = $this->uri->segment(5);
		
        $this->session->set_userdata('selected_app', $app_id);
		
		$i=0;
        $responce = new stdClass();
		if($app_id && $grp_id && $query = $this->privileges_model->get_by_app($app_id, $grp_id)) {
			foreach($query as $row) {
				$responce->aaData[$i][]=$row->module_id;
				$responce->aaData[$i][]=$row->kode;
				$responce->aaData[$i][]=$row->module_nm;
				$responce->aaData[$i][]='<input type="checkbox" onchange="update_stat('.$row->group_id.','.$row->module_id.',\'reads\', this.checked);"   '.($row->reads?'checked':'').' name="a">';
				$responce->aaData[$i][]='<input type="checkbox" onchange="update_stat('.$row->group_id.','.$row->module_id.',\'inserts\', this.checked);" '.($row->inserts?'checked':'').' name="b">';
				$responce->aaData[$i][]='<input type="checkbox" onchange="update_stat('.$row->group_id.','.$row->module_id.',\'writes\', this.checked);"  '.($row->writes?'checked':'').' name="c">';
				$responce->aaData[$i][]='<input type="checkbox" onchange="update_stat('.$row->group_id.','.$row->module_id.',\'deletes\', this.checked);" '.($row->deletes?'checked':'').' name="d">';
				$i++;
			}
		} else {
			$responce->sEcho=1;
			$responce->iTotalRecords="0";
			$responce->iTotalDisplayRecords="0";
			$responce->aaData=array();
		}
		echo json_encode($responce);
	}

	function grid_btn() {
		$app_id = $this->uri->segment(4);
		$grp_id = $this->uri->segment(5);
		$modul_id = $this->uri->segment(6);
		
        $this->session->set_userdata('selected_app', $app_id);

		$i=0;
        $responce = new stdClass();
		if($app_id && $grp_id && $query = $this->privileges_model->get_by_app_btn($app_id, $grp_id, $modul_id)) {
			foreach($query as $row) {
				$responce->aaData[$i][]=$row->modul_btn_id;
				$responce->aaData[$i][]=$row->btn_no;
				$responce->aaData[$i][]=$row->kode_btn;
				$responce->aaData[$i][]=$row->nama_btn;
				$responce->aaData[$i][]='<input type="checkbox" onchange="update_stat_role_btn('.$row->group_id.','.$row->module_id.','.$row->modul_btn_id.', this.checked);" '.($row->buttons?'checked':'').' name="e">';
				$i++;
			}
		} else {
			$responce->sEcho=1;
			$responce->iTotalRecords="0";
			$responce->iTotalDisplayRecords="0";
			$responce->aaData=array();
		}
		echo json_encode($responce);
	}

	function update_stat() {
		$gid = $this->uri->segment(4);
		$mid = $this->uri->segment(5);
		$fld = $this->uri->segment(6);
		$val = $this->uri->segment(7);

		if($gid && $mid && $fld) {
			$this->privileges_model->upd_auth($gid, $mid, $fld, $val);
		} 
	}

	function upd_stat_role_btn() {

		$group_id       = $this->uri->segment(4);
		$modules_id     = $this->uri->segment(5);
		$modules_btn_id = $this->uri->segment(6);
		$flg            = $this->uri->segment(7);

		if($group_id && $modules_id && $modules_btn_id) {
			$this->privileges_model->upd_auth_role_btn($group_id,$modules_id,$modules_btn_id, $flg);
		} 
	}

	//admin - modules
	private function fvalidation($model) {
		$this->form_validation->set_error_delimiters('<span>', '</span>');
		$this->form_validation->set_rules('nama', 'Nama Module', 'required');
		$this->form_validation->set_rules('kode', 'Kode', 'required');
		$this->form_validation->set_rules('cek_doubles', 'Data Double', 'callback_cek_double['."x".']');

		//$tmp_app_id = $tmp_app_id;
	//	$this->form_validation->set_rules('cek_test', 'Data tes', 'callback_cek_tes['.$tmp_app_id.']');

	}

    function cek_double($ver_old, $id)
    {
        $id     = $this->input->post('id');
        $nama   = $this->input->post('nama');
        $kode   = $this->input->post('kode');
        $app_id = $this->input->post('app_id');

        if (empty($nama)) {$nama = '';}
        if (empty($kode)) {$kode = '';}
        if (empty($app_id)) {$app_id = '0';}
        if (empty($id))     {$id = '0';}

        $filter = "app_id =".$app_id." and (nama ='".$nama."' or kode ='".$kode."') and id<>".$id;
        //
        $result = $this->db->select('app_id')->from('app.modules')->where($filter, NULL, false)->limit(1)->get()->row();
        //$result = empty ($result->app_id) ? 0 : $result->app_id;
        if (!empty($result)) {
            $this->form_validation->set_message('cek_double', 'Data tersebut sudah ada..!');
            return false;
        }
        else  {  return true;  }
    }

    function cek_tes($ver_old, $id)
    {
       $app_id = $this->input->post('app_id');
       $this->form_validation->set_message('cek_tes', ' xxxxxxx app_id : '.$app_id); return false;
    }

	private function fpost() {

		$data['id']     = $this->input->post('id');
		$data['kode']   = $this->input->post('kode');
		$data['nama']   = $this->input->post('nama');
		$data['app_id'] = $this->input->post('app_id');
		
		return $data;
	}
	
	public function add() {

	 	$tmp_app_id = $this->uri->segment(4);
	 	$modul_nm   = $this->uri->segment(5); //$this->input->get('modul_nm');
 		$post_data  = $this->fpost();
 		//
		$data['current']      = 'pengaturan';
		$data['apps']         = $this->apps_model->get_active_only();
		$data['modul_nm']     = $modul_nm;
		$data['faction']      = active_module_url("privileges/add/{$tmp_app_id}");
		$data['model_form']   = '1'; // add record
	 	$data['dt']           = $post_data;
 
		$this->fvalidation('add');
		if ($this->form_validation->run() == TRUE) {
			$data = array(
				'kode' => $this->input->post('kode'),
				'nama' => $this->input->post('nama'),
				'app_id' => $this->input->post('app_id'),
			);
			$this->modules_model->save($data);
			
			$this->session->set_flashdata('msg_success', 'Data telah disimpan');		
			redirect(active_module_url('privileges'));
		}
        //
        $data['dt'] = $post_data;
        $get        = (object) $post_data;
        $data['dt']['app_id'] = $tmp_app_id; 
     //   $data['dt']['nama']   = $get->nama;

        $select_data  = $this->app_modules_model->get_module($tmp_app_id);
        $options      = array();
        $kode = $post_data['kode'];

        if(!$select_data) $options[999] = '# REF. NOT FOUND #'; else
            foreach ($select_data as $row) {

                if($kode == '') { $kode = $row->kode; }
                $options[$row->kode] = $row->nama;
                //$options[$row->kode] = $row->kode." | ".$row->nama;
            }

        $js   = 'id="kode" class="input" onChange="ambil_nama(this.value);"  style="width:300px;" required';
        $data['select_module'] = form_dropdown('kode', $options, $post_data['kode'], $js);
        //
		$this->load->view('vmodules_form',$data);
	}

	public function edit() {

		$id = $this->uri->segment(4);
		$modul_nm = $this->uri->segment(5); //$this->input->get('modul_nm');

		$data['current']    = 'pengaturan';
		$data['apps']       = $this->apps_model->get_active_only();
		$data['modul_nm']   = $modul_nm;
		$data['faction']    = active_module_url('privileges/update');
		$data['model_form'] = '2'; // edit record
			
		if($id && $get = $this->modules_model->get($id)) {
			$data['dt']['id'] = $get->id;
			$data['dt']['kode'] = $get->kode;
			$data['dt']['nama'] = $get->nama;
			$data['dt']['app_id'] = $get->app_id;
			
			$this->load->view('vmodules_form',$data);
		} else {
			show_404();
		}
	}
	
	public function update() {

		$id = $this->uri->segment(4);
		$modul_nm = $this->uri->segment(5); //$this->input->get('modul_nm');

		$data['current']    = 'pengaturan';
		$data['apps']       = $this->apps_model->get_active_only();
		$modul_nm           = $this->uri->segment(5); //$this->input->get('modul_nm');
		$data['faction']    = active_module_url('privileges/update');
		$data['model_form'] = '2'; // edit record
		$data['dt'] = $this->fpost();
				
		$this->fvalidation('edit');
		if ($this->form_validation->run() == TRUE) {	
			$data = array(
				//'kode' => $this->input->post('kode'),
				'nama' => $this->input->post('nama'),
			//	'app_id' => $this->input->post('app_id'),
			);
			$this->modules_model->update($this->input->post('id'), $data);
			
			$this->session->set_flashdata('msg_success', 'Data telah disimpan');
			redirect(active_module_url('privileges'));
		}
		$this->load->view('vmodules_form',$data);
	}

	public function delete() {		
		$id = $this->uri->segment(4);
		if($id && $this->modules_model->get($id)) {
			$this->modules_model->delete($id);
			$this->session->set_flashdata('msg_success', 'Data telah dihapus');
			redirect(active_module_url('privileges'));
		} else {
			show_404();
		}
	} 

    public function get_nama_module($kode){
        // arig
        $nama_module = $this->app_modules_model->get_nama($kode);

        $kode = '';
        $nama = '';

        if($nama_module) {
            foreach ($nama_module as $row) {
                $kode= $row->kode;
                $nama= $row->nama;
                break;
            }}

        $data['kode'] = $kode;
        $data['nama'] = $nama;

        echo json_encode($data);
    }

    function tambah_btn_detil() {

		$nama      = $this->input->get('nama');
		$module_id = $this->input->get('module_id');
		$kode      = $this->input->get('kode');
		$btn_no      = $this->input->get('btn_no');

        $model    = $this->load->model('privileges_model');
		$usaha_id = $model->tambah_btn_detil($nama,$kode,$module_id,$btn_no);
 		echo "sukses";
    }

    function hapus_btn_detil() {

		$modules_btn_id = $this->uri->segment(4);
        $model    = $this->load->model('privileges_model');
		$usaha_id = $model->delete_btn_detil($modules_btn_id);
 		//echo "sukses";
    }

}