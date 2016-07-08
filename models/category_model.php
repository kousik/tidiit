<?php
class Category_model extends CI_Model {
	public $_table = 'category';
	public $_table_seo = 'seo_data';
	public $_table_template = 'category_view_page';
        private $_table_price = 'product_price';
        private $_tag = 'tags';
	private $_table_tag = 'product_tag';
        private $_product_seller = 'product_seller';
        private $_user = 'user';
        private $_currentUserCountryCode="";
                
	function __construct() {
		$this->_currentUserCountryCode=$this->session->userdata('FE_SESSION_USER_LOCATION_VAR');
	}
	
	public function get_all($parrentId){
		$this->db->select('*')->from($this->_table)->where('status <','2')->where('parrentCategoryId',$parrentId)->order_by('categoryId','DESC');
		return $this->db->get()->result();
	}
	
	public function add($dataArr){
		$this->db->insert($this->_table,$dataArr);
		return $this->db->insert_id();
	}
	
	public function edit($DataArr,$categoryId){
		$this->db->where('categoryId',$categoryId);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function get_id_by_name($categoryName){
		$this->db->select('categoryId')->from($this->_table)->like('categoryName',$categoryName);
		return $this->db->get()->result();
	}
	
	public function check_category_name_exists($categoryName){
		$dataArr=$this->db->select("categoryId")->from($this->_table)->where('categoryName',$categoryName)->get()->result();
                if(count($dataArr)>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function check_edit_category_name_exists($categoryName,$categoryId){
		$dataArr=$this->db->select("categoryId")->from($this->_table)->where('categoryName',$categoryName)->where('categoryId <>',$categoryId)->get()->result();
		if(count($dataArr)>0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function change_category_status($categoryId,$status){
            $this->db->where_in('categoryId',$categoryId);
            $this->db->update($this->_table,array('status'=>$status));
            //echo $this->db->last_query();die;
            return TRUE;
	}
	
	public function delete($categoryId){
            //pre($categoryId);die;
            $this->db->where_in('categoryId',$categoryId);
            $this->db->delete($this->_table); 
            //$this->db->delete($this->_table)->where('categoryId',$categoryId);; 
            return TRUE;
	}
	
	public function get_details_by_id($id){
            return $this->db->from($this->_table)->like('categoryId',$id)->get()->result();
	}
	
	public function get_subcategory_by_category_id($categoryId,$app=FALSE){
		$sql="SELECT `categoryId` , `categoryName`,`image` FROM category WHERE `parrentCategoryId` ='".$categoryId."' AND status=1";
                if($app==FALSE)
                    return $this->db->query($sql)->result();
                else
                    return $this->db->query($sql)->result_array();
	}
        
        public function get_reverse_subcategory_by_category_id($categoryId,$Action){
		$sql="SELECT `categoryId` , `categoryName` FROM category WHERE `parrentCategoryId` ='".$categoryId."' AND status=$Action";
                //echo $sql.'<br>';
		return $this->db->query($sql)->result();
	}
        
        public function get_without_status_subcategory_by_category_id($categoryId){
		$sql="SELECT `categoryId` , `categoryName` FROM category WHERE `parrentCategoryId` ='".$categoryId."' ";
                //echo $sql.'<br>';
		return $this->db->query($sql)->result();
	}
	
	public function get_parrent_by_category_id($categoryId){
		$sql="SELECT `categoryId` , `categoryName`,`parrentCategoryId`
FROM category
WHERE `parrentCategoryId` = (
SELECT `parrentCategoryId`
FROM category
WHERE categoryId ='".$categoryId."')";
		return $this->db->query($sql)->result();
	}
	
	public function get_top_category_for_product_list($app=false){
		$this->db->select('categoryId,categoryName,image')->from($this->_table)->where('parrentCategoryId','0')->where('status','1')->order_by('categoryName','ASC');
                if($app==FALSE)
                    $rs=$this->db->get()->result();
                else
                    $rs=$this->db->get()->result_array();
                //echo $this->db->last_query().'  ==  ';
                return $rs;
	}
	
	public function buttom_category_for_product_list(){
		$this->db->select('categoryId,categoryName,parrentCategoryId')->from($this->_table)->where('parrentCategoryId >','0')->where('status','1');
		$rs=$this->db->get()->result();
                //echo $this->db->last_query();die;
                return $rs;
	}
	
	
	public function get_parrent_data_by_id($CatID){
		$sql="SELECT `parrentCategoryId`,`categoryName`,categoryId,(SELECT categoryName FROM category WHERE `categoryId`='".$CatID."') AS ChildcategoryName
FROM `category`
WHERE categoryId = (
SELECT `parrentCategoryId`
FROM category AS c1
WHERE c1.categoryId ='".$CatID."') ";
            $rs=$this->db->query($sql)->result();
            //echo $this->db->last_query();
            return $rs;
	}
	
	public function category_of_product(){
		$sql="SELECT * FROM `category` WHERE `parrentCategoryId`>0 AND status=1";
		return $this->db->query($sql)->result();
	}
	
	
	
	public function getProductCategoryParrentCategoryInfo($CateoryID){
		$sql="SELECT c.`categoryId` AS ParrentID, c.`categoryName` AS ParrentName, c1.`categoryId` , c1.`categoryName`
FROM category AS c
JOIN `category` AS c1 ON ( c.categoryId = c1.parrentCategoryId )
WHERE c1.`categoryId` = '".$CateoryID."'";
		return $this->db->query($sql)->result();
	}
        

        function subcategory_has_product($categoryId){
            $sql="SELECT count(p.`productId`)AS Tot FROM "
                    . " `product` AS p JOIN `category` AS c on (p.categoryId=c.categoryId) "
                    . " JOIN `category` AS c1 ON(c1.categoryId=c.parrentCategoryId) "
                    . " WHERE c1.categoryId=".$categoryId;
            $rs=  $this->db->query($sql)->result();
            //echo 'subcategory_has_product $sql = '.$sql.'<br>';
            //echo 'subcategory_has_product = '.$rs[0]->Tot.'<br>';
            if($rs[0]->Tot>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }
        
        
        function get_all_parrent_details($categoryId){
            $sql="SELECT c.*, cParent1.categoryId AS firstParentcategoryId,cParent1.categoryName AS firstParentcategoryName, cParent2.categoryId AS secondParentcategoryId,cParent2.categoryName AS SecondParentcategoryName 
FROM category AS c
    LEFT JOIN category AS cParent1 ON c.parrentCategoryId = cParent1.categoryId
    LEFT JOIN category AS cParent2 ON cParent1.parrentCategoryId = cParent2.categoryId
WHERE c.categoryId =".$categoryId;
            return $this->db->query($sql)->result();
        }
        
    function get_page_template(){
        return $this->db->from($this->_table_template)->get()->result();
    }
    
    function has_chield($categroyId){
        $rs=$this->get_subcategory_by_category_id($categroyId);
        if(count($rs)>0){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    
    //======================Category-Products Display all main query=====================//
    /**
     * 
     * @param type $parent
     * @param type $level
     * @return type
     */
    public function display_children_categories($parent, $level = 0) { 
        $sql = "SELECT * FROM `{$this->_table}` WHERE parrentCategoryId = ".$parent." AND status = 1";
        $rs = $this->db->query($sql)->result();
        $newlevel = $level+1;
        $category = array();
        if($rs): 
            //if($newlevel != 0 && $newlevel < =):
            foreach($rs as $key => $cat):                
                if($pcats = $this->display_children_categories($cat->categoryId, $newlevel)):
                    $cat->parent = $pcats;
                endif;
                $category[] = $cat;
            endforeach;
        endif;
        return $category;
    } 
    
    /**
     * 
     * @return type
     */
    public function get_site_categories() { 
        $sql = "SELECT * FROM `{$this->_table}` WHERE parrentCategoryId = 0 AND status = 1";
        $rs = $this->db->query($sql)->result();
        $category = array();
        if($rs):            
            foreach($rs as $key => $cat):                
                if($pcats = $this->display_children_categories($cat->categoryId, 0)):
                    $cat->parent = $pcats;
                endif;
                $category[] = $cat;
            endforeach;
        endif;
        return $category;
    }
    
    /**
     * 
     * @param type $parent
     * @param type $level
     * @return array
     */
    public function get_children_categories_id($parent, $level = 0) { 
        $sql = "SELECT `categoryId` FROM `{$this->_table}` WHERE parrentCategoryId = ".$parent." AND status = 1";
        $rs = $this->db->query($sql)->result();
        $categoryid = array();
        if($rs):            
            foreach($rs as $key => $cat):   
                $categoryid[] = $cat->categoryId;
                if($pcats = $this->get_children_categories_id($cat->categoryId, $level+1)): 
                    $categoryid = array_merge($categoryid, $pcats);
                endif;                
            endforeach;
        endif;
        return $categoryid;
    }
    
    /**
     * 
     * @param type $categoryId
     * @param type $offset
     * @param type $limit
     * @param type $cond
     * @return type
     */
    public function get_children_categories_products($categoryId, $offset = null, $limit = null, $cond) { 
        
        $pcatsId = $this->get_children_categories_id($categoryId);
        $pcatsId[] = $categoryId;
        $pcatsId = implode("','", $pcatsId);        
        $group_by = ' GROUP BY p.productId';
        $join_query = "";
        
        $sort = array('featured','isNew','popular','lowestPrice');
        $order_by = 'p.popular';
        if(isset($cond['order_by']) && $cond['order_by'] && in_array($cond['order_by'], $sort)):
            $order_by = '  ORDER BY '.$cond['order_by'];
            unset($cond['order_by']);
        else:
            $order_by = '  ORDER BY p.popular';
        endif;
        
        $order_sort = ' ASC';
        
        if(isset($cond['order_sort']) && $cond['order_sort']):
            $order_sort = ' '.$cond['order_sort'];
            unset($cond['order_sort']);
        endif;
        
        $plimit = '';
        if($offset >= 0 && $limit):
            $plimit = ' LIMIT '.$offset.', '.$limit;
        endif;
        
        $where_str = 'p.status = 1 ';
        
        if($pcatsId):
            $where_str = $where_str." AND c.categoryId IN ('".$pcatsId."')";
        endif;
        
        
        if(isset($cond['brand']) && $cond['brand']):
            $brands = implode('","', $cond['brand']);        
            $where_str = $where_str.' AND b.title IN ("'.$brands.'")';
        endif;
        
        if(isset($cond['range']) && $cond['range']):
            $lowestPrice = $cond['range'][0];
            $heighestPrice = $cond['range'][1];
            $where_str = $where_str.' AND p.lowestPrice >= '.$lowestPrice.' AND p.lowestPrice <= '.$heighestPrice;
        endif;

        if(isset($cond['query']) && $cond['query']):
            $join_query .= " LEFT JOIN product_option_values AS popvalue ON popvalue.productId = p.productId ";
            $qdata = [];
            foreach($cond['query'] as $qry):
                $exq = explode("@", $qry);
                $qdata[$exq[0]][] = $exq[1];
            endforeach;

            $q_string = " ";
            $qn = count($qdata);
            $i = 0;
            foreach($qdata as $qkey => $qopval):
                $opval = implode("','", $qopval);
                $q_string .= " popvalue.option_id = '".$qkey."' AND value IN ('".$opval."')";
                $i++;
                if($i < $qn):
                    $q_string .= " OR ";
                endif;
            endforeach;
            $where_str = $where_str.' AND ('.$q_string.') ';
        endif;
        
        $sql = "SELECT `p`.*, `b`.`title` AS `btitle`, `c`.`categoryName`, 
            `c`.`image` AS `catImage`,`pimage`.`image` AS `pImage` 
            FROM `product` AS p
            JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId)
            JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId)
            LEFT JOIN product_brand AS pb ON p.productId = pb.productId
            LEFT JOIN brand AS b ON pb.brandId = b.brandId
            LEFT JOIN product_category AS pc ON pc.productId = p.productId
            LEFT JOIN category AS c ON c.categoryId = pc.categoryId
            LEFT JOIN product_image AS pimage ON pimage.productId = p.productId
            {$join_query}
            WHERE co.countryCode='".$this->_currentUserCountryCode."' AND {$where_str} {$group_by} {$order_by} {$order_sort} {$plimit}";
        $rs = $this->db->query($sql)->result();//echo $this->db->last_query();print_r($rs);
        $products = array();
        $brands = array();
        if($rs):            
            foreach($rs as $key => $product):  
                //$product->tags = $this->get_product_tags($product->productId);
                //$product->seller = $this->get_product_seller($product->productId);
               // $product->product_price = $this->get_products_price($product->productId);
                //$product->curent_category = $this->get_details_by_id($categoryId);
                $products['products'][] =  $product;  
                $brands[$product->btitle] = $product->btitle;
            endforeach;
        endif;
        $products['brands'] = $brands;
        return $products;
    }

    
    /**
     * 
     * @param type $categoryId
     * @param type $offset
     * @param type $limit
     * @param type $cond
     * @return type
     */
    public function get_children_categories_products_app($categoryId, $offset = null, $limit = null, $cond,$latitude,$longitude) { 
        
        $this->_currentUserCountryCode=get_counry_code_from_lat_long($latitude,$longitude);
        
        $pcatsId = $this->get_children_categories_id($categoryId);
        $pcatsId[] = $categoryId;
        $pcatsId = implode("','", $pcatsId);        
        $group_by = ' GROUP BY p.productId';
        $join_query = "";
        
        $sort = array('featured','isNew','popular','lowestPrice');
        $order_by = 'p.popular';
        if(isset($cond['order_by']) && $cond['order_by'] && in_array($cond['order_by'], $sort)):
            $order_by = '  ORDER BY '.$cond['order_by'];
            unset($cond['order_by']);
        else:
            $order_by = '  ORDER BY p.popular';
        endif;
        
        $order_sort = ' ASC';
        
        if(isset($cond['order_sort']) && $cond['order_sort']):
            $order_sort = ' '.$cond['order_sort'];
            unset($cond['order_sort']);
        endif;
        
        $plimit = '';
        if($offset >= 0 && $limit):
            $plimit = ' LIMIT '.$offset.', '.$limit;
        endif;
        
        $where_str = 'p.status = 1 ';
        
        if($pcatsId):
            $where_str = $where_str." AND c.categoryId IN ('".$pcatsId."')";
        endif;
        
        
        if(isset($cond['brand']) && $cond['brand']):
            $brands = implode('","', $cond['brand']);        
            $where_str = $where_str.' AND b.title IN ("'.$brands.'")';
        endif;
        
        if(isset($cond['range']) && $cond['range']):
            $lowestPrice = $cond['range'][0];
            $heighestPrice = $cond['range'][1];
            $where_str = $where_str.' AND p.lowestPrice >= '.$lowestPrice.' AND p.lowestPrice <= '.$heighestPrice;
        endif;

        if(isset($cond['query']) && $cond['query']):
            $join_query .= " LEFT JOIN product_option_values AS popvalue ON popvalue.productId = p.productId ";
            $qdata = [];
            foreach($cond['query'] as $qry):
                $exq = explode("@", $qry);
                $qdata[$exq[0]][] = $exq[1];
            endforeach;

            $q_string = " ";
            $qn = count($qdata);
            $i = 0;
            foreach($qdata as $qkey => $qopval):
                $opval = implode("','", $qopval);
                $q_string .= " popvalue.option_id = '".$qkey."' AND value IN ('".$opval."')";
                $i++;
                if($i < $qn):
                    $q_string .= " OR ";
                endif;
            endforeach;
            $where_str = $where_str.' AND ('.$q_string.') ';
        endif;
        
        $sql = "SELECT `p`.*, `b`.`title` AS `btitle`, `c`.`categoryName`, 
            `c`.`image` AS `catImage`,`pimage`.`image` AS `pImage` 
            FROM `product` AS p
            JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId)
            JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId)
            LEFT JOIN product_brand AS pb ON p.productId = pb.productId
            LEFT JOIN brand AS b ON pb.brandId = b.brandId
            LEFT JOIN product_category AS pc ON pc.productId = p.productId
            LEFT JOIN category AS c ON c.categoryId = pc.categoryId
            LEFT JOIN product_image AS pimage ON pimage.productId = p.productId
            {$join_query}
            WHERE co.countryCode='".$this->_currentUserCountryCode."' AND {$where_str} {$group_by} {$order_by} {$order_sort} {$plimit}";
        $rs = $this->db->query($sql)->result();//echo $this->db->last_query();print_r($rs);
        $products = array();
        $brands = array();
        if($rs):            
            foreach($rs as $key => $product):  
                //$product->tags = $this->get_product_tags($product->productId);
                //$product->seller = $this->get_product_seller($product->productId);
               // $product->product_price = $this->get_products_price($product->productId);
                //$product->curent_category = $this->get_details_by_id($categoryId);
                $products['products'][] =  $product;  
                $brands[$product->btitle] = $product->btitle;
            endforeach;
        endif;
        $products['brands'] = $brands;
        return $products;
    }


    /**
     *
     * @param type $categoryId
     * @param type $offset
     * @param type $limit
     * @param type $cond
     * @return type
     */
    public function get_brand_products($brandId, $offset = null, $limit = null, $cond) {

        $group_by = ' GROUP BY p.productId';
        $join_query = "";

        $sort = array('featured','isNew','popular','lowestPrice');
        $order_by = 'p.popular';
        if(isset($cond['order_by']) && $cond['order_by'] && in_array($cond['order_by'], $sort)):
            $order_by = '  ORDER BY '.$cond['order_by'];
            unset($cond['order_by']);
        else:
            $order_by = '  ORDER BY p.popular';
        endif;

        $order_sort = ' ASC';

        if(isset($cond['order_sort']) && $cond['order_sort']):
            $order_sort = ' '.$cond['order_sort'];
            unset($cond['order_sort']);
        endif;

        $plimit = '';
        if($offset >= 0 && $limit):
            $plimit = ' LIMIT '.$offset.', '.$limit;
        endif;

        $where_str = 'p.status = 1 ';


        if(isset($cond['brand']) && $cond['brand']):
            $brands = implode('","', $cond['brand']);
            $where_str = $where_str.' AND b.title IN ("'.$brands.'")';
        else:
            $where_str = $where_str.' AND b.brandId  IS NOT NULL';
        endif;

        if(isset($cond['range']) && $cond['range']):
            $lowestPrice = $cond['range'][0];
            $heighestPrice = $cond['range'][1];
            $where_str = $where_str.' AND p.lowestPrice >= '.$lowestPrice.' AND p.lowestPrice <= '.$heighestPrice;
        endif;

        if(isset($cond['query']) && $cond['query']):
            $join_query .= " LEFT JOIN product_option_values AS popvalue ON popvalue.productId = p.productId ";
            $qdata = [];
            foreach($cond['query'] as $qry):
                $exq = explode("@", $qry);
                $qdata[$exq[0]][] = $exq[1];
            endforeach;

            $q_string = " ";
            $qn = count($qdata);
            $i = 0;
            foreach($qdata as $qkey => $qopval):
                $opval = implode("','", $qopval);
                $q_string .= " popvalue.option_id = '".$qkey."' AND value IN ('".$opval."')";
                $i++;
                if($i < $qn):
                    $q_string .= " OR ";
                endif;
            endforeach;
            $where_str = $where_str.' AND ('.$q_string.') ';
        endif;
        
        
        $sql = "SELECT `p`.*, `b`.`title` AS `btitle`, `c`.`categoryName`,
            `c`.`image` AS `catImage`,`pimage`.`image` AS `pImage`
            FROM `product` AS p
            JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId)
            JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId)
            LEFT JOIN product_brand AS pb ON p.productId = pb.productId
            LEFT JOIN brand AS b ON pb.brandId = b.brandId
            LEFT JOIN product_category AS pc ON pc.productId = p.productId
            LEFT JOIN category AS c ON c.categoryId = pc.categoryId
            LEFT JOIN product_image AS pimage ON pimage.productId = p.productId
            {$join_query}
            WHERE co.countryCode='".$this->_currentUserCountryCode."' AND {$where_str} {$group_by} {$order_by} {$order_sort} {$plimit}";
        $rs = $this->db->query($sql)->result();//echo $this->db->last_query();print_r($rs);
        $products = array();
        $brands = array();
        $pids = [];
        if($rs):
            foreach($rs as $key => $product):
                //$product->tags = $this->get_product_tags($product->productId);
                //$product->seller = $this->get_product_seller($product->productId);
                // $product->product_price = $this->get_products_price($product->productId);
                //$product->curent_category = $this->get_details_by_id($categoryId);
                $products['products'][] =  $product;
                $brands[$product->btitle] = $product->btitle;
                $pids[] = $product->productId;
            endforeach;
        endif;
        $options = $this->get_products_common_options($pids);
        if($options):
            $products['options'] = $options;
        else:
            $products['options'] = [];
        endif;
        $products['brands'] = $brands;
        return $products;
    }

    
    /**
     *
     * @param type $categoryId
     * @param type $offset
     * @param type $limit
     * @param type $cond
     * @return type
     */
    public function get_brand_products_app($brandId, $offset = null, $limit = null, $cond,$latitude,$longitude) {
        $this->_currentUserCountryCode=get_counry_code_from_lat_long($latitude,$longitude);
        $group_by = ' GROUP BY p.productId';
        $join_query = "";

        $sort = array('featured','isNew','popular','lowestPrice');
        $order_by = 'p.popular';
        if(isset($cond['order_by']) && $cond['order_by'] && in_array($cond['order_by'], $sort)):
            $order_by = '  ORDER BY '.$cond['order_by'];
            unset($cond['order_by']);
        else:
            $order_by = '  ORDER BY p.popular';
        endif;

        $order_sort = ' ASC';

        if(isset($cond['order_sort']) && $cond['order_sort']):
            $order_sort = ' '.$cond['order_sort'];
            unset($cond['order_sort']);
        endif;

        $plimit = '';
        if($offset >= 0 && $limit):
            $plimit = ' LIMIT '.$offset.', '.$limit;
        endif;

        $where_str = 'p.status = 1 ';


        if(isset($cond['brand']) && $cond['brand']):
            $brands = implode('","', $cond['brand']);
            $where_str = $where_str.' AND b.title IN ("'.$brands.'")';
        else:
            $where_str = $where_str.' AND b.brandId  IS NOT NULL';
        endif;

        if(isset($cond['range']) && $cond['range']):
            $lowestPrice = $cond['range'][0];
            $heighestPrice = $cond['range'][1];
            $where_str = $where_str.' AND p.lowestPrice >= '.$lowestPrice.' AND p.lowestPrice <= '.$heighestPrice;
        endif;

        if(isset($cond['query']) && $cond['query']):
            $join_query .= " LEFT JOIN product_option_values AS popvalue ON popvalue.productId = p.productId ";
            $qdata = [];
            foreach($cond['query'] as $qry):
                $exq = explode("@", $qry);
                $qdata[$exq[0]][] = $exq[1];
            endforeach;

            $q_string = " ";
            $qn = count($qdata);
            $i = 0;
            foreach($qdata as $qkey => $qopval):
                $opval = implode("','", $qopval);
                $q_string .= " popvalue.option_id = '".$qkey."' AND value IN ('".$opval."')";
                $i++;
                if($i < $qn):
                    $q_string .= " OR ";
                endif;
            endforeach;
            $where_str = $where_str.' AND ('.$q_string.') ';
        endif;
        
        
        $sql = "SELECT `p`.*, `b`.`title` AS `btitle`, `c`.`categoryName`,
            `c`.`image` AS `catImage`,`pimage`.`image` AS `pImage`
            FROM `product` AS p
            JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId)
            JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId)
            LEFT JOIN product_brand AS pb ON p.productId = pb.productId
            LEFT JOIN brand AS b ON pb.brandId = b.brandId
            LEFT JOIN product_category AS pc ON pc.productId = p.productId
            LEFT JOIN category AS c ON c.categoryId = pc.categoryId
            LEFT JOIN product_image AS pimage ON pimage.productId = p.productId
            {$join_query}
            WHERE co.countryCode='".$this->_currentUserCountryCode."' AND {$where_str} {$group_by} {$order_by} {$order_sort} {$plimit}";
        $rs = $this->db->query($sql)->result();//echo $this->db->last_query();print_r($rs);
        $products = array();
        $brands = array();
        $pids = [];
        if($rs):
            foreach($rs as $key => $product):
                //$product->tags = $this->get_product_tags($product->productId);
                //$product->seller = $this->get_product_seller($product->productId);
                // $product->product_price = $this->get_products_price($product->productId);
                //$product->curent_category = $this->get_details_by_id($categoryId);
                $products['products'][] =  $product;
                $brands[$product->btitle] = $product->btitle;
                $pids[] = $product->productId;
            endforeach;
        endif;
        $options = $this->get_products_common_options($pids);
        if($options):
            $products['options'] = $options;
        else:
            $products['options'] = [];
        endif;
        $products['brands'] = $brands;
        return $products;
    }
    


