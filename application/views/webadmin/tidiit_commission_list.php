<?php echo $AdminHomeLeftPanel;

//print_r($UserDataArr);die;?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Tidiit Commission Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;height: 25px">
        <!--<input type="button" name="AddBtn" id="AddBtn" value="Add Logistics" onclick="ShowAddAdminBox();" class="btn-large btn-primary"/> -->
    </td>
  </tr>
 <script language="javascript">
 function ShowEditBox(id){
    $('#MessaeBox').html("");
    $('#PageHeading').fadeOut();
    $('#ListBox').fadeOut(400);

    $('#EditBox').fadeIn(2500);
    $('#commissionPercentage').val(DataArr[id]['commissionPercentage']);
    $('#commissionId').val(DataArr[id]['commissionId']);
 }

 function CancelEdit(){
    $('#AddBox').hide();
    $('#PageHeading').fadeIn(3000);
    $('#ListBox').fadeIn(3000);
    $('#AddBtn').fadeIn(3000);
    $('#EditBox').fadeOut(3500);
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
    <td width="5%">Sl No </td>
    <td width="25%">Seller Email</td>
    <td width="35%">Product Title </td>
    <td width="20%">Product Category Name</td>
    <td width="15%">Commission %</td>
    <td width="7%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->email;?></td>
    <td><?php echo $InerArr->title;?></td>
    <td><?php echo $InerArr->categoryName;?></td>
    <td><?php echo $InerArr->commissionPercentage;?> %</td>
    <td>
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->commissionId;?>');" class="AdminDashBoardLinkText edit"></a>
	</td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->commissionId?>]=new Array();
  DataArr[<?php echo $InerArr->commissionId?>]['commissionId']='<?php echo $InerArr->commissionId?>';
  DataArr[<?php echo $InerArr->commissionId?>]['commissionPercentage']='<?php echo $InerArr->commissionPercentage?>';
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
    <td><form name="AdminEdit" id="AdminEdit" method="post" action="<?=base_url()?>webadmin/tidiit_commission/edit/">
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">Update Tidiit Commission</span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Commission Percentage </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="commissionPercentage" type="text" id="commissionPercentage"  class="required" /></td>
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
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelEdit();" class=" btn-large btn-primary"/>
	  <input  type="hidden" name="commissionId"  id="commissionId" value=""/></td>
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
    $("#AdminEdit").validate();	
});
</script>