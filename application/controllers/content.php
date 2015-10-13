<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Cms_model','cms');
    }
    
    function show_content($str){
        if($str==""){
            redirect(BASE_URL);
        }
        $id=  base64_decode($str);
        $contentDetails=  $this->cms->get_content_by_id($id);
        if(empty($contentDetails)){
            redirect(BASE_URL);
        }else{
            $SEODataArr=array();
            $meta=array();
            $meta[]=array('name' => 'description', 'content' => $contentDetails[0]->metaDescription);
            $meta[]=array('name' => 'keywords', 'content' =>$contentDetails[0]->metaKeyWord);
            
            $SEODataArr['MetaTitle']=$contentDetails[0]->title;
            $SEODataArr['meta']=$meta;
            
            if($this->is_loged_in()){
                $data=$this->_get_logedin_template($SEODataArr);
            }else{
                $data=$this->_get_tobe_login_template($SEODataArr);
            }
            
            $data['contentDetails']=$contentDetails;
            $this->load->view('content',$data);
        }
    }
}

