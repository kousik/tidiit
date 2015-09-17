<?php
class City_model extends CI_Model {
	public $_table='sp_city';
	function __construct() {
		parent::__construct();
	}
	
	function get_all(){
		return $this->db->get($this->_table)->result();
	}
	
	
}
?>