    /**
     * 
     * @param type $produtcId
     * @return type
     */
    function get_products_price($produtcId){
        $rs = $this->db->from($this->_table_price)->where('productId',$produtcId)->get()->result();
        $prices = array();
        if($rs):            
            foreach($rs as $key => $price):   
                $prices[] = $price;            
            endforeach;
        endif;
        return $prices;
    }
    
    /**
     * 
     * @param type $produtcId
     * @return type
     */
    function get_product_tags($produtcId){
        $sql = "SELECT `t`.*
            FROM `{$this->_tag}` AS t
            LEFT JOIN {$this->_table_tag} AS pt ON pt.tagId = t.tagId
            WHERE `pt`.`productId` = {$produtcId}";
        $rs = $this->db->query($sql)->result();
        $tags = array();
        if($rs):            
            foreach($rs as $key => $tag):   
                $tags[] = $tag;            
            endforeach;
        endif;
        return $tags;
    }
    
    /**
     * 
     * @param type $produtcId
     * @return type
     */
    function get_product_seller($produtcId){
        $sql = "SELECT `u`.*
            FROM `{$this->_user}` AS u
            LEFT JOIN {$this->_product_seller} AS ps ON ps.userId = u.userId
            WHERE `ps`.`productId` = {$produtcId}";
        $rs = $this->db->query($sql)->result();
        $users = array();
        if($rs):            
            foreach($rs as $key => $user):   
                $users[] = $user;            
            endforeach;
        endif;
        return $users;
    }
    
