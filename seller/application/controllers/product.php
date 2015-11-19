<?php
class Product extends MY_Controller{
    public function __construct(){
            parent::__construct();
            $this->load->model('Product_model');
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
        //$this->load->library('My_Services');
        //$ServiceData=My_Services::request_services('User_model','get_user_page_type',array(4));
        //$ServiceData=My_Services::request_services('User_model','change_user_status',array(4,1));
        //pre($ServiceData);die;
        //$pageTypeData=$this->User_model->get_user_page_type($this->session->userdata('FE_SESSION_VAR'));
        //pre($pageTypeData);die;
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
            $categoryDetailsArr=$this->Category_model->get_details_by_id($categoryId);
            $productPageTypeArr=  $this->config->item('productPageTypeArr');
            $data['brandArr']=$this->Brand_model->get_all();
            $data['categoryId']=$categoryId;
            $productPageTypeArr=$this->Product_model->get_page_template();
            //pre($productPageTypeArr);die;
            $templateName='';
            foreach($productPageTypeArr As $k){
                if($k->productViewTemplateID==$categoryDetailsArr[0]->view){
                    $templateName=$k->templateFileName;
                    break;
                }
            }
            //pre($productPageTypeArr[$categoryDetailsArr[0]->view]);die;
            $viewPage='add_product_'.$templateName;
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
                $heighestPrice=$price;
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
                //pre($mobileDataArr);die;
                //echo base64_encode(serialize($mobileDataArr));die;
                //pre($priceArr);die;

                $config['upload_path'] =$this->config->item('ResourcesPath').'product/original/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['file_name']	= time();
                $config['max_size']	= '2047';
                $config['max_width'] = '1550';
                $config['max_height'] = '1550';
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

    public function view_edit($ProductID=0){
            if($ProductID==0){
                    $this->session->set_flashdata('Message','Invalid Course selected,Please try again.');
                    redirect(base_url().'admin/product/viewlist');
            }else{
                    $data=$this->_show_admin_logedin_layout();
                    $data['ckeditor'] = array(
                            //ID of the textarea that will be replaced
                            'id' 	=> 	'Description',
                            'path'	=>	$this->config->item('SiteJSURL').'ckeditor',
                            'judhipath'	=>	$this->config->item('SiteJSURL'),
                            //Optionnal values
                            'config' => array(
                                    'toolbar' 	=> 	"Full", 	//Using the Full toolbar
                                    'width' 	=> 	"90%",	//Setting a custom width
                                    'height' 	=> 	'250px',	//Setting a custom height
                            ),
                            //Replacing styles from the "Styles tool"
                            'styles' => array(
                                    //Creating a new style named "style 1"
                                    'style 1' => array (
                                            'name' 		=> 	'Blue Title',
                                            'element' 	=> 	'h2',
                                            'styles' => array(
                                                    'color' 	=> 	'Blue',
                                                    'font-weight' 	=> 	'bold'
                                            )
                                    ),
                                    //Creating a new style named "style 2"
                                    'style 2' => array (
                                            'name' 	=> 	'Red Title',
                                            'element' 	=> 	'h2',
                                            'styles' => array(
                                                    'color' 		=> 	'Red',
                                                    'font-weight' 		=> 	'bold',
                                                    'text-decoration'	=> 	'underline'
                                            )
                                    )
                            )
                    );
                    $details=$this->Product_model->details($ProductID);

                    $menuArr=array();
                    $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
                    //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
                    foreach($TopCategoryData as $k){
                        $SubCateory=$this->Category_model->get_subcategory_by_category_id($k->categoryId);
                        if(count($SubCateory)>0){
                            foreach($SubCateory as $kk => $vv){
                                $menuArr[$vv->categoryId]=$k->categoryName.' -> '.$vv->categoryName;
                                $ThirdCateory=$this->Category_model->get_subcategory_by_category_id($vv->categoryId);
                                if(count($ThirdCateory)>0){
                                    foreach($ThirdCateory AS $k3 => $v3){
                                        // now going for 4rath
                                        $menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                                        $FourthCateory=$this->Category_model->get_subcategory_by_category_id($v3->categoryId);
                                        if(count($FourthCateory)>0){ //print_r($v3);die;
                                            foreach($FourthCateory AS $k4 => $v4){
                                                $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $data['CategoryData']=$menuArr;

                    $data['ProductData']=$details;

                    $TagData=$this->Product_model->get_tag_by_product_id($details[0]->ProductID);
                    $TagStr='';
                    foreach($TagData as $k){
                            $TagStr .= $k->name.',';
                    }
                    $data['TagStr']=substr($TagStr,0,-1);
                    //$data['dataArr']=$this->Course_model->get_details_by_id($CourseID);
                    $this->load->view('admin/product_edit',$data);
            }
    }

    public function edit(){
            $categoryId=$this->input->post('SubcategoryId',TRUE);
            $Title=$this->input->post('Title',TRUE);
            $Description=$this->input->post('Description',TRUE);
            $ShortDescription=$this->input->post('ShortDescription',TRUE);
            $Price=$this->input->post('Price',TRUE);
            $Weight=$this->input->post('Weight',TRUE);
            $Quantity=$this->input->post('Quantity',TRUE);
            $MinQuantity=$this->input->post('MinQuantity',TRUE);
            $Tag=$this->input->post('Tag',TRUE);
            $MetaKeyWord=$this->input->post('MetaKeyword',TRUE);
            $MetaTitle=$this->input->post('MetaTitle',TRUE);
            $MetaDescription=$this->input->post('MetaDescription',TRUE);
            $Status=$this->input->post('Status',TRUE);
            $ProductID=$this->input->post('ProductID',TRUE);
            $RequireShipping=$this->input->post('RequireShipping',TRUE);
            $Model=$this->input->post('Model',TRUE);

            /*if(strlen($Title)>27){
                $this->session->set_flashdata('Message','Product name should be less than 27 Character');
                redirect(base_url().'admin/product/viewlist');
            }*/

            //die($ProductID);
            $dataArr=array(
            'categoryId'=>$categoryId,
            'Title'=>$Title,
            'ShortDescription'=>$ShortDescription,
            'Description'=>$Description,
            'Quantity'=>$Quantity,
            'MinQuantity'=>$MinQuantity,
            'Price'=>$Price,
            'Model'=>$Model,
            'Weight'=>$Weight,
            'MetaTitle'=>$MetaTitle,
            'MetaKeyword'=>$MetaKeyWord,
            'MetaDescription'=>$MetaDescription,
            'FreeShipping'=>$RequireShipping,
            'Status'=>$Status
            );
            //echo $ProductID.'<br>';
            //echo '<pre>';print_r($dataArr);die;

            $ParrentDataArr=$this->Category_model->get_all_parrent_details($categoryId);
            //pre($ParrentDataArr);
            if($ParrentDataArr[0]->SecondParentcategoryId==""){
                $dataArr['categoryId1']=$ParrentDataArr[0]->FirstParentcategoryId;
                $dataArr['categoryId2']=$categoryId;
            }else{
                $dataArr['categoryId1']=$ParrentDataArr[0]->SecondParentcategoryId;
                $dataArr['categoryId2']=$ParrentDataArr[0]->FirstParentcategoryId;
                $dataArr['categoryId3']=$categoryId;
            }

            //pre($dataArr);die;

            $this->Product_model->edit($dataArr,$ProductID);
            $ProductDetails=$this->Product_model->details($ProductID);
            $oldImmage=$ProductDetails[0]->Image;
            $filename="";
            if($_FILES["ProductImage"]["name"]!=""){
                    $file=$_FILES["ProductImage"];
                    $filename=time().'.'.end(explode('.',$file["name"]));
                    //$storePath=$this->config->item('ResourcesPath');
                    $this->product_image_resize($file,$filename);
                    $ImageDataArr=array('Image'=>$filename);
                    /*if(move_uploaded_file($file["tmp_name"],$storePath.'product/'.$filename)){
                            //echo 'file uploaded ====';
                            $ImageDataArr=array('Image'=>$filename);
                            if($this->Product_model->edit_product_image($ImageDataArr,$ProductID)){
                                    //echo 'file data updated =======';
                                    @unlink($storePath.'product/'.$oldImmage);
                                    //echo 'old file removed';die;

                            }
                    }*/
                    $ImageDataArr=array('Image'=>$filename);
                    if($this->Product_model->edit_product_image($ImageDataArr,$ProductID)){
                    //echo 'file data updated =======';
                            $this->delete_product_file($oldImmage);
                            //@unlink($storePath.'product/'.$oldImmage);
                            //echo 'old file removed';die;
                    }
            }

            $ProductTagArr=array('ProductID'=>$ProductID,'TagStr'=>$Tag);
            //echo '<pre>';print_r($ProductTagArr);die;
            $this->Product_model->edit_product_tag($ProductTagArr);

            $CategoryTagArr=array('categoryId'=>$ParrentDataArr[0]->FirstParentcategoryId,'TagStr'=>$Tag);
            $this->Product_model->add_category_tag($CategoryTagArr);

            $this->session->set_flashdata('Message','Product updated successfully.');
            redirect(base_url().'admin/product/viewlist');
    }

    function delete($productId){
        $ProductImages=$this->Product_model->get_products_images($productId);
        $this->Product_model->delete($productId);
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

            $dataArr=array('title'=>$title,'shortDescription'=>$shortDescription,'description'=>$description,'tag'=>$tag,'metaKeyword'=>$metaKeyWord,'metaTitle'=>$metaTitle,'metaDescription'=>$metaDescription);
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
}
?>