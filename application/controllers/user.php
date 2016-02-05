<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller{
    public function __construct(){
        parent::__construct();
        $this->_isLoggedIn();
        $this->load->model('User_model');
        $this->load->model('Order_model');
        $this->db->cache_off();
    }
    
    function edit_profile(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=8;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('my/edit_profile',$data);
    }
    
    function my_account(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=1;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('my/myaccount',$data);
    }
    
    function my_shipping_address(){
        $this->load->model('Country');
        $this->load->model('Category_model','category');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=2;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $userShippingDataDetails=$this->User_model->get_user_shipping_information();
        //pre($userShippingDataDetails);die;
        if(empty($userShippingDataDetails)){
            $userShippingDataDetails[0]=new stdClass();
            $userShippingDataDetails[0]->firstName="";
            $userShippingDataDetails[0]->lastName="";
            $userShippingDataDetails[0]->countryId="";
            $userShippingDataDetails[0]->cityId="";
            $userShippingDataDetails[0]->zipId="";
            $userShippingDataDetails[0]->localityId="";
            $userShippingDataDetails[0]->phone="";
            $userShippingDataDetails[0]->address="";
            $userShippingDataDetails[0]->contactNo="";
        }
        if($userShippingDataDetails[0]->countryId!=""){
            $data['cityDataArr']=  $this->Country->get_all_city1($userShippingDataDetails[0]->countryId);
        }
        if($userShippingDataDetails[0]->zipId!=""){
            $data['zipDataArr']=  $this->Country->get_all_zip1($userShippingDataDetails[0]->cityId);
        }
        if($userShippingDataDetails[0]->localityId!=""){
            $data['localityDataArr']=  $this->Country->get_all_locality($userShippingDataDetails[0]->zipId);
        }
        $data['countryDataArr']=$this->Country->get_all1();
        $data['userShippingDataDetails']=$userShippingDataDetails;
        $topCategoryDataArr=$this->category->get_top_category_for_product_list();
        $data['topCategoryDataArr']=$topCategoryDataArr;
        $rs=$this->User_model->get_my_product_type();
        //pre($rs); //die;
        $data['userProductTypeArr']=$rs;
        if(!empty($rs)){
            $billingAddressProductTypeHtml=$this->load->view('billing_address_product_type_view',$data,TRUE);
            //echo $billingAddressProductTypeHtml;die;
            $data['billingAddressProductTypeHtml']=$billingAddressProductTypeHtml;
        }
        $this->load->view('my/my_shipping_address',$data);
    }
    
    function my_billing_address(){
        $this->load->model('Country');
        $this->load->model('Category_model','category');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $userBillingDataDetails=$this->User_model->get_billing_address();
        if(empty($userBillingDataDetails)){
            $userBillingDataDetails[0]=new stdClass();
            $userBillingDataDetails[0]->firstName="";
            $userBillingDataDetails[0]->lastName="";
            $userBillingDataDetails[0]->countryId="";
            $userBillingDataDetails[0]->cityId="";
            $userBillingDataDetails[0]->zipId="";
            $userBillingDataDetails[0]->localityId="";
            $userBillingDataDetails[0]->phone="";
            $userBillingDataDetails[0]->address="";
            $userBillingDataDetails[0]->contactNo="";
            $userBillingDataDetails[0]->email="";
        }
        //pre($userBillingDataDetails);die;
        if($userBillingDataDetails[0]->countryId!=""){
            $data['cityDataArr']=  $this->Country->get_all_city1($userBillingDataDetails[0]->countryId);
        }
        if($userBillingDataDetails[0]->zipId!=""){
            $data['zipDataArr']=  $this->Country->get_all_zip1($userBillingDataDetails[0]->cityId);
        }
        if($userBillingDataDetails[0]->localityId!=""){
            $data['localityDataArr']=  $this->Country->get_all_locality($userBillingDataDetails[0]->zipId);
        }
        $data['userBillingDataDetails']=$userBillingDataDetails;
        $countryDataArr=$this->Country->get_all1();
        //pre($countryDataArr);die;
        $data['countryDataArr']=$this->Country->get_all1();
        $data['userMenuActive']=3;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $topCategoryDataArr=$this->category->get_top_category_for_product_list();
        $data['topCategoryDataArr']=$topCategoryDataArr;
        $rs=$this->User_model->get_my_product_type();
        //pre($rs); //die;
        $data['userProductTypeArr']=$rs;
        if(!empty($rs)){
            $billingAddressProductTypeHtml=$this->load->view('billing_address_product_type_view',$data,TRUE);
            //echo $billingAddressProductTypeHtml;die;
            $data['billingAddressProductTypeHtml']=$billingAddressProductTypeHtml;
        }
        $this->load->view('my/my_billing_address',$data);
    }
    
    function my_orders($rid = 0){
        $this->load->model('Country');
        $this->load->model('Order_model');
        
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $user = $this->_get_current_user_details();
        
        $config = array();
        $cond = array('a.userId'=>$user->userId);
        $config['per_page'] = '5';
        $config['uri_segment'] = '2';
        $orders = $this->Order_model->get_my_all_orders_with_parent($rid,(int)$config['per_page'],$cond);
        $data['orders'] = $orders;
        $total_rows = $this->Order_model->get_my_all_orders_with_parent($rid,null,$cond);
        $config['total_rows'] = (!empty($total_rows)?count($total_rows):0); 
        $data['pagignation'] = $this->global_pagination('my/my-orders',$config);
        $data['rid'] = $rid;
        $data['per_page'] = '5';
        
        
        $orderStatusobj=$this->Order_model->get_state();
        $stateArr=array();
        foreach($orderStatusobj As $k){
            $stateArr[$k->orderStateId]=$k->name;
        }
        $data['status']= $stateArr;
        $data['userMenuActive']=4;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('my/my_orders',$data);
    }
    
    function my_groups(){
        $this->load->model('Category_model');
        $this->load->model('Country');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['countryDataArr']=$this->Country->get_all1();
        //$data['CatArr']=$this->Category_model->get_all(0);
        $menuArr=array();
        $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
        //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->Category_model->get_subcategory_by_category_id($k->categoryId);
            if(count($SubCateory)>0){
                foreach($SubCateory as $kk => $vv){
                    $menuArr[$vv->categoryId]=$k->categoryName.' -> '.$vv->categoryName;
                    $ThirdCateory=$this->Category_model->get_subcategory_by_category_id($vv->categoryId);
                    if(count($ThirdCateory)>0){
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            $menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->Category_model->get_subcategory_by_category_id($v3->categoryId);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }
                            }
                        }
                    }
                }
            }
        }
        $data['CatArr']=$menuArr;
        //$data['CatArr']=$this->Category_model->get_all(0);
        $user = $this->_get_current_user_details();
        $my_groups = $this->User_model->get_my_groups();
        //pre($my_groups);die;
        $data['myGroups']=$my_groups;
        $data['user']=$user;
        $data['userMenuActive']=5;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('my/my_groups',$data);
    }
    
    function edit_groups($groupId){
        if(!$groupId):
            redirect(BASE_URL.'404_override');
        endif;
        $groupId = base64_decode($groupId)/987654321;
        $this->load->model('Category_model');
        $this->load->model('Country');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['countryDataArr']=$this->Country->get_all1();
        $menuArr=array();
        $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
        //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->Category_model->get_subcategory_by_category_id($k->categoryId);
            if(count($SubCateory)>0){
                foreach($SubCateory as $kk => $vv){
                    $menuArr[$vv->categoryId]=$k->categoryName.' -> '.$vv->categoryName;
                    $ThirdCateory=$this->Category_model->get_subcategory_by_category_id($vv->categoryId);
                    if(count($ThirdCateory)>0){
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            $menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->Category_model->get_subcategory_by_category_id($v3->categoryId);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }
                            }
                        }
                    }
                }
            }
        }
        $data['CatArr']=$menuArr;
        $user = $this->_get_current_user_details();
        $group = $this->User_model->get_group_by_id($groupId);
        if(!$group):
            redirect(BASE_URL.'404_override');
        endif;
        
        $data['group']=$group;
        $data['orderId']=0;
        $data['reorder'] = 1;
        $data['user']=$user;
        $data['userMenuActive']=5;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('my/my_group_edit',$data);
    }
    
    function edit_groups_re_order($groupId, $orderId){
        if(!$groupId):
            redirect(BASE_URL.'404_override');
        endif;
        $groupId = base64_decode($groupId)/987654321;
        $this->load->model('Category_model');
        $this->load->model('Country');
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['countryDataArr']=$this->Country->get_all1();
        $menuArr=array();
        $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->Category_model->get_subcategory_by_category_id($k->categoryId);
            if(count($SubCateory)>0){
                foreach($SubCateory as $kk => $vv){
                    $menuArr[$vv->categoryId]=$k->categoryName.' -> '.$vv->categoryName;
                    $ThirdCateory=$this->Category_model->get_subcategory_by_category_id($vv->categoryId);
                    if(count($ThirdCateory)>0){
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            $menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->Category_model->get_subcategory_by_category_id($v3->categoryId);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }
                            }
                        }
                    }
                }
            }
        }
        $data['CatArr']=$menuArr;
        $user = $this->_get_current_user_details();
        $group = $this->User_model->get_group_by_id($groupId);
        $orderId = base64_decode($orderId);
        $order = $this->Order_model->get_single_order_by_id($orderId);
        if(!$group || !$order):
            redirect(BASE_URL.'404_override');
        endif;
        
        $data['orderId']=$orderId;
        $data['group']=$group;
        $data['user']=$user;
        $data['reorder'] = 1;
        $data['userMenuActive']=5;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('my/my_group_edit',$data);
    }
        
    function my_group_orders(){
        
    }
    
    function my_finance_info(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=6;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $financeDataArr=$this->User_model->get_finance_info();
        if(empty($financeDataArr)){
            $financeDataArr[0]=new stdClass();
            $financeDataArr[0]->mpesaFullName="";
            $financeDataArr[0]->mpesaAccount="";
        }
        $data['financeDataArr']=$financeDataArr;
        $this->load->view('my/my_finance',$data);
    }
    
    function my_profile(){
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $data['userMenuActive']=8;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $userDataArr=$this->User_model->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
        $data['userDataArr']=$this->User_model->get_details_by_id($this->session->userdata('FE_SESSION_VAR'));
        $this->load->view('my/edit_profile',$data);
    }
    
    function recusive_category($newCateoryArr,$categoryId){
        $this->load->model('Category_model','category');
        $chieldCateArr=$this->category->get_subcategory_by_category_id1($categoryId);
        pre($chieldCateArr);
        if(empty($chieldCateArr)){
            return $newCateoryArr;
        }else{    
            foreach($chieldCateArr AS $k){
                //pre($k); echo ' =========';
                $newCateoryArr['']=$k;
                $newCateoryArr=$this->recusive_category($newCateoryArr, $k['categoryId']);
            }
            return $newCateoryArr;
        }
    }
    
    function ajax_get_update_message(){
        $this->load->library('cart');
        $mynotification = $this->User_model->notification_my_unread($this->session->userdata('FE_SESSION_VAR'));
        if(!empty($mynotification)):
            $data['tot_notfy'] = count($mynotification);
        else:
            $data['tot_notfy'] = 0;
        endif;

        $cart = $this->Order_model->tidiit_get_user_orders($this->session->userdata('FE_SESSION_VAR'), 0);
        $total = 0;
        if($cart):
            foreach ($cart as $item):
                    $total += $item->orderAmount;
            endforeach;
        endif;
        $data['carttotal'] = number_format($total, 2, '.', '');
        $data['totalitem'] = count($cart);
        echo json_encode( $data );
        die;
    }
    
    function my_parent_orders($orderId){
        $this->load->model('Country');
        $this->load->model('Order_model');
        
        $orderId = base64_decode($orderId);
        $orderId = $orderId/226201;
        
        $SEODataArr=array();
        $data=$this->_get_logedin_template($SEODataArr);
        $order = $this->Order_model->get_single_order_by_id($orderId);
        if(!$order):
            $this->session->set_flashdata('error', 'Invalid url!');            
            redirect(BASE_URL.'shopping/ord-message');
        endif;
        
        
        $orders = $this->Order_model->get_parent_order($orderId);
        $data['orders'] = $orders;
        $orderStatusobj=$this->Order_model->get_state();
        $stateArr=array();
        foreach($orderStatusobj As $k){
            $stateArr[$k->orderStateId]=$k->name;
        }
        $data['status']= $stateArr;
        $data['userMenuActive']=4;
        $data['userMenu']=  $this->load->view('my/my_menu',$data,TRUE);
        $this->load->view('my/my_parent_orders',$data);
    }
}