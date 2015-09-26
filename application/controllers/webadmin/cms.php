<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cms extends MY_Controller {

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
		$this->load->model('Cms_model');
	}
	
	
	public function index(){	
	
	}
	
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Cms_model->get_all();
		$this->load->view('webadmin/cms_list',$data);
	}

	
	public function add_view(){
		$data=$this->_show_admin_logedin_layout();
		$data['ckeditor'] = array(
			//ID of the textarea that will be replaced
			'id' 	=> 	'body',
			'path'	=>	$this->config->item('SiteJSURL').'ckeditor',
			'judhipath'	=>	$this->config->item('SiteJSURL'),
			//Optionnal values
			'config' => array(
				'toolbar' 	=> 	"Full", 	//Using the Full toolbar
				'width' 	=> 	"120%",	//Setting a custom width
				'height' 	=> 	'250px',	//Setting a custom height
			)
		);
		$this->load->view('webadmin/cms_add',$data);
	}

	public function add(){
		$title=$this->input->post('title',TRUE);
		$body=$this->input->post('body',TRUE);
		$shortBody=$this->input->post('shortBody',TRUE);
		$metaTitle=$this->input->post('metaTitle',TRUE);
		$metaKeyWord=$this->input->post('metaKeyWord',TRUE);
		$metaDescription=$this->input->post('metaDescription',TRUE);
		$status=$this->input->post('status',TRUE);
		
		$dataArr=array(
		'title'=>$title,
		'shortBody'=>$shortBody,
		'body'=>$body,
		'metaTitle'=>$metaTitle,
		'metaKeyWord'=>$metaKeyWord,
		'metaDescription'=>$metaDescription,
		'status'=>$status
		);
		
		//print_r($dataArr);die;
		$this->Cms_model->add($dataArr);
		
		$this->session->set_flashdata('Message','Content added successfully.');
		redirect(base_url().'webadmin/cms/viewlist');
	}
	
	public function view_edit($cmsId){
		$data=$this->_show_admin_logedin_layout();
		$data["dataArr"]=$this->Cms_model->get_content_by_id($cmsId);
		$data['ckeditor'] = array(
			//ID of the textarea that will be replaced
			'id' 	=> 	'body',
			'path'	=>	$this->config->item('SiteJSURL').'ckeditor',
			'judhipath'	=>	$this->config->item('SiteJSURL'),
			//Optionnal values
			'config' => array(
				'toolbar' 	=> 	"Full", 	//Using the Full toolbar
				'width' 	=> 	"120%",	//Setting a custom width
				'height' 	=> 	'250px',	//Setting a custom height
			)
		);
		$this->load->view('webadmin/cms_edit',$data);
	}
	
	
	public function edit(){
		$cmsId=$this->input->post('cmsId',TRUE);
		$title=$this->input->post('title',TRUE);
		$body=$this->input->post('body',TRUE);
		$shortBody=$this->input->post('shortBody',TRUE);
		$metaTitle=$this->input->post('metaTitle',TRUE);
		$metaKeyWord=$this->input->post('metaKeyWord',TRUE);
		$metaDescription=$this->input->post('metaDescription',TRUE);
		$status=$this->input->post('status',TRUE);
		
		$dataArr=array(
		'title'=>$title,
		'shortBody'=>$shortBody,
		'body'=>$body,
		'metaTitle'=>$metaTitle,
		'metaKeyWord'=>$metaKeyWord,
		'metaDescription'=>$metaDescription,
		'status'=>$status
		);
		
		//print_r($dataArr);die;
		$this->Cms_model->edit($dataArr,$cmsId);
		
		$this->session->set_flashdata('Message','Content edited successfully.');
		redirect(base_url().'webadmin/cms/viewlist');
	}
	
	public function delete($id)
	{
		//echo 'comming for delete to id '.$id;die;
		$No=$this->User_model->delete($id);
		if($No>0)
		{
			$this->session->set_flashdata('UserListPageMsg','Record deleted successfully.');
			redirect($this->config->item('base_url').'webadmin/feuser/');
		}else{
			$this->session->set_flashdata('UserListPageMsg','Unabel to delete the record,please try again .');
			redirect($this->config->item('base_url').'webadmin/feuser/');
		}
	}
	
	public function change_status($id,$status){
		if($status==1)
			$status=0;
		else
			$status=1;
		$No=$this->User_model->change_status($id,$status);
		if($No>0)
		{
			$this->session->set_flashdata('UserListPageMsg','Record status changed successfully.');
			redirect($this->config->item('base_url').'webadmin/feuser/');
		}else{
			$this->session->set_flashdata('UserListPageMsg','Unable to changed the for this record,please try again .');
			redirect($this->config->item('base_url').'webadmin/feuser/');
		}
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */