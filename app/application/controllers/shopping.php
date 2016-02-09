<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Shopping extends REST_Controller {
    
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
        $this->load->library('tidiitrcode');
    }
    
    function add_to_cart_post(){
        $productId = $this->post('productId');
        $userId = $this->post('userId');
        $productPriceId = $this->post('productPriceId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        //pre($_POST);die;
        if($userId=="" || $productId =="" || $productPriceId == "" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken==""){
            $this->response(array('error' => 'Please provide user index,product index,product price index,latitude,longitude,device id,device token !'), 400); return FALSE;
        }
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $product = $this->product->details($productId);
        if(empty($product)){
            $this->response(array('error' => 'Please provide valid product index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //$countryShortName='IN';
        if($countryShortName==FALSE){
            $this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
        }
        
        $cartDataArr=array('productId'=>$productId,'userId'=>$userId,'productPriceId'=>$productPriceId,'latitude'=>$latitude,'longitude'=>$longitude,
            'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'UDID'=>$UDID);
        $msg=$this->add_to_cart($cartDataArr);
        $result=array();
        $result['message']="Selected product slab added to truck successfully.";//$orderId.'-'.$qrCodeFileName; 
        success_response_after_post_get($result);
    }
    
    function add_to_cart($cartDataArr){
        $product = $this->product->details($cartDataArr['productId']);
        $product = $product[0];
        $prod_price_info = $this->product->get_products_price_details_by_id($cartDataArr['productPriceId']);
        $is_cart_update = false;
        
        $countryShortName=  get_counry_code_from_lat_long($cartDataArr['latitude'], $cartDataArr['longitude']);
        $countryShortName='IN';
        
        $currentLocationTaxDetails=$this->product->get_tax_for_current_location($cartDataArr['productId'],$countryShortName.'_tax');
        $taxCol=$countryShortName.'_tax';
        $taxPercentage=$currentLocationTaxDetails->$taxCol;
        $tax=$prod_price_info->price*$taxPercentage/100;
        
        $order_data = array();
        $order_data['orderType'] = 'SINGLE';
        $order_data['productId'] = $cartDataArr['productId'];
        $order_data['productPriceId'] = $cartDataArr['productPriceId'];
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        $order_data['userId'] = $cartDataArr['userId'];
        $order_data['productQty'] = $prod_price_info->qty;
        $order_data['subTotalAmount'] = $prod_price_info->price;
        $order_data['taxAmount'] = $tax;
        $order_data['orderAmount'] = $prod_price_info->price+$tax;
        $order_data['orderDevicetype'] = 3;
        $order_data['appSource'] = $cartDataArr['deviceType'];
        $order_data['longitude'] = $cartDataArr['longitude'];
        $order_data['latitude'] = $cartDataArr['latitude'];
        $order_data['deviceToken'] = $cartDataArr['deviceToken'];
        $order_data['udid'] = $cartDataArr['UDID'];
        
        $orderinfo = array();
        $orderinfo['pdetail'] = $product;
        $orderinfo['priceinfo']=$prod_price_info;
        $productImageArr =$this->product->get_products_images($cartDataArr['productId']);
        $orderinfo['pimage'] = $productImageArr[0];            
        $userShippingDataDetails = $this->user->get_user_shipping_information($cartDataArr['userId'],TRUE);
        $orderinfo['shipping'] = $userShippingDataDetails[0];
        
        $order_data['orderInfo'] = base64_encode(serialize($orderinfo));
        
        $qrCodeFileName=time().'-'.rand(1, 50).'.png';
        $order_data['qrCodeImageFile']=$qrCodeFileName;
        //pre($order_data);die;
        $orderId=$this->order->add($order_data);
        $data['orderId']=$orderId;
        $params=array();
        $params['data']=$orderId;
        $qrCodeFilePath=SITE_RESOURCES_PATH.'qr_code/'.$qrCodeFileName;
        $params['savename']=$qrCodeFilePath;
        $this->tidiitrcode->generate($params);
        @copy($qrCodeFilePath,MAIN_SERVER_RESOURCES_PATH.'qr_code/'.$qrCodeFileName);
        @unlink($qrCodeFilePath);
        return $orderId.'-'.$qrCodeFileName;
    }
    
    function get_cart_item_get(){
        $userId = $this->get('userId');
        $latitude = $this->get('latitude');
        $longitude = $this->get('longitude');
        if($userId=="" || $latitude=="" || $longitude==""){
            $this->response(array('error' => 'Please provide user index,latitude and Longitude !'), 400); return FALSE;
        }
        
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        $countryShortName='IN';
        if($countryShortName==FALSE){
            $this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
        }
        $allItemArr=$this->order->get_all_cart_item($userId);
        $newAllItemArr=array();
        foreach($allItemArr AS $k){
            //$fieldName=$countryShortName.'_tax';
            //$taxPercentage=$k[$fieldName];
            $orderInfo=  unserialize(base64_decode($k['orderInfo']));
            $k['productTitle']=$orderInfo['pdetail']->title;
            $k['qty']=$orderInfo['priceinfo']->qty;
            $k['pimage']=$orderInfo['pimage']->image;
            $newAllItemArr[]=$k;
        }
        //pre($newAllItemArr);die;
        $result=array();
        $result['allItemArr']=$newAllItemArr;
        success_response_after_post_get($result);
    }
    
    function remove_item_from_cart_post(){
        $userId = $this->post('userId');
        $orderId = $this->post('orderId');
        if($userId=="" || $orderId ==""){
            $this->response(array('error' => 'Please provide user index,order index !'), 400); return FALSE;
        }
        if($this->order->inctive_order_details_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Provided order index not match with user index!'), 400); return FALSE;
        }
        $this->order->remove_order_from_cart($orderId,$userId);
        $result=array();
        $result['message']='selected item removed from cart successfully';
        success_response_after_post_get($result);
    }
    
    function user_shipping_address_post(){
        $userId=  $this->post('userId');
        $firstName=  $this->post('firstName');
        $lastName=  $this->post('lastName');
        $countryId=  $this->post('countryId');
        $cityId=  $this->post('cityId');
        $zipId=  $this->post('zipId');
        $localityId=  $this->post('localityId');
        $phone=  $this->post('phone');
        $address=  $this->post('address');
        $landmark=  $this->post('landmark');
        $deviceType=  $this->post('deviceType');
        $longitude = $this->post('longitude');
        $latitude = $this->post('latitude');
        
        if($userId=="" || $firstName=="" || $lastName=="" || $countryId=="" || $cityId=="" || $zipId=="" || $localityId =="" || $deviceType =="" || $latitude=="" || $longitude==""){
            $this->response(array('error' => 'Please provide user index,first name,last name,latitude,longitude,device type,country index,city index,locality index,zip index !'), 400); return FALSE;
        }
        
        
        $rs=$this->Country->city_details($cityId);

        $isAdded=$this->user->is_shipping_address_added($userId);
        if(empty($isAdded)){
            $this->user->add_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'userId'=>$userId,'address'=>$address,'stateId'=>$rs[0]->stateId,'appSource'=>$deviceType,'landmark'=>$landmark));
        }else{
            $this->user->edit_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'address'=>$address,'stateId'=>$rs[0]->stateId,'landmark'=>$landmark),$userId);
        }
        
        $userShippingDetails=  $this->user->get_user_shipping_information($userId,TRUE);
        $allIncompleteOrders= $this->order->get_incomplete_order_by_user($userId);
        
        foreach($allIncompleteOrders As $k){
            $orderInfo= unserialize(base64_decode($k->orderInfo));
            $orderInfo['shipping']=$userShippingDetails[0];
            $this->order->update(array('orderInfo'=>base64_encode(serialize($orderInfo))),$k->orderId);
        }
        
        $result=array();
        $result['message']='Shipping address updated successfully';
        success_response_after_post_get($result);
    }
    
    function test_get(){
        pre($_SERVER['DOCUMENT_ROOT']);die;
    }
    
    function single_order_coupon_set_post(){
        $this->load->model('Coupon_model','coupon');
        $promocode=$this->post('couponCode',TRUE);
        $orderId = $this->post('orderId',TRUE);
        $userId = $this->post('userId',TRUE);
        $latitude = $this->post('latitude',TRUE);
        $longitude = $this->post('longitude',TRUE);
        $coupon = $this->coupon->is_coupon_code_exists($promocode);
        if(!$coupon):
            $this->response(array('error' => 'Invalid promo code or promo code has expaired!!'), 400); return FALSE;
        endif;
        
        $ordercoupon = $this->coupon->is_coupon_code_valid_for_single($coupon);
        
        if($ordercoupon):
            $this->response(array('error' => 'Invalid promo code or promo code has expaired!!'), 400); return FALSE;
        else:
           $userDetails=  $this->user->get_details_by_id($userId);
            //$ctotal = $this->cart->total();
            $allItemArr=$this->order->get_all_cart_item($userDetails[0]->userId,'single');
            $orderIdArr=array();
            foreach($allItemArr As $k){
                $orderIdArr[]=$k['orderId'];
            }
            if($this->coupon->is_coupon_recently_used($orderIdArr,$coupon->couponId)==TRUE){
                $this->response(array('error' => 'Promo code has alrady used in your current session.'), 400); return FALSE;
            }
            $orderDetails=$this->order->details($orderId);
            if(empty($orderDetails)){
                $this->response(array('error' => 'Invalid order index provided.'), 400); return FALSE;
            }
            //pre($orderDetails);die;
            $ctotal=0;
            foreach($allItemArr AS $k){ //pre($k);die;
                $ctotal +=$k['subTotalAmount'];
            }
            if($coupon->type == 'percentage'):
                $amt = ($coupon->amount/100)*$orderDetails[0]->subTotalAmount;
                $amt1 = number_format($amt, 2, '.', '');
                $data['couponAmount'] = substr($amt1, 0, -3);
            elseif($coupon->type == 'fix'):
                $data['couponAmount'] = $coupon->amount;
            endif;
            $tax=0;
            $grandTotal=0;
            $couponAmount=0;
            $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
            //$countryShortName='IN';
            if($countryShortName==FALSE){
                $this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
            }
            foreach($allItemArr AS $k){
                if($k['orderId']==$orderId){
                    $currentLocationTaxDetails=$this->product->get_tax_for_current_location($k['productId'],$countryShortName.'_tax');
                    $taxCol=$countryShortName.'_tax';
                    $taxPercentage=$currentLocationTaxDetails->$taxCol;
                    $orderAmountBeforeTax=$k['subTotalAmount']-$data['couponAmount'];
                    $cTax=$orderAmountBeforeTax*$taxPercentage/100;
                    $orderAmount=$orderAmountBeforeTax+$cTax;
                    $orderDataArr=array('taxAmount'=>$cTax,'discountAmount'=>$data['couponAmount'],'orderAmount'=>$orderAmount);
                    $this->order->update($orderDataArr,$k['orderId']);
                    $this->order->tidiit_creat_order_coupon(array('orderId'=>$k['orderId'],'couponId'=>$coupon->couponId,'amount'=>$data['couponAmount']));
                    $couponAmount +=$data['couponAmount'];
                }else{
                    $cTax=$k['taxAmount'];
                    $orderAmount=$k['orderAmount'];
                    $couponAmount +=$k['discountAmount'];
                }
                
                $tax +=$cTax;
                $grandTotal +=$orderAmount;
            }
            
            $data['tax'] = number_format(round($tax,0,PHP_ROUND_HALF_UP),2);
            //$grandTotal=round($ctotal - $data['couponAmount']+round($tax,0,PHP_ROUND_HALF_UP),0,PHP_ROUND_HALF_UP);
            $data['grandTotal'] = number_format(round($grandTotal,0,PHP_ROUND_HALF_UP),2);
            
            $result['message'] = "Promo code has been applied successfully!";
            $data['couponAmount'] = number_format(round($couponAmount,0,PHP_ROUND_HALF_UP),2);
            $result['content'] = $data;
            success_response_after_post_get($result);
        endif;
    }
    
    function single_order_coupon_unset_post(){
        $this->load->model('Coupon_model','coupon');
        $orderId=$this->post('orderId',TRUE);
        $latitude = $this->post('latitude',TRUE);
        $longitude = $this->post('longitude',TRUE);
        $orderDetails=$this->order->details($orderId);
        if(empty($orderDetails)){
            $this->response(array('error' => 'Invalid order index provided.'), 400); return FALSE;
        }
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //$countryShortName='IN';
        $currentLocationTaxDetails=$this->product->get_tax_for_current_location($orderDetails[0]->productId,$countryShortName.'_tax');
        $taxCol=$countryShortName.'_tax';
        $taxPercentage=$currentLocationTaxDetails->$taxCol;
        $tax=$orderDetails[0]->subTotalAmount*$taxPercentage/100;
        
        $orderDataArr=array();
        $orderDataArr['taxAmount'] = $tax;
        $orderDataArr['orderAmount'] = $orderDetails[0]->subTotalAmount+$tax;
        $orderDataArr['discountAmount'] ='';
        $this->order->update($orderDataArr,$orderId);
        
        $this->coupon->remove_order($orderId);
        $result['message'] = "Selected promo code removed form selected item.";
        $result['ajaxType']='yes';
        success_response_after_post_get($result);
    }
    
    function single_order_sod_payment_post(){
        $userId=  $this->post('userId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken==""){
            $this->response(array('error' => 'Please provide user index,latitude,longitude,device id,device token !'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        
        $allIncompleteOrders= $this->order->get_incomplete_order_by_user($userId,'single');
        //pre($allIncompleteOrders);die;
        $defaultResources=load_default_resources();
        $user=$this->user->get_details_by_id($userId)[0];
        foreach ($allIncompleteOrders As $k){
            $orderInfo = array();
            $mail_template_data = array();
            //pre($k);die;
            $orderInfo= unserialize(base64_decode($k->orderInfo));
            //pre($orderInfo);die;
            //pre($orderInfo['shipping']);die;
            //pre($orderInfo['pdetail']);die;
            //pre($orderInfo['priceinfo']);die;
           
            $settlementOnDeliveryId=$this->order->add_sod(array('latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId));
            $this->order->add_payment(array('orderId'=>$k->orderId,'paymentType'=>'settlementOnDelivery','settlementOnDeliveryId'=>$settlementOnDeliveryId,'orderType'=>'single'));
            $this->product->update_product_quantity($orderInfo['priceinfo']->productId,$orderInfo['priceinfo']->qty);
            $orderUpdateArr=array('orderUpdatedate'=>date('Y-m-d H:i:s'),'udidPayment'=>$UDID,'deviceTokenPayment'=>$deviceToken,
                'latitudePayment'=>$latitude,'longitudePayment'=>$longitude);
            $orderUpdateArr['status']=2;
            $this->order->update($orderUpdateArr,$k->orderId);
            /// sendin SMS to user
            /*$sms_data=array('nMessage'=>'You have successfull placed an order TIDIIT-OD-'.$k->orderId.' for '.$orderInfo['pdetail']->title.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'],
                'receiverMobileNumber'=>$user->mobile,'senderId'=>'','receiverId'=>$user->userId,
                'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER');*/
            //send_sms_notification($sms_data);
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderInfo;
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$k->orderId;
            $mail_template_view_data=$defaultResources;
            $mail_template_view_data['single_order_success']=$mail_template_data;
            $receiverFullName=$user->firstName.' '.$user->lastName;
            global_tidiit_mail($user->email, "Your Tidiit order - TIDIIT-OD-".$k->orderId.' has placed successfully', $mail_template_view_data,'single_order_success',$receiverFullName);
            
            $this->sent_single_order_complete_mail($k->orderId);
        }
        $result=array();
        $result['message']='Thanks you for shopping with '.$defaultResources['MainSiteBaseURL'].'.Order placed successfully for each item selected.For More details check your "My Order" section.';
        success_response_after_post_get($result);
    }
    
    function single_order_mpesa_payment_post(){
        $userId=  $this->post('userId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken==""){
            $this->response(array('error' => 'Please provide user index,latitude,longitude,device id,device token !'), 400); return FALSE;
        }
        
        $allIncompleteOrders= $this->order->get_incomplete_order_by_user($userId,'single');
        //pre($allIncompleteOrders);die;
        $defaultResources=load_default_resources();
        $user=$this->user->get_details_by_id($userId)[0];
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        foreach ($allIncompleteOrders As $k){
            $orderInfo = array();
            $mail_template_data = array();
            //pre($k);die;
            $orderInfo= unserialize(base64_decode($k->orderInfo));
            //pre($orderInfo);die;
            //pre($orderInfo['shipping']);die;
            //pre($orderInfo['pdetail']);die;
            //pre($orderInfo['priceinfo']);die;
           
            $mPesaId=$this->order->add_mpesa(array('latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId));
            $this->order->add_payment(array('orderId'=>$k->orderId,'paymentType'=>'mPesa','mPesaId'=>$mPesaId,'orderType'=>'single'));
            $this->product->update_product_quantity($orderInfo['priceinfo']->productId,$orderInfo['priceinfo']->qty);
            $orderUpdateArr=array('orderUpdatedate'=>date('Y-m-d H:i:s'),'udidPayment'=>$UDID,'deviceTokenPayment'=>$deviceToken,
                'latitudePayment'=>$latitude,'longitudePayment'=>$longitude,'isPaid'=>1);
            $orderUpdateArr['status']=2;
            $this->order->update($orderUpdateArr,$k->orderId);
            /// sendin SMS to user
            /*$sms_data=array('nMessage'=>'You have successfull placed an order TIDIIT-OD-'.$k->orderId.' for '.$orderInfo['pdetail']->title.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'],
                'receiverMobileNumber'=>$user->mobile,'senderId'=>'','receiverId'=>$user->userId,
                'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER');*/
            //send_sms_notification($sms_data);
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderInfo;
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$k->orderId;
            $mail_template_view_data=$defaultResources;
            $mail_template_view_data['single_order_success']=$mail_template_data;
            $receiverFullName=$user->firstName.' '.$user->lastName;
            global_tidiit_mail($user->email, "Your Tidiit order - TIDIIT-OD-".$k->orderId.' has placed successfully', $mail_template_view_data,'single_order_success',$receiverFullName);
            
            $this->sent_single_order_complete_mail($k->orderId);
        }
        $result=array();
        $result['message']='Thanks you for shopping with '.$defaultResources['MainSiteBaseURL'].'.Order placed successfully for each item selected.For More details check your "My Order" section.';
        success_response_after_post_get($result);
    }
    
    function single_order_mpesa_payment_final_post(){
        $userId=  $this->post('userId');
        $orderId=  $this->post('orderId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken==""){
            $this->response(array('error' => 'Please provide user index,latitude,longitude,device id,device token !'), 400); return FALSE;
        }
        
        //$orderId=0;
        $tidiitStrChr='TIDIIT-OD';
        $tidiitStr='';
        //Send Email message
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        
        $user = $this->user->get_details_by_id($userId)[0]; 
        $recv_email = $user->email;
        $recv_name=$user->firstName.' '.$user->lastName;
        $orderDetails=$this->order->details($orderId);
        $tidiitStr.=$tidiitStrChr.'-'.$orderId.',';
        $order_update=array();
        $order_update['isPaid'] = 1;
        $this->order->update($order_update,$orderId);
        $order=$this->order->get_single_order_by_id($orderId);
        $orderinfo=unserialize(base64_decode($order->orderInfo));
        $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
        $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$orderId;
        $mPesaId=$this->order->add_mpesa(array('latitude'=>$latitude,'userId'=>$userId,'longitude'=>$longitude,'appSource'=>$deviceType));
        $this->order->edit_payment(array('paymentType'=>'mPesa','mPesaId'=>$mPesaId),$orderId);
        
        $mail_template_view_data=load_default_resources();
        $mail_template_view_data['single_order_success']=$mail_template_data;
        global_tidiit_mail($recv_email, "Payment has completed for your Tidiit order TIDIIT-OD-".$orderId, $mail_template_view_data,'single_order_success_sod_final_payment',$recv_name);
        $this->_sent_single_order_complete_mail_sod_final_payment($orderId);
        
        /// here to preocess SMS to logistics partner
        /*$logisticsData=$PaymentDataArr['logisticsData'];
        if(!empty($logisticsData) && array_key_exists('deliveryStaffContactNo', $logisticsData)):
            $logisticMobileNo=$logisticsData['deliveryStaffContactNo'];
            if($logisticMobileNo!=""):
                $sms=$recv_name.' has completed the payment for Tidiit order '.$tidiitStr.' please process the delivery.';
                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$logisticMobileNo,'senderId'=>'','receiverId'=>'',
                'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-LOGISTICS');
                send_sms_notification($sms_data);
            endif;
        endif;*/
        
        /// SMS to payer
        $sms='Thanks for the payment.We have received for Tidiit order '.$tidiitStr.'.';
        $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$user->mobile,'senderId'=>'','receiverId'=>$user->userId,
        'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-PAYER');
        send_sms_notification($sms_data);
        
        /// here send mail to logistic partner
        /*$mailBody="Hi ".$PaymentDataArr['logisticsData']['deliveryStaffName'].",<br /> <b>$recv_name</b> has completed Tidiit payment for Order <b>".$tidiitStr.'</b><br /><br /> Pleasee process the delivery for the above order.<br /><br />Thanks<br>Tidiit Team.';
        $this->_global_tidiit_mail($PaymentDataArr['logisticsData']['deliveryStaffEmail'],'Tidiit payment submited  for Order '.$tidiitStr,$mailBody,'',$recv_name);
        unset($_SESSION['PaymentData']);*/
        
        
        $result=array();
        $result['message']='Thanks for the payment before order is Out for delivery';
        success_response_after_post_get($result);
    }
    
    
    
    function set_wishlist_post(){
        $userId=$this->post('userId');
        $productId=$this->post('productId');
        $productPriceId = $this->post('productPriceId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        $wishListDataArr=array('userId'=>$userId,'productId'=>$productId,'productPriceId'=>$productPriceId,'latitude'=>$latitude,'longitude'=>$longitude,'deviceType'=>$deviceType);
        if($this->order->add_to_wish_list($wishListDataArr)):
            $result=array();
            $result['message']='Selected item added to wish list successfully.';
            success_response_after_post_get($result);
        else:
            $this->response(array('error' => 'Unknow error to add the selected item for wishlist.'), 400);
        endif;
    }
    
    function unset_wishlist_post(){
        $userId=$this->post('userId');
        $productId=$this->post('productId');
        $productPriceId = $this->post('productPriceId');
        if($this->order->remove_wish_list($userId,$productId,$productPriceId)):
            $result=array();
            $result['message']='Selected item remove from wish list successfully.';
            success_response_after_post_get($result);
        else:
            $this->response(array('error' => 'Unknow error to remove the selected item from wishlist.'), 400);
        endif;
    }
    
    /// not used any where for testing only
    function get_order_details_get(){
        $orderId=$this->get('orderId');
        $orderDetails=$this->order->details($orderId);
        $orderInfo= unserialize(base64_decode($orderDetails[0]->orderInfo));
            pre($orderInfo);die;
    }
    
    function group_order_start_post(){
        $userId=  $this->post('userId');
        $productId=  $this->post('productId');
        $productPriceId=  $this->post('productPriceId');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=  $this->post('deviceType');
        $UDID=  $this->post('UDID');
        $deviceToken=  $this->post('deviceToken');
        
        if($userId=="" || $productId =="" || $productPriceId =="" || $latitude=="" || $longitude=="" || $deviceType =="" || $deviceToken=="" || $UDID==""){
            $this->response(array('error' => 'Please provide user index,product index, product price index,latitude,longitude,device id,device token,device type !'), 400); return FALSE;
        }
        
        $country_name=  get_counry_code_from_lat_long($latitude, $longitude);
        if($country_name==""){
            $this->response(array('error' => 'Please provide valid latitude and longitude for getting country for tax calculation'), 400); return FALSE;
        }
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }else{
            $user=$user[0];
        }
        $this->load->model('Product_model','product');
        $productDetails=  $this->product->details($productId);
        if(empty($productDetails)){
            $this->response(array('error' => 'Please provide valid product index!'), 400); return FALSE;
        }
        
        $cart = $this->order->tidiit_get_user_orders($user->userId, 0);//print_r($cart);die;//$user->userId
        $prod_price_info = $this->product->get_products_price_details_by_id($productPriceId);
        $is_cart_update = false;
        if($cart): 
            foreach ($cart as $item):            
                if(($item->productId == $productId) && ( $item->orderType== "GROUP")):
                    $data['orderId'] = $item->orderId;
                    $is_cart_update = true;
                endif;
            endforeach;
        endif;


        $orderinfo = [];
        $orderinfo['pdetail'] = $productDetails[0];
        $orderinfo['priceinfo'] = $prod_price_info;
        $productImageArr = $this->product->get_products_images($productId);
        $orderinfo['pimage'] = $productImageArr[0];

        //echo $country_name;die;
        $taxDetails = $this->product->get_tax_for_current_location($productId, $country_name.'_tax');
        $taxCol = $country_name.'_tax';
        $taxPercentage = $taxDetails->$taxCol;
        $orderAmountBeforeTax = $prod_price_info->price;
        $cTax = ($orderAmountBeforeTax*$taxPercentage)/100;
        $orderAmount = $orderAmountBeforeTax+$cTax;
        //$orderDataArr=array('taxAmount'=>$cTax,'discountAmount'=>$data['couponAmount'],'orderAmount'=>$orderAmount);

        
        //Order first step
        $order_data = array();
        $order_data['orderType'] = 'GROUP';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $productPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        $order_data['orderInfo'] = base64_encode(serialize($orderinfo));
        //Add Order
        if(!isset($data['orderId'])):
            $order_data['productQty'] = 0;
            $order_data['userId'] = $user->userId;
            $order_data['orderAmount'] = $orderAmount;
            $order_data['subTotalAmount'] = $prod_price_info->price;
            $order_data['taxAmount'] = $cTax;
            $qrCodeFileName=time().'-'.rand(1, 50).'.png';
            $order_data['qrCodeImageFile']=$qrCodeFileName;
            $order_data['IP']=  $this->input->ip_address();
            $orderId = $this->order->add($order_data);
            $data['orderId']=$orderId;
            $params=array();
            $params['data'] = $orderId;
            $params['savename'] = $this->config->item('ResourcesPath').'qr_code/'.$qrCodeFileName;
            $this->tidiitrcode->generate($params);
        endif;

        $order_data['orderId'] = $data['orderId'];
        $order_data['productPriceId'] = $productPriceId;
        $data['order'] = $this->order->get_single_order_by_id($data['orderId']);
        //==============================================//
        if($is_cart_update):
            if(isset($data['orderId'])):
                $order_update['orderInfo'] = base64_encode(serialize($orderinfo));
                $order_update['orderAmount'] = $orderAmount;
                $order_update['subTotalAmount'] = $prod_price_info->price;
                $order_update['taxAmount'] = $cTax;
                unset($order_update['orderId']);
                unset($order_update['orderDate']);
                $order_update['orderUpdatedate'] = date('Y-m-d H:i:s');
                $order_update['productQty'] = 0;
                //print_r($order_update);
                $this->order->update($order_update,$data['orderId']);
            endif;
        endif;

        $a = $this->_get_available_order_quantity($data['orderId']);
        $data['availQty'] = $prod_price_info->qty - $a[0]->productQty;

        //=============================================//
        if($data['order']->groupId):
            $data['group'] = $this->user->get_group_by_id($data['order']->groupId);
            $data['groupId'] = $data['order']->groupId;
        else:
            $data['group'] = false;
            $data['groupId'] = '';
        endif;
        //$my_groups = $this->user->get_my_groups();
        //$data['CatArr'] = $this->_get_user_select_cat_array();
        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        //$data['userMenuActive']=7;
        //$data['countryDataArr'] = $this->Country->get_all1();
        //$data['myGroups']=$my_groups;
        //$data['user']=$user;
        //$data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        //$this->load->view('group_order/group_order',$data);
        success_response_after_post_get($data);
    }
    
    function process_my_group_orders_by_id_post(){
        $orderId=$this->post('orderId');
        $userId=$this->post('userId');
        $latitude=$this->post('latitude');
        $logitude=$this->post('logitude');
        
        $country_name=  get_counry_code_from_lat_long($latitude, $longitude);
        $data['order'] = $this->order->get_single_order_by_id($orderId);
        $productId = $data['order']->productId;
        $productPriceId = $data['order']->productPriceId;
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            $this->response(array('error' => 'Please provide the product index, product price index.'), 400);
        endif;
        $data['orderId'] = $data['order']->orderId;
        $product = $this->product->details($productId);
        $product = $product[0];
        $prod_price_info = $this->product->get_products_price_details_by_id($productPriceId);
        $a = $this->_get_available_order_quantity($data['orderId']);
        $data['availQty'] = $prod_price_info->qty - $a[0]->productQty;
        
        //=============================================//
        if($data['order']->groupId):
            $data['group'] = $this->user->get_group_by_id($data['order']->groupId);
            $data['groupId'] = $data['order']->groupId;
        else:
            $data['group'] = false;
            $data['groupId'] = 0;
        endif;

        //$my_groups = $this->user->get_my_groups();
        //$data['CatArr'] = $this->_get_user_select_cat_array();
        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        //$data['userMenuActive']=7;
        //$data['countryDataArr']=$this->Country->get_all1();
        //$data['myGroups'] = $my_groups;
        //$data['user'] = $user;
        //$data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        //$this->load->view('group_order/group_order',$data);
        success_response_after_post_get($data);
    }
    
    function show_my_buyers_clubs_for_order_post(){
        $userId=  $this->post('userId');
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        $myGroupDataArr=$this->user->get_my_groups_apps($userId);
        $my_groups = $myGroupDataArr;
        $result['myGroups']=$my_groups;
        $result['ajaxType']="yes";
        success_response_after_post_get($result);
    }
    
    function update_order_buying_club_id_post(){
        $groupId = $this->post('groupId');
        $orderId = $this->post('orderId');
        $group = $this->user->get_group_by_id($groupId,TRUE);
        $data['groupId'] = $groupId;
        $this->order->update($data, $orderId);
        $group = json_decode(json_encode($group), true);
        $data['group'] = $group;
        
        
        $data['order'] = $this->order->get_single_order_by_id($orderId);
        $productId = $data['order']->productId;
        $productPriceId = $data['order']->productPriceId;
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            $this->response(array('error' => 'Please provide the product index, product price index.'), 400);
        endif;
        $data['orderId'] = $data['order']->orderId;
        $product = $this->product->details($productId);
        $product = $product[0];
        $prod_price_info = $this->product->get_products_price_details_by_id($productPriceId);
        $a = $this->_get_available_order_quantity($data['orderId']);
        $data['availQty'] = $prod_price_info->qty - $a[0]->productQty;

        
        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        success_response_after_post_get($data);
    }
    
    function sent_single_order_complete_mail($orderId){
        $orderDetails=  $this->order->details($orderId);
        //pre($orderDetails);die;
        $adminMailData= load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfoDataArr);die;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        /// for seller
        $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        global_tidiit_mail($orderDetails[0]->sellerEmail, "A new order no - TIDIIT-OD-".$orderId.' has placed from Tidiit Inc Ltd', $adminMailData,'seller_single_order_success',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

        /// for support
        $adminMailData['userFullName']='Tidiit Inc Support';
        $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        global_tidiit_mail($supportEmail, "Order no - TIDIIT-OD-".$orderId.' has placed by '.$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName, $adminMailData,'support_single_order_success','Tidiit Inc Support');
        //die;
        $sms_data=array('nMessage'=>'Your Tidiit order TIDIIT-OD-'.$orderId.' for '.$orderInfoDataArr['pdetail']->title.' has placed successfully. More details about this notifiaction,Check '.$adminMailData['MainSiteBaseURL'],
        'receiverMobileNumber'=>$orderDetails[0]->buyerMobileNo,'senderId'=>'','receiverId'=>$orderDetails[0]->userId,
        'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-CONFIRM');
        send_sms_notification($sms_data);
        return TRUE;
    }
    
    
    function move_to_wish_list_post(){
        $userId=$this->post('userId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)):
            $this->response(array('error' => 'Please provide valid user index.'), 400);return FALSE;
        endif;
        $orderId=$this->post('orderId');
        $orderDetails=$this->order->details($orderId);
        if(empty($orderDetails)):
            $this->response(array('error' => 'Please provide valid order index.'), 400);return FALSE;
        elseif($orderDetails[0]->status>0):
            $this->response(array('error' => 'Please provide valid order index,current is not in truck.'), 400);return FALSE;
        endif;
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        
        $orderInfo= unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfo);die;
        $productId=$orderInfo['priceinfo']->productId;
        $productPriceId = $orderInfo['priceinfo']->productPriceId;
        
        $wishListDataArr=array('userId'=>$userId,'productId'=>$productId,'productPriceId'=>$productPriceId,'latitude'=>$latitude,'longitude'=>$longitude,'appSource'=>$deviceType,'fromSource'=>'cart');
        if($this->order->add_to_wish_list($wishListDataArr)):
            $this->order->remove_order_from_cart($orderId,$userId);
            $result=array();
            $result['message']='selected item moved wishlist successfully';
            success_response_after_post_get($result);
        else:
            $this->response(array('error' => 'Unknow error to move the selected item to wishlist.'), 400);
        endif;
    }
    
    function _sent_single_order_complete_mail_sod_final_payment($orderId){
        $orderDetails=  $this->order->details($orderId);
        //pre($orderDetails);die;
        $adminMailData=  load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfoDataArr);die;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        /// for seller
        $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $buyerFullName=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $adminMailData['buyerFullName']=$buyerFullName;
        global_tidiit_mail($orderDetails[0]->sellerEmail, "Payment has submited for Tidiit order TIDIIT-OD-".$orderId.' before delivery', $adminMailData,'seller_single_order_success_sod_final_payment',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

        /// for support
        $adminMailData['userFullName']='Tidiit Inc Support';
        $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        global_tidiit_mail($supportEmail, "Payment has submited for Tidiit Order TIDIIT-OD-".$orderId.' before delivery', $adminMailData,'support_single_order_success_sod_final_payment','Tidiit Inc Support');
        //die;
        
        return TRUE;
    }
    
    function _get_available_order_quantity($orderId){
        $pevorder = $this->order->get_single_order_by_id($orderId);
        if($pevorder->parrentOrderID):
            $availQty = $this->order->get_available_order_quantity($pevorder->parrentOrderID);
        else:
            $availQty = $this->order->get_available_order_quantity($orderId);
        endif;
        return $availQty;
    }
}
    
?>