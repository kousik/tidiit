<?php
class Index extends MY_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->library('session');
	}
	
	function index(){
            
		if($this->_is_admin_loged_in()==FALSE){
			$this->session->set_flashdata('Message','Please login to access admin section');
			redirect(base_url().'webadmin/index/login');
			
		}else{
			redirect(base_url().'webadmin/index/admin_home');
		}
	}
	
	function viewsitedata(){
		$data=$this->_show_admin_logedin_layout();
		$this->load->view('webadmin/site_data_config_link',$data);
	}
	
	function admin_home(){
		$this->_show_admin_home();
		/*if($this->_is_admin_loged_in()==TRUE){
			//die('true');
			
		}else{
			//die('false');
			
		}*/
	}
	
	public function login(){
		$this->_show_admin_login();
	}
	
	public function check_login(){
		$UserName=$this->input->post('UserName',TRUE);
		$Password=$this->input->post('Password',TRUE);
                //echo '$UserName = '.$UserName.' $Password = '.$Password;die;
		$DataArr=$this->Admin_model->is_valid_data($UserName,$Password);
		//print_r($DataArr);die;
		if(count($DataArr)>0){
                    $roleArr=$this->Admin_model->get_roles_for_user($DataArr[0]->userId);
                    $this->session->set_userdata('ADMIN_ROLE_VAR',$roleArr);
			$this->session->set_userdata('ADMIN_SESSION_VAR',$DataArr[0]->userId);
			$this->session->set_userdata('ADMIN_SESSION_USERNAME_VAR',$UserName);
			$this->session->set_userdata('ADMIN_SESSION_USER_VAR_TYPE',$DataArr[0]->userType);
			$this->session->set_flashdata('Message','You have loged successfully.');
			redirect(base_url().'webadmin/index/admin_home');
		}else{
			$this->session->set_flashdata('Message','Invalid Login,Please try again');
			redirect(base_url().'webadmin/index/login');
		}
	}

	public function logout(){
		$this->session->unset_userdata('ADMIN_SESSION_VAR');
		$this->session->unset_userdata('ADMIN_SESSION_USERNAME_VAR');
		$this->session->unset_userdata('ADMIN_SESSION_USER_VAR_TYPE');
		$this->session->set_flashdata('LoginPageMsg', 'You are logout successfully');
		redirect(base_url().'webadmin/index/admin_home');
	}
        
        public function change_pass(){
            $data=$this->_show_admin_logedin_layout();
            $this->load->view('webadmin/change_pass',$data);
        }
        
        public function change_password(){
            $Password=$this->input->post('NewPassword',TRUE);
            $this->Admin_model->update_info(array('Password'=>base64_encode($Password)),  $this->session->userdata('ADMIN_SESSION_VAR'));
            $this->session->set_flashdata('AdminHeaderMessage', 'Password changed successfully.');
            
            $data=array();
            $data['SiteImagesURL']=$this->config->item('SiteImagesURL');
            $data['SiteCSSURL']=$this->config->item('SiteCSSURL');
            $data['SiteJSURL']=$this->config->item('SiteJSURL');
            $data['CurrentCont']=$this->uri->segment(2);
            $data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
            $data['AdminHomeLeftPanel']=$this->load->view('webadmin/left',$data,TRUE);
            $data['AdminHomeRest']=$this->load->view('webadmin/admin_home_rest',$data,TRUE);
            $data['AdminHeaderMessage']='Password changed successfully';
            $this->load->view('webadmin/home',$data);
        }
        
        function under_construnction(){
            die('rrrrrrrrrrrrrrrrrrrrrr');
        }
}
?>