<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class users_chgpwd extends CI_Controller {

	function __construct() {
		parent::__construct();
		if(!is_login()) {
			$this->session->set_flashdata('msg_warning', 'Session telah kadaluarsa, silahkan login ulang.');
			redirect('login');
			exit;
		}
		
		$this->load->model(array('apps_model'));
		$this->load->model(array('users_model', 'groups_model'));
	}

	public function index() {
		show_404();
	}
	
	private function fvalidation($model) {
		$this->form_validation->set_error_delimiters('<span>', '</span>');
		$this->form_validation->set_rules('userid', 'userid', 'required|min_length[1]');
		$this->form_validation->set_rules('nama', 'Uraian', 'required');
 
		if ($model == 'edit'){

			$this->form_validation->set_rules('passwd_old', 'Password (old)', 'required|min_length[2]|max_length[20]');

			$this->form_validation->set_rules('passwd', 'Password', 'required|min_length[3]|max_length[20]');
 			$this->form_validation->set_rules('passconf', 'Password (Confirm)', 'required|min_length[3]|max_length[20]|matches[passwd]');

			$this->form_validation->set_rules('password', 'Out off Data', 'callback_cek_versi['.$this->input->post('passwd_old').']');
		}	
	}
	
    function cek_versi($ver_old, $pwd_old)
    {
    	$pwd_lama = $this->input->post('passwd_old');
    	$pwd_long = strlen($pwd_lama);
    	if ($pwd_long < 21) {
			$id       = $this->input->post('id');
			$get_pwd  = $this->users_model->encript_value($pwd_lama);
			$pwd_old  = $get_pwd->fn_keylock;
			$filter   = "id=".$id." and passwd='".$pwd_old."'";
			//
	        $result = $this->db->select("passwd")->from('users')->where($filter,NULL,false)->limit(1)->get()->row();
	        if (empty($result))
	           { $this->form_validation->set_message('cek_versi', 'Password (old) salah........!'); return false; }
	        else  {  return true;  }
    	} 
    	else { $this->form_validation->set_message('cek_versi', 'Password (old) salah...!'); return false; }
    }

	private function fpost() {
		$data['id'] = $this->input->post('id');
		$data['userid'] = $this->input->post('userid');
		$data['nama'] = $this->input->post('nama');
		$data['passwd_old'] = $this->input->post('passwd_old');
		$data['passwd'] = $this->input->post('passwd');
		$data['passconf'] = $this->input->post('passconf');
		$data['nip'] = $this->input->post('nip');		
		return $data;
	}
	
	//admin	
	public function changepwd() {
		if ($this->uri->segment(4) == sipkd_user_id())	{
			$data['current'] = 'beranda';
			$data['apps']    = $this->apps_model->get_active_only();
			$data['faction'] = base_url('admin/users_chgpwd/update');
				
			$id = $this->uri->segment(4);  
			if($id && $get = $this->users_model->get($id)) {
				$data['dt']['id']     = $get->id;
				$data['dt']['userid'] = $get->userid; 
				$data['dt']['nama']   = $get->nama;
				$data['dt']['passwd_old'] = ''; 
				$data['dt']['passwd']     = ''; 
				$data['dt']['passconf']   = '';		
				$this->load->view('vusers_form_chgpwd',$data);
			} else { show_404(); }
		}
		else { show_404(); }
	}
	
	public function update() {
		$data['current'] = 'beranda';
		$data['apps']    = $this->apps_model->get_active_only();
		$data['faction'] = base_url('admin/users_chgpwd/update');
		$data['dt'] = $this->fpost();

		$pwd  = $this->users_model->encript_value($this->input->post('passwd'));
					
		$this->fvalidation('edit');
		if ($this->form_validation->run() == TRUE) {	
			$data = array(
				'nama'   => $this->input->post('nama'),
		 		'passwd' => $pwd->fn_keylock,
			);
			$this->users_model->update($this->input->post('id'), $data);
			
			$this->session->sess_destroy();
			$this->session->set_flashdata('msg_success', 'Data user telah berhasil di ubah, silahkan login kembali.');
			redirect(base_url('login'));
		}
		$this->load->view('vusers_form_chgpwd',$data);
	}
	
}
