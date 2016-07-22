<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

class MY_location_track
{

    var $CI;

    function __construct ( )
    {

        $this->CI = & get_instance();
    }
    
    function get_and_set_location(){
        $this->CI->load->library('session');
        $userLocation="";
        $userLocation=$this->CI->session->userdata('FE_SESSION_USER_LOCATION_VAR');
        if(!defined($userLocation)){
            $userLocation="";
        }
        if($userLocation==""){
            $cIP=$this->CI->input->ip_address();
            //$cIP="196.201.216.170";
            if($cIP=='127.0.0.1'){
                $cIP='117.214.82.169';
            }
            
            /*$params = getopt('l:i:');
            if (!isset($params['l'])) $params['l'] = 'puDQd5MDgVxy';
            //if (!isset($params['i'])) $params['i'] = '24.24.24.24';
            if (!isset($params['i'])) $params['i'] = $cIP; //'122.177.246.210';
            //if (!isset($params['i'])) $params['i'] = '122.177.246.210';
            
            //$cLocationvar=file_get_contents("http://ipinfo.io/$cIP/country");
            
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

            /*$curl = curl_init();
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
            $insights = array_combine($insights_keys, $insights_values);*/
            //echo $insights['country_code'];die;
            //$this->CI->session->set_userdata('FE_SESSION_USER_LOCATION_VAR',trim($cLocationvar));
            
            //$this->CI->session->set_userdata('FE_SESSION_USER_LOCATION_VAR',$insights['country_code']);
            $url = 'http://ip-api.com/json/'.$cIP;
            $json = json_decode(@file_get_contents($url));
            //echo '<pre>';print_r($json);die;
            if(!$json){
                $this->CI->session->set_userdata('FE_SESSION_USER_LOCATION_VAR','IN');
            }else{
                if($json->status="success"){
                //echo $json->countryCode;die;
                    $this->CI->session->set_userdata('FE_SESSION_USER_LOCATION_VAR',$json->countryCode);
                }else{
                    $this->CI->session->set_userdata('FE_SESSION_USER_LOCATION_VAR','IN');
                }
            }
        }
    }

}