<?php

class err_model extends CI_Model {
	private $dberr;
	public function __construct()
	{
	     // Call the Model constructor
        parent::__construct();
		$this->dberr = $this->load->database('err',TRUE);
		$this->load->library('My_AdminLibrary');
	}
	
	function getPesan($lang, $kode){
		$result=$this->dberr->query("select DESCR from M_MESSAGE WHERE VCODE ='$kode' AND LANGU='$lang'")->result_array();
		foreach($result as $val){
			$val = $val['DESCR'];
		}
		$this->dberr->close();
		
		return $val;
	}
	public function Exec($field=false, $val=false, $tbl=false, $kondisi=false, $state=false){
            $kondisi=($kondisi)?'where '.$kondisi:"";
            if($state=='insert'){
                $result=$this->dberr->query("insert into $tbl ($field) VALUES ($val)");
            }elseif($state=='update'){ 
                $result=$this->dberr->query("update $tbl set $field  $kondisi");
            }elseif($state=='delete'){ 
                $result=$this->dberr->query("delete from $tbl $kondisi");
            }
            $this->dberr->close();
            return $result;
	}
	public function select($field=FALSE, $table=FALSE,$kondisi=FALSE){
		$field=($field)?$field:'*';
		$table = ($table)?" from $table":"";
		$kondisi=($kondisi)?'where '.$kondisi:'';
		//echo "select $field from $table $kondisi";
		$result=$this->dberr->query("select $field $table $kondisi")->result_array();
		$this->dberr->close();
		return $result;
	}
	
	public function getIp(){
        $query = "Select ID_IP, IP_ADDR from M_IPACCESS";
		$result=$this->dberr->query($query)->result_array();
		$this->dberr->close();
		return $result;
	
    }
	
	function getCountry(){
		$query = "Select COUNTRY from M_COUNTRY";
		$result=$this->dberr->query($query)->result_array();
		$this->dberr->close();
		return $result;
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */