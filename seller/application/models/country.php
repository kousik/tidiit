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
	
	function get_state_country($CountryID)
	{
		$this->db->select('*');
		$this->db->from($this->_table_state);
		$this->db->where('CountryID',$CountryID);
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
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
		$this->db->select('StateName');
		$this->db->from($this->_table_state);
		$this->db->where('StateID',$id);
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}
        
        function get_add_state($CountryID,$StateName){
            $Arr=$this->db->select('StateID')->from($this->_table_state)->where('StateName',$StateName)->where('CountryID',$CountryID)->get()->row_array();
            if(count($Arr)>0){
                return $Arr['StateID'];
            }else{
                $this->db->insert($this->_table_state,array('CountryID'=>$CountryID,'StateName'=>$StateName));
                return $this->db->insert_id();
            }
        }
        
        function get_category_shipping_state($CountryName){
            return $this->db->query("SELECT s . * FROM `state` AS s JOIN `country` AS c ON ( s.CountryID = c.CountryID ) WHERE c.CountryName LIKE '".$CountryName."'")->result();
        }
}

?>
