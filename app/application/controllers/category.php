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
        $categoryId=$this->post('categoryId');
        
    }
}