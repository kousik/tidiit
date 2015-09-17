<?php echo $AdminHomeLeftPanel;

//print_r($UserDataArr);die;
$securityQuestionArr=$this->config->item('HintQuestionID');?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >User Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;"><!--<input type="button" name="AddBtn" id="AddBtn" value="Add User" onclick="ShowAddAdminBox();" class="common_button"/> --></td>
  </tr>
<script language="javascript">

function ShowAddAdminBox()
{
	$('#MessaeBox').html("");
	$('#EditBox').hide();
	$('#AddBtn').hide();
	$('#PageHeading').hide();
	$('#ListBox').fadeOut(500);
	$('#AddBox').fadeIn(3500);
}
 function ShowViewBox(id)
 {
 	$('#MessaeBox').html("");
	$('#AddBtn').fadeOut();
	$('#PageHeading').fadeOut();
	$('#AddBox').fadeOut();
	$('#ListBox').fadeOut(400);
	
	$('#ViewBox').fadeIn(2500);
	$('#EditBoxTitle').text(DataArr[id]['FirstName']+' '+DataArr[id]['LastName']);
	$('#EditEmail').text(DataArr[id]['Email']);
	$('#EditFirstName').text(DataArr[id]['FirstName']);
	$('#EditLastName').text(DataArr[id]['LastName']);
	$('#EditCity').text(DataArr[id]['City']);
	$('#EditZip').text(DataArr[id]['Zip']);
	$('#EditPhone').text(DataArr[id]['Phone']);
	$('#EditContactNo').text(DataArr[id]['Mobile']);
	$('#EditAddress').text(DataArr[id]['Address']);
	$('#EditCountryID').text(DataArr[id]['CountryName']);
	$('#EditStateID').text(DataArr[id]['StateName']);
	$('#EditAddedDate').text(DataArr[id]['ReisteredDate']);
	$('#EditSecurityQuestion').text(DataArr[id]['SecurityQuestion']);
	$('#EditSecurityAnswer').text(DataArr[id]['SecurityAnswer']);
	
 }

 function CancelAdd()
 {
 	$('#ViewBox').fadeOut('fast');
 	$('#EditBox').hide();
	$('#PageHeading').fadeIn(3000);
	$('#ListBox').fadeIn(3000);
	$('#AddBtn').fadeIn(3000);
	return false;
 }
 
function AskDelete(id)
{
	if(confirm('Are you sure to delete(Y/N) ?'))
	{
		location.href='<?php echo base_url()?>admin/user/delete/'+id;
	}
	return false;
}
 </script>
  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" id="ListBox" class="alt_row">
  <tr class="ListHeadingLable" bgcolor="#DFDFDF" height="25px;">
    <td width="4%">Sl No </td>
    <td width="17%">Full Name </td>
    <td width="17%">User Name</td>
    <td width="12%">Phone No</td>
    <td width="13%">Address</td>
    <td width="10%">City</td>
    <td width="12%">Register Date</td>
    <td width="5%">Status</td>
    <td width="17%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->FirstName.' '.$InerArr->LastName;?></td>
    <td><?php echo $InerArr->Email;?></td>
    <td><?php echo $InerArr->Phone;?></td>
	<td><?php echo $InerArr->Address;?></td>
	<td><?php echo $InerArr->City;?></td>
        <td><?php echo date('d-m-Y',strtotime($InerArr->AddDate));?></td>
	<td><?php echo ($InerArr->Status=='1')?'Active':'Inactive';?></td>
    <td>
	<?php if($InerArr->Status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo base_url().'admin/user/change_status/'.$InerArr->UserID.'/'.$action;?>" class="AdminDashBoardLinkText"><?php if($InerArr->Status=='1'){?><img src="<?php echo $SiteImagesURL.'admin/';?>active1.png" alt="Inactive" title="Active" /><?php }else{?><img src="<?php echo $SiteImagesURL.'admin/';?>inactive1.png" alt="Inactive" title="Inactive" /><?php }?></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="ShowViewBox('<?php echo $InerArr->UserID;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'admin/';?>viewer.png" width="25" height="25" title="View"/></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->UserID;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'admin/';?>delete.png" width="15" height="15" title="Delete"/></a>
	</td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->UserID?>]=new Array();
  DataArr[<?php echo $InerArr->UserID?>]['UserID']='<?php echo $InerArr->UserID?>';
  DataArr[<?php echo $InerArr->UserID?>]['Email']='<?php echo $InerArr->Email?>';
  DataArr[<?php echo $InerArr->UserID?>]['FirstName']='<?php echo $InerArr->FirstName?>';
  DataArr[<?php echo $InerArr->UserID?>]['LastName']='<?php echo $InerArr->LastName?>';
  DataArr[<?php echo $InerArr->UserID?>]['Address']='<?php echo $InerArr->Address?>';
  DataArr[<?php echo $InerArr->UserID?>]['City']='<?php echo $InerArr->City?>';
  DataArr[<?php echo $InerArr->UserID?>]['Zip']='<?php echo $InerArr->Zip?>';
  DataArr[<?php echo $InerArr->UserID?>]['Phone']='<?php echo $InerArr->Phone?>';
  DataArr[<?php echo $InerArr->UserID?>]['Mobile']='<?php echo $InerArr->Mobile?>';
  DataArr[<?php echo $InerArr->UserID?>]['Mobile']='<?php echo $InerArr->Mobile?>';
  DataArr[<?php echo $InerArr->UserID?>]['CountryName']='<?php echo $InerArr->CountryName?>';
  DataArr[<?php echo $InerArr->UserID?>]['StateName']='<?php echo $InerArr->StateName?>';
  DataArr[<?php echo $InerArr->UserID?>]['Status']='<?php echo $InerArr->Status?>';
  DataArr[<?php echo $InerArr->UserID?>]['ReisteredDate']='<?php echo date('d-m-Y',strtotime($InerArr->AddDate));?>';
  DataArr[<?php echo $InerArr->UserID?>]['SecurityQuestion']="<?php echo $securityQuestionArr[$InerArr->HintQuestionID];?>";
  DataArr[<?php echo $InerArr->UserID?>]['SecurityAnswer']="<?php echo $InerArr->HintAnswer;?>";
  </script>
  <?php $val++;}
  }else{?>
  <tr class="ListHeadingLable">
    <td colspan="7" style="text-align: center; height: 40px;"> No Report Found</td>
  </tr>
  <?php }?>
