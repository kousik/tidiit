<?php
class Shipping_model extends CI_Model{
	private $_table='shipping';
	private $_sd='shipping_address';
	private $_temp='user_temp_cart_shipping';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_all_admin(){
		$this->db->select('*')->from($this->_table);
		return $this->db->get()->result();
	}
	
	public function edit($DataArr,$ShippingID){
		$this->db->where('ShippingID',$ShippingID);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function get_shipping(){
		$IsFreeShipping=$this->session->userdata("IsShipping");
		if($IsFreeShipping>0){
			return $this->db->select("*")->from($this->_table)->where('ShippingID >','1')->get()->result();
		}else{
			return $this->db->select("*")->from($this->_table)->get()->result();
		}		
	}
	
	public function details($id){
		$data=$this->db->select('*')->from('shipping')->where('ShippingID',$id)->get()->result();
		return $data;
	}
	
	public function add_temp($dataArr){
		$this->db->insert($this->_temp,$dataArr);
		return $this->db->insert_id();		
	}
	
	public function edit_temp($dataArr,$UserID){
		$this->db->where('UserID',$UserID);
		$this->db->where('SessionID',$this->session->userdata('USER_SITE_SESSION_ID'));
		$this->db->update($this->_temp,$dataArr);
		return TRUE;
	}
        
        public function delete_temp($SessionID){
            
        }


        public function get_current_shipping_info(){
		$sql="SELECT utcs.ShippingID,utcs.ShippingCharges,utcs.ShippingAddresssID,utcs.ShippToName,"
                        . " sh.* FROM user_temp_cart_shipping AS utcs JOIN shipping_address AS sh ON(utcs.ShippingAddresssID=sh.ShippingAddressID) "
                        . " WHERE utcs.UserID='".$this->session->userdata('FE_SESSION_VAR')."' AND "
                        . " utcs.SessionID='".$this->session->userdata('USER_SITE_SESSION_ID')."' ORDER BY utcs.UserTempCartShippingID DESC LIMIT 0,1";
                ///echo $sql;die;
                $data=$this->db->query($sql)->result();
		return $data;
	}
	
	public function datails_ajax($id){
		$sql="SELECT sh.*,s.StateName,c.CountryName,u.FirstName,u.LastName FROM shipping_address AS sh JOIN state AS s ON(sh.StateID=s.StateID) JOIN country AS c ON(sh.CountryID=c.CountryID) JOIN user AS u ON(sh.UserID=u.UserID)  WHERE sh.ShippingAddressID='".$id."'";
		//die($sql);
		return $this->db->query($sql)->result();
		
	}
	
	public function remove_temp_shipping(){
		$this->db->where_in('SessionID',$this->session->userdata('USER_SITE_SESSION_ID'));
		$this->db->delete($this->_temp); 		
	}
	
	public function remove_all_from_temp_cart(){
		$this->db->where_in('SessionID',$this->_SiteSession);
		$this->db->delete($this->_temp); 		
	}
	
	public function check_exist_session(){
		$CSession=$this->session->userdata('USER_SITE_SESSION_ID');
		$dataArr=$this->db->select('*')->from($this->_temp)->where('SessionID',$CSession)->get()->result();
		if(count($dataArr)>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function edit_shipping_info($DataArr,$UserID){
		$CSession=$this->session->userdata('USER_SITE_SESSION_ID');
		$TmpDataArr=$this->db->select('ShippingAddresssID')->from($this->_temp)->where('SessionID',$CSession)->get()->result();
		//print_r($TmpDataArr);die;
		$ShippingAddresssID=$TmpDataArr[0]->ShippingAddresssID;
		$this->db->where('ShippingAddressID',$ShippingAddresssID);
		$this->db->update($this->_sd,$DataArr);
		return TRUE;
	}
	
	public function get_current_address_id(){
		$CSession=$this->session->userdata('USER_SITE_SESSION_ID');
		$DataArr=$this->db->select('ShippingAddresssID')->from($this->_temp)->where('SessionID',$CSession)->get()->result();
		//echo $this->db->last_query();die;
		return $DataArr[0]->ShippingAddresssID;
	}
}
?>