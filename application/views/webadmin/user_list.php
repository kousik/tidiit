<?php echo $AdminHomeLeftPanel;

//print_r($UserDataArr);die;?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  <tr id="PageHeading">
    <td class="PageHeading" >Admin User Manager</td>
  </tr>
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
      <td style="padding-left:10px;" id="actionRow">
        <div style="float:left; padding-right: 20px;"> <input type="button" name="AddBtn" id="AddBtn" value="Add User" onclick="ShowAddAdminBox();" class="btn-large btn-primary"/></div>
        <div style="float:left; padding-right: 20px;">
            <label>Select Flag and Action for Batch action : 
            <select id="batchActionSelect" name="batchActionSelect" style="height:25px; margin-top:5px;">
                <option value=""> * Select * </option>
                <option value="BatchActive">Batch Active</option>
                <option value="BatchInActive">Batch Inactive</option>
                <option value="BatchDelete">Batch Delete</option>
            </select></label>
        </div>
        <div style="float:left; padding-right: 20px;">&nbsp;</div>
        <div style="float:left; padding-right: 20px;">
            <form name="filterOptions" value="filterOptions" method="post" action="<?php echo ADMIN_BASE_URL.'user/filter/'?>">
                <label>
                    Select Filter Type
                    <select name="filterOptions" id="filterOptions" style="height:25px; margin-top:5px;">
                        <option value=""> * Select * </option>
                        <option value="userType">By User type</option>
                        <option value="email">By Email</option>
                        <option value="firstName">By First Name</option>
                        <option value="lastName">By Last Name</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="filterOptionsValue" id="filterOptionsValue" value="">
                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="filterOptionsValueSubmit" id="filterOptionsValueSubmit" value="Update by Filter" class="btn-large btn-primary">
                    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" name="clearFilterOptionsValueSubmit" id="clearFilterOptionsValueSubmit" value="Clear Filter" class="btn-large btn-primary">
                </label>
            </form>    
        </div>
    </td>
  </tr>
<script language="javascript">
    oldEditEmailID='';
function ShowAddAdminBox(){
    jQuery('#MessaeBox').html("");
    jQuery('#EditBox').hide();
    jQuery('#actionRow').hide();
    jQuery('#PageHeading').hide();
    jQuery('#ListBox').fadeOut(500);
    jQuery('#AddBox').fadeIn(3500);
}
 function ShowEditBox(id){
    jQuery('#MessaeBox').html("");
    jQuery('#actionRow').fadeOut();
    jQuery('#PageHeading').fadeOut();
    jQuery('#AddBox').fadeOut();
    jQuery('#ListBox').fadeOut(400);

    jQuery('#EditBox').fadeIn(2500);
    jQuery('#userId').val(id);
    oldEditEmailID=DataArr[id]['email'];
    jQuery('#EditfirstName').val(DataArr[id]['firstName']);
    jQuery('#EditlastName').val(DataArr[id]['lastName']);
    jQuery('#Editemail').val(DataArr[id]['email']);
    jQuery('#EditcontactNo').val(DataArr[id]['contactNo']);
    if(DataArr[id]['logisticsId']!=""){
        jQuery('.logisticTypeUserEdit').show();
        jQuery('#editlogisticsId').val(DataArr[id]['logisticsId']);
    }else{
        jQuery('.logisticTypeUserEdit').hide();
    }
 }

 function CancelEdit(){
    jQuery('#AddBox').hide();
    jQuery('#PageHeading').fadeIn(3000);
    jQuery('#ListBox').fadeIn(3000);
    jQuery('#actionRow').fadeIn(3000);
    jQuery('#EditBox').fadeOut(3500);
    return false;
 }
 function CancelAdd(){
    jQuery('#AddBox').fadeOut('fast');
    jQuery('#EditBox').hide();
    jQuery('#PageHeading').fadeIn(3000);
    jQuery('#ListBox').fadeIn(3000);
    jQuery('#actionRow').fadeIn(3000);
    return false;
 }
 
function AskDelete(id){
    if(confirm('Are you sure to delete(Y/N) ?')){
        location.href='<?php echo base_url()?>webadmin/user/delete/'+id;
    }
    return false;
}

