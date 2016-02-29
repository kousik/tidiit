<?php
class Faq_model extends CI_Model{
    public $_table='faq';
    public $_topics='faq_topics';

    function __construct() {
        parent::__construct();
    }

    public function get_all_admin(){
        return $this->db->select('f.*,ft.faqTopics')->from($this->_table.' f')->join($this->_topics.' ft','f.faqTopicsId=ft.faqTopicsId')->get()->result();
    }

    function get_details($faqId){
        return $this->db->select('f.*,ft.faqTopics')->from($this->_table.' f')->join($this->_topics.' ft','f.faqTopicsId=ft.faqTopicsId')->where('f.faqId',$faqId)->get()->result();
    }
    

    public function add($dataArr){
            $this->db->insert($this->_table,$dataArr);
            return $this->db->insert_id();
    }

    public function edit($DataArr,$faqId){
            $this->db->where('faqId',$faqId);
            $this->db->update($this->_table,$DataArr);
            //echo $this->db->last_query();die;
            return TRUE;		
    }

    public function change_status($faqId,$status){
            $this->db->where('faqId',$faqId);
            $this->db->update($this->_table,array('status'=>$status));
            return TRUE;
    }

    public function delete($faqId){
            $this->db->delete($this->_table, array('faqId' => $faqId)); 
            return TRUE;
    }

    public function get_all($type){
            $this->db->select('*')->from($this->_table)->where('status',1)->where('type',$type);
            return $this->db->get()->result();
    }
        
    function get_all_admin_topics(){
        return $this->db->get($this->_topics)->result();
    }    
    
    function get_all_active_topic(){
        return $this->db->get_where($this->_topics,array('status'=>1))->result();
    }
    
    public function add_topics($dataArr){
        $this->db->insert($this->_topics,$dataArr);
        return $this->db->insert_id();
    }
    
    public function edit_topics($DataArr,$faqTopicsId){
            $this->db->where('faqTopicsId',$faqTopicsId);
            $this->db->update($this->_topics,$DataArr);
            //echo $this->db->last_query();die;
            return TRUE;		
    }

    public function change_status_topics($faqTopicsId,$status){
        $this->db->where('faqTopicsId',$faqTopicsId);
        $this->db->update($this->_topics,array('status'=>$status));
        return TRUE;
    }

    public function delete_topics($faqTopicsId){
        $this->db->delete($this->_topics, array('faqTopicsId' => $faqTopicsId)); 
        return TRUE;
    }
    
    function check_faq_topics_exist($faqTopics,$faqTopicsType){
        $rs=$this->db->from($this->_topics)->where('faqTopics',$faqTopics)->where('faqTopicsType',$faqTopicsType)->get()->result();
        if(count($rs)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    function get_topics_by_type($type){
        return $this->db->get_where($this->_topics,array('faqTopicsType'=>$type))->result();
    }
}