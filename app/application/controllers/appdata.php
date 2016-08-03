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
        $this->load->model('Order_model','order');
        $this->load->model('Country');
    }
    
    function send_reg_id_udid_post(){
        $UDID=trim($this->post('UDID'));
        $registrationId=trim($this->post('registrationId'));
        $deviceToken=trim($this->post('deviceToken'));
        $deviceType=trim($this->post('deviceType'));
        $latitude=trim($this->post('latitude'));
        $longitude=trim($this->post('longitude'));
        $userId=trim($this->post('userId'));
        
        if($userId==""){
            $this->response(array('error' => 'user index should not be blank'), 400);
        }
        
        if($registrationId==""){
            $this->response(array('error' => 'registration id should not be blank'), 400);
        }
        
        if($this->user->check_user_phone_register($userId,$registrationId)==FALSE){
            $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
            $isValideDefaultData=  $this->check_default_data($defaultDataArr);
            
            if($isValideDefaultData['type']=='fail'){
                $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
            }
            $dataArray=array("registrationId"=>$registrationId,'deviceType'=>$deviceType,'UDID'=>$UDID,'deviceToken'=>$deviceToken,
                'latitude'=>$latitude,'longitude'=>$longitude,'addedDate'=>date('Y-m-d H:i:s'),'userId'=>$userId);
            $result=$this->siteconfig->add_app_info($dataArray);
            
            //pre($dataArray);die;
            if($result>0){
                $parram=array('message'=>'1');
                success_response_after_post_get($parram);
            }else{
                $this->response(array('error' => '3'), 400); return FALSE;
            }
        }else{
            $this->response(array('error' => '2'), 400); return FALSE;
        }
    }
    
    function home_get(){
        $timeStamp=$this->get('timestamp');
        $UDID=$this->get('UDID');
        $deviceType=$this->get('deviceType');
        $deviceToken=$this->get('deviceToken');
        $latitude=$this->get('latitude');
        $longitude=$this->get('longitude');
        $this->load->model('Banner_model','banner');
        $this->load->model('Brand_model','brand');
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);

        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        $this->response(array('error' => "App is under developed"), 400); return FALSE;
        $result = array();
        $slider1=$this->banner->get_home_slider(1,TRUE);
        //$slider2=$this->banner->get_home_slider(2,TRUE);
        $noOfItem=  $this->siteconfig->get_value_by_name('MOBILE_APP_HOME_PAGE_SLIDER_ITEM_NO');
        $newArrivalsData=  $this->product->get_recent($noOfItem,TRUE,$latitude,$longitude);
        $bestSellingData=  $this->product->get_best_selling($noOfItem,TRUE,$latitude,$longitude);
        $featuredData=  $this->product->get_featured_products($noOfItem,TRUE,$latitude,$longitude);
        //pre($newArrivalsData);die;
        $result['slider1']=$slider1;
        //$result['slider2']=$slider2;
        $result['category_menu']=$this->get_main_menu();
        $result['best_sellling_item']=$bestSellingData;
        $result['new_arrivals']=$newArrivalsData;
        $result['featured_products']=$featuredData;
        $result['brand']=$this->brand->get_all(TRUE);
        $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        $userId=$this->get('userId');
        if($userId!=""){
            $result['total_cart_item']=$this->user->get_total_cart_item($userId);
        }else{
            $result['total_cart_item']=0;
        }
        //$result['site_product_image_url']=$this->config->item('ProductURL');
        $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($result);
    }
    
    function login_post(){
        $userName=$this->post('userName');
        $password=$this->post('password');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        
        if (!filter_var($userName, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($userName!="" && $password!=""){
            $rs=$this->user->check_login_data($userName,$password,'buyer');
            if(count($rs)>0){
                $this->user->add_login_history(array('userId'=>$rs[0]->userId,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'udid'=>$UDID,'appSource'=>$deviceType));
                $parram=array('userId'=>$rs[0]->userId,'message'=>'You have logedin successfully');
                $parram['total_cart_item']=$this->user->get_total_cart_item($rs[0]->userId);
                success_response_after_post_get($parram);
            }else{$this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;}
        }else{
            $this->response(array('error' => 'Please provide Username,password'), 400);
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
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        if($password==""){
            $this->response(array('error' => 'Please provide password.'), 400); return FALSE;
        }
        
        if($confirmPassword==""){
            $this->response(array('error' => 'Please provide confirm password.'), 400); return FALSE;
        }
        
        if($password!=$confirmPassword){
            $this->response(array('error' => 'Password and confirm password is not matching.'), 400); return FALSE;
        }
        
        if($firstName==""){
            $this->response(array('error' => 'Please provide first name.'), 400); return FALSE;
        }
        
        if($lastName==""){
            $this->response(array('error' => 'Please provide last name.'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
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
        }else{
            $this->response(array('error' => 'Unknown error arises, please try again.'), 400); return FALSE;
        }
    }
    
    function my_profile_get(){
        $result = array();
        $userId=  $this->get('userId');
        if($userId==""):
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        else:
            $userData=$this->user->get_details_by_id($userId,TRUE);
            if(!empty($userData)){
                $userDataDOBArr=  explode('-',$userData[0]['DOB']);
                //pre($userDataDOBArr);die;
                if(count($userDataDOBArr)>0){
                    $userData[0]['DOB']=$userDataDOBArr[2].'-'.$userDataDOBArr[1].'-'.$userDataDOBArr[0];
                    $result['userProfileData']=$userData;
                }
            }else{
                $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
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
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        $aboutMe=$this->post('aboutMe');
        $userId=$this->post('userId');
        
        
        if($firstName==""){
            $this->response(array('error' => 'Please provide first name.'), 400); return FALSE;
        }
        
        if($lastName==""){
            $this->response(array('error' => 'Please provide last name.'), 400); return FALSE;
        }
        
        if($contactNo==""){
            $this->response(array('error' => 'Please provide contact number.'), 400); return FALSE;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        if($mobile==""){
            $this->response(array('error' => 'Please provide mobile number.'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $DOB="00-00-0000";
        
        $dobArr=  explode('-',$rowDOB);
        if(!empty($dobArr)):
            if(array_key_exists('2', $dobArr)){
                if(strlen($dobArr[2])==4):
                    $DOB=$dobArr[2].'-'.$dobArr[1].'-'.$dobArr[0];
                else:
                    $this->response(array('error' => 'Please provide valid year,Year must be 4 character.'), 400); return FALSE;
                endif;
            }
        endif;
        
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        }
        
        if($email!="" && $contactNo!="" && $deviceToken!="" && $deviceType!="" && $UDID!="" && $userId!="" && $mobile!=""):
            $myProfileDataArr=array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$contactNo,
                    'email'=>$email,'DOB'=>$DOB,'mobile'=>$mobile,'fax'=>$fax,'aboutMe'=>$aboutMe);
            $this->user->edit($myProfileDataArr,$userId);
            $result['message']="Profile data updated successfully.";
            if($this->user->is_user_had_group($userId)==FALSE):
                $result['show_manage_group']=1;
            else:    
                $result['show_manage_group']=0;
            endif;
            $result['show']="Profile data updated successfully.";
            success_response_after_post_get($result);
        else:
            $this->response(array('error' => 'All fields are required amd must be filled up.'), 400); return FALSE;
        endif;
    }
    
    function my_buyers_clubs_get(){
        $result = array();
        $userId=  $this->get('userId');
        
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        }
        
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
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        }
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
        
        $deviceType=  $this->post('deviceType');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        if($firstName==""){
            $this->response(array('error' => 'Please provide first name.'), 400); return FALSE;
        }
        
        if($lastName==""){
            $this->response(array('error' => 'Please provide last name.'), 400); return FALSE;
        }
        
        if($countryId==""){
            $this->response(array('error' => 'Please select country.'), 400); return FALSE;
        }
        
        if($cityId==""){
            $this->response(array('error' => 'Please select city.'), 400); return FALSE;
        }
        
        if($zipId==""){
            $this->response(array('error' => 'Please select zip.'), 400); return FALSE;
        }
        
        if($localityId==""){
            $this->response(array('error' => 'Please select location.'), 400); return FALSE;
        }
        
        if($phone==""){
            $this->response(array('error' => 'Please provide phone number.'), 400); return FALSE;
        }
        
        if($address==""){
            $this->response(array('error' => 'Please provide address.'), 400); return FALSE;
        }
        
        if(trim($productTypeId)==""){
            $this->response(array('error' => 'Please select product type.'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        }
        $productTypeIdArr=  explode(',', $productTypeId);
        
        if ($productTypeIdArr[0] == "") { 
            unset($productTypeIdArr[0]); 
        }
        
        if (end($productTypeIdArr) == "") { 
            array_pop($productTypeIdArr); 
        }
        if(empty($productTypeIdArr)){
            $this->response(array('error' => 'Please provide prodcut type for current user.'), 400); return FALSE;
        }
        
        foreach ($productTypeIdArr AS $k =>$v){
            $rs=$this->db->from('category')->where('categoryId',$v)->get()->result_array();
            if(count($rs)==0):
                $this->response(array('error' => 'Invalid product type id.'), 400); return FALSE;
            endif;
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

        $isAdded=$this->user->is_shipping_address_added($userId);
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
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        }
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
        $deviceType=  $this->post('deviceType');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
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
        
        $deviceType=  $this->post('deviceType');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        
        if($oldPassword==""){
            $this->response(array('error' => 'Please provide old password.'), 400); return FALSE;
        }
        
        if($newPassword==""){
           $this->response(array('error' => 'Please provide new $password.'), 400); return FALSE;
        }
        
        if($newPassword!=$newConfirmPassword){
            $this->response(array('error' => 'New password and new confirm password are must same.'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($userId==""):
            $this->response(array('error' => 'Please provide valid user data.'), 400); return FALSE;
        else:
            if($newPassword==$newConfirmPassword):
                $user=$this->user->get_details_by_id($userId);
                if(empty($user)){
                    $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
                }
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
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($userId =="" && $mpesaFullName=="" && $mpesaAccount ==""):
            $this->response(array('error' => 'Please provide all data.'), 400); return FALSE;
        else:
            $user=$this->user->get_details_by_id($userId);
            if(empty($user)){
                $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
            }
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
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $notify = array();
        
        if($groupAdminId==""){
            $this->response(array('error' => 'Please provide logined user index'), 400); return FALSE;
        }
        
        if($groupTitle==""){
            $this->response(array('error' => 'Please provide group title'), 400); return FALSE;
        }
        
        if(trim($productType)==""){
            $this->response(array('error' => 'Please provide product category for group'), 400); return FALSE;
        }
        
        if(count($groupUsersArr)==0){
            $this->response(array('error' => 'Please select atleast one user to create the group['.$groupTitle.']'), 400); return FALSE;
        }   
        
        if($this->user->group_title_exists($groupTitle)){
            $this->response(array('error' => $groupTitle.' is already created by some one,Your "Buyinb Club" Name must be unique.'), 400); return FALSE;
        }
        
        $userDetails=$this->user->get_details_by_id($groupAdminId);
        if(count($userDetails)==0){
            $this->response(array('error' => 'Invalid grop admin id'), 400); return FALSE;
        }
        
        if ($groupUsersArr[0] == "") { 
            unset($groupUsersArr[0]); 
        }
        
        if (end($groupUsersArr) == "") { 
            array_pop($groupUsersArr); 
        }
        
        $productTypeDetails=$this->category->get_details_by_id($productType);
        if(count($productTypeDetails)==0 || $productTypeDetails[0]->parrentCategoryId==0){
            $this->response(array('error' => 'Invalid product Type id'), 400); return FALSE;
        }
        foreach($groupUsersArr AS $k => $v){
            $userDetails=$this->user->get_details_by_id($v);
            if(count($userDetails)==0){
                $this->response(array('error' => 'Invalid user id provided for group cration'), 400); return FALSE;
            }
        }
        $groupUsers=implode(',', $groupUsersArr);
        $rs=$this->user->is_all_users_exists_for_group_by_admin_id($groupAdminId,$groupUsers);
        if($rs!=FALSE){
            $this->response(array('error' => 'All users are already attached with "Buying Club"['.$rs->groupTitle.'],Instead of create another Buying Club please use exist one.'), 400); return FALSE;
        }
        
        $groupDataArr=array('groupAdminId'=>$groupAdminId,'groupTitle'=>$groupTitle,'productType'=>$productType,'groupUsers'=>$groupUsers,'groupColor'=>$groupColor,'appSource'=>$deviceType);
        $groupId = $this->user->group_add($groupDataArr);
        $adminDataArr=  $this->user->get_details_by_id($groupAdminId);
        //pre($adminDataArr);die;
        
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
    
    function show_edit_group_post(){
        $adminId=$this->post('userId');
        $groupId=$this->post('groupId');
        
        $deviceType=  $this->post('deviceType');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $user=  $this->user->get_details_by_id($adminId);
        if(empty($user)){
            $this->response(array('error' => 'Invalid user index. Please try again!'), 400); return FALSE;
        }
        $isExist=$this->user->is_group_exist_group_id_admin_id($groupId,$adminId);
        if(count($isExist)==0){
            $this->response(array('error' => 'Profided buying club index is not match with buying club leadr index. Please provide correct index!'), 400); return FALSE;
        }
        $group = $this->user->get_group_by_id($groupId,TRUE);
        if(!$group):
            $this->response(array('error' => 'Invalid Buying Club index. Please try again!'), 400); return FALSE;
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
        $result['user']=$user;
        success_response_after_post_get($result);
    }
    
    function show_edit_group_for_reorder_post(){
        $adminId=$this->post('userId');
        $orderId=$this->post('orderId');
        $order=$this->order->details($orderId,TRUE);
        if(empty($order)){
            $this->response(array('error' => 'Invalid parrent order index provided,so unable to process it for reorder.'), 400); return FALSE;
        }
        if($order[0]['groupId']==0){
            $this->response(array('error' => 'This parrent order has no has buying club,so unable to process it for reorder.'), 400); return FALSE;
        }
        $groupId=$order[0]['groupId'];
        $deviceType=  $this->post('deviceType');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $userDetails=  $this->user->get_details_by_id($adminId);
        if(empty($userDetails)){
            $this->response(array('error' => 'Invalid Buying Club index. Please try again!'), 400); return FALSE;
        }
        $isExist=$this->user->is_group_exist_group_id_admin_id($groupId,$adminId);
        if(count($isExist)==0){
            $this->response(array('error' => 'Profided buying club index is not match with buying club leadr index. Please provide correct index!'), 400); return FALSE;
        }
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
        $result['groupId']=$groupId;
        $result['orderId']=$orderId;
        $result['reorder'] = 1;
        $result['user']=$user;
        success_response_after_post_get($result);
    }
    
    function edit_group_post(){
        $groupId = $this->post('groupId');
        $adminId = $this->post('userId');
        $groupTitle = $this->post('groupTitle');
        $productType=$this->post('productType');
        $groupUsers=$this->post('groupUsers');
        
        $deviceType=  $this->post('deviceType');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($productType==""){
            $this->response(array('error' => 'Please provide product type.'), 400); return FALSE;
        }
        
        if($groupTitle==""){
            $this->response(array('error' => 'Please provide buying club title.'), 400); return FALSE;
        }
        
        if($groupId==""){
            $this->response(array('error' => 'Please provide buying club index.'), 400); return FALSE;
        }
        
        if($adminId==""){
            $this->response(array('error' => 'Please provide user index.'), 400); return FALSE;
        }
        
        $groupUsersArr=  explode(',', $groupUsers);
        if(count($groupUsersArr)==0){
            $this->response(array('error' => 'Please provide atleast oen member for group'), 400); return FALSE;
        }
        
        if($this->user->group_title_exists_edit($groupTitle,$groupId)){
            $this->response(array('error' => $groupTitle.' is already created by some one,Your "Buying Club" Name must be unique.'), 400); return FALSE;
        }
        
        $userDetails=$this->user->get_details_by_id($adminId);
        if(count($userDetails)==0){
            $this->response(array('error' => 'Invalid grop admin index'), 400); return FALSE;
        }
        
        $colors = array('red','maroon','purple','green','blue');
        $rand_keys = array_rand($colors, 1);
        $groupColor = $colors[$rand_keys];
        
        if(!$groupUsersArr):
            $this->response(array('error' => 'Please select the at least one Buying Club member!'), 400); return FALSE;
        endif;
        
        if ($groupUsersArr[0] == "") { 
            unset($groupUsersArr[0]); 
        }
        
        if (end($groupUsersArr) == "") { 
            array_pop($groupUsersArr); 
        }
        
        $productTypeDetails=$this->category->get_details_by_id($productType);
        if(count($productTypeDetails)==0 || $productTypeDetails[0]->parrentCategoryId==0){
            $this->response(array('error' => 'Invalid product Type id'), 400); return FALSE;
        }
        
        foreach($groupUsersArr AS $k => $v){
            $userDetails=$this->user->get_details_by_id($v);
            if(count($userDetails)==0){
                $this->response(array('error' => 'Invalid user id provided for group cration'), 400); return FALSE;
            }
        }
        
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
        
        $groupIdUpdate = $this->user->group_update(array('groupTitle'=>$groupTitle,'groupUsers'=>$groupUsers,'groupColor'=>$groupColor,'productType'=>$productType), $groupId);
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
                $notify['appSource'] = $deviceType;
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
                $notify['appSource'] = $deviceType;
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
                $notify['appSource'] = $deviceType;
                $this->send_notification($notify);
            endforeach;
            success_response_after_post_get(array('message'=>'Selected group data updated successfully.'));
        else:    
            $this->response(array('error' => 'Some error happen. Please try again!'), 400); return FALSE;
        endif;
    }
    
    function edit_group_for_reorder_post(){
        $groupId = $this->post('groupId');
        $adminId = $this->post('userId');
        $orderId = $this->post('orderId');
        $groupTitle = $this->post('groupTitle');
        $productType=$this->post('productType');
        $groupUsers=$this->post('groupUsers');
                
        $deviceType=  $this->post('deviceType');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        
        if($adminId==""){
            $this->response(array('error' => 'Please provide user index.'), 400); return FALSE;
        }
        
        if($groupId==""){
            $this->response(array('error' => 'Please provide buying club index.'), 400); return FALSE;
        }
        
        if($groupTitle==""){
            $this->response(array('error' => 'Please provide buying club title.'), 400); return FALSE;
        }
        
        if($productType=="" || $groupUsers==""){
            $this->response(array('error' => 'Please provide product type.'), 400); return FALSE;
        }
        
        if($groupUsers==""){
            $this->response(array('error' => 'Please provide group user.'), 400); return FALSE;
        }
        
        $groupUsersArr=  explode(',', $groupUsers);
        if(count($groupUsersArr)==0){
            $this->response(array('error' => 'Please provide atleast oen member for group'), 400); return FALSE;
        }
        
        if($this->user->group_title_exists_edit($groupTitle,$groupId)){
            $this->response(array('error' => $groupTitle.' is already created by some one,Your "Buying Club" Name must be unique.'), 400); return FALSE;
        }
        
        $userDetails=$this->user->get_details_by_id($adminId);
        if(count($userDetails)==0){
            $this->response(array('error' => 'Invalid grop admin index'), 400); return FALSE;
        }
        
        $colors = array('red','maroon','purple','green','blue');
        $rand_keys = array_rand($colors, 1);
        $groupColor = $colors[$rand_keys];
        
        if(!$groupUsersArr):
            $this->response(array('error' => 'Please select the at least one Buying Club member!'), 400); return FALSE;
        endif;
        
        if ($groupUsersArr[0] == "") { 
            unset($groupUsersArr[0]); 
        }
        
        if (end($groupUsersArr) == "") { 
            array_pop($groupUsersArr); 
        }
        
        $productTypeDetails=$this->category->get_details_by_id($productType);
        if(count($productTypeDetails)==0 || $productTypeDetails[0]->parrentCategoryId==0){
            $this->response(array('error' => 'Invalid product Type id'), 400); return FALSE;
        }
        
        foreach($groupUsersArr AS $k => $v){
            $userDetails=$this->user->get_details_by_id($v);
            if(count($userDetails)==0){
                $this->response(array('error' => 'Invalid user id provided for group cration'), 400); return FALSE;
            }
        }
        
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
                $notify['appSource'] = $deviceType;
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
                $notify['appSource'] = $deviceType;
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
                $notify['appSource'] = $deviceType;
                $this->send_notification($notify);
            endforeach;
            
            $this->load->model('Order_model','order');
            $orderId = $this->post('orderId');
            $order = $this->order->get_single_order_by_id($orderId);
            $pro = $this->product->details($order->productId);
            $orderinfo['pdetail'] = $pro[0];
            $orderinfo['priceinfo'] = $this->product->get_products_price_details_by_id($order->productPriceId);
            
            $group = $this->user->get_group_by_id($groupId);
            $mail_template_data=array();
            $mail_template_view_data=load_default_resources();
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
                $data['nMessage'] .= "<a href='".$mail_template_view_data['MainSiteBaseURL']."shopping/group-order-decline/".base64_encode($orderId*226201)."' class='btn btn-danger btn-lg'>Decline</a>  or <a href='".$mail_template_view_data['MainSiteBaseURL']."shopping/group-re-order-accept-process/".base64_encode($orderId*226201)."/".base64_encode(100)."' class='btn btn-success btn-lg'>Accept</a><br>";
                $mail_template_data['TEMPLATE_GROUP_RE_ORDER_START_ORDER_ID1']=$orderId;
                $data['nMessage'] .= "Thanks <br> Tidiit Team.";
                $data['orderId'] =$orderId;
                $data['productId'] =$orderinfo['priceinfo']->productId;
                $data['productPriceId'] =$orderinfo['priceinfo']->productPriceId;

                $data['isRead'] = 0;
                $data['status'] = 1;
                $data['createDate'] = date('Y-m-d H:i:s');
                $data['adminName'] = $adminDataArr[0]->firstName.' '.$adminDataArr[0]->lastName;
                $data['adminEmail'] = $adminDataArr[0]->email;
                $data['adminContactNo'] = $adminDataArr[0]->contactNo;
                $data['appSource'] = $deviceType;

                //Send Email message
                $recv_email = $usr->email;
                $sender_email = $group->admin->email;
                /// firing mail

                $mail_template_view_data['group_order_re_start']=$mail_template_data;
                global_tidiit_mail($recv_email, "Buying Club Order Re-Invitation at Tidiit Inc Ltd", $mail_template_view_data,'group_order_re_start');

                $this->user->notification_add($data);
                
                $receiverDetails=$this->user->get_details_by_id($usr->userId);
                $notify['senderId'] = $adminId;
                $notify['receiverId'] = $usr->userId;
                $notify['nType'] = "BUYING-CLUB-ORDER";
                $notify['receiverMobileNumber'] = $receiverDetails[0]->mobile;
                $notify['senderMobileNumber'] = $adminDataArr[0]->mobile;
                $notify['nTitle'] = 'Buying Club Re-order';
                $notify['adminName'] = $adminDataArr[0]->firstName.' '.$adminDataArr[0]->lastName;
                $notify['adminEmail'] = $adminDataArr[0]->email;
                $notify['adminContactNo'] = $adminDataArr[0]->contactNo;
                $notify['appSource'] = $deviceType;
                $notify['orderId'] = $orderId;
                $notify['nMessage'] = 'Buying Club Re-order [TIDIIT-OD'.$order->orderId.'] running by <b>'.$group->admin->firstName.' '.$group->admin->lastName.'</b>';;;
                 
               $this->send_notification($notify);
            endforeach;
            
            success_response_after_post_get(array('message'=>'Selected group data updated successfully.'));
        else:    
            $this->response(array('error' => 'Some error happen. Please try again!'), 400); return FALSE;
        endif;
    }
    
    function delete_group_post(){
        $groupId=  $this->post('groupId');
        $userId=  $this->post('userId');
        
        $deviceType=  $this->post('deviceType');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $user=$this->user->get_details_by_id($userId);
        if(empty($user)){
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        }
        
        if($this->user->is_valid_admin_for_group($groupId,$userId)==FALSE){
            $this->response(array('error' => 'You are not leader of the selected buuying club,So not allow for delete the selected buying club.'), 400); return FALSE;
        }        
        
        $isBuyingClubHasOrder=$this->order->buying_club_has_order($groupId);
        if($isBuyingClubHasOrder==FALSE):
            $this->user->group_delete($groupId);
            success_response_after_post_get(array('message'=>'Selected group deleted successfully.'));
        else:    
            $this->response(array('error' => 'Your selected buying lcub already linked with several order.So it is not allow for delete!'), 400); return FALSE;
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
        //echo $localityId.' '.$userId.' '.$productTypeId;die;
        $result=array();
        $result['localityArr']=  $this->user->get_all_users_by_product_type_locality($productTypeId,$localityId,$userId);
        $result['ajaxType']='yes';
        success_response_after_post_get($result);
    }
    
    function my_orders_post(){
        $userId=$this->post('userId');
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($userId==""){
            $this->response(array('error' => 'Invalid user id. Please try again!'), 400); return FALSE;
        }
        
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Invalid user index. Please try again!'), 400); return FALSE;
        }
        $result=array();
        $myOrders=$this->order->get_my_all_orders_with_parent_app($userId);
        $myOrdersArr=array();
        foreach($myOrders AS $k){
            $k['orderDate']=date('d-m-Y H:i:s',  strtotime($k['orderDate']));
            $k['orderUpdatedate']=date('d-m-Y H:i:s',  strtotime($k['orderUpdatedate']));
            $k['cancelDate']=date('d-m-Y H:i:s',  strtotime($k['cancelDate']));
            $myOrdersArr[]=$k;
        }
        //pre($myOrdersArr);die;
        $result['my_orders']=$myOrdersArr;
        $result['order_state_data']=$this->order->get_state(true);
        $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($result);
    }
    
    function my_notifications_post(){
        $userId=$this->post('userId');
        if($userId==""){
            $this->response(array('error' => 'Invalid user index. Please try again!'), 400); return FALSE;
        }
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Invalid user id. Please try again!'), 400); return FALSE;
        }
        $result=array();
        $myNoticationsNew=array();
        $myNotications=$this->user->notification_all_my_app($userId);
        foreach($myNotications AS $k){
            $k['nTitle']=  strip_tags($k['nTitle']);
            $k['createDate']=  date('d-m-Y H:i:s',  strtotime($k['createDate']));
            $myNoticationsNew[]=$k;
        }
        //pre($myNoticationsNew);die;
        $result['my_notications']=$myNoticationsNew;
        success_response_after_post_get($result);
    }
    
    function my_notification_details_post(){
        $this->load->model('Product_model','product');
        $userId=$this->post('userId');
        $notificationId=$this->post('notificationId');
        
        /*$latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }*/
        
        if($userId==""){
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        }
        
        if($notificationId==""){
            $this->response(array('error' => 'Please provide valid notification index.'), 400); return FALSE;
        }
        
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Invalid user id. Please try again!'), 400); return FALSE;
        }
        $details=$this->user->notification_single($notificationId,TRUE);
        //pre($details);die;
        if(!$details){
            $this->response(array('error' => 'Invalid notification id. Please try again!'), 400); return FALSE;
        }
        
        $result=array();
        if($details['nType']=='BUYING-CLUB-ORDER' || $details['nType']=='BUYING-CLUB-ORDER-DECLINE'){
            //$orderId=$details['orderId'];
            $prodctImageArr=$this->product->get_products_images($details['productId']);
            $prodctDetails=$this->product->details($details['productId']);
            $prodctPriceDetails=$this->product->get_products_price_details_by_id($details['productPriceId']);
            //pre($prodctPriceDetails);die;
            $result['image']=$prodctImageArr[0]->image;
            $result['title']=$prodctDetails[0]->title;
            $result['productPrice']=$prodctPriceDetails->price;
            $result['productQty']=$prodctPriceDetails->qty;
            $result['notificationTitle']=  strip_tags($details['nTitle']);
            $result['orderId']=  $details['orderId'];
            $result['productId']=  $details['productId'];
            $result['productPriceId']=  $details['productPriceId'];
            if($details['nType']=='BUYING-CLUB-ORDER'):
                $tempArr=  explode('[',$details['nMessage']);
                if(count($tempArr)>1){
                    $tempArr1= explode(']',$tempArr[1]);
                    $result['clubTitle']=$tempArr1[0];
                }else{
                    $result['clubTitle']='';
                }
            endif;
        }else{
            $result['notications_details']=$details;
        }
        //pre($details);die;
        $cond['id'] = $notificationId;
        $setdata['isRead'] = 1;
        $this->user->notification_update($cond, $setdata);
        
        if($details['acceptDeclineState']==0){
            $result['accepted']='no';
            $result['declined']='no';
        }else if($details['acceptDeclineState']==1){
            $result['accepted']='yes';
            $result['declined']='no';
        }else if($details['acceptDeclineState']==2){
            $result['accepted']='no';
            $result['declined']='yes';
        }
        //pre($result);die;
        success_response_after_post_get($result);
    }
    
    function delete_my_notification_post(){
        $userId=  $this->post('userId');
        $notificationId=  $this->post('notificationId');
        if($userId=="" || $notificationId==""){
            $this->response(array('error' => 'Invalid user index and notification index. Please try again!'), 400); return FALSE;
        }
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Invalid user id. Please try again!'), 400); return FALSE;
        }
        $details=$this->user->notification_single($notificationId,TRUE);
        //pre($details);die;
        if(!$details){
            $this->response(array('error' => 'Invalid notification id. Please try again!'), 400); return FALSE;
        }
        
        if($details[0]['isRead']==1){
            $this->response(array('error' => 'Please read the notification before delete it'), 400); return FALSE;
        }
        $this->user->notification_delete($notificationId);
        $result=array();
        $result['message']="Selected notification deleted successfully";
        success_response_after_post_get($result);
    }
    
    function my_order_details_post(){
        $orderId=$this->post('orderId');
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $result=array();
        $orderDetails=$this->order->details($orderId,TRUE);
        if(empty($orderDetails)){
            $this->response(array('error' => 'Invalid order index. Please try again!'), 400); return FALSE;
        }
        //pre($orderDetails);die;
        $orderInfo=unserialize(base64_decode($orderDetails[0]['orderInfo']));
        $orderInfo = json_decode(json_encode($orderInfo), true);
        //pre($orderInfo);die;
        $orderDetails[0]['orderDate']=  date('d-m-Y H:i:s',strtotime($orderDetails[0]['orderDate']));
        $orderDetails[0]['orderUpdatedate']=  date('d-m-Y H:i:s',strtotime($orderDetails[0]['orderUpdatedate']));
        $orderDetails[0]['cancelDate']=  date('d-m-Y H:i:s',strtotime($orderDetails[0]['cancelDate']));
        $orderDetails[0]['productImage']=  $orderInfo['pimage']['image'];
        $orderDetails[0]['productTitle']=  $orderInfo['pdetail']['title'];
        //pre($orderDetails);die;
        $result['orderDetails']=  $orderDetails;
        $result['orderInfo']=  $orderInfo;
        $result['order_state_data']=$this->order->get_state(true);
        $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($result);
    }
    
    function track_order_post(){
        $orderId=  $this->post('orderId');
        $userId=  $this->post('userId');
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $result=array();
        $orderDetails=$this->order->details($orderId,TRUE);
        if(empty($orderDetails)){
            $this->response(array('error' => 'Invalid order index. Please try again!'), 400); return FALSE;
        }
        
        $userDetails=$this->user->get_details_by_id($userId);
        if(empty($userDetails)){
            $this->response(array('error' => 'Invalid user index. Please try again!'), 400); return FALSE;
        }
        
        //pre($orderDetails);die;
        $orderInfo=unserialize(base64_decode($orderDetails[0]['orderInfo']));
        $orderInfo = json_decode(json_encode($orderInfo), true);
        //pre($orderInfo);die;
        $orderDetails[0]['orderDate']=  date('d-m-Y H:i:s',strtotime($orderDetails[0]['orderDate']));
        $orderDetails[0]['orderUpdatedate']=  date('d-m-Y H:i:s',strtotime($orderDetails[0]['orderUpdatedate']));
        $orderDetails[0]['cancelDate']=  date('d-m-Y H:i:s',strtotime($orderDetails[0]['cancelDate']));
        $orderDetails[0]['productImage']=  $orderInfo['pimage']['image'];
        $orderDetails[0]['productTitle']=  $orderInfo['pdetail']['title'];
        //pre($orderDetails);die;
        $result['orderDetails']=  $orderDetails;
        $result['orderInfo']=  $orderInfo;
        $result['order_state_data']=$this->order->get_state(true);
        $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($result);
    }
   
    function show_cancel_my_order_post(){
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $userId=  $this->post('userId');
        $orderId=  $this->post('orderId');
        $userDetails=  $this->user->get_details_by_id($userId);
        if(empty($userDetails)){
            $this->response(array('error' => 'Invalid user index. Please try again!'), 400); return FALSE;
        }
        $orderDetails=$this->order->details($orderId,TRUE);
        if(empty($orderDetails)){
            $this->response(array('error' => 'Invalid order index. Please try again!'), 400); return FALSE;
        }
       $result=array();
       $result['orderDetails']=$orderDetails;
       $orderInfo=unserialize(base64_decode($orderDetails[0]['orderInfo']));
       $orderInfo = json_decode(json_encode($orderInfo), true);
       $result['productImage']=  $orderInfo['pimage']['image'];
       $result['productTitle']=  $orderInfo['pdetail']['title'];
       $cancelResean=array(
           'Not_interested_anymore'=>'Not interested anymore','Order_delivery_delayed'=>'Order delivery delayed',
           'Order_duplicate_items_by_mistake'=>'Order duplicate items by mistake','Purchased_wrong_item'=>'Purchased wrong item',
           'Other_Reasons'=>'Other Reasons');
       $result['cancelResean']=$cancelResean;
       success_response_after_post_get($result);
    }
    
    function cancel_my_order_post(){
        $userId=  $this->post('userId');
        $orderId=  $this->post('orderId');
        $reason=  $this->post('reason');
        $other_reason=  $this->post('other_reason');
        $comments=  $this->post('comments');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=  $this->post('deviceType');
        $UDID=  $this->post('UDID');
        $deviceToken=  $this->post('deviceToken');
        
        $userDetails=  $this->user->get_details_by_id($userId);
        if(empty($userDetails)){
            $this->response(array('error' => 'Invalid user index. Please try again!'), 400); return FALSE;
        }
        $order      = $this->order->get_single_order_by_id($orderId);
        if(empty($order)){
            $this->response(array('error' => 'Invalid order index. Please try again!'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($reason=="" && $other_reason==""){
            $this->response(array('error' => 'Please provide valid reason for cancelation of the selected order!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        //$countryShortName='IN';
        if($countryShortName==FALSE){
            $this->response(array('error' => 'Please provide valid latitude and longitude!'), 400); return FALSE;
        }
        $adminMailData= load_default_resources();
        
        $this->order->update(array('status'=> 7), $orderId);
        $this->product->update_product_quantity($order->productId,$order->productQty,'+');
        
        $this->single_order_cancel_mail($order,$reason,$comments);
        $sms_data_msg='Your cancelation request for Tidiit Order TIDIIT-OD-'.$orderId.' has placed successfully.';
        if($reason != "Other Reasons"){
            $sms_data_msg .='Cancelation request due to "'.$reason.'".';
        }else{
            $sms_data_msg .='Cancelation request due to "'.$comments.'".';
        }
        $sms_data_msg.='More details about this notifiaction,Check '.$adminMailData['MainSiteBaseURL'];
        
        $sms_data=array('nMessage'=>$sms_data_msg,'receiverMobileNumber'=>$userDetails[0]->mobile,'senderId'=>'','receiverId'=>$userDetails[0]->userId,
        'senderMobileNumber'=>'','nType'=>"SINGLE-ORDER-CANCEL-BUYER");
        send_sms_notification($sms_data);
        
        $result['cancelResean']="Selected order has canceled successfully.";
        success_response_after_post_get($result);
    }
    
    function help_topic_get(){
        $this->load->model('Help_model','help');
        $get_all_active_topic=$this->help->get_all_active_topic(TRUE);
        //$get_help_topics_data=$this->Help_model->get_topic_details_by_id($get_all_active_topic[0]->helpTopicsId);
        
        //pre($get_all_active_topic);die;
        $data['helpTopicsArr']=$get_all_active_topic;
        //$data['helpDataArr']=$get_help_topics_data;
        success_response_after_post_get($data);
    }
    
    function help_details_post(){
        $this->load->model('Help_model','help');
        $helpTopicsId=  $this->post('helpTopicsId');
        $get_help_topics_data=$this->help->get_topic_details_by_id($helpTopicsId,TRUE);
        $data['helpDataArr']=$get_help_topics_data;
        success_response_after_post_get($data);
    }
    
    function help_post(){
        $name=  $this->post('name');
        $email=  $this->post('email');
        $phone=  $this->post('phone');
        $subject=  $this->post('subject');
        $message=  $this->post('message');
        $deviceType=  $this->post('deviceType');
        $deviceToken=  $this->post('deviceToken');
        $UDID=  $this->post('UDID');
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($name=="" || $email=="" || $phone=="" || $subject=="" || $message==""){
            $this->response(array('error' => 'Please provide name,phone,subject,message.'), 400); return FALSE;
        }
        
        $dataArr=array('email'=>$email,'name'=>$name,'phone'=>$phone,'subject'=>$subject,'message'=>$message,'deviceToken'=>$deviceToken,
            'deviceType'=>$deviceType,'UDID'=>$UDID,'latitude'=>$latitude,'longitude'=>$longitude);
        $newId=  $this->user->post_help($dataArr);
        
        $result=array();
        if($newId>0){
            $result['message']="Your request received successfully,One of our representative will contacts you shortly.";
        }else{
            $result['message']="Unknown error arise to submit help request,Please try again..";
        }
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
            case '':
                
                
        }
        
        $data['isRead'] = 0;
        $data['status'] = 1;
        
        if($data['isMobMessage']):
            $smsData['nMessage']=  str_replace('<br />','', $data['nMessage']);
            $smsData['nMessage']=  str_replace('<br>','', $smsData['nMessage']);
            $smsData['nMessage']=  str_replace('<br/>','', $smsData['nMessage']);
            $smsData['nMessage']=  str_replace('<strong>','', $smsData['nMessage']);
            $smsData['nMessage']=  str_replace('</strong>','', $smsData['nMessage']);
            $smsData['receiverMobileNumber']=$data['receiverMobileNumber'];
            if(array_key_exists('senderId', $smsData)){
                $smsData['senderId']=$data['senderId'];
            }else{
                $smsData['senderId']=0;
            }
            if(array_key_exists('receiverId', $smsData)){
                $smsData['receiverId']=$data['receiverId'];
            }else{
                $smsData['receiverId']=0;
            }
            
            if(array_key_exists('senderMobileNumber', $smsData)){
                $smsData['senderMobileNumber']=$data['senderMobileNumber'];
            }else{
                $smsData['senderMobileNumber']=0;
            }
            $smsData['nType']=$data['nType'];
            
            send_sms_notification($smsData);
            if(array_key_exists('receiverId', $smsData)){
                send_push_notification($smsData);
            }
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
        if(array_key_exists('orderId', $data))
            unset($data['orderId']);
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
    
    function single_order_cancel_mail($order,$reason,$comments=""){
        $orderId=$order->orderId;
        $this->load->model('Order_model');
        $orderDetails=array();
        $orderDetails[]=$order;  
        
        //pre($orderDetails);die;
        $adminMailData=  load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $adminMailData['reason']=$reason;
        $adminMailData['comments']=$comments;
        $adminMailData['orderId']=$orderId;
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfoDataArr);die;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        $sellerFullName=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['sellerFullName']=$sellerFullName;
        $buyerFullName=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $adminMailData['buyerFullName']=$buyerFullName;
        
        // for buyer
        global_tidiit_mail($orderDetails[0]->buyerEmail,'Your Tidiit order TIDIIT-OD-'.$order->orderId.' has canceled successfully',$adminMailData,'single_order_canceled',$buyerFullName);
                
        /// for seller
        global_tidiit_mail($orderDetails[0]->sellerEmail, "Tidiit order TIDIIT-OD-".$order->orderId.' has canceled by '.$buyerFullName, $adminMailData,'seller_single_order_canceled',$sellerFullName);

        /// for support
        $adminMailData['userFullName']='Tidiit Inc Support';
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        global_tidiit_mail($supportEmail, "Tidiit Order TIDIIT-OD-".$order->orderId.' has canceled by '.$buyerFullName, $adminMailData,'support_single_order_canceled','Tidiit Inc Support');
        return TRUE;
    }
    
    function my_wish_list_post(){
        $userId=  $this->post('userId');
        
        /*$latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }*/
        
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        $allItemArr=$this->order->get_all_item_in_with_list($userId,TRUE);
        //pre($newAllItemArr);die;
        $result=array();
        $result['allItemArr']=$allItemArr;
        success_response_after_post_get($result);
    }
  
    function remove_from_wish_list_post(){
        $userId=  $this->post('userId');
        $wishListId=  $this->post('wishListId');
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $deviceType = $this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if($userId=="" || $wishListId == ""){
            $this->response(array('error' => 'Please provide user index,wish list index !'), 400); return FALSE;
        }
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $rs=$this->user->get_details_by_id($userId);
        if(empty($rs)){
            $this->response(array('error' => 'Please provide valid user index!'), 400); return FALSE;
        }
        
        $wishListDetails=$this->order->get_wishlist_by_id($wishListId,$userId);
        //pre($wishListDetails);die;
        if(empty($wishListDetails)){
            $this->response(array('error' => 'Please provide valid wish list index!'), 400); return FALSE;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($latitude, $longitude);
        if($countryShortName){
            ///
        }
        $this->order->remove_from_wish_list($wishListId,$userId);
        $result=array();
        $result['message']="Selected item removed from wish list successfully";
        success_response_after_post_get($result);
    }
    
    function get_total_item_in_cart_get(){
        $userId=$this->get('userId');
        if($userId!=""){
            $result['total_cart_item']=$this->user->get_total_cart_item($userId);
        }else{
            $result['total_cart_item']=0;
        }
        header('Content-type: application/json');
        echo json_encode($result);
    }
    
    function check_default_data($dataArr){
        $validateArr=array('type'=>'success');
        if($dataArr['UDID']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide UDID.';
            return $validateArr;
        }
        
        if($dataArr['deviceToken']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide deviceToken.';
            return $validateArr;
        }
        
        if($dataArr['deviceType']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide deviceType.';
            return $validateArr;
        }
        
        if($dataArr['latitude']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide latitude.';
            return $validateArr;
        }
        
        if($dataArr['longitude']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide longitude.';
            return $validateArr;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($dataArr['latitude'], $dataArr['longitude']);
        //die($countryShortName);
        if($countryShortName==FALSE){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide valid latitude and longitude';
            return $validateArr;
        }
        return $validateArr;
    }
    
    function testing_push_notification_post(){
        $regId=$this->post('regId');
        $message=$this->post('message');
        
        $fields=array();
        $regIdArr=array();
        $regIdArr[]=$regId;
        $fields=array('registration_ids'=>$regIdArr,'data' =>array('message'=>$message));
        $this->load->config('product');
        //@mail('judhisahoo@gmail.com','make ready for GoogleGSMKEY for message to GSM server ','make ready for GoogleGSMKEY for message to GSM server ');
        $GOOGLE_API_KEY=$this->config->item('GoogleGSMKEY');
        @mail('judhisahoo@gmail.com','GSM API Key is '.$GOOGLE_API_KEY,'GSM API Key is '.$GOOGLE_API_KEY);
        $url = 'https://android.googleapis.com/gcm/send';

        $headers = array(
            'Authorization: key=' .$GOOGLE_API_KEY ,
            'Content-Type: application/json'
        );
        @mail('judhisahoo@gmail.com','Opening conection for google GSM server ','Opening conection for google GSM server ');
        // Open connection
        $ch = curl_init();
        @mail('judhisahoo@gmail.com','reg ids are - '.json_encode($fields),'reg ids are - '.json_encode($fields));
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);
        $result=array();
        if ($result === FALSE) {
            @mail('judhisahoo@gmail.com','Google GSM server return fail','Google GSM server return fail');
            //die('Curl failed: ' . curl_error($ch));
            $result['message']=curl_error($ch);
        }else{
            @mail('judhisahoo@gmail.com','Google GSM server return success','Google GSM server return success');
            $result['message']='Google GSM server return success';
        }
        success_response_after_post_get($result);
    }
}