<?php
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
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=2;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('my_shipping_address',$data);
    }
    
    function my_billing_address(){
        $this->load->model('Country');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $userBillingDataDetails=$this->User_model->get_billing_address();
        //pre($userBillingDataDetails);die;
        if($userBillingDataDetails[0]->countryId!=""){
            $data['CityDataArr']=  $this->Country->get_all_city1($userBillingDataDetails[0]->countryId);
        }
        if($userBillingDataDetails[0]->zipId!=""){
            $data['ZipDataArr']=  $this->Country->get_all_zip1($userBillingDataDetails[0]->cityId);
        }
        if($userBillingDataDetails[0]->localityId!=""){
            $data['LocalityDataArr']=  $this->Country->get_all_locality($userBillingDataDetails[0]->zipId);
        }
        $data['userBillingDataDetails']=$userBillingDataDetails;
        $countryDataArr=$this->Country->get_all1();
        //pre($countryDataArr);die;
        $data['countryDataArr']=$this->Country->get_all1();
        $data['userMenuActive']=3;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
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
    
    function process_my_group_orders(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details(); //print_r($user);die;
        $productId = $this->input->post('productId');
        $prorductPriceId = $this->input->post('prorductPriceId');
        $data['userMenuActive']=7;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('group_order/group_order',$data);
    }
    
    function my_group_orders(){
        
    }
    
    function my_finance_info(){
        
    }
    
    function my_profile(){
        
    }
}