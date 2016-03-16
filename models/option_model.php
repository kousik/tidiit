<?php
class Option_model extends CI_Model{
	private $_table='product_options';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_all_admin(){
		$this->db->select('*')->from($this->_table);
		return $this->db->get()->result();
	}
	
        public function add($dataArr){
            $this->db->insert($this->_table,$dataArr);
            return $this->db->insert_id();
        }
        
	public function edit($DataArr,$brandId){
		$this->db->where('id',$brandId);
		$this->db->update($this->_table,$DataArr);
		//echo $this->db->last_query();die;
		return TRUE;		
	}
	
	public function details($id){
		$data=$this->db->from($this->_table)->where('id',$id)->get()->result();
                //echo $this->db->last_query();die;
		return $data[0];
	}
	
	
	public function change_status($bannerId,$Status){
		$this->db->where('id',$bannerId);
		$this->db->update($this->_table,array('status'=>$Status));
		return TRUE;
	}
	
	public function delete($bannerId){
		$this->db->delete($this->_table, array('id' => $bannerId));
		return TRUE;
	}
        
    public function get_all($app=FALSE){
        if($app==FALSE)
            return $this->db->get_where($this->_table,array('status'=>1))->result();
        else
            return $this->db->get_where($this->_table,array('status'=>1))->result_array();
    }

    public function get_bulk_options($options_ids){
        $this->db->select('*')->from($this->_table);
        $this->db->where_in('id', $options_ids);
        $result = $this->db->get()->result();
        $data = [];
        foreach($result as $key => $opt):
            $data[$opt->id] = $opt;
        endforeach;
        return $data;
    }



    ///================================================================//
    public function saveOptionsValues( $options = false, $productId )
    {

        //loop through the product options and add them to the db
        if($options !== false)
        {
          // save edited values
            foreach ($options as $key => $opt)
            {
                $optiondata = $this->details($key);
                // wipe the slate
                $this->deleteProductOptionValues($key, $productId);
                $count = 1;
                $val = [];
                foreach ($opt as $k => $optdata) {
                    $val['sequence'] = $count;
                    $val['name'] = $optiondata->display_name;
                    $val['value'] = $optdata;
                    $val['weight'] = isset($optdata['weight'])?$optdata['weight']:0;
                    $val['price'] = isset($optdata['price'])?$optdata['price']:0.00;
                    $val['option_id'] = $key;
                    $val['productId'] = $productId;
                    $this->db->insert('product_option_values', $val);
                    $count++;
                }
            }
        }
        //return the product id
        return true;
    }

    public function deleteOptionValues($id)
    {
        $this->db->where('option_id', $id);
        $this->db->delete('product_option_values');
    }

    public function deleteProductOptionValues($id, $productId)
    {
        $this->db->where('option_id', $id);
        $this->db->where('productId', $productId);
        $this->db->delete('product_option_values');
    }

    public function get_product_option_values($productId){
        $this->db->select('*')->from('product_option_values');
        $this->db->where('productId', $productId);
        $result = $this->db->get()->result();
        $data = [];
        if($result):
            foreach($result as $key => $opt):
                $data[$opt->option_id][] = $opt;
            endforeach;
        endif;
        return $data;
    }

    public function get_product_display_option_values($productId){
        $this->db->select('*')->from('product_option_values');
        $this->db->where('productId', $productId);
        $result = $this->db->get()->result();
        $data = [];
        if($result):
            foreach($result as $key => $opt):
                $data[$opt->option_id][$opt->name][] = $opt->value;
            endforeach;
        endif;
        return $data;
    }


    public function get_product_display_top_option_values($productId){
        $this->db->select('*')->from('product_option_values');
        $this->db->where('productId', $productId);
        $result = $this->db->get()->result();
        $data = [];
        if($result):
            foreach($result as $key => $opt):
                $op = $this->db->from("product_options")->where('id',$opt->option_id)->get()->result();
                if($op[0]->top > 0):
                    $data[$opt->option_id][$opt->name][] = $opt->value;
                endif;
            endforeach;
        endif;
        return $data;
    }

    public function delete_product_option_values($productId){
        $this->db->where('productId', $productId);
        $this->db->delete('product_option_values');
    }


    public function get_category_product_option_wedgets($options){
        $options = explode(",", $options);
        $optiondatas = $this->db->from("product_options")->where_in('id',$options)->get()->result();
        $data = [];
        if($optiondatas):
            foreach($optiondatas as $key=> $op):
                if(in_array($op->type, array('checkbox', 'radio', 'dropdown'))):
                    $data[$op->slug]['name'] = $op->display_name;
                    $data[$op->slug]['value'] = explode(",", $op->option_data);
                    $data[$op->slug]['type'] = $op->type;
                    $data[$op->slug]['id'] = $op->id;
                endif;
            endforeach;
            return $data;
        else:
            return false;
        endif;
    }
        
}
?>