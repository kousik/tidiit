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
        $result['site_product_image_url']='http://seller.tidiit.com/resources/product/';
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
        $data['SiteProductImageURL']='http://seller.tidiit.com/resources/product/';
        return $data;
	}
}

if ( ! function_exists('global_tidiit_mail')){
	function global_tidiit_mail($to,$subject,$dataResources,$tempplateName="",$toName=""){
		$CI=& get_instance();
            //pre($dataResources);    
            $message='';
            if($tempplateName==""){
                $message=$dataResources;
            }else{
                $message=  $CI->load->view('email_template/'.$tempplateName,$dataResources,TRUE);
            }
            //echo $message;//die;
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
        
        //die('rrr');
        if(!array_key_exists('receiverMobileNumber', $data)){
            return FALSE;
        }elseif($data['receiverMobileNumber']==""){
            return FALSE;
        }else{
            $dataMessage = $data['nMessage'].' message send mobile no '.$data['receiverMobileNumber'];;
            $smsLogFile=$smsLogPath.time().uniqid().'.txt';
            if ( ! write_file($smsLogFile, $dataMessage)){
                 //echo 'Unable to write the file';
            }else{
                 //echo 'File written!';
            }
            //Send Mobile message
            $smsAddHistoryDataArr=array();
            $smsConfig=array('sms_text'=>$data['nMessage'],'receive_phone_number'=>$data['receiverMobileNumber']);
            $smsResult=$CI->tidiitsms->send_sms($smsConfig);
            send_normal_push_notification($data);   
            
            if(!array_key_exists('senderId', $data)){
                $data['senderId']="";
            }
            
            if(!array_key_exists('receiverId', $data)){
                $data['receiverId']="";
            }
            
            if(!array_key_exists('senderMobileNumber', $data)){
                $data['senderMobileNumber']="";
            }
            
            if(!array_key_exists('nType', $data)){
                $data['nType']="";
            }
            
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
        //pre($jsondata);die;
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


if ( ! function_exists('get_currency_simble_from_lat_long')):
    function get_currency_simble_from_lat_long($lat,$long){
        //("country", $jsondata["results"][0]["address_components"]);
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false";
        // Make the HTTP request
        $data = @file_get_contents($url);
        // Parse the json response
        $jsondata = json_decode($data,true);
        //pre($jsondata);die;
        if(!empty($jsondata["results"])){
            foreach( $jsondata["results"][0]["address_components"] as $value) {
                if (in_array('country', $value["types"])) {
                    if($value["short_name"]=="IN"){
                        return 'Rs';
                    }else{
                        return 'KSh';
                    }
                }
            }
        }else{
            return FALSE;
        }
        //return $jsondata["results"][0]["formatted_address"];
    }
endif;


if ( ! function_exists('get_formatted_address_from_lat_long')):
    function get_formatted_address_from_lat_long($lat,$long){
        //("country", $jsondata["results"][0]["address_components"]);
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false";
        // Make the HTTP request
        $data = @file_get_contents($url);
        // Parse the json response
        $jsondata = json_decode($data,true);
        
        if(array_key_exists('formatted_address', $jsondata["results"][0])){
            return $jsondata["results"][0]["formatted_address"];
        }else{
            return FALSE;
        }
        //return $jsondata["results"][0]["formatted_address"];
    }
endif;

if( !function_exists('send_push_notification')){
    function send_push_notification($data){
        $CI=& get_instance();
        $CI->load->model('User_model','user');
        $CI->load->model('Siteconfig_model','siteconfig');
        //$CI->load->library('tidiitsms');
        /*
        $notify['senderId'] = ;
        $notify['receiverId'] = ;
        $notify['nType'] = ;
        $notify['nTitle'] = ;
        $notify['nMessage'] = ;
         */
         

        //die('rrr');
        if(!array_key_exists('receiverId', $data)){
            return FALSE;
        }elseif($data['receiverId']==""){
            return FALSE;
        }else{
            $regIds=$CI->user->get_reg_id_by_user_id($data['receiverId']);
            if($regIds!=FALSE){
                //'data' =>
                $apiData=array('message'=>$data['nMessage'],'userId'=>$data['receiverId']);
                $regIdArr=array();
                foreach($regIds AS $k){
                    $regIdArr[]=$k->registrationId;
                }
                $fields=array('registration_ids'=>$regIdArr);
                if($data['nType']=="BUYING-CLUB-ADD" || $data['nType']=="BUYING-CLUB-MODIFY" || $data['nType']=="BUYING-CLUB-MODIFY-NEW" || $data['nType']=="BUYING-CLUB-MODIFY-DELETE"){
                    //$apiData['notificationType']=$data['nType'];
                }else if($data['nType']=="BUYING-CLUB-ORDER-DECLINE"){
                    $apiData['notificationId']=$data['notificationId'];
                }else if($data['nType']=="BUYING-CLUB-ORDER"){
                    $apiData['notificationId']=$data['notificationId'];
                }
                $apiData['tagStr']=$data['nType'];
            }else{
                return FALSE;
            }
        }
    }
}


if(!function_exists('send_normal_push_notification')){
    function send_normal_push_notification($data){
        $CI=& get_instance();
        $CI->load->model('User_model','user');
        $CI->load->model('Siteconfig_model','siteconfig');
        //$CI->load->library('tidiitsms');
        /*
        $notify['senderId'] = ;
        $notify['receiverId'] = ;
        $notify['nType'] = ;
        $notify['nTitle'] = ;
        $notify['nMessage'] = ;
         */
         
        //die('rrr');
        if(!array_key_exists('receiverId', $data)){
            return FALSE;
        }elseif($data['receiverId']==""){
            return FALSE;
        }else{
            $regIds=$CI->user->get_reg_id_by_user_id($data['receiverId']);
            if($regIds!=FALSE){
                $regIdArr=array();
                foreach($regIds AS $k){
                    @mail('judhisahoo@gmail.com','making ready for push notification for reg id '.$k->registrationId,'making ready for push notification for reg id '.$k->registrationId);
                    $regIdArr[]=$k->registrationId;
                    @mail('judhisahoo@gmail.com','reg id '.$k->registrationId.' ready for push notification','reg id '.$k->registrationId.' ready for push notification');
                }
                $fields=array('registration_ids'=>$regIdArr,'data' =>array('message'=>$data['nMessage']));
                if(send_gsm_message($fields)==TRUE){
                    foreach($regIds as $k){
                        $dataArr[]=array('messsage'=>$data['nMessage'],'registrationNo'=>$k->registrationId,'deviceType'=>'android','sendTime'=>date('Y-m-d H:i:s'),'userId'=>$data['receiverId']);
                    }
                    $CI->user->save_push_notification_history($dataArr);
                }
            }else{
                return FALSE;
            }
        }
    }
}

if( !function_exists('send_gsm_message')){
    function send_gsm_message($fields){
        $CI=& get_instance();
        $CI->load->config('product');
        @mail('judhisahoo@gmail.com','make ready for GoogleGSMKEY for message to GSM server ','make ready for GoogleGSMKEY for message to GSM server ');
        $GOOGLE_API_KEY=$CI->config->item('GoogleGSMKEY');
        @mail('judhisahoo@gmail.com','GSM API Key is '.$GOOGLE_API_KEY,'GSM API Key is '.$GOOGLE_API_KEY);
        $url = 'https://android.googleapis.com/gcm/send';

        $headers = array(
            'Authorization: key=' .$GOOGLE_API_KEY ,
            'Content-Type: application/json'
        );
        @mail('judhisahoo@gmail.com','Opening conection for google GSM server ','Opening conection for google GSM server ');
        // Open connection
        $ch = curl_init();
        @mail('judhisahoo@gmail.com','reg ids are - '.json_encode($fields),'reg ids are - '.json_encode($fields));
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);
        if ($result === FALSE) {
            @mail('judhisahoo@gmail.com','Google GSM server return fail','Google GSM server return fail');
            //die('Curl failed: ' . curl_error($ch));
            return FALSE;
        }else{
            @mail('judhisahoo@gmail.com','Google GSM server return success','Google GSM server return success');
            return TRUE;
        }
    }
}
