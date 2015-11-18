<?php
class Banner extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Banner_model');
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
            $data=$this->_show_admin_logedin_layout();
            $data['DataArr']=$this->Banner_model->get_all();
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
            $data['CategoryMenuArr']=$menuArr;
            $this->load->view('webadmin/banner_list',$data);
	}
	
	public function add(){
            if($_FILES['banner']['name']!=""){
                //pre($_POST);die;
                $file=$_FILES['banner'];
                $image=time().'.'.end(explode('.',$file['name']));
                //move_uploaded_file($file['tmp_name'],$imagePath.$image);
                $this->banner_image_resize($file,$image);
                $status=$this->input->post('status',TRUE);
                $pageId=$this->input->post('pageId',TRUE);
                $bannerType=$this->input->post('bannerType',TRUE);
                $sliderSlNo=$this->input->post('sliderSlNo',TRUE);
                $categoryId=$this->input->post('categoryId',TRUE);
                $categoryImageTitle=$this->input->post('categoryImageTitle',TRUE);
                $categoryImageDetails=$this->input->post('categoryImageDetails',TRUE);
                $url=$this->input->post('url',TRUE);
                
                if($pageId==1 && $sliderSlNo==0){
                    $this->session->set_flashdata('Message','Invalid slider serial no selected!');
                }else{
                    $dataArr=array('image'=>$image,'pageId'=>$pageId,'status'=>$status,'bannerType'=>$bannerType,'sliderSlNo'=>$sliderSlNo,'url'=>$url,
                        'categoryId'=>$categoryId,'categoryImageTitle'=>$categoryImageTitle,'categoryImageDetails'=>$categoryImageDetails);
                    $this->Banner_model->add($dataArr);
                    $this->session->set_flashdata('Message','Banner added successfully.');
                }
            }else{
                $this->session->set_flashdata('Message','Invalid Banner uploaded.');
            }
            redirect(base_url().'webadmin/banner/viewlist');
	}
        
        function edit(){
            $pageId=$this->input->post('EditpageId',TRUE);
            $bannerType=$this->input->post('EditbannerType',TRUE);
            $sliderSlNo=$this->input->post('EditsliderSlNo',TRUE);
            $url=$this->input->post('Editurl',TRUE);
            $categoryId=$this->input->post('EditcategoryId',TRUE);
            $bannerId=$this->input->post('bannerId',TRUE);
            $categoryImageTitle=$this->input->post('EditcategoryImageTitle',TRUE);
            $categoryImageDetails=$this->input->post('EditcategoryImageDetails',TRUE);
            //pre($_FILES);die;
            $dataArr=array('pageId'=>$pageId,'bannerType'=>$bannerType,'sliderSlNo'=>$sliderSlNo,'url'=>$url);
            if($pageId==2 && $categoryId!=""){
                $dataArr['categoryId']=$categoryId;
                $dataArr['categoryImageTitle']=$categoryImageTitle;
                $dataArr['categoryImageDetails']=$categoryImageDetails;
            }
            if(array_key_exists('Editbanner', $_FILES)){
                if($_FILES['Editbanner']['name']!=""){
                    $file=$_FILES['Editbanner'];
                    $image=time().'.'.end(explode('.',$file['name']));
                    $bannerImg=$this->Banner_model->get_banner_img($bannerId);
                    //pre($bannerImg->image);die;
                    $this->delete_img($bannerImg->image);
                    $this->banner_image_resize($file,$image);
                    $dataArr['image']=$image;
                }
            }
            $this->Banner_model->edit($dataArr,$bannerId);
            $this->session->set_flashdata('Message','Banner data updated successfully.');
            redirect(base_url().'webadmin/banner/viewlist');
        }
	
	public function change_status($bannerId,$Action){
		$this->Banner_model->change_status($bannerId,$Action);
		
		$this->session->set_flashdata('Message','Banner status updated successfully.');
		redirect(base_url().'webadmin/banner/viewlist');
	}
	
	public function delete($bannerId){
            $BannerArr=$this->Banner_model->get_banner_img($bannerId);
            //pre($BannerArr);die;
            $this->delete_img($BannerArr->image);
            $this->Banner_model->delete($bannerId);

            $this->session->set_flashdata('Message','Banner deleted successfully.');
            redirect(base_url().'webadmin/banner/viewlist');
	}
        
        public function delete_img($fileName){
            $BannerResourcesPath=$this->config->item('ResourcesPath').'banner/';
            @unlink($BannerResourcesPath.'admin/'.$fileName);
            @unlink($BannerResourcesPath.'landing/'.$fileName);
            @unlink($BannerResourcesPath.'original/'.$fileName);
        }


        public function banner_image_resize($file,$fileName){
		/// process the image for all size. and water mark
		$PHOTOPATH=$this->config->item('ResourcesPath').'banner/';
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
		$config['width'] = 350;
		$config['height'] = 100;
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
		$config['new_image'] = $PHOTOPATH. 'landing/';
		$config['width'] = 880;
		$config['height'] = 232;
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
	
}
?>