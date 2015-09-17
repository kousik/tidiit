<?php
class Ckeditor extends CI_Controller {
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
	// extends CI_Controller for CI 2.x users
	public $data 	= 	array();
	public function __construct() {
		parent::__construct();
		// parent::__construct(); for CI 2.x users
		//$this->load->helper('url'); //You should autoload this one ;)
		//$this->load->helper('ckeditor');
		//Ckeditor's configuration
		
	}
	public function index() {
		//echo 'comming for view';
		$data=$this->AfterLogInTemplate();
		
		/*$data['ckeditor'] = array(
			//ID of the textarea that will be replaced
			'id' 	=> 	'content',
			'path'	=>	$this->config->item('SiteJSURL').'ckeditor',
			//Optionnal values
			'config' => array(
				'toolbar' 	=> 	"Full", 	//Using the Full toolbar
				'width' 	=> 	"550px",	//Setting a custom width
				'height' 	=> 	'100px',	//Setting a custom height
			),
			//Replacing styles from the "Styles tool"
			'styles' => array(
				//Creating a new style named "style 1"
				'style 1' => array (
					'name' 		=> 	'Blue Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 	=> 	'Blue',
						'font-weight' 	=> 	'bold'
					)
				),
				//Creating a new style named "style 2"
				'style 2' => array (
					'name' 	=> 	'Red Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 		=> 	'Red',
						'font-weight' 		=> 	'bold',
						'text-decoration'	=> 	'underline'
					)
				)
			)
		);*/
		/*$data['ckeditor_2'] = array(
			//ID of the textarea that will be replaced
			'id' 	=> 	'content_2',
			'path'	=>	$this->config->item('SiteJSURL').'ckeditor',
			//Optionnal values
			'config' => array(
				'width' 	=> 	"550px",	//Setting a custom width
				'height' 	=> 	'100px',	//Setting a custom height
				'toolbar' 	=> 	array(	//Setting a custom toolbar
					array('Bold', 'Italic'),
					array('Underline', 'Strike', 'FontSize'),
					array('Smiley'),
					'/'
				)
			),
			//Replacing styles from the "Styles tool"
			'styles' => array(
				//Creating a new style named "style 1"
				'style 3' => array (
					'name' 		=> 	'Green Title',
					'element' 	=> 	'h3',
					'styles' => array(
						'color' 	=> 	'Green',
						'font-weight' 	=> 	'bold'
					)
				)
			)
		);*/
		$data['ckeditor']='general editor';
		$data['ckeditor_2']='extra editor';
		$this->load->view('admin/ckeditor', $data);
	}
	
	public function AfterLogInTemplate()
	{
		$data['BaseURL']=$this->config->item('base_url');
		$data['SiteImagesURL']=$this->config->item('SiteImagesURL');
		$data['SiteCSSURL']=$this->config->item('SiteCSSURL');
		$data['SiteJSURL']=$this->config->item('SiteJSURL');
		
		$data['html_head']=$this->load->view('admin/html_head',$data,true);
		$data['header']=$this->load->view('admin/header',$data,true);
		$data['left']=$this->load->view('admin/left',$data,true);
		$data['footer']=$this->load->view('admin/footer',$data,true);
		return $data;
	}
}
?>