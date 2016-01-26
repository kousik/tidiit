<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Appdata extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Siteconfig_model','siteconfig');
        $this->load->model('Product_model','product');
        $this->load->model('Category_model','category');
        $this->load->model('Order_model','order');
        $this->load->model('Country');
        
    }
    
    function login_post(){
        $userName=$this->post('email');
        $userName=$this->post('password');
        
        if (!filter_var($userName, FILTER_VALIDATE_EMAIL)) {
            //$this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
            $parram=array('error'=>TRUE,'message'=>'Invalid email address provided.');
            success_response_after_post_get($parram);die;
        }
        
        if($password==""){
            $parram=array('error'=>TRUE,'message'=>'Please provide the password.');
            success_response_after_post_get($parram);die;
        }
        
        $rs=$this->user->check_login_data($userName,$password,'buyer');
        if(count($rs)>0){
            $parram=array('uid'=>$rs[0]->userId,'error'=>FALSE);
            $parram['user']['name']=$rs[0]->firstName.' '.$rs[0]->lastName;
            $parram['user']['email']=$rs[0]->email;
            $parram['user']['created_at']=time();
            $parram['user']['updaated_at']=time();
            success_response_after_post_get($parram);
        }
    }
}