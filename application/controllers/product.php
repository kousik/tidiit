<?php
class Product extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Product_model');
        $this->load->model('Option_model');
        $this->load->model('Category_model');
        //parse_str($_SERVER['QUERY_STRING'],$_GET);
        $this->db->cache_off();
    }
    
    function details($str){
        if(strtoupper(trim($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')))!='IN'){
            redirect(BASE_URL);
        }
        if($str==""){
            redirect(BASE_URL);
        }
        $this->config->load('product');
        /*$strArr=explode('+',$str);
        $productId=  base64_decode(end($strArr));*/
        $productId=  base64_decode($str);
        //echo $productId;die;
        $productDetailsArr=  $this->Product_model->details($productId);
        //pre($productDetailsArr);die;
        if($productDetailsArr[0]->status==0){
            redirect(BASE_URL);
        }
        
        
        $this->Product_model->update_view($productId);
        $productImageArr=$this->Product_model->get_products_images($productId);
        $productPriceArr=$this->Product_model->get_products_price($productId);
        $SEODataArr=array();
        if($this->is_loged_in()){
            $data=$this->_get_logedin_template($SEODataArr);
        }else{
            $data=$this->_get_tobe_login_template($SEODataArr);
        }
        $data['is_loged_in'] = $this->is_loged_in();
        $data['breadCrumbStr']=$this->breadCrumb($productDetailsArr[0]->categoryId, $productDetailsArr[0]->title);
        $data['productDetailsArr']=$productDetailsArr;
        $data['productImageArr']=$productImageArr;
        $data['productPriceArr']=$productPriceArr;
        
        $data['feedback']=$this->load->view('feedback',$data,TRUE);
        $data['common_how_it_works']=$this->load->view('common_how_it_works',$data,TRUE);
        $data['options'] = $this->Option_model->get_product_display_option_values($productId);
        $data['topoptions'] = $this->Option_model->get_product_display_top_option_values($productId);
        $this->load->view('details',$data);
    }
    
    private function breadCrumb($categoryId,$title){
        $breadCrumbStr='<a href="'.BASE_URL.'">Home</a>';
        $categoryArr=  $this->get_category_arr_for_breadcrumb($categoryId);
        ksort($categoryArr);
        //pre($categoryArr);die;
        foreach($categoryArr AS $k => $v){
            //echo $k .' = '.$v;die;
            $breadCrumbStr .=' >> <a href="'.BASE_URL.'category/details/'.  my_seo_freindly_url($v).'/'.  base64_encode('k').'~'.md5('tidiit').'">'.$v.'</a>';
        }
        return $breadCrumbStr .= ' >> '.$title;
    }
    
    function get_category_arr_for_breadcrumb($categoryId,$CategoryArr=array()){
        if(empty($CategoryArr)){
            $Details=$this->Category_model->get_details_by_id($categoryId);
            $CategoryArr[$categoryId]=$Details[0]->categoryName;
            return $this->get_category_arr_for_breadcrumb($Details[0]->parrentCategoryId,$CategoryArr);
        }else{
            $Details=$this->Category_model->get_details_by_id($categoryId);
            if($Details[0]->parrentCategoryId==0){
                $CategoryArr[$categoryId]=$Details[0]->categoryName;
                return $CategoryArr;    
            }else{
                $CategoryArr[$categoryId]=$Details[0]->categoryName;
                return $this->get_category_arr_for_breadcrumb($Details[0]->parrentCategoryId,$CategoryArr);
            }
        }
    }
}