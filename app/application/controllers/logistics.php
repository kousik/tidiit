<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Logistics extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Siteconfig_model','siteconfig');
        //$this->load->model('Testimonial_model');
        $this->load->model('Cms_model','cms');
        $this->load->model('Product_model','product');
        $this->load->model('Category_model','category');
        $this->load->model('Order_model','order');
        $this->load->model('Country');
    }
    
    function login_post(){
        $userName=  trim($this->post('userName'));
        $password=  trim($this->post('password'));
        $UDID=  trim($this->post('UDID'));
        $deviceType=  trim($this->post('deviceType'));
        $deviceToken=  trim($this->post('deviceToken'));
        $latitude=  trim($this->post('latitude'));
        $longitude=  trim($this->post('longitude'));
        
        if (!filter_var($userName, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        if($password==""){
            $this->response(array('error' => 'Please provide password.'), 400); return FALSE;
        }
        
        if($UDID==""){
            $this->response(array('error' => 'Please provide UDID.'), 400); return FALSE;
        }
        
        if($deviceToken==""){
            $this->response(array('error' => 'Please provide deviceToken.'), 400); return FALSE;
        }
        
        if($deviceType==""){
            $this->response(array('error' => 'Please provide deviceType.'), 400); return FALSE;
        }
        
        if($latitude==""){
            $this->response(array('error' => 'Please provide latitude.'), 400); return FALSE;
        }
        
        if($longitude==""){
            $this->response(array('error' => 'Please provide longitude.'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //$countryShortName='IN';
        if($countryShortName==FALSE){
            //$this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
        }
        
        //$rs=$this->db->from('user')->where('userName',$userName,'password',  base64_decode($password).'~'.)
        $rs=$this->user->check_login_data($userName,$password,'buyer');
        if(count($rs)>0){
            $parram=array('userId'=>$rs[0]->userId,'message'=>'You have logedin successfully');
            success_response_after_post_get($parram);
        }else{$this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;}
    }
    
    function scan_upload_post(){
        $rawOrderId=trim($this->post('orderId'));
        $userId=trim($this->post('userId'));
        $UDID=  trim($this->post('UDID'));
        $deviceType=  trim($this->post('deviceType'));
        $deviceToken=  trim($this->post('deviceToken'));
        $latitude=  trim($this->post('latitude'));
        $longitude=  trim($this->post('longitude'));
        
        $validOrderData=  $this->validate_scan_order_id($rawOrderId);
        
        if($validOrderData['type']=='faiil'){
            $this->response(array('error' =>$validOrderData['message']), 400); return FALSE;
        }else{
            $order=$validOrderData['order'];
            $logisticDetails=  $this->user->get_logistics_details_by_user_id($userId);
            if(empty($logisticDetails)){
                $this->response(array('error' =>'Getting invalid logistic user.'), 400); return FALSE;
            }
            $orderId=$order->orderId;
            $responseData=array();
            $scanUploadDataArr=array(
                'orderId'=>$orderId,'movementType'=>'upload','UDID'=>$UDID,'deviceType'=>$deviceType,
                'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId);
            $responseData=$this->update_order_movement_history($scanUploadDataArr);
            if($responseData['type']=="fail"){
                $this->response(array('error' =>$responseData['message']), 400); return FALSE;
            }else{
                $formatedAddress=  get_formatted_address_from_lat_long($latitude, $longitude);
                $movementDataArr=array('order'=>$order,'moveType'=>'upload','formatedAddress'=>$formatedAddress);
                $this->_send_notification_regarding_movement_of_item($movementDataArr);
                $result=array();
                $result['message']="Scandata updated successfully.";//$orderId.'-'.$qrCodeFileName; 
                success_response_after_post_get($result);
            }
        }
    }
    
    function scan_download_post(){
        $rawOrderId=trim($this->post('orderId'));
        $userId=trim($this->post('userId'));
        $UDID=  trim($this->post('UDID'));
        $deviceType=  trim($this->post('deviceType'));
        $deviceToken=  trim($this->post('deviceToken'));
        $latitude=  trim($this->post('latitude'));
        $longitude=  trim($this->post('longitude'));
        
        $validOrderData=  $this->validate_scan_order_id($rawOrderId);
        
        if($validOrderData['type']=='faiil'){
            $this->response(array('error' =>$validOrderData['message']), 400); return FALSE;
        }else{
            $order=$validOrderData['order'];
            $logisticDetails=  $this->user->get_logistics_details_by_user_id($userId);
            if(empty($logisticDetails)){
                $this->response(array('error' =>'Getting invalid logistic user.'), 400); return FALSE;
            }
            $orderId=$order->orderId;
            $responseData=array();
            $scanUploadDataArr=array(
                'orderId'=>$orderId,'movementType'=>'download','UDID'=>$UDID,'deviceType'=>$deviceType,
                'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId);
            $responseData=$this->update_order_movement_history($scanUploadDataArr);
            if($responseData['type']=="fail"){
                $this->response(array('error' =>$responseData['message']), 400); return FALSE;
            }else{
                $formatedAddress=  get_formatted_address_from_lat_long($latitude, $longitude);
                $movementDataArr=array('order'=>$order,'moveType'=>'download','formatedAddress'=>$formatedAddress);
                $this->_send_notification_regarding_movement_of_item($movementDataArr);
                $result=array();
                $result['message']="Scandata updated successfully.";//$orderId.'-'.$qrCodeFileName; 
                success_response_after_post_get($result);
            }
        }
    }
    
    function scan_pickup_post(){
        $rawOrderId=trim($this->post('orderId'));
        $userId=trim($this->post('userId'));
        $UDID=  trim($this->post('UDID'));
        $deviceType=  trim($this->post('deviceType'));
        $deviceToken=  trim($this->post('deviceToken'));
        $latitude=  trim($this->post('latitude'));
        $longitude=  trim($this->post('longitude'));
        
        $validOrderData=  $this->validate_scan_order_id($rawOrderId);
        
        if($validOrderData['type']=='faiil'){
            $this->response(array('error' =>$validOrderData['message']), 400); return FALSE;
        }else{
            $order=$validOrderData['order'];
            $logisticDetails=  $this->user->get_logistics_details_by_user_id($userId);
            if(empty($logisticDetails)){
                $this->response(array('error' =>'Getting invalid logistic user.'), 400); return FALSE;
            }
            $orderId=$order->orderId;
            $responseData=array();
            $scanUploadDataArr=array(
                'orderId'=>$orderId,'movementType'=>'Pickup from seller','UDID'=>$UDID,'deviceType'=>$deviceType,
                'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId);
            $responseData=$this->update_order_movement_history($scanUploadDataArr);
            if($responseData['type']=="fail"){
                $this->response(array('error' =>$responseData['message']), 400); return FALSE;
            }else{
                $formatedAddress=  get_formatted_address_from_lat_long($latitude, $longitude);
                $movementDataArr=array('order'=>$order,'moveType'=>'clientPickup','formatedAddress'=>$formatedAddress);
                $this->_send_notification_regarding_movement_of_item($movementDataArr);
                $result=array();
                $result['message']="Scandata updated successfully.";//$orderId.'-'.$qrCodeFileName; 
                success_response_after_post_get($result);
            }
        }
    }
    
    function validate_scan_order_id($rawOrderId){
        $responseData=array('type'=>'success');
        if($rawOrderId==""){
            $responseData['type']="fail";
            $responseData['message']='Please provide order index get from scanner.';
            return $responseData;
        }
        
        $orderIdArr=  explode("-", $rawOrderId);
        if(count($orderIdArr)!=3){
            $responseData['type']="fail";
            $responseData['message']='Please provide valid scan data.';
            return $responseData;
        }
        
        $orderId=$orderIdArr[1];
        //$orderDetails=$this->order->details($orderId);
        $order=$this->order->get_single_order_by_id($orderId);
        if(empty($order)){
            $responseData['type']="fail";
            $responseData['message']='Getting invalid order data from scanner.';
            return $responseData;
        }
        
        if($order->status!=4){
            $responseData['type']="fail";
            $responseData['message']='Scanned order is yet not shipped or out for delivery.';
            return $responseData;
        }
        $responseData=array('type'=>'success','order'=>$order);
        return $responseData;
    }
    
    function update_order_movement_history($movementDataArr){
        $orderId=$movementDataArr['orderId'];
        $responseData=array('type'=>'success');        
        $UDID=$movementDataArr['UDID'];
        if($UDID==""){
            $responseData['type']="fail";
            $responseData['message']='Please provide UDID.';
            return $responseData;
        }
        $deviceToken=$movementDataArr['deviceToken'];
        if($deviceToken==""){
            $responseData['type']="fail";
            $responseData['message']='Please provide deviceToken.';
            return $responseData;
        }
        $deviceType=$movementDataArr['deviceType'];
        if($deviceType==""){
            $responseData['type']="fail";
            $responseData['message']='Please provide deviceType.';
            return $responseData;
        }
        $latitude=$movementDataArr['latitude'];
        if($latitude==""){
            $responseData['type']="fail";
            $responseData['message']='Please provide latitude.';
            return $responseData;
        }
        $longitude=$movementDataArr['longitude'];
        if($longitude==""){
            $responseData['type']="fail";
            $responseData['message']='Please provide longitude.';
            return $responseData;
        }
        
        $formatedAddress=  get_formatted_address_from_lat_long($latitude, $longitude);
        if($formatedAddress==""){
            $responseData['type']="fail";
            $responseData['message']='Please provide valid latitude and longitude.';
            return $responseData;
        }
        $userId=$movementDataArr['userId'];
        
        $dataArr=array('orderId'=>$orderId,'movementType'=>$movementDataArr['movementType'],'addedDate'=>time(),'latitude'=>$latitude,'longitude'=>$longitude,'formattedAddress'=>$formatedAddress,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'UDID'=>$UDID,'userId'=>$userId);
        $this->order->add_movement_history($dataArr);
        return $responseData;
    }
    
    function _send_notification_regarding_movement_of_item($movementDataArr){
        $order=$movementDataArr['order'];
        $moveType=$movementDataArr['moveType'];
        $formatedAddress=$movementDataArr['formatedAddress'];
        $orderInfo=unserialize(base64_decode($order->orderInfo));
        /// sendin SMS to Buyer
        if($moveType=='clientPickup'){
            $smsMsg='Your item['.$orderInfo['pdetail']->title.'] for Tidiit order TIDIIT-OD-'.$order->orderId.' has pickup from seller location at '.$formatedAddress.'.';
        }else{
            $smsMsg='Your item['.$orderInfo['pdetail']->title.'] for Tidiit order TIDIIT-OD-'.$order->orderId.' has '.$moveType.' to a vehicle for next movement at '.$formatedAddress.'.';
        }
        $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$order->buyerMobileNo,'senderId'=>'','receiverId'=>$order->userId,
        'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-'.  strtoupper($moveType).'-MOVEMENT-UPDATE');
        send_sms_notification($sms_data);
        
        if($order->orderType=='GROUP'){
            if($order->userId!=$orderInfo["group"]->admin->userId):
                /// sendin SMS to Leader
                if($moveType=='clientPickup'){
                    $smsMsg='Your Buying Club['.$orderInfo['group']->groupTitle.']  member Tidiit order TIDIIT-OD-'.$order->orderId.' item has  pickup from seller location at '.$formatedAddress.'.';
                }else{
                    $smsMsg='Your Buying Club['.$orderInfo['group']->groupTitle.']  member Tidiit order TIDIIT-OD-'.$order->orderId.' item has '.$moveType.' to a vehicle for next movement at '.$formatedAddress.' .';
                }
                $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$orderInfo['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfo["group"]->admin->userId,
                'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-'.  strtoupper($moveType).'-MOVEMENT-UPDATE');
                send_sms_notification($sms_data);
            endif;
        }
        return TRUE;
    }
    
}