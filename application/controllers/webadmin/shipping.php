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
            $this->load->view('webadmin/shipping_list',$data);
    }

    function add(){
        $startWeight=$this->input->post('startWeight',TRUE);
        $countryCode=$this->input->post('countryCode',TRUE);
        $endWeight=$startWeight+49;
        $charges=$this->input->post('charges',TRUE);
        $status=$this->input->post('status',TRUE);

        $dataArr=array(
            'startWeight'=>$startWeight,
            'countryCode'=>$countryCode,
            'endWeight'=>$endWeight,
            'charges'=>$charges,
            'status'=>1,
        );
        //pre($dataArr);die;
        $this->Shipping_model->add($dataArr);
        $this->session->set_flashdata('Message','Shipping added successfully.');
        redirect(base_url().'webadmin/shipping/viewlist');
    }


    public function edit(){
        $startWeight=$this->input->post('EditstartWeight',TRUE);
        $countryCode=$this->input->post('EditcountryCode',TRUE);
        $endWeight=$this->input->post('EditendWeight',TRUE);
        $charges=$this->input->post('Editcharges',TRUE);
        $shippingId=$this->input->post('shippingId',TRUE);

        $dataArr=array(
        'startWeight'=>$startWeight,
        'countryCode'=>$countryCode,
        'endWeight'=>$endWeight,
        'charges'=>$charges);

        //print_r($dataArr);die;
        $this->Shipping_model->edit($dataArr,$shippingId);

        $this->session->set_flashdata('Message','shipping updated successfully.');
        redirect(base_url().'webadmin/shipping/viewlist');
    }
    
    public function change_status($shippingId,$Action){
        $this->Shipping_model->change_status($shippingId,$Action);

        $this->session->set_flashdata('Message','Shipping information  status updated successfully.');
        redirect(base_url().'webadmin/shipping/viewlist');
    }

    public function delete($shippingId){
        $this->Shipping_model->delete($shippingId);

        $this->session->set_flashdata('Message','Shipping information  deleted successfully.');
        redirect(base_url().'webadmin/shipping/viewlist');
    }
}