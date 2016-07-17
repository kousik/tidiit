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
    private $_finance ="finance_info";
    private $_user_product_type_category ="user_product_type_category";
    private $_login_history ="user_login_history";
    private $_product_type_user ="product_type_user";
    private $_table_country ="country";
    private $_table_state ="state";
    private $_table_city ="city";
    private $_table_locality ="locality";
    private $_table_zip ="zip";
    private $_table_order = 'order';
    private $_table_sms_history = 'sms_send_history';
    private $_table_logistics = 'logistics';
    private $_table_logistic_user = 'logistic_user';
    private $_table_warehouse = 'seller_warehouse';
    private $_table_tidiit_commission = 'tidiit_commission';
    private $_table_app_info = 'app_info';


    public $result=NULL;
    function __construct() {
            parent::__construct();
    }

    public function get_all(){
        return $this->db->select('u.*,lu.logisticsId')->from($this->_table.' u')->join('logistic_user lu','u.userId=lu.userId','left')->where('status <','2')->get()->result();
        //return $this->db->from($this->_table)->where('status <','2')->get()->result();
    }

    public function add($dataArray){
        $this->db->insert($this->_table,$dataArray);
        $userId=$this->db->insert_id();
        $this->add_shipping(array('userId'=>$userId,'firstName'=>$dataArray['firstName'],'lastName'=>$dataArray['lastName']));
        return $userId;
    }

    public function edit($DataArr,$userId){
        $this->db->where('userId',$userId);
        $this->db->update($this->_table,$DataArr);        
        return TRUE;		
    }

    public function get_user_type($app=false){
        if($app==FALSE)
            return $this->db->from($this->_table_type)->get()->result();
        else
            return $this->db->from($this->_table_type)->get()->result_array();
    }

    public function get_user_type_except_buyer_seller($app=FALSE){
         $names = array('buyer', 'seller');
         if($app==FALSE)
            return $this->db->from($this->_table_type)->where_not_in('userType', $names)->get()->result();
         else
             return $this->db->from($this->_table_type)->where_not_in('userType', $names)->get()->result_array();
    }

    public function change_user_status($userId,$status){
        $this->db->where('userId',$userId);
        $this->db->update($this->_table,array('status'=>$status));
        return TRUE;
    }

    public function get_details_by_id($userId,$app=false){
        return $this->get_active_details_by_id($userId,$app);
    }
    
    public function get_active_details_by_id($userId,$app=false){
        if($app==FALSE):
            return $this->db->from($this->_table)->where('userId',$userId)->where('status','1')->get()->result();
        else:
            return $this->db->from($this->_table)->where('userId',$userId)->where('status','1')->get()->result_array();
        endif;
    }

    public function get_active_user($app=false){
        if($app==FALSE)
            return $this->db->select('*')->from($this->_table)->where('status <','2')->get()->result();
        else
            return $this->db->select('*')->from($this->_table)->where('status <','2')->get()->result_array();
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

    function check_username_exists_without_type($userName){
        $no=$this->db->where('userName',$userName)->where('status <','2')->from($this->_table)->count_all_results();
        //$rs=$this->db->from($this->_table)->where('userName',$userName)->where('status <','2')->get()->result();
        if($no>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    function check_email_exists_without_type($email){
        $no=$this->db->where('email',$email)->where('status <','2')->from($this->_table)->count_all_results();
        //$rs=$this->db->from($this->_table)->where('userName',$userName)->where('status <','2')->get()->result();
        if($no>0){
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
        //$this->db->delete($this->_table, array('userId' => $userId)); 
        $this->db->where_in('userId',explode(',',$userId));
        $this->db->delete($this->_table); 
        $this->group_delete_by_admin($userId);
        $this->group_member_update($userId);
        $this->order_update($userId);
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
            if($this->get_billing_address($userId)):
                $this->db->where('userId',$userId);
                $this->db->update($this->_bill_address,$dataArray);
            else:
                $dataArray['userId'] = $userId;
                $this->db->insert($this->_bill_address,$dataArray);
            endif;
            return TRUE;
    }

    public function edit_shipping($dataArray,$userId){
            $this->db->where('userId',$userId);
            $this->db->update($this->_shipping_address,$dataArray);
            return TRUE;
    }

    /// this is for only guest user
    public function get_user_shipping_information($userId=0,$app=FALSE){
        $this->db->select('sa.*,c.countryName,s.stateName,ci.city,z.zip,l.locality');
        $this->db->from($this->_shipping_address.' sa')->join($this->_table_country.' c','sa.countryId=c.countryId','left');
        $this->db->join($this->_table_state.' s','sa.stateId=s.stateId','left')->join($this->_table_city.' ci','sa.cityId=ci.cityId','left');
        $this->db->join($this->_table_zip.' z','sa.zipId=z.zipId','left');
        $this->db->join($this->_table_locality.' l','sa.localityId=l.localityId','left');
        if($userId==0):
            $rs=$this->db->where('sa.userId',$this->session->userdata('FE_SESSION_VAR'))->get()->result();
            //echo $this->db->last_query();die;
            return $rs;
        else:
            if($app):
                return $this->db->where('sa.userId',$userId)->get()->result();            
            else:    
                return $this->db->where('sa.userId',$userId)->get()->result_array();
            endif;
        endif;
    }


    public function get_password($userName){
            $sql="SELECT password,firstName,lastName FROM user WHERE userName='".$userName."'";
            return $this->db->query($sql)->result();
    }

    public function get_user_info_by_username($userName,$app=false){
            $this->db->select('*')->from($this->_table)->where('userName',$userName);
            $query=$this->db->get();
            if($app==FALSE)
                return $query->result();
            else
                return $query->result_array();
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

    function subscribe($email,$appSource=""){
        $dataArr=array('email'=>$email,'subscribeDate'=>date('Y-m-d'),'IP'=>$this->input->ip_address());
        if(!empty($appSource))
            $dataArr['appSource']=$appSource;
        $this->db->insert($this->_subscriber,$dataArr);
        return $this->db->insert_id();
    }


    public function get_user_page_type($userId,$app=false){
        if($app==FALSE)
            return $this->db->from($this->_page_type)->where('userId',$userId)->get()->result();
        else
            return $this->db->from($this->_page_type)->where('userId',$userId)->get()->result_array();
    }

    function get_billing_address($userId=0){
        $this->db->select('ba.*,c.countryName,s.stateName,ci.city AS cityTableData,z.zip AS zipTableData,l.locality,u.firstName,u.lastName,u.email');
        $this->db->from($this->_bill_address.' ba')->join($this->_table.' u','ba.userId=u.userId','left');
        $this->db->join($this->_table_country.' c','ba.countryId=c.countryId','left');
        $this->db->join($this->_table_state.' s','ba.stateId=s.stateId','left')->join($this->_table_city.' ci','ba.cityId=ci.cityId','left');
        $this->db->join($this->_table_zip.' z','ba.zipId=z.zipId','left');
        $this->db->join($this->_table_locality.' l','ba.localityId=l.localityId','left');
        if($userId==0):
            $rs=$this->db->where('ba.userId',$this->session->userdata('FE_SESSION_VAR'))->get()->result();
            //echo $this->db->last_query();
            return $rs;
        else:
            $rs=$this->db->where('ba.userId',$userId)->get()->result_array();
            //echo $this->db->last_query();
            return $rs;
        endif;
    }

    function is_billing_address_added($userId=0){
        if($userId==0)
            return $this->db->get_where($this->_bill_address,array('userId'=>$this->session->userdata('FE_SESSION_VAR')))->result();
        else
            return $this->db->get_where($this->_bill_address,array('userId'=>$userId))->result_array();
    }

    function get_all_users_by_locality($localityId,$userId=0){
        if($userId==0):
            $this->db->where('userId !=', $this->session->userdata('FE_SESSION_VAR')); $this->db->distinct();
            $this->db->select('userId')->from($this->_shipping_address)->where('localityId',$localityId);
            $query=$this->db->get();
            $data = $query->result();
        else:
            $this->db->where('userId !=',$userId);$this->db->distinct();
            $this->db->select('userId')->from($this->_shipping_address)->where('localityId',$localityId);
            $query=$this->db->get();
            $data = $query->result_array();
        endif;    
        if($data): 
            $udata = array();
            foreach($data as $key => $usr):
                if($userId==0):
                    $udatas = $this->get_details_by_id($usr->userId);
                else:
                    $udatas = $this->get_details_by_id($usr['userId'],true);
                endif;
                
                if(!empty($udatas))
                    $udata[] = $udatas[0];
            endforeach;
            return $udata;
        else:    
            return false;
        endif;
    }

    /**
     * 
     * @param type $dataArray
     * @return type
     */
    public function group_add($dataArray){
        $this->db->insert($this->_group,$dataArray);
        return $this->db->insert_id();
    }

    /**
     * 
     * @param type $DataArr
     * @param type $groupId
     * @return boolean
     */
    public function group_update($DataArr,$groupId){
        $this->db->where('groupId',$groupId);
        $this->db->update($this->_group,$DataArr);
        return TRUE;		
    }

    /**
     * 
     * @param type $groupId
     * @return boolean
     */
    public function group_delete($groupId){
        $this->db->delete($this->_group, array('groupId' => $groupId)); 
        return TRUE;
    }
    
    /**
     * 
     * @param type $userId
     * @return boolean
     */
    public function group_delete_by_admin($userId){
        $this->db->delete($this->_group, array('groupAdminId' => $userId)); 
        return TRUE;
    }
    
    /**
     * 
     * @param type $userId
     */
    public function group_member_update($userId){
        $sql = "UPDATE `{$this->_group}` SET groupUsers = TRIM(BOTH ',' FROM REPLACE(REPLACE(groupUsers, '{$userId}', ''), ',,', ','))";
        $this->db->query($sql, array());
    }
    
    /**
     * 
     * @param type $groupId
     * @return type
     */
    public function get_group_by_id($groupId,$app=FALSE){
        $this->db->limit(1);
        if($app==FALSE)
            $groupData = $this->db->select('*')->from($this->_group)->where('groupId',$groupId)->get()->result();     
        else
            $groupData = $this->db->select('*')->from($this->_group)->where('groupId',$groupId)->get()->result_array();     
        if(!$groupData):
            return false;
        endif;
        $group = $groupData[0];
        if($app==FALSE)
            $users = explode(",", $group->groupUsers);
        else
            $users = explode(",", $group['groupUsers']);
        $udata = array();
        if($users):                        
            foreach($users as $ukey => $usrId):
                if($app==FALSE)
                    $udatas = $this->get_details_by_id($usrId);
                else
                    $udatas = $this->get_details_by_id($usrId,TRUE);
                if(!empty($udatas))
                    $udata[] = $udatas[0];
            endforeach;
        endif;
        if($app==FALSE):
            if(!empty($udata)):
                $group->users = $udata;
                $getgpadmin = $this->get_details_by_id($group->groupAdminId); 
                $group->admin = $getgpadmin[0];
            else:
                return array();
            endif;
        else:
            if(!empty($udata)):
                $group['users'] = $udata; 
                $getgpadmin = $this->get_details_by_id($group['groupAdminId'],TRUE); 
                $group['admin'] = $getgpadmin[0];
            else:
                return array();
            endif;
        endif;    
        return $group;
    }
    
    /**
     * 
     * @param type $df
     * @return boolean
     */
    public function get_my_groups($df = false){
        $this->db->order_by('groupId','desc');
        $datas = $this->db->from($this->_group)->where('groupAdminId =',$this->session->userdata('FE_SESSION_VAR'))->get()->result();
        if($datas):
            $groups = array();
            foreach($datas as $key => $grp):
                $users = explode(",", $grp->groupUsers);
                $udata = array();
                if($users):                        
                    foreach($users as $ukey => $usrId):
                        if($usrId):
                            $udatas = $this->get_details_by_id($usrId);
                            $udata[] = $udatas[0];
                        endif;
                    endforeach;
                endif;
                $grp->users = $udata;

                $getgpadmin = $this->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
                $grp->admin = $getgpadmin[0];
                $grp->hide = false;
                $groups[$grp->groupId] = $grp;
            endforeach;

            $in_groups = $this->get_my_on_groups();
            if($in_groups && !$df): //pre($groups);die;//pre($in_groups);die;
                $result = array_merge($groups, $in_groups);
            else:
                $result = $groups;
            endif;
            return (object)$result;
        else:
            return false;
        endif;
    }
    
    function get_my_groups_apps($userId,$df = false){
        $this->db->order_by('groupId','desc');
        
        $datas = $this->db->from($this->_group)->where('groupAdminId =',$userId)->get()->result_array();
        if($datas):
            $groups = array();
            foreach($datas as $key => $grp):
                $users = explode(",", $grp['groupUsers']);
                $udata = array();
                if($users):                        
                    foreach($users as $ukey => $usrId):
                        if($usrId):
                            $udatas = $this->get_details_by_id($usrId,TRUE);
                            $udata[] = $udatas[0];
                        endif;
                    endforeach;
                endif;
                $grp['users'] = $udata;

                $getgpadmin = $this->get_details_by_id($userId,true);

                $grp['admin'] = $getgpadmin[0];
                $grp['hide'] = null;
                $groups[] = $grp;
            endforeach;

            $in_groups = $this->get_my_on_groups_apps($userId);
            if($in_groups && !$df):
                $result = array_merge($groups, $in_groups);
            else:
                $result = $groups;
            endif;
            
            return $result;
        else:
            return array();
        endif;
    }
    
    
    function get_my_created_groups_apps($userId,$df = false){
        $this->db->order_by('groupId','desc');
        
        $datas = $this->db->from($this->_group)->where('groupAdminId =',$userId)->get()->result_array();
        if($datas):
            $groups = array();
            foreach($datas as $key => $grp):
                $users = explode(",", $grp['groupUsers']);
                $udata = array();
                if($users):                        
                    foreach($users as $ukey => $usrId):
                        if($usrId):
                            $udatas = $this->get_details_by_id($usrId,TRUE);
                            $udata[] = $udatas[0];
                        endif;
                    endforeach;
                endif;
                $grp['users'] = $udata;

                $getgpadmin = $this->get_details_by_id($userId,true);

                $grp['admin'] = $getgpadmin[0];
                $grp['hide'] = null;
                $groups[] = $grp;
            endforeach;

            //$in_groups = $this->get_my_on_groups_apps($userId);
            //if($in_groups && !$df):
            //    $result = array_merge($groups, $in_groups);
            //else:
                $result = $groups;
            //endif;
            
            return $result;
    
        else:
            return array();
        endif;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function get_my_on_groups(){
        $query = $this->db->query("SELECT *  FROM `{$this->_group}` WHERE FIND_IN_SET('{$this->session->userdata('FE_SESSION_VAR')}',groupUsers) > 0 ORDER BY `groupId` DESC  ");
        $datas = $query->result();
        //pre($datas);
        if($datas):
            $groups = array();
            foreach($datas as $key => $grp):
                $users = explode(",", $grp->groupUsers);
                $udata = array();
                if($users):                        
                    $grp->users = $udata;
                
                    $getgpadmin = $this->get_details_by_id($grp->groupAdminId); 
                    if(!empty($getgpadmin)):
                        $grp->admin= $getgpadmin[0];
                        $grp->hide = true;
                        $groups[$grp->groupId] = $grp;
                        foreach($users as $ukey => $usrId):
                            $udatas = $this->get_details_by_id($usrId);
                            if(!empty($udatas)){
                                $udata[] = $udatas[0];
                            }
                        endforeach;
                    endif;
                endif;
            endforeach;
            //pre($groups);die;
            return $groups;
        else:
            return false;
        endif;
    }
    
    public function get_my_on_groups_apps($userId){
        $query = $this->db->query("SELECT *  FROM `{$this->_group}` WHERE FIND_IN_SET('{$userId}',groupUsers) > 0 ORDER BY `groupId` DESC  ");
        $datas = $query->result_array();

        if($datas):
            $groups = array();
            foreach($datas as $key => $grp):
                $users = explode(",", $grp['groupUsers']);
                $udata = array();
                if($users):                        
                    foreach($users as $ukey => $usrId):
                        $udatas = $this->get_details_by_id($usrId,true);
                        if(!empty($udatas))
                            $udata[] = $udatas[0];
                    endforeach;
                endif;
                if(!empty($udata))
                    $grp['users'] = $udata;

                $getgpadmin = $this->get_details_by_id($grp['groupAdminId']); 
                if(!empty($getgpadmin))
                    $grp['admin'] = $getgpadmin[0];
                $grp['hide'] = true;
                $groups[] = $grp;
            endforeach;
            return $groups;
        else:
            return false;
        endif;
    }
    
    public function user_exists_on_group($userId,$groupId){
        $query = $this->db->query("SELECT *  FROM `{$this->_group}` WHERE `groupAdminId` = '{$userId}' OR FIND_IN_SET('{$userId}',groupUsers) > 0 AND `groupId` = '{$groupId}' ORDER BY `groupId` DESC");
        $datas = $query->result();
        if($datas):            
            return true;
        else:
            return false;
        endif;
    }
    
    /**
     * 
     * @param type $userId
     * @return boolean
     */
    public function order_update($userId){
        $DataArr = array('status' => 0);
        $this->db->where('userId',$userId);
        $this->db->update($this->_table_order,$DataArr);
        return TRUE;		
    }
    /**
     * 
     * @param type $dataArray
     * @return type
     */
    public function notification_add($dataArray){
        $this->db->insert($this->_notification,$dataArray);
        return $this->db->insert_id();
    }
    
    
    public function notification_edit($dataArray,$notificationId){
        $this->db->where('id',$notificationId);
        $this->db->update($this->_notification,$dataArray);
        return TRUE;		
    }
    
    /**
     * 
     * @param type $receiverId
     * @return type
     */
    public function notification_all_my($offset = null, $limit = null, $cond){
        $this->db->from($this->_notification);
        foreach($cond as $key=>$val){
                $this->db->where($key,$val);
        }
        
        if($limit != null){
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by('id','desc');
        $query = $this->db->get();
        $this->result = $query->result();
        //echo $str = $this->db->last_query();
        return $this->result;
    }
    
    /**
     * 
     * @param type $receiverId
     * @return type
     */
    public function notification_all_my_app($userId){
        $this->db->from($this->_notification);
        $cond = array('status'=>'1', 'receiverId'=>$userId);
        foreach($cond as $key=>$val){
                $this->db->where($key,$val);
        }
        
        $this->db->order_by('createDate','desc');
        $query = $this->db->get();
        $this->result = $query->result_array();
        //echo $str = $this->db->last_query();
        return $this->result;
    }
    
    /**
     * 
     * @param type $receiverId
     * @return type
     */
    public function notification_my_unread($receiverId,$app=false){
        $this->db->order_by('createDate','desc');
        if($app==FALSE)
            return $this->db->get_where($this->_notification,array('status'=>1, 'receiverId'=>$receiverId, 'isRead' => 0))->result();
        else
            return $this->db->get_where($this->_notification,array('status'=>1, 'receiverId'=>$receiverId, 'isRead' => 0))->result_array();
    }
    
    /**
     * 
     * @param type $id
     * @return boolean
     */
    function notification_delete($id){
        if($this->db->delete($this->_notification, array('id' => $id))){
         return true;
        } else {
            return false;
        }
    }
    
    function notification_single($id,$app=false){
        $query = $this->db->get_where($this->_notification,array('id'=>$id));
        if($app==FALSE)
            $result = $query->result();
        else
            $result = $query->result_array();
	return $result?$result[0]:false;
    }
    
    function notification_update($cond, $data) {
        if($this->db->update($this->_notification, $data, $cond)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @return type
     */
    function is_shipping_address_added($userId=0){
        if($userId==0)
            return $this->db->get_where($this->_shipping_address,array('userId'=>$this->session->userdata('FE_SESSION_VAR')))->result();
        else
            return $this->db->get_where($this->_shipping_address,array('userId'=>$userId))->result_array();
    }

    function get_finance_info($userId=0){
        if($userId==0)
            return $this->db->get_where($this->_finance,array('userId'=>  $this->session->userdata('FE_SESSION_VAR')))->result();
        else
            return $this->db->get_where($this->_finance,array('userId'=>  $userId))->result_array();
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
            //@mail('judhisahoo@gmail.com','update_user_product_type_category query string',$this->db->last_query());
            return TRUE;
        }else{
            $dataArr['userId']=$userId;
            $this->db->insert($this->_user_product_type_category,$dataArr);
            //@mail('judhisahoo@gmail.com','update_user_product_type_category query string',$this->db->last_query());
            return $this->db->insert_id();		
        }
    }

    public function change_status($userId,$action){
        if($action=='active'){
                $Status=1;
        }else{
                $Status=0;
        }
        $this->db->where_in('userId',explode(',',$userId));
        $this->db->update($this->_table,array('status'=>$Status));
        //echo $this->db->last_query();die;
        return TRUE;
    }

    function add_bill_address($dataArr){
        $this->db->insert($this->_bill_address,$dataArr);
        return $this->db->insert_id();
    }
    
    function get_data_by_email($email){
        return $this->db->get_where('user',array('email'=>$email,'status'=>1))->result();
    }
    
    function add_login_history($dataArray){
        $this->db->insert($this->_login_history,$dataArray);
        return $this->db->insert_id();
    }
    
    function get_last_login(){
        if($this->session->userdata('FE_SESSION_VAR')!="")
            return $this->db->order_by('loginHistoryId','DESC')->get_where($this->_login_history,array('userId'=>$this->session->userdata('FE_SESSION_VAR')),2,1)->result();
        else
            return FALSE;
    }
    
    function update_product_type_user($categoryId,$userId){
        $rs=$this->db->get_where($this->_product_type_user,array('productTypeId'=>$categoryId))->result();
        
        if(count($rs)>0){
            /// update record
            $userIdStr=$rs[0]->userIdStr.','.$userId;
            $this->db->where('productTypeId',$categoryId);
            $this->db->update($this->_product_type_user,array('userIdStr'=>$userIdStr));
            return TRUE;
        }else{
            $this->db->insert($this->_product_type_user,array('userIdStr'=>$userId,'productTypeId'=>$categoryId));
            return $this->db->insert_id();		
        }
    }
    
    function remove_user_from_product_type($userId){
        $rs=$this->db->get($this->_product_type_user)->result();
        foreach($rs As $k){
            $userIdStrArr=explode(',', $k->userIdStr);
            if(in_array($userId, explode(',', $k->userIdStr))){
                unset($userIdStrArr[array_search($userId, $userIdStrArr)]);
                $this->db->where('productTypeUser',$k->productTypeUser);
                $this->db->update($this->_product_type_user,array('userIdStr'=>  implode(',', $userIdStrArr)));
            }
        }
    }
    
    function get_all_users_by_product_type_locality($productType,$localityId,$userId=0){
        $rs=$this->db->get_where($this->_product_type_user,array('productTypeId'=>$productType))->result();
        if(empty($rs)){
            return array();
        }else{
            $this->db->distinct();
            $this->db->select('u.firstName,u.lastName,u.userId,u.email')->from('user u');
            $this->db->join($this->_shipping_address.' ba','ba.userId=u.userId');
            if($userId==0):
                $rs=$this->db->where_in('ba.userId',explode(',',$rs[0]->userIdStr))->where('ba.localityId',$localityId)->where_not_in('ba.userId',$this->session->userdata('FE_SESSION_VAR'))->get()->result();
            else:
                $rs=$this->db->where_in('ba.userId',explode(',',$rs[0]->userIdStr))->where('ba.localityId',$localityId)->where_not_in('ba.userId',$userId)->get()->result_array();
            endif;
            //echo $this->db->last_query();
            return $rs;
        }
    }
    
    function get_my_product_type($userId=0){
        $this->db->select('productTypeCateoryId')->from($this->_user_product_type_category);
        if($userId==0)
            $rs=$this->db->where('userId',  $this->session->userdata('FE_SESSION_VAR'))->get()->result();
        else
            $rs=$this->db->where('userId',  $userId)->get()->result_array();
        
        if(empty($rs)){
            return array();
        }else{
            if($userId==0):
                return explode(',', $rs[0]->productTypeCateoryId);
            else:
                return explode(',', $rs[0]['productTypeCateoryId']);
            endif;
        }
    }
    
    function check_old_password($password,$userId){
        $rs=$this->db->from($this->_table)->where('userId',$userId)->where('password', base64_encode($password).'~'.md5('tidiit'))->get()->result();
        if(count($rs)>0):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
    
    function add_sms_history($dataArray){
        $this->db->insert($this->_table_sms_history,$dataArray);
        return $this->db->insert_id();
    }
    
    function testtestFun(){
        
    }
    
    function group_title_exists($groupTitle){
        $this->db->where('groupTitle',$groupTitle);
        $this->db->from($this->_group);
        if($this->db->count_all_results()>0):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
    
    function group_title_exists_edit($groupTitle,$groupId){
        $this->db->where('groupTitle',$groupTitle)->where('groupId !=',$groupId);
        $this->db->from($this->_group);
        if($this->db->count_all_results()>0):
            return TRUE;
        else:
            return FALSE;
        endif;
    }
    
    function get_all_buyer(){
        $rs=$this->db->from($this->_table)->where('status >',0)->where('userType','buyer')->get()->result();
        //echo $this->db->last_query();
        return $rs;
    }
    
    function is_valid_admin_for_group($groupId,$adminId){
        $rs=  $this->is_group_exist_group_id_admin_id($groupId, $adminId);
        if(count($rs)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    function is_group_exist_group_id_admin_id($groupId,$adminId){
        return $this->db->from($this->_group)->where('groupId',$groupId)->where('groupAdminId',$adminId)->get()->result();
    }
    
    function is_all_users_exists_for_group_by_admin_id($adminId,$users){
        $rs=$this->db->from($this->_group)->where('groupAdminId',$adminId)->where('groupUsers',$users)->get()->row();
        if(empty($rs)):
            return FALSE;
        else:
            return $rs;
        endif;
    }
    
    function get_total_cart_item($userId){
        $this->db->where('userId',$userId)->where('status',0)->from('order');
        return $this->db->count_all_results();
    }
    
    function add_feedback($dataArray){
        $this->db->insert('feedback',$dataArray);
        return $this->db->insert_id();
    }
    
    function post_help($dataArray){
        $this->db->insert('help_request',$dataArray);
        return $this->db->insert_id();
    }
    
    function loged_in_user_shipping_country_code($userId){
        $this->db->select('c.countryCode')->from($this->_shipping_address.' sa')->join($this->_table_country.' c','sa.countryId=c.countryId');
        return $this->where('sa.userId',$userId)->get()->result();
    }
    
    function add_logistics_user($dataArray){
        $this->db->insert($this->_table_logistic_user,$dataArray);
        return $this->db->insert_id();
    }
    
    function delete_logistics_user($userId){
        $this->db->delete($this->_table_logistic_user, array('userId' => $userId)); 
        return TRUE;
    }
    
    function get_logistics_details_by_user_id($userId){
        $this->db->select('l.*,u.firstName,u.lastName,u.email,u.contactNo')->from($this->_table_logistics.' l');
        $this->db->join($this->_table_logistic_user.' lu','lu.logisticsId=l.logisticsId','left');
        return $this->db->join($this->_table.' u','lu.userId=u.userId','left')->where('u.userId',$userId)->get()->result_array();
    }


    public function add_warehouse($dataArray){
        $this->db->insert($this->_table_warehouse,$dataArray);
        return $this->db->insert_id();
    }

    public function get_all_warehouse($sellerId = false){
        $this->db->select('sw.*,c.countryName,s.stateName');
        $this->db->from($this->_table_warehouse.' sw')->join($this->_table_country.' c','sw.countryId=c.countryId','left');
        $this->db->join($this->_table_state.' s','sw.stateId=s.stateId','left');

        if(!$sellerId):
            $rs=$this->db->where('sw.sellerId',$this->session->userdata('FE_SESSION_VAR'))->get()->result();
            //echo $this->db->last_query();die;
            return $rs;
        else:
            return $this->db->where('sw.sellerId',$sellerId)->get()->result_array();
        endif;
    }

    public function get_single_warehouse($wid = false){
        $this->db->select('sw.*,c.countryName,s.stateName');
        $this->db->from($this->_table_warehouse.' sw')->join($this->_table_country.' c','sw.countryId=c.countryId','left');
        $this->db->join($this->_table_state.' s','sw.stateId=s.stateId','left');
        $result = $this->db->where('sw.id',$wid)->get()->result_array();
        return $result[0];
    }

    public function warehouse_delete($wid){
        $this->db->where('id',$wid);
        $this->db->delete($this->_table_warehouse);
        return TRUE;
    }
    
    function add_tidiit_commission($dataArray){
        $this->db->insert($this->_table_tidiit_commission,$dataArray);
        return $this->db->insert_id();
    }
    
    public function get_tidiit_commission_list(){
        $this->db->select('u.email,u.userId,p.title,p.productId,c.categoryName,tc.commissionPercentage,tc.commissionId')->from('tidiit_commission tc');
        $this->db->join('product p','tc.productId=p.productId')->join($this->_table.' u','tc.sellerId=u.userId');
        return $this->db->join('category c','tc.categoryId=c.categoryId')->get()->result();
    }
    
    function edit_tidiit_commission($dataArray,$commissionId){
        $this->db->where('commissionId',$commissionId);
        $this->db->update($this->_table_tidiit_commission,$dataArray);        
        return TRUE;		
    }
    
    function get_tidiit_details($commissionId){
        return $this->db->get_where($this->_table_tidiit_commission,array('commissionId'=>$commissionId))->result();
    }
    
    function check_user_phone_register($userId,$registrationId){
        $rs=$this->db->from($this->_table_app_info)->where('userId',$userId)->where('registrationId',$registrationId)->get()->result();
        if(count($rs)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    function get_reg_id_by_user_id($userId){
        $rs=$this->db->from($this->_table_app_info)->where('userId',$userId)->get()->result();
        if(count($rs)==0){
            return FALSE;
        }else{
            return $rs;
        }
    }
    
    function save_push_notification_history($dataArr){
        $this->db->insert_batch('push_notification_message',$dataArr);
        return $this->db->insert_id();
    }
    
    function is_user_had_group($userId){
        if(count($this->db->from($this->_group)->where('groupAdminId =',$userId)->get()->result_array())>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    function get_all_filter($filterOptions,$filterOptionsValue){
        $this->db->select('u.*,lu.logisticsId')->from($this->_table.' u')->join('logistic_user lu','u.userId=lu.userId','left');
        $this->db->where('status <','2')->like($filterOptions,$filterOptionsValue);
        return $this->db->get()->result();
    }
    
    function is_profile_data_updated($userId=""){
        if($userId==""){
            $userId=$this->session->userdata('FE_SESSION_VAR');
        };
        if($userId!=""){
            $rs=$this->db->get_where($this->_table,array('userId',$userId))->result();
            if($rs[0]->contactNo!="" || $rs[0]->mobile!="" || $rs[0]->DOB!="" || $rs[0]->aboutMe!=""){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
    
    function is_finance_added($userId=""){
        if($userId==""){
            $userId=$this->session->userdata('FE_SESSION_VAR');
        };
        if($userId!=""){
            $rs=$this->db->get_where($this->_finance,array('userId',$userId))->result();
            if(empty($rs)){
                return FALSE;
            }else{
                return TRUE;
            }
        }else{
            return FALSE;
        }
    }
}

