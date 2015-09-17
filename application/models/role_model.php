<?php
/**
* @todo :: It access Category table and operate all data like SCRUD operation
*/
class Role_model extends CI_Model {
    private $_table='role_group';
    private $_table_role='role';
    private $_table_user_role='user_role';
            function __construct() {
            parent::__construct();
   }
   
   function get_all_role_group(){
       return $this->db->from($this->_table)->order_by('roleGroupId','DESC')->get()->result();
   }
   
   
   function add_group($DataArr){
       $this->db->insert($this->_table,$DataArr);
       return $this->db->insert_id();
   }
   
   function add($DataArr){
       $this->db->insert($this->_table_role,$DataArr);
       return $this->db->insert_id();
   }
   
   function edit_group($DataArr,$Id){
//       print_r($DataArr);die;
        $this->db->where('roleGroupId',$Id);
        $this->db->update($this->_table,$DataArr);
        return $this->db->affected_rows();
   }
   
   function edit($DataArr,$Id){
//       print_r($DataArr);die;
        $this->db->where('roleId',$Id);
        $this->db->update($this->_table_role,$DataArr);
        return $this->db->affected_rows();
   }
   
   function delete_group($id){
        $this->db->delete($this->_table,array('roleGroupId'=>$id));
        return $this->db->affected_rows();
   }
   
   function delete($id){
        $this->db->delete($this->_table_role,array('roleId'=>$id));
        return $this->db->affected_rows();
   }
   
   function get_role_group_data($id){
       return $this->db->from($this->_table)->where('roleGroupId',$id)->get()->result();
   }
   
   function get_role_data($id){
       return $this->db->from($this->_table)->where('roleGroupId',$id)->get()->result();
   }
   
   function get_all_role($roleGroupId=0){
       if($roleGroupId==0){
           $where='';
       }else{
           $where=' AND r.roleGroupId='.$roleGroupId.' ';
       }
       $sql="SELECT r.*,rg.title FROM role AS r,role_group AS rg WHERE rg.roleGroupId=r.roleGroupId ".$where." ORDER BY roleGroupId DESC,roleId DESC";
       //echo $sql;die;
       return $this->db->query($sql)->result();
   }
   
   function get_role_by_group_id($roleGroupId){
       return $this->db->from($this->_table_role)->where('roleGroupId',$roleGroupId)->order_by('roleId','DESC')->get()->result();
   }
   
   function add_user_role($DataArr){
       $this->db->insert_batch($this->_table_user_role,$DataArr);
       return $this->db->insert_id();
   }
   
   function get_user_role_arr($userId){
       return $this->db->from($this->_table_user_role)->where('userId',$userId)->get()->result_array();
   }
   
   function remove_user_role($userId){
       $this->db->delete($this->_table_user_role,array('userId'=>$userId));
       return $this->db->affected_rows();
   }
}
