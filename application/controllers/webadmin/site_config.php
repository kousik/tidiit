<?php
class Site_config extends MY_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('Siteconfig_model');
	}
	
	public function index(){
		$data=$this->_show_admin_logedin_layout();
		$data['SiteConfigDataArr']=$this->Siteconfig_model->get_all();
		$this->load->view('webadmin/site_config',$data);
	}
	
	public function edit(){
		$constantValue=$this->input->post('EditconstantValue',TRUE);
		$description=$this->input->post('Editdescription',TRUE);
		$constantId=$this->input->post('constantId',TRUE);
		
		$dataArr=array(
		'constantValue'=>$constantValue,
		'description'=>$description
		);
		//print_r($dataArr);die;
		$this->Siteconfig_model->edit($dataArr,$constantId);
		
		$this->session->set_flashdata('Message','Site config updated successfully.');
		redirect(base_url().'webadmin/site_config');
	}
}
?>