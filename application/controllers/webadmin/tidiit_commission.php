<?php
class Tidiit_commission extends MY_Controller{
    public function __construct(){
            parent::__construct();
    }

    public function index(){
        redirect(base_url().'webadmin');
    }

    public function viewlist(){
        $data=$this->_show_admin_logedin_layout();
        $rs=$this->User_model->get_tidiit_commission_list();
        //pre($rs);die;
        $data['DataArr']=$rs;
        $this->load->view('webadmin/tidiit_commission_list',$data);
    }
    
    function edit(){
        $commissionPercentage=  trim($this->input->post('commissionPercentage',TRUE));
        $commissionId=  $this->input->post('commissionId',TRUE);
        if($commissionPercentage!=""){
            $this->User_model->edit_tidiit_commission(array('commissionPercentage'=>$commissionPercentage),$commissionId);
            $commissionDetails=  $this->User_model->get_tidiit_details($commissionId);
            $productId=$commissionDetails[0]->productId;
            
            $productPriceDetails=$this->db->get_where('product_price',array('productId'=>$productId))->result();
            $productDetails=$this->db->get_where('product',array('productId'=>$productId))->result();
            $tidiitCommissionsPer=  $this->get_tidiit_commission($productId);
            foreach ($productPriceDetails As $k => $v){
                $bulkQty=$v->qty;
                $weight=$productDetails[0]->weight;
                $shippingPrice=$this->calculate_shiiping_price($bulkQty,$weight);
                $tidiitCommissions=$v->price*$tidiitCommissionsPer/100;
                $fPrice=$v->price+($bulkQty*$shippingPrice)+$tidiitCommissions;
                $dataArray=array('price'=>$fPrice,'shippingCharges'=>($bulkQty*$shippingPrice),'tidiitCommissions'=>$tidiitCommissions);
                $this->db->where('productPriceId',$v->productPriceId);
                $this->db->update('product_price',$dataArray);        
            }
            $this->session->set_flashdata('Message','Tidiit commission updated successfully.');
        }else{
            $this->session->set_flashdata('Message','Please enter the commission percentage.');
        }
        redirect(base_url().'webadmin/tidiit_commission/viewlist/');
    }
    
    function calculate_shiiping_price($bulkQty,$weight){
        $weightF=round($bulkQty*$weight);
        $sql="SELECT `charges` FROM `logistic_weight_based_charges` WHERE $weightF BETWEEN `min` AND `max` LIMIT 1";
        //echo $sql.'<br>';
        $shippingchargesArr=$this->db->query($sql)->result();
        //pre($shippingchargesArr);
        return $shippingchargesArr[0]->charges;
    }
    
    function get_tidiit_commission($productId){
        $rs=$this->db->get_where('tidiit_commission',array('productId'=>$productId))->result(); return $rs[0]->commissionPercentage;
    }
    
    function is_tidiit_commission_updated($productId){
        $rs=$this->db->get_where('tidiit_comission',array('productId'=>$productId))->result(); 
        if(count($rs)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}