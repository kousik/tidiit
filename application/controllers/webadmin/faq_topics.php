<?php
class Faq_topics extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Faq_model');
        $this->db->cache_off();
    }

    public function index(){
            redirect(base_url().'webadmin');
    }

    public function viewlist(){
        $data=$this->_show_admin_logedin_layout();
        $data['DataArr']=$this->Faq_model->get_all_admin_topics();
        $this->load->view('webadmin/faq_topics_list',$data);
    }

    public function add(){
        $faqTopics=$this->input->post('faqTopics',TRUE);
        $faqTopicsType=$this->input->post('faqTopicsType',TRUE);
        
        
        $dataArr=array(
        'faqTopics'=>$faqTopics,
        'faqTopicsType'=>$faqTopicsType,
        'status'=>1
        );
        //pre($dataArr);die;
        $this->Faq_model->add_topics($dataArr);

        $this->session->set_flashdata('Message','FAQ Topics added successfully.');
        redirect(base_url().'webadmin/faq_topics/viewlist');
    }

    public function edit(){
            $faqTopics=$this->input->post('EditfaqTopics',TRUE);
            $faqTopicsType=$this->input->post('EditfaqTopicsType',TRUE);
            $status=$this->input->post('Editstatus',TRUE);

            $faqTopicsId=$this->input->post('faqTopicsId',TRUE);


            $dataArr=array(
                'faqTopics'=>$faqTopics,
                'faqTopicsType'=>$faqTopicsType,
                'status'=>$status
                );
            //print_r($dataArr);die;
            $this->Faq_model->edit_topics($dataArr,$faqTopicsId);

            $this->session->set_flashdata('Message','FAQ Topics updated successfully.');
            redirect(base_url().'webadmin/faq_topics/viewlist');
    }

    public function change_status($faqTopicsId,$Action){
            $this->Faq_model->change_status_topics($faqTopicsId,$Action);

            $this->session->set_flashdata('Message','FAQ Topics status updated successfully.');
            redirect(base_url().'webadmin/faq_topics/viewlist');
    }

    public function delete($faqTopicsId){
            $this->Faq_model->delete_topics($faqTopicsId);

            $this->session->set_flashdata('Message','FAQ Topics deleted successfully.');
            redirect(base_url().'webadmin/faq_topics/viewlist');
    }
}