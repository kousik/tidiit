<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('short_words')){
    function short_words($str,$NoOfWorrd=20){
            $strArr=explode(' ',$str);	
            $shortStr='';
            if(count($strArr)<$NoOfWorrd)
                    $NoOfWorrd=count($strArr);
            for($i=0;$i<$NoOfWorrd;$i++){
                    if($i==0){
                            $shortStr=$strArr[$i];
                    }else{
                            $shortStr.=' '.$strArr[$i];
                    }
            }
            return $shortStr;
    }
}

if ( ! function_exists('isValidTimeStamp'))
{
    function isValidTimeStamp($timestamp)
    {
        return ((string) (int) $timestamp === $timestamp) 
            && ($timestamp <= PHP_INT_MAX)
            && ($timestamp >= ~PHP_INT_MAX);
    }   
}

if ( ! function_exists('my_seo_freindly_url')){
    function my_seo_freindly_url($String){
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
}

if ( ! function_exists('check_exists_BPO')){
    function check_exists_BPO($v,$rs){
            foreach($rs AS $k){
                    if($k['Objectives']==$v){
                            return true;
                    }
            }
            return false;
    }
}


if ( ! function_exists('pre')){
    function pre($var){ //die('rrr');
        echo '<pre>';//print_r($var);
        if(is_array($var) || is_object($var)) {
          print_r($var);
        } else {
          var_dump($var);
        }
        echo '</pre>';
    }
}

if ( ! function_exists('multiple_array_search')){
    function multiple_array_search($id,$column, $dataArray){ //die('rrr');
       foreach ($dataArray as $key => $val) {
           //echo $val[$column].' = '.$id .'<br>';
           if ($val[$column] === $id) {
               //echo 'PP';
               return $key;
           }else{
               //echo 'zzz';
           }
       }
       return FALSE;
    }
}

if(!function_exists('user_role_check')){
    function user_role_check($controller,$method){
        $CI=&get_instance();
        if($CI->session->userdata('ADMIN_SESSION_USER_VAR_TYPE')=='supper_admin'){
            return TRUE;
        }
        //$roleArr=$CI->se
        $found=FALSE;
        $roleVar=  unserialize($CI->session->userdata('ADMIN_ROLE_VAR'));
        //pre($roleVar);die;
        if(in_array($controller, $roleVar['controller'])){
            return TRUE;
        }else{
            return FALSE;
        }
        /*foreach($roleVar AS $k => $v){
            if($v['method']==$method && $v['controller']==$controller){
                return TRUE;
            }elseif($v['controller']==$controller){
                return TRUE;
            }
        }*/
    }
}

if ( ! function_exists('title_more_string')){
    function title_more_string($str,$no_char=22){
        $strArr=  explode(' ', $str);
        $strLen=0;
        $newStr='';
        foreach($strArr AS $k){
            $strLen=$strLen+strlen($k);
            if($strLen>$no_char){
                return $newStr.' .....';
            }
            $newStr .= $k.' ';
        }
        return $str;
    }
}


if ( ! function_exists('return_current_country_code')){
    function return_current_country_code(){
        return 'US';die;
        $ip=$_SERVER['REMOTE_ADDR'];
        
        $params = getopt('l:i:');
        if (!isset($params['l'])) $params['l'] = 'puDQd5MDgVxy';
        //if (!isset($params['i'])) $params['i'] = '24.24.24.24';
        //if (!isset($params['i'])) $params['i'] = $ip; //'122.177.246.210';
        if (!isset($params['i'])) $params['i'] = '122.177.246.210';

        $query = 'http://geoip.maxmind.com/a?' . http_build_query($params);

        $insights_keys =
          array(
            'country_code',
            'country_name',
            'region_code',
            'region_name',
            'city_name',
            'latitude',
            'longitude',
            'metro_code',
            'area_code',
            'time_zone',
            'continent_code',
            'postal_code',
            'isp_name',
            'organization_name',
            'domain',
            'as_number',
            'netspeed',
            'user_type',
            'accuracy_radius',
            'country_confidence',
            'city_confidence',
            'region_confidence',
            'postal_confidence',
            'error'
            );

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $query,
                CURLOPT_USERAGENT => 'MaxMind PHP Sample',
                CURLOPT_RETURNTRANSFER => true
            )
        );

        $resp = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception(
                'GeoIP request failed with a curl_errno of '
                . curl_errno($curl)
            );
        }

        $insights_values = str_getcsv($resp);
        $insights_values = array_pad($insights_values, sizeof($insights_keys), '');
        $insights = array_combine($insights_keys, $insights_values);
        return $insights['country_code'];
    }
}

if ( ! function_exists('success_response_after_post_get')){
    function success_response_after_post_get($parram=array()){
        $result=array();
        if(!array_key_exists('ajaxType', $parram)):
            $result=  get_default_urls();    
        endif;
        //$result['message']="Shipping address data updated successfully.";
        $result['timestamp'] = (string)mktime();
        if(!empty($parram)):
            foreach ($parram as $k => $v){
                $result[$k]=$v;
            }
        endif;
        
        header('Content-type: application/json');
        echo json_encode($result);
    }
}

if ( ! function_exists('get_default_urls')){
	function get_default_urls(){
		$result=array();
        $result['site_product_image_url']='http://seller.tidiit.com/resources/product/original/';
        //$result['site_image_url']=$this->config->item('MainSiteResourcesURL').'images/';
        $result['site_image_url']='http://tidiit.com/resources/images/';
        $result['site_slider_image_url']='http://tidiit.com/resources/banner/original/';
        $result['site_brand_image_url']='http://tidiit.com/resources/brand/original/';
        $result['site_category_image_url']='http://tidiit.com/resources/category/original/';
        $result['main_site_url']='http://www.tidiit.com/';
        return $result;
	}
}

