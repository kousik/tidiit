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
        //echo $_SERVER['SERVER_ADDR'];print_r($_GET);die;
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
            $categoryId = base64_decode($_GET['cpid'])/226201;
            $currentCat = $this->Category_model->get_details_by_id($categoryId);
            $currCat = $currentCat[0];
            if(my_seo_freindly_url($currCat->categoryName) != $name):
                $this->session->set_flashdata('error', 'Invalid location. Please click proper category link!');
                redirect(BASE_URL.'products/ord-message');
            endif;
        endif;
        $data['currCat'] = $currCat;
        $is_last = $this->Category_model->is_category_last($categoryId);
        $data['widget_cats'] = $this->Category_model->get_parent_categories($categoryId);
        if(!$is_last):
            $data['body_cats'] = $this->Category_model->display_children_categories($categoryId);
        endif;
        $data['is_last'] = $is_last;
        $data['s_widget_cats'] = $data['widget_cats'];
        
        
        unset($_GET['cpid']);
        $cond = array();
        $data['sort'] = 'popular';
        $data['brand'] = array();
        $data['range'] = array(0,100000);
        foreach($_GET as $key => $get):
            if($key == 'sort' && $get):
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
        endforeach;
        
        $item_per_page = 8;
        
        if(isset($_GET['page']) && $_GET['page']):
            $offset = ($_GET['page'] * $item_per_page);
        else:
            $offset = 0;
        endif;
        
        if(isset($_GET['cls']) && $_GET['cls']):
            $offset = 0;
        else:
            $offset = $offset;
        endif;
        
        $products = $this->Category_model->get_children_categories_products($categoryId, $offset, $limit = $item_per_page, $cond);
        $total_rows = $this->Category_model->get_children_categories_products($categoryId, 0, false, $cond);
        $tr = (isset($total_rows['products'])?$total_rows['products']:false);
        $totalrows = (!empty($tr)?count($tr):0);
        $total_pages = ceil($totalrows/$item_per_page);
        $data['total_pages'] = $total_pages;
        $products['brands'] = $total_rows['brands'];
        $data['products'] = $products;
        

        if($total_pages <= $item_per_page):
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
            $brnd = isset($_GET['brand'])?explode("|", $_GET['brand']):array();
            $data['products'] = $this->create_product_view($products);
            $data['brands'] = $this->display_brands_view($total_rows['brands'],$brnd);
            echo json_encode($data);die;
        endif;
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
    
    
    function create_product_view($products){
        ob_start();
        if(isset($products['products']) && $products['products']):?>
        <?php foreach($products['products'] as $pkey => $pro):?>
        <div class="item col-md-3 col-sm-3 col-xs-6">
            <div class="prodct_box prodct_box1"> 
                <?php if($pro->qty < $pro->minQty):?>
                <a href="javascript//">
                <?php else :?>
                <a href="<?=BASE_URL.'product/details/'.base64_encode($pro->productId);?>"> 
                <?php endif;?> 
                    <img src="<?=HOME_LISTING.$pro->pImage;?>" class="img-responsive" />
                    <?php if($pro->qty < $pro->minQty):?>
                    <div class="ch-info">
                        <h3><i class="fa fa-thumbs-o-down"></i> &nbsp;Out of Stock</h3>
                    </div>
                    <?php else :?>
                    <div class="ch-info">
                        <h3><i class="fa fa-truck"></i> &nbsp;Add to Truck</h3>
                    </div>
                    <?php endif;?>
                </a>
                <p><?=$pro->title;?></p>
                <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                </ul>
                <p><?=$pro->lowestPrice.' - '.$pro->heighestPrice;?></p>
                <p>
                    <?php if($pro->qty < $pro->minQty):?>
                        <a href="javascript//">View Details &nbsp;<i class="fa fa-caret-right"></i></a>
                    <?php else:?>
                    <a href="<?=BASE_URL.'product/details/'.base64_encode($pro->productId);?>">View Details &nbsp;<i class="fa fa-caret-right"></i></a>
                    <?php endif;?>
                </p>
            </div>
        </div>
        <?php endforeach;?>
        <?php endif;
        $out2 = ob_get_contents();
        ob_end_clean();
        return $out2;
    }
    
    
    function display_brands_view($brands, $brand){
        ob_start();
        if(isset($brands) && $brands):?>
        <div class="brand_sec">
            <div class="sub_hdng">
                <h3>Brand</h3>
            </div>
            <ul id="brand" class="rand_list">
                <?php
                    foreach($brands as $bkey => $brnd):?>
                <li>
                    <input type="checkbox" class="brandsort" value="<?=$brnd?>" <?php if(in_array($brnd, $brand)):?>checked="checked" <?php endif;?> />
                    <span><?=$brnd?></span>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
          <?php endif;
        $out2 = ob_get_contents();
        ob_end_clean();
        return $out2;
    }
    
}