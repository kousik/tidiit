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
        $this->load->model('Category_model','categofry');
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
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //$countryShortName='IN';
        if($countryShortName==FALSE){
            //$this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
        }
        
        //$rs=$this->db->from('user')->where('userName',$userName,'password',  base64_decode($password).'~'.)
        $rs=$this->user->check_login_data($userName,$password,'logistics');
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
        
        if($validOrderData['type']=='fail'){
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
        //pre($validOrderData);die;
        if($validOrderData['type']=='fail'){
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
        
        if($validOrderData['type']=='fail'){
            $this->response(array('error' =>$validOrderData['message']), 400); return FALSE;
        }else{
            //pre($validOrderData);die;
            if(array_key_exists('order', $validOrderData)){
                $order=$validOrderData['order'];
                $logisticDetails=  $this->user->get_logistics_details_by_user_id($userId);
                if(empty($logisticDetails)){
                    $this->response(array('error' =>'Getting invalid logistic user.'), 400); return FALSE;
                }
                $orderId=$order->orderId;

                $responseData=array();
                $scanUploadDataArr=array(
                    'orderId'=>$orderId,'movementType'=>'pickup','UDID'=>$UDID,'deviceType'=>$deviceType,
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
            }else{
                
            }
        }
    }
    
    function pre_alert_out_for_delivery_post(){
        //$outForDeliveryType="preAlert";
        $outForDeliveryType=  trim($this->post('outForDeliveryType'));
        $rawOrderId=  trim($this->post('orderId'));
        $userId=  trim($this->post('userId'));
        $UDID=  trim($this->post('UDID'));
        $deviceToken=  trim($this->post('deviceToken'));
        $deviceType= "android";
        $latitude=  trim($this->post('latitude'));
        $longitude=  trim($this->post('longitude'));
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        $rawOrderIdArr=  explode('-', $rawOrderId);
        if(count($rawOrderIdArr)!=3){
            $this->response(array('error' =>"Getting nvalid scandata found."), 400); return FALSE;
        }
        $orderId=$rawOrderIdArr[1];
        $order=$this->order->get_single_order_by_id($orderId);
        if(empty($order)){
            $this->response(array('error' =>"Getting invalid order index"), 400); return FALSE;
        }
        
        if($order->status>5 || $order->status<4){
            $this->response(array('error' =>'Selected order is yet not shipped or out for delivery.'), 400); return FALSE;
        }
        
        $logisticDetails=$this->user->get_logistics_details_by_user_id($userId);
        
        $outForDeliveryDataArr=array('order'=>$order,'logisticDetails'=>$logisticDetails,'deliveryStaffName'=>$logisticDetails[0]['firstName'].' '.$logisticDetails[0]['lastName'],
            'deliveryStaffContactNo'=>$logisticDetails[0]['contactNo'],'deliveryStaffEmail'=>$logisticDetails[0]['email']);
        $dataArr=array('orderId'=>$orderId,'logisticsId'=>$logisticDetails[0]['logisticsId'],'outForDeliveryType'=>$outForDeliveryType,'deliveryStaffName'=>$logisticDetails[0]['firstName'].' '.$logisticDetails[0]['lastName'],
            'deliveryStaffContactNo'=>$logisticDetails[0]['contactNo'],'IP'=>$this->input->ip_address(),'deliveryStaffEmail'=>$logisticDetails[0]['email']);
        //pre($dataArr);die;
        $result=array();
        if($outForDeliveryType=='preAlert'):
            $outForDeliveryDays=$this->input->post('outForDeliveryDays',TRUE);
            if($outForDeliveryDays==""):
                $this->response(array('error' =>'Please select out for delivery days.'), 400); return FALSE;
            endif;    
            $outForDeliveryDataArr['outForDeliveryDays']=$outForDeliveryDays;
            $dataArr['outForDeliveryDays']=$outForDeliveryDays;
            $this->order->add_order_out_for_delivery($dataArr); 
            if($order->orderType=='GROUP'):
                $this->_send_pre_alert_regarding_out_for_delivery_of_group($outForDeliveryDataArr);
            else:
                $this->_send_pre_alert_regarding_out_for_delivery($outForDeliveryDataArr);
            endif;
            $result['message']="Pre alert for out for delivery updated successfully.";//$orderId.'-'.$qrCodeFileName; 
        else:
            $this->order->add_order_out_for_delivery($dataArr);
            $this->order->update_status(5,$orderId);
            if($order->orderType=='GROUP'):
                $this->_send_alert_regarding_out_for_delivery_of_group($outForDeliveryDataArr);
            else:
                $this->_send_alert_regarding_out_for_delivery($outForDeliveryDataArr);
            endif;
            $result['message']="Out for delivery updated successfully.";
        endif;
        success_response_after_post_get($result);
    }
    
    function order_delivery_post(){
        $orderId=  trim($this->post('orderId'));
        $userId=  trim($this->post('userId'));
        $UDID=  trim($this->post('UDID'));
        $deviceToken=  trim($this->post('deviceToken'));
        $deviceType= "android";
        $latitude=  trim($this->post('latitude'));
        $longitude=  trim($this->post('longitude'));
        
        $receiveStaffName=trim($this->input->post('receiveStaffName',TRUE));
        $receiveStaffContactNo=trim($this->input->post('receiveStaffContactNo',TRUE));
        $oldReceiveDateTime=trim($this->input->post('receiveDateTime',TRUE));
        
        if($receiveStaffName==""){
            $this->response(array('error' => 'Please enter the receive staff name.'), 400); return FALSE;
        }
        
        if($receiveStaffContactNo==""){
            $this->response(array('error' => 'Please enter the receive staff contact number.'), 400); return FALSE;
        }        
        
        $receiveDateTimeArr=  explode(' ', $oldReceiveDateTime);
        $receiveDateArr=  explode('-', $receiveDateTimeArr[0]);
        $receiveDateTime=$receiveDateArr[2].'-'.$receiveDateArr[1].'-'.$receiveDateArr[0].' '.$receiveDateTimeArr[1].':00';

        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $order=$this->order->get_single_order_by_id($orderId);
        if(empty($order)){
            $this->response(array('error' =>"Getting invalid order index"), 400); return FALSE;
        }
        
        if($order->status!=5){
            $this->response(array('error' =>'Selected order is yet not out for delivery or already delivered.'), 400); return FALSE;
        }
        
        /// need check payment has done or not.
        
        
        $logisticDetails=$this->user->get_logistics_details_by_user_id($userId);
        
        $logisticsId=$logisticDetails[0]['logisticsId'];
        $deliveryStaffName=$logisticDetails[0]['firstName'].' '.$logisticDetails[0]['lastName'];
        $deliveryStaffContactNo=$logisticDetails[0]['contactNo'];
        $deliveryStaffEmail=$logisticDetails[0]['email'];
        
        $dataArr=array('orderId'=>$orderId,'logisticsId'=>$logisticsId,'deliveryStaffName'=>$deliveryStaffName,
            'deliveryStaffContactNo'=>$deliveryStaffContactNo,'IP'=>$this->input->ip_address(),'deliveryStaffEmail'=>$deliveryStaffEmail,
            'receiveStaffName'=>$receiveStaffName,'receiveStaffContactNo'=>$receiveStaffContactNo,'receiveDateTime'=>$receiveDateTime);
        //$config['upload_path'] =$this->config->item('ResourcesPath').'order_delivery/original/';
        $config['upload_path'] =MAIN_SERVER_RESOURCES_PATH.'order_delivery/original/';
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['file_name']	= strtolower(my_seo_freindly_url($orderId)).'-'.rand(1,9).'-'.time();
        $config['max_size']	= '2047';
        $config['max_width'] = '1550';
        $config['max_height'] = '1550';
        $upload_files=array();
        $this->load->library('upload');
            
        foreach ($_FILES as $fieldname => $fileObject){  //fieldname is the form field name
            //pre($fieldname);
            //pre($fileObject);die;
            $this->upload->initialize($config);
            if (!$this->upload->do_upload($fieldname)):
                foreach($upload_files AS $k){
                    ///@unlink($this->config->item('ResourcesPath').'order_delivery/original/'.$k);
                    @unlink(MAIN_SERVER_RESOURCES_PATH.'order_delivery/original/'.$k);
                }
                $errors = $this->upload->display_errors();
                //pre($errors);die;
                $this->response(array('error' =>$errors), 400); return FALSE;
            else:
                $data=$this->upload->data();
                $this->order_delivery_image_resize($data['file_name']);
                $upload_files[]=$data['file_name'];
            endif;
        }
        if(empty($upload_files) || count($upload_files)<2){
            $this->response(array('error' =>'You must upload 2 photo for tidiit order delivery proof.'), 400); return FALSE;
        }
        $dataArr['photo1']=$upload_files[0];
        $dataArr['photo2']=$upload_files[1];
        //pre($dataArr);die;
        if(empty($oldDeliveryRequestDetails)):
            $orderDeliveredRequestId=$this->Order_model->add_order_delivered_request($dataArr);
        else:
            //pre($oldDeliveryRequestDetails);die;
            if($oldDeliveryRequestDetails[0]['photo1']!=""):
                if($data['photo1']!=""):
                    $this->delete_delivery_image($oldDeliveryRequestDetails[0]['photo1']);
                endif;
            endif;
            if($oldDeliveryRequestDetails[0]['photo2']!=""):
                if($data['photo2']!=""):
                    $this->delete_delivery_image($oldDeliveryRequestDetails[0]['photo2']);
                endif;
            endif;
            $this->Order_model->update_order_delivered_request($dataArr,$orderId);
            $orderDeliveredRequestId=$oldDeliveryRequestDetails[0]['orderDeliveredRequestId'];
        endif;
        if($orderDeliveredRequestId):
            $result=array();
            $result['message']='Delivery information updated successfully.';
            success_response_after_post_get($result);
        else:    
            $this->response(array('error' =>'Unknown error arises, please try again'), 400); return FALSE;
        endif;
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
        //pre($order);die;
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
         
        $defaultDataArr=array('UDID'=>$movementDataArr['UDID'],'deviceType'=>$movementDataArr['deviceType'],
            'deviceToken'=>$movementDataArr['deviceToken'],'latitude'=>$movementDataArr['latitude'],'longitude'=>$movementDataArr['longitude']);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $responseData['type']="fail";
            $responseData['message']=$isValideDefaultData['message'];
            return $responseData;
        }
        
        $formatedAddress=  get_formatted_address_from_lat_long($movementDataArr['latitude'], $movementDataArr['longitude']);
        if($formatedAddress==""){
            $responseData['type']="fail";
            $responseData['message']='Please provide valid latitude and longitude.';
            return $responseData;
        }
        $userId=$movementDataArr['userId'];
        //@mail('judhisahoo@gmail.com',' colomn movementType column testing',$movementDataArr['movementType']);
        $dataArr=array('orderId'=>$orderId,'movementType'=>$movementDataArr['movementType'],'addedDate'=>time(),'latitude'=>$movementDataArr['latitude'],'longitude'=>$movementDataArr['longitude'],'formattedAddress'=>$formatedAddress,'deviceType'=>$movementDataArr['deviceType'],'deviceToken'=>$movementDataArr['deviceToken'],'UDID'=>$movementDataArr['UDID'],'userId'=>$userId);
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
        @mail('judhisahoo@gmail.com','send_notification_regarding_movement_of_item',$smsMsg.' ==receiverMobileNumber== '.$order->buyerMobileNo);
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
    
    function check_default_data($dataArr){
        $validateArr=array('type'=>'success');
        if($dataArr['UDID']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide UDID.';
            return $validateArr;
        }
        
        if($dataArr['deviceToken']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide deviceToken.';
            return $validateArr;
        }
        
        if($dataArr['deviceType']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide deviceType.';
            return $validateArr;
        }
        
        if($dataArr['latitude']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide latitude.';
            return $validateArr;
        }
        
        if($dataArr['longitude']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide longitude.';
            return $validateArr;
        }
        return $validateArr;
    }
    
    /****
     *  sending pre-alert of out for delivery for BUYING-CLUB order 
     */
    function _send_pre_alert_regarding_out_for_delivery_of_group($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $orderInfo=unserialize(base64_decode($order->orderInfo));
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=load_default_resources();
        $mail_template_view_data['orderInfo']=$orderInfo;
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $mail_template_view_data['orderDetails']=$orderDetails;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        global_tidiit_mail($order->buyerEmail, "Pre-alert to your Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'group_order_out_for_delivery_pre_alert',$buyerFullName);
        
        /// mail for group leader
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        if($order->parrentOrderID>0):
            $mail_template_view_data['leaderFullName']=$orderInfo['group']->admin->firstName.' '.$orderInfo['group']->admin->lastName;
            global_tidiit_mail($orderInfo['group']->admin->email, "Pre-alert to your Tidiit Buying Club Member order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'group_order_out_for_delivery_pre_alert_leader',$buyerFullName);    
        endif;
        
        /// for seller
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $sellerFullName=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['sellerFullName']=$sellerFullName;
        global_tidiit_mail($order->sellerEmail, "Pre-alert for Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'seller_group_order_out_for_delivery_pre_alert',$sellerFullName);
        
        $mail_template_view_data['supportFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        global_tidiit_mail($supportEmail, "Pre-alert Tidiit Buying Club for Order no - TIDIIT-OD-".$order->orderId.' before delivery ', $mail_template_view_data,'support_group_order_out_for_delivery_pre_alert','Tidiit Inc Support');
        
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
    
    /****
     *  sending pre-alert of out for delivery for single order 
     */
    function _send_pre_alert_regarding_out_for_delivery($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=load_default_resources();
        $mail_template_view_data['orderInfo']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        global_tidiit_mail($order->buyerEmail, "Pre-alert to your Tidiit order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'single_order_out_for_delivery_pre_alert',$buyerFullName);
        
        
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderDetails']=$orderDetails;
        /// for seller
        $mail_template_view_data['userFullName']=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        global_tidiit_mail($order->sellerEmail, "Pre-alert for order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'seller_single_order_out_for_delivery_pre_alert',$order->sellerFirstName.' '.$order->sellerFirstName);
        
        
        $mail_template_view_data['userFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        global_tidiit_mail($supportEmail, "Pre-alert for Order no - TIDIIT-OD-".$order->orderId.' before delivery ', $mail_template_view_data,'support_single_order_out_for_delivery_pre_alert','Tidiit Inc Support');
        
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
    
    /****
     *  sending pre-alert of out for delivery for single order 
     */
    function _send_alert_regarding_out_for_delivery($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=load_default_resources();
        $mail_template_view_data['orderInfo']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        global_tidiit_mail($order->buyerEmail, "Your Tidiit order no - TIDIIT-OD-".$order->orderId.' is ready now to Out For Delivery', $mail_template_view_data,'single_order_out_for_delivery',$buyerFullName);
        
        /// for seller
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderDetails']=$orderDetails;
        $mail_template_view_data['userFullName']=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        global_tidiit_mail($order->sellerEmail, "Tidiit order no - TIDIIT-OD-".$order->orderId.' is ready to Out For Delivery', $mail_template_view_data,'seller_single_order_out_for_delivery',$order->sellerFirstName.' '.$order->sellerFirstName);
        
        
        $mail_template_view_data['userFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        global_tidiit_mail($supportEmail, "Tidiit Order no - TIDIIT-OD-".$order->orderId.' is ready to Out For Delivery ', $mail_template_view_data,'support_single_order_out_for_delivery','Tidiit Inc Support');
        
        $smsMsg='Tidiit order TIDIIT-OD-'.$order->orderId.' is ready to Out For Delivery.';
        if($order->isPaid==0):
            $smsMsg.="AS you had selected Settlement on Delivery method,please submit the payment,So delivery people will deliver your item at your door step.";
        endif;
        $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$order->buyerMobileNo,'senderId'=>'','receiverId'=>$order->userId,
        'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-OUT-FOR_DELIVERY');
        send_sms_notification($sms_data);
        
        return true;
    }
    
    function _send_alert_regarding_out_for_delivery_of_group($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $orderInfo=unserialize(base64_decode($order->orderInfo));
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=load_default_resources();
        $mail_template_view_data['orderInfo']=$orderInfo;
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $mail_template_view_data['orderDetails']=$orderDetails;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        global_tidiit_mail($order->buyerEmail, "Your Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' is ready for Out For Delivery', $mail_template_view_data,'group_order_out_for_delivery',$buyerFullName);
        
        /// mail for group leader
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        if($order->parrentOrderID>0):
            $mail_template_view_data['leaderFullName']=$orderInfo['group']->admin->firstName.' '.$orderInfo['group']->admin->lastName;
            global_tidiit_mail($orderInfo['group']->admin->email, "Your Tidiit Buying Club Member order no - TIDIIT-OD-".$order->orderId.' is ready for Out For Delivery', $mail_template_view_data,'group_order_out_for_delivery_leader',$buyerFullName);    
        endif;
        
        /// for seller
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $sellerFullName=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['sellerFullName']=$sellerFullName;
        global_tidiit_mail($order->sellerEmail, "Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' is ready for Out For Delivery', $mail_template_view_data,'seller_group_order_out_for_delivery',$sellerFullName);
        
        $mail_template_view_data['supportFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        global_tidiit_mail($supportEmail, "Tidiit Buying Club for Order no - TIDIIT-OD-".$order->orderId.' is ready for Out For Delivery ', $mail_template_view_data,'support_group_order_out_for_delivery','Tidiit Inc Support');
        
        /// sendin SMS to Buyer
        $smsMsg='Your Tidiit order TIDIIT-OD-'.$order->orderId.' is ready to Out For Delivery.';
        if($order->isPaid==0):
            $smsMsg.="AS you had selected Settlement on Delivery method,please submit the payment,So delivery people will delivery your item.";
        endif;
        $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$order->buyerMobileNo,'senderId'=>'','receiverId'=>$order->userId,
        'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-OUT-FOR_DELIVERY');
        send_sms_notification($sms_data);
        
        if($order->userId!=$orderInfo["group"]->admin->userId):
            /// sendin SMS to Leader
            $smsMsg='Your Buying Club['.$orderInfo['group']->groupTitle.']  member Tidiit order TIDIIT-OD-'.$order->orderId.' is ready to Out For Delivery.';
            if($order->isPaid==0):
                $smsMsg.="$buyerFullName had selected Settlement on Delivery method,please follow with him/her to submit the payment,So delivery people will delivery your item.";
            endif;
            $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$orderInfo['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfo["group"]->admin->userId,
            'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-OUT-FOR_DELIVERY');
            send_sms_notification($sms_data);
        endif;        
        return TRUE;
    }
    
    
    public function order_delivery_image_resize($fileName){
        $PHOTOPATH=MAIN_SERVER_RESOURCES_PATH.'order_delivery/';
        $OriginalPath=$PHOTOPATH.'original/';
        //echo '$OriginalPath :- '.$OriginalPath.'<br>';
        $OriginalFilePath=$OriginalPath.$fileName;
        //echo '$OriginalFilePath :- '.$OriginalFilePath.'<br>';

        /// ************100X100***************
        $config['image_library'] = 'gd2';
        $config['source_image'] = $OriginalFilePath;
        $config['new_image'] = $PHOTOPATH. '75X75/';
        $config['width'] = 75;
        $config['height'] = 75;
        $config['maintain_ratio'] = true;
        $config['master_dim'] = 'auto';
        $config['create_thumb'] = FALSE;
        $this->image_lib->initialize($config);
        $this->load->library('image_lib', $config);
        if($this->image_lib->resize()){
                //echo '<br>thumb done for 100X100.';
        }else{
                $this->image_lib->display_errors();
        }

        $this->image_lib->clear();
     }
     
     public function delete_delivery_image($fileName){
         $PHOTOPATH=MAIN_SERVER_RESOURCES_PATH.'order_delivery/';
         @unlink($PHOTOPATH.'original/'.$fileName);
         @unlink($PHOTOPATH.'75X75/'.$fileName);
         return TRUE;
     }
     
    
}