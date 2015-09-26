<?php
class Image_model extends CI_Model {
	public $_table='sp_image';
	function __construct() {
		parent::__construct();
	}
	
	public function get_all(){
		$this->db->select('*')->from($this->_table)->where('Status',1);
		return $this->db->get()->result();
	}
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	public function add_batch($dataArr){
		$this->db->insert_batch($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function update($dataArr,$ImageID){
		$this->db->update($this->_table, $dataArr,array('ImageID'=>$ImageID));
		return TRUE;
	}
	
	public function get_by_event_id($EventID){
		$this->db->select('*')->from($this->_table)->where('EventID',$EventID)->order_by('MainImageID','desc');
		return $this->db->get()->result();
	}
	
	public function get_by_id($ImageID){
		$this->db->select('*')->from($this->_table)->where('$ImageID',$ImageID);
		return $this->db->get()->result();
	}
}
