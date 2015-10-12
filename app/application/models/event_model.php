<?php
class Event_model extends CI_Model {
	public $_table='event';
	
	public $result=NULL;
	
	function __construct() {
            parent::__construct();
	}
	
	public function get_all(){
		$this->db->from($this->_table);
		$query=$this->db->get();
		return $query->result();
	}
	
	public function get_all_active_event(){
		$this->db->select('*')->from($this->_table)->where('Status',1);
		$query=$this->db->get();
		return $query->result();
	}
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit($EventID,$DataArr){
                //print_r($DataArr);die;
                $this->db->where('EventID',$EventID);
		$this->db->update($this->_table,$DataArr);
		return TRUE;
	}
	
	public function change_event_state($EventID,$Status){
		$this->db->update($this->_table,array('Status'=>$Status))->where('EventID',$EventID);
		return TRUE;
	}
	
	public function slider_data(){
		$this->db->select('sp_event.*,sp_image.Image');
		$this->db->from($this->_table);
		$this->db->join($this->_table_image,'sp_event.EventID=sp_image.EventID AND sp_image.`MainImageID` IS NOT NULL');
		//SELECT e.*,i.`Image` FROM `sp_event` AS e,`sp_image` AS i WHERE e.`EventID`=i.`EventID` AND i.`MainImageID` IS NOT NULL
		$query=$this->db->get();
		return $query->result();
	}
	
	
}
?>