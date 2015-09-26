<?php
class Messurement_model extends CI_Model {
	public $_table='big_unit';
	public $_table_small='small_unit';
	function __construct() {
		
	}
        
        
    function get_all(){
       $rs=$this->db->select('bu.*,su.name')->from($this->_table.' bu')->join($this->_table_small.' su','bu.smallUnitId=su.smallUnitId')->get()->result();
       return $rs;
    }
}