if ( ! function_exists('load_default_resources')){
	function load_default_resources(){
		$data=array();
        $data['SiteImagesURL']='http://tidiit.com/resources/images/';
        $data['SiteCSSURL']='http://tidiit.com/resources/css/';
        $data['SiteJSURL']='http://tidiit.com/resources/js/';
        $data['SiteResourcesURL']='http://tidiit.com/resources/';
        $data['MainSiteBaseURL']='http://tidiit.com/';
        $data['MainSiteImagesURL']='http://tidiit.com/resources/images/';
        $data['SiteProductImageURL']='http://seller.tidiit.com/resources/product/100X100/';
        return $data;
	}
}

if ( ! function_exists('global_tidiit_mail')){
	function global_tidiit_mail($to,$subject,$dataResources,$tempplateName="",$toName=""){
		$CI=& get_instance();
            $message='';
            if($tempplateName==""){
                $message=$dataResources;
            }else{
                $message=  $CI->load->view('email_template/'.$tempplateName,$dataResources,TRUE);
            }
            ///echo $message;die;
            //echo $to;die;
            $CI->load->library('email');
            $CI->email->from("no-reply@tidiit.com", 'Tidiit System Administrator');
            if($toName!="")
                $CI->email->to($to,$toName);
            else
                $CI->email->to($to);

            $CI->email->subject($subject);
            $CI->email->message($message);
            $CI->email->send();
            //echo $CI->email->print_debugger();die;
	}
}

if ( ! function_exists('recusive_category')){
	function recusive_category($newCateoryArr,$categoryId){
		$CI=& get_instance();
		$CI->load->model('Category_model','category');
        $chieldCateArr=$CI->category->get_subcategory_by_category_id($categoryId);
        if(empty($chieldCateArr)){
            return $newCateoryArr;
        }else{    
            foreach($chieldCateArr AS $k){
                $newCateoryArr[]=$k->categoryId;
                $newCateoryArr=recusive_category($newCateoryArr, $k->categoryId);
            }
            return $newCateoryArr;
        }
    }
}

if ( ! function_exists('send_sms_notification')):
  function send_sms_notification($data){
    $CI=& get_instance();
    $CI->load->model('User_model','user');
    $CI->load->model('Siteconfig_model','siteconfig');
    $CI->load->library('tidiitsms');
    /*
    $notify['senderId'] = ;
    $notify['receiverId'] = ;
    $notify['nType'] = ;
    $notify['nTitle'] = ;
    $notify['nMessage'] = ;
     */
    $SMS_SEND_ALLOW=$CI->siteconfig->get_value_by_name('SMS_SEND_ALLOW');
    if($SMS_SEND_ALLOW=='yes'){
        $smsLogPath=MAIN_SERVER_RESOURCES_PATH.'sms_log/'.date('d-m-Y').'/';
        if(!is_dir($smsLogPath)){ //create the folder if it's not already exists
          @mkdir($smsLogPath,0755,TRUE);
        } 
        $data = $data['nMessage'];
        $smsLogFile=$smsLogPath.time().uniqid().'.txt';
        if ( ! write_file($smsLogFile, $data)){
             //echo 'Unable to write the file';
        }else{
             //echo 'File written!';
        }
        //die('rrr');
        if(!array_key_exists('receiverMobileNumber', $data)){
            return FALSE;
        }elseif($data['receiverMobileNumber']==""){
            return FALSE;
        }else{
            //Send Mobile message
            $smsAddHistoryDataArr=array();
            $smsConfig=array('sms_text'=>$data['nMessage'],'receive_phone_number'=>$data['receiverMobileNumber']);
            $smsResult=$CI->tidiitsms->send_sms($smsConfig);

            $smsAddHistoryDataArr=array('senderUserId'=>$data['senderId'],'receiverUserId'=>$data['receiverId'],
                'senderPhoneNumber'=>$data['senderMobileNumber'],'receiverPhoneNumber'=>$data['receiverMobileNumber'],
                'IP'=>  $CI->input->ip_address(),'sms'=>$data['nMessage'],'sendActionType'=>$data['nType'],
                'smsGatewaySenderId'=>$CI->siteconfig->get_value_by_name('SMS_GATEWAY_SENDERID'),'smsGatewayReturnData'=>$smsResult);
                $CI->user->add_sms_history($smsAddHistoryDataArr);
        }
    }
  }  
endif;

if ( ! function_exists('get_full_address_from_lat_long')):
    function get_full_address_from_lat_long($lat,$long){
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false";
        // Make the HTTP request
        $data = @file_get_contents($url);
        // Parse the json response
        $jsondata = json_decode($data,true);
        return $jsondata["results"][0]["formatted_address"];
    }
endif;

if ( ! function_exists('get_counry_code_from_lat_long')):
    function get_counry_code_from_lat_long($lat,$long){
        //("country", $jsondata["results"][0]["address_components"]);
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false";
        // Make the HTTP request
        $data = @file_get_contents($url);
        // Parse the json response
        $jsondata = json_decode($data,true);
        if(!empty($jsondata["results"])){
            foreach( $jsondata["results"][0]["address_components"] as $value) {
                if (in_array('country', $value["types"])) {
                    return $value["short_name"];
                }
            }
        }else{
            return FALSE;
        }
        //return $jsondata["results"][0]["formatted_address"];
    }
endif;