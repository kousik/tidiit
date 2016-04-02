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
    
    function upload_image_post(){
        $UDID=$this->post('UDID');
        if($UDID==""){
            $this->response(array('error' => 'Invide UDID'), 400);return FALSE;
        }
        $deviceToken=$this->post('deviceToken');
        if($deviceToken==""){
            $this->response(array('error' => 'Invide devive token'), 400);return FALSE;
        }
        $deviceType=$this->post('deviceType');
        if($deviceType==""){
            $this->response(array('error' => 'Invide devive type'), 400);return FALSE;
        }
        if(array_key_exists('userFile', $_FILES)){
            $fileName='';
            $uploadPath=$_SERVER['DOCUMENT_ROOT'].'/resources/qr_code/';
            $file=$_FILES['userFile'];
            $fileName=time().'-'.basename(str_replace(' ','-',$file['name']));
            if(move_uploaded_file($file['tmp_name'], $uploadPath.$fileName)){
                $result['message']='Add data(deviceToken : '.$deviceToken.',deviceType : '.$deviceType.',UDID : '.$UDID.', File : '.$fileName.' at '.$uploadPath.') submited successfully.';
                header('Content-type: application/json');
                echo json_encode($result);        
            }else{
                $this->response(array('error'=>'Please upload a valid file.'),400);return false;
            }
        }else{
            $this->response(array('error' => 'Your have not submited Multipart formate of formdata or file field name must be "userFile".'), 400);return FALSE;
        }
    }
}