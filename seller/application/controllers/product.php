<?php
class Product extends MY_Controller{
    public function __construct(){
            parent::__construct();
            $this->load->model('Product_model');
            $this->load->model('Option_model');
            $this->load->model('Category_model');
            $this->load->model('Brand_model');
            parse_str($_SERVER['QUERY_STRING'],$_GET);
        $this->db->cache_off();
    }

    public function index(){
            redirect(base_url().'admin');
    }

    public function viewlist(){
        $data=$this->_get_logedin_template();
        $dataArr=$this->Product_model->get_all_admin();
        //pre($dataArr);die;
        $data['DataArr']=$dataArr;
        $this->load->view('product_list',$data);
    }

    function add_product($categoryId=""){
        $this->config->load('product');
        $data=$this->_get_logedin_template();
        $this->load->model('User_model');
        if(empty($categoryId)){
            //$productPageType=$pageTypeData[0]->pageType;
            $menuArr=array();
            $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
            $data['categoryData']=$TopCategoryData;  //$menuArr;
            $viewPage='add_product';
            $this->load->view($viewPage,$data);
        }else{
            //echo '$categoryId  ='.$categoryId;die;
            $menuArr=array();
            $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
            $categoryDetailsArr = $this->Category_model->get_details_by_id($categoryId);
            $data['brandArr']=$this->Brand_model->get_all();
            $data['categoryId']=$categoryId;
            $productPageTypeArr=$this->Product_model->get_page_template();
            $rootCategory = $this->Category_model->get_root_category($categoryId);

            if($categoryDetailsArr[0]->option_ids):
                $pieces = explode(",", $categoryDetailsArr[0]->option_ids);
                $options = $this->Option_model->get_bulk_options($pieces);
                $data['options'] = $options;
                $data['options_arrenge'] = $pieces;
            else:
                $data['options'] = '';
            endif;
            $templateName='';
            foreach($productPageTypeArr As $k){
                if($k->productViewTemplateID==$categoryDetailsArr[0]->view){
                    $templateName=$k->templateFileName;
                    break;
                }
            }
            //pre($productPageTypeArr[$categoryDetailsArr[0]->view]);die;
            if($templateName && $templateName == "mobile.php"):
                //$viewPage='add_product_'.$templateName;
                $viewPage='add_product_common.php';
            else:
                $viewPage='add_product_common.php';
            endif;
            //echo substr($templateName,0,-4);die;
            $data['productPageType']=substr($templateName,0,-4);
            //echo $viewPage;die;
            if (file_exists(APPPATH."views/$viewPage")){
                $this->load->view($viewPage,$data);
            }else{
                $this->session->set_flashdata('Message',"Selected category has no product,Please select different category.");
                redirect(BASE_URL.'product/add_product/');
            }
        }
    }

