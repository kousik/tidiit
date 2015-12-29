<?php 
class Ajax extends MY_Controller{
    public function __construct(){
            parent::__construct();
            $this->load->model('User_model');
            $this->load->model('Country');
            $this->db->cache_off();
    }
    
    public function get_state_checkbox(){
        $this->load->model('Country');
        $countryId=$this->input->post('countryId',TRUE);
        $StateData=$this->Country->get_state_country($countryId);
        $html='';
        $i=0;
        if(count($StateData)>0){
                $html='<div id="addCheckAll" style="font-weight:bold;font-size:12px;text-decoration: underline; cursor: pointer;padding-bottom: 5px;">Check All</div>
                        <div id="addUnCheckAll" style="display:none;font-weight:bold;font-size:12px;text-decoration: underline; cursor: pointer;padding-bottom: 5px;">UnCheck All</div>
                <div style="clear:both;"></div>';
                foreach($StateData as $k){
                        $html.='<div style="width:180px;float:left;"><input type="checkbox" name="stateId[]" value="'.$k->stateId.'" />'.$k->stateName.'</div>';
                        if($i==3){
                                $html.='<div style="clear:both;"></div>';
                                $i=0;
                        }
                        $i++;
                }
        }else{
                $html='There is no state for select country';	
        }
        echo $html;die;
    }   

    public function get_edit_state(){
            $this->load->model('Country');
            $countryId=$this->input->post('countryId',TRUE);
            $StateData=$this->Country->get_state_country($countryId);
            $html='<select id="EditstateId" name="EditstateId" class="required">';
            $html .= '<option value="">Select</soption>';
            foreach($StateData AS $k){
                    $html .='<option value="'.$k->stateId.'">'.$k->stateName.'</soption>';
            }
            $html .= '</select>';
            echo $html;die;
    }
    
    public function get_exist_geozone_state(){
        $this->load->model('Zeozone_model');
        $this->load->model("Country");
        $CountryID=$this->input->post('countryId',TRUE);
        $ZeoZoneID=$this->input->post('zeoZoneId',TRUE);
        $StateDataArr=$this->Country->get_state_country($CountryID);
        $ZeozoneStateData=$this->Zeozone_model->get_zeoone_statte_name_by_zeo_counrty($CountryID,$ZeoZoneID);
        $html='';
        $i=0;
        $html='<div id="addCheckAll" style="font-weight:bold;font-size:12px;text-decoration: underline; cursor: pointer;padding-bottom: 5px;">Check All</div>
                        <div id="addUnCheckAll" style="display:none;font-weight:bold;font-size:12px;text-decoration: underline; cursor: pointer;padding-bottom: 5px;">UnCheck All</div>
                <div style="clear:both;"></div>';
        foreach($StateDataArr AS $k){
            if($this->isStateExistsInZeoZone($ZeozoneStateData,$k->stateId)==TRUE){
                $ch='checked';
            }else{
                $ch='';
            }
            $html.='<div style="width:180px;float:left;"><input type="checkbox" name="stateId[]" value="'.$k->stateId.'" '.$ch.' />'.$k->stateName.'</div>';
            if($i==3){
                $html.='<div style="clear:both;"></div>';
                $i=0;
            }
            $i++;
        }
        echo $html;die;
    }

    public function isStateExistsInZeoZone($StateDataArr,$stateId){
        foreach($StateDataArr As $k){
            if($k->stateId==$stateId){
                    return TRUE;
            }
        }
        return FALSE;
    }
    
