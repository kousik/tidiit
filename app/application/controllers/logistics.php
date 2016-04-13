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
        $rawOrderId=$this->post('orderId');
        
        
        
        if($rawOrderId==""){
            $this->response(array('error' =>'Please provide order index get from scanner.'), 400); return FALSE;
        }
        
        $orderIdArr=  explode("-", $rawOrderId);
        if(count($orderIdArr)!=3){
            $this->response(array('error' =>'Please provide valid scan data.'), 400); return FALSE;
        }
        
        $orderId=$orderIdArr[1];
        //$orderDetails=$this->order->details($orderId);
        $order=$this->Order_model->get_single_order_by_id($orderId);
        if(empty($order)){
            $this->response(array('error' =>'Getting invalid order data from scanner.'), 400); return FALSE;
        }
        
        if($order->status!=4){
            $this->response(array('error' =>'Scanned order is yet not shipped or out for delivery.'), 400); return FALSE;
        }
        
        $responseData=array();
        $scanUploadDataArr=array(
            'orderId'=>$rawOrderId,'movementType'=>'upload','UDID'=>$UDID,'deviceType'=>$deviceType,
            'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $responseData=$this->update_order_movement_history($scanUploadDataArr);
        
        if($responseData['type']=="fail"){
            $this->response(array('error' =>$responseData['message']), 400); return FALSE;
        }else{
            $logisticDetails=
            $movementDataArr=array('order'=>$order,'logisticDetails'=>$logisticDetails,'deliveryStaffName'=>$deliveryStaffName,
                'deliveryStaffContactNo'=>$deliveryStaffContactNo,'deliveryStaffEmail'=>$deliveryStaffEmail);
            if($order->orderType=='GROUP'):
                $this->_send_pre_alert_regarding_out_for_delivery_of_group($movementDataArr);
            else:
                $this->_send_pre_alert_regarding_out_for_delivery($movementDataArr);
            endif;
        }
    }
    
    function update_order_movement_history($movementDataArr){
        $rawOrderId=$movementDataArr['orderId'];
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
        $dataArr=array('orderId'=>$orderId,'movementType'=>$movementDataArr['movementType'],'addedDate'=>time(),'latitude'=>$latitude,'longitude'=>$longitude,'formattedAddress'=>$formatedAddress,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'UDID'=>$UDID);
        
    }
    
    function _send_notification_regarding_movement_of_item_of_group($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $orderInfo=unserialize(base64_decode($order->orderInfo));
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=$this->load_default_resources();
        $mail_template_view_data['orderInfo']=$orderInfo;
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $mail_template_view_data['orderDetails']=$orderDetails;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        $this->_global_tidiit_mail($order->buyerEmail, "Pre-alert to your Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'group_order_out_for_delivery_pre_alert',$buyerFullName);
        
        /// mail for group leader
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        if($order->parrentOrderID>0):
            $mail_template_view_data['leaderFullName']=$orderInfo['group']->admin->firstName.' '.$orderInfo['group']->admin->lastName;
            $this->_global_tidiit_mail($orderInfo['group']->admin->email, "Pre-alert to your Tidiit Buying Club Member order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'group_order_out_for_delivery_pre_alert_leader',$buyerFullName);    
        endif;
        
        /// for seller
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $sellerFullName=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['sellerFullName']=$sellerFullName;
        $this->_global_tidiit_mail($order->sellerEmail, "Pre-alert for Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'seller_group_order_out_for_delivery_pre_alert',$sellerFullName);
        
        $mail_template_view_data['supportFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Pre-alert Tidiit Buying Club for Order no - TIDIIT-OD-".$order->orderId.' before delivery ', $mail_template_view_data,'support_group_order_out_for_delivery_pre_alert','Tidiit Inc Support');
        
        /// sendin SMS to Buyer
        $smsMsg='Your Tidiit order TIDIIT-OD-'.$order->orderId.' will delivered by '.$outForDeliveryDataArr['outForDeliveryDays'].' days.';
        if($order->isPaid==0):
            $smsMsg.="AS you had selected Settlement on Delivery method,please submit the payment,So delivery people will delivery your item.";
        endif;
        $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$order->buyerMobileNo,'senderId'=>'','receiverId'=>$order->userId,
        'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-OUT-FOR_DELIVERY-PRE-ALERT');
        send_sms_notification($sms_data);
        
        if($order->userId!=$orderInfo["group"]->admin->userId):
            /// sendin SMS to Leader
            $smsMsg='Your Buying Club['.$orderInfo['group']->groupTitle.']  member Tidiit order TIDIIT-OD-'.$order->orderId.' will delivered by '.$outForDeliveryDataArr['outForDeliveryDays'].' days.';
            if($order->isPaid==0):
                $smsMsg.="$buyerFullName had selected Settlement on Delivery method,please follow with him/her to submit the payment,So delivery people will delivery your item.";
            endif;
            $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$orderInfo['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfo["group"]->admin->userId,
            'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-OUT-FOR_DELIVERY-PRE-ALERT');
            send_sms_notification($sms_data);
        endif;
        return TRUE;
    }
    
    function _send_notification_regarding_movement_of_item($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=$this->load_default_resources();
        $mail_template_view_data['orderInfo']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        $this->_global_tidiit_mail($order->buyerEmail, "Pre-alert to your Tidiit order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'single_order_out_for_delivery_pre_alert',$buyerFullName);
        
        
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderDetails']=$orderDetails;
        /// for seller
        $mail_template_view_data['userFullName']=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        $this->_global_tidiit_mail($order->sellerEmail, "Pre-alert for order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'seller_single_order_out_for_delivery_pre_alert',$order->sellerFirstName.' '.$order->sellerFirstName);
        
        
        $mail_template_view_data['userFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Pre-alert for Order no - TIDIIT-OD-".$order->orderId.' before delivery ', $mail_template_view_data,'support_single_order_out_for_delivery_pre_alert','Tidiit Inc Support');
        
        /// sendin SMS to allmember
        $smsMsg='Tidiit order TIDIIT-OD-'.$order->orderId.' will delivered by '.$outForDeliveryDataArr['outForDeliveryDays'].' days.';
        if($order->isPaid==0):
            $smsMsg.="AS you had selected Settlement on Delivery method,please submit the payment,So delivery people will delivery your item.";
        endif;
        $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$order->buyerMobileNo,'senderId'=>'','receiverId'=>$order->userId,
        'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-OUT-FOR_DELIVERY-PRE-ALERT');
        send_sms_notification($sms_data);
        return TRUE;
    }
}