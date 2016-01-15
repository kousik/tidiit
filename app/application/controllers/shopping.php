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
    }
    
    function add_to_cart_post(){
        $productId = $this->input->post('productId');
        $userId = $this->input->post('userId');
        $productPriceId = $this->input->post('productPriceId');
        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $product = $this->product->details($productId);
        $product = $product[0];
        $prod_price_info = $this->product->get_products_price_details_by_id($productPriceId);
        $is_cart_update = false;
        
        $order_data = array();
        $order_data['orderType'] = 'GROUP';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $productPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        $order_data['userId'] = $userId;
        $order_data['productQty'] = $prod_price_info->qty;
        $order_data['subTotalAmount'] = $prod_price_info->price;
        $order_data['orderAmount'] = $prod_price_info->price;
        $order_data['orderDevicetype'] = 3;
        $order_data['appSource'] = 'android';
        $order_data['longitude'] = $longitude;
        $order_data['latitude'] = $latitude;
        
        
        $cartArr = array();
        $cartArr['id'] = $productId;
        $cart_data['name'] = $product->title;
        $single_price = ($prod_price_info->price/$prod_price_info->qty);
        $cart_data['price'] = number_format($single_price, 2, '.', '');
        $cart_data['qty'] = $prod_price_info->qty;
        
        $orderinfo = array();
        $orderinfo['pdetail'] = $product;
        $orderinfo['priceinfo']=$prod_price_info;
        $productImageArr =$this->product->get_products_images($productId);
        $orderinfo['pimage'] = $productImageArr[0];            
        $userShippingDataDetails = $this->user->get_user_shipping_information($userId,TRUE);
        $orderinfo['shipping'] = $userShippingDataDetails[0];
        
        $order_data['orderInfo'] = base64_encode(serialize($orderinfo));
        
        $qrCodeFileName=time().'-'.rand(1, 50).'.png';
        $order_data['qrCodeImageFile']=$qrCodeFileName;
        $orderId=$this->Order_model->add($order_data);
        $data['orderId']=$orderId;
        $params=array();
        $params['data']=$orderId;
        $params['savename']=$this->config->item('ResourcesPath').'qr_code/'.$qrCodeFileName;
        $this->tidiitrcode->generate($params);
        
        $order_data['orderId'] = $data['orderId'];
        $cart_data['options'] = $order_data;
        
        $data['order'] = $this->Order_model->get_single_order_by_id($data['orderId']);
    }
    
    function create_cart_data($userId){
        
    }
    
    function test_get(){
        pre($_SERVER['DOCUMENT_ROOT']);die;
    }
}
    
?>