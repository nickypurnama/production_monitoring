<?php

class prodmon_model extends CI_Model {
	private $dbprodmon;
	public function __construct()
	{
	     // Call the Model constructor
        parent::__construct();
		$this->dbprodmon = $this->load->database('prodmon',TRUE);
		$this->load->library('My_AdminLibrary');
	}

	function delete_temp($user){
		$this->dbprodmon->where('created_by', $user);
		$this->dbprodmon->delete('temp_upload');
	}

	function insert_temp_upload($data){
        return $this->dbprodmon->insert('temp_upload', $data);
    }

    function get_data_temp($nik){
    	$this->dbprodmon->where('created_by', $nik);
    	return $this->dbprodmon->get('temp_upload')->result_array();
    }

    function delete_temp_by_id($id){
		$this->dbprodmon->where('temp_upload_id', $id);
		$this->dbprodmon->delete('temp_upload');
	}

	function get_position_by_name($position){
		$this->dbprodmon->where('UPPER(process)', $position);
		return $this->dbprodmon->get('production_process')->row_array();
	}

	function insert_header($data){
        return $this->dbprodmon->insert('production_order_header', $data);
    }

    function get_list_header(){
    	$this->dbprodmon->where('status', '1');
    	return $this->dbprodmon->get('production_order_header')->result_array();
    }

    function get_list_header_by_plant($plant){
    	$this->dbprodmon->where('plant', $plant);
    	$this->dbprodmon->where('status', '1');
    	return $this->dbprodmon->get('production_order_header')->result_array();
    }

    function get_header($production_order){
		$this->dbprodmon->where('production_order', $production_order);
		return $this->dbprodmon->get('production_order_header')->row_array();
	}

	function update_header($id,$data){
		$this->dbprodmon->where('production_order', $id);
		return $this->dbprodmon->update('production_order_header', $data);
	}

	function delete_header($id){
		$this->dbprodmon->where('production_order', $id);
		$this->dbprodmon->delete('production_order_header');
	}

	function get_process(){
		return $this->dbprodmon->get('production_process')->result_array();
	}

	function get_process_ppic_pembahanan(){
		$id = array("1","2");
		$this->dbprodmon->where_in('production_process_id', $id);
		return $this->dbprodmon->get('production_process')->result_array();
	}

	function get_latest_line_item($production_order){
		$this->dbprodmon->select('line_item');
		$this->dbprodmon->from('production_order_item');
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->order_by('line_item', 'DESC');
		$this->dbprodmon->limit(1);
		return $this->dbprodmon->get()->row_array();
	}

	function insert_item($data){
        $insert =  $this->dbprodmon->insert('production_order_item', $data);
        if($insert){
        	$insert_id = $this->dbprodmon->insert_id();
   			return  $insert_id;
        } else{
        	return 0;
        }
    }

    function get_item($production_order){
		$this->dbprodmon->select('production_order_item.*, get_position(production_order_item.production_order_item_id) as process');
		$this->dbprodmon->from('production_order_header');
		$this->dbprodmon->join('production_order_item', 'production_order_header.production_order = production_order_item.production_order');
		$this->dbprodmon->join('production_process', 'production_order_item.position = production_process.production_process_id');
		$this->dbprodmon->where('production_order_header.production_order', $production_order);
		$this->dbprodmon->order_by('production_order_item.line_item', 'ASC');
		return $this->dbprodmon->get()->result_array();
	}

	function insert_item_quantity($data){
        return $this->dbprodmon->insert('production_order_item_quantity', $data);
    }

    function get_line_quantity_position($id,$process){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('production_order_item_quantity');
		$this->dbprodmon->where('production_order_item_id', $id);
		$this->dbprodmon->where('position', $process);
		return $this->dbprodmon->get()->row_array();
	}

    function update_item_qty($id,$data){
		$this->dbprodmon->where('production_order_item_quantity_id', $id);
		return $this->dbprodmon->update('production_order_item_quantity', $data);
	}

	function insert_item_history($data){
        return $this->dbprodmon->insert('production_order_item_history', $data);
    }

    function get_line_item($production_order, $line_item){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('production_order_item');
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->where('line_item', $line_item);
		return $this->dbprodmon->get()->row_array();
	}

	function update_item($id,$data){
		$this->dbprodmon->where('production_order_item_id', $id);
		return $this->dbprodmon->update('production_order_item', $data);
	}

	function delete_item($id){
		$this->dbprodmon->where('production_order_item_id', $id);
		$this->dbprodmon->delete('production_order_item');
	}

	function count_production_order($production_order){
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->from('production_order_header');
		return $this->dbprodmon->count_all_results();
	}

	function count_production_order_by_plant($production_order,$plant){
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->where('plant', $plant);
		$this->dbprodmon->from('production_order_header');
		return $this->dbprodmon->count_all_results();
	}

	function count_quantity($production_order){
		$this->dbprodmon->select('sum(quantity) as total');
		$this->dbprodmon->from('production_order_item');
		$this->dbprodmon->where('production_order', $production_order);
		return $this->dbprodmon->get()->row_array();
	}

	function get_data_by_production_order($production_order){
		$this->dbprodmon->select('production_order_header.*, production_order_item.*, get_position(production_order_item.production_order_item_id) as process');
		$this->dbprodmon->from('production_order_header');
		$this->dbprodmon->join('production_order_item', 'production_order_header.production_order = production_order_item.production_order');
		$this->dbprodmon->join('production_process', 'production_order_item.position = production_process.production_process_id');
		$this->dbprodmon->where('production_order_header.production_order = ', $production_order);
		$this->dbprodmon->order_by('production_order_item.production_order', 'ASC');
		return $this->dbprodmon->get()->result_array();
	}

