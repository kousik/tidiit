<?php
class Index extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        parse_str($_SERVER['QUERY_STRING'],$_GET);
        //$this->db->cache_off();
    }
    
    function index(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        
        //Best Sellling Item
        //New Arrivals
        //Featured Products
        $noOfItem=  $this->Siteconfig_model->get_value_by_name('HOME_PAGE_NEW_ARRIVAL_ITEM_NO');
        $newArrivalsData=  $this->Product_model->get_recent($noOfItem);
        $data['bestSelllingItem']=$newArrivalsData;
        $data['newArrivals']=$newArrivalsData;
        $data['featuredProducts']=$newArrivalsData;
        
        $this->load->view('home',$data);
    }
    
    function logout(){
        $this->_logout();
        redirect(BASE_URL);
    }
    
}