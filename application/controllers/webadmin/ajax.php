<?php 
class Ajax extends MY_Controller{
    public function __construct(){
            parent::__construct();
            $this->load->model('User_model');
            $this->load->model('Country');
            $this->db->cache_off();
    }
    
    public function get_state_checkbox(){
        $this->load->model('Country');
        $countryId=$this->input->post('countryId',TRUE);
        $StateData=$this->Country->get_state_country($countryId);
        $html='';
        $i=0;
        if(count($StateData)>0){
                $html='<div id="addCheckAll" style="font-weight:bold;font-size:12px;text-decoration: underline; cursor: pointer;padding-bottom: 5px;">Check All</div>
                        <div id="addUnCheckAll" style="display:none;font-weight:bold;font-size:12px;text-decoration: underline; cursor: pointer;padding-bottom: 5px;">UnCheck All</div>
                <div style="clear:both;"></div>';
                foreach($StateData as $k){
                        $html.='<div style="width:180px;float:left;"><input type="checkbox" name="stateId[]" value="'.$k->stateId.'" />'.$k->stateName.'</div>';
                        if($i==3){
                                $html.='<div style="clear:both;"></div>';
                                $i=0;
                        }
                        $i++;
                }
        }else{
                $html='There is no state for select country';	
        }
        echo $html;die;
    }   

    public function get_edit_state(){
            $this->load->model('Country');
            $countryId=$this->input->post('countryId',TRUE);
            $StateData=$this->Country->get_state_country($countryId);
            $html='<select id="EditstateId" name="EditstateId" class="required">';
            $html .= '<option value="">Select</soption>';
            foreach($StateData AS $k){
                    $html .='<option value="'.$k->stateId.'">'.$k->stateName.'</soption>';
            }
            $html .= '</select>';
            echo $html;die;
    }
    
    public function get_exist_geozone_state(){
        $this->load->model('Zeozone_model');
        $this->load->model("Country");
        $CountryID=$this->input->post('countryId',TRUE);
        $ZeoZoneID=$this->input->post('zeoZoneId',TRUE);
        $StateDataArr=$this->Country->get_state_country($CountryID);
        $ZeozoneStateData=$this->Zeozone_model->get_zeoone_statte_name_by_zeo_counrty($CountryID,$ZeoZoneID);
        $html='';
        $i=0;
        $html='<div id="addCheckAll" style="font-weight:bold;font-size:12px;text-decoration: underline; cursor: pointer;padding-bottom: 5px;">Check All</div>
                        <div id="addUnCheckAll" style="display:none;font-weight:bold;font-size:12px;text-decoration: underline; cursor: pointer;padding-bottom: 5px;">UnCheck All</div>
                <div style="clear:both;"></div>';
        foreach($StateDataArr AS $k){
            if($this->isStateExistsInZeoZone($ZeozoneStateData,$k->stateId)==TRUE){
                $ch='checked';
            }else{
                $ch='';
            }
            $html.='<div style="width:180px;float:left;"><input type="checkbox" name="stateId[]" value="'.$k->stateId.'" '.$ch.' />'.$k->stateName.'</div>';
            if($i==3){
                $html.='<div style="clear:both;"></div>';
                $i=0;
            }
            $i++;
        }
        echo $html;die;
    }

    public function isStateExistsInZeoZone($StateDataArr,$stateId){
        foreach($StateDataArr As $k){
            if($k->stateId==$stateId){
                    return TRUE;
            }
        }
        return FALSE;
    }
    
    public function is_user_name_exists(){
        $userName=  $this->input->post('userName',TRUE);
        if($this->User_model->check_username_exists($userName)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
    
    public function is_user_email_exists(){
        $email=  $this->input->post('email',TRUE);
        if($this->User_model->check_user_email_exists($email)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
    
    function is_edit_user_email_exists(){
        $email=  $this->input->post('email',TRUE);
        $userId=  $this->input->post('userId',TRUE);
        if($this->User_model->check_edit_email_exists($email,$userId)){
            echo 1;die;
        }else{
            echo 0;die;
        }
    }
}
