<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class app_modules_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_module($app_id)
    {
        if (empty($app_id)){$app_id = '0'; }

        $sql  = " select kode, nama, app_id from ";
        $sql .= "(select '...' as kode, 'Silahkan Pilih' as nama, ".$app_id." as app_id union all ";
        $sql .= " select am.kode, am.nama, am.app_id from app.app_modules am ) z1 ";
        $sql .= " where z1.app_id = ".$app_id ;

        $query = $this->db->query($sql);
        if($query->num_rows()!==0)
        {
            return $query->result();
        }
        else
            return FALSE;
    }

    function get_nama($kode) {

        // $sql = "select tarif from restribusi.tbl_jns_tarif_restrib where tjtr_id=".$tarif_id;
        $sql = "select am.kode, am.nama  from app.app_modules am where am.kode='".$kode."' limit 1";
        $query = $this->db->query($sql, array());
        if($query->num_rows()!==0)
        {
            //$row = $query->result();
            return $query->result();
            //return $row['ininodok'];
            //return 'berhasil';
        }
        else
            return FALSE;
    }

}

/* End of file _model.php */