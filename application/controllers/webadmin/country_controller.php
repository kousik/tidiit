<?php
class Country_controller extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Country');
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Country->get_all();
                $data['accessType']='';
		$this->load->view('webadmin/country_list',$data);
	}
        
        public function viewStatList($countryId){
            $data=$this->_show_admin_logedin_layout();
            $dataArr=$this->Country->get_all_state_admin($countryId);
            //pre($dataArr);die;
            $data['DataArr']=$dataArr;
            $data['accessType']='state';
            $data['countryId']=$countryId;
            $this->load->view('webadmin/state_list',$data);
	}
        
        public function viewCityList($stateId){
            $data=$this->_show_admin_logedin_layout();
            $stateDetails=  $this->Country->state_details($stateId);
            $data['DataArr']=$this->Country->get_all_city($stateId);
            $data['accessType']='city';
            $data['countryId']=$stateDetails[0]->countryId;
            $this->load->view('webadmin/city_list',$data);
        }
        
	
	public function add(){
            $this->session->set_flashdata('Message','Country added successfully.');
            redirect(base_url().'webadmin/country_controller/viewlist');	
	}
        
        function add_state(){
            $stateName=$this->input->post('stateName',true);
            $status=$this->input->post('status',true);
            $countryId=$this->input->post('countryId',true);
            $this->Country->get_add_state($countryId,$stateName);
            $this->session->set_flashdata('Message','State added successfully.');
            redirect(base_url().'webadmin/country_controller/viewStatList/'.$countryId);	
        }
        
        function add_city(){
            $city=$this->input->post('city',true);
            $status=$this->input->post('status',true);
            $countryId=$this->input->post('countryId',true);
            $stateId=$this->input->post('stateId',true);
            $this->Country->get_add_state(array('city'=>$city,'status'=>$status,'stateId'=>$stateId,'countryId'=>$countryId));
            $this->session->set_flashdata('Message','City added successfully.');
            redirect(base_url().'webadmin/country_controller/viewCityList/'.$countryId);	
        }
	
        public function edit(){
            $this->session->set_flashdata('Message','Country edit successfully.');
            redirect(base_url().'webadmin/country_controller/viewlist');	
	}
        
        function edit_state(){
            $stateName=$this->input->post('EditstateName',true);
            $status=$this->input->post('Editstatus',true);
            $countryId=$this->input->post('countryId',true);
            
            $stateId=$this->input->post('stateId',true);
            $this->Country->edit_state(array('stateName'=>$stateName,'status'=>$status),$stateId);
            $this->session->set_flashdata('Message','State edit successfully.');
            redirect(base_url().'webadmin/country_controller/viewStatList/'.$countryId);	
        }
        
        
        
	public function change_status($countryId,$Action){
            foreach ($this->Country->get_state_country($countryId) As $k){
                $this->state_change_status($k->stateId);
            }
            $this->Country->edit(array('status'=>$Action),$countryId);

            $this->session->set_flashdata('Message','Country status updated successfully.');
            redirect(base_url().'webadmin/country_controller/viewlist');
	}
        
        public function state_change_status($stateId,$Action){
            $detailsArr=  $this->Country->state_details($stateId);
            foreach ($this->Country->get_all_city($stateId) As $k){
                $this->city_change_status($k->cityId);
            }
            $this->Country->edit_state(array('status'=>$Action),$stateId);

            $this->session->set_flashdata('Message','Country status updated successfully.');
            redirect(base_url().'webadmin/country_controller/viewStatList/'.$detailsArr[0]->countryId);
	}
        
        function city_change_status($cityId){
            return true;
        }
	
	public function delete($countryId){
            foreach ($this->Country->get_state_country($countryId) As $k){
                $this->state_delete($k->stateId);
            }
            $this->Country->delete($countryId);

            $this->session->set_flashdata('Message','Country deleted successfully.');
            redirect(base_url().'webadmin/country_controller/viewlist');
	}
        
        function state_delete($stateId){
            $detailsArr=  $this->Country->state_details($stateId);
            foreach ($this->Country->get_all_city($stateId) As $k){
                $this->delete_city($k->cityId);
            }
            
            $this->Country->delete_state($stateId);

            $this->session->set_flashdata('Message','state deleted successfully.');
            redirect(base_url().'webadmin/country_controller/viewStatList/'.$detailsArr[0]->countryId);
        }
        
        function delete_city($cityId){
            return TRUE;
        }
	
}
?>