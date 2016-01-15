<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tidiit_debug extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Brand_model');
        parse_str($_SERVER['QUERY_STRING'],$_GET);
        //$this->db->cache_off();
    }
    
    function test(){
        pre($_SERVER['DOCUMENT_ROOT']);die;
    }
    
    function sent_order_complete_mail(){
        $this->load->model('Order_model');
        $order=new stdClass();
        $order->parrentOrderID=12;
        if($order->parrentOrderID>0){
            echo '$order id '.$order->parrentOrderID;
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
            $this->_global_tidiit_mail($orderInfoDataArr->group->admin->email, "Confirmation mail for your Tidiit Buying Club order no - TIDIIT-OD-".$order->parrentOrderID, $adminMailData,'group_order_success',$orderInfoDataArr->group->admin->firstName.' '.$orderInfoDataArr->group->admin->lastName);
            
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
                echo '<br>$order id '.$k->orderId.'<br>';
                $adminMailData['userFullName']=$userFullName;
                $this->_global_tidiit_mail($email, "Confirmation mail for your Buying Club Tidiit order no - TIDIIT-OD-".$k->orderId, $adminMailData,'group_order_success',$userFullName);
                
                echo '<br>$order id '.$k->orderId.'<br>';
                /// for seller
                $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
                $adminMailData['buyerFullName']=$userFullName;
                $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Buying Club order no - TIDIIT-OD-".$k->orderId.' has placed from Tidiit Inc Ltd', $adminMailData,'seller_group_order_success',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);
                
                echo '<br>$order id '.$k->orderId.'<br>';
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
    }
    
    function sent_single_order_complete_mail(){
        $this->load->model('Order_model');
        $orderId=2;
        //echo '$order id '.$order->parrentOrderID;
        /// mail to leader and seller and support
        $orderDetails=  $this->Order_model->details($orderId);
        //pre($orderDetails);die;
        $adminMailData=  $this->load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfoDataArr);die;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        /// for seller
        $adminMailData['userFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['billing']->firstName.' '.$orderInfoDataArr['billing']->lastName;
        //$this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Buying Club order no - TIDIIT-OD-".$orderId.' has placed from Tidiit Inc Ltd', $adminMailData,'seller_single_order_success',$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName);

        /// for support
        $adminMailData['userFullName']='Tidiit Inc Support';
        $adminMailData['sellerFullName']=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['buyerFullName']=$orderInfoDataArr['billing']->firstName.' '.$orderInfoDataArr['billing']->lastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Buying Club order no - TIDIIT-OD-".$orderId.' has placed from Tidiit Inc Ltd', $adminMailData,'support_single_order_success','Tidiit Inc Support');
        //die;
        
        return TRUE;
    }
}