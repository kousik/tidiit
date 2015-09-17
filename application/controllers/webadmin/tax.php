<?php
class Tax extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Tax_model');
		$this->load->model('Zeozone_model');
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['ZeoZoneData']=$this->Zeozone_model->get_list();
		$data['DataArr']=$this->Tax_model->get_all_admin();
		$this->load->view('webadmin/tax_list',$data);
	}
	
	public function add(){
		$name=$this->input->post('name',TRUE);
		$zeoZoneId=$this->input->post('zeoZoneId',TRUE);
		$taxPercentage=$this->input->post('taxPercentage',TRUE);
		$status=$this->input->post('status',TRUE);
		
		$dataArr=array(
		'name'=>$name,
		'zeoZoneId'=>$zeoZoneId,
		'taxPercentage'=>$taxPercentage,
		'status'=>$status
		);
		
		//print_r($dataArr);die;
		$this->Tax_model->add($dataArr);
		
		$this->session->set_flashdata('Message','Tax added successfully.');
		redirect(base_url().'webadmin/tax/viewlist');
	}
	
	
	public function edit(){
		$name=$this->input->post('Editname',TRUE);
		$zeoZoneId=$this->input->post('EditzeoZoneId',TRUE);
		$status=$this->input->post('Editstatus',TRUE);
		$taxId=$this->input->post('taxId',TRUE);
		
		
		$dataArr=array(
		'name'=>$name,
		'zeoZoneId'=>$zeoZoneId,
		'status'=>$status
		);
		
		
		//print_r($dataArr);die;
		
		$this->Tax_model->edit($dataArr,$taxId);
		
		$this->session->set_flashdata('Message','Tax updated successfully.');
		redirect(base_url().'webadmin/tax/viewlist');
	}
	
	
	public function change_status($taxId,$Action){
		$this->Tax_model->change_status($taxId,$Action);
		
		$this->session->set_flashdata('Message','Tax status updated successfully.');
		redirect(base_url().'webadmin/tax/viewlist');
	}
	
	public function delete($taxId){
		$this->Tax_model->delete($taxId);
		
		$this->session->set_flashdata('Message','Tax deleted successfully.');
		redirect(base_url().'webadmin/tax/viewlist');
	}
}
?>