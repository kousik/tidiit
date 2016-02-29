<?php
class Faq extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Faq_model');
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Faq_model->get_all_admin();
		$data['topicsDataArr']=$this->Faq_model->get_all_active_topic();
		$this->load->view('webadmin/faq_list',$data);
	}
	
        function view_edit($faqId){
            $data=$this->_show_admin_logedin_layout();
            $details=$this->Faq_model->get_details($faqId);
            $data['DataArr']=$details;
            $data['topicsDataArr']=$this->Faq_model->get_topics_by_type($details[0]->type);
            $data['faqId']=$faqId;
            $this->load->view('webadmin/faq_edit',$data);
        }
        
	public function add(){
		$question=$this->input->post('question',TRUE);
		$answer=$this->input->post('answer',TRUE);
		$status=$this->input->post('status',TRUE);
		$type=$this->input->post('type',TRUE);
		$faqTopicsId=$this->input->post('faqTopicsId',TRUE);
		
		$dataArr=array(
		'question'=>$question,
		'answer'=>$answer,
		'status'=>$status,
		'type'=>$type,
		'faqTopicsId'=>$faqTopicsId
		);
		//print_r($dataArr);die;
                
		$this->Faq_model->add($dataArr);
		
		$this->session->set_flashdata('Message','FAQ added successfully.');
		redirect(base_url().'webadmin/faq/viewlist');
	}
	
	
        
	public function edit(){
		$question=$this->input->post('Editquestion',TRUE);
		$answer=$this->input->post('Editanswer',TRUE);
		$type=$this->input->post('Edittype',TRUE);
		//$status=$this->input->post('Editstatus',TRUE);
		$faqId=$this->input->post('faqId',TRUE);
		
		
		$dataArr=array(
		'question'=>$question,
		'answer'=>$answer,
		'type'=>$type
		);
		
		
		//print_r($dataArr);die;
		
		$this->Faq_model->edit($dataArr,$faqId);
		
		$this->session->set_flashdata('Message','FAQ updated successfully.');
		redirect(base_url().'webadmin/faq/viewlist');
	}
	
	
	public function change_status($faqId,$Action){
		$this->Faq_model->change_status($faqId,$Action);
		
		$this->session->set_flashdata('Message','FAQ status updated successfully.');
		redirect(base_url().'webadmin/faq/viewlist');
	}
	
	public function delete($faqId){
		$this->Faq_model->delete($faqId);
		
		$this->session->set_flashdata('Message','FAQ deleted successfully.');
		redirect(base_url().'webadmin/faq/viewlist');
	}
}
?>