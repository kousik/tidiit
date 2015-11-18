<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Appdata extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        //$this->load->model('Siteconfig_model');
        //$this->load->model('Testimonial_model');
        $this->load->model('Cms_model');
        $this->load->model('Product_model');
        $this->load->model('Category_model');
    }
    
    function testservice_get()
    {
        $testid = "";
        if($this->get('testid'))
        	$testid = $this->get('testid');
        
        $this->response(array("test ID" => $testid), 200);
    }

    function testpostservice_post()
    {
        $testid = "";
        if($this->post('testid'))
            $testid = $this->post('testid');
        
        $this->response(array("test ID" => $testid), 200);
    }
    
    function home_get(){
        /*$timeStamp=$this->get('timestamp');
        if(!isValidTimeStamp($timeStamp)){
            $this->response(array('error' => 'Invalid timestamp'), 400);
        }else{*/
            $this->load->model('Banner_model','banner');
            $result = array();
            $slider=$this->banner->get_home_slider();
            print_r($slider);die;
        //}
    }
}
    
?>