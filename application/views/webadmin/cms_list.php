<?php echo $AdminHomeLeftPanel;

//print_r($UserDataArr);die;?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Site Content Page Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;"></td>
  </tr>
  <tr>
    <td style="padding-left:10px;">
        <!--<input type="button" name="AddBtn" id="AddBtn" value="Add Content Pages" onclick="ShowAddPages();" class="btn-primary btn-large"/>-->
    </td>
  </tr>
<script language="javascript">

	function ShowAddPages(){
		location.href='<?php echo base_url()?>webadmin/cms/add_view/';
	}

 function ShowEditBox(id){
 	location.href='<?php echo base_url()?>webadmin/cms/view_edit/'+id;
 }

function AskDelete(id){
	if(confirm('Are you sure to delete(Y/N) ?'))
	{
		location.href='<?php echo base_url()?>webadmin/cms/delete/'+id;
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
    <td width="10%">Sl No </td>
    <td width="60%">CMS Page title </td>
    <td width="10%">status</td>
    <td width="20%">Action</td>
  </tr>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->title;?></td>
    <td><?php echo ($InerArr->status=='1')?'Active':'Inactive';?></td>
    <td>
	<?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo base_url().'webadmin/cms/change_status/'.$InerArr->cmsId.'/'.$action;?>" class="AdminDashBoardLinkText"><?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="Inactive" title="Active" /><?php }else{?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="Inactive" title="Inactive" /><?php }?></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->cmsId;?>');" class="AdminDashBoardLinkText edit"></a>
	</td> 
  </tr>
  <?php $val++;}
  }else{?>
  <tr class="ListHeadingLable">
    <td colspan="4" style="text-align: center; height: 40px;"> No Record Found</td>
  </tr>
  <?php }?>
</table></td>
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