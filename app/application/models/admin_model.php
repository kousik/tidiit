<?php
class Admin_model extends CI_Model {
	public $_table='admin';
	public $_table_loged='log_event';
	public $result=NULL;
	
	public function add_admin($DataArr){
		$this->db->insert($this->_table,$DataArr);
		return $this->db->insert_id();
	}
	
	public function get_all_admin(){
		$this->db->select('*')->from($this->_table)->where('Status','<2');
		$query=$this->db->get();
		$result=$query->result();
		return $this->result;
	}
	 
	public function update_info($DataArr,$Id){
		$this->db->where('AdminID',$Id);
		$this->db->update($this->_table,$DataArr);
		return TRUE;
	}
	
	public function delete($id){
		$this->db->where('AdminID',$id);
		$this->db->delete($this->_table);
		return TRUE;
	}
	
	public function is_valid_data($UserName,$Password){
		$this->db->select('')->from($this->_table)->where('UserName',$UserName)->where('Password',  base64_encode($Password));
		return $this->db->get()->result();
	}
        
        public function show_loged_list(){
            $sql="SELECT le.*,e.Name FROM log_event AS le JOIN event AS e ON(le.event_id=e.EventID)";
            return $this->db->query($sql)->result();
        }
}
?>