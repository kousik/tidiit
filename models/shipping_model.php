<?php
class Shipping_model extends CI_Model{
    private $_table='shipping';

    function __construct() {
         parent::__construct();
    }

    public function get_all_admin(){
            $this->db->select('*')->from($this->_table);
            return $this->db->get()->result();
    }
    
    function add($dataArr){
        $this->db->insert($this->_table,$dataArr);
	return $this->db->insert_id();
    }

    public function edit($DataArr,$shippingId){
        $this->db->where('shippingId',$shippingId);
        $this->db->update($this->_table,$DataArr);
        //echo $this->db->last_query();die;
        return TRUE;		
    }

    public function details($id){
        return $this->db->get_where($this->_table,array('shippingId'=>$id))->result();
    }
    
    public function change_status($shippingId,$status){
        $this->db->where('shippingId',$shippingId);
        $this->db->update($this->_table,array('status'=>$status));
        return TRUE;
    }

    public function delete($shippingId){
        $this->db->delete($this->_table, array('shippingId' => $shippingId)); 
        return TRUE;
    }
    
    function check_startweight_by_country($startWeight,$countryCode,$shippingId){
        if($shippingId==""):
            $rs=$this->db->from($this->_table)->where('startWeight',$startWeight)->where('countryCode',$countryCode)->get()->result();
        else:
            $rs=$this->db->from($this->_table)->where('startWeight',$startWeight)->where('countryCode',$countryCode)->where('shippingId !=',$shippingId)->get()->result();
        endif;
        
        //echo $this->db->last_query();
        return $rs;
    }
}
