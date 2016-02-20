<?php
class Help_model extends CI_Model{
	public $_table='help';
	public $_topics='help_topics';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_all_admin(){
		$this->db->select('*')->from($this->_table)->where('status <',2);
		return $this->db->get()->result();
	}
	
	public function get_answer($question){
		$this->db->select('*')->from($this->_table)->like('question',$question);
		return $this->db->get()->result();
	}
        
        function get_details($faqId){
            return $this->db->from($this->_table)->where('faqId',$faqId)->get()->result();
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
        
    function get_all_admin_help_topics(){
        return $this->db->get($this->_topics)->result();
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
    
    function check_help_topics_exist($helpTopics){
        $rs=$this->db->from($this->_topics)->where('helpTopics',$helpTopics)->get()->result();
        if(count($rs)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}