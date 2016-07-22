<?php
class Index extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->load->model('Country');
        
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
        $allOrderArr=$this->Order_model->get_order_for_statics();
        $placeOrders=0;
        $confirmOrders=0;
        $shippedOrders=0;
        $outForDeliveryOrders=0;
        $successOrders=0;
        $cancelOrders=0;
        //$suc
        foreach($allOrderArr AS $k){
            if($k->status==2){
                $placeOrders++;
            }else if($k->status==3){
                $confirmOrders++;
            }else if($k->status==4){
                $shippedOrders++;
            }else if($k->status==5){
                $outForDeliveryOrders++;
            }else if($k->status==6){
                $successOrders++;
            }else if($k->status==0){
                $cancelOrders++;
            }
        }
        //echo $cancelOrders;die;
        $data['placeOrders']=$placeOrders;
        $data['confirmOrders']=$confirmOrders;
        $data['shippedOrders']=$shippedOrders;
        $data['outForDeliveryOrders']=$outForDeliveryOrders;
        $data['successOrders']=$successOrders;
        $data['cancelOrders']=$cancelOrders;
        
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
    
    function edit_profile(){
        $data = $this->_get_logedin_template();
        $me=$this->session->userdata['FE_SESSION_UDATA'];
        $billAddress=  $this->User_model->get_billing_address();


        if($billAddress):
            $data['billAddressDataArr'] = $billAddress;
        else:
            $data['billAddressDataArr'][0] = new stdClass;
            $data['billAddressDataArr'][0]->address = '';
            $data['billAddressDataArr'][0]->city = '';
            $data['billAddressDataArr'][0]->countryId = '';
            $data['billAddressDataArr'][0]->stateId = '';
            $data['billAddressDataArr'][0]->zip = '';
        endif;


        $data['me'] = $me;
        $data['CountryDataArr'] = $this->Country->get_all1();
        if($billAddress):
        $data['stateDataArr'] = $this->Country->get_state_country1($billAddress[0]->countryId);
        else:
            $data['stateDataArr'] = [];
        endif;

        
        $this->load->view('edit_profile',$data);
    }
    
    function update_profile(){
        $config = array(
            array('field'   => 'firstName','label'   => 'First Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]'),
            array('field'   => 'lastName','label'   => 'Last Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[25]'),
            array('field'   => 'email','label'   => 'Email','rules'   => 'trim|required|xss_clean|valid_email'),
            array('field'   => 'contactNo','label'   => 'Contact No','rules'   => 'trim|required|xss_clean|is_natural|min_length[7]|max_length[12]'),
            array('field'   => 'mobile','label'   => 'Contact No','rules'   => 'trim|xss_clean|is_natural|min_length[7]|max_length[12]'),
            array('field'   => 'address','label'   => 'Address','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'city','label'   => 'City','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'stateId','label'   => 'State Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'countryId','label'   => 'Country Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'zip','label'   => 'Zip','rules'   => 'trim|required|xss_clean|is_natural|min_length[5]|max_length[8]')
         );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
            //retun to login page with peroper error
            $this->session->set_flashdata('Message',validation_errors());
            redirect(BASE_URL.'index/edit_profile/');
        }else{
            $firstName=$this->input->post('firstName',TRUE);
            $lastName=$this->input->post('lastName',TRUE);
            $email=$this->input->post('email',TRUE);
            $mobile=$this->input->post('mobile',TRUE);
            $contactNo=$this->input->post('contactNo',TRUE);
            $fax=$this->input->post('fax',TRUE);
            $address=$this->input->post('address',TRUE);
            $city=$this->input->post('city',TRUE);
            $stateId=$this->input->post('stateId',TRUE);
            $countryId=$this->input->post('countryId',TRUE);
            $zip=$this->input->post('zip',TRUE);
            $aboutMe=$this->input->post('aboutMe',TRUE);
            
            $dataArr=array('firstName'=>$firstName,'lastName'=>$lastName,'contactNo'=>$contactNo,'mobile'=>$mobile,'email'=>$email,'fax'=>$fax,'aboutMe'=>$aboutMe);
            $this->User_model->edit($dataArr,$this->session->userdata('FE_SESSION_VAR'));
            //pre($dataArr);die;
            $billDataArr=array('countryId'=>$countryId,'stateId'=>$stateId,'city'=>$city,'address'=>$address,'zip'=>$zip,'contactNo'=>$contactNo);

            $this->User_model->edit_biiling_info($billDataArr,$this->session->userdata('FE_SESSION_VAR'));
            $this->session->set_flashdata('Message','Profile information updated successfully.');
            redirect(BASE_URL.'index/edit_profile/');
        }
    }
    
    function edit_finance_info(){
        $data = $this->_get_logedin_template();
        $this->load->view('under_construction',$data);
    }

    function my_warehouse(){
        $data = $this->_get_logedin_template();
        $me=$this->session->userdata['FE_SESSION_UDATA'];
        $data['CountryDataArr'] = $this->Country->get_all1();
        $data['warehouses'] = $this->User_model->get_all_warehouse();
        $this->load->view('my_warehouse',$data);
    }

    function update_warehouse(){
        $config = array(
            array('field'   => 'companyName','label'   => 'Company Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[60]'),
            array('field'   => 'taxInvoice','label'   => 'Last Name','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[50]'),
            array('field'   => 'vatNumber','label'   => 'Email','rules'   => 'trim|required|xss_clean|min_length[3]|max_length[50]'),
            array('field'   => 'contactNo','label'   => 'Contact No','rules'   => 'trim|required|xss_clean|is_natural|min_length[7]|max_length[12]'),
            array('field'   => 'mobile','label'   => 'Contact No','rules'   => 'trim|xss_clean|is_natural|min_length[7]|max_length[12]'),
            array('field'   => 'address1','label'   => 'Address','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'address2','label'   => 'Address','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'city','label'   => 'City','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'stateId','label'   => 'State Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'countryId','label'   => 'Country Name','rules'   => 'trim|required|xss_clean'),
            array('field'   => 'zip','label'   => 'Zip','rules'   => 'trim|required|xss_clean|is_natural|min_length[5]|max_length[8]')
        );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config);
        //checking validation
        if($this->form_validation->run() == FALSE){
            //retun to login page with peroper error
            $this->session->set_flashdata('Message',validation_errors());
            redirect(BASE_URL.'index/my_warehouse/');
        }else{
            $_POST['sellerId'] = $this->session->userdata('FE_SESSION_VAR');
            $this->User_model->add_warehouse($_POST);
            $this->session->set_flashdata('Message','Warehouse information added successfully.');
            redirect(BASE_URL.'index/my_warehouse/');
        }
    }
}
