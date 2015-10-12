<?php

class MY_Controller extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('User_model');
		//$this->load->model('User_model');
	}
	
	public function _logout(){
		$this->session->unset_userdata('FE_SESSION_VAR');
		$this->session->unset_userdata('FE_SESSION_USERNAME_VAR');
		$this->session->unset_userdata('FE_SESSION_USER_EMAIL_VAR');
		$this->session->unset_userdata('FE_SESSION_USER_SiteNickName_VAR');
		$this->session->unset_userdata('FE_SESSION_USER_PROFILE_IMG_VAR');
		$this->session->unset_userdata('FE_SESSION_USER_TYPE');
		$this->session->unset_userdata('FE_SESSION_VAR_TYPE');
		
		$this->session->set_flashdata('LoginPageMsg', 'You are logout successfully');
	}
	
	function is_loged_in(){
		$FE_SESSION_VAR = $this->session->userdata('FE_SESSION_VAR');
		//echo ' admin'.$ADMIN_SESSION_VAR.'<br>';
		if(isset($FE_SESSION_VAR)){
			//echo 'XX';die;
			if(empty($FE_SESSION_VAR)){
				/// checking usr already register or login by cookey
				/*if (isset($_COOKIE["MTUserLoginIDCookie"]))
				echo "Welcome " . $_COOKIE["MTUserLoginIDCookie"] . "!<br />";
				else
				echo "Welcome guest!<br />";
				die;*/
				if(isset($_COOKIE["SPUserLoginIDCookie"])){//$this->input->cookie('MaldivesTravellerUserLoginIDCookie')
					echo 'comming';die;
					$UserID=$_COOKIE["SPUserLoginIDCookie"];
					print_r($UserID);die;
					if($UserID!='' || $UserID>0){
						$UserDetails=$this->User_model->get_details_by_id($UserID);
						$this->session->set_userdata('FE_SESSION_VAR',$UserID);
						$this->session->set_userdata('FE_SESSION_USERNAME_VAR',$UserDetails[0]->UserName);
						$this->session->set_userdata('FE_SESSION_USER_EMAIL_VAR',$UserDetails[0]->Email);
						$this->session->set_userdata('FE_SESSION_USER_SiteNickName_VAR',$UserDetails[0]->SiteNickName);
						$this->session->set_userdata('FE_SESSION_USER_PROFILE_IMG_VAR',$UserDateArr[0]->Image);
						return true;
					}else{
						return false;
					}
				}else{
					//echo 'not getting';die;
					return false;
				}
			}else{
				return true;
			}
		}else{
			if(isset($_COOKIE["SPUserLoginIDCookie"])){//$this->input->cookie('MaldivesTravellerUserLoginIDCookie')
				//echo 'comming';die;
				$UserID=$_COOKIE["SPUserLoginIDCookie"];
				//print_r($UserID);die;
				if($UserID!='' || $UserID>0){
					$UserDetails=$this->User_model->get_details_by_id($UserID);
					$this->session->set_userdata('FE_SESSION_VAR',$UserID);
					$this->session->set_userdata('FE_SESSION_USERNAME_VAR',$UserDetails[0]->UserName);
					$this->session->set_userdata('FE_SESSION_USER_EMAIL_VAR',$UserDetails[0]->Email);
					$this->session->set_userdata('FE_SESSION_USER_SiteNickName_VAR',$UserDetails[0]->SiteNickName);
					$this->session->set_userdata('FE_SESSION_USER_PROFILE_IMG_VAR',$UserDateArr[0]->Image);
					return true;
				}else{
					return false;
				}
			}else{
				//echo 'not getting';die;
				return false;
			}
		}
	}

	

	public function _get_logedin_template($SEODataArr=array()){
		$data=array();
		/*$CountryDataArr=$this->Siteconfig_model->get_country();
		$HiName=$this->session->userdata('FE_SESSION_USER_SiteNickName_VAR');
		$MyAccountLink=base_url().'my_account/';
		$LoginType=$this->session->userdata('FE_SESSION_USER_TYPE');
		if($LoginType==''){
			$LoginType='Standard';
		}
		if($LoginType=='Standard'){
			$LogoutLink=base_url().'user/logout/';
		}else{
			$LogoutLink=base_url().'facebook_user_login/logoutByFacebook/';
		}*/
		$data=$this->html_heading($SEODataArr);
		$tagData=$this->Product_model->get_all_tag_for_footer();
		$data['tagData']=$tagData;
		$data['CMSData']=$this->Cms_model->get_all();
		$data['header']=$this->load->view('header1',$data,true);
		$data['footer']=$this->load->view('footer',$data,true);
		return $data;
	}
	
	
	public function _get_tobe_login_template($SEODataArr=array()){
		$data=array();
		$data=$this->html_heading($SEODataArr);
		$tagData=$this->Product_model->get_all_tag_for_footer();
		$data['tagData']=$tagData;
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
		
		$this->load->view('login',$data);
	}

	

	public function _show_admin_home(){
		if($this->_is_admin_loged_in()){
			$data=array();
			$data['SiteImagesURL']=$this->config->item('SiteImagesURL');
			$data['SiteCSSURL']=$this->config->item('SiteCSSURL');
			$data['SiteJSURL']=$this->config->item('SiteJSURL');
			$data['CurrentCont']=$this->uri->segment(2);
			$data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
			$data['AdminHomeLeftPanel']=$this->load->view('left',$data,TRUE);
			$data['AdminHomeRest']=$this->load->view('admin_home_rest',$data,TRUE);
			
			$this->load->view('home',$data);	
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
		$data['AdminHomeLeftPanel']=$this->load->view('left',$data,TRUE);
		$data['AdminHomeRest']=$this->load->view('admin_home_rest',$data,TRUE);
		return $data;
	}
	
	public function html_heading($SEODataArr=array()){
		$data=array();
		$TopCategoryData=$this->Category_model->get_top_category_for_product_list();
		$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
		foreach($TopCategoryData as $k){
			//echo $k->CategoryName.'<br>';
			$TempArr=array();
			//print_r($AllButtomCategoryData);die;
			foreach($AllButtomCategoryData as $kk => $vv){ //print_r($vv);die;
				if($vv->ParrentCategoryID==$k->CategoryID){
					$TempArr[$vv->CategoryID]=$vv->CategoryName;
					unset($AllButtomCategoryData[$kk]);
				}
				$k->SubCategory=$TempArr;
			}
		}
		$data["CategoryData"]=$TopCategoryData;
		
		$SiteSessionID=$this->session->userdata('USER_SITE_SESSION_ID');
		if($SiteSessionID==""){
			$NewSessionID=uniqid();
			$this->session->set_userdata('USER_SITE_SESSION_ID',$NewSessionID);
		}
                
                
                if(empty($SEODataArr)){
                    
                    $metaInfoUriSegmentArr=array('content');
                    $first_sagment=$this->uri->segment(1);
                    
                    if(in_array($first_sagment,$metaInfoUriSegmentArr)){
                            if($first_sagment=='content'){
                                    $fun=$this->uri->segment(2);
                                    $MetaData=$this->Cms_model->get_content($fun);
                                    $data['MetaTitle']=$MetaData[0]->MetaTitle;
                                    $data['MetaKeyWord']=$MetaData[0]->MetaKeyWord;
                                    $data['MetaDescription']=$MetaData[0]->MetaDescription;
                            }else{
                                $data=$this->get_general_meta($data);
                            }
                    }else{
                        
                            $data=$this->get_general_meta($data);
                            
                    }
                }else{
                    $data['MetaTitle']=$SEODataArr['MetaTitle'];
                    $data['MetaKeyWord']=$SEODataArr['MetaKeyWord'];
                    $data['MetaDescription']=$SEODataArr['MetaDescription'];
                }
                //echo $this->uri->segment(1).' = '.$this->uri->segment(2);die;
		$data['html_heading']=$this->load->view('html_heading',$data,true);
                //echo 'zzz1134588';die;
		return $data;
	}
	

	private function get_general_meta($data){
            
		$MetaData=$this->Siteconfig_model->get_html_head();
                
		$data['MetaTitle']=$MetaData[0]->ConstantValue;
		$data['MetaKeyWord']=$MetaData[1]->ConstantValue;
		$data['MetaDescription']=$MetaData[2]->ConstantValue;
		
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

}
