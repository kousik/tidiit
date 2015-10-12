<?php
class Api1 extends CI_Controller{
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		redirect(base_url());
	}
	
	public function getdata(){
            $this->load->model('api_model','api');
            $Arr=$this->api->get_event();
            //echo '<pre>';print_r($Arr);die;
            $xml='<?xml version="1.0" encoding="UTF-8"?><RequestData><allevent>'; 
            foreach($Arr As $k){
                $xml .= '<EventID>'.$k->EventID.'</EventID>';
                $xml .= '<Name>'.$k->Name.'</Name>';
                $xml .= '<Status>'.$k->Status.'</Status>';
                $xml .= '<info>'.$k->info.'</info>';
                $xml .= '<apple_location_service_text>'.$k->apple_location_service_text.'</apple_location_service_text>';
                $xml .= '<google_location_service_text>'.$k->google_location_service_text.'</google_location_service_text>';
            }
            $xml .= '</allevent>';
            $xml .= '<timestamp>'.(string)mktime().'</timestamp></RequestData>';
            echo $xml;die;
	}
        
        public function logevent($argc=null){
            $this->load->model('api_model','api');
            $device_type = $this->input->get_post('device_type');
            $UDID = $this->input->get_post('device_id');
            $device_token = $this->input->get_post('device_token');
            $device_id = $this->input->get_post('device_id');
            $event_id = $this->input->get_post('event_id');
            $lat = $this->input->get_post('lat');
            $long = $this->input->get_post('long');
            $comment = $this->input->get_post('comment');
            
            $dataArr=array('device_type'=>$device_type,'device_id'=>$device_id,'device_token'=>$device_token,'event_id'=>$event_id,'lat'=>$lat,'long'=>$long,'comment'=>$comment);

            $log_id = $this->api->log_event($dataArr);

            if(empty($log_id)){
                $this->response(array('error' => 'Problem in saving user, please try again.'), 400);
                //echo json_encode(array('error' => 'Problem in saving user, please try again.'));
                return false;
            }

            header('Content-type: application/json');
            echo json_encode(array('msg' => 'Thanks for attending the events'));
        }
}
?>