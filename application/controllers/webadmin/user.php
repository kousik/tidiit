<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends My_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Logistics_model');
                $this->db->cache_off();
	}
	
	
	public function viewlist(){
            if($this->_is_admin_loged_in()){
                $data=$this->_show_admin_logedin_layout();
                $data['DataArr']=$this->User_model->get_all();
                $data['UserTypeArr']=  $this->User_model->get_user_type_except_buyer_seller();
                $data['logisticData']=  $this->Logistics_model->get_all_active();
                $data['filterOptions']="";
                $data['filterOptionsValue']="";
                $this->load->view('webadmin/user_list',$data);
            }else{
                $data=$this->_show_admin_login();
                $this->load->view('webadmin/login',$data);
            }
	}
	
	public function add(){
            $userName=$this->input->post('userName',true);
            $password=$this->input->post('password',true);
            $status=$this->input->post('status',true);
            $email=$this->input->post('email',true);
            $firstName=$this->input->post('firstName',true);
            $lastName=$this->input->post('lastName',true);
            $contactNo=$this->input->post('contactNo',true);
            $userType=$this->input->post('userType',true);
            $logisticsId=$this->input->post('logisticsId',true);
            
            $config = array(
                array('field'   => 'userName','label'   => 'User Name','rules'=> 'trim|required|xss_clean|min_length[10]|max_length[75]|valid_email|callback_username_check'),
                array('field'   => 'email','label'   => 'User Name','rules'=> 'trim|required|xss_clean|min_length[10]|max_length[75]|valid_email|callback_email_check'),
                array('field'   => 'password','label'   => 'Password','rules'   => 'trim|required|xss_clean|min_length[4]|max_length[25]'),
                array('field'   => 'confirmPassword','label'   => 'Password','rules'   => 'trim|required|xss_clean|matches[password]'),
                array('field'   => 'firstName','label'   => 'First Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]|alpha'),
                array('field'   => 'lastName','label'   => 'Last Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]|alpha'),
                array('field'   => 'contactNo','label'   => 'Phone','rules'   => 'trim|required|xss_clean|min_length[9]|max_length[10]|numeric'),
                array('field'   => 'userType','label'   => 'User Type','rules'   => 'trim|required|xss_clean')
             );
            //initialise the rules with validatiion helper
            $this->form_validation->set_rules($config); 
            //checking validation
            if($this->form_validation->run() == FALSE){
                $this->session->set_flashdata('Message',validation_errors());
            }else{
                if($userType=='logistics' && $logisticsId==""){
                    $this->session->set_flashdata('Message',"plese select logistics company");
                }else{
                    $DataArr=array('userName'=>trim($userName),'password'=>  base64_encode(trim($password)).'~'.md5('tidiit'),'email'=>$email,
                    'status'=>trim($status),'firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$contactNo,'userType'=>$userType);
                    $userId=$this->User_model->add($DataArr);
                    if($userId!=""){
                        $DataArr['password']=$password;
                        $this->User_model->add_logistics_user(array('logisticsId'=>$logisticsId,'userId'=>$userId));
                        $this->user_creation_notification($DataArr);
                        $this->session->set_flashdata('Message','User added successfully.');
                    }else{
                        $this->session->set_flashdata('Message','Unable to user,Please try again.');
                    }
                }
                
            }
            redirect(base_url().'webadmin/user/viewlist');
	}
        
        function user_creation_notification($dataArr){
            $this->email->from("no-reply@tidiit.com", 'Tidiit System Administrator');
            $this->email->to($dataArr['email'],$dataArr['firstName'].' '.$dataArr['lastName']);
            $this->email->subject('Your login details for Tidiit Inc. Ltd.');
            $data=array();
            $data['userDetails']=$dataArr;
            $msg=$this->load->view('email_template/admin_user_create',$data,true);
            //echo $msg;die;
            $this->email->message($msg);
            $this->email->send();
        }
        
        public function username_check($str){
            $userName=$this->input->post('username',TRUE);
            if($userName!=''){
                $str=$userName;
            }
            //if($this->User_model->check_username_exists($str,'buyer')==TRUE){
            if($this->User_model->check_username_exists_without_type($str)==TRUE){
                $this->form_validation->set_message('username_check', 'This User Name already registered.Please try a new one.');
                return FALSE;
            }else{
                return TRUE;
            }
        }
        
        public function email_check($str){
            $email=$this->input->post('email',TRUE);
            $userType=$this->input->post('userType',TRUE);
            if($email!=''){
                $str=$email;
            }
            //if($this->User_model->check_email_exists($str,$userType)==TRUE){
            if($this->User_model->check_email_exists_without_type($str)==TRUE){
                $this->form_validation->set_message('email_check', 'This email already registered.Please try a new one.');
                return FALSE;
            }else{
                return TRUE;
            }
        }
        
	
	public function edit()
	{
            $userId=$this->input->post('userId');
            $email=$this->input->post('Editemail',true);
            $firstName=$this->input->post('EditfirstName',true);
            $lastName=$this->input->post('EditlastName',true);
            $contactNo=$this->input->post('EditcontactNo',true);
            $logisticsId=$this->input->post('editlogisticsId',true);
            if($userId>0){
                $DataArray=array('email'=>$email,'firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$contactNo);
                $No=$this->User_model->edit($DataArray,$userId);
                if($No>0){
                    if($logisticsId!=""){
                        $this->User_model->delete_logistics_user($userId);
                        $this->User_model->add_logistics_user(array('logisticsId'=>$logisticsId,'userId'=>$userId));
                    }
                    $this->session->set_flashdata('Message','Admin user updated successfully.');
                    redirect(base_url().'webadmin/user/viewlist');
                }
            }else{
                $this->session->set_flashdata('Message','Invalid ID selected,Please try again.');
                redirect(base_url().'webadmin/user/viewlist');
            }
	}
	
	
	public function delete($id)
	{
            $No=$this->User_model->delete($id);
            if($No>0){
                $this->session->set_flashdata('Message','Record deleted successfully.');
                redirect(base_url().'webadmin/user/viewlist');
            }else{
                $this->session->set_flashdata('Message','Unabel to delete the record,please try again .');
                redirect(base_url().'webadmin/user/viewlist');
            }
	}
	
	public function change_status($id,$status){
            $No=$this->User_model->edit(array('status'=>$status),$id);
            if($No>0){
                $this->session->set_flashdata('Message','Record status changed successfully.');
                redirect(base_url().'webadmin/user/viewlist');
            }else{
                $this->session->set_flashdata('Message','Unable to changed the for this record,please try again .');
                redirect(base_url().'webadmin/user/viewlist');
            }
	}
    
    function batchaction(){
        $batchaction_fun=  strtolower($this->input->post('batchaction_fun',TRUE));
        if($batchaction_fun==""){
            $this->session->set_flashdata('Message','Please select user batch action.');
            redirect(base_url().'webadmin/user/viewlist');
        }
        $batchaction_id=$this->input->post('batchaction_id',TRUE);
        //echo $batchaction_fun;die;
        $this->$batchaction_fun($batchaction_id);
    }
    
    public function batchactive($userIds){
        $Action='active';
        $this->User_model->change_status($userIds ,$Action);

        $this->session->set_flashdata('Message','User activated successfully.');
        redirect(base_url().'webadmin/user/viewlist');
    }

    public function batchinactive($userIds){
        $Action='inactive';
        $this->User_model->change_status($userIds ,$Action);

        $this->session->set_flashdata('Message','User inactivated successfully.');
        redirect(base_url().'webadmin/user/viewlist');
    }

    public function batchdelete($userIds){
        //$ProductImages=$this->User_model->get_products_images($userIds);
        $this->User_model->delete($userIds);
        /*foreach($ProductImages as $k){
                $this->delete_product_file($k->Image);
        }*/
        $this->session->set_flashdata('Message','Selected User deleted successfully.');
        redirect(base_url().'webadmin/user/viewlist');
    }
    
    function retrive_password($userId){
        $userDetails=$this->User_model->get_details_by_id($userId);
        $this->email->from("no-reply@tidiit.com", 'Tidiit System Administrator');
        $this->email->to($userDetails[0]->email,$userDetails[0]->firstName.' '.$userDetails[0]->lastName);
        $this->email->subject('Your password at Tidiit Inc. Ltd.');
        $data=array();
        $data['userDetails']=$userDetails;
        $msg=$this->load->view('email_template/retribe_user_password',$data,true);
        //echo $msg;die;
        $this->email->message($msg);
        $this->email->send();
        $this->session->set_flashdata('Message','Password send user registered email address successfully.');
        redirect(base_url().'webadmin/user/viewlist');
    }
    
    function filter(){
        if($this->_is_admin_loged_in()){
            $data=$this->_show_admin_logedin_layout();
            $filterOptions=  $this->input->post('filterOptions',TRUE);
            $filterOptionsValue=  $this->input->post('filterOptionsValue',TRUE);
            $data['DataArr']=$this->User_model->get_all_filter($filterOptions,$filterOptionsValue);
            $data['UserTypeArr']=  $this->User_model->get_user_type_except_buyer_seller();
            $data['logisticData']=  $this->Logistics_model->get_all_active();
            $data['filterOptions']=$filterOptions;
            $data['filterOptionsValue']=$filterOptionsValue;
            $this->load->view('webadmin/user_list',$data);
        }else{
            $data=$this->_show_admin_login();
            $this->load->view('webadmin/login',$data);
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>