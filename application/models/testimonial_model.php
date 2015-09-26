<?php
class Testimonial_model extends CI_Model {
	public $_table='testimonial';
	function __construct() {
		
	}
	
	function get_all_admin(){
		$sql="SELECT t.*,u.FName,u.LName FROM `user` AS u,`testimonial` AS t WHERE t.UserID=u.UserID";
		return $this->db->query($sql)->result();
	}
	
	function get_all(){
		$this->db->select('*')->from($this->_table)->where('Status','1')->order_by('TestimonialID','DESC')->limit(1);
		//echo $this->db->last_query();
		return $this->db->get()->result();
	}
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function get_details_by_id($TestimonialID){
		$this->db->select('*')->from($this->_table)->like('TestimonialID',$TestimonialID);
		return $this->db->get()->result();
	}
	
	public function edit($DataArr,$TestimonialID){
		$this->db->where('TestimonialID',$TestimonialID);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function delete($TestimonialID){
		$this->db->delete($this->_table, array('TestimonialID' => $TestimonialID)); 
		return TRUE;
	}
}
?>