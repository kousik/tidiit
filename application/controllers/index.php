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
        $this->show_under_construction1();
        //echo 'user   location : '.$this->session->userdata('FE_SESSION_USER_LOCATION_VAR');die;
        /*$SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $this->load->model('Banner_model');
        $this->load->model('Faq_model');
        /// Home Page slider data
        $slider1=$this->Banner_model->get_home_slider();
        //pre($slider1);die;
        $data['slider1']=$slider1;
        
        $slider2=$this->Banner_model->get_home_slider(2);
        //pre($slider2);die;
        $data['slider2']=$slider2;
        
        $noOfItem=  $this->Siteconfig_model->get_value_by_name('HOME_PAGE_NEW_ARRIVAL_ITEM_NO');
        $newArrivalsData=  $this->Product_model->get_recent($noOfItem);
        $data['bestSelllingItem']=$this->Product_model->get_best_selling($noOfItem);
        $data['newArrivals']=$newArrivalsData;
        $data['featuredProducts'] = $this->Product_model->get_featured_products($noOfItem);
        $data['brandZoneArr']=$this->Brand_model->get_all();
        $data['sellerDataArr']=$this->Faq_model->get_all('seller');
        $data['buyerDataArr']=$this->Faq_model->get_all('buyer');
        $data['buyerDataArr']=$this->Faq_model->get_all('buyer');
        $data['feedback']=$this->load->view('feedback',$data,TRUE);
        $data['common_how_it_works']=$this->load->view('common_how_it_works',$data,TRUE);
        
        $this->load->view('home',$data);*/
    }
    
    function out_for_delivery_update(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $this->load->model('Banner_model');
        $this->load->model('Faq_model');
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
        $data['sellerDataArr']=$this->Faq_model->get_all('seller');
        $data['buyerDataArr']=$this->Faq_model->get_all('buyer');
        
        $this->load->view('out_for_delivery',$data);
    }
    
    function delivery_update(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $this->load->model('Banner_model');
        $this->load->model('Faq_model');
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
        $data['sellerDataArr']=$this->Faq_model->get_all('seller');
        $data['buyerDataArr']=$this->Faq_model->get_all('buyer');
        
        $data['feedback']=$this->load->view('feedback',$data,TRUE);
        $data['common_how_it_works']=$this->load->view('common_how_it_works',$data,TRUE);
        
        $this->load->view('delivery',$data);
    }
    
    function logout(){
        $this->_logout();
        redirect(BASE_URL);
    }
 
    function top_search(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $this->load->view('under_construction',$data);
    }
    
    function contact_us(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $data['feedback']=$this->load->view('feedback',$data,TRUE);
        $data['common_how_it_works']=$this->load->view('common_how_it_works',$data,TRUE);
        
        //$this->load->view('under_construction',$data);
        $this->load->view('contacts',$data);
    }
    
    function seller_faq(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $this->load->model('Faq_model');
        $data['faqDataArr']=  $this->Faq_model->get_all('seller');
        
        $data['feedback']=$this->load->view('feedback',$data,TRUE);
        $data['common_how_it_works']=$this->load->view('common_how_it_works',$data,TRUE);
        
        $this->load->view('faq_seller',$data);
    }
    
    function buyer_faq(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $this->load->model('Faq_model');
        $data['faqDataArr']=  $this->Faq_model->get_all('buyer');
        
        $data['feedback']=$this->load->view('feedback',$data,TRUE);
        $data['common_how_it_works']=$this->load->view('common_how_it_works',$data,TRUE);
        
        $this->load->view('faq_buyer',$data);
    }
    
    function brand_zone(){
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $data['brandZoneArr']=$this->Brand_model->get_all();
        
        $data['feedback']=$this->load->view('feedback',$data,TRUE);
        $data['common_how_it_works']=$this->load->view('common_how_it_works',$data,TRUE);
        
        $this->load->view('brand_zone',$data);
    }
    
    function help(){
        $this->load->model('Help_model');
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $get_all_active_topic=$this->Help_model->get_all_active_topic();
        $get_help_topics_data=$this->Help_model->get_topic_details_by_id($get_all_active_topic[0]->helpTopicsId);
        
        //pre($get_all_active_topic);die;
        $data['helpTopicsArr']=$get_all_active_topic;
        $data['helpDataArr']=$get_help_topics_data;
        $questions = [];
        foreach($get_help_topics_data as $hkey => $hdata):
            $questions[] = $hdata->question;
        endforeach;
        $data['questions']=$questions;
        $data['feedback']=$this->load->view('feedback',$data,TRUE);
        $data['common_how_it_works']=$this->load->view('common_how_it_works',$data,TRUE);
        
        $this->load->view('help',$data);
    }

    function submit_help_query(){
        $help_subject   = $this->input->post('help_subject');
        if(!$help_subject):
            echo "-1<p class='box alert text-left'>Please choose the help topic!</p>";die;
        endif;
        $name           = $this->input->post('name');
        if(!$name):
            echo "-1<p class='box alert text-left'>Please enter your name!</p>";die;
        endif;
        $email          = $this->input->post('email');
        if(!$email):
            echo "-1<p class='box alert text-left'>Please enter your email!</p>";die;
        endif;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) :
            echo "-1<p class='box alert text-left'>This ($email) email address is not considered valid.</p>";
        endif;
        $phone          = $this->input->post('phone');
        if(!$phone):
            echo "-1<p class='box alert text-left'>Please enter your phone number!</p>";die;
        endif;

        $regex = "/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i";
        if(!preg_match( $regex, $phone )):
            echo "-1<p class='box alert text-left'>Please enter valid phone number!</p>";die;
        endif;
        $help_message   = $this->input->post('help_message');
        if(!$help_message):
            echo "-1<p class='box alert text-left'>Please enter your your enquiry!</p>";die;
        endif;

        $maildata           =  $this->load_default_resources();
        $maildata['name']   = $name;
        $maildata['email']  = $email;
        $maildata['phone']  = $phone;
        $maildata['message']= $help_message;
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='kousik.das.btech@gmail.com';
        $this->_global_tidiit_mail($supportEmail, $help_subject, $maildata,'help_mail','Tidiit Inc Help Support');
        echo "<p class='box info text-left'>Your query have been submitted successfully!</p>";die;
    }
    
    function show_under_construction1(){
        $data=array();
        $data['SiteImagesURL']=$this->config->item('SiteImagesURL');
        $data['SiteCSSURL']=$this->config->item('SiteCSSURL');
        $data['SiteJSURL']=$this->config->item('SiteJSURL');
        $data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
        $data['MainSiteBaseURL']=BASE_URL;
        $data['MainSiteImagesURL']=$this->config->item('SiteImagesURL');
        $data['SiteProductImageURL']=PRODUCT_DEAILS_SMALL;
        $this->load->view('under_construction1',$data);
    }
}