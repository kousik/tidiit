<?php echo $AdminHomeLeftPanel;
$i=1;
//print_r($UserDataArr);die;?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Shipping Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
<tr>
    <td style="padding-left:10px;">
        <input type="button" name="AddBtn" id="AddBtn" value="Add Shipping Slab" onclick="ShowAddAdminBox();" class="btn-large btn-primary"/>
    </td>
  </tr>
  <tr>
      <td>&nbsp;</td>
  </tr> 
<script language="javascript">
 function ShowEditBox(id){
    $('#AdminEdit')[0].reset();
    $('#EditEndWeightLable').text("");
    $('#MessaeBox').html("");
    $('#AddBtn').fadeOut();
    $('#PageHeading').fadeOut();
    $('#AddBox').fadeOut();
    $('#ListBox').fadeOut(400);

    $('#EditBox').fadeIn(2500);
    $('#EditstartWeight').val(DataArr[id]['startWeight']);
    $('#EditEndWeightLable').text(DataArr[id]['endWeight']);
    $('#EditcountryCode').val(DataArr[id]['countryCode']);
    $('#Editcharges').val(DataArr[id]['charges']);
    $('#shippingId').val(DataArr[id]['shippingId']);
 }

 function CancelEdit(){
    $('#AddBox').hide();
    $('#PageHeading').fadeIn(1000);
    $('#ListBox').fadeIn(1000);
    $('#AddBtn').fadeIn(1000);
    $('#EditBox').fadeOut(1500);
    return false;
}
function AskDelete(id){
    if(confirm('Are you sure to delete(Y/N) ?')){
        location.href='<?php echo base_url()?>webadmin/shipping/delete/'+id;
    }
    return false;
}
function ShowAddAdminBox(){
    $('#AdminAdd')[0].reset();
    $('#EndCostLable').text("");
    $('#MessaeBox').html("");
    $('#EditBox').hide();
    $('#AddBtn').hide();
    $('#PageHeading').hide();
    $('#ListBox').fadeOut(500);
    $('#AddBox').fadeIn(2500);
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
    <td width="20%"> Shipping Weight Slab</td>
    <td width="10%">Country</td>
    <td width=10%">Charges</td>	
    <td width="10%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;" style=" text-align: center;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->startWeight;?> Kg - <?php echo $InerArr->endWeight;?> Kg</td>
    <td><?php echo ($InerArr->countryCode=='IN') ? 'India' : 'Kenya';?></td>
    <td><?php echo $InerArr->charges;?> @ Kg</td>
    <td>
        <?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo BASE_URL.'webadmin/shipping/change_status/'.$InerArr->shippingId.'/'.$action;?>" class="AdminDashBoardLinkText">
            <?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="Make Inactive" title="Make Inactive" />
                <?php }else{?>
            <img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="Make Active" title="Make Active" /><?php }?></a>
       &nbsp;&nbsp;<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->shippingId;?>');" class="AdminDashBoardLinkText edit"></a>
        &nbsp;&nbsp;
        <a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->shippingId;?>');" class="AdminDashBoardLinkText delete"></a>
    </td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->shippingId?>]=new Array();
  DataArr[<?php echo $InerArr->shippingId?>]['shippingId']='<?php echo $InerArr->shippingId?>';
  DataArr[<?php echo $InerArr->shippingId?>]['startWeight']='<?php echo $InerArr->startWeight?>';
  DataArr[<?php echo $InerArr->shippingId?>]['countryCode']='<?php echo $InerArr->countryCode?>';
  DataArr[<?php echo $InerArr->shippingId?>]['endWeight']='<?php echo $InerArr->endWeight?>';
  DataArr[<?php echo $InerArr->shippingId?>]['charges']='<?php echo $InerArr->charges?>';
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
    <td><form name="AdminAdd" id="AdminAdd" method="post" action="<?=base_url()?>webadmin/shipping/add/">
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">Shipping Add Form</span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Country </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="countryCode" id="countryCode">
            <option value="">Select</option>
            <option value="IN">India</option>
            <option value="KE">Kenya</option>
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
    <td align="left" valign="top" class="ListHeadingLable">Start Weight </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="startWeight" id="startWeight">
            <option value="">Select</option>
            <?php for($i=1;$i<=500;$i+=50):?>
            <option value="<?php echo $i;?>"><?php echo $i;?></option>
            <?php endfor;?>
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
    <td align="left" valign="top" class="ListHeadingLable"> End cost</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><label id="EndCostLable"></label></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Charges</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="charges" id="charges" /></td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr><tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><label></label></td>
    <td align="left" valign="top"><input type="submit" name="Submit3" value="Submit" class="btn-large  btn-primary"/>&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelEdit();" class="btn-primary btn-large"/></td>
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
      <td>
          <form name="AdminEdit" id="AdminEdit" method="post" action="<?=base_url()?>webadmin/shipping/edit/">
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">Shipping Edit Form</span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Country </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="EditcountryCode" id="EditcountryCode">
            <option value="">Select</option>
            <option value="IN">India</option>
            <option value="KE">Kenya</option>
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
    <td align="left" valign="top" class="ListHeadingLable">Start Weight </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="EditstartWeight" id="EditstartWeight">
            <option value="">Select</option>
            <?php for($i=1;$i<=500;$i+=50):?>
            <option value="<?php echo $i;?>"><?php echo $i;?></option>
            <?php endfor;?>
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
    <td align="left" valign="top" class="ListHeadingLable"> End Weight</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><label id="EditEndWeightLable"></label></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Charges</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="Editcharges" id="Editcharges" /></td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr><tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><label></label></td>
    <td align="left" valign="top"><input type="submit" name="Submit3" value="Submit" class="btn-large  btn-primary"/>&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelEdit();" class="btn-primary btn-large"/>
	  <input  type="hidden" name="shippingId"  id="shippingId" value=""/></td>
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
</form>
      </td>
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
    jQuery("#AdminAdd").validate();	
    jQuery('#startWeight').on('change',function(){
        jQuery.ajax({
            type:"POST",
            url:myJsMain.baseURL+'ajax/check_is_exist_start_weight/',
            data:'startWeight='+jQuery(this).val()+'&countryCode='+jQuery('#countryCode').val(),
            success:function(res){ console.log(res);
                if(res=='exist'){
                    myJsMain.commonFunction.tidiitAlert('Tidiit Shipping System','Selected "start weight" is alredy exist, please select a new.',200);
                    jQuery("#startWeight").val('');
                    jQuery('#EndCostLable').text("");
                }else{
                    jQuery('#EndCostLable').text(parseInt(jQuery("#startWeight").val())+49);
                    //console.log(parseInt(jQuery("#startWeight").val())+49);
                }
            }
        });
    });
    
    jQuery('#EditstartWeight').on('change',function(){
        jQuery.ajax({
            type:"POST",
            url:myJsMain.baseURL+'ajax/check_is_exist_start_weight/',
            data:'startWeight='+jQuery(this).val()+'&countryCode='+jQuery('#countryCode').val()+'&shippingId='+jQuery('#shippingId').val(),
            success:function(res){ console.log(res);
                if(res=='exist'){
                    myJsMain.commonFunction.tidiitAlert('Tidiit Shipping System','Selected "start weight" is alredy exist, please select a new.',200);
                    jQuery("#EditstartWeight").val('');
                    jQuery('#EditEndCostLable').text("");
                }else{
                    jQuery('#EditEndCostLable').text(parseInt(jQuery("#EditstartWeight").val())+49);
                    //console.log(parseInt(jQuery("#startWeight").val())+49);
                }
            }
        });
    });
});
</script>