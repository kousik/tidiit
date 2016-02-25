<?php
class Category extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Category_model');
		$this->load->model('Product_model');
        $this->load->model('Option_model');
	}
	
	public function index(){
		redirect(base_url().'webadmin/');
	}
	
	public function viewlist($parrentId=0){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Category_model->get_all($parrentId);
        $data['options']=$this->Option_model->get_all_admin();
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
		if($_FILES['categoryImage']['name']==""){
                    $this->session->set_flashdata('Message','Please Browse Category Image.');
                }else{
                    if($_FILES['categoryImage']['name']!=""){
                        $file=$_FILES['categoryImage'];
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
                        'metaDescription'=>$metaDescription,
                        'is_last' => $this->input->post('is_last',TRUE),
                        'option_ids' => ($this->input->post('is_last',TRUE) && $this->input->post('options',TRUE))?implode(",",$this->input->post('options',TRUE)):''
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
            if(array_key_exists('EditcategoryImage', $_FILES)){
                if($_FILES['EditcategoryImage']['name']!=""){
                    $categoryDetails=$this->Category_model->get_details_by_id($CategoryID);
                    $file=$_FILES['EditcategoryImage'];
                    $image=time().'.'.end(explode('.',$file['name']));
                    //move_uploaded_file($file['tmp_name'],$imagePath.$image);
                    $this->category_image_resize($file,$image);
                     $this->delete_img($categoryDetails[0]->image);
                }
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
                        'metaDescription'=>$metaDescription,
                        'is_last' => $this->input->post('is_last',TRUE),
                        'option_ids' => ($this->input->post('is_last',TRUE) && $this->input->post('options',TRUE))?implode(",",$this->input->post('options',TRUE)):''
                    );
            if($image!=""){
                $dataArr['image']=$image;
            }
            //print_r($dataArr);die;
            
            $this->Category_model->edit($dataArr,$CategoryID);


            $this->session->set_flashdata('Message','Category updated successfully.');
            redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
	}
	
	public function change_status($categoryId,$Action){
            $retArr=$this->recusive_category($categoryId);
            $retArr[]=$categoryId;
            $this->Category_model->change_category_status($retArr,$Action);
            
            $Data=$this->Category_model->get_parrent_by_category_id($categoryId);
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
            //$this->delete_img($categoryDetails[0]->image);
            $retArr=$this->recusive_category($CategoryID);
            $retArr[]=$CategoryID;
            $rs=$this->db->from('category')->where_in('categoryId',$retArr)->get()->result();
            foreach($rs AS $k){
                if($k->image!="")
                    $this->delete_img($k->image);
            }
            //pre($rs);die;
            $this->Category_model->delete($retArr);
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
                $CategoryIDsArr=  explode(',',$CategoryIDs);
                foreach($CategoryIDsArr AS $k =>$v){
                    $retArr=$this->recusive_category($v);
                    $CategoryIDsArr=  array_merge($CategoryIDsArr,$retArr);
                }
                //pre($CategoryIDsArr);die;
		$this->Category_model->change_category_status($CategoryIDsArr ,$Action);
                $CategoryIDArr=explode(',',$CategoryIDs);
		$Data=$this->Category_model->get_parrent_by_category_id($CategoryIDArr[0]);
                //echo '<pre>';print_r($Data);die;
		$parrentCategoryId=$Data[0]->parrentCategoryId;
		$this->session->set_flashdata('Message','Category activated successfully.');
		redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
	}
	
	public function batchinactive($CategoryIDs){
		$Action='0'; //inactive
		$CategoryIDsArr=  explode(',',$CategoryIDs);
                foreach($CategoryIDsArr AS $k =>$v){
                    $retArr=$this->recusive_category($v);
                    $CategoryIDsArr=  array_merge($CategoryIDsArr,$retArr);
                }
                //pre($CategoryIDsArr);die;
		$this->Category_model->change_category_status($CategoryIDsArr ,$Action);
                $CategoryIDArr=explode(',',$CategoryIDs);
		$Data=$this->Category_model->get_parrent_by_category_id($CategoryIDArr[0]);
                //echo '<pre>';print_r($Data);die;
		$parrentCategoryId=$Data[0]->parrentCategoryId;
		$this->session->set_flashdata('Message','Category inactivated successfully.');
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
        
    function recusive_category($categoryId,$newCateoryArr=array()){
        $this->load->model('Category_model','category');
        $chieldCateArr=$this->category->get_without_status_subcategory_by_category_id($categoryId);
        if(empty($chieldCateArr)){
            return $newCateoryArr;
        }else{    
            foreach($chieldCateArr AS $k){
                $newCateoryArr[]=$k->categoryId;
                $newCateoryArr=$this->recusive_category($k->categoryId,$newCateoryArr);
            }
            return $newCateoryArr;
        }
    }
}
?>