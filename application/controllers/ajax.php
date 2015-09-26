<?php
class Ajax extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        //parse_str($_SERVER['QUERY_STRING'],$_GET);
        $this->db->cache_off();
    }
   
    public function check_registration(){
        //echo 'kk';die;
        $config = array(
            array(
                  'field'   => 'email',
                  'label'   => 'User Name',
                  'rules'   => 'trim|required|xss_clean|min_length[8]|max_length[35]|valid_email|callback_username_check'
               ),
            array(
                  'field'   => 'password',
                  'label'   => 'Password',
                  'rules'   => 'trim|required|xss_clean|min_length[4]|max_length[15]'
               ),
            array(
                  'field'   => 'confirmPassword',
                  'label'   => 'Password',
                  'rules'   => 'trim|required|xss_clean|matches[password]'
               ),
            array(
                  'field'   => 'firstName',
                  'label'   => 'First Name',
                  'rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]'
               ),
            array(
                  'field'   => 'lastName',
                  'label'   => 'Last Name',
                  'rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]'
               ),
            array(
                  'field'   => 'webIdRegistration',
                  'label'   => 'Unathorize Access',
                  'rules'   => 'trim|required|xss_clean|callback_valid_security_code'
               )
            /*,
            array(
                  'field'   => 'agree',
                  'label'   => 'Agree for Terms and Condition',
                  'rules'   => 'trim|required|xss_clean'
               )*/
         );
        //print_r($_POST);die;
        //echo json_encode(array('result'=>'bad','msg'=> 'kkkkkkkkkkkkkkk'));die;
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
            //retun to login page with peroper error
            echo json_encode(array('result'=>'bad','msg'=>validation_errors()));die;
        }else{
            //$userName=$this->input->post('userName',TRUE);
            $password=$this->input->post('password',TRUE);
            $firstName=$this->input->post('firstName',TRUE);
            $lastName=$this->input->post('lastName',TRUE);
            $email=$this->input->post('email',TRUE);
            $userName=$email;
            /*$mobile=$this->input->post('mobile',TRUE);
            $contactNo=$this->input->post('contactNo',TRUE);
            $fax=$this->input->post('fax',TRUE);
            $address=$this->input->post('address',TRUE);
            $city=$this->input->post('city',TRUE);
            $stateId=$this->input->post('stateId',TRUE);
            $countryId=$this->input->post('countryId',TRUE);
            $zip=$this->input->post('zip',TRUE);
            $aboutMe=$this->input->post('aboutMe',TRUE);
            $receiveNewsLetter=$this->input->post('receiveNewsLetter',TRUE);*/
            $receiveNewsLetter='';
            //echo json_encode(array('result'=>'bad','msg'=>$receiveNewsLetter));die; 
            
            $dataArr=array('userName'=>$userName,'password'=>  base64_encode($password).'~'.md5('tidiit'),'firstName'=>$firstName,'lastName'=>$lastName,
                'email'=>$email,'IP'=> $this->input->ip_address(),'userResources'=>'site','userType'=>'buyer','status'=>1);
            $userId=$this->User_model->add($dataArr);
            
            if($userId!=""){
                /*
                 * 
                 $this->load->library('email');
                $msg=$this->get_user_reg_email_body($firstName,$userName,$password);
                $SupportEmail=$this->Siteconfig_model->get_value_by_name('SupportEmail');
                $this->email->from($SupportEmail, 'Tidiit Inc Ltd Support');
                $this->email->to($email,$firstName.' '.$lastName);
                $this->email->subject('Your Tidiit Inc Ltd account information.');
                $this->email->message($msg);
                $this->email->send();
                 * 
                 */
                //$billDataArr=array('userId'=>$userId,'countryId'=>$countryId,'stateId'=>$stateId,'city'=>$city,'address'=>$address,'zip'=>$zip,'contactNo'=>$contactNo);
                //$this->User_model->add_bill_address($billDataArr);
            
                if($receiveNewsLetter==1){
                    if($this->User_model->is_already_subscribe($email)==FALSE){
                        $this->User_model->subscribe($email);
                    }
                }
                
                $this->session->set_userdata('FE_SESSION_VAR',$userId);
                $this->session->set_userdata('FE_SESSION_USERNAME_VAR',$userName);
                //$this->session->set_userdata('FE_SESSION_USERNAME_VAR',$UserName);
                $this->session->set_userdata('FE_SESSION_VAR_TYPE','buyer');
                
                echo json_encode(array('result'=>'good','url'=>BASE_URL,'msg'=>'You have successfully register your account with "Tidiit Inc Ltd.Your login information will be sent to registered email account.'));die; 
            }else{
                echo json_encode(array('result'=>'bad','msg'=>'Please check your user anme and password and try again.'));die;     
            }
        }
    }
    
    public function check_login(){
        $config = array(
            array(
                  'field'   => 'userName',
                  'label'   => 'User Name',
                  'rules'   => 'trim|required|xss_clean'
               ),
                array(
                  'field'   => 'loginPassword',
                  'label'   => 'Password',
                  'rules'   => 'trim|required|xss_clean'
               )
         );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
                //retun to login page with peroper error
                echo json_encode(array('result'=>'bad','msg'=>validation_errors()));die;
        }else{
            $UserName=$this->input->post('userName',TRUE);
            $Password=$this->input->post('loginPassword',TRUE);
            $DataArr=$this->User_model->check_login_data($UserName,$Password,'buyer');
            //print_r($DataArr);die;
            if(count($DataArr)>0){
                //$roleArr=$this->User_model->get_roles_for_user($DataArr[0]->userId);
                $this->session->set_userdata('FE_SESSION_VAR',$DataArr[0]->userId);
                $this->session->set_userdata('FE_SESSION_USERNAME_VAR',$UserName);
                $this->session->set_userdata('FE_SESSION_VAR_FNAME',$DataArr[0]->firstName);
                //$this->session->set_userdata('FE_SESSION_USERNAME_VAR',$UserName);
                $this->session->set_userdata('FE_SESSION_VAR_TYPE','seller');
                echo json_encode(array('result'=>'good','url'=>BASE_URL.'index/home/'));die; 
            }else{
                echo json_encode(array('result'=>'bad','msg'=>'Please check your "Username" and "Password" and try again.'));die;     
            }
        }
    }
    
    
    
    public function username_check($str){
        $userName=$this->input->post('email',TRUE);
        if($userName!=''){
            $str=$userName;
        }
        if($this->User_model->check_username_exists($str,'buyer')==TRUE){
            $this->form_validation->set_message('username_check', 'This User Name already registered.Please try a new one.');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    public function username_check1(){
        $userName=$this->input->post('email',TRUE);
        if($userName!=''){
            $str=$userName;
        }
        if($this->User_model->check_username_exists($str,'buyer')==TRUE){
            echo 'false';die;
        }else{
            echo 'true';die;
        }
    }
    
    public function user_name_to_sameas($str){
        $invalidNameArr=array('test');
        if(!in_array($str,$invalidNameArr)){
            $this->form_validation->set_message('user_name_to_sameas', 'The %s field can not be the word "test"');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    function valid_security_code($str){
        if($str!=$this->session->userdata('secret')){
            $this->form_validation->set_message('valid_security_code', 'Invalid security code,please try again.');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    public function reset_secret(){
        $secret=$this->input->post('secret',TRUE);
        $this->session->set_userdata('secret',$secret);
    }
    
    function show_how_it_works(){
        $how_it_works=$this->session->userdata('HOW_IT_WORKS_HAS_SHOWN');
        if($how_it_works==''){
            $data=  $this->load_default_resources();
            $this->session->set_userdata('HOW_IT_WORKS_HAS_SHOWN','yes');
            $this->load->view('how_it_works',$data);
        }else{
            echo '';die;
        }
    }
}