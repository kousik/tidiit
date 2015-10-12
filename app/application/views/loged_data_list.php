<?php echo $AdminHomeLeftPanel;

//print_r($UserDataArr);die;?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Event Loged Data Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;">&nbsp;</td>
  </tr>
<script language="javascript">

 
function AskDelete(id)
{
	if(confirm('Are you sure to delete(Y/N) ?'))
	{
		location.href='<?php echo base_url()?>event/delete/'+id;
	}
	return false;
}
 </script>
  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" id="ListBox" style="border:#FBE554 1px solid;">
  <tr class="ListHeadingLable" bgcolor="#FBE554" height="25px;">
    <td width="4%">Sl No </td>
    <td width="17%">Event Name</td>
    <td width="12%">Loged Latitude</td>
    <td width="12%">Loged Longitude</td>
    <td width="12%">Loged Time</td>
    <td width="12%">Loged Device</td>
    <td width="21%">Loged Comment</td>
   
    <td width="10%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable" height="20px;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->Name;?></td>
    <td><?php echo $InerArr->lat;?></td>
    <td><?php echo $InerArr->long;?></td>
    <td><?php echo date('d-m-Y H:i:s',strtotime($InerArr->log_time));?></td>
    <td><?php echo ($InerArr->device_type=='iphone') ? 'iPhone' : 'Android';?></td>
    <td><?php echo $InerArr->comment;?></td>
    <td>
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->log_id;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL;?>delete.png" width="15" height="15" title="Delete"/></a>
	</td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->log_id?>]=new Array();
  DataArr[<?php echo $InerArr->log_id?>]['log_id']='<?php echo $InerArr->log_id?>';
  DataArr[<?php echo $InerArr->log_id?>]['Name']='<?php echo $InerArr->Name?>';
  DataArr[<?php echo $InerArr->log_id?>]['Status']='<?php echo $InerArr->Status?>';
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
});
</script>