<?php
echo $AdminHomeLeftPanel;
$brandImagePath=ResourcesPath.'brand/admin/';
$brandImageURL=SiteResourcesURL.'brand/admin/';
?>

<script type="application/javascript">
    function ShowAddAdminBox(){
        $('#MessaeBox').html("");
        $('#EditBox').show();
        $('#AddBtn').hide();
        $('#PageHeading').hide();
        $('#ListBox').fadeOut(500);
        //$('#AddBox').fadeIn(3500);
        $('#id').val('');
        $("#sprice_no").click();
        $("#rqd_no").click();
        $("#stat_yes").click();
    }
    function ShowEditBox(id){

        $('#MessaeBox').html("");
        $('#AddBtn').fadeOut();
        $('#PageHeading').fadeOut();
        $('#AddBox').fadeOut();
        $('#ListBox').fadeOut(400);

        $('#EditBox').fadeIn(2500);
        $('#name').val(DataArr[id]['name']);

        $('#display_name').val(DataArr[id]['display_name']);
        //$('#slug').val(DataArr[id]['slug']);
        $('#sequence').val(DataArr[id]['sequence']);


        if(DataArr[id]['status'] == 1){
            $("#stat_yes").click();
        } else {
            $("#stat_no").click();
        }


        $('#type').val(DataArr[id]['type']);
        $('#option_data').val(DataArr[id]['option_data']);

        if(DataArr[id]['show_price'] == 1){
            $("#sprice_yes").click();
        } else {
            $("#sprice_no").click();
        }

        if(DataArr[id]['required'] == 1){
            $("#rqd_yes").click();
        } else {
            $("#rqd_no").click();
        }

        if(DataArr[id]['top'] == 1){
            $("#top_yes").click();
        } else {
            $("#top_no").click();
        }

        $('#id').val(DataArr[id]['id']);
        $('#stype').val("update");
    }

    function CancelEdit(){
        $('#AddBox').hide();
        $('#PageHeading').fadeIn(3000);
        $('#ListBox').fadeIn(3000);
        $('#AddBtn').fadeIn(3000);
        $('#EditBox').fadeOut(3500);
        $('#AdminEdit')[0].reset();
        return false;
    }
    function CancelAdd()
    {
        $('#AddBox').fadeOut('fast');
        $('#EditBox').hide();
        $('#PageHeading').fadeIn(3000);
        $('#ListBox').fadeIn(3000);
        $('#AddBtn').fadeIn(3000);
        $('#AdminAdd')[0].reset();
        return false;
    }

    function AskDelete(id)
    {
        if(confirm('Are you sure to delete(Y/N) ?'))
        {
            location.href='<?php echo base_url()?>webadmin/options/delete/'+id;
        }
        return false;
    }
</script>


