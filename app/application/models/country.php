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
    public $result = null;

    function __construct()
    {
        parent::__construct();
    }
    
	
	function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->_table);
		//$this->db->where('Status <','2');
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}	
}

?>