</table></td>
  </tr>
 
  <tr>
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="ViewBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">User View of <span id="EditBoxTitle"></span></span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Email </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><label id="EditEmail"></label></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">First Name</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditFirstName"></span></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Last Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditLastName"></span></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Address </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditAddress"></label></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Country </td>
    <td align="left" valign="top"><label><strong>:</strong></span></td>
    <td align="left" valign="top">
	<span id="EditCountryID"></span>
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
    <td align="left" valign="top" class="ListHeadingLable">State </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
		<span id="EditStateID"></span>
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
    <td align="left" valign="top" class="ListHeadingLable">City </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditCity"></span></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Zip </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditZip"></span></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Phone No </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditPhone"></span></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Mobile No </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditContactNo"></span></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Registered Date </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditAddedDate"></span></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Security Questions</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditSecurityQuestion"></span></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Security Answer</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="EditSecurityAnswer"></span></td>
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
    <td align="left" valign="top">&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-primary btn-large"/>
	  <input  type="hidden" name="UserID"  id="UserID" value=""/></td>
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
    <td><form name="AdminAdd" id="AdminAdd" method="post" action="<?=base_url()?>admin/user/add" >
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading">User Add</span></th>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Email </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="Email" type="text" id="Email"  class="required email" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">First Name</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="FName" type="text" id="FName"  class="required"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Last Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="LName" type="text" id="LName"  class="required"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Address </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="Address" type="text" id="Address"  class="required"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Country </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
		<select name="CountryID" id="CountryID" class="required">
			<option>Select Country</option>
			<?php foreach($CountryData AS $k){?>
			<option value="<?php echo $k->CountryID;?>"><?php echo $k->CountryName;?></option>
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
    <td align="left" valign="top" class="ListHeadingLable">State </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
		<div id="AddUserStateDiv">
		<select name="StateID" id="StateID" class="required">
		<option value="">Select</option>
		</select>
		</div>
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
    <td align="left" valign="top" class="ListHeadingLable">City </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="City" type="text" id="City"  class="required"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Zip </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="Zip" type="text" id="Zip"  class="required"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Contact No </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="ContactNo" type="text" id="ContactNo"  class="required"/></td>
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
      <input name="Status" type="radio" value="1"  class="required" checked=""/>
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
    <td align="left" valign="top"><input type="submit" name="Submit3" value="Submit" class="common_button"/>&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="common_button"/></td>
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
<script>
$(document).ready(function(){
	$("#AdminAdd").validate();	
	
	$('#CountryID').live('change',function(){
		var countryID=$(this).val();
		var GetStateDataAjaxURL='<?php echo ADMIN_BASE_URL.'ajax/get_state/'?>';
		var GetStateDataAjaxData='CountryID='+countryID;
		$.ajax({
		   type: "POST",
		   url: GetStateDataAjaxURL,
		   data: GetStateDataAjaxData,
		   success: function(msg){ 
		   	if(msg!=''){
				$('#AddUserStateDiv').html(msg);
				return false;
			}else{
				return true;
			}
		   }
		 });
	});
	
	
	$('#Email').live('blur',function(){
		var EmailID=$(this).val();
		var CheckUserNameAjaxURL='<?php echo ADMIN_BASE_URL.'ajax/check_user_name/'?>';
		var CheckUserNameAjaxData='EmailID='+EmailID;
		$.ajax({
		   type: "POST",
		   url: CheckUserNameAjaxURL,
		   data: CheckUserNameAjaxData,
		   success: function(msg){ 
		   	if(msg=='1'){
				alert('This email id has already used,Please enter a new one.');
				$('#Email').val(' ');
				return false;
			}else{
				return true;
			}
		   }
		 });
	});
	
	
	
	$('#EditCountryID').live('change',function(){
		var countryID=$(this).val();
		var GetStateDataAjaxURL='<?php echo ADMIN_BASE_URL.'ajax/get_edit_state/'?>';
		var GetStateDataAjaxData='CountryID='+countryID;
		$.ajax({
		   type: "POST",
		   url: GetStateDataAjaxURL,
		   data: GetStateDataAjaxData,
		   success: function(msg){ 
		   	if(msg!=''){
				$('#EditUserStateDiv').html(msg);
				return false;
			}else{
				return true;
			}
		   }
		 });
	});
	
	
	$('#EditEmail').live('blur',function(){
		var CheckEditUserNameAjaxURL='<?php echo ADMIN_BASE_URL.'ajax/check_edit_user_name/'?>';
		var CheckEditUserNameAjaxData='EmailID='+$(this).val()+'&UserID='+$('#UserID').val();
		$.ajax({
		   type: "POST",
		   url: CheckEditUserNameAjaxURL,
		   data: CheckEditUserNameAjaxData,
		   success: function(msg){ 
		   	if(msg=='1'){
				alert('This email id has already used,Please enter a new one.');
				$('#EditEmail').val('');
				return false;
			}else{
				return true;
			}
		   }
		 });
	});
	
});
</script>