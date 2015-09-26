<?php
class Cart_model extends CI_Model {
	private $_table='cart';
	private $_temp='temp_cart';
	private $_temp_shipping='user_temp_cart_shipping';
	private $_SiteSession="";
	
	function __construct() {
		parent::__construct();
		$this->_SiteSession=$this->session->userdata('USER_SITE_SESSION_ID');
	}
	
	public function get_all_temp_item(){
		$sql="DELETE FROM temp_cart WHERE TIMESTAMPDIFF(HOUR,AddDate,NOW())>2";
		$this->db->query($sql);
		
		$SessionID=$this->_SiteSession;
		//die($SessionID);
		$sql="SELECT tc.*,c.CategoryName FROM temp_cart AS tc JOIN product AS p ON(tc.ProductID=p.ProductID) JOIN category AS c ON(p.CategoryID=c.CategoryID) WHERE tc.SessionID='".$this->_SiteSession."'";
		return $this->db->query($sql)->result();
	}
	
	public function addTemp($dataArr){
		$sql="DELETE FROM temp_cart WHERE TIMESTAMPDIFF(HOUR,AddDate,NOW())>2";
		$this->db->query($sql);
		//echo $this->db->last_query();die;
		
		$this->db->insert($this->_temp,$dataArr);
		return $this->db->insert_id();
	}
	
	public function getTotalItem(){
            $this->db->cache_off();
		$SessionID=$this->_SiteSession;
                $this->db->select_sum('Qty');
		$rs=$this->db->from($this->_temp)->where('SessionID',$SessionID)->where('IP',$this->input->ip_address())->get()->result();
                //echo '<pre>';print_r($rs);die;
                //echo $rs[0]->Qty;die;
                return $rs[0]->Qty;
		//return $this->db->count_all_results($this->_temp);
                
	}
	
	public function isProductExistsInTempCart($ProductID){
		$SessionID=$this->_SiteSession;
		//echo $SessionID;die('zzzzzzzzzzz');
		$this->db->where('SessionID',$SessionID)->where('ProductID',$ProductID);
		$No=$this->db->count_all_results($this->_temp);
		if($No>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function update_temp_cart($ProductID,$Qty){
		$sql="DELETE FROM temp_cart WHERE TIMESTAMPDIFF(HOUR,AddDate,NOW())>2";
		$this->db->query($sql);
		//echo '<br> at update cart function <br>';
		$SessionID=$this->_SiteSession;
		//$TempCartProductDetails=$this->db->select('*')->from($this->_temp)->where('SessionID',$SessionID)->where('ProductID',$ProductID)->get()->result();
		//$NewQty=$TempCartProductDetails[0]->Qty+$Qty;
                $NewQty=$Qty;
		$this->db->where('SessionID',$SessionID)->where('ProductID',$ProductID)->update($this->_temp,array('Qty'=>$NewQty));
		//echo $this->db->last_query();die;
	}
	
	public function remove($ProductID){
		$sql="DELETE FROM temp_cart WHERE TIMESTAMPDIFF(HOUR,AddDate,NOW())>2";
		$this->db->query($sql);
		//echo $this->db->last_query();die;
		$SessionID=$this->_SiteSession;
		$Condition=array('ProductID' => $ProductID,'SessionID'=>$SessionID);
		$this->db->delete($this->_temp, $Condition); 
		$TotalItem=$this->getTotalItem();
                if($TotalItem==0){
                    $this->session->unset_userdata('CATEGORY_SHIPING_COUNTRY');
                }
		$this->session->set_userdata('TotalItemInCart',$TotalItem);
		if($TotalItem==0){
			$this->session->unset_userdata('IsShipping');
		}
		return TRUE;
	}
	
	public function add(){
		$this->db->insert($this->_temp,$dataArr);
		return $this->db->insert_id();	
	}
	
	public function add_cart($dataArr){
		$this->db->insert_batch($this->_table,$dataArr);
		//echo $this->db->last_query();die;
		return $this->db->insert_id();	
	}
	
	public function remove_all_from_temp_cart(){
		$this->db->where_in('SessionID',$this->_SiteSession);
		$this->db->delete($this->_temp);
		
		
		$this->db->where_in('SessionID',$this->_SiteSession);
		$this->db->delete($this->_temp_shipping);
		$this->session->unset_userdata('ShippingSelected');
		$this->session->unset_userdata('ShippingID');
		$this->session->unset_userdata('TotalItemInCart');
		$this->session->unset_userdata('CATEGORY_SHIPING_COUNTRY');
                return true;
	}
	
	public function update_cart_after_order($OrderID){
		$this->db->where('OrderID',$OrderID);
		$this->db->update($this->_table,array('Status'=>1));
	}	
	
	public function get_shipping_state_arr_by_country_name($CountryName){
            if($CountryName==""){
                return array();
            }else{
                $rs=$this->db->query("SELECT CountryID FROM country WHERE CountryName LIKE '%".$CountryName."%'")->result();
                if(count($rs)>1){
                    $rs1=$this->db->query("SELECT CountryID FROM country WHERE CountryName='".strtoupper($CountryName)."'")->result();
                    //print_r($rs1);die;
                    $sql="SELECT s.* FROM state AS s WHERE s.CountryID=".$rs1[0]->CountryID;
                    //echo $sql;die;
                    return $this->db->query($sql)->result();
                }else{
                    $sql="SELECT s.* FROM state AS s WHERE s.CountryID=(SELECT CountryID FROM country WHERE CountryName LIKE '%".$CountryName."%')";
                    //echo $sql;die;
                    return $this->db->query($sql)->result();
                }
            }
        }
	
}