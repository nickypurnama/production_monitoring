<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ajax extends CI_CONTROLLER {
    
    public function __construct(){
        parent::__construct();
        $this->load->library('My_Auth');
        $this->load->model('prodmon_model');
    }
    
    function get_production_order_by_batch(){
        $batch = $this->input->post('batch');
        
        $production_order = $this->prodmon_model->get_production_order_by_batch($batch);
       
        echo "<option value=''></option>";
        foreach($production_order as $row){
            echo '<option value="'.$row['production_order'].'">'.$row['production_order'].'</option>';
        }   
    }
    
   
}
?>