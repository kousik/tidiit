<?php
class Zeozone_model extends CI_Model{
	public $_table='zeo_zone';
	private $_country='zeo_zone_country';
	private $_state='zeo_zone_state';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_all_admin(){
		$sql="SELECT z.*,c.countryName,c.countryId FROM `zeo_zone` AS z JOIN `zeo_zone_country` AS zc ON(z.zeoZoneId=zc.zeoZoneId) JOIN `country` AS c ON(zc.countryId=c.countryId) WHERE z.status <2";
		return $this->db->query($sql)->result();
	}
	
	public function get_list(){
		return $this->db->select('*')->from($this->_table)->where('status','1')->get()->result();
	}
	
	public function add($dataArr){
		$zeoZoneName=$dataArr["zeoZoneName"];
		$countryId=$dataArr["countryId"];
		$stateIdArr=$dataArr["stateId"];
		$status=$dataArr["status"];
		
		$Arr=array('zeoZoneName'=>$zeoZoneName,'status'=>$status);
                //pre($Arr);die;
		$this->db->insert($this->_table,$Arr);
		$zeoZoneId=$this->db->insert_id();
		
		$CountryArr=array('zeoZoneId'=>$zeoZoneId,'countryId'=>$countryId);
		$this->db->insert($this->_country,$CountryArr);
		
		$newStateArr=array();
		foreach($stateIdArr as $k){
			$sql="SELECT * FROM `zeo_zone_state` WHERE `stateId`='".$k."'";
			if($this->db->query($sql)->num_rows()==0){
				$newStateArr[]=array('zeoZoneId'=>$zeoZoneId,'stateId'=>$k);	
			}
		}
		if(count($newStateArr)>0){
			$this->db->insert_batch($this->_state,$newStateArr);	
		}
		return TRUE;
	}
	
	public function edit($DataArr,$zeoZoneId){
		//print_r($DataArr);die;
		$zeoZoneName=$DataArr["zeoZoneName"];
		$countryId=$DataArr["countryId"];
		$stateIdArr=$DataArr["stateId"];
		$status=$DataArr["status"];
		//print_r($stateIdArr);die;
		$this->db->where('zeoZoneId',$zeoZoneId);
		$this->db->update($this->_table,array('zeoZoneName'=>$zeoZoneName));
		//echo $this->db->last_query();die;
		//echo 'zome naem updated <br>';
		$this->db->where('zeoZoneId',$zeoZoneId);
		$this->db->update($this->_country,array('countryId'=>$countryId));
		//echo 'country updated <br>';die;
		$this->db->delete($this->_state, array('zeoZoneId' => $zeoZoneId)); 
		
		$newStateArr=array();
		foreach($stateIdArr as $k){
			$sql="SELECT * FROM `zeo_zone_state` WHERE `stateId`='".$k."'";
			if($this->db->query($sql)->num_rows()==0){
				$newStateArr[]=array('zeoZoneId'=>$zeoZoneId,'stateId'=>$k);	
			}
		}
		if(count($newStateArr)>0){
			$this->db->insert_batch($this->_state,$newStateArr);	
		}
		return TRUE;		
	}
	
	public function change_status($zeoZoneId,$status){
		$this->db->where('zeoZoneId',$zeoZoneId);
		$this->db->update($this->_table,array('status'=>$status));
		return TRUE;
	}
	
	public function delete($zeoZoneId){
		$this->db->delete($this->_table, array('zeoZoneId' => $zeoZoneId)); 
		$this->db->delete($this->_country, array('zeoZoneId' => $zeoZoneId)); 
		$this->db->delete($this->_state, array('zeoZoneId' => $zeoZoneId)); 
		return TRUE;
	}
	
	public function get_zeoone_statte_name_by_zeo_counrty($countryId,$zeoZoneId){
		$sql="SELECT s.stateName,s.stateId FROM `state` AS s JOIN `zeo_zone_state` AS zs ON(s.stateId=zs.stateId) WHERE s.countryId='".$countryId."' AND zs.zeoZoneId='".$zeoZoneId."'";
		return $this->db->query($sql)->result();
	}
}
?>