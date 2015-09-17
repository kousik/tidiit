<?php
class Zeozone extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Zeozone_model');
		$this->load->model('Country');
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data["CountryData"]=$this->Country->get_all();
		$DataArr=$this->Zeozone_model->get_all_admin();
		if(count($DataArr)>0){
			foreach($DataArr as $k){
				$stateNameArr=$this->Zeozone_model->get_zeoone_statte_name_by_zeo_counrty($k->countryId,$k->zeoZoneId);
				$stateNames='';
				foreach($stateNameArr AS $kk){
					$stateNames.=trim($kk->stateName).',';
				}
				$k->stateNames=substr($stateNames,0,-1);
			}	
		}
		
		//echo '<pre>';print_r($DataArr);die;
		$data["DataArr"]=$DataArr;
		$this->load->view('webadmin/zeo_zone_list',$data);
	}
	
	public function add(){
		$zeoZoneName=$this->input->post('zeoZoneName',TRUE);
		$countryId=$this->input->post('countryId',TRUE);
		$stateId=$this->input->post('stateId',TRUE);
		
		//echo '<pre>';print_r($_POST);
		if(empty($stateId)){
			$stateId=array();
			$StateDataArr=$this->Country->get_state_country($countryId);
			foreach($StateDataArr as $key){
				$stateId[]=$key->stateId;
			}	
		}
		//print_r($stateId);die;
		$status=$this->input->post('status',TRUE);
		
		$dataArr=array(
		'zeoZoneName'=>$zeoZoneName,
		'countryId'=>$countryId,
		'stateId'=>$stateId,
		'status'=>$status
		);
		
		//print_r($dataArr);die;
		$this->Zeozone_model->add($dataArr);
		$this->session->set_flashdata('Message','Zeo Zone added successfully.');
		redirect(base_url().'webadmin/zeozone/viewlist');
	}
	
	
	public function edit(){
		$zeoZoneName=$this->input->post('EditzeoZoneName',TRUE);
		$countryId=$this->input->post('EditcountryId',TRUE);
		$stateId=$this->input->post('stateId',TRUE);
		$status=$this->input->post('Editstatus',TRUE);
		$zeoZoneId=$this->input->post('zeoZoneId',TRUE);
		//print_r($stateId);die;
		if(empty($stateId)){
			$stateId=array();
			$StateDataArr=$this->Country->get_state_country($countryId);
			foreach($StateDataArr as $key){
				$stateId[]=$key->stateId;
			}	
		}
		
		$dataArr=array(
		'zeoZoneName'=>$zeoZoneName,
		'countryId'=>$countryId,
		'stateId'=>$stateId,
		'status'=>$status
		);
		
		
		//echo '<pre>';print_r($dataArr);die;
		
		$this->Zeozone_model->edit($dataArr,$zeoZoneId);
		
		$this->session->set_flashdata('Message','Zeo Zone updated successfully.');
		redirect(base_url().'webadmin/zeozone/viewlist');
	}
	
	
	public function change_status($zeoZoneId,$Action){
		$this->Zeozone_model->change_status($zeoZoneId,$Action);
		
		$this->session->set_flashdata('Message','Zeo Zone status updated successfully.');
		redirect(base_url().'webadmin/zeozone/viewlist');
	}
	
	public function delete($zeoZoneId){
		$this->Zeozone_model->delete($zeoZoneId);
		
		$this->session->set_flashdata('Message','Zeo Zone deleted successfully.');
		redirect(base_url().'webadmin/zeozone/viewlist');
	}
}
?>