<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Razorpay\Api\Api;

class Shopping extends MY_Controller{

    public function __construct(){
        parent::__construct();
        session_start();
        $this->_isLoggedIn();
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Coupon_model');
        $this->db->cache_off();
        $this->load->library('cart');
        $this->load->model('Category_model');
        $this->load->model('Product_model');
        $this->load->model('Country');
        $this->load->library('session');
        $this->load->library('tidiitrcode');
    }

    /**
     *
     */
    function process_my_group_orders(){
        $this->session->unset_userdata('razorpayPaymentId');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details();
        $cart = $this->Order_model->tidiit_get_user_orders($user->userId, 0);//print_r($cart);die;//$user->userId
        $productId = $this->input->post('productId');
        $productPriceId = $this->input->post('productPriceId');
        if((isset($productId) && !$productId) && (isset($productPriceId) && !$productPriceId)):
            redirect(BASE_URL.'404_override');
        endif;
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($productPriceId);
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
        $pro = $this->Product_model->details($productId);
        $orderinfo['pdetail'] = $pro[0];
        $orderinfo['priceinfo'] = $prod_price_info;
        $productImageArr = $this->Product_model->get_products_images($productId);
        $orderinfo['pimage'] = $productImageArr[0];

        $country_name = $this->session->userdata('FE_SESSION_USER_LOCATION_VAR');
        $taxDetails = $this->Product_model->get_tax_for_current_location($productId, $country_name.'_tax');
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
            $orderId = $this->Order_model->add($order_data);
            $data['orderId']=$orderId;
            $params=array();
            $params['data'] = $orderId;
            $params['savename'] = $this->config->item('ResourcesPath').'qr_code/'.$qrCodeFileName;
            $this->tidiitrcode->generate($params);
        endif;

        $order_data['orderId'] = $data['orderId'];
        $order_data['productPriceId'] = $productPriceId;
        $data['order'] = $this->Order_model->get_single_order_by_id($data['orderId']);
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
                $this->Order_model->update($order_update,$data['orderId']);
            endif;
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
        $my_groups = $this->User_model->get_my_groups();
        $data['CatArr'] = $this->_get_user_select_cat_array();
        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        $data['userMenuActive']=7;
        $data['countryDataArr'] = $this->Country->get_all1();
        $data['myGroups']=$my_groups;
        $data['user']=$user;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
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
        $data['orderId'] = $data['order']->orderId;
        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($productPriceId);
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

        $my_groups = $this->User_model->get_my_groups();
        $data['CatArr'] = $this->_get_user_select_cat_array();
        $data['dftQty'] = $prod_price_info->qty - $a[0]->productQty;
        $data['totalQty'] = $prod_price_info->qty;
        $data['priceInfo'] = $prod_price_info;
        $data['userMenuActive']=7;
        $data['countryDataArr']=$this->Country->get_all1();
        $data['myGroups'] = $my_groups;
        $data['user'] = $user;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('group_order/group_order',$data);
    }

