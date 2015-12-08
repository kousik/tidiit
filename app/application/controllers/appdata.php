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
    }
    
    function testservice_get(){
        $testid = "";
        if($this->get('testid'))
        	$testid = $this->get('testid');
        
        $this->response(array("test ID" => $testid), 200);
    }

    function testpostservice_post(){
        $testid = "";
        if($this->post('testid'))
            $testid = $this->post('testid');
        
        $this->response(array("test ID" => $testid), 200);
    }
    
    function home_get(){
        $timeStamp=$this->get('timestamp');
        if(!isValidTimeStamp($timeStamp)){
            $this->response(array('error' => 'Invalid timestamp'), 400);
        }else{
            $this->load->model('Banner_model','banner');
            $this->load->model('Brand_model','brand');
            $result = array();
            $result=  $this->get_default_urls();
            $slider1=$this->banner->get_home_slider(1,TRUE);
            //$slider2=$this->banner->get_home_slider(2,TRUE);
            $noOfItem=  $this->siteconfig->get_value_by_name('MOBILE_APP_HOME_PAGE_SLIDER_ITEM_NO');
            $newArrivalsData=  $this->product->get_recent($noOfItem,TRUE);
            
            $result['slider1']=$slider1;
            //$result['slider2']=$slider2;
            $result['category_menu']=$this->get_main_menu();
            $result['best_sellling_item']=$newArrivalsData;
            $result['new_arrivals']=$newArrivalsData;
            $result['featured_products']=$newArrivalsData;
            $result['brand']=$this->brand->get_all(TRUE);
            //$result['site_product_image_url']=$this->config->item('ProductURL');
            
            
            $result['timestamp'] = (string)mktime();
            header('Content-type: application/json');
            echo json_encode($result);
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
                $this->user->add_login_history(array('userId'=>$rs[0]->userId,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'udid'=>$UDID));
                header('Content-type: application/json');
                echo json_encode(array('userId' => $rs[0]->userId));
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
                'email'=>$email,'userResources'=>'app','userType'=>'buyer','status'=>1);
        $userId=$this->user->add($dataArr);
        
        if($userId!=""){               
            if($this->user->is_already_subscribe($email)==FALSE){
                $this->user->subscribe($email);
            }
            $this->user->add_login_history(array('userId'=>$userId,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'udid'=>$UDID));
            header('Content-type: application/json');
            echo json_encode(array('userId' => $userId));
        }
    }
    
    function my_profile_get(){
        $result = array();
        $result=  $this->get_default_urls();
        $userId=  $this->get('userId');
        if($userId==""):
            $this->response(array('error' => 'Please provide valid user index.'), 400); return FALSE;
        else:
            $result['userProfileData']=$this->user->get_details_by_id($userId);
            $result['timestamp'] = (string)mktime();
            header('Content-type: application/json');
            echo json_encode($result);
        endif;
    }
    
    function my_profile_post(){
        $firstName=$this->post('firstName');
        $lastName=$this->post('lastName');
        $contactNo=$this->post('contactNo');
        $email=$this->post('email');
        $mobile=$this->post('mobile');
        $fax=$this->post('fax');
        $DOB=$this->post('DOB');
        $aboutMe=$this->post('aboutMe');
        $userId=$this->post('userId');
        
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->response(array('error' => 'Please provide valid email.'), 400); return FALSE;
        }
        
        if($email!="" && $contactNo!="" && $deviceToken!="" && $deviceType!="" && $UDID!="" && $userId!=""):
            $this->load->model();
            $myProfileDataArr=array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$contactNo,
                    'email'=>$email,'DOB'=>$DOB,'mobile'=>$mobile,'fax'=>$fax,'aboutMe'=>$aboutMe);
            $this->user->edit($myProfileDataArr,$userId);
            header('Content-type: application/json');
            echo json_encode(array('Messaage'=>'Profile data updated successfully'));
        endif;
    }
    
    function my_buyers_clubs_get(){
        $result = array();
        $result=  $this->get_default_urls();
        $userId=  $this->get('userId');
        $this->load->model('Country');
        $result['countryDataArr']=$this->Country->get_all1();
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
        $result['CatArr']=$menuArr;
        $my_groups = $this->user->get_my_groups_apps($userId);
        $result['myGroups']=$my_groups;
        $result['timestamp'] = (string)mktime();
        header('Content-type: application/json');
        echo json_encode($result);
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
    
    function get_default_urls(){
        $result=array();
        $result['site_product_image_url']='http://seller.tidiit.com/resources/product/original/';
        //$result['site_image_url']=$this->config->item('MainSiteResourcesURL').'images/';
        $result['site_image_url']='http://tidiit.com/resources/images/';
        $result['site_slider_image_url']='http://tidiit.com/resources/banner/original/';
        $result['site_brand_image_url']='http://tidiit.com/resources/brand/original/';
        $result['site_category_image_url']='http://tidiit.com/resources/category/original/';
        return $result;
    }
}
    
?>