<?php
class Logistics extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Logistics_model');
    }

    public function index(){
        redirect(base_url().'admin');
    }

    public function viewlist(){
        $data=$this->_show_admin_logedin_layout();
        $data['DataArr']=$this->Logistics_model->get_all_admin();
        $this->load->view('webadmin/logistics_list',$data);
    }

    public function add(){
        $name=$this->input->post('title',TRUE);
        $registrationNo=$this->input->post('registrationNo',TRUE);
        $address=$this->input->post('address',TRUE);
        $contactNo=$this->input->post('contactNo',TRUE);
        $contactNo1=$this->input->post('contactNo1',TRUE);
        $website=$this->input->post('website',TRUE);
        $contactPersonName=$this->input->post('contactPersonName',TRUE);
        $supportNo=$this->input->post('supportNo',TRUE);

        $status=$this->input->post('status',TRUE);

        $dataArr=array('title'=>$name,'registrationNo'=>$registrationNo,'address'=>$address,'contactNo'=>$contactNo,
        'contactNo1'=>$contactNo1,'website'=>$website,'contactPersonName'=>$contactPersonName,'supportNo'=>$supportNo,
        'status'=>$status
        );

        //print_r($dataArr);die;
        $this->Logistics_model->add($dataArr);

        $this->session->set_flashdata('Message','Logistics added successfully.');
        redirect(base_url().'webadmin/logistics/viewlist');
    }


    public function edit(){
        $name=$this->input->post('Edittitle',TRUE);
        $registrationNo=$this->input->post('EditregistrationNo',TRUE);
        $address=$this->input->post('Editaddress',TRUE);
        $contactNo=$this->input->post('EditcontactNo',TRUE);
        $contactNo1=$this->input->post('EditcontactNo1',TRUE);
        $website=$this->input->post('Editwebsite',TRUE);
        $supportNo=$this->input->post('EditsupportNo',TRUE);
        $contactPersonName=$this->input->post('EditcontactPersonName',TRUE);
        $status=$this->input->post('Editstatus',TRUE);
        $logisticsId=$this->input->post('logisticsId',TRUE);


        $dataArr=array('title'=>$name,'registrationNo'=>$registrationNo,'addrss'=>$address,'contactNo'=>$contactNo,
        'contactNo1'=>$contactNo1,'website'=>$website,'supportNo'=>$supportNo,'contactPersonName'=>$contactPersonName,
        'status'=>$status
        );


        //print_r($dataArr);die;

        $this->Logistics_model->edit($dataArr,$logisticsId);

        $this->session->set_flashdata('Message','Logistics updated successfully.');
        redirect(base_url().'webadmin/logistics/viewlist');
    }


    public function change_status($logisticsId,$Action){
        $this->Logistics_model->change_status($logisticsId,$Action);

        $this->session->set_flashdata('Message','Logistics status updated successfully.');
        redirect(base_url().'webadmin/logistics/viewlist');
    }

    public function delete($logisticsId){
        $this->Logistics_model->delete($logisticsId);

        $this->session->set_flashdata('Message','Logistics deleted successfully.');
        redirect(base_url().'webadmin/logistics/viewlist');
    }
}