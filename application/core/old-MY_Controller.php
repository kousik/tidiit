<?php

class MY_Controller extends CI_Controller {
	function __construct() {
            parent::__construct();
            $this->load->model('Admin_model');
            $this->load->model('User_model');
            //$this->load->model('Event_model');
            $this->load->model('Siteconfig_model');
            $this->load->model('Testimonial_model');
            $this->load->model('Cms_model');
            $this->load->model('Product_model');
            $this->load->model('Category_model');
	}
	
	public function _logout(){
		$this->session->unset_userdata('FE_SESSION_VAR');
		$this->session->unset_userdata('FE_SESSION_USERNAME_VAR');
		$this->session->unset_userdata('FE_SESSION_USER_EMAIL_VAR');
		$this->session->unset_userdata('FE_SESSION_USER_SiteNickName_VAR');
		$this->session->unset_userdata('FE_SESSION_USER_PROFILE_IMG_VAR');
		$this->session->unset_userdata('FE_SESSION_USER_TYPE');
		$this->session->unset_userdata('FE_SESSION_VAR_TYPE');
		$this->session->unset_userdata('ShippingSelected');
		$this->session->unset_userdata('ShippingID');
		$this->session->set_userdata('TotalItemInCart',0);
		
		$this->session->set_flashdata('LoginPageMsg', 'You are logout successfully');
	}

	public function _get_logedin_template($SEODataArr=array()){
		$data=array();
		$data=$this->html_heading($SEODataArr);
		$AboutUsData=$this->Cms_model->get_content('about_daily_plaza');
                $data['AboutUSShortData']=$AboutUsData[0]->ShortBody;
		$data['header']=$this->load->view('header1',$data,true);
		$data['footer']=$this->load->view('footer',$data,true);
		return $data;
	}
	
	public function _get_tobe_login_template($SEODataArr=array()){
		$data=array();
		$data=$this->html_heading($SEODataArr);
                $AboutUsData=$this->Cms_model->get_content('about_daily_plaza');
                $data['AboutUSShortData']=$AboutUsData[0]->ShortBody;
                $data['header']=$this->load->view('header',$data,true);
		$data['footer']=$this->load->view('footer',$data,true);
		return $data;
	}
	
	public function _my_seo_freindly_url_str($String){
		$ChangedStr = preg_replace('/\%/',' percentage',$String);
		$ChangedStr = preg_replace('/\@/',' at ',$ChangedStr);
		$ChangedStr = preg_replace('/\&/',' and ',$ChangedStr);
		$ChangedStr = preg_replace('/\s[\s]+/','-',$ChangedStr);    // Strip off multiple spaces
		$ChangedStr = preg_replace('/[\s\W]+/','-',$ChangedStr);    // Strip off spaces and non-alpha-numeric
		$ChangedStr = preg_replace('/^[\-]+/','',$ChangedStr); // Strip off the starting hyphens
		$ChangedStr = preg_replace('/[\-]+$/','',$ChangedStr); // // Strip off the ending hyphens
		return $ChangedStr;
	}
	
	function _show_short_words_words($str,$NoOfWorrd=20){
		$strArr=explode(' ',$str);	
		$shortStr='';
		//echo 'NoOfWorrd '.$NoOfWorrd;die;
		for($i=0;$i<$NoOfWorrd;$i++){
			if($i==0){
				$shortStr=$strArr[$i];
			}else{
				$shortStr.=' '.$strArr[$i];
			}
		}
		return $shortStr;
	}

	function switchLanguage($language = "") {
            $language = ($language != "") ? $language : "english";
            $this->session->set_userdata('site_lang', $language);
            redirect(base_url());
        }

