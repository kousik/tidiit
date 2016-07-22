<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends MY_Controller{
    private $commonProfileCompletionMessage;
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->model('Siteconfig_model');
        $this->load->model('Order_model');
        //parse_str($_SERVER['QUERY_STRING'],$_GET);
        $this->load->library('cart');
        $this->db->cache_off();
        $this->commonProfileCompletionMessage='Please complete "My Profile","My Shipping Address","My Buying Club","My Fiance" before start order process.';
    }
   
    public function check_registration(){
        if(strtoupper(trim($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')))!='IN'){
            echo json_encode(array('result'=>'bad','msg'=>'Please check your user anme and password and try again.','profile_common_message'=>""));die;     
        }
        //echo 'kk';die;
        $config = array(
            array('field'   => 'email','label'   => 'User Name','rules'=> 'trim|required|xss_clean|min_length[8]|max_length[35]|valid_email|callback_username_check'),
            array('field'   => 'password','label'   => 'Password','rules'   => 'trim|required|xss_clean|min_length[4]|max_length[15]'),
            array('field'   => 'confirmPassword','label'   => 'Password','rules'   => 'trim|required|xss_clean|matches[password]'),
            array('field'   => 'firstName','label'   => 'First Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]'),
            array('field'   => 'lastName','label'   => 'Last Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]')
         );
        //echo json_encode(array('result'=>'bad','msg'=> 'kkkkkkkkkkkkkkk'));die;
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
            //retun to login page with peroper error
            echo json_encode(array('result'=>'bad','msg'=>str_replace('</p>','',str_replace('<p>','',validation_errors()))));die;
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
                $this->User_model->add_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'userId'=>$userId));
                if($receiveNewsLetter==1){
                    if($this->User_model->is_already_subscribe($email)==FALSE){
                        $this->User_model->subscribe($email);
                    }
                }
                $this->User_model->add_login_history(array('userId'=>$userId,'IP'=>$this->input->ip_address()));
                $users=$this->User_model->get_details_by_id($userId);
                $this->session->set_userdata('FE_SESSION_VAR',$userId);
                $this->session->set_userdata('FE_SESSION_USERNAME_VAR',$userName);
                $this->session->set_userdata('FE_SESSION_VAR_TYPE','buyer');
                $this->session->set_userdata('FE_SESSION_VAR_FNAME',$firstName);
                $this->session->set_userdata('FE_SESSION_UDATA',$users[0]);
                
                $mail_template_data=array();
                $mail_template_data['TEMPLATE_CREATE_USER_EAMIL']=$email;
                $mail_template_data['TEMPLATE_CREATE_USER_FIRSTNAME']=$firstName;
                $mail_template_data['TEMPLATE_CREATE_USER_LASTNAME']=$lastName;
                $mail_template_data['TEMPLATE_CREATE_USER_USERNAME']=$email;
                $mail_template_data['TEMPLATE_CREATE_USER_PASSWORD']=$password;
                
                $mail_template_view_data=$this->load_default_resources();
                $mail_template_view_data['create_user']=$mail_template_data;
                $this->_global_tidiit_mail($email, "Your account at Tidiit Inc. Ltd.", $mail_template_view_data,'user_create',$firstName.' '.$lastName);
                
                echo json_encode(array('result'=>'good','url'=>BASE_URL.'my-shipping-address','msg'=>'You have successfully register your account with "Tidiit Inc Ltd.Your login information will be sent to registered email account.','profile_common_message'=>  $this->commonProfileCompletionMessage));die; 
            }else{
                echo json_encode(array('result'=>'bad','msg'=>'Please check your user anme and password and try again.','profile_common_message'=>""));die;     
            }
        }
    }
    
    public function check_login(){
        if(strtoupper(trim($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')))!='IN'){
            echo json_encode(array('result'=>'bad','msg'=>'Please check your user anme and password and try again.','profile_common_message'=>""));die;     
        }
        $config = array(
            array('field'   => 'userName','label'   => 'User Name','rules'   => 'trim|required|xss_clean|valid_email'),
            array('field'   => 'loginPassword','label'   => 'Password','rules'   => 'trim|required|xss_clean')
         );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
                //retun to login page with peroper error
                echo json_encode(array('result'=>'bad','msg'=>str_replace('</p>','',str_replace('<p>','',validation_errors()))));die;
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
                
                $this->db->where('userId',$DataArr[0]->userId)->where('status',0)->from('order');
                $this->session->set_userdata('TotalItemInCart',$this->db->count_all_results());
                
                $this->User_model->add_login_history(array('userId'=>$DataArr[0]->userId,'IP'=>$this->input->ip_address()));
                $redirect_url = $this->input->post('redirect_url',TRUE);
                echo json_encode(array('result'=>'good','url'=>$redirect_url?$redirect_url:$_SERVER['HTTP_REFERER']));die; 
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
        //if($this->User_model->check_username_exists($str,'buyer')==TRUE){
        if($this->User_model->check_username_exists_without_type($str)==TRUE){
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
                $html='<select class="form-control nova heght_cntrl required" name="cityId" id="cityId" value=""  tabindex="1"><option value="">Select City</option>';
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
                $html='<select class="form-control nova heght_cntrl required" name="zipId" id="zipId" value=""  tabindex="1"><option value="">Select Zip</option>';
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
                $html='<select class="form-control nova heght_cntrl required" name="localityId" id="localityId" value=""  tabindex="1"><option value="">Select Locality</option>';
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
                $html ='<label class="col-sm-3 control-label">Select Buying Club Users :</label>';
                $html .='<div class="boxes">';
                foreach($UsersDataArr as $user):
                $html.='<div class="checkbox-'.$user->userId.'"><div class="checkbox">
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
            array('field'   => 'firstName','label'   => 'First Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'lastName','label'   => 'Last Name','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'phone','label'   => 'Phone','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'email','label'   => 'Email','rules'   => 'trim|required|xss_clean|valid_email'),
           array('field'   => 'address','label'   => 'Address','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'countryId','label'   => 'Country','rules'   => 'trim|required|xss_clean'), 
           array('field'   => 'cityId','label'   => 'City','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'zipId','label'   => 'Zip','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'localityId','label'   => 'Locality','rules'   => 'trim|required|xss_clean') , 
           array('field'   => 'productTypeId[]','label'   => 'Product Type','rules'   => 'trim|required|xss_clean')
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
                $productTypeId=$this->input->post('productTypeId',TRUE);
                $newCateoryArr=array();
                //pre($productTypeId);
                foreach ($productTypeId AS $k =>$v){
                    $newCateoryArr[]=$v;
                    $newCateoryArr=$this->recusive_category($newCateoryArr,$v);
                }
                //pre($newCateoryArr);die;
                $this->User_model->update_user_product_type_category(array('productTypeCateoryId'=>implode(',', $newCateoryArr)),$userId);
                $this->User_model->remove_user_from_product_type($userId);
                foreach($newCateoryArr As $k => $v){
                    $this->User_model->update_product_type_user($v,$userId);
                }
                //echo json_encode(array('result'=>'good'));die; 
                $this->User_model->edit(array('firstName'=>$firstName,'lastName'=>$lastName,'email'=>$email),$userId);
                $this->load->model('Country');
                $rs=$this->Country->city_details($cityId);
                $isAdded=$this->User_model->is_billing_address_added();
                if(empty($isAdded)){
                    $this->User_model->add_biiling_info(array('contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'userId'=>$userId,'address'=>$address,'stateId'=>$rs[0]->stateId));
                }else{
                    $this->User_model->edit_biiling_info(array('contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'address'=>$address,'stateId'=>$rs[0]->stateId),$userId);
                }
                echo json_encode(array('result'=>'good'));die; 
            }
            
        }
    }
    
    function recusive_category($newCateoryArr,$categoryId){
        $this->load->model('Category_model','category');
        $chieldCateArr=$this->category->get_subcategory_by_category_id($categoryId);
        if(empty($chieldCateArr)){
            return $newCateoryArr;
        }else{    
            foreach($chieldCateArr AS $k){
                $newCateoryArr[]=$k->categoryId;
                $newCateoryArr=$this->recusive_category($newCateoryArr, $k->categoryId);
            }
            return $newCateoryArr;
        }
    }
    
    function add_new_group(){
        $groupAdminId = $this->input->post('groupAdminId',TRUE);
        $groupTitle = $this->input->post('groupTitle',TRUE);
        $productType = $this->input->post('productType',TRUE);
        $groupUsersArr = $this->input->post('groupUsers',TRUE);        
        $groupUsers = $groupUsersArr?implode(",", $groupUsersArr):0;
        $colors = array('red','maroon','purple','green','blue');
        $rand_keys = array_rand($colors, 1);
        $groupColor = $colors[$rand_keys];
        $notify = array();
        
        if(!$groupUsersArr):
            echo json_encode(array('result'=>'bad','msg'=>'Please select the at least one Buyer club member!'));die;
        endif;
        
        $rs=$this->User_model->is_all_users_exists_for_group_by_admin_id($groupAdminId,$groupUsers);
        if($rs!=FALSE){
            echo json_encode(array('result'=>'bad','msg'=>'All users are already attached with "Buying Club"['.$rs->groupTitle.'],Instead of create another Buying Club please use exist one.'));die;
        }
        
        $groupId = $this->User_model->group_add(array('groupAdminId'=>$groupAdminId,'groupTitle'=>$groupTitle,'productType'=>$productType,'groupUsers'=>$groupUsers,'groupColor'=>$groupColor));
        $adminDataArr=  $this->User_model->get_details_by_id($groupAdminId);
        if($groupId):
            if($groupUsersArr):
                foreach($groupUsersArr as $guser):
                    $receiverDetails=$this->User_model->get_details_by_id($guser);
                    $notify['senderId'] = $groupAdminId;
                    $notify['receiverId'] = $guser;
                    $notify['receiverMobileNumber'] = $receiverDetails[0]->mobile;
                    $notify['senderMobileNumber'] = $adminDataArr[0]->mobile;
                    $notify['nType'] = "BUYING-CLUB-ADD";
                    $notify['nTitle'] = $groupTitle;
                    $notify['adminName'] = $adminDataArr[0]->firstName.' '.$adminDataArr[0]->lastName;
                    $notify['adminEmail'] = $adminDataArr[0]->email;
                    $notify['adminContactNo'] = $adminDataArr[0]->contactNo;
                    $this->send_notification($notify);
                endforeach;
            endif;
            if($this->User_model->is_finance_added()){
                echo json_encode(array('result'=>'good','gid'=>$groupId,'url'=>BASE_URL.'myaccount/','profile_common_message'=>  $this->commonProfileCompletionMessage));die; 
            }else{
                echo json_encode(array('result'=>'good','gid'=>$groupId,'url'=>BASE_URL.'my-finance-info/','profile_common_message'=>''));die; 
            }
        else:    
            echo json_encode(array('result'=>'bad','msg'=>'Some error happen. Please try again!'));die;
        endif;
    }
    
    function update_group(){
        $groupId = $this->input->post('groupId',TRUE);
        $groupTitle = $this->input->post('groupTitle',TRUE);
        //$productType = $this->input->post('productType',TRUE);
        $groupUsersArr = $this->input->post('groupUsers',TRUE);
        
        $colors = array('red','maroon','purple','green','blue');
        $rand_keys = array_rand($colors, 1);
        $groupColor = $colors[$rand_keys];
        
        if(!$groupUsersArr):
            echo json_encode(array('result'=>'bad','msg'=>'Please select the at least one Buying Club member!'));die;
        endif;
        
        $groupUsers = implode(",", $groupUsersArr);
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
        
        $groupIdUpdate = $this->User_model->group_update(array('groupTitle'=>$groupTitle,'groupUsers'=>$groupUsers,'groupColor'=>$groupColor), $groupId);
        if($groupIdUpdate):
            $adminDataArr=  $this->User_model->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
            foreach($olduser as $ouser):
                $receiverDetails=$this->User_model->get_details_by_id($ouser);
                $notify['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $notify['receiverId'] = $ouser;
                $notify['nType'] = "BUYING-CLUB-MODIFY";
                $notify['receiverMobileNumber'] = $receiverDetails[0]->mobile;
                $notify['senderMobileNumber'] = $adminDataArr[0]->mobile;
                $notify['nTitle'] = $groupTitle;
                $this->send_notification($notify);
            endforeach;
            foreach($newUser as $nuser):
                $receiverDetails=$this->User_model->get_details_by_id($nuser);
                $notify['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $notify['receiverId'] = $nuser;
                $notify['receiverMobileNumber'] = $receiverDetails[0]->mobile;
                $notify['senderMobileNumber'] = $adminDataArr[0]->mobile;
                $notify['nType'] = "BUYING-CLUB-MODIFY-NEW";
                $notify['nTitle'] = $groupTitle;
                $notify['adminName'] = $adminDataArr[0]->firstName.' '.$adminDataArr[0]->lastName;
                $notify['adminEmail'] = $adminDataArr[0]->email;
                $notify['adminContactNo'] = $adminDataArr[0]->contactNo;
                $this->send_notification($notify);
            endforeach;

            foreach($deluser as $duser):
                $receiverDetails=$this->User_model->get_details_by_id($duser);
                $notify['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                $notify['receiverId'] = $duser;
                $notify['nType'] = "BUYING-CLUB-MODIFY-DELETE";
                $notify['receiverMobileNumber'] = $receiverDetails[0]->mobile;
                $notify['senderMobileNumber'] = $adminDataArr[0]->mobile;
                $notify['nTitle'] = $groupTitle;
                $this->send_notification($notify);
            endforeach;
            $reorder = $this->input->post('reorder',TRUE);
            
            if($reorder):
                $this->load->model('Order_model');
                $this->load->model('Product_model');
                $orderId = $this->input->post('orderId',TRUE);
                $order = $this->Order_model->get_single_order_by_id($orderId);
                $pro = $this->Product_model->details($order->productId);
                $orderinfo['pdetail'] = $pro[0];
                $group = $this->User_model->get_group_by_id($groupId);
                $mail_template_data=array();
                foreach($group->users as $key => $usr):
                    $mail_template_data=array();
                    $data['senderId'] = $this->session->userdata('FE_SESSION_VAR');
                    $data['receiverId'] = $usr->userId;
                    $data['nType'] = 'BUYING-CLUB-ORDER';
                    $data['nTitle'] = 'Buying Club Re-order [TIDIIT-OD'.$order->orderId.'] running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';
                    $mail_template_data['TEMPLATE_GROUP_RE_ORDER_START_ORDER_ID']=$order->orderId;
                    $mail_template_data['TEMPLATE_GROUP_RE_ORDER_START_ADMIN_NAME']=$group->admin->firstName.' '.$group->admin->lastName;
                    $data['nMessage'] = "Hi, <br> You have requested to buy Buying Club order product.<br>";
                    $data['nMessage'] .= "Product is <a href=''>".$orderinfo['pdetail']->title."</a><br>";
                    $mail_template_data['TEMPLATE_GROUP_RE_ORDER_START_PRODUCT_TITLE']=$orderinfo['pdetail']->title;
                    $data['nMessage'] .= "Want to process the order ? <br>";
                    $data['nMessage'] .= "<a href='".BASE_URL."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".BASE_URL."shopping/group-re-order-accept-process/".base64_encode($orderId*226201)."/".base64_encode(100)."' class='btn btn-success btn-lg'>Accept</a><br>";
                    $mail_template_data['TEMPLATE_GROUP_RE_ORDER_START_ORDER_ID1']=$orderId;
                    $data['nMessage'] .= "Thanks <br> Tidiit Team.";
                    $data['orderId'] =$orderId;
                    $data['productId'] =$orderinfo['priceinfo']->productId;
                    $data['productPriceId'] =$orderinfo['priceinfo']->productPriceId;
                    

                    $data['isRead'] = 0;
                    $data['status'] = 1;
                    $data['createDate'] = date('Y-m-d H:i:s');

                    //Send Email message
                    $recv_email = $usr->email;
                    $sender_email = $group->admin->email;
                    /// firing mail
                    $mail_template_view_data=$this->load_default_resources();
                    $mail_template_view_data['group_order_re_start']=$mail_template_data;
                    $this->_global_tidiit_mail($recv_email, "Buying Club Order Re-Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_re_start');
                    
                    $this->User_model->notification_add($data);
                endforeach;
            endif;
            
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
    
    function get_single_group(){
        $this->load->model('Order_model');
        $groupId = $this->input->post('groupId',TRUE);
        $orderId = $this->input->post('orderId',TRUE);
        $group = $this->User_model->get_group_by_id($groupId);
        $data['groupId'] = $groupId;
        $this->Order_model->update($data, $orderId);
        ob_start();?>
        <div class="container-fluid">
            <div class="alert alert-success" role="alert">
                <i class="fa fa-group"></i>
                <span class="sr-only">Success:</span>
                Buying Club has been added successfully. Please process the order without reload page.
            </div>
            <div class="col-md-3 col-sm-3 grp_dashboard" style="margin:0;">
            <div class="<?= $group->groupColor ?>">
                <span><i class="fa  fa-group fa-5x"></i></span>
            </div>
            <div class="grp_title"><?= $group->groupTitle ?></div>
        </div>        
        <div class="col-md-6">
            <h5><strong>Buying Club Leader</strong></h5>
            <p class="text-left"><?= $group->admin->firstName ?> <?= $group->admin->lastName ?></p>
            <?php if ($group->users): ?>
                <h5><strong>Buying Club Members</strong></h5><?php foreach ($group->users as $ukey => $usr): ?>
                    <p class="text-left"><?= $usr->firstName ?> <?= $usr->lastName ?></p>
                <?php endforeach;
            endif;
            ?>
        </div>
        </div><?php
        $result['contents'] = ob_get_contents();
	ob_end_clean();
	echo json_encode( $result );
	die;
    }
    
    function get_my_groups(){
        $me = $this->input->post('userId',TRUE);
        $groups = $this->User_model->get_my_groups(true);
        ob_start();
        if($groups):?>
        <div class="alert alert-success" role="alert">
            <i class="fa fa-exclamation-circle"></i>
            <span class="sr-only">Success:</span>
            Please select a Buying club!
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Buying Club Leader</th>
                    <th>Buying Club Members</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody><?php
                foreach($groups as $key => $group):?>
                <tr>
                    <td><span class="badge" style="background-color:<?=$group->groupColor?>;"><span><i class="fa  fa-group fa-5x"></i></span></span> <?=$group->groupTitle?></td>
                    <td><?=$group->admin->firstName?> <?=$group->admin->lastName?> <?php if($me == $group->admin->userId):?>[ME]<?php endif;?></td>
                    <td><?php if($group->users):
                        foreach($group->users as $ukey => $usr):?>
                       <?=$ukey+1?>#  <?=$usr->firstName?> <?=$usr->lastName?><br />
                        <?php endforeach; endif;?>
                    </td>
                    <td><input type="radio" name="select-group" class="js-select-group" value="<?=$group->groupId?>" ></td>
                </tr><?php
                endforeach;?>
            </tbody>
        </table>
        <?php
        else:?>
        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
            You have no own Buying Club or not added any other Buyer. Please create Buying Club first!
        </div>
        <?php
        endif;
        $result['contents'] = ob_get_contents();
	ob_end_clean();
	echo json_encode( $result );
	die;
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
            case 'BUYING-CLUB-ADD':
                $data['nMessage'] = "Hi, <br /> You Have added in my newly created Buying Club <strong>[".$data['nTitle']."]</strong> by ".$data['adminName'].".<br />Group Leader email id is ".$data['adminEmail'].".<br />Group Leader contact number is ".$data['adminContactNo'].".";
                $data['isEmail'] = true;
                if($this->Siteconfig_model->get_value_by_name('SMS_SEND_ALLOW')=='yes'):
                    $data['isMobMessage'] = true;
                else:
                    $data['isMobMessage'] = true;
                endif;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
            case 'BUYING-CLUB-MODIFY':
                $data['nMessage'] = "Hi, <br> Buying Club <strong>[".$data['nTitle']."]</strong> has been modified.";
                $data['isEmail'] = true;
                if($this->Siteconfig_model->get_value_by_name('SMS_SEND_ALLOW')=='yes'):
                    $data['isMobMessage'] = true;
                else:
                    $data['isMobMessage'] = true;
                endif;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
            case 'BUYING-CLUB-MODIFY-NEW':
                $data['nMessage'] = "Hi, <br> You Have added in my Buying Club <strong>[".$data['nTitle']."]</strong>.<br />My name is ".$data['adminName'].".<br />My email id is ".$data['adminEmail'].".<br />My contact number is ".$data['adminContactNo'].".";
                $data['isEmail'] = true;
                if($this->Siteconfig_model->get_value_by_name('SMS_SEND_ALLOW')=='yes'):
                    $data['isMobMessage'] = true;
                else:
                    $data['isMobMessage'] = true;
                endif;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
            case 'BUYING-CLUB-MODIFY-DELETE':
                $data['nMessage'] = "Hi, <br> You are not part of this Buying Club <strong>[".$data['nTitle']."]</strong>";
                $data['isEmail'] = true;
                if($this->Siteconfig_model->get_value_by_name('SMS_SEND_ALLOW')=='yes'):
                    $data['isMobMessage'] = true;
                else:
                    $data['isMobMessage'] = true;
                endif;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
        }
        
        
        $data['isRead'] = 0;
        $data['status'] = 1;
        
        
        
        if($data['isMobMessage']):
            $smsData=$data;
            $smsData['nMessage']=  str_replace('<br />','', $data['nMessage']);
            $smsData['nMessage']=  str_replace('<br>','', $smsData['nMessage']);
            $smsData['nMessage']=  str_replace('<br/>','', $smsData['nMessage']);
            $smsData['nMessage']=  str_replace('<strong>','', $smsData['nMessage']);
            $smsData['nMessage']=  str_replace('</strong>','', $smsData['nMessage']);
            send_sms_notification($smsData);
            unset($data['isMobMessage']);
        endif;
        
        if($data['isEmail']):
            //Send Email message
            unset($data['isEmail']);
        endif;
        unset($data['adminName']);
        unset($data['adminEmail']);
        unset($data['adminContactNo']);
        unset($data['receiverMobileNumber']);
        unset($data['senderMobileNumber']);
        
        $this->User_model->notification_add($data);
    
    }
    
    function show_cart(){
        $data=array();
        $user = $this->_get_current_user_details();
        if($user):
            $allItemArr=$this->Order_model->get_all_cart_item($user->userId);
            $newAllItemArr=array();
            foreach($allItemArr AS $k){
                $orderInfo =  unserialize(base64_decode($k['orderInfo']));
                $k['productTitle'] = $orderInfo['pdetail']->title;
                $k['qty'] = $k['productQty']?$k['productQty']:$orderInfo['priceinfo']->qty;
                $k['pimage']=$orderInfo['pimage']->image;
                $newAllItemArr[]=$k;
            }
            //pre($newAllItemArr);die;
            $data['allItemArr']=$newAllItemArr;
            $data['user']= $user;
        else:
            $data['allItemArr']=[];
            $data['user']= "";
        endif;
        $ret=$this->load->view('cart',$data,true);
        echo $ret;die;
    }
    
    function get_subcategory_for_user_product_type(){
        $this->load->model('Category_model','category');
        $categoryId=$this->input->post('categoryId',TRUE);
        $dataArr=$this->category->get_subcategory_by_category_id($categoryId);
        if(empty($dataArr)){
            echo json_encode(array('content'=>''));die;
        }else{
            $html='';
            foreach($dataArr AS $k):
                $html.='<div class="col-md-12">';
                $html.='<input type="checkbox" name="productTypeId[]" value="'.$k->categoryId.'" class="required productTypeCategorySelection" style="height:auto;margin-right:5px;"><a class="showInerCategoryData" href="javascript://" data-catdivid="'.$k->categoryId.'" data-isRoot="no">'.$k->categoryName.'</a></div>';
                //$html.='<div class="col-md-12" style="height: 10px;">';
                //$html.='</div>';
            endforeach;
            echo json_encode(array('content'=>$html));die;
        }
    }
    
    function submit_shippiing_address(){
        $config = array(
            array('field'   => 'firstName','label'   => 'First Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'lastName','label'   => 'Last Name','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'phone','label'   => 'Phone','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'address','label'   => 'Address','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'countryId','label'   => 'Country','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'cityId','label'   => 'City','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'zipId','label'   => 'Zip','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'localityId','label'   => 'Locality','rules'   => 'trim|required|xss_clean'), 
           array('field'   => 'productTypeId[]','label'   => 'Product Type','rules'   => 'trim|required|xss_clean')
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
                $countryId=$this->input->post('countryId',TRUE);
                $cityId=$this->input->post('cityId',TRUE);
                $zipId=$this->input->post('zipId',TRUE);
                $localityId=$this->input->post('localityId',TRUE);
                $address=$this->input->post('address',TRUE);
                
                $productTypeId=$this->input->post('productTypeId',TRUE);//pre($_POST);die;
                $newCateoryArr=array();
                //pre($productTypeId);
                foreach ($productTypeId AS $k =>$v){
                    $newCateoryArr[]=$v;
                    $newCateoryArr=$this->recusive_category($newCateoryArr,$v);
                }
                //pre($newCateoryArr);die;
                $this->User_model->update_user_product_type_category(array('productTypeCateoryId'=>implode(',', $newCateoryArr)),$userId);
                $this->User_model->remove_user_from_product_type($userId);
                foreach($newCateoryArr As $k => $v){
                    $this->User_model->update_product_type_user($v,$userId);
                }
                
                $this->load->model('Country');
                $rs=$this->Country->city_details($cityId);
                
                $isAdded=$this->User_model->is_shipping_address_added();
                if(empty($isAdded)){
                    $this->User_model->add_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'userId'=>$userId,'address'=>$address,'stateId'=>$rs[0]->stateId));
                }else{
                    $this->User_model->edit_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'address'=>$address,'stateId'=>$rs[0]->stateId),$userId);
                }
                if($this->User_model->is_profile_data_updated()){
                    echo json_encode(array('result'=>'good','url'=>BASE_URL.'myaccount/','profile_common_message'=>''));die; 
                }else{
                    echo json_encode(array('result'=>'good','url'=>BASE_URL.'my-profile/','profile_common_message'=>  $this->commonProfileCompletionMessage));die; 
                }
            }
            
        }
    }
    
    function submit_my_checkout_shipping_address(){
        $config = array(
            array('field'   => 'firstName','label'   => 'First Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'lastName','label'   => 'Last Name','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'phone','label'   => 'Phone','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'address','label'   => 'Address','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'countryId','label'   => 'Country','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'cityId','label'   => 'City','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'zipId','label'   => 'Zip','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'localityId','label'   => 'Locality','rules'   => 'trim|required|xss_clean')
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
                $countryId=$this->input->post('countryId',TRUE);
                $cityId=$this->input->post('cityId',TRUE);
                $zipId=$this->input->post('zipId',TRUE);
                $localityId=$this->input->post('localityId',TRUE);
                $address = $this->input->post('address',TRUE);
                $landmark = $this->input->post('landmark',TRUE);
                
                $this->load->model('Country');
                $rs=$this->Country->city_details($cityId);
                
                $isAdded=$this->User_model->is_shipping_address_added();
                if(empty($isAdded)){
                    $this->User_model->add_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'userId'=>$userId,'address'=>$address,'stateId'=>$rs[0]->stateId, 'landmark' => $landmark));
                }else{
                    $this->User_model->edit_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'address'=>$address,'stateId'=>$rs[0]->stateId, 'landmark' => $landmark),$userId);
                }
                $html = '';
                $html .= '<p>'.$firstName.' '.$lastName.'<br>';
                $html .= nl2br($address).'<br>';
                $html .= $phone.'<br>';
                if( $landmark ){
                    $html .= '<b>Landmark:</b>'.$landmark.'<br>';
                }
                echo json_encode(array('result'=>'good', 'html' => $html));die; 
            }
            
        }
    }
    
    function submit_finance(){
        $config = array(
            array('field'   => 'mPesaMobileNumber','label'   => 'm-Pesa Account Mobile Number','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'mPIN','label'   => 'm-Pesa Account Number mPIN','rules'   => 'trim|required|xss_clean') 
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
                $mPesaMobileNumber=$this->input->post('mPesaMobileNumber',TRUE);
                $mPIN=$this->input->post('mPIN',TRUE);
                
                $isAdded=$this->User_model->get_finance_info();
                if(empty($isAdded)){
                    $this->User_model->add_finance(array('mPesaMobileNumber'=>$mPesaMobileNumber,'mPIN'=>$mPIN,'userId'=>$userId));
                }else{
                    $this->User_model->edit_finance(array('mPesaMobileNumber'=>$mPesaMobileNumber,'mPIN'=>$mPIN),$userId);
                }
                echo json_encode(array('result'=>'good'));die; 
            }
            
        }
    }
    
    function submit_my_profile(){
        $config = array(
            array('field'   => 'firstName','label'   => 'First Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'lastName','label'   => 'Last Name','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'email','label'   => 'Email','rules'   => 'trim|required|xss_clean|valid_email'),
            array('field'   => 'contactNo','label'   => 'Phone','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'DOB','label'   => 'Date of Birth','rules'   => 'trim|required|xss_clean'),
           array('field'   => 'aboutMe','label'   => 'City','rules'   => 'trim|required|xss_clean')
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
                $contactNo=$this->input->post('contactNo',TRUE);
                $email=$this->input->post('email',TRUE);
                $rowDOB=$this->input->post('DOB',TRUE);
                $dobArr=  explode('-', $rowDOB);
                $DOB=$dobArr[2].'-'.$dobArr[1].'-'.$dobArr[0];
                $mobile=$this->input->post('mobile',TRUE);
                $fax=$this->input->post('fax',TRUE);
                $aboutMe=$this->input->post('aboutMe',TRUE);
                
                $myProfileDataArr=array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$contactNo,
                    'email'=>$email,'DOB'=>$DOB,'mobile'=>$mobile,'fax'=>$fax,'aboutMe'=>$aboutMe);
                $this->User_model->edit($myProfileDataArr,$userId);
                $DataArr=  $this->User_model->get_details_by_id($userId);
                $this->session->set_userdata('FE_SESSION_UDATA',$DataArr[0]);
                if($this->User_model->is_user_had_group($userId)==FALSE):
                    echo json_encode(array('result'=>'good','url'=>BASE_URL.'my-groups/','redirect'=>1,'profile_common_message'=>  $this->commonProfileCompletionMessage));die; 
                else:
                    echo json_encode(array('result'=>'good','redirect'=>0,'profile_common_message'=>''));die; 
                endif;
            }
        }
    }
    
    
    public function retribe_forgot_password(){
        if(strtoupper(trim($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')))!='IN'){
            echo json_encode(array('result'=>'bad','msg'=>'Please check your user anme and password and try again.','profile_common_message'=>""));die;     
        }
        $config = array(
            array('field'   => 'userForgotPasswordEmail','label'   => 'Forggot password email','rules'   => 'trim|required|xss_clean|valid_email')
         );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
            //retun to login page with peroper error
            echo json_encode(array('result'=>'bad','msg'=>str_replace('</p>','',str_replace('<p>','',validation_errors()))));die;
        }else{
            $email=$this->input->post('userForgotPasswordEmail',TRUE);
            $DataArr=$this->User_model->get_data_by_email($email);
            //print_r($DataArr);die;
            if(count($DataArr)>0){
                $mail_template_data=array();
                $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_EAMIL']=$DataArr[0]->email;
                $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_FIRSTNAME']=$DataArr[0]->firstName;
                $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_LASTNAME']=$DataArr[0]->lastName;
                $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_USERNAME']=$DataArr[0]->userName;
                $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_PASSWORD']=$DataArr[0]->password;
                
                $mail_template_view_data=$this->load_default_resources();
                $mail_template_view_data['retribe_user_password']=$mail_template_data;
                $this->_global_tidiit_mail($DataArr[0]->email, "Your password at Tidiit Inc. Ltd.", $mail_template_view_data,'retribe_user_password',$DataArr[0]->firstName.' '.$DataArr[0]->lastName);
                echo json_encode(array('result'=>'good','msg'=>'Your password has been sent to your register email address.'));die; 
            }else{
                echo json_encode(array('result'=>'bad','msg'=>'Please check your "email" and try again.'));die;     
            }
        }
    }
    
    function show_locality_all_users_with_product_type(){
        $localityId=$this->input->post('localityId',TRUE);
        $productType=$this->input->post('productType',TRUE);
        if($localityId=="" || $productType==""){
            echo '';die;
        }else{
            $this->load->model('User_model');
            $UsersDataArr=$this->User_model->get_all_users_by_product_type_locality($productType,$localityId);
            //pre($UsersDataArr);
            if(empty($UsersDataArr)){
                echo '';die;
            }else{
                //echo '';die;
                $html ='<label class="col-sm-3 control-label">Select Buying Club Users :</label>';
                $html .='<div class="boxes">';
                foreach($UsersDataArr as $user):
                $html.='<div class="checkbox-'.$user->userId.'"><div class="checkbox">
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
    
    function valid_user_order_out_for_delivery(){
        $orderId=trim($this->input->post('orderId',TRUE));
        $this->load->model('Order_model');
        $orderDetails=$this->Order_model->details($orderId);

        if(empty($orderDetails)){
            $this->form_validation->set_message('valid_user_order_for_delivery', 'Invalid Order Id.Please check the order id.');
            return FALSE;
        }else if($orderDetails[0]->status<4){
            $this->form_validation->set_message('valid_user_order_for_delivery', 'Order Id '.$orderId.' is yet not shipped.Please check the order id.');
            return FALSE;
        }else if($orderDetails[0]->status>5){
            $this->form_validation->set_message('valid_user_order_for_delivery', 'Order Id '.$orderId.' is delivered.Please check the order id.');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    function submit_out_for_delivery(){
        $config = array(
            array('field'   => 'outForDeliveryType','label'   => 'Select out for delivery type','rules'=> 'trim|required|xss_clean'),
            array('field'   => 'orderId','label'   => 'Enter the OrderID','rules'   => 'trim|required|xss_clean|callback_valid_user_order_out_for_delivery'),
            array('field'   => 'logisticsId','label'   => 'Logistics Tidiit Sign ID','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'deliveryStaffName','label'   => 'Delivery Staff Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'deliveryStaffContactNo','label'   => 'Delivery Staff Contact No','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'deliveryStaffEmail','label'   => 'Delivery Staff Email','rules'   => 'trim|required|xss_clean|valid_email')
         );
        
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE):
            //retun to login page with peroper error
            echo json_encode(array('result'=>'bad','msg'=>str_replace('</p>','',str_replace('<p>','',validation_errors()))));die;
        else:
            $outForDeliveryType=$this->input->post('outForDeliveryType',TRUE);
            $orderId=trim($this->input->post('orderId',TRUE));
            $logisticsId=trim($this->input->post('logisticsId',TRUE));
            $deliveryStaffName=$this->input->post('deliveryStaffName',TRUE);
            $deliveryStaffContactNo=$this->input->post('deliveryStaffContactNo',TRUE);
            $deliveryStaffEmail=$this->input->post('deliveryStaffEmail',TRUE);
            $outForDeliveryDays="";
            $this->load->model('Logistics_model');
            $logisticDetails=$this->Logistics_model->details($logisticsId);
            $this->load->model('Order_model');
            $order=$this->Order_model->get_single_order_by_id($orderId);
            
            if(empty($logisticDetails)):
                echo json_encode(array('result'=>'bad','msg'=>'Invalid logistics data entered.'));die;
            endif;
            if(empty($order)):
                echo json_encode(array('result'=>'bad','msg'=>'Invalid order data entered.'));die;
            endif;
            $outForDeliveryDataArr=array('order'=>$order,'logisticDetails'=>$logisticDetails,'deliveryStaffName'=>$deliveryStaffName,
                'deliveryStaffContactNo'=>$deliveryStaffContactNo,'deliveryStaffEmail'=>$deliveryStaffEmail);
            $dataArr=array('orderId'=>$orderId,'logisticsId'=>$logisticsId,'outForDeliveryType'=>$outForDeliveryType,'deliveryStaffName'=>$deliveryStaffName,
                'deliveryStaffContactNo'=>$deliveryStaffContactNo,'IP'=>$this->input->ip_address(),'deliveryStaffEmail'=>$deliveryStaffEmail);
            //pre($dataArr);die;
            if($outForDeliveryType=='preAlert'):
                $outForDeliveryDays=$this->input->post('outForDeliveryDays',TRUE);
                if($outForDeliveryDays==""):
                    echo json_encode(array('result'=>'bad','msg'=>'Please select out for delivery days.'));die;
                endif;    
                $outForDeliveryDataArr['outForDeliveryDays']=$outForDeliveryDays;
                $dataArr['outForDeliveryDays']=$outForDeliveryDays;
                $this->Order_model->add_order_out_for_delivery($dataArr); 
                if($order->orderType=='GROUP'):
                    $this->_send_pre_alert_regarding_out_for_delivery_of_group($outForDeliveryDataArr);
                else:
                    $this->_send_pre_alert_regarding_out_for_delivery($outForDeliveryDataArr);
                endif;
            else:
                $this->Order_model->add_order_out_for_delivery($dataArr);
                if($order->orderType=='GROUP'):
                    $this->_send_alert_regarding_out_for_delivery_of_group($outForDeliveryDataArr);
                else:
                    $this->_send_alert_regarding_out_for_delivery($outForDeliveryDataArr);
                endif;
            endif;
            $this->Order_model->update(array('status'=>5),$orderId);
            //$order=$this->Order_model->get_single_order_by_id($orderId);
            echo json_encode(array('result'=>'good','msg'=>'Out of delivery intimated to Buyer successfully.','paymentType'=>$order->paymentType));die;
        endif;
    }
    
    /****
     *  sending pre-alert of out for delivery for single order 
     */
    function _send_pre_alert_regarding_out_for_delivery($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=$this->load_default_resources();
        $mail_template_view_data['orderInfo']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        $this->_global_tidiit_mail($order->buyerEmail, "Pre-alert to your Tidiit order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'single_order_out_for_delivery_pre_alert',$buyerFullName);
        
        
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderDetails']=$orderDetails;
        /// for seller
        $mail_template_view_data['userFullName']=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        $this->_global_tidiit_mail($order->sellerEmail, "Pre-alert for order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'seller_single_order_out_for_delivery_pre_alert',$order->sellerFirstName.' '.$order->sellerFirstName);
        
        
        $mail_template_view_data['userFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Pre-alert for Order no - TIDIIT-OD-".$order->orderId.' before delivery ', $mail_template_view_data,'support_single_order_out_for_delivery_pre_alert','Tidiit Inc Support');
        
        /// sendin SMS to allmember
        $smsMsg='Tidiit order TIDIIT-OD-'.$order->orderId.' will delivered by '.$outForDeliveryDataArr['outForDeliveryDays'].' days.';
        if($order->isPaid==0):
            $smsMsg.="AS you had selected Settlement on Delivery method,please submit the payment,So delivery people will delivery your item.";
        endif;
        $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$order->buyerMobileNo,'senderId'=>'','receiverId'=>$order->userId,
        'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-OUT-FOR_DELIVERY-PRE-ALERT');
        send_sms_notification($sms_data);
        return TRUE;
    }
    
    /****
     *  sending pre-alert of out for delivery for BUYING-CLUB order 
     */
    function _send_pre_alert_regarding_out_for_delivery_of_group($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $orderInfo=unserialize(base64_decode($order->orderInfo));
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=$this->load_default_resources();
        $mail_template_view_data['orderInfo']=$orderInfo;
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $mail_template_view_data['orderDetails']=$orderDetails;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        $this->_global_tidiit_mail($order->buyerEmail, "Pre-alert to your Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'group_order_out_for_delivery_pre_alert',$buyerFullName);
        
        /// mail for group leader
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        if($order->parrentOrderID>0):
            $mail_template_view_data['leaderFullName']=$orderInfo['group']->admin->firstName.' '.$orderInfo['group']->admin->lastName;
            $this->_global_tidiit_mail($orderInfo['group']->admin->email, "Pre-alert to your Tidiit Buying Club Member order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'group_order_out_for_delivery_pre_alert_leader',$buyerFullName);    
        endif;
        
        /// for seller
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $sellerFullName=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['sellerFullName']=$sellerFullName;
        $this->_global_tidiit_mail($order->sellerEmail, "Pre-alert for Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' before delivery', $mail_template_view_data,'seller_group_order_out_for_delivery_pre_alert',$sellerFullName);
        
        $mail_template_view_data['supportFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Pre-alert Tidiit Buying Club for Order no - TIDIIT-OD-".$order->orderId.' before delivery ', $mail_template_view_data,'support_group_order_out_for_delivery_pre_alert','Tidiit Inc Support');
        
        /// sendin SMS to Buyer
        $smsMsg='Your Tidiit order TIDIIT-OD-'.$order->orderId.' will delivered by '.$outForDeliveryDataArr['outForDeliveryDays'].' days.';
        if($order->isPaid==0):
            $smsMsg.="AS you had selected Settlement on Delivery method,please submit the payment,So delivery people will delivery your item.";
        endif;
        $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$order->buyerMobileNo,'senderId'=>'','receiverId'=>$order->userId,
        'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-OUT-FOR_DELIVERY-PRE-ALERT');
        send_sms_notification($sms_data);
        
        if($order->userId!=$orderInfo["group"]->admin->userId):
            /// sendin SMS to Leader
            $smsMsg='Your Buying Club['.$orderInfo['group']->groupTitle.']  member Tidiit order TIDIIT-OD-'.$order->orderId.' will delivered by '.$outForDeliveryDataArr['outForDeliveryDays'].' days.';
            if($order->isPaid==0):
                $smsMsg.="$buyerFullName had selected Settlement on Delivery method,please follow with him/her to submit the payment,So delivery people will delivery your item.";
            endif;
            $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$orderInfo['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfo["group"]->admin->userId,
            'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-OUT-FOR_DELIVERY-PRE-ALERT');
            send_sms_notification($sms_data);
        endif;
        return TRUE;
    }
    
    /****
     *  sending pre-alert of out for delivery for single order 
     */
    function _send_alert_regarding_out_for_delivery($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=$this->load_default_resources();
        $mail_template_view_data['orderInfo']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        $this->_global_tidiit_mail($order->buyerEmail, "Your Tidiit order no - TIDIIT-OD-".$order->orderId.' is ready now to Out For Delivery', $mail_template_view_data,'single_order_out_for_delivery',$buyerFullName);
        
        /// for seller
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $mail_template_view_data['orderDetails']=$orderDetails;
        $mail_template_view_data['userFullName']=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        $this->_global_tidiit_mail($order->sellerEmail, "Tidiit order no - TIDIIT-OD-".$order->orderId.' is ready to Out For Delivery', $mail_template_view_data,'seller_single_order_out_for_delivery',$order->sellerFirstName.' '.$order->sellerFirstName);
        
        
        $mail_template_view_data['userFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Tidiit Order no - TIDIIT-OD-".$order->orderId.' is ready to Out For Delivery ', $mail_template_view_data,'support_single_order_out_for_delivery','Tidiit Inc Support');
        
        $smsMsg='Tidiit order TIDIIT-OD-'.$order->orderId.' is ready to Out For Delivery.';
        if($order->isPaid==0):
            $smsMsg.="AS you had selected Settlement on Delivery method,please submit the payment,So delivery people will deliver your item at your door step.";
        endif;
        $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$order->buyerMobileNo,'senderId'=>'','receiverId'=>$order->userId,
        'senderMobileNumber'=>'','nType'=>'SINGLE-ORDER-OUT-FOR_DELIVERY');
        send_sms_notification($sms_data);
        
        return true;
    }
    
    function _send_alert_regarding_out_for_delivery_of_group($outForDeliveryDataArr){
        $order=$outForDeliveryDataArr['order'];
        $orderDetails=array();
        $orderDetails[]=$order;
        $orderInfo=unserialize(base64_decode($order->orderInfo));
        $logisticDetails=$outForDeliveryDataArr['logisticDetails'];
        $mail_template_view_data=$this->load_default_resources();
        $mail_template_view_data['orderInfo']=$orderInfo;
        $mail_template_view_data['orderId']=$order->orderId;
        $mail_template_view_data['deliveryCompanyName']=$logisticDetails[0]['title'];
        $mail_template_view_data['deliveryStaffName']=$outForDeliveryDataArr['deliveryStaffName'];
        $mail_template_view_data['deliveryStaffContactNo']=$outForDeliveryDataArr['deliveryStaffContactNo'];
        $mail_template_view_data['deliveryStaffEmail']=$outForDeliveryDataArr['deliveryStaffEmail'];
        $mail_template_view_data['isPaid']=$order->isPaid;
        $mail_template_view_data['orderDetails']=$orderDetails;
        $buyerFullName=$order->buyerFirstName.' '.$order->buyerLastName;
        $this->_global_tidiit_mail($order->buyerEmail, "Your Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' is ready for Out For Delivery', $mail_template_view_data,'group_order_out_for_delivery',$buyerFullName);
        
        /// mail for group leader
        $mail_template_view_data['buyerFullName']=$buyerFullName;
        if($order->parrentOrderID>0):
            $mail_template_view_data['leaderFullName']=$orderInfo['group']->admin->firstName.' '.$orderInfo['group']->admin->lastName;
            $this->_global_tidiit_mail($orderInfo['group']->admin->email, "Your Tidiit Buying Club Member order no - TIDIIT-OD-".$order->orderId.' is ready for Out For Delivery', $mail_template_view_data,'group_order_out_for_delivery_leader',$buyerFullName);    
        endif;
        
        /// for seller
        $mail_template_view_data['orderInfoDataArr']=unserialize(base64_decode($order->orderInfo));
        $sellerFullName=$order->sellerFirstName.' '.$order->sellerFirstName;
        $mail_template_view_data['sellerFullName']=$sellerFullName;
        $this->_global_tidiit_mail($order->sellerEmail, "Tidiit Buying Club order no - TIDIIT-OD-".$order->orderId.' is ready for Out For Delivery', $mail_template_view_data,'seller_group_order_out_for_delivery',$sellerFullName);
        
        $mail_template_view_data['supportFullName']='Tidiit Inc Support';
        $mail_template_view_data['sellerFullName']=$order->sellerFirstName.' '.$order->sellerLastName;
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Tidiit Buying Club for Order no - TIDIIT-OD-".$order->orderId.' is ready for Out For Delivery ', $mail_template_view_data,'support_group_order_out_for_delivery','Tidiit Inc Support');
        
        /// sendin SMS to Buyer
        $smsMsg='Your Tidiit order TIDIIT-OD-'.$order->orderId.' is ready to Out For Delivery.';
        if($order->isPaid==0):
            $smsMsg.="AS you had selected Settlement on Delivery method,please submit the payment,So delivery people will delivery your item.";
        endif;
        $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$order->buyerMobileNo,'senderId'=>'','receiverId'=>$order->userId,
        'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-OUT-FOR_DELIVERY');
        send_sms_notification($sms_data);
        
        if($order->userId!=$orderInfo["group"]->admin->userId):
            /// sendin SMS to Leader
            $smsMsg='Your Buying Club['.$orderInfo['group']->groupTitle.']  member Tidiit order TIDIIT-OD-'.$order->orderId.' is ready to Out For Delivery.';
            if($order->isPaid==0):
                $smsMsg.="$buyerFullName had selected Settlement on Delivery method,please follow with him/her to submit the payment,So delivery people will delivery your item.";
            endif;
            $sms_data=array('nMessage'=>$smsMsg,'receiverMobileNumber'=>$orderInfo['group']->admin->mobile,'senderId'=>'','receiverId'=>$orderInfo["group"]->admin->userId,
            'senderMobileNumber'=>'','nType'=>'BUYING-CLUB-ORDER-OUT-FOR_DELIVERY');
            send_sms_notification($sms_data);
        endif;        
        return TRUE;
    }
    
    function check_order_id_for_logistic(){
        $orderId=trim($this->input->post('orderId',TRUE));
        $this->load->model('Order_model');
        $orderDetails=$this->Order_model->details($orderId);
        if(empty($orderDetails)){
            echo json_encode(array('result'=>'bad','msg'=>'Invalid order id provide,please try again.')) ;die;
        }else if($orderDetails[0]->status<4 || $orderDetails[0]->status>5){
            echo json_encode(array('result'=>'bad','msg'=>'Enter order is yet not shipped or already delivered,please try again.'));die;
        }else{
            echo json_encode(array('result'=>'goods','msg'=>''));die;
        }
    }
    
    function check_order_id_for_logistic_delivery(){
        $orderId=trim($this->input->post('orderId',TRUE));
        $this->load->model('Order_model');
        $orderDetails=$this->Order_model->details($orderId);
        if(empty($orderDetails)){
            echo json_encode(array('result'=>'bad','msg'=>$orderId.' is not valid order id,please try again.')) ;die;
        }else if($orderDetails[0]->status<5){
            echo json_encode(array('result'=>'bad','msg'=>'Order id '.$orderId.' is yet not out for delivery,please try again.'));die;
        }else if($orderDetails[0]->status>5){
            echo json_encode(array('result'=>'bad','msg'=>'Order id '.$orderId.' is already delivered,please try again.'));die;            
        }else if($orderDetails[0]->paymentType=='settlementOnDelivery'){
            echo json_encode(array('result'=>'bad','msg'=>'Payment for Order id '.$orderId.' is yet not received,it can not update as DELIVERED.'));die;            
        }else{
            $deliveryRequestedArr=$this->Order_model->get_latest_delivery_details($orderId);
            if(!empty($deliveryRequestedArr)){
                echo json_encode(array('result'=>'good','msg'=>'Order id '.$orderId.' is already delivered,Are sure to update the data ?.'));die;            
            }else{
                echo json_encode(array('result'=>'goods','msg'=>''));die;
            }
        }
    }
    
    function check_logistics_id_for_logistic(){
        $logisticsId=trim($this->input->post('logisticsId',TRUE));
        $this->load->model('Logistics_model');
        $details=$this->Logistics_model->details($logisticsId);
        if(empty($details)){
            echo json_encode(array('result'=>'bad','msg'=>'Invalid logistics sign id provide,please try again.')) ;die;
        }else{
            echo json_encode(array('result'=>'goods','msg'=>''));die;
        }
    }
    
    function sod_payment_final_input_view(){
        $orderId=trim($this->input->post('paymentid',TRUE));
        if($orderId!=""):
            $data=array();
            $data['orderId']=  (base64_decode($orderId))/226201;
            $data['payAmount']=trim($this->input->post('payAmount',TRUE));
            $data['paymentGatewayData']=$this->Order_model->get_all_gateway();
            echo json_encode(array('result'=>'good','content'=>$this->load->view('sod_payment_final_input_view',$data,TRUE)));
        else :
            echo json_encode(array('result'=>'bad'));
        endif;
    }
    
    function valid_user_order_for_delivery(){
        $orderId=trim($this->input->post('orderId',TRUE));
        $this->load->model('Order_model');
        $orderDetails=$this->Order_model->details($orderId);

        if(empty($orderDetails)){
            $this->form_validation->set_message('valid_user_order_for_delivery', 'Invalid Order Id.Please check the order id.');
            return FALSE;
        }else if($orderDetails[0]->status<5){
            $this->form_validation->set_message('valid_user_order_for_delivery', 'Order Id '.$orderId.' is yet not out delivery.Please check the order id.');
            return FALSE;
        }else if($orderDetails[0]->status>5){
            $this->form_validation->set_message('valid_user_order_for_delivery', 'Order Id '.$orderId.' is delivered.Please check the order id.');
            return FALSE;
        }else if($orderDetails[0]->paymentType=='settlementOnDelivery'){
            $this->form_validation->set_message('valid_user_order_for_delivery', 'Payment for Order id '.$orderId.' is yet not received,it can not update as DELIVERED.');
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    function submit_delivery(){
        $config = array(
            array('field'   => 'orderId','label'   => 'Enter the OrderID','rules'   => 'trim|required|xss_clean|callback_valid_user_order_for_delivery'),
            array('field'   => 'logisticsId','label'   => 'Logistics Tidiit Sign ID','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'deliveryStaffName','label'   => 'Delivery Staff Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'deliveryStaffContactNo','label'   => 'Delivery Staff Contact No','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'deliveryStaffEmail','label'   => 'Delivery Staff Email','rules'   => 'trim|required|xss_clean|valid_email'),
            array('field'   => 'receiveStaffName','label'   => 'Receive Staff Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'receiveStaffContactNo','label'   => 'Receive Staff Contact No','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'receiveDateTime','label'   => 'Receive Date Time','rules'   => 'trim|required|xss_clean'),
         );
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE):
            //retun to login page with peroper error
            echo json_encode(array('result'=>'bad','msg'=>str_replace('</p>','',str_replace('<p>','',validation_errors()))));die;
        else:
            $orderId=trim($this->input->post('orderId',TRUE));
            $logisticsId=trim($this->input->post('logisticsId',TRUE));
            $deliveryStaffName=$this->input->post('deliveryStaffName',TRUE);
            $deliveryStaffContactNo=$this->input->post('deliveryStaffContactNo',TRUE);
            $deliveryStaffEmail=$this->input->post('deliveryStaffEmail',TRUE);
            $receiveStaffName=$this->input->post('receiveStaffName',TRUE);
            $receiveStaffContactNo=$this->input->post('receiveStaffContactNo',TRUE);
            $oldReceiveDateTime=$this->input->post('receiveDateTime',TRUE);
            $receiveDateTimeArr=  explode(' ', $oldReceiveDateTime);
            $receiveDateArr=  explode('-', $receiveDateTimeArr[0]);
            $receiveDateTime=$receiveDateArr[2].'-'.$receiveDateArr[1].'-'.$receiveDateArr[0].' '.$receiveDateTimeArr[1].':00';
            
            $this->load->model('Logistics_model');
            $logisticDetails=$this->Logistics_model->details($logisticsId);
            
            $this->load->model('Order_model');
            $oldDeliveryRequestDetails=$this->Order_model->get_latest_delivery_details($orderId);
            
            if(empty($logisticDetails)):
                echo json_encode(array('result'=>'bad','msg'=>'Invalid logistics data entered.'));die;
            endif;
            $dataArr=array('orderId'=>$orderId,'logisticsId'=>$logisticsId,'deliveryStaffName'=>$deliveryStaffName,
                'deliveryStaffContactNo'=>$deliveryStaffContactNo,'IP'=>$this->input->ip_address(),'deliveryStaffEmail'=>$deliveryStaffEmail,
                'receiveStaffName'=>$receiveStaffName,'receiveStaffContactNo'=>$receiveStaffContactNo,'receiveDateTime'=>$receiveDateTime);
            $config['upload_path'] =$this->config->item('ResourcesPath').'order_delivery/original/';
            $config['allowed_types'] = 'jpg|png|jpeg';
            $config['file_name']	= strtolower(my_seo_freindly_url($orderId)).'-'.rand(1,9).'-'.time();
            $config['max_size']	= '2047';
            $config['max_width'] = '1550';
            $config['max_height'] = '1550';
            $upload_files=array();
            $this->load->library('upload');
            //pre($_FILES);die;
            foreach ($_FILES as $fieldname => $fileObject){  //fieldname is the form field name
                //pre($fieldname);
                //pre($fileObject);die;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload($fieldname)):
                    foreach($upload_files AS $k){
                        @unlink($this->config->item('ResourcesPath').'order_delivery/original/'.$k);
                    }
                    $errors = $this->upload->display_errors();
                    //pre($errors);die;
                    echo json_encode(array('result'=>'bad','msg'=>$errors));die;
                else:
                    $data=$this->upload->data();
                    $this->order_delivery_image_resize($data['file_name']);
                    $upload_files[]=$data['file_name'];
                endif;
            }
            if(empty($upload_files) || count($upload_files)<2){
                echo json_encode(array('result'=>'bad','msg'=>'You must upload 2 photo for tidiit order delivery proof.'));die;
            }
            $dataArr['photo1']=$upload_files[0];
            $dataArr['photo2']=$upload_files[1];
            //pre($dataArr);die;
            if(empty($oldDeliveryRequestDetails)):
                $orderDeliveredRequestId=$this->Order_model->add_order_delivered_request($dataArr);
            else:
                //pre($oldDeliveryRequestDetails);die;
                if($oldDeliveryRequestDetails[0]['photo1']!=""):
                    if($data['photo1']!=""):
                        $this->delete_delivery_image($oldDeliveryRequestDetails[0]['photo1']);
                    endif;
                endif;
                if($oldDeliveryRequestDetails[0]['photo2']!=""):
                    if($data['photo2']!=""):
                        $this->delete_delivery_image($oldDeliveryRequestDetails[0]['photo2']);
                    endif;
                endif;
                $this->Order_model->update_order_delivered_request($dataArr,$orderId);
                $orderDeliveredRequestId=$oldDeliveryRequestDetails[0]['orderDeliveredRequestId'];
            endif;
            if($orderDeliveredRequestId):
                echo json_encode(array('result'=>'good','msg'=>'Delivery information updated successfully.'));die;
            else:    
                echo json_encode(array('result'=>'good','msg'=>'Unknown error arises, please try again'));die;
            endif;    
        endif;
    }
    
     public function order_delivery_image_resize($fileName){
        $PHOTOPATH=$this->config->item('ResourcesPath').'order_delivery/';
        $OriginalPath=$PHOTOPATH.'original/';
        //echo '$OriginalPath :- '.$OriginalPath.'<br>';
        $OriginalFilePath=$OriginalPath.$fileName;
        //echo '$OriginalFilePath :- '.$OriginalFilePath.'<br>';

        /// ************100X100***************
        $config['image_library'] = 'gd2';
        $config['source_image'] = $OriginalFilePath;
        $config['new_image'] = $PHOTOPATH. '75X75/';
        $config['width'] = 75;
        $config['height'] = 75;
        $config['maintain_ratio'] = true;
        $config['master_dim'] = 'auto';
        $config['create_thumb'] = FALSE;
        $this->image_lib->initialize($config);
        $this->load->library('image_lib', $config);
        if($this->image_lib->resize()){
                //echo '<br>thumb done for 100X100.';
        }else{
                $this->image_lib->display_errors();
        }

        $this->image_lib->clear();
     }
     
     public function delete_delivery_image($fileName){
         $PHOTOPATH=$this->config->item('ResourcesPath').'order_delivery/';
         @unlink($PHOTOPATH.'original/'.$fileName);
         @unlink($PHOTOPATH.'75X75/'.$fileName);
         return TRUE;
     }
             
     function show_order_details(){
        $this->load->model('Order_model');
        $orderId=$this->input->post('orderId',TRUE);
        $data=  $this->load_default_resources();
        $order=$this->Order_model->get_single_order_by_id($orderId);
        $data['order']=$order;
        $data['orderId']=$orderId;
        $data['group'] = $this->User_model->get_group_by_id($order->groupId);
        $orderStatusobj=$this->Order_model->get_state();
        $stateArr=array();
        foreach($orderStatusobj As $k){
            $stateArr[$k->orderStateId]=$k->name;
        }
        $data['status']= $stateArr;
        //pre($data);die;
        echo json_encode(array('content'=>$this->load->view('order_details',$data,true)));die;
     }
     
     function check_group_title_exist(){
         $groupTitle=$this->input->post('groupTitle',TRUE);
         if($this->User_model->group_title_exists($groupTitle)){
             echo 'yes';die;
         }else{
             echo 'no';die;
         }
     }
     
     function show_help_topics_by_id(){
         $this->load->model('Help_model');
         $helpTopicsId=$this->input->post('topicsid',TRUE);
         $get_help_topics_data=$this->Help_model->get_topic_details_by_id($helpTopicsId);
         $help_topics_details=$this->Help_model->get_topics_details($helpTopicsId);
         $data['helpDataArr']=$get_help_topics_data;
         $data['help_topics_details']=$help_topics_details;
         $retData=$this->load->view('help_topics_details_ajax',$data,TRUE);
         $questions = [];
         foreach($get_help_topics_data as $hkey => $hdata):
             $questions[] = $hdata->question;
         endforeach;
         echo json_encode(array('content'=>$retData, 'qts' => $questions));die;
     }

    function ajax_search_autocomplete(){
        $term = $_GET['term'];

        $a_json_invalid = array(array("id" => "#", "value" => $term, "label" => "Only letters and digits are permitted..."));
        $json_invalid = json_encode($a_json_invalid);

        // replace multiple spaces with one
        $term = preg_replace('/\s+/', ' ', $term);

        // SECURITY HOLE ***************************************************************
        // allow space, any unicode letter and digit, underscore and dash
        if(preg_match("/[^\040\pL\pN_-]/u", $term)) {
            print $json_invalid;
            exit;
        }

        $this->load->model('Category_model','category');

        $a_json = $this->category->get_auto_serch_populet_by_text($term);

        $parts = explode(' ', $term);

        $a_json = $this->apply_highlight($a_json, $parts);

        $json = json_encode($a_json);
        print $json;die;
    }

    /**
     * mb_stripos all occurences
     * Find all occurrences of a needle in a haystack
     *
     * @param string $haystack
     * @param string $needle
     * @return array or false
     */
    function mb_stripos_all($haystack, $needle) {

        $s = 0;
        $i = 0;
        $aStrPos = [];
        while(is_integer($i)) {

            $i = mb_stripos($haystack, $needle, $s);

            if(is_integer($i)) {
                $aStrPos[] = $i;
                $s = $i + mb_strlen($needle);
            }
        }

        if(isset($aStrPos)) {
            return $aStrPos;
        } else {
            return false;
        }
    }

    /**
     * Apply highlight to row label
     *
     * @param string $a_json json data
     * @param array $parts strings to search
     * @return array
     */
    function apply_highlight($a_json, $parts) {

        $p = count($parts);
        $rows = count($a_json);

        for($row = 0; $row < $rows; $row++) {

            $label = $a_json[$row]["label"];
            $a_label_match = array();

            for($i = 0; $i < $p; $i++) {

                $part_len = mb_strlen($parts[$i]);
                $a_match_start = $this->mb_stripos_all($label, $parts[$i]);

                foreach($a_match_start as $part_pos) {

                    $overlap = false;
                    foreach($a_label_match as $pos => $len) {
                        if($part_pos - $pos >= 0 && $part_pos - $pos < $len) {
                            $overlap = true;
                            break;
                        }
                    }
                    if(!$overlap) {
                        $a_label_match[$part_pos] = $part_len;
                    }

                }

            }

            if(count($a_label_match) > 0) {
                ksort($a_label_match);

                $label_highlight = '';
                $start = 0;
                $label_len = mb_strlen($label);

                foreach($a_label_match as $pos => $len) {
                    if($pos - $start > 0) {
                        $no_highlight = mb_substr($label, $start, $pos - $start);
                        $label_highlight .= $no_highlight;
                    }
                    $highlight = '<span class="hl_results">' . mb_substr($label, $pos, $len) . '</span>';
                    $label_highlight .= $highlight;
                    $start = $pos + $len;
                }

                if($label_len - $start > 0) {
                    $no_highlight = mb_substr($label, $start);
                    $label_highlight .= $no_highlight;
                }

                $a_json[$row]["label"] = $label_highlight;
            }

        }

        return $a_json;

    }
}