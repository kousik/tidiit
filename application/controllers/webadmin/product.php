<?php
class Product extends MY_Controller{
	public function __construct(){
            parent::__construct();
            $this->load->model('Product_model');
            $this->load->model('Category_model');
            $this->load->model('User_model');
            $this->load->model('Order_model');
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
                
                $per_page=500;
                
                $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
                //echo 'KK  '.$config['per_page'];die;
                if($page==0){
                    $offcet=0;
                }else{
                    $offcet=$per_page*($page-1);
                }
                
                $PaginationConfig=array(
                    'base_url'=>base_url() . "webadmin/product/viewlist",
                    'total_rows'=>$this->Product_model->admin_list_total($per_page,$offcet),
                    'per_page'=>$per_page,
                    'num_links'=>15,
                    'uri_segment'=>4
                    );
                $this->_my_pagination($PaginationConfig);
                
                
                
                $dataArr=$this->Product_model->admin_list($per_page,$offcet);
                //pre($dataArr);die;
		$data['DataArr']=$dataArr;
                $data["links"] = $this->pagination->create_links();
		$this->load->view('webadmin/product_list',$data);
	}
        
        public function filter(){
            $data=$this->_show_admin_logedin_layout();
                
            $per_page=500;
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            //echo 'KK  '.$config['per_page'];die;
            if($page==0){
                $offcet=0;
            }else{
                $offcet=$per_page*($page-1);
            }

            $data['OrderStateDataArr']=$this->Order_model->get_state();
            $data['DataArr']=$this->Order_model->admin_list($per_page,$offcet);
            $data["links"] = "";
            $this->load->view('admin/order_list',$data);
        }
}