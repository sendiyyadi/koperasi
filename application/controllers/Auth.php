<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Register_model');
		$this->load->model('login_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if ($this->session->userdata('authenticated'))
			$this->load->view('Dashboard_controller');
		$this->load->view('admin/login');
	}

	public function add()
	{
		$register = $this->Register_model;
		$validation = $this->form_validation;
		$validation->set_rules($register->rules());
		$username = $this->input->post('username'); // Ambil isi dari inputan username pada form login
		$password = ($this->input->post('password'));
		$user = $this->User_model->get($username); // Panggil fungsi get yang ada di UserModel.php

		if ($validation->run()) {
			$register->save();
			$username = $this->input->post('username'); // Ambil isi dari inputan username pada form login
			$password = ($this->input->post('password')); // Ambil isi dari inputan password pada form login dan encrypt dengan md5
			$user = $this->User_model->get($username); // Panggil fungsi get yang ada di UserModel.php
			if(empty($user)){ // Jika hasilnya kosong / user tidak ditemukan
				$this->session->set_flashdata('message', 'Username tidak ditemukan'); // Buat session flashdata
				redirect('Auth'); // Redirect ke halaman login
			}else{
				if($password == $user->password){ // Jika password yang diinput sama dengan password yang didatabase
					$session = array(
						'authenticated'=>true, // Buat session authenticated dengan value true
						'username'=>$user->username,  // Buat session username
						'nama'=>$user->nama, // Buat session authenticated
						'id_user'=>$user->id_user
					);
					$this->session->set_userdata($session); // Buat session sesuai $session
					$this->session->set_flashdata('success', 'Nama Anda : '.$register->nama.' Berhahasil Di Registerasi Di Sistem Kami');
					redirect('Dashboard_controller', $user);

				}else{
					$this->session->set_flashdata('message', 'Password salah'); // Buat session flashdata
					redirect('Auth'); // Redirect ke halaman login
				}
			}
//			redirect('Auth');
		}
		$this->load->view("admin/register");
	}

	public function login(){
		$this->session->set_userdata('login', FALSE);
        $this->session->set_userdata('canchangemod', FALSE);
        $this->session->unset_userdata('groupname');

		$username = $this->input->post('username'); // Ambil isi dari inputan username pada form login
		$password = ($this->input->post('password')); // Ambil isi dari inputan password pada form login dan encrypt dengan md5
		$login = $this->User_model->get($username); // Panggil fungsi get yang ada di UserModel.php
		if(empty($login)){ // Jika hasilnya kosong / user tidak ditemukan
			$this->session->set_flashdata('message', 'Username tidak ditemukan'); // Buat session flashdata
			redirect('Auth'); // Redirect ke halaman login
		}else{
			if($password == $login->passwd){ // Jika password yang diinput sama dengan password yang didatabase
			// if(md5($password) == $user->password){ // Jika password yang diinput sama dengan password yang didatabase
				$this->session->set_userdata('uid', $login->userid);			// userid
				$this->session->set_userdata('userid', $login->id);		// id
				$this->session->set_userdata('username', htmlspecialchars($login->nama));
				$this->session->set_userdata('nip', $login->nip);
				$this->session->set_userdata('login', TRUE);
				$rs = $this->login_model->check_group($login->id);
				// var_dump($rs); die();
				if ($rs) {
					$this->session->set_userdata('groupid', $rs->id);
					$this->session->set_userdata('groupkd', $rs->kode);
					$this->session->set_userdata('groupname', $rs->nama);

					if (is_super_admin()) {
						$this->session->set_userdata('active_module', 'admin');
						$this->session->set_userdata('app_id', $this->login_model->get_appid('admin'));
						
						//arig 2018-06-08
						$tgl_jam = new DateTime();
						$this->session->set_userdata('arigsession', strtoupper($login->userid).$tgl_jam->format('YmdHis'));

					} else {
						if ($uapp = $this->login_model->check_user_app()) {
							$this->session->set_userdata('app_id', $uapp->app_id);
							$this->session->set_userdata('active_module', $uapp->app_path);

							$this->session->set_userdata('groupid', $uapp->group_id);
							$this->session->set_userdata('groupkd', $uapp->group_kode);
							$this->session->set_userdata('groupname', htmlspecialchars($uapp->group_nama));
							
														//arig 2018-06-08
							$tgl_jam = new DateTime();
							$this->session->set_userdata('arigsession', strtoupper($uid).$tgl_jam->format('YmdHis'));

							if($uapp->modcnt > 1)
								$this->session->set_userdata('canchangemod', true);

						} else {
							$this->session->set_userdata('login', FALSE);
						}
					}

					if ($this->session->userdata('login') == TRUE) {
						$this->session->set_flashdata('msg_success', 'Selamat datang, ' . htmlspecialchars($login->nama) . '.');
						redirect(active_module_url());
					}
				}


				// $session = array(
				// 	'authenticated'=>true, // Buat session authenticated dengan value true
				// 	'username'=>$user->username,  // Buat session username
				// 	'nama'=>$user->nama, // Buat session authenticated
				// 	'id_user'=>$user->id_user
				// );
				// $this->session->set_userdata($session); // Buat session sesuai $session
				// redirect('Dashboard_controller', $user);

			}else{
				$this->session->set_flashdata('message', 'Password salah'); // Buat session flashdata
				redirect('Auth'); // Redirect ke halaman login
			}
		}
	}

	public  function  logout(){
		$this->session->sess_destroy();
		redirect('Auth');
	}

	function change_module()
    {
        $m = $this->uri->segment(2);

        $this->session->set_userdata('active_module', 'admin');

        $id = $this->login_model->get_appid('admin');
        if ($id) $this->session->set_userdata('app_id', $id->id);

        if ($m) {
            $id = $this->login_model->get_appid($m);
            if ($id) {
                $this->session->set_userdata('active_module', $m);
                $this->session->set_userdata('app_id', $id->id);
            }
        }
        redirect(active_module_url());
    }

}

/* End of file Controllername.php */
