<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tes extends CI_Controller{

  function index()
  {
    // untuk menampilkan view dengan nama view_siswa
    $this->load->view('vtes');
  }

}