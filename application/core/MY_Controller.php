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
        $this->load->library('session');
        $this->load->library('user_agent');
    }

    public function _logout(){
            $this->session->unset_userdata('FE_SESSION_VAR');
            $this->session->unset_userdata('FE_SESSION_USERNAME_VAR');
            $this->session->unset_userdata('FE_SESSION_USER_EMAIL_VAR');
            $this->session->unset_userdata('FE_SESSION_USER_SiteNickName_VAR');
            $this->session->unset_userdata('FE_SESSION_USER_PROFILE_IMG_VAR');
            $this->session->unset_userdata('FE_SESSION_USER_TYPE');
            $this->session->unset_userdata('FE_SESSION_VAR_TYPE');
            $this->session->unset_userdata('FE_SESSION_UDATA');
            $this->session->unset_userdata('ShippingSelected');
            $this->session->unset_userdata('ShippingID');
            $this->session->set_userdata('TotalItemInCart',0);

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
    
    /**
    * Check if user is logged in
    *
    * @access    private
    */
    public function _isLoggedIn() {
        if($this->is_loged_in() != true){
            $this->_logout();
            $this->session->set_flashdata('redirect_url', $this->_get_the_current_url());
            $a= 'reopen modal';
            $this->session->set_flashdata('open_login', $a);
            redirect(BASE_URL);
        }
    }
    
    public function _get_the_current_url() {
    
        $protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
        $base_url = $protocol . "://" . $_SERVER['HTTP_HOST'];
        $complete_url =   $base_url . $_SERVER["REQUEST_URI"];

        return $complete_url;

    }
    
    public function _get_logedin_template($SEODataArr=array()){
        $this->load->library('cart');
        $data=array();
        $data=$this->html_heading($SEODataArr);
        $callUsForFree='CALL_US_FOR_FREE_'.$this->session->userdata("FE_SESSION_USER_LOCATION_VAR");
        $data['callForUsFree']=$this->Siteconfig_model->get_value_by_name($callUsForFree);
        $data['float_menu']=$this->get_site_categories_float_menu();
        $data['howItWorksBoxContent']=$this->Cms_model->get_content_by_id(19);
        $data['header']=$this->load->view('header1',$data,true);
        $data['footer']=$this->load->view('footer',$data,true);
        //$data['main_menu']=$this->load->view('main_menu',$data,true);
        //$data['main_menu']=$this->get_site_categories_view();
        $data['main_menu']=$this->get_site_categories_fixed_menu();
        
        $user = $this->_get_current_user_details();
        $mynotification = $this->User_model->notification_my_unread($user->userId);
        if(!empty($mynotification)):
            $data['tot_notfy'] = count($mynotification);
        else:
            $data['tot_notfy'] = 0;
        endif;
        return $data;
    }

    public function _get_tobe_login_template($SEODataArr=array()){
            $data=array();
            $data=$this->html_heading($SEODataArr);
            //$AboutUsData=$this->Cms_model->get_content('about_daily_plaza');
            //$data['AboutUSShortData']=$AboutUsData[0]->ShortBody;
            $callUsForFree='CALL_US_FOR_FREE_'.$this->session->userdata("FE_SESSION_USER_LOCATION_VAR");
            $data['callForUsFree']=$this->Siteconfig_model->get_value_by_name($callUsForFree);
            $data['howItWorksBoxContent']=$this->Cms_model->get_content_by_id(19);
            $data['float_menu']=$this->get_site_categories_float_menu();
            $data['header']=$this->load->view('header',$data,true);
            $data['footer']=$this->load->view('footer',$data,true);
            //$data['main_menu']=$this->load->view('main_menu',$data,true);
            //$data['main_menu']=$this->get_site_categories_view();
            $data['main_menu']=$this->get_site_categories_fixed_menu();
            
            return $data;
    }

    public function _my_seo_freindly_url_str($String){
            $ChangedStr = preg_replace('/\%/',' percentage',$String);
            $ChangedStr = preg_replace('/\@/',' at ',$ChangedStr);
            $ChangedStr = preg_replace('/\'/',' - ',$ChangedStr);
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
        if($this->_is_admin_loged_in()==FALSE){
            redirect(BASE_URL.'webadmin/');
        }
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

    function load_default_resources(){
        $data=array();
        $data['SiteImagesURL']=$this->config->item('SiteImagesURL');
        $data['SiteCSSURL']=$this->config->item('SiteCSSURL');
        $data['SiteJSURL']=$this->config->item('SiteJSURL');
        $data['SiteResourcesURL']=$this->config->item('SiteResourcesURL');
        $data['MainSiteBaseURL']=BASE_URL;
        $data['MainSiteImagesURL']=$this->config->item('SiteImagesURL');
        $data['SiteProductImageURL']=PRODUCT_DEAILS_SMALL;
        return $data;
    }
    
    public function html_heading($SEODataArr=array()){
        $fbData=array();
        $data=  $this->load_default_resources();
        
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
        if($this->agent->is_mobile())
            $data['isMobile']='yes';
        else
            $data['isMobile']='no';
        $data['categoryMenu']=  $this->get_category_menu();
        $data['html_heading']=$this->load->view('html_heading',$data,true);
        return $data;
    }
    
    function get_category_menu(){
        $TopCategoryData=$this->Category_model->get_top_category_for_product_list();
        //$AllButtomCategoryData=$this->Category_model->buttom_category_for_product_list();
        foreach($TopCategoryData as $k){
            $SubCateory=$this->Category_model->get_subcategory_by_category_id($k->categoryId);
            if(count($SubCateory)>0){
                foreach($SubCateory as $kk => $vv){
                    $ThirdCateory=$this->Category_model->get_subcategory_by_category_id($vv->categoryId);
                    if(count($ThirdCateory)>0){
                        foreach($ThirdCateory AS $k3 => $v3){
                            // now going for 4rath
                            //$menuArr[$v3->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName;
                            $FourthCateory=$this->Category_model->get_subcategory_by_category_id($v3->categoryId);
                            if(count($FourthCateory)>0){ //print_r($v3);die;
                                /*foreach($FourthCateory AS $k4 => $v4){
                                    $menuArr[$v4->categoryId]=$k->categoryName.' -> '.$vv->categoryName.' -> '.$v3->categoryName.' -> '.$v4->categoryName;
                                }*/
                                $v3->SubCategory=$FourthCateory;
                            }
                        }
                        $vv->SubCategory=$ThirdCateory;
                    }
                }
                $k->SubCategory=$SubCateory;
            }
        }
        return $TopCategoryData;
    }

    private function get_general_meta($data){
        $data=array();
        return $data;
        /*$this->load->model('Custom_page_seo_model');
        if($this->session->userdata('USER_SHIPPING_COUNTRY')==1){
            // for USA
            $HomePageInfoArr=  $this->Custom_page_seo_model->get_details(3);
        }else if($this->session->userdata('USER_SHIPPING_COUNTRY')==99){
            // India
            $HomePageInfoArr=  $this->Custom_page_seo_model->get_details(2);
        }else{
            // for Others
            $HomePageInfoArr=  $this->Custom_page_seo_model->get_details(4);
        }*/
        $HomePageInfoArr=array();

        $meta=$this->getDefaultMetaTag();
        $meta[]=array('name' => 'description', 'content' => $HomePageInfoArr['Description']);
        $meta[]=array('name' => 'keywords', 'content' =>$HomePageInfoArr['Keywords']);

        /*if($HomePageInfoArr['abstract']!="") $meta[]=array('name'=>'abstract','content'=>$HomePageInfoArr['abstract']);
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
        if($HomePageInfoArr['ICBM']!="") $meta[]=array('name'=>'ICBM','content'=>$HomePageInfoArr['ICBM']);*/


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
    
    /**
     * Get Current Logged in User data
     * @return Object User Data
     */
    function _get_current_user_details(){        
        $user = $this->session->userdata('FE_SESSION_UDATA');
        return $user;
    }
    
    function global_pagination($page,$config) {
	$this->load->library('pagination');
	$config['base_url'] = base_url().$page;
        
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";

        
        $this->pagination->initialize($config);
	return $this->pagination->create_links();

    }
    
    function _global_tidiit_mail($to,$subject,$dataResources,$tempplateName="",$toName=""){
        $message='';
        if($tempplateName==""){
            $message=$dataResources;
        }else{
            $message=  $this->load->view('email_template/'.$tempplateName,$dataResources,TRUE);
        }
        //echo $message.' === '.$to;die;
        $config = Array(
            'mailtype'  => 'html',
            'charset' => 'utf-8',
            'wordwrap' => TRUE
        );
        $this->load->library('email', $config);
        $this->email->from("no-reply@tidiit.com", 'Tidiit System Administrator');
        if($toName!="")
            $this->email->to($to);
        else
            $this->email->to($to);
        
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    }
    
    
    function get_site_categories_view(){
        $TopCategoryData=$this->Category_model->get_site_categories();
        ob_start();?>
        <ul class="categrs">
        <?php foreach($TopCategoryData as $key => $cat):?>
        <li>
            <a href="javascript:void(0);"><?php echo my_seo_freindly_url($cat->categoryName);?> <span>&nbsp;</span></a>
            <?php if(isset($cat->parent) && $cat->parent):?>
            <ul class="sub_catgrs">
                <?php 
                foreach($cat->parent As $pkey =>$pcat): //pre($v);die;?>
                <li>
                    <a href="<?php echo BASE_URL.'products/'.my_seo_freindly_url($pcat->categoryName).'/?cpid='.base64_encode($pcat->categoryId*226201);?>&sort=popular"><?php echo $pcat->categoryName;?> &ensp;<i class="fa fa-angle-right dsktp"></i><i class="fa fa-angle-down mobl_vrsn"></i></a>
                    <?php if(isset($pcat->parent) && $pcat->parent): 
                        $this->multi_cat_menu_list_design($pcat->parent);
                    endif;?>
                </li>
                <?php endforeach;?>
            </ul>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
        </ul>
        <?php 
        $menu = ob_get_contents();
        ob_get_clean();
        return $menu;
    }
    
    function multi_cat_menu_list_design($pcat){        
    ?>
        <ul class="sub_sub_ctgrs">
            <?php foreach($pcat AS $pkey => $cat):?>
            <li>
                <a href="<?php echo BASE_URL.'products/'.my_seo_freindly_url($cat->categoryName).'/?cpid='.base64_encode($cat->categoryId*226201);?>&sort=popular"><?php echo $cat->categoryName;?>&nbsp;<i class="fa fa-angle-down mobl_vrsn"></i></a>
                <?php if(isset($cat->parent) && $cat->parent):
                    $this->multi_cat_menu_list_design($cat->parent);
                endif;?>
            </li>
            <?php endforeach;?>
        </ul><?php
    }
    
    
    
    function get_site_categories_fixed_menu(){
        $TopCategoryData=$this->Category_model->get_site_categories();
        ob_start();?>
        <ul class="dropdown-menu multi-level fixed-menu fixed-menu-cat" role="menu" aria-labelledby="dropdownMenu" >
        <?php foreach($TopCategoryData as $key => $cat):?>
        <li <?php if(isset($cat->parent) && $cat->parent):?>class="dropdown-submenu"<?php endif; ?>>
            <a href="javascript:void(0);"><?php echo my_seo_freindly_url($cat->categoryName);?> <span>&nbsp;</span></a>
            <?php if(isset($cat->parent) && $cat->parent):?>
            <ul class="dropdown-menu">
                <?php 
                foreach($cat->parent As $pkey =>$pcat): //pre($v);die;?>
                <li <?php if(isset($pcat->parent) && $pcat->parent):?>class="dropdown-submenu"<?php endif; ?>>
                    <a href="<?php echo BASE_URL.'products/'.my_seo_freindly_url($pcat->categoryName).'/?cpid='.base64_encode($pcat->categoryId*226201);?>&sort=popular"><?php echo $pcat->categoryName;?></a>
                    <?php if(isset($pcat->parent) && $pcat->parent): 
                        $this->multi_cat_menu_fixed_list_menu($pcat->parent);
                    endif;?>
                </li>
                <?php endforeach;?>
            </ul>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
        </ul>
        <?php 
        $menu = ob_get_contents();
        ob_get_clean();
        return $menu;
    }
    
    function multi_cat_menu_fixed_list_menu($pcat){        
    ?>
        <ul class="dropdown-menu">
            <?php foreach($pcat AS $pkey => $cat):?>
            <li <?php if(isset($cat->parent) && $cat->parent):?>class="dropdown-submenu"<?php endif; ?>>
                <a href="<?php echo BASE_URL.'products/'.my_seo_freindly_url($cat->categoryName).'/?cpid='.base64_encode($cat->categoryId*226201);?>&sort=popular"><?php echo $cat->categoryName;?></a>
                <?php if(isset($cat->parent) && $cat->parent):
                    $this->multi_cat_menu_fixed_list_menu($cat->parent);
                endif;?>
            </li>
            <?php endforeach;?>
        </ul><?php
    }
    
    
    function get_site_categories_float_menu(){
        $TopCategoryData=$this->Category_model->get_site_categories();
        ob_start();?>
        <ul class="dropdown-menu multi-level float-menu" role="menu" aria-labelledby="dropdownMenu" >
        <?php foreach($TopCategoryData as $key => $cat):?>
        <li <?php if(isset($cat->parent) && $cat->parent):?>class="dropdown-submenu"<?php endif; ?>>
            <a href="javascript:void(0);"><?php echo my_seo_freindly_url($cat->categoryName);?> <span>&nbsp;</span></a>
            <?php if(isset($cat->parent) && $cat->parent):?>
            <ul class="dropdown-menu">
                <?php 
                foreach($cat->parent As $pkey =>$pcat): //pre($v);die;?>
                <li <?php if(isset($pcat->parent) && $pcat->parent):?>class="dropdown-submenu"<?php endif; ?>>
                    <a href="<?php echo BASE_URL.'products/'.my_seo_freindly_url($pcat->categoryName).'/?cpid='.base64_encode($pcat->categoryId*226201);?>&sort=popular"><?php echo $pcat->categoryName;?></a>
                    <?php if(isset($pcat->parent) && $pcat->parent): 
                        $this->multi_cat_menu_float_list_menu($pcat->parent);
                    endif;?>
                </li>
                <?php endforeach;?>
            </ul>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
        </ul>
        <?php 
        $menu = ob_get_contents();
        ob_get_clean();
        return $menu;
    }
    
    function multi_cat_menu_float_list_menu($pcat){        
    ?>
        <ul class="dropdown-menu">
            <?php foreach($pcat AS $pkey => $cat):?>
            <li <?php if(isset($cat->parent) && $cat->parent):?>class="dropdown-submenu"<?php endif; ?>>
                <a href="<?php echo BASE_URL.'products/'.my_seo_freindly_url($cat->categoryName).'/?cpid='.base64_encode($cat->categoryId*226201);?>&sort=popular"><?php echo $cat->categoryName;?></a>
                <?php if(isset($cat->parent) && $cat->parent):
                    $this->multi_cat_menu_float_list_menu($cat->parent);
                endif;?>
            </li>
            <?php endforeach;?>
        </ul><?php
    }
}