	function get_data_range($production_order1, $production_order2){
		$this->dbprodmon->select('production_order_header.*');
		$this->dbprodmon->select('production_order_item.*');
		$this->dbprodmon->select('get_position(production_order_item.production_order_item_id) as process');
		$this->dbprodmon->from('production_order_header');
		$this->dbprodmon->join('production_order_item', 'production_order_header.production_order = production_order_item.production_order');
		$this->dbprodmon->join('production_process', 'production_order_item.position = production_process.production_process_id');
		$this->dbprodmon->where('production_order_header.production_order >= ', $production_order1);
		$this->dbprodmon->where('production_order_header.production_order <= ', $production_order2);
		$this->dbprodmon->where('production_order_header.status', '1');
		$this->dbprodmon->order_by('production_order_item.production_order', 'ASC');
		$this->dbprodmon->order_by('production_order_item.line_item', 'ASC');
		return $this->dbprodmon->get()->result_array();
	}

	function get_data_production_order_all(){
		$this->dbprodmon->select('production_order_header.*');
		$this->dbprodmon->select('production_order_item.*');
		$this->dbprodmon->select('get_position(production_order_item.production_order_item_id) as process');
		$this->dbprodmon->from('production_order_header');
		$this->dbprodmon->join('production_order_item', 'production_order_header.production_order = production_order_item.production_order');
		$this->dbprodmon->join('production_process', 'production_order_item.position = production_process.production_process_id');
    	$this->dbprodmon->where('production_order_header.status', '1');
		$this->dbprodmon->order_by('production_order_item.production_order', 'ASC');
		$this->dbprodmon->order_by('production_order_item.line_item', 'ASC');
		return $this->dbprodmon->get()->result_array();
	}

	function get_data_production_order_all_by_plant($plant){
		$this->dbprodmon->select('production_order_header.*');
		$this->dbprodmon->select('production_order_item.*');
		$this->dbprodmon->select('get_position(production_order_item.production_order_item_id) as process');
		$this->dbprodmon->from('production_order_header');
		$this->dbprodmon->join('production_order_item', 'production_order_header.production_order = production_order_item.production_order');
		$this->dbprodmon->join('production_process', 'production_order_item.position = production_process.production_process_id');
		$this->dbprodmon->where('production_order_header.plant', $plant);
    	$this->dbprodmon->where('production_order_header.status', '1');
		$this->dbprodmon->order_by('production_order_item.production_order', 'ASC');
		$this->dbprodmon->order_by('production_order_item.line_item', 'ASC');
		return $this->dbprodmon->get()->result_array();
	}

