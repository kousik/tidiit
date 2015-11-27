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
            if($cIP=='127.0.0.1'){
                $cIP='117.214.82.169';
            }
            
            $cLocationvar=file_get_contents("http://ipinfo.io/$cIP/country");
            $this->CI->session->set_userdata('FE_SESSION_USER_LOCATION_VAR',trim($cLocationvar));
        }
    }

}