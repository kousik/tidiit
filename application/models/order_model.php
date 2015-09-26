<?php
class Order_model extends CI_Model {
	public $_table='order';
	public $_billing='order_details';
	public $_paypal='paypal_data';
	public $_ccavenue='ccavenue_data';
	public $_cart='cart';
	public $_payment='payment';
	private $_state='order_state';
	private $_shipped_history='shipped_history';
	private $_history='order_history';
	private $_product='product';
	
	
	public $result=NULL;
	function __construct() {
		parent::__construct();
	}
	
	public function add($dataArray){
		$this->db->insert($this->_table,$dataArray);
		return $this->db->insert_id();
	}
	
	public function add_paypal_data($dataArray){
		$this->db->insert($this->_paypal,$dataArray);
		return $this->db->insert_id();
	}
	
	public function add_payment($dataArray){
		$this->db->insert($this->_payment,$dataArray);
		return $this->db->insert_id();
	}
	
	public function update_status($Status,$OrderID){
		$this->db->where('OrderID',$OrderID);
		$this->db->update($this->_table,array('Status'=>1));
		return TRUE;
	}
	
	
	public function details($OrderID){
		$sql="SELECT 
		o.*,u.Email,u.FirstName,u.LastName, 
		b.City,b.Address,b.Zip,b.ContactNo,c.CountryName,s.StateName,s.StateCode,os.Name AS OrderStateName, 
		sad.City AS ShippingCity,sad.Address AS ShippingAddress,sad.Zip AS ShippingZip, 
		sad.ContactNo AS ShippingContactNo,ss.StateName AS ShippingStateName,ss.StateCode AS ShippingStateCode,sc.CountryName AS ShippingCountryName, 
		p.PaymentType,sh.Title 
		FROM `order` AS o JOIN `user` AS u ON(o.UserID=u.UserID) 
		JOIN `billing_address` AS b ON(o.UserID=b.UserID) 
		JOIN `country` AS c ON(b.CountryID=c.CountryID) 
		JOIN `state` AS s ON(b.StateID=s.StateID) 
		JOIN order_state AS os ON(o.OrderStateID=os.OrderStateID) 
		JOIN `shipping_address` AS sad ON(o.ShippingAddresssID=sad.ShippingAddressID) 
		JOIN state AS ss ON(sad.StateID=ss.StateID) 
		JOIN country AS sc ON(sad.CountryID=sc.CountryID) 
		JOIN payment AS p ON(o.OrderID=p.OrderID) 
		JOIN shipping AS sh ON(o.ShippingID=sh.ShippingID)WHERE o.OrderID='".$OrderID."'";
		$rs=$this->db->query($sql)->result();
		//echo $this->db->last_query();
		return $rs;
	}
	
	public function cc_details($OrderID){
		$sql="SELECT o.*,u.Email,u.FirstName,u.LastName,b.City,b.Address,b.Zip,b.ContactNo,c.CountryName,s.StateName,s.StateCode,"
                        . " os.Name AS OrderStateName,sad.City AS ShippingCity,sad.Address AS ShippingAddress,sad.Zip AS ShippingZip,"
                        . " sad.ContactNo AS ShippingContactNo,ss.StateName AS ShippingStateName,sc.CountryName AS ShippingCountryName "
                        . " FROM `order` AS o JOIN `user` AS u ON(o.UserID=u.UserID) JOIN `billing_address` AS b ON(o.UserID=b.UserID) "
                        . " JOIN `country` AS c ON(b.CountryID=c.CountryID) JOIN `state` AS s ON(b.StateID=s.StateID) "
                        . " JOIN order_state AS os ON(o.OrderStateID=os.OrderStateID) "
                        . " JOIN `shipping_address` AS sad ON(o.ShippingAddresssID=sad.ShippingAddressID) "
                        . " JOIN state AS ss ON(sad.StateID=ss.StateID) JOIN country AS sc ON(sad.CountryID=sc.CountryID) "
                        . " WHERE o.OrderID='".$OrderID."'";
		$rs=$this->db->query($sql)->result();
		//echo $this->db->last_query();
		return $rs;
	}
	
