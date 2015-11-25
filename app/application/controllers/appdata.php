<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Appdata extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Siteconfig_model','siteconfig');
        //$this->load->model('Testimonial_model');
        $this->load->model('Cms_model','cms');
        $this->load->model('Product_model','product');
        $this->load->model('Category_model','category');
    }
    
    function testservice_get(){
        $testid = "";
        if($this->get('testid'))
        	$testid = $this->get('testid');
        
        $this->response(array("test ID" => $testid), 200);
    }

    function testpostservice_post(){
        $testid = "";
        if($this->post('testid'))
            $testid = $this->post('testid');
        
        $this->response(array("test ID" => $testid), 200);
    }
    
    function home_get(){
        $timeStamp=$this->get('timestamp');
        if(!isValidTimeStamp($timeStamp)){
            $this->response(array('error' => 'Invalid timestamp'), 400);
        }else{
            $this->load->model('Banner_model','banner');
            $this->load->model('Brand_model','brand');
            $result = array();
            $slider1=$this->banner->get_home_slider(1,TRUE);
            $slider2=$this->banner->get_home_slider(2,TRUE);
            $noOfItem=  $this->siteconfig->get_value_by_name('MOBILE_APP_HOME_PAGE_SLIDER_ITEM_NO');
            $newArrivalsData=  $this->product->get_recent($noOfItem,TRUE);
            
            $result['slider1']=$slider1;
            $result['slider2']=$slider2;
            $result['category_menu']=$this->get_main_menu();
            $result['best_sellling_item']=$newArrivalsData;
            $result['new_arrivals']=$newArrivalsData;
            $result['featured_products']=$newArrivalsData;
            $result['brand']=$this->brand->get_all(TRUE);
            //$result['site_product_image_url']=$this->config->item('ProductURL');
            $result['site_product_image_url']='http://seller.tidiit.com/resources/product/original/';
            //$result['site_image_url']=$this->config->item('MainSiteResourcesURL').'images/';
            $result['site_image_url']='http://tidiit.com/resources/images/';
            $result['site_slider_image_url']='http://tidiit.com/resources/banner/original/';
            $result['site_brand_image_url']='http://tidiit.com/resources/brand/original/';
            
            $result['timestamp'] = (string)mktime();
            header('Content-type: application/json');
            echo json_encode($result);
        }
    }
    
    function get_main_menu(){
        $mainMenuArr=array();
        $TopCategoryData=$this->category->get_top_category_for_product_list(TRUE);
        //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->category->get_subcategory_by_category_id($k['categoryId'],TRUE);
            //pre($SubCateory);die;
            if(count($SubCateory)>0){
                $SubCateoryArr=array();
                foreach($SubCateory as $kk => $vv){
                    $ThirdCateory=$this->category->get_subcategory_by_category_id($vv['categoryId'],TRUE);
                    if(count($ThirdCateory)>0){
                        $ThirdCateoryArr=array();
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            //$menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->category->get_subcategory_by_category_id($v3['categoryId'],TRUE);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                /*foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }*/
                                $v3['SubCategory']=$FourthCateory;
                            }
                            $ThirdCateoryArr[]=$v3;
                        }
                        $vv['SubCategory']=$ThirdCateoryArr;
                    }
                    $SubCateoryArr[]=$vv;
                }
                $k['SubCategory']=$SubCateoryArr;
            }
            $mainMenuArr[]=$k;
            //pre($k);die;
        }
        //pre($mainMenuArr);die;
        //return $TopCategoryData;
        return $mainMenuArr;
    }
}
    
?>