    /**
     *
     */
    function ajax_update_group_order(){
        $orderId = $this->input->post('orderId',TRUE);
        $qty = $this->input->post('qty',TRUE);
        $order = $this->Order_model->get_single_order_by_id($orderId);
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($order->productPriceId);

        $single_price = ($prod_price_info->price/$prod_price_info->qty);
        $price = number_format($single_price, 2, '.', '');
        $totalprice = number_format($price*$qty, 2, '.', '');


        $country_name = $this->session->userdata('FE_SESSION_USER_LOCATION_VAR');
        $taxDetails = $this->Product_model->get_tax_for_current_location($order->productId, $country_name.'_tax');
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

        $coupon = $this->Coupon_model->is_coupon_code_exists($promocode);
        if(!$coupon):
            $result['error'] = "Invalid promo code!";
            echo json_encode( $result );
            die;
        endif;

        $ordercoupon = $this->Coupon_model->is_coupon_code_used_or_not($coupon, $orderId);

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
        $cartId = $orderId;
        $paymentOption = $this->input->post('paymentoption',TRUE);
        
        if($paymentOption==""){
            $this->session->set_flashdata("message","Invalid payment option selected!");
            redirect(BASE_URL.'shopping/my-cart');
        }
        
        $pevorder = $this->Order_model->get_single_order_by_id($orderId);
        $a = $this->_get_available_order_quantity($orderId);

        $prod_price_info = $this->Product_model->get_products_price_details_by_id($pevorder->productPriceId);

        if($prod_price_info->qty == $a[0]->productQty):
            if($paymentOption=='sod'):
                $order_update['status'] = 2;
            else:
                $order_update['status'] = 8;
            endif;
        else:
            if($paymentOption=='sod'):
                $order_update['status'] = 1;
            else:
                $order_update['status'] = 8;
            endif;
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
            //$userBillingDataDetails=$this->User_model->get_billing_address();
            //$orderinfo['billing'] = $userBillingDataDetails[0];

            $group = $this->User_model->get_group_by_id($order->groupId);

            if($order->groupId):
                $orderinfo['group'] = $group;
            endif;
            //echo '$paymentOption : '.$paymentOption;
            $info['orderInfo'] = base64_encode(serialize($orderinfo));
            $this->Order_model->update($info, $orderId);
            $allOrderArray=array();
            $paymentGatewayAmount=$order->orderAmount;
            $me = $this->_get_current_user_details();
            if($paymentOption=='sod'):
                if($order->parrentOrderID == 0):
                    $this->Product_model->update_product_quantity($prod_price_info->productId,$prod_price_info->qty);
                    foreach($group->users as $key => $usr):
                        $mail_template_data=array();
                        $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                        $data['receiverId'] = $usr->userId;
                        $data['nType'] = 'BUYING-CLUB-ORDER';

                        $data['nTitle'] = 'New Buying Club order running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';
                        $mail_template_data['TEMPLATE_GROUP_ORDER_START_TITLE']=$group->admin->firstName.' '.$group->admin->lastName;
                        $data['nMessage'] = "Hi, <br> You have requested to buy Buying Club[".$group->groupTitle."] order product.<br>";
                        $data['nMessage'] .= "Product is <a href=''>".$orderinfo['pdetail']->title."</a><br>";
                        $mail_template_data['TEMPLATE_GROUP_ORDER_START_PRODUCT_TITLE']=$orderinfo['pdetail']->title;
                        $data['nMessage'] .= "Want to process the order ? <br>";
                        $data['nMessage'] .= "<a href='".BASE_URL."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".BASE_URL."shopping/group-order-accept-process/".base64_encode($orderId*226201)."' class='btn btn-success btn-lg'>Accept</a><br>";
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

                        $mail_template_view_data=$this->load_default_resources();
                        $mail_template_view_data['group_order_start']=$mail_template_data;
                        $this->_global_tidiit_mail($recv_email, "New Buying Club Order Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_start');
                        //pre($data);die;
                        $this->User_model->notification_add($data);

                        /// sendin SMS to allmember
                        $sms_data=array('nMessage'=>'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.BASE_URL,
                            'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$usr->userId,
                            'senderMobileNumber'=>$group->admin->mobile,'nType'=>'CREATE-'.$data['nType']);
                        send_sms_notification($sms_data);

                    endforeach;
                else:
                    $mail_template_data=array();
                    foreach($group->users as $key => $usr):
                        if($me->userId != $usr->userId):
                            $mail_template_data=array();
                            $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
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

                            //Send Email message
                            $recv_email = $usr->email;
                            $sender_email = $me->email;

                            $mail_template_view_data=$this->load_default_resources();
                            $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                            $this->_global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                            $this->User_model->notification_add($data);

                            /// sendin SMS to allmember
                            $sms_data=array('nMessage'=>$usr->firstName.' '.$usr->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.BASE_URL,
                                'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$data['receiverId'],
                                'senderMobileNumber'=>$group->admin->mobile,'nType'=>$data['nType']);
                            send_sms_notification($sms_data);
                        endif;
                    endforeach;
                    $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                    $data['receiverId'] = $group->admin->userId;
                    $data['nType'] = 'BUYING-CLUB-ORDER-CONTINUE';
                    $data['nTitle'] = 'Your Buying Club['.$group->groupTitle.'] order continue by <b>'.$me->firstName.' '.$me->lastName.'</b>';
                    $data['nMessage'] = "Hi, <br> I have paid Rs. ".$order->orderAmount." /- for the quantity ".$order->productQty." of this Buying Club[".$group->groupTitle."].<br>";
                    $data['nMessage'] .= 'Order item is '.$orderinfo['pdetail']->title."<br /><br/>";
                    $data['nMessage'] .= "Thanks <br> Tidiit Team.";
                    $data['isRead'] = 0;
                    $data['status'] = 1;
                    $data['createDate'] = date('Y-m-d H:i:s');

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
                    $this->_global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');

                    $this->User_model->notification_add($data);

                    $sms_data=array('nMessage'=>$me->firstName.' '.$me->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of your Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.BASE_URL,
                        'receiverMobileNumber'=>$group->admin->mobile,'senderId'=>'','receiverId'=>$data['receiverId'],
                        'senderMobileNumber'=>$me->mobile,'nType'=>"BUYING-CLUB-ORDER-INVITED-MEMBER-COMPLETE");
                    send_sms_notification($sms_data);
                endif;
            else:
                $paymentDataArr = array('orders'=>$orderId,'orderType'=>'group','paymentGatewayAmount'=>$paymentGatewayAmount,'orderInfo'=>$orderinfo,'group'=>$group,'pevorder'=>$pevorder,'aProductQty'=>$a[0]->productQty,'prod_price_info'=>$prod_price_info,'order'=>$order,'cartId'=>$cartId,'final_return'=>'no');
                //pre($paymentDataArr);die;
                $_SESSION['PaymentData'] = $paymentDataArr;
                //pre($_SESSION);
                //die;
            endif;
            
            if($order_update['status'] == 2):
                $this->_sent_order_complete_mail($order);
            endif;
            //echo '$paymentOption : '.$paymentOption;die;
            if($paymentOption=='sod'):
                $settlementOnDeliveryId=$this->Order_model->add_sod(array('IP'=>$this->input->ip_address,'userId'=>$this->session->userdata('FE_SESSION_VAR')));
                $this->Order_model->add_payment(array('orderId'=>$orderId,'paymentType'=>'settlementOnDelivery','settlementOnDeliveryId'=>$settlementOnDeliveryId,'orderType'=>'group'));
                //$this->_remove_cart($cartId);
                redirect(BASE_URL.'shopping/success/');
            elseif($paymentOption=='payment_razorpay'):
                //pre($_SESSION);die;
                $this->_razorpay_process(array($orderId));
            elseif($paymentOption=='mpesa'):
                $this->_mpesa_process(array($orderId));
            endif;
        else:
            $this->session->set_flashdata("message","Some error happen, please try again later!");
            redirect(BASE_URL.'shopping/my-cart');
        endif;
    }

    /**
     *
     */
    function oredr_process_success(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('group_order/group_order_success',$data);
    }

    /**
     *
     */
    function oredr_default_message(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
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
        $data = $this->_get_logedin_template($SEODataArr);
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

        $coupon = $this->Order_model->get_order_coupon($orderId);
        $data['coupon'] = $coupon;
        $data['paymentGatewayData']=$this->Order_model->get_all_gateway();
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
        $user=  $this->_get_current_user_details();
        $cartId = $this->input->post('cartId',TRUE);
        $this->Order_model->remove_order_from_cart($cartId,$user->userId);
        $data=array();
        $data['status'] = 0;
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
            $this->session->set_flashdata('error', 'This Buying Club order process already done. There is no available quantity for you. Please contact your Buying Club Leader!');
            redirect(BASE_URL.'shopping/ord-message');
        endif;

        if(!$this->User_model->user_exists_on_group($this->session->userdata('FE_SESSION_VAR'),$data['order']->groupId)):
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this Buying Club.');
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

        $exists_order=false;
        //$exists_order = $this->Order_model->is_parent_group_order_available($data['order']->orderId, $user->userId);

        $orderinfo = [];
        $orderinfo['pdetail'] = $product;
        $orderinfo['priceinfo'] = $prod_price_info;
        $productImageArr = $this->Product_model->get_products_images($productId);
        $orderinfo['pimage'] = $productImageArr[0];
        $order_data['orderInfo'] = base64_encode(serialize($orderinfo));
        if($reorder):
            if($exists_order && $exists_order->status == 0):
                $this->Order_model->update($order_data,$exists_order->orderId);
                $parentOrderId = $exists_order->orderId;
            else:
                //$parentOrderId = $this->Order_model->add($order_data);
                $qrCodeFileName=time().'-'.rand(1, 50).'.png';
                $order_data['qrCodeImageFile']=$qrCodeFileName;
                $order_data['IP']=  $this->input->ip_address();
                $qrCodeOrderId=$this->Order_model->add($order_data);
                $parentOrderId=$qrCodeOrderId;
                $params=array();
                $params['data']=$qrCodeOrderId;
                $params['savename']=$this->config->item('ResourcesPath').'qr_code/'.$qrCodeFileName;
                $this->tidiitrcode->generate($params);
            endif;
        else:
            if(!$exists_order):
                $qrCodeFileName=time().'-'.rand(1, 50).'.png';
                $order_data['qrCodeImageFile']=$qrCodeFileName;
                $order_data['IP']=  $this->input->ip_address();
                $qrCodeOrderId=$this->Order_model->add($order_data);
                $parentOrderId=$qrCodeOrderId;
                $params=array();
                $params['data']=$qrCodeOrderId;
                $params['savename']=$this->config->item('ResourcesPath').'qr_code/'.$qrCodeFileName;
                $this->tidiitrcode->generate($params);
            elseif($exists_order && $exists_order->status == 0):
                $this->Order_model->update($order_data,$exists_order->orderId);
                $parentOrderId = $exists_order->orderId;
            else:
                $this->session->set_flashdata('error', 'This Buying Club order process already done. Please try to process for new order!');
                redirect(BASE_URL.'shopping/ord-message');
            endif;

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
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this Buying Club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;

        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($productPriceId);
        $is_cart_update = false;
        $cart = $this->Order_model->tidiit_get_user_orders($user->userId, 0);
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
        $productImageArr = $this->Product_model->get_products_images($productId);
        $orderinfo['pimage'] = $productImageArr[0];
        $order_data['orderInfo'] = base64_encode(serialize($orderinfo));

        if(!isset($data['orderId'])):
            $order_data['productQty'] = 0;
            //$data['orderId']=$this->Order_model->add($order_data);
            $qrCodeFileName=time().'-'.rand(1, 50).'.png';
            $order_data['qrCodeImageFile']=$qrCodeFileName;
            $order_data['IP']=  $this->input->ip_address();
            $orderId=$this->Order_model->add($order_data);
            $data['orderId']=$orderId;
            $params=array();
            $params['data']=$orderId;
            $params['savename']=$this->config->item('ResourcesPath').'qr_code/'.$qrCodeFileName;
            $this->tidiitrcode->generate($params);
        endif;

        //$data['orderId'] = 0;
        $order_data['orderId'] = $data['orderId'];
        $order_data['productPriceId'] = $productPriceId;
        $data['order'] = $this->Order_model->get_single_order_by_id($data['orderId']);
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
                $this->Order_model->update($order_update,$data['orderId']);
            endif;
        endif;

        //==============================================//

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
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
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
        $orderInfo = unserialize(base64_decode($order->orderInfo));

        if(!$availQty):
            $this->session->set_flashdata('msg', 'Order already completed by other members of this Buying Club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;

        if(!$this->User_model->user_exists_on_group($this->session->userdata('FE_SESSION_VAR'),$order->groupId)):
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this Buying Club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;


        if($order->parrentOrderID == 0):
            $me = $this->_get_current_user_details();
            foreach($group->users as $key => $usr):
                $mail_template_data=array();
                if($me->userId != $usr->userId):
                    $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
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

                    //Send Email message
                    $recv_email = $usr->email;
                    $sender_email = $me->email;
                    $mail_template_view_data=$this->load_default_resources();
                    $mail_template_view_data['group_order_decline']=$mail_template_data;
                    $this->_global_tidiit_mail($recv_email, "Buying Club order decline at Tidiit Inc Ltd", $mail_template_view_data,'group_order_decline',$usr->firstName.' '.$usr->lastName);

                    $this->User_model->notification_add($data);
                endif;
            endforeach;
            $data['receiverId'] = $group->admin->userId;

            unset($data['nMessage']);
            $mail_template_data=array();
            $mail_template_data=array();
            $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
            $data['nType'] = 'BUYING-CLUB-ORDER-DECLINE';
            $data['nTitle'] = 'Buying Club order [TIDIIT-OD-'.$order->orderId.'] cancel by <b>'.$me->firstName.' '.$me->lastName.'</b>';
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ORDER_ID']=$order->orderId;
            $mail_template_data['TEMPLATE_GROUP_ORDER_DECLINE_ADMIN_NAME']=$me->firstName.' '.$me->lastName;
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
            $this->_global_tidiit_mail($recv_email, "Buying Club order decline at Tidiit Inc Ltd", $mail_template_view_data,'group_order_decline_admin',$group->admin->firstName.' '.$group->admin->lastName);
            $this->User_model->notification_add($data);

            /// sendin SMS to Leader
            $smsMsg='Buying Club['.$group->groupTitle.']  member['.$usr->firstName.' '.$usr->lastName.'] has decline the invitation Tidiit order TIDIIT-OD-'.$order->orderId.'.';
            $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$orderInfo['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfo["group"]->admin->userId,
                'senderMobileNumber'=>'','nType'=>$data['nType']);
            send_sms_notification($sms_data);
        endif;

        $this->session->set_flashdata('msg', 'Sorry for Buying Club order cancelation!');
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
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this Buying Club.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;

        $group = $this->User_model->get_group_by_id($order->groupId);
        $data['order']= $order;
        $data['group']= $group;
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
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
            $this->session->set_flashdata('error', 'You can not process this order because you are not member of this Buying Club.');
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

        //$cartDataArr=array('productId'=>$productId,'userId'=>$user->userId,'productPriceId'=>$productPriceId);
        $product = $this->Product_model->details($productId);
        $product = $product[0];
        $prod_price_info = $this->Product_model->get_products_price_details_by_id($productPriceId);
        //$is_cart_update = false;
        $countryShortName=$this->session->userdata('FE_SESSION_USER_LOCATION_VAR');
        $currentLocationTaxDetails=$this->Product_model->get_tax_for_current_location($productId,$countryShortName.'_tax');
        $taxCol=$countryShortName.'_tax';
        $taxPercentage=$currentLocationTaxDetails->$taxCol;
        $tax=$prod_price_info->price*$taxPercentage/100;

        $order_data = array();
        $order_data['orderType'] = 'SINGLE';
        $order_data['productId'] = $productId;
        $order_data['productPriceId'] = $productPriceId;
        $order_data['orderDate'] = date('Y-m-d H:i:s');
        $order_data['status'] = 0;
        $order_data['userId'] = $user->userId;
        $order_data['productQty'] = $prod_price_info->qty;
        $order_data['subTotalAmount'] = $prod_price_info->price;
        $order_data['taxAmount'] = $tax;
        $order_data['orderAmount'] = $prod_price_info->price+$tax;
        $order_data['orderDevicetype'] = 1;

        $orderinfo = array();
        $orderinfo['pdetail'] = $product;
        $orderinfo['priceinfo']=$prod_price_info;
        $productImageArr =$this->Product_model->get_products_images($productId);
        $orderinfo['pimage'] = $productImageArr[0];
        $userShippingDataDetails = $this->User_model->get_user_shipping_information($user->userId,TRUE);
        $orderinfo['shipping'] = $userShippingDataDetails[0];
        //pre($order_data);die;
        $order_data['orderInfo'] = base64_encode(serialize($orderinfo));

        $qrCodeFileName=time().'-'.rand(1, 50).'.png';
        $order_data['qrCodeImageFile']=$qrCodeFileName;
        $order_data['IP']=  $this->input->ip_address();
        $orderId=$this->Order_model->add($order_data);
        //$data['orderId']=$orderId;
        $params=array();
        $params['data']=$orderId;
        $params['savename']=$this->config->item('ResourcesPath').'qr_code/'.$qrCodeFileName;
        $this->tidiitrcode->generate($params);
        $this->session->set_userdata('TotalItemInCart',$this->User_model->get_total_cart_item($user->userId));
        //return $orderId.'-'.$qrCodeFileName;        

        //redirect(BASE_URL.'product/details/'.base64_encode($productId));
        redirect(BASE_URL.'shopping/my-cart');
    }

    function single_order_check_out(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details();
        $allItemArr=$this->Order_model->get_all_cart_item($user->userId,'single');
        if(count($allItemArr) < 1):
            redirect(BASE_URL);
        endif;

        foreach($allItemArr AS $k){
            $orderInfo=  unserialize(base64_decode($k['orderInfo']));
            $k['productTitle']=$orderInfo['pdetail']->title;
            $k['qty']=$orderInfo['priceinfo']->qty;
            $k['pimage']=$orderInfo['pimage']->image;
            $newAllItemArr[]=$k;
        }
        $data['allItemArr']=$newAllItemArr;
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
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $data['paymentGatewayData']=$this->Order_model->get_all_gateway();
        $this->load->view('single_order/single_order_checkout',$data);
    }

    /**
     *
     */
    function remove_single_cart_processing(){
        $user=  $this->_get_current_user_details();
        $cartId = $this->input->post('cartId',TRUE);
        $this->Order_model->remove_order_from_cart($cartId,$user->userId);

        $result['contents'] = true;
        echo json_encode( $result );
        die;
    }

    /**
     *
     */
    function ajax_single_order_set_promo(){
        $promocode = $this->input->post('promocode',TRUE);
        $orderId = $this->input->post('orderId',TRUE);

        $coupon = $this->Coupon_model->is_coupon_code_exists($promocode);
        if(!$coupon):
            $result['error'] = "Invalid promo code or promo code has expaired!";
            echo json_encode( $result );
            die;
        endif;

        $ordercoupon = $this->Coupon_model->is_coupon_code_valid_for_single($coupon);

        if($ordercoupon):
            $result['error'] = "Promo code has expired.";
            echo json_encode( $result );
            die;
        else:
            $data = array();
            $user = $this->_get_current_user_details();
            //$ctotal = $this->cart->total();
            $allItemArr=$this->Order_model->get_all_cart_item($user->userId,'single');
            $orderIdArr=array();
            foreach($allItemArr As $k){
                $orderIdArr[]=$k['orderId'];
            }
            if($this->Coupon_model->is_coupon_recently_used($orderIdArr,$coupon->couponId)==TRUE){
                $result['error'] = "Promo code has alrady used in your current session.";
                echo json_encode( $result );
                die;
            }
            $orderDetails=$this->Order_model->details($orderId);
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
            $countryShortName=$this->session->userdata('FE_SESSION_USER_LOCATION_VAR');
            foreach($allItemArr AS $k){
                if($k['orderId']==$orderId){
                    $currentLocationTaxDetails=$this->Product_model->get_tax_for_current_location($k['productId'],$countryShortName.'_tax');
                    $taxCol=$countryShortName.'_tax';
                    $taxPercentage=$currentLocationTaxDetails->$taxCol;
                    $orderAmountBeforeTax=$k['subTotalAmount']-$data['couponAmount'];
                    $cTax=$orderAmountBeforeTax*$taxPercentage/100;
                    $orderAmount=$orderAmountBeforeTax+$cTax;
                    $orderDataArr=array('taxAmount'=>$cTax,'discountAmount'=>$data['couponAmount'],'orderAmount'=>$orderAmount);
                    $this->Order_model->update($orderDataArr,$k['orderId']);
                    $this->Order_model->tidiit_creat_order_coupon(array('orderId'=>$k['orderId'],'couponId'=>$coupon->couponId,'amount'=>$data['couponAmount']));
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

            $result['msg'] = "Promo code has been applied successfully!";
            $data['couponAmount'] = number_format(round($couponAmount,0,PHP_ROUND_HALF_UP),2);
            $result['content'] = $data;
            echo json_encode( $result );
            die;
        endif;

    }

    /**
     *
     */
    function ajax_process_single_payment_start(){
        /*$sms_data=array('nMessage'=>'comming to ajax_process_single_payment_start FUN at '.time().' $_POST[\'paymentoption\'] : '.$_POST['paymentoption'].' at line number 1196',
                'receiverMobileNumber'=>'9556644964','senderId'=>'','receiverId'=>100,
                'senderMobileNumber'=>'','nType'=>'TESTING');
            send_sms_notification($sms_data);*/
        //pre($_POST);
        $paymentType = $this->input->post('paymentoption');
        //pre($_POST);
        //die;
        if($paymentType == ""){
            //die($paymentType.' kkk');
            $this->session->set_flashdata("message","Invalid payment option selected!");
            redirect(BASE_URL.'shopping/my-cart');
        }
        
        
        $user = $this->_get_current_user_details();
        /*$sms_data=array('nMessage'=>'comming to ajax_process_single_payment_start FUN at '.time().' $paymentType '.$paymentType.' at line number 1213',
                'receiverMobileNumber'=>'9556644964','senderId'=>'','receiverId'=>100,
                'senderMobileNumber'=>'','nType'=>'TESTING');
            send_sms_notification($sms_data);*/
        //$cart = $this->cart->contents();
        $allItemArr=$this->Order_model->get_all_cart_item($user->userId,'single');

        if(empty($allItemArr)){
            //die('empty item');
            $this->session->set_flashdata("message","There is not item in truck for payment.");
            redirect(BASE_URL.'shopping/my-cart');
        }
        
        $order = array();
        $orderid = array();

        $totalsingleitem  = count($allItemArr);
        //echo '$totalsingleitem : '.$totalsingleitem;die;
        //foreach ($allItemArr as $k):
        //if($citem['options']['orderType'] == 'SINGLE'):
        //$totalsingleitem = $totalsingleitem + 1;
        //endif;
        //endforeach;
        
        //$countryShortName=$this->session->userdata('FE_SESSION_USER_LOCATION_VAR');
        //echo $countryShortName;die;
        /*$sms_data=array('nMessage'=>'comming to ajax_process_single_payment_start FUN at '.time().' $paymentType '.$paymentType.' at line number 1239',
                'receiverMobileNumber'=>'9556644964','senderId'=>'','receiverId'=>100,
                'senderMobileNumber'=>'','nType'=>'TESTING');
            send_sms_notification($sms_data);*/
        $paymentGatewayAmount=0;
        $allOrderArray=array();
        $allOrderInfoArray=array();
        foreach ($allItemArr as $kOrder):
            $order=array();
            $paymentGatewayAmount+=$kOrder['orderAmount'];
            if($paymentType=='sod')
                $order['status'] = 2;
            else
                $order['status'] = 8;

            $orderinfo = array();
            $mail_template_data = array();
            $pro = $this->Product_model->details($kOrder['productId']);
            $orderinfo['pdetail'] = $pro[0];
            $orderinfo['priceinfo'] = $this->Product_model->get_products_price_details_by_id($kOrder['productPriceId']);
            $productImageArr =$this->Product_model->get_products_images($kOrder['productId']);
            $orderinfo['pimage'] = $productImageArr[0];
            $orderinfo['tax']=$kOrder['taxAmount'];
            $orderinfo['discountAmount']=$kOrder['discountAmount'];

            $userShippingDataDetails = $this->User_model->get_user_shipping_information();
            $orderinfo['shipping'] = $userShippingDataDetails[0];
            //$userBillingDataDetails=$this->User_model->get_billing_address();
            //$orderinfo['billing'] = $userBillingDataDetails[0];
            $order['orderInfo'] = base64_encode(serialize($orderinfo));
            //$orderId = $this->Order_model->add($order);
            $this->Order_model->update($order,$kOrder['orderId']);
            $orderinfo['orderId']=$kOrder['orderId'];
            $allOrderArray[]=$kOrder['orderId'];

            $orderid['orderId'] = $kOrder['orderId'];

            if($paymentType=='sod'):
                $this->Product_model->update_product_quantity($kOrder['productId'],$kOrder['productQty']);
                $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
                $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$kOrder['orderId'];
            endif;
            $allOrderInfoArray[$kOrder['orderId']]['orderInfo']=$orderinfo;
            $allOrderInfoArray[$kOrder['orderId']]['order']=$kOrder;
            $allOrderInfoArray[$kOrder['orderId']]['cartId']=$kOrder['orderId'];
            //Send Email message
            $recv_email = $user->email;
            if($orderid):
                if($paymentType=='sod'):
                    $settlementOnDeliveryId=$this->Order_model->add_sod(array('IP'=>$this->input->ip_address,'userId'=>$this->session->userdata('FE_SESSION_VAR')));
                    $this->Order_model->add_payment(array('orderId'=>$kOrder['orderId'],'paymentType'=>'settlementOnDelivery','settlementOnDeliveryId'=>$settlementOnDeliveryId,'orderType'=>'single'));
                    $mail_template_view_data=$this->load_default_resources();
                    $mail_template_view_data['single_order_success']=$mail_template_data;
                    $this->_global_tidiit_mail($recv_email, "Your Tidiit order - TIDIIT-OD-".$kOrder['orderId'].' has placed successfully', $mail_template_view_data,'single_order_success');
                    $this->_sent_single_order_complete_mail($kOrder['orderId']);
                endif;
            endif;
        endforeach;

        if(!empty($allOrderArray)):
            /*$sms_data=array('nMessage'=>'comming to ajax_process_single_payment_start FUN at '.time().' $paymentType '.$paymentType.' at line number 1299',
                'receiverMobileNumber'=>'9556644964','senderId'=>'','receiverId'=>100,
                'senderMobileNumber'=>'','nType'=>'TESTING');
            send_sms_notification($sms_data);*/
            if($paymentType=='sod'):
                redirect(BASE_URL.'shopping/success/');
            elseif($paymentType=='payment_razorpay'):
                $paymentDataArr=array('orders'=>$allOrderArray,'orderType'=>'single','paymentGatewayAmount'=>$paymentGatewayAmount,'orderInfo'=>$allOrderInfoArray,'final_return'=>'no');
                $_SESSION['PaymentData'] = $paymentDataArr;
                /*$sms_data=array('nMessage'=>'comming to ajax_process_single_payment_start FUN at '.time().' $paymentType '.$paymentType.' at line number 1308',
                'receiverMobileNumber'=>'9556644964','senderId'=>'','receiverId'=>100,
                'senderMobileNumber'=>'','nType'=>'TESTING');
            send_sms_notification($sms_data);*/
                $this->_razorpay_process($allOrderArray);
            elseif($paymentType=='ebs'):
                $paymentDataArr=array('orders'=>$allOrderArray,'orderType'=>'single','paymentGatewayAmount'=>$paymentGatewayAmount,'orderInfo'=>$allOrderInfoArray,'final_return'=>'no');
                $_SESSION['PaymentData'] = $paymentDataArr;
                $this->_razorpay_process($allOrderArray);
            elseif($paymentType=='mpesa'):
                $paymentDataArr=array('orders'=>$allOrderArray,'orderType'=>'single','paymentGatewayAmount'=>$paymentGatewayAmount,'orderInfo'=>$allOrderInfoArray,'final_return'=>'no');
                $_SESSION['PaymentData'] = $paymentDataArr;
                $this->_mpesa_process($allOrderArray);
            endif;
        else:
            $this->session->set_flashdata("message","Some error happen, please try again later!");
            redirect(BASE_URL.'shopping/my-cart');
        endif;
    }


    function show_my_cart(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details();

        $allItemArr=$this->Order_model->get_all_cart_item($user->userId);
        $newAllItemArr=array();
        foreach($allItemArr AS $k){
            $orderInfo=  unserialize(base64_decode($k['orderInfo']));
            //pre($orderInfo);
            $k['productTitle']=$orderInfo['pdetail']->title;
            $k['qty']=$orderInfo['priceinfo']->qty;
            $k['pimage']=$orderInfo['pimage']->image;
            $newAllItemArr[]=$k;
        }
        //pre($newAllItemArr);die;
        $data['allItemArr']=$newAllItemArr;
        $data['user']= $user;
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('my/my_carts',$data);
    }

    function view_order_details($orderId){
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

        if($order->userId != $this->session->userdata('FE_SESSION_VAR')):
            $this->session->set_flashdata('error', 'You can not view this order because you are not enough permission.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;

        if($order->groupId):
            $group = $this->User_model->get_group_by_id($order->groupId);
            $data['group']= $group;
        else:
            $data['group']= false;
        endif;
        $data['order']= $order;
        $orderStatusobj=$this->Order_model->get_state();
        $stateArr=array();
        foreach($orderStatusobj As $k){
            $stateArr[$k->orderStateId]=$k->name;
        }
        $data['status']= $stateArr;
        $data['order']= $order;
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('my/my_order_details',$data);
    }

    /*
     *
     * send mail to Buying Club member and leader after success payment by all member as well leader
     */
    function _sent_order_complete_mail($order){
        //return TRUE;
        if($order->parrentOrderID>0){
            //echo '$order id '.$order->parrentOrderID;
            /// mail to leader and seller and support
            $orderDetails=  $this->Order_model->details($order->parrentOrderID);
            //pre($orderDetails);die;
            $adminMailData=  $this->load_default_resources();
            $adminMailData['orderDetails']=$orderDetails;
            $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
            //pre($orderInfoDataArr);die;
            $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
            $adminMailData['orderParrentId']=$order->parrentOrderID;
            $adminMailData['userFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            //pre($adminMailData);die;
            $this->_global_tidiit_mail($orderInfoDataArr['group']->admin->email, "Your  Buying Club order - TIDIIT-OD-".$order->parrentOrderID.' has placed successfully.', $adminMailData,'group_order_success',$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName);

            $sms_data=array('nMessage'=>'Your Tidiit Buying Club['.$orderInfoDataArr['group']->groupTitle.'] order TIDIIT-OD-'.$orderDetails[0]->orderId.' for '.$orderInfoDataArr['pdetail']->title.' has placed successfully. More details about this notifiaction,Check '.BASE_URL,
                'receiverMobileNumber'=>$orderInfoDataArr['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfoDataArr['group']->admin->userId,
                'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-PLACED');
            send_sms_notification($sms_data);


            /// for seller
            $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
            $adminMailData['buyerFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Buying Club order no - TIDIIT-OD-".$order->parrentOrderID.' has placed from Tidiit Inc Ltd', $adminMailData,'seller_group_order_success',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

            /// for support
            $adminMailData['userFullName']='Tidiit Inc Support';
            $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
            $adminMailData['buyerFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            $this->load->model('Siteconfig_model','siteconfig');
            //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
            $supportEmail='judhisahoo@gmail.com';
            $this->_global_tidiit_mail($supportEmail, "Buying Club order no - TIDIIT-OD-".$order->parrentOrderID.' has placed from Tidiit Inc Ltd', $adminMailData,'support_group_order_success','Tidiit Inc Support');
            //die;
            $this->Order_model->update(array('status'=>2),$order->parrentOrderID);
            ///mail to Buyer CLub
            $allChieldOrdersData=$this->Order_model->get_all_chield_order($order->parrentOrderID);
            foreach($allChieldOrdersData AS $k){
                $this->Order_model->update(array('status'=>2),$k->orderId);
                $orderDetails=  $this->Order_model->details($k->orderId);
                $adminMailData=array();
                $adminMailData=  $this->load_default_resources();
                $adminMailData['orderDetails']=$orderDetails;
                $orderInfoDataArr=unserialize(base64_decode($k->orderInfo));
                $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
                $adminMailData['orderParrentId']=$k->parrentOrderID;
                //pre($orderInfoDataArr);die;
                foreach($orderInfoDataArr['group']->users AS $kk){
                    $email='';$userFullName='';
                    if($kk->userId==$orderDetails[0]->userId){
                        $email=$kk->email;
                        $userFullName=$kk->firstName.' '.$kk->lastName;
                        $mobileNumber=$kk->mobile;
                        break;
                    }
                }
                //echo '<br>$order id '.$k->orderId.'<br>';
                $adminMailData['userFullName']=$userFullName;
                $this->_global_tidiit_mail($email, "Your Buying Club Tidiit order TIDIIT-OD-".$k->orderId.' has placed successfully', $adminMailData,'group_order_success',$userFullName);

                $sms_data=array('nMessage'=>'Your Tidiit Buying Club['.$orderInfoDataArr['group']->groupTitle.'] order TIDIIT-OD-'.$k->orderId.' for '.$orderInfoDataArr['pdetail']->title.' has placed successfully. More details about this notifiaction,Check '.BASE_URL,
                    'receiverMobileNumber'=>$mobileNumber,'senderId'=>'','receiverId'=>$orderDetails[0]->userId,
                    'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-PLACED');
                send_sms_notification($sms_data);

                //echo '<br>$order id '.$k->orderId.'<br>';
                /// for seller
                $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
                $adminMailData['buyerFullName']=$userFullName;
                $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Buying Club order no - TIDIIT-OD-".$k->orderId.' has placed from Tidiit Inc Ltd', $adminMailData,'seller_group_order_success',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

                //echo '<br>$order id '.$k->orderId.'<br>';
                /// for support
                $adminMailData['userFullName']='Tidiit Inc Support';
                $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
                $adminMailData['buyerFullName']=$userFullName;
                $this->load->model('Siteconfig_model','siteconfig');
                //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
                $supportEmail='judhisahoo@gmail.com';
                $this->_global_tidiit_mail($supportEmail, "Buying Club order no - TIDIIT-OD-".$k->orderId.' has placed from Tidiit Inc Ltd', $adminMailData,'support_group_order_success','Tidiit Inc Support');
            }
        }
        return TRUE;
    }

    function _sent_single_order_complete_mail($orderId){
        $orderDetails=  $this->Order_model->details($orderId);
        //pre($orderDetails);die;
        $adminMailData=  $this->load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfoDataArr);die;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        /// for seller
        $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "A new order no - TIDIIT-OD-".$orderId.' has placed from Tidiit Inc Ltd', $adminMailData,'seller_single_order_success',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

        /// for support
        $adminMailData['userFullName']='Tidiit Inc Support';
        $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Order no - TIDIIT-OD-".$orderId.' has placed by '.$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName, $adminMailData,'support_single_order_success','Tidiit Inc Support');
        //die;
        $sms_data=array('nMessage'=>'Your Tidiit order TIDIIT-OD-'.$orderId.' for '.$orderInfoDataArr['pdetail']->title.' has placed successfully. More details about this notifiaction,Check '.BASE_URL,
            'receiverMobileNumber'=>$orderDetails[0]->buyerMobileNo,'senderId'=>'','receiverId'=>$orderDetails[0]->userId,
            'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-CONFIRM');
        send_sms_notification($sms_data);
        return TRUE;
    }

    function _mpesa_process($orderIdArr){
        $SEODataArr=array();
        $mPesaInfo=$this->Order_model->get_mpesa_info();
        $data = $this->_get_logedin_template($SEODataArr);
        $data['marchantCode']=$mPesaInfo[0]->userName;
        $data['checkOutURL']=$mPesaInfo[0]->endPoint;
        $data['returnURL']=BASE_URL.'shopping/mpesa_return';
        
        $data['userMenuActive']=1;
        if(is_array($_SESSION['PaymentData']['orders'])):
            $orderIdStr= base64_encode(implode(',',$_SESSION['PaymentData']['orders']));
        else:
            $orderIdStr=$_SESSION['PaymentData']['orders'];
        endif;
        $newOrderIdStr=$orderIdStr.mt_rand (10,99);
        $data['orderIdStr']=$newOrderIdStr;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $data['orderId']=$orderIdArr[0];
        
        $this->load->view('payment/mpesa',$data);
    }

    function _razorpay_process($orderIdArr){
        /*$sms_data=array('nMessage'=>'comming to ajax_process_single_payment_start FUN at '.time().' at line number 1524',
                'receiverMobileNumber'=>'9556644964','senderId'=>'','receiverId'=>100,
                'senderMobileNumber'=>'','nType'=>'TESTING');*/
        $SEODataArr=array();
        $data = $this->_get_logedin_template($SEODataArr);
        if(array_key_exists('TempPaymentData', $_SESSION)):
            if($_SESSION['TempPaymentData']['orders']!=""){
                $paymentDataArr=$_SESSION['TempPaymentData'];
                $_SESSION['PaymentData']=$paymentDataArr;
            }else{
                $paymentDataArr=$_SESSION['PaymentData'];
            }
        else:
            $paymentDataArr=$_SESSION['PaymentData'];
        endif;
        //pre($paymentDataArr);die;
        $data['paymentGatewayAmount']=$paymentDataArr['paymentGatewayAmount'];
        $me = $this->_get_current_user_details();
        /*$sms_data=array('nMessage'=>'comming to ajax_process_single_payment_start FUN at '.time().' at line number 1533',
                'receiverMobileNumber'=>'9556644964','senderId'=>'','receiverId'=>100,
                'senderMobileNumber'=>'','nType'=>'TESTING');*/
        $razorpayInfo=$this->Order_model->get_razorpay_info();
        
        /*$sms_data=array('nMessage'=>'comming to ajax_process_single_payment_start FUN at '.time().' at line number 1537',
                'receiverMobileNumber'=>'9556644964','senderId'=>'','receiverId'=>100,
                'senderMobileNumber'=>'','nType'=>'TESTING');*/
        $data['keyId']=$razorpayInfo[0]->userName;
        $data['checkOutURL']=$razorpayInfo[0]->endPoint;
        $data['userData']=$me;
        $data['userMenuActive']=1;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        //$data['orderIds']=implode('^', $orderIdArr);
        $data['orderIds']= base64_encode(serialize($orderIdArr));
        /*$sms_data=array('nMessage'=>'comming to ajax_process_single_payment_start FUN at '.time().' at line number 1546',
                'receiverMobileNumber'=>'9556644964','senderId'=>'','receiverId'=>100,
                'senderMobileNumber'=>'','nType'=>'TESTING');*/
        //pre($_SESSION);die;
        $this->load->view('payment/razorpay',$data);
    }

    function razorpay_return(){
        $orderIds=trim($this->input->post('orderIds'));
        $razorpayPaymentId=trim($this->input->post('razorpay_payment_id'));

        //$CIPaymentData=$this->session->userdata('CIPaymentData');
        /*pre($_POST);
        pre($_SESSION);
        session_destroy();
        /*echo '=============================================================================================================';
        pre($CIPaymentData);
        die;
        
        $PaymentDataArr = $_SESSION['PaymentData'];
        pre($PaymentDataArr);die;
        $productPriceArr=$this->Order_model->get_product_price_details_by_orderid($PaymentDataArr['orders']);
        pre($productPriceArr);*/
        //die;
        
        if($orderIds!="" && $razorpayPaymentId!=""){
            $this->session->set_userdata('razorpayPaymentId',$razorpayPaymentId);
            $dataArr=array('userId'=>$this->session->userdata('FE_SESSION_VAR'),'orderIds'=>$orderIds,'razorpayPaymentId'=>$razorpayPaymentId,'IP'=>$this->input->ip_address);
            $this->Order_model->add_rajorpay_return_data($dataArr);
            $razorpayInfo=$this->Order_model->get_razorpay_info();
            $api_key=$razorpayInfo[0]->userName;
            $api_secret=$razorpayInfo[0]->password;

            //include_once src/Api.php;
            $api = new Api($api_key, $api_secret);
            $payment = $api->payment->fetch($razorpayPaymentId);
            $Amount=$payment->amount;
            $captureData=$api->payment->fetch($razorpayPaymentId)->capture(array('amount'=>$Amount));
            //pre($captureData);die;
            if($captureData->captured==1){
                $PaymentDataArr = $_SESSION['PaymentData'];
                $orderType = $PaymentDataArr['orderType'];
                if($orderType=='group'):
                    $orderDataArr = $PaymentDataArr;
                    if($PaymentDataArr['final_return']=='no'):
                        $productPriceArr=$this->Order_model->get_product_price_details_by_orderid($PaymentDataArr['orders']);
                        $this->Product_model->update_product_quantity($productPriceArr[0]['productId'],$productPriceArr[0]['qty']);
                        $this->process_razorpay_success_group_order($orderDataArr);
                    else:
                        //$this->process_mpesa_success_group_order_final($orderDataArr);
                        $this->process_razorpay_success_group_order_final($orderDataArr);
                    endif;
                else:
                    if($PaymentDataArr['final_return']=='no'):
                        foreach($PaymentDataArr['orders'] As $k=> $v){
                            $productPriceArr=$this->Order_model->get_product_price_details_by_orderid($v);
                            $this->Product_model->update_product_quantity($productPriceArr[0]['productId'],$productPriceArr[0]['qty']);
                        }
                        //$this->process_mpesa_success_single_order(array('orders'=>$PaymentDataArr['orders'],'orderInfo'=>$PaymentDataArr['orderInfo']));
                        $this->process_razorpay_success_single_order(array('orders'=>$PaymentDataArr['orders'],'orderInfo'=>$PaymentDataArr['orderInfo']));
                    else:
                        //$this->process_mpesa_success_single_order_final(array('orders'=>$PaymentDataArr['orders'],'orderInfo'=>$PaymentDataArr['orderInfo'],'logisticsData'=>$PaymentDataArr['logisticsData']));
                        $this->process_razorpay_success_single_order_final(array('orders'=>$PaymentDataArr['orders'],'orderInfo'=>$PaymentDataArr['orderInfo'],'logisticsData'=>$PaymentDataArr['logisticsData']));
                    endif;
                endif;
            }else{

            }
        }else{
            $this->session->set_flashdata("message","Some error happen, please try again later!");
            redirect(BASE_URL.'shopping/my-cart');
        }
    }

    function mpesa_return(){
        //pre($_POST);die;
        $mcompgtransid=$this->input->post('mcompgtransid');
        $transrefno=$this->input->post('transrefno');
        $csrf=$this->input->post('_csrf');
        $statuscode=  $this->input->post('statuscode');
        if($statuscode=='100'):
            $mPessaReturnDataArr=array('csrf'=>$csrf,'transrefNo'=>$transrefno,'mcomPgTransId'=>$mcompgtransid);
            $PaymentDataArr = $_SESSION['PaymentData'];
            $orderType = $PaymentDataArr['orderType'];
            if($orderType=='group'):
                $orderDataArr = $PaymentDataArr;
                if($PaymentDataArr['final_return']=='no'):
                    $productPriceArr=$this->Order_model->get_product_price_details_by_orderid($PaymentDataArr['orders']);
                    $this->Product_model->update_product_quantity($productPriceArr[0]['productId'],$productPriceArr[0]['qty']);
                    $this->process_mpesa_success_group_order($orderDataArr,$mPessaReturnDataArr);
                else:
                    $this->process_mpesa_success_group_order_final($orderDataArr,$mPessaReturnDataArr);
                endif;
            else:
                if($PaymentDataArr['final_return']=='no'):
                    foreach($PaymentDataArr['orders'] As $k=> $v){
                        $productPriceArr=$this->Order_model->get_product_price_details_by_orderid($v);
                        $this->Product_model->update_product_quantity($productPriceArr[0]['productId'],$productPriceArr[0]['qty']);
                    }
                    $this->process_mpesa_success_single_order(array('orders'=>$PaymentDataArr['orders'],'orderInfo'=>$PaymentDataArr['orderInfo']),$mPessaReturnDataArr);
                else:
                    $this->process_mpesa_success_single_order_final(array('orders'=>$PaymentDataArr['orders'],'orderInfo'=>$PaymentDataArr['orderInfo'],'logisticsData'=>$PaymentDataArr['logisticsData']),$mPessaReturnDataArr);
                endif;
            endif;
        else:
            // fail
        endif;
    }

    function process_mpesa_success_group_order($PaymentDataArr,$mPessaReturnDataArr){
        //pre($PaymentDataArr);die;
        $orderId = $PaymentDataArr['orders'];
        $pevorder = $PaymentDataArr['pevorder'];
        $prod_price_info = $PaymentDataArr['prod_price_info'];

        $order_update=array();
        if($prod_price_info->qty == $PaymentDataArr['aProductQty']):
            $order_update['status'] = 2;
            $order_update['isPaid'] = 1;
            $this->Product_model->update_product_quantity($prod_price_info->productId,$prod_price_info->qty);
        else:
            $order_update['status'] = 1;
        endif;

        $update = $this->Order_model->update($order_update,$orderId);

        if($update):
            $this->Order_model->order_group_status_update($orderId, $order_update['status'],$pevorder->parrentOrderID);

            //Notification
            $order = $PaymentDataArr['order'];
            $orderinfo = $PaymentDataArr['orderInfo'];

            if($order->groupId):
                $orderinfo['group'] = $PaymentDataArr['group'];
            endif;
            $group=$PaymentDataArr['group'];
            //pre($orderinfo);//die;
            $info['orderInfo'] = base64_encode(serialize($orderinfo));
            $this->Order_model->update($info, $orderId);
            if($order->parrentOrderID == 0):
                foreach($group->users as $key => $usr):
                    $mail_template_data=array();
                    $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                    $data['receiverId'] = $usr->userId;
                    $data['nType'] = 'BUYING-CLUB-ORDER';

                    $data['nTitle'] = 'New Buying Club order running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_TITLE']=$group->admin->firstName.' '.$group->admin->lastName;
                    $data['nMessage'] = "Hi, <br> You have requested to buy Buying Club order product.<br>";
                    $data['nMessage'] .= "Product is <a href=''>".$orderinfo['pdetail']->title."</a><br>";
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_PRODUCT_TITLE']=$orderinfo['pdetail']->title;
                    $data['nMessage'] .= "Want to process the order ? <br>";
                    $data['nMessage'] .= "<a href='".BASE_URL."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".BASE_URL."shopping/group-order-accept-process/".base64_encode($orderId*226201)."' class='btn btn-success btn-lg'>Accept</a><br>";
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

                    $mail_template_view_data=$this->load_default_resources();
                    $mail_template_view_data['group_order_start']=$mail_template_data;
                    $this->_global_tidiit_mail($recv_email, "New Buying Club Order Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_start');
                    $this->User_model->notification_add($data);
                    /// sendin SMS to allmember
                    $sms_data=array('nMessage'=>'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.BASE_URL,
                        'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$usr->userId,
                        'senderMobileNumber'=>$group->admin->mobile,'nType'=>'CREATE-'.$data['nType']);
                    send_sms_notification($sms_data);
                endforeach;
            else:
                $me = $this->_get_current_user_details();
                $mail_template_data=array();
                $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $data['nType'] = 'BUYING-CLUB-ORDER';
                foreach($group->users as $key => $usr):
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

                        $mail_template_view_data=$this->load_default_resources();
                        $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                        $this->_global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                        $this->User_model->notification_add($data);

                        /// sendin SMS to allmember
                        $sms_data=array('nMessage'=>$usr->firstName.' '.$usr->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.BASE_URL,
                            'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$data['receiverId'],
                            'senderMobileNumber'=>$group->admin->mobile,'nType'=>$data['nType']);
                        send_sms_notification($sms_data);
                    endif;
                endforeach;
                $data['receiverId'] = $group->admin->userId;
                //pre($data);die;
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
                $this->_global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                $this->User_model->notification_add($data);
                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$usr->firstName.' '.$usr->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of your Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.BASE_URL,
                    'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$data['receiverId'],
                    'senderMobileNumber'=>$group->admin->mobile,'nType'=>$data['nType']);
                send_sms_notification($sms_data);
            endif;

            if($order_update['status']==2):
                $this->_sent_order_complete_mail($order);
            endif;
            $mpesaArr=array('IP'=>$this->input->ip_address,'userId'=>$this->session->userdata('FE_SESSION_VAR'),
                    'mcomPgTransId'=>$mPessaReturnDataArr['mcomPgTransId'],'transrefNo'=>$mPessaReturnDataArr['transrefNo'],'csrf'=>$mPessaReturnDataArr['csrf']);
            $mPesaId=$this->Order_model->add_mpesa($mpesaArr);
            $this->Order_model->add_payment(array('orderId'=>$orderId,'paymentType'=>'mPesa','mPesaId'=>$mPesaId,'orderType'=>'group'));
            $this->_remove_cart($PaymentDataArr['cartId']);
            unset($_SESSION['PaymentData']);
            unset($_SESSION['TempPaymentData']);
            redirect(BASE_URL.'shopping/success/');
        else:
            $this->session->set_flashdata("message","Some error happen, please try again later!");
            redirect(BASE_URL.'shopping/my-cart');
        endif;
    }

    function process_razorpay_success_group_order($PaymentDataArr){
        $orderId = $PaymentDataArr['orders'];
        $pevorder = $PaymentDataArr['pevorder'];
        $prod_price_info = $PaymentDataArr['prod_price_info'];

        $order_update=array();
        if($prod_price_info->qty == $PaymentDataArr['aProductQty']):
            $order_update['status'] = 2;
            $order_update['isPaid'] = 1;
            $this->Product_model->update_product_quantity($prod_price_info->productId,$prod_price_info->qty);
        else:
            $order_update['status'] = 1;
        endif;

        $update = $this->Order_model->update($order_update,$orderId);

        if($update):
            $this->Order_model->order_group_status_update($orderId, $order_update['status'],$pevorder->parrentOrderID);

            //Notification
            $order = $PaymentDataArr['order'];
            $orderinfo = $PaymentDataArr['orderInfo'];

            if($order->groupId):
                $orderinfo['group'] = $PaymentDataArr['group'];
            endif;
            $group=$PaymentDataArr['group'];
            $info['orderInfo'] = base64_encode(serialize($orderinfo));
            $this->Order_model->update($info, $orderId);
            if($order->parrentOrderID == 0):
                foreach($group->users as $key => $usr):
                    $mail_template_data=array();
                    $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                    $data['receiverId'] = $usr->userId;
                    $data['nType'] = 'BUYING-CLUB-ORDER';

                    $data['nTitle'] = 'New Buying Club order running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_TITLE']=$group->admin->firstName.' '.$group->admin->lastName;
                    $data['nMessage'] = "Hi, <br> You have requested to buy Buying Club order product.<br>";
                    $data['nMessage'] .= "Product is <a href=''>".$orderinfo['pdetail']->title."</a><br>";
                    $mail_template_data['TEMPLATE_GROUP_ORDER_START_PRODUCT_TITLE']=$orderinfo['pdetail']->title;
                    $data['nMessage'] .= "Want to process the order ? <br>";
                    $data['nMessage'] .= "<a href='".BASE_URL."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".BASE_URL."shopping/group-order-accept-process/".base64_encode($orderId*226201)."' class='btn btn-success btn-lg'>Accept</a><br>";
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

                    $mail_template_view_data=$this->load_default_resources();
                    $mail_template_view_data['group_order_start']=$mail_template_data;
                    $this->_global_tidiit_mail($recv_email, "New Buying Club Order Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_start');
                    $this->User_model->notification_add($data);
                    /// sendin SMS to allmember
                    $sms_data=array('nMessage'=>'You have invited to Buying Club['.$group->groupTitle.'] by '.$group->admin->firstName.' '.$group->admin->lastName.'.More details about this notifiaction,Check '.BASE_URL,
                        'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$usr->userId,
                        'senderMobileNumber'=>$group->admin->mobile,'nType'=>'CREATE-'.$data['nType']);
                    send_sms_notification($sms_data);
                endforeach;
            else:
                $me = $this->_get_current_user_details();
                $mail_template_data=array();
                $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $data['nType'] = 'BUYING-CLUB-ORDER';
                foreach($group->users as $key => $usr):
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

                        $mail_template_view_data=$this->load_default_resources();
                        $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                        $this->_global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                        $this->User_model->notification_add($data);

                        /// sendin SMS to allmember
                        $sms_data=array('nMessage'=>$usr->firstName.' '.$usr->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.BASE_URL,
                            'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$data['receiverId'],
                            'senderMobileNumber'=>$group->admin->mobile,'nType'=>$data['nType']);
                        send_sms_notification($sms_data);
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
                $this->_global_tidiit_mail($recv_email,"One Buying Club member has completed his payment at Tidiit Inc, Ltd.", $mail_template_view_data,'group_order_group_member_payment');
                $this->User_model->notification_add($data);
                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$usr->firstName.' '.$usr->lastName.' has completed payment['.$order->orderAmount.'] of '.$order->productQty.' of your Buying Club['.$group->groupTitle.'] Order TIDIIT-OD-'.$orderId.'. More details about this notifiaction,Check '.BASE_URL,
                    'receiverMobileNumber'=>$usr->mobile,'senderId'=>$data['senderId'],'receiverId'=>$data['receiverId'],
                    'senderMobileNumber'=>$group->admin->mobile,'nType'=>$data['nType']);
                send_sms_notification($sms_data);
            endif;

            if($order_update['status']==2):
                $this->_sent_order_complete_mail($order);
            endif;

            $rajorpayDataArr=$this->Order_model->get_rajorpay_id_by_rajorpay_pament_id($this->session->userdata('razorpayPaymentId'));
            $this->Order_model->add_payment(array('orderId'=>$orderId,'paymentType'=>'razorpay','razorpayId'=>$rajorpayDataArr[0]->razorpayId,'orderType'=>'group'));
            $this->_remove_cart($PaymentDataArr['cartId']);
            $this->session->unset_userdata('razorpayPaymentId');
            unset($_SESSION['PaymentData']);
            unset($_SESSION['TempPaymentData']);
            redirect(BASE_URL.'shopping/success/');
        else:
            $this->session->set_flashdata("message","Some error happen, please try again later!");
            redirect(BASE_URL.'shopping/my-cart');
        endif;
    }

    function process_mpesa_success_single_order($PaymentDataArr,$mPessaReturnDataArr){
        foreach ($PaymentDataArr['orders'] AS $k => $v):
            $order_update=array();
            $order_update['status'] = 2;
            $order_update['isPaid'] = 1;
            $this->Order_model->update($order_update,$v);

            $order=$PaymentDataArr['orderInfo'][$v]['order'];
            $orderinfo=$PaymentDataArr['orderInfo'][$v]['orderInfo'];
            $this->Product_model->update_product_quantity($order['productId'],$order['productQty']);
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$v;
            $this->_remove_cart($PaymentDataArr['orderInfo'][$v]['cartId']);

            //Send Email message
            $user = $this->_get_current_user_details();
            $recv_email = $user->email;

            if($k==0){
                $mpesaArr=array('IP'=>$this->input->ip_address,'userId'=>$this->session->userdata('FE_SESSION_VAR'),
                    'mcomPgTransId'=>$mPessaReturnDataArr['mcomPgTransId'],'transrefNo'=>$mPessaReturnDataArr['transrefNo'],'csrf'=>$mPessaReturnDataArr['csrf']);
                $mPesaId=$this->Order_model->add_mpesa($mpesaArr);
            }
            $this->Order_model->add_payment(array('orderId'=>$v,'paymentType'=>'mPesa','mPesaId'=>$mPesaId,'orderType'=>'single'));

            $mail_template_view_data=$this->load_default_resources();
            $mail_template_view_data['single_order_success']=$mail_template_data;
            $this->_global_tidiit_mail($recv_email, "Your Tidiit order no - TIDIIT-OD-".$v.' has placed successfully', $mail_template_view_data,'single_order_success');
            $this->_sent_single_order_complete_mail($v);
        endforeach;
        unset($_SESSION['PaymentData']);
        redirect(BASE_URL.'shopping/success/');
    }

    function process_razorpay_success_single_order($PaymentDataArr){
        $rajorpayDataArr=$this->Order_model->get_rajorpay_id_by_rajorpay_pament_id($this->session->userdata('razorpayPaymentId'));
        foreach ($PaymentDataArr['orders'] AS $k => $v):
            $order_update=array();
            $order_update['status'] = 2;
            $order_update['isPaid'] = 1;
            $this->Order_model->update($order_update,$v);

            $order=$PaymentDataArr['orderInfo'][$v]['order'];
            $orderinfo=$PaymentDataArr['orderInfo'][$v]['orderInfo'];
            //$this->Product_model->update_product_quantity($order['productId'],$order['productQty']);
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$v;
            $this->_remove_cart($PaymentDataArr['orderInfo'][$v]['cartId']);

            //Send Email message
            $user = $this->_get_current_user_details();
            $recv_email = $user->email;
            $this->Order_model->add_payment(array('orderId'=>$v,'paymentType'=>'razorpay','razorpayId'=>$rajorpayDataArr[0]->razorpayId,'orderType'=>'single'));

            $mail_template_view_data=$this->load_default_resources();
            $mail_template_view_data['single_order_success']=$mail_template_data;
            $this->_global_tidiit_mail($recv_email, "Your Tidiit order no - TIDIIT-OD-".$v.' has placed successfully', $mail_template_view_data,'single_order_success');
            $this->_sent_single_order_complete_mail($v);
        endforeach;
        $this->session->unset_userdata('razorpayPaymentId');
        unset($_SESSION['PaymentData']);
        unset($_SESSION['TempPaymentData']);
        redirect(BASE_URL.'shopping/success/');
    }

    function order_cancel_process_view($orderId){
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
            $this->session->set_flashdata('error', 'Invalid url!');
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        $data['order'] = $order;
        if($order->status == 7):
            $this->session->set_flashdata('error', 'You ahave already canceled the order.');
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        $data['user']=$user;
        $data['userMenuActive']= '';
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('single_order/cancel_order',$data);
    }

    function order_cancel_processing(){
        $orderId    = $this->input->post('orderId');
        $reason     = $this->input->post('reason');
        $comments   = $this->input->post('comments');
        $orderId    = base64_decode($orderId);
        $orderId    = $orderId/226201;
        $order      = $this->Order_model->get_single_order_by_id($orderId);
        $me=  $this->_get_current_user_details();
        if(!$order):
            echo '-1<p class="box alert">Invalid form submission! Please try after sometime.</p>';
            die;
        endif;

        if(!$reason):
            echo '-1<p class="box alert">Please select a reason!</p>';
            die;
        endif;

        if($reason == "Other Reasons" && !$comments):
            echo '-1<p class="box alert">Please write your other reason in comment box!</p>';
            die;
        endif;
        $this->Order_model->update(array('status'=> 7), $orderId);
        $order=$this->Order_model->get_single_order_by_id($orderId);
        $this->Product_model->update_product_quantity($order->productId,$order->productQty,'+');
        $this->single_order_cancel_mail($order,$reason,$comments);
        /// sendin SMS to cancel the order
        $sms_data_msg='Your cancelation request for Tidiit Order TIDIIT-OD-'.$orderId.' has placed successfully.';
        if($reason != "Other Reasons"){
            $sms_data_msg .='Cancelation request due to "'.$reason.'".';
        }else{
            $sms_data_msg .='Cancelation request due to "'.$comments.'".';
        }
        $sms_data_msg.='More details about this notifiaction,Check '.BASE_URL;
        $sms_data=array('nMessage'=>$sms_data_msg,'receiverMobileNumber'=>$me->mobile,'senderId'=>'','receiverId'=>$me->userId,
            'senderMobileNumber'=>'','nType'=>"SINGLE-ORDER-CANCEL-BUYER");
        send_sms_notification($sms_data);
        die;
    }

    function single_order_cancel_mail($order,$reason,$comments=""){
        $orderId=$order->orderId;
        $this->load->model('Order_model');
        $orderDetails=array();
        $orderDetails[]=$order;

        //pre($orderDetails);die;
        $adminMailData=  $this->load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $adminMailData['reason']=$reason;
        $adminMailData['comments']=$comments;
        $adminMailData['orderId']=$orderId;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfoDataArr);die;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        $sellerFullName=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['sellerFullName']=$sellerFullName;
        $buyerFullName=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $adminMailData['buyerFullName']=$buyerFullName;

        // for buyer
        $this->_global_tidiit_mail($orderDetails[0]->buyerEmail,'Your Tidiit order TIDIIT-OD-'.$order->orderId.' has canceled successfully',$adminMailData,'single_order_canceled',$buyerFullName);

        /// for seller
        $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Tidiit order TIDIIT-OD-".$order->orderId.' has canceled by '.$buyerFullName, $adminMailData,'seller_single_order_canceled',$sellerFullName);

        /// for support
        $adminMailData['userFullName']='Tidiit Inc Support';
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Tidiit Order TIDIIT-OD-".$order->orderId.' has canceled by '.$buyerFullName, $adminMailData,'support_single_order_canceled','Tidiit Inc Support');
        return TRUE;
    }

    function complete_payment(){
        $config = array(
            //array('field'   => 'deliveryStaffEmail','label'   => 'Delivery Staff Email','rules'=> 'trim|required|xss_clean|valid_email'),
            //array('field'   => 'deliveryStaffContactNo','label'   => 'Delivery Staff Contact No','rules'   => 'trim|required|xss_clean'),
            //array('field'   => 'deliveryStaffName','label'   => 'Delivery Staff Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'paymentoption','label'   => 'Select Payment Method','rules'   => 'trim|required|xss_clean|'),
            array('field'   => 'orderId','label'   => 'Order Index','rules'   => 'trim|required|xss_clean|')
        );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config);
        //checking validation
        if($this->form_validation->run() == FALSE):
            echo json_encode(array('result'=>'bad','msg'=>str_replace('</p>','',str_replace('<p>','',validation_errors()))));die;
        else:
            $orderId=trim($this->input->post('orderId',TRUE));
            $deliveryStaffEmail=trim($this->input->post('deliveryStaffEmail',TRUE));
            $deliveryStaffContactNo=trim($this->input->post('deliveryStaffContactNo',TRUE));
            $deliveryStaffName=trim($this->input->post('deliveryStaffName',TRUE));
            $paymentOption=trim($this->input->post('paymentOption',TRUE));

            $order=$this->Order_model->get_single_order_by_id($orderId);
            $prod_price_info = $this->Product_model->get_products_price_details_by_id($order->productPriceId);
            $group = $this->User_model->get_group_by_id($order->groupId);
            $a = $this->_get_available_order_quantity($orderId);
            $orderinfo=unserialize(base64_decode($order->orderInfo));
            $logisticsData=array('deliveryStaffName'=>$deliveryStaffName,'deliveryStaffContactNo'=>$deliveryStaffContactNo,'deliveryStaffEmail'=>$deliveryStaffEmail);
            //pre($order);die;
            if($order->orderType=='SINGLE'):
                $allOrderInfoArray=array();
                $allOrderArray=array();
                $allOrderInfoArray[$orderId]['orderInfo']=$orderinfo;
                $allOrderInfoArray[$orderId]['order']=$order;
                $allOrderInfoArray[$orderId]['cartId']='';
                $allOrderArray[]=$orderId;
                $paymentDataArr=array('orders'=>$allOrderArray,'orderType'=>'single','paymentGatewayAmount'=>$order->orderAmount,'orderInfo'=>$allOrderInfoArray,'final_return'=>'yes','logisticsData'=>$logisticsData);
            else:
                $paymentDataArr = array('orders'=>$orderId,'orderType'=>'group','paymentGatewayAmount'=>$order->orderAmount,'orderInfo'=>$orderinfo,'group'=>$group,'pevorder'=>$order,'aProductQty'=>$a[0]->productQty,'prod_price_info'=>$prod_price_info,'order'=>$order,'cartId'=>'','final_return'=>'yes','logisticsData'=>$logisticsData);
            endif;
            $_SESSION['PaymentData'] = $paymentDataArr;
            $this->_mpesa_process(array($orderId));
        endif;
    }

    function process_mpesa_success_group_order_final($PaymentDataArr,$mPessaReturnDataArr){
        $orderId = $PaymentDataArr['orders'];
        $pevorder = $PaymentDataArr['pevorder'];
        $prod_price_info = $PaymentDataArr['prod_price_info'];

        $order_update=array();
        $order_update['isPaid'] = 1;

        $update = $this->Order_model->update($order_update,$orderId);

        //Notification
        $order = $PaymentDataArr['order'];
        $orderinfo = $PaymentDataArr['orderInfo'];

        if($order->groupId):
            $orderinfo['group'] = $PaymentDataArr['group'];
        endif;
        $group=$PaymentDataArr['group'];
        $user = $this->_get_current_user_details();
        $recv_email = $user->email;
        $recv_name=$user->firstName.' '.$user->lastName;
        $tidiitStr='TIDIIT-OD-';
        $logisticsData=$PaymentDataArr['logisticsData'];

        if($order->parrentOrderID == 0):
            foreach($group->users as $key => $usr):
                $mail_template_data=array();
                $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $data['receiverId'] = $usr->userId;
                $data['nType'] = 'BUYING-CLUB-ORDER';

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

                $mail_template_view_data=$this->load_default_resources();
                $mail_template_view_data['group_order_start']=$mail_template_data;
                $this->_global_tidiit_mail($recv_email, 'Payment has submited before delivery for Buying Club Order', $mail_template_view_data,'group_order_sod_final_payment');
                $this->User_model->notification_add($data);
            endforeach;
        else:
            $me = $this->_get_current_user_details();
            $mail_template_data=array();
            foreach($group->users as $key => $usr):
                $mail_template_data=array();
                $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $data['receiverId'] = $usr->userId;
                $data['nType'] = 'BUYING-CLUB-ORDER';

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

                $mail_template_view_data=$this->load_default_resources();
                $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                $this->_global_tidiit_mail($recv_email,"Payment submited for Tidiit Buying Club order  before delivery", $mail_template_view_data,'group_order_group_member_sod_final_payment');
                if($me->userId != $usr->userId):
                    $this->User_model->notification_add($data);
                endif;
            endforeach;
            $data['receiverId'] = $group->admin->userId;

            //Send Email message
            $recv_email = $group->admin->email;
            $leaderName=$group->admin->firstName.' '.$group->admin->lastName;
            $sender_email = $me->email;
            $mail_template_view_data=$this->load_default_resources();
            if(empty($mail_template_data)){
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$me->firstName.' '.$me->lastName;
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
            }
            $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
            $this->_global_tidiit_mail($recv_email,"Payment has submited for Tidiit Buying Club order before delivery", $mail_template_view_data,'group_order_group_member_sod_final_payment',$leaderName);
            $this->User_model->notification_add($data);
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
        $mpesaArr=array('IP'=>$this->input->ip_address,'userId'=>$this->session->userdata('FE_SESSION_VAR'),
                    'mcomPgTransId'=>$mPessaReturnDataArr['mcomPgTransId'],'transrefNo'=>$mPessaReturnDataArr['transrefNo'],'csrf'=>$mPessaReturnDataArr['csrf']);
        $mPesaId=$this->Order_model->add_mpesa($mpesaArr);
        $this->Order_model->edit_payment(array('paymentType'=>'mPesa','mPesaId'=>$mPesaId),$orderId);
        $logisticMobileNo=$PaymentDataArr['logisticsData']['deliveryStaffContactNo'];
        $sms="Hi ".$PaymentDataArr['logisticsData']['deliveryStaffName'].'. '.$recv_name.' has completed the payment for Tidiit order '.$tidiitStr.'-'.$orderId.' please process the delivery.';

        /// here send mail to logistic partner
        $mailBody="Hi ".$PaymentDataArr['logisticsData']['deliveryStaffName'].",<br /> <b>$recv_name</b> has completed Tidiit payment for Order <b>".$tidiitStr.'-'.$orderId.'</b><br /><br /> Pleasee process the delivery for the above order.<br /><br />Thanks<br>Tidiit Team.';
        $this->_global_tidiit_mail($PaymentDataArr['logisticsData']['deliveryStaffEmail'],'Tidiit payment completed for Order '.$tidiitStr.'-'.$orderId,$mailBody,'',$recv_name);

        $this->_sent_order_complete_mail_sod_final_payment1($this->Order_model->get_single_order_by_id($orderId));

        unset($_SESSION['PaymentData']);
        $this->session->set_flashdata('message','Thanks for the payment before order is Out for delivery');
        redirect(BASE_URL.'my-orders/');
    }

    function process_razorpay_success_group_order_final($PaymentDataArr){
        $orderId = $PaymentDataArr['orders'];
        $pevorder = $PaymentDataArr['pevorder'];
        $prod_price_info = $PaymentDataArr['prod_price_info'];

        $order_update=array();
        $order_update['isPaid'] = 1;

        $update = $this->Order_model->update($order_update,$orderId);

        //Notification
        $order = $PaymentDataArr['order'];
        $orderinfo = $PaymentDataArr['orderInfo'];

        if($order->groupId):
            $orderinfo['group'] = $PaymentDataArr['group'];
        endif;
        $group=$PaymentDataArr['group'];
        $user = $this->_get_current_user_details();
        $recv_email = $user->email;
        $recv_name=$user->firstName.' '.$user->lastName;
        $tidiitStr='TIDIIT-OD-';
        $logisticsData=$PaymentDataArr['logisticsData'];
        
        $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
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

                $mail_template_view_data=$this->load_default_resources();
                $mail_template_view_data['group_order_start']=$mail_template_data;
                $this->_global_tidiit_mail($recv_email, 'Payment has submited before delivery for Buying Club Order', $mail_template_view_data,'group_order_sod_final_payment');
                $this->User_model->notification_add($data);
            endforeach;
        else:
            $me = $this->_get_current_user_details();
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

                $mail_template_view_data=$this->load_default_resources();
                $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
                $this->_global_tidiit_mail($recv_email,"Payment submited for Tidiit Buying Club order  before delivery", $mail_template_view_data,'group_order_group_member_sod_final_payment');
                if($me->userId != $usr->userId):
                    $this->User_model->notification_add($data);
                endif;
            endforeach;
            $data['receiverId'] = $group->admin->userId;

            //Send Email message
            $recv_email = $group->admin->email;
            $leaderName=$group->admin->firstName.' '.$group->admin->lastName;
            $sender_email = $me->email;
            $mail_template_view_data=$this->load_default_resources();
            if(empty($mail_template_data)){
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_USER_NAME']=$me->firstName.' '.$me->lastName;
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_AMT']=$order->orderAmount;
                $mail_template_data['TEMPLATE_GROUP_ORDER_GROUP_MEMBER_PAYMENT_ORDER_QTY']=$order->productQty;
            }
            $mail_template_view_data['group_order_group_member_payment']=$mail_template_data;
            $this->_global_tidiit_mail($recv_email,"Payment has submited for Tidiit Buying Club order before delivery", $mail_template_view_data,'group_order_group_member_sod_final_payment',$leaderName);
            $this->User_model->notification_add($data);
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

        $rajorpayDataArr=$this->Order_model->get_rajorpay_id_by_rajorpay_pament_id($this->session->userdata('razorpayPaymentId'));
        $this->Order_model->edit_payment(array('paymentType'=>'razorpay','razorpayId'=>$rajorpayDataArr[0]->razorpayId),$orderId);
        $logisticMobileNo=$PaymentDataArr['logisticsData']['deliveryStaffContactNo'];
        $sms="Hi ".$PaymentDataArr['logisticsData']['deliveryStaffName'].'. '.$recv_name.' has completed the payment for Tidiit order '.$tidiitStr.'-'.$orderId.' please process the delivery.';

        /// here send mail to logistic partner
        $mailBody="Hi ".$PaymentDataArr['logisticsData']['deliveryStaffName'].",<br /> <b>$recv_name</b> has completed Tidiit payment for Order <b>".$tidiitStr.'-'.$orderId.'</b><br /><br /> Pleasee process the delivery for the above order.<br /><br />Thanks<br>Tidiit Team.';
        $this->_global_tidiit_mail($PaymentDataArr['logisticsData']['deliveryStaffEmail'],'Tidiit payment completed for Order '.$tidiitStr.'-'.$orderId,$mailBody,'',$recv_name);

        $this->_sent_order_complete_mail_sod_final_payment1($this->Order_model->get_single_order_by_id($orderId));
        $this->session->unset_userdata('razorpayPaymentId');
        unset($_SESSION['PaymentData']);
        unset($_SESSION['TempPaymentData']);
        $this->session->set_flashdata('message','Thanks for the payment before order is Out for delivery');
        redirect(BASE_URL.'my-orders/');
    }

    function process_mpesa_success_single_order_final($PaymentDataArr,$mPessaReturnDataArr){
        $orderId=0;
        $tidiitStrChr='TIDIIT-OD';
        $tidiitStr='';
        //Send Email message
        $user = $this->_get_current_user_details();
        $recv_email = $user->email;
        $recv_name=$user->firstName.' '.$user->lastName;
        foreach ($PaymentDataArr['orders'] AS $k => $v):
            $orderId=$v;
            $tidiitStr.=$tidiitStrChr.'-'.$v.',';
            $order_update=array();
            $order_update['isPaid'] = 1;
            $this->Order_model->update($order_update,$v);

            $order=$PaymentDataArr['orderInfo'][$v]['order'];
            $orderinfo=$PaymentDataArr['orderInfo'][$v]['orderInfo'];
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$v;
            $mpesaArr=array('IP'=>$this->input->ip_address,'userId'=>$this->session->userdata('FE_SESSION_VAR'),
                    'mcomPgTransId'=>$mPessaReturnDataArr['mcomPgTransId'],'transrefNo'=>$mPessaReturnDataArr['transrefNo'],'csrf'=>$mPessaReturnDataArr['csrf']);
            $mPesaId=$this->Order_model->add_mpesa($mpesaArr);
            $this->Order_model->edit_payment(array('paymentType'=>'mPesa','mPesaId'=>$mPesaId),$v);

            $mail_template_view_data=$this->load_default_resources();
            $mail_template_view_data['single_order_success']=$mail_template_data;
            $this->_global_tidiit_mail($recv_email, "Payment has completed for your Tidiit order TIDIIT-OD-".$v, $mail_template_view_data,'single_order_success_sod_final_payment',$recv_name);
            $this->_sent_single_order_complete_mail_sod_final_payment($v);
        endforeach;
        /// here to preocess SMS to logistics partner
        $logisticsData=$PaymentDataArr['logisticsData'];
        if(!empty($logisticsData) && array_key_exists('deliveryStaffContactNo', $logisticsData)):
            $logisticMobileNo=$logisticsData['deliveryStaffContactNo'];
            if($logisticMobileNo!=""):
                $sms=$recv_name.' has completed the payment for Tidiit order '.$tidiitStr.' please process the delivery.';
                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$logisticMobileNo,'senderId'=>'','receiverId'=>'',
                    'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-LOGISTICS');
                send_sms_notification($sms_data);
            endif;
        endif;

        /// SMS to payer
        $sms='Thanks for the payment.We have received for Tidiit order '.$tidiitStr.'.';
        $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$user->mobile,'senderId'=>'','receiverId'=>$user->userId,
            'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-PAYER');
        send_sms_notification($sms_data);

        /// here send mail to logistic partner
        $mailBody="Hi ".$PaymentDataArr['logisticsData']['deliveryStaffName'].",<br /> <b>$recv_name</b> has completed Tidiit payment for Order <b>".$tidiitStr.'</b><br /><br /> Pleasee process the delivery for the above order.<br /><br />Thanks<br>Tidiit Team.';
        $this->_global_tidiit_mail($PaymentDataArr['logisticsData']['deliveryStaffEmail'],'Tidiit payment submited  for Order '.$tidiitStr,$mailBody,'',$recv_name);
        unset($_SESSION['PaymentData']);
        $this->session->set_flashdata('message','Thanks for the payment before order is Out for delivery');
        redirect(BASE_URL.'my-orders/');
    }

    function process_razorpay_success_single_order_final($PaymentDataArr){
        $orderId=0;
        $tidiitStrChr='TIDIIT-OD';
        $tidiitStr='';
        //Send Email message
        $user = $this->_get_current_user_details();
        $recv_email = $user->email;
        $recv_name=$user->firstName.' '.$user->lastName;
        foreach ($PaymentDataArr['orders'] AS $k => $v):
            $orderId=$v;
            $tidiitStr.=$tidiitStrChr.'-'.$v.',';
            $order_update=array();
            $order_update['isPaid'] = 1;
            $this->Order_model->update($order_update,$v);

            $order=$PaymentDataArr['orderInfo'][$v]['order'];
            $orderinfo=$PaymentDataArr['orderInfo'][$v]['orderInfo'];
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_INFO']=$orderinfo;
            $mail_template_data['TEMPLATE_ORDER_SUCCESS_ORDER_ID']=$v;

            $rajorpayDataArr=$this->Order_model->get_rajorpay_id_by_rajorpay_pament_id($this->session->userdata('razorpayPaymentId'));
            $this->Order_model->edit_payment(array('paymentType'=>'razorpay','razorpayId'=>$rajorpayDataArr[0]->razorpayId),$v);

            $mail_template_view_data=$this->load_default_resources();
            $mail_template_view_data['single_order_success']=$mail_template_data;
            $this->_global_tidiit_mail($recv_email, "Payment has completed for your Tidiit order TIDIIT-OD-".$v, $mail_template_view_data,'single_order_success_sod_final_payment',$recv_name);
            $this->_sent_single_order_complete_mail_sod_final_payment($v);
        endforeach;
        /// here to preocess SMS to logistics partner
        $logisticsData=$PaymentDataArr['logisticsData'];
        if(!empty($logisticsData) && array_key_exists('deliveryStaffContactNo', $logisticsData)):
            $logisticMobileNo=$logisticsData['deliveryStaffContactNo'];
            if($logisticMobileNo!=""):
                $sms=$recv_name.' has completed the payment for Tidiit order '.$tidiitStr.' please process the delivery.';
                /// sendin SMS to allmember
                $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$logisticMobileNo,'senderId'=>'','receiverId'=>'',
                    'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-LOGISTICS');
                send_sms_notification($sms_data);
            endif;
        endif;

        /// SMS to payer
        $sms='Thanks for the payment.We have received for Tidiit order '.$tidiitStr.'.';
        $sms_data=array('nMessage'=>$sms,'receiverMobileNumber'=>$user->mobile,'senderId'=>'','receiverId'=>$user->userId,
            'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-FINAL-PAYMENT-BEFORE-DELIVERY-PAYER');
        send_sms_notification($sms_data);

        /// here send mail to logistic partner
        $mailBody="Hi ".$PaymentDataArr['logisticsData']['deliveryStaffName'].",<br /> <b>$recv_name</b> has completed Tidiit payment for Order <b>".$tidiitStr.'</b><br /><br /> Pleasee process the delivery for the above order.<br /><br />Thanks<br>Tidiit Team.';
        $this->_global_tidiit_mail($PaymentDataArr['logisticsData']['deliveryStaffEmail'],'Tidiit payment submited  for Order '.$tidiitStr,$mailBody,'',$recv_name);
        unset($_SESSION['PaymentData']);
        unset($_SESSION['TempPaymentData']);
        $this->session->set_flashdata('message','Thanks for the payment before order is Out for delivery');
        redirect(BASE_URL.'my-orders/');
    }

    function _sent_single_order_complete_mail_sod_final_payment($orderId){
        $orderDetails=  $this->Order_model->details($orderId);
        //pre($orderDetails);die;
        $adminMailData=  $this->load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfoDataArr);die;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        /// for seller
        $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $buyerFullName=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $adminMailData['buyerFullName']=$buyerFullName;
        $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Payment has submited for Tidiit order TIDIIT-OD-".$orderId.' before delivery', $adminMailData,'seller_single_order_success_sod_final_payment',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

        /// for support
        $adminMailData['userFullName']='Tidiit Inc Support';
        $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Payment has submited for Tidiit Order TIDIIT-OD-".$orderId.' before delivery', $adminMailData,'support_single_order_success_sod_final_payment','Tidiit Inc Support');
        //die;

        return TRUE;
    }

