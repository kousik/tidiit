<?php
class Banner_model extends CI_Model {
	public $_table='banner';
	function __construct() {
		
	}
	
	public function get_all(){
            $this->db->select('b.*,c.categoryName')->from($this->_table.' b');
            $this->db->join('category c','b.categoryId=c.categoryId','left');
            $this->db->where('b.status <','2');
            return $this->db->get()->result();
	}
	
	public function get_for_fe(){
            $this->db->select('*')->from($this->_table)->where('status','1');
            return $this->db->get()->result();
	}
        
        public function get_home_slider($sliderSlNo=1,$app=FALSE){
            $this->db->from($this->_table)->where('pageId',1)->where('sliderSlNo',$this->db->escape_str($sliderSlNo));
            if($app==FALSE)
                $rs=$this->db->get()->result();
            else
                $rs=$this->db->get()->result_array();
            //echo $this->db->last_query();
            return $rs;
        }
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit($DataArr,$bannerId){
		$this->db->where('bannerId',$bannerId);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function change_status($bannerId,$status){
		$this->db->where('bannerId',$bannerId);
		$this->db->update($this->_table,array('status'=>$status));
		return TRUE;
	}
	
	public function delete($bannerId){
		$this->db->delete($this->_table, array('bannerId' => $bannerId)); 
		return TRUE;
	}
        
        function get_banner_img($bannerId){
            $rs=$this->db->select('image')->from($this->_table)->where('bannerId',$bannerId)->get()->row();
            echo $this->db->last_query();
            return $rs;
        }
        
        function get_banner_id_by_page($pageId){
            return $this->db->from($this->_table)->where('pageId',$pageId)->get()->row();
        }
}
?>