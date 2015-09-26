<?php
class Sitedata_model extends CI_Model {
	private $_brand='brand';
	private $_country='country';
	function __construct() {
		parent::__construct();
	}
    function add_brand($dataArr){
        $this->db->insert($this->_brand,$dataArr);
        return $this->db->insert_id();
    }    
}