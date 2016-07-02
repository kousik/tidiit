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
            $data['stateId']=$stateId;
            $this->load->view('webadmin/city_list',$data);
        }
        
        public function viewZipList($cityId){
            $data=$this->_show_admin_logedin_layout();
            $cityDetails=  $this->Country->city_details($cityId);
            $data['DataArr']=$this->Country->get_all_zip($cityId);
            $data['accessType']='zip';
            $data['stateId']=$cityDetails[0]->stateId;
            $data['cityId']=$cityId;
            $this->load->view('webadmin/zip_list',$data);
        }
        
        function viewLocalityList($zipId){
            $data=$this->_show_admin_logedin_layout();
            $Details=  $this->Country->zip_details($zipId);
            $data['DataArr']=$this->Country->get_all_locality($zipId);
            $data['accessType']='zip';
            $data['cityId']=$Details[0]->cityId;
            $data['zipId']=$zipId;
            $this->load->view('webadmin/locality_list',$data);
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
            $this->session->set_flashdata('Message','State/County added successfully.');
            redirect(base_url().'webadmin/country_controller/viewStatList/'.$countryId);	
        }
        
        function add_city(){
            $city=$this->input->post('city',true);
            $status=$this->input->post('status',true);
            $countryId=$this->input->post('countryId',true);
            $stateId=$this->input->post('stateId',true);
            $this->Country->get_add_city(array('city'=>$city,'status'=>$status,'stateId'=>$stateId,'countryId'=>$countryId));
            $this->session->set_flashdata('Message','City added successfully.');
            redirect(base_url().'webadmin/country_controller/viewCityList/'.$stateId);	
        }
	
        function add_zip(){
            $zip=$this->input->post('zip',true);
            $status=$this->input->post('status',true);
            $cityId=$this->input->post('cityId',true);
            $dataArr=array('zip'=>$zip,'status'=>$status,'cityId'=>$cityId);
            //pre($dataArr);die;
            $this->Country->get_add_zip($dataArr);
            
            $this->session->set_flashdata('Message','Zip/Postal Box Zip Code added successfully.');
            redirect(base_url().'webadmin/country_controller/viewZipList/'.$cityId);	
        }
        
        function add_locality(){
            //pre($_POST);die;
            $zipId=$this->input->post('zipId',true);
            $status=$this->input->post('status',true);
            $locality=$this->input->post('locality',true);
            $dataArr=array('locality'=>$locality,'status'=>$status,'zipId'=>$zipId);
            //pre($dataArr);die;
            $this->Country->get_add_locality($dataArr);
            
            $this->session->set_flashdata('Message','Locality added successfully.');
            redirect(base_url().'webadmin/country_controller/viewLocalityList/'.$zipId);	
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
            $this->session->set_flashdata('Message','State/County edit successfully.');
            redirect(base_url().'webadmin/country_controller/viewStatList/'.$countryId);	
        }
        
        function edit_city(){
            $city=$this->input->post('Editcity',true);
            $status=$this->input->post('Editstatus',true);
            $stateId=$this->input->post('stateId',true);
            $cityId=$this->input->post('cityId',true);
            
            $this->Country->edit_city(array('city'=>$city,'status'=>$status),$cityId);
            $this->session->set_flashdata('Message','City edit successfully.');
            redirect(base_url().'webadmin/country_controller/viewCityList/'.$stateId);	
        }
        
        function edit_zip(){
            //pre($_POST);die;
            $zip=$this->input->post('Editzip',true);
            $status=$this->input->post('Editstatus',true);
            $zipId=$this->input->post('zipId',true);
            $cityId=$this->input->post('cityId',true);
            
            $this->Country->edit_zip(array('zip'=>$zip,'status'=>$status),$zipId);
            $this->session->set_flashdata('Message','Zip/Postal Box Zip Code edit successfully.');
            redirect(base_url().'webadmin/country_controller/viewZipList/'.$cityId);	
        }
        
        function edit_locality(){
            //pre($_POST);die;
            $locality=$this->input->post('Editlocality',true);
            $status=$this->input->post('Editstatus',true);
            $zipId=$this->input->post('zipId',true);
            $localityId=$this->input->post('localityId',true);
            
            $this->Country->edit_locality(array('locality'=>$locality,'status'=>$status),$localityId);
            $this->session->set_flashdata('Message','Locality edit successfully.');
            redirect(base_url().'webadmin/country_controller/viewLocalityList/'.$zipId);	
        }
        
	public function change_status($countryId,$Action){
            foreach ($this->Country->get_state_country($countryId) As $k){
                $this->state_change_status1($k->stateId,$Action);
            }
            $this->Country->edit(array('status'=>$Action),$countryId);

            $this->session->set_flashdata('Message','Country status updated successfully.');
            redirect(base_url().'webadmin/country_controller/viewlist');
	}
        
        public function state_change_status1($stateId,$Action){
            foreach ($this->Country->get_all_city($stateId) As $k){
                $this->city_change_status1($k->cityId,$Action);
            }
            $this->Country->edit_state(array('status'=>$Action),$stateId);
	}
        
        public function state_change_status($stateId,$Action){
            $detailsArr=  $this->Country->state_details($stateId);
            foreach ($this->Country->get_all_city($stateId) As $k){
                $this->city_change_status1($k->cityId,$Action);
            }
            $this->Country->edit_state(array('status'=>$Action),$stateId);

            $this->session->set_flashdata('Message','State/County status updated successfully.');
            redirect(base_url().'webadmin/country_controller/viewStatList/'.$detailsArr[0]->countryId);
	}
        
        function city_change_status1($cityId,$Action){
            foreach ($this->Country->get_all_zip($cityId) As $k){
                $this->zip_change_status1($k->zipId,$Action);
            }
            $this->Country->edit_city(array('status'=>$Action),$cityId);
        }
        
        function city_change_status($cityId,$Action){
            $detailsArr=  $this->Country->city_details($cityId);
            foreach ($this->Country->get_all_zip($cityId) As $k){
                $this->zip_change_status1($k->zipId,$Action);
            }
            $this->Country->edit_city(array('status'=>$Action),$cityId);

            $this->session->set_flashdata('Message','City status updated successfully.');
            redirect(base_url().'webadmin/country_controller/viewCityList/'.$detailsArr[0]->stateId);
        }
        
        function zip_change_status1($zipId,$Action){
            foreach ($this->Country->get_all_locality($zipId) As $k){
                $this->locality_change_status1($k->localityId,$Action);
            }
            $this->Country->edit_zip(array('status'=>$Action),$zipId);
        }
        
        function zip_change_status($zipId,$Action){
            $detailsArr=  $this->Country->zip_details($zipId);
            foreach ($this->Country->get_all_locality($zipId) As $k){
                $this->locality_change_status1($k->localityId,$Action);
            }
            $this->Country->edit_zip(array('status'=>$Action),$zipId);
            //echo $this->db->last_query();die;
            $this->session->set_flashdata('Message','Zip/Postal Box Zip Code status updated successfully.');
            redirect(base_url().'webadmin/country_controller/viewZipList/'.$detailsArr[0]->cityId);
        }
        
        function locality_change_status1($localityId,$Action){
            $this->Country->edit_locality(array('status'=>$Action),$localityId);
            //echo $this->db->last_query();
        }
        
        function locality_change_status($localityId,$Action){
            $detailsArr=  $this->Country->locality_details($localityId);
            $this->Country->edit_locality(array('status'=>$Action),$localityId);

            $this->session->set_flashdata('Message','Locality status updated successfully.');
            redirect(base_url().'webadmin/country_controller/viewLocalityList/'.$detailsArr[0]->zipId);
        }
	
	public function delete($countryId){
            foreach ($this->Country->get_state_country($countryId) As $k){
                $this->state_delete1($k->stateId);
            }
            $this->Country->delete($countryId);

            $this->session->set_flashdata('Message','Country deleted successfully.');
            redirect(base_url().'webadmin/country_controller/viewlist');
	}
        
        function state_delete1($stateId){
            foreach ($this->Country->get_all_city($stateId) As $k){
                $this->city_delete1($k->cityId);
            }
            $this->Country->delete_state($stateId);
        }
        
        function state_delete($stateId){
            $detailsArr=  $this->Country->state_details($stateId);
            //pre($detailsArr);die;
            foreach ($this->Country->get_all_city($stateId) As $k){
                $this->city_delete1($k->cityId);
            }
            
            $this->Country->delete_state($stateId);
            $this->session->set_flashdata('Message','State/County deleted successfully.');
            redirect(base_url().'webadmin/country_controller/viewStatList/'.$detailsArr[0]->countryId);
        }
        
        function city_delete1($cityId){
            foreach ($this->Country->get_all_zip($cityId) As $k){
                $this->zip_delete1($k->zipId);
            }
            $this->Country->delete_city($cityId);
        }
        
        function city_delete($cityId){
            $detailsArr=  $this->Country->city_details($cityId);
            foreach ($this->Country->get_all_zip($cityId) As $k){
                $this->zip_delete1($k->zipId);
            }
            
            $this->Country->delete_city($cityId);
            $this->session->set_flashdata('Message','City deleted successfully.');
            redirect(base_url().'webadmin/country_controller/viewCityList/'.$detailsArr[0]->stateId);
        }
        
        function zip_delete1($zipId){
            foreach ($this->Country->get_all_locality($zipId) As $k){
                $this->locality_delete1($k->localityId);
            }
            $this->Country->delete_zip($zipId);
        }
        
        function zip_delete($zipId){
            $detailsArr=  $this->Country->zip_details($zipId);
            foreach ($this->Country->get_all_locality($zipId) As $k){
                $this->locality_delete1($k->localityId);
            }
            
            $this->Country->delete_zip($zipId);
            $this->session->set_flashdata('Message','Zip/Postal Box Zip Code deleted successfully.');
            redirect(base_url().'webadmin/country_controller/viewZipList/'.$detailsArr[0]->cityId);
        }
        
        function locality_delete1($localityId){
            $this->Country->delete_locality($localityId);
        }
        
        function locality_delete($localityId){
            $detailsArr=  $this->Country->locality_details($localityId);
            $this->Country->delete_locality($localityId);
            $this->session->set_flashdata('Message','Locality deleted successfully.');
            redirect(base_url().'webadmin/country_controller/viewLocalityList/'.$detailsArr[0]->zipId);
        }
        
    function dyn_add_zip($zipStart,$zipLast,$cityId){
        //echo $zipStart.' == '.$zipLast.' == '.$cityId;die;
        $batchDataArr=array();
        for($i=$zipStart;$i<$zipLast+1;$i++):
            $innerDataArr=array();
            $innerDataArr=array('zip'=>$i,'cityId'=>$cityId);
            $batchDataArr[]=$innerDataArr;
        endfor;
        //pre($batchDataArr);die;
        $this->db->insert_batch('zip', $batchDataArr); 
    }
}