<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Option Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;">
        <div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;">
            <?php echo $this->session->flashdata('Message'); ?>
        </div>
    </td>
  </tr>
  <tr>
    <td style="padding-left:10px;"><input type="button" name="AddBtn" id="AddBtn" value="Add Option" onclick="ShowAddAdminBox();" class="btn-large btn-primary"/></td>
  </tr>

  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" id="ListBox" class="alt_row">
  <tr class="ListHeadingLable" bgcolor="#DFDFDF" height="25px;">
    <td width="4%">Sl No </td>
    <td width="20%">Option Name</td>
    <td width="20%">Display Name</td>
    <td width="15%">Status</td>
    <td width="20%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?></td>
    <td><?php echo $InerArr->name;?></td>
    <td><?php echo $InerArr->display_name;?>
    </td>
    <td><?php echo ($InerArr->status=='1')?'Active':'Inactive';?></td>
    <td>
	<?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo base_url().'webadmin/options/change_status/'.$InerArr->id.'/'.$action;?>" class="AdminDashBoardLinkText">
            <?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="Inactive" title="Active" /><?php }else{?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="Active" title="inactive" /><?php }?></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->id;?>');" class="AdminDashBoardLinkText edit"></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->id;?>');" class="AdminDashBoardLinkText delete"></a>
	</td> 
  </tr>
  <div class="op-data-<?php echo $InerArr->id?>" style="display: none;"><?php echo rtrim($InerArr->option_data);?></div>
  <script language="javascript">
  DataArr[<?php echo $InerArr->id?>]=new Array();
  DataArr[<?php echo $InerArr->id?>]['id']='<?php echo $InerArr->id?>';
  DataArr[<?php echo $InerArr->id?>]['name']='<?php echo $InerArr->name?>';
  DataArr[<?php echo $InerArr->id?>]['display_name']='<?php echo $InerArr->display_name?>';
  DataArr[<?php echo $InerArr->id?>]['slug']='<?php echo $InerArr->slug?>';
  DataArr[<?php echo $InerArr->id?>]['status']='<?php echo $InerArr->status?>';

  DataArr[<?php echo $InerArr->id?>]['sequence']='<?php echo $InerArr->sequence?>';
  DataArr[<?php echo $InerArr->id?>]['type']='<?php echo $InerArr->type?>';
  DataArr[<?php echo $InerArr->id?>]['option_data']= $("div.op-data-<?php echo $InerArr->id?>").html();
  DataArr[<?php echo $InerArr->id?>]['required']='<?php echo $InerArr->required?>';
  DataArr[<?php echo $InerArr->id?>]['show_price']='<?php echo $InerArr->show_price?>';
  DataArr[<?php echo $InerArr->id?>]['top']='<?php echo $InerArr->top?>';
      //console.log(DataArr[<?php echo $InerArr->id?>]['option_data']);
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
      <td><form name="AdminEdit" id="AdminEdit" method="post" action="<?php echo base_url();?>webadmin/options/edit/" enctype="multipart/form-data">
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">Option Add Form</span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="name" type="text" id="name"  class="required" /></td>
  </tr>

    <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top" class="ListHeadingLable"> Display Name </td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top"><input name="display_name" type="text" id="display_name"  class="required" /></td>
    </tr>

    <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top" class="ListHeadingLable"> Option Type </td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top">
            <select name="type" id="type">
                <option value="text">Text</option>
                <option value="textarea">TextArea</option>
                <option value="checkbox">Checkbox</option>
                <option value="radio">Radiobox</option>
                <option value="dropdown">Dropdown</option>
            </select>
        </td>
    </tr>

    <!--<tr style="display: none;">
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top" class="ListHeadingLable"> Slug </td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top"><input name="slug" type="text" id="slug"  class="required" /></td>
    </tr>-->


    <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top" class="ListHeadingLable"> Option Data [<small>If Required</small>] </td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top"><textarea name="option_data" id="option_data"  class="required"></textarea></td>
    </tr>

    <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top" class="ListHeadingLable">Sequence </td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top"><input name="sequence" type="text" id="sequence" /></td>
    </tr>

    <tr class="ListHeadingLable">
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">Show Price ?</td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top">Yes
            <input name="show_price" type="radio" value="1" id="sprice_yes"/>
            &nbsp;No
            <input name="show_price" type="radio" value="0"  id="sprice_no" /></td>
    </tr>

    <tr class="ListHeadingLable">
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">Is Required?</td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top">Yes
            <input name="required" type="radio" value="1" id="rqd_yes"/>
            &nbsp;No
            <input name="required" type="radio" value="0" id="rqd_no"/></td>
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
    <td align="left" valign="top">Show on top product display?</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">Yes
      <input name="top" type="radio" value="1"  class="top" id="top_yes"/>
&nbsp;No
<input name="top" type="radio" value="0"  class="top"  id="top_no"/></td>
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
            <input name="status" type="radio" value="1"  class="required" id="stat_yes"/>
            &nbsp;Inactive
            <input name="status" type="radio" value="0"  class="required"  id="stat_no"/></td>
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
	  <input  type="hidden" name="id"  id="id" value=""/>
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
    <td></td>
  </tr>
</table></td>
  </tr>

</table>
<?php echo $AdminHomeRest;?>
<script>
$(document).ready(function(){
	$("#AdminAdd").validate();	
        $('#EditbrandImageImg').on('click',function(){
            $('#EditbrandImage').fadeIn('slow');
            $(this).fadeOut();
        });
});
</script>