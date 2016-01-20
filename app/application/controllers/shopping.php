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
        $cartDataArr=array('productId'=>$productId,'userId'=>$userId,'productPriceId'=>$productPriceId,'latitude'=>$latitude,'longitude'=>$longitude,
            'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'UDID'=>$UDID);
        $msg=$this->add_to_cart($cartDataArr);
        $result=array();
        $result['message']=$msg;//$orderId.'-'.$qrCodeFileName;
        success_response_after_post_get($result);
    }
    
    function add_to_cart($cartDataArr){
        $product = $this->product->details($cartDataArr['productId']);
        $product = $product[0];
        $prod_price_info = $this->product->get_products_price_details_by_id($cartDataArr['productPriceId']);
        $is_cart_update = false;
        
        $order_data = array();
        $order_data['orderType'] = 'SINGLE';
        $order_data['productId'] = $cartDataArr['productId'];
        $order_data['productPriceId'] = $cartDataArr['productPriceId'];
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        $order_data['userId'] = $cartDataArr['userId'];
        $order_data['productQty'] = $prod_price_info->qty;
        $order_data['subTotalAmount'] = $prod_price_info->price;
        $order_data['orderAmount'] = $prod_price_info->price;
        $order_data['orderDevicetype'] = 3;
        $order_data['appSource'] = $cartDataArr['deviceType'];
        $order_data['longitude'] = $cartDataArr['longitude'];
        $order_data['latitude'] = $cartDataArr['latitude'];
        $order_data['deviceToken'] = $cartDataArr['deviceToken'];
        $order_data['udid'] = $cartDataArr['UDID'];
        
        
        /*$cartArr = array();
        $cartArr['id'] = $productId;
        $cart_data['name'] = $product->title;
        $single_price = ($prod_price_info->price/$prod_price_info->qty);
        $cart_data['price'] = number_format($single_price, 2, '.', '');
        $cart_data['qty'] = $prod_price_info->qty;*/
        
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
        $allItemArr=$this->order->get_all_cart_item($userId);
        $result=array();
        $result['allItemArr']=$allItemArr;
        success_response_after_post_get($result);
    }
    
    function remove_item_from_cart_get(){
        $userId = $this->get('userId');
        $orderId = $this->get('orderId');
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
        
        
        $rs=$this->Country->city_details($cityId);

        $isAdded=$this->user->is_shipping_address_added($userId);
        /*if(empty($isAdded)){
            $this->user->add_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'userId'=>$userId,'address'=>$address,'stateId'=>$rs[0]->stateId,'appSource'=>$deviceType,'landmark'=>$landmark));
        }else{
            $this->user->edit_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'address'=>$address,'stateId'=>$rs[0]->stateId,'landmark'=>$landmark),$userId);
        }*/
        
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
        
    }
    
    function single_order_sod_payment_post(){
        $userId=  $this->post('userId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        $allIncompleteOrders= $this->order->get_incomplete_order_by_user($userId);
        $defaultResources=load_default_resources();
        $user=$this->user->get_details_by_id($userId)[0];
        foreach ($allIncompleteOrders As $k){
            $orderinfo = array();
            $mail_template_data = array();
            $orderInfo= unserialize(base64_decode($k->orderInfo));
            //pre($orderInfo);die;
            //pre($orderInfo['shipping']);die;
            //pre($orderInfo['pdetail']);die;
            //pre($orderInfo['priceinfo']);die;
            $settlementOnDeliveryId=$this->order->add_sod(array('latitude'=>$latitude,'longitude'=>$longitude,'userId'=>$userId));
            $this->order->add_payment(array('orderId'=>$k->orderId,'paymentType'=>'settlementOnDelivery','settlementOnDeliveryId'=>$settlementOnDeliveryId,'orderType'=>'single'));
            $this->product->update_product_quantity($orderInfo['priceinfo']->productId,$orderInfo['priceinfo']->qty);
            $orderUpdateArr=array('orderUpdatedate'=>date('Y-m-d H:i:s'),'status'=>2,'udidPayment'=>$UDID,'deviceTokenPayment'=>$deviceToken,
                'latitudePayment'=>$latitude,'longitudePayment'=>$longitude);
            $this->order->update($orderUpdateArr,$k->orderId);
            /// sendin SMS to user
            /*$sms_data=array('nMessage'=>'You have successfull placed an order TIDIIT-OD-'.$k->orderId.' for '.$orderInfo['pdetail']->title.'.More details about this notifiaction,Check '.$defaultResources['MainSiteBaseURL'],
                'receiverMobileNumber'=>$user->mobile,'senderId'=>'','receiverId'=>$user->userId,
                'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER');*/
            //send_sms_notification($sms_data);
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
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
    
    function set_wishlist_post(){
        $userId=$this->post('userId');
        $productId=$this->post('productId');
        $productPriceId = $this->post('productPriceId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
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
        $sms_data=array('nMessage'=>'Your Tidiit order TIDIIT-OD-'.$orderId.' for '.$orderInfoDataArr['pdetail']->title.' has placed successfully. More details about this notifiaction,Check '.BASE_URL,
        'receiverMobileNumber'=>$orderDetails[0]->buyerMobileNo,'senderId'=>'','receiverId'=>$orderDetails[0]->userId,
        'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-CONFIRM');
        send_sms_notification($sms_data);
        return TRUE;
    }
    
}
    
?>