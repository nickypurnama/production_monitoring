<?php
 
/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/
 
/**
* Description of General
*
* @author fito@vivere
*/
class My_Auth {
 
//put your code here

var $ci;
 
	public function __construct() {
		//utk constructor ci library
		$this->ci = &get_instance();
	}
	
	
	
	function check_ip(){
		$this->ci->load->model('err_model');
		$list = $this->ci->err_model->getIp();
		foreach($list as $a){
			if(preg_match("/^".$a['IP_ADDR']."+/",$_SERVER['REMOTE_ADDR'])) {
				return "benar";
				break; //exit();
			}else{
				return "salah";
				break;
			}
		}
	}
	
	public function check_login() {
		//echo $this->stat_logged();
		if ($this->stat_logged() != TRUE) {
			redirect('manager/login');
		}
	}

	public function PassCrypt($var = false){	
		$encrypt= hash('sha1','App5V1V3R3Gr0uPst34mMobs6'.$var);
		return $encrypt;
	}
 
	public function hak_akses($var){
		
		if($var == "REQUESTER"){
			if((int)strpos($this->ci->session->userdata('role'),'ADMINISTRATOR') === false or (int)strpos($this->ci->session->userdata('role'),'REQUESTER') === false ){
				redirect('home/index');
			}
		}
		if($var == "APPROVAL"){
			if((int)strpos($this->ci->session->userdata('role'),'ADMINISTRATOR') === false or (int)strpos($this->ci->session->userdata('role'),'APPROVAL') === false ){
				redirect('home/index');
			}
		}
		if($var == "MASTERDATA"){
			if((int)strpos($this->ci->session->userdata('role'),'ADMINISTRATOR') === false or (int)strpos($this->ci->session->userdata('role'),'MASTERDATA') === false ){
				redirect('home/index');
			}
		}
		if($var == "ADMINISTRATOR"){
			if((int)strpos($this->ci->session->userdata('role'),'ADMINISTRATOR') === false){
				redirect('home/index');
			}
		}
		
	}
}
 
?>