	public function _is_admin_loged_in(){
            //echo 'admin id'.$this->session->userdata('ADMIN_SESSION_VAR'); die;
		if($this->session->userdata('ADMIN_SESSION_VAR')!=""){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function _show_admin_login(){
		$data=array();
		$data['SiteImagesURL']=$this->config->item('SiteImagesURL');
		$data['SiteCSSURL']=$this->config->item('SiteCSSURL');
		$data['SiteJSURL']=$this->config->item('SiteJSURL');
		$data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
		
		$this->load->view('webadmin/login',$data);
	}

	public function _show_admin_home(){
		if($this->_is_admin_loged_in()){
			$data=array();
			$data['SiteImagesURL']=$this->config->item('SiteImagesURL');
			$data['SiteCSSURL']=$this->config->item('SiteCSSURL');
			$data['SiteJSURL']=$this->config->item('SiteJSURL');
			$data['CurrentCont']=$this->uri->segment(2);
			$data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
			$data['AdminHomeLeftPanel']=$this->load->view('webadmin/left',$data,TRUE);
			$data['AdminHomeRest']=$this->load->view('webadmin/admin_home_rest',$data,TRUE);
			
			$this->load->view('webadmin/home',$data);	
		}else{
			$this->_show_admin_login();
		}
		
	}
	
	public function _show_admin_logedin_layout(){
		$data=array();
		$data['SiteImagesURL']=$this->config->item('SiteImagesURL');
		$data['SiteCSSURL']=$this->config->item('SiteCSSURL');
		$data['SiteJSURL']=$this->config->item('SiteJSURL');
		$data['CurrentCont']=$this->uri->segment(2);
		$data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
		$data['AdminHomeLeftPanel']=$this->load->view('webadmin/left',$data,TRUE);
		$data['AdminHomeRest']=$this->load->view('webadmin/admin_home_rest',$data,TRUE);
		return $data;
	}
	
	public function html_heading($SEODataArr=array()){
		$data=array();
                $fbData=array();
                $data['SiteImagesURL']=$this->config->item('SiteImagesURL');
		$data['SiteCSSURL']=$this->config->item('SiteCSSURL');
		$data['SiteJSURL']=$this->config->item('SiteJSURL');
		$data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
		
		$TopCategoryDataArr=$this->Category_model->get_top_category();
		$data["TopCategoryDataArr"]=$TopCategoryDataArr;
                
		$SiteSessionID=$this->session->userdata('USER_SITE_SESSION_ID');
		if($SiteSessionID==""){
			$NewSessionID=uniqid();
			$this->session->set_userdata('USER_SITE_SESSION_ID',$NewSessionID);
		}
                
                
                if(empty($SEODataArr)){
                    $data=$this->get_general_meta($data);
                }else{
                    //print_r($SEODataArr);die;
                    if(array_key_exists('meta', $SEODataArr)){
                        //echo 'rrr ppp';die;
                        $data['MetaTitle']=$SEODataArr['MetaTitle'];
                        $data['meta']=$SEODataArr['meta'];
                    }else{
                        //echo 'rrr ppp1';die;
                        $data=$this->get_general_meta($data);
                    }
                    
                    if(array_key_exists('ogImage', $SEODataArr)){
                        $data['ogImage']=$SEODataArr['ogImage'];
                    }else{
                        $data['ogImage']='';
                    }
                    //$data['MetaDescription']=$SEODataArr['MetaDescription'];
                }
                if(key_exists('fbData', $SEODataArr)){
                    $data['fbData']=$SEODataArr['fbData'];
                }else{
                    $data['fbData']=$fbData;
                }
                //echo $this->uri->segment(1).' = '.$this->uri->segment(2);die;
		$data['html_heading']=$this->load->view('html_heading',$data,true);
                //echo 'zzz1134588';die;
		return $data;
	}

	private function get_general_meta($data){
                
                $this->load->model('Custom_page_seo_model');
                if($this->session->userdata('USER_SHIPPING_COUNTRY')==1){
                    // for USA
                    $HomePageInfoArr=  $this->Custom_page_seo_model->get_details(3);
                }else if($this->session->userdata('USER_SHIPPING_COUNTRY')==99){
                    // India
                    $HomePageInfoArr=  $this->Custom_page_seo_model->get_details(2);
                }else{
                    // for Others
                    $HomePageInfoArr=  $this->Custom_page_seo_model->get_details(4);
                }
                
                $meta=$this->getDefaultMetaTag();
                $meta[]=array('name' => 'description', 'content' => $HomePageInfoArr['Description']);
                $meta[]=array('name' => 'keywords', 'content' =>$HomePageInfoArr['Keywords']);
                
                if($HomePageInfoArr['abstract']!="") $meta[]=array('name'=>'abstract','content'=>$HomePageInfoArr['abstract']);
                if($HomePageInfoArr['dc_relation']!="") $meta[]=array('name'=>'dc.relation','content'=>$HomePageInfoArr['dc_relation']);
                if($HomePageInfoArr['dc_title']!="") $meta[]=array('name'=>'dc.title','content'=>$HomePageInfoArr['dc_title']);
                if($HomePageInfoArr['dc_keywords']!="") $meta[]=array('name'=>'dc.keywords','content'=>$HomePageInfoArr['dc_keywords']);
                if($HomePageInfoArr['dc_subject']!="") $meta[]=array('name'=>'dc.subject','content'=>$HomePageInfoArr['dc_subject']);
                if($HomePageInfoArr['dc_description']!="") $meta[]=array('name'=>'dc.description','content'=>$HomePageInfoArr['dc_description']);
                if($HomePageInfoArr['classification']!="") $meta[]=array('name'=>'classification','content'=>$HomePageInfoArr['classification']);
                if($HomePageInfoArr['resource_type']!="") $meta[]=array('name'=>'resource-type','content'=>$HomePageInfoArr['resource_type']);
                if($HomePageInfoArr['geo_placename']!="") $meta[]=array('name'=>'geo.placename','content'=>$HomePageInfoArr['geo_placename']);
                if($HomePageInfoArr['geo_position']!="") $meta[]=array('name'=>'geo.position','content'=>$HomePageInfoArr['geo_position']);
                if($HomePageInfoArr['geo_region']!="") $meta[]=array('name'=>'geo.region','content'=>$HomePageInfoArr['geo_region']);
                if($HomePageInfoArr['ICBM']!="") $meta[]=array('name'=>'ICBM','content'=>$HomePageInfoArr['ICBM']);
                
                
                //$data=$this->html_heading($SEOArra); 
                //echo 'zzz';die;
                $data['MetaTitle']=$HomePageInfoArr['Title'];
                $data['meta']=$meta;
                return $data;
	}
	
	public function my_unique_string($Length){
		$listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		return substr(str_shuffle($listAlpha),0,$Length);
	}
	
	public function my_unique_number($Length){
		$listAlpha = '0123456789';
		return substr(str_shuffle($listAlpha),0,$Length);
	}
	
	public function product_model(){
		$Length=12;
		$listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$RowModelNo=substr(str_shuffle($listAlpha),0,$Length);
		$RowModelNoArr=str_split($RowModelNo);
		$t=0;
		$NewModelNo="";
		foreach($RowModelNoArr as $k){
			if($t==4){
				$NewModelNo .= '-'.$k;
				$t=1;
			}else{
				$NewModelNo .= $k;
				$t++;
			}
		}
		return $NewModelNo;
	}
        
        protected function _my_pagination($dataArr){
            $config = array();
            $config["base_url"] = $dataArr['base_url'];
            $config["total_rows"] = $dataArr['total_rows'];
            $config["per_page"] = $dataArr['per_page'];
            $config["num_links"] = $dataArr['num_links'];
            $config["uri_segment"] =$dataArr['uri_segment'];
            $config['use_page_numbers'] = TRUE;
            $config['next_link'] = 'Next';
            $config['prev_link'] = 'Previous';
            $config['cur_tag_open'] = '<lable style="font-weight:bold;font-size:14px;color:blue;"> ';
            $config['cur_tag_close'] = '</lable>';
            $this->pagination->initialize($config);
        }
        
        public function getDefaultMetaTag(){
            return array();
            return array(
                array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv'),
                array('name' => 'Cache-control', 'content' => 'public', 'type' => 'equiv'),
                array('name' => 'imagetoolbar', 'content' => 'yes', 'type' => 'equiv'),
                array('name' => 'viewport', 'content' => 'width=device-width,user-scalable=no,initial-scale=1,maximum-scale=1,minimum-scale=1'),
                array('name' => 'robots', 'content' => 'all, index, follow'),
                array('name' => 'googlebot', 'content' => 'index, follow, archive'),
                array('name' => 'msnbot', 'content' => 'index, follow, archive'),
                array('name' => 'Slurp', 'content' => 'index, follow, archive'),
                array('name' => 'identifier-url', 'content' => 'https://www.dailyplaza.com'),
                array('name' => 'canonical', 'content' => 'https://www.dailyplaza.com'),
                array('name' => 'author', 'content' => 'https://www.dailyplaza.com'),
                array('name' => 'revisit-after', 'content' => '7 days'),
                array('name' => 'contact', 'content' => 'service@dailyplaza.com'),
                array('name' => 'copyright', 'content' => 'dailyplaza.com'),
                array('name' => 'distribution', 'content' => 'global'),
                array('name' => 'language', 'content' => 'Enlish'),
                array('name' => 'MSThemeCompatible', 'content' => 'yes'),
                array('name' => 'web_author', 'content' => 'editorial staff of dailyplaza'),
                array('name' => 'google-translate-customization', 'content' => '3f4ffc751b6f619-aaa1185795a36d3e-gc243fde537c28ad5-11'),
                array('name' => 'audience', 'content' => 'all'),
                array('name' => 'rating', 'content' => 'General'),
                array('name' => 'dc.language', 'content' => 'English'),
                array('name' => 'dc.source', 'content' => 'https://www.dailyplaza.com'),
            );
        }
        
        function _get_home_url(){
            $countryId=$this->session->userdata('USER_SHIPPING_COUNTRY');
            if($countryId==1){
                return base_url().'send-online-gifts-usa';
            }else if($countryId==99){
                return base_url().'send-wine-cakes-flowers-online-india';
            }else if($countryId==240){
                return base_url().'send-gifts-worldwide';
            }
        }
        
        function _no_access(){
            $data=$this->_show_admin_logedin_layout();
            $this->load->view('no_access',$data);
        }
	
        function _has_access($controller,$function){
            if(user_role_check($controller,$function)==FALSE){
                $this->session->set_flashdata('Message','You have no access right for requested pages.');
                redirect(base_url());
            }
            return TRUE;
        }
        
        function _show_under_construction(){
            $data=$this->_show_admin_logedin_layout();
            $this->load->view('under_construction',$data);
        }
        
        
}
