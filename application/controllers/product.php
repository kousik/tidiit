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
        $this->config->load('product');
        /*$strArr=explode('+',$str);
        $productId=  base64_decode(end($strArr));*/
        $productId=  base64_decode($str);
        //echo $productId;die;
        $productDetailsArr=  $this->Product_model->details($productId);
        if($productDetailsArr[0]->status==0){
            redirect(BASE_URL);
        }
        $productImageArr=$this->Product_model->get_products_images($productId);
        $productPriceArr=$this->Product_model->get_products_price($productId);
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $data['is_loged_in'] = $this->is_loged_in();
        $data['productDetailsArr']=$productDetailsArr;
        $data['productImageArr']=$productImageArr;
        $data['productPriceArr']=$productPriceArr;
        
        $this->load->view('details',$data);
    }
}