<?php
if (! defined('BASEPATH')) exit('No direct script access allowed');

class MY_appctrl 
{
    public $data = array();
    
    function __construct ()
    {
        parent::__construct();
     	   
        ENVIRONMENT != 'production' || $this->output->enable_profiler(TRUE);
        
       // $this->load->database();
                
       // $this->data['user'] = 'Joost';
    }
    
    
}