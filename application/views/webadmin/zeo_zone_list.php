<?php echo $AdminHomeLeftPanel;

//pre($DataArr);die;?>
<table cellspacing=5 cellpadding=5 width=90% border=0>
  
  <tr id="PageHeading">
    <td class="PageHeading" >Zeo Zone Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;"><input type="button" name="AddBtn" id="AddBtn" value="Add Zeo Zone" onclick="ShowAddAdminBox();" class="btn-primary btn-large"/></td>
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
 function ShowEditBox(id)
 {
 	/// getting state data by ajax
	showEditStateZeozoneByCountry(DataArr[id]['countryId'],DataArr[id]['zeoZoneId']);	
 	$('#MessaeBox').html("");
	$('#AddBtn').fadeOut();
	$('#PageHeading').fadeOut();
	$('#AddBox').fadeOut();
	$('#ListBox').fadeOut(400);
	
	$('#EditBox').fadeIn(2500);
	$('#EditcountryId').val(DataArr[id]['countryId']);
	$('#EditzeoZoneName').val(DataArr[id]['zeoZoneName']);
	
	if(document.AdminEdit.Editstatus[0].value==DataArr[id]['status'])
	{
		document.AdminEdit.Editstatus[0].checked=true;
	}else{
		document.AdminEdit.Editstatus[1].checked=true;
	}
	//alert(DataArr[id]['zeoZoneId']);
	$('#zeoZoneId').val(DataArr[id]['zeoZoneId']);
 }

 function CancelEdit()
 {
	$('#AddBox').hide();
	$('#PageHeading').fadeIn(3000);
	$('#ListBox').fadeIn(3000);
	$('#AddBtn').fadeIn(3000);
	$('#EditBox').fadeOut(3500);
	return false;
 }
 function CancelAdd()
 {
 	$('#AddBox').fadeOut('fast');
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
		location.href='<?php echo base_url()?>webadmin/zeozone/delete/'+id;
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
    <td width="2%">Sl No </td>
    <td width="20%">Zeo Zone Name</td>
    <td width="20%">Country Name</td>
    <td width="30%">State Name</td>
	<td width="10%">status</td>
    <td width="10%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?></td>
	<td><?php echo $InerArr->zeoZoneName;?></td>
    <td><?php echo $InerArr->countryName;?></td>
    <td><?php echo $InerArr->stateNames;?></td>
    <td><?php echo ($InerArr->status=='1')?'Active':'Inactive';?></td>
    <td>
	<?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo base_url().'webadmin/zeozone/change_status/'.$InerArr->zeoZoneId.'/'.$action;?>" class="AdminDashBoardLinkText"><?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="Inactive" title="Inactive" /><?php }else{?><img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="Active" title="Active" /><?php }?></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->zeoZoneId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>edit.png" width="15" height="15" title="Edit"/></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->zeoZoneId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>delete.png" width="15" height="15" title="Delete"/></a>
	</td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->zeoZoneId?>]=new Array();
  DataArr[<?php echo $InerArr->zeoZoneId?>]['zeoZoneId']='<?php echo $InerArr->zeoZoneId?>';
  DataArr[<?php echo $InerArr->zeoZoneId?>]['zeoZoneName']='<?php echo $InerArr->zeoZoneName?>';
  DataArr[<?php echo $InerArr->zeoZoneId?>]['countryId']='<?php echo $InerArr->countryId?>';
  DataArr[<?php echo $InerArr->zeoZoneId?>]['status']='<?php echo $InerArr->status?>';
  </script>
  <?php $val++;}
  }else{?>
  <tr class="ListHeadingLable">
    <td colspan="6" style="text-align: center; height: 40px;"> No Record Found</td>
  </tr>
  <?php }?>
