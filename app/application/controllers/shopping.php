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
        $cartDataArr=array('productId'=>$productId,'userId'=>$userId,'productPriceId'=>$productPriceId,'latitude'=>$latitude,'longitude'=>$longitude,'deviceType'=>$deviceType);
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

        $isAdded=$this->user->is_shipping_address_added();
        if(empty($isAdded)){
            $this->user->add_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'userId'=>$userId,'address'=>$address,'stateId'=>$rs[0]->stateId,'appSource'=>$deviceType,'landmark'=>$landmark));
        }else{
            $this->user->edit_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'address'=>$address,'stateId'=>$rs[0]->stateId,'landmark'=>$landmark),$userId);
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
    
}
    
?>