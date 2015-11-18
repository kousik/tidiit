<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Brand_model');
        parse_str($_SERVER['QUERY_STRING'],$_GET);
        //$this->db->cache_off();
    }
    
    function under_construnction(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }

        $this->load->view('under_construction',$data);
    }
    
    function index(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $this->load->model('Banner_model');
        /// Home Page slider data
        $slider1=$this->Banner_model->get_home_slider();
        //pre($slider1);die;
        $data['slider1']=$slider1;
        
        $slider2=$this->Banner_model->get_home_slider(2);
        //pre($slider2);die;
        $data['slider2']=$slider2;
        
        $noOfItem=  $this->Siteconfig_model->get_value_by_name('HOME_PAGE_NEW_ARRIVAL_ITEM_NO');
        $newArrivalsData=  $this->Product_model->get_recent($noOfItem);
        $data['bestSelllingItem']=$newArrivalsData;
        $data['newArrivals']=$newArrivalsData;
        $data['featuredProducts']=$newArrivalsData;
        $data['brandZoneArr']=$this->Brand_model->get_all();
        $this->load->view('home',$data);
    }
    
    function logout(){
        $this->_logout();
        redirect(BASE_URL);
    }
    
}