    function is_category_last($categoryId){
        $sql = "SELECT `categoryId` FROM `{$this->_table}` WHERE parrentCategoryId = ".$categoryId." AND status = 1";
        $rs = $this->db->query($sql)->result();
        
        if($rs):
            return false;
        else:
            return true;
        endif;
    }
    
    function get_root_category($categoryId){
        $sql = "SELECT * FROM `{$this->_table}` WHERE categoryId = ".$categoryId." AND status = 1";
        $rs = $this->db->query($sql)->result();
        if($rs):
            foreach($rs as $key => $cat):
                //if($key == 0):
                    $datails = $this->get_details_by_id($cat->categoryId);
                    if($datails[0]->parrentCategoryId == 0):
                        return $datails[0];
                    else:
                        return $this->get_root_category($cat->parrentCategoryId);
                    endif;
                //endif;
            endforeach;  
        else:
            $details = $this->get_details_by_id($categoryId);
            return $details[0];
        endif;
    }
    
    
    function get_parent_categories($categoryId){
        $datails = $this->get_details_by_id($categoryId);
        $sql = "SELECT * FROM `{$this->_table}` WHERE parrentCategoryId = ".$datails[0]->parrentCategoryId." AND status = 1";
        $rs = $this->db->query($sql)->result();
        $pcats = array();
        if($rs):
            foreach($rs as $key => $cat):
                $pcats[] = $cat;
            endforeach;
        endif;
        return $pcats;
    }

