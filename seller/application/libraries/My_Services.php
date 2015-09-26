<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_Services {
    public function request_services($model_name,$model_function,$param_arrr){
        $CI =& get_instance();
        $Services_URL=$CI->config->item('Services_URL'); 
        $encodeData= base64_encode(serialize($param_arrr));
        $ServieData=file_get_contents($Services_URL.$model_name.'/'.$model_function.'/'.$encodeData);
        return $ServieData;
    }
}

/* End of file Someclass.php */