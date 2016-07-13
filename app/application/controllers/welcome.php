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
    
    function soap_mpesa_post(){
        ini_set('default_socket_timeout','300');
        ini_set("soap.wsdl_cache_enabled", 0);
        //$soapURL ='https://182.19.20.182:81/mcommerce/pgService?wsdl';
        $soapURL ='http://182.19.20.182:81/mcommerce.webservices/pgService?wsdl';
        echo '<pre>API URL : '.$soapURL.'<br>';
        $soapParameters = Array('Userid' => "aJtlkG0NQTRBaLgVt4YC4A==", 'Password' => "7p/MAUl80KP+FdRERRyvlQ==") ;
        echo 'Authentication Data <br>';print_r($soapParameters);
        //$soapParameters = Array('login' => "aJtlkG0NQTRBaLgVt4YC4A==", 'password' => "7p/MAUl80KP+FdRERRyvlQ==") ;
        $soapFunction = "processPayment" ;
        echo 'soap method : '.$soapFunction.' <br>';
        $soapFunctionParameters = Array('MCODE'=>'0021225252','TXNDATE' =>date('dmY'),'TRANSREFNO'=>time(),'MSISDN'=>'9556644964','AMT'=>'1.00','NARRATION'=>'shopping with mpes','mPIN'=>'1111'); ;
        echo 'soap method parameter <br>';
        print_r($soapFunctionParameters);
        $soapClient = new SoapClient($soapURL, $soapParameters);
        echo '<b>soap client init :</b> <br>';print_r($soapClient);
        echo 'calling soap method and show result <br>';
        $soapResult = $soapClient->__soapCall($soapFunction, $soapFunctionParameters) ;
        print_r($soapResult);echo '</pre>';die;
        //if(is_array($soapResult) && isset($soapResult['someFunctionResult'])) {
        if(is_array($soapResult)) {
            // Process result.
            echo '<pre>';print_r($soapResult);die;
        } else {
            // Unexpected result
            if(function_exists("debug_message")) {
                debug_message("Unexpected soapResult for {$soapFunction}: ".print_r($soapResult, TRUE)) ;
            }
        } 
    } 
}