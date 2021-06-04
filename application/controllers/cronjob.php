<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class cronjob extends CI_CONTROLLER {
    
    public function __construct(){
        parent::__construct();
        $this->load->library('My_Auth');
        $this->load->model('prodmon_model');
        date_default_timezone_set('Asia/Jakarta');
    }

    function reminder_target(){
        $date = date("Y-m-d");

        //Pembahanan
        $pembahanan = $this->prodmon_model->get_process_detail("2");
        $order = $pembahanan['order_process'];
        $list_production_order_pembahanan = $this->prodmon_model->get_production_order_pembahanan($date);
        foreach($list_production_order_pembahanan as $row){
            $production_order = $row['production_order'];
            $line_id = $row['production_order_item_id'];
            $line_item = $row['line_item'];
            $target = $row['date_target_pembahanan'];
            $plant = $row['plant'];

            $check = $this->prodmon_model->check_quantity_by_process($line_id, $order);
            if($check['qty'] > 0){
                if($target == $date){
                    $message = "Target Pembahanan untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " adalah hari ini";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '2', $plant);
                }elseif($target < $date){
                    $message = "Target Pembahanan untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " sudah lewat";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '2', $plant);
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', 'Viewer', $plant);
                }
            }
        }

        //Perakitan
        $perakitan = $this->prodmon_model->get_process_detail("3");
        $order = $perakitan['order_process'];
        $list_production_order_perakitan = $this->prodmon_model->get_production_order_perakitan($date);
        foreach($list_production_order_perakitan as $row){
            $production_order = $row['production_order'];
            $line_id = $row['production_order_item_id'];
            $line_item = $row['line_item'];
            $target = $row['date_target_perakitan'];
            $plant = $row['plant'];

            $check = $this->prodmon_model->check_quantity_by_process($line_id, $order);
            if($check['qty'] > 0){
                if($target == $date){
                    $message = "Target Perakitan untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " adalah hari ini";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '3', $plant);
                }elseif($target < $date){
                    $message = "Target Perakitan untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " sudah lewat";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '3', $plant);
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', 'Viewer', $plant);
                }
            }
        }

        //Finishing
        $finishing = $this->prodmon_model->get_process_detail("4");
        $order = $finishing['order_process'];
        $list_production_order_finishing = $this->prodmon_model->get_production_order_finishing($date);
        foreach($list_production_order_finishing as $row){
            $production_order = $row['production_order'];
            $line_id = $row['production_order_item_id'];
            $line_item = $row['line_item'];
            $target = $row['date_target_finishing'];
            $plant = $row['plant'];

            $check = $this->prodmon_model->check_quantity_by_process($line_id, $order);
            if($check['qty'] > 0){
                if($target == $date){
                    $message = "Target Finishing untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " adalah hari ini";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '4', $plant);
                }elseif($target < $date){
                    $message = "Target Finishing untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " sudah lewat";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '4', $plant);
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', 'Viewer', $plant);
                }
            }
        }

        //Finish Good
        $finish_good = $this->prodmon_model->get_process_detail("5");
        $order = $finish_good['order_process'];
        $list_production_order_finish_good = $this->prodmon_model->get_production_order_finish_good($date);
        foreach($list_production_order_finish_good as $row){
            $production_order = $row['production_order'];
            $line_id = $row['production_order_item_id'];
            $line_item = $row['line_item'];
            $target = $row['date_target_finish_good'];
            $plant = $row['plant'];

            $check = $this->prodmon_model->check_quantity_by_process($line_id, $order);
            if($check['qty'] > 0){
                if($target == $date){
                    $message = "Target Finish Good untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " adalah hari ini";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '5', $plant);
                }elseif($target < $date){
                    $message = "Target Finish Good untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " sudah lewat";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '5', $plant);
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', 'Viewer', $plant);
                }
            }
        }

        //Pengiriman
        $pengiriman = $this->prodmon_model->get_process_detail("6");
        $order = $pengiriman['order_process'];
        $list_production_order_pengiriman = $this->prodmon_model->get_production_order_pengiriman($date);
        foreach($list_production_order_pengiriman as $row){
            $production_order = $row['production_order'];
            $line_id = $row['production_order_item_id'];
            $line_item = $row['line_item'];
            $target = $row['date_target_pengiriman'];
            $plant = $row['plant'];

            $check = $this->prodmon_model->check_quantity_by_process($line_id, $order);
            if($check['qty'] > 0){
                if($target == $date){
                    $message = "Target Pengiriman untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " adalah hari ini";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '6', $plant);
                }elseif($target < $date){
                    $message = "Target Pengiriman untuk Production Order " .$production_order. " dengan Line Item " .$line_item. " sudah lewat";
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', '6', $plant);
                    $this->send_firebase($firebase_token, 'Reminder Target', $message, 'System', 'Viewer', $plant);
                }
            }
        }
    }

    function send_firebase($firebase_token, $title, $message, $from, $to, $plant){
        if($to == "Viewer"){
            $viewer_token = $this->prodmon_model->get_viewer_token($plant);
            $firebase_token = array();
            foreach($viewer_token as $row){
                array_push($firebase_token, $row['firebase_token']);
            }
        } else {
            $operator_token = $this->prodmon_model->get_operator_by_process($to, $plant);
            $firebase_token = array();
            foreach($operator_token as $row){
                array_push($firebase_token, $row['firebase_token']);
            }
        }
        // echo $message;
        // echo "<br>";
        
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

    function dashboard(){
        $plant_data = $this->prodmon_model->get_list_plant();

        foreach($plant_data as $row){
            $chart_data = $this->prodmon_model->chart_data($row['plant']);

            $check = $this->prodmon_model->get_jml_plant_dashboard($row['plant']);
            if($check > 0){
                $update_data = array(
                    'ppic' => $chart_data['ppic'],
                    'pembahanan' => $chart_data['pembahanan'],
                    'perakitan' => $chart_data['perakitan'],
                    'finishing' => $chart_data['finishing'],
                    'finish_good' => $chart_data['finish_good']
                );

                $update = $this->prodmon_model->update_dashboard($row['plant'], $update_data);
            } else {
                $data_insert = array(
                    'plant' => $row['plant'],
                    'ppic' => $chart_data['ppic'],
                    'pembahanan' => $chart_data['pembahanan'],
                    'perakitan' => $chart_data['perakitan'],
                    'finishing' => $chart_data['finishing'],
                    'finish_good' => $chart_data['finish_good']
                );

                $this->prodmon_model->insert_dashboard($data_insert);
            }
        }
    }
}
?>