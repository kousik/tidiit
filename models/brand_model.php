<?php
class Brand_model extends CI_Model{
	private $_table='brand';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_all_admin(){
		$this->db->select('*')->from($this->_table);
		return $this->db->get()->result();
	}
	
        public function add($dataArr){
            $this->db->insert($this->_table,$dataArr);
            return $this->db->insert_id();
        }
        
	public function edit($DataArr,$brandId){
		$this->db->where('brandId',$brandId);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function details($id){
		$data=$this->db->from($this->_table)->where('brandId',$id)->get()->result();
                //echo $this->db->last_query();die;
		return $data;
	}
	
	
	public function change_status($bannerId,$Status){
		$this->db->where('brandId',$bannerId);
		$this->db->update($this->_table,array('Status'=>$Status));
		return TRUE;
	}
	
	public function delete($bannerId){
		$this->db->delete($this->_table, array('brandId' => $bannerId)); 
		return TRUE;
	}
        
        public function get_all($app=FALSE){
            if($app==FALSE)
                return $this->db->get_where($this->_table,array('status'=>1))->result();
            else
                return $this->db->get_where($this->_table,array('status'=>1))->result_array();
        }
        
}
?>