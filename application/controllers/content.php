<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Cms_model','cms');
    }
    
    function show_content($str){
        //echo $str;die('rrr');
        if($str==""){
            redirect(BASE_URL);
        }
        $id=  base64_decode($str);
        //pre($id);die;
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
            
            $data['feedback']=$this->load->view('feedback',$data,TRUE);
            $data['common_how_it_works']=$this->load->view('common_how_it_works',$data,TRUE);
            
            $this->load->view('content',$data);
        }
    }
    
    function empty_content(){
        $this->db->truncate('cms');
    }
    
    function set_data_by_sql_statement(){
        $sql="INSERT INTO `cms` (`cmsId`, `title`, `metaTitle`, `metaKeyWord`, `metaDescription`, `shortBody`, `body`, `categoryId`, `status`) VALUES
(1, 'About Us', 'About Tidiit', 'About Tidiit', 'About Tidiit', 'About Tidiit', '<p>\r\n About Tidiit</p>\r\n', NULL, 1),
(2, 'Terms of Use', 'Terms & Conditions', 'Terms & Conditions', 'Terms & Conditions', 'Terms & Conditions', '<p>\r\n Terms & Conditions</p>\r\n', NULL, 1),
(3, 'Privac Policy', 'Privac Policy', 'Privac Policy', 'Privac Policy', 'Privac Policy', '<p>\r\n Privac Policy</p>\r\n', NULL, 1),
(4, 'Payment Method', 'Payment Method', 'Payment Method', 'Payment Method', 'Payment Method', '<p>\r\n Payment Method</p>\r\n', NULL, 1),
(5, 'Coupon', 'Coupon', 'Coupon', 'Coupon', 'Coupon', '<p>\r\n Coupon</p>\r\n', NULL, 1),
(6, 'Careers', 'Careers', 'Careers', 'Careers', 'Careers', '<p>\r\n Careers</p>\r\n', NULL, 1),
(7, 'Press', 'Press', 'Press', 'Press', 'Press', '<p>\r\n Press</p>\r\n', NULL, 1),
(8, 'Delivery Options', 'Delivery Options', 'Delivery Options', 'Delivery Options', 'Delivery Options', '<p>\r\n Delivery Options</p>\r\n', NULL, 1),
(9, 'Customs & Import Tax', 'Customs & Import Tax', 'Customs & Import Tax', 'Customs & Import Tax', 'Customs & Import Tax', '<p>\r\n Customs & Import Tax</p>\r\n', NULL, 1),
(10, 'Refund & Return Process', 'Refund & Return Process', 'Refund & Return Process', 'Refund & Return Process', 'Refund & Return Process', '<p>\r\n Refund & Return Process</p>\r\n', NULL, 1),
(11, 'Tidiit Resolution Center', 'Tidiit Resolution Center', 'Tidiit Resolution Center', 'Tidiit Resolution Center', 'Tidiit Resolution Center', '<p>\r\n Tidiit Resolution Center</p>\r\n', NULL, 1),
(12, 'Terms of Sale', 'Terms of Sale', 'Terms of Sale', 'Terms of Sale', 'Terms of Sale', '<p>\r\n Terms of Sale</p>\r\n', NULL, 1),
(13, 'Report Abuse', 'Report Abuse', 'Report Abuse', 'Report Abuse', 'Report Abuse', '<p>\r\n Report Abuse</p>\r\n', NULL, 1),
(14, 'Contact Us', 'Contact Us', 'Contact Us', 'Contact Us', 'Contact Us', '<p>\r\n Contact Us</p>\r\n', NULL, 1),
(15, 'Help', 'Help', 'Help', 'Help', 'Help', '<p>\r\n Help</p>\r\n', NULL, 1),
(16, 'Customer Service', 'Customer Service', 'Customer Service', 'Customer Service', 'Customer Service', '<p>\r\n Customer Service</p>\r\n', NULL, 1),
(17, 'Buyer Protection', 'Buyer Protection', 'Buyer Protection', 'Buyer Protection', 'Buyer Protection', '<p>\r\n Buyer Protection</p>\r\n', NULL, 1);";
        $this->db->query($sql);
    }
}