    //==========================Start Search Code=========================//

    function get_auto_serch_populet_by_text($term){
        $a_json = array();
        $a_json_row = array();

        $parts = explode(' ', $term);
        $p = count($parts);


        $group_by = ' GROUP BY p.productId';
        $join_query = "";
        $order_by = ' ORDER BY p.popular';
        $order_sort = ' ASC';
        $where_str = 'p.status = 1 ';

        for($i = 0; $i < $p; $i++):
            if($i == 0):
                $where_str .= ' AND ';
            else:
                $where_str .= ' OR ';
            endif;
            $where_str .= ' (b.title LIKE "%'.$parts[$i].'%"';
            if(is_numeric($parts[$i])):
                $where_str .= ' OR (p.lowestPrice >= '.$parts[$i].' AND p.lowestPrice <= '.$parts[$i].')';
            endif;
            $where_str .= '  OR p.title LIKE "%'.$parts[$i].'%"';
            $where_str .= '  OR ptag.tag LIKE "%'.$parts[$i].'%"';
            $where_str .= ' OR (popvalue.value LIKE "%'.$parts[$i].'%" OR popvalue.value LIKE "%'.$parts[$i].'%"))';
            $where_str .= '  OR c.categoryName LIKE "%'.$parts[$i].'%"';
            $where_str .= '  OR b.title LIKE "%'.$parts[$i].'%"';

        endfor;


        $join_query .= " LEFT JOIN product_option_values AS popvalue ON popvalue.productId = p.productId ";
        $join_query .= " LEFT JOIN product_tag AS ptag ON ptag.productId = p.productId ";
        $sql = "SELECT `p`.*, `b`.`title` AS `btitle`, `b`.`brandId` AS `bid`, `c`.`categoryName`, `c`.`categoryId` AS `cid`, `c`.`image` AS `catImage`,`pimage`.`image` AS `pImage`
            FROM `product` AS p
            JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId)
            JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId)
            LEFT JOIN product_brand AS pb ON p.productId = pb.productId
            LEFT JOIN brand AS b ON pb.brandId = b.brandId
            LEFT JOIN product_category AS pc ON pc.productId = p.productId
            LEFT JOIN category AS c ON c.categoryId = pc.categoryId
            LEFT JOIN product_image AS pimage ON pimage.productId = p.productId
            {$join_query}
            WHERE co.countryCode='".$this->_currentUserCountryCode."' AND {$where_str} {$group_by} {$order_by} {$order_sort} ";
        $rs = $this->db->query($sql)->result(); //echo $this->db->last_query();print_r($rs);

        if($rs):
            foreach($rs as $key => $product):
                $a_json_row['id'] =  $product->productId;
                $a_json_row["value"] = $product->title;
                $a_json_row["label"] = $product->title;
                $a_json_row["type"] = "product";
                array_push($a_json, $a_json_row);

                $a_json_row['id'] =  $product->cid;
                $a_json_row["value"] = $product->title."+".$product->categoryName;
                $a_json_row["label"] = $product->title." in this category ".$product->categoryName;
                $a_json_row["type"] = "category";
                array_push($a_json, $a_json_row);

                $a_json_row['id'] =  $product->bid;
                $a_json_row["value"] = $product->title."+".$product->btitle;
                $a_json_row["label"] = $product->title." in this brand  ".$product->btitle;
                $a_json_row["type"] = "brand";
                array_push($a_json, $a_json_row);
            endforeach;
        endif;

        return $a_json;
    }


    /**
     *
     * @param type $categoryId
     * @param type $offset
     * @param type $limit
     * @param type $cond
     * @return type
     */
    public function get_search_products($offset = null, $limit = null, $cond) {


        $group_by = ' GROUP BY p.productId';
        $join_query = "";

        $sort = array('featured','isNew','popular','lowestPrice');
        $order_by = 'p.popular';
        if(array_key_exists('order_by', $cond)){
            if(isset($cond['order_by']) && $cond['order_by'] && in_array($cond['order_by'], $sort)):
                $order_by = '  ORDER BY '.$cond['order_by'];
                unset($cond['order_by']);
            else:
                $order_by = '  ORDER BY p.popular';
            endif;
        }else{
            $order_by = '  ORDER BY p.popular';
        }

        $order_sort = ' ASC';
        if(array_key_exists('order_sort', $cond)){
            if(isset($cond['order_sort']) && $cond['order_sort']):
                $order_sort = ' '.$cond['order_sort'];
                unset($cond['order_sort']);
            endif;
        }
        

        $plimit = '';
        if($offset >= 0 && $limit):
            $plimit = ' LIMIT '.$offset.', '.$limit;
        endif;

        $where_str = 'p.status = 1 ';
        if(array_key_exists('qtype', $cond) && array_key_exists('id', $cond)){
            if(( $cond['qtype'] == "category") && $cond['id'] ):
                $where_str = $where_str." AND c.categoryId = ".$cond['id']."";
            endif;
        }
        

        $bd = false;
        $bdor = 0;
        $bdquery = "";
        $bb = "";
        
        if(array_key_exists('brand', $cond)){
            if(isset($cond['brand']) && $cond['brand']):
                $brands = implode('","', $cond['brand']);
                $bdquery = $bdquery.' AND b.title IN ("'.$brands.'")';
                $bb = $bb.' b.title IN ("'.$brands.'")';
                $bd = true;
            endif;
        }
        
        if(array_key_exists('qtype', $cond) && array_key_exists('id', $cond)){
            if(( $cond['qtype'] == "brand") && $cond['id'] ):
                $opr = "AND";
                if($bd):
                    $opr = "OR";
                    $bdquery = " AND ( ".$bb." ";
                endif;
                $bdquery = $bdquery." ".$opr." b.brandId = ".$cond['id']."";
                if($bd):
                    $bdquery = $bdquery." ) ";
                endif;
            endif;
        }
        

        $where_str = $where_str.$bdquery;
        if(array_key_exists('range', $cond)){
            if(isset($cond['range']) && $cond['range']):
                $lowestPrice = $cond['range'][0];
                $heighestPrice = $cond['range'][1];
                $where_str = $where_str.' AND p.lowestPrice >= '.$lowestPrice.' AND p.lowestPrice <= '.$heighestPrice;
            endif;
        }
        
        if(array_key_exists('qtype', $cond) && array_key_exists('id', $cond)){
            if(( $cond['qtype'] == "product") && $cond['id'] ):
                $where_str = $where_str." AND p.productId = ".$cond['id']."";
            endif;
        }
        

        if(array_key_exists('range', $cond)){
            if(isset($cond['terms']) && $cond['terms']):
                //$where_str = $where_str." ( ";
                $join_query .= " LEFT JOIN product_option_values AS popvalue ON popvalue.productId = p.productId ";
                $qdata = [];
                $parts = $cond['terms'];
                $p = count($parts);
                $qstring = "";
                for($i = 0; $i < $p; $i++):
                    if($i == 0):
                        $qstring .= ' AND ( ';
                    else:
                        $qstring .= ' OR ';
                    endif;
                    $qstring .= ' p.title LIKE "%'.$parts[$i].'%"';
                    if(array_key_exists('qtype', $cond) || array_key_exists('id', $cond)):
                        $qstring .= '  OR c.categoryName LIKE "%'.$parts[$i].'%"';
                        $qstring .= '  OR b.title LIKE "%'.$parts[$i].'%" ';
                        //$qstring .= ' OR (popvalue.value LIKE "%'.$parts[$i].'%" OR popvalue.value LIKE "%'.$parts[$i].'%") ';
                    endif;
                endfor;

                $where_str = $where_str.$qstring;
                $where_str = $where_str." ) ";
            endif;
        }

        if(isset($cond['query']) && $cond['query']):
            $qdata = [];
            foreach($cond['query'] as $qry):
                $exq = explode("@", $qry);
                $qdata[$exq[0]][] = $exq[1];
            endforeach;

            $q_string = " ";
            $qn = count($qdata);
            $i = 0;
            foreach($qdata as $qkey => $qopval):
                $opval = implode("','", $qopval);
                $q_string .= " popvalue.option_id = '".$qkey."' AND value IN ('".$opval."')";
                $i++;
                if($i < $qn):
                    $q_string .= " OR ";
                endif;
            endforeach;
            $where_str = $where_str.' AND ('.$q_string.') ';
        endif;
        
        $sql = "SELECT `p`.*, `b`.`title` AS `btitle`, `c`.`categoryName`,
            `c`.`image` AS `catImage`,`pimage`.`image` AS `pImage`
            FROM `product` AS p
            JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId)
            JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId)
            LEFT JOIN product_brand AS pb ON p.productId = pb.productId
            LEFT JOIN brand AS b ON pb.brandId = b.brandId
            LEFT JOIN product_category AS pc ON pc.productId = p.productId
            LEFT JOIN category AS c ON c.categoryId = pc.categoryId
            LEFT JOIN product_image AS pimage ON pimage.productId = p.productId
            {$join_query}
            WHERE co.countryCode='".$this->_currentUserCountryCode."' AND {$where_str} {$group_by} {$order_by} {$order_sort} {$plimit}";
        $rs = $this->db->query($sql)->result();//echo $this->db->last_query();//print_r($rs);
        $products = array();
        $brands = array();
        $pids = [];
        if($rs):
            foreach($rs as $key => $product):
                $products['products'][] =  $product;
                $brands[$product->btitle] = $product->btitle;
                $pids[] = $product->productId;
            endforeach;
        endif;
        $options = $this->get_products_common_options($pids);
        if($options):
            $products['options'] = $options;
        else:
            $products['options'] = [];
        endif;
        $products['brands'] = $brands;
        return $products;
    }
    
    /**
     *
     * @param type $categoryId
     * @param type $offset
     * @param type $limit
     * @param type $cond
     * @return type
     */
    public function get_search_products_app($offset = null, $limit = null, $cond,$latitude,$longitude) {
        $this->_currentUserCountryCode=get_counry_code_from_lat_long($latitude,$longitude);

        $group_by = ' GROUP BY p.productId';
        $join_query = "";

        $sort = array('featured','isNew','popular','lowestPrice');
        $order_by = 'p.popular';
        if(array_key_exists('order_by', $cond)){
            if(isset($cond['order_by']) && $cond['order_by'] && in_array($cond['order_by'], $sort)):
                $order_by = '  ORDER BY '.$cond['order_by'];
                unset($cond['order_by']);
            else:
                $order_by = '  ORDER BY p.popular';
            endif;
        }else{
            $order_by = '  ORDER BY p.popular';
        }

        $order_sort = ' ASC';
        if(array_key_exists('order_sort', $cond)){
            if(isset($cond['order_sort']) && $cond['order_sort']):
                $order_sort = ' '.$cond['order_sort'];
                unset($cond['order_sort']);
            endif;
        }
        

        $plimit = '';
        if($offset >= 0 && $limit):
            $plimit = ' LIMIT '.$offset.', '.$limit;
        endif;

        $where_str = 'p.status = 1 ';
        if(array_key_exists('qtype', $cond) && array_key_exists('id', $cond)){
            if(( $cond['qtype'] == "category") && $cond['id'] ):
                $where_str = $where_str." AND c.categoryId = ".$cond['id']."";
            endif;
        }
        

        $bd = false;
        $bdor = 0;
        $bdquery = "";
        $bb = "";
        
        if(array_key_exists('brand', $cond)){
            if(isset($cond['brand']) && $cond['brand']):
                $brands = implode('","', $cond['brand']);
                $bdquery = $bdquery.' AND b.title IN ("'.$brands.'")';
                $bb = $bb.' b.title IN ("'.$brands.'")';
                $bd = true;
            endif;
        }
        
        if(array_key_exists('qtype', $cond) && array_key_exists('id', $cond)){
            if(( $cond['qtype'] == "brand") && $cond['id'] ):
                $opr = "AND";
                if($bd):
                    $opr = "OR";
                    $bdquery = " AND ( ".$bb." ";
                endif;
                $bdquery = $bdquery." ".$opr." b.brandId = ".$cond['id']."";
                if($bd):
                    $bdquery = $bdquery." ) ";
                endif;
            endif;
        }
        

        $where_str = $where_str.$bdquery;
        if(array_key_exists('range', $cond)){
            if(isset($cond['range']) && $cond['range']):
                $lowestPrice = $cond['range'][0];
                $heighestPrice = $cond['range'][1];
                $where_str = $where_str.' AND p.lowestPrice >= '.$lowestPrice.' AND p.lowestPrice <= '.$heighestPrice;
            endif;
        }
        
        if(array_key_exists('qtype', $cond) && array_key_exists('id', $cond)){
            if(( $cond['qtype'] == "product") && $cond['id'] ):
                $where_str = $where_str." AND p.productId = ".$cond['id']."";
            endif;
        }
        

        if(array_key_exists('range', $cond)){
            if(isset($cond['terms']) && $cond['terms']):
                //$where_str = $where_str." ( ";
                $join_query .= " LEFT JOIN product_option_values AS popvalue ON popvalue.productId = p.productId ";
                $qdata = [];
                $parts = $cond['terms'];
                $p = count($parts);
                $qstring = "";
                for($i = 0; $i < $p; $i++):
                    if($i == 0):
                        $qstring .= ' AND ( ';
                    else:
                        $qstring .= ' OR ';
                    endif;
                    $qstring .= ' p.title LIKE "%'.$parts[$i].'%"';
                    if(array_key_exists('qtype', $cond) || array_key_exists('id', $cond)):
                        $qstring .= '  OR c.categoryName LIKE "%'.$parts[$i].'%"';
                        $qstring .= '  OR b.title LIKE "%'.$parts[$i].'%" ';
                        //$qstring .= ' OR (popvalue.value LIKE "%'.$parts[$i].'%" OR popvalue.value LIKE "%'.$parts[$i].'%") ';
                    endif;
                endfor;

                $where_str = $where_str.$qstring;
                $where_str = $where_str." ) ";
            endif;
        }

        if(isset($cond['query']) && $cond['query']):
            $qdata = [];
            foreach($cond['query'] as $qry):
                $exq = explode("@", $qry);
                $qdata[$exq[0]][] = $exq[1];
            endforeach;

            $q_string = " ";
            $qn = count($qdata);
            $i = 0;
            foreach($qdata as $qkey => $qopval):
                $opval = implode("','", $qopval);
                $q_string .= " popvalue.option_id = '".$qkey."' AND value IN ('".$opval."')";
                $i++;
                if($i < $qn):
                    $q_string .= " OR ";
                endif;
            endforeach;
            $where_str = $where_str.' AND ('.$q_string.') ';
        endif;
        
        $sql = "SELECT `p`.*, `b`.`title` AS `btitle`, `c`.`categoryName`,
            `c`.`image` AS `catImage`,`pimage`.`image` AS `pImage`
            FROM `product` AS p
            JOIN product_seller AS ps ON(p.productId=ps.productId) JOIN user AS u ON(ps.userId=u.userId)
            JOIN billing_address AS ba ON(u.userId=ba.userId) JOIN country AS co ON(ba.countryId=co.countryId)
            LEFT JOIN product_brand AS pb ON p.productId = pb.productId
            LEFT JOIN brand AS b ON pb.brandId = b.brandId
            LEFT JOIN product_category AS pc ON pc.productId = p.productId
            LEFT JOIN category AS c ON c.categoryId = pc.categoryId
            LEFT JOIN product_image AS pimage ON pimage.productId = p.productId
            {$join_query}
            WHERE co.countryCode='".$this->_currentUserCountryCode."' AND {$where_str} {$group_by} {$order_by} {$order_sort} {$plimit}";
        $rs = $this->db->query($sql)->result();//echo $this->db->last_query();//print_r($rs);
        $products = array();
        $brands = array();
        $pids = [];
        if($rs):
            foreach($rs as $key => $product):
                $products['products'][] =  $product;
                $brands[$product->btitle] = $product->btitle;
                $pids[] = $product->productId;
            endforeach;
        endif;
        $options = $this->get_products_common_options($pids);
        if($options):
            $products['options'] = $options;
        else:
            $products['options'] = [];
        endif;
        $products['brands'] = $brands;
        return $products;
    }

    public function get_products_common_options($products = []){
        $productIds = implode(",",$products);
        if($products):
            $sql = "SELECT `po`.id
                FROM `product_options` AS po
                LEFT JOIN product_option_values AS pov ON po.id = pov.option_id
                WHERE pov.productId IN ( {$productIds} ) AND po.type IN ('checkbox', 'dropdown', 'radio') GROUP BY po.id";
            $rs = $this->db->query($sql)->result_array();//echo $this->db->last_query();print_r($rs);

            $ids = [];
            foreach($rs as $key => $r):
                $ids[] = $r['id'];
            endforeach;
            return $ids;
        else:
            return [];
        endif;
    }

}
?>