<?php
class Banner_model extends CI_Model {
	public $_table='banner';
	function __construct() {
		
	}
	
	public function get_all(){
		$this->db->select('*')->from($this->_table)->where('Status <','2');
		return $this->db->get()->result();
	}
	
	public function get_for_fe(){
            $this->db->cache_on();
		$this->db->select('*')->from($this->_table)->where('Status','1');
		return $this->db->get()->result();
	}
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit($DataArr,$BannerID){
		$this->db->where('BannerID',$BannerID);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function change_status($BannerID,$Status){
		$this->db->where('BannerID',$BannerID);
		$this->db->update($this->_table,array('Status'=>$Status));
		return TRUE;
	}
	
	public function delete($BannerID){
		$this->db->delete($this->_table, array('BannerID' => $BannerID)); 
		return TRUE;
	}
        
        function get_banner_img($BannerID){
            return $this->db->select('Image')->from($this->_table)->where('BannerID',$BannerID)->get()->row();
        }
        
        function get_banner_id_by_page($PageID){
            return $this->db->from($this->_table)->where('PageID',$PageID)->get()->row();
        }
}
?>