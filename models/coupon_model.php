<?php
class Coupon_model extends CI_Model{
	public $_table='coupon';
	public $_table_category='coupon_category';
	public $_table_user='coupon_user';
        
        private $_coupon = 'coupon';
        private $_order_coupon = 'order_coupon';
	
	function __construct() {
		parent::__construct();
                $this->_SiteSession=$this->session->userdata('USER_SITE_SESSION_ID');
	}
	
	public function get_all_admin(){
            $this->db->select('co.*,c.categoryName');
            $this->db->from('coupon AS co')->join('coupon_category AS cc','co.couponId=cc.couponId','left');
            $this->db->join('category AS c','cc.categoryId=c.categoryId','left');
            
            $rs=$this->db->get()->result();
            //echo $this->db->last_query();die;
            return $rs;
		//return $this->db->query($sql)->result();
	}
	
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
        
        public function add_user($dataArr){
            $this->db->insert($this->_table_user,$dataArr);
		return $this->db->insert_id();
        }


        public function edit($DataArr,$couponId){
		$this->db->where('couponId',$couponId);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function change_status($couponId,$Status){
		$this->db->where('couponId',$couponId);
		$this->db->update($this->_table,array('Status'=>$Status));
		return TRUE;
	}
	
	public function delete($couponId){
            $this->db->delete($this->_table_user, array('couponId' => $couponId)); 
            $this->db->delete($this->_table_category, array('couponId' => $couponId)); 
            $this->db->delete($this->_table, array('couponId' => $couponId)); 
		return TRUE;
	}
        
        function add_category($dataArr){
            $this->db->insert($this->_table_category,$dataArr);
            return $this->db->insert_id();
        }
        
        function edit_category($DataArr,$couponId){
            $this->db->where('couponId',$couponId);
            $this->db->update($this->_table_category,$DataArr);
            //echo $this->db->last_query();die;
            return TRUE;		
        }
        
        function check_coupon_user_exists($couponId,$userId){
            $rs=$this->db->from($this->_table_user)->where('couponId',$couponId)->where('userId',$userId)->get()->result();
            if(count($rs)>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        
        
        
        function details_by_code($Code){
            $this->db->select('co.*,cc.categoryId')->from($this->_table.' AS co');
            $this->db->join($this->_table_category.' AS cc','co.couponId=cc.couponId','left');
            return $this->db->where('co.Code',$Code)->get()->result();
        }
        
        function details($couponId){
            return $this->db->from($this->_table)->where('couponId',$couponId)->get()->result();
        }
        
        function check_coupon_exists($Code){
            if(count($this->details_by_code($Code))>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        
        
        
        function check_coupon_code_cc($Code){
            $error=FALSE;
            $error_msg='';
            $sql="SELECT * FROM coupon WHERE `code`='".$Code."' AND CURDATE() BETWEEN `startDate` AND `endDate` AND Status=1";
            //echo $sql;die;
            $couponRs=$this->db->query($sql)->result();
            //pre($couponRs);die;
            if(empty($couponRs)){
                $error=TRUE;
                $error_msg='Invalid Coupon or Coupon Code has expired,Please use correct Coupon Code.';
                
            }
            if($error==FALSE){
                $SessionID=$this->_SiteSession;
                //die($SessionID);
                $couponCategory=$this->db->from($this->_table_category)->where('couponId',$couponRs[0]->couponId)->get()->result();
                //pre($couponCategory);
                //checking the coupon for exist category.
                if($error==TRUE){
                    //echo 'errrrrrrrrrrrrrrrrrrrrrrrrrrr';
                    $error_msg='The cupon code you entered is not valid for selected category. Please check your email for supported category for this coupon.';
                }
                /*if(empty($currentCartItem)){
                    $sql1="SELECT p.categoryId FROM temp_cart AS tc JOIN product AS p ON(tc.ProductID=p.ProductID) WHERE tc.SessionID='".$this->_SiteSession."' AND p.categoryId=".$couponCategory[0]->categoryId3;
                    echo $sql1;die;
                    $currentCartItem=$this->db->query($sql1)->result();
                    if(empty($currentCartItem)){
                        $error=TRUE;
                        $error_msg='The cupon code you entered is not valid for selected category. Please check your email for supported category for this coupon.';
                    }
                }*/
            }
            if($error==FALSE){
                $userId=$this->session->userdata("FE_SESSION_VAR");
                if($couponRs[0]->UserUsesType==1){
                    $rsUserSession=$this->db->from($this->_table_user)->where('couponId',$couponRs[0]->couponId)->where('userId',$userId)->get()->result();
                    if(empty($rsUserSession)){
                        $error=TRUE;
                        $error_msg='This Coupon Code is not assigned to you, please enter a different Coupon Code.';
                    }else{
                        if($rsUserSession[0]->isUsed==1 && $rsUserSession[0]->UserUsesType==1){
                            $error=TRUE;
                            $error_msg='The cupon code you entered has already been used onces, please try a different code.';
                        }
                    }
                }
            }
            
            if($error==FALSE){
                return 'ok';
            }else{
                return $error_msg;
            }
                
        }
        
        
    function get_user_data_by_coupon_id($couponId){
        $this->db->select('c.code,u.userName,u.firstName,u.lastName,cu.userUsesType,cu.isUsed');
        $this->db->from($this->_table_user.' AS cu');
        $this->db->join($this->_table.' AS c','cu.couponId=c.couponId');
        $this->db->join('user AS u','cu.userId=u.userId');
        $this->db->where('cu.couponId',$couponId);
        $rs=$this->db->get()->result();
        //echo $this->db->last_query();die;
        return $rs;
    }    
    
    
    public function is_coupon_code_used_or_not($coupon, $orderId){
        $coupons = $this->db->from($this->_order_coupon)->where('couponId',$coupon->couponId)->get()->result();
        $order = $this->get_single_order_by_id($orderId);
        //pre($coupon);pre($coupons);pre($order); //die;
        if($coupons):
            $data = $coupons[0];
            if($data->orderId == $orderId)://die('kkk');
                $edata = array();
                $edata['orderId'] = $data->orderId;
                $edata['couponId'] = $data->couponId;
                $edata['amount'] = $data->amount;
                $edata['orderAmount'] = $order->subTotalAmount - $data->amount;
                $edata['applied'] = true;
                return $edata;
            else: //die('mmmm');
                //return false;
                $cdata = array();
                $cdata['orderId'] = $orderId;
                $cdata['couponId'] = $coupon->couponId;
                if($coupon->type == 'percentage'):
                    $amt = ($coupon->amount/100)*$order->subTotalAmount;
                    $amt1 = number_format($amt, 2, '.', '');
                    $cdata['amount'] = substr($amt1, 0, -3);
                elseif($coupon->type == 'fix'):
                    $cdata['amount'] = $coupon->amount;
                endif;    
                $this->db->insert($this->_order_coupon,$cdata);
                $cdata['orderCouponId'] = $this->db->insert_id();

                $order_update['orderAmount'] = $order->subTotalAmount - $cdata['amount'];
                $order_update['discountAmount'] = $cdata['amount'];        
                $this->update_order($order_update,$orderId);
                $cdata['orderAmount'] = $order_update['orderAmount'];
                return $cdata;
            endif;
        else:
            $rs=$this->db->from($this->_order_coupon)->where('orderId',$orderId)->get()->result();
            if(count($rs)>0):
                $cdata = array();
                $cdata['couponId'] = $coupon->couponId;
                if($coupon->type == 'percentage'):
                    $amt = ($coupon->amount/100)*$order->subTotalAmount;
                    $amt1 = number_format($amt, 2, '.', '');
                    $cdata['amount'] = substr($amt1, 0, -3);
                elseif($coupon->type == 'fix'):
                    $cdata['amount'] = $coupon->amount;
                endif;
                $this->db->where('orderId',$orderId);
		$this->db->update($this->_order_coupon,$cdata);
                $cdata['orderCouponId']=$rs[0]->orderCouponId;
                
                $order_update['orderAmount'] = $order->subTotalAmount - $cdata['amount'];
                $order_update['discountAmount'] = $cdata['amount'];        
                $this->update_order($order_update,$orderId);
                $cdata['orderAmount'] = $order_update['orderAmount'];
                return $cdata;
            else:
                $cdata = array();
                $cdata['orderId'] = $orderId;
                $cdata['couponId'] = $coupon->couponId;
                if($coupon->type == 'percentage'):
                    $amt = ($coupon->amount/100)*$order->subTotalAmount;
                    $amt1 = number_format($amt, 2, '.', '');
                    $cdata['amount'] = substr($amt1, 0, -3);
                elseif($coupon->type == 'fix'):
                    $cdata['amount'] = $coupon->amount;
                endif;    
                //pre($cdata);die;
                $this->db->insert($this->_order_coupon,$cdata);
                $cdata['orderCouponId'] = $this->db->insert_id();

                $order_update['orderAmount'] = $order->subTotalAmount - $cdata['amount'];
                $order_update['discountAmount'] = $cdata['amount'];        
                $this->update_order($order_update,$orderId);
                $cdata['orderAmount'] = $order_update['orderAmount'];
                return $cdata;
            endif;
        endif;
    }
    
    public function is_coupon_code_valid_for_single($coupon){
        $sql="SELECT * FROM coupon WHERE `couponId`='".$coupon->couponId."' AND CURDATE() BETWEEN `startDate` AND `endDate` AND status=1";
        $coupons = $this->db->query($sql)->result();
        //echo $this->db->last_query();die;
        if($coupons):
            return false;
        else:  
            return true;
        endif;
    }
    
    function is_coupon_recently_used($orderIdArr,$couponId){
        $rs=$this->db->from($this->_order_coupon)->where_in('orderId',$orderIdArr)->where('couponId',$couponId)->get()->result();
        //echo $this->db->last_query();die;
        if(count($rs)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }



    public function is_coupon_code_exists($code){
        //$coupons = $this->db->from($this->_coupon)->where('code',$code)->get()->result();
        $sql="SELECT * FROM coupon WHERE `code`='".$code."' AND CURDATE() BETWEEN `startDate` AND `endDate` AND status=1";
        //echo $sql;die;
        $coupons=$this->db->query($sql)->result();
        if($coupons):
            return $coupons[0];
        else:    
            return false;
        endif;
    }
    
    public function get_single_order_by_id($orderId){
        $this->db->limit(1);
        $this->db->order_by('orderUpdatedate','DESC');
        $this->db->select('o.*,p.paymentType,s.email AS sellerEmail,s.firstName AS sellerFirstName,s.lastName AS sellerLastName,s1.email AS buyerEmail,s1.firstName AS buyerFirstName,s1.lastName AS buyerLastName,s1.mobile AS buyerMobileNo');
        $this->db->from('order o');
        $this->db->join('product_seller ps','o.productId=ps.productId')->join('user s','ps.userId=s.userId')->join('user s1','o.userId=s1.userId');
        $this->db->join('payment p','o.orderId=p.orderId','left');
        $orderData = $this->db->where('o.orderId',$orderId)->get()->result(); 
        $order = !empty($orderData)?$orderData[0]:false;
        return $order;
    }
    
    public function update_order($DataArr,$orderId){
        $this->db->where('orderId',$orderId);
        $this->db->update('order',$DataArr);
        return TRUE;		
    }
    
    function remove_order($orderId){
        $this->db->where('orderId',$orderId);
        $this->db->delete($this->_order_coupon); 
    }
}
?>