    public function add_mobile(){
        //pre($_POST);die;
        $retDataArr=$this->default_data_validate();
        if($retDataArr['status']=='fail'){
            $this->session->set_flashdata('Message',$retDataArr['data']);
            redirect(BASE_URL.'product/add_product/');
            //echo json_encode(array('result'=>'bad','msg'=>$retDataArr['data']));
        }else{
            //pre($_POST);die;
            $config=array(
                array('field'   => 'mobileBoxContent[]','label'   => 'Items in the box','rules'   => 'trim|required|xss_clean'),  
                array('field'   => 'model','label'   => 'Model','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'brandId','label'   => 'Brand','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'noOfSims','label'   => 'Nos of SIM in the box','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'color','label'   => 'Mobile Color','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'os','label'   => 'Operating System','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'qty','label'   => 'Quantity','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'minQty','label'   => 'Minimum Quantity','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'bulkQty','label'   => 'First Quantity Range','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'price','label'   => 'First Price Range','rules'   => 'trim|required|xss_clean'),
            );
            $type='mobile';
            $mobileBoxContent=$this->input->post('mobileBoxContent');
            //$mobileBoxContent=$this->input->post('mobileBoxContent[]',TRUE);
            $model=$this->input->post('model',TRUE);
            $brandId=$this->input->post('brandId',TRUE);
            $noOfSims=$this->input->post('noOfSims',TRUE);
            $color=$this->input->post('color',TRUE);
            $mobileOtherFeatures=$this->input->post('mobileOtherFeatures',TRUE);
            $screenSize=$this->input->post('screenSize',TRUE);
            $displayResolution=$this->input->post('displayResolution',TRUE);
            $displayType=$this->input->post('displayType',TRUE);
            $pixelDensity=$this->input->post('pixelDensity',TRUE);
            $os=$this->input->post('os',TRUE);
            $osVersion=$this->input->post('osVersion',TRUE);
            $multiLanguages=$this->input->post('multiLanguages',TRUE);
            $mobileRearCamera=$this->input->post('mobileRearCamera',TRUE);
            $mobileFlash=$this->input->post('mobileFlash',TRUE);
            $frontCamera=$this->input->post('frontCamera',TRUE);
            $mobileOtherCameraFeatures=$this->input->post('mobileOtherCameraFeatures',TRUE);
            $mobileConnectivity=$this->input->post('mobileConnectivity',TRUE);
            $processorSpeed=$this->input->post('processorSpeed',TRUE);
            $processorCores=$this->input->post('processorCores',TRUE);
            $processorBrand=$this->input->post('processorBrand',TRUE);
            $ram=$this->input->post('ram',TRUE);
            $internalMemory=$this->input->post('internalMemory',TRUE);
            $expandableMemory=$this->input->post('expandableMemory',TRUE);
            $memoryCardSlot=$this->input->post('memoryCardSlot',TRUE);
            $batteryCapacity=$this->input->post('batteryCapacity',TRUE);
            $batteryType=$this->input->post('batteryType',TRUE);
            $talkTime=$this->input->post('talkTime',TRUE);
            $standbyTime=$this->input->post('standbyTime',TRUE);
            $warrantyType=$this->input->post('warrantyType',TRUE);
            $warrantyDuration=$this->input->post('warrantyDuration',TRUE);
            $categoryId=$this->input->post('categoryId',TRUE);
            $taxable=$this->input->post('taxable',TRUE);
            $minQty=$this->input->post('minQty',TRUE);
            $qty=$this->input->post('qty',TRUE);
            $length=$this->input->post('length',TRUE);
            $width=$this->input->post('width',TRUE);
            $height=$this->input->post('height',TRUE);
            $lengthClass=$this->input->post('lengthClass',TRUE);
            $weight=$this->input->post('weight',TRUE);
            $weightClass=$this->input->post('weightClass',TRUE);
            $bulkQty=$this->input->post('bulkQty',TRUE);
            $price=$this->input->post('price',TRUE);
            $total_price_row_added=$this->input->post('total_price_row_added',TRUE);

            $status=$this->input->post('status',TRUE);
            $this->form_validation->set_rules($config); 

            if($this->form_validation->run() == FALSE){
                $data=validation_errors();
                //pre($data);die;
                //return array('status'=>'fail','data'=>$data);
                $this->session->set_flashdata('Message',$data);
                redirect(BASE_URL.'product/add_product/');
            }else{
                $priceArr=array();
                $lowestPrice=$price;
                $priceArr[]=array('qty'=>$bulkQty,'price'=>$price);
                for($i=1;$i<$total_price_row_added;$i++){
                    $bulkQty=$this->input->post('bulkQty_'.$i,TRUE);
                    $price=$this->input->post('price_'.$i,TRUE);

                    if($bulkQty=="" || $price==""){
                        //echo '$bulkQty= '.$bulkQty.'  === $price '.$price;die;
                        $this->session->set_flashdata('Message','Please fill the price and relted quanity');
                        redirect(BASE_URL.'product/add_product/');
                    }
                    $priceArr[]=array('qty'=>$bulkQty,'price'=>$price);
                }
                usort($priceArr, 'sortingProductPriceArr');
                $heighestPrice=$price;
                $minQty=$priceArr[count($priceArr)-1]['qty'];
                //$dataArr=$retDataArr['data'];
                $dataArr=array('mobileBoxContent'=>  implode(',', $mobileBoxContent),'model'=>$model,'noOfSims'=>$noOfSims,'color'=>$color,
                    'mobileOtherFeatures'=>$mobileOtherFeatures,'screenSize'=>$screenSize,'displayResolution'=>$displayResolution,'displayType'=>$displayType,
                    'pixelDensity'=>$pixelDensity,'os'=>$os,'osVersion'=>$osVersion,'multiLanguages'=>$multiLanguages,'mobileRearCamera'=>$mobileRearCamera,
                    'mobileFlash'=>$mobileFlash,'frontCamera'=>$frontCamera,'mobileOtherCameraFeatures'=>$mobileOtherCameraFeatures,'batteryType'=>$batteryType,
                    'processorSpeed'=>$processorSpeed,'processorCores'=>$processorCores,'ram'=>$ram,
                    'processorBrand'=>$processorBrand,'internalMemory'=>$internalMemory,'expandableMemory'=>$expandableMemory,'memoryCardSlot'=>$memoryCardSlot,
                    'batteryCapacity'=>$batteryCapacity,'talkTime'=>$talkTime,'standbyTime'=>$standbyTime,'warrantyType'=>$warrantyType,'taxable'=>$taxable,
                    'warrantyDuration'=>$warrantyDuration,'minQty'=>$minQty,'qty'=>$qty,'length'=>$length,'width'=>$width,
                    'height'=>$height,'lengthClass'=>$lengthClass,'weight'=>$weight,'weightClass'=>$weightClass,'status'=>$status,
                    'lowestPrice'=>$lowestPrice,'heighestPrice'=>$heighestPrice);
            $ParrentDataArr=$this->Category_model->get_all_parrent_details($categoryId);
            pre($ParrentDataArr);
            if($ParrentDataArr[0]->secondParentcategoryId==""){
                $dataArr['CategoryID1']=$ParrentDataArr[0]->firstParentcategoryId;
                $dataArr['CategoryID2']=$categoryId;
            }else{
                $dataArr['CategoryID1']=$ParrentDataArr[0]->secondParentcategoryId;
                $dataArr['CategoryID2']=$ParrentDataArr[0]->firstParentcategoryId;
                $dataArr['CategoryID3']=$categoryId;
            }
                if(!empty($mobileConnectivity)){$dataArr['mobileConnectivity']=implode(',', $mobileConnectivity);}
                $tag=$retDataArr['data']['tag'];
                unset($retDataArr['data']['tag']);
                $mobileDataArr=array_merge($retDataArr['data'],$dataArr);
                //pre($mobileDataArr);die;
                //echo base64_encode(serialize($mobileDataArr));die;
                //pre($priceArr);die;

                $config['upload_path'] =$this->config->item('ResourcesPath').'product/original/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['file_name']	= strtolower(my_seo_freindly_url($mobileDataArr['title'])).'-'.rand(1,9).'-'.time();
                $config['max_size']	= '2047';
                $config['max_width'] = '2500';
                $config['max_height'] = '2500';
                //$config['max_width']  = '1024';
                //$config['max_height']  = '1024';
                $upload_files=array();
                $this->load->library('upload');
                //pre($_FILES);die;
                $blank=0;
                foreach ($_FILES as $fieldname => $fileObject){  //fieldname is the form field name
                    //pre($fileObject);
                    if (!empty($fileObject['name'])){
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload($fieldname)){
                            foreach($upload_files AS $k){
                                    @unlink($this->config->item('ResourcesPath').'product/original/'.$k);
                            }
                            $errors = $this->upload->display_errors();
                            pre($errors);die;
                            $this->session->set_flashdata('Message',$errors);
                            redirect(base_url().'product/add_product/');
                        }
                        else
                        {
                             // Code After Files Upload Success GOES HERE
                            $data=$this->upload->data();
                            $this->product_image_resize($data['file_name']);
                            $upload_files[]=$data['file_name'];
                        }
                    }else{                            
                        if(substr($fieldname,-1)==1)
                            $blank++;
                    }
                }
            }
            //die($blank.' = rrr');
            //if($blank==1 || $blank==2){
            if($blank==1){
                @unlink($this->config->item('ResourcesPath').'product/original/'.$upload_files[0]);
                $this->session->set_flashdata('Message','Please upload at least two image for this product.');
                redirect(BASE_URL.'product/add_product/');
            }
            //pre($upload_files);die;

            $productId=$this->Product_model->add($mobileDataArr);
            //$productId=1;
            //echo 'product added done.<br>';
            $imageBatchArr=array();
            foreach ($upload_files AS $k =>$v){
                $imageBatchArr[]=array('productId'=>$productId,'image'=>$v);
            }
            $this->Product_model->add_image($imageBatchArr);
            //echo 'image uploaded done.<br>';
            $productTagArr=array('productId'=>$productId,'tagStr'=>$tag);
            $this->Product_model->add_product_tag($productTagArr);
            //echo 'tag added done.<br>';
            
            $newPriceArr=array();
            foreach ($priceArr AS $k){
                $k['productId']=$productId;
                $newPriceArr[]=$k;
            }
            //pre($newPriceArr);die;
            $this->Product_model->add_product_price($newPriceArr);
            //echo 'price added done.<br>';
            $this->Product_model->add_product_category(array('productId'=>$productId,'categoryId'=>$categoryId));
            //echo 'product category done.<br>';
            $this->Product_model->add_product_owner(array('productId'=>$productId,'userId'=>$this->session->userdata('FE_SESSION_VAR')));
            $this->Product_model->add_brand(array('productId'=>$productId,'brandId'=>$brandId));

            $this->session->set_flashdata('Message','Product added successfully.');
            redirect(base_url().'product/viewlist');
        }

    }
    
    public function edit_product($productId){
        $productDetails=$this->Product_model->details($productId/999999);
        $categoryDetailsArr=$this->Category_model->get_details_by_id($productDetails[0]->categoryId);
        $productPageTypeArr=$this->Product_model->get_page_template();
        //pre($productPageTypeArr);die;
        $templateName='';
        foreach($productPageTypeArr As $k){
            if($k->productViewTemplateID==$categoryDetailsArr[0]->view){
                $templateName=$k->templateFileName;
                break;
            }
        }

        if($templateName && $templateName == "mobile.php"):
            //$viewPage =$templateName;
            $viewPage = 'common.php';
        else:
            $viewPage = 'common.php';
        endif;

        $functionName='edit_'.substr($viewPage,0,-4);
        $this->$functionName($productId/999999);
    }
    
    function edit_mobile($productId){
        $this->config->load('product');
        $data=$this->_get_logedin_template();
        $this->load->model('User_model');
        $productPriceArr=$this->Product_model->get_products_price($productId);
        $productImageArr=$this->Product_model->get_products_images($productId);
        //pre($productImageArr); //die;
        $productDetails=$this->Product_model->details($productId);
        $allTagArr=$this->Product_model->get_tag_by_product_id($productId);
        $details=$productDetails[0];
        //pre($details);
        $data['detail']=$details;
        $data['bulkQty']=$productPriceArr[0]->qty;
        $data['bulkPrice']=$productPriceArr[0]->price;
        $data['totalPriceRowAdded']=count($productPriceArr);
        $data['productPriceView']=  $this->get_price_data_for_edit($productPriceArr,'mobile');
        $data['brandArr']=$this->Brand_model->get_all();
        $data['tag']=$allTagArr->productTag;
        $data['productImageArr']=$productImageArr;
        $data['categoryId']=$productDetails[0]->categoryId;
        $data['productPageType']='mobile';
        $this->load->view('edit_mobile',$data);
    }
    
    public function edit_mobile_submit(){
        //pre($_POST);die;
        $retDataArr=$this->default_data_validate();
        if($retDataArr['status']=='fail'):
            $this->session->set_flashdata('Message',$retDataArr['data']);
            redirect(BASE_URL.'product/add_product/');
            //echo json_encode(array('result'=>'bad','msg'=>$retDataArr['data']));
        else:
            $config=array(
                array('field'   => 'mobileBoxContent[]','label'   => 'Items in the box','rules'   => 'trim|required|xss_clean'),  
                array('field'   => 'model','label'   => 'Model','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'brandId','label'   => 'Brand','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'noOfSims','label'   => 'Nos of SIM in the box','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'color','label'   => 'Mobile Color','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'os','label'   => 'Operating System','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'qty','label'   => 'Quantity','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'minQty','label'   => 'Minimum Quantity','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'bulkQty','label'   => 'First Quantity Range','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'price','label'   => 'First Price Range','rules'   => 'trim|required|xss_clean'),
            );
            $type='mobile';
            $mobileBoxContent=$this->input->post('mobileBoxContent');
            //$mobileBoxContent=$this->input->post('mobileBoxContent[]',TRUE);
            $model=$this->input->post('model',TRUE);
            $brandId=$this->input->post('brandId',TRUE);
            $noOfSims=$this->input->post('noOfSims',TRUE);
            $color=$this->input->post('color',TRUE);
            $mobileOtherFeatures=$this->input->post('mobileOtherFeatures',TRUE);
            $screenSize=$this->input->post('screenSize',TRUE);
            $displayResolution=$this->input->post('displayResolution',TRUE);
            $displayType=$this->input->post('displayType',TRUE);
            $pixelDensity=$this->input->post('pixelDensity',TRUE);
            $os=$this->input->post('os',TRUE);
            $osVersion=$this->input->post('osVersion',TRUE);
            $multiLanguages=$this->input->post('multiLanguages',TRUE);
            $mobileRearCamera=$this->input->post('mobileRearCamera',TRUE);
            $mobileFlash=$this->input->post('mobileFlash',TRUE);
            $frontCamera=$this->input->post('frontCamera',TRUE);
            $mobileOtherCameraFeatures=$this->input->post('mobileOtherCameraFeatures',TRUE);
            $mobileConnectivity=$this->input->post('mobileConnectivity',TRUE);
            $processorSpeed=$this->input->post('processorSpeed',TRUE);
            $processorCores=$this->input->post('processorCores',TRUE);
            $processorBrand=$this->input->post('processorBrand',TRUE);
            $ram=$this->input->post('ram',TRUE);
            $internalMemory=$this->input->post('internalMemory',TRUE);
            $expandableMemory=$this->input->post('expandableMemory',TRUE);
            $memoryCardSlot=$this->input->post('memoryCardSlot',TRUE);
            $batteryCapacity=$this->input->post('batteryCapacity',TRUE);
            $batteryType=$this->input->post('batteryType',TRUE);
            $talkTime=$this->input->post('talkTime',TRUE);
            $standbyTime=$this->input->post('standbyTime',TRUE);
            $warrantyType=$this->input->post('warrantyType',TRUE);
            $warrantyDuration=$this->input->post('warrantyDuration',TRUE);
            $categoryId=$this->input->post('categoryId',TRUE);
            $taxable=$this->input->post('taxable',TRUE);
            $minQty=$this->input->post('minQty',TRUE);
            $qty=$this->input->post('qty',TRUE);
            $length=$this->input->post('length',TRUE);
            $width=$this->input->post('width',TRUE);
            $height=$this->input->post('height',TRUE);
            $lengthClass=$this->input->post('lengthClass',TRUE);
            $weight=$this->input->post('weight',TRUE);
            $weightClass=$this->input->post('weightClass',TRUE);
            $bulkQty=$this->input->post('bulkQty',TRUE);
            $price=$this->input->post('price',TRUE);
            $productId=$this->input->post('productId',TRUE);
            $total_price_row_added=$this->input->post('total_price_row_added',TRUE);

            //$status=$this->input->post('status',TRUE);
            $this->form_validation->set_rules($config); 
            if($this->form_validation->run() == FALSE):
                $data=validation_errors();
                //pre($data);die;
                //return array('status'=>'fail','data'=>$data);
                $this->session->set_flashdata('Message',$data);
                redirect(BASE_URL.'product/add_product/');
            else:
                $priceArr=array();
                $lowestPrice=$price;
                $priceArr[]=array('qty'=>$bulkQty,'price'=>$price);
                for($i=1;$i<$total_price_row_added;$i++){
                    $bulkQty=$this->input->post('bulkQty_'.$i,TRUE);
                    $price=$this->input->post('price_'.$i,TRUE);

                    if($bulkQty=="" || $price==""){
                        //echo '$bulkQty= '.$bulkQty.'  === $price '.$price;die;
                        $this->session->set_flashdata('Message','Please fill the price and relted quanity');
                        redirect(BASE_URL.'product/add_product/');
                    }
                    $priceArr[]=array('qty'=>$bulkQty,'price'=>$price);
                }
                usort($priceArr, 'sortingProductPriceArr');
                //pre($priceArr);die;
                $newPriceArr=array();
                foreach ($priceArr AS $k):
                    $k['productId']=$productId;
                    $newPriceArr[]=$k;
                endforeach;
                //pre($newPriceArr);die;
                $this->Product_model->edit_product_price($newPriceArr,$productId);
                
                $heighestPrice=$price;
                $minQty=$priceArr[count($priceArr)-1]['qty'];
                //$dataArr=$retDataArr['data'];
                $dataArr=array('mobileBoxContent'=>  implode(',', $mobileBoxContent),'model'=>$model,'noOfSims'=>$noOfSims,'color'=>$color,
                    'mobileOtherFeatures'=>$mobileOtherFeatures,'screenSize'=>$screenSize,'displayResolution'=>$displayResolution,'displayType'=>$displayType,
                    'pixelDensity'=>$pixelDensity,'os'=>$os,'osVersion'=>$osVersion,'multiLanguages'=>$multiLanguages,'mobileRearCamera'=>$mobileRearCamera,
                    'mobileFlash'=>$mobileFlash,'frontCamera'=>$frontCamera,'mobileOtherCameraFeatures'=>$mobileOtherCameraFeatures,'batteryType'=>$batteryType,
                    'processorSpeed'=>$processorSpeed,'processorCores'=>$processorCores,'ram'=>$ram,
                    'processorBrand'=>$processorBrand,'internalMemory'=>$internalMemory,'expandableMemory'=>$expandableMemory,'memoryCardSlot'=>$memoryCardSlot,
                    'batteryCapacity'=>$batteryCapacity,'talkTime'=>$talkTime,'standbyTime'=>$standbyTime,'warrantyType'=>$warrantyType,'taxable'=>$taxable,
                    'warrantyDuration'=>$warrantyDuration,'minQty'=>$minQty,'qty'=>$qty,'length'=>$length,'width'=>$width,
                    'height'=>$height,'lengthClass'=>$lengthClass,'weight'=>$weight,'weightClass'=>$weightClass,
                    'lowestPrice'=>$lowestPrice,'heighestPrice'=>$heighestPrice);
                $ParrentDataArr=$this->Category_model->get_all_parrent_details($categoryId);
                //pre($ParrentDataArr);
                if($ParrentDataArr[0]->secondParentcategoryId==""){
                    $dataArr['CategoryID1']=$ParrentDataArr[0]->firstParentcategoryId;
                    $dataArr['CategoryID2']=$categoryId;
                }else{
                    $dataArr['CategoryID1']=$ParrentDataArr[0]->secondParentcategoryId;
                    $dataArr['CategoryID2']=$ParrentDataArr[0]->firstParentcategoryId;
                    $dataArr['CategoryID3']=$categoryId;
                }
                if(!empty($mobileConnectivity)){$dataArr['mobileConnectivity']=implode(',', $mobileConnectivity);}
                $tag=$retDataArr['data']['tag'];
                unset($retDataArr['data']['tag']);
                $mobileDataArr=array_merge($retDataArr['data'],$dataArr);
                
                $config['upload_path'] =$this->config->item('ResourcesPath').'product/original/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['file_name']	= strtolower(my_seo_freindly_url($mobileDataArr['title'])).'-'.rand(1,9).'-'.time();
                $config['max_size']	= '2047';
                $config['max_width'] = '1550';
                $config['max_height'] = '1550';
                //$config['max_width']  = '1024';
                //$config['max_height']  = '1024';
                $upload_files=array();
                $this->load->library('upload');
                //pre($_FILES);die;
                $blank=0;
                foreach ($_FILES as $fieldname => $fileObject):
                    //fieldname is the form field name
                    //pre($fileObject);
                    if (!empty($fileObject['name'])):
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload($fieldname)):
                            foreach($upload_files AS $k):
                                @unlink($this->config->item('ResourcesPath').'product/original/'.$k);
                            endforeach;
                            $errors = $this->upload->display_errors();
                            //pre($errors);die;
                            $this->session->set_flashdata('Message',$errors);
                            redirect(base_url().'product/add_product/');
                        else:
                             // Code After Files Upload Success GOES HERE
                            $currgImgNo=substr($fieldname,3);
                            $oldUploadedFileName=$this->input->post('oldImgFile'.$currgImgNo,TRUE);
                            $this->Product_model->remove_product_by_file($oldUploadedFileName,$productId);
                            $this->delete_product_file($oldUploadedFileName);
                            $data=$this->upload->data();
                            $this->product_image_resize($data['file_name']);
                            $upload_files[]=$data['file_name'];
                        endif;
                    else:
                        if(substr($fieldname,-1)==1)
                            $blank++;
                    endif; 
                endforeach;
                
                $this->Product_model->edit($mobileDataArr,$productId);
                
                //$productId=1;
                //echo 'product added done.<br>';
                $imageBatchArr=array();
                if(!empty($upload_files)):
                    foreach ($upload_files AS $k =>$v):
                        $imageBatchArr[]=array('productId'=>$productId,'image'=>$v);
                    endforeach;
                endif;
                
                
                if(!empty($imageBatchArr)):
                    //pre($imageBatchArr);die;
                    $this->Product_model->edit_image_product($imageBatchArr,$productId);
                endif;
                //pre($imageBatchArr);die;
                $productTagArr=array('productId'=>$productId,'tagStr'=>$tag);
                $this->Product_model->edit_product_tag($productTagArr);
                
                
                //$this->Product_model->add_product_category(array('categoryId'=>$categoryId));
                //$this->Product_model->add_product_owner(array('productId'=>$productId,'userId'=>$this->session->userdata('FE_SESSION_VAR')));
                $this->Product_model->edit_brand(array('brandId'=>$brandId),$productId);

                $this->session->set_flashdata('Message','Product updated successfully.');
                redirect(base_url().'product/viewlist');
            endif;
        endif;
    }
    
    function update_stock(){
        $productId=$this->input->post('updateQuantityProductId',TRUE);
        $newQty=  $this->input->post('newQty',TRUE);
        $productDetails=$this->Product_model->details($productId);
        $newQty+=$productDetails[0]->qty;
        $this->Product_model->edit(array('qty'=>$newQty),$productId);
        $this->session->set_flashdata('Message','Stock updated successfully.');
        redirect(BASE_URL.'product/viewlist');
    }

    function delete($productId){
        $ProductImages=$this->Product_model->get_products_images($productId);
        $this->Product_model->delete($productId);
        $this->Option_model->delete_product_option_values($productId);
        foreach($ProductImages as $k){
                $this->delete_product_file($k->Image);
        }
        $this->session->set_flashdata('Message','Selected product/s deleted successfully.');
        redirect(base_url().'product/viewlist');
    }
    
    function change_status($productId,$action){
        if($action==0){
            $this->Product_model->change_status($productId,'active');
            $this->session->set_flashdata('Message','Selected product activated successfully.');
        }else{
            $this->Product_model->change_status($productId,'inactive');
            $this->session->set_flashdata('Message','Selected product inactivated successfully.');
        }
        redirect(base_url().'product/viewlist');
    }

    public function default_data_validate(){
        //pre($_POST);die;
        $config=array(
            array('field'   => 'title','label'   => 'Product Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'shortDescription','label'   => 'Product short Description','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'description','label'   => 'Product Description','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'metaTitle','label'   => 'Product Meta Title','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'metaDescription','label'   => 'Product Meta Description','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'metaKeyword','label'   => 'Product Meta Keywords','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'tag','label'   => 'Product Tags','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'categoryId','label'   => 'Product Category','rules'   => 'trim|required|xss_clean'),
        );
        $this->form_validation->set_rules($config);

        if($this->form_validation->run() == FALSE){
            $data=validation_errors();
            //pre('JJJJJJJJJJJJJJJJ'.$data);die;
            return array('status'=>'fail','data'=>$data);
        }else{
            $title=$this->input->post('title',TRUE);
            $shortDescription=$this->input->post('shortDescription',TRUE);
            $description=$this->input->post('description',TRUE);
            $tag=$this->input->post('tag',TRUE);
            $metaKeyWord=$this->input->post('metaKeyword',TRUE);
            $metaTitle=$this->input->post('metaTitle',TRUE);
            $metaDescription=$this->input->post('metaDescription',TRUE);
            $categoryId=$this->input->post('categoryId',TRUE);

            $dataArr=array('title'=>$title,'shortDescription'=>$shortDescription,'description'=>$description,'tag'=>$tag,'metaKeyword'=>$metaKeyWord,'metaTitle'=>$metaTitle,'metaDescription'=>$metaDescription,'categoryId'=>$categoryId);
            return array('status'=>'success','data'=>$dataArr);
        }
    }

    public function product_image_resize($fileName,$action='uploaded'){
            /// process the image for all size. and water mark
            $PHOTOPATH=$this->config->item('ResourcesPath').'product/';
            $OriginalPath=$PHOTOPATH.'original/';
            //echo '$OriginalPath :- '.$OriginalPath.'<br>';
            $OriginalFilePath=$OriginalPath.$fileName;
            //echo '$OriginalFilePath :- '.$OriginalFilePath.'<br>';

            /// ************100X100***************
            $config['image_library'] = 'gd2';
            $config['source_image'] = $OriginalFilePath;
            $config['new_image'] = $PHOTOPATH. '100X100/';
            $config['width'] = 100;
            $config['height'] = 100;
            $config['maintain_ratio'] = true;
            $config['master_dim'] = 'auto';
            $config['create_thumb'] = FALSE;
            $this->image_lib->initialize($config);
            $this->load->library('image_lib', $config);
            if($this->image_lib->resize()){
                    //echo '<br>thumb done for 100X100.';
            }else{
                    $this->image_lib->display_errors();
            }

            $this->image_lib->clear();

            /// ************200X200*************** at index left panel
            $config['image_library'] = 'gd2';
            $config['source_image'] = $OriginalFilePath;
            $config['new_image'] = $PHOTOPATH. '200X200/';
            $config['width'] = 200;
            $config['height'] = 200;
            $config['maintain_ratio'] = true;
            $config['master_dim'] = 'auto';
            $config['create_thumb'] = FALSE;
            $this->image_lib->initialize($config);
            $this->load->library('image_lib', $config);
            if($this->image_lib->resize()){
                    //echo '<br>thumb done for 200X200.';
            }else{
                    $this->image_lib->display_errors();
            }

            $this->image_lib->clear();


            /// this thum crate for 230X230 ==> it is at index page listing
            $config['image_library'] = 'gd2';
            $config['source_image'] = $OriginalFilePath;
            $config['new_image'] = $PHOTOPATH . '230X230/'; 
            $config['width'] = 230;
            $config['height'] = 230;
            $config['maintain_ratio'] = true;
            $config['master_dim'] = 'auto';
            $config['create_thumb'] = FALSE;

            $this->image_lib->initialize($config);
            $this->load->library('image_lib', $config);

            if($this->image_lib->resize()){
                    //echo '<br>thumb done for 230X230.';
            }else{
                    $this->image_lib->display_errors();
            }

            $this->image_lib->clear();

            /// this thumb create for 450X450 at details page
            $config['image_library'] = 'gd2';
            $config['source_image'] = $OriginalFilePath;
            $config['new_image'] = $PHOTOPATH . '450X450/'; //'/path/to/new_image.jpg';
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 540;
            $config['height'] = 450;
            $config['master_dim'] = 'auto';

            $this->image_lib->initialize($config);
            $this->load->library('image_lib', $config);

            if($this->image_lib->resize()){
                    //echo '<br>thumb done for 450X450.';
            }else{
                    $this->image_lib->display_errors();
            }

            $this->image_lib->clear();

            /// this thum crate for 700X700 at shoppign cart
            $config['image_library'] = 'gd2';
            $config['source_image'] = $OriginalFilePath;
            $config['new_image'] = $PHOTOPATH . '700X700/'; //'/path/to/new_image.jpg';
            $config['create_thumb'] = FALSE;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 700;
            $config['height'] = 700;
            $config['master_dim'] = 'auto';

            $this->image_lib->initialize($config);
            $this->load->library('image_lib', $config);

            if($this->image_lib->resize()){
                    //echo '<br>thumb done for 700X700.';
            }else{
                    $this->image_lib->display_errors();
            }

            $this->image_lib->clear();
            //die('end fo rproudct iamge upload');
    }

    function manual_batch_image_resize($new_width_height_size=""){
        if($new_width_height_size==""){
            return FALSE;
        }
        $new_width_height_size_arr=array();
        if($new_width_height_size!='admin'){
            $new_width_height_size_arr=  explode("X", $new_width_height_size);
        }else{
            $new_width_height_size_arr[0]='100';
            $new_width_height_size_arr[1]='80';
        }
        @set_time_limit(3500);
        //echo 'kkk';die;
        /// process the image for all size. and water mark
            $PHOTOPATH=$this->config->item('ResourcesPath').'product/';
            $OriginalPath=$PHOTOPATH.'original/';
            $AllOriginalImages=@scandir($OriginalPath,1);
            if(count($AllOriginalImages)>2){
                $tempNo=count($AllOriginalImages)-2;
                //echo '$tempNo => '.$tempNo.' total => '.count($AllOriginalImages).'<br>';
                for($i=0;$i<$tempNo;$i++){
                    $OriginalFilePath=$OriginalPath.$AllOriginalImages[$i];
                    //echo '$OriginalFilePath = > '.$OriginalFilePath.'<br>';
                    if(file_exists($OriginalFilePath)){
                        $MobileCategoyPhotoPath=$PHOTOPATH. $new_width_height_size.'/';
                        $MobileCategoyImage=$MobileCategoyPhotoPath.$AllOriginalImages[$i];
                        if(!file_exists($MobileCategoyImage)){
                            //echo $AllOriginalImages[$i].' for '.$new_width_height_size.' <br>';
                            $config['image_library'] = 'gd2';
                            $config['source_image'] = $OriginalFilePath;
                            $config['new_image'] = $MobileCategoyPhotoPath;
                            $config['width'] = $new_width_height_size_arr[0];
                            $config['height'] =$new_width_height_size_arr[1];
                            $config['maintain_ratio'] = true;
                            $config['master_dim'] = 'auto';
                            $config['create_thumb'] = FALSE;
                            $this->image_lib->initialize($config);
                            $this->load->library('image_lib', $config);
                            if($this->image_lib->resize()){
                                    //echo '<br>thumb done for 140X100.';
                            }else{
                                    $this->image_lib->display_errors();
                            }
                        }else{
                            echo $AllOriginalImages[$i].' for '.$new_width_height_size.' exists <br>';
                        }

                        $this->image_lib->clear();
                    }else{
                        echo '$OriginalFilePath  not exists = > <br>';
                    }

                }
            }
    }

    function update_new_category(){
        @set_time_limit(2500);
        $rs=$this->db->query("SELECT pc.* FROM `product` AS p JOIN `product_category` AS pc ON(p.productId=pc.productId)")->result();
        //pre($rs);die;
        foreach($rs AS $k){
            //pre($k->CategoryID);die;
            $CategoryArr=  array_reverse($this->get_category_arr($k->categoryId));
            $ColArr=array();
            foreach($CategoryArr as $p => $pV){
                $nt=$p+1;
                $ColArr['categoryId'.$nt]=$pV;
            }
            $this->Product_model->edit($ColArr,$k->productId);
        }
    }
    
    function get_category_arr($categoryId,$CategoryArr=array()){
        if(empty($CategoryArr)){
            $CategoryArr[]=$categoryId;
            $Details=$this->Category_model->get_details_by_id($categoryId);
            $CategoryArr[]=$Details[0]->parrentCategoryId;
            return $this->get_category_arr($Details[0]->parrentCategoryId,$CategoryArr);
        }else{
            $Details=$this->Category_model->get_details_by_id($categoryId);
            if($Details[0]->parrentCategoryId==0){
                return $CategoryArr;    
            }else{
                $CategoryArr[]=$Details[0]->parrentCategoryId;
                return $this->get_category_arr($Details[0]->parrentCategoryId,$CategoryArr);
            }
        }
    }
    
    public function delete_product_file($fileName,$original=FALSE){
        $PHOTOPATH=$this->config->item('ResourcesPath').'product/';
        if($original==FALSE){
            $OriginalPath=$PHOTOPATH.'original/';
            @unlink($OriginalPath.$fileName);
        }
        
        $AdminImage=$PHOTOPATH. '100X100/';
        @unlink($AdminImage.$fileName);
        $Image1=$PHOTOPATH. '200X200/';
        @unlink($Image1.$fileName);
        $Image2=$PHOTOPATH. '230X230/';
        @unlink($Image2.$fileName);
        $Image3=$PHOTOPATH. '450X450/';
        @unlink($Image3.$fileName);
        $Image4=$PHOTOPATH. '700X700/';
        @unlink($Image4.$fileName);
        return TRUE;
    }
    
    function get_price_data_for_edit($productPriceArr,$productPageType){
        $this->config->load('product');
        $priceRangeSettingsArr=$this->config->item('priceRangeSettings');
        $priceRangeSettingsDataArr=$priceRangeSettingsArr[$productPageType];
        $priceOption='';
        for($i=$priceRangeSettingsDataArr['start'];$i<$priceRangeSettingsDataArr['end'];$i=$i+$priceRangeSettingsDataArr['consistencyNo']){
            $priceOption.='<option value="'.$i.'?>">'.$i.'</option>';
        }
        ob_start();
        $price_row=0;
        foreach ($productPriceArr AS $k =>$v){ 
            if($k==0){continue;}
            $price_row++;?>
           <div class="col-sm-12"  style="padding:0;margin-top:5px;" id="remove_price_quantity_for_product_<?php echo $price_row;?>">
                   <div class="col-sm-12" style="padding:0;">
                           <div class="col-sm-6" style="padding:0;"><label style="margin-top:8px;" for="bulkQty_<?php echo $price_row;?>">Quantity <?php echo $price_row;?></label></div>
                           <div class="col-sm-6"  style="padding:0;">
                                   <select class="required" id="bulkQty_<?php echo $price_row;?>" name="bulkQty_<?php echo $price_row;?>" style="width:auto;">
                                           <option value="">-- Select --</option>
                                           <?php for($i=$priceRangeSettingsDataArr['start'];$i<$priceRangeSettingsDataArr['end'];$i=$i+$priceRangeSettingsDataArr['consistencyNo']){?>
                                           <option value="<?php echo $i;?>" <?php if($i==$v->qty){?>selected<?php }?>><?php echo $i;?></option>
                                           <?php }?>
                                   </select>
                           </div>
                   </div>
                   <div class="col-sm-12"  style="padding:0;height: 5px;"></div>
                   <div class="col-sm-12" style="padding:0;">
                           <div class="col-sm-6" style="padding:0;"><label for="price_<?php echo $price_row;?>">Price <?php echo $price_row;?></label></div>
                                   <div class="col-sm-3" style="padding:0;"><input type="text" class="form-control required" id="price_<?php echo $price_row;?>" placeholder="Price" value="<?php echo $v->price;?>" name="price_<?php echo $price_row;?>"  style="width:auto;"></div>
                                   <div class="col-sm-1" style="padding:0;"></div>
                                   <div class="col-sm-2" style="padding:0;"><button class="removePriceRow" type="button" alt="<?php echo $price_row;?>">Remove Row</button></div>
                           </div>
                   </div>					
           <?php
        }
        $retView=ob_get_contents();
        ob_end_clean();
        return $retView;
    }

    /**
     *
     */
    public function add_common_product(){
        //pre($_POST);die;
        $categoryId=$this->input->post('categoryId',TRUE);
        $retDataArr=$this->default_data_validate();
        if($retDataArr['status']=='fail'){
            $this->session->set_flashdata('Message',$retDataArr['data']);
            redirect(BASE_URL.'product/add_product/'.$categoryId);
            //echo json_encode(array('result'=>'bad','msg'=>$retDataArr['data']));
        }else{
            //pre($_POST);die;
            $config=array(
                array('field'   => 'brandId','label'   => 'Brand','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'qty','label'   => 'Quantity','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'minQty','label'   => 'Minimum Quantity','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'bulkQty','label'   => 'First Quantity Range','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'price','label'   => 'First Price Range','rules'   => 'trim|required|xss_clean'),
            );

            $brandId=$this->input->post('brandId',TRUE);
            $categoryId=$this->input->post('categoryId',TRUE);
            $taxable=$this->input->post('taxable',TRUE);
            $minQty=$this->input->post('minQty',TRUE);
            $qty=$this->input->post('qty',TRUE);
            $length=$this->input->post('length',TRUE);
            $width=$this->input->post('width',TRUE);
            $height=$this->input->post('height',TRUE);
            $lengthClass=$this->input->post('lengthClass',TRUE);
            $weight=$this->input->post('weight',TRUE);
            $weightClass=$this->input->post('weightClass',TRUE);
            $bulkQty=$this->input->post('bulkQty',TRUE);
            $price=$this->input->post('price',TRUE);
            $total_price_row_added=$this->input->post('total_price_row_added',TRUE);



            $status=$this->input->post('status',TRUE);
            $this->form_validation->set_rules($config);

            if($this->form_validation->run() == FALSE){
                $data=validation_errors();
                //pre($data);die;
                //return array('status'=>'fail','data'=>$data);
                $this->session->set_flashdata('Message',$data);
                redirect(BASE_URL.'product/add_product/'.$categoryId);
            }else{
                $priceArr=array();
                $lowestPrice=$price;
                $priceArr[]=array('qty'=>$bulkQty,'price'=>$price);
                for($i=1;$i<$total_price_row_added;$i++){
                    $bulkQty=$this->input->post('bulkQty_'.$i,TRUE);
                    $price=$this->input->post('price_'.$i,TRUE);

                    if($bulkQty=="" || $price==""){
                        //echo '$bulkQty= '.$bulkQty.'  === $price '.$price;die;
                        $this->session->set_flashdata('Message','Please fill the price and relted quanity');
                        redirect(BASE_URL.'product/add_product/'.$categoryId);
                    }
                    //$shippingPrice=$this->calculate_shiiping_price($bulkQty,$weight);
                    //$tidiitCommissions=  $this->get_tidiit_commission($productId);
                    //$fPrice=$price+($bulkQty*$shippingPrice)+$tidiitCommissions;
                    //$priceArr[]=array('qty'=>$bulkQty,'price'=>$fPrice,'shippingCharges'=>($bulkQty*$shippingPrice),'tidiitCommissions'=>$tidiitCommissions);
                    $priceArr[]=array('qty'=>$bulkQty,'price'=>$price);
                }
                usort($priceArr, 'sortingProductPriceArr');
                $heighestPrice=$price;
                $minQty=$priceArr[count($priceArr)-1]['qty'];
                //$dataArr=$retDataArr['data'];
                $dataArr=array('taxable'=>$taxable,'minQty'=>$minQty,'qty'=>$qty,'length'=>$length,'width'=>$width,
                    'height'=>$height,'lengthClass'=>$lengthClass,'weight'=>$weight,'weightClass'=>$weightClass,'status'=>$status,
                    'lowestPrice'=>$lowestPrice,'heighestPrice'=>$heighestPrice);
                $ParrentDataArr=$this->Category_model->get_all_parrent_details($categoryId);
                //pre($ParrentDataArr);die;
                if($ParrentDataArr[0]->secondParentcategoryId==""){
                    $dataArr['CategoryID1']=$ParrentDataArr[0]->firstParentcategoryId;
                    $dataArr['CategoryID2']=$categoryId;
                }else{
                    $dataArr['CategoryID1']=$ParrentDataArr[0]->secondParentcategoryId;
                    $dataArr['CategoryID2']=$ParrentDataArr[0]->firstParentcategoryId;
                    $dataArr['CategoryID3']=$categoryId;
                }

                $dataArr['isNew'] = $this->input->post('isNew')?$this->input->post('isNew',TRUE):0;
                $dataArr['popular'] = $this->input->post('popular')?$this->input->post('popular',TRUE):0;
                $dataArr['featured'] = $this->input->post('featured')?$this->input->post('featured',TRUE):0;

                $dataArr['isOptionsAdded'] = 1;
                if(!empty($mobileConnectivity)){$dataArr['mobileConnectivity']=implode(',', $mobileConnectivity);}
                $tag=$retDataArr['data']['tag'];
                unset($retDataArr['data']['tag']);
                $mobileDataArr=array_merge($retDataArr['data'],$dataArr);
                //pre($mobileDataArr);die;
                //echo base64_encode(serialize($mobileDataArr));die;
                //pre($priceArr);die;

                $config['upload_path'] =$this->config->item('ResourcesPath').'product/original/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['file_name']	= strtolower(my_seo_freindly_url($mobileDataArr['title'])).'-'.rand(1,9).'-'.time();
                $config['max_size']	= '2047';
                $config['max_width'] = '2500';
                $config['max_height'] = '2500';
                //$config['max_width']  = '1024';
                //$config['max_height']  = '1024';
                $upload_files=array();
                $this->load->library('upload');
                //pre($_FILES);die;
                $blank=0;
                foreach ($_FILES as $fieldname => $fileObject){  //fieldname is the form field name
                    //pre($fileObject);
                    if (!empty($fileObject['name'])){
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload($fieldname)){
                            foreach($upload_files AS $k){
                                @unlink($this->config->item('ResourcesPath').'product/original/'.$k);
                            }
                            $errors = $this->upload->display_errors();
                            //pre($errors);die;
                            $this->session->set_flashdata('Message',$errors);
                            redirect(base_url().'product/add_product/'.$categoryId);
                        }
                        else
                        {
                            // Code After Files Upload Success GOES HERE
                            $data=$this->upload->data();
                            $this->product_image_resize($data['file_name']);
                            $upload_files[]=$data['file_name'];
                        }
                    }else{
                        if(substr($fieldname,-1)==1)
                            $blank++;
                    }
                }
            }
            //die($blank.' = rrr');
            //if($blank==1 || $blank==2){
            if($blank==1){
                @unlink($this->config->item('ResourcesPath').'product/original/'.$upload_files[0]);
                $this->session->set_flashdata('Message','Please upload at least two image for this product.');
                redirect(BASE_URL.'product/add_product/'.$categoryId);
            }
            //pre($upload_files);die;

            $productId=$this->Product_model->add($mobileDataArr);
            //$productId=1;
            //echo 'product added done.<br>';
            $imageBatchArr=array();
            foreach ($upload_files AS $k =>$v){
                $imageBatchArr[]=array('productId'=>$productId,'image'=>$v);
            }
            $this->Product_model->add_image($imageBatchArr);
            //echo 'image uploaded done.<br>';

            $productTagArr=array('productId'=>$productId,'tagStr'=>$tag);
            $this->Product_model->add_product_tag($productTagArr);
            //echo 'tag added done.<br>';

            $newPriceArr=array();
            foreach ($priceArr AS $k){
                $k['productId']=$productId;
                $newPriceArr[]=$k;
            }

            //pre($newPriceArr);die;
            $this->Product_model->add_product_price($newPriceArr);
            //echo 'price added done.<br>';

            $this->Product_model->add_product_category(array('productId'=>$productId,'categoryId'=>$categoryId));

            //echo 'product category done.<br>';
            $this->Product_model->add_product_owner(array('productId'=>$productId,'userId'=>$this->session->userdata('FE_SESSION_VAR')));
            $this->User_model->add_tidiit_commission(array('productId'=>$productId,'sellerId'=>$this->session->userdata('FE_SESSION_VAR'),'categoryId'=>$categoryId));
            $this->Product_model->add_brand(array('productId'=>$productId,'brandId'=>$brandId));

            //Add product option values
            $this->Option_model->saveOptionsValues( $this->input->post('options',TRUE)?$this->input->post('options',TRUE):[], $productId );

            $this->session->set_flashdata('Message','Product added successfully.');
            redirect(base_url().'product/viewlist');
        }
    }

    /**
     * @param $productId
     */
    function edit_common($productId){
        $this->config->load('product');
        $data=$this->_get_logedin_template();
        $this->load->model('User_model');
        $productPriceArr=$this->Product_model->get_products_price($productId);
        $productImageArr=$this->Product_model->get_products_images($productId);
        //pre($productImageArr); //die;
        $productDetails=$this->Product_model->details($productId);
        $allTagArr=$this->Product_model->get_tag_by_product_id($productId);
        $details=$productDetails[0];
        //pre($details);
        $data['detail']=$details;
        $data['bulkQty']=$productPriceArr[0]->qty;
        $data['bulkPrice']=$productPriceArr[0]->price;
        $data['totalPriceRowAdded']=count($productPriceArr);
        $data['productPriceView']=  $this->get_price_data_for_edit($productPriceArr,'mobile');
        $data['brandArr']=$this->Brand_model->get_all();
        $data['tag']=$allTagArr->productTag;
        $data['productImageArr']=$productImageArr;
        $data['categoryId']=$productDetails[0]->categoryId;
        $data['productPageType']='mobile';

        $categoryDetailsArr = $this->Category_model->get_details_by_id($data['categoryId']);
        if($categoryDetailsArr[0]->option_ids):
            $pieces = explode(",", $categoryDetailsArr[0]->option_ids);
            $options = $this->Option_model->get_bulk_options($pieces);
            $data['options'] = $options;
            $data['options_arrenge'] = $pieces;
        else:
            $data['options'] = '';
        endif;
        $options = $this->Option_model->get_product_option_values($productId);
        $data['proptions'] = $options;
        //print_r($options);die;
        $this->load->view('edit_common_product',$data);
    }

    public function update_common_product(){
        //pre($_POST);die;
        $productId = $this->input->post('productId',TRUE);
        $enc_pro_id = $productId*999999;
        $retDataArr=$this->default_data_validate();
        if($retDataArr['status']=='fail'):
            $this->session->set_flashdata('Message',$retDataArr['data']);
            redirect(BASE_URL.'product/edit_product/'.$enc_pro_id);
        else:
            $config=array(
                array('field'   => 'brandId','label'   => 'Brand','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'qty','label'   => 'Quantity','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'minQty','label'   => 'Minimum Quantity','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'bulkQty','label'   => 'First Quantity Range','rules'   => 'trim|required|xss_clean'),
                array('field'   => 'price','label'   => 'First Price Range','rules'   => 'trim|required|xss_clean'),
            );


            $brandId=$this->input->post('brandId',TRUE);
            $categoryId=$this->input->post('categoryId',TRUE);
            $taxable=$this->input->post('taxable',TRUE);
            $minQty=$this->input->post('minQty',TRUE);
            $qty=$this->input->post('qty',TRUE);
            $length=$this->input->post('length',TRUE);
            $width=$this->input->post('width',TRUE);
            $height=$this->input->post('height',TRUE);
            $lengthClass=$this->input->post('lengthClass',TRUE);
            $weight=$this->input->post('weight',TRUE);
            $weightClass=$this->input->post('weightClass',TRUE);
            $bulkQty=$this->input->post('bulkQty',TRUE);
            $price=$this->input->post('price',TRUE);
            $total_price_row_added=$this->input->post('total_price_row_added',TRUE);

            //$status=$this->input->post('status',TRUE);
            $this->form_validation->set_rules($config);
            if($this->form_validation->run() == FALSE):
                $data=validation_errors();
                $this->session->set_flashdata('Message',$data);
                redirect(BASE_URL.'product/edit_product/'.$enc_pro_id);
            else:
                $priceArr=array();
                $lowestPrice=$price;
                $priceArr[]=array('qty'=>$bulkQty,'price'=>$price);
                for($i=1;$i<$total_price_row_added;$i++){
                    $bulkQty=$this->input->post('bulkQty_'.$i,TRUE);
                    $price=$this->input->post('price_'.$i,TRUE);
                    if($bulkQty=="" || $price==""){
                        //echo '$bulkQty= '.$bulkQty.'  === $price '.$price;die;
                        $this->session->set_flashdata('Message','Please fill the price and relted quanity');
                        redirect(BASE_URL.'product/edit_product/'.$enc_pro_id);
                    }
                    if($this->is_tidiit_commission_updated($productId)==TRUE){
                        $shippingPrice=$this->calculate_shiiping_price($bulkQty,$weight);
                        $shippingCharges=round($bulkQty*$weight)*$shippingPrice;
                        $tidiitCommissionsPer=  $this->get_tidiit_commission($productId);
                        $tidiitCommissions=$price*$tidiitCommissionsPer/100;
                        $fPrice=$price+$shippingCharges+$tidiitCommissions;
                        $priceArr[]=array('qty'=>$bulkQty,'price'=>$fPrice,'shippingCharges'=>$shippingCharges,'tidiitCommissions'=>$tidiitCommissions);
                    }else{
                        $priceArr[]=array('qty'=>$bulkQty,'price'=>$price);
                    }
                    
                }
                usort($priceArr, 'sortingProductPriceArr');
                $newPriceArr=array();
                foreach ($priceArr AS $k):
                    $k['productId']=$productId;
                    $newPriceArr[]=$k;
                endforeach;

                $this->Product_model->edit_product_price($newPriceArr,$productId);

                $heighestPrice=$price;
                $minQty=$priceArr[count($priceArr)-1]['qty'];
                $dataArr=array('taxable'=>$taxable,'minQty'=>$minQty,'qty'=>$qty,'length'=>$length,'width'=>$width,
                    'height'=>$height,'lengthClass'=>$lengthClass,'weight'=>$weight,'weightClass'=>$weightClass,
                    'lowestPrice'=>$lowestPrice,'heighestPrice'=>$heighestPrice);
                $ParrentDataArr=$this->Category_model->get_all_parrent_details($categoryId);
                //pre($ParrentDataArr);
                if($ParrentDataArr[0]->secondParentcategoryId==""){
                    $dataArr['CategoryID1']=$ParrentDataArr[0]->firstParentcategoryId;
                    $dataArr['CategoryID2']=$categoryId;
                }else{
                    $dataArr['CategoryID1']=$ParrentDataArr[0]->secondParentcategoryId;
                    $dataArr['CategoryID2']=$ParrentDataArr[0]->firstParentcategoryId;
                    $dataArr['CategoryID3']=$categoryId;
                }

                $dataArr['isNew'] = $this->input->post('isNew')?$this->input->post('isNew',TRUE):0;
                $dataArr['popular'] = $this->input->post('popular')?$this->input->post('popular',TRUE):0;
                $dataArr['featured'] = $this->input->post('featured')?$this->input->post('featured',TRUE):0;

                if(!empty($mobileConnectivity)){$dataArr['mobileConnectivity']=implode(',', $mobileConnectivity);}
                $tag=$retDataArr['data']['tag'];
                unset($retDataArr['data']['tag']);
                $mobileDataArr=array_merge($retDataArr['data'],$dataArr);

                $config['upload_path'] =$this->config->item('ResourcesPath').'product/original/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['file_name']	= strtolower(my_seo_freindly_url($mobileDataArr['title'])).'-'.rand(1,9).'-'.time();
                $config['max_size']	= '2047';
                $config['max_width'] = '1550';
                $config['max_height'] = '1550';
                //$config['max_width']  = '1024';
                //$config['max_height']  = '1024';
                $upload_files=array();
                $this->load->library('upload');
                //pre($_FILES);die;
                $blank=0;
                foreach ($_FILES as $fieldname => $fileObject):
                    //fieldname is the form field name
                    //pre($fileObject);
                    if (!empty($fileObject['name'])):
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload($fieldname)):
                            foreach($upload_files AS $k):
                                @unlink($this->config->item('ResourcesPath').'product/original/'.$k);
                            endforeach;
                            $errors = $this->upload->display_errors();
                            //pre($errors);die;
                            $this->session->set_flashdata('Message',$errors);
                            redirect(BASE_URL.'product/edit_product/'.$enc_pro_id);
                        else:
                            // Code After Files Upload Success GOES HERE
                            $currgImgNo=substr($fieldname,3);
                            $oldUploadedFileName=$this->input->post('oldImgFile'.$currgImgNo,TRUE);
                            $this->Product_model->remove_product_by_file($oldUploadedFileName,$productId);
                            $this->delete_product_file($oldUploadedFileName);
                            $data=$this->upload->data();
                            $this->product_image_resize($data['file_name']);
                            $upload_files[]=$data['file_name'];
                        endif;
                    else:
                        if(substr($fieldname,-1)==1)
                            $blank++;
                    endif;
                endforeach;

                $this->Product_model->edit($mobileDataArr,$productId);

                //$productId=1;
                //echo 'product added done.<br>';
                $imageBatchArr=array();
                if(!empty($upload_files)):
                    foreach ($upload_files AS $k =>$v):
                        $imageBatchArr[]=array('productId'=>$productId,'image'=>$v);
                    endforeach;
                endif;


                if(!empty($imageBatchArr)):
                    //pre($imageBatchArr);die;
                    $this->Product_model->edit_image_product($imageBatchArr,$productId);
                endif;
                //pre($imageBatchArr);die;
                $productTagArr=array('productId'=>$productId,'tagStr'=>$tag);
                $this->Product_model->edit_product_tag($productTagArr);



                $this->Product_model->edit_brand(array('brandId'=>$brandId),$productId);

                //Add product option values
                $this->Option_model->saveOptionsValues( $this->input->post('options',TRUE)?$this->input->post('options',TRUE):[], $productId );

                $this->session->set_flashdata('Message','Product updated successfully.');
                redirect(base_url().'product/viewlist');
            endif;
        endif;
    }
    
    function insert_old_add_tidiit_commission(){
        $rs=  $this->db->get("product")->result();
        foreach($rs as $k=> $v){
            $rs1=$this->db->get_where('tidiit_commission',array('productId'=>$v->productId))->result();
            if(count($rs1)==0){
                $sql="SELECT pc.categoryId,ps.userId FROM product_category AS pc JOIN product_seller AS ps ON(pc.productId=ps.productId) WHERE pc.productId=$v->productId";
                $rs2=$this->db->query($sql)->result();
                $this->User_model->add_tidiit_commission(array('productId'=>$v->productId,'sellerId'=>$rs2[0]->userId,'categoryId'=>$rs[0]->categoryId));
            }
        }
    }
    
    function calculate_shiiping_price($bulkQty,$weight){
        $weightF=round($bulkQty*$weight);
        $sql="SELECT `charges` FROM `logistic_weight_based_charges` WHERE $weightF BETWEEN `min` AND `max` LIMIT 1";
        $shippingchargesArr=$this->db->query($sql)->result();
        return $shippingchargesArr[0]->charges;
    }
    
    function get_tidiit_commission($productId){
        $rs=$this->db->get_where('tidiit_comission',array('productId'=>$productId))->result(); return $rs[0]->commissionPercentage;
    }
    
    function is_tidiit_commission_updated($productId){
        $rs=$this->db->get_where('tidiit_comission',array('productId'=>$productId))->result(); 
        if(count($rs)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}