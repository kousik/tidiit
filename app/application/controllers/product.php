<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Product extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Siteconfig_model','siteconfig');
        //$this->load->model('Testimonial_model');
        $this->load->model('Cms_model','cms');
        $this->load->model('Product_model','product');
        $this->load->model('Category_model','category');
        $this->load->model('Country');
    }
    
    function product_details_get(){
        $userId=$this->get('userId');
        $this->config->load('product');
        $productId= $this->get('productId');
        $productDetailsArr=  $this->product->details($productId,TRUE);
        if(empty($productDetailsArr)){
            $this->response(array('error' => 'Invalid product index. Please try again!'), 400); return FALSE;
        }
        //pre($productDetailsArr);die;
        if($productDetailsArr[0]['status']==0){
            $this->response(array('error' => 'This product is an inactive product. Please try again!'), 400); return FALSE;
        }
        $this->product->update_view($productId);
        $productImageArr=$this->product->get_products_images($productId,TRUE);
        $productPriceArr=$this->product->get_products_price($productId,TRUE);
        $result=array();
        $result['is_loged_in'] = $userId;
        //$data['breadCrumbStr']=$this->breadCrumb($productDetailsArr[0]->categoryId, $productDetailsArr[0]->title);
        $result['productDetailsArr']=$productDetailsArr;
        $result['productImageArr']=$productImageArr;
        $result['productPriceArr']=$productPriceArr;
        
        $mobileBoxContentsArr=$this->config->item('mobileBoxContents');
        $mobileContentArr=  explode(',', $productDetailsArr[0]['mobileBoxContent']);
        if(!empty($mobileContentArr)):
            $inBox='';
            foreach($mobileContentArr AS $k){
                if($inBox=='')
                    $inBox=$mobileBoxContentsArr[$k];
                else
                    $inBox.= ', '.$mobileBoxContentsArr[$k];
            }
            $result['mobileBoxContents']=$inBox;
        else:
            $result['mobileBoxContents']='No thing inside';
        endif;
        
        $mobileColor=$this->config->item('mobileColor');
        $mobileColorName='';if(array_key_exists($productDetailsArr[0]['color'], $mobileColor)){$mobileColorName=$mobileColor[$productDetailsArr[0]['color']];}
        $result['mobileColorName']=$mobileColorName;
        
        $mobileDisplayResolution=$this->config->item('mobileDisplayResolution');
        $mobileDisplayResolutiontype='';if(array_key_exists($productDetailsArr[0]['displayResolution'], $mobileDisplayResolution)){$mobileDisplayResolutiontype=$mobileDisplayResolution[$productDetailsArr[0]['displayResolution']];}
        $result['mobileDisplayResolutionType']=$mobileDisplayResolutiontype;
        
        $mobileBatteryType=$this->config->item('mobileBatteryType');
        $mobileBatteryName='';if(array_key_exists($productDetailsArr[0]['batteryType'], $mobileBatteryType)){$mobileBatteryName=$mobileBatteryType[$productDetailsArr[0]['batteryType']];}
        $result['mobileBatteryName']=$mobileBatteryName;
        
        $result['mobileOS']=$this->config->item('mobileOS')[$productDetailsArr[0]['os']];
                
        $mobileConnectivity=$this->config->item('mobileConnectivity');
        $connectivityArr= explode(',', $productDetailsArr[0]['mobileConnectivity']);
        $connectionStr='';
        foreach($connectivityArr AS $k =>$v){
            $connectionStr.=$mobileConnectivity[$v].', ';
        }
        $result['connectivityType']=$connectionStr;
        
        $mobileProcessorCores=$this->config->item('mobileProcessorCores');        
        $processorType="";if(array_key_exists($productDetailsArr[0]['processorCores'], $mobileProcessorCores)){$processorType=$mobileProcessorCores[$productDetailsArr[0]['processorCores']];}
        $result['processorType']=$processorType;
        
        $result['mobileProcessorBrand']=$this->config->item('mobileProcessorBrand')[$productDetailsArr[0]['processorBrand']];
        
        $mobileCamera=$this->config->item('mobileCamera');
        $rearCamera='';if(array_key_exists($productDetailsArr[0]['mobileRearCamera'], $mobileCamera)){$rearCamera=$mobileCamera[$productDetailsArr[0]['mobileRearCamera']];}
        $result['rearCamera']=$rearCamera;
        $frontCamera='';if(array_key_exists($productDetailsArr[0]['frontCamera'], $mobileCamera)){$frontCamera=$mobileCamera[$productDetailsArr[0]['frontCamera']];}
        $result['frontCamera']=$frontCamera;
        
        $ramData=$this->config->item('ramData');
        $ram='';if(array_key_exists($productDetailsArr[0]['ram'], $ramData)){$ram=$ramData[$productDetailsArr[0]['ram']];}
        $result['ram']=$ram;
        
        $expandableMemory=$this->config->item('expandableMemory');
        $expandableMemoryData='';if(array_key_exists($productDetailsArr[0]['expandableMemory'],$expandableMemory)){$expandableMemoryData=$expandableMemory[$productDetailsArr[0]['expandableMemory']];}
        $result['expandableMemoryData']=$expandableMemoryData;
        
        $warrantyDuration=$this->config->item('warrantyDuration');
        $warrantyDurationData='';if(array_key_exists($productDetailsArr[0]['warrantyDuration'], $warrantyDuration)){$warrantyDurationData=$warrantyDuration[$productDetailsArr[0]['warrantyDuration']];}
        $result['warrantyDurationData']=$warrantyDurationData;
        
        $internalMemory=$this->config->item('internalMemory');
        $internalMemoryData='';if(array_key_exists($productDetailsArr[0]['internalMemory'], $internalMemory)){$internalMemoryData=$internalMemory[$productDetailsArr[0]['internalMemory']];}
        $result['internalMemoryData']=$internalMemoryData;
        
        success_response_after_post_get($result);
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
            $Details=$this->category->get_details_by_id($categoryId);
            $CategoryArr[$categoryId]=$Details[0]->categoryName;
            return $this->get_category_arr_for_breadcrumb($Details[0]->parrentCategoryId,$CategoryArr);
        }else{
            $Details=$this->category->get_details_by_id($categoryId);
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