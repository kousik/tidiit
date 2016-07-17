<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';

use Razorpay\Api\Api;

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
        if($userId==""){
            $this->response(array('error' => 'Please provide user index.'), 400); return FALSE;
        }
        
        if($productId ==""){
            $this->response(array('error' => 'Please provide product index.'), 400); return FALSE;
        }
        
        if($productPriceId == ""){
            $this->response(array('error' => 'Please provide product price index.'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
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
        //die($countryShortName);
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
        //$countryShortName='IN';
        $suggestedCountryShortName=array('IN','KE');
        if(!in_array($countryShortName, $suggestedCountryShortName)){
            $countryShortName=  $this->user->loged_in_user_shipping_country_code($cartDataArr['userId']);
        }
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
        if($userId==""){
            $this->response(array('error' => 'Please provide user index.'), 400); return FALSE;
        }
        
        if($latitude==""){
            $this->response(array('error' => 'Please provide latitude.'), 400); return FALSE;
        }
        
        if($longitude==""){
            $this->response(array('error' => 'Please provide Longitude.'), 400); return FALSE;
        }
        
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //$countryShortName='IN';
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
        $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($result);
    }
    
    function remove_item_from_cart_post(){
        $userId = $this->post('userId');
        $orderId = $this->post('orderId');
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=  $this->post('deviceType');
        $UDID=  $this->post('UDID');
        $deviceToken=  $this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
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
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        $longitude = $this->post('longitude');
        $latitude = $this->post('latitude');
        $orderId = $this->post('orderId');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        //$defaultDataArr=array('deviceType'=>$deviceType,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index.!'), 400); return FALSE;
        }
        
        if($firstName==""){
            $this->response(array('error' => 'Please provide first name.'), 400); return FALSE;
        }
        
        if($lastName==""){
            $this->response(array('error' => 'Please provide last name.'), 400); return FALSE;
        }
        
        if($countryId==""){
            $this->response(array('error' => 'Please provide country index.'), 400); return FALSE;
        }
        
        if($cityId==""){
            $this->response(array('error' => 'Please provide city index.'), 400); return FALSE;
        }
        
        if($zipId==""){
            $this->response(array('error' => 'Please provide zip index.'), 400); return FALSE;
        }
        
        if($localityId ==""){
            $this->response(array('error' => 'Please provide locality index.'), 400); return FALSE;
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
        if($orderId==""):
            foreach($allIncompleteOrders As $k){
                $orderInfo= unserialize(base64_decode($k->orderInfo));
                $orderInfo['shipping']=$userShippingDetails[0];
                $this->order->update(array('orderInfo'=>base64_encode(serialize($orderInfo))),$k->orderId);
            }
        else:
            foreach($allIncompleteOrders As $k){
                $orderInfo= unserialize(base64_decode($k->orderInfo));
                $orderInfo['shipping']=$userShippingDetails[0];
                if($orderId==$k->orderId)
                    $this->order->update(array('orderInfo'=>base64_encode(serialize($orderInfo))),$k->orderId);
            }
        endif;    
        
        
        $result=array();
        $result['message']='Shipping address updated successfully';
        success_response_after_post_get($result);
    }
    
    function test_get(){
        /*$orderId=$this->get('orderId');
        $order = $this->order->get_single_order_by_id($orderId);
        $pro = $this->product->details($order->productId);
        $orderinfo['pdetail'] = $pro[0];
        $orderinfo['priceinfo'] = $this->product->get_products_price_details_by_id($order->productPriceId);
        pre($orderinfo);die;*/
        $userId=  $this->get('userId');
        $rs=  $this->user->get_details_by_id($userId);
        pre($rs);die;
    }
    
    function test_post(){
        $userId=  $this->post('userId');
        $rs=  $this->user->app_get_details_by_id($userId);
        pre($rs);die;
    }
    
    function single_order_coupon_set_post(){
        $this->load->model('Coupon_model','coupon');
        $promocode=$this->post('couponCode',TRUE);
        $orderId = $this->post('orderId',TRUE);
        $userId = $this->post('userId',TRUE);
        $latitude = $this->post('latitude',TRUE);
        $longitude = $this->post('longitude',TRUE);
        $deviceType = $this->post('deviceType',TRUE);
        
        $UDID=  $this->post('UDID');
        $deviceToken=  $this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        $coupon = $this->coupon->is_coupon_code_exists($promocode);
        if(!$coupon):
            $this->response(array('error' => 'Invalid promo code or promo code has expaired!!'), 400); return FALSE;
        endif;
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //$countryShortName='IN';
        $userDetails=  $this->user->get_details_by_id($userId);
        if(empty($userDetails)){
            $this->response(array('error' => 'Invalid user index provide!'), 400); return FALSE;
        }
            
        $ordercoupon = $this->coupon->is_coupon_code_valid_for_single($coupon);
        
        if($ordercoupon):
            $this->response(array('error' => 'Invalid promo code or promo code has expaired!!'), 400); return FALSE;
        else:
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
            
            if($orderDetails[0]->orderType!="SINGLE"){
                $this->response(array('error' => 'Invalid order type provided.'), 400); return FALSE;
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
            if($data['couponAmount']=="0.00"){
                $this->response(array('error' => 'Unknown error arise to applied the coupon code.'), 400); return FALSE;
            }
            $tax=0;
            $grandTotal=0;
            $couponAmount=0;
            if($countryShortName==FALSE){
                $this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
            }
            foreach($allItemArr AS $k){
                if($k['orderId']==$orderId){
                    $suggestedCountryShortName=array('IN','KE');
                    if(!in_array($countryShortName, $suggestedCountryShortName)){
                        $countryShortName=  $this->user->loged_in_user_shipping_country_code($userId);
                    }
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
            $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
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
        $suggestedCountryShortName=array('IN','KE');
        if(!in_array($countryShortName, $suggestedCountryShortName)){
            $countryShortName=  $this->user->loged_in_user_shipping_country_code($orderDetails[0]->userId);
        }
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
    
    function show_payment_gateway_name_post(){
        $userId=  $this->post('userId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index.'), 400); return FALSE;
        }
        
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //die($countryShortName);
        if($countryShortName==FALSE){
            $this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
        }
        
        $dbGatewayDataArr=$this->order->get_all_gateway(TRUE,$countryShortName);
        $dbGatewayDataArr[]=array('gatewayId'=>100001,'gatewayTitle'=>'Settlement On Delivery','gatewayName'=>'SettlementOnDelivery','gatewayCode'=>'settlement_on_delivery','countryCode'=>$countryShortName,'status'=>1);
        
        $result=array();
        $result['paymentGatewayData']=$dbGatewayDataArr;
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
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index'), 400); return FALSE;
        }
        
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
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
            
            $mPesaId=$this->order->add_mpesa(array('latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId));
            $this->order->add_payment(array('orderId'=>$k->orderId,'paymentType'=>'mPesa','mPesaId'=>$mPesaId,'orderType'=>'single'));
            $this->product->update_product_quantity($orderInfo['priceinfo']->productId,$orderInfo['priceinfo']->qty);
            $orderUpdateArr=array('orderUpdatedate'=>date('Y-m-d H:i:s'),'udidPayment'=>$UDID,'deviceTokenPayment'=>$deviceToken,
                'latitudePayment'=>$latitude,'longitudePayment'=>$longitude,'isPaid'=>1);
            $orderUpdateArr['status']=2;
            $this->order->update($orderUpdateArr,$k->orderId);
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
        /*$logisticsData=$logisticsData;
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
        /*$mailBody="Hi ".$logisticsData['deliveryStaffName'].",<br /> <b>$recv_name</b> has completed Tidiit payment for Order <b>".$tidiitStr.'</b><br /><br /> Pleasee process the delivery for the above order.<br /><br />Thanks<br>Tidiit Team.';
        global_tidiit_mail($logisticsData['deliveryStaffEmail'],'Tidiit payment submited  for Order '.$tidiitStr,$mailBody,'',$recv_name);
        unset($_SESSION['PaymentData']);*/
        
        
        $result=array();
        $result['message']='Thanks for the payment before order is Out for delivery';
        success_response_after_post_get($result);
    }
    
    function single_order_razorpay_post(){
        $userId=  $this->post('userId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index'), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId)[0];
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $allIncompleteOrders= $this->order->get_incomplete_order_by_user($userId,'single');
        $defaultResources=load_default_resources();
        $paymentGatewayAmount=0;
        $allOrderArray=array();
        if(!empty($allIncompleteOrders)){
            foreach ($allIncompleteOrders As $k){
                $order=array();
                $paymentGatewayAmount+=$k->orderAmount;
                $order['status'] = 8;
                $allOrderArray[] = $k->orderId;

                $orderInfo = array();
                $mail_template_data = array();
                //pre($k);die;
                $this->order->update($order,$k->orderId);
            }
        }else{
            $this->response(array('error' =>'There is no valid item in the cart for process.'), 400); return FALSE;
        }
        
        $result=array();
        $result['profile_key']="rzp_test_yfWhsaZcULj1KQ";
        $result['currency']='INR';
        $result['amount']=$paymentGatewayAmount*100;
        $result['name']=$user->firstName.' '.$user->lastName;
        $result['email']=$user->email;
        if($user->contactNo!=""):
            $result['contact']=$user->contactNo;
        else:
            $result['contact']=$user->mobile;
        endif;
        $result['description']="Shopping from Tidiit";
        $result['image']="http://tidiit.com/resources/images/logo.png";
        $result['orderIdData']=  base64_encode(serialize($allOrderArray));
        $result['orderType']= 'single';
        $result['finalReturn']= 'no';
        success_response_after_post_get($result);
    }
    
    function razorpay_return_success_post(){
        $razorpayPaymentId=trim($this->post('razorpayPaymentId'));
        $orderIdData=trim($this->post('orderIdData'));
        $orderType=trim($this->post('orderType'));
        $finalReturn=trim($this->post('finalReturn'));
        $userId=trim($this->post('userId'));
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $deliveryStaffEmail=trim($this->post('deliveryStaffEmail',TRUE));
        $deliveryStaffContactNo=trim($this->post('deliveryStaffContactNo',TRUE));
        $deliveryStaffName=trim($this->post('deliveryStaffName',TRUE));
        
        $logisticsData=array('deliveryStaffName'=>$deliveryStaffName,'deliveryStaffContactNo'=>$deliveryStaffContactNo,'deliveryStaffEmail'=>$deliveryStaffEmail);
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index.'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($razorpayPaymentId==""){
            $this->response(array('error' => 'Please provide Razorpay payment id'), 400); return FALSE;
        }
        
        if($orderIdData==""){
            $this->response(array('error' => 'Please provide order ids'), 400); return FALSE;
        }
        
        if($orderType==""){
            $this->response(array('error' => 'Please provide order type'), 400); return FALSE;
        }
        
        if( $finalReturn ==""){
            $this->response(array('error' => "Please provide final return data not received."), 400); return FALSE;
        }else{
            $orderIdDataArr= unserialize(base64_decode($orderIdData));
            //$mail_message='$userId : '.$userId.' == $orderIdData : '.$orderIdData.' == $razorpayPaymentId : '.$razorpayPaymentId.' == $latitude : '.$latitude.' == $longitude : '.$longitude.' == $deviceToken : '.$deviceToken.' == $UDID : '.$UDID.' == $orderType : '.$orderType.' == $finalReturn : '.$finalReturn;
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>$mail_message));
            ///@mail('cto.tidiit@gmail.com','POST data',$mail_message);
            if(count($orderIdDataArr)==1){
                $dataArr=array('userId'=>$userId,'orderIds'=>$orderIdDataArr[0],'razorpayPaymentId'=>$razorpayPaymentId,'latitude'=>$latitude,'longitude'=>$longitude,'appSource'=>$deviceType,'deviceToken'=>$deviceToken,'UDID'=>$UDID,'addedTime'=> date('Y-m-d H:i:s'));
            }else{
                $dataArr=array('userId'=>$userId,'orderIds'=>  implode(',', $orderIdDataArr),'razorpayPaymentId'=>$razorpayPaymentId,'latitude'=>$latitude,'longitude'=>$longitude,'appSource'=>$deviceType,'deviceToken'=>$deviceToken,'UDID'=>$UDID,'addedTime'=>date('Y-m-d H:i:s'));
            }
            $this->order->add_rajorpay_return_data($dataArr);
            $razorpayInfo=$this->order->get_razorpay_info();
            $api_key=$razorpayInfo[0]->userName;
            $api_secret=$razorpayInfo[0]->password;
            $api = new Api($api_key, $api_secret);
            $payment = $api->payment->fetch($razorpayPaymentId);
            $Amount=$payment->amount;
            $captureData=$api->payment->fetch($razorpayPaymentId)->capture(array('amount'=>$Amount));
            if($captureData->captured==1){
                $orderType = $orderType;
                if($orderType=='group'):
                    if($finalReturn=='no'):
                        $productPriceArr=$this->order->get_product_price_details_by_orderid($orderIdDataArr[0]);
                        $this->product->update_product_quantity($productPriceArr[0]['productId'],$productPriceArr[0]['qty']);
                        $this->process_razorpay_success_group_order($orderIdDataArr[0],$razorpayPaymentId);
                    else:
                        $this->process_razorpay_success_group_order_final($orderIdDataArr[0],$razorpayPaymentId,$logisticsData);
                    endif;
                else:
                    send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'Single Order process start'));
                    if($finalReturn=='no'):
                        foreach($orderIdDataArr As $k=> $v){
                            $productPriceArr=$this->order->get_product_price_details_by_orderid($v);
                            $this->product->update_product_quantity($productPriceArr[0]['productId'],$productPriceArr[0]['qty']);
                        }
                        $this->process_razorpay_success_single_order($orderIdDataArr,$razorpayPaymentId);
                    else:
                        send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'Single Order process start with $finalReturn == yes'));
                        $this->process_razorpay_success_single_order_final($orderIdDataArr,$razorpayPaymentId,$logisticsData);
                    endif;
                endif;
            }else{
                $this->response(array('error' => "unknown eror in capturing the data."), 400); return FALSE;
            }
        }
        
    }
    
    function set_wishlist_post(){
        $userId=$this->post('userId');
        $productId=$this->post('productId');
        $productPriceId = $this->post('productPriceId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID = $this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken=="" || $productId=="" || $productPriceId==""){
            $this->response(array('error' => 'Please provide user index,latitude,longitude,device id,device token,product index,product price index !'), 400); return FALSE;
        }
        $wishListDataArr=array('userId'=>$userId,'productId'=>$productId,'productPriceId'=>$productPriceId,'latitude'=>$latitude,'longitude'=>$longitude,'appSource'=>$deviceType);
        if($this->order->add_to_wish_list($wishListDataArr)):
            $result=array();
            $result['message']='Selected item added to wish list successfully.';
            success_response_after_post_get($result);
        else:
            $this->response(array('error' => 'The selected item already in your wishlist.'), 400);
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
        $suggestedCountryShortName=array('IN','KE');
        if(!in_array($country_name, $suggestedCountryShortName)){
            $country_name=  $this->user->loged_in_user_shipping_country_code($userId);
        }
        $taxDetails = $this->product->get_tax_for_current_location($productId, $country_name.'_tax');
        $taxCol = $country_name.'_tax';
        $taxPercentage = $taxDetails->$taxCol;
        $orderAmountBeforeTax = $prod_price_info->price;
        $cTax = ($orderAmountBeforeTax*$taxPercentage)/100;
        $orderAmount = $orderAmountBeforeTax+$cTax;
        
        //Order first step
        $order_data = array();
        $order_data['orderType'] = 'GROUP';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $productPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        $order_data['appSource'] =$deviceType;
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
            $order_data['latitude']=  $latitude;
            $order_data['longitude']=  $longitude;
            $order_data['appSource']= $deviceType;
            $orderId = $this->order->add($order_data);
            $data['orderId']=$orderId;
            $params=array();
            $params['data'] = $orderId;
            $qrCodeFilePath=SITE_RESOURCES_PATH.'qr_code/'.$qrCodeFileName;
            $params['savename']=$qrCodeFilePath;
            $this->tidiitrcode->generate($params);
            @copy($qrCodeFilePath,MAIN_SERVER_RESOURCES_PATH.'qr_code/'.$qrCodeFileName);
            @unlink($qrCodeFilePath);
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
        if(!isset($data['orderId'])){
            $this->response(array('error' => 'Unknown error to generate the order index for start order!'), 400); return FALSE;
        }
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
        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        success_response_after_post_get($data);
    }
    
    function process_my_group_orders_by_id_post(){
        $orderId=$this->post('orderId');
        $userId=$this->post('userId');
        $latitude=$this->post('latitude');
        $longitude=$this->post('longitude');
        $deviceType=  $this->post('deviceType');
        $UDID=  $this->post('UDID');
        $deviceToken=  $this->post('deviceToken');
        
        $country_name=  get_counry_code_from_lat_long($latitude, $longitude);
        
        if($deviceToken == "" || $deviceType =="" || $UDID =="" ){
            $this->response(array('error' => 'Please provide the device token,device type, UDID.'), 400);
        }
        $order=$this->order->get_single_order_by_id($orderId);
        if(empty($order)){
            $this->response(array('error' => 'Please valid order index.'), 400);
        }
        $data['order'] = $order;
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

        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        success_response_after_post_get($data);
    }
    
    function show_my_buyers_clubs_for_order_post(){
        $userId=  $this->post('userId');
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        $myGroupDataArr=$this->user->get_my_created_groups_apps($userId);
        $my_groups = $myGroupDataArr;
        $result['myGroups']=$my_groups;
        $result['ajaxType']="yes";
        success_response_after_post_get($result);
    }
    
    function update_order_buying_club_id_post(){
        $userId = $this->post('userId');
        $orderId = $this->post('orderId');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=  $this->post('deviceType');
        $UDID=  $this->post('UDID');
        $deviceToken=  $this->post('deviceToken');
        if($userId=="" || $orderId =="" || $latitude=="" || $longitude=="" || $deviceToken="" || $UDID=="" || $deviceType==""){
            $this->response(array('error' => 'Please provide valid user index,order index,latitude,longitude,device type,devce token,UDID!'), 400); return FALSE;
        }
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        $groupId = $this->post('groupId');
        $group = $this->user->get_group_by_id($groupId,TRUE);
        if(empty($group)){
            $this->response(array('error' => 'Please provide valid group index!'), 400); return FALSE;
        }
        
        if(!$this->user->is_valid_admin_for_group($groupId,$userId)){
            $this->response(array('error' => 'Invallid group index as you are not leader of the group.'), 400); return FALSE;
        }
        
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
        $data['message']="success";
        success_response_after_post_get($data);
    }
    
    function set_qty_for_buying_club_order_post(){
        $userId = $this->post('userId');
        $orderId = $this->post('orderId');
        $qty=  $this->post('qty');
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=  $this->post('deviceType');
        $UDID=  $this->post('UDID');
        $deviceToken=  $this->post('deviceToken');
        if($userId=="" || $orderId=="" || $UDID=="" || $deviceToken=="" || $deviceType=="" || $latitude=="" || $longitude=="" ||$qty==""){
            $this->response(array('error' => 'Please provide user index,order index,UDID,device token,device type,latitude,longitude,quntity!'), 400); return FALSE;
        }
        
        $country_name=  get_counry_code_from_lat_long($latitude, $longitude);
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        
        $order = $this->order->get_single_order_by_id($orderId);
        $prod_price_info = $this->product->get_products_price_details_by_id($order->productPriceId);
        
        $a = $this->_get_available_order_quantity($orderId);
        $availQty = $prod_price_info->qty - $a[0]->productQty;
        
        if($qty > $availQty):
            $this->response(array('error' => 'Please provide valid quanity less or equal to the avilable!'), 400); return FALSE;
        endif;

        $single_price = ($prod_price_info->price/$prod_price_info->qty);
        $price = number_format($single_price, 2, '.', '');
        $totalprice = number_format($price*$qty, 2, '.', '');

        
        if($country_name==''){
            $this->response(array('error' => 'Please provide valid latitude and longitude for getting country for tax calculation'), 400); return FALSE;
        }
        $suggestedCountryShortName=array('IN','KE');
        if(!in_array($country_name, $suggestedCountryShortName)){
            $country_name=  $this->user->loged_in_user_shipping_country_code($userId);
        }
        $taxDetails = $this->product->get_tax_for_current_location($order->productId, $country_name.'_tax');
        $taxCol = $country_name.'_tax';
        $taxPercentage = $taxDetails->$taxCol;
        $orderAmountBeforeTax = $totalprice;
        $cTax = ($orderAmountBeforeTax*$taxPercentage)/100;
        $orderAmount = $orderAmountBeforeTax+$cTax;

        $order_update = [];
        $order_update['orderAmount'] = $orderAmount;
        $order_update['subTotalAmount'] = $totalprice;
        $order_update['productQty'] = $qty;
        $order_update['taxAmount'] = $cTax;
        $this->order->update($order_update,$orderId);
        $result=array('message'=>'success');
        success_response_after_post_get($result);
    }
    
    function review_buying_club_order_post(){
        $userId=  $this->post('userId');
        $orderId=  $this->post('orderId');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        
        $order = $this->order->get_single_order_by_id($orderId);
        $groupDetails = $this->user->get_group_by_id($order->groupId,TRUE);
        $orderInfo=unserialize(base64_decode($order->orderInfo));
        //pre($orderInfo);die;
        $order = json_decode(json_encode($order), true);
        $order['productTitle']=$orderInfo['pdetail']->title;
        $order['qty']=$orderInfo['priceinfo']->qty;
        $order['pimage']=$orderInfo['pimage']->image;
        
        //$order['orderInfo']=json_decode(json_encode($orderInfo), true);;
        //pre($order);die;
        $result=array();
        $newAllItemArr=array();
        $newAllItemArr[]=$order;
        $result['allItemArr']=$newAllItemArr;
        if($userId==$groupDetails['groupAdminId']){
            $result['isLeader']='yes';
        }else{
            $result['isLeader']='no';
        }
        $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($result);
    }
    
    function buying_club_order_coupon_set_post(){
        $this->load->model('Coupon_model','coupon');
        $promocode=$this->post('couponCode',TRUE);
        $orderId = $this->post('orderId',TRUE);
        $userId = $this->post('userId',TRUE);
        $latitude = $this->post('latitude',TRUE);
        $longitude = $this->post('longitude',TRUE);
        
        $deviceType=  $this->post('deviceType');
        $UDID=  $this->post('UDID');
        $deviceToken=  $this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($userId=="" || $orderId=="" || $promocode==""){
            $this->response(array('error' => 'Please provide user index,order index,coupon Code!'), 400); return FALSE;
        }
        $coupon = $this->coupon->is_coupon_code_exists($promocode);
        $orderDetails=$this->order->details($orderId,TRUE);
        if(empty($orderDetails)){
            $this->response(array('error' => 'Invalid order index provided.'), 400); return FALSE;
        }
        
        $userDetails=  $this->user->get_details_by_id($userId);
        if(empty($userDetails)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        
        if(!$coupon):
            $this->response(array('error' => 'Invalid promo code or promo code has expaired!!'), 400); return FALSE;
        endif;
        
        $ordercoupon = $this->coupon->is_coupon_code_valid_for_single($coupon);
        
        if($ordercoupon):
            $this->response(array('error' => 'Invalid promo code or promo code has expaired!!'), 400); return FALSE;
        else:
            $orderIdArr=array();
            foreach($orderDetails As $k){
                $orderIdArr[]=$k['orderId'];
            }
            /*if($this->coupon->is_coupon_recently_used($orderIdArr,$coupon->couponId)==TRUE){
                $this->response(array('error' => 'Promo code has alrady used in your current session.'), 400); return FALSE;
            }*/
            //pre($orderDetails);die;
            $ctotal=0;
            foreach($orderDetails AS $k){ //pre($k);die;
                $ctotal +=$k['subTotalAmount'];
            }
            
            if($coupon->type == 'percentage'):
                $amt = ($coupon->amount/100)*$orderDetails[0]['subTotalAmount'];
                $amt1 = number_format($amt, 2, '.', '');
                $data['couponAmount'] = substr($amt1, 0, -3);
            elseif($coupon->type == 'fix'):
                $data['couponAmount'] = $coupon->amount;
            endif;
            //pre($data);die;
            $tax=0;
            $grandTotal=0;
            $couponAmount=0;
            $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
            //$countryShortName='IN';
            if($countryShortName==FALSE){
                $this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
            }
            foreach($orderDetails AS $k){
                if($k['orderId']==$orderId){
                    $suggestedCountryShortName=array('IN','KE');
                    if(!in_array($countryShortName, $suggestedCountryShortName)){
                        $countryShortName=  $this->user->loged_in_user_shipping_country_code($userId);
                    }
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
            $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
            success_response_after_post_get($result);
        endif;
    }
    
    function buying_club_order_coupon_unset_post(){
        $this->load->model('Coupon_model','coupon');
        $orderId=$this->post('orderId',TRUE);
        $userId=$this->post('userId',TRUE);
        $latitude = $this->post('latitude',TRUE);
        $longitude = $this->post('longitude',TRUE);
        
        $orderDetails=$this->order->details($orderId);
        if(empty($orderDetails)){
            $this->response(array('error' => 'Invalid order index provided.'), 400); return FALSE;
        }
        
        $userDetails=  $this->user->get_details_by_id($userId);
        if(empty($userDetails)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //$countryShortName='IN';
        $suggestedCountryShortName=array('IN','KE');
        if(!in_array($countryShortName, $suggestedCountryShortName)){
            $countryShortName=  $this->user->loged_in_user_shipping_country_code($userId);
        }
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
    
    function buying_club_leader_sod_payment_post(){
        $orderId=  $this->post('orderId');
        $userId=  $this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index'), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId)[0];
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        
        $orderDetails= $this->order->details($orderId);
                
        //pre($allIncompleteOrders);die;
        $defaultResources=load_default_resources();
        $pevorder = $this->order->get_single_order_by_id($orderId);
        $a = $this->_get_available_order_quantity($orderId);
        $prod_price_info = $this->product->get_products_price_details_by_id($pevorder->productPriceId);
        $order_update=array();
        $order_update['status']=1;
        $this->order->update($order_update,$orderId);
        $this->order->order_group_status_update($orderId,$order_update['status'],$pevorder->parrentOrderID);
        $order = $pevorder;
        
        $this->product->update_product_quantity($prod_price_info->productId,$prod_price_info->qty);
        
        $orderinfo = array();
        $pro = $this->product->details($order->productId);
        $orderinfo['pdetail'] = $pro[0];
        $orderinfo['priceinfo'] = $this->product->get_products_price_details_by_id($order->productPriceId);
        $productImageArr =$this->product->get_products_images($order->productId);
        $orderinfo['pimage'] = $productImageArr[0];            

        $userShippingDataDetails = $this->user->get_user_shipping_information($userId);
        $orderinfo['shipping'] = $userShippingDataDetails[0];
        $group = $this->user->get_group_by_id($order->groupId);    
        if($order->groupId):
            $orderinfo['group'] = $group;
        endif;
        //pre($orderinfo);die;
        $info['orderInfo'] = base64_encode(serialize($orderinfo));
        $this->order->update($info, $orderId);
        $orderinfo=json_decode(json_encode(unserialize(base64_decode($orderDetails[0]->orderInfo))), true);
        //pre($or)
        foreach($group->users as $key => $usr):
            $mail_template_data=array();
            $data['senderId'] = $userId;
            $data['receiverId'] = $usr->userId;
            $data['nType'] = 'BUYING-CLUB-ORDER';

            $data['nTitle'] = 'New Buying Club order running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';
            $mail_template_data['TEMPLATE_GROUP_ORDER_START_TITLE']=$group->admin->firstName.' '.$group->admin->lastName;
            $data['nMessage'] = "Hi, <br> You have requested to buy Buying Club[".$group->groupTitle."] order product.<br>";
            $data['nMessage'] .= "Product is <a href=''>".$orderinfo['pdetail']['title']."</a><br>";
            $mail_template_data['TEMPLATE_GROUP_ORDER_START_PRODUCT_TITLE']=$orderinfo['pdetail']['title'];
            $data['nMessage'] .= "Want to process the order ? <br>";
            $data['nMessage'] .= "<a href='".$defaultResources['MainSiteBaseURL']."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".$defaultResources['MainSiteBaseURL']."shopping/group-order-accept-process/".base64_encode($orderId*226201)."' class='btn btn-success btn-lg'>Accept</a><br>";
            $mail_template_data['TEMPLATE_GROUP_ORDER_START_ORDERID']=$orderId;
            $data['nMessage'] .= "Thanks <br> Tidiit Team.";
            $data['orderId'] =$orderId;
            $data['productId'] =$orderinfo['priceinfo']['productId'];
            $data['productPriceId'] =$orderinfo['priceinfo']['productPriceId'];


            $data['isRead'] = 0;
            $data['status'] = 1;
            $data['createDate'] = date('Y-m-d H:i:s');
            $data['appSource'] = $deviceType;

            //Send Email message
            $recv_email = $usr->email;
            $sender_email = $group->admin->email;

            $mail_template_view_data=$defaultResources;
            $mail_template_view_data['group_order_start']=$mail_template_data;
            global_tidiit_mail($recv_email, "New Buying Club Order Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_start');
            //pre($data);die;
            $notificationId=$this->user->notification_add($data);
            
            $push_not_data['receiverId'] = $usr->userId;
            $push_not_data['nType'] = "BUYING-CLUB-ORDER";
            $push_not_data['nTitle'] = 'New Buying Club Order Invitation';
            $push_not_data['appSource'] = $deviceType;
            $push_not_data['notificationId'] = $notificationId;
            $push_not_data['nMessage'] = 'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'];
            send_push_notification($push_not_data);

            /// sendin SMS to allmember
            $sms_data=array('nMessage'=>'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'],
                'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$usr->userId,
                'senderMobileNumber'=>$group->admin->mobile,'nType'=>'CREATE-'.$data['nType']);
            send_sms_notification($sms_data);
        endforeach;
        $settlementOnDeliveryId=$this->order->add_sod(array('latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId,'appSource'=>$deviceType));
        $this->order->add_payment(array('orderId'=>$orderId,'paymentType'=>'settlementOnDelivery','settlementOnDeliveryId'=>$settlementOnDeliveryId,'orderType'=>'group'));
        $result=array();
        $result['message']='Group invited to group member successfully.Your group order started successfully,';
        success_response_after_post_get($result);
    }
    
    function buying_club_leader_mpesa_payment_post(){
        $orderId=  $this->post('orderId');
        $userId=  $this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index'), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId)[0];
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        
        $orderDetails= $this->order->details($orderId);
                
        //pre($allIncompleteOrders);die;
        $defaultResources=load_default_resources();
        $pevorder = $this->order->get_single_order_by_id($orderId);
        $a = $this->_get_available_order_quantity($orderId);
        $prod_price_info = $this->product->get_products_price_details_by_id($pevorder->productPriceId);
        $order_update=array('status'=>1,'isPaid'=>1);
        $update = $this->order->update($order_update,$orderId);
        
        $this->order->order_group_status_update($orderId,$order_update['status'],$pevorder->parrentOrderID);
        $order = $this->order->get_single_order_by_id($orderId);
        
        $orderinfo = array();
        $pro = $this->product->details($order->productId);
        $orderinfo['pdetail'] = $pro[0];
        $orderinfo['priceinfo'] = $this->product->get_products_price_details_by_id($order->productPriceId);
        $productImageArr =$this->product->get_products_images($order->productId);
        $orderinfo['pimage'] = $productImageArr[0];            

        $userShippingDataDetails = $this->user->get_user_shipping_information($userId);
        $orderinfo['shipping'] = $userShippingDataDetails[0];
        $group = $this->user->get_group_by_id($order->groupId);    
        if($order->groupId):
            $orderinfo['group'] = $group;
        endif;
        //pre($orderinfo);die;
        $info['orderInfo'] = base64_encode(serialize($orderinfo));
        $this->order->update($info, $orderId);
               
        $orderinfo=json_decode(json_encode(unserialize(base64_decode($orderDetails[0]->orderInfo))), true);
        
        $this->product->update_product_quantity($prod_price_info->productId,$prod_price_info->qty);
        foreach($group->users as $key => $usr):
            $mail_template_data=array();
            $data['senderId'] = $userId;
            $data['receiverId'] = $usr->userId;
            $data['nType'] = 'BUYING-CLUB-ORDER';

            $data['nTitle'] = 'New Buying Club order running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';
            $mail_template_data['TEMPLATE_GROUP_ORDER_START_TITLE']=$group->admin->firstName.' '.$group->admin->lastName;
            $data['nMessage'] = "Hi, <br> You have requested to buy Buying Club[".$group->groupTitle."] order product.<br>";
            $data['nMessage'] .= "Product is <a href=''>".$orderinfo['pdetail']['title']."</a><br>";
            $mail_template_data['TEMPLATE_GROUP_ORDER_START_PRODUCT_TITLE']=$orderinfo['pdetail']['title'];
            $data['nMessage'] .= "Want to process the order ? <br>";
            $data['nMessage'] .= "<a href='".$defaultResources['MainSiteBaseURL']."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".$defaultResources['MainSiteBaseURL']."shopping/group-order-accept-process/".base64_encode($orderId*226201)."' class='btn btn-success btn-lg'>Accept</a><br>";
            $mail_template_data['TEMPLATE_GROUP_ORDER_START_ORDERID']=$orderId;
            $data['nMessage'] .= "Thanks <br> Tidiit Team.";
            $data['orderId'] =$orderId;
            $data['productId'] =$orderinfo['priceinfo']['productId'];
            $data['productPriceId'] =$orderinfo['priceinfo']['productPriceId'];

            $data['isRead'] = 0;
            $data['status'] = 1;
            $data['createDate'] = date('Y-m-d H:i:s');
            $data['appSource'] = $deviceType;

            //Send Email message
            $recv_email = $usr->email;
            $sender_email = $group->admin->email;

            $mail_template_view_data=$defaultResources;
            $mail_template_view_data['group_order_start']=$mail_template_data;
            global_tidiit_mail($recv_email, "New Buying Club Order Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_start');
            //pre($data);die;
            $this->user->notification_add($data);
            
            $push_not_data['receiverId'] = $usr->userId;
            $push_not_data['nType'] = "BUYING-CLUB-ORDER";
            $push_not_data['nTitle'] = 'New Buying Club Order Invitation';
            $push_not_data['appSource'] = $deviceType;
            $push_not_data['orderId'] = $orderId;
            $push_not_data['nMessage'] = 'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'];
            send_push_notification($push_not_data);

            /// sendin SMS to allmember
            $sms_data=array('nMessage'=>'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'],
                'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$usr->userId,
                'senderMobileNumber'=>$group->admin->mobile,'nType'=>'CREATE-'.$data['nType']);
            send_sms_notification($sms_data);
        endforeach;
        $mPesaId=$this->order->add_mpesa(array('latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId,'appSource'=>$deviceType));
        $this->order->add_payment(array('orderId'=>$orderId,'paymentType'=>'mPesa','mPesaId'=>$mPesaId,'orderType'=>'group'));
        $result=array();
        $result['message']='Group invited to group member successfully.Your group order started successfully,';
        success_response_after_post_get($result);
    }
    
    function buying_club_leader_razorpay_payment_post(){
        $orderId=  $this->post('orderId');
        $userId=  $this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index'), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId)[0];
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        $order=$this->order->get_single_order_by_id($orderId);
        $paymentGatewayAmount=0;
        $allOrderArray=array();
        
        $updateOrder=array();
        $paymentGatewayAmount=$order->orderAmount;
        $updateOrder['status'] = 8;
         
        $allOrderArray[]=$orderId;
        $this->order->update($updateOrder,$orderId);
        
        $resdult=array();
        $result['profile_key']="rzp_test_yfWhsaZcULj1KQ";
        $result['currency']='INR';
        $result['amount']=$paymentGatewayAmount*100;
        $result['name']=$user->firstName.' '.$user->lastName;
        $result['email']=$user->email;
        if($user->contactNo!=""):
            $result['contact']=$user->contactNo;
        else:
            $result['contact']=$user->mobile;
        endif;
        $result['description']="Shopping from Tidiit";
        $result['image']="http://tidiit.com/resources/images/logo.png";
        $result['orderIdData']=  base64_encode(serialize($allOrderArray));
        $result['orderType']= 'group';
        $result['finalReturn']= 'no';
        success_response_after_post_get($result);
    }
    
    function accept_buying_club_order_invite_post(){
        $orderId=$this->post('orderId');
        $userId=$this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        $notificationId=$this->post('notificationId');
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken=="" || $orderId==""){
            $this->response(array('error' => 'Please provide user index,order index,latitude,longitude,device id,device token !'), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId)[0];
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index to process the invitation!'), 400); return FALSE;
        }
        $order=$this->order->get_single_order_by_id($orderId);
        if($order->userId==$user->userId){
            $this->response(array('error' => 'Please provide valid user index to accept the invitation!'), 400); return FALSE;
        }
        if(empty($order)){
            $this->response(array('error' => 'Please provide valid order index to accept the invitation!'), 400); return FALSE;
        }
        $data['order'] = $order;
        $productId = $data['order']->productId;
        $productPriceId = $data['order']->productPriceId;
        
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            $this->response(array('error' => 'Please provide valid order index to process the invitation!'), 400); return FALSE;
        endif;
        
        $product = $this->product->details($productId);
        $product = $product[0];
        $prod_price_info = $this->product->get_products_price_details_by_id($productPriceId);
        
        $a = $this->_get_available_order_quantity($orderId);
        $availQty = $prod_price_info->qty - $a[0]->productQty;
        
        if($prod_price_info->qty == $availQty):
            $this->response(array('error' => 'This Buying Club order process already done. There is no available quantity for you. Please contact your Buying Club Leader!'), 400); return FALSE;
        endif;
        
        if(!$this->user->user_exists_on_group($userId,$data['order']->groupId)):
            $this->response(array('error' => 'You can not process this order because you are not member of this Buying Club.'), 400); return FALSE;
        endif;
        $result=array();
        if($data['order']->groupId):
            $orderInfo=json_decode(json_encode(unserialize(base64_decode($order->orderInfo))), true);
            $result['group'] = json_decode(json_encode($this->user->get_group_by_id($data['order']->groupId)), true);
            //$result['group'] = $this->user->get_group_by_id($data['order']->groupId);
            $result['groupId'] = $data['order']->groupId;
        else:
            $result['group'] = false;
            $result['groupId'] = 0;
        endif;
        
        $order_data = array();
        $order_data['orderType'] = 'GROUP';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $productPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        $order_data['parrentOrderID'] = $data['order']->orderId;
        $order_data['groupId'] = $data['order']->groupId;
        $order_data['productQty'] = 0;
        $order_data['userId'] = $userId;
        $order_data['notificationId'] = $notificationId;
        
        //$exists_order = $this->order->is_parent_group_order_available($data['order']->orderId, $user->userId);
        $exists_order=false;
        //pre($exists_order);die;

        $orderinfo = [];
        $orderinfo['pdetail'] = $product;
        $orderinfo['priceinfo'] = $prod_price_info;
        $productImageArr = $this->product->get_products_images($productId);
        $orderinfo['pimage'] = $productImageArr[0];
        $order_data['orderInfo'] = base64_encode(serialize($orderinfo));
        $reorder=FALSE;
        if($reorder):
            if($exists_order && $exists_order->status == 0):
                $this->order->update($order_data,$exists_order->orderId);
                $parentOrderId = $exists_order->orderId;
            else:    
                //$parentOrderId = $this->order->add($order_data);
                $qrCodeFileName=time().'-'.rand(1, 50).'.png';
                $order_data['qrCodeImageFile']=$qrCodeFileName;
                $order_data['latitude']=  $latitude;
                $order_data['longitude']=  $longitude;
                $order_data['appSource']= $deviceType;
                $qrCodeOrderId=$this->order->add($order_data);
                $parentOrderId=$qrCodeOrderId;
                $params=array();
                $params['data']=$qrCodeOrderId;
                $qrCodeFilePath=SITE_RESOURCES_PATH.'qr_code/'.$qrCodeFileName;
                $params['savename']=$qrCodeFilePath;
                $this->tidiitrcode->generate($params);
                @copy($qrCodeFilePath,MAIN_SERVER_RESOURCES_PATH.'qr_code/'.$qrCodeFileName);
                @unlink($qrCodeFilePath);
            endif;
        else:
            if(!$exists_order):
                $qrCodeFileName=time().'-'.rand(1, 50).'.png';
                $order_data['qrCodeImageFile']=$qrCodeFileName;
                $order_data['latitude']=  $latitude;
                $order_data['longitude']=  $longitude;
                $order_data['appSource']= $deviceType;
                $qrCodeOrderId=$this->order->add($order_data);
                $parentOrderId=$qrCodeOrderId;
                $params=array();
                $params['data']=$qrCodeOrderId;
                $qrCodeFilePath=SITE_RESOURCES_PATH.'qr_code/'.$qrCodeFileName;
                $params['savename']=$qrCodeFilePath;
                $this->tidiitrcode->generate($params);
                @copy($qrCodeFilePath,MAIN_SERVER_RESOURCES_PATH.'qr_code/'.$qrCodeFileName);
                @unlink($qrCodeFilePath);
            elseif($exists_order && $exists_order->status == 0):
                $this->order->update($order_data,$exists_order->orderId);
                $parentOrderId = $exists_order->orderId;
            else:
                $this->response(array('error' => 'This Buying Club order process already done. Please try to process for new order!'), 400); return FALSE;
            endif;
        endif;
        $accept_data=array();
        $accept_data['acceptDeclineState'] =2;
        $this->user->notification_edit($accept_data,$notificationId);
        //$this->process_my_parent_group_orders_by_id($orderId,$userId);
        
        $result['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $result['totalQty'] = $prod_price_info->qty;
        $result['orderId'] = $parentOrderId;
        //$result['priceInfo'] = $prod_price_info;
        success_response_after_post_get($result);
    }
    
    function buying_club_re_order_me_post(){
        $orderId=$this->post('orderId');
        $userId=$this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken=="" || $orderId==""){
            $this->response(array('error' => 'Please provide user index,order index,latitude,longitude,device id,device token !'), 400); return FALSE;
        }
        $userArr=$this->user->get_details_by_id($userId);
        //pre($userArr);die;
        if(empty($userArr)){
            $this->response(array('error' => 'Please provide valid user index to process the invitation!'), 400); return FALSE;
        }
        $user=$userArr[0];
        //pre($user);die;
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index to process the invitation!'), 400); return FALSE;
        }
        $order=$this->order->get_single_order_by_id($orderId);
        if(empty($order)){
            $this->response(array('error' => 'Please provide valid order index to accept the invitation!'), 400); return FALSE;
        }
        $data['order'] = $order;
        $productId = $data['order']->productId;
        $productPriceId = $data['order']->productPriceId;
        
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            $this->response(array('error' => 'Please provide valid order index to process the invitation!'), 400); return FALSE;
        endif;
        
        $product = $this->product->details($productId);
        $product = $product[0];
        $prod_price_info = $this->product->get_products_price_details_by_id($productPriceId);
        
        $a = $this->_get_available_order_quantity($orderId);
        $availQty = $prod_price_info->qty - $a[0]->productQty;
        
        if($prod_price_info->qty == $availQty):
            $this->response(array('error' => 'This Buying Club order process already done. There is no available quantity for you. Please contact your Buying Club Leader!'), 400); return FALSE;
        endif;
        
        if(!$this->user->user_exists_on_group($userId,$data['order']->groupId)):
            $this->response(array('error' => 'You can not process this order because you are not member of this Buying Club.'), 400); return FALSE;
        endif;
        $result=array();
        if($data['order']->groupId):
            $orderInfo=json_decode(json_encode(unserialize(base64_decode($order->orderInfo))), true);
            $result['group'] = json_decode(json_encode($this->user->get_group_by_id($data['order']->groupId)), true);
            //$result['group'] = $this->user->get_group_by_id($data['order']->groupId);
            $result['groupId'] = $data['order']->groupId;
        else:
            $result['group'] = false;
            $result['groupId'] = 0;
        endif;
        
        $order_data = array();
        $order_data['orderType'] = 'GROUP';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $productPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        $order_data['parrentOrderID'] = $data['order']->orderId;
        $order_data['groupId'] = $data['order']->groupId;
        $order_data['productQty'] = 0;
        $order_data['userId'] = $userId;
        
        $exists_order = $this->order->is_parent_group_order_available($data['order']->orderId, $user->userId);
        //pre($exists_order);die;

        $orderinfo = [];
        $orderinfo['pdetail'] = $product;
        $orderinfo['priceinfo'] = $prod_price_info;
        $productImageArr = $this->product->get_products_images($productId);
        $orderinfo['pimage'] = $productImageArr[0];
        $order_data['orderInfo'] = base64_encode(serialize($orderinfo));
        /// re-oreder by leader started here
        if($exists_order && $exists_order->status == 0):
            $this->order->update($order_data,$exists_order->orderId);
            $qrCodeOrderId=$exists_order->orderId;
            $parentOrderId = $exists_order->orderId;
            $groupId=$exists_order->groupId;
        else:    
            $groupId=$order_data['groupId'];
            $qrCodeFileName=time().'-'.rand(1, 50).'.png';
            $order_data['qrCodeImageFile']=$qrCodeFileName;
            $order_data['latitude']=  $latitude;
            $order_data['longitude']=  $longitude;
            $order_data['appSource']= $deviceType;
            $qrCodeOrderId=$this->order->add($order_data);
            $parentOrderId=$qrCodeOrderId;
            $params=array();
            $params['data']=$qrCodeOrderId;
            $qrCodeFilePath=SITE_RESOURCES_PATH.'qr_code/'.$qrCodeFileName;
            $params['savename']=$qrCodeFilePath;
            $this->tidiitrcode->generate($params);
            @copy($qrCodeFilePath,MAIN_SERVER_RESOURCES_PATH.'qr_code/'.$qrCodeFileName);
            @unlink($qrCodeFilePath);
        endif;
        //$this->process_my_parent_group_orders_by_id($orderId,$userId);
        $result['orderId']=$qrCodeOrderId;
        $result['groupId']=$groupId;
        $result['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $result['totalQty'] = $prod_price_info->qty;
        $result['orderId'] = $parentOrderId;
        //$result['priceInfo'] = $prod_price_info;
        success_response_after_post_get($result);
    }
    
    function decline_buying_club_order_invite_post(){
        $orderId=$this->post('orderId');
        $productId=$this->post('productId');
        $productPriceId=$this->post('productPriceId');
        $orderId=$this->post('orderId');
        $userId=$this->post('userId');
        $notificationId=$this->post('notificationId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken=="" || $orderId=="" || $productId=="" || $productPriceId==""){
            $this->response(array('error' => 'Please provide user index,order index,latitude,longitude,device id,device token,product index,product price index !'), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId)[0];
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index to process the invitation!'), 400); return FALSE;
        }
        $order=$this->order->get_single_order_by_id($orderId);
        
        if($order->userId==$user->userId){
            $this->response(array('error' => 'Please provide valid user index to accept the invitation!'), 400); return FALSE;
        }
        if(empty($order)){
            $this->response(array('error' => 'Please provide valid order index to accept the invitation!'), 400); return FALSE;
        }
        
        $group = $this->user->get_group_by_id($order->groupId);
        $prod_price_info = $this->product->get_products_price_details_by_id($order->productPriceId);
        $a = $this->_get_available_order_quantity($orderId);
        $availQty = $prod_price_info->qty - $a[0]->productQty;
        $orderInfo = unserialize(base64_decode($order->orderInfo));

        if(!$availQty):
            $this->response(array('error' => 'Order already completed by other members of this Buying Club.'), 400); return FALSE;
        endif;
        
        if(!$this->user->user_exists_on_group($userId,$order->groupId)):
            $this->response(array('error' => 'You can not process this order because you are not member of this Buying Club.'), 400); return FALSE;
        endif;
        
        
        if($order->parrentOrderID == 0):
            $me = $user;
            foreach($group->users as $key => $usr):
                $mail_template_data=array();
                if($me->userId != $usr->userId):
                    $data['senderId'] = $userId;
                    $data['receiverId'] = $usr->userId;
                    $data['nType'] = 'BUYING-CLUB-ORDER-DECLINE';
                    $data['nTitle'] = 'Buying Club order [TIDIIT-OD-'.$order->orderId.'] cancel by <b>'.$me->firstName.' '.$me->lastName.'</b>';
                    $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID']=$order->orderId;
                    $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ADMIN_NAME']=$me->firstName.' '.$me->lastName;
                    $data['nMessage'] = "Hi, <br> Sorry! I can not process this Buying Club order right now.<br>";
                    $data['nMessage'] .= "";
                    $data['nMessage'] .= "Thanks <br> Tidiit Team.";

                    $data['isRead'] = 0;
                    $data['status'] = 1;
                    $data['createDate'] = date('Y-m-d H:i:s');
                    $data['appSource'] = $deviceType;
                    
                    //Send Email message
                    $recv_email = $usr->email;
                    $sender_email = $me->email;
                    $mail_template_view_data=load_default_resources();
                    $mail_template_view_data['group_order_decline']=$mail_template_data;
                    global_tidiit_mail($recv_email, "Buying Club order decline at Tidiit Inc Ltd", $mail_template_view_data,'group_order_decline',$usr->firstName.' '.$usr->lastName);
                    
                    $notificationId=$this->user->notification_add($data);
                    
                    $push_not_data['receiverId'] = $group->admin->userId;
                    $push_not_data['nType'] = "BUYING-CLUB-ORDER-DECLINE";
                    $push_not_data['nTitle'] = 'New Buying Club Order Invitation';
                    $push_not_data['appSource'] = $deviceType;
                    //$push_not_data['orderId'] = $orderId;
                    $push_not_data['nMessage'] = 'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'];
                    send_normal_push_notification($push_not_data);
                endif;
            endforeach;
            $data['receiverId'] = $group->admin->userId;
            
            unset($data['nMessage']);
            $mail_template_view_data=load_default_resources();
            $defaultResources=$mail_template_view_data;
            $mail_template_data=array();
            $data['senderId'] = $userId;
            $data['nType'] = 'BUYING-CLUB-ORDER-DECLINE';
            $data['nTitle'] = 'Buying Club order [TIDIIT-OD-'.$order->orderId.'] cancel by <b>'.$me->firstName.' '.$me->lastName.'</b>';
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID']=$order->orderId;
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ADMIN_NAME']=$me->firstName.' '.$me->lastName;
            $data['nMessage'] = "Hi, <br> Sorry! I can not process this order right now.<br>";
            $data['nMessage'] .= "<a href='".$defaultResources['MainSiteBaseURL']."shopping/group-re-order-process/".base64_encode($orderId*226201)."' class='btn btn-warning btn-lg'>Re-order now</a><br><br>";
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID1']=$orderId;
            $data['nMessage'] .= "Thanks <br> Tidiit Team.";
            $data['isRead'] = 0;
            $data['status'] = 1;
            $data['orderId'] = $orderId;
            $data['productId'] = $productId;
            $data['productPriceId'] = $productPriceId;
            $data['createDate'] = date('Y-m-d H:i:s');
            $data['appSource'] = $deviceType;
            
            //Send Email message
            $recv_email = $group->admin->email;
            $sender_email = $me->email;
            $mail_template_view_data=load_default_resources();
            $mail_template_view_data['group_order_decline']=$mail_template_data;
            global_tidiit_mail($recv_email, "Buying Club order decline at Tidiit Inc Ltd", $mail_template_view_data,'group_order_decline_admin',$group->admin->firstName.' '.$group->admin->lastName);
            $notificationId=$this->user->notification_add($data);
            
            $declient_data=array();
            $declient_data['acceptDeclineState'] =2;
            $this->user->notification_edit($declient_data,$notificationId);
            
            $this->order->update(array('reOrder'=>1,'cancelOrderUserId'=>$userId),$orderId);
            
            /// sendin SMS to Leader
            $smsMsg='Buying Club['.$group->groupTitle.']  member['.$usr->firstName.' '.$usr->lastName.'] has decline the invitation Tidiit order TIDIIT-OD-'.$order->orderId.'.';
            $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$orderInfo['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfo["group"]->admin->userId,
            'senderMobileNumber'=>'','nType'=>$data['nType']);
            send_sms_notification($sms_data);
            
            $push_not_data['receiverId'] = $group->admin->userId;
            $push_not_data['nType'] = "BUYING-CLUB-ORDER-DECLINE";
            $push_not_data['nTitle'] = 'New Buying Club Order Invitation';
            $push_not_data['appSource'] = $deviceType;
            $push_not_data['notificationId'] = $notificationId;
            $push_not_data['nMessage'] = 'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'];
            send_push_notification($push_not_data);
        endif;
        $result= array();
        $result['message']='Sorry for Buying Club order cancelation!';
        success_response_after_post_get($result);
    }
    
    function decline_buying_club_cart_order_post(){
        $orderId=$this->post('orderId');
        $userId=$this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken=="" || $orderId==""){
            $this->response(array('error' => 'Please provide user index,order index,latitude,longitude,device id,device token,product index,product price index !'), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId)[0];
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index to process the invitation!'), 400); return FALSE;
        }
        $order=$this->order->get_single_order_by_id($orderId);
        $notificationId=$order->notificationId;
        $productId=$order->productId;
        $productPriceId=$order->productPriceId;
        
        if($order->userId==$user->userId){
            $this->response(array('error' => 'Please provide valid user index to accept the invitation!'), 400); return FALSE;
        }
        if(empty($order)){
            $this->response(array('error' => 'Please provide valid order index to accept the invitation!'), 400); return FALSE;
        }
        
        $group = $this->user->get_group_by_id($order->groupId);
        $prod_price_info = $this->product->get_products_price_details_by_id($order->productPriceId);
        $a = $this->_get_available_order_quantity($orderId);
        $availQty = $prod_price_info->qty - $a[0]->productQty;
        $orderInfo = unserialize(base64_decode($order->orderInfo));

        if(!$availQty):
            $this->response(array('error' => 'Order already completed by other members of this Buying Club.'), 400); return FALSE;
        endif;
        
        if(!$this->user->user_exists_on_group($userId,$order->groupId)):
            $this->response(array('error' => 'You can not process this order because you are not member of this Buying Club.'), 400); return FALSE;
        endif;
        
        
        if($order->parrentOrderID == 0):
            $me = $user;
            foreach($group->users as $key => $usr):
                $mail_template_data=array();
                if($me->userId != $usr->userId):
                    $data['senderId'] = $userId;
                    $data['receiverId'] = $usr->userId;
                    $data['nType'] = 'BUYING-CLUB-ORDER-DECLINE';
                    $data['nTitle'] = 'Buying Club order [TIDIIT-OD-'.$order->orderId.'] cancel by <b>'.$me->firstName.' '.$me->lastName.'</b>';
                    $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID']=$order->orderId;
                    $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ADMIN_NAME']=$me->firstName.' '.$me->lastName;
                    $data['nMessage'] = "Hi, <br> Sorry! I can not process this Buying Club order right now.<br>";
                    $data['nMessage'] .= "";
                    $data['nMessage'] .= "Thanks <br> Tidiit Team.";

                    $data['isRead'] = 0;
                    $data['status'] = 1;
                    $data['createDate'] = date('Y-m-d H:i:s');
                    $data['appSource'] = $deviceType;
                    
                    //Send Email message
                    $recv_email = $usr->email;
                    $sender_email = $me->email;
                    $mail_template_view_data=load_default_resources();
                    $mail_template_view_data['group_order_decline']=$mail_template_data;
                    global_tidiit_mail($recv_email, "Buying Club order decline at Tidiit Inc Ltd", $mail_template_view_data,'group_order_decline',$usr->firstName.' '.$usr->lastName);
                    
                    $this->user->notification_add($data);
                endif;
            endforeach;
            $data['receiverId'] = $group->admin->userId;
            
            unset($data['nMessage']);
            $mail_template_view_data=load_default_resources();
            $defaultResources=$mail_template_view_data;
            $mail_template_data=array();
            $data['senderId'] = $userId;
            $data['nType'] = 'BUYING-CLUB-ORDER-DECLINE';
            $data['nTitle'] = 'Buying Club order [TIDIIT-OD-'.$order->orderId.'] cancel by <b>'.$me->firstName.' '.$me->lastName.'</b>';
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID']=$order->orderId;
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ADMIN_NAME']=$me->firstName.' '.$me->lastName;
            $data['nMessage'] = "Hi, <br> Sorry! I can not process this order right now.<br>";
            $data['nMessage'] .= "<a href='".$defaultResources['MainSiteBaseURL']."shopping/group-re-order-process/".base64_encode($orderId*226201)."' class='btn btn-warning btn-lg'>Re-order now</a><br><br>";
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID1']=$orderId;
            $data['nMessage'] .= "Thanks <br> Tidiit Team.";
            $data['isRead'] = 0;
            $data['status'] = 1;
            $data['orderId'] = $orderId;
            $data['productId'] = $productId;
            $data['productPriceId'] = $productPriceId;
            $data['createDate'] = date('Y-m-d H:i:s');
            $data['appSource'] = $deviceType;
            
            //Send Email message
            $recv_email = $group->admin->email;
            $sender_email = $me->email;
            $mail_template_view_data=load_default_resources();
            $mail_template_view_data['group_order_decline']=$mail_template_data;
            global_tidiit_mail($recv_email, "Buying Club order decline at Tidiit Inc Ltd", $mail_template_view_data,'group_order_decline_admin',$group->admin->firstName.' '.$group->admin->lastName);
            $notificationId=$this->user->notification_add($data);
            
            $declient_data=array();
            $declient_data['acceptDeclineState'] =2;
            $this->user->notification_edit($declient_data,$notificationId);
            
            $this->order->update(array('reOrder'=>1,'cancelOrderUserId'=>$userId),$orderId);
            $push_not_data['receiverId'] = $group->admin->userId;
            $push_not_data['nType'] = "BUYING-CLUB-ORDER-DECLINE";
            $push_not_data['nTitle'] = 'New Buying Club Order Invitation';
            $push_not_data['appSource'] = $deviceType;
            $push_not_data['notificationId'] = $notificationId;
            $push_not_data['nMessage'] = 'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'];

            /// sendin SMS to Leader
            $smsMsg='Buying Club['.$group->groupTitle.']  member['.$usr->firstName.' '.$usr->lastName.'] has decline the invitation Tidiit order TIDIIT-OD-'.$order->orderId.'.';
            $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$orderInfo['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfo["group"]->admin->userId,
            'senderMobileNumber'=>'','nType'=>$data['nType']);
            send_sms_notification($sms_data);
        endif;
        $result= array();
        $result['message']='Sorry for Buying Club order cancelation!';
        success_response_after_post_get($result);
    }
    
    function process_my_parent_group_orders_by_id($orderId,$userId){
        if(!$orderId):
            $this->response(array('error' => 'Invalid order index.'), 400); return FALSE;
        endif;
        $user = $this->user->get_details_by_id($userId)[0];
        
        $data['order'] = $this->order->get_single_order_by_id($orderId);
        
        $productId = $data['order']->productId;
        $productPriceId = $data['order']->productPriceId;
        
        if(!$this->user->user_exists_on_group($userId,$data['order']->groupId)):
            $this->response(array('error' => 'You can not process this order because you are not member of this Buying Club.'), 400); return FALSE;
        endif;
        
        $product = $this->product->details($productId);
        $product = $product[0];
        $prod_price_info = $this->product->get_products_price_details_by_id($productPriceId);
        $is_cart_update = false;
        $cart = $this->order->tidiit_get_user_orders($user->userId, 0);
        if($cart):
            foreach ($cart as $item):
                if(($item->productId == $productId) && ( $item->orderType== "GROUP")):
                    $data['orderId'] = $item->orderId;
                    $is_cart_update = true;
                endif;
            endforeach;
        endif;
        
        //Order first step
        $order_data = array();
        $order_data['orderType'] = 'GROUP';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $productPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;

        $orderinfo = [];
        $orderinfo['pdetail'] = $product;
        $orderinfo['priceinfo'] = $prod_price_info;
        $productImageArr = $this->product->get_products_images($productId);
        $orderinfo['pimage'] = $productImageArr[0];
        $order_data['orderInfo'] = base64_encode(serialize($orderinfo));

        if(!isset($data['orderId'])):
            $order_data['productQty'] = 0;
            //$data['orderId']=$this->order->add($order_data);
            $qrCodeFileName=time().'-'.rand(1, 50).'.png';
            $order_data['qrCodeImageFile']=$qrCodeFileName;
            $order_data['IP']=  $this->input->ip_address();
            $orderId=$this->order->add($order_data);
            $data['orderId']=$orderId;
            $params=array();
            $params['data']=$orderId;
            $params['savename']=$this->config->item('ResourcesPath').'qr_code/'.$qrCodeFileName;
            $this->tidiitrcode->generate($params);
        endif;
        
        //$data['orderId'] = 0;
        $order_data['orderId'] = $data['orderId'];
        $order_data['productPriceId'] = $productPriceId;
        $data['order'] = $this->order->get_single_order_by_id($data['orderId']);
        //==============================================//
        if($is_cart_update):
            if(isset($data['orderId'])):
                $order_update['orderInfo'] = base64_encode(serialize($orderinfo));
                $order_update['orderAmount'] = $prod_price_info->price;
                $order_update['subTotalAmount'] = $prod_price_info->price;
                unset($order_update['orderId']);
                unset($order_update['orderDate']);
                $order_update['orderUpdatedate'] = date('Y-m-d H:i:s');
                $order_update['productQty'] = 0;
                //print_r($order_update);
                $this->order->update($order_update,$data['orderId']);
            endif;
        endif;
        
        //==============================================//

        $a = $this->_get_available_order_quantity($data['order']->parrentOrderID);
        $data['availQty'] = $prod_price_info->qty - $a[0]->productQty;
        
        //=============================================//
        if($data['order']->groupId):
            $data['group'] = $this->user->get_group_by_id($data['order']->groupId);
            $data['groupId'] = $data['order']->groupId;
        else:
            $data['group'] = false;
            $data['groupId'] = 0;
        endif;
        
        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        $data['userMenuActive']=7;        
        $data['user']=$user;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('group_order/group_order_parent',$data);
    }
    
    function buying_club_member_sod_payment_post(){
        $orderId=  $this->post('orderId');
        $userId=  $this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken=="" || $orderId==""){
            $this->response(array('error' => 'Please provide user index,order index,latitude,longitude,device id,device token !'), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        
        $orderDetails= $this->order->details($orderId);
        $orderinfo=json_decode(json_encode(unserialize(base64_decode($orderDetails[0]->orderInfo))), true);
                
        //pre($allIncompleteOrders);die;
        $defaultResources=load_default_resources();
        $user=$this->user->get_details_by_id($userId)[0];
        $pevorder = $this->order->get_single_order_by_id($orderId);
        $a = $this->_get_available_order_quantity($orderId);
        $prod_price_info = $this->product->get_products_price_details_by_id($pevorder->productPriceId);
        //pre($prod_price_info);pre($a);die;
        if($prod_price_info->qty == $a[0]->productQty):
            $order_update=array('status'=>2);
            $order_update['orderDate'] = date('Y-m-d H:i:s');
        else:
            $order_update=array('status'=>1);
            $order_update['orderDate'] = date('Y-m-d H:i:s');
        endif;
        
        $update = $this->order->update($order_update,$orderId);
        $this->order->order_group_status_update($orderId,$order_update['status'],$pevorder->parrentOrderID);
        $order = $this->order->get_single_order_by_id($orderId);
        $group = $this->user->get_group_by_id($order->groupId);
        
        $orderinfo = array();
        $pro = $this->product->details($order->productId);
        $orderinfo['pdetail'] = $pro[0];
        $orderinfo['priceinfo'] = $this->product->get_products_price_details_by_id($order->productPriceId);
        $productImageArr =$this->product->get_products_images($order->productId);
        $orderinfo['pimage'] = $productImageArr[0];            

        $userShippingDataDetails = $this->user->get_user_shipping_information($userId);
        $orderinfo['shipping'] = $userShippingDataDetails[0];
        $group = $this->user->get_group_by_id($order->groupId);    
        if($order->groupId):
            $orderinfo['group'] = $group;
        endif;
        //pre($orderinfo);die;
        $info['orderInfo'] = base64_encode(serialize($orderinfo));
        $this->order->update($info, $orderId);
        $allOrderArray=array();
        $paymentGatewayAmount=$order->orderAmount;
        $me = $this->user->get_details_by_id($userId)[0];
        
        $settlementOnDeliveryId=$this->order->add_sod(array('latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId,'appSource'=>$deviceType));
        $this->order->add_payment(array('orderId'=>$orderId,'paymentType'=>'settlementOnDelivery','settlementOnDeliveryId'=>$settlementOnDeliveryId,'orderType'=>'group'));
        
        $mail_template_data=array();
        foreach($group->users as $key => $usr):
            if($me->userId != $usr->userId):
                $mail_template_data=array();
                $data['senderId'] = $userId;
                $data['receiverId'] = $usr->userId;
                $data['nType'] = 'BUYING-CLUB-ORDER-CONTINUE';

                $data['nTitle'] = 'Buying Club['.$group->groupTitle.'] order continue by <b>'.$me->firstName.' '.$me->lastName.'</b>';
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$usr->firstName.' '.$usr->lastName;
                $data['nMessage'] = "Hi, <br> I have paid Rs. ".$order->orderAmount." /- for the quantity ".$order->productQty." of this Buying Club[".$group->groupTitle."].<br>";
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
                $data['nMessage'] .= "Order item is ".$orderinfo['pdetail']->title."<br /><br />";
                $data['nMessage'] .= "Thanks <br> Tidiit Team.";

                $data['isRead'] = 0;
                $data['status'] = 1;
                $data['createDate'] = date('Y-m-d H:i:s');
                $data['appSource'] = $deviceType;
                //Send Email message
                $recv_email = $usr->email;
                $sender_email = $me->email;

                $mail_template_view_data=load_default_resources();
                $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                $this->user->notification_add($data);
                
                $userFirstName=$usr->firstName;
                $userlastName=$usr->lastName;
                $orderAmount=$order->orderAmount;
                $orderQuantity=$order->productQty;
                $groupTitle=$group->groupTitle;
                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$userFirstName.' '.$userlastName.' has completed payment['.$orderAmount.'] of '.$orderQuantity.' of Buying Club['.$groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'],
                'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$data['receiverId'],
                'senderMobileNumber'=>$group->admin->mobile,'nType'=>$data['nType']);
                send_sms_notification($sms_data);
            endif;
        endforeach;
        $data['senderId'] = $userId;
        $data['receiverId'] = $group->admin->userId;
        $data['nType'] = 'BUYING-CLUB-ORDER-CONTINUE';
        $data['nTitle'] = 'Your Buying Club['.$group->groupTitle.'] order continue by <b>'.$me->firstName.' '.$me->lastName.'</b>';
        $data['nMessage'] = "Hi, <br> I have paid Rs. ".$order->orderAmount." /- for the quantity ".$order->productQty." of this Buying Club['.$group->groupTitle.'].<br>";
        $data['nMessage'] .= 'Order item is '.$orderinfo['pdetail']->title."<br /><br/>";
        $data['nMessage'] .= "Thanks <br> Tidiit Team.";
        $data['isRead'] = 0;
        $data['status'] = 1;
        $data['createDate'] = date('Y-m-d H:i:s');
        $data['appSource'] = $deviceType;
        
        //Send Email message
        $recv_email = $group->admin->email;
        $sender_email = $me->email;
        $mail_template_view_data=load_default_resources();
        if(empty($mail_template_data)){
            $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$me->firstName.' '.$me->lastName;
            $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
            $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
        }
        $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
        global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');

        $this->user->notification_add($data);

        $sms_data=array('nMessage'=>$me->firstName.' '.$me->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of your Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'],
                'receiverMobileNumber'=>$group->admin->mobile,'senderId'=>'','receiverId'=>$data['receiverId'],
                'senderMobileNumber'=>$me->mobile,'nType'=>"BUYING-CLUB-ORDER-INVITED-MEMBER-COMPLETE");
                send_sms_notification($sms_data);
        $result=array();        
        if($order_update['status'] == 2):
            $this->sent_buying_club_order_complete_mail($order);
            $result['message']='Your payment has received successfully,We are processing your order shortly and By separate notification we will update about your order. ';
        else:
            $result['message']='Your payment has received successfully';
        endif;        
        success_response_after_post_get($result);
    }
    
    function buying_club_member_mpesa_payment_post(){
        $orderId=  $this->post('orderId');
        $userId=  $this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId=="" || $latitude =="" || $longitude =="" || $deviceType=="" || $UDID ==""  || $deviceToken=="" || $orderId==""){
            $this->response(array('error' => 'Please provide user index,order index,latitude,longitude,device id,device token !'), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        
        $orderDetails= $this->order->details($orderId);
        $orderinfo=json_decode(json_encode(unserialize(base64_decode($orderDetails[0]->orderInfo))), true);
                
        $defaultResources=load_default_resources();
        $user=$this->user->get_details_by_id($userId)[0];
        $pevorder = $this->order->get_single_order_by_id($orderId);
        $a = $this->_get_available_order_quantity($orderId);
        $prod_price_info = $this->product->get_products_price_details_by_id($pevorder->productPriceId);
        if($prod_price_info->qty == $a[0]->productQty):
            $order_update=array('status'=>2,'isPaid'=>1);
            $order_update['orderDate'] = date('Y-m-d H:i:s');
        else:
            $order_update=array('status'=>1,'isPaid'=>1);
            $order_update['orderDate'] = date('Y-m-d H:i:s');
        endif;
        
        $update = $this->order->update($order_update,$orderId);
        $this->order->order_group_status_update($orderId,$order_update['status'],$pevorder->parrentOrderID);
        $order = $this->order->get_single_order_by_id($orderId);
        $group = $this->user->get_group_by_id($order->groupId);
        
        $orderinfo = array();
        $pro = $this->product->details($order->productId);
        $orderinfo['pdetail'] = $pro[0];
        $orderinfo['priceinfo'] = $this->product->get_products_price_details_by_id($order->productPriceId);
        $productImageArr =$this->product->get_products_images($order->productId);
        $orderinfo['pimage'] = $productImageArr[0];            

        $userShippingDataDetails = $this->user->get_user_shipping_information($userId);
        $orderinfo['shipping'] = $userShippingDataDetails[0];
        $group = $this->user->get_group_by_id($order->groupId);    
        if($order->groupId):
            $orderinfo['group'] = $group;
        endif;
        //pre($orderinfo);die;
        $info['orderInfo'] = base64_encode(serialize($orderinfo));
        $this->order->update($info, $orderId);
        $allOrderArray=array();
        $paymentGatewayAmount=$order->orderAmount;
        $me = $this->user->get_details_by_id($userId)[0];
        
        $mPesaId=$this->order->add_mpesa(array('latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId,'appSource'=>$deviceType));
        $this->order->add_payment(array('orderId'=>$orderId,'paymentType'=>'mPesa','mPesaId'=>$mPesaId,'orderType'=>'group'));
        
        $mail_template_data=array();
        foreach($group->users as $key => $usr):
            if($me->userId != $usr->userId):
                $mail_template_data=array();
                $data['senderId'] = $userId;
                $data['receiverId'] = $usr->userId;
                $data['nType'] = 'BUYING-CLUB-ORDER-CONTINUE';

                $data['nTitle'] = 'Buying Club['.$group->groupTitle.'] order continue by <b>'.$me->firstName.' '.$me->lastName.'</b>';
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$usr->firstName.' '.$usr->lastName;
                $data['nMessage'] = "Hi, <br> I have paid Rs. ".$order->orderAmount." /- for the quantity ".$order->productQty." of this Buying Club[".$group->groupTitle."].<br>";
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
                $data['nMessage'] .= "Order item is ".$orderinfo['pdetail']->title."<br /><br />";
                $data['nMessage'] .= "Thanks <br> Tidiit Team.";
                $data['appSource'] = $deviceType;
                $data['isRead'] = 0;
                $data['status'] = 1;
                $data['createDate'] = date('Y-m-d H:i:s');

                //Send Email message
                $recv_email = $usr->email;
                $sender_email = $me->email;

                $mail_template_view_data=load_default_resources();
                $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                $this->user->notification_add($data);

                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$usr->firstName.' '.$usr->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'],
                'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$data['receiverId'],
                'senderMobileNumber'=>$group->admin->mobile,'nType'=>$data['nType']);
                send_sms_notification($sms_data);
            endif;
        endforeach;
        $data['senderId'] = $userId;
        $data['receiverId'] = $group->admin->userId;
        $data['nType'] = 'BUYING-CLUB-ORDER-CONTINUE';
        $data['nTitle'] = 'Your Buying Club['.$group->groupTitle.'] order continue by <b>'.$me->firstName.' '.$me->lastName.'</b>';
        $data['nMessage'] = "Hi, <br> I have paid Rs. ".$order->orderAmount." /- for the quantity ".$order->productQty." of this Buying Club['.$group->groupTitle.'].<br>";
        $data['nMessage'] .= 'Order item is '.$orderinfo['pdetail']->title."<br /><br/>";
        $data['nMessage'] .= "Thanks <br> Tidiit Team.";
        $data['isRead'] = 0;
        $data['status'] = 1;
        $data['createDate'] = date('Y-m-d H:i:s');
        $data['appSource'] = $deviceType;
        //Send Email message
        $recv_email = $group->admin->email;
        $sender_email = $me->email;
        $mail_template_view_data=load_default_resources();
        if(empty($mail_template_data)){
            $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$me->firstName.' '.$me->lastName;
            $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
            $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
        }
        $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
        global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');

        $this->user->notification_add($data);

        $sms_data=array('nMessage'=>$me->firstName.' '.$me->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of your Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'],
                'receiverMobileNumber'=>$group->admin->mobile,'senderId'=>'','receiverId'=>$data['receiverId'],
                'senderMobileNumber'=>$me->mobile,'nType'=>"BUYING-CLUB-ORDER-INVITED-MEMBER-COMPLETE");
                send_sms_notification($sms_data);
        if($order_update['status'] == 2):
            $this->sent_buying_club_order_complete_mail($order);
        endif;
        $result=array();
        $result['message']='Group invited to group member successfully.Your group order started successfully,';
        success_response_after_post_get($result);
    }
    
    function buying_club_member_razorpay_payment_post(){
        $orderId=  $this->post('orderId');
        $userId=  $this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index'), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId)[0];
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        $order=$this->order->get_single_order_by_id($orderId);
        $paymentGatewayAmount=0;
        $allOrderArray=array();
        $orderUpdate=array();
        $paymentGatewayAmount=$order->orderAmount;
        $orderUpdate['status'] = 8;
         
        $allOrderArray[]=$orderId;
        $this->order->update($orderUpdate,$orderId);
        
        $result=array();
        $result['profile_key']="rzp_test_yfWhsaZcULj1KQ";
        $result['currency']='INR';
        $result['amount']=$paymentGatewayAmount*100;
        $result['name']=$user->firstName.' '.$user->lastName;
        $result['email']=$user->email;
        if($user->contactNo!=""):
            $result['contact']=$user->contactNo;
        else:
            $result['contact']=$user->mobile;
        endif;
        $result['description']="Shopping from Tidiit";
        $result['image']="http://tidiit.com/resources/images/logo.png";
        $result['orderIdData']=  base64_encode(serialize($allOrderArray));
        $result['orderType']= 'group';
        $result['finalReturn']= 'no';
        success_response_after_post_get($result);
    }
    
    function my_order_razorpay_payment_post(){
        $orderId=  $this->post('orderId');
        $userId=  $this->post('userId');
        
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId==""){
            $this->response(array('error' => 'Please provide user index'), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId)[0];
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId,2)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        $order=$this->order->get_single_order_by_id($orderId);
        $paymentGatewayAmount=0;
        $allOrderArray=array();
        
        $paymentGatewayAmount=$order->orderAmount;
         
        $allOrderArray[]=$orderId;
        
        $result=array();
        $result['profile_key']="rzp_test_yfWhsaZcULj1KQ";
        $result['currency']='INR';
        $result['amount']=$paymentGatewayAmount*100;
        $result['name']=$user->firstName.' '.$user->lastName;
        $result['email']=$user->email;
        if($user->contactNo!=""):
            $result['contact']=$user->contactNo;
        else:
            $result['contact']=$user->mobile;
        endif;
        $result['description']="Shopping from Tidiit";
        $result['image']="http://tidiit.com/resources/images/logo.png";
        $result['orderIdData']=  base64_encode(serialize($allOrderArray));
        $result['orderType']= strtolower($order->orderType);
        $result['finalReturn']= 'yes';
        success_response_after_post_get($result);
    }
    
    function buying_club_cart_process_post(){
        $userId=  $this->post('userId');
        $orderId=  $this->post('orderId');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=  $this->post('deviceType');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        if($userId=="" || $orderId=="" || $latitude=="" || $longitude=="" || $deviceType=="" || $deviceToken=="" || $UDID==""){
            $this->response(array('error' => 'Please provide user index,order index,latitude,longitude,device id,device token and udid !'), 400); return FALSE;
        }
        
        if($this->order->is_valid_order_by_order_id_user_id($orderId,$userId)==FALSE){
            $this->response(array('error' => 'Please provide valid user index and related order index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        
        $orderDetails= $this->order->details($orderId);
        if($orderDetails[0]->groupId==0){
            /// need to show group selection screen
            $data=array();
            $data['screen_name']="show_buying_club_selection";
            success_response_after_post_get($data);
        }else if($orderDetails[0]->productQty==0){
            $groupId = $orderDetails[0]->groupId;
            $data=array();
            $data['groupId'] = $groupId;
            $data['orderId'] = $orderId;
            $data['screen_name']="show_quantity_entry";
            success_response_after_post_get($data);
        }else{
            $data=array();
            $data['screen_name']="show_shipping_address";
            success_response_after_post_get($data);
        }
    }
    
    function group_order_final_mail_check_post(){
        $orderId=$this->post('orderId');
        $order = $this->order->get_single_order_by_id($orderId);
        //pre($order);die;
        $this->sent_buying_club_order_complete_mail($order);
    }
    
    function sent_buying_club_order_complete_mail_check_get(){
        $ordeerId=$this->get('orderId');
        $order=$this->order->get_single_order_by_id($ordeerId);
        $this->sent_buying_club_order_complete_mail($order);
    }
    
    /*
     * 
     * send mail to Buying Club member and leader after success payment by all member as well leader
     */
    function sent_buying_club_order_complete_mail($order){
        if($order->parrentOrderID>0){
            /// mail to leader and seller and support
            $orderDetails=  $this->order->details($order->parrentOrderID);
            $adminMailData=  load_default_resources();
            $adminMailData['orderDetails']=$orderDetails;
            $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
            $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
            $adminMailData['orderParrentId']=$order->parrentOrderID;
            $adminMailData['userFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            global_tidiit_mail($orderInfoDataArr['group']->admin->email, "Your  Buying Club order - TIDIIT-OD-".$order->parrentOrderID.' has placed successfully.', $adminMailData,'group_order_success',$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName);
            
            $sms_data=array('nMessage'=>'Your Tidiit Buying Club['.$orderInfoDataArr['group']->groupTitle.'] order TIDIIT-OD-'.$orderDetails[0]->orderId.' for '.$orderInfoDataArr['pdetail']->title.' has placed successfully. More details about this notifiaction,Check '.$adminMailData['MainSiteBaseURL'],
            'receiverMobileNumber'=>$orderInfoDataArr['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfoDataArr['group']->admin->userId,
            'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-PLACED');
            send_sms_notification($sms_data);
            
            /// for seller
            $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
            $adminMailData['buyerFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            global_tidiit_mail($orderDetails[0]->sellerEmail, "Buying Club order no - TIDIIT-OD-".$order->parrentOrderID.' has placed from Tidiit Inc Ltd', $adminMailData,'seller_group_order_success',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);
            
            /// for support
            $adminMailData['userFullName']='Tidiit Inc Support';
            $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
            $adminMailData['buyerFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            $this->load->model('Siteconfig_model','siteconfig');
            //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
            $supportEmail='judhisahoo@gmail.com';
            global_tidiit_mail($supportEmail, "Buying Club order no - TIDIIT-OD-".$order->parrentOrderID.' has placed from Tidiit Inc Ltd', $adminMailData,'support_group_order_success','Tidiit Inc Support');
            ///mail to Buyer CLub
            $this->order->update(array('status'=>2),$order->parrentOrderID);
            $allChieldOrdersData=$this->order->get_all_chield_order($order->parrentOrderID);
            foreach($allChieldOrdersData AS $k){
                $this->order->update(array('status'=>2),$k->orderId);
                $orderDetails=  $this->order->details($k->orderId);
                $adminMailData=array();
                $adminMailData=  load_default_resources();
                $adminMailData['orderDetails']=$orderDetails;
                $orderInfoDataArr=unserialize(base64_decode($k->orderInfo));
                $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
                $adminMailData['orderParrentId']=$k->parrentOrderID;
                foreach($orderInfoDataArr['group']->users AS $kk){
                    $email='';$userFullName='';
                    if($kk->userId==$orderDetails[0]->userId){
                        $email=$kk->email;
                        $userFullName=$kk->firstName.' '.$kk->lastName;
                        $mobileNumber=$kk->mobile;
                        break;
                    }
                }
                $adminMailData['userFullName']=$userFullName;
                global_tidiit_mail($email, "Your Buying Club Tidiit order TIDIIT-OD-".$k->orderId.' has placed successfully', $adminMailData,'group_order_success',$userFullName);
                
                $sms_data=array('nMessage'=>'Your Tidiit Buying Club['.$orderInfoDataArr['group']->groupTitle.'] order TIDIIT-OD-'.$k->orderId.' for '.$orderInfoDataArr['pdetail']->title.' has placed successfully. More details about this notifiaction,Check '.$adminMailData['MainSiteBaseURL'],
                'receiverMobileNumber'=>$mobileNumber,'senderId'=>'','receiverId'=>$orderDetails[0]->userId,
                'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-PLACED');
                send_sms_notification($sms_data);
                
                /// for seller
                $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
                $adminMailData['buyerFullName']=$userFullName;
                global_tidiit_mail($orderDetails[0]->sellerEmail, "Buying Club order no - TIDIIT-OD-".$k->orderId.' has placed from Tidiit Inc Ltd', $adminMailData,'seller_group_order_success',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);
                
                /// for support
                $adminMailData['userFullName']='Tidiit Inc Support';
                $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
                $adminMailData['buyerFullName']=$userFullName;
                $this->load->model('Siteconfig_model','siteconfig');
                $supportEmail='judhisahoo@gmail.com';
                global_tidiit_mail($supportEmail, "Buying Club order no - TIDIIT-OD-".$k->orderId.' has placed from Tidiit Inc Ltd', $adminMailData,'support_group_order_success','Tidiit Inc Support');
            }
        }
        return TRUE;
    }
    
    function sent_single_order_complete_mail($orderId){
        $orderDetails=  $this->order->details($orderId);
        $adminMailData= load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
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
        $supportEmail='judhisahoo@gmail.com';
        global_tidiit_mail($supportEmail, "Order no - TIDIIT-OD-".$orderId.' has placed by '.$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName, $adminMailData,'support_single_order_success','Tidiit Inc Support');
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
        $adminMailData=  load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
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
    
    function process_razorpay_success_group_order($orderId,$razorpayPaymentId){
        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'comming to process_razorpay_success_group_order fun.'));
        $defaultResourcesData=  load_default_resources();
        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'commint to privious order details'));
        $pevorder = $this->order->get_single_order_by_id($orderId);
        $userId=$pevorder->userId;
        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'commi to product price details.'));
        $prod_price_info = $this->product->get_products_price_details_by_id($pevorder->productPriceId);
        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'getting avialble quantity'));
        $aProductQty= $this->_get_available_order_quantity($orderId);
        $order_update=array();
        if($prod_price_info->qty == $aProductQty):
            $order_update['status'] = 2;
            $order_update['isPaid'] = 1;
            $this->product->update_product_quantity($prod_price_info->productId,$prod_price_info->qty);
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'update quantity and is paid status and complete group order status of the order'));
        else:
            $order_update['status'] = 1;
            $order_update['isPaid'] = 1;
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'update quantity and is paid status and Leader group order status of the order'));
        endif;
        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'update order data'));
        $update = $this->order->update($order_update,$orderId);

        if($update):
            $this->order->order_group_status_update($orderId, $order_update['status'],$pevorder->parrentOrderID);

            //Notification
            $order =$pevorder;
            $orderinfo =  unserialize(base64_decode($order->orderInfo));
            $group =$this->user->get_group_by_id($order->groupId);;
            $orderinfo['group'] = $group;
            
            //$info['orderInfo'] = base64_encode(serialize($orderinfo));
            //$this->order->update($info, $orderId);
            if($order->parrentOrderID == 0):
                //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'sending message for LEADER order'));
                foreach($group->users as $key => $usr):
                    $mail_template_data=array();
                    $data['senderId'] = $userId;
                    $data['receiverId'] = $usr->userId;
                    $data['nType'] = 'BUYING-CLUB-ORDER';

                    $data['nTitle'] = 'New Buying Club order running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_TITLE']=$group->admin->firstName.' '.$group->admin->lastName;
                    $data['nMessage'] = "Hi, <br> You have requested to buy Buying Club order product.<br>";
                    $data['nMessage'] .= "Product is <a href=''>".$orderinfo['pdetail']->title."</a><br>";
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_PRODUCT_TITLE']=$orderinfo['pdetail']->title;
                    $data['nMessage'] .= "Want to process the order ? <br>";
                    $data['nMessage'] .= "<a href='".$defaultResourcesData["MainSiteBaseURL"]."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".$defaultResourcesData['MainSiteBaseURL']."shopping/group-order-accept-process/".base64_encode($orderId*226201)."' class='btn btn-success btn-lg'>Accept</a><br>";
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_ORDERID']=$orderId;
                    $data['nMessage'] .= "Thanks <br> Tidiit Team.";
                    $data['orderId'] =$orderId;
                    $data['productId'] =$orderinfo['priceinfo']->productId;
                    $data['productPriceId'] =$orderinfo['priceinfo']->productPriceId;

                    $data['isRead'] = 0;
                    $data['status'] = 1;
                    $data['createDate'] = date('Y-m-d H:i:s');

                    //Send Email message
                    $recv_email = $usr->email;
                    $sender_email = $group->admin->email;

                    $mail_template_view_data=load_default_resources();
                    $mail_template_view_data['group_order_start']=$mail_template_data;
                    global_tidiit_mail($recv_email, "New Buying Club Order Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_start');
                    $this->user->notification_add($data);
                    //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'sending mail and message to group member'));
                    /// sendin SMS to allmember
                    $sms_data=array('nMessage'=>'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.$defaultResourcesData["MainSiteBaseURL"],
                        'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$usr->userId,
                        'senderMobileNumber'=>$group->admin->mobile,'nType'=>'CREATE-'.$data['nType']);
                    send_sms_notification($sms_data);
                endforeach;
            else:
                //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'sending message for member order'));
                $rsUser=$this->user->get_details_by_id($pevorder->userId);
                $me = $rsUser[0];
                $mail_template_data=array();
                $data['senderId'] = $userId;
                $data['nType'] = 'BUYING-CLUB-ORDER';
                foreach($group->users as $key => $usr):
                    //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'sending message to all member except me about the order.'));
                    if($me->userId != $usr->userId):
                        $mail_template_data=array();
                        $data['receiverId'] = $usr->userId;
                        
                        $data['nTitle'] = 'Buying Club order continue by <b>'.$usr->firstName.' '.$usr->lastName.'</b>';
                        $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$usr->firstName.' '.$usr->lastName;
                        $data['nMessage'] = "Hi, <br> I have paid Rs. ".$order->orderAmount." /- for the quantity ".$order->productQty." of this Buying Club.<br>";
                        $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                        $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
                        $data['nMessage'] .= "";
                        $data['nMessage'] .= "Thanks <br> Tidiit Team.";
                        $data['orderId'] =$orderId;
                        $data['productId'] =$orderinfo['priceinfo']->productId;
                        $data['productPriceId'] =$orderinfo['priceinfo']->productPriceId;

                        $data['isRead'] = 0;
                        $data['status'] = 1;
                        $data['createDate'] = date('Y-m-d H:i:s');

                        //Send Email message
                        $recv_email = $usr->email;
                        $sender_email = $me->email;

                        $mail_template_view_data=load_default_resources();
                        $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                        global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                        $this->user->notification_add($data);
                        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'sending mail and message to group member except me'));
                        /// sendin SMS to allmember
                        $sms_data=array('nMessage'=>$usr->firstName.' '.$usr->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.$defaultResourcesData["MainSiteBaseURL"],
                            'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$data['receiverId'],
                            'senderMobileNumber'=>$group->admin->mobile,'nType'=>$data['nType']);
                        send_sms_notification($sms_data);
                    endif;
                endforeach;
                $data['receiverId'] = $group->admin->userId;
                //Send Email message
                $recv_email = $group->admin->email;
                $sender_email = $me->email;
                $mail_template_view_data=load_default_resources();
                if(empty($mail_template_data)){
                    $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$me->firstName.' '.$me->lastName;
                    $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                    $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
                }
                $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                $this->user->notification_add($data);
                //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'notification send to group admin'));
                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$usr->firstName.' '.$usr->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of your Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.$defaultResourcesData["MainSiteBaseURL"],
                    'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$data['receiverId'],
                    'senderMobileNumber'=>$group->admin->mobile,'nType'=>$data['nType']);
                send_sms_notification($sms_data);
            endif;

            if($order_update['status']==2):
                //$this->_sent_order_complete_mail($order);
                //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'going for send order complete mail by sent_buying_club_order_complete_mail fun'));
                $this->sent_buying_club_order_complete_mail($order);
                //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'order complete mail sent success fully to all member and grop admin'));
            endif;
            
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'razorpay payment data aded to DB'));
            $rajorpayDataArr=$this->order->get_rajorpay_id_by_rajorpay_pament_id($razorpayPaymentId);
            $this->order->add_payment(array('orderId'=>$orderId,'paymentType'=>'razorpay','razorpayId'=>$rajorpayDataArr[0]->razorpayId,'orderType'=>'group'));
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'razorpay payment data added successfully'));
            $result=array();
            $result['message']='Thanks you for shopping with '.$defaultResources["MainSiteBaseURL"].'.Order placed successfully for each item selected.For More details check your "My Order" section.';
            success_response_after_post_get($result);
        else:
            $this->response(array('error' => 'Invalid order index provided'), 400); return FALSE;
        endif;
    }
    
    function process_razorpay_success_group_order_final($orderId,$razorpayPaymentId,$logisticsData){
        $defaultResourcesData=  load_default_resources();
        $pevorder = $this->order->get_single_order_by_id($orderId);
        $prod_price_info = $this->product->get_products_price_details_by_id($pevorder->productPriceId);
        $aProductQty= $this->_get_available_order_quantity($orderId);
        $order_update=array();
        
        $order_update=array();
        $order_update['isPaid'] = 1;

        $update = $this->order->update($order_update,$orderId);

        //Notification
        $order = $pevorder;
        $orderinfo = unserialize(base64_decode($order->orderInfo));

        $group =$this->user->get_group_by_id($order->groupId);;
        $orderinfo['group'] = $group;
        
        $rsUser=$this->user->get_details_by_id($order->userId);
        $user = $rsUser[0];
        $recv_email = $user->email;
        $recv_name=$user->firstName.' '.$user->lastName;
        $tidiitStr='TIDIIT-OD-';
        
        $data['senderId'] = $order->userId;
        $data['nType'] = 'BUYING-CLUB-ORDER';
            
        if($order->parrentOrderID == 0):
            foreach($group->users as $key => $usr):
                $mail_template_data=array();
                $data['receiverId'] = $usr->userId;
                
                $data['nTitle'] = '<b>'.$user->firstName.' '.$user->lastName.'</b>  completed payment before delivery';
                $mail_template_data['TEMPLATE_GROUP_ORDER_START_TITLE']=$user->firstName.' '.$user->lastName;
                $data['nMessage'] = "Hi, ".$group->admin->firstName.' '.$group->admin->lastName."<br> completed his payment for buy the Buying Club order product.<br>";
                $data['nMessage'] .= "Product is <a href=''>".$orderinfo['pdetail']->title."</a><br>";
                $mail_template_data['TEMPLATE_GROUP_ORDER_START_PRODUCT_TITLE']=$orderinfo['pdetail']->title;
                $mail_template_data['TEMPLATE_GROUP_ORDER_START_ORDERID']=$orderId;
                $mail_template_data['TEMPLATE_GROUP_ORDER_START_ORDERAMT']=$order->orderAmount;;
                $mail_template_data['TEMPLATE_GROUP_ORDER_START_ORDERQTY']=$order->productQty;
                $data['nMessage'] .= "Thanks <br> Tidiit Team.";
                $data['orderId'] =$orderId;
                $data['productId'] =$orderinfo['priceinfo']->productId;
                $data['productPriceId'] =$orderinfo['priceinfo']->productPriceId;

                $data['isRead'] = 0;
                $data['status'] = 1;
                $data['createDate'] = date('Y-m-d H:i:s');

                //Send Email message
                $recv_email = $usr->email;
                $sender_email = $group->admin->email;

                $mail_template_view_data=load_default_resources();
                $mail_template_view_data['group_order_start']=$mail_template_data;
                global_tidiit_mail($recv_email, 'Payment has submited before delivery for Buying Club Order', $mail_template_view_data,'group_order_sod_final_payment');
                $this->user->notification_add($data);
            endforeach;
        else:
            $rsUser=$this->user->get_details_by_id($pevorder->userId);
            $me = $rsUser[0];
            $mail_template_data=array();
            foreach($group->users as $key => $usr):
                $mail_template_data=array();
                $data['receiverId'] = $usr->userId;
                
                $data['nTitle'] = '<b>'.$recv_name.'</b> completed payment before delivery for  Buying Club order';
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$me->firstName.' '.$me->lastName;
                $data['nMessage'] = "Hi, <br> I have paid Rs. ".$order->orderAmount." /- for the quantity ".$order->productQty." of this Buying Club.<br>";
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
                $data['nMessage'] .= "";
                $data['nMessage'] .= "Thanks <br> Tidiit Team.";
                $data['orderId'] =$orderId;
                $data['productId'] =$orderinfo['priceinfo']->productId;
                $data['productPriceId'] =$orderinfo['priceinfo']->productPriceId;

                $data['isRead'] = 0;
                $data['status'] = 1;
                $data['createDate'] = date('Y-m-d H:i:s');

                //Send Email message
                $recv_email = $usr->email;
                $sender_email = $me->email;

                $mail_template_view_data=load_default_resources();
                $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                global_tidiit_mail($recv_email,"Payment submited for Tidiit Buying Club order  before delivery", $mail_template_view_data,'group_order_group_member_sod_final_payment');
                if($me->userId != $usr->userId):
                    $this->user->notification_add($data);
                endif;
            endforeach;
            $data['receiverId'] = $group->admin->userId;

            //Send Email message
            $recv_email = $group->admin->email;
            $leaderName=$group->admin->firstName.' '.$group->admin->lastName;
            $sender_email = $me->email;
            $mail_template_view_data=load_default_resources();
            if(empty($mail_template_data)){
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$me->firstName.' '.$me->lastName;
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
            }
            $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
            global_tidiit_mail($recv_email,"Payment has submited for Tidiit Buying Club order before delivery", $mail_template_view_data,'group_order_group_member_sod_final_payment',$leaderName);
            $this->user->notification_add($data);
        endif;

        if(!empty($logisticsData) && array_key_exists('deliveryStaffContactNo', $logisticsData)):
            $logisticMobileNo=$logisticsData['deliveryStaffContactNo'];
            if($logisticMobileNo!=""):
                $sms=$recv_name.' has completed the payment for Tidiit order '.$tidiitStr.'-'.$orderId.' please process the delivery.';
                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$logisticMobileNo,'senderId'=>'','receiverId'=>'',
                    'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-LOGISTICS');
                send_sms_notification($sms_data);
            endif;
        endif;

        /// SMS to payer
        $sms='Thanks for the payment.We have received for Tidiit Buying Club['.$group->groupTitle.'] order '.$tidiitStr.'-'.$orderId.'.';
        $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$user->mobile,'senderId'=>'','receiverId'=>$user->userId,
            'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-PAYER');
        send_sms_notification($sms_data);

        if($user->userId!=$group->admin->userId){
            /// SMS to group admin
            $sms='Your Tidiit Buying Club['.$group->groupTitle.'] member['.$user->firstName.' '.$user->lastName.'] has completed payment for order '.$tidiitStr.'-'.$orderId.'.';
            $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$group->admin->mobile,'senderId'=>'','receiverId'=>$group->admin->userId,
                'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-LEADER');
            send_sms_notification($sms_data);
        }

        $rajorpayDataArr=$this->order->get_rajorpay_id_by_rajorpay_pament_id($razorpayPaymentId);
        $this->order->edit_payment(array('paymentType'=>'razorpay','razorpayId'=>$rajorpayDataArr[0]->razorpayId),$orderId);
        $logisticMobileNo=$logisticsData['deliveryStaffContactNo'];
        $sms="Hi ".$logisticsData['deliveryStaffName'].'. '.$recv_name.' has completed the payment for Tidiit order '.$tidiitStr.'-'.$orderId.' please process the delivery.';

        /// here send mail to logistic partner
        $mailBody="Hi ".$logisticsData['deliveryStaffName'].",<br /> <b>$recv_name</b> has completed Tidiit payment for Order <b>".$tidiitStr.'-'.$orderId.'</b><br /><br /> Pleasee process the delivery for the above order.<br /><br />Thanks<br>Tidiit Team.';
        global_tidiit_mail($logisticsData['deliveryStaffEmail'],'Tidiit payment completed for Order '.$tidiitStr.'-'.$orderId,$mailBody,'',$recv_name);

        $this->_sent_single_order_complete_mail_sod_final_payment($orderId);
        $result=array();
        $result['message']='Thanks for the payment before order is Out for delivery';
        success_response_after_post_get($result);
    }
    
    function process_razorpay_success_single_order($orderIdArr,$razorpayPaymentId){
        $orderId=0;
        $tidiitStrChr='TIDIIT-OD';
        $tidiitStr='';
        $rajorpayDataArr=$this->order->get_rajorpay_id_by_rajorpay_pament_id($razorpayPaymentId);
        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'calling process_razorpay_success_single_order fun at 3258 line === '));
        foreach ($orderIdArr AS $k => $v):
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'calling process_razorpay_success_single_order fun at 3258 line in side loop === '));;
            $tidiitStr=$tidiitStrChr.'-'.$v.',';
            $order_update=array();
            $order_update['status'] = 2;
            $order_update['isPaid'] = 1;
            $this->order->update($order_update,$v);
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'update order in loop === '));
            $order=$this->order->get_single_order_by_id($v);
            $orderinfo =  unserialize(base64_decode($order->orderInfo));
            //$this->Product_model->update_product_quantity($order['productId'],$order['productQty']);
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$v;
            
            //Send Email message
            $rsUser=$this->user->get_details_by_id($order->userId);
            $user = $rsUser[0];
            $recv_email = $user->email;
            $this->order->add_payment(array('orderId'=>$v,'paymentType'=>'razorpay','razorpayId'=>$rajorpayDataArr[0]->razorpayId,'orderType'=>'single'));
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'add payment data to db === '));
            $mail_template_view_data=load_default_resources();
            $mail_template_view_data['single_order_success']=$mail_template_data;
            global_tidiit_mail($recv_email, "Your Tidiit order no - TIDIIT-OD-".$v.' has placed successfully', $mail_template_view_data,'single_order_success');
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'Going for sent_single_order_complete_mail function ==== '));
            $this->sent_single_order_complete_mail($v);
        endforeach;
        $result=array();
        $result['message']="Your order will process shortly and will update you by separate notification over mail and SMS";
        success_response_after_post_get($result);
    }
    
    function process_razorpay_success_single_order_final($orderIdArr,$razorpayPaymentId,$logisticsData){
        $orderId=0;
        $tidiitStrChr='TIDIIT-OD';
        $tidiitStr='';
        //Send Email message
        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'select rajorpaypayment data from db'));
        $rajorpayDataArr=$this->order->get_rajorpay_id_by_rajorpay_pament_id($razorpayPaymentId);
        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'foreach with order id arr'));
        foreach ($orderIdArr AS $k => $v):
            $orderId=$v;
            $tidiitStr=$tidiitStrChr.'-'.$v.',';
            $order_update=array();
            $order_update['isPaid'] = 1;
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'going to update order with id : '.$v));
            $this->order->update($order_update,$v);
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'complete order update for order id :'.$v));
            $order=$this->order->get_single_order_by_id($v);
            $orderinfo =  unserialize(base64_decode($order->orderInfo));
            
            $rsUser=$this->user->get_details_by_id($order->userId);
            $user = $rsUser[0];
            $recv_email = $user->email;
            $recv_name=$user->firstName.' '.$user->lastName;
            
            
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$v;
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'getting razorpay payment id details'));
            $rajorpayDataArr=$this->order->get_rajorpay_id_by_rajorpay_pament_id($razorpayPaymentId);
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'going to update payment type for order id : '.$v));
            $this->order->edit_payment(array('paymentType'=>'razorpay','razorpayId'=>$rajorpayDataArr[0]->razorpayId),$v);
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'Payment type updated success.'));

            $mail_template_view_data=$this->load_default_resources();
            $mail_template_view_data['single_order_success']=$mail_template_data;
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'going to send mail to suer about payment'));
            global_tidiit_mail($recv_email, "Payment has completed for your Tidiit order TIDIIT-OD-".$v, $mail_template_view_data,'single_order_success_sod_final_payment',$recv_name);
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'going for _sent_single_order_complete_mail_sod_final_payment fun.'));
            $this->_sent_single_order_complete_mail_sod_final_payment($v);
            
        endforeach;
        //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'checking for logistic partner data'));
        /// here to preocess SMS to logistics partner
        if(!empty($logisticsData) && array_key_exists('deliveryStaffContactNo', $logisticsData)):
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'cehcking delivery staff contact number'));
            $logisticMobileNo=$logisticsData['deliveryStaffContactNo'];
            if($logisticMobileNo!=""):
                //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'going to send SMS to delivery staff for delivery'));
                $sms=$recv_name.' has completed the payment for Tidiit order '.$tidiitStr.' please process the delivery.';
                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$logisticMobileNo,'senderId'=>'','receiverId'=>'',
                    'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-LOGISTICS');
                send_sms_notification($sms_data);
            else:
                //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'no deliery staff contact number found'));
            endif;
        else:
            //send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=>'not logistic partner data found'));
        endif;

        /// SMS to payer
        $sms='Thanks for the payment.We have received for Tidiit order '.$tidiitStr.'.';
        $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$user->mobile,'senderId'=>'','receiverId'=>$user->userId,
            'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-PAYER');
        send_sms_notification($sms_data);

        /// here send mail to logistic partner
        $mailBody="Hi ".$logisticsData['deliveryStaffName'].",<br /> <b>$recv_name</b> has completed Tidiit payment for Order <b>".$tidiitStr.'</b><br /><br /> Pleasee process the delivery for the above order.<br /><br />Thanks<br>Tidiit Team.';
        global_tidiit_mail($logisticsData['deliveryStaffEmail'],'Tidiit payment submited  for Order '.$tidiitStr,$mailBody,'',$recv_name);
        $result=array();
        $result['message']='Thanks for the payment before order is Out for delivery';
        success_response_after_post_get($result);
    }
    
    function debuging_razorpay_post(){
        $razorpayPaymentId=$this->post('razorpayPaymentId');
        $orderIdData=$this->post('orderIdData');
        $orderType="single";
        $finalReturn="no";
        $userId=$this->post('userId');
        $latitude = "20.658933333333334";
        $longitude = "85.60109499999999";
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $deliveryStaffEmail=trim($this->input->post('deliveryStaffEmail',TRUE));
        $deliveryStaffContactNo=trim($this->input->post('deliveryStaffContactNo',TRUE));
        $deliveryStaffName=trim($this->input->post('deliveryStaffName',TRUE));
        
        $logisticsData=array('deliveryStaffName'=>$deliveryStaffName,'deliveryStaffContactNo'=>$deliveryStaffContactNo,'deliveryStaffEmail'=>$deliveryStaffEmail);
        $orderIdDataArr= unserialize(base64_decode($orderIdData));
        send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'getting razorpay info from DB'));
        $razorpayInfo=$this->order->get_razorpay_info();
        $api_key=$razorpayInfo[0]->userName;
        $api_secret=$razorpayInfo[0]->password;
        
        send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'started razorpay API'));
        //include_once src/Api.php;
        $api = new Api($api_key, $api_secret);
        $payment = $api->payment->fetch($razorpayPaymentId);
        send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'get payment infor by razorpay API'));
        $Amount=$payment->amount;
        send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'commit to capture payment by rajorpay API'));
        $captureData=$api->payment->fetch($razorpayPaymentId)->capture(array('amount'=>$Amount));
        send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'payemnt capture completed by rajorpay API'));
        if($captureData->captured==1){
            if($orderType=='group'):
                //$orderDataArr = $PaymentDataArr;
                if($finalReturn=='no'):
                    $productPriceArr=$this->order->get_product_price_details_by_orderid($orderIdDataArr[0]);
                    $this->product->update_product_quantity($productPriceArr[0]['productId'],$productPriceArr[0]['qty']);
                    $this->process_razorpay_success_group_order($orderIdDataArr[0],$razorpayPaymentId);
                else:
                    $this->process_razorpay_success_group_order_final($orderIdDataArr[0],$razorpayPaymentId,$logisticsData);
                endif;
            else:
                if($finalReturn=='no'):
                    send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> ' commit to update the order product qty'));
                    foreach($orderIdDataArr As $k=> $v){
                        send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'getting product price data with order id : '.$v));
                        $productPriceArr=$this->order->get_product_price_details_by_orderid($v);
                        send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'commit udpate QTY'));
                        $this->product->update_product_quantity($productPriceArr[0]['productId'],$productPriceArr[0]['qty']);
                        send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> 'qty update completed'));
                    }
                    //$this->process_mpesa_success_single_order(array('orders'=>$PaymentDataArr['orders'],'orderInfo'=>$PaymentDataArr['orderInfo']));
                    send_sms_notification(array('receiverMobileNumber'=>'9556644964', 'nMessage'=> ' single order process start for razorpay with fun process_razorpay_success_single_order === '));
                    $this->process_razorpay_success_single_order($orderIdDataArr,$razorpayPaymentId);
                else:
                    //$this->process_mpesa_success_single_order_final(array('orders'=>$PaymentDataArr['orders'],'orderInfo'=>$PaymentDataArr['orderInfo'],'logisticsData'=>$logisticsData));
                    $this->process_razorpay_success_single_order_final($orderIdDataArr,$razorpayPaymentId,$logisticsData);
                endif;
            endif;
        }else{
            $this->response(array('error' => "unknown eror in capturing the data."), 400); return FALSE;
        }
    }
    
    
    
}