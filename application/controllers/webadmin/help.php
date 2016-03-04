<?php
class Help extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Help_model');
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Help_model->get_all_admin();
		$data['topicsDataArr']=$this->Help_model->get_all_active_topic();
		$this->load->view('webadmin/help_list',$data);
	}
	
        function view_edit($helpId){
            $data=$this->_show_admin_logedin_layout();
            $details=$this->Help_model->get_details($helpId);
            $data['DataArr']=$details;
            $data['topicsDataArr']=$this->Help_model->get_all_active_topic();;
            $data['helpId']=$helpId;
            $this->load->view('webadmin/help_edit',$data);
        }
        
	public function add(){
		$question=$this->input->post('question',TRUE);
		$answer=$this->input->post('answer',TRUE);
		$status=$this->input->post('status',TRUE);
		$helpTopicsId=$this->input->post('helpTopicsId',TRUE);
		
		$dataArr=array(
		'question'=>$question,
		'answer'=>$answer,
		'status'=>$status,
		'helpTopicsId'=>$helpTopicsId
		);
		//print_r($dataArr);die;
                
		$this->Help_model->add($dataArr);
		
		$this->session->set_flashdata('Message','Help added successfully.');
		redirect(base_url().'webadmin/help/viewlist');
	}
	
	
        
	public function edit(){
		$question=$this->input->post('Editquestion',TRUE);
		$answer=$this->input->post('Editanswer',TRUE);
		$helpTopicsId=$this->input->post('EdithelpTopicsId',TRUE);
		$helpId=$this->input->post('helpId',TRUE);
		//echo '$helpId = '.$helpId;die;
		
		$dataArr=array(
		'question'=>$question,
		'answer'=>$answer,
		'helpTopicsId'=>$helpTopicsId
		);
		
		
		//print_r($dataArr);die;
		
		$this->Help_model->edit($dataArr,$helpId);
		
		$this->session->set_flashdata('Message','Help updated successfully.');
		redirect(base_url().'webadmin/help/viewlist');
	}
	
	
	public function change_status($helpId,$Action){
		$this->Help_model->change_status($helpId,$Action);
		
		$this->session->set_flashdata('Message','Help status updated successfully.');
		redirect(base_url().'webadmin/help/viewlist');
	}
	
	public function delete($helpId){
		$this->Help_model->delete($helpId);
		
		$this->session->set_flashdata('Message','Help deleted successfully.');
		redirect(base_url().'webadmin/help/viewlist');
	}
}