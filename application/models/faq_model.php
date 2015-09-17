<?php
class Faq_model extends CI_Model{
	public $_table='faq';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_all_admin(){
		$this->db->select('*')->from($this->_table)->where('Status <',2);
		return $this->db->get()->result();
	}
	
	public function get_answer($Question){
		$this->db->select('*')->from($this->_table)->like('Question',$Question);
		return $this->db->get()->result();
	}
        
        function get_details($FaqID){
            return $this->db->from($this->_table)->where('FaqID',$FaqID)->get()->result();
        }
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit($DataArr,$FaqID){
		$this->db->where('FaqID',$FaqID);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function change_status($FaqID,$Status){
		$this->db->where('FaqID',$FaqID);
		$this->db->update($this->_table,array('Status'=>$Status));
		return TRUE;
	}
	
	public function delete($FaqID){
		$this->db->delete($this->_table, array('FaqID' => $FaqID)); 
		return TRUE;
	}
	
	public function get_all(){
		$this->db->select('*')->from($this->_table)->where('Status',1);
		return $this->db->get()->result();
	}
}
?>