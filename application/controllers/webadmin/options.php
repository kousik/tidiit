<?php
class Options extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Option_model');
		$this->load->model('Category_model');
	}
	
	public function index(){
		redirect(base_url().'webadmin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
		$data['DataArr']=$this->Option_model->get_all_admin();
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
		$this->load->view('webadmin/options_list',$data);
	}
        

	
	
	public function edit(){
		$id = $this->input->post('id',TRUE);
        if($id):
            $details = $this->Option_model->details($id);
            if(!$details->slug):
                $_POST['slug'] = $this->slugify($_POST['name']."_".$_POST['display_name'])."-".mt_rand(100000, 999999);
            endif;
        else:
            $_POST['slug'] = $this->slugify($_POST['name']."_".$_POST['display_name'])."_".mt_rand(100000, 999999);
        endif;
        unset($_POST['id']);
        unset($_POST['Submit3']);

		$dataArr = $_POST;

        if($id):
            $this->Option_model->edit($dataArr,$id);
		    $this->session->set_flashdata('Message','Option Updated successfully.');
        else:
            //pre($dataArr);die;
            $this->Option_model->add($dataArr);
            $this->session->set_flashdata('Message','Option Added successfully.');
        endif;
		redirect(base_url().'webadmin/options/viewlist');
	}
        
        public function change_status($brandId,$Action){
            $this->Option_model->change_status($brandId,$Action);

            $this->session->set_flashdata('Message','Option status updated successfully.');
            redirect(base_url().'webadmin/options/viewlist');
	}
	
	public function delete($brandId){
            $details=$this->Option_model->details($brandId);

            $this->Option_model->delete($brandId);
            $this->session->set_flashdata('Message','Option deleted successfully.');
            redirect(base_url().'webadmin/options/viewlist');
	}    


    public function slugify($text){
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text))
        {
            return 'n-a';
        }

        return $text;
    }
}