<?php
class Tidiit_commission extends MY_Controller{
    public function __construct(){
            parent::__construct();
    }

    public function index(){
        redirect(base_url().'webadmin');
    }

    public function viewlist(){
        $data=$this->_show_admin_logedin_layout();
        $rs=$this->User_model->get_tidiit_commission_list();
        //pre($rs);die;
        $data['DataArr']=$rs;
        $this->load->view('webadmin/tidiit_commission_list',$data);
    }
    
    function edit(){
        $commissionPercentage=  trim($this->input->post('commissionPercentage',TRUE));
        $commissionId=  $this->input->post('commissionId',TRUE);
        if($commissionPercentage!=""){
            $this->User_model->edit_tidiit_commission(array('commissionPercentage'=>$commissionPercentage),$commissionId);
            $this->session->set_flashdata('Message','Tidiit commission updated successfully.');
        }else{
            $this->session->set_flashdata('Message','Please enter the commission percentage.');
        }
        redirect(base_url().'webadmin/tidiit_commission/viewlist/');
    }
}