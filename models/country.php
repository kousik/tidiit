<?php

/*
| ----------------------------------------------
| Start Date : 23-11-2010
| Framework : CodeIgniter
| ----------------------------------------------
| Model Video Gallery
| ----------------------------------------------
*/

class Country extends CI_Model {

    public $_table = 'country';
    public $_table_state = 'state';
    public $_table_city = 'city';
    public $result = null;

    function __construct()
    {
        parent::__construct();
    }
    
	
	function get_all(){
		$this->db->select('*');
		$this->db->from($this->_table);
		//$this->db->where('Status <','2');
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}	
	
	function get_state_country($countryId){
            return $this->db->from($this->_table_state)->where('countryId',$countryId)->get()->result();
	}	
	
	function get_all_state(){
		$this->db->select('*');
		$this->db->from($this->_table_state);
		//$this->db->where('Status <','2');
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}
	
	public function get_indian_state(){
		return $this->get_state_country(99);
	}
	
	function get_usa_state(){
		return $this->get_state_country(1);
	}
	
	function get_240(){
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('CountryID <>','1');
		$this->db->where('CountryID <>','99');
		$query = $this->db->get();
		$this->result = $query->result();
		//echo $this->db->last_query();die;
		return $this->result;
	}
	
	function get_country_name($id){
		$this->db->select('CountryName');
		$this->db->from($this->_table);
		$this->db->where('CountryID',$id);
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}
	
	function get_state_name($id){
		$this->db->select('stateName');
		$this->db->from($this->_table_state);
		$this->db->where('stateId',$id);
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}
        
        function get_add_state($countryId,$stateName){
            $Arr=$this->db->select('stateId')->from($this->_table_state)->where('stateName',$stateName)->where('countryId',$countryId)->get()->row_array();
            if(count($Arr)>0){
                return $Arr['stateId'];
            }else{
                $this->db->insert($this->_table_state,array('countryId'=>$countryId,'stateName'=>$stateName));
                return $this->db->insert_id();
            }
        }
        
        function get_category_shipping_state($CountryName){
            return $this->db->query("SELECT s . * FROM `state` AS s JOIN `country` AS c ON ( s.CountryID = c.CountryID ) WHERE c.CountryName LIKE '".$CountryName."'")->result();
        }
        
        function get_all_state_admin($countryId){
            $sql="SELECT s.*,c.countryName FROM `state` as s join `country` AS c ON(s.countryId=c.countryId) WHERE s.countryId=".$countryId;
            return $this->db->query($sql)->result();
        }
        
        function state_change_status(){
            
        }
        
        function state_details($stateId){
            return $this->db->from($this->_table_state)->where('stateId',$stateId)->get()->result();
        }
        
        function get_all_city($stateId){
            $sql="SELECT c.*,s.stateName,co.countryName FROM city AS c LEFT JOIN state AS s ON(c.stateId=s.stateId) LEFT JOIN country AS co ON(c.countryId=co.countryId) WHERE s.stateId=".$stateId;
            return $this->db->query($sql)->result();
        }
        
        function edit_state($dataArr,$stateId){
            $this->db->where('stateId',$stateId);
            $this->db->update($this->_table_state,$dataArr);
            return TRUE;		
        }
        
        function delete_state($stateId){
            $this->db->delete($this->_table_state, array('stateId' => $stateId)); 
            return TRUE;
        }
}

?>
