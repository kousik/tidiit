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
        $this->load->model('Brand_model','brand');
        $this->load->model('Country');
    }
    /**
     * Men-> 2nd
     */
    function show_category_post(){
        $categoryId=  $this->post('categoryId');
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $result=array();
        $range = array(0,100000);
        $result['range']=array('minimum'=>$range[0],'maximum'=>$range[01]);
        $result['master_sort'] =array('popular'=>'Popularity','lowestPrice'=>'Lowest Price','highestPrice'=>'Highest Price','new'=>'isNew');
        
        $offset=NULL;
        $item_per_page=NULL;
        $cond=array();
        $products = $this->category->get_children_categories_products_app($categoryId, $offset, $limit = $item_per_page, $cond,$latitude,$longitude);
        $total_rows = $this->category->get_children_categories_products_app($categoryId, 0, false, $cond,$latitude,$longitude);
        //$tr = (isset($total_rows['products'])?$total_rows['products']:false);
        //$totalrows = (!empty($tr)?count($tr):0);
        //$total_pages = ceil($totalrows/$item_per_page);
        //$data['total_pages'] = $total_pages;
        if(empty($total_rows['brands'])){
            $products['brands']=NULL;
        }else{
            $products['brands'] = $total_rows['brands'];
        }
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
        $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($result);
    }
    
    function show_option_filter_post(){
        $offset=NULL;
        $item_per_page=NULL;
        $cond=[];
        $categoryId=$this->post('categoryId');
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
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


        $products = $this->category->get_children_categories_products_app($categoryId, $offset, $limit = $item_per_page, $cond,$latitude,$longitude);
        $total_rows = $this->category->get_children_categories_products_app($categoryId, 0, false, $cond,$latitude,$longitude);
        
        $result=array();
        $range = array(0,100000);
        $result['range']=array('minimum'=>$range[0],'maximum'=>$range[01]);
        $result['master_sort'] =array('popular'=>'Popularity','lowestPrice'=>'Lowest Price','highestPrice'=>'Highest Price','new'=>'isNew');
        
        if(empty($total_rows['brands'])){
            $products['brands']=NULL;
        }else{
            $products['brands'] = $total_rows['brands'];
        }
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
        $result['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($result);
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
        $this->load->model('category','category');
        $a_json = $this->category->get_auto_serch_populet_by_text($term);
        $parts = explode(' ', $term);
        $a_json = $this->apply_highlight($a_json, $parts);
        $data['suggestion']=$a_json;
        //pre($data);die;
        success_response_after_post_get($data);
    }
    
    /**
     * Apply highlight to row label
     *
     * @param string $a_json json data
     * @param array $parts strings to search
     * @return array
     */
    function apply_highlight($a_json, $parts) {

        $p = count($parts);
        $rows = count($a_json);

        for($row = 0; $row < $rows; $row++) {

            $label = $a_json[$row]["label"];
            $a_label_match = array();

            for($i = 0; $i < $p; $i++) {

                $part_len = mb_strlen($parts[$i]);
                $a_match_start = $this->mb_stripos_all($label, $parts[$i]);

                foreach($a_match_start as $part_pos) {

                    $overlap = false;
                    foreach($a_label_match as $pos => $len) {
                        if($part_pos - $pos >= 0 && $part_pos - $pos < $len) {
                            $overlap = true;
                            break;
                        }
                    }
                    if(!$overlap) {
                        $a_label_match[$part_pos] = $part_len;
                    }

                }

            }

            if(count($a_label_match) > 0) {
                ksort($a_label_match);

                $label_highlight = '';
                $start = 0;
                $label_len = mb_strlen($label);

                foreach($a_label_match as $pos => $len) {
                    if($pos - $start > 0) {
                        $no_highlight = mb_substr($label, $start, $pos - $start);
                        $label_highlight .= $no_highlight;
                    }
                    $highlight = '<span class="hl_results">' . mb_substr($label, $pos, $len) . '</span>';
                    $label_highlight .= $highlight;
                    $start = $pos + $len;
                }

                if($label_len - $start > 0) {
                    $no_highlight = mb_substr($label, $start);
                    $label_highlight .= $no_highlight;
                }

                $a_json[$row]["label"] = $label_highlight;
            }

        }

        return $a_json;

    }
    
    /**
     * mb_stripos all occurences
     * Find all occurrences of a needle in a haystack
     *
     * @param string $haystack
     * @param string $needle
     * @return array or false
     */
    function mb_stripos_all($haystack, $needle) {

        $s = 0;
        $i = 0;
        $aStrPos = [];
        while(is_integer($i)) {

            $i = mb_stripos($haystack, $needle, $s);

            if(is_integer($i)) {
                $aStrPos[] = $i;
                $s = $i + mb_strlen($needle);
            }
        }

        if(isset($aStrPos)) {
            return $aStrPos;
        } else {
            return false;
        }
    }
    
    function final_search_data_post(){
        $searchText=$this->post('searchText');
        $searchTextType=$this->post('searchTextType');
        $searchTextTypeId=$this->post('searchTextTypeId');
        $userId=$this->post('userId');
        
        $latitude=  $this->post('latitude');
        $longitude=  $this->post('longitude');
        $deviceType=$this->post('deviceType');
        $UDID=$this->post('UDID');
        $deviceToken=$this->post('deviceToken');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        if($searchText==""):
            $this->response(array('error' => 'Invalid search keyword. Please try again with proper text!'), 400); return FALSE;
        endif;



        $cond = array();
        $data['master_sort'] =array('popular'=>'Popularity','lowestPrice'=>'Lowest Price','highestPrice'=>'Highest Price','new'=>'isNew');
        $data['sort'] = 'popular';
        $data['brand'] = array();
        $range=array(0,100000);
        $data['query'] = array();
        //foreach($_GET as $key => $get):
            /*if($key == 'sort' && $get):
                $cond['order_by'] = $get;
                $data['sort'] = $get;
            endif;
            if($key == 'brand' && $get):
                $brands = explode("|", $get);
                $cond['brand'] = $brands;
                $data['brand'] = $brands;
            endif;
            if($key == 'range' && $get):
                $ranges = explode("|", $get);
                $cond['range'] = $ranges;
                $data['range'] = array($ranges[0],$ranges[1]);
            endif;
            if($key == 'query' && $get):
                $queries = explode("|", $get);
                $cond['query'] = $queries;
                $data['query'] = $queries;
            endif;*/
            if($searchText):
                $cond['terms'] = explode("+", $searchText);
            endif;
            if($searchTextType):
                $cond['qtype'] = $searchTextType;
            endif;
            if($searchTextTypeId):
                $cond['id'] = $searchTextTypeId;
            endif;
        //endforeach;
        $data['brand'] = [];
        if(isset($cond['qtype']) && $cond['qtype'] == "brand"):
            if(isset($cond['id']) && $cond['id']):
                $brandDetails = $this->brand->details($cond['id']);
                $brands = explode("|", $brandDetails[0]->title);
                $data['brand'] = $brands;
            endif;
        endif;


        $offset=NULL;
        $item_per_page=NULL;
        
        /*if(isset($_GET['page']) && $_GET['page']):
            $offset = ($_GET['page'] * $item_per_page);
        else:
            $offset = 0;
        endif;*/
        /*if(isset($_GET['cls']) && $_GET['cls']):
            $offset = 0;
        else:
            $offset = $offset;
        endif;*/

        $products = $this->category->get_search_products_app($offset, $limit = $item_per_page, $cond,$latitude,$longitude);
        $total_rows = $this->category->get_search_products_app(0, false, $cond,$latitude,$longitude);
        $tr = (isset($total_rows['products'])?$total_rows['products']:false);
        $totalrows = (!empty($tr)?count($tr):0);
        //$total_pages = ceil($totalrows/$item_per_page);
        //$data['total_pages'] = $total_pages;



        $brnds = $this->brand->get_all();
        $brand = [];
        foreach($brnds as $bkey => $bdata):
            $brand[] = $bdata->title;
        endforeach;
        
        if(empty($brand)){
            $products['brands']=NULL;
        }else{
            $products['brands'] = $brand;
        }
        $data['products'] = $products;

        /*if($total_pages <= $item_per_page):
            $data['show_loads'] = true;
        else:
            $data['show_loads'] = false;
        endif;
        
        if(isset($_GET['stype']) && $_GET['stype'] == "ajax"):
            if(isset($_GET['cls']) && $_GET['cls']):
                $data['cls'] = 1;
            else:
                $data['cls'] = 0;
            endif;
            unset($data['header']);
            $brnd = isset($_GET['brand'])?explode("|", $_GET['brand']):array();
            $brand = array_merge($data['brand'],$brnd);
            $data['products'] = $this->create_product_view($products);
            $data['brands'] = $this->display_brands_view($brand, $brnd);
            echo json_encode($data);die;
        endif;*/
        $data['range']=array('minimum'=>$range[0],'maximum'=>$range[01]);
        $data['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($data);
    }
 
    function get_product_by_brand_post(){
        $brandId=$this->post('brandId');
        $userId=$this->post('userId');
        $UDID=$this->post('UDID');
        $deviceType=$this->post('deviceType');
        $deviceToken=$this->post('deviceToken');
        $latitude=$this->post('latitude');
        $longitude=$this->post('longitude');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $brandDetails = $this->brand->details($brandId);
        
        if(!$brandDetails):
            $this->response(array('error' => 'Invalid brand id!'), 400); return FALSE;
        endif;
        $brandDetails = $brandDetails[0];
        
        $data['branddetails'] = $brandDetails;
        $cond = array();
        $cond['brand'] = [$brandDetails->title];
        $data['sort'] = 'popular';
        $data['brand'] = array();
        $data['range'] = array(0,100000);
        
        
        $offset=NULL;
        $item_per_page=NULL;
        
        $products = $this->category->get_brand_products_app($brandId, $offset, $limit = $item_per_page, $cond,$latitude,$longitude);
        $total_rows = $this->category->get_brand_products_app($brandId, 0, false, $cond,$latitude,$longitude);
        $tr = (isset($total_rows['products'])?$total_rows['products']:false);
        $totalrows = (!empty($tr)?count($tr):0);
        //$total_pages = ceil($totalrows/$item_per_page);
        //$data['total_pages'] = $total_pages;
        //$products['brands'] = $total_rows['brands'];


        $brnds = $this->brand->get_all();
        $brand = [];
        foreach($brnds as $bkey => $bdata):
            $brand[] = $bdata->title;
        endforeach;

        $data['brands'] = $brand;
        $data['products'] = $products;
        $range = array(0,100000);
        $data['range']=array('minimum'=>$range[0],'maximum'=>$range[01]);
        $data['master_sort'] =array('popular'=>'Popularity','lowestPrice'=>'Lowest Price','highestPrice'=>'Highest Price','new'=>'isNew');
        $data['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($data);
    }
    
    function new_products_list_post(){
        $userId=$this->post('userId');
        $UDID=$this->post('UDID');
        $deviceType=$this->post('deviceType');
        $deviceToken=$this->post('deviceToken');
        $latitude=$this->post('latitude');
        $longitude=$this->post('longitude');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $cond = array();
        //$cond['brand'] = [$brandDetails->title];
        $data['sort'] = 'popular';
        $data['range'] = array(0,100000);
        
        //$offset=NULL;
        //$item_per_page=NULL;
        
        //$products = $this->category->get_brand_products($brandId, $offset, $limit = $item_per_page, $cond);
        $products=  $this->product->get_new_product($latitude,$longitude);
        $brnds = $this->brand->get_all();
        $brand = [];
        foreach($brnds as $bkey => $bdata):
            $brand[] = $bdata->title;
        endforeach;

        $data['brands'] = $brand;
        $data['products'] = $products;
        $range = array(0,100000);
        $data['range']=array('minimum'=>$range[0],'maximum'=>$range[01]);
        $data['master_sort'] =array('popular'=>'Popularity','lowestPrice'=>'Lowest Price','highestPrice'=>'Highest Price','new'=>'isNew');
        $data['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($data);
    }
    
    function featured_products_list_post(){
        $userId=$this->post('userId');
        $UDID=$this->post('UDID');
        $deviceType=$this->post('deviceType');
        $deviceToken=$this->post('deviceToken');
        $latitude=$this->post('latitude');
        $longitude=$this->post('longitude');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $cond = array();
        //$cond['brand'] = [$brandDetails->title];
        $data['sort'] = 'popular';
        $data['range'] = array(0,100000);
        
        //$offset=NULL;
        //$item_per_page=NULL;
        
        //$products = $this->category->get_brand_products($brandId, $offset, $limit = $item_per_page, $cond);
        $products=  $this->product->get_all_featured_product();
        $brnds = $this->brand->get_all();
        $brand = [];
        foreach($brnds as $bkey => $bdata):
            $brand[] = $bdata->title;
        endforeach;

        $data['brands'] = $brand;
        $data['products'] = $products;
        $range = array(0,100000);
        $data['range']=array('minimum'=>$range[0],'maximum'=>$range[01]);
        $data['master_sort'] =array('popular'=>'Popularity','lowestPrice'=>'Lowest Price','highestPrice'=>'Highest Price','new'=>'isNew');
        $data['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($data);
    }
    
    function best_selling_products_list_post(){
        $userId=$this->post('userId');
        $UDID=$this->post('UDID');
        $deviceType=$this->post('deviceType');
        $deviceToken=$this->post('deviceToken');
        $latitude=$this->post('latitude');
        $longitude=$this->post('longitude');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $cond = array();
        //$cond['brand'] = [$brandDetails->title];
        $data['sort'] = 'popular';
        $data['range'] = array(0,100000);
        
        //$offset=NULL;
        //$item_per_page=NULL;
        
        //$products = $this->category->get_brand_products($brandId, $offset, $limit = $item_per_page, $cond);
        $products=  $this->product->get_all_best_selling_product();
        $brnds = $this->brand->get_all();
        $brand = [];
        foreach($brnds as $bkey => $bdata):
            $brand[] = $bdata->title;
        endforeach;

        $data['products'] = $products;
        $data['brands'] = $brand;
        $range = array(0,100000);
        $data['range']=array('minimum'=>$range[0],'maximum'=>$range[01]);
        $data['master_sort'] =array('popular'=>'Popularity','lowestPrice'=>'Lowest Price','highestPrice'=>'Highest Price','new'=>'isNew');
        $data['tidiit_currency_simbol']=get_currency_simble_from_lat_long($latitude,$longitude);
        success_response_after_post_get($data);
    }
    
    function show_all_brands_post(){
        $userId=$this->post('userId');
        $UDID=$this->post('UDID');
        $deviceType=$this->post('deviceType');
        $deviceToken=$this->post('deviceToken');
        $latitude=$this->post('latitude');
        $longitude=$this->post('longitude');
        
        $defaultDataArr=array('UDID'=>$UDID,'deviceType'=>$deviceType,'deviceToken'=>$deviceToken,'latitude'=>$latitude,'longitude'=>$longitude);
        $isValideDefaultData=  $this->check_default_data($defaultDataArr);
        
        if(strtoupper(get_counry_code_from_lat_long($latitude,$longitude)) !='IN'){
            $this->response(array('error' => 'Invalid username or password,please try again.'), 400); return FALSE;
        }
        
        if($isValideDefaultData['type']=='fail'){
            $this->response(array('error' => $isValideDefaultData['message']), 400); return FALSE;
        }
        
        $brands = $this->brand->get_all();
        $data['brands'] = $brands;
        success_response_after_post_get($data);
    }
    
    function check_default_data($dataArr){
        $validateArr=array('type'=>'success');
        if($dataArr['UDID']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide UDID.';
            return $validateArr;
        }
        
        if($dataArr['deviceToken']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide deviceToken.';
            return $validateArr;
        }
        
        if($dataArr['deviceType']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide deviceType.';
            return $validateArr;
        }
        
        if($dataArr['latitude']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide latitude.';
            return $validateArr;
        }
        
        if($dataArr['longitude']==""){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide longitude.';
            return $validateArr;
        }
        
        $countryShortName=  get_counry_code_from_lat_long($dataArr['latitude'], $dataArr['longitude']);
        //die($countryShortName);
        if($countryShortName==FALSE){
            $validateArr['type']='fail';
            $validateArr['message']='Please provide valid latitude and longitude';
            return $validateArr;
        }
        return $validateArr;
    }
}