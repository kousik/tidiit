<?php
class Index extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        $this->load->model('User_model');
        
        parse_str($_SERVER['QUERY_STRING'], $_GET);
        
        $this->db->cache_off();
    }

    function index() {
        if ($this->_is_loged_in() == FALSE) {
            //$this->session->set_flashdata('Message', 'Please login to access admin section');
            redirect(base_url() . 'index/login');
        } else {
            redirect(base_url() . 'index/home');
        }
    }

    function home() {
        $data = $this->_get_logedin_template();
        $data['viewsData'] =$this->Product_model->get_views_times_by_seller();
        $this->load->view('home', $data);
    }

    function login() {
        $this->load->model('Country');
        $data = $this->_get_tobe_login_template();
        $data['CountryDataArr'] = $this->Country->get_all1();
        $this->load->view('login', $data);
    }

    
    function logout() {
        $this->_logout();
        redirect(BASE_URL . 'index');
    }

    function messurement_list() {
        $this->load->model('Messurement_model');
        $data = $this->_get_logedin_template();
        $data['messureDataArr'] = $this->Messurement_model->get_all();
    }

    function update_brand_page_type() {
        $data = $this->_get_logedin_template();
        $this->load->view('settings_brand_page_type', $data);
    }

    function a_update_brand_page_type() {
        $this->load->model('Sitedata_model');
        $brandName = $this->input->post('brandName', TRUE);
        $productPageType = $this->input->post('productPageType', TRUE);

        if (trim($brandName) == "" && trim($productPageType) == "") {
            $this->update_brand_page_type();
        } else {
            $userId = $this->session->userdata('FE_SESSION_VAR');
            //echo $userId;die;
            $rsBrandDataArr = $this->User_model->get_user_brand($userId);
            if (empty($rsBrandDataArr)) {
                $brandId = $this->Sitedata_model->add_brand(array('title' => $brandName));
                $this->User_model->add_user_brand(array('userId' => $userId, 'brandId' => $brandId));
            }

            $rsPageTypeDataArr = $this->User_model->get_user_page_type($userId);
            if (empty($rsPageTypeDataArr)) {
                $this->User_model->add_user_page_type(array('userId' => $userId, 'pageType' => $productPageType));
            }
            $this->home();
        }
    }

}
