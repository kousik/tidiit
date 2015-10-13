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
               )/*,
            array(
                  'field'   => 'webIdRegistration',
                  'label'   => 'Unathorize Access',
                  'rules'   => 'trim|required|xss_clean|callback_valid_security_code'
               )*/
            /*,
            array(
                  'field'   => 'agree',
                  'label'   => 'Agree for Terms and Condition',
                  'rules'   => 'trim|required|xss_clean'
               )*/
         );
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
            $receiveNewsLetter = 1;
            
            $dataArr=array('userName'=>$userName,'password'=>  base64_encode($password).'~'.md5('tidiit'),'firstName'=>$firstName,'lastName'=>$lastName,
                'email'=>$email,'IP'=> $this->input->ip_address(),'userResources'=>'site','userType'=>'buyer','status'=>1);
            $userId=$this->User_model->add($dataArr);
            
            if($userId!=""){               
            
                if($receiveNewsLetter==1){
                    if($this->User_model->is_already_subscribe($email)==FALSE){
                        $this->User_model->subscribe($email);
                    }
                }
                $users=$this->User_model->get_details_by_id($userId);
                $this->session->set_userdata('FE_SESSION_VAR',$userId);
                $this->session->set_userdata('FE_SESSION_USERNAME_VAR',$userName);
                $this->session->set_userdata('FE_SESSION_VAR_TYPE','buyer');
                $this->session->set_userdata('FE_SESSION_VAR_FNAME',$firstName);
                $this->session->set_userdata('FE_SESSION_UDATA',$users[0]);
                
                echo json_encode(array('result'=>'good','url'=>BASE_URL.'my-billing-address','msg'=>'You have successfully register your account with "Tidiit Inc Ltd.Your login information will be sent to registered email account.'));die; 
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
                $this->session->set_userdata('FE_SESSION_UDATA',$DataArr[0]);
                echo json_encode(array('result'=>'good','url'=>BASE_URL));die; 
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
            echo "false";die;
        }else{
            echo "true";die;
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
    
    function show_city_by_country(){
        $countryId=$this->input->post('countryId',TRUE);
        if($countryId==""){
            echo '';die;
        }else{
            $this->load->model('Country');
            $cityDataArr=$this->Country->get_city_country($countryId);
            if(empty($cityDataArr)){
                echo '';die;
            }else{
                $html='<select class="form-control nova heght_cntrl required" name="cityId" id="cityId" value=""  tabindex="1"><option value="">Select</option>';
                foreach($cityDataArr AS $k){
                    $html .='<option value="'.$k->cityId.'">'.$k->city.'</option>';
                }
                $html .='</select>';
                echo $html;die;
            }
        }
    }
    
    function show_zip_by_city(){
        $cityId=$this->input->post('cityId',TRUE);
        if($cityId==""){
            echo '';die;
        }else{
            $this->load->model('Country');
            $zipDataArr=$this->Country->get_all_zip1($cityId);
            if(empty($zipDataArr)){
                echo '';die;
            }else{
                $html='<select class="form-control nova heght_cntrl required" name="zipId" id="zipId" value=""  tabindex="1"><option value="">Select</option>';
                foreach($zipDataArr AS $k){
                    $html .='<option value="'.$k->zipId.'">'.$k->zip.'</option>';
                }
                $html .='</select>';
                echo $html;die;
            }
        }
    }
    
    function show_locality_by_zip(){
        $zipId=$this->input->post('zipId',TRUE);
        if($zipId==""){
            echo '';die;
        }else{
            $this->load->model('Country');
            $localityDataArr=$this->Country->get_all_locality1($zipId);
            if(empty($localityDataArr)){
                echo '';die;
            }else{
                $html='<select class="form-control nova heght_cntrl required" name="localityId" id="localityId" value=""  tabindex="1"><option value="">Select</option>';
                foreach($localityDataArr AS $k){
                    $html .='<option value="'.$k->localityId.'">'.$k->locality.'</option>';
                }
                $html .='</select>';
                echo $html;die;
            }
        }
    }
    
    function show_locality_all_users(){
       $localityId=$this->input->post('localityId',TRUE);
        if($localityId==""){
            echo '';die;
        }else{
            $this->load->model('User_model');
            $UsersDataArr=$this->User_model->get_all_users_by_locality($localityId);
            if(empty($UsersDataArr)){
                echo '';die;
            }else{
                $html ='<div class="form-group"><label>Select Group Users :</label></div>';
                $html .='<div class="boxes">';
                foreach($UsersDataArr as $user):
                $html.='<div class="form-group checkbox-'.$user->userId.'"><div class="checkbox">
                           <label>
                               <input type="checkbox" class="tags-group" name="" value="'.$user->userId.'" data-name="'.$user->firstName.' '.$user->lastName.' ('.$user->email.')"> '.$user->firstName.' '.$user->lastName.' ('.$user->email.')
                           </label>
                      </div></div>';
                $html .='';
                endforeach;
                $html .='</div>';
                echo $html;die;
            }
        } 
    }
    
    function submit_my_billing_address(){
        $config = array(
            array(
                  'field'   => 'firstName',
                  'label'   => 'First Name',
                  'rules'   => 'trim|required|xss_clean'
               ),
            array(
                  'field'   => 'lastName',
                  'label'   => 'Last Name',
                  'rules'   => 'trim|required|xss_clean'
               ),
           array(
                  'field'   => 'phone',
                  'label'   => 'Phone',
                  'rules'   => 'trim|required|xss_clean'
               ),
           array(
                  'field'   => 'email',
                  'label'   => 'Email',
                  'rules'   => 'trim|required|xss_clean|valid_email'
               ),
           array(
                  'field'   => 'countryId',
                  'label'   => 'Country',
                  'rules'   => 'trim|required|xss_clean'
               ),
           array(
                  'field'   => 'cityId',
                  'label'   => 'City',
                  'rules'   => 'trim|required|xss_clean'
               ),
           array(
                  'field'   => 'zipId',
                  'label'   => 'Zip',
                  'rules'   => 'trim|required|xss_clean'
               ),
           array(
                  'field'   => 'localityId',
                  'label'   => 'Locality',
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
            $userId=$this->session->userdata('FE_SESSION_VAR');
            if($userId==""){
                echo json_encode(array('result'=>'bad','msg'=>'Please login before update changes.'));die;
            }else{
                $firstName=$this->input->post('firstName',TRUE);
                $lastName=$this->input->post('lastName',TRUE);
                $phone=$this->input->post('phone',TRUE);
                $email=$this->input->post('email',TRUE);
                $countryId=$this->input->post('countryId',TRUE);
                $cityId=$this->input->post('cityId',TRUE);
                $zipId=$this->input->post('zipId',TRUE);
                $localityId=$this->input->post('localityId',TRUE);
                $address=$this->input->post('address',TRUE);
                
                $this->User_model->edit(array('firstName'=>$firstName,'lastName'=>$lastName,'email'=>$email),$userId);
                $isAdded=$this->User_model->is_billing_address_added();
                if(empty($isAdded)){
                    $this->load->model('Country');
                    $rs=$this->Country->city_details($cityId);
                    $this->User_model->add_biiling_info(array('contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'userId'=>$userId,'address'=>$address));
                }else{
                    $this->User_model->edit_biiling_info(array('contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'address'=>$address),$userId);
                }
                echo json_encode(array('result'=>'good'));die; 
            }
            
        }
    }
    
    function add_new_group(){
        $groupAdminId = $this->input->post('groupAdminId',TRUE);
        $groupTitle = $this->input->post('groupTitle',TRUE);
        $productType = $this->input->post('productType',TRUE);
        $groupUsersArr = $this->input->post('groupUsers',TRUE);
        $groupUsers = implode(",", $groupUsersArr);
        $colors = array('red','maroon','purple','green','blue');
        $rand_keys = array_rand($colors, 1);
        $groupColor = $colors[$rand_keys];
        $notify = array();
        
        $groupId = $this->User_model->group_add(array('groupAdminId'=>$groupAdminId,'groupTitle'=>$groupTitle,'productType'=>$productType,'groupUsers'=>$groupUsers,'groupColor'=>$groupColor));
        if($groupId):
            foreach($groupUsersArr as $guser):
                $notify['senderId'] = $groupAdminId;
                $notify['receiverId'] = $guser;
                $notify['nType'] = "GROUP-ADD";
                $notify['nTitle'] = $groupTitle;
                $this->send_notification($notify);
            endforeach;
            echo json_encode(array('result'=>'good'));die; 
        else:    
            echo json_encode(array('result'=>'bad','msg'=>'Some error happen. Please try again!'));die;
        endif;
    }
    
    function update_group(){
        $groupId = $this->input->post('groupId',TRUE);
        $groupTitle = $this->input->post('groupTitle',TRUE);
        $productType = $this->input->post('productType',TRUE);
        $groupUsersArr = $this->input->post('groupUsers',TRUE);
        $groupUsers = implode(",", $groupUsersArr);
        $colors = array('red','maroon','purple','green','blue');
        $rand_keys = array_rand($colors, 1);
        $groupColor = $colors[$rand_keys];
        
        $bfrUpdateGroup = $this->User_model->get_group_by_id($groupId);
        $bfrUsers = explode(",", $bfrUpdateGroup->groupUsers);
        $olduser = array();
        $deluser = array();
        $newUser = array();
        foreach($groupUsersArr as $guser):
            if(in_array($guser, $bfrUsers)):
                $olduser[] = $guser;
            else:
                $newUser[] = $guser;
            endif;
        endforeach;
        foreach($bfrUsers as $bfruser):
            if(!in_array($bfruser, $groupUsersArr)):
                $deluser[] = $bfruser;
            endif;
        endforeach;        
        
        $groupId = $this->User_model->group_update(array('groupTitle'=>$groupTitle,'productType'=>$productType,'groupUsers'=>$groupUsers,'groupColor'=>$groupColor), $groupId);
        if($groupId):
            foreach($olduser as $ouser):
                $notify['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $notify['receiverId'] = $ouser;
                $notify['nType'] = "GROUP-MODIFY";
                $notify['nTitle'] = $groupTitle;
                $this->send_notification($notify);
            endforeach;

            foreach($newUser as $nuser):
                $notify['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $notify['receiverId'] = $nuser;
                $notify['nType'] = "GROUP-MODIFY-NEW";
                $notify['nTitle'] = $groupTitle;
                $this->send_notification($notify);
            endforeach;

            foreach($deluser as $duser):
                $notify['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $notify['receiverId'] = $duser;
                $notify['nType'] = "GROUP-MODIFY-DELETE";
                $notify['nTitle'] = $groupTitle;
                $this->send_notification($notify);
            endforeach;
            echo json_encode(array('result'=>'good'));die; 
        else:    
            echo json_encode(array('result'=>'bad','msg'=>'Some error happen. Please try again!'));die;
        endif;
    }
    
    function delete_group(){
        $groupId = $this->input->post('groupId',TRUE);
        $del = $this->User_model->group_delete($groupId);
        if($del):
            echo json_encode(array('result'=>'good'));die; 
        else:    
            echo json_encode(array('result'=>'bad'));die;
        endif;
    }
    
    function send_notification($data){
        /*
        $notify['senderId'] = ;
        $notify['receiverId'] = ;
        $notify['nType'] = ;
        $notify['nTitle'] = ;
        $notify['nMessage'] = ;
         */
        $type = $data['nType'];
        switch($type){
            case 'GROUP-ADD':
                $data['nMessage'] = "Hi, <br> You Have added in my group <b>[".$data['nTitle']."]</b>";
                $data['isEmail'] = true;
                $data['isMobMessage'] = true;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
            case 'GROUP-MODIFY':
                $data['nMessage'] = "Hi, <br> Group <b>[".$data['nTitle']."]</b> has been modified.";
                $data['isEmail'] = true;
                $data['isMobMessage'] = true;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
            case 'GROUP-MODIFY-NEW':
                $data['nMessage'] = "Hi, <br> You Have added in my group <b>[".$data['nTitle']."]</b>";
                $data['isEmail'] = true;
                $data['isMobMessage'] = true;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
            case 'GROUP-MODIFY-DELETE':
                $data['nMessage'] = "Hi, <br> You are not part of this group <b>[".$data['nTitle']."]</b>";
                $data['isEmail'] = true;
                $data['isMobMessage'] = true;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
        }
        
        
        $data['isRead'] = 0;
        $data['status'] = 1;
        
        
        
        if($data['isMobMessage']):
            //Send Mobile message
            unset($data['isMobMessage']);
        endif;
        
        if($data['isEmail']):
            //Send Email message
            unset($data['isEmail']);
        endif;
        
        $this->User_model->notification_add($data);
    }
}