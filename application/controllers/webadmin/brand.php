<?php
class Brand extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Brand_model');
		$this->load->model('Category_model');
	}
	
	public function index(){
		redirect(base_url().'webadmin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Brand_model->get_all_admin();
                $menuArr=array();
                $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
                //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
                foreach($TopCategoryData as $k){
                    $SubCateory=$this->Category_model->get_subcategory_by_category_id($k->categoryId);
                    if(count($SubCateory)>0){
                        foreach($SubCateory as $kk => $vv){
                            $menuArr[$vv->categoryId]=$vv->categoryName;
                            $ThirdCateory=$this->Category_model->get_subcategory_by_category_id($vv->categoryId);
                            if(count($ThirdCateory)>0){
                                foreach($ThirdCateory AS $k3 => $v3){
                                    // now going for 4rath
                                    $menuArr[$v3->categoryId]=$v3->categoryName;
                                    $FourthCateory=$this->Category_model->get_subcategory_by_category_id($v3->categoryId);
                                    if(count($FourthCateory)>0){ //print_r($v3);die;
                                        foreach($FourthCateory AS $k4 => $v4){
                                            $menuArr[$v4->categoryId]=$v4->categoryName;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $data['CategoryMenuArr']=$menuArr;
		$this->load->view('webadmin/brand_list',$data);
	}
        
        function add(){
            //pre($_FILES);die;
            $categoryId=$this->input->post('categoryId',TRUE);
            if(empty($categoryId)){
                $this->session->set_flashdata('Message','Category for brand should not empty.');
                redirect(base_url().'webadmin/brand/viewlist');
            }
            $image='';
            if(!array_key_exists('brandImage', $_FILES)){
                $_FILES=array();
                $_FILES['brandImage']=array();
                $_FILES['brandImage']['name']="";
            }
            if($_FILES['brandImage']['name']!=""){
                //$imagePath=$this->config->item('ResourcesPath').'banner/';
                $file=$_FILES['brandImage'];
                $image=time().'.'.end(explode('.',$file['name']));
                //move_uploaded_file($file['tmp_name'],$imagePath.$image);
                $this->brand_image_resize($file,$image);   
            }
            $title=$this->input->post('title',TRUE);
            $status=$this->input->post('status',TRUE);
            $dataArr=array(
                'brandImage'=>$image,
                'status'=>1,
                'title'=>$title,
                'categoryId'=>  implode(',', $categoryId)
                );
            $this->Brand_model->add($dataArr);

            $this->session->set_flashdata('Message','Brand  added successfully.');
            redirect(base_url().'webadmin/brand/viewlist');
        }
	
	
	public function edit(){
		$title=$this->input->post('Edittitle',TRUE);
		$brandId=$this->input->post('brandId',TRUE);
		$categoryId=$this->input->post('EditcategoryId',TRUE);
                if(empty($categoryId)){
                    $this->session->set_flashdata('Message','Category for brand should not empty.');
                    redirect(base_url().'webadmin/brand/viewlist');
                }
                $image='';
                $details=$this->Brand_model->details($brandId);
                if(!array_key_exists('EditbrandImage', $_FILES)){
                    $_FILES=array();
                    $_FILES['EditbrandImage']=array();
                    $_FILES['EditbrandImage']['name']="";
                }
                if($_FILES['EditbrandImage']['name']!=""){
                    $file=$_FILES['EditbrandImage'];
                    $image=time().'.'.end(explode('.',$file['name']));
                    //move_uploaded_file($file['tmp_name'],$imagePath.$image);
                    $this->brand_image_resize($file,$image);
                }
		
		$dataArr=array(
		'title'=>$title,
                'categoryId'=>  implode(',', $categoryId)   
		);
                
                if($image!=''){
                    $dataArr['brandImage']=$image;
                    $this->delete_brand_image($details[0]->brandImage);
                }
		
		$this->Brand_model->edit($dataArr,$brandId);
		
		$this->session->set_flashdata('Message','Brand Updated successfully.');
		redirect(base_url().'webadmin/brand/viewlist');
	}
        
        public function change_status($brandId,$Action){
            $this->Brand_model->change_status($brandId,$Action);

            $this->session->set_flashdata('Message','Brand status updated successfully.');
            redirect(base_url().'webadmin/brand/viewlist');
	}
	
	public function delete($brandId){
            $details=$this->Brand_model->details($brandId);
            //pre($details);die;
            $this->delete_brand_image($details[0]->brandImage);
            
            $this->Brand_model->delete($brandId);
            $this->session->set_flashdata('Message','Brand deleted successfully.');
            redirect(base_url().'webadmin/brand/viewlist');
	}    
        
    public function brand_image_resize($file,$fileName){
        /// process the image for all size. and water mark
        $PHOTOPATH=$this->config->item('ResourcesPath').'brand/';
        $OriginalPath=$PHOTOPATH.'original/';
        //echo '$OriginalPath :- '.$OriginalPath.'<br>';
        $OriginalFilePath=$OriginalPath.$fileName;
        //echo '$OriginalFilePath :- '.$OriginalFilePath.'<br>';
        //echo '<pre>';print_r($file);
        if(!@move_uploaded_file($file["tmp_name"],$OriginalFilePath)){
                die('not moveing the file');
        }

        //echo 'move uploaed done for original image ====<br>';
        /// ************admin***************
        $config['image_library'] = 'gd2';
        $config['source_image'] = $OriginalFilePath;
        $config['new_image'] = $PHOTOPATH. 'admin/';
        $config['width'] = 50;
        $config['height'] = 50;
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

        $this->image_lib->clear();

        /// ************880X232*************** at Langing Page
        $config['image_library'] = 'gd2';
        $config['source_image'] = $OriginalFilePath;
        $config['new_image'] = $PHOTOPATH. 'category/';
        $config['width'] = 150;
        $config['height'] = 150;
        $config['maintain_ratio'] = true;
        $config['master_dim'] = 'auto';
        $config['create_thumb'] = FALSE;
        $this->image_lib->initialize($config);
        $this->load->library('image_lib', $config);
        if($this->image_lib->resize()){
                //echo '<br>thumb done for 95X82.';
        }else{
                $this->image_lib->display_errors();
        }

        $this->image_lib->clear();
    }
    
    public function delete_brand_image($fileName){
            $BrandResourcesPath=$this->config->item('ResourcesPath').'brand/';
            @unlink($BrandResourcesPath.'admin/'.$fileName);
            @unlink($BrandResourcesPath.'category/'.$fileName);
            @unlink($BrandResourcesPath.'original/'.$fileName);
        }
}