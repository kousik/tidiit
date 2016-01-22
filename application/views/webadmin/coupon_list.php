<?php echo $AdminHomeLeftPanel;

//pre($CategoryData);die;?>
<script src="<?php echo SiteJSURL ?>jq_datepicker/js/jquery.ui.core.js"></script>
<script src="<?php echo SiteJSURL ?>jq_datepicker/js/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="<?php echo SiteJSURL ?>jq_datepicker/themes/ui-lightness/jquery.ui.all.css">
<link rel="stylesheet" href="<?php echo SiteJSURL ?>jq_datepicker/css/datepicker_ui.css">
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Coupon Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;"><input type="button" name="AddBtn" id="AddBtn" value="Add Coupon" onclick="ShowAddAdminBox();" class="btn-large btn-primary"/></td>
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
 	$('#MessaeBox').html("");
	$('#AddBtn').fadeOut();
	$('#PageHeading').fadeOut();
	$('#AddBox').fadeOut();
	$('#ListBox').fadeOut(400);
	
	$('#EditBox').fadeIn(2500);
	$('#Editcode').text(DataArr[id]['code']);
	$('#Edittype').val(DataArr[id]['type']);
	$('#Editamount').val(DataArr[id]['amount']);
	$('#EditstartDate').val(DataArr[id]['startDate']);
	$('#EditendDate').val(DataArr[id]['dndDate']);
	$('#Editcategory').val(DataArr[id]['categoryId']);
	//$('#EdituserId').val(DataArr[id]['userId']);
	//$('#EdituserUsesType').val(DataArr[id]['userUsesType']);
	$('#couponId').val(DataArr[id]['couponId']);
	
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
 	$('#AddUserBox').fadeOut('fast');
 	$('#ViewAllUserBox').fadeOut('fast');
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
		location.href='<?php echo base_url()?>webadmin/coupon/delete/'+id;
	}
	return false;
}

function ShowAddMoreUser(id){
    $('#MessaeBox').html("");
	$('#AddBtn').fadeOut();
	$('#PageHeading').fadeOut();
	$('#AddBox').fadeOut();
	$('#EditBox').fadeOut();
	$('#ListBox').fadeOut(400);
	
	$('#AddUserBox').fadeIn(2500);
	$('#couponcodeSpan1').text(DataArr[id]['code']);
	$('#couponCodeId').val(DataArr[id]['couponId']);
}

