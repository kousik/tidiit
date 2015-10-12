<?php
class Event extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Event_model');
	}
	
	public function index(){
		redirect(base_url());
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Event_model->get_all();
		$this->load->view('event_list',$data);
	}
	
	public function add(){
		$Name=$this->input->post('Name',TRUE);
		$Status=1;
		
		$dataArr=array(
		'Name'=>$Name,
		'Status'=>$Status
		);
		
		//print_r($dataArr);die;
		$this->Event_model->add($dataArr);
		
		$this->session->set_flashdata('Message','Event added successfully.');
		redirect(base_url().'event/viewlist');
	}
	
	
	public function edit(){
		$Name=$this->input->post('EditName',TRUE);
		//$Answer=$this->input->post('EditAnswer',TRUE);
		$Status=1; //$this->input->post('EditStatus',TRUE);
		$EventID=$this->input->post('EventID',TRUE);
		
		
		$dataArr=array(
		'Name'=>$Name,
            //		'Answer'=>$Answer,
		'Status'=>$Status
		);
		
		
		//print_r($dataArr);die;
		
		$this->Event_model->edit($EventID,$dataArr);
		
		$this->session->set_flashdata('Message','Event updated successfully.');
		redirect(base_url().'event/viewlist');
	}
	
	
	public function change_status($EventID,$Action){
		$this->Event_model->change_status($EventID,$Action);
		
		$this->session->set_flashdata('Message','Event status updated successfully.');
		redirect(base_url().'event/viewlist');
	}
	
	public function delete($EventID){
		$this->Event_model->delete($EventID);
		
		$this->session->set_flashdata('Message','Event deleted successfully.');
		redirect(base_url().'event/viewlist');
	}
}
?>