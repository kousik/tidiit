<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shopping extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->_isLoggedIn();
        $this->load->model('User_model');
        $this->db->cache_off();
        $this->load->library('cart');
        $this->load->model('Category_model');
        $this->load->model('Country');
    }
        
    function process_my_group_orders(){
        
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details(); //print_r($user);die;
        $productId = $this->input->post('productId');
        $prorductPriceId = $this->input->post('prorductPriceId');
        $data['userMenuActive']=7;
        $data['countryDataArr']=$this->Country->get_all1();
        $data['CatArr']=$this->Category_model->get_all(0);
        $user = $this->_get_current_user_details();
        $my_groups = $this->User_model->get_my_groups();
        $data['myGroups']=$my_groups;
        $data['user']=$user;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('group_order/group_order',$data);
    }
    
    function _add_to_cart($data) {
        
        // Set array for send data.
        $insert_data = array(
            'id' => 0,
            'name' => '',
            'price' => 0,
            'qty' => 0
        );
        
        $data = array_merge($data, $insert_data);
        
        // This function add items into cart.
        $this->cart->insert($data);
    }
    
    
    function _update_cart(){

        // Recieve post values,calcute them and update
        $cart_info = $_POST['cart'] ;
        foreach( $cart_info as $id => $cart) {
            $rowid = $cart['rowid'];
            $price = $cart['price'];
            $amount = $price * $cart['qty'];
            $qty = $cart['qty'];

            $data = array(
                'rowid' => $rowid,
                'price' => $price,
                'amount' => $amount,
                'qty' => $qty
            );

            $this->cart->update($data);
        }
    }
    
    
    function _remove_cart($rowid) {
        // Check rowid value.
        if ($rowid==="all"){
            // Destroy data which store in session.
            $this->cart->destroy();
        }else{
            // Destroy selected rowid in session.
            $data = array(
                'rowid' => $rowid,
                'qty' => 0
            );
            // Update cart data, after cancel.
            $this->cart->update($data);
        }
    }
}