function ShowAllUsers(id){
    //ViewAllUserBoxContent
    $('#MessaeBox').html("");
    $('#AddBtn').fadeOut();
    $('#PageHeading').fadeOut();
    $('#AddBox').fadeOut();
    $('#EditBox').fadeOut();
    $('#ListBox').fadeOut(400);
    $('#AddUserBox').fadeOut(400);
    $('#ViewAllUserBox').fadeIn();
    $.ajax({
        type:"POST",
        url:'<?php echo ADMIN_BASE_URL.'ajax/get_all_user/';?>',
        data:"couponId="+id,
        success:function(data){ //alert(data);
            $('#ViewAllUserBoxContent').html(data);
        }
    });
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
    <td width="18%">codes</td>
    <td width="15%">Coupon Amount Type</td>
    <td width="10%">Coupon Amount</td>
    <td width="10%">Start Date</td>
    <td width="10%">End Date</td>
    <?php /*<td width="13%">Category Name</td>
    <td width="6%">Coupon Type</td>
    <td width="5%">Coupon Uses Type</td>*/ ?>
    <td width="5%">status</td>
    <td width="25%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){ //pre($InerArr);?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;" style=" text-align: center;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->code;?></td>
    <td><?php echo $InerArr->type;?></td>
    <td><?php echo $InerArr->amount;?></td>
    <td><?php echo date('d-m-Y',  strtotime($InerArr->startDate));?></td>
    <td><?php echo date('d-m-Y',  strtotime($InerArr->endDate));?></td>
    <?php /*<td>
        <?php $categoryNameData='';
        $categoryNameData=$InerArr->CountycategoryName.' - '.$InerArr->categoryName;
        if($InerArr->LastcategoryName!=""){$categoryNameData.=" - ".$InerArr->LastcategoryName;}
        echo $categoryNameData;
    </td>
    <td><?php echo ($InerArr->userUsesType==1) ? 'User' : 'System';?></td>
    <td><?php echo ($InerArr->usesType==1) ? 'Single' : 'Multiple';?></td>*/?>
    <td><?php echo ($InerArr->status=='1')?'Active':'Inactive';?></td>
    <td>
	<?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo base_url().'webadmin/coupon/change_status/'.$InerArr->couponId.'/'.$action;?>" class="AdminDashBoardLinkText">
            <?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="" title="Inactive" />
        <?php }else{?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="" title="Active" /><?php }?></a>
	<?php /*if($InerArr->isUsed==0){?>&nbsp;
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->couponId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'admin/';?>edit.png" width="15" height="15" title="Edit"/></a>
        <?php / }*/ ?>&nbsp; 
        <a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->couponId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>delete.png" width="15" height="15" title="Delete"/></a> &nbsp; 
	<?php /*<a href="javascript:void(0);" onclick="ShowAddMoreUser('<?php echo $InerArr->couponId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'admin/';?>add-user.png" width="20" height="20" title="Add User"/></a> &nbsp; 
        <?php if($InerArr->userUsesType==1){?>
        <a href="javascript:void(0);" onclick="ShowAllUsers('<?php echo $InerArr->couponId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'admin/';?>view_all_user.png" width="20" height="20" title="View All User"/></a>
        <?php } */?>&nbsp; 
	</td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->couponId?>]=new Array();
  DataArr[<?php echo $InerArr->couponId?>]['couponId']='<?php echo $InerArr->couponId?>';
  DataArr[<?php echo $InerArr->couponId?>]['code']='<?php echo $InerArr->code?>';
  DataArr[<?php echo $InerArr->couponId?>]['type']='<?php echo $InerArr->type?>';
  DataArr[<?php echo $InerArr->couponId?>]['amount']='<?php echo $InerArr->amount?>';
  <?php /*DataArr[<?php echo $InerArr->couponId?>]['categoryId']='<?php echo $InerArr->categoryId?>'; */ ?>
  DataArr[<?php echo $InerArr->couponId?>]['startDate']='<?php echo date('d-m-Y',strtotime($InerArr->startDate));?>';
  DataArr[<?php echo $InerArr->couponId?>]['endDate']='<?php echo date('d-m-Y',strtotime($InerArr->endDate));?>';
  DataArr[<?php echo $InerArr->couponId?>]['status']='<?php echo $InerArr->status?>';
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
    <td><form name="AdminEdit" id="AdminEdit" method="post" action="<?php echo base_url()?>webadmin/coupon/edit/">
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">Coupon Edit Form</span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Coupon code </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="Editcode"></span>
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
    <td align="left" valign="top" class="ListHeadingLable">Coupon code Price Type</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
		<select name="Edittype" id="Edittype" class="required">
			<option value="">Select</option>
			<option value="1">Fix</option>
			<option value="2">Percentage</option>
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
    <td align="left" valign="top" class="ListHeadingLable"> Coupon code Price For USA</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="Editamount" type="text" id="Editamount"  class="required" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Start Date </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="EditstartDate" type="text" id="EditstartDate"  class="required" readonly/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> End Date </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="EditendDate" type="text" id="EditendDate"  class="required" readonly/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Select Category </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="Editcategory" id="Editcategory">
            <option value="">Select</option>
            <?php foreach($categoryData AS $k => $v){?>
            <option value="<?php echo $k;?>"><?php echo $v;?></option>
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
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class=" btn-large btn-primary"/>
	  <input  type="hidden" name="couponId"  id="couponId" value=""/></td>
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
    <td><form name="AdminAdd" id="AdminAdd" method="post" action="<?php echo base_url();?>webadmin/coupon/add" >
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading">Coupon Add Form </span></th>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Coupon code </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="code" type="text" id="code"  class="required" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Coupon Price Type</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
		<select name="type" id="type" class="required">
			<option value="">Select</option>
			<option value="fix">Fix</option>
			<option value="percentage">Percentage</option>
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
    <td align="left" valign="top" class="ListHeadingLable"> Amount</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="amount" type="text" id="amount"  class="required" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Start Date </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="startDate" type="text" id="startDate"  class="required" readonly/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> End Date </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="endDate" type="text" id="endDate"  class="required" readonly/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <?php /*<tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Select Category </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="category" id="category" class="required">
            <option value="">Select</option>
            <?php foreach($categoryData AS $k => $v){?>
            <option value="<?php echo $k;?>"><?php echo $v;?></option>
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
    <td align="left" valign="top" class="ListHeadingLable"> Select User </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="userID" id="userID">
            <option value="">Select</option>
            <?php foreach($UserDataArr AS $k){?>
            <option value="<?php echo $k->userId;?>"><?php echo $k->firstName.' '.$k->lastName.'  -  '.$k->email;?></option>
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
    <td align="left" valign="top" class="ListHeadingLable"> User Uses Type </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="userUsesType" id="userUsesType">
            <option value="">Select</option>
            <option value="1">Single</option>
            <option value="2">Multiple</option>
        </select>
    </td>
  </tr>*/ ?>
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
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-large btn-primary"/></td>
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
      <td>
          <form name="AdminAddUser" id="AdminAddUser" method="post" action="<?php echo base_url();?>webadmin/coupon/add_user" >
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddUserBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading">Add User to Coupon code</span></th>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Coupon code </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><span id="couponcodeSpan1" style=" font-weight: bold;"></span></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Select User </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="addUserId" id="addUserId" class="required">
            <option value="">Select</option>
            <?php foreach($UserDataArr AS $k){?>
            <option value="<?php echo $k->userId;?>"><?php echo $k->firstName.' '.$k->lastName.'  -  '.$k->email;?></option>
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
    <td align="left" valign="top" class="ListHeadingLable"> User Uses Type </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="userUsesType" id="userUsesType" class="required">
            <option value="">Select</option>
            <option value="1">Single</option>
            <option value="2">Multiple</option>
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
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><label></label></td>
    <td align="left" valign="top"><input type="submit" name="Submit3" value="Submit" class="btn-primary btn-large"/>&nbsp;&nbsp;&nbsp;
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-large btn-primary"/>
      <input type="hidden" name="couponCodeId" id="couponCodeId" value="" /></td>
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
      <td>
          <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0" id="ViewAllUserBox" style="display:none;">
              <tr><td>&nbsp;</td></tr>
              <tr><td>&nbsp;</td></tr>
              <tr><td>&nbsp;</td></tr>
              <tr>
                  <td>
                      <div id="ViewAllUserBoxContent">
                          
                      </div>
                  </td>
              </tr>
              <tr><td>&nbsp;</td></tr>
              <tr><td>&nbsp;</td></tr>
              <tr><td><input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-large btn-primary"/></td></tr>
              <tr><td>&nbsp;</td></tr>
              <tr><td>&nbsp;</td></tr>
          </table>
      </td>
  </tr>
</table></td>
  </tr>

</table>
<?php echo $AdminHomeRest;?>
<script>
$(document).ready(function(){
	$("#AdminAdd").validate();
	$("#AdminEdit").validate();
        $("#AdminAddUser").validate();
        
        
        $("#startDate").datepicker({
            dateFormat:"dd-mm-yy",
            minDate:0,
            maxDate: "+4M +10D",
            onSelect: function (selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate() + 1);
                $("#endDate").datepicker("option", "minDate", dt);
            }
        });
        
        $("#endDate").datepicker({
            dateFormat:"dd-mm-yy",
            minDate:0,
            maxDate: "+4M +10D",
            onSelect: function (selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate() - 1);
                $("#startDate").datepicker("option", "maxDate", dt);
            }
        });
        
        $("#EditstartDate").datepicker({
            dateFormat:"dd-mm-yy",
            yearRange: '1900:' + new Date().getFullYear()
        });
        
        $("#EditendDate").datepicker({
            dateFormat:"dd-mm-yy",
            yearRange: '1900:' + new Date().getFullYear()
        });
        
        $('#userId').on('change',function(){
            if($(this).val()!=''){
                $('#userUsesType').addClass('required');
            }else{
                $('#userUsesType').removeClass('required');
            }
        });
        
        $('#addUserId').on('change',function(){
            $.ajax({
                type:"POST",
                url:'<?php echo ADMIN_BASE_URL.'ajax/check_coupon_user/';?>',
                data:'couponId='+$('#couponCodeId').val()+'&userId='+$(this).val(),
                success:function(msg){
                    if(msg=='exist'){
                        alert('Select user has added to this coupon,Please add new one.');
                        $('#addUserId').val('');
                    }
                }
            })
        });
        
        $('#code').on('blur',function(){
            $.ajax({
                type:"POST",
                url:'<?php echo ADMIN_BASE_URL.'ajax/coupon_exists/';?>',
                data:'code='+$('#code').val(),
                success:function(msg){
                    if(msg=='exist'){
                        alert('This coupon is already exists,Please enter new one.');
                        $('#code').val('');
                    }
                }
            });
        });
});
</script>