<?php
class Category extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Category_model');
	}
	
	public function index(){
		redirect(base_url().'admin');
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
		$this->load->view('webadmin/category_list',$data);
	}
	
	public function add(){
		$categoryName=$this->input->post('categoryName',TRUE);
		$note=$this->input->post('note',TRUE);
		$isTop=$this->input->post('isTop',TRUE);
		$parrentCategoryId=$this->input->post('parrentCategoryId',TRUE);
		$status=$this->input->post('status',TRUE);
		
		
		if($isTop==""){
			$isTop=1;
		}
		$dataArr=array(
		'categoryName'=>$categoryName,
		'parrentCategoryId'=>$parrentCategoryId,
		'isTop'=>$isTop,
		'status'=>$status,
                'note'=>$note
		);
		
		//print_r($dataArr);die;
		$this->Category_model->add($dataArr);
		
		$this->session->set_flashdata('Message','Category added successfully.');
		redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
	}
	
	public function edit(){
		$categoryName=$this->input->post('EditcategoryName',TRUE);
		$note=$this->input->post('Editnote',TRUE);
		$status=$this->input->post('Editstatus',TRUE);
		$CategoryID=$this->input->post('CategoryID',TRUE);
		$parrentCategoryId=$this->input->post('parrentCategoryId',TRUE);
		
		if($parrentCategoryId==""){
			$parrentCategoryId=0;
		}
		
		$dataArr=array(
		'categoryName'=>$categoryName,
		'status'=>$status,
                'note'=>$note
		);
		
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
		$this->Category_model->delete($CategoryID);
		
		$this->session->set_flashdata('Message','Category deleted successfully.');
		redirect(base_url().'webadmin/category/viewlist');
	}
        
        public function manage_category_link($CategoryID,$Linktype){
            $this->Category_model->manage_category_link($CategoryID,$Linktype);
            $Data=$this->Category_model->get_parrent_by_category_id($CategoryID);
            //echo '<pre>';print_r($Data);die;
            $parrentCategoryId=$Data[0]->parrentCategoryId;
		
            $this->session->set_flashdata('Message','Category link updated success successfully.');
            redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
        }
        
        
        public function manage_category_add_to_cart_link($CategoryID,$Linktype){
            $this->Category_model->manage_category_add_to_cart_link($CategoryID,$Linktype);
            $Data=$this->Category_model->get_parrent_by_category_id($CategoryID);
            //echo '<pre>';print_r($Data);die;
            $parrentCategoryId=$Data[0]->parrentCategoryId;
		
            $this->session->set_flashdata('Message','Category add to cart link updated success successfully.');
            redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
        }


        public function update_terms($CategoryID,$RegionID=0){
            $data=$this->_show_admin_logedin_layout();
            $Arr=array();
            if($RegionID>0){
                $InitArr=array('Terms'=>'');
                $ckeditor = array(
                            //ID of the textarea that will be replaced
                            'id' 	=> 	'Terms',
                            'path'	=>	$this->config->item('SiteJSURL').'ckeditor',
                            'judhipath'	=>	$this->config->item('SiteJSURL'),
                            //Optionnal values
                            'config' => array(
                                    'toolbar' 	=> 	"Full", 	//Using the Full toolbar
                                    'width' 	=> 	"90%",	//Setting a custom width
                                    'height' 	=> 	'250px',	//Setting a custom height
                            )
                    );
                $Arr=$this->Category_model->get_seo_data($CategoryID,$RegionID);
                //echo '<pre>';print_r($Arr);die;
                $data['val']=$Arr;
                $data['ckeditor']=$ckeditor;
                $data['RegionID']=$RegionID;
                $data['CategoryID']=$CategoryID;
                $this->load->view('webadmin/terms_data1',$data);
            }else{
                $data['val']=$Arr;
                $data['RegionID']=$RegionID;
                $data['CategoryID']=$CategoryID;
                $this->load->view('webadmin/terms_data',$data);
            }
        }
        
        public function save_update_terms(){
            $RegionID=$this->input->post('CategoryTermsRegionID',TRUE);
            $CategoryID=$this->input->post('CategoryID',TRUE);
            $Terms=$this->input->post('Terms',TRUE);
            
            if($RegionID!="" && $CategoryID!=""){
                $this->Category_model->manage_seo_data(array('Terms'=>$Terms),$RegionID,$CategoryID);
                $parrent=$this->Category_model->getProductCategoryParrentCategoryInfo($CategoryID);
                redirect(base_url().'webadmin/category/viewlist/'.$parrent[0]->ParrentID);
            }else{
                redirect(base_url().'webadmin/category/viewlist');
            }
            
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
	
	public function batchpopularstore($CategoryIDs){
		$this->Category_model->popularstore($CategoryIDs);
		
		$this->session->set_flashdata('Message','Product/s popular stores successfully.');
		redirect(base_url().'webadmin/category/viewlist');
	}
        
        public function manage_seo_data(){
            //echo '<pre>';
            //print_r($_POST);
            $RegionID=$this->input->post('CategorySEORegionID');
            $parrentCategoryId=$this->input->post('parrentCategoryId');
            $CategoryID=$this->input->post('SEODataCategoryID');
            $Title=$this->input->post('Title');
            $Keywords=$this->input->post('Keywords');
            $Description=$this->input->post('Description');
            $abstract=$this->input->post('abstract');
            $dc_relation=$this->input->post('dc_relation');
            $dc_title=$this->input->post('dc_title');
            $dc_keywords=$this->input->post('dc_keywords');
            $dc_subject=$this->input->post('dc_subject');
            $dc_description=$this->input->post('dc_description');
            $geo_placename=$this->input->post('geo_placename');
            $geo_position=$this->input->post('geo_position');
            $geo_region=$this->input->post('geo_region');
            $ICBM=$this->input->post('ICBM');
            $classification=$this->input->post('classification');
            $resource_type=$this->input->post('resource_type');
            $dataArr=array('Title'=>$Title,'Keywords'=>$Keywords,'Description'=>$Description,'abstract'=>$abstract,'dc_relation'=>$dc_relation,
                'dc_title'=>$dc_title,'dc_keywords'=>$dc_keywords,'dc_subject'=>$dc_subject,'dc_description'=>$dc_description,
                'geo_placename'=>$geo_placename,'geo_position'=>$geo_position,'geo_region'=>$geo_region,'ICBM'=>$ICBM,'classification'=>$classification,
                'resource_type'=>$resource_type);
            $this->Category_model->manage_seo_data($dataArr,$RegionID,$CategoryID);
            //print_r($dataArr);die;
            redirect(base_url().'webadmin/category/viewlist/'.$parrentCategoryId);
        }
}
?>