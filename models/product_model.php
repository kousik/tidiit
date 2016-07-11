<?php
class Product_model extends CI_Model {
	private $_table='product';
	private $_table_image='product_image';
	private $_table_price='product_price';
	private $_table_country='product_country';
	private $_table_category='product_category';
	private $_tag='tags';
	private $_table_tag='product_tag';
	private $_table_discount='product_discount';
	private $_SiteSession="";
	private $_tmp_cart="temp_cart";
	private $_tmp_shipping="user_temp_cart_shipping";
        private $_table_deal="product_deal";
        private $_table_brand="product_brand";
        private $_table_seller="product_seller";
        private $_table_template="product_view_page";
        private $_table_views="product_views";
        
        private $_currentUserCountryCode="";
                
	function __construct() {
		$this->_SiteSession=$this->session->userdata('USER_SITE_SESSION_ID');
                $this->_currentUserCountryCode=$this->session->userdata('FE_SESSION_USER_LOCATION_VAR');
	}
	
	public function get_all_admin($PerPage=0,$PageNo=0){
            //echo '<pre>'.print_r($_POST);die;
            $userId=  $this->session->userdata('FE_SESSION_VAR');
            $Title              =  $this->input->get_post('SearchFilterTitle',TRUE);
            $productModel       =  $this->input->get_post('SearchFilterCode',TRUE);
            $Status             =  $this->input->get_post('SearchFilterStatus',TRUE);
            $Featured           =  $this->input->get_post('SearchFilterFeatured',TRUE);
            $Deals           =  $this->input->get_post('SearchFilterDeal',TRUE);
            $Popular           =  $this->input->get_post('SearchFilterPopular',TRUE);
            $categoryId         =  $this->input->get_post('SearchFilterTopCategory',TRUE);
            $Region             =  $this->input->get_post('SearchFilterRegion',TRUE);
            $isNew             =  $this->input->get_post('SearchFilterIsNew',TRUE);
            
            //echo '$Title'.$Title.'$Status '.$Status.'$Featured'.$Featured.'$categoryId'.$categoryId.'$Region'.$Region.' $Deals '.$Deals.' $isNew '.$isNew;die;
                    
            
            //echo 'PerPage '.$PerPage.'   $PageNo  '.$PageNo;die;
		//$sql="SELECT p.*,pi.Image,co.CountryName,c.CategoryName FROM `product` as p JOIN `product_image` as pi ON(p.productId=pi.productId) JOIN `product_country` AS pc ON(p.productId=pc.productId) JOIN `category` AS c ON(p.categoryId=c.categoryId) LEFT JOIN `country` AS co ON(pc.CountryID=co.CountryID)  WHERE p.Status < 2 AND c.Status='1' ORDER BY p.productId DESC";
                $this->db->select('p.*,pi.image,c.categoryName')->from('product p');
                $this->db->join('product_image pi','p.productId=pi.productId');
                $this->db->join('product_seller ps','p.productId=ps.productId');
                $this->db->join('product_category pc','pc.productId=p.productId');
                $this->db->join('category c','pc.categoryId=c.categoryId', 'LEFT');
                //$this->db->where('c.status','1');
                $this->db->where('ps.userId',$userId);
                
                if($Status==""){
                    $this->db->where('p.status <','2');
                }else{
                    $this->db->where('p.status',$Status);
                }
                
                /*if($Featured!=""){
                    $this->db->where('p.Featured',$Featured);
                }*/
                
                if($categoryId!=""){
                    $this->db->where('p.categoryId',$categoryId);
                }
                
                if($Title!=""){
                    $this->db->like('p.title',$this->db->escape_like_str($Title));
                }
                
                /*if($isNew!=""){
                    $this->db->like('p.isNew',$isNew);
                }
                
                if($Deals!=""){
                    $this->db->like('p.Deals',$Deals);
                }
                
                if($Popular!=""){
                    $this->db->like('p.Popular',$Popular);
                }*/
                
                if($productModel!=""){
                    $this->db->like('p.model',$productModel);
                }
                $this->db->group_by('p.productId');
                $this->db->order_by('p.productId','DESC');
                //$this->db->order_by('p.DealPriceUpdateTime','DESC');
                
                if($PerPage>0){
                    $this->db->limit($PerPage,$PageNo);
                }    
                $dataArr= $this->db->get()->result();
                //echo '<br />'.$this->db->last_query(); die;
                return $dataArr;
	}
        
