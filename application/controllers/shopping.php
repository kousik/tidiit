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
        $productPriceId = $this->input->post('productPriceId');
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            redirect(BASE_URL.'404_override');
        endif;
        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($productPriceId);
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
        $order_data['productPriceId'] = $productPriceId;
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
        $order_data['productPriceId'] = $productPriceId;
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
        $productPriceId = $data['order']->productPriceId;
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            redirect(BASE_URL.'404_override');
        endif;
        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($productPriceId);
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
        $order_data['productPriceId'] = $productPriceId;
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
        $order_data['productPriceId'] = $productPriceId;
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
        
        $pevorder = $this->Order_model->get_single_order_by_id($orderId);
        $a = $this->_get_available_order_quantity($orderId);
        
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($pevorder->productPriceId);
        
        if($prod_price_info->qty == $a[0]->productQty):
            $order_update['status'] = 2;   
        else:
            $order_update['status'] = 1;   
        endif;
        $order_update['orderDate'] = date('Y-m-d H:i:s');
        
        $update = $this->Order_model->update($order_update,$orderId);
        if($update):
            $this->Order_model->order_group_status_update($orderId, $order_update['status'],$pevorder->parrentOrderID);
            
            //Notification
            $order = $this->Order_model->get_single_order_by_id($orderId);
            
            $orderinfo = array();
            $pro = $this->Product_model->details($order->productId);
            $orderinfo['pdetail'] = $pro[0];
            $orderinfo['priceinfo'] = $this->Product_model->get_products_price_details_by_id($order->productPriceId);
            $productImageArr =$this->Product_model->get_products_images($order->productId);
            $orderinfo['pimage'] = $productImageArr[0];            
            
            $userShippingDataDetails = $this->User_model->get_user_shipping_information();
            $orderinfo['shipping'] = $userShippingDataDetails[0];
            $userBillingDataDetails=$this->User_model->get_billing_address();
            $orderinfo['billing'] = $userBillingDataDetails[0];
            
            $info['orderInfo'] = base64_encode(serialize($orderinfo));
            $this->Order_model->update($info, $orderId);
            
        
            $group = $this->User_model->get_group_by_id($order->groupId);
            if($order->parrentOrderID == 0):
                foreach($group->users as $key => $usr):
                    $mail_template_data=array();
                    $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                    $data['receiverId'] = $usr->userId;
                    $data['nType'] = 'GROUP-ORDER';

                    $data['nTitle'] = 'New Group order running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_TITLE']=$group->admin->firstName.' '.$group->admin->lastName;
                    $data['nMessage'] = "Hi, <br> You have requested to buy group order product.<br>";
                    $data['nMessage'] .= "Product is <a href=''>".$orderinfo['pdetail']->title."</a><br>";
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_PRODUCT_TITLE']=$orderinfo['pdetail']->title;
                    $data['nMessage'] .= "Want to process the order ? <br>";
                    $data['nMessage'] .= "<a href='".BASE_URL."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".BASE_URL."shopping/group-order-accept-process/".base64_encode($orderId*226201)."' class='btn btn-success btn-lg'>Accept</a><br>";
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_ORDERID']=$orderId;
                    $data['nMessage'] .= "Thanks <br> Tidiit Team.";

                    $data['isRead'] = 0;
                    $data['status'] = 1;
                    $data['createDate'] = date('Y-m-d H:i:s');

                    //Send Email message
                    $recv_email = $usr->email;
                    $sender_email = $group->admin->email;
                    
                    $mail_template_view_data=$this->load_default_resources();
                    $mail_template_view_data['group_order_start']=$mail_template_data;
                    $this->_global_tidiit_mail($recv_email, "New Group Order Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_start');
                    $this->User_model->notification_add($data);
                endforeach;
            else:
                $me = $this->_get_current_user_details();
                $mail_template_data=array();
                foreach($group->users as $key => $usr):
                    if($me->userId != $usr->userId):
                        $mail_template_data=array();
                        $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                        $data['receiverId'] = $usr->userId;
                        $data['nType'] = 'GROUP-ORDER';

                        $data['nTitle'] = 'Buyers club order continue by <b>'.$usr->firstName.' '.$usr->lastName.'</b>';
                        $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$usr->firstName.' '.$usr->lastName;
                        $data['nMessage'] = "Hi, <br> I have paid Rs. ".$order->orderAmount." /- for the quantity ".$order->productQty." of this buyers club.<br>";
                        $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                        $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
                        $data['nMessage'] .= "";
                        $data['nMessage'] .= "Thanks <br> Tidiit Team.";

                        $data['isRead'] = 0;
                        $data['status'] = 1;
                        $data['createDate'] = date('Y-m-d H:i:s');

                        //Send Email message
                        $recv_email = $usr->email;
                        $sender_email = $me->email;
                        
                        $mail_template_view_data=$this->load_default_resources();
                        $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                        $this->_global_tidiit_mail($recv_email,"One buyers club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                        $this->User_model->notification_add($data);
                    endif;
                endforeach;
                $data['receiverId'] = $group->admin->userId;
                //Send Email message
                $recv_email = $group->admin->email;
                $sender_email = $me->email;
                $mail_template_view_data=$this->load_default_resources();
                if(empty($mail_template_data)){
                    $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$me->firstName.' '.$me->lastName;
                    $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                    $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
                }
                $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                $this->_global_tidiit_mail($recv_email,"One buyers club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                $this->User_model->notification_add($data);
            endif;
            
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
     */
    function oredr_default_message(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('group_order/order_default_message',$data);
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
            $this->session->set_flashdata('error', 'Please set your quantity!');
            if($order->parrentOrderID == 0):
                redirect(BASE_URL.'shopping/mod-group-order/'.base64_encode($orderId*226201));
            else:
                redirect(BASE_URL.'shopping/mod-pt-group-order/'.base64_encode($orderId*226201));
            endif;            
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
        $pevorder = $this->Order_model->get_single_order_by_id($orderId);
        if($pevorder->parrentOrderID):
            $availQty = $this->Order_model->get_available_order_quantity($pevorder->parrentOrderID);
        else:
            $availQty = $this->Order_model->get_available_order_quantity($orderId);
        endif;
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
    
    
    
    function process_group_parent_order($orderId, $reorder = false){
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
        $productPriceId = $data['order']->productPriceId;
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            redirect(BASE_URL.'404_override');
        endif;
        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($productPriceId);
        
        
        $a = $this->_get_available_order_quantity($orderId);
        $availQty = $prod_price_info->qty - $a[0]->productQty;
        
        if($prod_price_info->qty == $availQty):
            $this->session->set_flashdata('error', 'This buyers club order process already done. There is no available quantity for you. Please contact your buyers club administrator!');
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        
        if(!$this->User_model->user_exists_on_group($this->session->userdata('FE_SESSION_VAR'),$data['order']->groupId)):
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this buyers club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        //Order first step
        $order_data = array();
        $order_data['orderType'] = 'GROUP';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $productPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        $order_data['parrentOrderID'] = $data['order']->orderId;
        $order_data['groupId'] = $data['order']->groupId;
        $order_data['productQty'] = 0;
        $order_data['userId'] = $this->session->userdata('FE_SESSION_VAR');
        
        $exists_order = $this->Order_model->is_parent_group_order_available($data['order']->orderId, $this->session->userdata('FE_SESSION_VAR'));
        
        if($reorder):
            if($exists_order && $exists_order->status == 0):
                $this->Order_model->update($order_data,$exists_order->orderId);
                $parentOrderId = $exists_order->orderId;
            else:    
                $parentOrderId = $this->Order_model->add($order_data);
            endif;
        else:
            if(!$exists_order):
                $parentOrderId = $this->Order_model->add($order_data);        
            elseif($exists_order && $exists_order->status == 0):
                $this->Order_model->update($order_data,$exists_order->orderId);
                $parentOrderId = $exists_order->orderId;
            else:
                $this->session->set_flashdata('error', 'This buyers club order process already done. Please try to process for new order!');
                redirect(BASE_URL.'shopping/ord-message');
            endif;
        
        endif;
        
        
        //Cart first step        
        $is_cart_update = false;
        $cart = $this->cart->contents();
        if($cart): 
            foreach ($cart as $item):            
                if(($item['id'] == $productId)):
                    $is_cart_update = $item['rowid'];                    
                endif;
            endforeach;
        endif;
        
        $cart_data = array();
        $cart_data['id'] = $productId;
        $cart_data['name'] = $product->title;
        $single_price = ($prod_price_info->price/$prod_price_info->qty);
        $cart_data['price'] = number_format($single_price, 2, '.', '');        
        $cart_data['qty'] = $prod_price_info->qty;        
        $order_data['orderId'] = $parentOrderId;
        $order_data['productPriceId'] = $productPriceId;
        $cart_data['options'] = $order_data;
        
        if($is_cart_update):
            $this->_remove_cart($is_cart_update); //echo $is_cart_update;
            $this->_add_to_cart($cart_data);
        else:
            $this->_add_to_cart($cart_data);
        endif;
        
        redirect(BASE_URL.'shopping/mod-pt-group-order/'.base64_encode($parentOrderId*226201));
    }
    
    /**
     * 
     * @param type $orderId
     */
    function process_my_parent_group_orders_by_id($orderId){
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
        $productPriceId = $data['order']->productPriceId;
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            redirect(BASE_URL.'404_override');
        endif;
        if(!$this->User_model->user_exists_on_group($this->session->userdata('FE_SESSION_VAR'),$data['order']->groupId)):
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this buyers club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        
        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($productPriceId);
        $is_cart_update = false;
        $cart = $this->cart->contents();
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
        $order_data['productPriceId'] = $productPriceId;
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
        $order_data['productPriceId'] = $productPriceId;
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
        $a = $this->_get_available_order_quantity($data['order']->parrentOrderID);
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
        $data['user']=$user;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('group_order/group_order_parent',$data);
    }
    
    
    function order_group_decline_process($orderId){
        if(!$orderId):
            redirect(BASE_URL.'404_override');
        endif;
        $orderId = base64_decode($orderId);
        $orderId = $orderId/226201;        
        
        $user = $this->_get_current_user_details(); 
        
        $order = $this->Order_model->get_single_order_by_id($orderId);
        
        $group = $this->User_model->get_group_by_id($order->groupId);
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($order->productPriceId);
        $a = $this->_get_available_order_quantity($orderId);
        $availQty = $prod_price_info->qty - $a[0]->productQty;
        
        if(!$availQty):
            $this->session->set_flashdata('msg', 'Order already completed by other members of this buyers club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        
        if(!$this->User_model->user_exists_on_group($this->session->userdata('FE_SESSION_VAR'),$order->groupId)):
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this buyers club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        
        
        if($order->parrentOrderID == 0):
            $me = $this->_get_current_user_details();
            foreach($group->users as $key => $usr):
                $mail_template_data=array();
                if($me->userId != $usr->userId):
                    $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                    $data['receiverId'] = $usr->userId;
                    $data['nType'] = 'GROUP-ORDER-DECLINE';
                    $data['nTitle'] = 'Buyers club order [TIDIIT-OD-'.$order->orderId.'] cancel by <b>'.$usr->firstName.' '.$usr->lastName.'</b>';
                    $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID']=$order->orderId;
                    $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ADMIN_NAME']=$usr->firstName.' '.$usr->lastName;
                    $data['nMessage'] = "Hi, <br> Sorry! I can not process this group order right now.<br>";
                    $data['nMessage'] .= "";
                    $data['nMessage'] .= "Thanks <br> Tidiit Team.";

                    $data['isRead'] = 0;
                    $data['status'] = 1;
                    $data['createDate'] = date('Y-m-d H:i:s');

                    //Send Email message
                    $recv_email = $usr->email;
                    $sender_email = $me->email;
                    $mail_template_view_data=$this->load_default_resources();
                    $mail_template_view_data['group_order_decline']=$mail_template_data;
                    $this->_global_tidiit_mail($recv_email, "Buyers club order decline at Tidiit Inc Ltd", $mail_template_view_data,'group_order_decline');
                    
                    $this->User_model->notification_add($data);
                endif;
            endforeach;
            $data['receiverId'] = $group->admin->userId;
            
            unset($data['nMessage']);
            $mail_template_data=array();
            $mail_template_data=array();
            $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
            $data['nType'] = 'GROUP-ORDER-DECLINE';
            $data['nTitle'] = 'Buyers club order [TIDIIT-OD-'.$order->orderId.'] cancel by <b>'.$usr->firstName.' '.$usr->lastName.'</b>';
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID']=$order->orderId;
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ADMIN_NAME']=$usr->firstName.' '.$usr->lastName;
            $data['nMessage'] = "Hi, <br> Sorry! I can not process this order right now.<br>";
            $data['nMessage'] .= "<a href='".BASE_URL."shopping/group-re-order-process/".base64_encode($orderId*226201)."' class='btn btn-warning btn-lg'>Re-order now</a><br><br>";
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID1']=$orderId;
            $data['nMessage'] .= "Thanks <br> Tidiit Team.";
            $data['isRead'] = 0;
            $data['status'] = 1;
            $data['createDate'] = date('Y-m-d H:i:s');
            //Send Email message
            $recv_email = $group->admin->email;
            $sender_email = $me->email;
            $mail_template_view_data=$this->load_default_resources();
            $mail_template_view_data['group_order_decline']=$mail_template_data;
            $this->_global_tidiit_mail($recv_email, "Buyers club order decline at Tidiit Inc Ltd", $mail_template_view_data,'group_order_decline_admin');
            $this->User_model->notification_add($data);
        endif;
        
        $this->session->set_flashdata('msg', 'Thanks for buyers club order cancelation!');
        redirect(BASE_URL.'shopping/ord-message');
        
    }
    
    function order_group_re_order_process($orderId){
        if(!$orderId):
            redirect(BASE_URL.'404_override');
        endif;
        $orderId = base64_decode($orderId);
        $orderId = $orderId/226201;
        
                
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details(); 
        
        $order = $this->Order_model->get_single_order_by_id($orderId);
        if(!$order):
            redirect(BASE_URL.'404_override');
        endif;
        
        if(!$this->User_model->user_exists_on_group($this->session->userdata('FE_SESSION_VAR'),$order->groupId)):
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this buyers club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        
        $group = $this->User_model->get_group_by_id($order->groupId);
        $data['order']= $order;
        $data['group']= $group;
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('group_order/re_order_process',$data);
    }
    
    /**
     * 
     * @param type $orderId
     * @param type $status
     */
    function process_group_parent_re_order($orderId, $status){
        if(!$orderId && $status):
            redirect(BASE_URL.'404_override');
        endif;
        $orderId = base64_decode($orderId);
        $orderId = $orderId/226201;
        
        $status = base64_decode($status);
        $order = $this->Order_model->get_single_order_by_id($orderId);
        
        if(!$order && $status != '100'):
            redirect(BASE_URL.'404_override');
        endif;
        
        if(!$this->User_model->user_exists_on_group($this->session->userdata('FE_SESSION_VAR'),$order->groupId)):
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this buyers club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        
        $this->process_group_parent_order(base64_encode($orderId*226201), true);        
    }
    
    function process_my_single_orders(){
        //print_r($_POST);
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details(); 
        $productId = $this->input->post('productId');
        $productPriceId = $this->input->post('productPriceId');
        
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            redirect(BASE_URL.'404_override');
        endif;
        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($productPriceId);
        $is_cart_update = false;
        $cart = $this->cart->contents();
        if($cart): 
            foreach ($cart as $item):            
                if(($item['id'] == $productId) && ($item['options']['orderType'] == 'SINGLE')):
                    $is_cart_update = $item['rowid'];                    
                endif;
            endforeach;
        endif;
        
        //Order first step
        $order_data = array();
        $order_data['orderType'] = 'SINGLE';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $productPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        
        //Cart first step
        $cart_data = array();
        $cart_data['id'] = $productId;
        $cart_data['name'] = $product->title;
        $single_price = ($prod_price_info->price/$prod_price_info->qty);
        $cart_data['price'] = number_format($single_price, 2, '.', '');
        $cart_data['qty'] = $prod_price_info->qty;
        $order_data['productPriceId'] = $productPriceId;
        $cart_data['options'] = $order_data;
        
        //==============================================//
        if($is_cart_update):            
            $this->_remove_cart($is_cart_update);
            $this->_add_to_cart($cart_data);            
        else:
            $this->_add_to_cart($cart_data);
        endif;        
        
        redirect(BASE_URL.'product/details/'.base64_encode($productId));
    }
    
    function single_order_check_out(){
        if(count($this->cart->contents()) < 1):
            redirect(BASE_URL);
        endif;
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details(); 
        if($this->session->userdata('coupon')):
            $coupon = $this->session->userdata('coupon');
        else:    
            $coupon = new stdClass();
            $coupon->amount = '0.00';
        endif;
        $data['coupon'] = $coupon;
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
        
        $data['userMenuActive']=7; 
        $data['user']=$user;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('single_order/single_order_checkout',$data);
    }
    
    
    /**
     * 
     */
    function remove_single_cart_processing(){
        $cartId = $this->input->post('cartId',TRUE);        
        $this->_remove_cart($cartId);
        if(count($this->cart->contents()) > 0):
            $result['reload'] = true;
        else:
            $this->session->unset_userdata('coupon');
            $result['reload'] = false;
        endif;
        $result['contents'] = true;
	echo json_encode( $result );
	die;
    }
    
    /**
     * 
     */
    function ajax_single_order_set_promo(){
        $promocode = $this->input->post('promocode',TRUE);
        
        $coupon = $this->Order_model->is_coupon_code_exists($promocode);
        if(!$coupon):
            $result['error'] = "Invalid promo code!";
            echo json_encode( $result );
            die;
        endif;
        
        $ordercoupon = $this->Order_model->is_coupon_code_used_or_not_for_single($coupon);
        
        if($ordercoupon):
            $result['error'] = "Promo code already used!";
            echo json_encode( $result );
            die;
        else:
            $data = array();
            $ctotal = $this->cart->total();
            if($coupon->type == 'percentage'):
                $amt = ($coupon->amount/100)*$ctotal;
                $amt1 = number_format($amt, 2, '.', '');
                $data['amount'] = substr($amt1, 0, -3);
            elseif($coupon->type == 'fix'):
                $data['amount'] = $coupon->amount;
            endif;
            $data['orderAmount'] = $ctotal - $coupon->amount;
            
            
            $cpn = new stdClass();
            $cpn->amount = $coupon->amount;
            $cpn->couponId = $coupon->couponId;
            $this->session->set_userdata('coupon', $cpn);
            $result['msg'] = "Promo code has been applied successfully!";
            $result['content'] = $data;
            echo json_encode( $result );
            die;
        endif;
	
    }
    
    /**
     * 
     */
    function ajax_process_single_payment(){
        $user = $this->_get_current_user_details(); 
        $cart = $this->cart->contents();
        $order = array();
        $orderid = array();
        if($this->session->userdata('coupon')):
            $coupon = $this->session->userdata('coupon');
        else:    
            $coupon = new stdClass();
            $coupon->amount = 0;
            $coupon->couponId = 0;
        endif;
        
        $totalsingleitem  = 0;
        foreach ($this->cart->contents() as $citem):            
            if($citem['options']['orderType'] == 'SINGLE'):
                $totalsingleitem = $totalsingleitem + 1;                    
            endif;
        endforeach;
        
        $coupon_price = 0;
        if($coupon->couponId && $totalsingleitem > 1):
            $coupon_price = $coupon->amount / $totalsingleitem;
        elseif($coupon->couponId && $totalsingleitem == 1):
            $coupon_price = $coupon->amount;            
        endif;
        
        foreach ($cart as $item):  
            if($item['options']['orderType'] == 'SINGLE'):
                $order['orderType'] = 'SINGLE';
                $order['productId'] = $item['options']['productId'];
                $order['productPriceId'] = $item['options']['productPriceId'];
                $order['orderDate'] = date('Y-m-d H:i:s');
                $order['orderUpdatedate'] = date('Y-m-d H:i:s');
                $order['productQty'] = $item['qty'];
                $order['userId'] = $this->session->userdata('FE_SESSION_VAR');
                $order['orderAmount'] = $item['subtotal'] - $coupon_price;
                $order['subTotalAmount'] = $item['subtotal'];   
                $order['discountAmount'] = $coupon_price;
                $order['status'] = 2; 

                $orderinfo = array();
                $mail_template_data = array();
                $pro = $this->Product_model->details($order['productId']);
                $orderinfo['pdetail'] = $pro[0];
                $orderinfo['priceinfo'] = $this->Product_model->get_products_price_details_by_id($order['productPriceId']);
                $productImageArr =$this->Product_model->get_products_images($order['productId']);
                $orderinfo['pimage'] = $productImageArr[0];            

                $userShippingDataDetails = $this->User_model->get_user_shipping_information();
                $orderinfo['shipping'] = $userShippingDataDetails[0];
                $userBillingDataDetails=$this->User_model->get_billing_address();
                $orderinfo['billing'] = $userBillingDataDetails[0];
                $order['orderInfo'] = base64_encode(serialize($orderinfo));
                $orderId = $this->Order_model->add($order);
                $orderinfo['orderId']=$orderId;
                $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
                $orderid['orderId'] = $orderId;
                $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$orderId;
                if($coupon->couponId):
                    $cdata = array();
                    $cdata['orderId'] = $orderId;
                    $cdata['couponId'] = $coupon->couponId;
                    $cdata['amount'] = $coupon_price;
                    $this->Order_model->tidiit_creat_order_coupon($cdata);
                endif;
                $this->_remove_cart($item['rowid']);
                
                //Send Email message
                $recv_email = $user->email;
                if($orderid):
                    $mail_template_view_data=$this->load_default_resources();
                    $mail_template_view_data['single_order_success']=$mail_template_data;
                    $this->_global_tidiit_mail($recv_email, "Confirmation mail for your Tidiit order no - TIDIIT-OD-".$orderId, $mail_template_view_data,'single_order_success');
                endif;
            endif;
        endforeach;
        
        if($orderid):
            $result['url'] = BASE_URL.'shopping/success/';
            echo json_encode( $result );
            die;                    
        else:
            $result['error'] = "Some error happen, please try again later!";
            echo json_encode( $result );
            die;
        endif;
    }
    
    
    function show_my_cart(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details();         
        $data['user']= $user;
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('my_carts',$data);
    }
}