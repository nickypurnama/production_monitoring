<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class transaction extends CI_CONTROLLER {
    
    public function __construct(){
        parent::__construct();
        $this->load->library('My_Auth');
        $this->load->model('prodmon_model');
        $this->load->library('/gdrive/GDriveUpload');
        date_default_timezone_set('Asia/Bangkok');
    }
    
    function upload(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $data['main_content']='transaction/upload';
        $this->load->view('template/main', $data);
    }

    function upload_data(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $this->prodmon_model->delete_temp($user);
        
        $file = $_FILES['excel']['tmp_name'];
        //load the excel library
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        $rows = $objPHPExcel->getActiveSheet()->getHighestRow();

        $delete_temp = $this->prodmon_model->delete_temp($user);

        for ($i = 6; $i <= $rows; $i++) {
            $pic_p = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->getValue();
            $pic_w = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i)->getValue();
            $pic_install = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i)->getValue();
            $production_order = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i)->getValue();
            $so_vmk = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $i)->getValue();
            $po_vmk = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i)->getValue();
            $order_description = str_replace("'", "", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $i)->getValue());
            $line_description = str_replace("'", "", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $i)->getValue());
            $length = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $i)->getValue();
            $width = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $i)->getValue();
            $height = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10, $i)->getValue();
            $code = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(11, $i)->getValue();
            $code_information = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $i)->getValue();
            $floor = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(13, $i)->getValue();
            $quantity = str_replace(",", ".", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(14, $i)->getValue());
            $distribution_to_production_date = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(15, $i)->getValue();

            if($distribution_to_production_date <> ""){
                if(strpos($distribution_to_production_date, "-")){
                    $distribution_to_production_date = date("Y-m-d", strtotime($distribution_to_production_date));
                }else{
                    $distribution_to_production_date = ($distribution_to_production_date - 25569) * 86400;
                    $distribution_to_production_date = gmdate("Y-m-d", $distribution_to_production_date);
                }
            }
            else
                $distribution_to_production_date = null;
    
            $pic = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(17, $i)->getValue();
            //$position = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(15, $i)->getValue();
            $date_target_ppic = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(18, $i)->getValue();
            $date_target_pembahanan = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(19, $i)->getValue();
            $date_target_perakitan = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(20, $i)->getValue();
            $subcont_perakitan = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(21, $i)->getValue();
            $date_target_finishing = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(22, $i)->getValue();
            $subcont_finishing = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(23, $i)->getValue();
            $date_target_finish_good = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(24, $i)->getValue();
            $date_target_pengiriman = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(25, $i)->getValue();
            $information = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(26, $i)->getValue();
            $position = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(27, $i)->getValue();
            
            if($date_target_ppic <> ""){
                if(strpos($date_target_ppic, "-")){
                    $date_target_ppic = date("Y-m-d", strtotime($date_target_ppic));
                }else{
                    $date_target_ppic = ($date_target_ppic - 25569) * 86400;
                    $date_target_ppic = gmdate("Y-m-d", $date_target_ppic);
                }
            }
            else
                $date_target_ppic = null;

            if($date_target_pembahanan <> ""){
                if(strpos($date_target_pembahanan, "-")){
                    $date_target_pembahanan = date("Y-m-d", strtotime($date_target_pembahanan));
                }else{
                    $date_target_pembahanan = ($date_target_pembahanan - 25569) * 86400;
                    $date_target_pembahanan = gmdate("Y-m-d", $date_target_pembahanan);
                }
            }
            else
                $date_target_pembahanan = null;
        
            if($date_target_perakitan <> ""){
                if(strpos($date_target_perakitan, "-")){
                    $date_target_perakitan = date("Y-m-d", strtotime($date_target_perakitan));
                }else{
                    $date_target_perakitan = ($date_target_perakitan - 25569) * 86400;
                    $date_target_perakitan = gmdate("Y-m-d", $date_target_perakitan);
                }
            }
            else
                $date_target_perakitan = null;
            
            if($date_target_finishing <> ""){
                if(strpos($date_target_finishing, "-")){
                    $date_target_finishing = date("Y-m-d", strtotime($date_target_finishing));
                }else{
                    $date_target_finishing = ($date_target_finishing - 25569) * 86400;
                    $date_target_finishing = gmdate("Y-m-d", $date_target_finishing);
                }
            }
            else
                $date_target_finishing = null;
            
            if($date_target_finish_good <> ""){
                if(strpos($date_target_finish_good, "-")){
                    $date_target_finish_good = date("Y-m-d", strtotime($date_target_finish_good));
                }else{
                    $date_target_finish_good = ($date_target_finish_good - 25569) * 86400;
                    $date_target_finish_good = gmdate("Y-m-d", $date_target_finish_good);
                }
            }
            else
                $date_target_finish_good = null;
            
            if($date_target_pengiriman <> ""){
                if(strpos($date_target_pengiriman, "-")){
                    $date_target_pengiriman = date("Y-m-d", strtotime($date_target_pengiriman));
                }else{
                    $date_target_pengiriman = ($date_target_pengiriman - 25569) * 86400;
                    $date_target_pengiriman = gmdate("Y-m-d", $date_target_pengiriman);
                }
            }
            else
                $date_target_pengiriman = null;

            $data_karyawan = $this->get_data_karyawan();
            foreach($data_karyawan as $row){
                if($row['NIK'] == $pic_p)
                    $pic_p_name = $row['NAMA'];

                if($row['NIK'] == $pic_w)
                    $pic_w_name = $row['NAMA'];

                if($row['NIK'] == $pic_install)
                    $pic_install_name = $row['NAMA'];

                if($row['NIK'] == $pic)
                    $pic_name = $row['NAMA'];
            }

            if($production_order <> ""){
                $order = $this->cek_order($production_order);

                if($order['result'] == "true"){
                    $message = "OK";   
                } else {
                    $message = "Production Order not registered at SAP";
                }

                $insert_data = array(
                    'pic_p'                             => $pic_p,
                    'pic_p_name'                        => $pic_p_name,
                    'pic_w'                             => $pic_w,
                    'pic_w_name'                        => $pic_w_name,
                    'pic_install'                       => $pic_install,
                    'pic_install_name'                  => $pic_install_name,
                    'production_order'                  => $production_order,
                    'po_vmk'                            => $po_vmk,
                    'order_description'                 => $order_description,
                    'line_description'                  => $line_description,
                    'length'                            => $length,
                    'width'                             => $width,
                    'height'                            => $height,
                    'code'                              => $code,
                    'code_information'                  => $code_information,
                    'floor'                             => $floor,
                    'quantity'                          => $quantity,
                    'distribution_to_production_date'   => $distribution_to_production_date,
                    'pic'                               => $pic,
                    'pic_name'                          => $pic_name,
                    'position'                          => $position,
                    'date_target_ppic'                  => $date_target_ppic,
                    'date_target_pembahanan'            => $date_target_pembahanan,
                    'date_target_perakitan'             => $date_target_perakitan,
                    'subcont_perakitan'                 => $subcont_perakitan,
                    'date_target_finishing'             => $date_target_finishing,
                    'subcont_finishing'                 => $subcont_finishing,
                    'date_target_finish_good'           => $date_target_finish_good,
                    'date_target_pengiriman'            => $date_target_pengiriman,
                    'information'                       => $information,
                    'message'                           => $message,
                    'created_by'                        => $user,
                    'so_vmk'                            => $so_vmk
                );

                $insert = $this->prodmon_model->insert_temp_upload($insert_data);
            }
        }
        $filename = 'upload-data-' . date('YmdHis');
        $uploaded = $this->gdriveupload->uploadExcelToServer($file, $filename);
        redirect('transaction/temp');
    }

    function temp(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        

        $data['production_order'] = $this->prodmon_model->get_data_temp($user);

        $data['main_content']='transaction/temp';
        $this->load->view('template/main', $data);
    }

    function delete_temp(){
        $id = $this->input->post('id');
        $delete = $this->prodmon_model->delete_temp_by_id($id);

        redirect('transaction/temp');
    }

    function input_temp(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');

        $err_message = array();
        $success = 0;
        $list_production_order = $this->prodmon_model->get_data_temp($user);
        foreach($list_production_order as $row){
            $production_order = $row['production_order'];
            $so_vmk = $row['so_vmk'];
            $order_description = str_replace("'", "", $row['order_description']);

            $check = $this->prodmon_model->count_production_order($production_order);
            if($check == 0){
                $order = $this->cek_order($production_order);

                if($order['result'] == "true"){
                    $current_date = date("Y-m-d H:i:s");

                    if($order['data'][0]['TGL_CRTD'] <> "00000000")
                        $created_date = date("Y-m-d", strtotime($order['data'][0]['TGL_CRTD']));

                    if($order['data'][0]['TGL_REL'] <> "00000000")
                        $release_date = date("Y-m-d", strtotime($order['data'][0]['TGL_REL']));

                    if($order['data'][0]['TGL_POSTING'] <> "00000000")
                        $posting_date = date("Y-m-d", strtotime($order['data'][0]['TGL_POSTING']));
                    
                    if($order['data'][0]['BASIC_DATE'] <> "00000000")
                        $basic_date_start = date("Y-m-d", strtotime($order['data'][0]['BASIC_DATE']));

                    if($order['data'][0]['END_DATE'] <> "00000000")
                        $basic_date_end = date("Y-m-d", strtotime($order['data'][0]['END_DATE']));

                    if(!empty($user_plant)){
                        if($user_plant == $order['data'][0]['PLANT']){
                            $insert_data = array(
                                'production_order'      => $order['data'][0]['NO_ORDER'],
                                'order_description'     => $order_description,
                                'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                                'project_definition'    => $order['data'][0]['PROJ_DEF'],
                                'project_description'   => $order['data'][0]['PROJ_DESC'],
                                'created_date'          => $created_date,
                                'release_date'          => $release_date,
                                'posting_date'          => $posting_date,
                                'plant'                 => $order['data'][0]['PLANT'],
                                'order_type'            => $order['data'][0]['ORDER_TYPE'],
                                'material_code'         => $order['data'][0]['KODE_MAT'],
                                'material_description'  => $order['data'][0]['DESC_MAT'],
                                'uom'                   => $order['data'][0]['SATUAN'],
                                'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                                'sales_order'           => $order['data'][0]['SO_NUMBER'],
                                'basic_date_start'      => $basic_date_start,
                                'basic_date_end'        => $basic_date_end,
                                'created_by'            => $user,
                                'so_vmk'                => $so_vmk,
                            );

                            $insert = $this->prodmon_model->insert_header($insert_data);
                            if(!$insert){
                                array_push($err_message, "- Failed Input Header with Production Order ".$production_order);
                            }
                        } else {
                            array_push($err_message, "- Failed Input Header with Production Order. You are not authorized to input Production Order with Plant ".$order['data'][0]['PLANT']);
                        }
                    } else {
                        $insert_data = array(
                            'production_order'      => $order['data'][0]['NO_ORDER'],
                            'order_description'     => $order_description,
                            'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                            'project_definition'    => $order['data'][0]['PROJ_DEF'],
                            'project_description'   => $order['data'][0]['PROJ_DESC'],
                            'created_date'          => $created_date,
                            'release_date'          => $release_date,
                            'posting_date'          => $posting_date,
                            'plant'                 => $order['data'][0]['PLANT'],
                            'order_type'            => $order['data'][0]['ORDER_TYPE'],
                            'material_code'         => $order['data'][0]['KODE_MAT'],
                            'material_description'  => $order['data'][0]['DESC_MAT'],
                            'uom'                   => $order['data'][0]['SATUAN'],
                            'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                            'sales_order'           => $order['data'][0]['SO_NUMBER'],
                            'basic_date_start'      => $basic_date_start,
                            'basic_date_end'        => $basic_date_end,
                            'created_by'            => $user,
                            'so_vmk'                => $so_vmk,
                        );

                        $insert = $this->prodmon_model->insert_header($insert_data);
                        if(!$insert){
                            array_push($err_message, "- Failed Input Header with Production Order ".$production_order);
                        }
                    }
                }
            }else
                $order = $this->cek_order($production_order);

            $line_description = $row['line_description'];
            $pic_p_nik = $row['pic_p'];
            $pic_p_name = $row['pic_p_name'];
            $pic_w_nik = $row['pic_w'];
            $pic_w_name = $row['pic_w_name'];
            $pic_install_nik = $row['pic_install'];
            $pic_install_name = $row['pic_install_name'];
            $po_vmk = $row['po_vmk'];
            $length = $row['length'];
            $width = $row['width'];
            $height = $row['height'];
            $code = $row['code'];
            $code_information = $row['code_information'];
            $floor = $row['floor'];
            $quantity = str_replace(",", ".", $row['quantity']);
            $distribution_to_production_date = $row['distribution_to_production_date'];
            $pic_nik = $row['pic'];
            $pic_name = $row['pic_name'];

            $position_data = $this->prodmon_model->get_position_by_name(strtoupper($row['position']));
            $position = $position_data['production_process_id'];

            $date_target_ppic = $row['date_target_ppic'];
            $date_target_pembahanan = $row['date_target_pembahanan'];
            $date_target_perakitan = $row['date_target_perakitan'];
            $subcont_perakitan = $row['subcont_perakitan'];
            $date_target_finishing = $row['date_target_finishing'];
            $subcont_finishing = $row['subcont_finishing'];
            $date_target_finish_good = $row['date_target_finish_good'];
            $date_target_pengiriman = $row['date_target_pengiriman'];

            $information = $row['information'];

            $line_item_no = $this->prodmon_model->get_latest_line_item($production_order);
            if(isset($line_item_no['line_item']))
                $line_item = $line_item_no['line_item']+1;
            else
                $line_item = 1;

            $data_header = $this->prodmon_model->get_header($production_order);
            $total_qty = $data_header['quantity'];
            $check_qty = $this->prodmon_model->count_quantity($production_order);
            $new_total_qty = $check_qty['total'] + $quantity;
            
            if(trim($new_total_qty) > $total_qty){
                array_push($err_message, "- Failed Input Some Item with Production Order ".$production_order.". Quantity Exceed Header Quantity");
            } else {
                $insert_data = array(
                    'production_order'                  => $production_order,
                    'line_item'                         => $line_item,
                    'line_description'                  => $line_description,
                    'pic_p'                             => $pic_p_nik,
                    'pic_p_name'                        => $pic_p_name,
                    'pic_w'                             => $pic_w_nik,
                    'pic_w_name'                        => $pic_w_name,
                    'pic_install'                       => $pic_install_nik,
                    'pic_install_name'                  => $pic_install_name,
                    'po_vmk'                            => $po_vmk,
                    'length'                            => $length,
                    'width'                             => $width,
                    'height'                            => $height,
                    'code'                              => $code,
                    'code_information'                  => $code_information,
                    'floor'                             => $floor,
                    'quantity'                          => $quantity,
                    'distribution_to_production_date'   => $distribution_to_production_date,
                    'pic'                               => $pic_nik,
                    'pic_name'                          => $pic_name,
                    'position'                          => $position,
                    'date_target_ppic'                  => $date_target_ppic,
                    'date_target_pembahanan'            => $date_target_pembahanan,
                    'date_target_perakitan'             => $date_target_perakitan,
                    'subcont_perakitan'                 => $subcont_perakitan,
                    'date_target_finishing'             => $date_target_finishing,
                    'subcont_finishing'                 => $subcont_finishing,
                    'date_target_finish_good'           => $date_target_finish_good,
                    'date_target_pengiriman'            => $date_target_pengiriman,
                    'information'                       => $information,
                    'created_by'                        => $user,
                );

                $insert = $this->prodmon_model->insert_item($insert_data);
                if($insert <> 0){
                    $insert_data_quantity = array(
                        'production_order_item_id'  => $insert,
                        'position'                  => $position,
                        'quantity'                  => $quantity,
                        'created_by'                => $user
                    );

                    $this->prodmon_model->insert_item_quantity($insert_data_quantity);

                    $insert_data_history = array(
                        'production_order_item_id'  => $insert,
                        'position'                  => $position,
                        'created_by'                => $user
                    );

                    $insert = $this->prodmon_model->insert_item_history($insert_data_history);         
                    $success = 1;        
                }
                else{
                    array_push($err_message, "- Failed Input Some Item with Production Order ".$production_order);
                }
            }
        }

        if(!empty($err_message) && $success == 0){
            $string_err = implode("<br>", $err_message);
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                <a class="close" data-dismiss="alert"></a>
                <strong>There are some error. </strong>'.$string_err.'
            </div>');  
        } elseif(!empty($err_message) && $success == 1){
            $string_err = implode("<br>", $err_message);
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Some Data successfully added <br>

                <strong>There are some error.<br></strong>'.$string_err.'
            </div>');  
        } else {
            $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Data successfully added
            </div>'); 
        }
        
        redirect('transaction/upload');
    }

    function input(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $data['main_content']='transaction/input';
        $this->load->view('template/main', $data);
    }

    function input_production_order(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');

        $production_order = $this->input->post('production_order');
        $order_description = str_replace("'", "", $this->input->post('order_description'));

        $check = $this->prodmon_model->count_production_order($production_order);
        if($check == 0){
            $order = $this->cek_order($production_order);
            if($order['result'] == "true"){
                $current_date = date("Y-m-d H:i:s");

                if($order['data'][0]['TGL_CRTD'] <> "00000000")
                    $created_date = date("Y-m-d", strtotime($order['data'][0]['TGL_CRTD']));

                if($order['data'][0]['TGL_REL'] <> "00000000")
                    $release_date = date("Y-m-d", strtotime($order['data'][0]['TGL_REL']));

                if($order['data'][0]['TGL_POSTING'] <> "00000000")
                    $posting_date = date("Y-m-d", strtotime($order['data'][0]['TGL_POSTING']));
                
                if($order['data'][0]['BASIC_DATE'] <> "00000000")
                    $basic_date_start = date("Y-m-d", strtotime($order['data'][0]['BASIC_DATE']));

                if($order['data'][0]['END_DATE'] <> "00000000")
                    $basic_date_end = date("Y-m-d", strtotime($order['data'][0]['END_DATE']));

                if(!empty($user_plant)){
                    if($user_plant == $order['data'][0]['PLANT']){
                        $insert_data = array(
                            'production_order'      => $order['data'][0]['NO_ORDER'],
                            'order_description'     => $order_description,
                            'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                            'project_definition'    => $order['data'][0]['PROJ_DEF'],
                            'project_description'   => $order['data'][0]['PROJ_DESC'],
                            'created_date'          => $created_date,
                            'release_date'          => $release_date,
                            'posting_date'          => $posting_date,
                            'plant'                 => $order['data'][0]['PLANT'],
                            'order_type'            => $order['data'][0]['ORDER_TYPE'],
                            'material_code'         => $order['data'][0]['KODE_MAT'],
                            'material_description'  => $order['data'][0]['DESC_MAT'],
                            'uom'                   => $order['data'][0]['SATUAN'],
                            'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                            'sales_order'           => $order['data'][0]['SO_NUMBER'],
                            'basic_date_start'      => $basic_date_start,
                            'basic_date_end'        => $basic_date_end,
                            'created_by'            => $user
                        );
                        
                        $insert = $this->prodmon_model->insert_header($insert_data);
                        if($insert){
                            $this->session->set_flashdata('msg','<div class="alert alert-success">
                                     <a class="close" data-dismiss="alert"></a>
                                     <strong>Success!</strong> Data successfully added
                                 </div>');
                            $url = "transaction/view_production_order/".$production_order;
                            redirect($url);
                        }
                        else{
                            $this->session->set_flashdata('msg','<div class="alert alert-error">
                                     <a class="close" data-dismiss="alert"></a>
                                     <strong>Error!</strong> Failed input data
                                 </div>');
                            $url = "transaction/input/";
                            redirect($url); 
                        }
                    } else {
                        $this->session->set_flashdata('msg','<div class="alert alert-error">
                                 <a class="close" data-dismiss="alert"></a>
                                 <strong>Error!</strong> You are not authorized to input Production Order with Plant '.$order['data'][0]['PLANT'].'
                             </div>');
                        $url = "transaction/input/";
                        redirect($url);
                    }
                } else {
                    $insert_data = array(
                        'production_order'      => $order['data'][0]['NO_ORDER'],
                        'order_description'     => $order_description,
                        'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                        'project_definition'    => $order['data'][0]['PROJ_DEF'],
                        'project_description'   => $order['data'][0]['PROJ_DESC'],
                        'created_date'          => $created_date,
                        'release_date'          => $release_date,
                        'posting_date'          => $posting_date,
                        'plant'                 => $order['data'][0]['PLANT'],
                        'order_type'            => $order['data'][0]['ORDER_TYPE'],
                        'material_code'         => $order['data'][0]['KODE_MAT'],
                        'material_description'  => $order['data'][0]['DESC_MAT'],
                        'uom'                   => $order['data'][0]['SATUAN'],
                        'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                        'sales_order'           => $order['data'][0]['SO_NUMBER'],
                        'basic_date_start'      => $basic_date_start,
                        'basic_date_end'        => $basic_date_end,
                        'created_by'            => $user
                    );

                    $insert = $this->prodmon_model->insert_header($insert_data);
                    if($insert){
                        $this->session->set_flashdata('msg','<div class="alert alert-success">
                                 <a class="close" data-dismiss="alert"></a>
                                 <strong>Success!</strong> Data successfully added
                             </div>');
                        $url = "transaction/view_production_order/".$production_order;
                        redirect($url);
                    }
                    else{
                        $this->session->set_flashdata('msg','<div class="alert alert-error">
                                 <a class="close" data-dismiss="alert"></a>
                                 <strong>Error!</strong> Failed input data
                             </div>');
                        $url = "transaction/input/";
                        redirect($url); 
                    }
                }
            } else {
                $this->session->set_flashdata('msg','<div class="alert alert-error">
                             <a class="close" data-dismiss="alert"></a>
                             <strong>Error!</strong> Production Order not registered at SAP
                         </div>');
                $url = "transaction/input/";
                redirect($url);
            }
        } else {
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                             <a class="close" data-dismiss="alert"></a>
                             <strong>Error!</strong> Production Order already exist
                         </div>');
            $url = "transaction/input/";
            redirect($url);
        }
    }

    function cek_order($production_order){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://webdev.vivere.co.id/vservice/monitoring_produksi/get_data_no_order",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "[\r\n\t{ \r\n       \"no_prod_order\" : \"$production_order\"\r\n    }\r\n]\r\n   ",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Postman-Token: 7c8d4fcf-5eef-4a5f-bdd4-1c13fb0eb714",
            "cache-control: no-cache"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $response = json_decode($response, true);
        }

         return $response;
    }

    function view_production_order($production_order){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');

        if(!empty($user_plant)){
            $check = $this->prodmon_model->count_production_order_by_plant($production_order,$user_plant);
            if($check == 0){
                $this->session->set_flashdata('msg','<div class="alert alert-error">
                             <a class="close" data-dismiss="alert"></a>
                             <strong>Error!</strong> You are not authorized to view Production Order '.$production_order.'
                         </div>');
                $url = "transaction/change/";
                redirect($url); 
            }
        }

        $line_item = $this->prodmon_model->get_item($production_order);
        foreach ($line_item as $key => $value) {
            $qty_pack = $this->prodmon_model->get_qty_pack($production_order, $value['packing_code'], 'panel');
            $line_item[$key]['qty_pack'] = $qty_pack['qty_pack'];
        }

        $non_panel = $this->prodmon_model->get_item_non_panel($production_order);
        foreach ($non_panel as $key => $value) {
            $qty_pack = $this->prodmon_model->get_qty_pack($production_order, $value['packing_code'], 'non-panel');
            $non_panel[$key]['qty_pack'] = $qty_pack['qty_pack'];
        }

        $data = $this->prodmon_model->get_header($production_order);
        $product = $this->prodmon_model->get_produk($production_order);
        $data['product'] = $product['product'];
        $data['line_item'] = $line_item;
        $data['non_panel'] = $non_panel;
        $data['main_content']='transaction/view_production_order';
        $this->load->view('template/main', $data);
    }

    function edit_header($production_order){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $data['header'] = $this->prodmon_model->get_header($production_order);

        $data['main_content']='transaction/edit_header';
        $this->load->view('template/main', $data);
    }

    function edit_header_data(){
        $user = $this->session->userdata('nik_prodmon');

        $production_order = $this->input->post('production_order');
        $order_description = str_replace("'", "", $this->input->post('order_description'));

        $update_data = array(
            'order_description' => $order_description
        );

        $update = $this->prodmon_model->update_header($production_order, $update_data);
        if($update){
            $this->prodmon_model->delete_report_header($production_order);

            $this->session->set_flashdata('msg','<div class="alert alert-success">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Success!</strong> Data successfully updated
                 </div>');
            $url = "transaction/view_production_order/".$production_order;
            redirect($url);
        }
        else{
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Error!</strong> Failed update data
                 </div>');
            $url = "transaction/view_production_order/".$production_order;
            redirect($url); 
        }
    }

    function delete_header(){
        $production_order = $this->input->post('production_order');

        $this->prodmon_model->delete_report_header($production_order);
        $this->prodmon_model->delete_item_by_header($production_order);
        $this->prodmon_model->delete_header($production_order);

        $this->session->set_flashdata('msg','<div class="alert alert-success">
                 <a class="close" data-dismiss="alert"></a>
                 <strong>Success!</strong> Header successfully deleted
             </div>');
        $url = "transaction/input/";
        redirect($url);
    }

    function add_item($production_order){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user = $this->session->userdata('nik_prodmon');

        $data['karyawan'] = $this->get_data_karyawan();
        $data['process'] = $this->prodmon_model->get_process();
        $data['production_order'] = $production_order;

        $line_item_no = $this->prodmon_model->get_latest_line_item($production_order);
        if(isset($line_item_no['line_item']))
            $data['line_item_no'] = $line_item_no['line_item']+1;
        else
            $data['line_item_no'] = 1;

        $data['main_content']='transaction/add_item';
        $this->load->view('template/main', $data);
    }

    function add_item_data(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $production_order = $this->input->post('production_order');
        $line_description = $this->input->post('line_description');
        $po_vmk = $this->input->post('po_vmk');
        $pic_p = explode(" - ", $this->input->post('pic_p'));
        $pic_p_nik = $pic_p[0];
        $pic_p_name = $pic_p[1];
        $pic_w = explode(" - ", $this->input->post('pic_w'));
        $pic_w_nik = $pic_w[0];
        $pic_w_name = $pic_w[1];
        $pic_install = explode(" - ", $this->input->post('pic_install'));
        $pic_install_nik = $pic_install[0];
        $pic_install_name = $pic_install[1];
        $length = $this->input->post('panjang');
        $width = $this->input->post('lebar');
        $height = $this->input->post('tinggi');
        $code = $this->input->post('kode');
        $code_information = $this->input->post('keterangan_kode');
        $floor = $this->input->post('lantai');
        $quantity = str_replace(",", ".", $this->input->post('quantity'));

        if($this->input->post('tgl1') <> "")
            $distribution_to_production_date = date("Y-m-d", strtotime($this->input->post('tgl1')));
            
        $pic = explode(" - ", $this->input->post('pic'));
        $pic_nik = $pic[0];
        $pic_name = $pic[1];
        $position = $this->input->post('posisi');
        $information = $this->input->post('keterangan');

        if($this->input->post('tgl2') <> "")
            $detail_schedule = date("Y-m-d", strtotime($this->input->post('tgl2')));

        if($this->input->post('tgl_ppic') <> "")
            $date_target_ppic = date("Y-m-d", strtotime($this->input->post('tgl_ppic')));

        if($this->input->post('tgl_pembahanan') <> "")
            $date_target_pembahanan = date("Y-m-d", strtotime($this->input->post('tgl_pembahanan')));
        
        if($this->input->post('tgl_perakitan') <> "")
            $date_target_perakitan = date("Y-m-d", strtotime($this->input->post('tgl_perakitan')));
        
        $subcont_perakitan = $this->input->post('subkon_perakitan');

        if($this->input->post('tgl_finishing') <> "")
            $date_target_finishing = date("Y-m-d", strtotime($this->input->post('tgl_finishing')));
        
        $subcont_finishing = $this->input->post('subkon_finishing');

        if($this->input->post('tgl_finishgood') <> "")
            $date_target_finish_good = date("Y-m-d", strtotime($this->input->post('tgl_finishgood')));
        
        if($this->input->post('tgl_pengiriman') <> "")
            $date_target_pengiriman = date("Y-m-d", strtotime($this->input->post('tgl_pengiriman')));
        
        $line_item = $this->input->post('line_item');

        $data_header = $this->prodmon_model->get_header($production_order);
        $total_qty = $data_header['quantity'];
        $check_qty = $this->prodmon_model->count_quantity($production_order);
        $new_total_qty = $check_qty['total'] + $quantity;

        if(trim($new_total_qty) > $total_qty){
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Error!</strong> Quantity will exceed Production Order quantity
                     </div>');
            $url = "transaction/view_production_order/".$production_order;
            redirect($url); 
        } else {
            $insert_data = array(
                'production_order'                  => $production_order,
                'line_description'                  => $line_description,
                'po_vmk'                            => $po_vmk,
                'line_item'                         => $line_item,
                'pic_p'                             => $pic_p_nik,
                'pic_p_name'                        => $pic_p_name,
                'pic_w'                             => $pic_w_nik,
                'pic_w_name'                        => $pic_w_name,
                'pic_install'                       => $pic_install_nik,
                'pic_install_name'                  => $pic_install_name,
                'length'                            => $length,
                'width'                             => $width,
                'height'                            => $height,
                'code'                              => $code,
                'code_information'                  => $code_information,
                'floor'                             => $floor,
                'quantity'                          => $quantity,
                'distribution_to_production_date'   => $distribution_to_production_date,
                'pic'                               => $pic_nik,
                'pic_name'                          => $pic_name,
                'position'                          => $position,
                'information'                       => $information,
                'detail_schedule'                   => $detail_schedule,
                'date_target_ppic'                  => $date_target_ppic,
                'date_target_pembahanan'            => $date_target_pembahanan,
                'date_target_perakitan'             => $date_target_perakitan,
                'subcont_perakitan'                 => $subcont_perakitan,
                'date_target_finishing'             => $date_target_finishing,
                'subcont_finishing'                 => $subcont_finishing,
                'date_target_finish_good'           => $date_target_finish_good,
                'date_target_pengiriman'            => $date_target_pengiriman,
                'created_by'                        => $user
            );

            $insert = $this->prodmon_model->insert_item($insert_data);
            if($insert <> 0){
                $insert_data_quantity = array(
                    'production_order_item_id'  => $insert,
                    'position'                  => $position,
                    'quantity'                  => $quantity,
                    'created_by'                => $user
                );

                $this->prodmon_model->insert_item_quantity($insert_data_quantity);

                $insert_data_history = array(
                    'production_order_item_id'  => $insert,
                    'position'                  => $position,
                    'created_by'                => $user
                );

                $this->prodmon_model->insert_item_history($insert_data_history);

                $this->session->set_flashdata('msg','<div class="alert alert-success">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Success!</strong> Data successfully added
                     </div>');
                $url = "transaction/view_production_order/".$production_order;
                redirect($url);
            }
            else{
                $this->session->set_flashdata('msg','<div class="alert alert-error">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Error!</strong> Failed input data
                     </div>');
                $url = "transaction/view_production_order/".$production_order;
                redirect($url); 
            }
        }
    }

    function edit_item($production_order, $line_item){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user = $this->session->userdata('nik_prodmon');

        $data['karyawan'] = $this->get_data_karyawan();
        
        $data['process'] = $this->prodmon_model->get_process_ppic_pembahanan();
        $data['production_order'] = $production_order;

        $product = $this->prodmon_model->get_produk($production_order);
        $data['product'] = $product['product'];

        $item = $this->prodmon_model->get_line_item($production_order, $line_item);
        $qty_pack = $this->prodmon_model->get_qty_pack($production_order, $item['packing_code'], 'panel');
        $item['qty_pack'] = $qty_pack['qty_pack'];

        $data['item'] = $item;

        $data['main_content']='transaction/edit_item';
        $this->load->view('template/main', $data);
    }

    function edit_item_data(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $production_order = $this->input->post('production_order');
        $line_item = $this->input->post('line_item');
        $line_description = $this->input->post('line_description');
        $workshop = $this->input->post('workshop');
        $box_no = $this->input->post('box_no');
        $packing_code = $this->input->post('packing_code');
        $grouping_code = $this->input->post('grouping_code');
        $module = $this->input->post('module');
        $batch = $this->input->post('batch');
        $material = $this->input->post('material');
        $weight = $this->input->post('weight');
        $product = $this->input->post('product');
        $component_no = $this->input->post('component_no');
        $component_name = $this->input->post('component_name');
        $po_vmk = $this->input->post('po_vmk');
        
        $length = $this->input->post('panjang');
        $width = $this->input->post('lebar');
        $height = $this->input->post('tinggi');
        $code = $this->input->post('kode');
        $code_information = $this->input->post('ket_kode');
        
        $floor = $this->input->post('lantai');
        $quantity = str_replace(",", ".", $this->input->post('quantity'));
        $qty_pack = $this->input->post('qty_pack');

        $position = $this->input->post('posisi');

        if(strlen($box_no) < 2)
            $box_no = "0".$box_no;

        if($this->input->post('tgl2') <> "")
            $detail_schedule = date("Y-m-d", strtotime($this->input->post('tgl2')));
        
        if($this->input->post('distribution_to_production_date') <> "")
            $distribution_to_production_date = date("Y-m-d", strtotime($this->input->post('distribution_to_production_date')));
            
        if($this->input->post('date_target_ppic') <> "")
            $date_target_ppic = date("Y-m-d", strtotime($this->input->post('date_target_ppic')));
        
        if($this->input->post('date_target_pembahanan') <> "")
            $date_target_pembahanan = date("Y-m-d", strtotime($this->input->post('date_target_pembahanan')));

        if($this->input->post('date_target_perakitan') <> "")
            $date_target_perakitan = date("Y-m-d", strtotime($this->input->post('date_target_perakitan')));
        
        if($this->input->post('date_target_finishing') <> "")
            $date_target_finishing = date("Y-m-d", strtotime($this->input->post('date_target_finishing')));
        
        if($this->input->post('date_target_finish_good') <> "")
            $date_target_finish_good = date("Y-m-d", strtotime($this->input->post('date_target_finish_good')));
        
        if($this->input->post('date_target_pengiriman') <> "")
            $date_target_pengiriman = date("Y-m-d", strtotime($this->input->post('date_target_pengiriman')));

        $subcont_perakitan = $this->input->post('subcont_perakitan');
        $subcont_finishing = $this->input->post('subcont_finishing');

        $quantity_old = str_replace(",", ".", $this->input->post('quantity_old'));
        $position_old = $this->input->post('posisi_old');
        $production_order_item_id = $this->input->post('production_order_item_id');

        $data_header = $this->prodmon_model->get_header($production_order);
        $total_qty = $data_header['quantity'];
        $check_qty = $this->prodmon_model->count_quantity($production_order);
        $new_total_qty = $check_qty['total'] + ($quantity - $quantity_old);
        
        if($position_old > "2")
            $position = $position_old;
        
        // if(str_replace(".", ",", $new_total_qty) > str_replace(".", ",", $total_qty)){
        //     $this->session->set_flashdata('msg','<div class="alert alert-error">
        //                  <a class="close" data-dismiss="alert"></a>
        //                  <strong>Error!</strong> Quantity will exceed Production Order quantity
        //              </div>');
        //     $url = "transaction/view_production_order/".$production_order;
        //     redirect($url); 
        // } else {
            
            $update_data = array(
                'line_description'        => $line_description,
                'workshop'                => $workshop,
                'box_no'                  => $box_no,
                'packing_code'            => $packing_code,
                'grouping_code'           => $grouping_code,
                'module'                  => $module,
                'batch'                   => $batch,
                'material'                => $material,
                'weight'                  => $weight,
                'component_no'            => $component_no,
                'component_name'          => $component_name,
                'po_vmk'                  => $po_vmk,
                'length'                  => $length,
                'width'                   => $width,
                'height'                  => $height,
                'code'                    => $code,
                'code_information'        => $code_information,
                'floor'                   => $floor,
                'quantity'                => $quantity,
                'position'                => $position,
                'detail_schedule'         => $detail_schedule,
                'distribution_to_production_date' => $distribution_to_production_date,
                'date_target_ppic'        => $date_target_ppic,
                'date_target_pembahanan'  => $date_target_pembahanan,
                'date_target_perakitan'   => $date_target_perakitan,
                'subcont_perakitan'       => $subcont_perakitan,
                'date_target_finishing'   => $date_target_finishing,
                'subcont_finishing'       => $subcont_finishing,
                'date_target_finish_good' => $date_target_finish_good,
                'date_target_pengiriman'  => $date_target_pengiriman,
                'updated_by'              => $user
            );

            $update = $this->prodmon_model->update_item($production_order_item_id, $update_data);
            if($update){
                $this->prodmon_model->delete_report_item($production_order_item_id);
                
                if($position <> $position_old && $position_old <= 2){
                    $check_position = $this->prodmon_model->get_line_quantity_position($production_order_item_id,$position);
                    if(sizeof($check_position) > 0){
                        $id = $check_position['production_order_item_quantity_id'];
                        $qty_now = $check_position['quantity'];

                        $new_qty = $qty_now + $quantity;

                        $update_data = array(
                            'quantity'          => $new_qty,
                            'remark'            => $remark,
                            'updated_by'        => $user
                        );

                        $update = $this->prodmon_model->update_item_qty($id, $update_data);

                    } else {
                        $insert_data = array(
                            'production_order_item_id'  => $production_order_item_id,
                            'position'                  => $position,
                            'quantity'                  => $quantity,
                            'remark'                    => $remark,
                            'created_by'                => $user
                        );

                        $insert = $this->prodmon_model->insert_item_quantity($insert_data);
                        
                    }

                    $position_qty_data = $this->prodmon_model->get_line_quantity_position($production_order_item_id,$position_old);
                    $position_id = $position_qty_data['production_order_item_quantity_id'];

                    $update_data = array(
                        'quantity'          => "0",
                        'updated_by'        => $user
                    );

                    $update = $this->prodmon_model->update_item_qty($position_id, $update_data);

                    $insert_data_history = array(
                        'production_order_item_id'  => $production_order_item_id,
                        'from_position'             => $position_old,
                        'position'                  => $position,
                        'created_by'                => $user
                    );

                    $insert = $this->prodmon_model->insert_item_history($insert_data_history);

                    $message = "Terdapat Production Order yang sudah direlease ke pembahanan. Silahkan dilakukan produksi";

                    $title = "Notifikasi Pembahanan";
                    $operator_token = $this->prodmon_model->get_operator_token();
                    $firebase_token = array();
                    foreach($operator_token as $row){
                        array_push($firebase_token, $row['firebase_token']);
                    }

                    $this->send_firebase($firebase_token, $title, $message, 'PPIC', 'Operator');
                }

                $input_qty_pack = array(
                    'qty_pack' => $qty_pack
                );
                $update_qty_pack = $this->prodmon_model->update_production_order_pack($production_order, $packing_code, 'panel', $input_qty_pack);

                $input_product = array(
                    'product'    => $product,
                    'updated_by' => $user,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $update_product = $this->prodmon_model->update_product($production_order, $input_product);
                
                $this->session->set_flashdata('msg','<div class="alert alert-success">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Success!</strong> Data successfully updated
                     </div>');
                $url = "transaction/view_production_order/".$production_order;
                redirect($url);
            }
            else{
                $this->session->set_flashdata('msg','<div class="alert alert-error">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Error!</strong> Failed update data
                     </div>');
                $url = "transaction/view_production_order/".$production_order;
                redirect($url); 
            }
        // }
    }

    function delete_item(){
        $id = $this->input->post('id');
        $production_order = $this->input->post('production_order');

        $this->prodmon_model->delete_report_item($id);
        $this->prodmon_model->delete_item($id);

        $this->session->set_flashdata('msg','<div class="alert alert-success">
                 <a class="close" data-dismiss="alert"></a>
                 <strong>Success!</strong> Item successfully deleted
             </div>');
        $url = "transaction/view_production_order/".$production_order;
        redirect($url);
    }

    function change(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user_plant = $this->session->userdata('plant_prodmon');

        if(!empty($user_plant)){
            $data['list_production_order'] = $this->prodmon_model->get_list_header_by_plant($user_plant);
        } else {
            $data['list_production_order'] = $this->prodmon_model->get_list_header();
        }

        $data['main_content']='transaction/change';
        $this->load->view('template/main', $data);
    }

    function change_data(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $production_order = $this->input->post('production_order');
        $check = $this->prodmon_model->count_production_order($production_order);
        if($check > 0){
            $view = "transaction/view_production_order/".$production_order;
            redirect($view);
        } else {
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Error!</strong> Production Order not found
                     </div>');
            $url = "transaction/change/";
            redirect($url); 
        }
    }

    function display(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user_plant = $this->session->userdata('plant_prodmon');

        if(!empty($user_plant)){
            $data['list_production_order'] = $this->prodmon_model->get_list_header_by_plant($user_plant);
        } else {
            $data['list_production_order'] = $this->prodmon_model->get_list_header();
        }

        
        $data['main_content']='transaction/display';
        $this->load->view('template/main', $data);
    }

    function display_data(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user_plant = $this->session->userdata('plant_prodmon');

        $production_order1 = $this->input->post('production_order1');
        $production_order2 = $this->input->post('production_order2');

        if($production_order1 <> "" && $production_order2 == "")
            $data['production_order'] = $this->prodmon_model->get_data_by_production_order($production_order1);
        elseif($production_order1 == "" && $production_order2 <> "")
            $data['production_order'] = $this->prodmon_model->get_data_by_production_order($production_order2);
        elseif($production_order1 == "" && $production_order2 == ""){
            if(!empty($user_plant))
                $data['production_order'] = $this->prodmon_model->get_data_production_order_all_by_plant($user_plant);
            else
                $data['production_order'] = $this->prodmon_model->get_data_production_order_all();
        }
        else
            $data['production_order'] = $this->prodmon_model->get_data_range($production_order1, $production_order2);

        $data['main_content']='transaction/display_data';
        $this->load->view('template/main', $data);
        
    }

    function get_data_karyawan(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://apps.vivere.co.id/snabsys/master/get_data_karyawan",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Postman-Token: 202ec94d-657a-4294-bf7a-7e16ae17842c",
            "cache-control: no-cache"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $response = json_decode($response, true);
        }

        return $response;
    }

    function send_firebase($firebase_token, $title, $message, $from, $to){

        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';

        $fields = array(
            'priority'     => 'high',
            'notification' => array(
                'title' => $title,
                'body'  => $message,
                'sound' => 'default'
            ),
            'data'         => array(
                'title'        => $title,
                'body'         => $message,
                'message'      => $message
                // 'category'     => $category,
                // 'id_or_number' => $id_or_number
            )
        );

        if (is_array($firebase_token)) {
            $fields['registration_ids'] = $firebase_token;
        } else {
            $fields['to'] = $firebase_token;
        }

        $headers = array(
            'Authorization:key=AAAALdmYz_w:APA91bH4RuRtdKitTnd1b_CsOC0xDtOfum3uENwdcysNjT0l0cRbIMGSc9VLtWGp3YQ3OFHgGs04M71PL8hue5NRJQp1TbFpOi-ksAiJ5DUJ1XK27TeTQlLwYSVpAVcG6P3Sb5jQaIp-',
            'Content-Type:application/json'
        );
        $ch      = curl_init();

        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        curl_close($ch);

        $data_insert = array(
            'title' => $title,
            'message' => $message,
            'from_notification' => $from,
            'to_notification' => $to,
            'status_send' => "1"
        );

        $this->prodmon_model->insert_notification_history($data_insert);

        return $result;
    }

    function sync_header($production_order){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');

        $order = $this->cek_order($production_order);

        if($order['result'] == "true"){
            $current_date = date("Y-m-d H:i:s");

            if($order['data'][0]['TGL_CRTD'] <> "00000000")
                $created_date = date("Y-m-d", strtotime($order['data'][0]['TGL_CRTD']));

            if($order['data'][0]['TGL_REL'] <> "00000000")
                $release_date = date("Y-m-d", strtotime($order['data'][0]['TGL_REL']));

            if($order['data'][0]['TGL_POSTING'] <> "00000000")
                $posting_date = date("Y-m-d", strtotime($order['data'][0]['TGL_POSTING']));
            
            if($order['data'][0]['BASIC_DATE'] <> "00000000")
                $basic_date_start = date("Y-m-d", strtotime($order['data'][0]['BASIC_DATE']));

            if($order['data'][0]['END_DATE'] <> "00000000")
                $basic_date_end = date("Y-m-d", strtotime($order['data'][0]['END_DATE']));

            if(!empty($user_plant)){
                if($user_plant == $order['data'][0]['PLANT']){
                    $update_data = array(
                        'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                        'project_definition'    => $order['data'][0]['PROJ_DEF'],
                        'project_description'   => $order['data'][0]['PROJ_DESC'],
                        'created_date'          => $created_date,
                        'release_date'          => $release_date,
                        'posting_date'          => $posting_date,
                        'plant'                 => $order['data'][0]['PLANT'],
                        'order_type'            => $order['data'][0]['ORDER_TYPE'],
                        'material_code'         => $order['data'][0]['KODE_MAT'],
                        'material_description'  => $order['data'][0]['DESC_MAT'],
                        'uom'                   => $order['data'][0]['SATUAN'],
                        'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                        'sales_order'           => $order['data'][0]['SO_NUMBER'],
                        'basic_date_start'      => $basic_date_start,
                        'basic_date_end'        => $basic_date_end
                    );

                    $update = $this->prodmon_model->update_header($production_order, $update_data);
                    if($update){
                        $this->prodmon_model->delete_report_header($production_order);

                        $this->session->set_flashdata('msg','<div class="alert alert-success">
                            <a class="close" data-dismiss="alert"></a>
                            <strong>Success!</strong> Data successfully sync
                        </div>');
                    }else{
                        $this->session->set_flashdata('msg','<div class="alert alert-error">
                            <a class="close" data-dismiss="alert"></a>
                            <strong>Error!</strong> Failed sync data
                        </div>'); 
                    }
                } else {
                    $this->session->set_flashdata('msg','<div class="alert alert-error">
                        <a class="close" data-dismiss="alert"></a>
                        <strong>Error!</strong> Failed sync data. 
                    </div>');
                }
            } else {
                $update_data = array(
                    'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                    'project_definition'    => $order['data'][0]['PROJ_DEF'],
                    'project_description'   => $order['data'][0]['PROJ_DESC'],
                    'created_date'          => $created_date,
                    'release_date'          => $release_date,
                    'posting_date'          => $posting_date,
                    'plant'                 => $order['data'][0]['PLANT'],
                    'order_type'            => $order['data'][0]['ORDER_TYPE'],
                    'material_code'         => $order['data'][0]['KODE_MAT'],
                    'material_description'  => $order['data'][0]['DESC_MAT'],
                    'uom'                   => $order['data'][0]['SATUAN'],
                    'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                    'sales_order'           => $order['data'][0]['SO_NUMBER'],
                    'basic_date_start'      => $basic_date_start,
                    'basic_date_end'        => $basic_date_end
                );
                $update = $this->prodmon_model->update_header($production_order, $update_data);
                if($update){
                    $this->prodmon_model->delete_report_header($production_order);
                    
                    $this->session->set_flashdata('msg','<div class="alert alert-success">
                        <a class="close" data-dismiss="alert"></a>
                        <strong>Success!</strong> Data successfully sync
                    </div>');
                }else{
                    $this->session->set_flashdata('msg','<div class="alert alert-error">
                        <a class="close" data-dismiss="alert"></a>
                        <strong>Error!</strong> Failed sync data. Plant not authorized
                    </div>'); 
                }
            }
        }

        redirect('transaction/view_production_order/'.$production_order);
    }
    
    function upload_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $data['main_content']='transaction/upload_panel';
        $this->load->view('template/main', $data);
    }

    function upload_data_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $this->prodmon_model->delete_temp($user);
        
        $file = $_FILES['excel']['tmp_name'];
        //load the excel library
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        $rows = $objPHPExcel->getActiveSheet()->getHighestRow();

        $delete_temp = $this->prodmon_model->delete_temp($user);

        for ($i = 6; $i <= $rows; $i++) {
            $production_order = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->getValue();
            $workshop = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i)->getValue();
            $box_no = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i)->getValue();
           
            if(strlen($box_no) < 2)
               $box_no = "0".$box_no;

            $packing_code = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i)->getValue();
            $grouping_code = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $i)->getValue();
            $module = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i)->getValue();
            $batch = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $i)->getValue();
            $material = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $i)->getValue();
            $weight = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $i)->getValue();
            $product = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $i)->getValue();
            $component_no = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10, $i)->getValue();
            $component_name = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(11, $i)->getValue();
            $order_description = str_replace("'", "", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(12, $i)->getValue());
            $length = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(13, $i)->getValue();
            $width = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(14, $i)->getValue();
            $height = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(15, $i)->getValue();
            $code = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(16, $i)->getValue();
            $floor = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(17, $i)->getValue();
            $quantity = str_replace(",", ".", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(18, $i)->getValue());
            $qty_pack = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(20, $i)->getValue();

            if($production_order <> ""){
                $order = $this->cek_order($production_order);
                $check_component = $this->prodmon_model->check_component($production_order, $component_no);

                if($order['result'] == "true" && $check_component == 0)
                    $message = "OK";
                elseif($order['result'] == "true" && $check_component == 1)
                    $message = "No Komponen already exist";
                else 
                    $message = "Production Order not registered at SAP ";
                
                $insert_data = array(
                    'production_order'                  => $production_order,
                    'workshop'                          => $workshop,
                    'box_no'                            => $box_no,
                    'packing_code'                      => $packing_code,
                    'grouping_code'                     => $grouping_code,
                    'module'                            => $module,
                    'batch'                             => $batch,
                    'material'                          => $material,
                    'weight'                            => $weight,
                    'component_no'                      => $component_no,
                    'component_name'                    => $component_name,
                    'order_description'                 => $order_description,
                    'length'                            => $length,
                    'width'                             => $width,
                    'height'                            => $height,
                    'code'                              => $code,
                    'floor'                             => $floor,
                    'quantity'                          => $quantity,
                    'is_panel'                          => "1",
                    'message'                           => $message,
                    'created_by'                        => $user,
                    'qty_pack'                          => $qty_pack,
                    'product'                           => $product,
                );

                $insert = $this->prodmon_model->insert_temp_upload($insert_data);
            }
        }
        $filename = 'upload-data-panel-' . date('YmdHis');
        $uploaded = $this->gdriveupload->uploadExcelToServer($file, $filename);
        redirect('transaction/temp_panel');
    }

    function temp_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $data['production_order'] = $this->prodmon_model->get_data_temp($user);

        $data['main_content']='transaction/temp_panel';
        $this->load->view('template/main', $data);
    }

    function delete_temp_panel(){
        $id = $this->input->post('id');
        $delete = $this->prodmon_model->delete_temp_by_id($id);

        redirect('transaction/temp_panel');
    }

    function input_temp_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');

        $err_message = array();
        $success = 0;
        $list_production_order = $this->prodmon_model->get_data_temp($user);
        foreach($list_production_order as $row){
            $production_order = $row['production_order'];
            $order_description = str_replace("'", "", $row['order_description']);

            $check = $this->prodmon_model->count_production_order($production_order);
            if($check == 0){
                $order = $this->cek_order($production_order);

                if($order['result'] == "true"){
                    $current_date = date("Y-m-d H:i:s");

                    if($order['data'][0]['TGL_CRTD'] <> "00000000")
                        $created_date = date("Y-m-d", strtotime($order['data'][0]['TGL_CRTD']));

                    if($order['data'][0]['TGL_REL'] <> "00000000")
                        $release_date = date("Y-m-d", strtotime($order['data'][0]['TGL_REL']));

                    if($order['data'][0]['TGL_POSTING'] <> "00000000")
                        $posting_date = date("Y-m-d", strtotime($order['data'][0]['TGL_POSTING']));
                    
                    if($order['data'][0]['BASIC_DATE'] <> "00000000")
                        $basic_date_start = date("Y-m-d", strtotime($order['data'][0]['BASIC_DATE']));

                    if($order['data'][0]['END_DATE'] <> "00000000")
                        $basic_date_end = date("Y-m-d", strtotime($order['data'][0]['END_DATE']));

                    if(!empty($user_plant)){
                        if($user_plant == $order['data'][0]['PLANT']){
                            $insert_data = array(
                                'production_order'      => $order['data'][0]['NO_ORDER'],
                                'order_description'     => $order_description,
                                'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                                'project_definition'    => $order['data'][0]['PROJ_DEF'],
                                'project_description'   => $order['data'][0]['PROJ_DESC'],
                                'created_date'          => $created_date,
                                'release_date'          => $release_date,
                                'posting_date'          => $posting_date,
                                'plant'                 => $order['data'][0]['PLANT'],
                                'order_type'            => $order['data'][0]['ORDER_TYPE'],
                                'material_code'         => $order['data'][0]['KODE_MAT'],
                                'material_description'  => $order['data'][0]['DESC_MAT'],
                                'uom'                   => $order['data'][0]['SATUAN'],
                                'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                                'sales_order'           => $order['data'][0]['SO_NUMBER'],
                                'basic_date_start'      => $basic_date_start,
                                'basic_date_end'        => $basic_date_end,
                                'created_by'            => $user
                            );

                            $insert = $this->prodmon_model->insert_header($insert_data);
                            if(!$insert){
                                array_push($err_message, "- Failed Input Header with Production Order ".$production_order);
                            }
                        } else {
                            array_push($err_message, "- Failed Input Header with Production Order. You are not authorized to input Production Order with Plant ".$order['data'][0]['PLANT']);
                        }
                    } else {
                        $insert_data = array(
                            'production_order'      => $order['data'][0]['NO_ORDER'],
                            'order_description'     => $order_description,
                            'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                            'project_definition'    => $order['data'][0]['PROJ_DEF'],
                            'project_description'   => $order['data'][0]['PROJ_DESC'],
                            'created_date'          => $created_date,
                            'release_date'          => $release_date,
                            'posting_date'          => $posting_date,
                            'plant'                 => $order['data'][0]['PLANT'],
                            'order_type'            => $order['data'][0]['ORDER_TYPE'],
                            'material_code'         => $order['data'][0]['KODE_MAT'],
                            'material_description'  => $order['data'][0]['DESC_MAT'],
                            'uom'                   => $order['data'][0]['SATUAN'],
                            'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                            'sales_order'           => $order['data'][0]['SO_NUMBER'],
                            'basic_date_start'      => $basic_date_start,
                            'basic_date_end'        => $basic_date_end,
                            'created_by'            => $user
                        );

                        $insert = $this->prodmon_model->insert_header($insert_data);
                        if(!$insert){
                            array_push($err_message, "- Failed Input Header with Production Order ".$production_order);
                        }
                    }
                }
            }else
                $order = $this->cek_order($production_order);

            $workshop = $row['workshop'];
            $box_no = $row['box_no'];
            $packing_code = $row['packing_code'];
            $grouping_code = $row['grouping_code'];
            $module = $row['module'];
            $batch = $row['batch'];
            $material = $row['material'];
            $weight = $row['weight'];
            $component_no = $row['component_no'];
            $component_name = $row['component_name'];
            $length = $row['length'];
            $width = $row['width'];
            $height = $row['height'];
            $code = $row['code'];
            $floor = $row['floor'];
            $quantity = str_replace(",", ".", $row['quantity']);

            $position = "2";

            $is_panel = $row['is_panel'];

            $line_item_no = $this->prodmon_model->get_latest_line_item($production_order);
            if(isset($line_item_no['line_item']))
                $line_item = $line_item_no['line_item']+1;
            else
                $line_item = 1;

            $data_header = $this->prodmon_model->get_header($production_order);
            $total_qty = $data_header['quantity'];
            $check_qty = $this->prodmon_model->count_quantity($production_order);
            $new_total_qty = $check_qty['total'] + $quantity;
            
            $insert_data = array(
                'production_order'                  => $production_order,
                'line_item'                         => $line_item,
                'workshop'                          => $workshop,
                'box_no'                            => $box_no,
                'packing_code'                      => $packing_code,
                'grouping_code'                     => $grouping_code,
                'module'                            => $module,
                'batch'                             => $batch,
                'material'                          => $material,
                'weight'                            => $weight,
                'component_no'                      => $component_no,
                'component_name'                    => $component_name,
                'length'                            => $length,
                'width'                             => $width,
                'height'                            => $height,
                'code'                              => $code,
                'floor'                             => $floor,
                'quantity'                          => $quantity,
                'position'                          => $position,
                'is_panel'                          => $is_panel,
                'created_by'                        => $user
            );

            $insert = $this->prodmon_model->insert_item($insert_data);
            if($insert <> 0){

                $position = "23";

                $insert_data_quantity = array(
                    'production_order_item_id'  => $insert,
                    'position'                  => $position,
                    'quantity'                  => $quantity,
                    'created_by'                => $user
                );

                $this->prodmon_model->insert_item_quantity($insert_data_quantity);

                $insert_data_history = array(
                    'production_order_item_id'  => $insert,
                    'position'                  => $position,
                    'created_by'                => $user
                );

                $insert = $this->prodmon_model->insert_item_history($insert_data_history);         
                $success = 1;        
            }
            else{
                array_push($err_message, "- Failed Input Some Item with Production Order ".$production_order);
            }

            $qty_pack = $row['qty_pack'];
            $product = $row['product'];
            $insert_data_packs = array(
                'production_order' => $production_order,
                'packing_code'     => $packing_code,
                'qty_pack'         => $qty_pack,
                'product'          => $product,
                'is_panel'         => 1,
                'created_by'       => $user,
                'created_at'       => date('Y-m-d H:i:s'),
            );
            $check_pack = $this->prodmon_model->check_production_order_pack($production_order, $packing_code, 'panel');
            if ($check_pack==0) {
                $this->prodmon_model->insert_production_order_pack($insert_data_packs);
            }
        }

        if(!empty($err_message) && $success == 0){
            $string_err = implode("<br>", $err_message);
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                <a class="close" data-dismiss="alert"></a>
                <strong>There are some error. </strong>'.$string_err.'
            </div>');  
        } elseif(!empty($err_message) && $success == 1){
            $string_err = implode("<br>", $err_message);
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Some Data successfully added <br>

                <strong>There are some error.<br></strong>'.$string_err.'
            </div>');  
        } else {
            $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Data successfully added
            </div>'); 
        }
        
        redirect('transaction/upload_panel');
    }

    function insert_history($line_id, $from_position, $position, $reason, $remark, $nik){

        $insert_data_history = array(
            'production_order_item_id'  => $line_id,
            'from_position'             => $from_position,
            'position'                  => $position,
            'reason'                    => $reason,
            'remark'                    => $remark,
            'created_by'                => $nik
        );

        $insert = $this->prodmon_model->insert_item_history($insert_data_history);
    }

    function insert_history_panel($line_id, $position, $quantity, $qc_scan, $nik){

        $insert_data_history = array(
            'production_order_item_id'  => $line_id,
            'position'                  => $position,
            'quantity'                  => $quantity,
            'qc_scan'                   => $qc_scan,
            'created_by'                => $nik
        );

        $insert = $this->prodmon_model->insert_item_history_panel($insert_data_history);
    }

    function scan_panel(){
        $nik = $this->session->userdata('nik_prodmon');

        $batch = $this->input->post('batch');
        $type = $this->input->post('type');
        $production_order = $this->input->post('production_order');
        $qrcode_text = $this->input->post('qrcode-text');
        $qty_panel = $this->input->post('qty_panel');
        $position = $this->input->post('position');
        $position_new = $this->input->post('position_new');

        if(strpos($qrcode_text, '.xxl') !== false || strpos($qrcode_text, '.xml') !== false){

            $format = explode("_", $qrcode_text);
            $production_order = $format[0];
            $component_no = $format[2];
            $component_no = str_replace('.xxl', '', $component_no);
            $component_no = str_replace('.xml', '', $component_no);

            $line_item = $this->prodmon_model->get_item_by_komponen($production_order, $component_no);
            $line_id = $line_item[0]['production_order_item_id'];

            $data_position = $this->prodmon_model->get_position_data($position);

            $check_station = $this->prodmon_model->count_quantity_station($line_id, $position);
            $check_history_panel = $this->prodmon_model->check_history_panel($line_id, $position);
            if($check_station['total'] > 0){
                $status = 0;

                if($position == "23" || $position == "25"){
                    $from_position_data = $this->prodmon_model->get_line_quantity_position($line_id, $position);
                    $from_position_id = $from_position_data['production_order_item_quantity_id'];
                    
                    if($type == "Normal"){
                        $quantity = $from_position_data['quantity'];
                        $quantity_left = $quantity;
                    }else{
                        $quantity = $qty_panel;
                        $quantity_left = $from_position_data['quantity']-$quantity;
                    }

                    if($quantity_left < 0){
                        $status = 2;
                    }else{
                        $check_position = $this->prodmon_model->get_line_quantity_position($line_id, $position_new);
                        if(sizeof($check_position) > 0){
                            $id = $check_position['production_order_item_quantity_id'];
                            $update_data = array(
                                'quantity'          => $check_position['quantity']+$quantity,
                                'remark'            => $remark,
                                'updated_by'        => $nik
                            );

                            $update = $this->prodmon_model->update_item_qty($id, $update_data);
                            if($update)
                                $status = 1;
                        } else {
                            $insert_data = array(
                                'production_order_item_id'  => $line_id,
                                'position'                  => $position_new,
                                'quantity'                  => $quantity,
                                'remark'                    => $remark,
                                'created_by'                => $nik
                            );

                            $insert = $this->prodmon_model->insert_item_qty($insert_data);
                            if($insert)
                                $status = 1;
                        }
                        //insert history
                        $this->insert_history($line_id, $position, $position_new, NULL, $remark, $nik);

                        $check_history_panel = $this->prodmon_model->check_history_panel($line_id, $position);
                        if($check_history_panel == "0")
                            $this->insert_history_panel($line_id, $position, $quantity, "0", $nik);
                        else{
                            $history_panel = $this->prodmon_model->get_history_panel_scan($line_id, $position);
                            $quantity_history_panel = $history_panel['quantity'];

                            $update_data = array(
                                'quantity'          => $quantity_history_panel+$quantity,
                                'qc_scan'           => "0"
                            );

                            $update = $this->prodmon_model->update_history_panel($history_panel['production_order_item_panel_history_id'], $update_data);
                        }

                        if($type == "Normal"){
                            $update_data = array(
                                'quantity'          => "0",
                                'updated_by'        => $nik
                            );
                        }else{
                            $update_data = array(
                                'quantity'          => $quantity_left,
                                'updated_by'        => $nik
                            );
                        }

                        $update = $this->prodmon_model->update_item_qty($from_position_id, $update_data);
                    }
                }elseif($position == "24"){
                    $check_history_panel = $this->prodmon_model->check_history_panel($line_id, $position);
                    if($check_history_panel == "0")
                        $this->insert_history_panel($line_id, $position, NULL, "1", $nik);
                    else{
                        $history_panel = $this->prodmon_model->get_history_panel_scan($line_id, $position);
                        $quantity_history_panel = $history_panel['quantity'];

                        $update_data = array(
                            'qc_scan'           => "1"
                        );

                        $update = $this->prodmon_model->update_history_panel($history_panel['production_order_item_panel_history_id'], $update_data);
                    }
                    $status = 1;
                }

                if($status == 1){
                    $this->session->set_flashdata('msg','<div class="alert alert-success">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Success!</strong> Success Updated Data Status Barang
                     </div>');
                }elseif($status == 2){
                    $this->session->set_flashdata('msg','<div class="alert alert-error">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Error!</strong> Failed Update Data, insufficient quantity. Quantity available is '.$from_position_data['quantity'].'
                     </div>');
                }else{
                    $this->session->set_flashdata('msg','<div class="alert alert-error">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Error!</strong> Failed Update Data, new status not valid
                     </div>');
                } 
            }elseif($check_station['total'] == 0 && $check_history_panel == 0){
                $this->session->set_flashdata('msg','<div class="alert alert-error">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Error!</strong> No data found in '.$data_position['process'].'
                 </div>');
            }elseif($check_station['total'] == 0 && $check_history_panel > 0){
                $this->session->set_flashdata('msg','<div class="alert alert-error">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Error!</strong> All quantity has been scanned
                 </div>');
            }
        }else{
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                 <a class="close" data-dismiss="alert"></a>
                 <strong>Error!</strong> Wrong QR Code format
             </div>');
        }

        if($position == "23")
            redirect('transaction/station_a/'.$batch.'/'.$type.'/'.$production_order);
        elseif($position == "24")
            redirect('transaction/station_b/'.$batch.'/'.$type.'/'.$production_order);
        elseif($position == "25")
            redirect('transaction/station_c/'.$batch.'/'.$type.'/'.$production_order);
    }

    function scan_packing_code(){
        $nik = $this->session->userdata('nik_prodmon');

        $batch = $this->input->post('batch');
        $packing_code = $this->input->post('qrcode-text');
        $from_position = $this->input->post('from_position');
        $position = $this->input->post('position');

        $format = explode("-", $packing_code);
        $production_order = $format[0];

        $data_packing_code = $this->prodmon_model->get_packing_code_by_id($production_order, $packing_code, $from_position);

        if(sizeof($data_packing_code) > 0){
            // if($data_packing_code['is_panel'] == "0")
            //     $status = 1;
            // elseif($data_packing_code['is_panel'] == "1"){
            //     if($data_packing_code['total_scan'] == $data_packing_code['total_packing_code'])
            //         $status = 1;
            // }

            // if($status == 1){

                $panel = $this->prodmon_model->get_panel_by_packing_code($packing_code);
                
                foreach($panel as $row){
                    if($row['is_panel'] == "0")
                        $quantity = $row['quantity'];
                    else
                        $quantity = $row['qty_available'];
                    
                    $this->insert_history($row['production_order_item_id'], $position, $position, NULL, $remark, $nik);

                    $check_history_panel = $this->prodmon_model->get_history_panel_scan($row['production_order_item_id'], $position);
                    if(sizeof($check_history_panel) == 0){
                        //insert history
                        $this->insert_history_panel($row['production_order_item_id'], $position, $quantity, "0", $nik);
                    }else{
                        $history_panel = $this->prodmon_model->get_history_panel_scan($row['production_order_item_id'], "26");
                        
                        $update_data = array(
                            'quantity'          => $quantity
                        );

                        $update = $this->prodmon_model->update_history_panel($history_panel['production_order_item_panel_history_id'], $update_data);
                    }
                    
                }

                $this->session->set_flashdata('msg','<div class="alert alert-success">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Success!</strong> Success Updated Data Status Barang
                 </div>');

                $data=array(
                    'result' => "true",
                    'status_no' => "0",
                    'message' => "Success Updated Data Status Barang"
                );  
            // }else{
            //     $this->session->set_flashdata('msg','<div class="alert alert-error">
            //          <a class="close" data-dismiss="alert"></a>
            //          <strong>Error!</strong> Failed Update Data, Ada yang Belum Scan Station C - Packing
            //      </div>');
            // } 
        }else{
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                 <a class="close" data-dismiss="alert"></a>
                 <strong>Error!</strong> Failed Update Data, Packing Code not found
             </div>');
        }

        redirect('transaction/station_d/'.$batch);
    }

    function station_a($batch = NULL, $type = NULL, $production_order = NULL){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        
        $this->session->set_userdata('redirect_url', '/transaction/station_a');
        $client = $this->gdriveupload->getClient();

        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');
        $hide_header = 1;

        if(($role == "1" && in_array("23", $user_role_process)) || ($this->session->userdata('role_prodmon') == '5') || $this->session->userdata('role_prodmon') == '6'){
            $qrcode_text = $this->input->get('qrcode-text');

            if($production_order == ""){
                $hide_header = 0;
                $production_order = $this->input->get('production_order');
            }

            if($batch == "")
                $batch = $this->input->get('batch');

            if($type == ""){
                $type = $this->input->get('type');
            }else{
                $type = urldecode($type);
            }

            $process = "23";

            if($production_order <> ""){

                $header = $this->prodmon_model->get_header($production_order);
                $panel_process_type = $header['panel_process_type'];

                if($panel_process_type == ""){
                    $update_data = array(
                        'panel_process_type' => $type
                    );

                    $update = $this->prodmon_model->update_header($production_order, $update_data);
                }elseif($type <> $panel_process_type){
                    $this->session->set_flashdata('msg','<div class="alert alert-error">
                        <a class="close" data-dismiss="alert"></a>
                        <strong>Error!</strong> Production Order '.$production_order.' can not use '.$type.' type
                    </div>');

                    redirect('transaction/station_a');
                }

                $data['list_production_order'] = $this->prodmon_model->get_production_order_by_batch($batch);
                $data['module'] = $this->prodmon_model->get_module_by_production_order($production_order, $process);

                $total_module = 0;
                $total_module_scan = 0;
                foreach($data['module'] as $row){
                    if($row['total_scan'] == $row['total_panel'])
                        $total_module_scan ++;
                    $total_module++;
                }

                $data['total_module'] = $total_module;
                $data['total_module_scan'] = $total_module_scan;

                $panel = $this->prodmon_model->get_panel_by_production_order($production_order, $process);

                $total_component = 0;
                $total_component_scan = 0;
                foreach($panel as $row){
                    if($row['total_scan'] == $row['quantity'])
                        $total_component_scan ++;
                    // $total_component++;
                    $total_component += $row['quantity'];
                }

                $data['total_component'] = $total_component;
                $data['total_component_scan'] = $total_component_scan;
            }

            $data['batch'] = $batch;
            $data['type'] = $type;
            $data['production_order'] = $production_order;
            $data['hide_header'] = $hide_header;

            $data['main_content']='transaction/station_a';
            $this->load->view('template/main', $data);
        }else{
            redirect('site/index');
        }
    }

    function station_a_panel($production_order, $module){
        
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        
        $process = "23";
        $data['panel'] = $this->prodmon_model->get_panel_by_module($production_order, $module, $process);

        $data['module'] = $module;

        $this->load->view('transaction/station_a_panel', $data);
    }

    function station_b($batch = NULL, $type = NULL, $production_order = NULL){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $this->session->set_userdata('redirect_url', '/transaction/station_b');
        $client = $this->gdriveupload->getClient();

        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');
        $hide_header = 1;


        if(($role == "1" && in_array("24", $user_role_process)) || ($role == "2") || ($this->session->userdata('role_prodmon') == '5') || $this->session->userdata('role_prodmon') == '6'){
            $qrcode_text = $this->input->get('qrcode-text');

            if($production_order == ""){
                $hide_header = 0;
                $production_order = $this->input->get('production_order');
            }

            if($batch == "")
                $batch = $this->input->get('batch');

            if($type == ""){
                $type = $this->input->get('type');
            }else{
                $type = urldecode($type);
            }

            $process = "24";

            if($production_order <> ""){

                $header = $this->prodmon_model->get_header($production_order);
                $panel_process_type = $header['panel_process_type'];

                if($panel_process_type == ""){
                    $update_data = array(
                        'panel_process_type' => $type
                    );

                    $update = $this->prodmon_model->update_header($production_order, $update_data);
                }elseif($type <> $panel_process_type){
                    $this->session->set_flashdata('msg','<div class="alert alert-error">
                        <a class="close" data-dismiss="alert"></a>
                        <strong>Error!</strong> Production Order '.$production_order.' can not use '.$type.' type
                    </div>');

                    redirect('transaction/station_b');
                }

                $data['list_production_order'] = $this->prodmon_model->get_production_order_by_batch($batch);
                $data['module'] = $this->prodmon_model->get_module_qc_by_production_order($production_order, $process);

                $total_module = 0;
                $total_module_scan = 0;
                foreach($data['module'] as $row){
                    if($row['total_scan'] == $row['total_panel'])
                        $total_module_scan ++;
                    $total_module++;
                }

                $data['total_module'] = $total_module;
                $data['total_module_scan'] = $total_module_scan;

                $panel = $this->prodmon_model->get_panel_qc_by_production_order($production_order, $process);

                $total_component = 0;
                $total_component_scan = 0;
                foreach($panel as $row){
                    if($row['total_scan'] == $row['quantity'])
                        $total_component_scan ++;
                    // $total_component++;
                    $total_component += $row['quantity'];
                }

                $data['total_component'] = $total_component;
                $data['total_component_scan'] = $total_component_scan;
            }

            $data['batch'] = $batch;
            $data['type'] = $type;
            $data['production_order'] = $production_order;
            $data['hide_header'] = $hide_header;

            $data['main_content']='transaction/station_b';
            $this->load->view('template/main', $data);
        }else{
            redirect('site/index');
        }
    }

    function station_b_panel($production_order, $batch, $type, $line_item, $module){
        
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        
        $process = "24";
        $data['panel'] = $this->prodmon_model->get_panel_by_module($production_order, $module, $process);

        foreach($data['panel'] as $key => $row){
            $check = $this->prodmon_model->check_status_qc($row['production_order_item_id'], $process);

            if($check['position'] == "23")
                $approval = "-1";
            elseif($check['position'] == "24")
                $approval = "0";
            else
                $approval = "1";

            $data['panel'][$key]['status'] = $approval;
            $data['panel'][$key]['qty_remaining'] = $check['qty_remaining'];
            $data['panel'][$key]['qc_scan'] = $check['qc_scan'];
        }

        $data['batch'] = $batch;
        $data['type'] = $type;
        $data['production_order'] = $production_order;
        $data['line_item'] = $line_item;
        $data['module'] = $module;

        $this->load->view('transaction/station_b_panel', $data);
    }

    function station_b_qc($production_order, $batch, $type, $line_item, $from_approval){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');

        $process = "24";
        $check = $this->prodmon_model->check_status_qc($line_item, $process);
        // var_dump($check);
        // exit();

        if($from_approval <> ""){
            if($check['position'] == "23")
                $approval = "-1";
            elseif($check['position'] == "25" && $check['qty_remaining'] == 0)
                $approval = "1";
        }else{
            if($check['position'] <> "24" && $check['qty_remaining'] == 0)
                $approval = "0";
            else{
                if($check['total_scan'] == "0")
                    $approval = "-2";
            }
        }

        $item = $this->prodmon_model->get_item_by_id($line_item);

        $data['reason'] = $this->prodmon_model->get_panel_remark();

        $data['batch'] = $batch;
        $data['type'] = $type;
        $data['production_order'] = $production_order;
        $data['line_item'] = $line_item;
        $data['component_no'] = $item['component_no'];
        $data['approval'] = $approval;

        $data['main_content']='transaction/station_b_qc';
        $this->load->view('template/main', $data);
    }

    function approve_qc(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');

        $remark = $this->input->post('remark');
        $production_order = $this->input->post('production_order');
        $batch = $this->input->post('batch');
        $type = $this->input->post('type');
        $line_item = $this->input->post('line_item');
        
        $position = "24";
        $position_new = "25";
        $status = 0;

        $from_position_data = $this->prodmon_model->get_line_quantity_position($line_item, $position);
        $from_position_id = $from_position_data['production_order_item_quantity_id'];
        $quantity = $from_position_data['quantity'];
        
        $check_position = $this->prodmon_model->get_line_quantity_position($line_item, $position_new);
        if(sizeof($check_position) > 0){
            $id = $check_position['production_order_item_quantity_id'];
            $update_data = array(
                'quantity'          => $quantity,
                'remark'            => $remark,
                'updated_by'        => $user
            );

            $update = $this->prodmon_model->update_item_qty($id, $update_data);
            if($update)
                $status = 1;
        } else {
            $insert_data = array(
                'production_order_item_id'  => $line_item,
                'position'                  => $position_new,
                'quantity'                  => $quantity,
                'remark'                    => $remark,
                'created_by'                => $user
            );

            $insert = $this->prodmon_model->insert_item_qty($insert_data);
            if($insert)
                $status = 1;
        }

        if($status == 1){
            $insert_data_history = array(
                'production_order_item_id'  => $line_item,
                'from_position'             => $position,
                'position'                  => $position_new,
                'remark'                    => $remark,
                'created_by'                => $user
            );

            $insert = $this->prodmon_model->insert_item_history($insert_data_history);
            
            $history_panel = $this->prodmon_model->get_history_panel_scan($line_item, $position);
            $quantity_history_panel = $history_panel['quantity'];

            $update_data = array(
                'quantity'          => $quantity
            );

            $update = $this->prodmon_model->update_history_panel($history_panel['production_order_item_panel_history_id'], $update_data);

            $update_data = array(
                'quantity'          => "0",
                'updated_by'        => $user
            );

            $update = $this->prodmon_model->update_item_qty($from_position_id, $update_data);

            $update_data = array(
                'flag_reject_qc' => 0,
            );

            $update = $this->prodmon_model->update_item($line_item, $update_data);

        }

        redirect('transaction/station_b_qc/'.$production_order."/".$batch."/".$type."/".$line_item."/1");
    }

    function reject_approval(){

        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');

        $reason = $this->input->post('reason');
        $remark = $this->input->post('remark');
        $production_order = $this->input->post('production_order');
        $batch = $this->input->post('batch');
        $line_item = $this->input->post('line_item');
        
        $position = "24";
        $position_new = "23";
        $status = 0;

        $from_position_data = $this->prodmon_model->get_line_quantity_position($line_item, $position);
        $from_position_id = $from_position_data['production_order_item_quantity_id'];
        $quantity = $from_position_data['quantity'];

        $check_position = $this->prodmon_model->get_line_quantity_position($line_item, $position_new);
        if(sizeof($check_position) > 0){
            $id = $check_position['production_order_item_quantity_id'];
            $update_data = array(
                'quantity'          => $quantity,
                'reason'            => $reason,
                'remark'            => $remark,
                'updated_by'        => $user
            );

            $update = $this->prodmon_model->update_item_qty($id, $update_data);
            if($update)
                $status = 1;
        } else {
            $insert_data = array(
                'production_order_item_id'  => $line_item,
                'position'                  => $position_new,
                'quantity'                  => $quantity,
                'reason'                    => $reason,
                'remark'                    => $remark,
                'created_by'                => $user
            );

            $insert = $this->prodmon_model->insert_item_qty($insert_data);
            if($insert)
                $status = 1;
        }

        if($status == 1){
            $insert_data_history = array(
                'production_order_item_id'  => $line_item,
                'from_position'             => $position,
                'position'                  => $position_new,
                'reason'                    => $reason,
                'remark'                    => $remark,
                'created_by'                => $user
            );

            $insert = $this->prodmon_model->insert_item_history($insert_data_history);
            
            $update_data = array(
                'quantity'          => "0",
                'updated_by'        => $user
            );

            $update = $this->prodmon_model->update_item_qty($from_position_id, $update_data);

            $this->prodmon_model->delete_history_panel($line_item, $position);
            $this->prodmon_model->delete_history_panel($line_item, $position_new);

            $update_data = array(
                'flag_reject_qc' => 1,
            );

            $update = $this->prodmon_model->update_item($line_item, $update_data);

        }
    }

    function save_approval(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $nik = $this->session->userdata('nik_prodmon');

        $qty_confirm = $this->input->post('qty_confirm');
        $qty_reject = $this->input->post('qty_reject');
        $reason = $this->input->post('reason');
        $remark = $this->input->post('remark');
        $production_order = $this->input->post('production_order');
        $batch = $this->input->post('batch');
        $type = $this->input->post('type');
        $line_id = $this->input->post('line_item');

        if($qty_confirm <> "" && $qty_confirm > 0){
            $position = "24";
            $position_new = "25";
            $status = 0;

            $from_position_data = $this->prodmon_model->get_line_quantity_position($line_id, $position);
            $from_position_id = $from_position_data['production_order_item_quantity_id'];

            if($type == "Normal"){
                $quantity = $from_position_data['quantity'];
                $quantity_left = $quantity;
            }else{
                $quantity = $qty_confirm;
                $quantity_left = $from_position_data['quantity']-$quantity;
            }

            if($quantity_left < 0){
                $status = 2;
            }else{
                $check_position = $this->prodmon_model->get_line_quantity_position($line_id, $position_new);
                if(sizeof($check_position) > 0){
                    $id = $check_position['production_order_item_quantity_id'];
                    $update_data = array(
                        'quantity'          => $check_position['quantity']+$quantity,
                        'reason'            => $reason,
                        'remark'            => $remark,
                        'updated_by'        => $nik
                    );

                    $update = $this->prodmon_model->update_item_qty($id, $update_data);
                    if($update)
                        $status = 1;
                } else {
                    $insert_data = array(
                        'production_order_item_id'  => $line_id,
                        'position'                  => $position_new,
                        'quantity'                  => $quantity,
                        'reason'                    => $reason,
                        'remark'                    => $remark,
                        'created_by'                => $nik
                    );

                    $insert = $this->prodmon_model->insert_item_qty($insert_data);
                    if($insert)
                        $status = 1;
                }
                //insert history
                $this->insert_history($line_id, $position, $position_new, $reason, $remark, $nik);

                $history_panel = $this->prodmon_model->get_history_panel_scan($line_id, $position);
                $quantity_history_panel = $history_panel['quantity'];

                $update_data = array(
                    'quantity'          => $quantity_history_panel+$quantity
                );

                $update = $this->prodmon_model->update_history_panel($history_panel['production_order_item_panel_history_id'], $update_data);

                $update_data = array(
                    'quantity'          => $quantity_left,
                    'updated_by'        => $nik
                );

                $update = $this->prodmon_model->update_item_qty($from_position_id, $update_data);
            }

            if($status == 1){
                $msg_confirm = '<div class="alert alert-success">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Success!</strong> Success Updated Data Confirm Qty
                 </div>';
            }elseif($status == 2){
                $msg_confirm = '<div class="alert alert-error">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Error!</strong> Failed Update Data Confirm Qty, insufficient quantity. Quantity available is '.$from_position_data['quantity'].'
                 </div>';
            }else{
                $msg_confirm = '<div class="alert alert-error">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Error!</strong> Failed Update Data Confirm Qty, new status not valid
                 </div>';
            }
        }

        if($qty_reject <> "" && $qty_reject > 0){
            $position = "24";
            $position_new = "23";
            $status = 0;

            $from_position_data = $this->prodmon_model->get_line_quantity_position($line_id, $position);
            $from_position_id = $from_position_data['production_order_item_quantity_id'];
            
            if($type == "Normal"){
                $quantity = $from_position_data['quantity'];
                $quantity_left = $quantity;
            }else{
                $quantity = $qty_confirm;
                $quantity_left = $from_position_data['quantity']-$qty_reject;
            }

            if($quantity_left < 0){
                $status = 2;
            }else{
                $check_position = $this->prodmon_model->get_line_quantity_position($line_id, $position_new);
                if(sizeof($check_position) > 0){
                    $id = $check_position['production_order_item_quantity_id'];
                    $update_data = array(
                        'quantity'          => $check_position['quantity']+$qty_reject,
                        'reason'            => $reason,
                        'remark'            => $remark,
                        'updated_by'        => $nik
                    );

                    $update = $this->prodmon_model->update_item_qty($id, $update_data);
                    if($update)
                        $status = 1;
                }
            }

            if($status == 1){
                $insert_data_history = array(
                    'production_order_item_id'  => $line_id,
                    'from_position'             => $position,
                    'position'                  => $position_new,
                    'reason'                    => $reason,
                    'remark'                    => $remark,
                    'created_by'                => $nik
                );

                $insert = $this->prodmon_model->insert_item_history($insert_data_history);

                $update_data = array(
                    'quantity'          => $from_position_data['quantity']-$qty_reject,
                    'updated_by'        => $nik
                );

                $update = $this->prodmon_model->update_item_qty($from_position_id, $update_data);

                $history_panel = $this->prodmon_model->get_history_panel_scan($line_id, $position_new);
                $quantity_history_panel = $history_panel['quantity'];

                $update_data = array(
                    'quantity'          => $quantity_history_panel-$qty_reject
                );

                $update = $this->prodmon_model->update_history_panel($history_panel['production_order_item_panel_history_id'], $update_data);

                $update_data = array(
                    'flag_reject_qc' => 1,
                );

                $update = $this->prodmon_model->update_item($line_id, $update_data);

                $msg_reject = '<div class="alert alert-success">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Success!</strong> Success Updated Data Reject Qty
                 </div>';
            }elseif($status == 2){
                $msg_reject = '<div class="alert alert-error">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Error!</strong> Failed Update Data Reject Qty, insufficient quantity. Quantity available is '.$from_position_data['quantity'].'
                 </div>';
            }else{
                $msg_reject = '<div class="alert alert-error">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Error!</strong> Failed Update Data Reject Qty, new status not valid
                 </div>';
            }
        }

        $this->session->set_flashdata('msg', $msg_confirm.$msg_reject);

        redirect('transaction/station_b_qc/'.$production_order."/".$batch."/".$type."/".$line_id."/1");
    }

    function station_c($batch = NULL, $type = NULL, $production_order = NULL){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $this->session->set_userdata('redirect_url', '/transaction/station_c');
        $client = $this->gdriveupload->getClient();

        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');
        $hide_header = 1;


        if(($role == "1" && in_array("25", $user_role_process)) || ($this->session->userdata('role_prodmon') == '5') || $this->session->userdata('role_prodmon') == '6'){
            $qrcode_text = $this->input->get('qrcode-text');

            if($production_order == ""){
                $hide_header = 0;
                $production_order = $this->input->get('production_order');
            }

            if($batch == "")
                $batch = $this->input->get('batch');

            if($type == ""){
                $type = $this->input->get('type');
            }else{
                $type = urldecode($type);
            }

            $process = "25";

            if($production_order <> ""){

                $header = $this->prodmon_model->get_header($production_order);
                $panel_process_type = $header['panel_process_type'];

                if($panel_process_type == ""){
                    $update_data = array(
                        'panel_process_type' => $type
                    );

                    $update = $this->prodmon_model->update_header($production_order, $update_data);
                }elseif($type <> $panel_process_type){
                    $this->session->set_flashdata('msg','<div class="alert alert-error">
                        <a class="close" data-dismiss="alert"></a>
                        <strong>Error!</strong> Production Order '.$production_order.' can not use '.$type.' type
                    </div>');

                    redirect('transaction/station_c');
                }

                $data['list_production_order'] = $this->prodmon_model->get_production_order_by_batch($batch);
                $data['module'] = $this->prodmon_model->get_module_by_production_order($production_order, $process);

                $total_module = 0;
                $total_module_scan = 0;

                foreach($data['module'] as $key => $row){
                    $packing_code = $this->prodmon_model->get_packing_code_by_module($production_order, $row['module'], $process);

                    // $total_packing_code = sizeof($packing_code);
                    // $data['module'][$key]['total_packing_code'] = $total_packing_code;

                    // $total_packing_code_scan = 0;
                    // foreach($packing_code as $row2){
                    //     if($row2['total_scan'] == $row2['total_packing_code'])
                    //         $total_packing_code_scan++;
                    // }

                    //$data['module'][$key]['total_packing_code_scan'] = $total_packing_code_scan;

                    $total_packing_code = 0;
                    $total_packing_code_scan = 0;
                    foreach($packing_code as $row2){
                        $total_packing_code_scan += $row2['total_scan'];
                        $total_packing_code += $row2['total_packing_code'];
                    }
                    
                    $data['module'][$key]['total_packing_code'] = $total_packing_code;
                    $data['module'][$key]['total_packing_code_scan'] = $total_packing_code_scan;

                    $grouping_code = $row['grouping_code'];

                    if($total_packing_code == $total_packing_code_scan)
                        $total_module_scan ++;

                    $total_module++;
                }

                $data['total_module'] = $total_module;
                $data['total_module_scan'] = $total_module_scan;

                $panel = $this->prodmon_model->get_panel_by_production_order($production_order, $process);

                $total_component = 0;
                $total_component_scan = 0;
                foreach($panel as $row){
                    if($row['total_scan'] == $row['quantity'])
                        $total_component_scan ++;
                    // $total_component++;
                    $total_component += $row['quantity'];
                }

                $data['total_component'] = $total_component;
                $data['total_component_scan'] = $total_component_scan;
            }

            $data['batch'] = $batch;
            $data['type'] = $type;
            $data['production_order'] = $production_order;
            $data['grouping_code'] = $grouping_code;
            $data['hide_header'] = $hide_header;

            $data['main_content']='transaction/station_c';
            $this->load->view('template/main', $data);
        }else{
            redirect('site/index');
        }
    }

    function station_c_panel($production_order, $module){
        
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        
        $process = "25";
        $data['packing_code'] = $this->prodmon_model->get_packing_code_by_module($production_order, $module, $process);

        $data['module'] = $module;

        $this->load->view('transaction/station_c_panel', $data);
    }

    function upload_non_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $data['main_content']='transaction/upload_non_panel';
        $this->load->view('template/main', $data);
    }

    function upload_data_non_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $this->prodmon_model->delete_temp($user);
        
        $file = $_FILES['excel']['tmp_name'];
        //load the excel library
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        $rows = $objPHPExcel->getActiveSheet()->getHighestRow();

        $delete_temp = $this->prodmon_model->delete_temp($user);

        for ($i = 6; $i <= $rows; $i++) {
            $production_order = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->getValue();
            $workshop = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i)->getValue();
            $box_no = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i)->getValue();
           
            if(strlen($box_no) < 2)
               $box_no = "0".$box_no;

            $packing_code = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i)->getValue();
            $grouping_code = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $i)->getValue();
            $batch = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i)->getValue();
            $material = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $i)->getValue();
            $description = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $i)->getValue();
            $quantity = str_replace(",", ".", $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $i)->getValue());
            $uom = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(9, $i)->getValue();
            $qty_pack = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(10, $i)->getValue();

            if($production_order <> ""){
                $order = $this->cek_order($production_order);

                if($order['result'] == "true"){
                    $message = "OK";   
                } else {
                    $message = "Production Order not registered at SAP";
                }

                $insert_data = array(
                    'production_order'   => $production_order,
                    'workshop'           => $workshop,
                    'box_no'             => $box_no,
                    'packing_code'       => $packing_code,
                    'grouping_code'      => $grouping_code,
                    'batch'              => $batch,
                    'material'           => $material,
                    'description'        => $description,
                    'quantity'           => $quantity,
                    'uom'                => $uom,
                    'is_panel'           => "0",
                    'message'            => $message,
                    'created_by'         => $user,
                    'qty_pack'           => $qty_pack
                );

                $insert = $this->prodmon_model->insert_temp_upload($insert_data);
            }
        }
        $filename = 'upload-data-nonpanel-' . date('YmdHis');
        $uploaded = $this->gdriveupload->uploadExcelToServer($file, $filename);
        redirect('transaction/temp_non_panel');
    }

    function temp_non_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $data['production_order'] = $this->prodmon_model->get_data_temp($user);

        $data['main_content']='transaction/temp_non_panel';
        $this->load->view('template/main', $data);
    }

    function delete_temp_non_panel(){
        $id = $this->input->post('id');
        $delete = $this->prodmon_model->delete_temp_by_id($id);

        redirect('transaction/temp_non_panel');
    }

    function input_temp_non_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');

        $err_message = array();
        $success = 0;
        $list_production_order = $this->prodmon_model->get_data_temp($user);
        foreach($list_production_order as $row){
            $production_order = $row['production_order'];
            $order_description = str_replace("'", "", $row['order_description']);

            $check = $this->prodmon_model->count_production_order($production_order);
            if($check == 0){
                $order = $this->cek_order($production_order);

                if($order['result'] == "true"){
                    $current_date = date("Y-m-d H:i:s");

                    if($order['data'][0]['TGL_CRTD'] <> "00000000")
                        $created_date = date("Y-m-d", strtotime($order['data'][0]['TGL_CRTD']));

                    if($order['data'][0]['TGL_REL'] <> "00000000")
                        $release_date = date("Y-m-d", strtotime($order['data'][0]['TGL_REL']));

                    if($order['data'][0]['TGL_POSTING'] <> "00000000")
                        $posting_date = date("Y-m-d", strtotime($order['data'][0]['TGL_POSTING']));
                    
                    if($order['data'][0]['BASIC_DATE'] <> "00000000")
                        $basic_date_start = date("Y-m-d", strtotime($order['data'][0]['BASIC_DATE']));

                    if($order['data'][0]['END_DATE'] <> "00000000")
                        $basic_date_end = date("Y-m-d", strtotime($order['data'][0]['END_DATE']));

                    if(!empty($user_plant)){
                        if($user_plant == $order['data'][0]['PLANT']){
                            $insert_data = array(
                                'production_order'      => $order['data'][0]['NO_ORDER'],
                                'order_description'     => $order_description,
                                'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                                'project_definition'    => $order['data'][0]['PROJ_DEF'],
                                'project_description'   => $order['data'][0]['PROJ_DESC'],
                                'created_date'          => $created_date,
                                'release_date'          => $release_date,
                                'posting_date'          => $posting_date,
                                'plant'                 => $order['data'][0]['PLANT'],
                                'order_type'            => $order['data'][0]['ORDER_TYPE'],
                                'material_code'         => $order['data'][0]['KODE_MAT'],
                                'material_description'  => $order['data'][0]['DESC_MAT'],
                                'uom'                   => $order['data'][0]['SATUAN'],
                                'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                                'sales_order'           => $order['data'][0]['SO_NUMBER'],
                                'basic_date_start'      => $basic_date_start,
                                'basic_date_end'        => $basic_date_end,
                                'created_by'            => $user
                            );

                            $insert = $this->prodmon_model->insert_header($insert_data);
                            if(!$insert){
                                array_push($err_message, "- Failed Input Header with Production Order ".$production_order);
                            }
                        } else {
                            array_push($err_message, "- Failed Input Header with Production Order. You are not authorized to input Production Order with Plant ".$order['data'][0]['PLANT']);
                        }
                    } else {
                        $insert_data = array(
                            'production_order'      => $order['data'][0]['NO_ORDER'],
                            'order_description'     => $order_description,
                            'quantity'              => str_replace(",", ".", $order['data'][0]['QTY']),
                            'project_definition'    => $order['data'][0]['PROJ_DEF'],
                            'project_description'   => $order['data'][0]['PROJ_DESC'],
                            'created_date'          => $created_date,
                            'release_date'          => $release_date,
                            'posting_date'          => $posting_date,
                            'plant'                 => $order['data'][0]['PLANT'],
                            'order_type'            => $order['data'][0]['ORDER_TYPE'],
                            'material_code'         => $order['data'][0]['KODE_MAT'],
                            'material_description'  => $order['data'][0]['DESC_MAT'],
                            'uom'                   => $order['data'][0]['SATUAN'],
                            'sap_uom'               => $order['data'][0]['SATUAN_SAP'],
                            'sales_order'           => $order['data'][0]['SO_NUMBER'],
                            'basic_date_start'      => $basic_date_start,
                            'basic_date_end'        => $basic_date_end,
                            'created_by'            => $user
                        );

                        $insert = $this->prodmon_model->insert_header($insert_data);
                        if(!$insert){
                            array_push($err_message, "- Failed Input Header with Production Order ".$production_order);
                        }
                    }
                }
            }else
                $order = $this->cek_order($production_order);

            $workshop = $row['workshop'];
            $box_no = $row['box_no'];
            $packing_code = $row['packing_code'];
            $grouping_code = $row['grouping_code'];
            $batch = $row['batch'];
            $material = $row['material'];
            $description = $row['description'];
            $quantity = str_replace(",", ".", $row['quantity']);
            $uom = $row['uom'];
            $is_panel = $row['is_panel'];
            
            $position = "2";

            $line_item_no = $this->prodmon_model->get_latest_line_item($production_order);
            if(isset($line_item_no['line_item']))
                $line_item = $line_item_no['line_item']+1;
            else
                $line_item = 1;

            $data_header = $this->prodmon_model->get_header($production_order);
            $total_qty = $data_header['quantity'];
            $check_qty = $this->prodmon_model->count_quantity($production_order);
            $new_total_qty = $check_qty['total'] + $quantity;
          
            $insert_data = array(
                'production_order'                  => $production_order,
                'line_item'                         => $line_item,
                'workshop'                          => $workshop,
                'box_no'                            => $box_no,
                'packing_code'                      => $packing_code,
                'grouping_code'                     => $grouping_code,
                'batch'                             => $batch,
                'material'                          => $material,
                'description'                       => $description,
                'quantity'                          => $quantity,
                'uom'                               => $uom,
                'position'                          => $position,
                'is_panel'                          => $is_panel,
                'created_by'                        => $user
            );

            $insert = $this->prodmon_model->insert_item($insert_data);
            if($insert <> 0){

                $position = "26";

                $insert_data_quantity = array(
                    'production_order_item_id'  => $insert,
                    'position'                  => $position,
                    'quantity'                  => $quantity,
                    'created_by'                => $user
                );

                $this->prodmon_model->insert_item_quantity($insert_data_quantity);

                $insert_data_history = array(
                    'production_order_item_id'  => $insert,
                    'position'                  => $position,
                    'created_by'                => $user
                );

                $insert = $this->prodmon_model->insert_item_history($insert_data_history);         
                $success = 1;        
            }
            else{
                array_push($err_message, "- Failed Input Some Item with Production Order ".$production_order);
            }

            $qty_pack = $row['qty_pack'];
            $insert_data_packs = array(
                'production_order' => $production_order,
                'packing_code'     => $packing_code,
                'qty_pack'         => $qty_pack,
                'is_panel'         => 0,
                'created_by'       => $user,
                'created_at'       => date('Y-m-d H:i:s'),
            );
            $check_pack = $this->prodmon_model->check_production_order_pack($production_order, $packing_code, 'non-panel');
            if ($check_pack==0) {
                $this->prodmon_model->insert_production_order_pack($insert_data_packs);
            }
        }

        if(!empty($err_message) && $success == 0){
            $string_err = implode("<br>", $err_message);
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                <a class="close" data-dismiss="alert"></a>
                <strong>There are some error. </strong>'.$string_err.'
            </div>');  
        } elseif(!empty($err_message) && $success == 1){
            $string_err = implode("<br>", $err_message);
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Some Data successfully added <br>

                <strong>There are some error.<br></strong>'.$string_err.'
            </div>');  
        } else {
            $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Data successfully added
            </div>'); 
        }
        
        redirect('transaction/upload_non_panel');
    }

    function station_d($batch = NULL){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $this->session->set_userdata('redirect_url', '/transaction/station_d');
        $client = $this->gdriveupload->getClient();

        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');
        $hide_header = 1;

        if(($role == "1" && in_array("26", $user_role_process)) || ($this->session->userdata('role_prodmon') == '5') || $this->session->userdata('role_prodmon') == '6'){
            
            if($batch == ""){
                $hide_header = 0;
                $batch = $this->input->get('batch');
            }
            
            if($batch <> ""){
                $data['production_order'] = $this->prodmon_model->get_production_order_scan_by_batch($batch);

                $total_prod = 0;
                $total_prod_scan = 0;
                foreach($data['production_order'] as $row){
                    if($row['total_internal'] == $row['total_internal_scan'] && $row['total_eksternal'] == $row['total_eksternal_scan'])
                        $total_prod_scan++;

                    $total_prod++;
                }
            }

            $data['batch'] = $batch;
            $data['total_prod'] = $total_prod;
            $data['total_prod_scan'] = $total_prod_scan;
            $data['hide_header'] = $hide_header;

            $data['main_content']='transaction/station_d';
            $this->load->view('template/main', $data);
        }else{
            redirect('site/index');
        }
    }

    function station_d_panel($production_order){
        
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        
        $data['panel'] = $this->prodmon_model->get_production_order_scan($production_order, "1");
        $data['non_panel'] = $this->prodmon_model->get_production_order_scan($production_order, "0");
        $data['module'] = $this->prodmon_model->get_module_scan_by_production_order($production_order);
        
        $total_panel = 0;
        $total_panel_scan = 0;
        foreach($data['panel'] as $row){
            if($row['quantity'] == $row['total_scan'])
                $total_panel_scan++;

            $total_panel++;
        }

        $total_non_panel = 0;
        $total_non_panel_scan = 0;
        foreach($data['non_panel'] as $row){
            if($row['quantity'] == $row['total_scan'])
                $total_non_panel_scan++;

            $total_non_panel++;
        }

        $total_module = 0;
        $total_module_scan = 0;
        foreach($data['module'] as $row){
            if($row['total_scan'] == $row['total_module'])
                $total_module_scan++;

            $total_module++;
        }

        $data['production_order'] = $production_order;
        $data['total_module'] = $total_module;
        $data['total_module_scan'] = $total_module_scan;
        $data['total_panel'] = $total_panel;
        $data['total_panel_scan'] = $total_panel_scan;
        $data['total_non_panel'] = $total_non_panel;
        $data['total_non_panel_scan'] = $total_non_panel_scan;

        $this->load->view('transaction/station_d_panel', $data);
    }

    function create_qrcode($packing_code){
        $this->load->library('ciqrcode');

        $params['data'] = $packing_code;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH.'assets/img/qrcode/packing/' .$packing_code. '.jpg';
        
        $this->ciqrcode->generate($params);
    }

    function create_qrcode_delivery($production_order){
        $this->load->library('ciqrcode');

        $params['data'] = $production_order;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH.'assets/img/qrcode/delivery/' .$production_order. '.jpg';
        
        $this->ciqrcode->generate($params);
    }

    function packing_list(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        
        $this->session->set_userdata('redirect_url', '/transaction/packing_list');

        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');

        if(($role == "1" && in_array("27", $user_role_process)) || ($this->session->userdata('role_prodmon') == '5') || $this->session->userdata('role_prodmon') == '6'){
            $client = $this->gdriveupload->getClient();
            $data['main_content']='transaction/packing_list';
            $this->load->view('template/main', $data);
        }else{
            redirect('site/index');
        }
    }

    function export_packing_list(){
        $batch = $this->input->post('batch');
        $production_order = $this->input->post('production_order');
        $type = $this->input->post('type');

        $result = $this->prodmon_model->get_packing_code_by_production_order($production_order);
        
        $total_qty_pack = $this->prodmon_model->total_in_pack_production_order($production_order, 'panel');
        $in_total = $total_qty_pack['total'];

        $total = 0;
        foreach($result as $key => $row){
            $q_pack = $this->prodmon_model->get_qty_pack($production_order, $row['packing_code'], 'panel');
            
            $header = $this->prodmon_model->get_header($row['production_order']);

            $result[$key]['order_description'] = $header['order_description'];
            $result[$key]['project_definition'] = $header['project_definition'];
            $result[$key]['project_description'] = $header['project_description'];
            $result[$key]['material_description'] = $header['material_description'];
            
            $result[$key]['qty_pack'] = ($q_pack) ? $q_pack['qty_pack'] : 1;

            if($row['production_order'] == $temp_production_order)
                $packing_no++;
            else
                $packing_no = 1;
            
            $temp_production_order = $row['production_order'];

            if(strlen($packing_no) < 2)
                $packing_no = "000".$packing_no;     
            elseif(strlen($packing_no) < 3)       
                $packing_no = "00".$packing_no;
            elseif(strlen($packing_no) < 4)       
                $packing_no = "0".$packing_no;

            $result[$key]['packing_no'] = $packing_no;

            if(!file_exists(base_url().'/assets/img/qrcode/packing/' .$row['packing_code']. '.jpg')){
                $this->create_qrcode($row['packing_code']);
            }

            $result[$key]['qr_code'] = base_url().'/assets/img/qrcode/packing/' .$row['packing_code']. '.jpg';

            $item = $this->prodmon_model->get_item_by_packing_code($row['production_order'], $row['packing_code']);

            $result[$key]['floor'] = $item[0]['floor'];
            $result[$key]['code'] = $item[0]['code'];
            $result[$key]['item'] = $item;

            $floor = $item[0]['floor'];
            $total += 1;
        }

        $non_panel = $this->prodmon_model->get_non_panel_packing_code_by_production_order($production_order);
        foreach($non_panel as $key => $row){
            $q_pack = $this->prodmon_model->get_qty_pack($production_order, $row['packing_code'], 'non-panel');

            $header = $this->prodmon_model->get_header($row['production_order']);

            $non_panel[$key]['order_description'] = $header['order_description'];
            $non_panel[$key]['project_definition'] = $header['project_definition'];
            $non_panel[$key]['project_description'] = $header['project_description'];
            $non_panel[$key]['material_description'] = $header['material_description'];

            $non_panel[$key]['qty_pack'] = ($q_pack) ? $q_pack['qty_pack'] : 1;

            if($row['production_order'] == $temp_production_order)
                $packing_no++;
            else
                $packing_no = 1;
            
            $temp_production_order = $row['production_order'];

            if(strlen($packing_no) < 2)
                $packing_no = "000".$packing_no;     
            elseif(strlen($packing_no) < 3)       
                $packing_no = "00".$packing_no;
            elseif(strlen($packing_no) < 4)       
                $packing_no = "0".$packing_no;

            $non_panel[$key]['packing_no'] = $packing_no;

            if(!file_exists(base_url().'/assets/img/qrcode/packing/' .$row['packing_code']. '.jpg')){
                $this->create_qrcode($row['packing_code']);
            }

            $non_panel[$key]['qr_code'] = base_url().'/assets/img/qrcode/packing/' .$row['packing_code']. '.jpg';

            $item = $this->prodmon_model->get_item_non_panel_by_packing_code($row['production_order'], $row['packing_code']);

            $non_panel[$key]['code'] = $item[0]['code'];
            $non_panel[$key]['item'] = $item;

            $total += 1;
        }


        $data['result'] = $result;
        $data['non_panel'] = $non_panel;
        $data['total'] = $total;
        $data['in_total'] = $in_total;
        $data['batch'] = $batch;
        $data['floor'] = $floor;
        $data['logo'] = base_url().'/assets/img/logo-ggs.png';

        if($type == "PDF"){
            $this->load->view('template/packing_list', $data);
            
            // Get output html
            $html = $this->output->get_output();
            
            // Load pdf library
            $this->load->library('pdf');
            
            // Load HTML content
            $this->dompdf->loadHtml($html);
            
            $this->dompdf->set_option('isRemoteEnabled', TRUE);

            // (Optional) Setup the paper size and orientation
            $this->dompdf->setPaper('A4', 'portrait');
            
            // Render the HTML as PDF
            $this->dompdf->render();
            
            // Output the generated PDF (1 = download and 0 = preview)
            $file_name = "Packing List - ".$production_order.".pdf";
            $this->dompdf->stream($file_name, array("Attachment"=>1));
        }else{
            $namaTitle = "Packing List - ".$production_order;

            $namaFile = $namaTitle.".xls";

            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            $this->excel->getActiveSheet()->setTitle($namaTitle);
            //set cell A1 content with some text

            $i = 1; // 1-based index
            $start_arr = array();
            $item_arr = array();
            $item_start_arr = array();
            $item_end_arr = array();
            $end_arr = array();

            $start_non_arr = array();
            $item_non_arr = array();
            $item_start_non_arr = array();
            $item_end_non_arr = array();
            $end_non_arr = array();

            $start_header_arr = array();
            $item_header_arr = array();
            
            // panel
            foreach($result as $row){
                for($s=0; $s<=$row['qty_pack']-1; $s++) {
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "Terbitan 1");

                    $i++;

                    array_push($start_arr, $i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PACKING LIST");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'6',($i+4));

                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',$i,'9',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', ($i+1), "Form: QMS-CKR-QAS-F.001.10");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',($i+1),'9',($i+1));
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',($i+2),'9',($i+4));
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath('./assets/img/logo-ggs.png');
                    $objDrawing->setOffsetX(5);
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setHeight(50);
                    $objDrawing->setCoordinates('H'.($i+2));
                    $objDrawing->setWorksheet($this->excel->getActiveSheet());

                    $i = $i+5;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "BACTH NUMBER");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $batch);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "IN TOTAL");
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $in_total." PACKS");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8',$i,'9',($i+2));
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath('./assets/img/qrcode/packing/' .$row['packing_code']. '.jpg');
                    $objDrawing->setOffsetX(5);
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setHeight(100);
                    $objDrawing->setCoordinates('I'.$i);
                    $objDrawing->setWorksheet($this->excel->getActiveSheet());

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "WBS NO");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['project_definition']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "PACK NUMBER");
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, str_pad(($s+1), 4, "0", STR_PAD_LEFT));
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PROD. ORDER NO");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('2', $i, $row['production_order']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "PACK CODE");
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, $row['packing_code']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PROJECT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['project_description']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "FLOOR");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5',$i,'5',($i+2));
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, $row['floor']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',($i+2));

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, "QC Check");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8',$i,'8',($i+2));

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "Tanggal");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9',$i,'9',($i+2));

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PRODUCT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['code']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "FINISH COLOUR");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['order_description']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $i++;
                    array_push($item_arr, $i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "NO");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'0',($i+1));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, "LABEL NUMBER");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('1',$i,'1',($i+1));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, "GROUP NAME");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'2',($i+1));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, "PANEL NAME");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('3',$i,'3',($i+1));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, "QTY TO CHECK");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('4',$i,'4',($i+1));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "MATERIAL");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5',$i,'5',($i+1));
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, "SIZE");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'8',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "WEIGHT (Kg)");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9',$i,'9',($i+1));

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, "W");
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', $i, "D");
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, "H");

                    $i++;
                    array_push($item_start_arr, $i);

                    $no = 1;
                    $total_component = 0;
                    $total_weight = 0;
                    
                    foreach($row['item'] as $row2){ 
                        $total_component++;
                        $total_weight += $row2['weight'];

                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $no);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, $row2['component_no']);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row2['module']);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, $row2['component_name']);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, 1);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, $row2['material']);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, str_replace(".", ",", $row2['width']));
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', $i, str_replace(".", ",", $row2['length']));
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, str_replace(".", ",", $row2['height']));
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, str_replace(".", ",", $row2['weight']));

                        $i++;
                        $no++;
                    }

                    array_push($item_end_arr, ($i-1));

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "TOTAL COMPONENT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'3',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, $total_component);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "TOTAL WEIGHT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5',$i,'8',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, $total_weight);

                    array_push($end_arr, $i);
                    
                    $i = $i+5;
                }
            }

            // non_panel
            foreach($non_panel as $row){
                for ($s=0; $s <= $row['qty_pack']-1 ; $s++) {
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "Terbitan 1");

                    $i++;

                    array_push($start_non_arr, $i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PACKING LIST");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'6',($i+4));

                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',$i,'9',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', ($i+1), "Form: QMS-CKR-QAS-F.001.10");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',($i+1),'9',($i+1));
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',($i+2),'9',($i+4));
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath('./assets/img/logo-ggs.png');
                    $objDrawing->setOffsetX(5);
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setHeight(50);
                    $objDrawing->setCoordinates('H'.($i+2));
                    $objDrawing->setWorksheet($this->excel->getActiveSheet());

                    $i = $i+5;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "BACTH NUMBER");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $batch);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "IN TOTAL");
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $in_total." PACKS");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8',$i,'9',($i+2));
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath('./assets/img/qrcode/packing/' .$row['packing_code']. '.jpg');
                    $objDrawing->setOffsetX(5);
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setHeight(100);
                    $objDrawing->setCoordinates('I'.$i);
                    $objDrawing->setWorksheet($this->excel->getActiveSheet());

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "WBS NO");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['project_definition']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "PACK NUMBER");
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, str_pad(($s+1), 4, "0", STR_PAD_LEFT));
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PROD. ORDER NO");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('2', $i, $row['production_order']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "PACK CODE");
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, $row['packing_code']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PROJECT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['project_description']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "FLOOR");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5',$i,'5',($i+2));
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, $row['floor']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',($i+2));

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, "QC Check");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8',$i,'8',($i+2));

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "Tanggal");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9',$i,'9',($i+2));

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PRODUCT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['code']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "FINISH COLOUR");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['order_description']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $i++;
                    array_push($item_non_arr, $i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "NO");
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, "SAP CODE");
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, "DESCRIPTION");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'7',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, "QTY TO CHECK");
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "UNIT");

                    $i++;
                    array_push($item_start_non_arr, $i);

                    $no = 1;
                    $total_component = 0;
                    $total_weight = 0;
                    foreach($row['item'] as $row2){ 
                        $total_component += $row2['quantity'];
                        $total_weight += $row2['weight'];

                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $no);
                        $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('1', $i, $row2['material']);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row2['description']);
                        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'7',$i);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, $row2['quantity']);
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, $row2['uom']);
                        
                        $i++;
                        $no++;
                    }

                    array_push($item_end_non_arr, ($i-1));
                    array_push($end_non_arr, ($i-1));
                    
                    $i = $i+5;
                }
            }

            // header
            foreach($result as $row){
                for ($s=0; $s <= ($row['qty_pack']-1) ; $s++) {
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "Terbitan 1");

                    $i++;

                    array_push($start_header_arr, $i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PACKING LIST");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'6',($i+4));

                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',$i,'9',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', ($i+1), "Form: QMS-CKR-QAS-F.001.10");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',($i+1),'9',($i+1));
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',($i+2),'9',($i+4));
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath('./assets/img/logo-ggs.png');
                    $objDrawing->setOffsetX(5);
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setHeight(50);
                    $objDrawing->setCoordinates('H'.($i+2));
                    $objDrawing->setWorksheet($this->excel->getActiveSheet());

                    $i = $i+5;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "BACTH NUMBER");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $batch);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "IN TOTAL");
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $in_total." PACKS");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8',$i,'9',($i+2));
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath('./assets/img/qrcode/packing/' .$row['packing_code']. '.jpg');
                    $objDrawing->setOffsetX(5);
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setHeight(100);
                    $objDrawing->setCoordinates('I'.$i);
                    $objDrawing->setWorksheet($this->excel->getActiveSheet());

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "WBS NO");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['project_definition']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "PACK NUMBER");
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, str_pad(($s+1), 4, '0', STR_PAD_LEFT));
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PROD. ORDER NO");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('2', $i, $row['production_order']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "PACK CODE");
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, $row['packing_code']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PROJECT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['project_description']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "FLOOR");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5',$i,'5',($i+2));
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, $row['floor']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',($i+2));

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, "QC Check");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8',$i,'8',($i+2));

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "Tanggal");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9',$i,'9',($i+2));

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PRODUCT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['code']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "FINISH COLOUR");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['order_description']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $i++;
                    array_push($item_header_arr, $i);
                    
                    $i = $i+5;
                }
            }

            foreach($non_panel as $row){
                for ($s=0; $s <=($row['qty_pack']-1); $s++) { 
                    $pack_number[$row['packing_code']] = $pack_number[$row['packing_code']]+1;
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "Terbitan 1");

                    $i++;

                    array_push($start_header_arr, $i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PACKING LIST");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'6',($i+4));

                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',$i,'9',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', ($i+1), "Form: QMS-CKR-QAS-F.001.10");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',($i+1),'9',($i+1));
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7',($i+2),'9',($i+4));
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath('./assets/img/logo-ggs.png');
                    $objDrawing->setOffsetX(5);
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setHeight(50);
                    $objDrawing->setCoordinates('H'.($i+2));
                    $objDrawing->setWorksheet($this->excel->getActiveSheet());

                    $i = $i+5;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "BACTH NUMBER");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $batch);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "IN TOTAL");
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $in_total." PACKS");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8',$i,'9',($i+2));
                    $objDrawing = new PHPExcel_Worksheet_Drawing();
                    $objDrawing->setPath('./assets/img/qrcode/packing/' .$row['packing_code']. '.jpg');
                    $objDrawing->setOffsetX(5);
                    $objDrawing->setOffsetY(5);
                    $objDrawing->setHeight(100);
                    $objDrawing->setCoordinates('I'.$i);
                    $objDrawing->setWorksheet($this->excel->getActiveSheet());

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "WBS NO");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['project_definition']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "PACK NUMBER");
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, str_pad(($s+1), 4, '0', STR_PAD_LEFT));
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PROD. ORDER NO");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('2', $i, $row['production_order']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "PACK CODE");
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, $row['packing_code']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PROJECT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['project_description']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "FLOOR");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5',$i,'5',($i+2));
                    $this->excel->getActiveSheet()->setCellValueExplicitByColumnAndRow('6', $i, $row['floor']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6',$i,'7',($i+2));

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, "QC Check");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8',$i,'8',($i+2));

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, "Tanggal");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9',$i,'9',($i+2));

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "PRODUCT");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['code']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $i++;

                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, "FINISH COLOUR");
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0',$i,'1',$i);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['order_description']);
                    $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2',$i,'4',$i);

                    $i++;
                    array_push($item_header_arr, $i);
                    
                    $i = $i+5;
                }
            }

            $BStyle = array(
                'borders' => array(
                    'allborders' => array(
                      'style' => PHPExcel_Style_Border::BORDER_THIN
                    )
                )
            );

            for($j=0; $j<sizeof($start_arr); $j++){
                $start = $start_arr[$j];
                $item = $item_arr[$j];
                $item_start = $item_start_arr[$j];
                $item_end = $item_end_arr[$j];
                $end = $end_arr[$j];

                //$this->excel->getActiveSheet()->getStyle('A'.$start_arr[$j].':J'.$end_arr[$j])->applyFromArray($BStyle);
                $this->excel->getActiveSheet()->getStyle('A'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A'.$start)->getFont()->setSize(20);
                $this->excel->getActiveSheet()->getStyle('A'.$start.':A'.($item-1))->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('F'.$start.':F'.($item-1))->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A'.$start.':G'.($item-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('I'.$start.':J'.($item-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

                for($k=$start+5; $k<$item; $k++){
                    $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(26);
                }

                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.$item)->getAlignment()->setWrapText(true);
                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.$item)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.$item)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.($item+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.($item+1))->getFont()->setBold(true);
            }

            for($j=0; $j<sizeof($start_arr); $j++){
                $start = $start_arr[$j];
                $item = $item_arr[$j];
                $item_start = $item_start_arr[$j];
                $item_end = $item_end_arr[$j];
                $end = $end_arr[$j];

                $this->excel->getActiveSheet()->getStyle('A'.$item_start.':B'.$item_end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('E'.$item_start.':E'.$item_end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('G'.$item_start.':J'.$item_end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }

            for($j=0; $j<sizeof($start_arr); $j++){
                $start = $start_arr[$j];
                $item = $item_arr[$j];
                $item_start = $item_start_arr[$j];
                $item_end = $item_end_arr[$j];
                $end = $end_arr[$j];

                $this->excel->getActiveSheet()->getStyle('A'.$end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $this->excel->getActiveSheet()->getStyle('E'.$end.':E'.$end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('F'.$end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $this->excel->getActiveSheet()->getStyle('J'.$end.':J'.$end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }

            for($j=0; $j<sizeof($start_non_arr); $j++){
                $start = $start_non_arr[$j];
                $item = $item_non_arr[$j];
                $item_start = $item_start_non_arr[$j];
                $item_end = $item_end_non_arr[$j];
                $end = $end_non_arr[$j];

                //$this->excel->getActiveSheet()->getStyle('A'.$start_arr[$j].':J'.$end_arr[$j])->applyFromArray($BStyle);
                $this->excel->getActiveSheet()->getStyle('A'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A'.$start)->getFont()->setSize(20);
                $this->excel->getActiveSheet()->getStyle('A'.$start.':A'.($item-1))->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('F'.$start.':F'.($item-1))->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A'.$start.':G'.($item-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('I'.$start.':J'.($item-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

                for($k=$start+5; $k<$item; $k++){
                    $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(26);
                }

                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.$item)->getAlignment()->setWrapText(true);
                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.$item)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.$item)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.$item)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A'.$item.':J'.$item)->getFont()->setBold(true);
            }

            for($j=0; $j<sizeof($start_non_arr); $j++){
                $start = $start_non_arr[$j];
                $item = $item_non_arr[$j];
                $item_start = $item_start_non_arr[$j];
                $item_end = $item_end_non_arr[$j];
                $end = $end_non_arr[$j];

                $this->excel->getActiveSheet()->getStyle('A'.$item_start.':A'.$item_end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('I'.$item_start.':I'.$item_end)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }

            for($j=0; $j<sizeof($start_header_arr); $j++){
                $start = $start_header_arr[$j];
                $item = $item_header_arr[$j];

                //$this->excel->getActiveSheet()->getStyle('A'.$start_arr[$j].':J'.$end_arr[$j])->applyFromArray($BStyle);
                $this->excel->getActiveSheet()->getStyle('A'.$start)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('A'.$start)->getFont()->setSize(20);
                $this->excel->getActiveSheet()->getStyle('A'.$start.':A'.($item-1))->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('F'.$start.':F'.($item-1))->getFont()->setBold(true);
                $this->excel->getActiveSheet()->getStyle('A'.$start.':G'.($item-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $this->excel->getActiveSheet()->getStyle('I'.$start.':J'.($item-1))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

                for($k=$start+5; $k<$item; $k++){
                    $this->excel->getActiveSheet()->getRowDimension($k)->setRowHeight(26);
                }
            }

            $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
            // $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
            // $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

            // header('Content-Type: application/vnd.ms-excel'); //mime type
            // header('Content-Disposition: attachment;filename="'.$namaFile.'"'); //tell browser what's the file name
            // header('Cache-Control: max-age=0'); //no cache
                         
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
            ob_end_clean();
            //force user to download the Excel file without writing it to server's HD
            // $objWriter->save('php://output');
            $file_name = 'packing-list-'.$production_order.'-'.date('YmdHis').'.xls';
            $objWriter->save(str_replace(__FILE__,'assets/storage/excel/'.$file_name,__FILE__));

            $file = base_url('assets/storage/excel/'.$file_name);
            $uploaded = $this->gdriveupload->uploadToClient($file, 'vivere-prodmon', $file_name);
            
            $this->session->set_flashdata('msg','<div class="alert alert-success">
                    <a class="close" data-dismiss="alert"></a>
                    <strong>Success!</strong> Data berhasil diexport ke account Google Drive Anda. 
                    <a href="https://drive.google.com/file/d/'.$uploaded.'/view?usp=drivesdk" target="_blank">Klik disini</a>
                </div>');
            redirect('transaction/packing_list');
        }
        
    }

    function delivery_list(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');

        if(($role == "1" && in_array("28", $user_role_process)) || ($this->session->userdata('role_prodmon') == '5') || $this->session->userdata('role_prodmon') == '6'){
            $data['main_content']='transaction/delivery_list';
            $this->load->view('template/main', $data);
        }else{
            redirect('site/index');
        }
    }

    function export_delivery_list(){
        $batch = $this->input->post('batch');
        $production_order = $this->input->post('production_order');

        $data['header'] = $this->prodmon_model->get_header($production_order);
        $panel = $this->prodmon_model->get_item_panel_by_production_order($production_order);
        foreach($panel as $key => $row)
        {
            $qty_pack = $this->prodmon_model->get_qty_pack($production_order, $row['packing_code'], 'panel');
            $panel[$key]['qty_pack'] = ($qty_pack['qty_pack']) ? $qty_pack['qty_pack'] : 0;
        }

        $non_panel = $this->prodmon_model->get_item_non_panel_by_production_order($production_order);
        foreach($non_panel as $key=> $row)
        {
            $qty_pack = $this->prodmon_model->get_qty_pack($production_order, $row['packing_code'], 'non-panel');
            $non_panel[$key]['qty_pack'] = ($qty_pack['qty_pack']) ? $qty_pack['qty_pack'] : 0;
        }
        $data['panel'] = $panel;
        $data['non_panel'] = $non_panel;
        $product = $this->prodmon_model->get_produk($production_order);
        
        // $data['panel'] = $this->prodmon_model->get_item_panel_by_production_order($production_order);
        // $data['non_panel'] = $this->prodmon_model->get_item_non_panel_by_production_order($production_order);

        if(!file_exists(base_url().'/assets/img/qrcode/delivery/' .$production_order. '.jpg')){
            $this->create_qrcode_delivery($production_order);
        }

        $data['qr_code'] = base_url().'/assets/img/qrcode/delivery/' .$production_order. '.jpg';
        
        $data['batch'] = $batch;
        $data['logo'] = base_url().'/assets/img/logo-ggs.png';
        // $data['code'] = $data['panel'][0]['code'];
        $data['code'] = $product['product'];

        $this->load->view('template/delivery_list', $data);
        
        // Get output html
        $html = $this->output->get_output();
        
        // Load pdf library
        $this->load->library('pdf');
        
        // Load HTML content
        $this->dompdf->loadHtml($html);
        
        $this->dompdf->set_option('isRemoteEnabled', TRUE);

        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'portrait');
        
        // Render the HTML as PDF
        $this->dompdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        $file_name = "Delivery List - ".$production_order.".pdf";
        $this->dompdf->stream($file_name, array("Attachment"=>1));
    }

    function export_station(){
        $batch = $this->input->post('batch');
        $production_order = $this->input->post('production_order');
        $position = $this->input->post('position');

        $panel = $this->prodmon_model->get_panel_by_production_order($production_order, $position);

        if($position == "23")
        {
            $namaTitle = "Station A - ".$production_order;
            $file_name = 'station-A-'.$production_order.'-'.date('YmdHis').'.xls';
        }
        elseif($position == "24")
        {
            $namaTitle = "Station B - ".$production_order;
            $file_name = 'station-B-'.$production_order.'-'.date('YmdHis').'.xls';
        }elseif($position == "25"){
            $namaTitle = "Station C - ".$production_order;
            $file_name = 'station-C-'.$production_order.'-'.date('YmdHis').'.xls';
        }else {
            $namaTitle = "Station - ".$production_order;
            $file_name = 'station-'.$production_order.'-'.date('YmdHis').'.xls';
        }
            

        $namaFile = $namaTitle.".xls";

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle($namaTitle);
        //set cell A1 content with some text

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', '1' , 'SIZE');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6','1','8','1');

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', '1' , 'NO');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0','1','0','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', '1' , 'PRODUCTION ORDER');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('1','1','1','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', '1' , 'LABEL NUMBER');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2','1','2','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', '1' , 'GROUP NAME');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('3','1','3','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', '1' , 'PANEL NAME');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('4','1','4','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', '1' , 'MATERIAL');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5','1','5','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', '2' , 'W');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', '2' , 'D');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', '2' , 'H');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', '1' , 'QTY PANEL');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9','1','9','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', '1' , 'QTY TO CHECK');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('10','1','10','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', '1' , 'STATUS');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('11','1','11','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', '1' , 'REASON');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('12','1','12','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', '1' , 'REMARK');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('13','1','13','2');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', '1' , 'DATE');
        $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('14','1','14','2');
        

        $i = 3; // 1-based index
        $no=1;

        foreach($panel as $row){

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $no);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, $row['production_order']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['component_no']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, $row['module']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, $row['component_name']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, $row['material']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $row['length']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', $i, $row['width']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, $row['height']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, $row['quantity']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', $i, $row['total_scan']);

            if($position == "24"){
                $check = $this->prodmon_model->check_status_qc($row['production_order_item_id'], $position);
                
                if($check['quantity'] == $check['total_qty_scan'])
                    $status = "Done";
                elseif($check['qty_remaining'] > 0 && $check['qc_scan'] == "1")
                    $status = "Waiting";
                elseif($check['qty_remaining'] > 0 && $check['qc_scan'] == "0")
                    $status = "Waiting";
                elseif($row['flag_reject_qc'] == 1){
                    $status = "Reject";

                    $history = $this->prodmon_model->get_history_panel($row['production_order_item_id'], "24", "23");
                    $reason = $history['reason'];
                    $remark = $history['remark'];
                }else
                    $status = "Waiting";

                // if($row['total_scan'] == 0 && ($check['position'] == "23" || $check['position'] == "24") && $row['flag_reject_qc'] == 0)
                //     $status = "Waiting";
                // elseif($row['total_scan'] == 0 && ($check['position'] == "23" || $check['position'] == "24") && $row['flag_reject_qc'] == 1){
                //     $status = "Reject";

                //     $history = $this->prodmon_model->get_history_panel($row['production_order_item_id'], "24", "23");
                //     $remark = $history['remark'];
                // }
                // elseif($row['total_scan'] >= 1 && $check['position'] == "24")
                //     $status = "Waiting";
                // else
                //     $status = "Done";
            }else{
                if($row['total_scan'] == $row['quantity'])
                    $status = "Done";
                else
                    $status = "Waiting";
            }

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', $i, $status);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', $i, $reason);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', $i, $remark);

            if($status <> "Waiting")
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', $i, $row['log'] <> "" ? date('d-m-Y H:i:s', strtotime($row['log'])) : "");
            else
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', $i, "");

            $remark = "";
            $no++;
            $i++;
        }

        $BStyle = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $this->excel->getActiveSheet()->getStyle('A1:O2')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:O'.($i-1))->applyFromArray($BStyle);
        $this->excel->getActiveSheet()->getStyle('A1:O'.($i-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('L1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('M1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('N1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('O1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(25);

        // header('Content-Type: application/vnd.ms-excel'); //mime type
        // header('Content-Disposition: attachment;filename="'.$namaFile.'"'); //tell browser what's the file name
        // header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        ob_end_clean();
        //force user to download the Excel file without writing it to server's HD
        // $objWriter->save('php://output');
        $objWriter->save(str_replace(__FILE__,'assets/storage/excel/'.$file_name,__FILE__));

        $file = base_url('assets/storage/excel/'.$file_name);
        $uploaded = $this->gdriveupload->uploadToClient($file, 'vivere-prodmon', $file_name);

        $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Data berhasil diexport ke account Google Drive Anda. 
                <a href="https://drive.google.com/file/d/'.$uploaded.'/view?usp=drivesdk" target="_blank">Klik disini</a>
            </div>');
        if($position=="23")
            redirect('transaction/station_a');
        elseif($position=="24")
            redirect('transaction/station_b');
        elseif($position=="25")
            redirect('transaction/station_c');
    }

    function add_panel($production_order){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user = $this->session->userdata('nik_prodmon');

        $data['karyawan'] = $this->get_data_karyawan();
        $data['process'] = $this->prodmon_model->get_process();
        $data['production_order'] = $production_order;

        $line_item_no = $this->prodmon_model->get_latest_line_item($production_order);
        if(isset($line_item_no['line_item']))
            $data['line_item_no'] = $line_item_no['line_item']+1;
        else
            $data['line_item_no'] = 1;

        $data['main_content']='transaction/add_panel';
        $this->load->view('template/main', $data);
    }

    function add_panel_data(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $production_order = $this->input->post('production_order');
        $workshop = $this->input->post('workshop');
        $box_no = $this->input->post('box_no');
        $packing_code = $this->input->post('packing_code');
        $grouping_code = $this->input->post('grouping_code');
        $module = $this->input->post('module');
        $batch = $this->input->post('batch');
        $material = $this->input->post('material');
        $weight = $this->input->post('weight');
        $component_no = $this->input->post('component_no');
        $component_name = $this->input->post('component_name');
        $pic_p = explode(" - ", $this->input->post('pic_p'));
        $pic_p_nik = $pic_p[0];
        $pic_p_name = $pic_p[1];
        $pic_w = explode(" - ", $this->input->post('pic_w'));
        $pic_w_nik = $pic_w[0];
        $pic_w_name = $pic_w[1];
        $pic_install = explode(" - ", $this->input->post('pic_install'));
        $pic_install_nik = $pic_install[0];
        $pic_install_name = $pic_install[1];
        $length = $this->input->post('panjang');
        $width = $this->input->post('lebar');
        $height = $this->input->post('tinggi');
        $code = $this->input->post('kode');
        $code_information = $this->input->post('keterangan_kode');
        $floor = $this->input->post('lantai');
        $quantity = str_replace(",", ".", $this->input->post('quantity'));

        if($this->input->post('tgl1') <> "")
            $distribution_to_production_date = date("Y-m-d", strtotime($this->input->post('tgl1')));
            
        $pic = explode(" - ", $this->input->post('pic'));
        $pic_nik = $pic[0];
        $pic_name = $pic[1];
        $information = $this->input->post('keterangan');

        if($this->input->post('tgl2') <> "")
            $detail_schedule = date("Y-m-d", strtotime($this->input->post('tgl2')));

        if($this->input->post('tgl_ppic') <> "")
            $date_target_ppic = date("Y-m-d", strtotime($this->input->post('tgl_ppic')));

        if($this->input->post('tgl_pembahanan') <> "")
            $date_target_pembahanan = date("Y-m-d", strtotime($this->input->post('tgl_pembahanan')));
        
        if($this->input->post('tgl_perakitan') <> "")
            $date_target_perakitan = date("Y-m-d", strtotime($this->input->post('tgl_perakitan')));
        
        $subcont_perakitan = $this->input->post('subkon_perakitan');

        if($this->input->post('tgl_finishing') <> "")
            $date_target_finishing = date("Y-m-d", strtotime($this->input->post('tgl_finishing')));
        
        $subcont_finishing = $this->input->post('subkon_finishing');

        if($this->input->post('tgl_finishgood') <> "")
            $date_target_finish_good = date("Y-m-d", strtotime($this->input->post('tgl_finishgood')));
        
        if($this->input->post('tgl_pengiriman') <> "")
            $date_target_pengiriman = date("Y-m-d", strtotime($this->input->post('tgl_pengiriman')));
        
        $line_item = $this->input->post('line_item');

        if(strlen($box_no) < 2)
            $box_no = "0".$box_no;

        $position = "2";
        
        $data_header = $this->prodmon_model->get_header($production_order);
        $total_qty = $data_header['quantity'];
        $check_qty = $this->prodmon_model->count_quantity($production_order);
        $new_total_qty = $check_qty['total'] + $quantity;

        $check_component = $this->prodmon_model->check_component($production_order, $component_no);

        if($check_component == 0){

            $insert_data = array(
                'production_order'                  => $production_order,
                'workshop'                          => $workshop,
                'box_no'                            => $box_no,
                'packing_code'                      => $packing_code,
                'grouping_code'                     => $grouping_code,
                'module'                            => $module,
                'batch'                             => $batch,
                'material'                          => $material,
                'weight'                            => $weight,
                'component_no'                      => $component_no,
                'component_name'                    => $component_name,
                'line_item'                         => $line_item,
                'pic_p'                             => $pic_p_nik,
                'pic_p_name'                        => $pic_p_name,
                'pic_w'                             => $pic_w_nik,
                'pic_w_name'                        => $pic_w_name,
                'pic_install'                       => $pic_install_nik,
                'pic_install_name'                  => $pic_install_name,
                'length'                            => $length,
                'width'                             => $width,
                'height'                            => $height,
                'code'                              => $code,
                'code_information'                  => $code_information,
                'floor'                             => $floor,
                'quantity'                          => $quantity,
                'distribution_to_production_date'   => $distribution_to_production_date,
                'pic'                               => $pic_nik,
                'pic_name'                          => $pic_name,
                'position'                          => $position,
                'information'                       => $information,
                'detail_schedule'                   => $detail_schedule,
                'date_target_ppic'                  => $date_target_ppic,
                'date_target_pembahanan'            => $date_target_pembahanan,
                'date_target_perakitan'             => $date_target_perakitan,
                'subcont_perakitan'                 => $subcont_perakitan,
                'date_target_finishing'             => $date_target_finishing,
                'subcont_finishing'                 => $subcont_finishing,
                'date_target_finish_good'           => $date_target_finish_good,
                'date_target_pengiriman'            => $date_target_pengiriman,
                'is_panel'                          => "1",
                'created_by'                        => $user
            );

            $insert = $this->prodmon_model->insert_item($insert_data);
            if($insert <> 0){

                $position = "23";

                $insert_data_quantity = array(
                    'production_order_item_id'  => $insert,
                    'position'                  => $position,
                    'quantity'                  => $quantity,
                    'created_by'                => $user
                );

                $this->prodmon_model->insert_item_quantity($insert_data_quantity);

                $insert_data_history = array(
                    'production_order_item_id'  => $insert,
                    'position'                  => $position,
                    'created_by'                => $user
                );

                $insert = $this->prodmon_model->insert_item_history($insert_data_history);         
                
                $this->session->set_flashdata('msg','<div class="alert alert-success">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Success!</strong> Data successfully added
                     </div>');
                $url = "transaction/view_production_order/".$production_order;
                redirect($url);     
            }
            else{
                $this->session->set_flashdata('msg','<div class="alert alert-error">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Error!</strong> Failed input data
                     </div>');
                $url = "transaction/view_production_order/".$production_order;
                redirect($url); 
            }
        }else{
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                         <a class="close" data-dismiss="alert"></a>
                         <strong>Error!</strong> No Komponen already exist
                     </div>');
            $url = "transaction/view_production_order/".$production_order;
            redirect($url); 
        }
    }

    function add_non_panel($production_order){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user = $this->session->userdata('nik_prodmon');

        $data['karyawan'] = $this->get_data_karyawan();
        $data['process'] = $this->prodmon_model->get_process();
        $data['production_order'] = $production_order;

        $line_item_no = $this->prodmon_model->get_latest_line_item($production_order);
        if(isset($line_item_no['line_item']))
            $data['line_item_no'] = $line_item_no['line_item']+1;
        else
            $data['line_item_no'] = 1;

        $data['main_content']='transaction/add_non_panel';
        $this->load->view('template/main', $data);
    }

    function add_non_panel_data(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $production_order = $this->input->post('production_order');
        $workshop = $this->input->post('workshop');
        $box_no = $this->input->post('box_no');
        $packing_code = $this->input->post('packing_code');
        $grouping_code = $this->input->post('grouping_code');
        $batch = $this->input->post('batch');
        $material = $this->input->post('material');
        $description = $this->input->post('description');
        $quantity = str_replace(",", ".", $this->input->post('quantity'));
        $uom = $this->input->post('uom');

        $line_item = $this->input->post('line_item');

        if(strlen($box_no) < 2)
            $box_no = "0".$box_no;
        
        $position = "2";
        
        $data_header = $this->prodmon_model->get_header($production_order);
        $total_qty = $data_header['quantity'];
        $check_qty = $this->prodmon_model->count_quantity($production_order);
        $new_total_qty = $check_qty['total'] + $quantity;

        $insert_data = array(
            'production_order'                  => $production_order,
            'line_item'                         => $line_item,
            'workshop'                          => $workshop,
            'box_no'                            => $box_no,
            'packing_code'                      => $packing_code,
            'grouping_code'                     => $grouping_code,
            'batch'                             => $batch,
            'material'                          => $material,
            'description'                       => $description,
            'quantity'                          => $quantity,
            'uom'                               => $uom,
            'position'                          => $position,
            'is_panel'                          => "0",
            'created_by'                        => $user
        );

        $insert = $this->prodmon_model->insert_item($insert_data);
        if($insert <> 0){

            $position = "26";

            $insert_data_quantity = array(
                'production_order_item_id'  => $insert,
                'position'                  => $position,
                'quantity'                  => $quantity,
                'created_by'                => $user
            );

            $this->prodmon_model->insert_item_quantity($insert_data_quantity);

            $insert_data_history = array(
                'production_order_item_id'  => $insert,
                'position'                  => $position,
                'created_by'                => $user
            );

            $insert = $this->prodmon_model->insert_item_history($insert_data_history);          
            
            $this->session->set_flashdata('msg','<div class="alert alert-success">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Success!</strong> Data successfully added
                 </div>');
            $url = "transaction/view_production_order/".$production_order;
            redirect($url);     
        }
        else{
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Error!</strong> Failed input data
                 </div>');
            $url = "transaction/view_production_order/".$production_order;
            redirect($url); 
        }
    }

    function edit_non_panel($production_order, $line_item){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $user = $this->session->userdata('nik_prodmon');

        $data['karyawan'] = $this->get_data_karyawan();
        $data['process'] = $this->prodmon_model->get_process_ppic_pembahanan();
        $data['production_order'] = $production_order;

        $item = $this->prodmon_model->get_line_item($production_order, $line_item);
        $qty_pack = $this->prodmon_model->get_qty_pack($production_order, $item['packing_code'],  'non-panel');
        $item['qty_pack'] = $qty_pack['qty_pack'];

        $data['item'] = $item;

        $data['main_content']='transaction/edit_non_panel';
        $this->load->view('template/main', $data);
    }

    function edit_non_panel_data(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $production_order = $this->input->post('production_order');
        $line_item = $this->input->post('line_item');
        $workshop = $this->input->post('workshop');
        $box_no = $this->input->post('box_no');
        $packing_code = $this->input->post('packing_code');
        $grouping_code = $this->input->post('grouping_code');
        $batch = $this->input->post('batch');
        $material = $this->input->post('material');
        $description = $this->input->post('description');
        $quantity = str_replace(",", ".", $this->input->post('quantity'));
        $qty_pack = $this->input->post('qty_pack');
        $uom = $this->input->post('uom');

        $quantity_old = str_replace(",", ".", $this->input->post('quantity_old'));
        $position_old = $this->input->post('posisi_old');
        $production_order_item_id = $this->input->post('production_order_item_id');

        if(strlen($box_no) < 2)
            $box_no = "0".$box_no;
        
        $data_header = $this->prodmon_model->get_header($production_order);
        $total_qty = $data_header['quantity'];
        $check_qty = $this->prodmon_model->count_quantity($production_order);
        $new_total_qty = $check_qty['total'] + ($quantity - $quantity_old);
        
        if($position_old > "2")
            $position = $position_old;
          
        $update_data = array(
            'workshop'                          => $workshop,
            'box_no'                            => $box_no,
            'packing_code'                      => $packing_code,
            'grouping_code'                     => $grouping_code,
            'batch'                             => $batch,
            'material'                          => $material,
            'description'                       => $description,
            'quantity'                          => $quantity,
            'uom'                               => $uom,
            'updated_by'                        => $user
        );

        $update = $this->prodmon_model->update_item($production_order_item_id, $update_data);
        
        $input_qty_pack = array(
            'qty_pack'   => $qty_pack,
            'updated_by' => $user,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $update_qty_pack = $this->prodmon_model->update_production_order_pack($production_order, $packing_code, 'non-panel', $input_qty_pack);

        if($update){
            $this->prodmon_model->delete_report_item($production_order_item_id);
            
            $this->session->set_flashdata('msg','<div class="alert alert-success">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Success!</strong> Data successfully updated
                 </div>');
            $url = "transaction/view_production_order/".$production_order;
            redirect($url);
        }
        else{
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                     <a class="close" data-dismiss="alert"></a>
                     <strong>Error!</strong> Failed update data
                 </div>');
            $url = "transaction/view_production_order/".$production_order;
            redirect($url); 
        }
    }

    function export_station_d(){
        $batch = $this->input->post('batch');
        $position = "26";

        $list_production_order = $this->prodmon_model->get_production_order_scan_by_batch($batch);

        $namaTitle = "Station D - ".$batch;

        $namaFile = $namaTitle.".xls";
        $file_name = 'station-D-'.$batch.'-'.date('YmdHis').'.xls';

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle($namaTitle);
        //set cell A1 content with some text

        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', '1' , 'NO');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', '1' , 'PRODUCTION ORDER');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', '1' , 'GROUP NAME');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', '1' , 'PACK INTERNAL');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', '1' , 'PACK EKSTERNAL');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', '1' , 'STATUS');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', '1' , 'DATE');
        

        $i = 2; // 1-based index
        $no=1;

        foreach($list_production_order as $row){

            $production_order = $row['production_order'];

            $panel = $this->prodmon_model->get_production_order_scan_export($production_order);
            $non_panel = $this->prodmon_model->get_production_order_non_panel_scan_export($production_order);

            foreach($panel as $row2){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $no);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, $row2['production_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row2['module']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, $row2['packing_code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, "");

                if($row2['total_internal_scan'] < $row2['quantity'])
                    $status = "Waiting";
                else
                    $status = "Done";

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, $status);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $row2['log'] <> "" ? date('d-m-Y H:i:s', strtotime($row2['log'])) : "");

                $no++;
                $i++;
            }
            
            foreach($non_panel as $row2){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $no);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, $row2['production_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row2['module']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, "");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, $row2['packing_code']);

                if($row2['total_eksternal_scan'] < $row2['quantity'])
                    $status = "Waiting";
                else
                    $status = "Done";

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, $status);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $row2['log'] <> "" ? date('d-m-Y H:i:s', strtotime($row2['log'])) : "");

                $no++;
                $i++;
            }
        }


        $BStyle = array(
            'borders' => array(
                'allborders' => array(
                  'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );

        $this->excel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:G'.($i-1))->applyFromArray($BStyle);
        $this->excel->getActiveSheet()->getStyle('A1:G'.($i-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);

        // header('Content-Type: application/vnd.ms-excel'); //mime type
        // header('Content-Disposition: attachment;filename="'.$namaFile.'"'); //tell browser what's the file name
        // header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        ob_end_clean();
        //force user to download the Excel file without writing it to server's HD
        // $objWriter->save('php://output');

        $objWriter->save(str_replace(__FILE__,'assets/storage/excel/'.$file_name,__FILE__));

        $file = base_url('assets/storage/excel/'.$file_name);
        $uploaded = $this->gdriveupload->uploadToClient($file, 'vivere-prodmon', $file_name);

        $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Data berhasil diexport ke account Google Drive Anda. 
                <a href="https://drive.google.com/file/d/'.$uploaded.'/view?usp=drivesdk" target="_blank">Klik disini</a>
            </div>');
        redirect('transaction/station_d');
    }

    function gr_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');

        if(($role == "1" && in_array("26", $user_role_process)) || ($this->session->userdata('role_prodmon') == '5') || $this->session->userdata('role_prodmon') == '6'){
            
            $data['main_content']='transaction/gr_panel';
            $this->load->view('template/main', $data);
        }else{
            redirect('site/index');
        }
    }

    function gr_panel_process(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');

        $production_order = $this->input->post('qrcode-text');

        $data['header'] = $this->prodmon_model->get_header($production_order);
        $plant = $data['header']['plant'];

        $panel = $this->prodmon_model->get_production_order_scan_gr($production_order);
        foreach($panel as $row){
            if(($row['total_internal'] == $row['total_internal_scan']) && ($row['total_eksternal'] == $row['total_eksternal_scan']))
                $status = 1;
            else
                $status = 0;
        }

        if($status == 1){
            $data['sloc'] = $this->prodmon_model->get_sloc_by_plant($plant);
            $data['date'] = date('d-m-Y');

            $data['main_content']='transaction/gr_panel_process';
            $this->load->view('template/main', $data);
        }else{
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                <a class="close" data-dismiss="alert"></a>
                <strong>Error!</strong> No Data to GR
            </div>'); 

            redirect('transaction/gr_panel');
        }
    }

    function post_gr_panel(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');

        $production_order = $this->input->post('production_order');
        $plant = $this->input->post('plant');
        $material_code = $this->input->post('material_code');
        $sloc = $this->input->post('sloc');
        $gr_date = $this->input->post('tgl1');
        $quantity = $this->input->post('quantity_gr');

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "http://webdev.vivere.co.id/vservice/monitoring_produksi/post_gr_production_panel",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\n\t\"nik\" : \"$user\",\n\t\"production_order\" : \"$production_order\",\n\t\"qty\" : \"$quantity\",\n\t\"material\" : \"$material_code\",\n\t\"plant\" : \"$plant\",\n\t\"sloc\" : \"$sloc\",\n\t\"tgl\" : \"$gr_date\"\n}",
          CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Postman-Token: 7d095ac0-7bba-448e-9e91-af16aaf1c462",
            "cache-control: no-cache"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          $response = json_decode($response, true);
        }

        if($response['result'] == "true"){
            $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Berhasil Posting
            </div>'); 
        } else {
            $this->session->set_flashdata('msg','<div class="alert alert-error">
                <a class="close" data-dismiss="alert"></a>
                <strong>Error!</strong> SAP INFO: '.$response['message'].'
            </div>'); 
        }

        redirect('transaction/gr_panel');
    }

    public function close_production_order()
    {
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $data['main_content']='transaction/close_production_order';
        $this->load->view('template/main', $data);
    }

    public function close_production_order_data()
    {
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        
        $file = $_FILES['excel']['tmp_name'];
        //load the excel library
        $this->load->library('excel');
        //read file from path
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        //get only the Cell Collection
        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        $rows = $objPHPExcel->getActiveSheet()->getHighestRow();

        for ($i = 2; $i <= $rows; $i++) {
            $production_order = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->getValue();
            $plant = $objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i)->getValue();
            $updated = $this->prodmon_model->close_production_order($production_order, $plant);
        }
        $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Berhasil close roduction order
            </div>'); 
        redirect('transaction/close_production_order');
    }
}
?>