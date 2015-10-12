<?php
class Site_config extends MY_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('Siteconfig_model');
	}
	
	public function index(){
		$data=$this->_show_admin_logedin_layout();
		$data['SiteConfigDataArr']=$this->Siteconfig_model->get_all();
		$this->load->view('site_config',$data);
	}
	
	public function edit(){
		$ConstantValue=$this->input->post('EditConstantValue',TRUE);
		$Description=$this->input->post('EditDescription',TRUE);
		$ConstantID=$this->input->post('ConstantID',TRUE);
		
		$dataArr=array(
		'ConstantValue'=>$ConstantValue,
		'Description'=>$Description
		);
		//print_r($dataArr);die;
		$this->Siteconfig_model->edit($dataArr,$ConstantID);
		
		$this->session->set_flashdata('Message','Site config updated successfully.');
		redirect(base_url().'site_config');
	}
}
?>