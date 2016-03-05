<?php
class Help_model extends CI_Model{
	public $_table='help';
	public $_topics='help_topics';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_all_admin(){
            return $this->db->select('h.*,ht.helpTopics')->from($this->_table.' h')->join($this->_topics.' ht','h.helpTopicsId=ht.helpTopicsId')->get()->result();
	}
	
	public function get_answer($question){
            return $this->db->from($this->_table)->like('question',$question)->get()->result();
	}
        
        function get_details($helpId){
            return $this->db->select('h.*,ht.helpTopics')->from($this->_table.' h')->join($this->_topics.' ht','h.helpTopicsId=ht.helpTopicsId')->where('h.helpId',$helpId)->get()->result();
        }
        
        function get_topic_details_by_id($helpTopicsId,$app=FALSE){
            if($app==TRUE){
                return $this->db->get_where($this->_table,array('helpTopicsId'=>$helpTopicsId))->result_array();
            }else{
                return $this->db->get_where($this->_table,array('helpTopicsId'=>$helpTopicsId))->result();
            }
        }
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit($DataArr,$helpId){
		$this->db->where('helpId',$helpId);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function change_status($helpId,$status){
		$this->db->where('helpId',$helpId);
		$this->db->update($this->_table,array('status'=>$status));
		return TRUE;
	}
	
	public function delete($helpId){
		$this->db->delete($this->_table, array('helpId' => $helpId)); 
		return TRUE;
	}
	
	public function get_all($type){
            return $this->db->get_where($this->_table,array('status'=>1,'type'=>$type))->result();
	}
        
    function get_all_admin_help_topics(){
        return $this->db->get($this->_topics)->result();
    }
    
    function get_all_active_topic($app=FALSE){
        if($app==TRUE){
            return $this->db->get_where($this->_topics,array('status'=>1))->result_array();
        }else{
            return $this->db->get_where($this->_topics,array('status'=>1))->result();
        }
    }
    
    function get_top_help(){
        return $this->db->from($this->_table)->limit(1)->get()->result();
    }
    
    public function add_topics($dataArr){
        $this->db->insert($this->_topics,$dataArr);
        return $this->db->insert_id();
    }
    
    public function edit_topics($DataArr,$helpTopicsId){
            $this->db->where('helpTopicsId',$helpTopicsId);
            $this->db->update($this->_topics,$DataArr);
            //echo $this->db->last_query();die;
            return TRUE;		
    }

    public function change_status_topics($helpTopicsId,$status){
        $this->db->where('helpTopicsId',$helpTopicsId);
        $this->db->update($this->_topics,array('status'=>$status));
        return TRUE;
    }

    public function delete_topics($helpTopicsId){
        $this->db->delete($this->_topics, array('helpTopicsId' => $helpTopicsId)); 
        return TRUE;
    }
    
    function check_help_topics_exist($helpTopics){
        $rs=$this->db->get_where($this->_topics,array('helpTopics'=>$helpTopics))->result();
        if(count($rs)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    function get_topics_details($topicsId){
        return $this->db->get_where($this->_topics,array('helpTopicsId'=>$topicsId))->result();
    }
}