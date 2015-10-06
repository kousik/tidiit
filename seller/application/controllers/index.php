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
            $this->session->set_flashdata('Message', 'Please login to access admin section');
            redirect(base_url() . 'index/login');
        } else {
            redirect(base_url() . 'index/home');
        }
    }

    function home() {
        $data = $this->_get_logedin_template();
        $this->load->view('home', $data);
    }

    function login() {
        $this->load->model('Country');
        $data = $this->_get_tobe_login_template();
        $data['CountryDataArr'] = $this->Country->get_all();
        $this->load->view('login', $data);
    }

    function submit_login() {
        $config = array(
            array(
                'field' => 'userName',
                'label' => 'User Name',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|xss_clean'
            )
        );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config);
        //checking validation
        if ($this->form_validation->run() == FALSE) {
            //retun to login page with peroper error
            $error_msg = validation_errors();
            $this->session->set_flashdata('Message', $error_msg);
            redirect(base_url() . 'index/login');
        } else {
            $UserName = $this->input->post('userName', TRUE);
            $Password = $this->input->post('password', TRUE);
            $DataArr = $this->User_model->is_valid_data($UserName, $Password);
            //print_r($DataArr);die;
            if (count($DataArr) > 0) {
                $roleArr = $this->Admin_model->get_roles_for_user($DataArr[0]->userId);
                $this->session->set_userdata('FE_SESSION_VAR', $DataArr[0]->userId);
                $this->session->set_userdata('FE_SESSION_USERNAME_VAR', $UserName);
                $this->session->set_userdata('FE_SESSION_USERNAME_VAR', $UserName);
                $this->session->set_userdata('FE_SESSION_VAR_TYPE', 'seller');

                $this->session->set_flashdata('Message', 'You have logedin successfully.');
                redirect(base_url() . 'index/home');
            } else {
                $this->session->set_flashdata('Message', 'Invalid Login information,Please try again');
                redirect(base_url() . 'index/login');
            }
        }
    }

    function submit_register() {
        $config = array(
            array(
                'field' => 'userName',
                'label' => 'User Name',
                'rules' => 'trim|required|xss_clean|is_unique[user.userName]'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|xss_clean|matches[confirmPassword]'
            ),
            array(
                'field' => 'confirmPassword',
                'label' => 'Password',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|xss_clean|valid_email|is_unique[user.email]'
            ),
            array(
                'field' => 'firstName',
                'label' => 'First Name',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'lastName',
                'label' => 'Last Name',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'contactNo',
                'label' => 'Contact No',
                'rules' => 'trim|required|xss_clean|is_natural|max_length[10]'
            ),
            array(
                'field' => 'mobile',
                'label' => 'Mobile',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'fax',
                'label' => 'FAX',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'address',
                'label' => 'Address',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'city',
                'label' => 'City',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'stateId',
                'label' => 'State',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'countryId',
                'label' => 'Country',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'zip',
                'label' => 'Zip Code',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'aboutMe',
                'label' => 'About me',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'secret',
                'label' => 'Security Code',
                'rules' => 'trim|required|xss_clean'
            )
        );
        $this->form_validation->set_rules($config);
        //checking validation
        if ($this->form_validation->run() == FALSE) {
            //retun to login page with peroper error
            $error_msg = validation_errors();
            $this->session->set_flashdata('Message', $error_msg);
            redirect(base_url() . 'index/register');
        } else {
            $secret = $this->input->post('secret', TRUE);
            $server_secret = $this->session->userdata('secret');
            if ($server_secret != $secret) {
                $this->session->set_flashdata('Message', 'Invalid security code provided.');
                redirect(base_url() . 'index/login');
            }
            $userName = $this->input->post('userName', TRUE);
            $password = $this->input->post('password', TRUE);
            $email = $this->input->post('email', TRUE);
            $firstName = $this->input->post('firstName', TRUE);
            $lastName = $this->input->post('lastName', TRUE);
            $contactNo = $this->input->post('contactNo', TRUE);
            $mobile = $this->input->post('mobile', TRUE);
            $fax = $this->input->post('fax', TRUE);
            $address = $this->input->post('address', TRUE);
            $city = $this->input->post('city', TRUE);
            $stateId = $this->input->post('stateId', TRUE);
            $countryId = $this->input->post('countryId', TRUE);
            $zip = $this->input->post('zip', TRUE);
            $IP = $this->input->ip_address();
            $aboutMe = $this->input->post('aboutMe', TRUE);

            $dataArr = array('userName' => $userName, 'password' => base64_encode($password) . '~' . md5('tidiit'), 'email' => $email, 'firstName' => $firstName,
                'lastName' => $lastName, 'contactNo' => $contactNo, 'mobile' => $mobile, 'fax' > $fax, 'address' => $address, 'city' => $city, 'stateId' => $stateId,
                'countryId' => $countryId, 'zip' => $zip, 'IP' => $IP, 'userResources' > 'site', 'userType' => 'seller', 'status' => 0, 'aboutMe' => $aboutMe);
            $userId = $this->User_model->add($dataArr);

            $this->load->library('email');
            $msg = $this->get_user_reg_email_body($firstName, $userName, $password);
            $SupportEmail = $this->Siteconfig_model->get_value_by_name('SupportEmail');
            $this->email->from($SupportEmail, 'Tidiit Inc Ltd Support');
            $this->email->to($email, $firstName . ' ' . $lastName);
            $this->email->subject('Your Tidiit Inc Ltd account information.');
            $this->email->message($msg);
            $this->email->send();

            $billDataArr = array('userId' => $userId, 'countryId' => $countryId, 'stateId' => $stateId, 'city' => $city, 'address' => $address, 'zip' => $zip, 'contactNo' => $contactNo);
            $this->User_model->add_bill_address($billDataArr);

            $this->session->set_flashdata('Message', 'You have successfully register your account with Dailyplaza.Your login information will be sent to registered email account.');
            redirect(base_url());
        }
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
