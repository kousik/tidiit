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
            $logisticsId=  $this->input->post('logisticsId',TRUE);
            $awbNo=  $this->input->post('awbNo',TRUE);
            $trackingURL=  $this->input->post('trackingURL',TRUE);
            
            if($status==3):
                $this->order_confirm($orderId);
            elseif($status==4):
                $this->order_shipped($orderId,$logisticsId,$awbNo,$trackingURL);
            endif;
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
            
        }
        
        function group_order_confirm_mail($order){
            
        }
	
        function order_shipped($orderId,$logisticsId,$awbNo,$trackingURL){
            
        }
}