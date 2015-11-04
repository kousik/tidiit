<?php
class User_model extends CI_Model {
        private $_table='user';
	private $_table_type='user_type';
	private $_subscriber='subscriber';
	private $_table_bill_address='billing_address';
        private $_brand='brand_user';
        private $_page_type="page_type_user";


        public $result=NULL;
	function __construct() {
		parent::__construct();
	}
	
        public function get_all(){
            return $this->db->from($this->_table)->where('status <','2')->get()->result();
	}
	
	public function add($dataArray){
            $this->db->insert($this->_table,$dataArray);
            return $this->db->insert_id();
	}
	
	public function edit($DataArr,$userId){
            $this->db->where('userId',$userId);
            $this->db->update($this->_table,$DataArr);
            return TRUE;		
	}
        
        public function get_user_type(){
            return $this->db->from($this->_table_type)->get()->result();
        }
	
	public function change_user_status($userId,$status){
            $this->db->where('userId',$userId);
            $this->db->update($this->_table,array('status'=>$status));
            return TRUE;
	}
	
	public function get_details_by_id($userId){
            return $this->db->select('*')->from($this->_table)->where('userId',$userId)->get()->result();
	}
	
	public function get_active_user(){
		return $this->db->select('*')->from($this->_table)->where('status <','2')->get()->result();
	}
        
        public function check_user_email_exists($email){
            $rs=$this->db->from($this->_table)->where('email',$email)->where('status <','2')->get()->result();
            if(count($rs)>0){
                return TRUE;
            }else{
                return FALSE;
            }
	}


        public function check_username_exists($email,$userType){
            //SELECT userId FROM ".TABLEPREFIX."_user WHERE email='".$email."' AND status<2
            $rs=$this->db->from($this->_table)->where('userName',$email)->where('status <','2')->where('userType',$userType)->get()->result();
            if(count($rs)>0){
                return TRUE;
            }else{
                return FALSE;
            }
	}
	
	
	public function check_edit_username_exists($email,$userId){
		//$sql="SELECT * FROM ".$this->_table." WHERE `userName`='".$email."' AND `userId`<>'".$userId."'";
		$rs=$this->db->from($this->_table)->where('userName',$email)->where('userId <>',$userId)->get()->result();
		if(count($rs)>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
        
        function check_edit_email_exists($email,$userId){
            $rs=$this->db->from($this->_table)->where('email',$email)->where('userId <>',$userId)->get()->result();
            if(count($rs)>0){
                    return TRUE;
            }else{
                    return FALSE;
            }
        }
	
	public function check_login_data($email,$Password,$userType){
            //echo $email.'  '.$Password;die;
            $rs=$this->db->select('*')->from($this->_table)->where('userName',$email)->where('password',base64_encode($Password).'~'.md5('tidiit'))->where('status','1')->where('userType',$userType)->get()->result();
            //echo $this->db->last_query();die;
            return $rs;
	}
	
	
	
	public function get_user_for_admin(){
		$this->db->select('*')->from($this->_table)->where('userTypeId',1)->where('status',1);
		return $this->db->get()->result();
	}
	
	public function delete($userId){
		$this->db->delete($this->_table, array('userId' => $userId)); 
		return TRUE;
	}
	
	public function add_biiling_info($dataArray){
		$this->db->insert($this->_billing,$dataArray);
		return $this->db->insert_id();		
	}
	
	public function add_shipping_info($dataArray){
		$this->db->insert($this->_shipping,$dataArray);
		return $this->db->insert_id();		
	}
	
	public function edit_biiling_info($dataArray,$userId){
		$this->db->where('userId',$userId);
		$this->db->update($this->_billing,$dataArray);
		return TRUE;
	}
	
	public function edit_shipping_info($dataArray,$userId){
		$this->db->where('userId',$userId);
		$this->db->update($this->_shipping,$dataArray);
		return TRUE;
	}
	
	/// this is for only guest user
	public function get_user_shipping_information(){
		$sql="";
		die('get_user_shipping_information() is calling User_model');
		return $this->db->query($sql)->result();
	}
	
	/// this is for only guest user
	public function get_user_shipping_information1(){
            die('get_user_shipping_information1() is calling User_model');
            $sql="";
            //die($sql);
            return $this->db->query($sql)->result();
	}
	
	public function get_password($userName){
		$sql="SELECT password,firstName,lastName FROM user WHERE userName='".$userName."'";
		return $this->db->query($sql)->result();
	}
	
	public function get_user_info_by_username($userName){
		$this->db->select('*')->from($this->_table)->where('userName',$userName);
		$query=$this->db->get();
		return $query->result();
	}
        
        public function check_email_exists($email){
		$sql="SELECT * FROM ".$this->_table." WHERE `email`='".$email."' AND `userId`<>'".$this->session->userdata('FE_SESSION_VAR')."'";
		$rs=$this->db->query($sql)->result();
		if(count($rs)>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
        
        function is_already_subscribe($email){
            $rs=$this->db->from($this->_subscriber)->where('email',$email)->get()->result();
            if(count($rs)>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        
        function subscribe($email){
            $this->db->insert($this->_subscriber,array('email'=>$email,'subscribeDate'=>date('Y-m-d'),'IP'=>$this->input->ip_address()));
            return $this->db->insert_id();
        }
        
        
}
?>