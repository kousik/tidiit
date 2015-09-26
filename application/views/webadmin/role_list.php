<?php echo $AdminHomeLeftPanel;

//print_r($UserDataArr);die;
 $PageArr=$this->config->item('OtherPages4Banner'); 
 //pre($PageArr);?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading">Role Manager</td>
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
echo form_button('AddBtn', 'Add Role', $js);?>
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
    
    $('#roleId').val(DataArr[id]['roleId']);
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
		location.href='<?php echo base_url()?>webadmin/role/delete/'+id;
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
    <td width="10%">Group Title</td>
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
    <td><?php echo $InerArr->roleTitle;?></td>
    <td><?php echo $InerArr->roleAccessTitle?></td>
    <td><?php echo $InerArr->title?></td>
    <td><?php if($InerArr->status==1){echo 'Active';}else{echo 'Inactive';}?></td>
    <td>
        <?php /*<a href="<?php echo base_url().'role/change_status/'.$InerArr->roleId;?>" class="AdminDashBoardLinkText active-icon" onclick="if(!confirm('Are you Sure to do the action ?')){ return false;}"></a> &nbsp;*/?>
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->roleId;?>');" class="AdminDashBoardLinkText edit"></a>&nbsp;
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->roleId;?>');" class="AdminDashBoardLinkText delete"></a>
	</td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->roleId;?>]=new Array();
  DataArr[<?php echo $InerArr->roleId;?>]['roleGroupId']='<?php echo $InerArr->roleGroupId;?>';
  DataArr[<?php echo $InerArr->roleId;?>]['roleId']='<?php echo $InerArr->roleId;?>';
  DataArr[<?php echo $InerArr->roleId;?>]['roleTitle']='<?php echo $InerArr->roleTitle;?>';
  DataArr[<?php echo $InerArr->roleId;?>]['roleAccessTitle']='<?php echo $InerArr->roleAccessTitle;?>';
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
    <td><?php $attributes = array('name' => 'EditForm', 'id' => 'EditForm');echo form_open_multipart('webadmin/role/edit/', $attributes);?>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;" class="tbl-bg">
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
    <th width="61%" align="left" valign="top" scope="col"><span class="PageHeading"><h3><span class="txtgrn txt28">Role Edit </span></th>
  </tr>
  <tr>
    <td colspan="5" height="50px">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Role Title <span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><?php $Edittitle = array('name'=>'EditroleTitle','id'=>'EditroleTitle','value'=>'','class'=>'required element_flote_4_validate');echo form_input($Edittitle);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Role Access Title <span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><?php $EditaccessTitle = array('name'=>'EditroleAccessTitle','id'=>'EditroleAccessTitle','value'=>'','class'=>'required element_flote_4_validate');echo form_input($EditaccessTitle);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Select Role Group<span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="EditroleGroupId" id="EditroleGroupId" class="required">
            <option value="">* Select *</option>
            <?php foreach ($roleGroupDataArr AS $k){?>
            <option value="<?php echo $k->roleGroupId?>" <?php if($k->roleGroupId==$roleGroupId){ echo 'selected';}?>><?php echo $k->title;?></option>
            <?php }?>
        </select>
    </td>
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
echo form_button('Submit2', 'Cancel', $js); ?>
  <input type="hidden" id="roleId" name="roleId" value=""/></td>
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
          <?php $attributes = array('name' => 'AddForm', 'id' => 'AddForm');echo form_open_multipart('webadmin/role/add', $attributes);?>
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading">Role Group Add</span></th>
  </tr>
  <tr>
    <td colspan="4" height="70px;">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Role Title <span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><?php $title = array('name'=>'roleTitle','id'=>'roleTitle','value'=>'','class'=>'required element_flote_4_validate');echo form_input($title);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Role Access Title <span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><?php $accessTitle = array('name'=>'roleAccessTitle','id'=>'roleAccessTitle','value'=>'','class'=>'required element_flote_4_validate');echo form_input($accessTitle);?></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Select Group Group<span class="red_star">*</span></td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="roleGroupId" id="roleGroupId" class="required">
            <option value="">* Select *</option>
            <?php foreach ($roleGroupDataArr AS $k){?>
            <option value="<?php echo $k->roleGroupId;?>" <?php if($k->roleGroupId==$roleGroupId){ echo 'selected';}?>><?php echo $k->title;?></option>
            <?php }?>
        </select>
    </td>
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
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><label></label></td>
    <td align="left" valign="top"><?php echo form_submit('Submit3', 'Submit','class="btn"')." &nbsp; ";
	$js = 'onClick="return CancelAdd();" class="btn"';
echo form_button('Submit22', 'Cancel', $js);?>
        <?php /*<input type="hidden" id="mainRoleGroupId" name="mainRoleGroupId" value="<?php echo $roleGroupId;?>"/> */?>
    </td>
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