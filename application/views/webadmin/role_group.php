<?php echo $AdminHomeLeftPanel;

//print_r($UserDataArr);die;
 $PageArr=$this->config->item('OtherPages4Banner'); 
 //pre($PageArr);?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading">Role Group Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;">
        <?php $js = 'id="AddBtn" onClick="ShowAddAdminBox();" class="btn-large btn-primary"';
echo form_button('AddBtn', 'Add Role Group', $js);?>
    </td>
  </tr>
<script language="javascript">
function ShowAddAdminBox(){
	$('#MessaeBox').html("");
	$('#EditBox').hide();
	$('#AddBtn').hide();
	$('#PageHeading').hide();
	$('#ListBox').hide();
	$('#AddBox').fadeIn(2000);
}
 function ShowEditBox(id){
    $('#MessaeBox').html("");
    $('#AddBtn').hide();
    $('#AddBtn').hide();
    $('#ListBox').hide();
    $('#PageHeading').hide();
    $('#EditBox').fadeIn(2000);
    $('#Edittitle').val(DataArr[id]['title']);
    $('#EditaccessTitle').val(DataArr[id]['accessTitle']);
    
    $('#roleGroupId').val(DataArr[id]['roleGroupId']);
    $('#ListBox').hide();
    $('#AddBox').hide();
 }

 function CancelEdit(){
 	$('#AddBtn').show();
	$('#AddBox').hide();
 	$('#EditBox').hide();
 	$('#ListBox').show();
        $('#PageHeading').show();
	return false;
 }
 function CancelAdd()
 {
     $('#AddBtn').show();
	$('#AddBox').hide();
 	$('#EditBox').hide();
 	$('#ListBox').show();
	$('#PageHeading').show();
	return false;
 }
 
function AskDelete(id)
{
	if(confirm('Are you sure to delete(Y/N) ?'))
	{
		location.href='<?php echo base_url()?>webadmin/role/group_delete/'+id;
	}
	return false;
}
 </script>
  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" id="ListBox"  class="alt_row">
  <tr class="ListHeadingLable" bgcolor="#DFDFDF" height="25px;">
    <td width="4%">Sl No </td>
    <td width="15%">Title </td>
    <td width="10%">Access Title</td>
    <td width="7%">Status</td>
    <td width="25%">Action</td>
  </tr>
<script language="javascript">
  var DataArr=new Array(<?php echo count($DataArr)?>);
  </script>  
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->title;?></td>
    <td><?php echo $InerArr->accessTitle?></td>
    <td><?php if($InerArr->status==1){echo 'Active'; $stateClass='active-icon';}else{echo 'Inactive';$stateClass='in-active-icon';}?></td>
    <td>
        <a href="<?php echo base_url().'webadmin/role/group_change_status/'.$InerArr->roleGroupId;?>" class="AdminDashBoardLinkText <?php echo $stateClass;?>" onclick="if(!confirm('Are you Sure to do the action ?')){ return false;}"></a> &nbsp;
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->roleGroupId;?>');" class="AdminDashBoardLinkText edit"></a>&nbsp;
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->roleGroupId;?>');" class="AdminDashBoardLinkText delete"></a>
	<a href="<?php echo base_url().'webadmin/role/view_list/'.$InerArr->roleGroupId;?>" class="AdminDashBoardLinkText">Manage Role</a>
	</td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->roleGroupId;?>]=new Array();
  DataArr[<?php echo $InerArr->roleGroupId;?>]['roleGroupId']='<?php echo $InerArr->roleGroupId;?>';
  DataArr[<?php echo $InerArr->roleGroupId;?>]['title']='<?php echo $InerArr->title;?>';
  DataArr[<?php echo $InerArr->roleGroupId;?>]['accessTitle']='<?php echo $InerArr->accessTitle;?>';
  </script>
  <?php $val++;}
  }else{?>
  <tr class="ListHeadingLable">
    <td colspan="6" style="text-align: center; height: 40px;"> No Report Found</td>
  </tr>
  <?php }?>
</table></td>
  </tr>
 
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php $attributes = array('name' => 'EditForm', 'id' => 'EditForm');echo form_open_multipart('webadmin/role/edit_group/', $attributes);?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
<tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <th width="20%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="17%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="2%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="61%" align="left" valign="top" scope="col"><span class="PageHeading">Role Group Edit </span></th>
  </tr>
  <tr>
    <td colspan="5" height="50px">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Role Group Title <span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><?php $Edittitle = array('name'=>'Edittitle','id'=>'Edittitle','value'=>'','class'=>'required element_flote_4_validate');echo form_input($Edittitle);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Role Group Access Title <span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><?php $EditaccessTitle = array('name'=>'EditaccessTitle','id'=>'EditaccessTitle','value'=>'','class'=>'required element_flote_4_validate');echo form_input($EditaccessTitle);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><label></label></td>
    <td align="left" valign="top">
	<?php echo form_submit('Submit', 'Update','class="btn"')." &nbsp; ";
	$js = 'onClick="return CancelEdit();" class="btn"';
echo form_button('Submit2', 'Cancel', $js); ?><input type="hidden" id="roleGroupId" name="roleGroupId" value=""/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
</table>
<?php echo form_close();?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  
  <tr>
      <td>
          <?php $attributes = array('name' => 'AddForm', 'id' => 'AddForm');echo form_open_multipart('webadmin/role/add_group', $attributes);?>
          <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;" class="tbl-bg">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading"><h3><span class="txtgrn txt28">Role Group Add</span></th>
  </tr>
  <tr>
    <td colspan="4" height="70px;">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Role Group Title <span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><?php $title = array('name'=>'title','id'=>'title','value'=>'','class'=>'required element_flote_4_validate');echo form_input($title);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Role Group Access Title <span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><?php $accessTitle = array('name'=>'accessTitle','id'=>'accessTitle','value'=>'','class'=>'required element_flote_4_validate');echo form_input($accessTitle);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr class="ListHeadingLable">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">Status</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
	<?php echo form_radio('status', '1',TRUE);?>Active
&nbsp;Inactive <?php echo form_radio('status', '0',FALSE);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><label></label></td>
    <td align="left" valign="top"><?php echo form_submit('Submit3', 'Submit','class="btn"')." &nbsp; ";
	$js = 'onClick="return CancelAdd();" class="btn"';
echo form_button('Submit22', 'Cancel', $js);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
</table>
<?php echo form_close();?>
      </td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
  </tr>
</table></td>
  </tr>

</table>
<?php echo $AdminHomeRest;?>
<script language="javascript">
$(document).ready(function(){
	$("#EditForm").validate();
	$("#AddForm").validate();
});
</script>