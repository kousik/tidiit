<?php
class Event_model extends CI_Model {
	public $_table='sp_event';
	public $_table_image='sp_image';
	public $_table_sponsor_package_features='sp_sponsorship_package_feature';
	public $_table_sponsorship_package='sp_sponsorship_package';
	public $_table_category='sp_event_category';
	public $_table_age='sp_event_age';
	
	public $result=NULL;
	
	function __construct() {
		
	}
	
	public function get_all(){
		$this->db->select($this->_table);
		$query=$this->db->get();
		return $query->result();
	}
	
	public function get_all_active_event(){
		$this->db->select('*')->from($this->_table)->where('Status',1);
		$query=$this->db->get();
		return $query->result();
	}
	
	public function add_event($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit_event($EventID,$DataArr){
		$this->db->update($this->_table,$DataArr)->where('EventID',$EventID);
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
	
	public function get_event_gllery_image(){
		$this->db->select('sp_image.*');
		$this->db->from($this->_table);
		$this->db->join($this->_table_image,'sp_event.EventID=sp_image.EventID AND sp_image.`MainImageID` IS NOT NULL');
		//SELECT e.*,i.`Image` FROM `sp_event` AS e,`sp_image` AS i WHERE e.`EventID`=i.`EventID` AND i.`MainImageID` IS NOT NULL
		$this->db->order_by('sp_image.EventID','DESC');
		$query=$this->db->get();
		return $query->result();
	}
	
	public function get_featured_event(){
		$this->db->select('EventID,EventTitle,EventDetails')->from($this->_table)->where('Featured','1')->order_by('FeaturedDate desc,EventID desc')->limit(3);
		return $this->db->get()->result();
	}
	
	public function get_3_completed_event($UserID){
		$this->db->select('*')->from($this->_table)->where('UserID',$UserID)->where('EventEndDate <','NOW()',FALSE)->order_by('EventEndDate desc,EventID desc ')->limit(3);
		return $this->db->get()->result();
	}
	
	public function get_3_upcomming_event($UserID){
		$this->db->select('*')->from($this->_table)->where('UserID',$UserID)->where('EventEndDate >','NOW()',FALSE)->order_by('EventEndDate desc,EventID desc ')->limit(3);
		return $this->db->get()->result();
	}
	
	public function get_sponsored_event($UserID){
		$sql="SELECT e.EventTitle,e.EventID FROM sp_event AS e,sp_event_sponsor AS es WHERE es.EventID=e.EventID AND es.SponsorID='".$UserID."' AND es.Status=1";
		return $this->db->query($sql)->result();
	}
	
	public function get_recomended_event($UserID){
		$sql="SELECT e.EventID,e.EventTitle FROM sp_event AS e,sp_sponsor_category AS sc,sp_event_category AS ec WHERE sc.SponsorID='".$UserID."' AND sc.CategoryID=ec.CategoryID AND e.EventID=ec.EventID AND e.EventEndDate>NOW() GROUP BY e.EventID";
		return $this->db->query($sql)->result();
	}
	
	public function get_sponsorship_package_features(){
		$this->db->select('*')->from($this->_table_sponsor_package_features)->where('Status',1);
		return $this->db->get()->result();
	}
	
	public function get_base_sponsorship_package_specification(){
		return $this->db->from($this->_table_sponsor_package_features)->where('Status',1)->count_all_results();
	}
	
	public function add_sponsorship_package($DataArr){
		$this->db->insert($this->_table_sponsorship_package,$DataArr);
		return $this->db->insert_id();
	}
	
	public function add_batch_event_category($dataArr){
		$this->db->insert_batch($this->_table_category,$dataArr);
		return $this->db->insert_id();
	}
	
	public function add_event_caategory($dataArr){
		$this->db->insert($this->_table_category,$dataArr);
		return $this->db->insert_id();
	}
	
	public function add_batch_event_age($dataArr){
		$this->db->insert_batch($this->_table_age,$dataArr);
		return $this->db->insert_id();
	}
	
	public function add_event_age($dataArr){
	$this->db->insert($this->_table_age,$dataArr);
		return $this->db->insert_id();
	}
	
	public function get_details($EventID){
		$this->db->select('*')->from($this->_table)->where('EventID',$EventID);
		return $this->db->get()->result();
	}
	
	public function get_event_city($EventID){
		$sql="SELECT c.CategoryName FROM sp_event_category AS ec,sp_category AS c WHERE ec.CategoryID=c.CategoryID AND ec.EventID='".$EventID."'";
		return $this->db->query($sql)->result();
	}
	
	public function get_event_age($EventID){
		$this->db->select('*')->from($this->_table_age)->where('EventID',$EventID);
		return $this->db->get()->result();
	}
	
	public function get_event_sponsorship_package($EventID){
		$this->db->select('*')->from($this->_table_sponsorship_package)->where('EventID',$EventID);
		return $this->db->get()->result();
	}
	
	public function search_event($WhereString){
		$sql="SELECT e.EventTitle,e.EventID,e.City,e.EventStartDate,e.EventEndDate,c.CategoryName,i.Image FROM sp_event AS e,sp_event_category AS ec,sp_image i,sp_category AS c WHERE e.EventID=i.EventID AND i.MainImageID IS NOT NULL AND ec.CategoryID=c.CategoryID AND ec.EventID=e.EventID ".$WhereString;
		//die($sql);
		return $this->db->query($sql)->result();
	}
}
?>