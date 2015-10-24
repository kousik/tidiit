<?php
class User_model extends CI_Model {
        private $_table='user';
	private $_table_type='user_type';
	private $_subscriber='subscriber';
	private $_bill_address='billing_address';
	private $_shipping_address='shipping_address';
        private $_brand='brand_user';
        private $_page_type="page_type_user";
        private $_group="group";
        private $_notification ="notifications";
        private $_finance ="user_m_pesa";
        private $_user_product_type_category ="user_product_type_category";


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
            return $this->db->from($this->_table)->where('userId',$userId)->get()->result();
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
		$this->db->insert($this->_bill_address,$dataArray);
		return $this->db->insert_id();		
	}
	
	public function add_shipping($dataArray){
		$this->db->insert($this->_shipping_address,$dataArray);
		return $this->db->insert_id();		
	}
	
	public function edit_biiling_info($dataArray,$userId){
		$this->db->where('userId',$userId);
		$this->db->update($this->_bill_address,$dataArray);
		return TRUE;
	}
	
	public function edit_shipping($dataArray,$userId){
		$this->db->where('userId',$userId);
		$this->db->update($this->_shipping_address,$dataArray);
		return TRUE;
	}
	
	/// this is for only guest user
	public function get_user_shipping_information(){
            return $this->db->from($this->_shipping_address)->where('userId',$this->session->userdata('FE_SESSION_VAR'))->get()->result();
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
        
        
        public function get_user_page_type($userId){
            return $this->db->from($this->_page_type)->where('userId',$userId)->get()->result();
        }
        
        function get_billing_address(){
            return $this->db->select('u.firstName,u.lastName,u.email,ba.*')->from('user u')->join('billing_address ba','u.userId=ba.userId','left')->where('u.userId',$this->session->userdata('FE_SESSION_VAR'))->get()->result();
        }
        
        function is_billing_address_added(){
            return $this->db->get_where($this->_bill_address,array('userId'=>$this->session->userdata('FE_SESSION_VAR')))->result();
        }
        
        function get_all_users_by_locality($localityId){
            $this->db->where('userId !=', $this->session->userdata('FE_SESSION_VAR'));
            $this->db->select('userId')->from($this->_bill_address)->where('localityId',$localityId);
            $query=$this->db->get();
            $data = $query->result();
            if($data): 
                $udata = array();
                foreach($data as $key => $usr):
                    $udatas = $this->get_details_by_id($usr->userId);
                    $udata[] = $udatas[0];
                endforeach;
                return $udata;
            else:    
                return false;
            endif;
        }
        
        
        public function group_add($dataArray){
            $this->db->insert($this->_group,$dataArray);
            return $this->db->insert_id();
	}
        
        
        public function group_update($DataArr,$groupId){
            $this->db->where('groupId',$groupId);
            $this->db->update($this->_group,$DataArr);
            return TRUE;		
	}
        
        
        public function group_delete($groupId){
            $this->db->delete($this->_group, array('groupId' => $groupId)); 
            return TRUE;
	}
        
        public function get_group_by_id($groupId){
            $this->db->limit(1);
            $groupData = $this->db->select('*')->from($this->_group)->where('groupId',$groupId)->get()->result();            
            $group = $groupData[0];
            $users = explode(",", $group->groupUsers);
            $udata = array();
            if($users):                        
                foreach($users as $ukey => $usrId):
                    $udatas = $this->get_details_by_id($usrId);
                    $udata[] = $udatas[0];
                endforeach;
            endif;
            $group->users = $udata;
            $getgpadmin = $this->get_details_by_id($group->groupAdminId); 
            $group->admin = $getgpadmin[0];
            return $group;
	}
        
        public function get_my_groups(){
            $this->db->order_by('groupId','desc');
            $datas = $this->db->from($this->_group)->where('groupAdminId =',$this->session->userdata('FE_SESSION_VAR'))->get()->result();
            if($datas):
                $groups = array();
                foreach($datas as $key => $grp):
                    $users = explode(",", $grp->groupUsers);
                    $udata = array();
                    if($users):                        
                        foreach($users as $ukey => $usrId):
                            $udatas = $this->get_details_by_id($usrId);
                            $udata[] = $udatas[0];
                        endforeach;
                    endif;
                    $grp->users = $udata;
                    
                    $getgpadmin = $this->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
                    
                    $grp->admin = $getgpadmin[0];
                    $grp->hide = false;
                    $groups[$grp->groupId] = $grp;
                endforeach;
                
                $in_groups = $this->get_my_on_groups();
                if($in_groups):
                    $result = array_merge($groups, $in_groups);
                else:
                    $result = $groups;
                endif;
                return (object)$result;
            else:
                return false;
            endif;
	}
        
        public function get_my_on_groups(){
            $query = $this->db->query("SELECT *  FROM `{$this->_group}` WHERE FIND_IN_SET('{$this->session->userdata('FE_SESSION_VAR')}',groupUsers) > 0 ORDER BY `groupId` DESC  ");
            $datas = $query->result();
           
            if($datas):
                $groups = array();
                foreach($datas as $key => $grp):
                    $users = explode(",", $grp->groupUsers);
                    $udata = array();
                    if($users):                        
                        foreach($users as $ukey => $usrId):
                            $udatas = $this->get_details_by_id($usrId);
                            $udata[] = $udatas[0];
                        endforeach;
                    endif;
                    $grp->users = $udata;
                    
                    $getgpadmin = $this->get_details_by_id($grp->groupAdminId); 
                    $grp->admin = $getgpadmin[0];
                    $grp->hide = true;
                    $groups[$grp->groupId] = $grp;
                endforeach;
                return $groups;
            else:
                return false;
            endif;
	}
        
        
        public function notification_add($dataArray){
            $this->db->insert($this->_notification,$dataArray);
            return $this->db->insert_id();
	}
        
        function is_shipping_address_added(){
            return $this->db->get_where($this->_shipping_address,array('userId'=>$this->session->userdata('FE_SESSION_VAR')))->result();
        }
        
        function get_finance_info(){
            return $this->db->get_where($this->_finance,array('userId'=>  $this->session->userdata('FE_SESSION_VAR')))->result();
        }
        
        public function add_finance($dataArray){
		$this->db->insert($this->_finance,$dataArray);
		return $this->db->insert_id();		
	}
	
	public function edit_finance($dataArray,$userId){
		$this->db->where('userId',$userId);
		$this->db->update($this->_finance,$dataArray);
		return TRUE;
	}
        
        function update_user_product_type_category($dataArr,$userId){
            if($this->db->where('userId',$userId)->from($this->_user_product_type_category)->count_all_results()>0){
                /// update record
                $this->db->where('userId',$userId);
		$this->db->update($this->_user_product_type_category,$dataArr);
                return TRUE;
            }else{
                $dataArr['userId']=$userId;
                $this->db->insert($this->_user_product_type_category,$dataArr);
		return $this->db->insert_id();		
            }
        }
}
?>