function RetirbePassword(id){
    location.href='<?php echo base_url()?>webadmin/user/retrive_password/'+id;
}
 </script>
  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
      <td><form name="user_list_form" id="user_list_form" method="POST" action="<?php echo base_url().'webadmin/user/batchaction/'?>">
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" id="ListBox" class="alt_row">
  <tr class="ListHeadingLable" bgcolor="#DFDFDF" height="25px;">
    <td width="5%">Sl No 
        <a href="#" id="CheckAll" style="text-decoration: underline;">CheckAll</a>
	<a href="#" id="UnCheckAll" style="display: none;text-decoration: underline;">UnCheckAll</a>
    </td>
    <td width="15%">User Name</td>
    <td width="15%">Email</td>
    <td width="10%">First Name</td>
    <td width="10%">Last Name</td>
    <td width="10%">Contact No</td>
    <td width="10%">User Type</td>
    <td width="10%">status</td>
    <td width="25%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?php echo count($DataArr)?>);
  </script>
  <?php $val=0; //pre($DataArr);die;
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td>
        <?php echo $val+1;?>
        <input  type="checkbox" name="userId[]" value="<?php echo $InerArr->userId;?>"/>
    </td>
    <td><?php echo $InerArr->userName;?></td>
    <td><?php echo $InerArr->email;?></td>
    <td><?php echo $InerArr->firstName;?></td>
    <td><?php echo $InerArr->lastName;?></td>
    <td><?php echo $InerArr->contactNo;?></td>
    <td><?php echo $InerArr->userType;?></td>
    <td><?php echo ($InerArr->status=='1')?'Active':'Inactive';?></td>
    <td>
        <?php /*if($InerArr->userName=='webadmin' && $this->session->userdata('ADMIN_SESSION_USERNAME_VAR')=='webadmin'){?>
        &nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->userId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>edit.png" width="15" height="15" title="Edit"/></a>
        <?php }*/
        if($InerArr->userType!='buyer' || $InerArr->userType!='seller'){?>
        &nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->userId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>edit.png" width="15" height="15" title="Edit"/></a>
        <?php }?>
        &nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="RetirbePassword('<?php echo $InerArr->userId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>reset-pass.jpg" width="15" height="15" title="Retrieve Password"/></a>
        <?php //}?>
        <?php if($InerArr->userName!='webadmin'){?>
            <?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo base_url().'webadmin/user/change_status/'.$InerArr->userId.'/'.$action;?>" class="AdminDashBoardLinkText"><?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="Inactive" title="Inactive" /><?php }else{?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="Active" title="Active" /><?php }?></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->userId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>delete.png" width="15" height="15" title="Delete"/></a>
        <?php }?></td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->userId?>]=new Array();
  DataArr[<?php echo $InerArr->userId?>]['userId']='<?php echo $InerArr->userId?>';
  DataArr[<?php echo $InerArr->userId?>]['firstName']='<?php echo $InerArr->firstName?>';
  DataArr[<?php echo $InerArr->userId?>]['lastName']='<?php echo $InerArr->lastName?>';
  DataArr[<?php echo $InerArr->userId?>]['status']='<?php echo $InerArr->status?>';
  DataArr[<?php echo $InerArr->userId?>]['email']='<?php echo $InerArr->email?>';
  DataArr[<?php echo $InerArr->userId?>]['contactNo']='<?php echo $InerArr->contactNo?>';
  DataArr[<?php echo $InerArr->userId?>]['logisticsId']='<?php echo $InerArr->logisticsId?>';
  </script>
  <?php $val++;}
  }else{?>
  <tr class="ListHeadingLable">
    <td colspan="6" style="text-align: center; height: 40px;"> No Report Found</td>
  </tr>
  <?php }?>
