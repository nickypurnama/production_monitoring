<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class site extends CI_CONTROLLER {
    
    public function __construct(){
        parent::__construct();
        $this->load->library('My_Auth');
        $this->load->model('prodmon_model');
    }
    
    function index(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        if($role == "6")
	        $data['chart_data'] = $this->prodmon_model->get_chart_data_all();
    	else
    	    $data['chart_data'] = $this->prodmon_model->get_chart_data($user_plant);

        $data['main_content']='home';
        $this->load->view('template/main', $data);
    }
    
    function login(){
        if( $this->session->userdata('logged_state_prodmon') !== false){
            redirect('site/index');
        }else{
            $this->load->view('login');
        }
    }
    
    public function cek_login(){
		$this->load->model('sso_model');
		$user=stripslashes(strip_tags(htmlspecialchars ($this->input->post('username'),ENT_QUOTES)));
		$pass=stripslashes(strip_tags(htmlspecialchars ($this->input->post('password'),ENT_QUOTES)));
		
		$this->sso_model->valid_login($user, $pass);
    }
	
    function logout(){
            $this->session->unset_userdata('logged_state_prodmon');
            //$this->session->sess_destroy();
            redirect("site/login");
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
	else { 
	    return $content; 
	} 
	curl_close($curlHandle); 
    }
    
    function proses_reset(){
	$nik = $this->input->post('nik_reset');
	$reason = $this->input->post('reason');
	$auth = "f9fd29913056154e451def4f1f982927";
	
	$url = "http://pitstop.vivere.co.id/vservice/action/reset_password/".$auth."/".$nik."/".$reason;
	$grab = $this->getURL($url); 
	$hasil = json_decode($grab, true);
	
	if($hasil['result'] == "true"){
		redirect("site/req_reset_sukses");
	}else{
		redirect("site/req_reset_gagal");
	}
	    
    }
    
    function req_reset_sukses(){
	$this->load->view('req_reset_sukses');
    }
    
    function req_reset_gagal(){
	$this->load->view('req_reset_gagal');
    }
    
    
    function manage_account(){
	if( $this->session->userdata('logged_state') !== true){
		redirect('home/login');
	}
	$this->load->model('oci_model');
	$this->load->model('sso_model');
	
	$data['nik']=$this->session->userdata('NIK');
	$data['main_content']="manage_account";
	$this->load->view('template/main',$data);
    }
   
}
?>