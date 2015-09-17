<?php
class Brand extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Brand_model');
	}
	
	public function index(){
		redirect(base_url().'webadmin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Brand_model->get_all_admin();
		$this->load->view('webadmin/brand_list',$data);
	}
        
        function add(){
            //pre($_FILES);die;
            if($_FILES['brandImage']['name']!=""){
                //$imagePath=$this->config->item('ResourcesPath').'banner/';
                $file=$_FILES['brandImage'];
                $image=time().'.'.end(explode('.',$file['name']));
                //move_uploaded_file($file['tmp_name'],$imagePath.$image);
                $this->brand_image_resize($file,$image);
                $title=$this->input->post('title',TRUE);
                
                $status=$this->input->post('status',TRUE);
                $dataArr=array(
                    'brandImage'=>$image,
                    'status'=>1,
                    'title'=>$title
                    );
                $this->Brand_model->add($dataArr);
                
                $this->session->set_flashdata('Message','Brand  added successfully.');
            }else{
                $this->session->set_flashdata('Message','Invalid Banner uploaded.');
            }
            redirect(base_url().'webadmin/brand/viewlist');
        }
	
	
	public function edit(){
		$title=$this->input->post('Edittitle',TRUE);
		$brandId=$this->input->post('brandId',TRUE);
                $image='';
                $details=$this->Brand_model->details($brandId);
                if($_FILES['EditbrandImage']['name']!=""){
                    $file=$_FILES['EditbrandImage'];
                    $image=time().'.'.end(explode('.',$file['name']));
                    //move_uploaded_file($file['tmp_name'],$imagePath.$image);
                    $this->brand_image_resize($file,$image);
                }
		
		$dataArr=array(
		'title'=>$title
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
            pre($details);die;
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