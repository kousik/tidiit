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
        private $_table_brand="product_deal";
                
	function __construct() {
		$this->_SiteSession=$this->session->userdata('USER_SITE_SESSION_ID');
	}
	
	public function get_all_admin($PerPage=0,$PageNo=0){
            //echo '<pre>'.print_r($_POST);die;
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
                $this->db->join('product_category pc','pc.productId=p.productId');
                $this->db->join('category c','pc.categoryId=c.categoryId');
                $this->db->where('c.status','1');
                
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
                $this->db->join('product_country pc','p.productId=pc.productId');
                $this->db->join('category c','p.categoryId=c.categoryId');
                $this->db->join('country co','pc.CountryID=co.CountryID');
                $this->db->where('c.Status','1');
                
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
        
        function add_product_category($dataArr){
            $rs=$this->db->from($this->_table_category)->where('categoryId',$dataArr['categoryId'])->where('productId',$dataArr['productId'])->get()->result();
            if(count($rs)==0){
                $this->db->insert($this->_table_category,$dataArr);
                return $this->db->insert_id();
            }else{
                return FALSE;
            }
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
		$this->db->delete($this->_table_b); 
		return TRUE;
	}
	
	public function add_image($dataArr){
		$this->db->insert_batch($this->_table_image,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit_product_image($dataArr,$productId){
		$this->db->where('productId',$productId);
		$this->db->update($this->_table_image,$dataArr);
		//echo $this->db->last_query();die;
		return TRUE;
	}
	
	public function get_products_images($productId){
		$this->db->select('*')->from($this->_table_image)->where_in('productId',explode(',',$productId));
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
	
	public function add_country($dataArr){
		$this->db->insert($this->_table_country,$dataArr);
		return $this->db->insert_id();	
		
		/*if($dataArr["CountryID"]=='240'){
			$CountryDataArr=$this->db->select('CountryID')->from($this->_country)->get()->result();
			$BatchDataArr=array();
			foreach($CountryDataArr AS $k){
				$TmpArr=array();
				$TmpArr=array('productId'=>$dataArr['productId'],'CountryID'=>$k->CountryID);
				$BatchDataArr[]=$TmpArr;
			}
			$this->db->insert_batch($this->_table_country, $BatchDataArr); 
			return TRUE;
		}else{
			
		}*/
		
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
			$tagId=0;
			$productTagArr=array();
			$tagDataArr=array();
			$tagDataArr=$this->get_tag_id_by_tag($k);
			if(count($tagDataArr)>0){
				$productTagArr=array('productId'=>$productId,'tagId'=>$tagDataArr[0]->tagId);
				$this->add_tag_product($productTagArr);
			}else{
				if($k!=""){
					$tagId=$this->add_tag($k);
					if($tagId>0){
						$productTagArr=array('productId'=>$productId,'tagId'=>$tagId);
						$this->add_tag_product($productTagArr);	
					}
				}
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
	public function edit_product_tag($dataArr){
		$productId=$dataArr["productId"];
		$tagArr=explode(',',$dataArr["tagStr"]);
                //echo '<pre>';print_r($tagArr);die;
		foreach($tagArr AS $k){
			$tagId=0;
			$productTagArr=array();
			$tagDataArr=array();
                        //echo $k;die;
			$tagDataArr=$this->get_tag_id_by_tag($k);
                        //echo '<pre>';print_r($tagDataArr);die;
			if(count($tagDataArr)>0){
				if($this->is_product_tag_exists($productId,$tagDataArr[0]->tagId)==FALSE){
					$productTagArr=array('productId'=>$productId,'tagId'=>$tagDataArr[0]->tagId);
					$this->add_tag_product($productTagArr);
				}
			}else{
				if($k!=""){
					$tagId=$this->add_tag($k);
					if($tagId>0){
						$productTagArr=array('productId'=>$productId,'tagId'=>$tagId);
						$this->add_tag_product($productTagArr);	
					}
				}
			}
		}
		return true;
	}
	
	public function is_product_tag_exists($productId,$tagId){
		$sql="SELECT * FROM product_tag WHERE `productId`='".$productId."' AND `tagId`='".$tagId."'";
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
	
	public function details($id){
		$sql="SELECT p.*,c.CountryName,c.CountryID,pi.Image,dis.Amount,pd.DiscountID,ca.isAddToCart "
                        . " FROM `product` AS p JOIN `product_country` AS pc ON(p.productId=pc.productId) "
                        . " JOIN product_image as pi ON(p.productID=pi.productId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN `country` AS c ON(pc.CountryID=c.CountryID) "
                        . " LEFT JOIN `category` AS ca ON(ca.categoryId=p.categoryId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) WHERE p.productId='".$id."' ";
		//die($sql);
		return $this->db->query($sql)->result();
	}
	
	public function get_tag_by_product_id($productId){
		$sql="SELECT t.name FROM tags as t JOIN product_tag as pt ON(pt.tagId=t.tagId) WHERE pt.productId='".$productId."'";
		return $this->db->query($sql)->result();
		
	}
	
	public function get_all_tag_for_landing(){
		return $this->db->select('*')->from($this->_tag)->where('Landing',1)->limit(12)->order_by("tagId", "desc")->get()->result();
	}
	
	public function get_all_tag_for_footer(){
            $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
            $sql="SELECT DISTINCT t.tagId,t.name FROM `tags` AS t "
                    . " JOIN `product_tag` AS pt ON(t.tagId=pt.tagId) "
                    . " JOIN `product_country` AS pc ON(pt.productId=pc.productId) "
                    . " WHERE pc.CountryID='".$CountryID."' AND t.Home=1 ORDER BY t.tagId DESC LIMIT 0,10";
		return $this->db->query($sql)->result();
	}
	
	public function get_home_popular(){
		$CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
		$sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId)  "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Popular='1' AND c.Status=1 AND p.Status='1' AND pc.CountryID='".$CountryID."' ORDER BY RAND() DESC LIMIT 0,5";
		return $this->db->query($sql)->result();
	}
        
        public function get_category_popular($categoryId){
		$CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
		$sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId)  "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Popular='1' AND c.Status=1 AND p.Status='1' AND pc.CountryID='".$CountryID."' AND c.categoryId=".$categoryId." ORDER BY RAND() DESC LIMIT 0,5";
		return $this->db->query($sql)->result();
	}
	
	
	public function get_home_featured(){
		$CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
		$sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart FROM product AS p "
                        . " JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Featured='1' AND c.Status=1 AND p.Status='1' AND pc.CountryID='".$CountryID."' ORDER BY p.productId DESC LIMIT 0,6";
		return $this->db->query($sql)->result();
	}
	
	
        
	public function get_recent_4_product(){
		$CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
		$sql="SELECT p.*,pi.Image,c.CategoryName,c.isAddToCart,dis.Amount "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId)  "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND p.isNew='1' ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC LIMIT 0,4";
		return $this->db->query($sql)->result();
	}
        
	public function get_product_by_category($categoryId,$per_page=0,$offcet=0,$productId=0,$level=3){
            $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
            $MinPrice=$this->input->post('min_price',TRUE);
            $MaxPrice=$this->input->post('max_price',TRUE);
            $PriceSortType=$this->input->post('price_sort_type',TRUE);
            $SearchProductTitle=$this->input->post('SearchProductTitle',TRUE);
            $CateLevel='categoryId'.$level;
            $sql="SELECT p.*,pi.Image,c.CategoryName,c.isAddToCart,c.Note,dis.Amount "
                    . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId)"
                    . "  JOIN product_country AS pc ON(pc.productId=p.productId) "
                    . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                    . " JOIN category AS c1 ON(p.`categoryId`=c1.categoryId) "
                    . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                    . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID)"
                    . "  WHERE p.Status=1 AND c.Status=1  AND c1.Status='1' AND p.`".$CateLevel."`='".$categoryId."' AND pc.CountryID='".$CountryID."'  ";
            if($SearchProductTitle!="")
                $sql .= " AND p.Title LIKE '%".$SearchProductTitle."%'";
            if($productId>0){
                $sql .=" AND p.productId !=".$productId;
            }

            if($MinPrice!="" && $MaxPrice!=""){
                $sql .= " AND p.Price BETWEEN $MinPrice AND $MaxPrice ";
            }


            if($PriceSortType=="")
                $sql .=" ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC ";
            else
                $sql .=" ORDER BY p.price $PriceSortType";

            
            $sql.=" LIMIT $offcet,$per_page";
            //echo $sql;die;
            return $this->db->query($sql)->result();
	}
        
        
        
        public function get_total_product_by_category($categoryId,$productId=0){
            $ci =& get_instance();
            $ci->load->model('Category_model');
            $rsCat=$ci->Category_model->get_parrent_data_by_id($categoryId);
            $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
            if($rsCat[0]->ParrentcategoryId==0){
                $sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " JOIN category AS c1 ON( c.ParrentcategoryId=c1.`categoryId` AND c.Status=1 ) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Status=1 AND c1.Status=1 AND c1.`categoryId`='".$categoryId."' AND pc.CountryID='".$CountryID."' ";
                if($productId>0){
                    $sql .=" AND p.productId !=".$productId;
                }
                
                $sql .=" ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC ";
                $num=$this->db->query($sql)->num_rows();
                
		$sql1="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Status=1 AND c.Status=1 AND p.categoryId='".$categoryId."' AND pc.CountryID='".$CountryID."' ";
                if($productId>0){
                    $sql1 .=" AND p.productId !=".$productId;
                }
                
                $sql1 .=" ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC ";
                $num1=$this->db->query($sql1)->num_rows();
                return $num+$num1;
            }else{
                $sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Status=1 AND c.Status=1 AND p.categoryId='".$categoryId."' AND pc.CountryID='".$CountryID."' ";
                if($productId>0){
                    $sql .=" AND p.productId !=".$productId;
                }
                
                $sql .=" ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC ";
                $num=$this->db->query($sql)->num_rows();
                return $num;
            }
	}
        
	
	public function products_for_discount(){
		$sql="SELECT p.productId,p.Title,pc.CountryID "
                        . " FROM product AS p JOIN `product_country` AS pc ON(pc.productId=p.productId) WHERE p.Status=1";
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
	
	
	public function show_country_wise($Title,$categoryId){
		//$sql="SELECT pd.* FROM `product` AS p JOIN `product_country` AS pd ON p.productId=pd.productId WHERE p.Title='".$Title."' AND pd.CountryID IN('1','240','99') ORDER BY pd.CountryID";
                $this->db->select('pd.*');
                $this->db->from('product p');
                $this->db->join('product_country pd','p.productId=pd.productId');
                $this->db->where('p.Title',$Title);
                $this->db->where('p.categoryId',$categoryId);
                $this->db->where_in('pd.CountryID',array(1,240,99));
                $this->db->order_by('pd.CountryID','ASC');
                
                //echo $this->db->last_query();die;
		return $this->db->get()->result();
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
	
	public function get_popular_store(){
		$CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
		$sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND c.PopularStore=1 ORDER BY p.productId DESC";
		return $this->db->query($sql)->result();
	}
	
	public function get_recent_deals(){
		$CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
		$sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND p.Deals=1 ORDER BY p.productId DESC";
		return $this->db->query($sql)->result();
	}
	
	public function get_recent_product($PerPage=0,$PageNo=0){
		$CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
		$sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND p.isNew='1' ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC";
                if($PerPage>0){
                    $sql.=" LIMIT $PageNo,$PerPage";
                }
		return $this->db->query($sql)->result();
	}
        
        public function get_recent_product_total(){
		$CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
		$sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND p.isNew='1' ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC ";
                
		$arr=$this->db->query($sql)->result();
                return count($arr);
	}
	
	public function get_featured(){
		$CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
		$sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                        . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                        . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                        . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                        . " WHERE p.Featured='1' AND c.Status=1 AND p.Status='1' AND pc.CountryID='".$CountryID."' ORDER BY p.productId";
		return $this->db->query($sql)->result();
	}
        
        public function update_cancel_quantity($productId,$Qty){
            $dataArr=  $this->db->select('Quantity')->from($this->_table)->where('productId',$productId)->get()->result();
            $UpdateArr=array('Quantity'=>($dataArr[0]->Quantity+$Qty));
            $this->edit($UpdateArr, $productId);
            return TRUE;
        }
        
        public function search($search_text,$categoryId){
            $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
            $sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                    . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                    . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                    . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                    . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                    . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                    . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."'";
            if($categoryId>0){
                $sql .= "AND p.categoryId='".$categoryId."' ";
            }
            
            $sql .= " AND p.Title LIKE('%".$this->db->escape_like_str($search_text)."%') ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC";
		return $this->db->query($sql)->result();
        }
        
        public function get_all_tag(){
            return $this->db->select('*')->from($this->_tag)->order_by("tagId", "desc")->get()->result();
        }
        
        public function get_product_list_by_tag_id($tagId){
            $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
            $sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                    . " FROM product_tag AS pt JOIN product AS p ON(pt.productId=p.productId) "
                    . " JOIN product_image AS pi ON(pi.productId=p.productId) "
                    . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                    . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                    . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                    . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                    . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND pt.tagId='".$tagId."' ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC";
		return $this->db->query($sql)->result();
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
        
        function get_product_image_for_download($productIds){
            $sql="SELECT pi.Image FROM `product` AS p JOIN `product_country` AS pc ON(p.productId=pc.productId) LEFT JOIN `product_image` AS pi 
                 ON(p.productId=pi.productId) WHERE p.productId IN($productIds)";
            return $this->db->query($sql)->result();
        }
        
        function get_deal_product_by_category($categoryId){
            $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
            if($categoryId>0){
                $sql="SELECT p.*,c.ParrentcategoryId,pi.Image,c.isAddToCart FROM product AS p JOIN category AS c ON(p.categoryId=c.categoryId ) "
                        . " JOIN product_image AS pi ON(pi.productId=p.productId) JOIN product_country AS pc ON(pc.productId=p.productId) "
                        . "  WHERE pc.CountryID='".$CountryID."' AND p.Status=1 AND p.Deals=1 ORDER BY p.DealPriceUpdateTime DESC LIMIT 0,1";
            }else{
                $sql="SELECT p.*,'TOPCat' AS ParrentcategoryId,pi.Image,c.isAddToCart FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                        . " JOIN product_country AS pc ON(pc.productId=p.productId) WHERE Status=1 AND Deals=1 ORDER BY DealPriceUpdateTime DESC LIMIT 0,1";
            }
            //echo $sql;die;
            return $this->db->query($sql)->result();
        }
        
        function get_products_by_category_only($categoryId,$PerPage=4,$PageNo=0){
            $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
            $sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                    . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                    . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                    . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                    . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                    . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                    . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND p.categoryId=$categoryId ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC";
            if($PerPage>0){
                $sql.=" LIMIT $PageNo,$PerPage";
            }
            return $this->db->query($sql)->result();
        }
        
        
        function get_products_by_all_category_only($categoryId,$PerPage=4,$PageNo=0){
            $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
            $sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                    . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                    . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                    . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                    . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                    . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                    . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND p.categoryId IN(SELECT c1.categoryId FROM `category` AS c1 where c1.ParrentcategoryId=$categoryId) ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC";
            if($PerPage>0){
                $sql.=" LIMIT $PageNo,$PerPage";
            }
            //echo $sql;die;
            return $this->db->query($sql)->result();
        }
        
    function set_deal($dataArr){
        $this->db->insert($this->_table_deal,$dataArr);
        return $this->db->insert_id();
    }
    
    function remove_deal($dataArr){
        $this->db->where_in('productId',$productId);
        $this->db->delete($this->_table_deal);
        return TRUE;
    }
    
    function edit_deal($productId,$categoryId,$RegionID){
        $this->db->where('RegionID',$RegionID);
        $this->db->where('categoryId',$categoryId);
        $this->db->update($this->_table_deal,array('productId'=>$productId));
        //echo $this->db->last_query();die;
        return TRUE;		
    }
    
    function get_deal($categoryId=0){
        $this->db->cache_off();
        $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
        if($categoryId>0){
            $sql="SELECT p.*,pi.Image"
                    . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                    . " JOIN product_deal AS pd ON(pd.productId=p.productId) "
                    . " JOIN category AS c ON(c.categoryId=p.categoryId) "
                    . " WHERE p.Status=1 AND pd.RegionID='".$CountryID."' AND pd.categoryId=$categoryId AND c.isAddToCart=1 ORDER BY rand() LIMIT 1";
        }else{
            $sql="SELECT p.*,pi.Image"
                    . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                    . " JOIN product_deal AS pd ON(pd.productId=p.productId) "
                    . " JOIN category AS c ON(c.categoryId=p.categoryId) "
                    . " WHERE p.Status=1 AND pd.RegionID='".$CountryID."' AND c.isAddToCart=1 ORDER BY p.DealPriceUpdateTime DESC LIMIT 0,5";
        }
        //echo $sql;die;
        return $this->db->query($sql)->result();
    }
    
    public function get_product_by_category_max_min($categoryId,$level=3){
        $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
        $CateLevel='categoryId'.$level;
        $sql="SELECT MAX(p.Price) AS MaxPrice,MIN(p.Price) AS MinPrice "
                . " FROM product AS p JOIN product_country AS pc ON(pc.productId=p.productId) "
                . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                . "  WHERE p.Status=1 AND c.Status=1 AND p.`".$CateLevel."`='".$categoryId."' AND pc.CountryID='".$CountryID."'  ";
        return $this->db->query($sql)->result();
    }
    
    function search_result($categoryId,$SearchProductTitle,$per_page){
        $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
        $sql="SELECT p.*,pi.Image,c.CategoryName,dis.Amount,c.isAddToCart "
                . " FROM product AS p JOIN product_image AS pi ON(pi.productId=p.productId) "
                . " JOIN product_country AS pc ON(pc.productId=p.productId) "
                . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                . " LEFT JOIN product_discount AS pd ON(p.productId=pd.productId) "
                . " LEFT JOIN discount AS dis ON(pd.DiscountID=dis.DiscountID) "
                . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND p.categoryId1=$categoryId AND p.Title LIKE '%".$SearchProductTitle."%' ORDER BY p.productId DESC,p.DealPriceUpdateTime DESC";
            $sql.=" LIMIT 0,$per_page";
        return $this->db->query($sql)->result();   
    }
    
    
    function get_max_min_for_search($categoryId,$SearchProductTitle){
        $CountryID=$this->session->userdata('USER_SHIPPING_COUNTRY');
        $sql="SELECT MAX(p.Price) AS MaxPrice,MIN(p.Price) AS MinPrice "
                . " FROM product AS p JOIN product_country AS pc ON(pc.productId=p.productId) "
                . " JOIN category AS c ON(p.categoryId=c.categoryId) "
                . " WHERE p.Status=1 AND c.Status=1 AND pc.CountryID='".$CountryID."' AND p.categoryId1=$categoryId AND p.Title LIKE '%".$SearchProductTitle."%' ";
        //echo $sql;die;
        return $this->db->query($sql)->result();   
    }
    
    function is_exist_deal_for_category($TopcategoryId,$RegionID){
        $rs=$this->db->from($this->_table_deal)->where('RegionID',$RegionID)->where('categoryId',$TopcategoryId)->get()->result();
        if(count($rs)>0)
            return TRUE;
        else
            return FALSE;
    }
}
