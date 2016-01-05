<?php

/*
| ----------------------------------------------
| Start Date : 23-11-2010
| Framework : CodeIgniter
| ----------------------------------------------
| Model Video Gallery
| ----------------------------------------------
*/

class Country extends CI_Model {

    public $_table = 'country';
    public $_table_state = 'state';
    public $_table_city = 'city';
    public $_table_zip = 'zip';
    public $_table_locality = 'locality';
    public $result = null;

    function __construct(){
        parent::__construct();
    }
    
	
	function get_all(){
            return $this->db->get($this->_table)->result();
	}	
        
        function get_all1($app=FALSE){
            $this->db->order_by('countryName','asc');
            if($app)
                return $this->db->get_where($this->_table,array('status'=>1))->result_array();
            else
                return $this->db->get_where($this->_table,array('status'=>1))->result();
        }
	
	function get_state_country($countryId,$app=FALSE){
            if($app)
                return $this->db->from($this->_table_state)->where('countryId',$countryId)->get()->result_array();
            else    
                return $this->db->from($this->_table_state)->where('countryId',$countryId)->get()->result();
	}
        
        function get_state_country1($countryId,$app=FALSE){
            if($app)
                return $this->db->from($this->_table_state)->where('countryId',$countryId)->where('status',1)->get()->result_array();
            else    
                return $this->db->from($this->_table_state)->where('countryId',$countryId)->where('status',1)->get()->result();
	}
	
	function get_all_state($app=FALSE){
            if($app)
                return  $this->db->get($this->_table_state)->result();
            else    
                return  $this->db->get($this->_table_state)->result();
	}
	
	public function get_indian_state(){
            return $this->get_state_country(99);
	}
	
	
	function get_country_name($id,$app=FALSE){
            if($app)
                return $this->db->select('CountryName')->from($this->_table)->where('CountryID',$id)->get()->result_array();
            else    
                return $this->db->select('CountryName')->from($this->_table)->where('CountryID',$id)->get()->result();
	}
	
	function get_state_name($id,$app=FALSE){
            if($app)
                return $this->db->select('stateName')->from($this->_table_state)->where('stateId',$id)->get()->result_array();
           else 
                return $this->db->select('stateName')->from($this->_table_state)->where('stateId',$id)->get()->result();
	}
        
        function get_add_state($countryId,$stateName){
            $Arr=$this->db->select('stateId')->from($this->_table_state)->where('stateName',$stateName)->where('countryId',$countryId)->get()->row_array();
            if(count($Arr)>0){
                return $Arr['stateId'];
            }else{
                $this->db->insert($this->_table_state,array('countryId'=>$countryId,'stateName'=>$stateName));
                return $this->db->insert_id();
            }
        }
        
        function get_all_state_admin($countryId){
            $sql="SELECT s.*,c.countryName FROM `state` as s join `country` AS c ON(s.countryId=c.countryId) WHERE s.countryId=".$countryId;
            return $this->db->query($sql)->result();
        }
        
        function edit($dataArr,$countryId){
            $this->db->where('countryId',$countryId);
            $this->db->update($this->_table,$dataArr);
            return TRUE;
        }
        
        function delete($countryId){
            $this->db->delete($this->_table, array('countryId' => $countryId)); 
            return TRUE;
        }
        
        function state_details($stateId,$app=FALSE){
            if($app)
                return $this->db->from($this->_table_state)->where('stateId',$stateId)->get()->result_array();
            else
                return $this->db->from($this->_table_state)->where('stateId',$stateId)->get()->result();
        }
        
        function get_all_city($stateId,$app=FALSE){
            $sql="SELECT c.*,s.stateName,co.countryName FROM city AS c LEFT JOIN state AS s ON(c.stateId=s.stateId) LEFT JOIN country AS co ON(c.countryId=co.countryId) WHERE s.stateId=".$stateId;
            if($app)
                return $this->db->query($sql)->resul_array();
            else
                return $this->db->query($sql)->result();
        }
        
        function get_all_city1($countryId,$app=false){
            if($app==FALSE)
                return $this->db->from($this->_table_city)->where('countryId',$countryId)->where('status',1)->get()->result();
            else
                return $this->db->from($this->_table_city)->where('countryId',$countryId)->where('status',1)->get()->result_array();
        }
        
        function edit_state($dataArr,$stateId){
            $this->db->where('stateId',$stateId);
            $this->db->update($this->_table_state,$dataArr);
            return TRUE;		
        }
        
