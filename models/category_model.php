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
        
	function __construct() {
		
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
        $order_by = ' ORDER BY p.productId';
        if(isset($cond['order_by']) && $cond['order_by']):
            $order_by = $order_by.', '.$cond['order_by'];
            unset($cond['order_by']);
        endif;
        $order_sort = ' ASC';
        
        if(isset($cond['order_sort']) && $cond['order_sort']):
            $order_sort = ' '.$cond['order_sort'];
            unset($cond['order_sort']);
        endif;
        
        $plimit = '';
        if($offset >= 0 && $limit):
            $plimit = ' LIMIT '.$offset.', '.$limit;
        else:
            $plimit = ' LIMIT 0, 12';
        endif;
        
        $where_str = 'p.status = 1 ';
        
        if($pcatsId):
            $where_str = $where_str." AND c.categoryId IN ('".$pcatsId."')";
        endif;
        
        $sql = "SELECT `p`.*, `b`.`title` AS `btitle`, `c`.`categoryName`, 
            `c`.`image` AS `catImage`,`pimage`.`image` AS `pImage` 
            FROM `product` AS p
            LEFT JOIN product_brand AS pb ON p.productId = pb.productId
            LEFT JOIN brand AS b ON pb.brandId = b.brandId
            LEFT JOIN product_category AS pc ON pc.productId = p.productId
            LEFT JOIN category AS c ON c.categoryId = pc.categoryId
            LEFT JOIN product_image AS pimage ON pimage.productId = p.productId
            WHERE {$where_str} {$group_by} {$order_by} {$order_sort} {$plimit}";
        $rs = $this->db->query($sql)->result();//echo $this->db->last_query();print_r($rs);
        $products = array();
        $brands = array();
        if($rs):            
            foreach($rs as $key => $product):  
                //$product->tags = $this->get_product_tags($product->productId);
                //$product->seller = $this->get_product_seller($product->productId);
                //$product->product_price = $this->get_products_price($product->productId);
                //$product->curent_category = $this->get_details_by_id($categoryId);
                $products[] =  $product;  
                $brands[$product->btitle] = $product->btitle;
            endforeach;
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
        $sql = "SELECT * FROM `{$this->_table}` WHERE parrentCategoryId = ".$categoryId." AND status = 1";
        $rs = $this->db->query($sql)->result();
        if($rs):
            foreach($rs as $key => $cat):
                if($key == 0):
                    $datails = $this->get_details_by_id($cat->categoryId);
                    if($datails[0]->parrentCategoryId == 0):
                        return $datails[0];
                    else:
                        $this->get_root_category($cat->categoryId);
                    endif;
                endif;
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
}
?>