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
                'total_rows'=>$this->Order_model->get_admin_total(),
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
            $orderDataArr=$this->Order_model->admin_list($per_page,$offcet);
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
	
	public function update_state(){
		$OrderStateID=$this->input->post('OrderStateID',TRUE);
		$ShortMessage=$this->input->post('ShortMessage',TRUE);
		$TrackingURL=$this->input->post('TrackingURL',TRUE);
		$OrderID=$this->input->post('OrderID',TRUE);
		//echo 'kkk';die;
                
		if($OrderID>0){
                        $HistoryDataArr=array();
                        $HistoryDataArr[]=array('OrderID'=>$OrderID,'State'=>$OrderStateID);
                        $this->Order_model->add_history($HistoryDataArr);
                        //pre('rrr');die;
			$this->Order_model->change_state($OrderID,$OrderStateID);
			$OrderDetailsForMail=$this->Order_model->details($OrderID);
                        
			$body_ex='';
			if($OrderStateID==3){
				if($TrackingURL==""){
					$this->session->set_flashdata('Message','Invalid tracking url provided.');
					redirect(base_url().'admin/order/viewlist');
				}
			}
			$OrderDetails=$this->Order_model->details($OrderID);
			$this->load->library('email');
			
                        $SupportEmail=$this->Siteconfig_model->get_value_by_name('SupportEmail');
                        
			$this->email->from($SupportEmail, 'Daily Plaza Support');
			$this->email->to($OrderDetails[0]->Email,$OrderDetails[0]->FirstName.' '.$OrderDetails[0]->LastName);
			
			//$this->email->cc('another@another-example.com');
			//$this->email->bcc('them@their-example.com');

			$this->email->subject('Your order status change information from Daily Plaza.');
			
                        echo '$OrderStateID = '.$OrderStateID;die;
                        
			if($OrderStateID==2){
				$msg=$this->order_process_email_body($OrderID);
			}else if($OrderStateID==3){
				$msg=$this->order_shipped_email_body($OrderID,$TrackingURL);
				$DataArr=$this->Order_model->get_cart_details_by_order($OrderID);
				$BatchDataArr=array();
				foreach($DataArr As $k){
					$TempDataArr=array('OrderID'=>$OrderID,'UserID'=>$k->UserID,'TrackingURL'=>$TrackingURL,'ProductID'=>$k->ProductID,'Qty'=>$k->Qty);
					//print_r($TempDataArr);die;
					$BatchDataArr[]=$TempDataArr;
				}
				//print_r($BatchDataArr);die;
				$this->Order_model->add_shipped_history($BatchDataArr);
			}elseif($OrderStateID=4){
				$msg=$this->order_complete_email_body($OrderID,$OrderDetails[0]->FirstName,$OrderDetails[0]->LastName);
			}
			
			//$msg .= $body_ex;
			//$msg .= 'If you need any more assistance please fill free to put email by customercare@dailyplaza.com.<br>';
			//$msg .= '<br><br>Thanks,<br>Daily Plaza Team';
			$this->email->message($msg);

			$this->email->send();
			
			
			
			////message to admin.
			$this->load->model('Siteconfig_model');
			$AdminEmail=$this->Siteconfig_model->get_value_by_name('AdminMail');
			
			$OrderDetailsForMail=$this->Order_model->details($OrderID);
			
			$this->email->from('no-reply@daily-plaza.com', 'Daily Plaza Administrator');
			$this->email->to($AdminEmail,'Admin Email');
			
			//$this->email->cc('another@another-example.com');
			//$this->email->bcc('them@their-example.com');

			$this->email->subject('Order status change notification.');
			$msg='Hi ,<br><br>Order of Invoice No : '.$OrderDetailsForMail[0]->InvoiceNo.', vide Order Amount : '.$OrderDetailsForMail[0]->OrderAmount.'.Order Id is  : '.$OrderDetailsForMail[0]->OrderID.'. has been changed to '.$OrderDetailsForMail[0]->OrderStateName.'.<br> ';
			$msg .= '<br><br>Thanks,<br>Daily Plaza Team';
			$this->email->message($msg);

			$this->email->send();
			$this->session->set_flashdata('Message','Order status change successfully.');
		}else{
			$this->session->set_flashdata('Message','Invalid Order Index for status change,Please try again.');
		}
		redirect(base_url().'admin/order/viewlist');
	}
	
}