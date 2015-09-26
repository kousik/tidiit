<?php echo $AdminHomeLeftPanel;?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Sponsor Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><!--<input type="button" name="AddAdminBtn" id="AddAdminBtn" value="Add Admin" onclick="ShowAddAdminBox();"/>--></td>
  </tr>
<script language="javascript">

/*function ShowAddAdminBox()
{
	$('#MessaeBox').html("");
	$('#EditBox').hide();
	$('#ListBox').hide();
	$('#AddBtn').hide();
	$('#PageHeading').hide();
	$('#AddBox').show('fast');
}
 function ShowEditBox(id)
 {
 	$('#MessaeBox').html("");
	$('#AddBtn').hide();
	$('#PageHeading').hide();
	$('#EditBox').fadeIn(2000);
	$('#LabelConstantName').text(UserDataArr[id]['ConstantName']);
	$('#EditConstantValue').val(UserDataArr[id]['ConstantValue']);
	$('#EditDescription').val(UserDataArr[id]['Description']);
	/*if(document.AdminEdit.Status[0].value==UserDataArr[id]['Status'])
	{
		document.AdminEdit.Status[0].checked=true;
	}else{
		document.AdminEdit.Status[1].checked=true;
	}
	$('#ConstantID').value=UserDataArr[id]['ConstantID'];
	$('#ListBox').hide();
	$('#AddBox').hide();
 }

 function CancelEdit()
 {
 	$('#AddBtn').hide();
	$('#AddBox').hide();
 	$('#EditBox').hide();
 	$('#ListBox').show();
	$('#PageHeading').show();
	$('#AddAdminBtn').show();
	return false;
 }
 function CancelAdd()
 {
 	$('#AddBtn').hide();
	$('#AddBox').hide();
 	$('#EditBox').hide();
 	$('#ListBox').show();
	$('#PageHeading').show();
	$('#AddAdminBtn').show();
	return false;
 }
 
/*function AskDelete(id)
{
	if(confirm('Are you sure to delete(Y/N) ?'))
	{
		location.href='<?php //echo base_url()?>admin/adminuser/delete/'+id;
	}
	return false;
}*/
 </script>
  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="ListBox">
  <tr class="ListHeadingLable">
    <td width="5%">Sl No </td>
    <td width="25%">Full Name </td>
    <td width="20%">User Name</td>
	<td width="15%">Image</td>
	<td width="15%">Site Nickname</td>
    <td width="20%">Action</td>
  </tr>
  <script language="javascript">
  var UserDataArr=new Array(<?=count($UserDataArr)?>);
  </script>
  <?php $val=0; 
  //print_r($DataArr);
  foreach($UserDataArr as $InerArr){?>
  <tr class="ListTestLable">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->FName.' '.$InerArr->LName;?></td>
    <td><?php echo $InerArr->Email;?></td>
    <td>
	<?php if($InerArr->Image==''){
			$ImageSrc=$SiteImagesURL.'user_profile.png';
		}else{
			$ImageSrc=$SiteResourcesURL.'user_img/original/'.$InerArr->Image;
		}?>
	<img src="<?php echo $SiteResourcesURL;?>timthumb.php?src=<?php echo $ImageSrc;?>&w=87&h=54&zc=1" border="0"/></td>
	<td><?php echo $InerArr->SiteNickName;?></td>
    <td><a href="<?php echo base_url().'user/change_status/'.$InerArr->UserID;?>" class="AdminDashBoardLinkText">Change Status</a></td> 
  </tr>
  <script language="javascript">
  UserDataArr[<?php echo $InerArr->UserID?>]=new Array();
  UserDataArr[<?php echo $InerArr->UserID?>]['UserID']='<?php echo $InerArr->UserID?>';
  </script>
  <?php $val++;}?>
</table></td>
  </tr>
 
  <tr>
    <td><form name="AdminEdit" id="AdminEdit" method="post" action="<?=base_url()?>admin/adminuser/edit/">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th width="20%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="17%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="2%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="61%" align="left" valign="top" scope="col"><span class="PageHeading">Admin Edit </span></th>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Constant Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><label name="LabelConstantName" id="LabelConstantName"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Constant Value </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="EditConstantValue" type="text" id="EditConstantValue" class="required"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr class="ListHeadingLable">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">EditDescription</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input  type="text" name="EditDescription" id="EditDescription" value="" class="required"/></td>
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
    <td align="left" valign="top"><input type="submit" name="Submit" value="Update" />
      <input type="button" name="Submit2" value="Cancel" onclick="return CancelEdit();" />
<input name="ConstantID" type="hidden" id="ConstantID" /></td>
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
</form></td>
  </tr>
  <tr>
    <td><form name="AdminAdd" id="AdminAdd" method="post" action="<?=base_url()?>admin/adminuser/add" >
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading">Admin Add</span></th>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> UserName </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="UserName" type="text" id="UserName"  class="required"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Password</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="Password" type="password" id="Password"  class="required"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Full Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="FullName" type="text" id="FullName"  class="required"/></td>
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
    <td align="left" valign="top">Active
      <input name="Status" type="radio" value="1"  class="required"/>
&nbsp;Inactive
<input name="Status" type="radio" value="0"  class="required"/></td>
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
    <td align="left" valign="top"><input type="submit" name="Submit3" value="Submit" />&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" /></td>
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
</form></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
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