<?php echo $AdminHomeLeftPanel;?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Site configuration Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:50px;"></td>
  </tr>
<script language="javascript">
 function ShowEditBox(id){
 	$('#MessaeBox').html("");
	$('#AddBtn').hide();
	$('#PageHeading').hide();
	$('#EditBox').fadeIn(2000);
	$('#LabelConstantName').text(SiteConfigDataArr[id]['ConstantName']);
	$('#EditConstantValue').val(SiteConfigDataArr[id]['ConstantValue']);
	$('#EditDescription').val(SiteConfigDataArr[id]['Description']);
	$('#ConstantID').val(SiteConfigDataArr[id]['ConstantID']);
	$('#ListBox').hide();
	$('#AddBox').hide();
 }

 function CancelEdit(){
 	$('#AddBtn').hide();
	$('#AddBox').hide();
 	$('#EditBox').hide();
 	$('#ListBox').show();
	$('#PageHeading').show();
	$('#AddAdminBtn').show();
	return false;
 }
 </script>
  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="ListBox" style="border:#FBE554 1px solid;">
  <tr class="ListHeadingLable" bgcolor="#FBE554" height="25px;">
    <td width="5%">Sl No </td>
    <td width="35%">Constant Name </td>
    <td width="20%">Constant Value</td>
	<td width="20%">Constant Description</td>
    <td width="20%">Action</td>
  </tr>
  <script language="javascript">
  var SiteConfigDataArr=new Array(<?=count($SiteConfigDataArr)?>);
  </script>
  <?php $val=0; 
  //print_r($DataArr);
  foreach($SiteConfigDataArr as $InerArr){?>
  <tr class="ListTestLable">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->ConstantName;?></td>
    <td><?php echo $InerArr->ConstantValue;?></td>
    <td><?php echo $InerArr->Description;?></td>
    <td><a href="javascript:void(0);" onclick="javascript:ShowEditBox('<?php echo $InerArr->ConstantID?>')"><img src="<?php echo $SiteImagesURL;?>edit.png" width="15" height="15" title="Edit"/></a></td> 
  </tr>
  <script language="javascript">
  SiteConfigDataArr[<?php echo $InerArr->ConstantID?>]=new Array();
  SiteConfigDataArr[<?php echo $InerArr->ConstantID?>]['ConstantID']='<?php echo $InerArr->ConstantID?>';
  SiteConfigDataArr[<?php echo $InerArr->ConstantID?>]['ConstantName']='<?php echo $InerArr->ConstantName;?>';
  SiteConfigDataArr[<?php echo $InerArr->ConstantID?>]['ConstantValue']='<?php echo $InerArr->ConstantValue;?>';
  SiteConfigDataArr[<?php echo $InerArr->ConstantID?>]['Description']='<?php echo $InerArr->Description;?>';
  
  </script>
  <?php $val++;}?>
</table></td>
  </tr>
 
  <tr>
    <td><form name="AdminEdit" id="AdminEdit" method="post" action="<?=base_url()?>site_config/edit/">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th width="20%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="17%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="2%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="61%" align="left" valign="top" scope="col"><span class="PageHeading">Configuration Update </span></th>
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
    <td align="left" valign="top">
        <textarea name="EditConstantValue" id="EditConstantValue" class="required"></textarea></td>
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
    <td align="left" valign="top">
    <textarea name="EditDescription" id="EditDescription" class="required"></textarea></td>
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
    <td align="left" valign="top"><input type="submit" name="Submit" value="Update"  class="common_button"/>
      <input type="button" name="Submit2" value="Cancel" onclick="return CancelEdit();" class="common_button" />
<input name="ConstantID" type="hidden" id="ConstantID" value="" /></td>
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
    <td></td>
  </tr>
</table></td>
  </tr>

</table>
<?php echo $AdminHomeRest;?>