<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller{
    public function __construct(){
        parent::__construct();
        //$this->db->cache_off();
    }
    
    function details($name,$idStr){
        $idStrArr=  explode('~', $idStr);
        if(count($idStrArr)>0){
            $categoryDetails=$this->Category_model->get_details_by_id(base64_decode($idStrArr[0]));
            if(!empty($categoryDetails)){
                $SEODataArr=array();
                if($this->is_loged_in()){
                    $data=$this->_get_logedin_template($SEODataArr);
                }else{
                    $data=$this->_get_tobe_login_template($SEODataArr);
                }
                $data['is_loged_in'] = $this->is_loged_in();
                if($this->Category_model->has_chield(base64_decode($idStrArr[0])))
                    $this->load->view('category_details',$data);
                else
                    $this->load->view('category_details1',$data);
            }else{
                redirect(BASE_URL);
            }
        }else{
            redirect(BASE_URL);
        }
    }
    
    
}