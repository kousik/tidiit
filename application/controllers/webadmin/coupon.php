<?php
class Coupon extends MY_Controller{
    public function __construct(){
            parent::__construct();
            $this->load->model('Coupon_model');
            $this->load->model('Category_model');
        $this->db->cache_off();
    }

    public function index(){
            redirect(base_url().'webadmin');
    }

    public function viewlist(){
        $this->load->model('User_model');
            $data=$this->_show_admin_logedin_layout();
            $TopcategoryData=$this->Category_model->get_top_category_for_product_list();
            $AllButtomcategoryData=$this->Category_model->buttom_category_for_product_list();
            foreach($TopcategoryData as $k){
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
            //pre($menuArr);die;
            $data['categoryData']=$menuArr;
            $userArr=$this->User_model->get_all_buyer();
            //pre($userArr);die;
            $data['UserDataArr']=$userArr;
            $data['DataArr']=$this->Coupon_model->get_all_admin();
            $this->load->view('webadmin/coupon_list',$data);
    }

    public function add(){
        $code=$this->input->post('code',TRUE);
        $type=$this->input->post('type',TRUE);
        $amount=$this->input->post('amount',TRUE);

        $startDateArr=  explode('-',$this->input->post('startDate',TRUE));
        $startDate=$startDateArr[2].'-'.$startDateArr[1].'-'.$startDateArr[0];

        $endDateArr=  explode('-',$this->input->post('endDate',TRUE));
        $endDate=$endDateArr[2].'-'.$endDateArr[1].'-'.$endDateArr[0];

        $category=$this->input->post('category',TRUE);
        $userId=$this->input->post('userId',TRUE);
        $userUsesType=$this->input->post('userUsesType',TRUE);
        $status=$this->input->post('status',TRUE);

        $dataArr=array(
        'code'=>$code,
        'type'=>$type,
        'amount'=>$amount,
        'startDate'=>$startDate,
        'endDate'=>$endDate,
        'status'=>$status
        );

        if($userId!=""){
            $dataArr['userUsesType']=1;
            $dataArr['usesType']=$userUsesType;
        }else{
            $dataArr['userUsesType']=2;
            $dataArr['usesType']=2;
        }

        //pre($dataArr);die;

        $couponId=$this->Coupon_model->add($dataArr);

        /*$categoryDataArr=$this->Category_model->get_all_parrent_details($category);
        $AddcategoryDataArr=array('couponId'=>$couponId,'categoryId'=>$category);
        //pre($AddcategoryDataArr);die;
        $this->Coupon_model->add_category($AddcategoryDataArr);*/

        if($userId!=""){
            $this->Coupon_model->add_user(array('couponId'=>$couponId,'userId'=>$userId,'userUsesType'=>$userUsesType));
        }

        $this->session->set_flashdata('Message','Coupon added successfully.');
        redirect(base_url().'webadmin/coupon/viewlist');
    }

    public function edit(){
            $code=$this->input->post('Editcode',TRUE);
            $type=$this->input->post('Edittype',TRUE);
            $status=$this->input->post('Editstatus',TRUE);

            $amount=$this->input->post('Editamount',TRUE);

            $startDateArr=  explode('-',$this->input->post('EditstartDate',TRUE));
            $startDate=$startDateArr[2].'-'.$startDateArr[1].'-'.$startDateArr[0];

            $endDateArr=  explode('-',$this->input->post('EditendDate',TRUE));
            $endDate=$endDateArr[2].'-'.$endDateArr[1].'-'.$endDateArr[0];

            $category=$this->input->post('Editcategory',TRUE);

            $couponId=$this->input->post('couponId',TRUE);


            $dataArr=array(
                'type'=>$type,
                'amount'=>$amount,
                'startDate'=>$startDate,
                'endDate'=>$endDate,
                'status'=>$status
                );
            //print_r($dataArr);die;
            $this->Coupon_model->edit($dataArr,$couponId);

            $categoryDataArr=$this->Category_model->get_all_parrent_details($category);
            $AddcategoryDataArr=array('categoryId'=>$category);
            $this->Coupon_model->edit_category($AddcategoryDataArr,$couponId);


            $this->Coupon_model->edit_category($AddcategoryDataArr,$couponId);

            $this->session->set_flashdata('Message','Coupon updated successfully.');
            redirect(base_url().'webadmin/coupon/viewlist');
    }

    public function change_status($couponId,$Action){
            $this->Coupon_model->change_status($couponId,$Action);

            $this->session->set_flashdata('Message','Coupon status updated successfully.');
            redirect(base_url().'webadmin/coupon/viewlist');
    }

    public function delete($couponId){
            $this->Coupon_model->delete($couponId);

            $this->session->set_flashdata('Message','Coupon deleted successfully.');
            redirect(base_url().'webadmin/coupon/viewlist');
    }

    public function add_user(){
        $userId=$this->input->post('addUserId',TRUE);
        $userUsesType=$this->input->post('userUsesType',TRUE);
        $couponId=$this->input->post('couponCodeId',TRUE);

        $this->Coupon_model->add_user(array('couponId'=>$couponId,'userId'=>$userId,'userUsesType'=>$userUsesType));
        $this->session->set_flashdata('Message','User added to coupon successfully.');
        redirect(base_url().'webadmin/coupon/viewlist');
    }       
}