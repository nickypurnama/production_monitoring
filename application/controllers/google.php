<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class google extends CI_CONTROLLER 
{
    function callback(){
        $this->load->library('/gdrive/GDriveUpload');
        if (isset($_GET['code'])) {
            $this->gdriveupload->setToken($_GET['code']);
        }
    }
}