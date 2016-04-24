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
            $lowestPricceUpdate=0;
            foreach ($productPriceDetails As $k => $v){
                $bulkQty=$v->qty;
                $weight=$productDetails[0]->weight;
                $shippingPrice=$this->calculate_shiiping_price($bulkQty,$weight);
                $shippingCharges=round($bulkQty*$weight)*$shippingPrice;
                $tidiitCommissions=$v->price*$tidiitCommissionsPer/100;
                $fPrice=$v->price+$shippingCharges+$tidiitCommissions;
                $dataArray=array('price'=>$fPrice,'shippingCharges'=>$shippingCharges,'tidiitCommissions'=>$tidiitCommissions);
                $this->db->where('productPriceId',$v->productPriceId);
                $this->db->update('product_price',$dataArray);
                if($lowestPricceUpdate==0){
                    $this->db->where('productId',$productId);
                    $this->db->update('product',array('lowestPrice'=>$fPrice));
                    $lowestPricceUpdate=1;
                }
            }
            $this->db->where('productId',$productId);
            $this->db->update('product',array('heighestPrice'=>$fPrice));
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
    
    function recursive_uptdate_product_price(){
        $rs=$this->db->get('product')->result();
        foreach ($rs as $k=>$v){
            $productId=$v->productId;
            $rsTemp=  $this->db->get_where('product_price',array('productId'=>$productId))->result();
            $priceOne=$rsTemp[0]->price;
            $last=count($rsTemp)-1;
            $priceTwo=$rsTemp[$last]->price;
            if($priceOne>$priceTwo){
                $this->db->where('productId',$productId);
                $this->db->update('product',array('lowestPrice'=>$priceTwo));
                
                $this->db->where('productId',$productId);
                $this->db->update('product',array('heighestPrice'=>$priceOne));
            }else{
                $this->db->where('productId',$productId);
                $this->db->update('product',array('lowestPrice'=>$priceOne));
                $this->db->where('productId',$productId);
                $this->db->update('product',array('heighestPrice'=>$priceTwo));
            }
            
        }
    }
}