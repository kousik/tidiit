<?php
class Admin_model extends CI_Model {
	public $_table='user';
	
	
	public function add($DataArr){
		$this->db->insert($this->_table,$DataArr);
                //echo $this->db->last_query();die;
		return $this->db->insert_id();
	}
	
	public function get_all_admin(){
		$rs=$this->db->select('*')->from($this->_table)->where('Status <','2')->get()->result();
		return $rs;
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
	
	public function is_valid_data($UserName,$Password){ //echo 'kk';die;
            //echo base64_encode(trim('admin')).'~'.md5('tidiit').'<br>';
            $rs=$this->db->from($this->_table)->where('userName',$UserName)->where('password', base64_encode(trim($Password)).'~'.md5('tidiit'))->where_in('userType',array('admin','support','supper_admin'))->get()->result();
            //$rs=  $this->db->query("SELECT * FROM (`user`) WHERE `userName` = 'webadmin' AND `password` = 'YWRtaW4=' AND `userType` IN ('admin', 'support', 'supper_admin') ")->result();
            //pre($rs);
            //echo $this->db->last_query();die;
            //pre($rs);die;
            return $rs;
	}
        
        public function userIsExists($UserName){
            $rs=$this->db->from($this->_table)->where('UserName',$UserName)->get()->result();
            if(empty($rs)){
                return FALSE;
            }else{
                return TRUE;
            }
        }
        
        public function has_access($controller,$method){
            $sql="SELECT * FROM `role` AS r "
                    . " JOIN `role_group` AS rg ON(r.roleGroupId=rg.roleGroupId) "
                    . " JOIN `user_role` AS ur ON(ur.roleId=r.roleId) "
                    . " WHERE rg.accessTitle='".$controller."' AND r.roleAccessTitle='".$method."' "
                    . " AND ur.userId=".$this->session->userdata('ADMIN_SESSION_VAR');
            //echo $sql;die;
            $rs=$this->db->query($sql)->result();
           
            if(count($rs)>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        
        function get_roles_for_user($userId){
            $sql="SELECT r.`roleAccessTitle` AS `method`,rg.`accessTitle` AS `controller` FROM `role` AS r  "
                    . " JOIN `role_group` AS rg ON(r.roleGroupId=rg.roleGroupId)  "
                    . " JOIN `user_role` AS ur ON(ur.roleId=r.roleId) WHERE ur.userId=".$userId;
            return $this->db->query($sql)->result_array();
        }
        
        function get_user_data($GetColumn,$WhereColumn,$WhereColumnValue){
		$this->db->select($GetColumn)->from($this->_table)->like($WhereColumn,$WhereColumnValue);
		return $this->db->get()->result();
	}
}
?>