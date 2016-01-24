<?php
class Shipping extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Shipping_model');
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Shipping_model->get_all_admin();
		$this->load->view('admin/shipping_list',$data);
	}
	
	
	public function edit(){
		$Title=$this->input->post('Title',TRUE);
		$Cost_US=$this->input->post('Cost_US',TRUE);
		$MinmumCost_US=$this->input->post('MinmumCost_US',TRUE);
		$Cost_IND=$this->input->post('Cost_IND',TRUE);
		$MinmumCost_IND=$this->input->post('MinmumCost_IND',TRUE);
		$Cost_OT=$this->input->post('Cost_OT',TRUE);
		$MinmumCost_OT=$this->input->post('MinmumCost_OT',TRUE);
		$UserNote=$this->input->post('UserNote',TRUE);
		$ShippingID=$this->input->post('ShippingID',TRUE);
		
		
		$dataArr=array(
		'Title'=>$Title,
		'Cost_US'=>$Cost_US,
		'MinmumCost_US'=>$MinmumCost_US,
		'Cost_IND'=>$Cost_IND,
		'MinmumCost_IND'=>$MinmumCost_IND,
		'Cost_OT'=>$Cost_OT,
		'MinmumCost_OT'=>$MinmumCost_OT,
		'UserNote'=>$UserNote
		);
		
		
		//print_r($dataArr);die;
		
		$this->Shipping_model->edit($dataArr,$ShippingID);
		
		$this->session->set_flashdata('Message','shipping updated successfully.');
		redirect(base_url().'admin/shipping/viewlist');
	}
	
}