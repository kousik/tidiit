<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Appdata extends REST_Controller {
    
    function testservice_get()
    {
        $testid = "";
        if($this->get('testid'))
        	$testid = $this->get('testid');
        
        $this->response(array("test ID" => $testid), 200);
    }

    function testpostservice_post()
    {
        $testid = "";
        if($this->post('testid'))
            $testid = $this->post('testid');
        
        $this->response(array("test ID" => $testid), 200);
    }
    
    
    function getdata_get()
    {
        /*if($this->get('userid') == 999)
        {
            file_put_contents("app_io".DIRECTORY_SEPARATOR."999.log", "called    ", FILE_APPEND);
        }*/
        /*if(!$this->get('userid') || !$this->get('timestamp') || $this->get('appuserid') === false)
        	$this->response(array('error' => 'Please provide User Id, App User Id and Timestamp'), 400);
        $userid = $this->get('userid');
        $timestamp = $this->get('timestamp');
        $appuserid = $this->get('appuserid');
        if(!isValidTimeStamp($timestamp))
            $this->response(array('error' => 'Invalid timestamp'), 400);*/
        $this->load->model('api_model','api');
        /*$appid = $this->api->get_appid($userid);
        if($appid == -1)
            $this->response(array('error' => 'No Published data found for the specified user id.'),400);*/
        $result = array();
        $result['allevent'] = $this->api->get_event();
        //$result['buttons'] = $this->api->get_button($appid,$timestamp);
        /*$result['design'] = $this->api->get_design($appid, $timestamp);
        $result['fonts'] = $this->api->get_fonts($appid, $timestamp);
        $result['images'] = $this->api->get_userimages($userid, $timestamp);
        $result['master_fonts'] = $this->api->get_masterfonts($timestamp);
        $result['social_media'] = $this->api->get_socialmedialinks($appid, $timestamp);
        $result['business_data'] = $this->api->get_businessdata($appid, $timestamp);
        $result['testimonial'] = $this->api->get_testimonialdata($appid, $timestamp);
        $result['multimedia'] = $this->api->get_multimediadata($appid, $timestamp);
        $result['offering_data'] = $this->api->get_offeringdata($appid, $timestamp);
        $result['appointment_data'] = $this->api->get_appointmentdata($appid, $timestamp);
        $result['appointment_days'] = $this->api->get_appointmentdays($appid, $timestamp);
        $result['appointment_fields'] = $this->api->get_appointmentfields($appid, $timestamp);
        $result['appointment_title'] = $this->api->get_appointmenttitle($appid, $timestamp);
        $result['location_data'] = $this->api->get_locationdata($appid, $timestamp);
        $result['location_attributes'] = $this->api->get_locationattributes($appid, $timestamp);
        $result['static_pages_data'] = $this->api->get_pagesdata($appid, $timestamp);
        $result['registration_form_data'] = $this->api->get_registrationFormdata($appid, $timestamp);
        $result['koifeemail_data'] = $this->api->get_koifeemaildata($appuserid, $timestamp);
        $result['qusdata'] = $this->api->get_feedbackInquiryQusdata($appid, $timestamp);
        $result['ansdata'] = $this->api->get_feedbackInquiryAnsdata($appid, $timestamp);
        $result['emails_data'] = $this->api->get_apiEmails($appid, $timestamp);
        $result['cdn_url'] = $this->config->item('cdn_resource_url');
        $result['app_name_icon'] = $this->api->get_app_name_icon($appid,$timestamp);*/
        
        
        $result['timestamp'] = (string)mktime();
        header('Content-type: application/json');
        echo json_encode($result);
    }
    
    
    function logevent_post(){
        $this->load->model('api_model','api');
        
        if($this->input->post('debug')!=''){
            if($this->input->post('debug') == "1"){
                $post_data = $this->input->post();
                echo json_encode($post_data);
                return;
            }
        }
        
        $device_type = $this->post('device_type');
        $UDID = $this->post('device_id');
        $device_token = $this->post('device_token');
        $device_id = $this->post('device_id');
        $event_id = $this->post('event_id');
        $lat = $this->post('lat');
        $long = $this->post('long');
        $comment = $this->post('comment');
        
        

        /*if(empty($user_id) || empty($device_type) || empty($UDID) || empty($form_data)) {
            $this->response(array('error' => 'Device type or Device Id or User id or Form Data is missing.'), 400);
            //$result = json_encode(array('error' => 'Device type or Device Id or User id or Form Data is missing.'));
            return false;
        }
        $this->load->model('user_m');
        $this->load->model('registered_user_m');

        $userData = array(
            'registration_date' => date('Y-m-d H:i:s'),
            'user_id'           => $user_id,
            'app_id'            => $this->user_m->fetch_app_id_by_user($user_id),
            'device_type'       => $device_type,
            'UDID'              => $UDID,
            'device_token'      =>$device_token
        );*/
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
    
    
    

    function getkoifeemaildata_get()
    {
        if(!$this->get('userid') || !$this->get('timestamp') || $this->get('appuserid') === false)
            $this->response(array('error' => 'Please provide User id , App User Id and Timestamp'), 400);
        $timestamp = $this->get('timestamp');
        $appuserid = $this->get('appuserid');
        $userid = $this->get('userid');
        if(!isValidTimeStamp($timestamp))
            $this->response(array('error' => 'Invalid timestamp'), 400);
        $this->load->model('api_model','api');
        $appid = $this->api->get_appid($userid);
        if($appid == -1)
            $this->response(array('error' => 'No Published data found for the specified user id.'),400);
        $result = array();
        $result['koifeemail_data'] = $this->api->get_koifeemaildata($appuserid, $timestamp);
        
        $result['timestamp'] = (string)mktime();
        header('Content-type: application/json');
        echo json_encode($result);
    }

    function registeruser_post(){
        if($this->input->post('debug')!=''){
            if($this->input->post('debug') == "1"){
                $post_data = $this->input->post();
                echo json_encode($post_data);
                return;
            }
        }
        
        $device_type = $this->post('device_type');
        $UDID = $this->post('device_id');
        $device_token = $this->post('device_token');
        $user_id = $this->post('user_id');
        $form_data = $this->post('form_data');

        if(empty($user_id) || empty($device_type) || empty($UDID) || empty($form_data)) {
            $this->response(array('error' => 'Device type or Device Id or User id or Form Data is missing.'), 400);
            //$result = json_encode(array('error' => 'Device type or Device Id or User id or Form Data is missing.'));
            return false;
        }
        $this->load->model('user_m');
        $this->load->model('registered_user_m');

        $userData = array(
            'registration_date' => date('Y-m-d H:i:s'),
            'user_id'           => $user_id,
            'app_id'            => $this->user_m->fetch_app_id_by_user($user_id),
            'device_type'       => $device_type,
            'UDID'              => $UDID,
            'device_token'      =>$device_token
        );
        $app_user_id = $this->registered_user_m->saveUser($userData);

        if(empty($app_user_id)){
            $this->response(array('error' => 'Problem in saving user, please try again.'), 400);
            //echo json_encode(array('error' => 'Problem in saving user, please try again.'));
            return false;
        }
        $data = array();
        $i = 0;
        if(!empty($form_data) && !empty($app_user_id)){
            foreach($form_data as $key=> $row){
            
                if($key == '') continue;
                
                $data[$i] = array(
                    'user_id' => $user_id,
                    'app_user_id' => $app_user_id,
                    'attribute_id' => $key,
                    'attribute_value' => $row,
                );
                $i++;
            }
        }
        if(!empty($data)) $this->registered_user_m->saveUserData($data);

        header('Content-type: application/json');
        echo json_encode(array('app_user_id' => $app_user_id));
    }


    function updateuser_post(){

        $app_user_id = $this->post('appuserid');
        $form_data = $this->post('form_data');

        if(empty($app_user_id) ||  empty($form_data)) {
            $this->response(array('error' => 'App User id or Form Data is missing.'), 400);
            return false;
        }

        $this->load->model('registered_user_m');

        $data = array();
        $i = 0;
        foreach($form_data as $key=> $row){
        
            if($key == '') continue;
            
            $this->registered_user_m->updateUserData($row, $key, $app_user_id);
        }
        

        header('Content-type: application/json');
        echo json_encode(array('app_user_id' => $app_user_id));
    }
}
    
?>