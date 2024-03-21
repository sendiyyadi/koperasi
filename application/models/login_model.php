<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class login_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function check_user($uid)
    {
        $fields = explode(',', POS_FIELD);
        $field  = "";
        $join   = "";
        foreach ($fields as $f) {
            $f = trim($f);
            $join .= " AND u.$f=tp.$f ";
            $field .= "u.$f ,";
        };
        $qry  = "select u.id userid, u.nama username, u.nip, u.passwd
				from sec_users u
				where u.userid='$uid' and disabled<>1
				limit 1";
        $rows = $this->db->query($qry);
        if ($rows->num_rows() > 0) {
            return $rows->row();
        } else {
            return FALSE;
        }
    }

    function check_user_injek($usr, $pwd)
    {
        //
        $string = $usr;
        $usr = str_replace("'", "''", $string);
        //
        $string = $pwd;
        $pwd = str_replace("'", "''", $string);
        $ctr = 0;
        //
        $qry  = "select userid, passwd from sec_users us where us.userid='$usr' and 1=1 ";
        $rows = $this->db->query($qry);
        $ctr  = $rows->num_rows();
        if ($rows->num_rows() > 0) {
            $row = $rows->row();
            $userid = $row->userid;
            $passwd = $row->passwd;

            $pwd_row  = $this->encript_isi($usr, $pwd);
            $pwd_enc  = $pwd_row->fn_keylock;
            if (($userid == $usr) && ($passwd == $pwd_enc)) {
                $ctr = 1;
            }
        }
        return $ctr;
    }

    function encript_isi($id, $value)
    {

        $qry = "select fn_keylock('{$id}','{$value}') as FN_KEYLOCK ";
        $query = $this->db->query($qry);
        return $query->row();
    }

    function check_group($uid)
    {
        $qry = "select g.*
            from sec_groups g
            inner join sec_user_groups ug on g.id=ug.group_id
            where ug.user_id='$uid'
            order by g.id limit 1 ";

        $rows = $this->db->query($qry);
        if ($rows->num_rows() > 0) {
            return $rows->row();
        } else {
            return FALSE;
        }
    }

    function check_user_app()
    {
        $uid  = $this->session->userdata('userid');
        $mid  = $this->session->userdata('app_id');
        if ($mid <> '')
            $mid = ' and m.app_id=' . $mid;

        $qry  = "select distinct a.id app_id, a.app_path, g.id as group_id, g.kode as group_kode, g.nama as group_nama
            from sec_user_groups ug
                inner join sec_groups g on g.id=ug.group_id
                inner join sec_group_modules gm on g.id=gm.group_id
                inner join sec_modules m on gm.module_id=m.id
                inner join sec_apps a on m.app_id=a.id
            where ug.user_id={$uid} {$mid} and (gm.reads=1 or gm.writes=1 or gm.deletes=1 or gm.inserts=1)
                order by a.id";

        $rows = $this->db->query($qry);
        if ($rows->num_rows() > 0) {
            //20140120 -- biar nanti bisa pilih module kalo usernya ada di lebih dari 1 module
            $ret = new stdClass();
            $ret = $rows->row();
            $ret->modcnt = $rows->num_rows();
            return $ret;
        } else {
            return FALSE;
        }
    }

    function get_module($app_id)
    {
        $qry = "select *
				from sec_apps a
				where a.id=$app_id
				limit 1";

        $rows = $this->db->query($qry);
        if ($rows->num_rows() > 0) {
            return $rows->row();
        } else {
            return FALSE;
        }
    }

    function aktif_tahun($app_id)
    {
        $qry = "select *
				from apps a
				inner join app_status s on a.id=s.app_id
				where s.step<>'closing' and a.id=$app_id
				order by a.id, s.tahun
				limit 1";

        $rows = $this->db->query($qry);
        if ($rows->num_rows() > 0) {
            return $rows->row();
        } else {
            return FALSE;
        }
    }

    function inaktif_tahun($app_id)
    {
        $qry = "select max(tahun) tahun, step
				from apps a
				inner join app_status s on a.id=s.app_id
				where s.step='closing' and a.id=$app_id
				group by 2
				limit 1";

        $rows = $this->db->query($qry);
        if ($rows->num_rows() > 0) {
            return $rows->row();
        } else {
            return FALSE;
        }
    }

    function get_appid($m)
    {
        $qry  = "select id
				from sec_apps a
				where a.app_path='$m'
				limit 1";
        $rows = $this->db->query($qry);
        if ($rows->num_rows() > 0) {
            return $rows->row();
        } else {
            return FALSE;
        }
    }

    
}
