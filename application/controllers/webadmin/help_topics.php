<?php
class Help_topics extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Help_model');
        $this->db->cache_off();
    }

    public function index(){
            redirect(base_url().'webadmin');
    }

    public function viewlist(){
        $data=$this->_show_admin_logedin_layout();
        $data['DataArr']=$this->Help_model->get_all_admin_help_topics();
        $this->load->view('webadmin/help_topics_list',$data);
    }

    public function add(){
        $helpTopics=$this->input->post('helpTopics',TRUE);
        $dataArr=array('helpTopics'=>$helpTopics,'status'=>1);
        //pre($dataArr);die;
        $this->Help_model->add_topics($dataArr);

        $this->session->set_flashdata('Message','Help Topics added successfully.');
        redirect(base_url().'webadmin/help_topics/viewlist');
    }

    public function edit(){
            $helpTopics=$this->input->post('EdithelpTopics',TRUE);
            $status=$this->input->post('Editstatus',TRUE);

            $helpTopicsId=$this->input->post('helpTopicsId',TRUE);


            $dataArr=array('helpTopics'=>$helpTopics,'status'=>$status);
            //print_r($dataArr);die;
            $this->Help_model->edit_topics($dataArr,$helpTopicsId);

            $this->session->set_flashdata('Message','Help Topics updated successfully.');
            redirect(base_url().'webadmin/help_topics/viewlist');
    }

    public function change_status($helpTopicsId,$Action){
            $this->Help_model->change_status_topics($helpTopicsId,$Action);

            $this->session->set_flashdata('Message','Help Topics status updated successfully.');
            redirect(base_url().'webadmin/help_topics/viewlist');
    }

    public function delete($helpTopicsId){
            $this->Help_model->delete_topics($helpTopicsId);

            $this->session->set_flashdata('Message','Help Topics deleted successfully.');
            redirect(base_url().'webadmin/help_topics/viewlist');
    }
}