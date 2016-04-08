<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Logistics extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Siteconfig_model','siteconfig');
        //$this->load->model('Testimonial_model');
        $this->load->model('Cms_model','cms');
        $this->load->model('Product_model','product');
        $this->load->model('Category_model','category');
        $this->load->model('Order_model','order');
        $this->load->model('Country');
    }
    
    function login_post(){
        $userName=  trim($this->post('userName'));
        $password=  trim($this->post('password'));
        $UDID=  trim($this->post('UDID'));
        $deviceType=  trim($this->post('deviceType'));
        $deviceToken=  trim($this->post('deviceToken'));
        $latitude=  trim($this->post('latitude'));
        $longitude=  trim($this->post('longitude'));
        
        if (!filter_var($userName, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        if($password==""){
            $this->response(array('error' => 'Please provide password.'), 400); return FALSE;
        }
        
        if($UDID==""){
            $this->response(array('error' => 'Please provide UDID.'), 400); return FALSE;
        }
        
        if($deviceToken==""){
            $this->response(array('error' => 'Please provide deviceToken.'), 400); return FALSE;
        }
        
        if($deviceType==""){
            $this->response(array('error' => 'Please provide deviceType.'), 400); return FALSE;
        }
        
        if($latitude==""){
            $this->response(array('error' => 'Please provide latitude.'), 400); return FALSE;
        }
        
        if($longitude==""){
            $this->response(array('error' => 'Please provide longitude.'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //$countryShortName='IN';
        if($countryShortName==FALSE){
            //$this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
        }
        
        //$rs=$this->db->from('user')->where('userName',$userName,'password',  base64_decode($password).'~'.)
        $rs=$this->user->check_login_data($userName,$password,'buyer');
        if(count($rs)>0){
            $parram=array('userId'=>$rs[0]->userId,'message'=>'You have logedin successfully');
            success_response_after_post_get($parram);
        }else{$this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;}
    }
}