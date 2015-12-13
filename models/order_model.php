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
        
        private $_coupon = 'coupon';
        private $_order_coupon = 'order_coupon';
        
        private $_sod="settlement_on_delivery";
        private $_mpesa="mpesa";
        private $_netbanking="netbanking_data";
	
	
	public $result=NULL;
	function __construct() {
		parent::__construct();
	}
	
	public function add($dataArray){
		$this->db->insert($this->_table,$dataArray);
		return $this->db->insert_id();
	}
	
        public function update($DataArr,$orderId){
            $this->db->where('orderId',$orderId);
            $this->db->update($this->_table,$DataArr);
            return TRUE;		
	}
        
        public function delete($orderId){
            $this->db->where('orderId', $orderId);
            $this->db->delete($this->_table); 
            
            $this->db->where('orderId', $orderId);
            $this->db->delete($this->_order_coupon); 
            return TRUE;
        }
        
        public function get_single_order_by_id($orderId){
            $this->db->limit(1);
            $this->db->select('o.*,p.paymentType')->from($this->_table.' o');
            $this->db->join('payment p','o.orderId=p.orderId','left');
            $orderData = $this->db->where('o.orderId',$orderId)->get()->result(); 
            //echo $str = $this->db->last_query();
            //print_r($orderData);
            $order = !empty($orderData)?$orderData[0]:false;
            return $order;
        }
        
        public function get_available_order_quantity($orderId){
            $this->db->select_sum('productQty');
            $this->db->where('orderId',$orderId);
            $this->db->or_where('parrentOrderID',$orderId);
            $query = $this->db->get($this->_table);
            return $query->result();
        }
        
        public function is_parent_group_order_available($orderId, $userId){
            $this->db->select('*');
            $this->db->where('userId',$userId);
            $this->db->where('parrentOrderID',$orderId);
            $this->db->limit(1);
            $query = $this->db->get($this->_table);
            $result = $query->result();
            if($result):
                return $result[0];
            endif;
            return false;
        }
        
        public function get_my_all_orders_with_parent($offset = null, $limit = null, $cond){
            //$this->db->select('a.*, count(c.parrentOrderId) as pid, pd.*, bd.title AS brandTitle, pi.image');
            $this->db->select('a.*, pd.title, bd.title AS brandTitle, pi.image');
            $this->db->from($this->_table.' as a');
            //$this->db->join($this->_table.' as c', 'c.parrentOrderId = a.orderId', 'LEFT OUTER');
            
            $this->db->join('product as pd', 'pd.productId = a.productId', 'LEFT');
            $this->db->join('product_brand as pb', 'pd.productId = pb.productId', 'LEFT');
            $this->db->join('brand as bd', 'pb.brandId = bd.brandId', 'LEFT');
            $this->db->join('product_image as pi', 'pd.productId = pi.productId', 'LEFT');
            
            
            foreach($cond as $key=>$val){
                    $this->db->where($key,$val);
            }
            
            $this->db->where('a.status !=',0);
            //$this->db->where('c.status !=',0);
            if($limit != null){
                $this->db->limit($limit, $offset);
            }    
            
            $this->db->group_by('a.orderId');
            $this->db->order_by('a.orderId','DESC');
            $query = $this->db->get();
            $this->result = $query->result();
            //echo $str = $this->db->last_query();
            return $this->result;
            
        }
        
               
        public function get_parent_order($orderId){
            $this->db->select('*');
            $this->db->where('parrentOrderID',$orderId);
            $this->db->where('status !=',0);
            $query = $this->db->get($this->_table);
            $result = $query->result();
            if($result):
                return $result;
            else:
                return false;
            endif;
        }
        
        
        public function order_group_status_update($orderId, $status, $parrentOrderID){          
            if($parrentOrderID):
                $this->db->where('orderId',$parrentOrderID);
                $this->db->update($this->_table,array('status' => $status));
                
                $this->db->where('parrentOrderID',$parrentOrderID);
                $this->db->update($this->_table,array('status' => $status));
            else:
                $this->db->where('orderId',$orderId);
                $this->db->update($this->_table,array('status' => $status));
                
                $this->db->where('parrentOrderID',$orderId);
                $this->db->update($this->_table,array('status' => $status));
            endif;            
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
	
	
	public function details($orderId){
            $this->db->select('o.*,pp.qty AS productSlabPrice,pp.price AS productSlabPrice,s.email AS sellerEmail,s.firstName AS sellerFirstName,s.lastName AS sellerLastName');
            $this->db->from($this->_table.' o')->join('product_price pp','o.productPriceId=pp.productPriceId');
            $this->db->join('product_seller ps','o.productId=ps.productId')->join('user s','ps.userId=s.userId');
            return $this->db->where('o.orderId',$orderId)->get()->result();
	}
	
	
	public function remove_order($OrderID){
		$this->db->where_in('OrderID',$OrderID);
		$this->db->delete($this->_table);
		
		$this->db->where_in('OrderID',$OrderID);
		$this->db->delete($this->_cart);		
		
		return TRUE;
	}
	
	public function seller_list($per_page,$offcet=0){
            $FromDate=$this->input->post('HiddenFilterFromDate',TRUE);
            $ToDate=$this->input->post('HiddenFilterToDate',TRUE);
            $UserName=$this->input->post('HiddenFilterUserName',TRUE);
            $OrderStatus=$this->input->post('HiddenFilterOrderStatus',TRUE);
            $sql='SELECT o.*,u1.email FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) '
                    . ' JOIN `user` AS u ON(u.userId=ps.userId)'
                    . ' JOIN `user` AS u1 ON(u1.userId=o.userId) WHERE o.status >1 AND u.userId='.$this->session->userdata('FE_SESSION_VAR').' ';//.' AND o.parrentOrderID=0 ';

            /*if($UserName!=""){
                $sql .= " AND u.UserName='".$UserName."'";
            }

            if($OrderStatus!=""){
                $sql .= " AND o.OrderStateID='".$OrderStatus."'";
            }

            if($FromDate!="" && $ToDate!=""){
                $sql .= " AND o.OrderDate BETWEEN '".$FromDate."' AND '".$ToDate."' ";
            }*/

            $sql .= 'ORDER BY o.orderId DESC';
            $sql.=" LIMIT $offcet,$per_page";
            $arr=$this->db->query($sql)->result();
            //echo $this->db->last_query(); //die;
            return $arr;
	}
        
        public function admin_list($per_page,$offcet=0){
            $FromDate=$this->input->post('HiddenFilterFromDate',TRUE);
            $ToDate=$this->input->post('HiddenFilterToDate',TRUE);
            $UserName=$this->input->post('HiddenFilterUserName',TRUE);
            $OrderStatus=$this->input->post('HiddenFilterOrderStatus',TRUE);
            $sql='SELECT o.*,u1.email,u.email AS SellerEmail,os.name AS orderStatusType,p.paymentType FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) '
                    . ' JOIN `user` AS u ON(u.userId=ps.userId) JOIN `user` AS u1 ON(u1.userId=o.userId) '
                    . ' JOIN `order_state` AS os ON(o.status=os.orderStateId) JOIN `payment` AS p ON(p.orderId=o.orderId)  WHERE o.status >1 ';

            /*if($UserName!=""){
                $sql .= " AND u.UserName='".$UserName."'";
            }

            if($OrderStatus!=""){
                $sql .= " AND o.OrderStateID='".$OrderStatus."'";
            }

            if($FromDate!="" && $ToDate!=""){
                $sql .= " AND o.OrderDate BETWEEN '".$FromDate."' AND '".$ToDate."' ";
            }*/

            $sql .= 'ORDER BY o.orderId DESC';
            $sql.=" LIMIT $offcet,$per_page";
            $arr=$this->db->query($sql)->result();
            //echo $this->db->last_query(); die;
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
        
        
        public function seller_list_total(){
            $FromDate=$this->input->post('HiddenFilterFromDate',TRUE);
            $ToDate=$this->input->post('HiddenFilterToDate',TRUE);
            $UserName=$this->input->post('HiddenFilterUserName',TRUE);
            $OrderStatus=$this->input->post('HiddenFilterOrderStatus',TRUE);
            $sql='SELECT o.* FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) JOIN `user` AS u ON(u.userId=ps.userId) WHERE o.status >1 AND u.userId='.$this->session->userdata('FE_SESSION_VAR').' ';
            return count($this->db->query($sql)->result());
        }
        
        public function admin_list_total(){
            $FromDate=$this->input->post('HiddenFilterFromDate',TRUE);
            $ToDate=$this->input->post('HiddenFilterToDate',TRUE);
            $UserName=$this->input->post('HiddenFilterUserName',TRUE);
            $OrderStatus=$this->input->post('HiddenFilterOrderStatus',TRUE);
            $sql='SELECT o.* FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) JOIN `user` AS u ON(u.userId=ps.userId) WHERE o.status >1 ';
            return count($this->db->query($sql)->result());
        }
        
        function get_order_for_statics(){
            $sql='SELECT o.* FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) JOIN `user` AS u ON(u.userId=ps.userId) WHERE o.status >1 AND u.userId='.$this->session->userdata('FE_SESSION_VAR').' ';
            return $this->db->query($sql)->result();
        }
        
        
        public function get_admin_uncomplete_total(){
            $sql='SELECT o.OrderID FROM `order` AS o JOIN `user` AS u ON(o.UserID=u.UserID) WHERE o.Status="0" ORDER BY o.OrderID DESC';
		return count($this->db->query($sql)->result());
        }
        
	
	public function get_state($app=FALSE){
            if($app==FALSE)
		return $this->db->select('*')->from($this->_state)->where('OrderStateID <','8')->order_by('OrderStateID')->get()->result();
            else
                return $this->db->select('*')->from($this->_state)->where('OrderStateID <','8')->order_by('OrderStateID')->get()->result_array();
	}
        
        public function get_state1($app=FALSE){
            if($app==FALSE)
		return $this->db->select('*')->from($this->_state)->where('OrderStateID <','7')->order_by('OrderStateID')->get()->result();
            else
                return $this->db->select('*')->from($this->_state)->where('OrderStateID <','7')->order_by('OrderStateID')->get()->result_array();
	}
        
	
	public function change_state($OrderID,$OrderStateID){
		$this->db->where('OrderID',$OrderID);
		$this->db->update($this->_table,array('OrderStateID'=>$OrderStateID));
		return TRUE;
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
        
        public function is_coupon_code_exists($code){
            $coupons = $this->db->from($this->_coupon)->where('code',$code)->get()->result();
            if($coupons):
                return $coupons[0];
            else:    
                return false;
            endif;
        }
        
        
        public function is_coupon_code_used_or_not($coupon, $orderId){
            $coupons = $this->db->from($this->_order_coupon)->where('couponId',$coupon->couponId)->get()->result();
            $order = $this->get_single_order_by_id($orderId);
            if($coupons):
                $data = $coupons[0];
                if($data->orderId == $orderId):
                    $edata = array();
                    $edata['orderId'] = $data->orderId;
                    $edata['couponId'] = $data->couponId;
                    $edata['amount'] = $data->amount;
                    $edata['orderAmount'] = $order->subTotalAmount - $data->amount;
                    $edata['applied'] = true;
                    return $edata;
                else:
                    return false;
                endif;
            else:                
                $cdata = array();
                $cdata['orderId'] = $orderId;
                $cdata['couponId'] = $coupon->couponId;
                if($coupon->type == 'percentage'):
                    $amt = ($coupon->amount/100)*$order;
                    $amt1 = number_format($amt, 2, '.', '');
                    $cdata['amount'] = substr($amt1, 0, -3);
                elseif($coupon->type == 'fix'):
                    $cdata['amount'] = $coupon->amount;
                endif;    
                $this->db->insert($this->_order_coupon,$cdata);
		$cdata['orderCouponId'] = $this->db->insert_id();
                
                $order_update['orderAmount'] = $order->subTotalAmount - $cdata['amount'];
                $order_update['discountAmount'] = $cdata['amount'];        
                $this->update($order_update,$orderId);
                $cdata['orderAmount'] = $order_update['orderAmount'];
                return $cdata;
            endif;
        }
        
        
        public function is_coupon_code_used_or_not_for_single($coupon){
            $coupons = $this->db->from($this->_order_coupon)->where('couponId',$coupon->couponId)->get()->result();
            if($coupons):
                return true;                
            else:  
                return false;
            endif;
        }
        
        public function get_order_coupon($orderId){
            $coupons = $this->db->from($this->_order_coupon)->where('orderId',$orderId)->get()->result();
            if($coupons):
                return $coupons[0];
            else:
                return false;
            endif;
        }
        
        public function tidiit_creat_order_coupon($data){
            if($this->db->insert($this->_order_coupon, $data)){
                return $this->db->insert_id();
            }else{
                return false;
            }
        }
        
        function get_all_chield_order($orderId){
            $rs=  $this->db->from($this->_table)->where('parrentOrderID',$orderId)->get()->result();
            return $rs;
        }
        
        function add_sod($dataArr){
            $this->db->insert($this->_sod,$dataArr);
            return $this->db->insert_id();
        }
        
        function add_mpesa($dataArr){
            $this->db->insert($this->_mpesa,$dataArr);
            return $this->db->insert_id();
        }
}