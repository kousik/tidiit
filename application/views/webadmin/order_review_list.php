<?php echo $AdminHomeLeftPanel;

//print_r($UserDataArr);die;?>
<table cellspacing=5 cellpadding=5 width=100% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >User Order Review Manager</td>
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

 
function AskApprove(id){
	if(confirm('Are you sure to approve the review (Y/N) ?')){
		location.href='<?php echo base_url()?>admin/user/reveiw_approve/'+id;
	}
	return false;
}

function makeInactive(id){
    if(confirm('Are you sure to inactive the review (Y/N) ?')){
            location.href='<?php echo base_url()?>admin/user/reveiw_inactive/'+id;
    }
    return false;
}

function deleteReview(id){
    if(confirm('Are you sure to delete the review (Y/N) ?')){
            location.href='<?php echo base_url()?>admin/user/reveiw_delete/'+id;
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
    <td width="35%">Comment</td>
    <td width="13%">User Name</td>
    <td width="13%">Email</td>
    <td width="8%">Phone No</td>
    <td width="4%">Order ID</td>
    <td width="5%">Status</td>
    <td width="15%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->Comment;?></td>
    <td><?php echo $InerArr->FirstName.' '.$InerArr->LastName;?></td>
    <td><?php echo $InerArr->Email;?></td>
    <td><?php echo $InerArr->Phone;?></td>
    <td><?php echo $InerArr->OrderID;?></td>
    <td><?php echo ($InerArr->Status==1) ? 'Active' : 'Inactive';?></td>
	<td>
	<a href="javascript:void(0);" onclick="AskApprove('<?php echo $InerArr->OrderReviewID;?>');" class="AdminDashBoardLinkText">Approve</a>&nbsp; &nbsp;<a href="javascript:void(0);" onclick="makeInactive('<?php echo $InerArr->OrderReviewID;?>');" class="AdminDashBoardLinkText">Inactive</a>&nbsp; &nbsp;<a href="javascript:void(0);" onclick="deleteReview('<?php echo $InerArr->OrderReviewID;?>');" class="AdminDashBoardLinkText">Delete</a>&nbsp; &nbsp;</td> 
  </tr>
  <?php $val++;}?>
  <tr>
    <td colspan="7" style="text-align: center; height: 40px;"> <?php echo $links;?></td>
  </tr>
  <?php }else{?>
  <tr class="ListHeadingLable">
    <td colspan="7" style="text-align: center; height: 40px;"> No Report Found</td>
  </tr>
  <?php }?>
</table></td>
  </tr>
 
  <tr>
    <td></td>
  </tr>
</table></td>
  </tr>

</table>
<?php echo $AdminHomeRest;?>