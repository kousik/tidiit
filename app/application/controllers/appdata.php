<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Appdata extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Siteconfig_model','siteconfig');
        //$this->load->model('Testimonial_model');
        $this->load->model('Cms_model','cms');
        $this->load->model('Product_model','product');
        $this->load->model('Category_model','category');
        $this->load->model('Country');
    }
    
    function home_get(){
        $timeStamp=$this->get('timestamp');
        if(!isValidTimeStamp($timeStamp)){
            $this->response(array('error' => 'Invalid timestamp'), 400);
        }else{
            $this->load->model('Banner_model','banner');
            $this->load->model('Brand_model','brand');
            $result = array();
            $slider1=$this->banner->get_home_slider(1,TRUE);
            //$slider2=$this->banner->get_home_slider(2,TRUE);
            $noOfItem=  $this->siteconfig->get_value_by_name('MOBILE_APP_HOME_PAGE_SLIDER_ITEM_NO');
            $newArrivalsData=  $this->product->get_recent($noOfItem,TRUE);
            //pre($newArrivalsData);die;
            $result['slider1']=$slider1;
            //$result['slider2']=$slider2;
            $result['category_menu']=$this->get_main_menu();
            $result['best_sellling_item']=$newArrivalsData;
            $result['new_arrivals']=$newArrivalsData;
            $result['featured_products']=$newArrivalsData;
            $result['brand']=$this->brand->get_all(TRUE);
            //$result['site_product_image_url']=$this->config->item('ProductURL');
            success_response_after_post_get($result);
        }
    }
    
    function login_post(){
        $userName=$this->post('userName');
        $password=$this->post('password');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if (!filter_var($userName, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        if($userName!="" && $password!="" && $deviceToken!="" && $deviceType!="" && $UDID!=""){
            $rs=$this->user->check_login_data($userName,$password,'buyer');
            if(count($rs)>0){
                $this->user->add_login_history(array('userId'=>$rs[0]->userId,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'udid'=>$UDID,'appSource'=>$deviceType));
                $parram=array('userId'=>$rs[0]->userId,'message'=>'You have logedin successfully');
                success_response_after_post_get($parram);
            }else{$this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;}
        }else{
            $this->response(array('error' => 'Please provide Username,password,device token,device type,UDID.'), 400);
            //echo json_encode(array('error' => 'Problem in saving user, please try again.'));
            return false;
        }
    }
    
    function registration_post(){
        $email=$this->post('email');
        $password=$this->post('password');
        $confirmPassword=$this->post('confirmPassword');
        $firstName=$this->post('firstName');
        $lastName=$this->post('lastName');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        if($password!=$confirmPassword){
            $this->response(array('error' => 'Password and confirm password is not matching.'), 400); return FALSE;
        }
        
        if($password =="" || $confirmPassword =="" || $firstName =="" || $lastName=="" || $deviceType=="" || $deviceToken=="" || $UDID==""){
            $this->response(array('error' => 'All fields are required amd must be filled up.'), 400); return FALSE;
        }
        
        if($this->user->check_username_exists_without_type($email)==TRUE){
            $this->response(array('error' => 'This email is already registered.Please try a new one.'), 400); return FALSE;
        }
        
        $dataArr=array('userName'=>$email,'password'=>  base64_encode($password).'~'.md5('tidiit'),'firstName'=>$firstName,'lastName'=>$lastName,
                'email'=>$email,'userResources'=>'app','userType'=>'buyer','status'=>1,'appSource'=>$deviceType);
        $userId=$this->user->add($dataArr);
        
        if($userId!=""){               
            if($this->user->is_already_subscribe($email)==FALSE){
                $this->user->subscribe($email,$deviceType);
            }
            $this->user->add_login_history(array('userId'=>$userId,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'udid'=>$UDID,'appSource'=>$deviceType));
            
            $mail_template_data=array();
            $mail_template_data['TEMPLATE_CREATE_USER_EAMIL']=$email;
            $mail_template_data['TEMPLATE_CREATE_USER_FIRSTNAME']=$firstName;
            $mail_template_data['TEMPLATE_CREATE_USER_LASTNAME']=$lastName;
            $mail_template_data['TEMPLATE_CREATE_USER_USERNAME']=$email;
            $mail_template_data['TEMPLATE_CREATE_USER_PASSWORD']=$password;

            $mail_template_view_data=load_default_resources();
            $mail_template_view_data['create_user']=$mail_template_data;
            global_tidiit_mail($email, "Your account at Tidiit Inc. Ltd.", $mail_template_view_data,'user_create',$firstName.' '.$lastName);
            
            $parram=array('userId'=>$userId,'message'=>'You have registered successfully, you will get separte mail for regisration details.');
            success_response_after_post_get($parram);
        }
    }
    
    function my_profile_get(){
        $result = array();
        $userId=  $this->get('userId');
        if($userId==""):
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        else:
            $userData=$this->user->get_details_by_id($userId,TRUE);
            $userDataDOBArr=  explode('-',$userData[0]['DOB']);
            if(count($userDataDOBArr)>0){
                $result['userProfileDateData']=$userDataDOBArr[2].'-'.$userDataDOBArr[1].'-'.$userDataDOBArr[0];
                $result['userProfileData']=$userData;
            }else{
                $result['userProfileData']=$userData;
            }
            success_response_after_post_get($result);
        endif;
    }
    
    function my_profile_post(){
        $firstName=$this->post('firstName');
        $lastName=$this->post('lastName');
        $contactNo=$this->post('contactNo');
        $email=$this->post('email');
        $mobile=$this->post('mobile');
        $fax=$this->post('fax');
        $rowDOB=$this->post('DOB');
        $DOB="00-00-0000";
        $dobArr=  explode('-',$rowDOB);
        if(!empty($dobArr)):
            $DOB=$dobArr[2].'-'.$dobArr[1].'-'.$dobArr[0];
        endif;
        $aboutMe=$this->post('aboutMe');
        $userId=$this->post('userId');
        
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        if($email!="" && $contactNo!="" && $deviceToken!="" && $deviceType!="" && $UDID!="" && $userId!=""):
            $myProfileDataArr=array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$contactNo,
                    'email'=>$email,'DOB'=>$DOB,'mobile'=>$mobile,'fax'=>$fax,'aboutMe'=>$aboutMe);
            $this->user->edit($myProfileDataArr,$userId);
            $result['message']="Profile data updated successfully.";
            success_response_after_post_get($result);
        endif;
    }
    
    function my_buyers_clubs_get(){
        $result = array();
        $userId=  $this->get('userId');
        $result['countryDataArr']=$this->Country->get_all1(TRUE);
        //$data['CatArr']=$this->Category_model->get_all(0);
        $menuArr=array();
        $TopCategoryData=$this->category->get_top_category_for_product_list();
        //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->category->get_subcategory_by_category_id($k->categoryId);
            if(count($SubCateory)>0){
                foreach($SubCateory as $kk => $vv){
                    $menuArr[$vv->categoryId]=$k->categoryName.' -> '.$vv->categoryName;
                    $ThirdCateory=$this->category->get_subcategory_by_category_id($vv->categoryId);
                    if(count($ThirdCateory)>0){
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            $menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->category->get_subcategory_by_category_id($v3->categoryId);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }
                            }
                        }
                    }
                }
            }
        }
        $categoryMenyArr=array();
        foreach($menuArr As $k => $v){
            $categoryMenyArr[]=array('categoryId'=>$k,'categoryName'=>$v);
        }
        
        $result['CatArr']=  $categoryMenyArr;
        $myGroupDataArr=$this->user->get_my_groups_apps($userId);
        $my_groups = $myGroupDataArr;
        $result['myGroups']=$my_groups;
        success_response_after_post_get($result);
    }
    
    function my_shipping_address_get(){
        $result = array();
        $userShippingDataDetails = array();
        $userId=  $this->get('userId');
        $this->load->model('Country');
        $userShippingDataDetails=$this->user->get_user_shipping_information($userId);
        //pre($userShippingDataDetails);die;
        if(empty($userShippingDataDetails)){
            $userShippingDataDetails[0]=array();
            $userShippingDataDetails[0]['firstName']="";
            $userShippingDataDetails[0]['lastName']="";
            $userShippingDataDetails[0]['countryId']="";
            $userShippingDataDetails[0]['cityId']="";
            $userShippingDataDetails[0]['zipId']="";
            $userShippingDataDetails[0]['localityId']="";
            $userShippingDataDetails[0]['phone']="";
            $userShippingDataDetails[0]['address']="";
            $userShippingDataDetails[0]['contactNo']="";
            $userShippingDataDetails[0]['landmark']="";
        }
        if($userShippingDataDetails[0]['countryId']!=""){
            $result['cityDataArr']=  $this->Country->get_all_city1($userShippingDataDetails[0]['countryId'],TRUE);
        }
        if($userShippingDataDetails[0]['zipId']!=""){
            $result['zipDataArr']=  $this->Country->get_all_zip1($userShippingDataDetails[0]['cityId'],TRUE);
        }
        if($userShippingDataDetails[0]['localityId']!=""){
            $result['localityDataArr']=  $this->Country->get_all_locality1($userShippingDataDetails[0]['zipId'],TRUE);
        }
        $result['countryDataArr']=$this->Country->get_all1(TRUE);
        $result['userShippingDataDetails']=$userShippingDataDetails;
        $rs=$this->user->get_my_product_type($userId);
        //pre($rs); die;
        $result['userProductTypeArr']=$rs;
        $result['topCategoryDataArr']=$this->category->get_top_category_for_product_list(true);
        success_response_after_post_get($result);
    }
    
    function my_shipping_address_post(){
        $userId=  $this->post('userId');
        $firstName=  $this->post('firstName');
        $lastName=  $this->post('lastName');
        $countryId=  $this->post('countryId');
        $cityId=  $this->post('cityId');
        $zipId=  $this->post('zipId');
        $localityId=  $this->post('localityId');
        $phone=  $this->post('phone');
        $address=  $this->post('address');
        $productTypeId=  $this->post('productTypeId');
        $productTypeIdArr=  explode(',', $productTypeId);
        $deviceType=$this->post('deviceType');
        if(empty($productTypeIdArr)){
            $this->response(array('error' => 'Please provide prodcut type for current user.'), 400); return FALSE;
        }
        
        foreach ($productTypeIdArr AS $k =>$v){
            $newCateoryArr[]=$v;
            $newCateoryArr=recusive_category($newCateoryArr,$v);
        }
        //pre($newCateoryArr);die;
        $this->user->update_user_product_type_category(array('productTypeCateoryId'=>implode(',', $newCateoryArr)),$userId);
        $this->user->remove_user_from_product_type($userId);
        foreach($newCateoryArr As $k => $v){
            $this->user->update_product_type_user($v,$userId);
        }

        $rs=$this->Country->city_details($cityId);

        $isAdded=$this->user->is_shipping_address_added();
        if(empty($isAdded)){
            $this->user->add_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'userId'=>$userId,'address'=>$address,'stateId'=>$rs[0]->stateId,'appSource'=>$deviceType));
        }else{
            $this->user->edit_shipping(array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$phone,'countryId'=>$countryId,'cityId'=>$cityId,'zipId'=>$zipId,'localityId'=>$localityId,'address'=>$address,'stateId'=>$rs[0]->stateId),$userId);
        }
        
        $result['message']="Shipping address data updated successfully.";
        success_response_after_post_get($result);
    }
    
    function my_finance_get(){
        $result = array();
        $financeDataArr=array();
        $userId=  $this->get('userId');
        $financeDataArr=$this->user->get_finance_info($userId);
        if(empty($financeDataArr)){
            $financeDataArr[0]=array();
            $financeDataArr[0]['mpesaFullName']="";
            $financeDataArr[0]['mpesaAccount']="";
        }
        $result['financeDataArr']=$financeDataArr;
        success_response_after_post_get($result);
    }
    
    function retrive_your_password_post(){
        $email=$this->post('email');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        $DataArr=$this->user->get_data_by_email($email);
        if(count($DataArr)>0):
            $mail_template_data=array();
            $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_EAMIL']=$DataArr[0]->email;
            $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_FIRSTNAME']=$DataArr[0]->firstName;
            $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_LASTNAME']=$DataArr[0]->lastName;
            $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_USERNAME']=$DataArr[0]->userName;
            $mail_template_data['TEMPLATE_RETRIBE_USER_PASSWORD_PASSWORD']=$DataArr[0]->password;

            $mail_template_view_data=load_default_resources();
            $mail_template_view_data['retribe_user_password']=$mail_template_data;
            global_tidiit_mail($DataArr[0]->email, "Your password at Tidiit Inc. Ltd.", $mail_template_view_data,'retribe_user_password',$DataArr[0]->firstName.' '.$DataArr[0]->lastName);
            $result = array();
            $result['message']="Your password has send by your registered email.";
            success_response_after_post_get($result);
        else:
            $this->response(array('error' => 'Please check your "email" and try again.'), 400); return FALSE;
        endif;
        
    }
    
    function change_my_password_post(){
        $userId=$this->post('userId');
        $oldPassword=$this->post('oldPassword');
        $newPassword=$this->post('newPassword');
        $newConfirmPassword=$this->post('newConfirmPassword');
        if($userId =="" && $oldPassword=="" && $newPassword =="" && $newConfirmPassword ==""):
            $this->response(array('error' => 'Please provide all data.'), 400); return FALSE;
        else:
            if($newPassword==$newConfirmPassword):
                if($this->user->check_old_password($oldPassword,$userId)==TRUE):
                    $dataArr=array('password'=>  base64_encode($newPassword).'~'.md5('tidiit'));
                    $this->user->edit($dataArr,$userId);
                    $result=array();
                    $result['message']="Your password has changed successfully.";
                    success_response_after_post_get($result);
                else:
                    $this->response(array('error' => 'Invalid old password provided,try again.'), 400); return FALSE;
                endif;
            else:    
                $this->response(array('error' => 'New password and confirm password is not matching,try again.'), 400); return FALSE;
            endif;
            
        endif;
    }
    
    function get_city_by_country_get(){
        $countryId=$this->get('countryId');
        $result=array();
        $result['cityArr']=  $this->Country->get_all_city1($countryId,true);
        $result['ajaxType']='yes';
        success_response_after_post_get($result);
    }
    
    function get_zip_by_city_get(){
        $cityId=$this->get('cityId');
        $result=array();
        $result['zipArr']=  $this->Country->get_all_zip1($cityId,TRUE);
        $result['ajaxType']='yes';
        success_response_after_post_get($result);
    }
    
    function get_locality_by_zip_get(){
        $zipId=$this->get('zipId');
        $result=array();
        $result['localityArr']=  $this->Country->get_all_locality1($zipId,TRUE);
        $result['ajaxType']='yes';
        success_response_after_post_get($result);
    }
    
    function get_all_user_by_locality_except_me_get(){
        $localityId=$this->get('localityId');
        $userId=$this->get('userId');
        $result=array();
        $result['localityArr']=  $this->user->get_all_users_by_locality($localityId,$userId);
        $result['ajaxType']='yes';
        success_response_after_post_get($result);
    }
    
    function my_finance_post(){
        $result=array();
        $userId=$this->post('userId');
        $deviceType=$this->post('deviceType');
        $mpesaFullName=$this->post('mpesaFullName');
        $mpesaAccount=$this->post('mpesaAccount');
        if($userId =="" && $mpesaFullName=="" && $mpesaAccount ==""):
            $this->response(array('error' => 'Please provide all data.'), 400); return FALSE;
        else:
            $isAdded=$this->user->get_finance_info($userId);
            if(empty($isAdded)){
                $this->user->add_finance(array('mpesaFullName'=>$mpesaFullName,'mpesaAccount'=>$mpesaAccount,'userId'=>$userId,'appSource'=>$deviceType));
            }else{
                $this->user->edit_finance(array('mpesaFullName'=>$mpesaFullName,'mpesaAccount'=>$mpesaAccount),$userId);
            }
            $result['message']="Your finance information has updated successfully.";
            success_response_after_post_get($result);
        endif;
    }
    
    function add_new_group_post(){
        $groupAdminId=$this->post('userId');
        $groupTitle=$this->post('groupTitle');
        $productType=$this->post('productType');
        $groupUsers=$this->post('groupUsers');
        $deviceType=$this->post('deviceType');
        $colors = array('red','maroon','purple','green','blue');
        $rand_keys = array_rand($colors, 1);
        $groupColor = $colors[$rand_keys];
        $groupUsersArr=  explode(',', $groupUsers);
        
        $notify = array();
        
        if(!$groupUsersArr):
            $this->response(array('error' => 'Please select the at least one Buyer club member!'), 400); return FALSE;
        endif;
        $groupDataArr=array('groupAdminId'=>$groupAdminId,'groupTitle'=>$groupTitle,'productType'=>$productType,'groupUsers'=>$groupUsers,'groupColor'=>$groupColor,'appSource'=>$deviceType);
        $groupId = $this->user->group_add($groupDataArr);
        $adminDataArr=  $this->user->get_details_by_id($groupAdminId);
        //pre($adminDataArr);die;
        if(empty($groupUsersArr[0])):
            $this->response(array('error' =>'Invalid group user data provided.'), 400); return FALSE;
        endif;
        if($groupId):
            if($groupUsersArr):
                foreach($groupUsersArr as $guser):
                    $receiverDetails=$this->user->get_details_by_id($guser);
                    $notify['senderId'] = $groupAdminId;
                    $notify['receiverId'] = $guser;
                    $notify['receiverMobileNumber'] = $receiverDetails[0]->mobile;
                    $notify['senderMobileNumber'] = $adminDataArr[0]->mobile;
                    $notify['nType'] = "BUYING-CLUB-ADD";
                    $notify['nTitle'] = $groupTitle;
                    $notify['adminName'] = $adminDataArr[0]->firstName.' '.$adminDataArr[0]->lastName;
                    $notify['adminEmail'] = $adminDataArr[0]->email;
                    $notify['adminContactNo'] = $adminDataArr[0]->contactNo;
                    $notify['appSource'] = $deviceType;
                    $this->send_notification($notify);
                endforeach;
            endif;
            $result=array();
            $result['groupId']=$groupId;
            $result['message']="Buying Club Created successfully";
            success_response_after_post_get($result);
        else:    
            $this->response(array('error' => 'Some error happen. Please try again!'), 400); return FALSE;
        endif;
    }
    
    function edit_group_get(){
        $adminId=$this->get('userId');
        $groupId=$this->get('groupId');
        $group = $this->user->get_group_by_id($groupId,TRUE);
        if(!$group):
            $this->response(array('error' => 'Invalid Buying Club index. Please try again!'), 400); return FALSE;
        endif;
        $user = $this->user->get_details_by_id($adminId,TRUE);
        if(!$user):
            $this->response(array('error' => 'Invalid user index. Please try again!'), 400); return FALSE;
        endif;
        $result=array();
        $result['countryDataArr']=$this->Country->get_all1();
        $menuArr=array();
        $TopCategoryData=$this->category->get_top_category_for_product_list();
        //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->category->get_subcategory_by_category_id($k->categoryId);
            if(count($SubCateory)>0){
                foreach($SubCateory as $kk => $vv){
                    $menuArr[$vv->categoryId]=$k->categoryName.' -> '.$vv->categoryName;
                    $ThirdCateory=$this->category->get_subcategory_by_category_id($vv->categoryId);
                    if(count($ThirdCateory)>0){
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            $menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->category->get_subcategory_by_category_id($v3->categoryId);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }
                            }
                        }
                    }
                }
            }
        }
        $categoryMenyArr=array();
        foreach($menuArr As $k => $v){
            $categoryMenyArr[]=array('categoryId'=>$k,'categoryName'=>$v);
        }
        $result['CatArr']=  $categoryMenyArr;
        
        
        $result['group']=$group;
        $result['orderId']=0;
        $result['reorder'] = 1;
        $result['user']=$user;
        success_response_after_post_get($result);
    }
    
    function edit_group_post(){
        $groupId = $this->post('groupId');
        $adminId = $this->post('userId');
        $groupTitle = $this->post('groupTitle');
        //$productType = $this->input->post('productType');
        $groupUsersArr = $this->post('groupUsers');
        
        $colors = array('red','maroon','purple','green','blue');
        $rand_keys = array_rand($colors, 1);
        $groupColor = $colors[$rand_keys];
        
        if(!$groupUsersArr):
            $this->response(array('error' => 'Please select the at least one Buying Club member!'), 400); return FALSE;
        endif;
        
        $groupUsers = implode(",", $groupUsersArr);
        $bfrUpdateGroup = $this->user->get_group_by_id($groupId);
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
        
        $groupIdUpdate = $this->user->group_update(array('groupTitle'=>$groupTitle,'groupUsers'=>$groupUsers,'groupColor'=>$groupColor), $groupId);
        if($groupIdUpdate):
            $adminDataArr=  $this->user->get_details_by_id($adminId);
            foreach($olduser as $ouser):
                $receiverDetails=$this->user->get_details_by_id($ouser);
                $notify['senderId'] = $adminId;
                $notify['receiverId'] = $ouser;
                $notify['nType'] = "BUYING-CLUB-MODIFY";
                $notify['receiverMobileNumber'] = $receiverDetails[0]->mobile;
                $notify['senderMobileNumber'] = $adminDataArr[0]->mobile;
                $notify['adminName'] = $adminDataArr[0]->firstName.' '.$adminDataArr[0]->lastName;
                $notify['adminEmail'] = $adminDataArr[0]->email;
                $notify['adminContactNo'] = $adminDataArr[0]->contactNo;
                $notify['nTitle'] = $groupTitle;
                $this->send_notification($notify);
            endforeach;
            foreach($newUser as $nuser):
                $receiverDetails=$this->user->get_details_by_id($nuser);
                $notify['senderId'] = $adminId;
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
                $receiverDetails=$this->user->get_details_by_id($duser);
                $notify['senderId'] = $adminId;
                $notify['receiverId'] = $duser;
                $notify['nType'] = "BUYING-CLUB-MODIFY-DELETE";
                $notify['receiverMobileNumber'] = $receiverDetails[0]->mobile;
                $notify['senderMobileNumber'] = $adminDataArr[0]->mobile;
                $notify['nTitle'] = $groupTitle;
                $notify['adminName'] = $adminDataArr[0]->firstName.' '.$adminDataArr[0]->lastName;
                $notify['adminEmail'] = $adminDataArr[0]->email;
                $notify['adminContactNo'] = $adminDataArr[0]->contactNo;
                $this->send_notification($notify);
            endforeach;
            $reorder = $this->post('reorder');
            
            if($reorder):
                $this->load->model('Order_model','order');
                $orderId = $this->post('orderId');
                $order = $this->order->get_single_order_by_id($orderId);
                $pro = $this->product->details($order->productId);
                $orderinfo['pdetail'] = $pro[0];
                $group = $this->user->get_group_by_id($groupId);
                $mail_template_data=array();
                foreach($group->users as $key => $usr):
                    $mail_template_data=array();
                    $data['senderId'] = $adminId;
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

                    $data['isRead'] = 0;
                    $data['status'] = 1;
                    $data['createDate'] = date('Y-m-d H:i:s');
                    $data['adminName'] = $adminDataArr[0]->firstName.' '.$adminDataArr[0]->lastName;
                    $data['adminEmail'] = $adminDataArr[0]->email;
                    $data['adminContactNo'] = $adminDataArr[0]->contactNo;

                    //Send Email message
                    $recv_email = $usr->email;
                    $sender_email = $group->admin->email;
                    /// firing mail
                    $mail_template_view_data=load_default_resources();
                    $mail_template_view_data['group_order_re_start']=$mail_template_data;
                    global_tidiit_mail($recv_email, "Buying Club Order Re-Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_re_start');
                    
                    $this->user->notification_add($data);
                endforeach;
            endif;
            success_response_after_post_get(array('message'=>'Selected group data updated successfully.'));
        else:    
            $this->response(array('error' => 'Some error happen. Please try again!'), 400); return FALSE;
        endif;
    }
    
    function delete_group_post(){
        $groupId=  $this->post('groupId');
        $userId=  $this->post('userId');
        if($userId>0):
            $this->user->group_delete($groupId);
            success_response_after_post_get(array('message'=>'Selected group deleted successfully.'));
        else:    
            $this->response(array('error' => 'Invalid user id. Please try again!'), 400); return FALSE;
        endif;
    }
    
    function get_child_category_get(){
        $categoryId=$this->get('categoryId');
        $chieldCategoryArr=$this->category->get_subcategory_by_category_id($categoryId,true);
        if(count($chieldCategoryArr)>0){
            success_response_after_post_get(array('chieldCategoryArr'=>$chieldCategoryArr));
        }else{
            success_response_after_post_get(array());
        }
    }
    
    function get_users_by_locality_product_type_except_me_get(){
        $localityId=$this->get('localityId');
        $userId=$this->get('userId');
        $productTypeId=$this->get('productTypeId');
        $result=array();
        $result['localityArr']=  $this->user->get_all_users_by_product_type_locality($productTypeId,$localityId,$userId);
        $result['ajaxType']='yes';
        success_response_after_post_get($result);
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
                if($this->siteconfig->get_value_by_name('SMS_SEND_ALLOW')=='yes'):
                    $data['isMobMessage'] = true;
                else:
                    $data['isMobMessage'] = true;
                endif;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
            case 'BUYING-CLUB-MODIFY':
                $data['nMessage'] = "Hi, <br> Buying Club <strong>[".$data['nTitle']."]</strong> has been modified.";
                $data['isEmail'] = true;
                if($this->siteconfig->get_value_by_name('SMS_SEND_ALLOW')=='yes'):
                    $data['isMobMessage'] = true;
                else:
                    $data['isMobMessage'] = true;
                endif;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
            case 'BUYING-CLUB-MODIFY-NEW':
                $data['nMessage'] = "Hi, <br> You Have added in my Buying Club <strong>[".$data['nTitle']."]</strong>.<br />My name is ".$data['adminName'].".<br />My email id is ".$data['adminEmail'].".<br />My contact number is ".$data['adminContactNo'].".";
                $data['isEmail'] = true;
                if($this->siteconfig->get_value_by_name('SMS_SEND_ALLOW')=='yes'):
                    $data['isMobMessage'] = true;
                else:
                    $data['isMobMessage'] = true;
                endif;
                $data['createDate'] = date('Y-m-d H:i:s');
                break;
            case 'BUYING-CLUB-MODIFY-DELETE':
                $data['nMessage'] = "Hi, <br> You are not part of this Buying Club <strong>[".$data['nTitle']."]</strong>";
                $data['isEmail'] = true;
                if($this->siteconfig->get_value_by_name('SMS_SEND_ALLOW')=='yes'):
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
            $smsData['nMessage']=  str_replace('<br />','', $data['nMessage']);
            $smsData['nMessage']=  str_replace('<br>','', $smsData['nMessage']);
            $smsData['nMessage']=  str_replace('<br/>','', $smsData['nMessage']);
            $smsData['nMessage']=  str_replace('<strong>','', $smsData['nMessage']);
            $smsData['nMessage']=  str_replace('</strong>','', $smsData['nMessage']);
            send_sms_notification($smsData);
        endif;
        
        if($data['isEmail']):
            //Send Email message
            unset($data['isEmail']);
        endif;
        unset($data['adminName']);
        unset($data['isMobMessage']);
        unset($data['adminEmail']);
        unset($data['adminContactNo']);
        unset($data['receiverMobileNumber']);
        unset($data['senderMobileNumber']);
        $this->user->notification_add($data);
    }
    
    function get_main_menu(){
        $mainMenuArr=array();
        $TopCategoryData=$this->category->get_top_category_for_product_list(TRUE);
        //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->category->get_subcategory_by_category_id($k['categoryId'],TRUE);
            //pre($SubCateory);die;
            if(count($SubCateory)>0){
                $SubCateoryArr=array();
                foreach($SubCateory as $kk => $vv){
                    $ThirdCateory=$this->category->get_subcategory_by_category_id($vv['categoryId'],TRUE);
                    if(count($ThirdCateory)>0){
                        $ThirdCateoryArr=array();
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            //$menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->category->get_subcategory_by_category_id($v3['categoryId'],TRUE);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                /*foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }*/
                                $v3['SubCategory']=$FourthCateory;
                            }
                            $ThirdCateoryArr[]=$v3;
                        }
                        $vv['SubCategory']=$ThirdCateoryArr;
                    }
                    $SubCateoryArr[]=$vv;
                }
                $k['SubCategory']=$SubCateoryArr;
            }
            $mainMenuArr[]=$k;
            //pre($k);die;
        }
        //pre($mainMenuArr);die;
        //return $TopCategoryData;
        return $mainMenuArr;
    }
    
    /*function _global_tidiit_mail($to,$subject,$dataResources,$tempplateName="",$toName=""){
            $message='';
            if($tempplateName==""){
                $message=$dataResources;
            }else{
                $message=  $this->load->view('email_template/'.$tempplateName,$dataResources,TRUE);
            }
            ///echo $message;die;
            //echo $to;die;
            $this->load->library('email');
            $this->email->from("no-reply@tidiit.com", 'Tidiit System Administrator');
            if($toName!="")
                $this->email->to($to,$toName);
            else
                $this->email->to($to);

            $this->email->subject($subject);
            $this->email->message($message);
            $this->email->send();
            echo $this->email->print_debugger();die;
	}*/
}
    
?>