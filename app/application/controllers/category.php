<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Category extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Siteconfig_model','siteconfig');
        //$this->load->model('Testimonial_model');
        $this->load->model('Cms_model','cms');
        $this->load->model('Product_model','product');
        $this->load->model('Category_model','category');
        $this->load->model('Order_model','order');
        $this->load->model('Country');
    }
    /**
     * Men-> 2nd
     */
    function show_category_post(){
        $categoryId=  $this->post('categoryId');
        $result=array();
        $range = array(0,100000);
        $result['minimum']=$range[0];
        $result['maximum']=$range[01];
        
        $offset=NULL;
        $item_per_page=NULL;
        $cond=array();
        $products = $this->category->get_children_categories_products($categoryId, $offset, $limit = $item_per_page, $cond);
        $total_rows = $this->category->get_children_categories_products($categoryId, 0, false, $cond);
        //$tr = (isset($total_rows['products'])?$total_rows['products']:false);
        //$totalrows = (!empty($tr)?count($tr):0);
        //$total_pages = ceil($totalrows/$item_per_page);
        //$data['total_pages'] = $total_pages;
        $products['brands'] = $total_rows['brands'];
        $result['products'] = $products;
        $this->load->model('Option_model','option');
        $currentCat = $this->category->get_details_by_id($categoryId);
        $currCat = $currentCat[0];
        $options = $this->option->get_category_product_option_wedgets($currCat->option_ids);
        $result['options'] = $options;
        
        /// 2n level +category
        $is_last = $this->category->is_category_last($categoryId);
        $result['widget_cats'] = $this->category->get_parent_categories($categoryId);
        if(!$is_last):
            $result['body_cats'] = $this->category->display_children_categories($categoryId);
        endif;
        $result['is_last'] = $is_last;
        $result['s_widget_cats'] = $result['widget_cats'];
        
        success_response_after_post_get($result);
    }
    
    function show_option_filter_post(){
        $offset=NULL;
        $item_per_page=NULL;
        $cond=[];
        $categoryId=$this->post('categoryId');
        //$filterOptions="brand=Samsung|Micromax|Intex&query=12@Handset|12@Warranty Card|12@User Manual|14@1|14@3|15@Gray|15@Black|21@Android|24@8 MP&sort=popular&range=31800|100000";
        $filterOptions=$this->post('filterOptions');
        $queries = explode("&", $filterOptions);
        foreach($queries AS $k => $v){
            if(substr($v,0,5)=='sort='){
                $sortValArr=  explode('=', $v);
                $cond['order_by']=$sortValArr[1];
            }else if(substr($v,0,6)=='brand='){
                $brandValArr=  explode('=', $v);
                $cond['brand']=  explode('|',$brandValArr[1]);
            }else if(substr($v,0,6)=='range='){
                $rangeValArr=  explode('=', $v);
                $cond['range']=  explode('|',$rangeValArr[1]);
            }else if(substr($v,0,6)=='query='){
                $queryValArr=  explode('=', $v);
                $cond['query']=  explode('|',$queryValArr[1]);
            }
        }


        $products = $this->category->get_children_categories_products($categoryId, $offset, $limit = $item_per_page, $cond);
        $total_rows = $this->category->get_children_categories_products($categoryId, 0, false, $cond);
        
        $result=array();
        $range = array(0,100000);
        $result['minimum']=$range[0];
        $result['maximum']=$range[01];
        
        $products['brands'] = $total_rows['brands'];
        $result['products'] = $products;
        $this->load->model('Option_model','option');
        $currentCat = $this->category->get_details_by_id($categoryId);
        $currCat = $currentCat[0];
        $options = $this->option->get_category_product_option_wedgets($currCat->option_ids);
        $result['options'] = $options;
        
        /// 2n level +category
        $is_last = $this->category->is_category_last($categoryId);
        $result['widget_cats'] = $this->category->get_parent_categories($categoryId);
        if(!$is_last):
            $result['body_cats'] = $this->category->display_children_categories($categoryId);
        endif;
        $result['is_last'] = $is_last;
        $result['s_widget_cats'] = $result['widget_cats'];
        
        success_response_after_post_get($result);
    }
    
    function sho_search_suggestions_post(){
        $userRequest=$this->post('userRequest');
    }
    
    function show_option_filter_get(){
        $cond=[];
        //$categoryId=$this->post('categoryId');
        
        
    }
    
    
    function show_sugestion_post(){
        $term=$this->post('textForSearch');
        //$term = $_GET['term'];
        $a_json_invalid = array(array("id" => "#", "value" => $term, "label" => "Only letters and digits are permitted..."));
        $json_invalid = json_encode($a_json_invalid);
        // replace multiple spaces with one
        $term = preg_replace('/\s+/', ' ', $term);
        // SECURITY HOLE ***************************************************************
        // allow space, any unicode letter and digit, underscore and dash
        if(preg_match("/[^\040\pL\pN_-]/u", $term)) {
            print $json_invalid;
            exit;
        }
        $this->load->model('Category_model','category');
        $a_json = $this->category->get_auto_serch_populet_by_text($term);
        $parts = explode(' ', $term);
        $a_json = $this->apply_highlight($a_json, $parts);
        success_response_after_post_get($a_json);
    }
    
    function final_search_data_post(){
        $searchText=$this->post('searchText');
        $searchTextType=$this->post('searchTextType');
        $searchTextTypeId=$this->post('searchTextTypeId');
        
    }
    
}