</table></td>
  </tr>
 
  <tr>
    <td><form name="AdminEdit" id="AdminEdit" method="post" action="<?=base_url()?>webadmin/zeozone/edit/">
	<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">Zeozone Edit Form</span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Zeozone Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="EditzeoZoneName" id="EditzeoZoneName" value=""></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Country </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
		<select name="EditcountryId" id="EditcountryId" class="required">
			<option value="">Select</option>
			<?php foreach($CountryData as $k){?>
			<option value="<?php echo $k->countryId?>"><?php echo $k->countryName?></option>
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
    <td align="left" valign="top" class="ListHeadingLable">State Name</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><div id="EditstateIdDiv"></div></td>
  </tr>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr class="ListHeadingLable">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">status</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">Active
      <input name="Editstatus" type="radio" value="1"  class="required" checked=""/>
&nbsp;Inactive
<input name="Editstatus" type="radio" value="0"  class="required"/></td>
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
    <td align="left" valign="top"><input type="submit" name="Submit3" value="Submit" class="btn-primary btn-large"/>&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-primary btn-large"/>
	  <input  type="hidden" name="zeoZoneId"  id="zeoZoneId" value=""/></td>
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
    <td><form name="AdminAdd" id="AdminAdd" method="post" action="<?=base_url()?>webadmin/zeozone/add" >
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading">Zeo Zone Add Form </span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="50px">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Zeozone Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="zeoZoneName" id="zeoZoneName" value="" class="required"></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Country Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
		<select name="countryId" id="countryId" class="required">
			<option value="">Select</option>
			<?php foreach($CountryData as $k){?>
			<option value="<?php echo $k->countryId?>"><?php echo $k->countryName?></option>
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
    <td align="left" valign="top" class="ListHeadingLable">State Name</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><div id="stateIdDiv">
	Select Country for Select State</div>
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
    <td align="left" valign="top">status</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">Active
      <input name="status" type="radio" value="1"  class="required" checked=""/>
&nbsp;Inactive
<input name="status" type="radio" value="0"  class="required"/></td>
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
    <td align="left" valign="top"><input type="submit" name="Submit3" value="Submit" class="btn-primary btn-large"/>&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-primary btn-large"/></td>
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
	
	$('#countryId').on('change',function(){
		var img='<img src="<?php echo $SiteImagesURL.'webadmin/ajax_img.gif';?>" alt=""/>';
		$('#stateIdDiv').html(img);
		var GetStateByCounryAjaxURL='<?php echo ADMIN_BASE_URL.'ajax/get_state_checkbox/'?>';
		var GetStateByCounryAjaxData='countryId='+$(this).val();
                $.ajax({
		   type: "POST",
		   url: GetStateByCounryAjaxURL,
		   data: GetStateByCounryAjaxData,
		   success: function(msg){ //alert(msg);
		   	if(msg!=''){
				$('#stateIdDiv').html(msg);
				return false;
			}else{
				return true;
			}
		   }
		 });		
	});
	
	$('#EditcountryId').on('change',function(){
		var img='<img src="<?php echo $SiteImagesURL.'webadmin/ajax_img.gif';?>" alt=""/>';
		$('#EditstateIdDiv').html(img);
		var GetStateByCounryAjaxURL='<?php echo ADMIN_BASE_URL.'ajax/get_state_checkbox/'?>';
		var GetStateByCounryAjaxData='countryId='+$(this).val();
		$.ajax({
		   type: "POST",
		   url: GetStateByCounryAjaxURL,
		   data: GetStateByCounryAjaxData,
		   success: function(msg){ //alert(msg);
		   	if(msg!=''){
				$('#EditstateIdDiv').html(msg);
				return false;
			}else{
				return true;
			}
		   }
		 });		
	});
	$('#addCheckAll').on('click',function(){
		$('input[name="stateId[]"]').each(function(){
                jQuery(this).prop( "checked", true );
        });	
		$('#addUnCheckAll').show();
		$(this).hide();	
	});
	
	$('#addUnCheckAll').on('click',function(){
		$('input[name="stateId[]"]').each(function(){
                jQuery(this).prop( "checked", false );
        });	
		$('#addCheckAll').show();
		$(this).hide();	
	});
});
function showEditStateZeozoneByCountry(countryId,ZeozoneID){
	var img='<img src="<?php echo $SiteImagesURL.'webadmin/ajax_img.gif';?>" alt=""/>';
	$('#EditstateIdDiv').html(img);
	var GetExistStateZeozoneAjaxURL='<?php echo ADMIN_BASE_URL.'ajax/get_exist_geozone_state/'?>';
	var GetExistStateZeozoneAjaxData='countryId='+countryId+'&zeoZoneId='+ZeozoneID;
	$.ajax({
	   type: "POST",
	   url: GetExistStateZeozoneAjaxURL,
	   data: GetExistStateZeozoneAjaxData,
	   success: function(msg){ //alert(msg);
	   	if(msg!=''){
			$('#EditstateIdDiv').html(msg);
			return false;
		}else{
			return true;
		}
	   }
	 });
}
</script>