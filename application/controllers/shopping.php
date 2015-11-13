<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shopping extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->_isLoggedIn();
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->db->cache_off();
        $this->load->library('cart');
        $this->load->model('Category_model');
        $this->load->model('Product_model');
        $this->load->model('Country');
    }
       
    
    /**
     * 
     */
    function process_my_group_orders(){
        
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details(); 
        $productId = $this->input->post('productId');
        $prorductPriceId = $this->input->post('prorductPriceId');
        if((isset($productId) && !$productId) && (isset($prorductPriceId) && !$prorductPriceId)):
            redirect(BASE_URL.'404_override');
        endif;
        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($prorductPriceId);
        $is_cart_update = false;
        $cart = $this->cart->contents();
        
        
        //$this->cart->destroy();
        if($cart): 
            foreach ($cart as $item):            
                if(($item['id'] == $productId)):
                    $data['orderId'] = $item['options']['orderId'];
                    $is_cart_update = $item['rowid'];                    
                endif;
            endforeach;
        endif;
        
        //Order first step
        $order_data = array();
        $order_data['orderType'] = 'GROUP';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $prorductPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        
        //Cart first step
        $cart_data = array();
        $cart_data['id'] = $productId;
        $cart_data['name'] = $product->title;
        $single_price = ($prod_price_info->price/$prod_price_info->qty);
        $cart_data['price'] = number_format($single_price, 2, '.', '');
        
        $cart_data['qty'] = $prod_price_info->qty;
        
        if(!isset($data['orderId'])):
            $order_data['productQty'] = 0;
            $order_data['userId'] = $this->session->userdata('FE_SESSION_VAR');
            $data['orderId']=$this->Order_model->add($order_data);
        endif;
        
        //$data['orderId'] = 0;
        $order_data['orderId'] = $data['orderId'];
        $order_data['productPriceId'] = $prorductPriceId;
        $cart_data['options'] = $order_data;
        
        
        $data['order'] = $this->Order_model->get_single_order_by_id($data['orderId']);
        //==============================================//
        if($is_cart_update):
            //$cart_data['rowid'] = $is_cart_update;
            $this->_remove_cart($is_cart_update); //echo $is_cart_update;
            $this->_add_to_cart($cart_data);
            //$this->_update_single_cart($cart_data);
            if(isset($data['orderId'])):
                $order_update = $order_data;
                unset($order_update['orderId']);
                unset($order_update['orderDate']);
                $order_update['orderUpdatedate'] = date('Y-m-d H:i:s');
                $order_update['productQty'] = $data['order']->productQty;
                //print_r($order_update);
                $this->Order_model->update($order_update,$data['orderId']);
            endif;
        else:
            $this->_add_to_cart($cart_data);
        endif;
        
        
        
        $cart = $this->cart->contents();
        
        $data['rowid'] = false; 
        if($cart): 
            foreach ($cart as $item):            
                if(($data['orderId'] == $item['options']['orderId'])):
                    $data['rowid'] = $item['rowid'];                    
                endif;
            endforeach;
        endif;
        
        $a = $this->_get_available_order_quantity($data['orderId']);
        $data['availQty'] = $prod_price_info->qty - $a[0]->productQty;
        //=============================================//
        
        
        if($data['order']->groupId):
            $data['group'] = $this->User_model->get_group_by_id($data['order']->groupId);
            $data['groupId'] = $data['order']->groupId;
        else:
            $data['group'] = false;
            $data['groupId'] = '';
        endif;
        
        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        $data['userMenuActive']=7;
        $data['countryDataArr']=$this->Country->get_all1();
        $menuArr=array();
        $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
        //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->Category_model->get_subcategory_by_category_id($k->categoryId);
            if(count($SubCateory)>0){
                foreach($SubCateory as $kk => $vv){
                    $menuArr[$vv->categoryId]=$k->categoryName.' -> '.$vv->categoryName;
                    $ThirdCateory=$this->Category_model->get_subcategory_by_category_id($vv->categoryId);
                    if(count($ThirdCateory)>0){
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            $menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->Category_model->get_subcategory_by_category_id($v3->categoryId);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }
                            }
                        }
                    }
                }
            }
        }
        $data['CatArr']=$menuArr;
        $user = $this->_get_current_user_details();
        $my_groups = $this->User_model->get_my_groups();
        $data['myGroups']=$my_groups;
        $data['user']=$user;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('group_order/group_order',$data);
    }
    
    /**
     * 
     * @param type $orderId
     */
    function process_my_group_orders_by_id($orderId){
        if(!$orderId):
            redirect(BASE_URL.'404_override');
        endif;
        $orderId = base64_decode($orderId);
        $orderId = $orderId/226201;
        
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details(); 
        
        $data['order'] = $this->Order_model->get_single_order_by_id($orderId);
        
        $productId = $data['order']->productId;
        $prorductPriceId = $data['order']->productPriceId;
        if((isset($productId) && !$productId) && (isset($prorductPriceId) && !$prorductPriceId)):
            redirect(BASE_URL.'404_override');
        endif;
        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($prorductPriceId);
        $is_cart_update = false;
        $cart = $this->cart->contents();
        
        
        //$this->cart->destroy();
        if($cart): 
            foreach ($cart as $item):            
                if(($item['id'] == $productId)):
                    $data['orderId'] = $item['options']['orderId'];
                    $is_cart_update = $item['rowid'];                    
                endif;
            endforeach;
        endif;
        
        //Order first step
        $order_data = array();
        $order_data['orderType'] = 'GROUP';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $prorductPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        
        //Cart first step
        $cart_data = array();
        $cart_data['id'] = $productId;
        $cart_data['name'] = $product->title;
        $single_price = ($prod_price_info->price/$prod_price_info->qty);
        $cart_data['price'] = number_format($single_price, 2, '.', '');
        $cart_data['qty'] = $prod_price_info->qty;
        
        if(!isset($data['orderId'])):
            $order_data['productQty'] = 0;
            $data['orderId']=$this->Order_model->add($order_data);
        endif;
        
        //$data['orderId'] = 0;
        $order_data['orderId'] = $data['orderId'];
        $order_data['productPriceId'] = $prorductPriceId;
        $cart_data['options'] = $order_data;
        
        //==============================================//
        
        $cart = $this->cart->contents();
        
        $data['rowid'] = false; 
        if($cart): 
            foreach ($cart as $item):            
                if(($data['orderId'] == $item['options']['orderId'])):
                    $data['rowid'] = $item['rowid'];                    
                endif;
            endforeach;
        endif;
        $a = $this->_get_available_order_quantity($data['orderId']);
        $data['availQty'] = $prod_price_info->qty - $a[0]->productQty;
        
        //=============================================//
        
        if($data['order']->groupId):
            $data['group'] = $this->User_model->get_group_by_id($data['order']->groupId);
            $data['groupId'] = $data['order']->groupId;
        else:
            $data['group'] = false;
            $data['groupId'] = 0;
        endif;
        
        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        $data['userMenuActive']=7;
        $data['countryDataArr']=$this->Country->get_all1();
        $menuArr=array();
        $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
        //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->Category_model->get_subcategory_by_category_id($k->categoryId);
            if(count($SubCateory)>0){
                foreach($SubCateory as $kk => $vv){
                    $menuArr[$vv->categoryId]=$k->categoryName.' -> '.$vv->categoryName;
                    $ThirdCateory=$this->Category_model->get_subcategory_by_category_id($vv->categoryId);
                    if(count($ThirdCateory)>0){
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            $menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->Category_model->get_subcategory_by_category_id($v3->categoryId);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }
                            }
                        }
                    }
                }
            }
        }
        $data['CatArr']=$menuArr;
        $user = $this->_get_current_user_details();
        $my_groups = $this->User_model->get_my_groups();
        $data['myGroups']=$my_groups;
        $data['user']=$user;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('group_order/group_order',$data);
    }
    
    /**
     * 
     */
    function ajax_update_group_order(){
        $orderId = $this->input->post('orderId',TRUE);
        $cartid = $this->input->post('cartid',TRUE);
        $qty = $this->input->post('qty',TRUE);
        
        
        $cart_info['rowid'] = $cartid;
        $cart_info['qty'] = $qty;
        $this->_update_single_cart($cart_info);
        
        
        $cart = $this->cart->contents();
        
        $data['rowid'] = false; 
        if($cart): 
            foreach ($cart as $item):            
                if(($cartid == $item['rowid'])):
                    $order_update['orderAmount'] = $item['subtotal']; 
                    $order_update['subTotalAmount'] = $item['subtotal']; 
                endif;
            endforeach;
        endif;
        $order_update['productQty'] = $qty;        
        $this->Order_model->update($order_update,$orderId);
        
        ob_start();
        echo BASE_URL.'shopping/checkout/'.base64_encode($orderId*226201);
        $result['contents'] = ob_get_contents();
	ob_end_clean();
	echo json_encode( $result );
	die;
    }
    
    /**
     * 
     */
    function ajax_order_set_promo(){
        $orderId = $this->input->post('orderId',TRUE);
        $promocode = $this->input->post('promocode',TRUE);
        
        $coupon = $this->Order_model->is_coupon_code_exists($promocode);
        if(!$coupon):
            $result['error'] = "Invalid promo code!";
            echo json_encode( $result );
            die;
        endif;
        
        $ordercoupon = $this->Order_model->is_coupon_code_used_or_not($coupon, $orderId);
        
        if(!$ordercoupon):
            $result['error'] = "Promo code already used!";
            echo json_encode( $result );
            die;
        elseif(isset($ordercoupon['applied']) &&  $ordercoupon['applied']):
            $result['msg'] = "Promo code has been applied successfully!";
            $result['content'] = $ordercoupon;
            echo json_encode( $result );
            die;
        elseif($ordercoupon && !isset($ordercoupon['applied'])):
            $result['msg'] = "Promo code has been applied successfully!";
            $result['content'] = $ordercoupon;
            echo json_encode( $result );
            die;
        endif;
	
    }
    
    /**
     * 
     */
    function ajax_process_group_payment(){
        $orderId = $this->input->post('orderId',TRUE);
        $cartId = $this->input->post('cartId',TRUE);
        
        $order_update['status'] = 1;        
        $update = $this->Order_model->update($order_update,$orderId);
        if($update):
            
            //Notification
            $order = $this->Order_model->get_single_order_by_id($orderId);
            
            $group = $this->User_model->get_group_by_id($order->groupId);
            
            foreach($group->users as $key => $usr):
                $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $data['receiverId'] = $usr->userId;
                $data['nType'] = 'GROUP-ORDER';
                $data['nTitle'] = 'New Group oreder running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';
                $data['nMessage'] = "Hi, <br> You have requested to buy group order product.<br>";
                $data['nMessage'] .= "Product is <a href=''></a><br>";
                $data['nMessage'] .= "Want to process the order ? <br>";
                $data['nMessage'] .= "<a href='".BASE_URL."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".BASE_URL."shopping/group-order-accept-process/".base64_encode($orderId*226201)."' class='btn btn-success btn-lg'>Accept</a><br>";
                $data['nMessage'] .= "Thanks <br> Tidiit Team.";
                
                $data['isRead'] = 0;
                $data['status'] = 1;
                $data['createDate'] = date('Y-m-d H:i:s');
                
                //Send Email message
                $recv_email = $usr->email;
                $sender_email = $group->admin->email;
                
                $this->User_model->notification_add($data);
            endforeach;
            
            
            $this->_remove_cart($cartId);
            $result['url'] = BASE_URL.'shopping/success/';
            echo json_encode( $result );
            die;
        else:
            $result['error'] = "Some error happen, please try again later!";
            echo json_encode( $result );
            die;
        endif;
    }
    
    /**
     * 
     */
    function oredr_process_success(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('group_order/group_order_success',$data);
    }


    /**
     * 
     * @param type $orderId
     */
    function process_checkout($orderId){
        
        if(!$orderId):
            redirect(BASE_URL.'404_override');
        endif;
        $orderId = base64_decode($orderId);
        $orderId = $orderId/226201;
        
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $order = $this->Order_model->get_single_order_by_id($orderId);
        if(!$order):
            redirect(BASE_URL.'404_override');
        endif;
        if($order->orderType == "GROUP" && $order->productQty == 0):
            redirect(BASE_URL.'shopping/mod-group-order/'.base64_encode($orderId*226201));
            $this->session->set_flashdata('error', 'Please set your quantity!');
        elseif($order->orderType == "SINGLE" && $order->productQty == 0):
            $this->session->set_flashdata('error', 'Please set your quantity!');
        endif;
        
        $userShippingDataDetails=$this->User_model->get_user_shipping_information();
        if(empty($userShippingDataDetails)){
            $userShippingDataDetails[0]=new stdClass();
            $userShippingDataDetails[0]->firstName="";
            $userShippingDataDetails[0]->lastName="";
            $userShippingDataDetails[0]->countryId="";
            $userShippingDataDetails[0]->cityId="";
            $userShippingDataDetails[0]->zipId="";
            $userShippingDataDetails[0]->localityId="";
            $userShippingDataDetails[0]->phone="";
            $userShippingDataDetails[0]->address="";
            $userShippingDataDetails[0]->contactNo="";
            $userShippingDataDetails[0]->landmark="";
        }
        if($userShippingDataDetails[0]->countryId!=""){
            $data['cityDataArr']=  $this->Country->get_all_city1($userShippingDataDetails[0]->countryId);
        }
        if($userShippingDataDetails[0]->zipId!=""){
            $data['zipDataArr']=  $this->Country->get_all_zip1($userShippingDataDetails[0]->cityId);
        }
        if($userShippingDataDetails[0]->localityId!=""){
            $data['localityDataArr']=  $this->Country->get_all_locality($userShippingDataDetails[0]->zipId);
        }
        $data['countryDataArr']=$this->Country->get_all1();
        $data['userShippingDataDetails']=$userShippingDataDetails[0];
        
        
        $data['order'] = $order;
        
        $product = $this->Product_model->details($order->productId);
        $data['product'] = $product[0];
        
        $cart = $this->cart->contents();        
        $data['rowid'] = false; 
        if($cart): 
            foreach ($cart as $item):            
                if(($orderId == $item['options']['orderId'])):
                    $data['rowid'] = $item['rowid'];                    
                endif;
            endforeach;
        endif;
        
        $coupon = $this->Order_model->get_order_coupon($orderId);
        $data['coupon'] = $coupon;
        $this->load->view('group_order/checkout',$data);
    }
    
    function _get_available_order_quantity($orderId){
        $availQty = $this->Order_model->get_available_order_quantity($orderId);
        return $availQty;
    }
    
    
    
    //AJAX = Function
    /**
     * 
     */
    function remove_group_cart(){
        $cartId = $this->input->post('cartId',TRUE);
        $orderId = $this->input->post('orderId',TRUE);
        $this->_remove_cart($cartId);
        
        $data['status'] = 0;
        $this->Order_model->delete($orderId);
        ob_start();
        echo true;
        $result['contents'] = ob_get_contents();
	ob_end_clean();
	echo json_encode( $result );
	die;
    }
    
    function _add_to_cart($data) {
        
        // Set array for send data.
        $insert_data = array(
            'id' => 0,
            'name' => '',
            'price' => 0,
            'qty' => 0
        );
        
        $datas = array_merge($insert_data, $data);
        
        // This function add items into cart.
        $this->cart->insert($datas);
    }
    
    
    function _update_cart($cart_info){

        // Recieve post values,calcute them and update
        foreach( $cart_info as $id => $cart) {
            $rowid = $cart['rowid'];
            $price = $cart['price'];
            $amount = $price * $cart['qty'];
            $qty = $cart['qty'];

            $data = array(
                'rowid' => $rowid,
                'price' => $price,
                'amount' => $amount,
                'qty' => $qty
            );

            $this->cart->update($data);
        }
    }
    
    function _update_single_cart($cart_info){

        // Recieve post values,calcute them and update
        $this->cart->update($cart_info);
    }
    
    
    function _remove_cart($rowid) {
        // Check rowid value.
        if ($rowid==="all"){
            // Destroy data which store in session.
            $this->cart->destroy();
        }else{
            // Destroy selected rowid in session.
            $data = array(
                'rowid' => $rowid,
                'qty' => 0
            );
            // Update cart data, after cancel.
            $this->cart->update($data);
        }
    }
}