	function get_data_report($query){
		$query = "SELECT production_order_header.*, production_order_item.*, get_position(production_order_item.production_order_item_id) as process, get_remark(production_order_item.production_order_item_id, production_order_item.position) as remark, get_process_quantity(production_order_item.production_order_item_id,1) as ppic_qty, coalesce(get_process_date_ppic(production_order_item.production_order_item_id), get_process_date(production_order_item.production_order_item_id,1)) as ppic, get_process_quantity(production_order_item.production_order_item_id,2) as pembahanan_qty, get_process_date(production_order_item.production_order_item_id,2) as pembahanan, get_process_quantity(production_order_item.production_order_item_id,3) as perakitan_qty, get_process_date(production_order_item.production_order_item_id,3) as perakitan, get_process_quantity(production_order_item.production_order_item_id,4) as finishing_qty, get_process_date(production_order_item.production_order_item_id,4) as finishing, get_process_quantity(production_order_item.production_order_item_id,5) as finish_good_qty, get_process_date(production_order_item.production_order_item_id,5) as finish_good, get_process_quantity(production_order_item.production_order_item_id,6) as pengiriman_qty, get_process_date_pengiriman(production_order_item.production_order_item_id) as pengiriman FROM production_order_header JOIN production_order_item ON production_order_header.production_order = production_order_item.production_order WHERE $query AND production_order_header.status = '1' ORDER BY production_order_item.production_order, production_order_item.line_item ASC";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_chart_data($plant){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('dashboard');
		$this->dbprodmon->where('plant', $plant);
		return $this->dbprodmon->get()->result_array();
	}

	function get_chart_data_all(){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('dashboard');
		return $this->dbprodmon->get()->result_array();
	}

	function chart_data($plant){
		$query = "SELECT count_production_item_position('PPIC', '$plant') as ppic, count_production_item_position('Pembahanan', '$plant') as pembahanan, count_production_item_position('Perakitan', '$plant') as perakitan, count_production_item_position('Finishing', '$plant') as finishing, count_production_item_position('Finish Good', '$plant') as finish_good";
		return $this->dbprodmon->query($query)->row_array();
	}

	function get_list_project(){
		$query = "SELECT DISTINCT project_definition, project_description FROM production_order_header WHERE project_definition != '' AND status ='1' ORDER BY SUBSTR(project_definition,5,5)";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_list_project_by_plant($plant){
		$query = "SELECT DISTINCT project_definition FROM production_order_header WHERE project_definition != '' AND plant = '$plant' AND status = '1' ORDER BY SUBSTR(project_definition,5,5)";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_list_role(){
    	return $this->dbprodmon->get('role')->result_array();
	}

	function get_list_role_plant(){
		$this->dbprodmon->where('role_id != ', "6");
    	return $this->dbprodmon->get('role')->result_array();
	}
	
	function get_list_user_role(){
		$this->dbprodmon->select('user_role.*');
		$this->dbprodmon->select('role.role_name');
		$this->dbprodmon->select('production_process.process as process_name');
		$this->dbprodmon->from('user_role');
		$this->dbprodmon->join('role', 'user_role.role_id = role.role_id');
		$this->dbprodmon->join('production_process', 'user_role.process = production_process.production_process_id', 'left');
		$this->dbprodmon->order_by('user_role.user_role_id', 'ASC');
		return $this->dbprodmon->get()->result_array();
	}

	function get_list_user_role_by_plant($plant){
		$this->dbprodmon->select('user_role.*');
		$this->dbprodmon->select('role.role_name');
		$this->dbprodmon->select('production_process.process as process_name');
		$this->dbprodmon->from('user_role');
		$this->dbprodmon->join('role', 'user_role.role_id = role.role_id');
		$this->dbprodmon->join('production_process', 'user_role.process = production_process.production_process_id', 'left');
		$this->dbprodmon->where('user_role.plant', $plant);
		$this->dbprodmon->order_by('user_role.user_role_id', 'ASC');
		return $this->dbprodmon->get()->result_array();
	}

	function get_jml_role($nik){
		$this->dbprodmon->where('nik', $nik);
		$this->dbprodmon->from('user_role');
		return $this->dbprodmon->count_all_results();
	}

	function insert_user_role($data){
        return $this->dbprodmon->insert('user_role', $data);
    }

    function delete_user_role($id){
		$this->dbprodmon->where('user_role_id', $id);
		$this->dbprodmon->delete('user_role');
	}

	function get_production_process(){
		$type = array("PRODUCTION", "PANEL");
		$this->dbprodmon->where_in('type', $type);
		$this->dbprodmon->order_by('type', 'ASC');
		$this->dbprodmon->order_by('order_process', 'ASC');
		return $this->dbprodmon->get('production_process')->result_array();
	}

	function get_production_order_pembahanan($date){
		$this->dbprodmon->select('production_order_item.*');
		$this->dbprodmon->select('production_order_header.plant');
		$this->dbprodmon->from('production_order_item');
		$this->dbprodmon->join('production_order_header', 'production_order_item.production_order = production_order_header.production_order');
		$this->dbprodmon->where('production_order_item.date_target_pembahanan <=', $date);
    	$this->dbprodmon->where('production_order_header.status', '1');
		return $this->dbprodmon->get()->result_array();
	}

	function get_production_order_perakitan($date){
		$this->dbprodmon->select('production_order_item.*');
		$this->dbprodmon->select('production_order_header.plant');
		$this->dbprodmon->from('production_order_item');
		$this->dbprodmon->join('production_order_header', 'production_order_item.production_order = production_order_header.production_order');
		$this->dbprodmon->where('date_target_perakitan <=', $date);
    	$this->dbprodmon->where('production_order_header.status', '1');
		return $this->dbprodmon->get()->result_array();
	}

	function get_production_order_finishing($date){
		$this->dbprodmon->select('production_order_item.*');
		$this->dbprodmon->select('production_order_header.plant');
		$this->dbprodmon->from('production_order_item');
		$this->dbprodmon->join('production_order_header', 'production_order_item.production_order = production_order_header.production_order');
		$this->dbprodmon->where('date_target_finishing <=', $date);
    	$this->dbprodmon->where('production_order_header.status', '1');
		return $this->dbprodmon->get()->result_array();
	}

	function get_production_order_finish_good($date){
		$this->dbprodmon->select('production_order_item.*');
		$this->dbprodmon->select('production_order_header.plant');
		$this->dbprodmon->from('production_order_item');
		$this->dbprodmon->join('production_order_header', 'production_order_item.production_order = production_order_header.production_order');
		$this->dbprodmon->where('date_target_finish_good <=', $date);
    	$this->dbprodmon->where('production_order_header.status', '1');
		return $this->dbprodmon->get()->result_array();
	}

	function get_production_order_pengiriman($date){
		$this->dbprodmon->select('production_order_item.*');
		$this->dbprodmon->select('production_order_header.plant');
		$this->dbprodmon->from('production_order_item');
		$this->dbprodmon->join('production_order_header', 'production_order_item.production_order = production_order_header.production_order');
		$this->dbprodmon->where('date_target_pengiriman <=', $date);
    	$this->dbprodmon->where('production_order_header.status', '1');
		return $this->dbprodmon->get()->result_array();
	}

	function get_process_detail($id){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('production_process');
		$this->dbprodmon->where('production_process_id', $id);
		return $this->dbprodmon->get()->row_array();
	}

	function check_quantity_by_process($id,$order){
		$this->dbprodmon->select('SUM(quantity) as qty');
		$this->dbprodmon->from('production_order_item_quantity');
		$this->dbprodmon->join('production_process', 'production_order_item_quantity.position = production_process.production_process_id');
		$this->dbprodmon->where('production_process.order_process  <', $order);
		$this->dbprodmon->where('production_process.type', "PRODUCTION");
		$this->dbprodmon->where('production_order_item_quantity.production_order_item_id', $id);
		return $this->dbprodmon->get()->row_array();
	}

	function get_operator_token(){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('firebase_token');
		$this->dbprodmon->join('user_role', 'firebase_token.nik = user_role.nik');
		$this->dbprodmon->where('role_id', '1');
		return $this->dbprodmon->get()->result_array();
	}

	function insert_notification_history($data){
        return $this->dbprodmon->insert('notification_history', $data);
		}
	
	function get_operator_by_process($position, $plant){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('firebase_token');
		$this->dbprodmon->join('user_role', 'firebase_token.nik = user_role.nik');
		$this->dbprodmon->where('process', $position);
		$this->dbprodmon->where('plant', $plant);
		return $this->dbprodmon->get()->result_array();
	}

	function get_viewer_token($plant){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('firebase_token');
		$this->dbprodmon->join('user_role', 'firebase_token.nik = user_role.nik');
		$this->dbprodmon->where('role_id', '4');
		$this->dbprodmon->where('plant', $plant);
		return $this->dbprodmon->get()->result_array();
	}

	function get_list_plant(){
    	return $this->dbprodmon->get('m_plant')->result_array();
    }

    function get_jml_plant($plant){
		$this->dbprodmon->where('plant', $plant);
		$this->dbprodmon->from('m_plant');
		return $this->dbprodmon->count_all_results();
	}

	function insert_plant($data){
        return $this->dbprodmon->insert('m_plant', $data);
    }

    function delete_plant($plant){
		$this->dbprodmon->where('plant', $plant);
		$this->dbprodmon->delete('m_plant');
	}

	function get_jml_plant_dashboard($plant){
		$this->dbprodmon->where('plant', $plant);
		$this->dbprodmon->from('dashboard');
		return $this->dbprodmon->count_all_results();
	}

	function insert_dashboard($data){
		return $this->dbprodmon->insert('dashboard', $data);
	}

	function update_dashboard($plant, $data){
		$this->dbprodmon->where('plant', $plant);
		return $this->dbprodmon->update('dashboard', $data);
	}

	function get_list_sales_order_by_plant($plant){
		$query = "SELECT DISTINCT sales_order FROM production_order_header WHERE sales_order != '' AND plant = '$plant' AND status = '1' ORDER BY sales_order";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_list_so_vmk_by_plant($plant){
		$query = "SELECT DISTINCT so_vmk FROM production_order_header WHERE so_vmk != '' AND plant = '$plant' AND status = '1' ORDER BY so_vmk";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_history($line_item){
		$this->dbprodmon->select('production_order_item_history.*, get_position_name(from_position) as from_name, get_position_name(position) as to_name, get_user_name(created_by) as name');
		$this->dbprodmon->from('production_order_item_history');
		$this->dbprodmon->where('production_order_item_id', $line_item);
		return $this->dbprodmon->get()->result_array();
	}

	function get_data_report_production_order($query){
		$query = "SELECT production_order_item.production_order, production_order_item.production_order_item_id, production_order_item.line_item FROM production_order_header, production_order_item WHERE production_order_header.production_order = production_order_item.production_order AND $query AND production_order_header.status = '1' ORDER BY production_order_header.production_order ASC";
		return $this->dbprodmon->query($query)->result_array();
	}

	function count_report_production_order($production_order, $line_item){
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->where('line_item', $line_item);
		$this->dbprodmon->from('report');
		return $this->dbprodmon->count_all_results();
	}

	function get_data_report_by_production_order($production_order, $line_item){
		$query = "SELECT production_order_header.order_description, production_order_header.project_definition, production_order_header.project_description, production_order_header.created_date, production_order_header.release_date, production_order_header.uom AS uom_header, production_order_header.sales_order, production_order_header.so_vmk, production_order_item.*, get_position(production_order_item.production_order_item_id) as process, get_remark(production_order_item.production_order_item_id, production_order_item.position) as remark, get_position_date(production_order_item.production_order_item_id) as process_date, get_process_quantity(production_order_item.production_order_item_id,1) as ppic_qty, coalesce(get_process_date_ppic(production_order_item.production_order_item_id), get_process_date(production_order_item.production_order_item_id,1)) as ppic, get_process_quantity(production_order_item.production_order_item_id,2) as pembahanan_qty, get_process_date(production_order_item.production_order_item_id,2) as pembahanan, get_process_quantity(production_order_item.production_order_item_id,3) as perakitan_qty, get_process_date(production_order_item.production_order_item_id,3) as perakitan, get_process_quantity(production_order_item.production_order_item_id,4) as finishing_qty, get_process_date(production_order_item.production_order_item_id,4) as finishing, get_process_quantity(production_order_item.production_order_item_id,5) as finish_good_qty, get_process_date(production_order_item.production_order_item_id,5) as finish_good, get_process_quantity(production_order_item.production_order_item_id,6) as pengiriman_qty, get_process_date_pengiriman(production_order_item.production_order_item_id) as pengiriman, get_process_quantity(production_order_item.production_order_item_id,22) as install_qty, get_process_date_install(production_order_item.production_order_item_id) as install FROM production_order_header JOIN production_order_item ON production_order_header.production_order = production_order_item.production_order WHERE production_order_header.production_order = '$production_order' AND production_order_item.line_item = '$line_item' ORDER BY production_order_item.production_order, production_order_item.line_item ASC";
		return $this->dbprodmon->query($query)->row_array();
	}

	function insert_report($data){
        return $this->dbprodmon->insert('report', $data);
    }

    function get_latest_quantity($production_order_item_id){
    	$query = "SELECT updated_at FROM production_order_item_quantity WHERE production_order_item_id = '$production_order_item_id' ORDER BY updated_at DESC LIMIT 1";
		return $this->dbprodmon->query($query)->row_array();
    }

    function get_report_updated_at($production_order, $line_item){
		$query = "SELECT updated_at FROM report WHERE production_order = '$production_order' AND line_item = '$line_item'";
		return $this->dbprodmon->query($query)->row_array();
	}

	function delete_report($production_order, $line_item){
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->where('line_item', $line_item);
		$this->dbprodmon->delete('report');
	}

	function get_data_report_new($production_order_str){
		$query = "SELECT * FROM report WHERE production_order IN ($production_order_str) ORDER BY production_order, production_order_item_id ASC";
		return $this->dbprodmon->query($query)->result_array();
    }
    
    function delete_report_header($production_order){
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->delete('report');
    }
    
    function delete_report_item($id){
		$this->dbprodmon->where('production_order_item_id', $id);
		$this->dbprodmon->delete('report');
	}

	function delete_item_by_header($production_order){
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->delete('production_order_item');
	}

	function get_production_order_by_batch($batch){
		$this->dbprodmon->select('production_order');
		$this->dbprodmon->from('production_order_item');
		$this->dbprodmon->where('batch', $batch);
		$this->dbprodmon->distinct();
		return $this->dbprodmon->get()->result_array();
	}

	function get_module_by_production_order($production_order, $process){
		// $query = "SELECT production_order_item_id, grouping_code, module, get_total_panel_scan_by_module(production_order, module, '$process') as total_scan, count(*) as total_panel FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' GROUP BY module ORDER BY module";
		$query = "SELECT production_order_item_id, grouping_code, module, COALESCE(get_total_quantity_panel_scan_by_module(production_order, module, '$process'),0) as total_scan, sum(quantity) as total_panel FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' GROUP BY module ORDER BY module";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_module_qc_by_production_order($production_order, $process){
		// $query = "SELECT production_order_item_id, grouping_code, module, get_total_panel_scan_qc_by_module(production_order, module, '$process') as total_scan, count(*) as total_panel FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' GROUP BY module ORDER BY module";
		$query = "SELECT production_order_item_id, grouping_code, module, COALESCE(get_total_quantity_panel_scan_qc_by_module(production_order, module, '$process'),0) as total_scan, sum(quantity) as total_panel FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' GROUP BY module ORDER BY module";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_panel_by_module($production_order, $module, $process){
		// $query = "SELECT production_order_item_id, component_no, packing_code, flag_reject_qc, get_total_panel_scan(production_order_item_id, '$process') as total_scan FROM production_order_item WHERE production_order = '$production_order' AND module = '$module'";
		$query = "SELECT production_order_item_id, component_no, packing_code, flag_reject_qc, quantity, COALESCE(get_total_quantity_panel_scan(production_order_item_id, '$process'),0) as total_scan FROM production_order_item WHERE production_order = '$production_order' AND module = '$module'";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_panel_by_production_order($production_order, $process){
		// $query = "SELECT production_order_item_id, production_order, module, component_no, component_name, quantity, material, width, length, height, flag_reject_qc, get_total_panel_scan(production_order_item_id, '$process') as total_scan, get_panel_process_date(production_order_item_id, '$process') as log FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1'";
		$query = "SELECT production_order_item_id, production_order, module, component_no, component_name, quantity, material, width, length, height, flag_reject_qc, COALESCE(get_total_quantity_panel_scan(production_order_item_id, '$process'),0) as total_scan, get_panel_process_date(production_order_item_id, '$process') as log FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1'";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_panel_qc_by_production_order($production_order, $process){
		// $query = "SELECT production_order_item_id, production_order, module, component_no, component_name, quantity, material, width, length, height, flag_reject_qc, get_total_panel_qc_scan(production_order_item_id, '$process') as total_scan FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1'";
		$query = "SELECT production_order_item_id, production_order, module, component_no, component_name, quantity, material, width, length, height, flag_reject_qc, COALESCE(get_total_quantity_panel_qc_scan(production_order_item_id, '$process'),0) as total_scan FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1'";
		return $this->dbprodmon->query($query)->result_array();
	}

	function check_status_qc($production_order_item_id, $position){
		// $query = "SELECT get_position_id($production_order_item_id) as position, get_total_panel_scan($production_order_item_id, $position) as total_scan FROM dual";
		$query = "SELECT quantity, get_position_id(production_order_item_id) as position, get_total_panel_scan(production_order_item_id, $position) as total_scan, COALESCE(get_process_quantity(production_order_item_id, $position),0) as qty_remaining, COALESCE(get_total_quantity_panel_scan(production_order_item_id, $position),0) as total_qty_scan, COALESCE(get_status_qc_scan(production_order_item_id, $position),0) as qc_scan FROM production_order_item WHERE production_order_item_id = '$production_order_item_id'";
		return $this->dbprodmon->query($query)->row_array();
	}

	function insert_item_qty($data){
        return $this->dbprodmon->insert('production_order_item_quantity', $data);
    }

    function delete_history_panel($production_order_item_id, $process){
		$this->dbprodmon->where('production_order_item_id', $production_order_item_id);
		$this->dbprodmon->where('position', $process);
		$this->dbprodmon->delete('production_order_item_panel_history');
	}

	function get_packing_code_by_module($production_order, $module, $process){
		// $query = "SELECT production_order_item_id, packing_code, get_total_panel_scan_by_packing_code_module(production_order, packing_code, '$module', '$process') as total_scan, COUNT(*) as total_packing_code FROM production_order_item WHERE production_order = '$production_order' AND module = '$module' GROUP BY packing_code ORDER BY packing_code";
		$query = "SELECT production_order_item_id, packing_code, COALESCE(get_total_quantity_panel_scan_by_packing_code_module(production_order, packing_code, '$module', '$process'),0) as total_scan, sum(quantity) as total_packing_code FROM production_order_item WHERE production_order = '$production_order' AND module = '$module' GROUP BY packing_code ORDER BY packing_code";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_production_order_scan_by_batch($batch){
		// $query = "SELECT DISTINCT production_order, get_total_packing_internal(production_order) AS total_internal, get_total_packing_internal_scan(production_order) AS total_internal_scan, get_total_packing_eksternal(production_order) AS total_eksternal, get_total_packing_eksternal_scan(production_order) AS total_eksternal_scan FROM production_order_item WHERE batch = '$batch' ORDER BY production_order";
		$query = "SELECT production_order, SUM(quantity) AS quantity, COALESCE(get_total_quantity_packing_internal(production_order),0) AS total_internal, COALESCE(get_total_quantity_packing_internal_scan(production_order),0) AS total_internal_scan, COALESCE(get_total_quantity_packing_eksternal(production_order),0) AS total_eksternal, COALESCE(get_total_quantity_packing_eksternal_scan(production_order),0) AS total_eksternal_scan FROM production_order_item WHERE batch = '$batch' GROUP BY production_order ORDER BY production_order";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_production_order_scan($production_order, $is_panel){
		// $query = "SELECT DISTINCT production_order, get_total_packing_internal(production_order) AS total_internal, get_total_packing_internal_scan(production_order) AS total_internal_scan FROM production_order_item WHERE production_order = '$production_order' ORDER BY production_order";
		$query = "SELECT production_order_item_id, quantity, COALESCE(get_total_quantity_panel_scan(production_order_item_id, '26'),0) AS total_scan FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '$is_panel' ORDER BY production_order";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_production_order_non_panel_scan($production_order){
		$query = "SELECT DISTINCT production_order, box_no, get_total_packing_eksternal_by_box(production_order, box_no) AS total_eksternal, get_total_packing_eksternal_scan_by_box(production_order, box_no) AS total_eksternal_scan FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '0' ORDER BY production_order";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_module_scan_by_production_order($production_order){
		// $query = "SELECT module, get_total_packing_scan_by_module(production_order, module) AS total_scan, COUNT(*) as total_module FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' GROUP BY module";
		$query = "SELECT module, COALESCE(get_total_quantity_packing_scan_by_module(production_order, module),0) AS total_scan, sum(quantity) as total_module FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' GROUP BY module";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_packing_code_by_production_order($production_order){
		$query = "SELECT DISTINCT production_order, packing_code FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' ORDER BY production_order";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_non_panel_packing_code_by_production_order($production_order){
		$query = "SELECT DISTINCT production_order, packing_code FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '0' ORDER BY production_order";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_item_by_packing_code($production_order, $packing_code){
		$query = "SELECT * FROM production_order_item WHERE production_order = '$production_order' AND packing_code = '$packing_code' AND is_panel = '1' ORDER BY production_order,component_no";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_item_non_panel_by_packing_code($production_order, $packing_code){
		$query = "SELECT * FROM production_order_item WHERE production_order = '$production_order' AND packing_code = '$packing_code' AND is_panel = '0' ORDER BY production_order,component_no";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_item_panel_by_production_order($production_order){
		$query = "SELECT * FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' ORDER BY production_order,packing_code,component_no";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_item_non_panel_by_production_order($production_order){
		$query = "SELECT * FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '0' ORDER BY production_order,box_no";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_history_panel($line_item, $from_position, $position){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('production_order_item_history');
		$this->dbprodmon->where('production_order_item_id', $line_item);
		$this->dbprodmon->where('from_position', $from_position);
		$this->dbprodmon->where('position', $position);
		$this->dbprodmon->order_by('production_order_item_history_id', 'DESC');
		$this->dbprodmon->limit(1);
		return $this->dbprodmon->get()->row_array();
	}

	function get_item_by_id($production_order_item_id){
		$this->dbprodmon->select('production_order_item.*, get_position(production_order_item.production_order_item_id) as process');
		$this->dbprodmon->from('production_order_header');
		$this->dbprodmon->join('production_order_item', 'production_order_header.production_order = production_order_item.production_order');
		$this->dbprodmon->join('production_process', 'production_order_item.position = production_process.production_process_id');
		$this->dbprodmon->where('production_order_item.production_order_item_id', $production_order_item_id);
		$this->dbprodmon->order_by('production_order_item.line_item', 'ASC');
		return $this->dbprodmon->get()->row_array();
	}

	function get_item_non_panel($production_order){
		$query = "SELECT *, get_position(production_order_item.production_order_item_id) as process FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '0' ORDER BY production_order_item_id";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_panel_remark(){
		$this->dbprodmon->order_by('value', 'ASC');
		return $this->dbprodmon->get('panel_remark')->result_array();
	}

	function get_production_order_scan_export($production_order){
		// $query = "SELECT DISTINCT production_order, module, packing_code, get_total_packing_internal_scan_by_packing_code(production_order, packing_code) AS total_internal_scan, get_packing_code_process_date(packing_code, '26') AS log FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' ORDER BY production_order";
		$query = "SELECT production_order, module, packing_code, COALESCE(SUM(quantity),0) AS quantity, COALESCE(get_total_quantity_packing_internal_scan_by_packing_code_module(production_order, packing_code, module),0) AS total_internal_scan, get_packing_code_process_date(packing_code, '26') AS log FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '1' GROUP BY packing_code,module ORDER BY production_order";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_production_order_non_panel_scan_export($production_order){
		// $query = "SELECT DISTINCT production_order, module, packing_code, get_total_packing_eksternal_scan_by_packing_code(production_order, packing_code) AS total_eksternal_scan, get_packing_code_process_date(packing_code, '26') AS log FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '0' ORDER BY production_order";
		$query = "SELECT production_order, module, packing_code, COALESCE(SUM(quantity),0) AS quantity, COALESCE(get_total_quantity_packing_eksternal_scan_by_packing_code_module(production_order, packing_code, module),0) AS total_eksternal_scan, get_packing_code_process_date(packing_code, '26') AS log FROM production_order_item WHERE production_order = '$production_order' AND is_panel = '0' GROUP BY packing_code,module ORDER BY production_order";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_item_by_komponen($production_order, $component_no){
		$this->dbprodmon->select('production_order_item.*, production_process.process, production_order_header.material_code, production_order_header.material_description, production_order_header.plant, production_order_header.order_type, production_order_header.project_definition, production_order_header.project_description');
		$this->dbprodmon->from('production_order_header');
		$this->dbprodmon->join('production_order_item', 'production_order_header.production_order = production_order_item.production_order');
		$this->dbprodmon->join('production_process', 'production_order_item.position = production_process.production_process_id');
		$this->dbprodmon->where('production_order_header.production_order', $production_order);
		$this->dbprodmon->where('production_order_item.component_no', $component_no);
		$this->dbprodmon->order_by('production_order_item.line_item', 'ASC');
		return $this->dbprodmon->get()->result_array();
	}

	function get_position_data($id){
    	$this->dbprodmon->select('*');
		$this->dbprodmon->from('production_process');
		$this->dbprodmon->where('production_process_id', $id);
		return $this->dbprodmon->get()->row_array();
    }

    function count_quantity_station($production_order_item_id, $position){
		$this->dbprodmon->select('sum(quantity) as total');
		$this->dbprodmon->from('production_order_item_quantity');
		$this->dbprodmon->where('production_order_item_id', $production_order_item_id);
		$this->dbprodmon->where('position', $position);
		return $this->dbprodmon->get()->row_array();
	}

	function insert_item_history_panel($data){
        return $this->dbprodmon->insert('production_order_item_panel_history', $data);
    }

    function check_history_panel($production_order_item_id, $position){
		$this->dbprodmon->where('production_order_item_id', $production_order_item_id);
		$this->dbprodmon->where('position', $position);
		$this->dbprodmon->from('production_order_item_panel_history');
		return $this->dbprodmon->count_all_results();
	}

	function get_packing_code_by_id($production_order, $packing_code, $process){
		// $query = "SELECT packing_code, is_panel, get_total_panel_scan_by_packing_code(production_order, packing_code, '$process') as total_scan, COUNT(*) as total_packing_code FROM production_order_item WHERE production_order = '$production_order' AND packing_code = '$packing_code' GROUP BY packing_code ORDER BY packing_code";
		$query = "SELECT packing_code, is_panel, COALESCE(get_total_quantity_panel_scan_by_packing_code(production_order, packing_code, '$process'),0) as total_scan, sum(quantity) as total_packing_code FROM production_order_item WHERE production_order = '$production_order' AND packing_code = '$packing_code' GROUP BY packing_code ORDER BY packing_code";
		return $this->dbprodmon->query($query)->row_array();
	}

	function get_panel_by_packing_code($packing_code){
		$query = "SELECT production_order_item_id, quantity, is_panel, COALESCE(get_process_quantity(production_order_item_id, '26'),0) AS qty_available FROM production_order_item WHERE packing_code = '$packing_code' ORDER BY production_order_item_id";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_history_panel_scan($production_order_item_id, $process){
		$this->dbprodmon->select('*');
		$this->dbprodmon->from('production_order_item_panel_history');
		$this->dbprodmon->where('production_order_item_id', $production_order_item_id);
		$this->dbprodmon->where('position', $process);
		return $this->dbprodmon->get()->row_array();
	}

	function get_production_order_scan_gr($production_order){
		// $query = "SELECT DISTINCT production_order, get_total_packing_internal(production_order) AS total_internal, get_total_packing_internal_scan(production_order) AS total_internal_scan, get_total_packing_eksternal(production_order) AS total_eksternal, get_total_packing_eksternal_scan(production_order) AS total_eksternal_scan FROM production_order_item WHERE production_order = '$production_order' ORDER BY production_order";
		$query = "SELECT production_order, SUM(quantity) AS quantity, COALESCE(get_total_quantity_packing_internal(production_order),0) AS total_internal, COALESCE(get_total_quantity_packing_internal_scan(production_order),0) AS total_internal_scan, COALESCE(get_total_quantity_packing_eksternal(production_order),0) AS total_eksternal, COALESCE(get_total_quantity_packing_eksternal_scan(production_order),0) AS total_eksternal_scan FROM production_order_item WHERE production_order = '$production_order'";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_sloc_by_plant($plant){
		$this->dbprodmon->where('plant', $plant);
		$this->dbprodmon->order_by('id', 'ASC');
		return $this->dbprodmon->get('m_sloc')->result_array();
	}

	function check_component($production_order, $component_no){
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->where('component_no', $component_no);
		$this->dbprodmon->from('production_order_item');
		return $this->dbprodmon->count_all_results();
	}

	function get_report_panel_logistic($production_order){
		$query = "SELECT DISTINCT i.production_order, i.module, i.packing_code, i.is_panel, h.remark, h.reject_reason FROM production_order_item i, production_order_item_panel_history h WHERE i.production_order_item_id = h.production_order_item_id AND h.position = '29' AND h.status = '1' AND i.production_order = '$production_order' ORDER BY i.production_order_item_id";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_report_by_project($project_definition){
		$query = "SELECT SUM(quantity) AS total_qty, COALESCE(get_process_quantity_by_project('$project_definition',1),0) AS ppic_qty, COALESCE(get_process_quantity_by_project('$project_definition',2),0) AS pembahanan_qty, COALESCE(get_process_quantity_by_project('$project_definition',3),0) AS perakitan_qty, COALESCE(get_process_quantity_by_project('$project_definition',4),0) AS finishing_qty, COALESCE(get_process_quantity_by_project('$project_definition',5),0) AS finish_good_qty, COALESCE(get_process_quantity_by_project('$project_definition',6),0) AS pengiriman_qty, COALESCE(get_process_quantity_by_project('$project_definition',22),0) AS install_qty FROM production_order_header WHERE production_order IN (SELECT DISTINCT h.production_order FROM production_order_header h, production_order_item i WHERE h.production_order = i.production_order AND h.project_definition = '$project_definition' AND i.is_panel IS NULL)";
		return $this->dbprodmon->query($query)->row_array();
	}

	function get_list_po_vmk_by_plant($plant){
		$query = "SELECT DISTINCT i.po_vmk FROM production_order_item i, production_order_header h WHERE i.production_order = h.production_order AND i.po_vmk != '' AND h.plant = '$plant' AND h.status = '1' ORDER BY i.po_vmk";
		return $this->dbprodmon->query($query)->result_array();
	}

	function get_report_panel_by_project($project_definition){
		$query = "SELECT SUM(quantity) AS total_panel_qty, COALESCE(get_process_quantity_panel_by_project('$project_definition',23),0) AS station_a_qty, COALESCE(get_process_quantity_panel_by_project('$project_definition',24),0) AS station_b_qty, COALESCE(get_process_quantity_panel_by_project('$project_definition',25),0) AS station_c_qty, COALESCE(get_process_quantity_panel_by_project('$project_definition',26),0) AS station_d_qty, COALESCE(get_process_quantity_panel_by_project('$project_definition',29),0) AS logistic_qty, COALESCE(get_process_quantity_panel_by_project('$project_definition',22),0) AS install_qty FROM production_order_header WHERE production_order IN (SELECT DISTINCT h.production_order FROM production_order_header h, production_order_item i WHERE h.production_order = i.production_order AND h.project_definition = '$project_definition' AND i.is_panel IS NOT NULL)";
		return $this->dbprodmon->query($query)->row_array();
	}

	function update_history_panel($id,$data){
		$this->dbprodmon->where('production_order_item_panel_history_id', $id);
		return $this->dbprodmon->update('production_order_item_panel_history', $data);
	}

	function close_production_order($id, $plant){
		$this->dbprodmon->where('production_order', $id)->where('plant', $plant);
		return $this->dbprodmon->update('production_order_header', ['status' => 0]);
	}

	function insert_production_order_pack($data){
		return $this->dbprodmon->insert('production_order_packs', $data);
    }

	function update_production_order_pack($production_order, $packing_code, $destination, $data){
		if($destination=='panel')
		{
			$this->dbprodmon->where('production_order', $production_order)
						->where('packing_code', $packing_code)
						->where('is_panel', 1);
		} elseif($destination=='non-panel')
		{
			$this->dbprodmon->where('production_order', $production_order)
						->where('packing_code', $packing_code)
						->where('is_panel', 0);
		}
		
		return $this->dbprodmon->update('production_order_packs', $data);
	}

	function update_product($production_order, $data){
		$this->dbprodmon->where('production_order', $production_order)
						->where('is_panel', 1);
		return $this->dbprodmon->update('production_order_packs', $data);
	}

	function check_production_order_pack($production_order, $packing_code, $destination)
	{
		if($destination=='panel')
		{
			$this->dbprodmon->where('production_order', $production_order)->where('packing_code', $packing_code)->where('is_panel', 1);
			$this->dbprodmon->from('production_order_packs');
			return $this->dbprodmon->count_all_results();
		} elseif($destination=='non-panel')
		{
			$this->dbprodmon->where('production_order', $production_order)->where('packing_code', $packing_code)->where('is_panel', 0);
			$this->dbprodmon->from('production_order_packs');
			return $this->dbprodmon->count_all_results();
		}
	}

	function total_in_pack_production_order($production_order)
	{
		$this->dbprodmon->select('sum(qty_pack) as total');
		$this->dbprodmon->from('production_order_packs');
		$this->dbprodmon->where('production_order', $production_order);
		return $this->dbprodmon->get()->row_array();
	}

	function get_qty_pack($production_order, $packing_code, $destination)
	{
		if($destination=='panel')
		{
			$this->dbprodmon->select('qty_pack');
			$this->dbprodmon->from('production_order_packs');
			$this->dbprodmon->where('production_order', $production_order);
			$this->dbprodmon->where('packing_code', $packing_code);
			$this->dbprodmon->where('is_panel', 1);
			return $this->dbprodmon->get()->row_array();
		} elseif($destination=='non-panel')
		{
			$this->dbprodmon->select('qty_pack');
			$this->dbprodmon->from('production_order_packs');
			$this->dbprodmon->where('production_order', $production_order);
			$this->dbprodmon->where('packing_code', $packing_code);
			$this->dbprodmon->where('is_panel', 0);
			return $this->dbprodmon->get()->row_array();
		}
	}

	function get_produk($production_order)
	{
		$this->dbprodmon->select('product');
		$this->dbprodmon->from('production_order_packs');
		$this->dbprodmon->where('production_order', $production_order);
		$this->dbprodmon->where('is_panel', 1);
		$this->dbprodmon->limit(1);
		return $this->dbprodmon->get()->row_array();
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */