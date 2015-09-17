<?php
class Home_banner_model extends CI_Model {
	public $_table='home_banner';
	function __construct() {
            
	}
	
	public function get_all(){
            $sql="SELECT hb.*,c.CategoryID,c.CategoryName FROM home_banner AS hb LEFT JOIN category AS c ON(hb.CategoryID=c.CategoryID) WHERE hb.Status<2 ORDER BY hb.Status DESC,hb.CategoryID ASC";
            return $this->db->query($sql)->result();
	} 
	
	public function get_for_fe($CategoryID=0){
            $this->db->cache_on();
            if($CategoryID>0){
                $sql="SELECT hb.*,c.CategoryID,c.ParrentCategoryID FROM home_banner AS hb,category AS c WHERE hb.CategoryID=c.CategoryID And hb.CategoryID=$CategoryID AND hb.Status=1";
            }else{
                $sql="SELECT hb.*,'TOPCat' AS ParrentCategoryID FROM home_banner AS hb WHERE hb.Status=1 AND hb.CategoryID=$CategoryID";
            }
            //echo $sql;die;
            $result=$this->db->query($sql)->result();
            //pre($result);die;
            return $result;
	}
	
	public function add($dataArr){
            $this->db->insert($this->_table,$dataArr);
            return $this->db->insert_id();
	}
	
	public function edit($DataArr,$HomeBannerID){
		$this->db->where('HomeBannerID',$HomeBannerID);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function change_status($HomeBannerID,$Status){
		$this->db->where('HomeBannerID',$HomeBannerID);
		$this->db->update($this->_table,array('Status'=>$Status));
		return TRUE;
	}
	
	public function delete($HomeBannerID){
		$this->db->delete($this->_table, array('HomeBannerID' => $HomeBannerID)); 
		return TRUE;
	}
}
?>