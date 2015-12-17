<?php

class MY_Controller extends CI_Controller {
	function __construct() {
            parent::__construct();
            $this->load->model('User_model');
            //$this->load->model('Event_model');
            $this->load->model('Siteconfig_model');
            //$this->load->model('Testimonial_model');
            $this->load->model('Cms_model');
            $this->load->model('Product_model');
            $this->load->model('Category_model');
            $userLocation=$this->session->userdata('FE_SESSION_USER_LOCATION_VAR');
            if($userLocation==""){
                $cIP=$this->input->ip_address();
                $this->session->set_userdata('FE_SESSION_USER_LOCATION_VAR',file_get_contents("http://ipinfo.io/$cIP/country"));
            }
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
		
		$this->session->set_flashdata('Message', 'You are logout successfully');
	}
	
        public function _is_loged_in(){
            if($this->session->userdata('FE_SESSION_VAR')==""){
                return FALSE;
            }else{
                return TRUE;
            }
        }

	public function _get_logedin_template($SEODataArr=array()){
            if($this->_is_loged_in()==FALSE){
                redirect(BASE_URL.'index/login');
            }
		$data=array();
		$data=$this->html_heading($SEODataArr);
		return $data;
	}
	
	
	public function _get_tobe_login_template($SEODataArr=array()){
		$data=array();
		$data=$this->html_heading();
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

	public function html_heading($SEODataArr=array()){
		$data=array();
                $data['SiteImagesURL']=$this->config->item('SiteImagesURL');
		$data['SiteCSSURL']=$this->config->item('SiteCSSURL');
		$data['SiteJSURL']=$this->config->item('SiteJSURL');
		$data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
		
		
		$SiteSessionID=$this->session->userdata('USER_SITE_SESSION_ID');
		if($SiteSessionID==""){
			$NewSessionID=uniqid();
			$this->session->set_userdata('USER_SITE_SESSION_ID',$NewSessionID);
		}
                
                
                //echo $this->uri->segment(1).' = '.$this->uri->segment(2);die;

                $data['last_login']=$this->User_model->get_last_login();
		$data['html_heading']=$this->load->view('html_heading',$data,true);
                $data['top']=$this->load->view('top',$data,TRUE);
                $data['left']=$this->load->view('left',$data,TRUE);
                $data['footer']=$this->load->view('footer',$data,TRUE);
                //echo 'zzz1134588';die;
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
        
    function _global_tidiit_mail($to,$subject,$dataResources,$tempplateName="",$toName=""){
        $message='';
        //echo $tempplateName;
        if($tempplateName==""){
            $message=$dataResources;
        }else{
            $message=  $this->load->view('email_template/'.$tempplateName,$dataResources,TRUE);
        }
        //echo $message;die;
        $this->load->library('email');
        $this->email->from("no-reply@tidiit.com", 'Tidiit System Administrator');
        if($toName!="")
            $this->email->to($to,$toName);
        else
            $this->email->to($to);
        
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    }
    
    function load_default_resources(){
        $data=array();
        $data['SiteImagesURL']=$this->config->item('SiteImagesURL');
        $data['SiteCSSURL']=$this->config->item('SiteCSSURL');
        $data['SiteJSURL']=$this->config->item('SiteJSURL');
        $data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
        $data['MainSiteBaseURL']=BASE_URL;
        $data['MainSiteImagesURL']=$this->config->item('SiteImagesURL');
        $data['SiteProductImageURL']=DETAILS_PAGE_SMALL_IMG;
        return $data;
    }
}
