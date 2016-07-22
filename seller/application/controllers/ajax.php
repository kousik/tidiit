<?php
class Ajax extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        parse_str($_SERVER['QUERY_STRING'],$_GET);
        $this->db->cache_off();
    }
   
    
    public function check_login(){
        if(strtoupper(trim($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')))!='IN'){
            echo json_encode(array('result'=>'bad','msg'=>'Please check your "Username" and "Password" and try again......'));die;     
        }
        // sleep for 10 seconds
        //sleep(5);
        $config = array(
            array('field'   => 'userName','label'   => 'User Name','rules'   => 'trim|required|xss_clean|valid_email'),
            array('field'   => 'password','label'   => 'Password','rules'   => 'trim|required|xss_clean')
         );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
            //retun to login page with peroper error
            echo json_encode(array('result'=>'bad','msg'=>str_replace('</p>','',str_replace('<p>','',validation_errors()))));die;
        }else{
            $UserName=$this->input->post('userName',TRUE);
            $Password=$this->input->post('password',TRUE);
            $DataArr=$this->User_model->check_login_data($UserName,$Password,'seller');
            //print_r($DataArr);die;
            if(count($DataArr)>0){
                //$roleArr=$this->User_model->get_roles_for_user($DataArr[0]->userId);
                $this->session->set_userdata('FE_SESSION_VAR',$DataArr[0]->userId);
                $this->session->set_userdata('FE_SESSION_USERNAME_VAR',$UserName);
                $this->session->set_userdata('FE_SESSION_VAR_FNAME',$DataArr[0]->firstName);
                //$this->session->set_userdata('FE_SESSION_USERNAME_VAR',$UserName);
                $this->session->set_userdata('FE_SESSION_VAR_TYPE','seller');
                $this->session->set_userdata('FE_SESSION_UDATA',$DataArr[0]);
                $this->User_model->add_login_history(array('userId'=>$DataArr[0]->userId));
                echo json_encode(array('result'=>'good','url'=>BASE_URL.'index/home/'));die; 
            }else{
                echo json_encode(array('result'=>'bad','msg'=>'Please check your "Username" and "Password" and try again.'));die;     
            }
        }
    }
    
    public function retribe_forgot_password(){
        if(strtoupper(trim($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')))!='IN'){
            echo json_encode(array('result'=>'bad','msg'=>'Please check your "Username" and "Password" and try again.'));die;     
        }
        $config = array(
            array('field'   => 'forgot_password_email','label'   => 'Forggot password email','rules'   => 'trim|required|xss_clean|valid_email')
         );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
            //retun to login page with peroper error
            echo json_encode(array('result'=>'bad','msg'=>str_replace('</p>','',str_replace('<p>','',validation_errors()))));die;
        }else{
            $email=$this->input->post('forgot_password_email',TRUE);
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

    public function check_registration(){
        if(strtoupper(trim($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')))!='IN'){
            echo json_encode(array('result'=>'bad','msg'=>'Please check your "Username" and "Password" and try again.'));die;     
        }
        // sleep for 10 seconds
        //sleep(5);
        //echo json_encode(array('result'=>'bad','msg'=>' cCode= '.$_POST['secret'].'  ==  server code'.$this->session->userdata('secret')));die;     
        $config = array(
            array('field'   => 'userName','label'   => 'User Name','rules'   => 'trim|required|xss_clean|min_length[8]|max_length[35]|valid_email|callback_username_check'),
            array('field'   => 'password','label'   => 'Password','rules'   => 'trim|required|xss_clean|min_length[4]|max_length[15]'),
            array('field'   => 'confirmPassword','label'   => 'Password','rules'   => 'trim|required|xss_clean|matches[password]'),
            array('field'   => 'firstName','label'   => 'First Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]'),
            array('field'   => 'lastName','label'   => 'Last Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]'),
            array('field'   => 'email','label'   => 'Email','rules'   => 'trim|required|xss_clean|valid_email'),
            array('field'   => 'contactNo','label'   => 'Contact No','rules'   => 'trim|required|xss_clean|is_natural|min_length[7]|max_length[12]'),
            array('field'   => 'mobile','label'   => 'Contact No','rules'   => 'trim|xss_clean|is_natural|min_length[7]|max_length[12]'),
            array('field'   => 'address','label'   => 'Address','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'city','label'   => 'City','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'stateId','label'   => 'State Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'countryId','label'   => 'Country Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'zip','label'   => 'Zip','rules'   => 'trim|required|xss_clean|is_natural|min_length[5]|max_length[8]'),
            array('field'   => 'secret','label'   => 'Security Code','rules'   => 'trim|required|xss_clean|callback_valid_security_code'),
            array('field'   => 'agree','label'   => 'Agree for Terms and Condition','rules'   => 'trim|required|xss_clean')
         );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
            //retun to login page with peroper error
            echo json_encode(array('result'=>'bad','msg'=>str_replace('</p>','',str_replace('<p>','',validation_errors()))));die;
        }else{
            $userName=$this->input->post('userName',TRUE);
            $password=$this->input->post('password',TRUE);
            $firstName=$this->input->post('firstName',TRUE);
            $lastName=$this->input->post('lastName',TRUE);
            $email=$this->input->post('email',TRUE);
            $mobile=$this->input->post('mobile',TRUE);
            $contactNo=$this->input->post('contactNo',TRUE);
            $fax=$this->input->post('fax',TRUE);
            $address=$this->input->post('address',TRUE);
            $city=$this->input->post('city',TRUE);
            $stateId=$this->input->post('stateId',TRUE);
            $countryId=$this->input->post('countryId',TRUE);
            $zip=$this->input->post('zip',TRUE);
            $aboutMe=$this->input->post('aboutMe',TRUE);
            $receiveNewsLetter=$this->input->post('receiveNewsLetter',TRUE);
            
            //echo json_encode(array('result'=>'bad','msg'=>$receiveNewsLetter));die; 
            
            $dataArr=array('userName'=>$userName,'password'=>  base64_encode($password).'~'.md5('tidiit'),'firstName'=>$firstName,'lastName'=>$lastName,
                'contactNo'=>$contactNo,'mobile'=>$mobile,'email'=>$email,'fax'=>$fax,'IP'=> $this->input->ip_address(),'userResources'=>'site',
                'userType'=>'seller','status'=>1,'aboutMe'=>$aboutMe);
            $userId=$this->User_model->add($dataArr);
            
            if($userId!=""){
                $billDataArr=array('userId'=>$userId,'countryId'=>$countryId,'stateId'=>$stateId,'city'=>$city,'address'=>$address,'zip'=>$zip,'contactNo'=>$contactNo);
                $this->User_model->add_bill_address($billDataArr);
            
                if($receiveNewsLetter==1){
                    if($this->User_model->is_already_subscribe($email)==FALSE){
                        $this->User_model->subscribe($email);
                    }
                }
                $DataArr=$this->User_model->get_details_by_id($userId);
                $this->session->set_userdata('FE_SESSION_VAR',$userId);
                $this->session->set_userdata('FE_SESSION_USERNAME_VAR',$userName);
                $this->session->set_userdata('FE_SESSION_VAR_FNAME',$firstName);
                $this->session->set_userdata('FE_SESSION_UDATA',$DataArr[0]);
                
                $mail_template_data=array();
                $mail_template_data['TEMPLATE_CREATE_USER_EAMIL']=$email;
                $mail_template_data['TEMPLATE_CREATE_USER_FIRSTNAME']=$firstName;
                $mail_template_data['TEMPLATE_CREATE_USER_LASTNAME']=$lastName;
                $mail_template_data['TEMPLATE_CREATE_USER_USERNAME']=$email;
                $mail_template_data['TEMPLATE_CREATE_USER_PASSWORD']=$password;
                
                $mail_template_view_data=$this->load_default_resources();
                $mail_template_view_data['create_user']=$mail_template_data;
                $this->_global_tidiit_mail($email, "Your account at Tidiit Inc. Ltd.", $mail_template_view_data,'user_create',$firstName.' '.$lastName);
                
                $this->session->set_userdata('FE_SESSION_VAR_TYPE','seller');
                
                echo json_encode(array('result'=>'good','url'=>BASE_URL.'index/home/','msg'=>'You have successfully register your account with "Tidiit Inc Ltd.Your login information will be sent to registered email account.'));die; 
            }else{
                echo json_encode(array('result'=>'bad','msg'=>'Please check your user anme and password and try again.'));die;     
            }
        }
    }
    
    public function username_check($str){
        if($this->User_model->check_username_exists($str,'seller')==TRUE){
            $this->form_validation->set_message('username_check', 'This User Name already registered.Please try a new one.');
            return FALSE;
        }else{
            return TRUE;
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
            //$this->form_validation->set_message('valid_security_code', 'Invalid security code,please try again.');
            $this->form_validation->set_message('valid_security_code', '$str = '.$str.' == '.$this->session->userdata('secret'));
            return FALSE;
        }else{
            return TRUE;
        }
    }
    
    public function reset_secret(){
        $secret=$this->input->post('secret',TRUE);
        $this->session->set_userdata('secret',$secret);
    }
    
    function show_state(){
        $this->load->model('Country');
        $countryId=  $this->input->post('countryId',TRUE);
        $stateDataArr=$this->Country->get_state_country($countryId);
        $stateDropdown='<select name="stateId" id="stateId" class="form-control middle" required>';
        $stateDropdown.='<option value=""> * Select * </option>';
        if(empty($stateDataArr)){
            $stateDropdown.='</select>';
        }else{
            foreach ($stateDataArr AS $k){
                $stateDropdown.='<option value="'.$k->stateId.'">'.$k->stateName.'</option>';
            }
            $stateDropdown.='</select>';
        }
        echo $stateDropdown;die;
    }
    
    function next_category_dropdown(){
        $categoryId=  $this->input->post('categoryId',TRUE);
        $required=  $this->input->post('required',TRUE);
        $categoryDataArr=$this->Category_model->get_subcategory_by_category_id($categoryId);
        $html='';
        if(empty($categoryDataArr)){
            echo $html;die;
        }else{
            if($required=='yes'){
                $required=' required';
            }else{$required='';}
            $html='<tr><td class="text-left" width="33%">Select Next Category</td><td class="text-left" width="33%"><select class="form-control '.$required.'" name="NextCategory'.$categoryId.'" id="NextCategory'.$categoryId.'"><option value="">Select</option>';
            foreach ($categoryDataArr AS $k){
                $html.='<option value="'.$k->categoryId.'">'.$k->categoryName.'</option>';
            }
            $html.='</select></td></tr>';
            $html.='<script type="text/javascript">jQuery(document).ready(function(){
       jQuery("#NextCategory'.$categoryId.'").on("change",function(){
           var removeNextRow=0;
           jQuery("#categoryMainTable tr").each(function(){
                if(removeNextRow>0){
                    jQuery(this).remove();
                }else{
                    if(jQuery(this).find("select[id=\'NextCategory'.$categoryId.'\']").length>0){removeNextRow=1;}
                }
           });
           if(jQuery(this).val()==""){
                return false;
            }
         var ajaxURL="'.BASE_URL.'ajax/next_category_dropdown/"; 
           var ajaxData="categoryId="+jQuery(this).val();
           jQuery.ajax({
               type:"POST",
               url:ajaxURL,
               data:ajaxData,
               success:function(msg){
                   jQuery("#categoryMainTable tr:last").after(msg);
               }
           }); 
       }); 
    });
</script>';
            echo $html;die;
        }
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
    
    function show_order_group_details(){
        $this->load->model('Order_model');
        $groupId=$this->input->post('groupId',TRUE);
        $data=  $this->load_default_resources();
        $groupData=$this->User_model->get_group_by_id($groupId);
        //pre($groupData);die;
        $data['group']= $groupData;
        $data['groupId']= $groupId;
        $data['groupTitle']= $groupData->groupTitle;
        //pre($data);die;
        echo json_encode(array('content'=>$this->load->view('order_group_details',$data,true)));die;
    }
    
    function change_order_state(){
        $this->load->model('Order_model');
        $this->load->model('Logistics_model');
        $orderId=$this->input->post('orderId',TRUE);
        $data=  $this->load_default_resources();
        $order=$this->Order_model->get_single_order_by_id($orderId);
        $data['order']=$order;
        $data['orderId']=$orderId;
        $data['group'] = $this->User_model->get_group_by_id($order->groupId);
        $data['logisticsData'] = $this->Logistics_model->get_all_admin();
        $orderStatusobj=$this->Order_model->get_state1();
        $latestOrderState=$this->Order_model->current_order_state_history($orderId);
        $stateArr=array();
        foreach($orderStatusobj As $k){
            $stateArr[$k->orderStateId]=$k->name;
        }
        $data['status']= $stateArr;
        $data['latestOrderState']= $latestOrderState;
        //pre($data);die;
        echo json_encode(array('content'=>$this->load->view('order_status_change',$data,true)));die;
    }
    
    function change_order_state_cancel(){
        $this->load->model('Order_model');
        $orderId=$this->input->post('orderId',TRUE);
        $data=  $this->load_default_resources();
        $order=$this->Order_model->get_single_order_by_id($orderId);
        $data['order']=$order;
        $data['orderinfo']=unserialize(base64_decode($order->orderInfo));
        $data['orderId']=$orderId;
        $data['group'] = $this->User_model->get_group_by_id($order->groupId);
        $orderStatusobj=$this->Order_model->get_state1();
        $stateArr=array();
        foreach($orderStatusobj As $k){
            $stateArr[$k->orderStateId]=$k->name;
        }
        $data['status']= $stateArr;
        //pre($data);die;
        echo json_encode(array('content'=>$this->load->view('order_status_change_cancel',$data,true)));die;
    }

    function delete_warehouse(){
        $id=$this->input->post('id',TRUE);
        $this->User_model->warehouse_delete($id);
        echo json_encode(array('content'=> true));die;
    }

    function update_order_seller_info(){
        $data = [];
        $error = false;
        $total = $this->input->post('total',TRUE);
        $orderId = $this->input->post('orderId',TRUE);
        $serial = $this->input->post('serial',TRUE);
        $setWarehouse = $this->input->post('setWarehouse',TRUE);
        $ct = 0;
        foreach($serial as $v):
            if($v):
                $ct = $ct+1;
            endif;
        endforeach;;

        if($ct < $total):
            $error[] = '<div class="alert alert-danger text-left" role="alert">Please enter all products unique number!</div>';
        endif;
        if(!$setWarehouse):
            $error[] = '<div class="alert alert-danger text-left" role="alert">Please selet your warehose for dispatch products!</div>';
        endif;
        if($error):
            echo json_encode(array('error'=>implode("", $error), 'orderid' => $orderId));die;
        endif;

        $this->load->model('Order_model');

        $data['productsSerial'] = serialize($serial);
        $whouse = $this->User_model->get_single_warehouse($setWarehouse);
        $data['setWarehouse'] = serialize($whouse);
        $this->Order_model->update($data,$orderId);
        echo json_encode(array('success'=>true, 'orderid' => $orderId));die;
    }
}