    function _sent_order_complete_mail_sod_final_payment1($order){
        $sessionUserData=  $this->_get_current_user_details();
        $currentUserFullName=$sessionUserData->firstName.' '.$sessionUserData->lastName;
        if($order->parrentOrderID==0)
            $orderDetails=  $this->Order_model->details($order->orderId);
        else
            $orderDetails=  $this->Order_model->details($order->parrentOrderID);
        //pre($orderDetails);die;
        $adminMailData=  $this->load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfoDataArr);die;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        $adminMailData['orderParrentId']=$order->parrentOrderID;
        $adminMailData['userFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
        //pre($adminMailData);die;
        $this->_global_tidiit_mail($orderInfoDataArr['group']->admin->email, "Payment has submited for Tiidit Buying Club order - TIDIIT-OD-".$order->orderId.' before delivery.', $adminMailData,'group_order_success_sod_final_payment',$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName);

        /// for seller
        $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
        $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Payment submited for the Buying Club order TIDIIT-OD-".$order->orderId.' before delivery.', $adminMailData,'seller_group_order_success_sod_final_payment',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

        /// for support
        $adminMailData['userFullName']='Tidiit Inc Support';
        $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Payment has submited for Tidiit Buying Club order TIDIIT-OD-".$order->orderId.' before delivery.', $adminMailData,'support_group_order_success_sod_final_payment','Tidiit Inc Support');
    }

