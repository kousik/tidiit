<?php echo $AdminHomeLeftPanel;

//pre($DataArr);die;?>
<table cellspacing=5 cellpadding=5 width=90% border=0>
  
  <tr id="PageHeading">
    <td class="PageHeading" >Zip/Postal Box Zip Code Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;">
        <input type="button" name="AddBtn" id="AddBtn" value="Add Zip/Postal Box Zip Code" onclick="ShowAddAdminBox();" class="btn-primary btn-large"/> &nbsp; &nbsp; &nbsp;
         <input type="button" name="AddBtn1" id="AddBtn1" value="Back to City List" onclick="location.href='<?php echo base_url().'webadmin/country_controller/viewCityList/'.$stateId;?>'" class="btn-primary btn-large"/>
    </td>
  </tr>
<script language="javascript">
function ShowAddAdminBox(){
	$('#MessaeBox').html("");
	$('#EditBox').hide();
	$('#AddBtn').hide();
	$('#PageHeading').hide();
	$('#ListBox').fadeOut(500);
	$('#AddBox').fadeIn(3500);
}
 function ShowEditBox(id){
 	/// getting state data by ajax
 	$('#MessaeBox').html("");
	$('#AddBtn').fadeOut();
	$('#PageHeading').fadeOut();
	$('#AddBox').fadeOut();
	$('#ListBox').fadeOut(400);
	
	$('#EditBox').fadeIn(2500);
	$('#EditzipId').val(DataArr[id]['zipId']);
	$('#Editzip').val(DataArr[id]['zip']);
	
	if(document.AdminEdit.Editstatus[0].value==DataArr[id]['status'])
	{
		document.AdminEdit.Editstatus[0].checked=true;
	}else{
		document.AdminEdit.Editstatus[1].checked=true;
	}
	//alert(DataArr[id]['zipId']);
	$('#zipId').val(DataArr[id]['zipId']);
 }

 function CancelEdit(){
	$('#AddBox').hide();
	$('#PageHeading').fadeIn(3000);
	$('#ListBox').fadeIn(3000);
	$('#AddBtn').fadeIn(3000);
	$('#EditBox').fadeOut(3500);
	return false;
 }
 function CancelAdd(){
 	$('#AddBox').fadeOut('fast');
 	$('#EditBox').hide();
	$('#PageHeading').fadeIn(3000);
	$('#ListBox').fadeIn(3000);
	$('#AddBtn').fadeIn(3000);
	return false;
 }
 
function AskDelete(id){
	if(confirm('Are you sure to delete(Y/N) ?')){
		location.href='<?php echo base_url()?>webadmin/country_controller/zip_delete/'+id;
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
    <td width="20%">Zip/Postal Box Zip Code Name</td>
    <td width="20%">City/County Name</td>
    <td width="10%">status</td>
    <td width="30%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->zip;?></td>
    <td><?php echo $InerArr->city;?></td>
    <td><?php echo ($InerArr->status=='1')?'Active':'Inactive';?></td>
    <td>
	<?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo base_url().'webadmin/country_controller/zip_change_status/'.$InerArr->zipId.'/'.$action;?>" class="AdminDashBoardLinkText"><?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="Active" title="Active" /><?php }else{?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="inactive" title="Inactive" /><?php }?></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->zipId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>edit.png" width="15" height="15" title="Edit"/></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->zipId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>delete.png" width="15" height="15" title="Delete"/></a>
        &nbsp;&nbsp;
        <a href="<?php echo base_url().'webadmin/country_controller/viewLocalityList/'.$InerArr->zipId;?>" class="AdminDashBoardLinkText">Manage Locality</a>
	</td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->zipId?>]=new Array();
  DataArr[<?php echo $InerArr->zipId?>]['zipId']='<?php echo $InerArr->zipId?>';
  DataArr[<?php echo $InerArr->zipId?>]['zip']='<?php echo $InerArr->zip?>';
  DataArr[<?php echo $InerArr->zipId?>]['cityId']='<?php echo $InerArr->cityId?>';
  DataArr[<?php echo $InerArr->zipId?>]['status']='<?php echo $InerArr->status?>';
  </script>
  <?php $val++;}
  }else{?>
  <tr class="ListHeadingLable">
    <td colspan="6" style="text-align: center; height: 40px;"> No Record Found</td>
  </tr>
  <?php }?>
</table>
    </td>
  </tr>
 
  <tr>
    <td><form name="AdminEdit" id="AdminEdit" method="post" action="<?=base_url()?>webadmin/country_controller/edit_zip/">
	<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">Zip/Postal Box Zip Code Edit Form</span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Zip/Postal Box Zip Code </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="Editzip" id="Editzip" value=""></td>
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
      <input  type="hidden" name="zipId"  id="zipId" value=""/>
      <input  type="hidden" name="cityId" value="<?php echo $cityId;?>"/>
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
</form></td>
  </tr>
  <tr>
    <td><form name="AdminAdd" id="AdminAdd" method="post" action="<?=base_url()?>webadmin/country_controller/add_zip" >
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading">Zip/Postal Box Zip Code Add Form </span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="50px">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Zip/Postal Box Zip Code Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="zip" id="zip" value="" class="required"></td>
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
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-primary btn-large"/>
      <input  type="hidden" name="cityId"  value="<?php echo $cityId;?>"/>
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
	$("#AdminEdit").validate();	
	$('#addCheckAll').on('click',function(){
		$('input[name="zipId[]"]').each(function(){
                jQuery(this).prop( "checked", true );
        });	
		$('#addUnCheckAll').show();
		$(this).hide();	
	});
	
	$('#addUnCheckAll').on('click',function(){
		$('input[name="zipId[]"]').each(function(){
                jQuery(this).prop( "checked", false );
        });	
		$('#addCheckAll').show();
		$(this).hide();	
	});
});

</script>