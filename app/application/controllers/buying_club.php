<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Buying_club extends REST_Controller {
    
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
    
    function checking_user_data_post(){
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
        //$rs=$this->get_user_details_from_model($userId);
        //pre($rs);die;
        
        $user=$this->user->app_get_details_by_id($userId);
        //pre($user);die;
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