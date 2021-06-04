<?php

class sso_model extends CI_Model {
	private $dbsso;
	public function __construct()
	{
	     // Call the Model constructor
        parent::__construct();
		// $this->dbsso = $this->load->database('sso',TRUE);
		$this->load->library('My_AdminLibrary');
	}
	
	function getURL($url) { 
		$curlHandle = curl_init(); // init curl 
		curl_setopt($curlHandle, CURLOPT_URL, $url); // setthe url to fetch 
		curl_setopt($curlHandle, CURLOPT_HEADER, 0); 
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($curlHandle, CURLOPT_TIMEOUT,30); 
		curl_setopt($curlHandle, CURLOPT_POST, 0); 
		$content = curl_exec($curlHandle); 
		if(!$content){ 
		return 'Curl error: ' . curl_error($curlHandle); 
		} 
		Else { 
		return $content; 
		} 
		curl_close($curlHandle); 
	}
	
	
	public function valid_login($nik=FALSE, $pas=FALSE){
		date_default_timezone_set('Asia/Jakarta');
                $tgl=date("Y-m-d H:i:s");
                
		$nik = $this->my_adminlibrary->CleanLogin($nik);
		$pas = $this->my_adminlibrary->CleanLogin($pas);
                    
		// $auth = "fd38931ee314416e27ec7fb33bad513e";
		// $url = "http://pitstop.vivere.co.id/vservice/action/login/".$auth."/".$nik."/".$pas;
		// $grab = $this->getURL($url); 
		
		$auth = "d6d344759f92627372aa381f3ed0e2d7";
		$url = "https://apps.vivere.co.id/vservice/action/login/".$auth."/".$nik."/".$pas;
		$grab = $this->getURL($url); 
				
		$hasil = json_decode($grab, true);
		
		if($hasil['result'] == "true"){
			
			$role = 0;

			//Get Role User Login
			$this->dbprodmon = $this->load->database('prodmon',TRUE);

		 	$this->dbprodmon->select('user_role.*');
			$this->dbprodmon->select('role.role_name');
			$this->dbprodmon->select('production_process.process as process_name');
			$this->dbprodmon->from('user_role');
			$this->dbprodmon->join('role', 'user_role.role_id = role.role_id');
			$this->dbprodmon->join('production_process', 'user_role.process = production_process.production_process_id', 'left');
			$this->dbprodmon->where('user_role.nik', $nik);
			$data_user =  $this->dbprodmon->get()->row_array();
			
		 	if(!empty($data_user)){
		 		$role = $data_user['role_id'];
		 		$plant = $data_user['plant'];

		 		$this->dbprodmon->select('user_role.process');
				$this->dbprodmon->from('user_role');
				$this->dbprodmon->where('user_role.nik', $nik);
				$data_user_process =  $this->dbprodmon->get()->result_array();

				$process = array();
				foreach($data_user_process as $row){
					array_push($process, $row['process']);
				}
		 	}
		 	
			$session_login = array(
				'nik_prodmon' => trim($nik), 
				'usr_name_prodmon' => trim($hasil['name']),
				'seskode' => $nik.strtotime($tgl),
				'role_prodmon' => $role,
				'role_process_prodmon' => $process,
				'plant_prodmon' => $plant,
				'logged_state_prodmon' => TRUE );

			$this->session->set_userdata($session_login);

			redirect('site/index');
		}else{
			$this->session->set_flashdata('msg','<div class="alert alert-error alert-block"> <a class="close" data-dismiss="alert" href="#">x</a>
                            <h4 class="alert-heading">Error!</h4>
                             Username/Password Salah!</div>');		
			redirect(site_url('site/login')); 
		}
		// $this->dbsso->close();
	}
	
	public function Exec($field=false, $val=false, $tbl=false, $kondisi=false, $state=false){
		$kondisi=($kondisi)?'where '.$kondisi:"";
		if($state=='insert'){
			 // $result=$this->dbintra->insert($tbl, $val); 				
			$result=$this->dbsso->query("insert into $tbl ($field) VALUES ($val)");
		}elseif($state=='update'){ 
			//echo $result="update $tbl set $field  where $kondisi";
			$result=$this->dbsso->query("update $tbl set $field  $kondisi");
			//exit;
		}elseif($state=='delete'){ 
			//echo $result="update $tbl set $field  where $kondisi";
			$result=$this->dbsso->query("delete from $tbl $kondisi");
		}
		$this->dbsso->close();
		return $result;
		
	}
	public function select($field=FALSE, $table=FALSE,$kondisi=FALSE){
		$field=($field)?$field:'*';
		$table = ($table)?" from $table":"";
		$kondisi=($kondisi)?'where '.$kondisi:'';
		//echo "select $field from $table $kondisi";
		$result=$this->dbsso->query("select $field $table $kondisi")->result_array();
		$this->dbsso->close();
		return $result;
	}

	public function valid_login_mobile($nik=FALSE, $pas=FALSE){
		date_default_timezone_set('Asia/Jakarta');
                $tgl=date("Y-m-d H:i:s");
          
		$nik = $this->my_adminlibrary->CleanLogin($nik);
		$pas = $this->my_adminlibrary->CleanLogin($pas);
                    
		$auth = "fd38931ee314416e27ec7fb33bad513e";
		$url = "https://apps.vivere.co.id/vservice/action/login/".$auth."/".$nik."/".$pas;
		$grab = $this->getURL($url); 
		$hasil = json_decode($grab, true);
			
		if($hasil['result'] == "true"){
			return true;
		}else{
			return false;
		}
		$this->dbsso->close();
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */