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
    
    /**
     * 
     * @param type $rid
     */
    public function get_my_notifications($rid = 0){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        
        $user = $this->_get_current_user_details();
        
	$config = array();
        $cond = array('status'=>'1', 'receiverId'=>$user->userId);
        $config['per_page'] = '8';
        $config['uri_segment'] = '2';
        $notifications = $this->User_model->notification_all_my($rid,(int)$config['per_page'],$cond);
        $data['notifications'] = $notifications;
        $total_rows = $this->User_model->notification_all_my($rid,null,$cond);
        $config['total_rows'] = (!empty($total_rows)?count($total_rows):0);        
        $data['notifications'] = $notifications;
        $data['pagignation'] = $this->global_pagination('my-notifications',$config);
        $data['rid'] = $rid;
        $data['per_page'] = '8';
        $data['userMenuActive']= 9;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('notification/notification_my',$data);
    }
    
    /**
     * 
     */
    public function ajax_notification_delete(){
        $nd = $this->input->post('nd',TRUE);
        $nd = base64_decode($nd);
        $nd = $nd/226201;
        $delete = $this->User_model->notification_delete($nd);
        if($delete):
            $url = $this->input->post('url',TRUE);
            $ppage = $this->input->post('ppage',TRUE);
            (int)$last = $this->input->post('last',TRUE);
            (int)$list = $this->input->post('list',TRUE);
            if(is_numeric($list) && $list > 1 ){
                $result['url'] = $url;
            } else {
                $sub = $last-$ppage;
                if($last):
                    $sub = $sub;
                else:
                    $sub = '';
                endif;
                $result['url'] = BASE_URL.'my-notifications/'.$sub;
            }
            
            $this->session->set_flashdata('msg', 'Notification deleted!');
            $result['msg'] = $delete;
            echo json_encode( $result );
            die;
        else:    
            $result['error'] = "Promo code has been applied successfully!";
            echo json_encode( $result );
            die;
        endif;
    }
    
    /**
     * 
     */
    public function ajax_notification_view(){
        $nd = $this->input->post('nd',TRUE);
        $nd = base64_decode($nd);
        $nd = $nd/226201;
        $nType = $this->input->post('tp',TRUE);
        $notfi = $this->User_model->notification_single($nd);
        $data['n'] = $notfi;
        
        if($notfi):
            $cond['id'] = $nd;
            $setdata['isRead'] = 1;
            $this->User_model->notification_update($cond, $setdata);
        endif;
        $result['msg'] = $notfi?$this->load->view('notification/notification_view',$data,true):false;
        echo json_encode( $result );
        die;
    }
}