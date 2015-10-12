<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* Design Model
*
* package ACE
* design page functionality have save, update, edit, delete, active and inactive
* @access public
*/
class Api_Model extends CI_Model{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        //$this->load->database('publish');
    }
    
    function get_event(){
        $Arr=$this->db->from('event')->get()->result();
        $AppInfo=$this->db->from('system_constants')->where('ConstantName','AppInfo')->get()->result();
        $AppAppleLocation=$this->db->from('system_constants')->where('ConstantName','LocationServiceMessageApple')->get()->result();
        $AppGooleLocation=$this->db->from('system_constants')->where('ConstantName','LocationServiceMessageGoole')->get()->result();
        foreach($Arr as $k){
            $k ->info=$AppInfo[0]->ConstantValue;
            $k ->apple_location_service_text=$AppAppleLocation[0]->ConstantValue;
            $k ->google_location_service_text=$AppGooleLocation[0]->ConstantValue;
            $k ->event_log_confirmation_text="Are you sure to log this event ?";
        }
        return $Arr;
    }
    
    function log_event($arr){
        $this->db->insert('log_event',$arr);
        return $this->db->insert_id();
    }
    
    function get_userimages($id, $timestamp)
    {
        $this->db->select('image_id, user_id, image_name, attribute_key, crop_data, is_active');
        $this->db->where_in('user_id', array($id,0));
        $this->db->where('modified_date >=', date('Y-m-d H:i:s', $timestamp));
        $query = $this->db->get('app_images');
        return $query->result();
        //echo json_encode($query->result());
    }
    
    function get_appid($id)
    {
        $this->db->select('app_id');
        $this->db->where('user_id',$id);
        $this->db->where('is_publish',0);
        $query = $this->db->get('user_app_info');
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            return $row->app_id;
        }
        else
            return "-1";
    }
    
    function get_buttons($appid, $timestamp)
    {
        $this->db->select('business_info_text, business_info_image, offerings_text, offerings_image, intraction_text, intraction_image, koifee_mail_text, koifee_mail_image, social_media_text, social_media_image, contact_text, contact_image, navigation_image, navigation_color, navigation_type, navigation_opacity, navigation_divider_color,selected_app_menu_color, koifee_message, share_text, share_icon, sub_menu_icon, arrow_icon');
        $this->db->where('app_id',$appid);
        $this->db->where('modified_date >=', date('Y-m-d H:i:s', $timestamp));
        $query = $this->db->get('app_buttons');
        return $query->result();
    }
    
    function get_design($appid, $timestamp)
    {
        $this->db->select('header_image, header_color, header_type, background_image, background_color, background_type, background_opacity,interaction_form_header_color');
        $this->db->where('app_id',$appid);
        $this->db->where('modified_date >=', date('Y-m-d H:i:s', $timestamp));
        $query = $this->db->get('app_design');
        return $query->result();
    }
    
    function get_fonts($appid, $timestamp)
    {
        $this->db->select('header_font, header_color,header_title_font,header_title_color, subheader_font, subheader_color, interaction_font, interaction_color, navigation_font, navigation_color, description_font, description_color');
        $this->db->where('app_id',$appid);
        $this->db->where('modified_date >=', date('Y-m-d H:i:s', $timestamp));
        $query = $this->db->get('app_fonts');
        return $query->result();
    }
    
    function get_masterfonts($timestamp)
    {
        $this->db->select('id, font_name, iphone_font, android_font');
        $this->db->where('modified_date >=', date('Y-m-d H:i:s', $timestamp));
        $query = $this->db->get('master_fonts');
        return $query->result();
    }
    
    function get_socialmedialinks($appid, $timestamp)
    {
        $query = $this->db->query("Select id, name, icon, url, is_deleted from app_social_media where app_id=".$appid." and modified_date >='".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
        /*
        $this->db->select('name, icon, url, is_deleted');
        $this->db->where('app_id',$appid);
        $this->db->where('modified_date >=', date('Y-m-d H:i:s', $timestamp));
        $query = $this->db->get('app_social_media');
        return $query->result();*/
    }
    
    function get_businessdata($appid, $timestamp)
    {
        /*
        $this->db->select('Id as id', 'name as title', 'position', 'ownerEl as parent_id', 'is_activemenu as is_active', 'menu_type', 'is_deleted', 'subtitle', 'banner_images', 'menu_image', 'main_image', 'description', 'testimonial_id', 'multimedia_id', 'phone', 'email');
        "Select Id as id, name as title, position, ownerEl as parent_id, is_activemenu as is_active, menu_type, is_deleted, subtitle, banner_images, menu_image, main_image, description, testimonial_id, multimedia_id, phone, email from business_tree where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'"
        $this->db->where('app_id',$appid);
        $this->db->where('modified_date >=', date('Y-m-d H:i:s', $timestamp));
        $query = $this->db->get('business_tree');*/
        $query = $this->db->query("Select Id as id, name as title, position, ownerEl as parent_id,slave, is_activemenu as is_active, menu_type, is_deleted, subtitle, banner_images, menu_image, main_image, description, testimonial_id, multimedia_id, phone, email from business_tree where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }
    
    function get_multimediadata($appid, $timestamp)
    {
        $query = $this->db->query("SELECT id, filedisplayname AS 'display_name', filename, iconid AS 'image_id', filetype, POSITION, is_deleted,type FROM app_multimedia_files WHERE app_id =".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }
    
    function get_testimonialdata($appid, $timestamp)
    {
        $query = $this->db->query("SELECT Id AS 'id', name AS 'title', subtitle, banner_images, description, is_deleted from app_testimonial_data WHERE app_id =".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        //echo "SELECT Id AS 'id', name AS 'title', subtitle, banner_images, description, is_deleted from app_testimonial_data WHERE app_id =".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'";
        return $query->result();        
    }
    
    function get_offeringdata($appid, $timestamp)
    {
        $query = $this->db->query("Select Id as id, name as title, position, ownerEl as parent_id, is_activemenu as is_active, menu_type, is_deleted, subtitle, banner_images, menu_image, main_image, description, multimedia_id, phone, email from app_offerings where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }
    
    function get_appointmentdata($appid, $timestamp)
    {
        $query = $this->db->query("Select id, time_slot, lunch_time_start, lunch_time_end from app_appointment_data where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }
    
    function get_appointmentdays($appid, $timestamp)
    {
        $query = $this->db->query("Select id, weekday, start_time, end_time, IF(is_deleted=1,1,!is_active) as is_deleted from app_appointment_days where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }
    
    function get_appointmentfields($appid, $timestamp)
    {
        $query = $this->db->query("Select id, label, parent_id, value_type, IF(is_deleted=1,1,!is_active) as is_deleted from app_appointment_fields where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }
    
    function get_appointmenttitle($appid, $timestamp)
    {
        $query = $this->db->query("Select config_value from user_config where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."' AND `config_name`='AppointmentTitle'");
        return $query->result();
    }
    
    function get_locationdata($appid, $timestamp)
    {
        $query = $this->db->query("Select id, title, address, latitude, longitude, url, image_id, is_deleted from app_location where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }
    
    function get_locationattributes($appid, $timestamp)
    {
        $query = $this->db->query("Select id, location_id, key_type as value_type, label, key_value as value, is_deleted from app_location_attribute where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }

    function get_pagesdata($appid, $timestamp)
    {
        $query = $this->db->query("Select id, attribute_key,title,IF(attribute_key='invite',IF(page_data='','Please register to use all the features of this app.',page_data),page_data) AS page_data from app_pages where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }

    function get_registrationFormdata($appid, $timestamp)
    {
        $query = $this->db->query("Select id, label, value_type, is_mandatory, is_active from app_user_attributes where app_id=".$appid." and modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'");
        return $query->result();
    }

    function get_koifeemaildata($appuserid,$timestamp)
    {
        if($appuserid==0) return array();

        $this->db->select('app_mails.id, message_type, title, subtitle, description,  multimedia_ids, banner_images, app_mails.modified_date');
        $this->db->from('app_mail_users');
        $this->db->join('app_mails', 'app_mails.id=app_mail_users.mail_id', 'inner');
        $this->db->where('app_user_id', $appuserid);
        $this->db->where('app_mails.modified_date >= ',date('Y-m-d H:i:s', $timestamp));
        $query=$this->db->get();
        return $query->result();
    }
    
    function get_feedbackInquiryQusdata($appid,$timestamp)
    {
        $query = $this->db->select('afq.id as id, afq.question, afq.question_type,afq.attribute,afq.is_deleted, afq.image_id')                          
                          ->from('app_feedback_questions afq')
                          ->where('afq.app_id ', $appid)
                          ->where('afq.modified_date >= ',date('Y-m-d H:i:s', $timestamp))
                          ->order_by('afq.id', 'asc')
                          ->get();
        $result_array = $query->result_array();
        $query->free_result();

	return $result_array;
    }
    
    function get_feedbackInquiryAnsdata($appid,$timestamp)
    {
        $query = $this->db->select('afa.id as ans_id, afa.question_id,afa.answer,afa.attribute,afa.is_deleted')                          
                          ->from('app_feedback_answer afa')
                          ->where('afa.app_id ', $appid)
                          ->where('afa.modified_date >= ',date('Y-m-d H:i:s', $timestamp))
                          ->order_by('afa.id', 'asc')
                          ->get();
        $result_array = $query->result_array();
        $query->free_result();

	return $result_array;
    }

    /*
    * Api Email which is used in 
    * feedback, enquiry and appointment
    *
    * @param int app_id, int timestamp
    * @access public
    * @return array
    */
    function get_apiEmails($appid,$timestamp){
        
        $query = $this->db->select('feedback_email, appointment_email, enquiry_email')
                    ->from('app_emails')
                    ->where('app_id', $appid)
                    ->where('modified_date >= ',date('Y-m-d H:i:s', $timestamp))
                    ->get();

        $result_array = $query->result_array();
        $query->free_result();

        return $result_array;

    }
//    function get_feedbackInquirydata($appid,$timestamp,$type)
//    {
//        $query = $this->db->select('afq.id as id, afq.question, afq.question_type')
//                          ->select('afa.id as ans_id, afa.answer')
//                          ->from('app_feedback_questions afq')
//                          ->join('app_feedback_answer afa', 'afq.id=afa.question_id AND afq.app_id = afa.app_id and afa.is_deleted = 0', 'left')
//                          ->where('afq.app_id ', $appid)
//                          ->where('afq.attribute', $type)
//                          ->where('afq.is_deleted ', 0)
//                          ->where('afq.modified_date >= ',date('Y-m-d H:i:s', $timestamp))
//                          ->order_by('afq.id', 'asc')
//                          ->get();
//
//		$result_array = $query->result_array();
//		$query->free_result();
//
////		$dataArray = array();
////		foreach($result_array as $row){
////			$dataArray[$row['id']]['questionid'] = $row['id'];
////                        $dataArray[$row['id']]['question'] = $row['question'];
////			$dataArray[$row['id']]['question_type'] = $row['question_type']; 
////			$dataArray[$row['id']]['answer'][$row['ans_id']] = $row['answer']; 
////		}
//
//	return $result_array;
//    }
       function get_app_name_icon($appid, $timestamp)
    {
        $sql="Select uai.app_name,ai.* FROM user_app_info as uai JOIN app_images as ai ON(uai.image_id=ai.image_id)  WHERE uai.app_id=".$appid." AND uai.modified_date >= '".date('Y-m-d H:i:s', $timestamp)."'";
        $query = $this->db->query($sql);
        return $query->result();
    }
    
}