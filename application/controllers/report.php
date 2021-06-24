<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class report extends CI_CONTROLLER {
    
    public function __construct(){
        parent::__construct();
        $this->load->library('My_Auth');
        $this->load->library('/gdrive/GDriveUpload');
        $this->load->model('prodmon_model');
        date_default_timezone_set('Asia/Bangkok');
    }

    function report_plant(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        
        $data['user_plant'] = $this->session->userdata('plant_prodmon');

        $data['plant'] = $this->prodmon_model->get_list_plant();

        $data['main_content']='report/report_plant';

        $this->load->view('template/main', $data);
    }

    function index(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $this->session->set_userdata('redirect_url', '/report/index');

        $client = $this->gdriveupload->getClient();
        
        $user_plant = $this->session->userdata('plant_prodmon');

        $data['plant'] = ($this->input->post('plant')) ? $this->input->post('plant') : $this->session->userdata('rpo_plant');
        $type = ($this->input->post('type')) ? $this->input->post('type') : $this->session->userdata('rpo_type');

        $data_session = array(
            'rpo_plant' => $data['plant'],
            'rpo_type'  => $type,
        );
        $this->session->set_userdata($data_session);

        $data['list_production_order'] = $this->prodmon_model->get_list_header_by_plant($data['plant']);
        $data['list_project'] = $this->prodmon_model->get_list_project_by_plant($data['plant']);
        $data['list_sales_order'] = $this->prodmon_model->get_list_sales_order_by_plant($data['plant']);
        $data['list_po_vmk'] = $this->prodmon_model->get_list_po_vmk_by_plant($data['plant']);
        $data['list_so_vmk'] = $this->prodmon_model->get_list_so_vmk_by_plant($data['plant']);
        
        if($type == "web")
            $data['main_content']='report/report';
        else
            $data['main_content']='report/report_export';
        
        $this->load->view('template/main', $data);
    }

    function report_result(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }

        $production_order1 = $this->input->post('production_order1');
        $production_order2 = $this->input->post('production_order2');
        $production_order3 = $this->input->post('production_order3');
        $project_definition1 = $this->input->post('project_definition1');
        $project_definition2 = $this->input->post('project_definition2');
        $project_definition3 = $this->input->post('project_definition3');
        $sales_order1 = $this->input->post('sales_order1');
        $sales_order2 = $this->input->post('sales_order2');
        $sales_order3 = $this->input->post('sales_order3');
        $basic_date_start = $this->input->post('tgl1');
        $basic_date_end = $this->input->post('tgl2');
        $po_vmk = $this->input->post('po_vmk');
        $so_vmk = $this->input->post('so_vmk');
        $plant = $this->input->post('plant');

        if($production_order1 == "" && $production_order2 == "" && $production_order3 == "" && $project_definition1 == "" && $project_definition2 == "" && $project_definition3 == "" && $sales_order1 == "" && $sales_order2 == "" && $sales_order3 == "" && $basic_date_start == "" && $basic_date_end == "" && $po_vmk == "" && $so_vmk==""){

            $this->session->set_flashdata('msg','<div class="alert alert-warning">
                <a class="close" data-dismiss="alert"></a>
                Please input a value
            </div>'); 
            redirect('report/index');
        }

        $query = "";

        if(!empty($production_order3)){
            $production_order_str = "'" . implode("','", $production_order3) . "'";
            $query = "production_order_header.production_order IN ($production_order_str) ";
        } else {
            if($production_order1 <> "" && $production_order2 == ""){
                $query = "production_order_header.production_order = '$production_order1' ";
            } elseif($production_order2 <> "" && $production_order1 == ""){
                $query = "production_order_header.production_order = '$production_order2' ";
            } elseif($production_order1 <> "" && $production_order2 <> "") {
                $query = "production_order_header.production_order >= '$production_order1' AND production_order_header.production_order <= '$production_order2' ";
            }
        }

        if(!empty($project_definition3)){
            if($query <> "")
                $query = $query."AND ";
            $project_definition_str = "'" . implode("','", $project_definition3) . "'";
            $query = $query."production_order_header.project_definition IN ($project_definition_str) ";
        } else {
            if($project_definition1 <> "" && $project_definition2 == ""){
                if($query <> "")
                    $query = $query."AND ";
                $query = $query."production_order_header.project_definition = '$project_definition1' ";
            } elseif($project_definition2 <> "" && $project_definition1 == ""){
                if($query <> "")
                    $query = $query."AND ";
                $query = $query."production_order_header.project_definition = '$project_definition2' ";
            } elseif($project_definition1 <> "" && $project_definition2 <> "") {
                if($query <> "")
                    $query = $query."AND ";
                $project_definition1_str = substr($project_definition1, 4, 5);
                $project_definition2_str = substr($project_definition2, 4, 5);
                $query = $query."SUBSTR(production_order_header.project_definition,5,5) >= '$project_definition1_str' AND SUBSTR(production_order_header.project_definition,5,5) <= '$project_definition2_str' ";
            }
        }

        if(!empty($sales_order3)){
            $sales_order_str = "'" . implode("','", $sales_order3) . "'";
            $query = "production_order_header.sales_order IN ($sales_order_str) ";
        } else {
            if($sales_order1 <> "" && $sales_order2 == ""){
                $query = "production_order_header.sales_order = '$sales_order1' ";
            } elseif($sales_order2 <> "" && $sales_order1 == ""){
                $query = "production_order_header.sales_order = '$sales_order2' ";
            } elseif($sales_order1 <> "" && $sales_order2 <> "") {
                $query = "production_order_header.sales_order >= '$sales_order1' AND production_order_header.sales_order <= '$sales_order2' ";
            }
        }
        
        if($basic_date_start <> "" && $basic_date_end == ""){
            $basic_date_start = date('Y-m-d', strtotime($basic_date_start));
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.basic_date_start >= '$basic_date_start' ";
        } elseif($basic_date_end <> "" && $basic_date_start == ""){
            $basic_date_end = date('Y-m-d', strtotime($basic_date_end));
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.basic_date_end <= '$basic_date_end' ";
        } elseif($basic_date_start <> "" && $basic_date_end <> "") {
            $basic_date_start = date('Y-m-d', strtotime($basic_date_start));
            $basic_date_end = date('Y-m-d', strtotime($basic_date_end));
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.basic_date_start >= '$basic_date_start' AND production_order_header.basic_date_end <= '$basic_date_end' ";
        }

        if(!empty($po_vmk)){
            $po_vmk_str = "'" . implode("','", $po_vmk) . "'";
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_item.po_vmk IN ($po_vmk_str) ";
        }

        if(!empty($so_vmk)){
            $so_vmk_str = "'" . implode("','", $so_vmk) . "'";
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.so_vmk IN ($so_vmk_str) ";
        }

        if($query <> "")
            $query = $query."AND ";
        $query = $query."production_order_header.plant = '$plant' ";

        $production_order = $this->prodmon_model->get_data_report_production_order($query);

        $production_order_arr = array();

        foreach($production_order as $row){
            array_push($production_order_arr, $row['production_order']);

            $count_row = $this->prodmon_model->count_report_production_order($row['production_order'], $row['line_item']);
            if($count_row == 0){
                $data = $this->prodmon_model->get_data_report_by_production_order($row['production_order'], $row['line_item']);
                
                $uom = $data['uom'] <> "" ? $data['uom'] : $data['uom_header'];

                $insert_data = array(
                    'project_definition'                => $data['project_definition'],
                    'project_description'               => $data['project_description'],
                    'sales_order'                       => $data['sales_order'],
                    'production_order'                  => $data['production_order'],
                    'line_item'                         => $data['line_item'],
                    'line_description'                  => $data['line_description'],
                    'workshop'                          => $data['workshop'],
                    'box_no'                            => $data['box_no'],
                    'packing_code'                      => $data['packing_code'],
                    'grouping_code'                     => $data['grouping_code'],
                    'module'                            => $data['module'],
                    'batch'                             => $data['batch'],
                    'material'                          => $data['material'],
                    'weight'                            => $data['weight'],
                    'component_no'                      => $data['component_no'],
                    'component_name'                    => $data['component_name'],
                    'po_vmk'                            => $data['po_vmk'],
                    'production_order_item_id'          => $data['production_order_item_id'],
                    'order_description'                 => $data['order_description'],
                    'length'                            => $data['length'],
                    'width'                             => $data['width'],
                    'height'                            => $data['height'],
                    'created_date'                      => $data['created_date'],
                    'release_date'                      => $data['release_date'],
                    'code'                              => $data['code'],
                    'code_information'                  => $data['code_information'],
                    'floor'                             => $data['floor'],
                    'description'                       => $data['description'],
                    'quantity'                          => $data['quantity'],
                    'uom'                               => $uom,
                    'process'                           => $data['process'],
                    'process_date'                      => $data['process_date'],
                    'remark'                            => $data['remark'],
                    'date_target_ppic'                  => $data['date_target_ppic'],
                    'ppic'                              => $data['ppic'],
                    'ppic_qty'                          => $data['ppic_qty'],
                    'date_target_pembahanan'            => $data['date_target_pembahanan'],
                    'pembahanan'                        => $data['pembahanan'],
                    'pembahanan_qty'                    => $data['pembahanan_qty'],
                    'machine'                           => $data['machine'],
                    'date_target_perakitan'             => $data['date_target_perakitan'],
                    'subcont_perakitan'                 => $data['subcont_perakitan'],
                    'perakitan'                         => $data['perakitan'],
                    'perakitan_qty'                     => $data['perakitan_qty'],
                    'date_target_finishing'             => $data['date_target_finishing'],
                    'subcont_finishing'                 => $data['subcont_finishing'],
                    'finishing'                         => $data['finishing'],
                    'finishing_qty'                     => $data['finishing_qty'],
                    'date_target_finish_good'           => $data['date_target_finish_good'],
                    'finish_good'                       => $data['finish_good'],
                    'finish_good_qty'                   => $data['finish_good_qty'],
                    'date_target_pengiriman'            => $data['date_target_pengiriman'],
                    'pengiriman'                        => $data['pengiriman'],
                    'pengiriman_qty'                    => $data['pengiriman_qty'],
                    'install'                           => $data['install'],
                    'install_qty'                       => $data['install_qty'],
                    'pic_p_name'                        => $data['pic_p_name'],
                    'pic_w_name'                        => $data['pic_w_name'],
                    'pic_install_name'                  => $data['pic_install_name'],
                    'pic_name'                          => $data['pic_name'],
                    'distribution_to_production_date'   => $data['distribution_to_production_date'],
                    'detail_schedule'                   => $data['detail_schedule'],
                    'information'                       => $data['information'],
                    'so_vmk'                            => $data['so_vmk'],
                );

                $insert = $this->prodmon_model->insert_report($insert_data);
            }else{
                $quantity_last_update = $this->prodmon_model->get_latest_quantity($row['production_order_item_id']);
                $data_report = $this->prodmon_model->get_report_updated_at($row['production_order'], $row['line_item']);

                if(strtotime($quantity_last_update['updated_at']) > strtotime($data_report['updated_at'])){
                    $delete_report = $this->prodmon_model->delete_report($row['production_order'], $row['line_item']);

                    $data = $this->prodmon_model->get_data_report_by_production_order($row['production_order'], $row['line_item']);

                    $uom = $data['uom'] <> "" ? $data['uom'] : $data['uom_header'];

                    $insert_data = array(
                        'project_definition'                => $data['project_definition'],
                        'project_description'               => $data['project_description'],
                        'sales_order'                       => $data['sales_order'],
                        'production_order'                  => $data['production_order'],
                        'line_item'                         => $data['line_item'],
                        'line_description'                  => $data['line_description'],
                        'workshop'                          => $data['workshop'],
                        'box_no'                            => $data['box_no'],
                        'packing_code'                      => $data['packing_code'],
                        'grouping_code'                     => $data['grouping_code'],
                        'batch'                             => $data['batch'],
                        'module'                            => $data['module'],
                        'material'                          => $data['material'],
                        'weight'                            => $data['weight'],
                        'component_no'                      => $data['component_no'],
                        'component_name'                    => $data['component_name'],
                        'po_vmk'                            => $data['po_vmk'],
                        'production_order_item_id'          => $data['production_order_item_id'],
                        'order_description'                 => $data['order_description'],
                        'length'                            => $data['length'],
                        'width'                             => $data['width'],
                        'height'                            => $data['height'],
                        'created_date'                      => $data['created_date'],
                        'release_date'                      => $data['release_date'],
                        'code'                              => $data['code'],
                        'code_information'                  => $data['code_information'],
                        'floor'                             => $data['floor'],
                        'description'                       => $data['description'],
                        'quantity'                          => $data['quantity'],
                        'uom'                               => $uom,
                        'process'                           => $data['process'],
                        'process_date'                      => $data['process_date'],
                        'remark'                            => $data['remark'],
                        'date_target_ppic'                  => $data['date_target_ppic'],
                        'ppic'                              => $data['ppic'],
                        'ppic_qty'                          => $data['ppic_qty'],
                        'date_target_pembahanan'            => $data['date_target_pembahanan'],
                        'pembahanan'                        => $data['pembahanan'],
                        'pembahanan_qty'                    => $data['pembahanan_qty'],
                        'machine'                           => $data['machine'],
                        'date_target_perakitan'             => $data['date_target_perakitan'],
                        'subcont_perakitan'                 => $data['subcont_perakitan'],
                        'perakitan'                         => $data['perakitan'],
                        'perakitan_qty'                     => $data['perakitan_qty'],
                        'date_target_finishing'             => $data['date_target_finishing'],
                        'subcont_finishing'                 => $data['subcont_finishing'],
                        'finishing'                         => $data['finishing'],
                        'finishing_qty'                     => $data['finishing_qty'],
                        'date_target_finish_good'           => $data['date_target_finish_good'],
                        'finish_good'                       => $data['finish_good'],
                        'finish_good_qty'                   => $data['finish_good_qty'],
                        'date_target_pengiriman'            => $data['date_target_pengiriman'],
                        'pengiriman'                        => $data['pengiriman'],
                        'pengiriman_qty'                    => $data['pengiriman_qty'],
                        'install'                           => $data['install'],
                        'install_qty'                       => $data['install_qty'],
                        'pic_p_name'                        => $data['pic_p_name'],
                        'pic_w_name'                        => $data['pic_w_name'],
                        'pic_install_name'                  => $data['pic_install_name'],
                        'pic_name'                          => $data['pic_name'],
                        'distribution_to_production_date'   => $data['distribution_to_production_date'],
                        'detail_schedule'                   => $data['detail_schedule'],
                        'information'                       => $data['information'],
                        'so_vmk'                            => $data['so_vmk']
                    );

                    $insert = $this->prodmon_model->insert_report($insert_data);
                }
            }
        }

        $str_production_order = "'".implode("','", $production_order_arr)."'";
        $data['production_order'] = $this->prodmon_model->get_data_report_new($str_production_order);

        //$data['production_order'] = $this->prodmon_model->get_data_report($query);
        
        $data['production_order1'] = $production_order1;
        $data['production_order2'] = $production_order2;
        $data['production_order_str'] = $production_order_str;
        $data['project_definition1'] = $project_definition1;
        $data['project_definition2'] = $project_definition2;
        $data['project_definition_str'] = $project_definition_str;
        $data['sales_order1'] = $sales_order1;
        $data['sales_order2'] = $sales_order2;
        $data['sales_order_str'] = $sales_order_str;
        $data['basic_date_start'] = $basic_date_start;
        $data['basic_date_end'] = $basic_date_end;
        $data['po_vmk'] = $po_vmk_str;
        $data['so_vmk_str'] = $so_vmk_str;
        $data['plant'] = $plant;

        if($plant == "1600")
            $data['main_content']='report/report_panel_result';
        else
           $data['main_content']='report/report_result';
            
        $this->load->view('template/main', $data);
    }

    function export_excel(){

        $production_order1 = $this->input->post('production_order1');
        $production_order2 = $this->input->post('production_order2');
        $production_order_str = $this->input->post('production_order_str');
        $project_definition1 = $this->input->post('project_definition1');
        $project_definition2 = $this->input->post('project_definition2');
        $project_definition_str = $this->input->post('project_definition_str');
        $sales_order1 = $this->input->post('sales_order1');
        $sales_order2 = $this->input->post('sales_order2');
        $sales_order_str = $this->input->post('sales_order_str');
        $basic_date_start = $this->input->post('basic_date_start');
        $basic_date_end = $this->input->post('basic_date_end');
        $po_vmk_str = $this->input->post('po_vmk');
        $so_vmk_str = $this->input->post('so_vmk_str');
        $plant = $this->input->post('plant');

        $query = "";

        if(!empty($production_order_str)){
            $query = "production_order_header.production_order IN ($production_order_str) ";
        } else {
            if($production_order1 <> "" && $production_order2 == ""){
                $query = "production_order_header.production_order = '$production_order1' ";
            } elseif($production_order2 <> "" && $production_order1 == ""){
                $query = "production_order_header.production_order = '$production_order2' ";
            } elseif($production_order1 <> "" && $production_order2 <> "") {
                $query = "production_order_header.production_order >= '$production_order1' AND production_order_header.production_order <= '$production_order2' ";
            }
        }

        if(!empty($project_definition_str)){
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.project_definition IN ($project_definition_str) ";
        } else {
            if($project_definition1 <> "" && $project_definition2 == ""){
                if($query <> "")
                    $query = $query."AND ";
                $query = $query."production_order_header.project_definition = '$project_definition1' ";
            } elseif($project_definition2 <> "" && $project_definition1 == ""){
                if($query <> "")
                    $query = $query."AND ";
                $query = $query."production_order_header.project_definition = '$project_definition2' ";
            } elseif($project_definition1 <> "" && $project_definition2 <> "") {
                if($query <> "")
                    $query = $query."AND ";
                $project_definition1_str = substr($project_definition1, 4, 5);
                $project_definition2_str = substr($project_definition2, 4, 5);
                $query = $query."SUBSTR(production_order_header.project_definition,5,5) >= '$project_definition1_str' AND SUBSTR(production_order_header.project_definition,5,5) <= '$project_definition2_str' ";
            }
        }
        
        if(!empty($sales_order_str)){
            $query = "production_order_header.sales_order IN ($sales_order_str) ";
        } else {
            if($sales_order1 <> "" && $sales_order2 == ""){
                $query = "production_order_header.sales_order = '$sales_order1' ";
            } elseif($sales_order2 <> "" && $sales_order1 == ""){
                $query = "production_order_header.sales_order = '$sales_order2' ";
            } elseif($sales_order1 <> "" && $sales_order2 <> "") {
                $query = "production_order_header.sales_order >= '$sales_order1' AND production_order_header.sales_order <= '$sales_order2' ";
            }
        }

        if($basic_date_start <> "" && $basic_date_end == ""){
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.basic_date_start >= '$basic_date_start' ";
        } elseif($basic_date_end <> "" && $basic_date_start == ""){
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.basic_date_end <= '$basic_date_end' ";
        } elseif($basic_date_start <> "" && $basic_date_end <> "") {
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.basic_date_start >= '$basic_date_start' AND production_order_header.basic_date_end <= '$basic_date_end' ";
        }

        if(!empty($po_vmk_str)){
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_item.po_vmk IN ($po_vmk_str) ";
        }

        if(!empty($so_vmk_str)){
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.so_vmk IN ($so_vmk_str) ";
        }

        if($query <> "")
            $query = $query."AND ";
        $query = $query."production_order_header.plant = '$plant' ";

        $production_order = $this->prodmon_model->get_data_report_production_order($query);

        $production_order_arr = array();

        foreach($production_order as $row){
            array_push($production_order_arr, $row['production_order']);
        }

        $str_production_order = "'".implode("','", $production_order_arr)."'";
        $data_production_order = $this->prodmon_model->get_data_report_new($str_production_order);
        
        //$data_production_order = $this->prodmon_model->get_data_report($query);
        // var_dump($data_production_order);
        // exit();
        $namaTitle = "Report Production Order";
        $namaFile = $namaTitle.".xls";

        $file_name = "report-production-order-".date('YmdHis').'.xls';
        // $file_name = "Report Production Order " . date('YmdHis');

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle($namaTitle);
        //set cell A1 content with some text

        if($plant == "1600"){
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', '1' , 'Ukuran');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('22','1','24','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', '1' , 'Kode');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('25','1','26','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', '1' , 'Target-Realisasi Produksi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('36','1','65','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', '1' , 'PIC P');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0','1','0','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', '1' , 'PIC W');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('1','1','1','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', '1' , 'PIC Install');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2','1','2','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', '1' , 'Project Def.');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('3','1','3','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', '1' , 'Project Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('4','1','4','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', '1' , 'Sales Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5','1','5','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', '1' , 'Production Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6','1','6','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', '1' , 'SO VMK');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7','1','7','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', '1' , 'Line Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8','1','8','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', '1' , 'Workshop');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9','1','9','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', '1' , 'No Box');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('10','1','10','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', '1' , 'Kode Packing');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('11','1','11','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', '1' , 'Kode Grouping');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('12','1','12','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', '1' , 'Modul');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('13','1','13','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', '1' , 'Batch');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('14','1','14','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('15', '1' , 'Material');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('15','1','15','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', '1' , 'Deskripsi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('16','1','16','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('17', '1' , 'Weight');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('17','1','17','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('18', '1' , 'No Komponen');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('18','1','18','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('19', '1' , 'Nama Komponen');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('19','1','19','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('20', '1' , 'Purchase Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('20','1','20','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('21', '1' , 'Order Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('21','1','21','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', '2' , 'P');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('23', '2' , 'L');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('24', '2' , 'T');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', '2' , 'Kode');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('26', '2' , 'Keterangan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', '1' , 'LT');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('27','1','27','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('28', '1' , 'QTY');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('28','1','29','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('30', '1' , 'Tgl. Dist. ke Prod');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('30','1','30','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('31', '1' , 'Tgl CRTD');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('31','1','31','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('32', '1' , 'Tgl REL');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('32','1','32','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('33', '1' , 'PIC');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('33','1','33','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('34', '1' , 'Posisi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('34','1','34','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('35', '1' , 'Remark');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('35','1','35','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', '2' , 'Target PPIC');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('37', '2' , 'Realisasi PPIC');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('38', '2' , 'Qty PPIC');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('38','2','39','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('40', '2' , 'Target Pembahanan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('41', '2' , 'Realisasi Pembahanan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('42', '2' , 'Qty Pembahanan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('42','2','43','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('44', '2' , 'Mesin');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('45', '2' , 'Target Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('46', '2' , 'Subkon Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('47', '2' , 'Realisasi Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('48', '2' , 'Qty Perakitan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('48','2','49','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('50', '2' , 'Target Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('51', '2' , 'Subkon Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('52', '2' , 'Realisasi Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('53', '2' , 'Qty Finishing');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('53','2','54','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('55', '2' , 'Target Finish Good');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('56', '2' , 'Realisasi Finish Good');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('57', '2' , 'Qty Finish Good');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('57','2','58','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('59', '2' , 'Target Pengiriman');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('60', '2' , 'Realisasi Pengiriman');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('61', '2' , 'Qty Pengiriman');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('61','2','62','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('63', '2' , 'Realisasi Install');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('64', '2' , 'Qty Install');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('64','2','65','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('66', '1' , 'Lead Time Process');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('66','1','66','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('67', '1' , 'Detail Schedule');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('67','1','67','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('68', '1' , 'Keterangan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('68','1','68','2');

            $i = 3; // 1-based index
            $no=1;

            foreach($data_production_order as $row){

                $distribution_to_production_date = new Datetime( $row['distribution_to_production_date']);
                $created_date = new Datetime( $row['created_date']);
                $release_date = new Datetime( $row['release_date']);
                $date_target_ppic = new Datetime( $row['date_target_ppic']);
                $ppic = new Datetime( $row['ppic']);
                $date_target_pembahanan = new Datetime( $row['date_target_pembahanan']);
                $pembahanan = new Datetime( $row['pembahanan']);
                $date_target_perakitan = new Datetime( $row['date_target_perakitan']);
                $perakitan = new Datetime( $row['perakitan']);
                $date_target_finishing = new Datetime( $row['date_target_finishing']);
                $finishing = new Datetime( $row['finishing']);
                $date_target_finish_good = new Datetime( $row['date_target_finish_good']);
                $finish_good = new Datetime( $row['finish_good']);
                $date_target_pengiriman = new Datetime( $row['date_target_pengiriman']);
                $pengiriman = new Datetime( $row['pengiriman']);
                $install = new Datetime( $row['install']);
                $detail_schedule = new Datetime( $row['detail_schedule']);

                if($row['process_date'] <> ""){
                    $process_date = date_create($row['process_date']);
                    $diff = date_diff($created_date,$process_date);
                    $lead_time_process = $diff->format("%a hari");
                } else {
                    $lead_time_process = "-";
                }

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $row['pic_p_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, $row['pic_w_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['pic_install_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, $row['project_definition']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, $row['project_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, $row['sales_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $row['production_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', $i, $row['so_vmk']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, $row['line_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, $row['workshop']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', $i, $row['box_no']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', $i, $row['packing_code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', $i, $row['grouping_code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', $i, $row['module']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', $i, $row['batch']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('15', $i, $row['material']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', $i, $row['description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('17', $i, (isset($row['weight']) ? $row['weight']." KG" : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('18', $i, $row['component_no']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('19', $i, $row['component_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('20', $i, $row['po_vmk']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('21', $i, $row['order_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', $i, $row['length']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('23', $i, $row['width']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('24', $i, $row['height']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', $i, $row['code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('26', $i, $row['code_information']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', $i, $row['floor']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('28', $i, $row['quantity']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('29', $i, $row['uom']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('30', $i, (isset($row['distribution_to_production_date']) ? PHPExcel_Shared_Date::PHPToExcel($distribution_to_production_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('31', $i, (isset($row['created_date']) ? PHPExcel_Shared_Date::PHPToExcel($created_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('32', $i, (isset($row['release_date']) ? PHPExcel_Shared_Date::PHPToExcel($release_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('33', $i, $row['pic_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('34', $i, $row['process']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('35', $i, $row['remark']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', $i, (isset($row['date_target_ppic']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_ppic) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('37', $i, (isset($row['ppic']) ? PHPExcel_Shared_Date::PHPToExcel($ppic) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('38', $i, (isset($row['ppic_qty']) ? $row['ppic_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('39', $i, (isset($row['ppic_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('40', $i, (isset($row['date_target_pembahanan']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_pembahanan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('41', $i, (isset($row['pembahanan']) ? PHPExcel_Shared_Date::PHPToExcel($pembahanan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('42', $i, (isset($row['pembahanan_qty']) ? $row['pembahanan_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('43', $i, (isset($row['pembahanan_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('44', $i, $row['machine']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('45', $i, (isset($row['date_target_perakitan']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_perakitan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('46', $i, $row['subcont_perakitan']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('47', $i, (isset($row['perakitan']) ? PHPExcel_Shared_Date::PHPToExcel($perakitan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('48', $i, (isset($row['perakitan_qty']) ? $row['perakitan_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('49', $i, (isset($row['perakitan_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('50', $i, (isset($row['date_target_finishing']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_finishing) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('51', $i, $row['subcont_finishing']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('52', $i, (isset($row['finishing']) ? PHPExcel_Shared_Date::PHPToExcel($finishing) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('53', $i, (isset($row['finishing_qty']) ? $row['finishing_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('54', $i, (isset($row['finishing_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('55', $i, (isset($row['date_target_finish_good']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_finish_good) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('56', $i, (isset($row['finish_good']) ? PHPExcel_Shared_Date::PHPToExcel($finish_good) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('57', $i, (isset($row['finish_good_qty']) ? $row['finish_good_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('58', $i, (isset($row['finish_good_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('59', $i, (isset($row['date_target_pengiriman']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_pengiriman) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('60', $i, (isset($row['pengiriman']) ? PHPExcel_Shared_Date::PHPToExcel($pengiriman) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('61', $i, (isset($row['pengiriman_qty']) ? $row['pengiriman_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('62', $i, (isset($row['pengiriman_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('63', $i, (isset($row['install']) ? PHPExcel_Shared_Date::PHPToExcel($install) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('64', $i, (isset($row['install_qty']) ? $row['install_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('65', $i, (isset($row['install_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('66', $i, $lead_time_process);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('67', $i, (isset($row['detail_schedule']) ? date('d-m-Y', strtotime($row['detail_schedule'])) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('68', $i, $row['information']);

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

            $this->excel->getActiveSheet()->getStyle('A1:BQ2')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A1:BQ'.($i-1))->applyFromArray($BStyle);
            $this->excel->getActiveSheet()->getStyle('A1:BQ'.($i-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
            $this->excel->getActiveSheet()->getStyle('P1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Q1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('R1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('S1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Y1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Z1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AA1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AB1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AC1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AD1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AE1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AF1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AG1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BL1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BM1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BN1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BO1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BP1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BQ1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AF')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AK')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AL')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AM')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AN')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AO')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AP')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AQ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AR')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AS')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AT')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AU')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AV')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AW')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AX')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AY')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AZ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BA')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BB')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BC')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BD')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BE')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BF')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BG')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BH')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BI')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BJ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BK')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BL')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BM')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BN')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BO')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BP')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BQ')->setWidth(25);

            $format = 'dd-mm-yyyy';
            $this->excel->getActiveSheet()->getStyle('AE3:AE'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AF3:AF'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AG3:AG'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AK3:AK'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AL3:AL'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AO3:AN'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AP3:AP'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AT3:AT'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AV3:AV'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AY3:AY'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BA3:BA'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BD3:BD'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BE3:BE'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BH3:BH'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BI3:BI'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BL3:BL'.($i-1))->getNumberFormat()->setFormatCode($format);
            //$this->excel->getActiveSheet()->getStyle('BO3:BO'.($i-1))->getNumberFormat()->setFormatCode($format);
        }else{
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', '1' , 'Ukuran');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('13','1','15','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', '1' , 'Kode');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('16','1','17','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', '1' , 'Target-Realisasi Produksi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('27','1','56','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', '1' , 'PIC P');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0','1','0','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', '1' , 'PIC W');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('1','1','1','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', '1' , 'PIC Install');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2','1','2','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', '1' , 'Project Def.');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('3','1','3','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', '1' , 'Project Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('4','1','4','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', '1' , 'Sales Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5','1','5','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', '1' , 'Production Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6','1','6','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', '1' , 'SO VMK');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7','1','7','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', '1' , 'Line Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8','1','8','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', '1' , 'No Komponen');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9','1','9','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', '1' , 'Nama Komponen');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('10','1','10','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', '1' , 'Purchase Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('11','1','11','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', '1' , 'Order Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('12','1','12','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', '2' , 'P');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', '2' , 'L');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('15', '2' , 'T');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', '2' , 'Kode');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('17', '2' , 'Keterangan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('18', '1' , 'LT');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('18','1','18','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('19', '1' , 'QTY');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('19','1','20','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('21', '1' , 'Tgl. Dist. ke Prod');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('21','1','21','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', '1' , 'Tgl CRTD');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('22','1','22','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('23', '1' , 'Tgl REL');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('23','1','23','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('24', '1' , 'PIC');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('24','1','24','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', '1' , 'Posisi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('25','1','25','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('26', '1' , 'Remark');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('26','1','26','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', '2' , 'Target PPIC');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('28', '2' , 'Realisasi PPIC');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('29', '2' , 'Qty PPIC');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('29','2','30','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('31', '2' , 'Target Pembahanan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('32', '2' , 'Realisasi Pembahanan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('33', '2' , 'Qty Pembahanan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('33','2','34','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('35', '2' , 'Mesin');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', '2' , 'Target Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('37', '2' , 'Subkon Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('38', '2' , 'Realisasi Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('39', '2' , 'Qty Perakitan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('39','2','40','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('41', '2' , 'Target Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('42', '2' , 'Subkon Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('43', '2' , 'Realisasi Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('44', '2' , 'Qty Finishing');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('44','2','45','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('46', '2' , 'Target Finish Good');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('47', '2' , 'Realisasi Finish Good');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('48', '2' , 'Qty Finish Good');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('48','2','49','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('50', '2' , 'Target Pengiriman');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('51', '2' , 'Realisasi Pengiriman');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('52', '2' , 'Qty Pengiriman');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('52','2','53','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('54', '2' , 'Realisasi Install');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('55', '2' , 'Qty Install');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('55','2','56','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('57', '1' , 'Lead Time Process');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('57','1','57','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('58', '1' , 'Detail Schedule');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('58','1','58','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('59', '1' , 'Keterangan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('59','1','59','2');

            $i = 3; // 1-based index
            $no=1;

            foreach($data_production_order as $row){
                $distribution_to_production_date = new Datetime( $row['distribution_to_production_date']);
                $created_date = new Datetime( $row['created_date']);
                $release_date = new Datetime( $row['release_date']);
                $date_target_ppic = new Datetime( $row['date_target_ppic']);
                $ppic = new Datetime( $row['ppic']);
                $date_target_pembahanan = new Datetime( $row['date_target_pembahanan']);
                $pembahanan = new Datetime( $row['pembahanan']);
                $date_target_perakitan = new Datetime( $row['date_target_perakitan']);
                $perakitan = new Datetime( $row['perakitan']);
                $date_target_finishing = new Datetime( $row['date_target_finishing']);
                $finishing = new Datetime( $row['finishing']);
                $date_target_finish_good = new Datetime( $row['date_target_finish_good']);
                $finish_good = new Datetime( $row['finish_good']);
                $date_target_pengiriman = new Datetime( $row['date_target_pengiriman']);
                $pengiriman = new Datetime( $row['pengiriman']);
                $install = new Datetime( $row['install']);
                $detail_schedule = new Datetime( $row['detail_schedule']);

                if($row['process_date'] <> ""){
                    $process_date = date_create($row['process_date']);
                    $diff = date_diff($created_date,$process_date);
                    $lead_time_process = $diff->format("%a hari");
                } else {
                    $lead_time_process = "-";
                }

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $row['pic_p_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, $row['pic_w_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['pic_install_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, $row['project_definition']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, $row['project_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, $row['sales_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $row['production_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', $i, $row['so_vmk']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, $row['line_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, $row['component_no']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', $i, $row['component_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', $i, $row['po_vmk']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', $i, $row['order_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', $i, $row['length']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', $i, $row['width']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('15', $i, $row['height']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', $i, $row['code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('17', $i, $row['code_information']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('18', $i, $row['floor']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('19', $i, $row['quantity']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('20', $i, $row['uom']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('21', $i, (isset($row['distribution_to_production_date']) ? PHPExcel_Shared_Date::PHPToExcel($distribution_to_production_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', $i, (isset($row['created_date']) ? PHPExcel_Shared_Date::PHPToExcel($created_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('23', $i, (isset($row['release_date']) ? PHPExcel_Shared_Date::PHPToExcel($release_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('24', $i, $row['pic_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', $i, $row['process']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('26', $i, $row['remark']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', $i, (isset($row['date_target_ppic']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_ppic) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('28', $i, (isset($row['ppic']) ? PHPExcel_Shared_Date::PHPToExcel($ppic) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('29', $i, (isset($row['ppic_qty']) ? $row['ppic_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('30', $i, (isset($row['ppic_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('31', $i, (isset($row['date_target_pembahanan']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_pembahanan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('32', $i, (isset($row['pembahanan']) ? PHPExcel_Shared_Date::PHPToExcel($pembahanan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('33', $i, (isset($row['pembahanan_qty']) ? $row['pembahanan_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('34', $i, (isset($row['pembahanan_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('35', $i, $row['machine']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', $i, (isset($row['date_target_perakitan']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_perakitan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('37', $i, $row['subcont_perakitan']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('38', $i, (isset($row['perakitan']) ? PHPExcel_Shared_Date::PHPToExcel($perakitan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('39', $i, (isset($row['perakitan_qty']) ? $row['perakitan_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('40', $i, (isset($row['perakitan_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('41', $i, (isset($row['date_target_finishing']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_finishing) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('42', $i, $row['subcont_finishing']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('43', $i, (isset($row['finishing']) ? PHPExcel_Shared_Date::PHPToExcel($finishing) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('44', $i, (isset($row['finishing_qty']) ? $row['finishing_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('45', $i, (isset($row['finishing_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('46', $i, (isset($row['date_target_finish_good']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_finish_good) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('47', $i, (isset($row['finish_good']) ? PHPExcel_Shared_Date::PHPToExcel($finish_good) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('48', $i, (isset($row['finish_good_qty']) ? $row['finish_good_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('49', $i, (isset($row['finish_good_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('50', $i, (isset($row['date_target_pengiriman']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_pengiriman) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('51', $i, (isset($row['pengiriman']) ? PHPExcel_Shared_Date::PHPToExcel($pengiriman) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('52', $i, (isset($row['pengiriman_qty']) ? $row['pengiriman_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('53', $i, (isset($row['pengiriman_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('54', $i, (isset($row['install']) ? PHPExcel_Shared_Date::PHPToExcel($install) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('55', $i, (isset($row['install_qty']) ? $row['install_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('56', $i, (isset($row['install_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('57', $i, $lead_time_process);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('58', $i, (isset($row['detail_schedule']) ? date('d-m-Y', strtotime($row['detail_schedule'])) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('59', $i, $row['information']);

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

            $this->excel->getActiveSheet()->getStyle('A1:BH2')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A1:BH'.($i-1))->applyFromArray($BStyle);
            $this->excel->getActiveSheet()->getStyle('A1:BH'.($i-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
            $this->excel->getActiveSheet()->getStyle('P1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Q1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('R1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('S1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('T1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('U1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('V1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('W1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('X1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BC1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BD1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BE1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BF1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BG1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BH1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AF')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AK')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AL')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AM')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AN')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AO')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AP')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AQ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AR')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AS')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AT')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AU')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AV')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AW')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AX')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AY')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AZ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BA')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BB')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BC')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BD')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BE')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BF')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BG')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BH')->setWidth(25);

            $format = 'dd-mm-yyyy';
            $this->excel->getActiveSheet()->getStyle('V3:V'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('W3:W'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('X3:X'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AB3:AB'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AC3:AC'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AF3:AF'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AG3:AG'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AK3:AK'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AM3:AM'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AP3:AP'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AR3:AR'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AU3:AU'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AV3:AV'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AY3:AY'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AZ3:AZ'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BC3:BC'.($i-1))->getNumberFormat()->setFormatCode($format);
            // $this->excel->getActiveSheet()->getStyle('BD3:BD'.($i-1))->getNumberFormat()->setFormatCode($format);
        }

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
        redirect('report/index');
    }

    function history($line_id){
        $data['list'] = $this->prodmon_model->get_history($line_id);

        //$data['main_content']='report/history';
        $this->load->view('report/history', $data);
    }

    function report_export(){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        
        $user_plant = $this->session->userdata('plant_prodmon');

        //$data['plant'] = $this->input->post('plant');
        $data['plant'] = "1200";

        $data['list_production_order'] = $this->prodmon_model->get_list_header_by_plant($data['plant']);
        $data['list_project'] = $this->prodmon_model->get_list_project_by_plant($data['plant']);
        $data['list_sales_order'] = $this->prodmon_model->get_list_sales_order_by_plant($data['plant']);
        $data['list_po_vmk'] = $this->prodmon_model->get_list_po_vmk_by_plant($data['plant']);
        
        $data['main_content']='report/report_export';
        $this->load->view('template/main', $data);
    }

    function report_export_data(){
        $user = $this->session->userdata('nik_prodmon');
        $production_order1 = $this->input->post('production_order1');
        $production_order2 = $this->input->post('production_order2');
        $production_order3 = $this->input->post('production_order3');
        $project_definition1 = $this->input->post('project_definition1');
        $project_definition2 = $this->input->post('project_definition2');
        $project_definition3 = $this->input->post('project_definition3');
        $sales_order1 = $this->input->post('sales_order1');
        $sales_order2 = $this->input->post('sales_order2');
        $sales_order3 = $this->input->post('sales_order3');
        $basic_date_start = $this->input->post('tgl1');
        $basic_date_end = $this->input->post('tgl2');
        $po_vmk = $this->input->post('po_vmk');
        $so_vmk = $this->input->post('so_vmk');
        $plant = $this->input->post('plant');

        if($production_order1 == "" && $production_order2 == "" && $production_order3 == "" && $project_definition1 == "" && $project_definition2 == "" && $project_definition3 == "" && $sales_order1 == "" && $sales_order2 == "" && $sales_order3 == "" && $basic_date_start == "" && $basic_date_end == "" && $po_vmk == "" && $so_vmk=""){

            $this->session->set_flashdata('msg','<div class="alert alert-warning">
                <a class="close" data-dismiss="alert"></a>
                Please input a value
            </div>'); 
            redirect('report/index');
        }

        $query = "";

        if(!empty($production_order3)){
            $production_order_str = "'" . implode("','", $production_order3) . "'";
            $query = "production_order_header.production_order IN ($production_order_str) ";
        } else {
            if($production_order1 <> "" && $production_order2 == ""){
                $query = "production_order_header.production_order = '$production_order1' ";
            } elseif($production_order2 <> "" && $production_order1 == ""){
                $query = "production_order_header.production_order = '$production_order2' ";
            } elseif($production_order1 <> "" && $production_order2 <> "") {
                $query = "production_order_header.production_order >= '$production_order1' AND production_order_header.production_order <= '$production_order2' ";
            }
        }

        if(!empty($project_definition3)){
            if($query <> "")
                $query = $query."AND ";
            $project_definition_str = "'" . implode("','", $project_definition3) . "'";
            $query = $query."production_order_header.project_definition IN ($project_definition_str) ";
        } else {
            if($project_definition1 <> "" && $project_definition2 == ""){
                if($query <> "")
                    $query = $query."AND ";
                $query = $query."production_order_header.project_definition = '$project_definition1' ";
            } elseif($project_definition2 <> "" && $project_definition1 == ""){
                if($query <> "")
                    $query = $query."AND ";
                $query = $query."production_order_header.project_definition = '$project_definition2' ";
            } elseif($project_definition1 <> "" && $project_definition2 <> "") {
                if($query <> "")
                    $query = $query."AND ";
                $project_definition1_str = substr($project_definition1, 4, 5);
                $project_definition2_str = substr($project_definition2, 4, 5);
                $query = $query."SUBSTR(production_order_header.project_definition,5,5) >= '$project_definition1_str' AND SUBSTR(production_order_header.project_definition,5,5) <= '$project_definition2_str' ";
            }
        }

        if(!empty($sales_order3)){
            $sales_order_str = "'" . implode("','", $sales_order3) . "'";
            $query = "production_order_header.sales_order IN ($sales_order_str) ";
        } else {
            if($sales_order1 <> "" && $sales_order2 == ""){
                $query = "production_order_header.sales_order = '$sales_order1' ";
            } elseif($sales_order2 <> "" && $sales_order1 == ""){
                $query = "production_order_header.sales_order = '$sales_order2' ";
            } elseif($sales_order1 <> "" && $sales_order2 <> "") {
                $query = "production_order_header.sales_order >= '$sales_order1' AND production_order_header.sales_order <= '$sales_order2' ";
            }
        }

        if($basic_date_start <> "" && $basic_date_end == ""){
            $basic_date_start = date('Y-m-d', strtotime($basic_date_start));
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.basic_date_start >= '$basic_date_start' ";
        } elseif($basic_date_end <> "" && $basic_date_start == ""){
            $basic_date_end = date('Y-m-d', strtotime($basic_date_end));
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.basic_date_end <= '$basic_date_end' ";
        } elseif($basic_date_start <> "" && $basic_date_end <> "") {
            $basic_date_start = date('Y-m-d', strtotime($basic_date_start));
            $basic_date_end = date('Y-m-d', strtotime($basic_date_end));
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.basic_date_start >= '$basic_date_start' AND production_order_header.basic_date_end <= '$basic_date_end' ";
        }

        if(!empty($po_vmk)){
            $po_vmk_str = "'" . implode("','", $po_vmk) . "'";
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_item.po_vmk IN ($po_vmk_str) ";
        }

        if(!empty($so_vmk)){
            $so_vmk_str = "'" . implode("','", $so_vmk) . "'";
            if($query <> "")
                $query = $query."AND ";
            $query = $query."production_order_header.so_vmk IN ($so_vmk_str) ";
        }

        if($query <> "")
            $query = $query."AND ";
        $query = $query."production_order_header.plant = '$plant' ";

        $production_order = $this->prodmon_model->get_data_report_production_order($query);

        $production_order_arr = array();

        foreach($production_order as $row){
            array_push($production_order_arr, $row['production_order']);

            $count_row = $this->prodmon_model->count_report_production_order($row['production_order'], $row['line_item']);
            if($count_row == 0){
                $data = $this->prodmon_model->get_data_report_by_production_order($row['production_order'], $row['line_item']);
                
                $uom = $data['uom'] <> "" ? $data['uom'] : $data['uom_header'];

                $insert_data = array(
                    'project_definition'                => $data['project_definition'],
                    'project_description'               => $data['project_description'],
                    'sales_order'                       => $data['sales_order'],
                    'production_order'                  => $data['production_order'],
                    'line_item'                         => $data['line_item'],
                    'line_description'                  => $data['line_description'],
                    'workshop'                          => $data['workshop'],
                    'box_no'                            => $data['box_no'],
                    'packing_code'                      => $data['packing_code'],
                    'grouping_code'                     => $data['grouping_code'],
                    'module'                            => $data['module'],
                    'batch'                             => $data['batch'],
                    'material'                          => $data['material'],
                    'weight'                            => $data['weight'],
                    'component_no'                      => $data['component_no'],
                    'component_name'                    => $data['component_name'],
                    'po_vmk'                            => $data['po_vmk'],
                    'production_order_item_id'          => $data['production_order_item_id'],
                    'order_description'                 => $data['order_description'],
                    'length'                            => $data['length'],
                    'width'                             => $data['width'],
                    'height'                            => $data['height'],
                    'created_date'                      => $data['created_date'],
                    'release_date'                      => $data['release_date'],
                    'code'                              => $data['code'],
                    'code_information'                  => $data['code_information'],
                    'floor'                             => $data['floor'],
                    'description'                       => $data['description'],
                    'quantity'                          => $data['quantity'],
                    'uom'                               => $uom,
                    'process'                           => $data['process'],
                    'process_date'                      => $data['process_date'],
                    'remark'                            => $data['remark'],
                    'date_target_ppic'                  => $data['date_target_ppic'],
                    'ppic'                              => $data['ppic'],
                    'ppic_qty'                          => $data['ppic_qty'],
                    'date_target_pembahanan'            => $data['date_target_pembahanan'],
                    'pembahanan'                        => $data['pembahanan'],
                    'pembahanan_qty'                    => $data['pembahanan_qty'],
                    'machine'                           => $data['machine'],
                    'date_target_perakitan'             => $data['date_target_perakitan'],
                    'subcont_perakitan'                 => $data['subcont_perakitan'],
                    'perakitan'                         => $data['perakitan'],
                    'perakitan_qty'                     => $data['perakitan_qty'],
                    'date_target_finishing'             => $data['date_target_finishing'],
                    'subcont_finishing'                 => $data['subcont_finishing'],
                    'finishing'                         => $data['finishing'],
                    'finishing_qty'                     => $data['finishing_qty'],
                    'date_target_finish_good'           => $data['date_target_finish_good'],
                    'finish_good'                       => $data['finish_good'],
                    'finish_good_qty'                   => $data['finish_good_qty'],
                    'date_target_pengiriman'            => $data['date_target_pengiriman'],
                    'pengiriman'                        => $data['pengiriman'],
                    'pengiriman_qty'                    => $data['pengiriman_qty'],
                    'install'                           => $data['install'],
                    'install_qty'                       => $data['install_qty'],
                    'pic_p_name'                        => $data['pic_p_name'],
                    'pic_w_name'                        => $data['pic_w_name'],
                    'pic_install_name'                  => $data['pic_install_name'],
                    'pic_name'                          => $data['pic_name'],
                    'distribution_to_production_date'   => $data['distribution_to_production_date'],
                    'detail_schedule'                   => $data['detail_schedule'],
                    'information'                       => $data['information'],
                    'so_vmk'                            => $data['so_vmk'],
                );

                $insert = $this->prodmon_model->insert_report($insert_data);
            }else{
                $quantity_last_update = $this->prodmon_model->get_latest_quantity($row['production_order_item_id']);
                $data_report = $this->prodmon_model->get_report_updated_at($row['production_order'], $row['line_item']);

                if(strtotime($quantity_last_update['updated_at']) > strtotime($data_report['updated_at'])){
                    $delete_report = $this->prodmon_model->delete_report($row['production_order'], $row['line_item']);

                    $data = $this->prodmon_model->get_data_report_by_production_order($row['production_order'], $row['line_item']);

                    $uom = $data['uom'] <> "" ? $data['uom'] : $data['uom_header'];

                    $insert_data = array(
                        'project_definition'                => $data['project_definition'],
                        'project_description'               => $data['project_description'],
                        'sales_order'                       => $data['sales_order'],
                        'production_order'                  => $data['production_order'],
                        'line_item'                         => $data['line_item'],
                        'line_description'                  => $data['line_description'],
                        'workshop'                          => $data['workshop'],
                        'box_no'                            => $data['box_no'],
                        'packing_code'                      => $data['packing_code'],
                        'grouping_code'                     => $data['grouping_code'],
                        'module'                            => $data['module'],
                        'batch'                             => $data['batch'],
                        'material'                          => $data['material'],
                        'weight'                            => $data['weight'],
                        'component_no'                      => $data['component_no'],
                        'component_name'                    => $data['component_name'],
                        'po_vmk'                            => $data['po_vmk'],
                        'production_order_item_id'          => $data['production_order_item_id'],
                        'order_description'                 => $data['order_description'],
                        'length'                            => $data['length'],
                        'width'                             => $data['width'],
                        'height'                            => $data['height'],
                        'created_date'                      => $data['created_date'],
                        'release_date'                      => $data['release_date'],
                        'code'                              => $data['code'],
                        'code_information'                  => $data['code_information'],
                        'floor'                             => $data['floor'],
                        'description'                       => $data['description'],
                        'quantity'                          => $data['quantity'],
                        'uom'                               => $uom,
                        'process'                           => $data['process'],
                        'process_date'                      => $data['process_date'],
                        'remark'                            => $data['remark'],
                        'date_target_ppic'                  => $data['date_target_ppic'],
                        'ppic'                              => $data['ppic'],
                        'ppic_qty'                          => $data['ppic_qty'],
                        'date_target_pembahanan'            => $data['date_target_pembahanan'],
                        'pembahanan'                        => $data['pembahanan'],
                        'pembahanan_qty'                    => $data['pembahanan_qty'],
                        'machine'                           => $data['machine'],
                        'date_target_perakitan'             => $data['date_target_perakitan'],
                        'subcont_perakitan'                 => $data['subcont_perakitan'],
                        'perakitan'                         => $data['perakitan'],
                        'perakitan_qty'                     => $data['perakitan_qty'],
                        'date_target_finishing'             => $data['date_target_finishing'],
                        'subcont_finishing'                 => $data['subcont_finishing'],
                        'finishing'                         => $data['finishing'],
                        'finishing_qty'                     => $data['finishing_qty'],
                        'date_target_finish_good'           => $data['date_target_finish_good'],
                        'finish_good'                       => $data['finish_good'],
                        'finish_good_qty'                   => $data['finish_good_qty'],
                        'date_target_pengiriman'            => $data['date_target_pengiriman'],
                        'pengiriman'                        => $data['pengiriman'],
                        'pengiriman_qty'                    => $data['pengiriman_qty'],
                        'install'                           => $data['install'],
                        'install_qty'                       => $data['install_qty'],
                        'pic_p_name'                        => $data['pic_p_name'],
                        'pic_w_name'                        => $data['pic_w_name'],
                        'pic_install_name'                  => $data['pic_install_name'],
                        'pic_name'                          => $data['pic_name'],
                        'distribution_to_production_date'   => $data['distribution_to_production_date'],
                        'detail_schedule'                   => $data['detail_schedule'],
                        'information'                       => $data['information'],
                        'so_vmk'                            => $data['so_vmk']
                    );

                    $insert = $this->prodmon_model->insert_report($insert_data);
                }
            }
        }

        $str_production_order = "'".implode("','", $production_order_arr)."'";
        $data_production_order = $this->prodmon_model->get_data_report_new($str_production_order);

        //$data_production_order = $this->prodmon_model->get_data_report($query);
        // var_dump($plant);
        // exit();
        
        $namaTitle = "Report Production Order";
        $namaFile = $namaTitle.".xls";

        $file_name = "report-production-order-".date('YmdHis').'.xls';

        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle($namaTitle);
        //set cell A1 content with some text

        if($plant == "1600"){
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', '1' , 'Ukuran');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('22','1','24','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', '1' , 'Kode');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('25','1','26','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', '1' , 'Target-Realisasi Produksi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('36','1','65','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', '1' , 'PIC P');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0','1','0','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', '1' , 'PIC W');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('1','1','1','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', '1' , 'PIC Install');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2','1','2','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', '1' , 'Project Def.');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('3','1','3','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', '1' , 'Project Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('4','1','4','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', '1' , 'Sales Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5','1','5','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', '1' , 'Production Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6','1','6','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', '1' , 'SO VMK');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7','1','7','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', '1' , 'Line Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8','1','8','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', '1' , 'Workshop');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9','1','9','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', '1' , 'No Box');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('10','1','10','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', '1' , 'Kode Packing');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('11','1','11','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', '1' , 'Kode Grouping');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('12','1','12','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', '1' , 'Modul');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('13','1','13','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', '1' , 'Batch');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('14','1','14','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('15', '1' , 'Material');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('15','1','15','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', '1' , 'Deskripsi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('16','1','16','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('17', '1' , 'Weight');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('17','1','17','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('18', '1' , 'No Komponen');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('18','1','18','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('19', '1' , 'Nama Komponen');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('19','1','19','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('20', '1' , 'Purchase Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('20','1','20','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('21', '1' , 'Order Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('21','1','21','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', '2' , 'P');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('23', '2' , 'L');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('24', '2' , 'T');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', '2' , 'Kode');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('26', '2' , 'Keterangan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', '1' , 'LT');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('27','1','27','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('28', '1' , 'QTY');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('28','1','29','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('30', '1' , 'Tgl. Dist. ke Prod');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('30','1','30','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('31', '1' , 'Tgl CRTD');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('31','1','31','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('32', '1' , 'Tgl REL');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('32','1','32','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('33', '1' , 'PIC');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('33','1','33','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('34', '1' , 'Posisi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('34','1','34','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('35', '1' , 'Remark');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('35','1','35','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', '2' , 'Target PPIC');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('37', '2' , 'Realisasi PPIC');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('38', '2' , 'Qty PPIC');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('38','2','39','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('40', '2' , 'Target Pembahanan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('41', '2' , 'Realisasi Pembahanan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('42', '2' , 'Qty Pembahanan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('42','2','43','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('44', '2' , 'Mesin');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('45', '2' , 'Target Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('46', '2' , 'Subkon Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('47', '2' , 'Realisasi Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('48', '2' , 'Qty Perakitan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('48','2','49','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('50', '2' , 'Target Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('51', '2' , 'Subkon Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('52', '2' , 'Realisasi Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('53', '2' , 'Qty Finishing');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('53','2','54','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('55', '2' , 'Target Finish Good');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('56', '2' , 'Realisasi Finish Good');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('57', '2' , 'Qty Finish Good');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('57','2','58','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('59', '2' , 'Target Pengiriman');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('60', '2' , 'Realisasi Pengiriman');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('61', '2' , 'Qty Pengiriman');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('61','2','62','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('63', '2' , 'Realisasi Install');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('64', '2' , 'Qty Install');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('64','2','65','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('66', '1' , 'Lead Time Process');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('66','1','66','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('67', '1' , 'Detail Schedule');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('67','1','67','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('68', '1' , 'Keterangan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('68','1','68','2');

            $i = 3; // 1-based index
            $no=1;

            foreach($data_production_order as $row){
                $distribution_to_production_date = new Datetime( $row['distribution_to_production_date']);
                $created_date = new Datetime( $row['created_date']);
                $release_date = new Datetime( $row['release_date']);
                $date_target_ppic = new Datetime( $row['date_target_ppic']);
                $ppic = new Datetime( $row['ppic']);
                $date_target_pembahanan = new Datetime( $row['date_target_pembahanan']);
                $pembahanan = new Datetime( $row['pembahanan']);
                $date_target_perakitan = new Datetime( $row['date_target_perakitan']);
                $perakitan = new Datetime( $row['perakitan']);
                $date_target_finishing = new Datetime( $row['date_target_finishing']);
                $finishing = new Datetime( $row['finishing']);
                $date_target_finish_good = new Datetime( $row['date_target_finish_good']);
                $finish_good = new Datetime( $row['finish_good']);
                $date_target_pengiriman = new Datetime( $row['date_target_pengiriman']);
                $pengiriman = new Datetime( $row['pengiriman']);
                $install = new Datetime( $row['install']);
                $detail_schedule = new Datetime( $row['detail_schedule']);

                if($row['process_date'] <> ""){
                    $process_date = date_create($row['process_date']);
                    $diff = date_diff($created_date,$process_date);
                    $lead_time_process = $diff->format("%a hari");
                } else {
                    $lead_time_process = "-";
                }

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $row['pic_p_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, $row['pic_w_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['pic_install_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, $row['project_definition']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, $row['project_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, $row['sales_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $row['production_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', $i, $row['so_vmk']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, $row['line_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, $row['workshop']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', $i, $row['box_no']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', $i, $row['packing_code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', $i, $row['grouping_code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', $i, $row['module']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', $i, $row['batch']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('15', $i, $row['material']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', $i, $row['description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('17', $i, (isset($row['weight']) ? $row['weight']." KG" : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('18', $i, $row['component_no']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('19', $i, $row['component_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('20', $i, $row['po_vmk']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('21', $i, $row['order_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', $i, $row['length']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('23', $i, $row['width']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('24', $i, $row['height']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', $i, $row['code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('26', $i, $row['code_information']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', $i, $row['floor']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('28', $i, $row['quantity']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('29', $i, $row['uom']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('30', $i, (isset($row['distribution_to_production_date']) ? PHPExcel_Shared_Date::PHPToExcel($distribution_to_production_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('31', $i, (isset($row['created_date']) ? PHPExcel_Shared_Date::PHPToExcel($created_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('32', $i, (isset($row['release_date']) ? PHPExcel_Shared_Date::PHPToExcel($release_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('33', $i, $row['pic_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('34', $i, $row['process']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('35', $i, $row['remark']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', $i, (isset($row['date_target_ppic']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_ppic) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('37', $i, (isset($row['ppic']) ? PHPExcel_Shared_Date::PHPToExcel($ppic) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('38', $i, (isset($row['ppic_qty']) ? $row['ppic_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('39', $i, (isset($row['ppic_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('40', $i, (isset($row['date_target_pembahanan']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_pembahanan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('41', $i, (isset($row['pembahanan']) ? PHPExcel_Shared_Date::PHPToExcel($pembahanan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('42', $i, (isset($row['pembahanan_qty']) ? $row['pembahanan_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('43', $i, (isset($row['pembahanan_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('44', $i, $row['machine']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('45', $i, (isset($row['date_target_perakitan']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_perakitan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('46', $i, $row['subcont_perakitan']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('47', $i, (isset($row['perakitan']) ? PHPExcel_Shared_Date::PHPToExcel($perakitan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('48', $i, (isset($row['perakitan_qty']) ? $row['perakitan_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('49', $i, (isset($row['perakitan_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('50', $i, (isset($row['date_target_finishing']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_finishing) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('51', $i, $row['subcont_finishing']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('52', $i, (isset($row['finishing']) ? PHPExcel_Shared_Date::PHPToExcel($finishing) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('53', $i, (isset($row['finishing_qty']) ? $row['finishing_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('54', $i, (isset($row['finishing_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('55', $i, (isset($row['date_target_finish_good']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_finish_good) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('56', $i, (isset($row['finish_good']) ? PHPExcel_Shared_Date::PHPToExcel($finish_good) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('57', $i, (isset($row['finish_good_qty']) ? $row['finish_good_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('58', $i, (isset($row['finish_good_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('59', $i, (isset($row['date_target_pengiriman']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_pengiriman) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('60', $i, (isset($row['pengiriman']) ? PHPExcel_Shared_Date::PHPToExcel($pengiriman) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('61', $i, (isset($row['pengiriman_qty']) ? $row['pengiriman_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('62', $i, (isset($row['pengiriman_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('63', $i, (isset($row['install']) ? PHPExcel_Shared_Date::PHPToExcel($install) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('64', $i, (isset($row['install_qty']) ? $row['install_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('65', $i, (isset($row['install_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('66', $i, $lead_time_process);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('67', $i, (isset($row['detail_schedule']) ? date('d-m-Y', strtotime($row['detail_schedule'])) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('68', $i, $row['information']);

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

            $this->excel->getActiveSheet()->getStyle('A1:BQ2')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A1:BQ'.($i-1))->applyFromArray($BStyle);
            $this->excel->getActiveSheet()->getStyle('A1:BQ'.($i-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
            $this->excel->getActiveSheet()->getStyle('P1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Q1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('R1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('S1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Y1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Z1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AA1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AB1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AC1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AD1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AE1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AF1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('AG1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BL1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BM1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BN1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BO1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BP1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BQ1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AF')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AK')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AL')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AM')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AN')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AO')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AP')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AQ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AR')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AS')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AT')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AU')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AV')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AW')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AX')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AY')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AZ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BA')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BB')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BC')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BD')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BE')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BF')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BG')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BH')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BI')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BJ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BK')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BL')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BM')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BN')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BO')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BP')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BQ')->setWidth(25);

            $format = 'dd-mm-yyyy';
            $this->excel->getActiveSheet()->getStyle('AE3:AE'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AF3:AF'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AG3:AG'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AK3:AK'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AL3:AL'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AO3:AN'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AP3:AP'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AT3:AT'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AV3:AV'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AY3:AY'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BA3:BA'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BD3:BD'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BE3:BE'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BH3:BH'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BI3:BI'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BL3:BL'.($i-1))->getNumberFormat()->setFormatCode($format);
            //$this->excel->getActiveSheet()->getStyle('BO3:BO'.($i-1))->getNumberFormat()->setFormatCode($format);
        }else{
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', '1' , 'Ukuran');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('13','1','15','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', '1' , 'Kode');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('16','1','17','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', '1' , 'Target-Realisasi Produksi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('27','1','56','1');

            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', '1' , 'PIC P');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('0','1','0','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', '1' , 'PIC W');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('1','1','1','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', '1' , 'PIC Install');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('2','1','2','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', '1' , 'Project Def.');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('3','1','3','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', '1' , 'Project Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('4','1','4','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', '1' , 'Sales Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('5','1','5','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', '1' , 'Production Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('6','1','6','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', '1' , 'SO VMK');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('7','1','7','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', '1' , 'Line Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('8','1','8','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', '1' , 'No Komponen');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('9','1','9','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', '1' , 'Nama Komponen');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('10','1','10','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', '1' , 'Purchase Order');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('11','1','11','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', '1' , 'Order Description');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('12','1','12','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', '2' , 'P');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', '2' , 'L');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('15', '2' , 'T');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', '2' , 'Kode');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('17', '2' , 'Keterangan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('18', '1' , 'LT');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('18','1','18','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('19', '1' , 'QTY');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('19','1','20','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('21', '1' , 'Tgl. Dist. ke Prod');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('21','1','21','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', '1' , 'Tgl CRTD');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('22','1','22','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('23', '1' , 'Tgl REL');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('23','1','23','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('24', '1' , 'PIC');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('24','1','24','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', '1' , 'Posisi');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('25','1','25','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('26', '1' , 'Remark');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('26','1','26','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', '2' , 'Target PPIC');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('28', '2' , 'Realisasi PPIC');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('29', '2' , 'Qty PPIC');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('29','2','30','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('31', '2' , 'Target Pembahanan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('32', '2' , 'Realisasi Pembahanan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('33', '2' , 'Qty Pembahanan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('33','2','34','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('35', '2' , 'Mesin');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', '2' , 'Target Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('37', '2' , 'Subkon Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('38', '2' , 'Realisasi Perakitan');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('39', '2' , 'Qty Perakitan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('39','2','40','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('41', '2' , 'Target Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('42', '2' , 'Subkon Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('43', '2' , 'Realisasi Finishing');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('44', '2' , 'Qty Finishing');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('44','2','45','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('46', '2' , 'Target Finish Good');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('47', '2' , 'Realisasi Finish Good');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('48', '2' , 'Qty Finish Good');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('48','2','49','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('50', '2' , 'Target Pengiriman');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('51', '2' , 'Realisasi Pengiriman');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('52', '2' , 'Qty Pengiriman');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('52','2','53','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('54', '2' , 'Realisasi Install');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('55', '2' , 'Qty Install');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('55','2','56','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('57', '1' , 'Lead Time Process');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('57','1','57','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('58', '1' , 'Detail Schedule');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('58','1','58','2');
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('59', '1' , 'Keterangan');
            $this->excel->getActiveSheet()->mergeCellsByColumnAndRow('59','1','59','2');

            $i = 3; // 1-based index
            $no=1;

            foreach($data_production_order as $row){
                $distribution_to_production_date = new Datetime( $row['distribution_to_production_date']);
                $created_date = new Datetime( $row['created_date']);
                $release_date = new Datetime( $row['release_date']);
                $date_target_ppic = new Datetime( $row['date_target_ppic']);
                $ppic = new Datetime( $row['ppic']);
                $date_target_pembahanan = new Datetime( $row['date_target_pembahanan']);
                $pembahanan = new Datetime( $row['pembahanan']);
                $date_target_perakitan = new Datetime( $row['date_target_perakitan']);
                $perakitan = new Datetime( $row['perakitan']);
                $date_target_finishing = new Datetime( $row['date_target_finishing']);
                $finishing = new Datetime( $row['finishing']);
                $date_target_finish_good = new Datetime( $row['date_target_finish_good']);
                $finish_good = new Datetime( $row['finish_good']);
                $date_target_pengiriman = new Datetime( $row['date_target_pengiriman']);
                $pengiriman = new Datetime( $row['pengiriman']);
                $install = new Datetime( $row['install']);
                $detail_schedule = new Datetime( $row['detail_schedule']);

                if($row['process_date'] <> ""){
                    $process_date = date_create($row['process_date']);
                    $diff = date_diff($created_date,$process_date);
                    $lead_time_process = $diff->format("%a hari");
                } else {
                    $lead_time_process = "-";
                }

                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $row['pic_p_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, $row['pic_w_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['pic_install_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, $row['project_definition']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, $row['project_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, $row['sales_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $row['production_order']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', $i, $row['so_vmk']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('8', $i, $row['line_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('9', $i, $row['component_no']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('10', $i, $row['component_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('11', $i, $row['po_vmk']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('12', $i, $row['order_description']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('13', $i, $row['length']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('14', $i, $row['width']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('15', $i, $row['height']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('16', $i, $row['code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('17', $i, $row['code_information']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('18', $i, $row['floor']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('19', $i, $row['quantity']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('20', $i, $row['uom']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('21', $i, (isset($row['distribution_to_production_date']) ? PHPExcel_Shared_Date::PHPToExcel($distribution_to_production_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('22', $i, (isset($row['created_date']) ? PHPExcel_Shared_Date::PHPToExcel($created_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('23', $i, (isset($row['release_date']) ? PHPExcel_Shared_Date::PHPToExcel($release_date) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('24', $i, $row['pic_name']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('25', $i, $row['process']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('26', $i, $row['remark']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('27', $i, (isset($row['date_target_ppic']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_ppic) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('28', $i, (isset($row['ppic']) ? PHPExcel_Shared_Date::PHPToExcel($ppic) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('29', $i, (isset($row['ppic_qty']) ? $row['ppic_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('30', $i, (isset($row['ppic_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('31', $i, (isset($row['date_target_pembahanan']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_pembahanan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('32', $i, (isset($row['pembahanan']) ? PHPExcel_Shared_Date::PHPToExcel($pembahanan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('33', $i, (isset($row['pembahanan_qty']) ? $row['pembahanan_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('34', $i, (isset($row['pembahanan_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('35', $i, $row['machine']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('36', $i, (isset($row['date_target_perakitan']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_perakitan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('37', $i, $row['subcont_perakitan']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('38', $i, (isset($row['perakitan']) ? PHPExcel_Shared_Date::PHPToExcel($perakitan) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('39', $i, (isset($row['perakitan_qty']) ? $row['perakitan_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('40', $i, (isset($row['perakitan_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('41', $i, (isset($row['date_target_finishing']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_finishing) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('42', $i, $row['subcont_finishing']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('43', $i, (isset($row['finishing']) ? PHPExcel_Shared_Date::PHPToExcel($finishing) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('44', $i, (isset($row['finishing_qty']) ? $row['finishing_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('45', $i, (isset($row['finishing_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('46', $i, (isset($row['date_target_finish_good']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_finish_good) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('47', $i, (isset($row['finish_good']) ? PHPExcel_Shared_Date::PHPToExcel($finish_good) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('48', $i, (isset($row['finish_good_qty']) ? $row['finish_good_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('49', $i, (isset($row['finish_good_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('50', $i, (isset($row['date_target_pengiriman']) ? PHPExcel_Shared_Date::PHPToExcel($date_target_pengiriman) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('51', $i, (isset($row['pengiriman']) ? PHPExcel_Shared_Date::PHPToExcel($pengiriman) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('52', $i, (isset($row['pengiriman_qty']) ? $row['pengiriman_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('53', $i, (isset($row['pengiriman_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('54', $i, (isset($row['install']) ? PHPExcel_Shared_Date::PHPToExcel($install) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('55', $i, (isset($row['install_qty']) ? $row['install_qty'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('56', $i, (isset($row['install_qty']) ? $row['uom'] : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('57', $i, $lead_time_process);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('58', $i, (isset($row['detail_schedule']) ? date('d-m-Y', strtotime($row['detail_schedule'])) : ""));
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('59', $i, $row['information']);

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

            $this->excel->getActiveSheet()->getStyle('A1:BH2')->getFont()->setBold(true);
            $this->excel->getActiveSheet()->getStyle('A1:BH'.($i-1))->applyFromArray($BStyle);
            $this->excel->getActiveSheet()->getStyle('A1:BH'.($i-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
            $this->excel->getActiveSheet()->getStyle('P1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('Q1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('R1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('S1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('T1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('U1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('V1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('W1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('X1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BC1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BD1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BE1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BF1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BG1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('BH1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

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
            $this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('W')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('X')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Y')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('Z')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AA')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AB')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AC')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AD')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AE')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AF')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AG')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AH')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AI')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AJ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AK')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AL')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AM')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AN')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AO')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AP')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AQ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AR')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AS')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AT')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AU')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AV')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AW')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AX')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AY')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('AZ')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BA')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BB')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BC')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BD')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BE')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BF')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BG')->setWidth(25);
            $this->excel->getActiveSheet()->getColumnDimension('BH')->setWidth(25);

            $format = 'dd-mm-yyyy';
            $this->excel->getActiveSheet()->getStyle('V3:V'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('W3:W'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('X3:X'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AB3:AB'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AC3:AC'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AF3:AF'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AG3:AG'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AK3:AK'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AM3:AM'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AP3:AP'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AR3:AR'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AU3:AU'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AV3:AV'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AY3:AY'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('AZ3:AZ'.($i-1))->getNumberFormat()->setFormatCode($format);
            $this->excel->getActiveSheet()->getStyle('BC3:BC'.($i-1))->getNumberFormat()->setFormatCode($format);
            // $this->excel->getActiveSheet()->getStyle('BD3:BD'.($i-1))->getNumberFormat()->setFormatCode($format);
        }

        // header('Content-Type: application/vnd.ms-excel'); //mime type
        // header('Content-Disposition: attachment;filename="'.$namaFile.'"'); //tell browser what's the file name
        // header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        ob_end_clean();
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save(str_replace(__FILE__,'assets/storage/excel/'.$file_name,__FILE__));

        $file = base_url('assets/storage/excel/'.$file_name);
        $uploaded = $this->gdriveupload->uploadToClient($file, 'vivere-prodmon', $file_name);

        $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Data berhasil diexport ke account Google Drive Anda. 
                <a href="https://drive.google.com/file/d/'.$uploaded.'/view?usp=drivesdk" target="_blank">Klik disini</a>
            </div>');
        redirect('report/index');
    }

    function panel_logistic($batch = NULL, $production_order = NULL){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $this->session->set_userdata('redirect_url', '/report/panel_logistic');
        $client = $this->gdriveupload->getClient();

        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');
        $hide_header = 1;

        if($this->session->userdata('role_prodmon') == '5' || $this->session->userdata('role_prodmon') == '6' || $this->session->userdata('role_prodmon') == '8'){
            
            if($production_order == ""){
                $hide_header = 0;
                $production_order = $this->input->get('production_order');
            }

            if($batch == "")
                $batch = $this->input->get('batch');

            if($production_order <> ""){
                $data['list_production_order'] = $this->prodmon_model->get_production_order_by_batch($batch);
                $data['packing_code'] = $this->prodmon_model->get_report_panel_logistic($production_order);
                $hide_header = 0;
            }

            $data['batch'] = $batch;
            $data['production_order'] = $production_order;
            $data['hide_header'] = $hide_header;

            $data['main_content']='report/panel_logistic';
            $this->load->view('template/main', $data);
        }else{
            redirect('site/index');
        }
    }

    function export_panel_logistic(){
        $production_order = $this->input->post('production_order');
        
        $packing_code = $this->prodmon_model->get_report_panel_logistic($production_order);

        $namaTitle = "Panel Logistic - ".$production_order;

        $namaFile = $namaTitle.".xls";

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
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', '1' , 'REMARK');
        $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', '1' , 'REJECT REASON');
        

        $i = 2; // 1-based index
        $no=1;

        foreach($packing_code as $row){
            
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('0', $i, $no);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('1', $i, $row['production_order']);
            $this->excel->getActiveSheet()->setCellValueByColumnAndRow('2', $i, $row['module']);

            if($row['is_panel'] == "1"){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, $row['packing_code']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, "");
            }else{
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('3', $i, "");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('4', $i, $row['packing_code']);
            }

            if($row['reject_reason'] == ""){
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "Approved");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, "");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', $i, "");
            }else{
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('5', $i, "Reject");
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('6', $i, $row['remark']);
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow('7', $i, base_url()."/assets/img/panel-logistic/".$row['reject_reason']);
            }
                
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

        $this->excel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1:H'.($i-1))->applyFromArray($BStyle);
        $this->excel->getActiveSheet()->getStyle('A1:H'.($i-1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(100);

        // header('Content-Type: application/vnd.ms-excel'); //mime type
        // header('Content-Disposition: attachment;filename="'.$namaFile.'"'); //tell browser what's the file name
        // header('Cache-Control: max-age=0'); //no cache
                     
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        ob_end_clean();
        //force user to download the Excel file without writing it to server's HD
        // $objWriter->save('php://output');
        $file_name = 'panel-logistic-'.$production_order.'-'.date('YmdHis').'.xls';
        $objWriter->save(str_replace(__FILE__,'assets/storage/excel/'.$file_name,__FILE__));

        $file = base_url('assets/storage/excel/'.$file_name);
        $uploaded = $this->gdriveupload->uploadToClient($file, 'vivere-prodmon', $file_name);

        $this->session->set_flashdata('msg','<div class="alert alert-success">
                <a class="close" data-dismiss="alert"></a>
                <strong>Success!</strong> Data berhasil diexport ke account Google Drive Anda. 
                <a href="https://drive.google.com/file/d/'.$uploaded.'/view?usp=drivesdk" target="_blank">Klik disini</a>
            </div>');
        redirect('report/panel_logistic');
    }

    function report_by_project($project = NULL){
        if($this->session->userdata('logged_state_prodmon') !== true){
            redirect('site/login');
        }
        $user = $this->session->userdata('nik_prodmon');
        $user_plant = $this->session->userdata('plant_prodmon');
        $role = $this->session->userdata('role_prodmon');
        $user_role_process = $this->session->userdata('role_process_prodmon');
        $hide_header = 1;

        if($this->session->userdata('role_prodmon') == '6'){
            
            if($project == ""){
                $hide_header = 0;
                $project = $this->input->get('project');
            }

            
            if($project <> ""){
                $data['project'] = $project;
                $data['project_data'] = $this->prodmon_model->get_report_by_project($project);
                $data['project_panel_data'] = $this->prodmon_model->get_report_panel_by_project($project);
                $hide_header = 0;
            }

            $data['list_project'] = $this->prodmon_model->get_list_project();
            $data['hide_header'] = $hide_header;

            $data['main_content']='report/report_by_project';
            $this->load->view('template/main', $data);
        }else{
            redirect('site/index');
        }
    }
}
?>