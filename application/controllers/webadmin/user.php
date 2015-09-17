<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends My_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();
		$this->load->model('User_model');
                $this->db->cache_off();
	}
	
	
	public function viewlist(){
            if($this->_is_admin_loged_in()){
                    $data=$this->_show_admin_logedin_layout();
                    $data['DataArr']=$this->User_model->get_all();
                    $data['UserTypeArr']=  $this->User_model->get_user_type();
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
		
                //pre($_POST);die;
		$DataArr=array('userName'=>trim($userName),'password'=>  base64_encode(trim($password)).'~'.md5('tidiit'),'email'=>$email,
                    'status'=>trim($status),'firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$contactNo);
		
		if($this->User_model->check_username_exists($userName)==TRUE){
			$this->session->set_flashdata('Message','Username already used,Please try again.');
			redirect(base_url().'webadmin/user/viewlist');
		}
		
                if($this->User_model->check_user_email_exists($email)==TRUE){
			$this->session->set_flashdata('Message','Email already used,Please try again.');
			redirect(base_url().'webadmin/user/viewlist');
		}
                //pre($DataArr);die;
		if($this->User_model->add($DataArr))
		{
			$this->session->set_flashdata('Message','User added successfully.');
			redirect(base_url().'webadmin/user/viewlist');
		}else{
			$this->session->set_flashdata('Message','Unable to user,Please try again.');
			redirect(base_url().'webadmin/user/viewlist');
		}
	}
	
	public function edit()
	{
            $userId=$this->input->post('userId');
            $email=$this->input->post('Editemail',true);
            $firstName=$this->input->post('EditfirstName',true);
            $lastName=$this->input->post('EditlastName',true);
            $contactNo=$this->input->post('EditcontactNo',true);
            if($userId>0){
                
                $DataArray=array('email'=>$email,'firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$contactNo);
                $No=$this->User_model->edit($DataArray,$userId);
                if($No>0){
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
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>