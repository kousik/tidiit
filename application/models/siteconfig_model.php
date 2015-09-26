<?php
class Siteconfig_model extends CI_Model {
	public $_table='system_constants';
	public $_table_country='country';
	function __construct() {
		parent::__construct();
	}
	
	function get_all(){
		$this->db->select('*')->from($this->_table);
		$query=$this->db->get();
		return $query->result();
	}
	
	function get_html_head(){
		$value_arr=array('MetaTitle','MetaKeyWord','MetaDescription');
		$this->db->select('ConstantValue')->from($this->_table);
		$this->db->where_in('ConstantName',$value_arr);
		$query = $this->db->get();
		//echo  $this->db->last_query();
		return $query->result();
		
	}
	
	function get_value_by_name($ConfigName){
		$this->db->select('ConstantValue');
		$this->db->from($this->_table);
		$this->db->where('ConstantName',$ConfigName);
		$query = $this->db->get();
		//echo  $this->db->last_query();
		$this->result = $query->result();
		return $this->result[0]->ConstantValue;
	}
	
	function get_country(){
		$this->db->select('*')->from($this->_table_country);
		//$this->db->order_by("CountryID", "desc"); 
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}
	
	function edit($DataArr,$Id){
		$this->db->where('ConstantID',$Id);
		$this->db->update($this->_table,$DataArr);
		//echo  $this->db->last_query();die;
		return TRUE;
	}
}
?>