<?php
class Discount_model extends CI_Model{
	public $_table='discount';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_all_admin(){
		$sql="SELECT * FROM discount WHERE Status<2";
		return $this->db->query($sql)->result();
	}
	
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit($DataArr,$DiscountID){
		$this->db->where('DiscountID',$DiscountID);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function change_status($DiscountID,$Status){
		$this->db->where('DiscountID',$DiscountID);
		$this->db->update($this->_table,array('Status'=>$Status));
		return TRUE;
	}
	
	public function delete($DiscountID){
		$this->db->delete($this->_table, array('DiscountID' => $DiscountID)); 
		return TRUE;
	}
}
?>