<?php
class Category extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Category_model');
		$this->load->model('Product_model');
	}
	
	public function index(){
		redirect(base_url().'webadmin/');
	}
	
	public function viewlist($parrentId=0){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Category_model->get_all($parrentId);
		//echo '$parrentId :- '.$parrentId;die;
		if($parrentId==0){
			$Arr=new stdClass();
			$Arr->parrentCategoryId=0;
			$Arr->categoryId=0;
			$Arr->categoryName='Root';
			$parrentData=array('0'=>$Arr);
		}else{
			$parrentData=$this->Category_model->get_details_by_id($parrentId);
		}
                //pre($parrentData);die;
		$data["parrentData"]=$parrentData;
                $data['productPageTypeArr']=$this->Product_model->get_page_template();
                $data['categoryTemplateArr']=$this->Category_model->get_page_template();
		$this->load->view('webadmin/category_list',$data);
	}
	
	public function add(){
		$categoryName=$this->input->post('categoryName',TRUE);
		$shortDescription=$this->input->post('shortDescription',TRUE);
		$parrentCategoryId=$this->input->post('parrentCategoryId',TRUE);
		$userCategoryView=$this->input->post('userCategoryView',TRUE);
		$metaTitle=$this->input->post('metaTitle',TRUE);
		$metaKeyWord=$this->input->post('metaKeyWord',TRUE);
		$metaDescription=$this->input->post('metaDescription',TRUE);
		$view=$this->input->post('view',TRUE);
		$status=$this->input->post('status',TRUE);
                if(!array_key_exists('categoryImage', $_FILES)){
                    $_FILES=array();
                    $_FILES['categoryImage']=array();
                    $_FILES['categoryImage']['name']="";
                }
		if($_FILES['categoryImage']['name']=="" && $parrentCategoryId>0){
                    $this->session->set_flashdata('Message','Please Browse Category Image.');
                }else{
                    if($_FILES['categoryImage']['name']!=""){
                        $image=time().'.'.end(explode('.',$file['name']));
                        //move_uploaded_file($file['tmp_name'],$imagePath.$image);
                        $this->category_image_resize($file,$image);
                    }else{
                        $image="";
                    }
                    $dataArr=array(
                        'categoryName'=>$categoryName,
                        'parrentCategoryId'=>$parrentCategoryId,
                        'status'=>$status,
                        'view'=>$view,
                        'shortDescription'=>$shortDescription,
                        'userCategoryView'=>$userCategoryView,
                        'image'=>$image,
                        'metaTitle'=>$metaTitle,
                        'metaKeyWord'=>$metaKeyWord,
                        'metaDescription'=>$metaDescription
                    );

                    //print_r($dataArr);die;
                    $this->Category_model->add($dataArr);
                    $this->session->set_flashdata('Message','Category added successfully.');
                }
                redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
	}
	
	public function edit(){
            $categoryName=$this->input->post('EditcategoryName',TRUE);
            $shortDescription=$this->input->post('EditshortDescription',TRUE);
            $status=$this->input->post('Editstatus',TRUE);
            $CategoryID=$this->input->post('categoryId',TRUE);
            $parrentCategoryId=$this->input->post('parrentCategoryId',TRUE);
            
            $userCategoryView=$this->input->post('EdituserCategoryView',TRUE);
            $metaTitle=$this->input->post('EditmetaTitle',TRUE);
            $metaKeyWord=$this->input->post('EditmetaKeyWord',TRUE);
            $metaDescription=$this->input->post('EditmetaDescription',TRUE);
            $view=$this->input->post('Editview',TRUE);

            if($parrentCategoryId==""){
                $parrentCategoryId=0;
            }
            $image='';
            if($_FILES['EditcategoryImage']['name']!=""){
                $categoryDetails=$this->Category_model->get_details_by_id($CategoryID);
                $file=$_FILES['EditcategoryImage'];
                $image=time().'.'.end(explode('.',$file['name']));
                //move_uploaded_file($file['tmp_name'],$imagePath.$image);
                $this->category_image_resize($file,$image);
                 $this->delete_img($categoryDetails[0]->image);
            }

            $dataArr=array(
                        'categoryName'=>$categoryName,
                        'parrentCategoryId'=>$parrentCategoryId,
                        'status'=>$status,
                        'view'=>$view,
                        'shortDescription'=>$shortDescription,
                        'userCategoryView'=>$userCategoryView,
                        'metaTitle'=>$metaTitle,
                        'metaKeyWord'=>$metaKeyWord,
                        'metaDescription'=>$metaDescription
                    );
            if($image!=""){
                $dataArr['image']=$image;
            }
            //print_r($dataArr);die;
            
            $this->Category_model->edit($dataArr,$CategoryID);

            $this->session->set_flashdata('Message','Category updated successfully.');
            redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
	}
	
	public function change_status($CategoryID,$Action){
            $this->Category_model->change_category_status($CategoryID,$Action);
            $Data=$this->Category_model->get_parrent_by_category_id($CategoryID);
            //echo '<pre>';print_r($Data);die;
            $parrentCategoryId=$Data[0]->parrentCategoryId;
            //echo $parrentCategoryId;die;
            $this->session->set_flashdata('Message','Category status updated successfully.');
            redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
	}
	
	public function delete($CategoryID){
            $Data=$this->Category_model->get_parrent_by_category_id($CategoryID);
            $parrentCategoryId=$Data[0]->parrentCategoryId;
            //pre($parrentCategoryId);die;
            $categoryDetails=$this->Category_model->get_details_by_id($CategoryID);
            $this->Category_model->delete($CategoryID);
            $this->delete_img($categoryDetails[0]->image);
            $this->session->set_flashdata('Message','Category deleted successfully.');
            redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
	}
        
        private function delete_img($fileName){
            $BannerResourcesPath=$this->config->item('ResourcesPath').'category/';
            @unlink($BannerResourcesPath.'admin/'.$fileName);
            @unlink($BannerResourcesPath.'350X350/'.$fileName);
            @unlink($BannerResourcesPath.'original/'.$fileName);
        }
        
	function batchaction(){
		//print_r($_POST);die;
		$batchaction_fun=$this->input->post('batchaction_fun',TRUE);
		$batchaction_id=$this->input->post('batchaction_id',TRUE);
		//echo $batchaction_fun;die;
		$this->$batchaction_fun($batchaction_id);
	}
	
	public function batchactive($CategoryIDs){
		$Action='1'; //active
		$this->Category_model->change_category_status($CategoryIDs ,$Action);
                $CategoryIDArr=explode(',',$CategoryIDs);
		$Data=$this->Category_model->get_parrent_by_category_id($CategoryIDArr[0]);
                //echo '<pre>';print_r($Data);die;
		$parrentCategoryId=$Data[0]->parrentCategoryId;
		$this->session->set_flashdata('Message','Cateory/s activated successfully.');
		redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
	}
	
	public function batchinactive($CategoryIDs){
		$Action='0'; //inactive
		$this->Category_model->change_category_status($CategoryIDs ,$Action);
                $CategoryIDArr=explode(',',$CategoryIDs);
		$Data=$this->Category_model->get_parrent_by_category_id($CategoryIDArr[0]);
                //echo '<pre>';print_r($Data);die;
		$parrentCategoryId=$Data[0]->parrentCategoryId;
		$this->session->set_flashdata('Message','Cateory/s inactivated successfully.');
		redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
	}
	
	
	/*public function batchdelete($ProductIDs){
		$this->Product_model->delete($ProductIDs);
		$this->session->set_flashdata('Message','Selected category/s deleted successfully.');
		redirect(base_url().'webadmin/product/viewlist');
	}*/
	
	private function category_image_resize($file,$fileName){
            /// process the image for all size. and water mark
            $PHOTOPATH=$this->config->item('ResourcesPath').'category/';
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
            $config['width'] = 75;
            $config['height'] = 75;
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

            /// ************350X350*************** at first level category
            $config['image_library'] = 'gd2';
            $config['source_image'] = $OriginalFilePath;
            $config['new_image'] = $PHOTOPATH. '350X350/';
            $config['width'] = 350;
            $config['height'] = 350;
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