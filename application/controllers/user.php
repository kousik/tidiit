<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->_isLoggedIn();
        $this->load->model('User_model');
        $this->db->cache_off();
    }
    
    function edit_profile(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=8;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('edit_profile',$data);
    }
    
    function my_account(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=1;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('myaccount',$data);
    }
    
    function my_shipping_address(){
        $this->load->model('Country');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=2;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $userShippingDataDetails=$this->User_model->get_user_shipping_information();
        if(empty($userShippingDataDetails)){
            $userShippingDataDetails[0]=new stdClass();
            $userShippingDataDetails[0]->firstName="";
            $userShippingDataDetails[0]->lastName="";
            $userShippingDataDetails[0]->countryId="";
            $userShippingDataDetails[0]->cityId="";
            $userShippingDataDetails[0]->zipId="";
            $userShippingDataDetails[0]->localityId="";
            $userShippingDataDetails[0]->phone="";
            $userShippingDataDetails[0]->address="";
            $userShippingDataDetails[0]->contactNo="";
        }
        if($userShippingDataDetails[0]->countryId!=""){
            $data['cityDataArr']=  $this->Country->get_all_city1($userShippingDataDetails[0]->countryId);
        }
        if($userShippingDataDetails[0]->zipId!=""){
            $data['zipDataArr']=  $this->Country->get_all_zip1($userShippingDataDetails[0]->cityId);
        }
        if($userShippingDataDetails[0]->localityId!=""){
            $data['localityDataArr']=  $this->Country->get_all_locality($userShippingDataDetails[0]->zipId);
        }
        $data['countryDataArr']=$this->Country->get_all1();
        $data['userShippingDataDetails']=$userShippingDataDetails;
        $this->load->view('my_shipping_address',$data);
    }
    
    function my_billing_address(){
        $this->load->model('Country');
        $this->load->model('Category_model','category');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $userBillingDataDetails=$this->User_model->get_billing_address();
        if(empty($userBillingDataDetails)){
            $userBillingDataDetails[0]=new stdClass();
            $userBillingDataDetails[0]->firstName="";
            $userBillingDataDetails[0]->lastName="";
            $userBillingDataDetails[0]->countryId="";
            $userBillingDataDetails[0]->cityId="";
            $userBillingDataDetails[0]->zipId="";
            $userBillingDataDetails[0]->localityId="";
            $userBillingDataDetails[0]->phone="";
            $userBillingDataDetails[0]->address="";
            $userBillingDataDetails[0]->contactNo="";
            $userBillingDataDetails[0]->email="";
        }
        //pre($userBillingDataDetails);die;
        if($userBillingDataDetails[0]->countryId!=""){
            $data['cityDataArr']=  $this->Country->get_all_city1($userBillingDataDetails[0]->countryId);
        }
        if($userBillingDataDetails[0]->zipId!=""){
            $data['zipDataArr']=  $this->Country->get_all_zip1($userBillingDataDetails[0]->cityId);
        }
        if($userBillingDataDetails[0]->localityId!=""){
            $data['localityDataArr']=  $this->Country->get_all_locality($userBillingDataDetails[0]->zipId);
        }
        $data['userBillingDataDetails']=$userBillingDataDetails;
        $countryDataArr=$this->Country->get_all1();
        //pre($countryDataArr);die;
        $data['countryDataArr']=$this->Country->get_all1();
        $data['userMenuActive']=3;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        
        $data['topCategoryDataArr']=  $this->category->get_top_category_for_product_list();
        
        $this->load->view('my_billing_address',$data);
    }
    
    function my_orders(){
        $this->load->model('Country');
        
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['countryDataArr']=$this->Country->get_all1();
        
        $data['userMenuActive']=4;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('my_orders',$data);
    }
    
    function my_groups(){
        $this->load->model('Category_model');
        $this->load->model('Country');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['countryDataArr']=$this->Country->get_all1();
        $data['CatArr']=$this->Category_model->get_all(0);
        $user = $this->_get_current_user_details();
        $my_groups = $this->User_model->get_my_groups();
        $data['myGroups']=$my_groups;
        $data['user']=$user;
        $data['userMenuActive']=5;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('my_groups',$data);
    }
    
    
    function edit_groups($groupId){
        $groupId = base64_decode($groupId)/987654321;
        $this->load->model('Category_model');
        $this->load->model('Country');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['countryDataArr']=$this->Country->get_all1();
        $data['CatArr']=$this->Category_model->get_all(0);
        $user = $this->_get_current_user_details();
        $group = $this->User_model->get_group_by_id($groupId);
        $data['group']=$group;
        $data['user']=$user;
        $data['userMenuActive']=5;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('my_group_edit',$data);
    }
        
    function my_group_orders(){
        
    }
    
    function my_finance_info(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=6;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $financeDataArr=$this->User_model->get_finance_info();
        if(empty($financeDataArr)){
            $financeDataArr[0]=new stdClass();
            $financeDataArr[0]->mpesaFullName="";
            $financeDataArr[0]->mpesaAccount="";
        }
        $data['financeDataArr']=$financeDataArr;
        $this->load->view('my_finance',$data);
    }
    
    function my_profile(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=8;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $userDataArr=$this->User_model->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
        $data['userDataArr']=$this->User_model->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
        $this->load->view('edit_profile',$data);
    }
}