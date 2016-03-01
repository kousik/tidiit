<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Welcome extends REST_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
    }
    
    function login_post(){
        $userName=  $this->post('userName');
        $password=  $this->post('password');
        
        $result=array();
        $result['response']="true";
        $result['postData']="$userName: ".$userName.' <=> $password= '.$password;
        header('Content-type: application/json');
        echo json_encode($result);
    }
}