        public function get_admin_total(){
            $userId=  $this->session->userdata('FE_SESSION_VAR');
                $Title              =  $this->input->get_post('SearchFilterTitle',TRUE);
                $productModel       =  $this->input->get_post('SearchFilterCode',TRUE);
                $Status             =  $this->input->get_post('SearchFilterStatus',TRUE);
                $Featured           =  $this->input->get_post('SearchFilterFeatured',TRUE);
                $Deals              =  $this->input->get_post('SearchFilterDeal',TRUE);
                $Popular              =  $this->input->get_post('SearchFilterPopular',TRUE);
                $categoryId         =  $this->input->get_post('SearchFilterTopCategory',TRUE);
                $Region             =  $this->input->get_post('SearchFilterRegion',TRUE);
                $IsNew             =  $this->input->get_post('SearchFilterIsNew',TRUE);

                $this->db->select('p.*,pi.Image,co.CountryName,c.CategoryName,c.isAddToCart')->from('product p');
                $this->db->join('product_image pi','p.productId=pi.productId');
                $this->db->join('product_seller ps','p.productId=ps.productId');
                $this->db->join('product_country pc','p.productId=pc.productId');
                $this->db->join('category c','p.categoryId=c.categoryId');
                $this->db->join('country co','pc.CountryID=co.CountryID');
                $this->db->where('c.Status','1');
                $this->db->where('ps.userId',$userId);
                
                if($Status==""){
                    $this->db->where('p.Status <','2');
                }else{
                    $this->db->where('p.Status',$Status);
                }
                
                if($Featured!=""){
                    $this->db->where('p.Featured',$Featured);
                }
                
                if($categoryId!=""){
                    $this->db->where('p.categoryId',$categoryId);
                }
                
                if($Region!=""){
                    $this->db->where('pc.CountryID',$Region);
                }
                
                if($IsNew!=""){
                    $this->db->where('p.isNew',$IsNew);
                }
                
                if($Deals!=""){
                    $this->db->where('p.Deals',$Deals);
                }
                
                if($Popular!=""){
                    $this->db->where('p.Popular',$Popular);
                }
                
                if($Title!=""){
                    $this->db->like('p.Title',$this->db->escape_like_str($Title));
                }
                
                if($productModel!=""){
                    $this->db->like('p.Model',$productModel);
                }
                
                $this->db->order_by('p.productId','DESC');
                $this->db->order_by('p.DealPriceUpdateTime','DESC');
		$rs=$this->db->get()->result();
                //echo $this->db->last_query();die;
                return count($rs);

        }
	public function get_all_product(){
		$this->db->select('*')->from($this->_table)->where('Status','1');
		return $this->db->get()->result();
	}
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
        }
        
        public function add_brand($data){
            $this->db->insert($this->_table_brand,$data);
            return $this->db->insert_id();
        }
        
        function edit_brand($DataArr,$productId){
            $this->db->where('productId',$productId);
            $this->db->update($this->_table_brand,$DataArr);
            return TRUE;		
        }
        
        function add_product_category($dataArr){
            $rs=$this->db->from($this->_table_category)->where('categoryId',$dataArr['categoryId'])->where('productId',$dataArr['productId'])->get()->result();
            if(count($rs)==0){
                $this->db->insert($this->_table_category,$dataArr);
                return $this->db->insert_id();
            }else{
                return FALSE;
            }
        }
        
        function add_product_owner($dataArr){
		$this->db->insert($this->_table_seller,$dataArr);
		return $this->db->insert_id();
        }
        
	public function edit($DataArr,$productId){
		$this->db->where('productId',$productId);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function change_status($productId,$action){
		if($action=='active'){
			$Status=1;
		}else{
			$Status=0;
		}
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->update($this->_table,array('Status'=>$Status));
		//echo $this->db->last_query();die;
		return TRUE;
	}
	
	public function remove_featured($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->update($this->_table,array('Featured'=>0));
		return TRUE;
	}
        
        public function featured($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->update($this->_table,array('Featured'=>1));
		return TRUE;
	}

	public function make_new($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->update($this->_table,array('isNew'=>1));
		return TRUE;
	}
        
        public function make_old($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->update($this->_table,array('isNew'=>0));
		return TRUE;
	}
	
        public function deals($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->update($this->_table,array('Deals'=>1));
		return TRUE;
	}
        
        public function remove_deals($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->update($this->_table,array('Deals'=>0));
		return TRUE;
	}
        
	public function popular($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->update($this->_table,array('Popular'=>1));
		return TRUE;
	}
	
        public function remove_popular($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->update($this->_table,array('Popular'=>0));
		return TRUE;
	}
	
	public function delete($productId){
		// delete from product table
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->delete($this->_table); 
		
		// delete prodcut image
		$this->delete_product_image($productId);
		
		// delete prodcut  price
		$this->delete_product_price($productId);
		
		// delete prodcut  price
		$this->delete_product_tag($productId);
                
                $this->db->where_in('productId',explode(',',$productId));
		$this->db->delete($this->_table_brand); 
                
                $this->db->where_in('productId',explode(',',$productId));
		$this->db->delete($this->_table_category); 
                
                $this->db->where_in('productId',explode(',',$productId));
		$this->db->delete($this->_table_deal); 
                
                $this->db->where_in('productId',explode(',',$productId));
		$this->db->delete($this->_table_seller); 
		return TRUE;
	}
	
	public function add_image($dataArr){
		$this->db->insert_batch($this->_table_image,$dataArr);
		return $this->db->insert_id();
	}
        
        function edit_image_product($dataArr,$productId){
            $this->db->insert_batch($this->_table_image,$dataArr);
            return $this->db->insert_id();
        }
        
        function remove_product_by_file($FileName,$productId){
            $this->db->where('productId',$productId);
            $this->db->where('image',$FileName);
            $this->db->delete($this->_table_image); 
        }
	
	public function edit_product_image($dataArr,$productId){
		$this->db->where('productId',$productId);
		$this->db->update($this->_table_image,$dataArr);
		//echo $this->db->last_query();die;
		return TRUE;
	}
	
	public function get_products_images($productId,$app=false){
            $this->db->select('*')->from($this->_table_image)->where_in('productId',explode(',',$productId));
            if($app)
                return $this->db->get()->result_array();
            else    
                return $this->db->get()->result();
	}
	
	public function delete_product_image($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->delete($this->_table_image); 
	}
	
	public function add_product_price($dataArr){
            $this->db->insert_batch($this->_table_price,$dataArr);
            return $this->db->insert_id();
	}
        
        public function edit_product_price($dataArr,$productId){
            $this->db->where('productId',$productId);
            $this->db->delete($this->_table_price);
            $this->db->insert_batch($this->_table_price,$dataArr);
            return $this->db->insert_id();
        }
	
	public function add_country($dataArr){
		$this->db->insert($this->_table_country,$dataArr);
		return $this->db->insert_id();	
		
	}
	public function edit_price($dataArr,$productId){
		$this->db->where('productId',$productId);
		$this->db->update($this->_table_price,$dataArr);
		//echo $this->db->last_query();die;
		return TRUE;
	}
	
	public function delete_product_price($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->delete($this->_table_price); 
	}
	
	public function add_product_tag($dataArr){
            $productId=$dataArr["productId"];
            if(!array_key_exists('tagStr', $dataArr)){return FALSE;}
            $tagArr=explode(',',$dataArr["tagStr"]);
            if(empty($tagArr)){return FALSE;}
            foreach($tagArr AS $k){
                if($this->is_product_tag_added($k,$productId)==FALSE){
                    $this->add_tag_product(array('productId'=>$productId,'tag'=>$k));
                }
            }
            return true;
	}
        
        public function add_category_tag($dataArr){
            $categoryId=$dataArr["categoryId"];
            $tagArr=explode(',',$dataArr["TagStr"]);
            $categoryTagArr=array();
            foreach($tagArr AS $k){
                $tagDataArr=array();
                $tagDataArr=$this->get_tag_id_by_tag($k);
                if(count($tagDataArr)>0){
                    $rs=$this->db->query("SELECT * FROM category_tag WHERE categoryId='".$categoryId."' AND tagId='".$tagDataArr[0]->tagId."'")->result();
                    if(empty($rs))
                        $categoryTagArr[]=array('categoryId'=>$categoryId,'tagId'=>$tagDataArr[0]->tagId);
                }else{
                    if($k!=""){
                        //echo '<br>not exis add new <br>';
                        $tagId=$this->add_tag($k);
                        if($tagId>0){
                                $categoryTagArr[]=array('categoryId'=>$categoryId,'tagId'=>$tagId);
                        }
                    }
                }
            }
            pre($categoryTagArr);
            return true;
	}
	public function get_tag_by_product_id($productId){
            return $this->db->select('GROUP_CONCAT(`tag`) AS productTag',false)->from($this->_table_tag)->where('productId',$productId)->get()->row();
        }
        public function is_product_tag_added($productId,$tag){
            $sql="SELECT * FROM product_tag WHERE `productId`='".$productId."' AND `tag`='".$tag."'";
            $dataArr=$this->db->query($sql)->result();
            if(count($dataArr)>0){
                return TRUE;
            }else{
                return FALSE;
            }
	}	
	public function add_tag($tag){
		$dataArr=array('name'=>$this->db->escape_str($tag));
		$this->db->insert($this->_tag,$dataArr);
		return $this->db->insert_id();
	}
	
	public function get_tag_id_by_tag($tag){
		$this->db->select('*')->from($this->_tag)->where('name',$this->db->escape_str($tag));
		$rs=$this->db->get()->result();
                //echo $this->db->last_query();die;
                return $rs;
	}
	
	public function add_tag_product($DataArr){
		$this->db->insert($this->_table_tag,$DataArr);
		return $this->db->insert_id();
	}
	
	public function delete_product_tag($productId){
		$this->db->where_in('productId',explode(',',$productId));
		$this->db->delete($this->_table_tag); 		
	}
	
	public function details($id,$app=FALSE){
            $sql="SELECT p.*,b.title AS brandTitle,b.brandId FROM `product` AS p JOIN `product_brand` AS pb ON(p.productId=pb.productId) "
                    . " JOIN `brand` AS b ON(pb.brandId=b.brandId) WHERE p.productId='".$id."' ";
            //die($sql);
            if($app)
                return $this->db->query($sql)->result_array();
            else
                return $this->db->query($sql)->result();
	}
	
	public function products_for_discount($app=false){
            $sql="SELECT p.productId,p.Title,pc.CountryID "
                    . " FROM product AS p JOIN `product_country` AS pc ON(pc.productId=p.productId) WHERE p.Status=1";
            if($app)
                return $this->db->query($sql)->result_array();
            else    
                return $this->db->query($sql)->result();
	}	
	
	public function add_product_discount($dataArr){
            $sql="DELETE FROM `product_discount` WHERE productId IN(".$dataArr['productId'].")";
            $this->db->query($sql);

            $this->db->insert($this->_table_discount,$dataArr);
            return $this->db->insert_id();
	}
	
	public function add_category_discount($dataArr){
		$productIdArr=$this->db->select('productId')->from($this->_table)->where('categoryId',$dataArr["categoryId"])->where('Status','1')->get()->result();
		if(count($productIdArr)>0){
			$newArr=array();$batchDataArr=array();
			foreach($productIdArr as $k){
				//print_r($k);die;
				$newArr[]=$k->productId;
				$batchDataArr[]=array('productId'=>$k->productId,'DiscountID'=>$dataArr["DiscountID"]);
			}
			
			$this->db->where_in('productId',$newArr);
			$this->db->delete($this->_table_discount);
			
			$this->db->insert_batch($this->_table_discount, $batchDataArr); 
		}
		return TRUE;
	}
	
	public function manage_tmp_cart(){
		$countryShortName=$this->session->userdata('USER_SHIPPING_COUNTRY_NAME');
		
		$sql="DELETE FROM ".$this->_tmp_cart." WHERE  IP='".$this->input->ip_address()."'";
		$this->db->query($sql);
		//echo $this->db->last_query();die;
		
		$sql="DELETE FROM ".$this->_tmp_shipping."  WHERE IP='".$this->input->ip_address()."'";
		$this->db->query($sql);
		$this->session->unset_userdata('ShippingSelected');
		$this->session->set_userdata('TotalItemInCart',0);
		$this->session->unset_userdata('IsShipping');
		$this->session->unset_userdata('ShippingID');
		
		$this->session->unset_userdata('OrderDetails');
		$this->session->unset_userdata('OrderID');
		$this->session->unset_userdata('PaymentThanksPageOrderID');
		
		return TRUE;
	}
	
	public function manage_tmp_cart1(){
		$countryShortName=$this->session->userdata('USER_SHIPPING_COUNTRY_NAME');
		
		$sql="DELETE FROM ".$this->_tmp_cart." WHERE PurchaseLocation <> '".$countryShortName."' AND IP='".$this->input->ip_address()."'";
		$this->db->query($sql);
		//echo $this->db->last_query();die;
		
		$sql="DELETE FROM ".$this->_tmp_shipping." WHERE PurchaseLocation <> '".$countryShortName."' AND IP='".$this->input->ip_address()."'";
		$this->db->query($sql);
		return TRUE;
	}
	
	public function copy_tag($productId,$NewCopyproductId){
		$OldTagArr=$this->db->select('*')->from($this->_table_tag)->where('productId',$productId)->get()->result();
		$BatchArr=array();
		$TmpArr=array();
		foreach($OldTagArr AS $k){
			$TmpArr=array('productId'=>$NewCopyproductId,'tagId'=>$k->tagId);	
			$BatchArr[]=$TmpArr;
		}
		$this->db->insert_batch($this->_table_tag,$BatchArr);
		//echo $this->db->last_query();die;
		return TRUE;
	}
	
        public function get_all_tag(){
            return $this->db->select('*')->from($this->_tag)->order_by("tagId", "desc")->get()->result();
        }
        
        public function update_tag_state($tagId,$DataArr){
		$this->db->where_in('tagId',explode(',',$tagId));
		$this->db->update($this->_tag,$DataArr);
		return TRUE;
	}
        
        public function get_top_category($categoryId){
            //echo '$categoryId = '.$categoryId;
            $sql="SELECT CategoryName,ParrentcategoryId,categoryId "
                    . " FROM `category` WHERE `categoryId`=(select `ParrentcategoryId` from category where `categoryId`=$categoryId)";
            //echo $sql;die;
                $rs=$this->db->query($sql)->result();
            //echo '<pre>';print_r($rs);//
            if($rs[0]->ParrentcategoryId>0){
                $sql1="SELECT CategoryName,ParrentcategoryId FROM `category` WHERE `categoryId`='".$rs[0]->ParrentcategoryId."'";
                //echo $sql1; //die; 
                $rs1=$this->db->query($sql1)->result();
               // echo '<pre>';print_r($rs1);die;
                return strtolower($rs1[0]->CategoryName);
            }else{
                return strtolower($rs[0]->CategoryName);
            }       
        }
        
    function is_exist_deal_for_category($TopcategoryId,$RegionID){
        $rs=$this->db->from($this->_table_deal)->where('RegionID',$RegionID)->where('categoryId',$TopcategoryId)->get()->result();
        if(count($rs)>0)
            return TRUE;
        else
            return FALSE;
    }
        
    public function get_recent($noOfItem=12,$app=false,$latitude="",$longitude=""){
        if($app==TRUE){
            $this->_currentUserCountryCode=get_counry_code_from_lat_long($latitude,$longitude);
        }
        $sql="SELECT p.productId,p.title,p.lowestPrice,p.heighestPrice,p.qty,p.minQty,pi.image,c.categoryName "
                . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                . " JOIN product_category AS pc ON(pc.productId=p.productId) JOIN category AS c ON(pc.categoryId=c.categoryId) "
                . " JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId) "
                . " JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId) "
                . " WHERE co.countryCode='".$this->_currentUserCountryCode."' AND p.status=1 AND p.isNew = 1 AND c.status=1 GROUP BY pi.productId ORDER BY p.productId DESC,p.updateTime DESC LIMIT 0,$noOfItem";
        if($app==TRUE)
            return $this->db->query($sql)->result_array();
        else
            return $this->db->query($sql)->result();
    }

    public function get_best_selling($noOfItem=12,$app=false,$latitude="",$longitude=""){
        if($app==TRUE){
            $this->_currentUserCountryCode=get_counry_code_from_lat_long($latitude,$longitude);
        }
        $sql="SELECT p.productId,p.title,p.lowestPrice,p.heighestPrice,p.qty,p.minQty,pi.image,c.categoryName "
            . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
            . " JOIN product_category AS pc ON(pc.productId=p.productId) JOIN category AS c ON(pc.categoryId=c.categoryId)  "
            . " JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId) "
            . " JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId) "
            . " WHERE co.countryCode='".$this->_currentUserCountryCode."' AND p.status=1 AND p.popular = 1 AND c.status=1 GROUP BY pi.productId ORDER BY p.productId DESC,p.updateTime DESC LIMIT 0,$noOfItem";
        if($app==TRUE)
            return $this->db->query($sql)->result_array();
        else
            return $this->db->query($sql)->result();
    }

    public function get_featured_products($noOfItem=12,$app=false,$latitude="",$longitude=""){
        if($app==TRUE){
            $this->_currentUserCountryCode=get_counry_code_from_lat_long($latitude,$longitude);
        }
        $sql="SELECT p.productId,p.title,p.lowestPrice,p.heighestPrice,p.qty,p.minQty,pi.image,c.categoryName "
            . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
            . " JOIN product_category AS pc ON(pc.productId=p.productId) JOIN category AS c ON(pc.categoryId=c.categoryId)  "
            . " JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId) "
            . " JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId) "    
            . " WHERE co.countryCode='".$this->_currentUserCountryCode."' AND p.status=1 AND p.featured = 1 AND c.status=1 GROUP BY pi.productId ORDER BY p.productId DESC,p.updateTime DESC LIMIT 0,$noOfItem";
        if($app==TRUE)
            return $this->db->query($sql)->result_array();
        else
            return $this->db->query($sql)->result();
    }
    
    function get_page_template($app=false){
        if($app)
            return $this->db->from($this->_table_template)->get()->result_array();
        else    
            return $this->db->from($this->_table_template)->get()->result();
    }
    
    function get_products_price($produtcId,$app=false){
        if($app)
            return $this->db->from($this->_table_price)->where('productId',$produtcId)->order_by('qty','asc')->get()->result_array();
        else    
            return $this->db->from($this->_table_price)->where('productId',$produtcId)->order_by('qty','asc')->get()->result();
    }
    
    function get_products_price_details_by_id($productPriceId,$app=false){
        if($app)
            $data = $this->db->from($this->_table_price)->where('productPriceId',$productPriceId)->get()->result_array();
        else    
            $data = $this->db->from($this->_table_price)->where('productPriceId',$productPriceId)->get()->result();
        
        if(empty($data))
            return array();
        else
            return $data[0];
    }
    
    function update_view($productId){
        $rs=  $this->db->get_where($this->_table_views,array('productId'=>$productId))->result();
        if(count($rs)>0){
            $this->db->where('productId',$productId);
            $this->db->update($this->_table_views,array('noOfViews'=>(int)$rs[0]->noOfViews+1));
        }else{
            $this->db->insert($this->_table_views,array('noOfViews'=>1,'productId'=>$productId));
        }
        return TRUE;
    }
    
    function get_views_times_by_seller($app=false){
        $this->db->select('p.title,pv.*')->from($this->_table.' p')->join($this->_table_seller.' ps','p.productId=ps.productId');
        $this->db->join($this->_table_views.' pv','p.productId=pv.productId','left')->where('ps.userId',  $this->session->userdata('FE_SESSION_VAR'));
        if($app)
            return $this->db->get()->result_array();
        else    
        return $this->db->get()->result();
    }
    
    function update_product_quantity($productId,$qty,$action='-'){
        $this->db->query("UPDATE `product` SET `qty`=`qty`$action".$qty." WHERE `productId`=".$productId);
    }
    
    function edit_product_tag($dataArr){
        $productId=$dataArr["productId"];
        if(!array_key_exists('tagStr', $dataArr)){return FALSE;}
        $tagArr=explode(',',$dataArr["tagStr"]);
        if(empty($tagArr)){return FALSE;}
        $tagDataArr=array();
        foreach($tagArr AS $k){
            if($this->is_product_tag_added($productId,$k)==FALSE){
                $tagDataArr[]=array('productId'=>$productId,'tag'=>$k);
            }
        }
        if(!empty($tagDataArr))
            $this->db->insert_batch($this->_table_tag,$tagDataArr);
        return true;
    }
    
    function get_tax_for_current_location($productId,$taxCol){
        return $this->db->select('c.'.$taxCol)->from('category c')->join($this->_table_category.' pc','pc.categoryId=c.categoryId')->join($this->_table.' p','p.productId=pc.productId')->where('p.productId',$productId)->get()->row();
    }
    
    public function get_new_product($latitude,$longitude){
        $this->_currentUserCountryCode=get_counry_code_from_lat_long($latitude,$longitude);
        $sql="SELECT p.productId,p.title,p.lowestPrice,p.heighestPrice,p.qty,p.minQty,pi.image,c.categoryName "
                . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                . " JOIN product_category AS pc ON(pc.productId=p.productId) JOIN category AS c ON(pc.categoryId=c.categoryId) "
                . " JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId) "
                . " JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId) "
                . " WHERE co.countryCode='".$this->_currentUserCountryCode."' AND p.status=1 AND p.isNew = 1 AND c.status=1 GROUP BY pi.productId ORDER BY p.productId DESC,p.updateTime DESC";
        return $this->db->query($sql)->result_array();
    }
    
    public function get_all_best_selling_product($latitude="",$longitude=""){
        $this->_currentUserCountryCode=get_counry_code_from_lat_long($latitude,$longitude);
        $sql="SELECT p.productId,p.title,p.lowestPrice,p.heighestPrice,p.qty,p.minQty,pi.image,c.categoryName "
            . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
            . " JOIN product_category AS pc ON(pc.productId=p.productId) JOIN category AS c ON(pc.categoryId=c.categoryId)  "
            . " JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId) "
            . " JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId) "
            . " WHERE co.countryCode='".$this->_currentUserCountryCode."' AND p.status=1 AND p.popular = 1 AND c.status=1 GROUP BY pi.productId ORDER BY p.productId DESC,p.updateTime DESC";
        return $this->db->query($sql)->result_array();
    }

    public function get_all_featured_product($latitude="",$longitude=""){
        $this->_currentUserCountryCode=get_counry_code_from_lat_long($latitude,$longitude);
        
        $sql="SELECT p.productId,p.title,p.lowestPrice,p.heighestPrice,p.qty,p.minQty,pi.image,c.categoryName "
            . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
            . " JOIN product_category AS pc ON(pc.productId=p.productId) JOIN category AS c ON(pc.categoryId=c.categoryId)  "
            . " JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId) "
            . " JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId) "    
            . " WHERE co.countryCode='".$this->_currentUserCountryCode."' AND p.status=1 AND p.featured = 1 AND c.status=1 GROUP BY pi.productId ORDER BY p.productId DESC,p.updateTime DESC";
        return $this->db->query($sql)->result_array();
    }
    
    
    public function admin_list($per_page,$offcet=0){
        $ProductName=$this->input->post('FilterProductName',TRUE);
        $FilterProductStatus=$this->input->post('FilterProductStatus',TRUE);
        
        $this->db->select('p.*,pi.image,c.categoryName,u.email AS sellerEmail,u.firstName AS sellerFirstName,u.lastName AS sellerLastName,ba.city,co.countryName');
        $this->db->from('product p');
        $this->db->join('product_image pi','p.productId=pi.productId');
        $this->db->join('product_seller ps','p.productId=ps.productId');
        $this->db->join('user u','ps.userId=u.userId');
        $this->db->join('billing_address ba','ba.userId=u.userId');
        $this->db->join('country co','co.countryId=ba.countryId');
        $this->db->join('product_category pc','pc.productId=p.productId');
        $this->db->join('category c','pc.categoryId=c.categoryId', 'LEFT');
        //$this->db->where('c.status','1');

        if($FilterProductStatus==""){
            $this->db->where('p.status <','2');
        }else{
            $this->db->where('p.status',$FilterProductStatus);
        }

        /*if($Featured!=""){
            $this->db->where('p.Featured',$Featured);
        }*/

        
        if($ProductName!=""){
            $this->db->like('p.title',$this->db->escape_like_str($ProductName));
        }

        /*if($isNew!=""){
            $this->db->like('p.isNew',$isNew);
        }

        if($Deals!=""){
            $this->db->like('p.Deals',$Deals);
        }

        if($Popular!=""){
            $this->db->like('p.Popular',$Popular);
        }

        if($productModel!=""){
            $this->db->like('p.model',$productModel);
        }*/
        $this->db->group_by('p.productId');
        $this->db->order_by('p.productId','DESC');
        //$this->db->order_by('p.DealPriceUpdateTime','DESC');

        if($per_page>0){
            $this->db->limit($per_page,$offcet);
        }    
        $dataArr= $this->db->get()->result();
        //echo '<br />'.$this->db->last_query(); die;
        return $dataArr;
        
        /*$sql='SELECT p.*,u.email AS sellerEmail,u.firstName AS sellerFirstName,u.lastName AS sellerLastName '
                . ' FROM `product` AS p JOIN `product_seller` AS ps ON(p.productId=ps.productId) '
                . ' JOIN `user` AS u ON(u.userId=ps.userId) WHERE o.status >1 ';

        if($FilterProductStatus!=""):
            $sql.=" AND p.status=$FilterProductStatus ";
        endif;
        
        $sql .= 'ORDER BY p.productId DESC';
        $sql.=" LIMIT $offcet,$per_page";
        $arr=$this->db->query($sql)->result();
        //echo $this->db->last_query(); die;
        return $arr;*/
    }
    
    public function admin_list_total($per_page,$offcet=0){
        $ProductName=$this->input->post('FilterProductName',TRUE);
        $FilterProductStatus=$this->input->post('FilterProductStatus',TRUE);
        
        $this->db->select('p.*,pi.image,c.categoryName,u.email AS sellerEmail,u.firstName AS sellerFirstName,u.lastName AS sellerLastName,ba.city,co.countryName');
        $this->db->from('product p');
        $this->db->join('product_image pi','p.productId=pi.productId');
        $this->db->join('product_seller ps','p.productId=ps.productId');
        $this->db->join('user u','ps.userId=u.userId');
        $this->db->join('billing_address ba','ba.userId=u.userId');
        $this->db->join('country co','co.countryId=ba.countryId');
        $this->db->join('product_category pc','pc.productId=p.productId');
        $this->db->join('category c','pc.categoryId=c.categoryId', 'LEFT');
        //$this->db->where('c.status','1');

        if($FilterProductStatus==""){
            $this->db->where('p.status <','2');
        }else{
            $this->db->where('p.status',$FilterProductStatus);
        }

        /*if($Featured!=""){
            $this->db->where('p.Featured',$Featured);
        }*/

        
        if($ProductName!=""){
            $this->db->like('p.title',$this->db->escape_like_str($ProductName));
        }

        /*if($isNew!=""){
            $this->db->like('p.isNew',$isNew);
        }

        if($Deals!=""){
            $this->db->like('p.Deals',$Deals);
        }

        if($Popular!=""){
            $this->db->like('p.Popular',$Popular);
        }

        if($productModel!=""){
            $this->db->like('p.model',$productModel);
        }*/
        $this->db->group_by('p.productId');
        $this->db->order_by('p.productId','DESC');
        //$this->db->order_by('p.DealPriceUpdateTime','DESC');

        if($per_page>0){
            $this->db->limit($per_page,$offcet);
        }    
        $dataArr= $this->db->get()->result();
        //echo '<br />'.$this->db->last_query(); die;
        return count($dataArr);
    }
}
