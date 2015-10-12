<?php

/*
| ----------------------------------------------
| Start Date : 23-11-2010
| Framework : CodeIgniter
| ----------------------------------------------
| Model Video Gallery
| ----------------------------------------------
*/

class User_model extends CI_Model {

    public $_table = 'user';
    public $result = null;

    function __construct()
    {
        parent::__construct();
    }
    function add_user($dataArray)
	{
		return $this->db->insert($this->_table, $dataArray);
	}

	function get_all_by_email($email)
	{
		$this->db->select('*')->from($this->_table)->where('Email', $email);
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}
	function update_password($id, $dataArray)
	{
		$this->db->where('UserID', $id);
		$this->db->update($this->_table, $dataArray);
		 return true;
	}
	
	function check_login($email,$password){
		$this->db->select('UserID');
		$this->db->from($this->_table);
		$this->db->where('UserName', $email);
		$this->db->where('Password',$password);	
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}
	
	function check_password($id,$password)
	{
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('UserID', $id);
		$this->db->where('Password',$password);	
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}
	
	function get_all()
	{
		$this->db->select('*');
		$this->db->from($this->_table);
		$this->db->where('Status <','2');
		$query = $this->db->get();
		$this->result = $query->result();
		return $this->result;
	}
	
	function delete($id){
		//$this->db->set('Status','2');
		$this->db->where('UserID',$id);
		$this->db->update($this->_table,array('Status'=>'2'));
		//$query = $this->db->get();
		return $this->db->affected_rows();
	}
	
	function change_status($id,$status){
		//$this->db->set('Status','2');
		$this->db->where('UserID',$id);
		$this->db->update($this->_table,array('Status'=>$status));
		//$query = $this->db->get();
		return $this->db->affected_rows();
	}
	
	function edit($DataArr,$id)
	{
		$this->db->where('UserID',$id);
		$this->db->update($this->_table,$DataArr);
		//$query = $this->db->get();
		return $this->db->affected_rows();
	}
	
	function add($DataArr){
		if($this->db->insert($this->_table,$DataArr))
			return $this->db->insert_id();
		else
			return false;
	}
}

?>