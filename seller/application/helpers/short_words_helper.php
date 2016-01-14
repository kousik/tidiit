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


if ( ! function_exists('my_seo_freindly_url')){
    function my_seo_freindly_url($String){
            $ChangedStr = preg_replace('/\%/',' percentage',$String);
            $ChangedStr = preg_replace('/\@/',' at ',$ChangedStr);
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

if ( ! function_exists('sortingProductPriceArr')){
    function sortingProductPriceArr($a, $b){
        return $a['qty'] - $b['qty'];
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
        foreach($CI->session->userdata('ADMIN_ROLE_VAR') AS $k => $v){
            if($v['method']==$method && $v['controller']==$controller){
                return TRUE;
            }
        }
        return FALSE;
    }
}

if ( ! function_exists('get_home_url')){
    function get_home_url(){
        $CI =& get_instance();
        $countryId=$CI->session->userdata('USER_SHIPPING_COUNTRY');
        if($countryId==1){
            return base_url().'send-online-gifts-usa';
        }else if($countryId==99){
            return base_url().'send-wine-cakes-flowers-online-india';
        }else if($countryId==240){
            return base_url().'send-gifts-worldwide';
        }
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
endif;

?>