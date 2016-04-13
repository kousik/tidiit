<?php
class Logistics_model extends CI_Model{
    public $_table='logistics';

    function __construct() {
            parent::__construct();
    }

    public function get_all_admin(){
        return $this->db->get($this->_table)->result();
    }

    public function add($dataArr){
            $this->db->insert($this->_table,$dataArr);
            return $this->db->insert_id();
    }

    public function edit($DataArr,$logisticsId){
            $this->db->where('logisticsId',$logisticsId);
            $this->db->update($this->_table,$DataArr);
            //echo $this->db->last_query();die;
            return TRUE;		
    }

    public function change_status($logisticsId,$status){
            $this->db->where('logisticsId',$logisticsId);
            $this->db->update($this->_table,array('status'=>$status));
            return TRUE;
    }

    public function delete($logisticsId){
            $this->db->delete($this->_table, array('logisticsId' => $logisticsId)); 
            return TRUE;
    }
    
    function details($logisticsId){
        return $this->db->where('logisticsId',$logisticsId)->get($this->_table)->result_array();                
    }
    
    public function get_all_active(){
        return $this->db->get_where($this->_table,array('status'=>1))->result();
    }
}