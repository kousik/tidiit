<?php echo $header;?>
<?php echo $leftpanel; //pre($roleGroupDataArr);die;?>
<div>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr id="PageHeading">
<td class="PageHeading bdr-none txt-center" ><h3><span class="txtgrn txt28">Role Manager</span></h3></td>
</tr>
<div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div>
<tr><td style=" margin-bottom: 20px;"><?php $js = 'id="AddBtn" onClick="ShowAddBox();" class="btn-add"';
echo form_button('AddBtn', 'Add Role', $js);?></td></tr>
<script language="javascript">
function ShowAddBox(){
	$('#MessaeBox').html("");
	$('#EditBox').hide();
	$('#AddBtn').hide();
	$('#PageHeading').hide();
	$('#ListBox').hide();
	$('#AddBox').fadeIn(2000);
}
 function ShowEditBox(adminId){
    $('#MessaeBox').html("");
    $('#AddBtn').hide();
    $('#AddBtn').hide();
    $('#ListBox').hide();
    $('#PageHeading').hide();
    $('#EditBox').fadeIn(2000);
    $('#EditroleTitle').val(DataArr[id]['roleTitle']);
    $('#EditroleAccessTitle').val(DataArr[id]['roleAccessTitle']);
    
    $('#roleId').val(DataArr[id]['roleId']);
    $('#ListBox').hide();
    $('#AddBox').hide();
 }
 function CancelEdit()
 {
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
 
function AskDelete(uesrId)
{
	if(confirm('Are you sure to delete(Y/N) ?'))
	{
		location.href='<?php echo base_url()?>role/delete/'+uesrId;
	}
	return false;
}

function ShowAssignRoleBox(id,name){
        var AjaxURLGetUserRole='<?php echo base_url();?>webadmin/admin_controller/ajax_get_user_role';
        var AjaxDataGetUserRole='userId='+id;
        //alert(AjaxDataGetUserTask);
        //$('#TaskList').html(img);
    $.ajax({
        type:"POST",
        url:AjaxURLGetUserRole,
        data:AjaxDataGetUserRole,
        success:function(msg){
            $('#AssignRoleBoxMainRoleDiv').html(msg);
        }
    });
    $('#MessaeBox').html("");
    $('#EditBox').hide();
    $('#AddBtn').hide();
    $('#PageHeading').hide();
    $('#ListBox').hide();
    $('#AddBox').hide();
    $('#AssignRoleBox').fadeIn(2000);
    $('#AssignRoleBoxHeading').text(name);
    $('#assignUserId').val(id);
   
}

function hideAssignRoleBox(){
    $('#AddBtn').show();
    $('#AddBox').hide();
    $('#AssignRoleBox').hide();
    $('#EditBox').hide();
    $('#ListBox').show();
    //$('#EdituserImage').hide();
    //$('#Editimage').show();
    $('#PageHeading').show();
    $('#AssignRoleBoxMainRoleDiv').empty();
    $('body,html').animate({scrollTop: 0}, 'slow');
}
</script>
<tr>
<td valign="top"> 
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="top-margin">
<tr>
<td>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="ListBox" class="table-orders">
  <tr class="ListHeadingLable bdr list-heading">
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
  <tr class="ListTestLable">
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
    <td colspan="6" style=" text-align: center;" height="35px;">No record found.</td>
  </tr>
  <?php } ?>
</table></td>
  </tr>
 
  <tr>
    <td></td>
  </tr>
  <tr>
    <td></td>
  </tr>  
  <tr>
    <td></td>
  </tr>
</table>
</td>
  </tr>
  <tr>
      <td class="bdr-none">
          <?php $attributes = array('name' => 'EditForm', 'id' => 'EditForm');echo form_open_multipart('role/edit/', $attributes);?>
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
<?php echo form_close();?>
      </td>
  </tr>
  <tr>
    <td>
        <?php $attributes = array('name' => 'AddForm', 'id' => 'AddForm');echo form_open_multipart('role/add', $attributes);?>
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
<?php echo form_close();?>
</td>
  </tr>
  
  
  <tr>
    <td>&nbsp;</td>
  </tr>
  
</table></td>
  </tr>

</table>
    
</div> 
<script language="javascript">
$(document).ready(function(){
    $("#EditForm").validate();
    $("#AddForm").validate();
});
</script>
</article>

</body>
<?php echo $footer; ?>