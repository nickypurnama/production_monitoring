<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class MyController extends CI_Controller
{
    public $data = array();
    
    function __construct ()
    {
        parent::__construct();
     	   
        ENVIRONMENT != 'development' || $this->output->enable_profiler(FALSE);
        
       // $this->load->database();
                
      // $this->data['user'] = 'Joost';
    }
    
    
}