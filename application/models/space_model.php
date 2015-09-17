<?php
class Space_model extends CI_Model {
	public $_table='sp_space';
	function __construct() {
		
	}
	
	public function getl_all(){
		$this->db->select('*')->from($this->_table)->order_by('SpaceID','desc')->limit(1);
		return $this->db->get()->result();
	}
}
?>