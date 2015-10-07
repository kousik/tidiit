<?php
class User extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->db->cache_off();
    }
    
    function my_account(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            redirect(BASE_URL);
        }
        $data['userMenuActive']=1;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('myaccount',$data);
    }
    
    function my_shipping_address(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            redirect(BASE_URL);
        }
        $data['userMenuActive']=2;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('my_shipping_address',$data);
    }
    
    function my_billing_address(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            redirect(BASE_URL);
        }
        $data['userMenuActive']=3;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('my_billing_address',$data);
    }
    
    function my_orders(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            redirect(BASE_URL);
        }
        $data['userMenuActive']=4;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('my_orders',$data);
    }
    
    function my_groups(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            redirect(BASE_URL);
        }
        $data['userMenuActive']=5;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('my_groups',$data);
    }
    
    function process_my_group_orders(){
        print_r($_POST);
        $user = $this->_get_current_user_details(); print_r($user);die;
        $productId = $this->input->post('productId');
        $prorductPriceId = $this->input->post('prorductPriceId');
    }
}