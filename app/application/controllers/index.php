<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {

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
                //$this->load->model('Admin_model');
	}
	
	
	public function index(){
            if($this->validateLogin()){
                /// loading event list
                $this->showHome();
            }else{
                $this->showLogin();
            }
	}
        
        public function showHome(){
            $this->_show_admin_home();
        }


        private  function showLogin(){
            $data=array();
            $data['SiteImagesURL']=$this->config->item('SiteImagesURL');
            $data['SiteCSSURL']=$this->config->item('SiteCSSURL');
            $data['SiteJSURL']=$this->config->item('SiteJSURL');
            $data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');

            $this->load->view('login',$data);
        }
        
        public function check_login(){
            echo '<pre>';print_r($_POST);die;
		/*$UserName=$this->input->post('UserName',TRUE);
		$Password=$this->input->post('Password',TRUE);
		$DataArr=$this->Admin_model->is_valid_data($UserName,$Password);
		//print_r($DataArr);die;
		if(count($DataArr)>0){
			$this->session->set_userdata('ADMIN_SESSION_VAR',$DataArr[0]->AdminID);
			$this->session->set_userdata('ADMIN_SESSION_USERNAME_VAR',$UserName);
			$this->session->set_flashdata('Message','You have loged successfully.');
			redirect(base_url().'index/showHome');
		}else{
			$this->session->set_flashdata('Message','Invalid Login,Please try again');
			redirect(base_url().'index/login');
		}*/
	}
        
        
        public function login(){
            $data=array();
            $data['SiteImagesURL']=$this->config->item('SiteImagesURL');
            $data['SiteCSSURL']=$this->config->item('SiteCSSURL');
            $data['SiteJSURL']=$this->config->item('SiteJSURL');
            $data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');

            $this->load->view('login',$data);
        }
        
        public function showLogedList(){
            $data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Admin_model->show_loged_list();
		$this->load->view('loged_data_list',$data);
        }


        private function validateLogin(){
            if($this->session->userdata('ADMIN_SESSION_VAR')!="" || $this->session->userdata('ADMIN_SESSION_VAR')>0){
                return true;
            }else{
                return false;
            }
        }


        public function go_next()
	{
		$this->load->view('go_next');
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>