</table>
            <input  type="hidden" name="batchaction_fun" id="batchaction_fun" value=""/>
            <input  type="hidden" name="batchaction_id" id="batchaction_id" value=""/>
        </form>
    </td>
  </tr>
 
  <tr>
    <td><form name="AdminEdit" id="AdminEdit" method="post" action="<?php echo base_url()?>webadmin/user/edit/">
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">User Edit  Form</span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr class="logisticTypeUserEdit" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr class="logisticTypeUserEdit" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Select logistic partner </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="editlogisticsId" id="editlogisticsId" class="required">
            <option value="">Select</option>
            <?php foreach($logisticData AS $k){?><option value="<?php echo $k->logisticsId?>"><?php echo $k->title;?></option><?php }?>
        </select>
    </td>
  </tr>
  <tr class="logisticTypeUserEdit" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Email</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="Editemail" type="text" id="Editemail"  class="required email" /></td>
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
    <td align="left" valign="top"><input name="EditfirstName" type="text" id="EditfirstName"  class="required" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Last Name</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="EditlastName" type="text" id="EditlastName"  class="required" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Contact No</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="EditcontactNo" type="text" id="EditcontactNo"  class="required" /></td>
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
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class=" btn-large btn-primary"/>
	  <input  type="hidden" name="userId"  id="userId" value=""/></td>
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
    <td><form name="AdminAdd" id="AdminAdd" method="post" action="<?php echo base_url()?>webadmin/user/add" >
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading">User Add Form </span></th>
  </tr>
  <script type="text/javascript">
      jQuery(document).ready(
              function(){
                  jQuery('#userType').change(function(){
                      if(jQuery(this).val()=='logistics'){
                          jQuery('.logisticTypeUser').show();
                      }else{
                          jQuery('.logisticTypeUser').hide();
                      }
                  });
              });
      
  </script>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Select User Type </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="userType" id="userType">
            <option value="">Select</option>
            <?php foreach($UserTypeArr AS $k){?><option value="<?php echo $k->userType?>"><?php echo $k->description;?></option><?php }?>
        </select></td>
  </tr>
  <tr class="logisticTypeUser" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr class="logisticTypeUser" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Select logistic partner </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="logisticsId" id="logisticsId" class="required">
            <option value="">Select</option>
            <?php foreach($logisticData AS $k){?><option value="<?php echo $k->logisticsId?>"><?php echo $k->title;?></option><?php }?>
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
    <td align="left" valign="top" class="ListHeadingLable">User Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="userName" type="text" id="userName"  class="required email" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Password </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="password" type="password" id="password"  class="required" rangelength="5,25"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Confirm Password </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="confirmPassword" type="password" id="confirmPassword"  class="required" rangelength="5,25" equalTo="#password"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Email</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="email" type="text" id="email"  class="required email" /></td>
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
    <td align="left" valign="top"><input name="firstName" type="text" id="firstName"  class="required" rangelength="3,25"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Last Name</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="lastName" type="text" id="lastName"  class="required" rangelength="3,25"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Contact No</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="contactNo" type="text" id="contactNo"  class="required" number="true"/></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr class="ListHeadingLable">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">status</td>
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
    jQuery("#AdminAdd").validate();	
    jQuery("#AdminEdit").validate();	
    
    jQuery('#filterOptionsValueSubmit').on('click',function(){
        if(jQuery('#filterOptions').val()==""){
            alert('Please select filter option.');
            return false;
        }
        
        if(jQuery.trim(jQuery('#filterOptionsValue').val())==""){
            alert('Please select filter option value.');
            return false;
        }
        document.filterOptions.submit();
    });
    
    jQuery('#clearFilterOptionsValueSubmit').on('click',function(){
        location.href='<?php echo ADMIN_BASE_URL.'user/viewlist'; ?>';
    });
    
    jQuery('#userName').on('blur',function(){
        var ajaxURL='<?php echo ADMIN_BASE_URL.'ajax/is_user_name_exists'?>';
        var ajaxData='userName='+jQuery(this).val();
        $.ajax({
            type :"POST",
            url:ajaxURL,
            data:ajaxData,
            success:function(msg){
                if(msg=='1'){
                    alert('This username has already used,Please enter a new one.');
                    jQuery('#userName').val('');
                    return false;
                }else{
                    return true;
                }
            }
        });
    });
    
    jQuery('#email').on('blur',function(){
        var ajaxURL='<?php echo ADMIN_BASE_URL.'ajax/is_user_email_exists'?>';
        var ajaxData='email='+jQuery(this).val();
        $.ajax({
            type :"POST",
            url:ajaxURL,
            data:ajaxData,
            success:function(msg){
                if(msg=='1'){
                    alert('This email id has already used,Please enter a new one.');
                    jQuery('#email').val('');
                    return false;
                }else{
                    return true;
                }
            }
        });
    });
    
    jQuery('#Editemail').on('blur',function(){
        if(jQuery(this).val()==oldEditEmailID){
            return false;
        }
        var ajaxURL='<?php echo ADMIN_BASE_URL.'ajax/is_edit_user_email_exists'?>';
        var ajaxData='email='+jQuery(this).val()+'&userId='+jQuery('#userId').val();
        $.ajax({
            type :"POST",
            url:ajaxURL,
            data:ajaxData,
            success:function(msg){
                if(msg=='1'){
                    alert('This email id has already used,Please enter a new one.');
                    jQuery('#Editemail').val(oldEditEmailID);
                    return false;
                }else{
                    return true;
                }
            }
        });
    });    
    
    jQuery('#CheckAll').on('click',function(){
	$('input[name="userId[]"]').each(function(){
                jQuery(this).prop( "checked", true );
        });	
        jQuery('#UnCheckAll').show();
        jQuery(this).hide();
    });

    jQuery('#UnCheckAll').on('click',function(){
	$('input[name="userId[]"]:checked').each(function(){
            jQuery(this).prop( "checked", false );
        });	
	jQuery('#CheckAll').show();
	jQuery(this).hide();
    });
    
    $("#batchActionSelect").on('change',function(){
        if(jQuery(this).val()==''){
            return false;
        }
        var itemChecked=false;
        var userIds= new Array();
        $('input[name="userId[]"]').each(function(){
            if (jQuery(this).prop( "checked" )) {
                userIds.push(jQuery(this).val());
                itemChecked=true;
            }
        });

        if(itemChecked==false){
            alert('Please select item for action');
            jQuery(this).val("");
            return false;
        }else{
            if(confirm('Are you sure to do the action ? Y/N')){
                jQuery('#batchaction_fun').val(jQuery(this).val());
                jQuery('#batchaction_id').val(userIds);
                jQuery('#user_list_form').submit();
            }else{
                jQuery(this).val('');
            }
        } 
     });
});
</script>