	public function remove_order($OrderID){
		$this->db->where_in('OrderID',$OrderID);
		$this->db->delete($this->_table);
		
		$this->db->where_in('OrderID',$OrderID);
		$this->db->delete($this->_cart);		
		
		return TRUE;
	}
	
	public function admin_list($per_page,$offcet=0){
            $FromDate=$this->input->post('HiddenFilterFromDate',TRUE);
            $ToDate=$this->input->post('HiddenFilterToDate',TRUE);
            $UserName=$this->input->post('HiddenFilterUserName',TRUE);
            $OrderStatus=$this->input->post('HiddenFilterOrderStatus',TRUE);
		$sql='SELECT p.PaymentType,o.*,os.Name AS OrderStateName,u.FirstName,u.LastName,u.Email,pp.PayPalTransactionID,'
                        . ' ccp.TransactionID,oh.ActionDate FROM `payment` AS p JOIN `order` AS o ON(p.OrderID=o.OrderID) '
                        . ' JOIN `user` AS u ON(o.UserID=u.UserID) JOIN `order_state` AS os ON(o.OrderStateID=os.OrderStateID) '
                        . ' LEFT JOIN `paypal_data` AS pp ON(p.PaypalID=pp.PayPalDataID) '
                        . ' LEFT JOIN `ccavenue_data` AS ccp ON(p.CcAvenueID=ccp.CcAvenueDataID) '
                        . ' LEFT JOIN order_history AS oh ON(o.OrderID=oh.OrderID AND os.OrderStateID=oh.State) WHERE o.Status="1" ';
                
                if($UserName!=""){
                    $sql .= " AND u.UserName='".$UserName."'";
                }
                
                if($OrderStatus!=""){
                    $sql .= " AND o.OrderStateID='".$OrderStatus."'";
                }
                
                if($FromDate!="" && $ToDate!=""){
                    $sql .= " AND o.OrderDate BETWEEN '".$FromDate."' AND '".$ToDate."' ";
                }
                
                $sql .= 'ORDER BY o.OrderID DESC';
		//if($offcet>0 && $per_page>0){
                    $sql.=" LIMIT $offcet,$per_page";
                //}
		$arr=$this->db->query($sql)->result();
                //echo $this->db->last_query(); //die;
                return $arr;
	}
        
        public function admin_uncomplete_list($per_page,$offcet=0){
		$sql='SELECT o.*,u.FirstName,u.LastName,u.Email,u.Phone FROM `order` AS o JOIN `user` AS u ON(o.UserID=u.UserID) WHERE o.Status="0" ORDER BY o.OrderID DESC';
		//if($offcet>0 && $per_page>0){
                    $sql.=" LIMIT $offcet,$per_page";
                //}
		$arr=$this->db->query($sql)->result();
                //echo $this->db->last_query();die;
                return $arr;
	}
        
        
        public function get_admin_total(){
            $sql='SELECT p.PaymentType,o.*,os.Name AS OrderStateName,u.FirstName,u.LastName,u.Email,pp.PayPalTransactionID,ccp.TransactionID '
                    . ' FROM `payment` AS p JOIN `order` AS o ON(p.OrderID=o.OrderID) JOIN `user` AS u ON(o.UserID=u.UserID) '
                    . ' JOIN `order_state` AS os ON(o.OrderStateID=os.OrderStateID) LEFT JOIN `paypal_data` AS pp ON(p.PaypalID=pp.PayPalDataID) '
                    . ' LEFT JOIN `ccavenue_data` AS ccp ON(p.CcAvenueID=ccp.CcAvenueDataID) WHERE o.Status="1" ORDER BY o.OrderID DESC';
		return count($this->db->query($sql)->result());
        }
        
        
        public function get_admin_uncomplete_total(){
            $sql='SELECT o.OrderID FROM `order` AS o JOIN `user` AS u ON(o.UserID=u.UserID) WHERE o.Status="0" ORDER BY o.OrderID DESC';
		return count($this->db->query($sql)->result());
        }
        
	
	public function get_state(){
		return $this->db->select('*')->from($this->_state)->where('OrderStateID <','6')->order_by('OrderStateID')->get()->result();
	}
	
