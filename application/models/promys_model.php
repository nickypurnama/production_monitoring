<?php
class Promys_model extends CI_Model{
    
    function __construct(){
	parent::__construct();
    }
    
    function getUserIDbyNIK($nik){
	$this->db = $this->load->database('promys_eb', TRUE);
	$query="select mt_users_id from mt_users where nik='$nik'";
        $q=$this->db->query($query);
        if ($q->num_rows() > 0)
	{
	    $row = $q->row();
	    $s=$row->mt_users_id;
	}
	return $s;
    }
    
    function getDataProjectByNIK($userID){
	$this->db = $this->load->database('promys_eb', TRUE);
	$query="select a.tr_project_profile_id, a.project_code, a.mt_bussines_unit_id, a.description,
                a.lat, a.lang, a.radius, b.mt_jabatan_id
                from tr_project_profile a, tr_project_person b where 
                a.tr_project_profile_id = b.tr_project_profile_id
                and b.mt_users_id = '$userID' and b.status = '1' order by a.description";
        $q=$this->db->query($query);    
	if ($q->num_rows() > 0){
            foreach($q->result() as $row){
                $data[]=$row;
            }
            return $data;
        }
    }
    
    function getDataProjectAktif(){
	$this->db = $this->load->database('promys_eb', TRUE);
	$query="select tr_project_profile_id, project_code, mt_bussines_unit_id, description,
                lat, lang, radius 
                from tr_project_profile where aktif = '1'";
        $q=$this->db->query($query);    
	if ($q->num_rows() > 0){
            foreach($q->result() as $row){
                $data[]=$row;
            }
            return $data;
        }
    }
    
    function getNamaProject($kode){
	$this->db = $this->load->database('promys_eb', TRUE);
	$query="select description from tr_project_profile where project_code='$kode'";
        $q=$this->db->query($query);
        if ($q->num_rows() > 0)
	{
	    $row = $q->row();
	    $s=$row->description;
	}
	return $s;
    }
    
    function getDataProjectByCode($kode){
	$this->db = $this->load->database('promys_eb', TRUE);
	$query="select tr_project_profile_id, project_code, mt_bussines_unit_id, description,
                lat, lang, radius 
                from tr_project_profile where project_code = '$kode'";
        $q=$this->db->query($query);    
	if ($q->num_rows() > 0){
            foreach($q->result() as $row){
                $data[]=$row;
            }
            return $data;
        }
    }
    
    function getDataProjectByName($nama){
	$this->db = $this->load->database('promys_eb', TRUE);
	$query="select tr_project_profile_id, project_code, mt_bussines_unit_id, description,
                lat, lang, radius 
                from tr_project_profile where upper(description) like '%$nama%'";
        $q=$this->db->query($query);    
	if ($q->num_rows() > 0){
            foreach($q->result() as $row){
                $data[]=$row;
            }
            return $data;
        }
    }

    function get_data_user_bynik($nik){
        $this->db = $this->load->database('promys_eb', TRUE);
        $query = "SELECT * FROM mt_users WHERE nik = '$nik'"; 
        $result=$this->db->query($query)->row_array(); 
        return $result;
    }

    function get_project_person($nik, $jabatan){
        $this->db = $this->load->database('promys_eb', TRUE);
        $query = "SELECT p.project_code FROM tr_project_person pp, tr_project_profile p, mt_users m WHERE pp.tr_project_profile_id = p.tr_project_profile_id AND pp.mt_users_id = m.mt_users_id AND p.status = '1' AND p.aktif = '1' AND pp.mt_jabatan_id = '$jabatan' AND m.nik = '$nik'"; 
        $result=$this->db->query($query)->result_array(); 
        return $result;
    }

    function get_user_byprojectperson($project, $jabatan){
        $this->db = $this->load->database('promys_eb', TRUE);
        $query = "SELECT m.nik, m.nama, m.email FROM tr_project_person pp, tr_project_profile p, mt_users m WHERE pp.tr_project_profile_id = p.tr_project_profile_id AND pp.mt_users_id = m.mt_users_id AND p.status = '1' AND p.aktif = '1' AND pp.mt_jabatan_id = '$jabatan' AND p.project_code IN ($project)"; 
        $result=$this->db->query($query)->result_array(); 
        return $result;
    }
} 
?>