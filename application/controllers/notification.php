<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends MY_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->_isLoggedIn();
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->db->cache_off();
        $this->load->library('cart');
        $this->load->model('Category_model');
        $this->load->model('Product_model');
        $this->load->model('Country');
    }
    
    public function get_my_notifications($rid = 0){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        
        $user = $this->_get_current_user_details();
        
	$config = array();
        $cond = array('status'=>'1', 'receiverId'=>$user->userId, 'isRead' => '0');
        $config['per_page'] = '5';
        $config['uri_segment'] = '2';
        $notifications = $this->User_model->notification_all_my($rid,(int)$config['per_page'],$cond);
        $data['notifications'] = $notifications;
        $total_rows = $this->User_model->notification_all_my($rid,null,$cond);
        $config['total_rows'] = (!empty($total_rows)?count($total_rows):0);        
        $data['notifications'] = $notifications;
        $data['pagignation'] = $this->global_pagination('my-notifications',$config);
        $data['rid'] = $rid;
        
        $data['userMenuActive']= 9;
        $data['userMenu']=  $this->load->view('my_menu',$data,TRUE);
        $this->load->view('notification/notification_my',$data);
    }
}