	public function change_state($OrderID,$OrderStateID){
		$this->db->where('OrderID',$OrderID);
		$this->db->update($this->_table,array('OrderStateID'=>$OrderStateID));
		return TRUE;
	}
	
	public function get_paypal_transaction_id($OrderID){
		$sql="SELECT pd.PayPalTransactionID,cd.TransactionID FROM `order` AS o JOIN payment AS p ON(o.OrderID=p.OrderID) "
                        . " LEFT JOIN  paypal_data AS pd ON(p.PaypalID=pd.PayPalDataID) "
                        . " LEFT JOIN ccavenue_data AS cd ON(p.CcAvenueID=cd.CcAvenueDataID) WHERE o.OrderID='".$OrderID."'";
		$dataArr=$this->db->query($sql)->result();
		if($dataArr[0]->PayPalTransactionID==""){
			return $dataArr[0]->TransactionID;
		}else{
			return $dataArr[0]->PayPalTransactionID;
		}
	}
	
	public function get_cart_details_by_order($OrderID){
		return $this->db->select('*')->from($this->_cart)->where('OrderID',$OrderID)->get()->result();
	}
	
	public function add_ccavenue_data($dataArray){
		$this->db->insert($this->_ccavenue,$dataArray);
		return $this->db->insert_id();		
	}
	
	public function get_shipping_address_by_order_id($OrderID){
		$sql="SELECT sd.*,c.CountryName,s.StateName,u.FirstName,u.LastName,u.Email,o.ShippToName,o.UserPreferredDeliveryDate,"
                        . " o.UserSpecialOccasion,o.SpecialInstruction,o.UserNote FROM `order` AS o JOIN user AS u ON(o.UserID=u.UserID) "
                        . " JOIN shipping_address AS sd ON(o.ShippingAddresssID=sd.ShippingAddressID) "
                        . " JOIN country AS c ON(sd.CountryID=c.CountryID) JOIN state AS s ON(sd.StateID=s.StateID) WHERE o.OrderID='".$OrderID."'";
		return $this->db->query($sql)->result();
	}
	
	public function add_shipped_history($DataArr){
		$this->db->insert_batch($this->_shipped_history,$DataArr);
		return $this->db->insert_id();
	}
        
        public function add_history($DataArr){
		$this->db->insert_batch($this->_history,$DataArr);
		return $this->db->insert_id();
	}
	
	public function update_inventory($OrderID){
		$sql="SELECT ProductID,Qty FROM `cart` WHERE OrderID='".$OrderID."'";
		$dataArr=$this->db->query($sql)->result();
		foreach($dataArr AS $k){
			$this->db->where('ProductID',$k->ProductID);
			$this->db->update($this->_product,array('Quantity'=>$k->Qty));
		}
		return TRUE;
	}
        
        public function get_track_url($OrderID){
            return $this->db->select('*')->from($this->_shipped_history)->where('OrderID',$OrderID)->get()->result();
        }
        
        public function cancel($OrderID){
            $this->db->where('OrderID',$OrderID);
            $this->db->update($this->_table,array('OrderStateID'=>7));
            //echo $this->db->last_query();die;
            return TRUE;
        }
        
        public function get_shipping_country_name_id($OrderID){
            $sql="SELECT c.CountryName,c.CountryID FROM `order` AS o JOIN `country` AS c ON(o.ShippingCountryID=c.CountryID) "
                    . " WHERE o.OrderID='".$OrderID."'";
            //echo $sql;die;
            return $this->db->query($sql)->row_array();
        }
        
        public function get_order_shipping_country_id($OrderID){
            $dataArr=$this->db->select('ShippingCountryID')->from($this->_table)->where('OrderID',$OrderID)->get()->row_array();
            return $dataArr['ShippingCountryID'];
        }
        
        
        public function update_order_spipping_address($dataArr,$OrderID){
            $this->db->where('OrderID',$OrderID);
            $this->db->update($this->_table,$dataArr);
            //echo $this->db->last_query();die;
            return TRUE;
        }
        
}