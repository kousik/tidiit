<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*  @author :: Judhisthira Sahoo
*  @todo :: It will show Admin Dashboar Page and if not loged in then ask for login
*/
class Role extends MY_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('Role_model');
        $this->db->cache_off();
    }
    
    function index($roleGroupId=0){
        $this->_has_access('role', 'index');
        $data=$this->_show_admin_logedin_layout();
        //echo '<pre>';print_r($data);echo '</pre>';die;
        $data['DataArr']=$this->Role_model->get_all_role();
        $data['roleGroupDataArr']=$this->Role_model->get_all_role_group();
        $data['roleGroupId']=$roleGroupId;
        $this->load->view('webadmin/role_list',$data);
    }
    
    function group(){
        $data=$this->_show_admin_logedin_layout();
        //echo '<pre>';print_r();echo '</pre>';die;
        $data['DataArr']=$this->Role_model->get_all_role_group();
        $this->load->view('webadmin/role_group',$data);
    }
    
    
    function add_group(){
        $config = array(
           array(
                 'field'   => 'title',
                 'label'   => 'Role Group Title',
                 'rules'   => 'trim|required|xss_clean'
              ),
           array(
                 'field'   => 'accessTitle',
                 'label'   => 'Role Group Access Title',
                 'rules'   => 'trim|required|xss_clean'
              )
        );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
                //retun to list page with peroper error
                $error_msg=validation_errors();
                $this->session->set_flashdata('Message',$error_msg);
                redirect(base_url().'webadmin/role/group');
        }else{
                //Getting the data from POST and ceate the category and redirect to category list page
                $title=$this->input->post('title',TRUE);
                $accessTitle=$this->input->post('accessTitle',TRUE);
                
                $DataArr=array('title'=>$title,'accessTitle'=>$accessTitle);
                //pre($DataArr);die;
                $RetData=$this->Role_model->add_group($DataArr);
                if($RetData>0){
                        $this->session->set_flashdata('Message','Role Group '.title.' has Added Successfully.');
                }else{
                        $this->session->set_flashdata('Message','Unknown Error hapenning to add Role Group.');
                }
                redirect(base_url().'webadmin/role/group');
        }
    }
    
    function edit_group(){
        $config = array(
           array(
                 'field'   => 'Edittitle',
                 'label'   => 'Role Group Title',
                 'rules'   => 'trim|required|xss_clean'
              ),
           array(
                 'field'   => 'EditaccessTitle',
                 'label'   => 'Role Group Access Title',
                 'rules'   => 'trim|required|xss_clean'
              )
        );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        if($this->form_validation->run() == FALSE){
                //retun to list page with peroper error
                $error_msg=validation_errors();
                $this->session->set_flashdata('Message',$error_msg);
                redirect(base_url().'webadmin/role/group');
        }else{
                //Getting the data from POST and ceate the category and redirect to category list page
                $title=$this->input->post('Edittitle',TRUE);
                $accessTitle=$this->input->post('EditaccessTitle',TRUE);
                $roleGroupId=$this->input->post('roleGroupId',TRUE);
                
                $DataArr=array('title'=>$title,'accessTitle'=>$accessTitle);
                //echo '<pre>';print_r($DataArr);die;
                $RetData=$this->Role_model->edit_group($DataArr,$roleGroupId);
                if($RetData>0){
                        $this->session->set_flashdata('Message','Role Group '.title.' has Updated Successfully.');
                }else{
                        $this->session->set_flashdata('Message','Unknown Error hapenning to add Role Group.');
                }
                redirect(base_url().'webadmin/role/group');
        }
    }
    
    function group_change_status($roleGroupId){
        $Status=0;
        // getting the value form url and do the XSS clean operation and do the chnges and redirect
        $roleGroupId=$this->security->xss_clean($roleGroupId);
        $roleGroupData=$this->Role_model->get_role_group_data($roleGroupId);
        //print_r($roleGroupData);die;
        if($roleGroupData[0]->status==1){
                $Status=$this->Role_model->edit_group(array('status'=>0),$roleGroupId);
        }else{
                $Status=$this->Role_model->edit_group(array('status'=>1),$roleGroupId);
        }
        if($Status){
                $this->session->set_flashdata('Message','Status Changed Successfully');
        }else{
                $this->session->set_flashdata('Message','Unknown error to change the status');
        }
        redirect(base_url().'webadmin/role/group');
    }
    
    function group_delete($roleGroupId){
        $Status=0;
        // getting the value form url and do the XSS clean operation and do the perform delete operation and redirect
        $roleGroupId=$this->security->xss_clean($roleGroupId);
        $Status=$this->Role_model->delete_group($roleGroupId);
        if($Status){
                $this->session->set_flashdata('Message','Record deleted Successfully');
        }else{
                $this->session->set_flashdata('Message','Unknown error to delete the record');
        }
        redirect(base_url().'webadmin/role/group');
    }
    
    
    function view_list($roleGroupId=0){
        $data=$this->_show_admin_logedin_layout();
        //echo '<pre>';print_r();echo '</pre>';die;
        $data['DataArr']=$this->Role_model->get_all_role($roleGroupId);
        $data['roleGroupId']=$roleGroupId;
        $data['roleGroupDataArr']=$this->Role_model->get_all_role_group();
        $this->load->view('webadmin/role_list',$data);
    }
    
    function add(){
        $config = array(
           array(
                 'field'   => 'roleTitle',
                 'label'   => 'Role Title',
                 'rules'   => 'trim|required|xss_clean'
              ),
           array(
                 'field'   => 'roleAccessTitle',
                 'label'   => 'Role Access Title',
                 'rules'   => 'trim|required|xss_clean'
              )
            ,
           array(
                 'field'   => 'roleGroupId',
                 'label'   => 'Role Group Index',
                 'rules'   => 'trim|required|xss_clean'
              )
        );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        //pre($_POST);die;
        $roleGroupId=$this->input->post('roleGroupId',TRUE);
        //echo $roleGroupId;die;
        if($this->form_validation->run() == FALSE){
                //retun to list page with peroper error
                $error_msg=validation_errors();
                $this->session->set_flashdata('Message',$error_msg);
                redirect(base_url().'webadmin/role/view_list/'.$roleGroupId);
        }else{
                //Getting the data from POST and ceate the category and redirect to category list page
                $roleTitle=$this->input->post('roleTitle',TRUE);
                $roleAccessTitle=$this->input->post('roleAccessTitle',TRUE);
                
                $DataArr=array('roleTitle'=>$roleTitle,'roleAccessTitle'=>$roleAccessTitle,'roleGroupId'=>$roleGroupId);
                //echo '<pre>';print_r($DataArr);die;
                $RetData=$this->Role_model->add($DataArr);
                if($RetData>0){
                        $this->session->set_flashdata('Message','Role '.title.' has Added Successfully.');
                }else{
                        $this->session->set_flashdata('Message','Unknown Error hapenning to add Role.');
                }
                redirect(base_url().'webadmin/role/view_list/'.$roleGroupId);
        }
    }
    
    function edit(){
        $config = array(
           array(
                 'field'   => 'EditroleTitle',
                 'label'   => 'Role Title',
                 'rules'   => 'trim|required|xss_clean'
              ),
           array(
                 'field'   => 'EditroleAccessTitle',
                 'label'   => 'Role Access Title',
                 'rules'   => 'trim|required|xss_clean'
              )
            ,
           array(
                 'field'   => 'EditroleGroupId',
                 'label'   => 'Role Group Index',
                 'rules'   => 'trim|required|xss_clean'
              ),
            array(
                 'field'   => 'roleId',
                 'label'   => 'Role Index',
                 'rules'   => 'trim|required|xss_clean'
              )
        );
        //initialise the rules with validatiion helper
        $this->form_validation->set_rules($config); 
        //checking validation
        $roleGroupId=$this->input->post('roleGroupId',TRUE);
        $roleId=$this->input->post('roleId',TRUE);
        if($this->form_validation->run() == FALSE){
                //retun to list page with peroper error
                $error_msg=validation_errors();
                $this->session->set_flashdata('Message',$error_msg);
                redirect(base_url().'webadmin/role/view_list/'.$roleGroupId);
        }else{
                //Getting the data from POST and ceate the category and redirect to category list page
                $roleTitle=$this->input->post('EditroleTitle',TRUE);
                $roleAccessTitle=$this->input->post('EditroleAccessTitle',TRUE);
                
                $DataArr=array('roleTitle'=>$roleTitle,'roleAccessTitle'=>$roleAccessTitle,'roleGroupId'=>$roleGroupId);
                //echo '<pre>';print_r($DataArr);die;
                $RetData=$this->Role_model->edit($DataArr,$roleId);
                if($RetData>0){
                        $this->session->set_flashdata('Message','Role '.title.' has updated Successfully.');
                }else{
                        $this->session->set_flashdata('Message','Unknown Error hapenning to add Role.');
                }
                redirect(base_url().'webadmin/role/view_list/'.$roleGroupId);
        }
    }
    
    function delete($roleId,$roleGroupId=0){
        $Status=0;
        // getting the value form url and do the XSS clean operation and do the perform delete operation and redirect
        $roleGroupId=$this->security->xss_clean($roleGroupId);
        $roleId=$this->security->xss_clean($roleId);
        $Status=$this->Role_model->delete($roleId);
        if($Status){
                $this->session->set_flashdata('Message','Record deleted Successfully');
        }else{
                $this->session->set_flashdata('Message','Unknown error to delete the record');
        }
        redirect(base_url().'webadmin/role/view_list/'.$roleGroupId);
    }
}