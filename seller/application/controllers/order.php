<?php
class Order extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Product_model');
		$this->load->model('Cart_model');
		$this->load->model('Category_model');
		$this->load->model('User_model');
		$this->load->model('Order_model');
		
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
            $data=$this->_get_logedin_template();

            $per_page=20;
            $PaginationConfig=array(
                'base_url'=>base_url() . "admin/order/viewlist",
                'total_rows'=>$this->Order_model->seller_list_total(),
                'per_page'=>$per_page,
                'num_links'=>15,
                'uri_segment'=>4
                );
            $this->_my_pagination($PaginationConfig);

            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            //echo 'KK  '.$config['per_page'];die;
            if($page==0){
                $offcet=0;
            }else{
                $offcet=$per_page*($page-1);
            }
            $orderDataArr=$this->Order_model->seller_list($per_page,$offcet);
            $orderStatusobj=$this->Order_model->get_state();
            $stateArr=array();
            foreach($orderStatusobj As $k){ 
                $stateArr[$k->orderStateId]=$k->name;
            }
            $data['status']= $stateArr;
            $data['DataArr']=$orderDataArr;
            $data["links"] = $this->pagination->create_links();
            $this->load->view('order_list',$data);
	}
        
        public function viewUncompleteList(){
            $data=$this->_show_admin_logedin_layout();

            $per_page=20;
            $PaginationConfig=array(
                'base_url'=>base_url() . "admin/order/viewUncompleteList",
                'total_rows'=>$this->Order_model->get_admin_uncomplete_total(),
                'per_page'=>$per_page,
                'num_links'=>15,
                'uri_segment'=>4
                );
            $this->_my_pagination($PaginationConfig);

            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            //echo 'KK  '.$config['per_page'];die;
            if($page==0){
                $offcet=0;
            }else{
                $offcet=$per_page*($page-1);
            }

            $data['DataArr']=$this->Order_model->admin_uncomplete_list($per_page,$offcet);
            $data["links"] = $this->pagination->create_links();
            $this->load->view('admin/order_list1',$data);
	}
	
        function state_change(){
            $status=  $this->input->post('status',TRUE);
            $orderId=  $this->input->post('orderId',TRUE);
            if($status=="" || $orderId==""):
                $this->session->set_flashdata('Message',"Please provide order index and select order status type.");
                redirect(BASE_URL.'order/viewlist/');
            elseif($status==4):
                $config=array(
                    array('field'   => 'logisticsId','label'   => 'Select Logistics Partner','rules'   => 'trim|required|xss_clean'),  
                    array('field'   => 'awbNo','label'   => 'Enter your Air Way Bill Number','rules'   => 'trim|required|xss_clean'),
                    array('field'   => 'trackingURL','label'   => 'Enter your tracking URL','rules'   => 'trim|required|xss_clean')
                );
                $this->form_validation->set_rules($config); 
                if($this->form_validation->run() == FALSE){
                    $data=validation_errors();
                    $this->session->set_flashdata('Message',$data);
                    redirect(BASE_URL.'order/viewlist/');
                }
            endif;
            $note=  $this->input->post('note',TRUE);
            $logisticsId=  $this->input->post('logisticsId',TRUE);
            $awbNo=  $this->input->post('awbNo',TRUE);
            $trackingURL=  $this->input->post('trackingURL',TRUE);
            
            $this->Order_model->update(array('status'=>$status),$orderId);
            //echo $status;die;
            if($status==3):
                $orderHistoryArr=array('orderId'=>$orderId,'state'=>$status,'historyBy'=>2,'actionOwnerId'=>  $this->session->userdata('FE_SESSION_VAR'),'note'=>$note);
                $this->Order_model->add_history($orderHistoryArr);
                //pre($orderHistoryArr);die;
                $this->order_confirm($orderId);
            elseif($status==4):
                $orderHistoryArr=array('orderId'=>$orderId,'state'=>$status,'historyBy'=>2,'actionOwnerId'=>  $this->session->userdata('FE_SESSION_VAR'),'note'=>$note,'awbNo'=>$awbNo,'trackingURL'=>$trackingURL,'logisticsId'=>$logisticsId);
                $this->Order_model->add_history($orderHistoryArr);
                $this->order_shipped($orderId,$logisticsId,$awbNo,$trackingURL);
            endif;
            $this->session->set_flashdata('Message',"Select Status updated for selected order successfully.");
            redirect(BASE_URL.'order/viewlist/');
        }
        
        function order_confirm($orderId){
            $order=$this->Order_model->get_single_order_by_id($orderId);
            if($order->orderType=='GROUP'):
                $this->group_order_confirm_mail($order);
            else:
                $this->single_order_confirm_mail($order);
            endif;
        }
        
        function single_order_confirm_mail($order){
            $mail_template_data['TEMPLATE_ORDER_CONFIRM_ORDER_INFO']=unserialize(base64_decode($order->orderInfo));
            $mail_template_data['TEMPLATE_ORDER_CONFIRM_ORDER_ID']=$order->orderId;
            $mail_template_view_data=$this->load_default_resources();
            $mail_template_view_data['single_order_confirm']=$mail_template_data;
            $userDetails=  $this->User_model->get_details_by_id($order->userId);
            $sellerDetails=  $this->User_model->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
            //pre($order);pre($userDetails);pre($sellerDetails);
            $mail_template_view_data['SellerName']=$sellerDetails[0]->firstName.' '.$sellerDetails[0]->lastName;
            $this->_global_tidiit_mail($userDetails[0]->email, "Your Tidiit order no - TIDIIT-OD-".$order->orderId.' has confirmed', $mail_template_view_data,'single_order_confirm',$userDetails[0]->firstName.' '.$userDetails[0]->lastName);
            
            /// for tidiit support
            $orderDetails=  array();
            $orderDetails[]=$order;
            //pre($orderDetails);die;
            $adminMailData=  $this->load_default_resources();
            $adminMailData['orderDetails']=$orderDetails;
            $orderInfoDataArr=unserialize(base64_decode($order->orderInfo));
            //pre($orderInfoDataArr);die;
            $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
            $adminMailData['userFullName']='Tidiit Inc Support';
            $adminMailData['sellerFullName']=$sellerDetails[0]->firstName.' '.$sellerDetails[0]->lastName;
            $adminMailData['buyerFullName']=$userDetails[0]->firstName.' '.$userDetails[0]->lastName;
            $this->load->model('Siteconfig_model','siteconfig');
            //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
            $supportEmail='judhisahoo@gmail.com';
            $this->_global_tidiit_mail($supportEmail, "Order no - TIDIIT-OD-".$order->orderId.' has confirmed by '.$sellerDetails[0]->firstName.' '.$sellerDetails[0]->lastName, $adminMailData,'support_single_order_confirm','Tidiit Inc Support');
            return TRUE;
        }
        
        function group_order_confirm_mail($order){
            $sellerDetails=  $this->User_model->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
            $orderDetails=  array();
            $orderDetails[]=$order;
            //pre($orderDetails);die;
            $adminMailData=  $this->load_default_resources();
            $adminMailData['orderDetails']=$orderDetails;
            $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
            //pre($orderInfoDataArr);die;
            $currentOrderUser=array();
            foreach($orderInfoDataArr['group']->users As $k => $v){
                if($v->userId==$order->userId){
                    $currentOrderUser=$v;break;
                }
            }
            //pre($currentOrderUser);die;
            $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
            $adminMailData['orderParrentId']=$order->parrentOrderID;
            $userFullName=$currentOrderUser->firstName.' '.$currentOrderUser->lastName;
            $adminMailData['userFullName']=$userFullName;
            $adminMailData['sellerFullName']=$sellerDetails[0]->firstName.' '.$sellerDetails[0]->lastName;
            //pre($adminMailData);die;
            $this->_global_tidiit_mail($currentOrderUser->email, "Your Buying Club order - TIDIIT-OD-".$order->parrentOrderID.' has confirmed.', $adminMailData,'group_order_confirm',$userFullName);
            
            
            $leaderFullName=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            $adminMailData['leaderFullName']=$leaderFullName;
            $this->_global_tidiit_mail($orderInfoDataArr['group']->admin->email, "Your member order of Buying Club order - TIDIIT-OD-".$order->orderId.' has confirmed.', $adminMailData,'group_order_leader_confirm',$userFullName);

            $this->load->model('Siteconfig_model','siteconfig');
            //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
            $supportEmail='judhisahoo@gmail.com';
            $this->_global_tidiit_mail($supportEmail, "Order no - TIDIIT-OD-".$order->orderId.' has confirmed by '.$sellerDetails[0]->firstName.' '.$sellerDetails[0]->lastName, $adminMailData,'support_group_order_confirm','Tidiit Inc Support');
            return TRUE;
        }
	
        function order_shipped($orderId,$logisticsId,$awbNo,$trackingURL){  
            $this->load->model('Logistics_model');
            $order=$this->Order_model->get_single_order_by_id($orderId);
            $logisticDetails=$this->Logistics_model->details($logisticsId);
            $shippedDataArr=array('logisticsName'=>$logisticDetails[0]['title'],'awbNo'=>$awbNo,'trackingURL'=>$trackingURL);
            if($order->orderType=='GROUP'):
                $this->group_order_shipped_mail($order,$shippedDataArr);
            else:
                $this->single_order_shipped_mail($order,$shippedDataArr);
            endif;
        }
        
        function group_order_shipped_mail($order,$shippedDataArr){
            $sellerDetails=  $this->User_model->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
            $orderDetails=  array();
            $orderDetails[]=$order;
            //pre($orderDetails);die;
            $adminMailData=  $this->load_default_resources();
            $adminMailData['orderDetails']=$orderDetails;
            $adminMailData['shippedDataArr']=$shippedDataArr;
            $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
            //pre($orderInfoDataArr);die;
            $currentOrderUser=array();
            foreach($orderInfoDataArr['group']->users As $k => $v){
                if($v->userId==$order->userId){
                    $currentOrderUser=$v;break;
                }
            }
            //pre($currentOrderUser);die;
            $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
            $adminMailData['orderParrentId']=$order->parrentOrderID;
            $userFullName=$currentOrderUser->firstName.' '.$currentOrderUser->lastName;
            $adminMailData['userFullName']=$userFullName;
            $adminMailData['sellerFullName']=$sellerDetails[0]->firstName.' '.$sellerDetails[0]->lastName;
            //pre($adminMailData);die;
            $this->_global_tidiit_mail($currentOrderUser->email, "Your Buying Club order - TIDIIT-OD-".$order->orderId.' has shipped.', $adminMailData,'group_order_shipped',$userFullName);
            
            
            $leaderFullName=$orderInfoDataArr['group']->admin->firstName.' '.$orderInfoDataArr['group']->admin->lastName;
            $adminMailData['leaderFullName']=$leaderFullName;
            $this->_global_tidiit_mail($orderInfoDataArr['group']->admin->email, "Your member order of Buying Club order - TIDIIT-OD-".$order->orderId.' has shipped', $adminMailData,'group_order_leader_shipped',$leaderFullName);

            $this->load->model('Siteconfig_model','siteconfig');
            //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
            $supportEmail='judhisahoo@gmail.com';
            $this->_global_tidiit_mail($supportEmail, "Order no - TIDIIT-OD-".$order->orderId.' has shipped', $adminMailData,'support_group_order_shipped','Tidiit Inc Support');
            return TRUE;
        }
        
        function single_order_shipped_mail($order,$shippedDataArr){
            $mail_template_data['TEMPLATE_SHIPPED_ORDER_INFO']=unserialize(base64_decode($order->orderInfo));
            $mail_template_data['TEMPLATE_SHIPPED_ORDER_ID']=$order->orderId;
            $mail_template_data['TEMPLATE_SHIPPED_ORDER_SHIPPED_INFO']=$shippedDataArr;
            $mail_template_view_data=$this->load_default_resources();
            $mail_template_view_data['single_order_shipped']=$mail_template_data;
            $userDetails=  $this->User_model->get_details_by_id($order->userId);
            $sellerDetails=  $this->User_model->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
            //pre($order);pre($userDetails);pre($sellerDetails);
            $mail_template_view_data['SellerName']=$sellerDetails[0]->firstName.' '.$sellerDetails[0]->lastName;
            $this->_global_tidiit_mail($userDetails[0]->email, "Your Tidiit order no - TIDIIT-OD-".$order->orderId.' has shipped', $mail_template_view_data,'single_order_shipped',$userDetails[0]->firstName.' '.$userDetails[0]->lastName);
            
            /// for tidiit support
            $orderDetails=  array();
            $orderDetails[]=$order;
            //pre($orderDetails);die;
            $adminMailData=  $this->load_default_resources();
            $adminMailData['orderDetails']=$orderDetails;
            $adminMailData['shippedDataArr']=$shippedDataArr;
            $orderInfoDataArr=unserialize(base64_decode($order->orderInfo));
            //pre($orderInfoDataArr);die;
            $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
            $adminMailData['userFullName']='Tidiit Inc Support';
            $adminMailData['sellerFullName']=$sellerDetails[0]->firstName.' '.$sellerDetails[0]->lastName;
            $adminMailData['buyerFullName']=$userDetails[0]->firstName.' '.$userDetails[0]->lastName;
            $this->load->model('Siteconfig_model','siteconfig');
            //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
            $supportEmail='judhisahoo@gmail.com';
            $this->_global_tidiit_mail($supportEmail, "Order no - TIDIIT-OD-".$order->orderId.' has shipped by '.$sellerDetails[0]->firstName.' '.$sellerDetails[0]->lastName, $adminMailData,'support_single_order_shipped','Tidiit Inc Support');
            return TRUE;
        }
}