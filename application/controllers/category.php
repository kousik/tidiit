<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller{
    public function __construct(){
        parent::__construct();
        //$this->db->cache_off();
    }
    
    function details($name,$idStr){
        //echo '<pre>';
        //$cats = $this->Category_model->get_site_categories();
        //print_r($cats);die;
        $idStrArr=  explode('~', $idStr);
        if(count($idStrArr)>0){
            $categoryDetails=$this->Category_model->get_details_by_id(base64_decode($idStrArr[0]));
            if(!empty($categoryDetails)){
                $SEODataArr=array();
                if($this->is_loged_in()){
                    $data=$this->_get_logedin_template($SEODataArr);
                }else{
                    $data=$this->_get_tobe_login_template($SEODataArr);
                }
                $data['is_loged_in'] = $this->is_loged_in();
                if($this->Category_model->has_chield(base64_decode($idStrArr[0])))
                    $this->load->view('category_details',$data);
                else
                    $this->load->view('category_details1',$data);
            }else{
                redirect(BASE_URL);
            }
        }else{
            redirect(BASE_URL);
        }
    }
    
    
    function display_category_products($name){
        
        $SEODataArr=array();        
        if($this->is_loged_in()):
            $data=$this->_get_logedin_template($SEODataArr);
        else:
            $data=$this->_get_tobe_login_template($SEODataArr);
        endif;
        
        if(!isset($_GET['cpid']) || !$_GET['cpid']):
            $this->session->set_flashdata('error', 'Invalid location. Please click proper category link!');
            redirect(BASE_URL.'products/ord-message');
        else:  
            //echo $_GET['cpid'];
            $categoryId = base64_decode($_GET['cpid'])/226201;
            $currentCat = $this->Category_model->get_details_by_id($categoryId);
            $currCat = $currentCat[0];
            
            //echo my_seo_freindly_url($currentCat->categoryName);
            //echo $name;die;
            if(my_seo_freindly_url($currCat->categoryName) != $name):
                $this->session->set_flashdata('error', 'Invalid location. Please click proper category link!');
                redirect(BASE_URL.'products/ord-message');
            endif;
        endif;
        $data['currCat'] = $currCat;
        $is_last = $this->Category_model->is_category_last($categoryId);
        $data['widget_cats'] = $this->Category_model->get_parent_categories($categoryId);
        $data['body_cats'] = $this->Category_model->display_children_categories($categoryId);
        $data['is_last'] = $is_last;
        $data['s_widget_cats'] = $data['widget_cats'];
        
        
        $cond = array();
        $data['products'] = $this->Category_model->get_children_categories_products($categoryId, $offset = 0, $limit = 12, $cond);
        
        $this->load->view('category_details',$data);
        
    }
    
    /**
     * 
     */
    function display_default_message(){
        $SEODataArr=array();
        if($this->is_loged_in()):
            $data=$this->_get_logedin_template($SEODataArr);
        else:
            $data=$this->_get_tobe_login_template($SEODataArr);
        endif;
        $this->load->view('product_default_message',$data);
    }
    
    
}