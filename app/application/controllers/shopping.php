<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';
class Shopping extends REST_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('User_model','user');
        $this->load->model('Siteconfig_model','siteconfig');
        //$this->load->model('Testimonial_model');
        $this->load->model('Cms_model','cms');
        $this->load->model('Product_model','product');
        $this->load->model('Category_model','category');
        $this->load->model('Order_model','order');
        $this->load->model('Country');
    }
    
    function add_to_cart_post(){
        
    }
}
    
?>