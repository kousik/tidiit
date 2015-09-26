<?php
class Product extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        //parse_str($_SERVER['QUERY_STRING'],$_GET);
        $this->db->cache_off();
    }
    
    function details($str){
        if($str==""){
            redirect(BASE_URL);
        }
        $productDetailsArr=  $this->Product_model->details(base64_decode($str));
    }
}