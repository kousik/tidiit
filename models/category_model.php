<?php
class Category_model extends CI_Model {
	public $_table='category';
	public $_table_seo='seo_data';
	public $_table_template='category_view_page';
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
		//$this->db->where('categoryId',$categoryId);
                $this->db->where_in('categoryId',explode(',',$categoryId));
		$this->db->update($this->_table,array('status'=>$status));
		return TRUE;
	}
	
	public function delete($categoryId){
		$this->db->delete($this->_table, array('categoryId' => $categoryId)); 
		return TRUE;
	}
	
	public function get_details_by_id($id){
            return $this->db->from($this->_table)->like('categoryId',$id)->get()->result();
	}
	
	public function get_subcategory_by_category_id($categoryId){
		$sql="SELECT `categoryId` , `categoryName` FROM category WHERE `parrentCategoryId` ='".$categoryId."' AND status=1";
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
	
	public function get_top_category_for_product_list(){
		$this->db->select('categoryId,categoryName')->from($this->_table)->where('parrentCategoryId','0')->where('status','1')->order_by('categoryName','ASC');
		$rs=$this->db->get()->result();
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
}
?>