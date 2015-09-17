<?php
class Partners_model extends CI_Model {
	public $_table='sp_partners';
	function __construct() {
		
	}
	
	public function get_all(){
		$this->db->select('*')->from($this->_table)->where('Status','1');
		return $this->db->get()->result();
	}
}
?>