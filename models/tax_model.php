<?php
class Tax_model extends CI_Model{
	public $_table='tax';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_all_admin(){
		$sql="SELECT t.*,z.zeoZoneName FROM tax AS t JOIN zeo_zone AS z ON(t.zeoZoneId=z.zeoZoneId)";
		return $this->db->query($sql)->result();
	}
	
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit($DataArr,$taxId){
		$this->db->where('taxId',$taxId);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function change_status($taxId,$status){
		$this->db->where('taxId',$taxId);
		$this->db->update($this->_table,array('status'=>$status));
		return TRUE;
	}
	
	public function delete($taxId){
		$this->db->delete($this->_table, array('taxId' => $taxId)); 
		return TRUE;
	}
}
?>