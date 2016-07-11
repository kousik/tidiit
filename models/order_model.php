<?php
class Order_model extends CI_Model {
    public $_table='order';
    public $_billing='order_details';
    public $_cart='cart';
    public $_payment='payment';
    private $_state='order_state';
    //private $_shipped_history='shipped_history';
    private $_history='order_history';
    private $_product='product';

    private $_coupon = 'coupon';
    private $_order_coupon = 'order_coupon';    

    private $_sod="settlement_on_delivery";
    private $_mpesa="mpesa";
    private $_netbanking="netbanking_data";
    private $_razorpay="razorpay";
    private $_out_for_delivery="order_out_for_delivery_pre_alert";
    private $_delivered_request="order_delivered_request";
    private $_movement_history="order_movement_history";
    
    private $_wishlist="wishlist";
    private $_payment_gateway="payment_gateway";
    


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
        
        $this->db->where('userId',$this->session->userdata('FE_SESSION_VAR'))->where('status',0)->from('order');
        $this->session->set_userdata('TotalItemInCart',$this->db->count_all_results());
        return TRUE;
    }

    public function get_single_order_by_id($orderId){
        $this->db->limit(1);
        $this->db->order_by('orderUpdatedate','DESC');
        $this->db->select('o.*,p.paymentType,s.email AS sellerEmail,s.firstName AS sellerFirstName,s.lastName AS sellerLastName,s1.email AS buyerEmail,s1.firstName AS buyerFirstName,s1.lastName AS buyerLastName,s1.mobile AS buyerMobileNo');
        $this->db->from($this->_table.' o');
        $this->db->join('product_seller ps','o.productId=ps.productId')->join('user s','ps.userId=s.userId')->join('user s1','o.userId=s1.userId');
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
        $this->db->or_where('parrentorderId',$orderId);
        $query = $this->db->get($this->_table);
        return $query->result();
    }

    public function is_parent_group_order_available($orderId, $userId){
        $this->db->select('*');
        $this->db->where('userId',$userId);
        $this->db->where('parrentorderId',$orderId);
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
    
    public function get_my_all_orders_with_parent_app($userId){
        //$this->db->select('a.*, count(c.parrentOrderId) as pid, pd.*, bd.title AS brandTitle, pi.image');
        $this->db->select('a.*, pd.title, bd.title AS brandTitle, pi.image');
        $this->db->from($this->_table.' as a');
        //$this->db->join($this->_table.' as c', 'c.parrentOrderId = a.orderId', 'LEFT OUTER');

        $this->db->join('product as pd', 'pd.productId = a.productId', 'LEFT');
        $this->db->join('product_brand as pb', 'pd.productId = pb.productId', 'LEFT');
        $this->db->join('brand as bd', 'pb.brandId = bd.brandId', 'LEFT');
        $this->db->join('product_image as pi', 'pd.productId = pi.productId', 'LEFT');

       $cond= array('a.userId'=>$userId);
        foreach($cond as $key=>$val){
                $this->db->where($key,$val);
        }

        $this->db->where('a.status !=',0);
        //$this->db->where('c.status !=',0);
        
        $this->db->group_by('a.orderId');
        $this->db->order_by('a.orderId','DESC');
        $this->result = $this->db->get()->result_array();
        //echo $str = $this->db->last_query();
        return $this->result;

    }

    public function get_parent_order($orderId){
        $this->db->select('*');
        $this->db->where('parrentorderId',$orderId);
        $this->db->where('status !=',0);
        $query = $this->db->get($this->_table);
        $result = $query->result();
        if($result):
            return $result;
        else:
            return false;
        endif;
    }

    public function order_group_status_update($orderId, $status, $parrentorderId){          
        if($parrentorderId):
            $this->db->where('orderId',$parrentorderId);
            $this->db->update($this->_table,array('status' => $status));

            $this->db->where('parrentorderId',$parrentorderId);
            $this->db->update($this->_table,array('status' => $status));
        else:
            $this->db->where('orderId',$orderId);
            $this->db->update($this->_table,array('status' => $status));

            $this->db->where('parrentorderId',$orderId);
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

    public function update_status($Status,$orderId){
            $this->db->where('orderId',$orderId);
            $this->db->update($this->_table,array('status'=>$Status));
            return TRUE;
    }


    public function details($orderId,$app=FALSE){
        $this->db->select('o.*,pp.qty AS productSlabPrice,pp.price AS productSlabPrice,s.email AS sellerEmail,s.firstName AS sellerFirstName, '
                . 's.lastName AS sellerLastName,s1.email AS buyerEmail,s1.firstName AS buyerFirstName,s1.lastName AS buyerLastName,p.paymentType,s1.mobile AS buyerMobileNo');
        $this->db->from($this->_table.' o')->join('product_price pp','o.productPriceId=pp.productPriceId')->join('user s1','o.userId=s1.userId');
        $this->db->join('product_seller ps','o.productId=ps.productId')->join('user s','ps.userId=s.userId')->join('`payment` As p','o.orderId=p.orderId','left');
        if($app==TRUE)
            return $this->db->where('o.orderId',$orderId)->order_by('orderUpdatedate','DESC')->get()->result_array();
        else    
            return $this->db->where('o.orderId',$orderId)->order_by('orderUpdatedate','DESC')->get()->result();
    }


    public function remove_order($orderId){
            $this->db->where_in('orderId',$orderId);
            $this->db->delete($this->_table);

            $this->db->where_in('orderId',$orderId);
            $this->db->delete($this->_cart);		
            
            $this->db->where('userId',$this->session->userdata('FE_SESSION_VAR'))->where('status',0)->from('order');
            $this->session->set_userdata('TotalItemInCart',$this->db->count_all_results());
            return TRUE;
    }

    public function seller_list($per_page,$offcet=0){
        $FromDate=$this->input->get_post('HiddenFilterFromDate',TRUE);
        $ToDate=$this->input->get_post('HiddenFilterToDate',TRUE);
        $UserName=$this->input->get_post('HiddenFilterUserName',TRUE);
        $OrderStatus=$this->input->get_post('HiddenFilterOrderStatus',TRUE);
        $sql='SELECT o.*,u1.email FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) '
                . ' JOIN `user` AS u ON(u.userId=ps.userId)'
                . ' JOIN `user` AS u1 ON(u1.userId=o.userId) WHERE o.status > 1 AND u.userId='.$this->session->userdata('FE_SESSION_VAR').' ';//.' AND o.parrentorderId=0 ';
        if($OrderStatus!=""):
            $sql.=" AND o.status=$OrderStatus ";
        endif;
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
        $sql='SELECT o.*,u1.email,u.email AS SellerEmail,os.name AS orderStatusType, (SELECT paymentType FROM `payment` As p WHERE p.orderId=o.orderId ORDER BY `paymentId` DESC LIMIT 0,1) AS paymentType, '
                . ' (select odr.orderDeliveredRequestId FROM `order_delivered_request` AS odr where o.orderId=odr.orderId order by odr.orderDeliveredRequestId DESC limit 0,1) AS orderDeliveredRequestId  '
                . ' FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) '
                . ' JOIN `user` AS u ON(u.userId=ps.userId) JOIN `user` AS u1 ON(u1.userId=o.userId) '
                . ' JOIN `order_state` AS os ON(o.status=os.orderStateId) '
                . ' LEFT JOIN `order_delivered_request` AS odr ON(o.orderId=odr.orderId)  WHERE o.status >1 ';

        if($OrderStatus!=""):
            $sql.=" AND o.status=$OrderStatus ";
        endif;
        
        $sql .= 'ORDER BY o.orderId DESC';
        $sql.=" LIMIT $offcet,$per_page";
        $arr=$this->db->query($sql)->result();
        //echo $this->db->last_query(); die;
        return $arr;
    }

    public function admin_uncomplete_list($per_page,$offcet=0){
            $sql='SELECT o.*,u.FirstName,u.LastName,u.Email,u.Phone FROM `order` AS o JOIN `user` AS u ON(o.UserID=u.UserID) WHERE o.Status="0" ORDER BY o.orderId DESC';
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
        $sql='SELECT o.* FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) JOIN `user` AS u ON(u.userId=ps.userId) '
                . ' WHERE o.status >1 AND u.userId='.$this->session->userdata('FE_SESSION_VAR').' ';
        return count($this->db->query($sql)->result());
    }

    public function admin_list_total(){
        $FromDate=$this->input->post('HiddenFilterFromDate',TRUE);
        $ToDate=$this->input->post('HiddenFilterToDate',TRUE);
        $UserName=$this->input->post('HiddenFilterUserName',TRUE);
        $OrderStatus=$this->input->post('HiddenFilterOrderStatus',TRUE);
        $sql='SELECT o.* FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) '
                . ' JOIN `user` AS u ON(u.userId=ps.userId) WHERE o.status >1 ';
        return count($this->db->query($sql)->result());
    }

    function get_order_for_statics(){
        $sql='SELECT o.* FROM `order` AS o JOIN `product_seller` AS ps ON(o.productId=ps.ProductId) JOIN `user` AS u ON(u.userId=ps.userId) WHERE o.status !=1 AND u.userId='.$this->session->userdata('FE_SESSION_VAR').' ';
        return $this->db->query($sql)->result();
    }


    public function get_admin_uncomplete_total(){
        $sql='SELECT o.orderId FROM `order` AS o JOIN `user` AS u ON(o.UserID=u.UserID) WHERE o.Status="0" ORDER BY o.orderId DESC';
            return count($this->db->query($sql)->result());
    }


    public function get_state($app=FALSE){
        if($app==FALSE)
            return $this->db->select('*')->from($this->_state)->where('orderStateId <=','8')->order_by('orderStateId')->get()->result();
        else
            return $this->db->select('*')->from($this->_state)->where('orderStateId <=','8')->order_by('orderStateId')->get()->result_array();
    }

    public function get_state1($app=FALSE){
        if($app==FALSE)
            return $this->db->select('*')->from($this->_state)->where('orderStateId <','7')->order_by('orderStateId')->get()->result();
        else
            return $this->db->select('*')->from($this->_state)->where('orderStateId <','7')->order_by('orderStateId')->get()->result_array();
    }


    public function change_state($orderId,$orderStateId){
            $this->db->where('orderId',$orderId);
            $this->db->update($this->_table,array('orderStateId'=>$orderStateId));
            return TRUE;
    }


    public function get_cart_details_by_order($orderId){
            return $this->db->select('*')->from($this->_cart)->where('orderId',$orderId)->get()->result();
    }

    public function add_ccavenue_data($dataArray){
            $this->db->insert($this->_ccavenue,$dataArray);
            return $this->db->insert_id();		
    }

    public function get_shipping_address_by_order_id($orderId){
            $sql="SELECT sd.*,c.CountryName,s.StateName,u.FirstName,u.LastName,u.Email,o.ShippToName,o.UserPreferredDeliveryDate,"
                    . " o.UserSpecialOccasion,o.SpecialInstruction,o.UserNote FROM `order` AS o JOIN user AS u ON(o.UserID=u.UserID) "
                    . " JOIN shipping_address AS sd ON(o.ShippingAddresssID=sd.ShippingAddressID) "
                    . " JOIN country AS c ON(sd.CountryID=c.CountryID) JOIN state AS s ON(sd.StateID=s.StateID) WHERE o.orderId='".$orderId."'";
            return $this->db->query($sql)->result();
    }

    /*public function add_shipped_history($DataArr){
            $this->db->insert_batch($this->_shipped_history,$DataArr);
            return $this->db->insert_id();
    }*/

    public function add_history($DataArr){
            $this->db->insert($this->_history,$DataArr);
            return $this->db->insert_id();
    }


    /*public function get_track_url($orderId){
        return $this->db->select('*')->from($this->_shipped_history)->where('orderId',$orderId)->get()->result();
    }*/

    public function cancel($orderId){
        $this->db->where('orderId',$orderId);
        $this->db->update($this->_table,array('orderStateId'=>7));
        //echo $this->db->last_query();die;
        return TRUE;
    }

    public function get_shipping_country_name_id($orderId){
        $sql="SELECT c.CountryName,c.CountryID FROM `order` AS o JOIN `country` AS c ON(o.ShippingCountryID=c.CountryID) "
                . " WHERE o.orderId='".$orderId."'";
        //echo $sql;die;
        return $this->db->query($sql)->row_array();
    }

    public function get_order_shipping_country_id($orderId){
        $dataArr=$this->db->select('ShippingCountryID')->from($this->_table)->where('orderId',$orderId)->get()->row_array();
        return $dataArr['ShippingCountryID'];
    }


    public function update_order_spipping_address($dataArr,$orderId){
        $this->db->where('orderId',$orderId);
        $this->db->update($this->_table,$dataArr);
        //echo $this->db->last_query();die;
        return TRUE;
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
        $this->db->where('orderId',$data['orderId']);
        $this->delete($this->_order_coupon);
        if($this->db->insert($this->_order_coupon, $data)){
            return $this->db->insert_id();
        }else{
            return false;
        }
    }

    function get_all_chield_order($orderId){
        $rs=  $this->db->from($this->_table)->where('parrentorderId',$orderId)->get()->result();
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
        
    function current_order_state_history($orderId){
        return $this->db->from($this->_history)->where('orderId',$orderId)->order_by('actionDate','desc')->limit(1)->get()->row_array();
    }
    
    function add_order_out_for_delivery($dataArr){
        $this->db->insert($this->_out_for_delivery,$dataArr);
        return $this->db->insert_id();
    }
    
    public function edit_payment($dataArray,$orderId){
        $this->db->where('orderId',$orderId);
        $this->db->update($this->_payment,$dataArray);
        return TRUE;		
    }
    
    function get_product_price_details_by_orderid($orderId){
        return $this->db->select("pp.productId,pp.qty")->from($this->_table.' AS o')->join('product_price AS pp','o.productPriceId=pp.productPriceId')->where('o.orderId',$orderId)->get()->result_array();
    }
    
    function add_order_delivered_request($dataArr){
        $this->db->insert($this->_delivered_request,$dataArr);
        return $this->db->insert_id();
    }
    
    function get_latest_delivery_details($orderId){
        $this->db->select('dr.*,l.title AS logisticsCompanyName')->from($this->_delivered_request.' AS dr')->join('logistics AS l','dr.logisticsId=l.logisticsId');
        return $this->db->where('dr.orderId',$orderId)->order_by('dr.orderDeliveredRequestId','DESC')->limit(1)->get()->result_array();
    }
    
    function update_order_delivered_request($dataArr,$orderId){
        $this->db->where('orderId',$orderId);
        $this->db->update($this->_delivered_request,$dataArr);
        return TRUE;		
    }
    
    function get_order_delivery_details_by_order_id($orderId){
        $this->db->select('odr.*,l.title,l.registrationNo,l.supportNo')->from($this->_delivered_request.' AS odr');
        return $this->db->join('logistics As l','odr.logisticsId=l.logisticsId')->where('odr.orderId',$orderId)->get()->row();
    }
    
    function get_all_cart_item($userId,$orderType=''){
        $this->db->select('o.*,c.IN_tax,c.KE_tax')->from($this->_table.' AS o');
        $this->db->join('product_category pc','pc.productId=o.productId')->join('category c','pc.categoryId=c.categoryId');
        $this->db->where('o.userId',$userId)->where_in('o.status',array(0,8));
        if($orderType!='')
            $this->db->where('orderType', strtoupper($orderType));
        
        return $this->db->get()->result_array();
    }
    
    function remove_order_from_cart($orderId,$userId){
        $this->db->where('orderId',$orderId);
        $this->delete($this->_order_coupon);
        
        $this->db->where_in('orderId',$orderId)->where('userId',$userId);
        $this->db->delete($this->_table);
    }
    
    function add_to_wish_list($dataArr){
        //$this->db->where('productId',$dataArr['productId'])->where('userId',$dataArr['userId'])->where('productPriceId',$dataArr['productPriceId'])->where('orderType','SINGLE');
        $this->db->where('productId',$dataArr['productId'])->where('userId',$dataArr['userId'])->where('productPriceId',$dataArr['productPriceId']);
        $no=$this->db->from($this->_wishlist)->count_all_results();
        //echo $this->db->last_query();die;
        //echo $no;die;
        if($no==0){
            $this->db->insert($this->_wishlist,$dataArr);
            return $this->db->insert_id();
        }
        return FALSE;
    }
    
    function remove_from_wish_list($wishlistId,$userId){
        $this->db->where('wishlistId',$wishlistId)->where('userId',$userId);
        $this->db->delete($this->_wishlist);
        return TRUE;
    }
    
    function get_all_item_in_with_list($userId,$app=TRUE){
        $sql="SELECT w.*,pp.qty,pp.price,p.title,(select pi.image from `product_image` AS `pi` where pi.productId=w.productId limit 0,1) AS `productImage` from ";
        $sql.= " `wishlist` AS `w` JOIN `product` AS `p` ON(w.productId=p.productId) JOIN `product_price` AS `pp` ON(w.productPriceId=pp.productPriceId) ";
        $sql .= " WHERE w.userId=$userId";
        if($app==true){
            return $this->db->query($sql)->result_array();
        }else{
            return $this->db->query($sql)->result();
        }
    }
    
    function get_wishlist_by_id($wishlistId,$userId){
        return $this->db->get_where($this->_wishlist,array('wishlistId'=>$wishlistId,'userId'=>$userId))->result_array();
    }
    
    
    public function get_incomplete_order_by_user($userId,$orderType=''){
        //$this->db->limit(1);
        $this->db->order_by('orderUpdatedate','DESC');
        $this->db->select('o.*,p.paymentType,s.email AS sellerEmail,s.firstName AS sellerFirstName,s.lastName AS sellerLastName,s1.email AS buyerEmail,s1.firstName AS buyerFirstName,s1.lastName AS buyerLastName,s1.mobile AS buyerMobileNo');
        $this->db->from($this->_table.' o');
        $this->db->join('product_seller ps','o.productId=ps.productId')->join('user s','ps.userId=s.userId')->join('user s1','o.userId=s1.userId');
        $this->db->join('payment p','o.orderId=p.orderId','left');
        $this->db->where('o.userId',$userId)->where('o.status',0);
        if($orderType!='')
            $this->db->where('o.orderType',  strtoupper($orderType));
        $orderData = $this->db->get()->result();
        //echo $str = $this->db->last_query();
        //print_r($orderData);
        $order = !empty($orderData)?$orderData:false;
        return $order;
    }
    
    public function inctive_order_details_by_order_id_user_id($orderId,$userId){
        $this->db->where('orderId',$orderId)->where('userId',$userId)->where('status',0)->or_where('status',8);
        if($this->db->from($this->_table)->count_all_results()>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    function has_order_with_order_type($userId){
        $this->db->where('userId',$userId)->where('status <',2);
        return $this->db->from($this->_table)->get()->row();
    }
    
    function get_coupon_details_by_order_id($orderId){
        return $this->db->from('coupon c')->join('order_coupon oc','oc.couponId=c.couponId')->where('oc.orderId',$orderId)->get()->row();
    }

    function tidiit_get_user_orders($userId, $status = 0){
        $this->db->where('userId',$userId)->where_in('status',array(0,8));
        return $this->db->from($this->_table)->get()->result();
    }

    function is_valid_order_by_order_id_user_id($orderId,$userId,$status=0){
        $this->db->where('orderId',$orderId)->where('userId',$userId)->where('status',$status);
        $no=$this->db->from($this->_table)->count_all_results();
        //echo $this->db->last_query();die;
        if($no>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    function buying_club_has_order($groupId){
        $this->db->where('groupId',$groupId);
        $no=$this->db->from($this->_table)->count_all_results();
        if($no>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
 
    function add_movement_history($dataArr){
        $this->db->insert($this->_movement_history,$dataArr);
        return $this->db->insert_id();
    }
    
    
    function add_rajorpay_return_data($dataArr){
        $this->db->insert($this->_razorpay,$dataArr);
        return $this->db->insert_id();
    }
    
    function get_razorpay_info(){
        $rs=$this->db->from('system_constants')->where('constantName','PAYMENT_GATEWAY_STATE')->get()->result();
        return $this->db->select('pgc.*')->from('payment_gateway pg')->join('payment_gateway_config pgc','pg.gatewayId=pgc.gatewayId')->where('pg.gatewayId',1)->where('pgc.type',$rs[0]->constantValue)->get()->result();
        //return $this->db->from()->where('gatewayName','razorpay')->where('type',$rs[0]->constantValue)->get()->result();
    }
    
    function get_rajorpay_id_by_rajorpay_pament_id($razorpayPaymentId){
        return $this->db->from($this->_razorpay)->where('razorpayPaymentId',$razorpayPaymentId)->get()->result();
    }
    
    function get_all_gateway($app=FALSE,$countryCode=""){
        if($app==FALSE):
            return $this->db->from($this->_payment_gateway)->where('countryCode',  $this->session->userdata('FE_SESSION_USER_LOCATION_VAR'))->where('status',1)->get()->result();
        else:
            return $this->db->from($this->_payment_gateway)->where('countryCode',  $countryCode)->where('status',1)->get()->result_array();
        endif;
    }
    
    function get_mpesa_info(){
        $rs=$this->db->from('system_constants')->where('constantName','PAYMENT_GATEWAY_STATE')->get()->result();
        return $this->db->select('pgc.*')->from('payment_gateway pg')->join('payment_gateway_config pgc','pg.gatewayId=pgc.gatewayId')->where('pg.gatewayId',3)->where('pgc.type',$rs[0]->constantValue)->get()->result();
        //return $this->db->from()->where('gatewayName','razorpay')->where('type',$rs[0]->constantValue)->get()->result();
    }
}