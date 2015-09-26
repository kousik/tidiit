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
		$this->load->view('webadmin/banner_list',$data);
	}
	
	public function add(){
            if($_FILES['Banner']['name']!=""){
                //$imagePath=$this->config->item('ResourcesPath').'banner/';
                $file=$_FILES['Banner'];
                $image=time().'.'.end(explode('.',$file['name']));
                //move_uploaded_file($file['tmp_name'],$imagePath.$image);
                $this->banner_image_resize($file,$image);
                $status=$this->input->post('status',TRUE);
                $pageId=$this->input->post('pageId',TRUE);

                $BannerDataArr=$this->Banner_model->get_banner_id_by_page($pageId);
                if(empty($BannerDataArr)){
                    $dataArr=array(
                    'image'=>$image,
                    'pageId'=>$pageId,
                    'status'=>$status
                    );
                    $this->Banner_model->add($dataArr);
                }else{
                    $this->delete_img($BannerDataArr->image);
                    $this->Banner_model->edit(array('image'=>$image),$BannerDataArr->bannerId);
                }

                $this->session->set_flashdata('Message','Banner added successfully.');
                redirect(base_url().'webadmin/banner/viewlist');	
            }else{
                $this->session->set_flashdata('Message','Invalid Banner uploaded.');
                redirect(base_url().'webadmin/banner/viewlist');	
            }
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