    function _sent_order_complete_mail_sod_final_payment($order){
        //return TRUE;
        $sessionUserData=  $this->_get_current_user_details();
        $currentUserFullName=$sessionUserData->firstName.' '.$sessionUserData->lastName;
        if($order->parrentOrderID>0){
            //echo '$order id '.$order->parrentOrderID;
            /// mail to leader and seller and support
            $orderDetails=  $this->Order_model->details($order->parrentOrderID);
            //pre($orderDetails);die;
            $adminMailData=  $this->load_default_resources();
            $adminMailData['orderDetails']=$orderDetails;
            $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
            //pre($orderInfoDataArr);die;
            $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
            $adminMailData['orderParrentId']=$order->parrentOrderID;
            $adminMailData['userFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            //pre($adminMailData);die;
            //$this->_global_tidiit_mail($orderInfoDataArr['group']->admin->email, "$currentUserFullName  has completed payment in your Buying Club order - TIDIIT-OD-".$order->orderId.' before delivery.', $adminMailData,'group_order_success_sod_final_payment',$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName);

            /// for seller
            $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
            $adminMailData['buyerFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Payment has submited for the Buying Club order TIDIIT-OD-".$order->parrentOrderID.' before delivery.', $adminMailData,'seller_group_order_success',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

            /// for support
            $adminMailData['userFullName']='Tidiit Inc Support';
            $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
            $adminMailData['buyerFullName']=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            $this->load->model('Siteconfig_model','siteconfig');
            //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
            $supportEmail='judhisahoo@gmail.com';
            $this->_global_tidiit_mail($supportEmail, "Payment has submited for the Tidiit Buying Club order TIDIIT-OD-".$order->parrentOrderID.' before delivery.', $adminMailData,'support_group_order_success_sod_final_payment','Tidiit Inc Support');
            //die;
            ///mail to Buyer CLub
            $allChieldOrdersData=$this->Order_model->get_all_chield_order($order->parrentOrderID);
            foreach($allChieldOrdersData AS $k){
                $orderDetails=  $this->Order_model->details($k->orderId);
                $adminMailData=array();
                $adminMailData=  $this->load_default_resources();
                $adminMailData['orderDetails']=$orderDetails;
                $orderInfoDataArr=unserialize(base64_decode($k->orderInfo));
                $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
                $adminMailData['orderParrentId']=$k->parrentOrderID;
                //pre($orderInfoDataArr);die;
                foreach($orderInfoDataArr['group']->users AS $kk){
                    $email='';$userFullName='';
                    if($kk->userId==$orderDetails[0]->userId){
                        $email=$kk->email;
                        $userFullName=$kk->firstName.' '.$kk->lastName;
                        break;
                    }
                }
                //echo '<br>$order id '.$k->orderId.'<br>';
                $adminMailData['userFullName']=$userFullName;
                $this->_global_tidiit_mail($email, "Payment has completed for your Tidiit Buying Club order TIDIIT-OD-".$k->orderId.' before delivery.', $adminMailData,'group_order_success_sod_final_payment',$userFullName);

                //echo '<br>$order id '.$k->orderId.'<br>';
                /// for seller
                $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
                $adminMailData['buyerFullName']=$userFullName;
                $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Payment has completed for Tidiit Buying Club order TIDIIT-OD-".$k->orderId.' before delivery.', $adminMailData,'seller_group_order_success_sod_final_payment',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

                //echo '<br>$order id '.$k->orderId.'<br>';
                /// for support
                $adminMailData['userFullName']='Tidiit Inc Support';
                $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
                $adminMailData['buyerFullName']=$userFullName;
                $this->load->model('Siteconfig_model','siteconfig');
                //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
                $supportEmail='judhisahoo@gmail.com';
                $this->_global_tidiit_mail($supportEmail, "Payment has completed for Tidiit Buying Club order TIDIIT-OD-".$k->orderId.' before delivery.', $adminMailData,'support_group_order_success_sod_final_payment','Tidiit Inc Support');
            }
        }else{
            die('comming else part');
        }

        return TRUE;
    }

    function check_old_order_type(){
        $orderType=  $this->input->post('orderType',TRUE);
        $user=  $this->_get_current_user_details();
        if(count($user)==0){
            echo json_encode(array('contents'=>'-1'));
            die;
        }else{
            echo json_encode(array('contents'=>'1')); die;
            /*$rs=$this->Order_model->has_order_with_order_type($user->userId);
            if(empty($rs)){
                echo json_encode(array('contents'=>'1')); die;
            }else{
                if($orderType=='single'){
                    if(strtolower($rs->orderType)=='single'){
                        echo json_encode(array('contents'=>'1')); die;
                    }else{
                        echo json_encode(array('contents'=>'0')); die;
                    }
                }else{
                    if(strtolower($rs->orderType)=='single'){
                        echo json_encode(array('contents'=>'2')); die;
                    }else{
                        echo json_encode(array('contents'=>'1')); die;
                    }
                }   
            }*/
        }
    }

    function ajax_single_order_remove_promo(){
        $orderId=$this->input->post('orderId',TRUE);
        $orderDetails=$this->Order_model->details($orderId);
        $countryShortName=$this->session->userdata('FE_SESSION_USER_LOCATION_VAR');
        $fieldName=$countryShortName.'_tax';
        $currentLocationTaxDetails=$this->Product_model->get_tax_for_current_location($orderDetails[0]->productId,$countryShortName.'_tax');
        $taxCol=$countryShortName.'_tax';
        $taxPercentage=$currentLocationTaxDetails->$taxCol;
        $tax=$orderDetails[0]->subTotalAmount*$taxPercentage/100;

        $orderDataArr=array();
        $orderDataArr['taxAmount'] = $tax;
        $orderDataArr['orderAmount'] = $orderDetails[0]->subTotalAmount+$tax;
        $orderDataArr['discountAmount'] ='';
        $this->Order_model->update($orderDataArr,$orderId);

        $this->Coupon_model->remove_order($orderId);
        $result['msg'] = "ok";
        echo json_encode( $result );
        die;
    }

    function _get_user_select_cat_array(){
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
        return $menuArr;
    }
}