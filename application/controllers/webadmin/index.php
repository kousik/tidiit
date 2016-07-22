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
        
        function view_push_notification_test(){
            $data=$this->_show_admin_logedin_layout();
            $this->load->view('webadmin/push_notification_test',$data);
	}
	
	function admin_home(){
            $this->_show_admin_home();
	}
	
	public function login(){
		$this->_show_admin_login();
	}
	
	public function check_login(){
            if(strtoupper(trim($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')))!='IN'){
                $this->session->set_flashdata('Message','Invalid Login,Please try again');
                redirect(base_url().'webadmin/index/login');
            }
            $UserName=$this->input->post('UserName',TRUE);
            $Password=$this->input->post('Password',TRUE);
            //echo '$UserName = '.$UserName.' $Password = '.$Password;die;
            $DataArr=$this->Admin_model->is_valid_data($UserName,$Password);
            if(count($DataArr)>0){
                if($DataArr[0]->status==1){
                    if($DataArr[0]->userType=='accounts' || $DataArr[0]->userType=='admin' || $DataArr[0]->userType=='supper_admin'){
                        //$roleArr=$this->Admin_model->get_roles_for_user($DataArr[0]->userId);
                        $roleArr=  $this->get_admin_user_role();
                        //pre($roleArr);
                        //pre($roleArr[$DataArr[0]->userType]);die;
                        $roleVar=  serialize($roleArr[$DataArr[0]->userType]);

                        $this->session->set_userdata('ADMIN_ROLE_VAR',$roleVar);
                        $this->session->set_userdata('ADMIN_SESSION_VAR',$DataArr[0]->userId);
                        $this->session->set_userdata('ADMIN_SESSION_USERNAME_VAR',$UserName);
                        $this->session->set_userdata('ADMIN_SESSION_USER_VAR_TYPE',$DataArr[0]->userType);
                        $this->session->set_flashdata('Message','You have loged successfully.');
                        $this->User_model->add_login_history(array('userId'=>$DataArr[0]->userId));
                        redirect(base_url().'webadmin/index/admin_home');
                    }else{
                        $this->session->set_flashdata('Message','You have no access for this section,Please contact administrator.');
                        redirect(base_url().'webadmin/index/login');
                    }
                }else{
                    $this->session->set_flashdata('Message','Your access has disabled.,Please contact administrator.');
                    redirect(base_url().'webadmin/index/login');
                }
            }else{
                    $this->session->set_flashdata('Message','Invalid Login,Please try again');
                    redirect(base_url().'webadmin/index/login');
            }
	}
        
        function get_admin_user_role(){
            return  array(
                'accounts'=>array(
                    'controller'=>array('order','user'),
                    'method'=>array()
                ),
                'admin'=>array(
                    'controller'=>array('index','user','category','site_config'),
                    'method'=>array()
                ),
                'supper_admin'=>array(
                    'controller'=>array('index','user','category','site_config','order','role'),
                    'method'=>array()
                )
            );
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