<?php
/**
 * PHP QR Code porting for Codeigniter
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @porting author	kousik.das.btech@gmail.com
 * 
 * @version		1.0
 */
 
class Tidiitsms
{
    var $CI;
    private $gateway_url = 'http://bhashsms.com/api/sendmsg.php?';
    private $gateway_user = 'judhisahoo';
    private $gateway_user_password = '';
    private $gateway_sender_id = '';
    private $receive_phone_number = '';
    private $sms_text = '';
    private $sms_priority = 'ndnd';
    private $sms_stype = 'normal';
    private $result;

    function __construct() {
        $this->CI = & get_instance();
        // call original library
        $this->initialize();
    }

    public function initialize() {
        $this->CI->load->model('Siteconfig_model');
        $this->gateway_user=$this->CI->Siteconfig_model->get_value_by_name('SMS_GATEWAY_USERID');
        $this->gateway_user_password=$this->CI->Siteconfig_model->get_value_by_name('SMS_GATEWAY_PASSWORD');
        $this->gateway_sender_id=$this->CI->Siteconfig_model->get_value_by_name('SMS_GATEWAY_SENDERID');
    }

    public function send_sms($params = array()) {
        if(!isset($params['sms_text'])):
            return FALSE;
        else:
            $this->sms_text=urlencode($params['sms_text']);
        endif;
        
        if(!isset($params['receive_phone_number'])):
            return FALSE;
        else:
            $this->receive_phone_number=$params['receive_phone_number'];
        endif;
        $data="user=".$this->gateway_user."&pass=".$this->gateway_user_password."&sender=".$this->gateway_sender_id."&phone=".$this->receive_phone_number."&text=".$this->sms_text."&priority=".$this->sms_priority."&stype=".$this->sms_stype;
        $ch = curl_init($this->gateway_url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$this->result = curl_exec($ch); // This is the result from the API
	curl_close($ch);
        return $this->result;
    }
}