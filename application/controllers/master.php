<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class master extends CI_CONTROLLER {
    
    public function __construct(){
        parent::__construct();
        $this->load->library('My_Auth');
        $this->load->model('prodmon_model');
        $this->load->model('sso_model');
        date_default_timezone_set('Asia/Bangkok');
    }

    function user_role(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user_plant = $this->session->userdata('plant_prodmon');
        $data['user_plant'] = $user_plant;

        if(!empty($user_plant))
            $data['role'] = $this->prodmon_model->get_list_role_plant();
        else
            $data['role'] = $this->prodmon_model->get_list_role();
        
        $data['plant'] = $this->prodmon_model->get_list_plant();

        if(!empty($user_plant))
            $data['list'] = $this->prodmon_model->get_list_user_role_by_plant($user_plant);
        else
            $data['list'] = $this->prodmon_model->get_list_user_role();

        $data['process'] = $this->prodmon_model->get_production_process();

        $fieldSSO="NIK, GET_NAME_BY_NIK(NIK) as NAMA";
        $kondisiSSO="APP_CODE= 'PRDM' ORDER BY NIK";
        $data['nik_sso'] = $this->sso_model->select($fieldSSO, 'M_USER_APP', $kondisiSSO);

        $data['main_content']='master/user_role';
        $this->load->view('template/main', $data);
    }

    function add_role(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user_plant = $this->session->userdata('plant_prodmon');

        $nik = $this->input->post('nik');
        $role = $this->input->post('role');
        $process = $this->input->post('process');
        $plant = $this->input->post('plant');

        // $jml = $this->prodmon_model->get_jml_role($nik);
        // if($jml > 0){
        //     $this->session->set_flashdata('msg','<div class="alert alert-error">
        //         <a class="close" data-dismiss="alert"></a>
        //         <strong>Failed!</strong> User can only have one role
        //     </div>'); 

        //     $url    = "master/user_role";
        //     redirect($url);
        // }
        
        if(!empty($user_plant)){
            if($user_plant <> $plant){
                $this->session->set_flashdata('msg','<div class="alert alert-error">
                    <a class="close" data-dismiss="alert"></a>
                    <strong>Failed!</strong> User can only add plant '.$user_plant.'
                </div>'); 

                $url    = "master/user_role";
                redirect($url);
            }
        }
        
        $field_sso = "*";
        $table_sso = "TM_KARYAWAN";
        $kondisi_sso = "NIK = '$nik'";
        $dataSSO = $this->sso_model->select($field_sso,$table_sso,$kondisi_sso);
        foreach($dataSSO as $row){
            $name = $row['NAMA'];
        }
        
        $insert_data = array(
            'role_id'   => $role,
            'nik'       => $nik,
            'name'      => $name,
            'process'   => ($process <> "" ? $process : null),
            'plant'     => ($plant <> "" ? $plant : null)
        );

        $insert = $this->prodmon_model->insert_user_role($insert_data);
        
        $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Role successfully added
            </div>'); 

        $url    = "master/user_role";
        redirect($url);
    }

    function user_role_delete(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $id = $this->input->post('id');
        $delete = $this->prodmon_model->delete_user_role($id);

        $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Successfully delete role
            </div>'); 

        $url    = "master/user_role";
        redirect($url);
    }
    
    function plant(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        if($this->session->userdata('role_prodmon') != '6'){
            redirect('site/index');
        }

        $data['list'] = $this->prodmon_model->get_list_plant();


        $data['main_content']='master/plant';
        $this->load->view('template/main', $data);
    }

    function add_plant(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $plant = $this->input->post('plant');

        $jml = $this->prodmon_model->get_jml_plant($plant);
        if($jml > 0){
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                <a class="close" data-dismiss="alert"></a>
                <strong>Failed!</strong> Plant already exist
            </div>'); 

            $url    = "master/plant";
            redirect($url);
        }

        $insert_data = array(
            'plant'   => $plant
        );

        $insert = $this->prodmon_model->insert_plant($insert_data);
        
        $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Plant successfully added
            </div>'); 

        $url    = "master/plant";
        redirect($url);
    }

    function plant_delete(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $plant = $this->input->post('plant');
        $delete = $this->prodmon_model->delete_plant($plant);

        $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Successfully delete plant
            </div>'); 

        $url    = "master/plant";
        redirect($url);
    }
}
?>