        function delete_state($stateId){
            $this->db->delete($this->_table_state, array('stateId' => $stateId)); 
            return TRUE;
        }
        
        function get_add_city($dataArr){
            $Arr=$this->db->select('cityId')->from($this->_table_city)->where('city',$dataArr['city'])->where('stateId',$dataArr['stateId'])->get()->row_array();
            if(count($Arr)>0){
                return $Arr['cityId'];
            }else{
                $this->db->insert($this->_table_city,$dataArr);
                return $this->db->insert_id();
            }
        }
        
        
        function get_add_zip($dataArr){
            $Arr=$this->db->select('zipId')->from($this->_table_zip)->where('zip',$dataArr['zip'])->where('cityId',$dataArr['cityId'])->get()->row_array();
            if(count($Arr)>0){
                return $Arr['zipId'];
            }else{
                $this->db->insert($this->_table_zip,$dataArr);
                return $this->db->insert_id();
            }
        }
        
        function get_add_locality($dataArr){
            $Arr=$this->db->select('localityId')->from($this->_table_locality)->where('locality',$dataArr['locality'])->where('zipId',$dataArr['zipId'])->get()->row_array();
            if(count($Arr)>0){
                return $Arr['localityId'];
            }else{
                $this->db->insert($this->_table_locality,$dataArr);
                return $this->db->insert_id();
            }
        }
        
        function edit_city($dataArr,$cityId){
            $this->db->where('cityId',$cityId);
            $this->db->update($this->_table_city,$dataArr);
            return TRUE;		
        }
        
        function delete_city($cityId){
            $this->db->delete($this->_table_city, array('cityId' => $cityId)); 
            return TRUE;
        }
        
        function city_details($cityId){
            return $this->db->from($this->_table_city)->where('cityId',$cityId)->get()->result();
        }
        
        function get_all_zip($cityId){
            $sql="SELECT z.*,c.city FROM `zip` As z JOIN `city` AS c ON(z.cityId=c.cityId) WHERE c.cityId=".$cityId;
            return $this->db->query($sql)->result();
        }
        
        function get_all_zip1($cityId,$app=FALSE){
            if($app==FALSE)
                return $this->db->from($this->_table_zip)->where('cityId',$cityId)->where('status',1)->get()->result();
            else
                return $this->db->from($this->_table_zip)->where('cityId',$cityId)->where('status',1)->get()->result_array();
        }
        
        function zip_details($zipId){
            return $this->db->from($this->_table_zip)->where('zipId',$zipId)->get()->result();
        }
        
        function edit_zip($dataArr,$zipId){
            $this->db->where('zipId',$zipId);
            $this->db->update($this->_table_zip,$dataArr);
            return TRUE;		
        }
        
        function delete_zip($zipId){
            $this->db->delete($this->_table_zip, array('zipId' => $zipId)); 
            return TRUE;
        }
        
        function get_all_locality($zipId){
            $sql="SELECT l.*,z.zip,c.city FROM `locality` AS l JOIN `zip` As z ON(l.zipId=z.zipId) JOIN `city` AS c ON(z.cityId=c.cityId) WHERE l.zipId=".$zipId;
            return $this->db->query($sql)->result();
        }
        
        function get_all_locality1($zipId,$app=FALSE){
            if($app==FALSE)
                return $this->db->from($this->_table_locality)->where('zipId',$zipId)->where('status',1)->get()->result();
            else
                return $this->db->from($this->_table_locality)->where('zipId',$zipId)->where('status',1)->get()->result_array();
        }
        
        function locality_details($localityId){
            return $this->db->from($this->_table_locality)->where('localityId',$localityId)->get()->result();
        }
        
        function edit_locality($dataArr,$localityId){
            $this->db->where('localityId',$localityId);
            $this->db->update($this->_table_locality,$dataArr);
            return TRUE;		
        }
        
        function delete_locality($localityId){
            $this->db->delete($this->_table_locality, array('localityId' => $localityId)); 
            return TRUE;
        }
        
        function get_city_country($countryId,$app=FALSE){
            if($app)
                return $this->db->get_where($this->_table_city,array('countryId'=>$countryId,'status'=>1))->result_array();
            else    
                return $this->db->get_where($this->_table_city,array('countryId'=>$countryId,'status'=>1))->result();
        }
        
}

?>