    public function is_user_name_exists(){
        $userName=  $this->input->post('userName',TRUE);
        if($this->User_model->check_username_exists_without_type($userName)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
    
    public function is_user_email_exists(){
        $email=  $this->input->post('email',TRUE);
        if($this->User_model->check_email_exists_without_type($email)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
    
    function is_edit_user_email_exists(){
        $email=  $this->input->post('email',TRUE);
        $userId=  $this->input->post('userId',TRUE);
        if($this->User_model->check_edit_email_exists($email,$userId)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
    
    public function check_category_name(){
        $this->load->model('Category_model');
        $categoryName=$this->input->post('categoryName',TRUE);
        if($this->Category_model->check_category_name_exists($categoryName)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
    
    public function check_edit_category_name(){
        $this->load->model('Category_model');
        $categoryName=$this->input->post('categoryName',TRUE);
        $categoryId=$this->input->post('categoryId',TRUE);
        if($this->Category_model->check_edit_category_name_exists($categoryName,$categoryId)){
                echo 1;die;
        }else{
                echo 0;die;
        }
    }
    
    function show_order_details(){
        $this->load->model('Order_model');
        $orderId=$this->input->post('orderId',TRUE);
        $data=  $this->load_default_resources();
        $order=$this->Order_model->get_single_order_by_id($orderId);
        $data['order']=$order;
        $data['orderId']=$orderId;
        $data['group'] = $this->User_model->get_group_by_id($order->groupId);
        $orderStatusobj=$this->Order_model->get_state();
        $stateArr=array();
        foreach($orderStatusobj As $k){
            $stateArr[$k->orderStateId]=$k->name;
        }
        $data['status']= $stateArr;
        //pre($data);die;
        echo json_encode(array('content'=>$this->load->view('webadmin/order_details',$data,true)));die;
    }
    
    function show_order_group_details(){
        $this->load->model('Order_model');
        $groupId=$this->input->post('groupId',TRUE);
        $data=  $this->load_default_resources();
        $groupData=$this->User_model->get_group_by_id($groupId);
        //pre($groupData);die;
        $data['group']= $groupData;
        $data['groupId']= $groupId;
        $data['groupTitle']= $groupData->groupTitle;
        //pre($data);die;
        echo json_encode(array('content'=>$this->load->view('webadmin/order_group_details',$data,true)));die;
    }
    
    function update_order_delivered(){
        $this->load->model('Logistics_model');
        $this->load->model('Order_model');
        $orderId=$this->input->post('orderId',TRUE);
        $order=$this->Order_model->get_single_order_by_id($orderId);
        if($order->orderType=='SINGLE'):
            $this->single_order_delivered_mail($order);
            $this->Order_model->update(array('status'=>6),$orderId);
        else:    
            $this->group_order_delivered_mail($order);
            $this->Order_model->update(array('status'=>6),$orderId);
        endif;
        echo json_encode(array('result'=>'good'));die;
    }
    
    function single_order_delivered_mail($order){
        $orderId=$order->orderId;
        $this->load->model('Order_model');
        $orderDetails=  $this->Order_model->details($order->orderId);
        $orderDeliveryDetails=  $this->Order_model->get_latest_delivery_details($order->orderId);
        
        //pre($orderDetails);die;
        $adminMailData=  $this->load_default_resources();
        $adminMailData['orderDetails']=$orderDetails;
        $adminMailData['orderId']=$orderId;
        $adminMailData['orderDeliveryDetails']=$orderDeliveryDetails;
        $adminMailData['orderDeliveryPhotoURL']=SiteResourcesURL.'order_delivery/75X75/';
        $orderInfoDataArr=unserialize(base64_decode($orderDetails[0]->orderInfo));
        //pre($orderInfoDataArr);die;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        $adminMailData['orderInfoDataArr']=$orderInfoDataArr;
        $sellerFullName=$orderDetails[0]->sellerFirstName.' '.$orderDetails[0]->sellerFirstName;
        $adminMailData['sellerFullName']=$sellerFullName;
        $buyerFullName=$orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->lastName;
        $adminMailData['buyerFullName']=$buyerFullName;
        
        // for buyer
        $this->_global_tidiit_mail($orderDetails[0]->buyerEmail,'Your Tidiit order TIDIIT-OD-'.$order->orderId.' has delivered successfully',$adminMailData,'single_order_delivered',$buyerFullName);
                
        /// for seller
        $this->_global_tidiit_mail($orderDetails[0]->sellerEmail, "Tidiit order TIDIIT-OD-".$order->orderId.' has delivered successfully', $adminMailData,'seller_single_order_delivered',$sellerFullName);

        /// for support
        $adminMailData['userFullName']='Tidiit Inc Support';
        $this->load->model('Siteconfig_model','siteconfig');
        //$supportEmail=$this->siteconfig->get_value_by_name('MARKETING_SUPPORT_EMAIL');
        $supportEmail='judhisahoo@gmail.com';
        $this->_global_tidiit_mail($supportEmail, "Tidiit Order TIDIIT-OD-".$order->orderId.' has delivered successfully', $adminMailData,'support_single_order_delivered','Tidiit Inc Support');
        return TRUE;
    }
}
