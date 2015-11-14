<?php echo $AdminHomeLeftPanel;
//print_r($UserDataArr);die;
$PageArr=array('1'=>'Home','2'=>'Category');
$bannerTypeArr=array('slider'=>'Slider','normal'=>'Normal');
$sliderSlNoArr=array(1,2);
 ?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading">Banner Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessageBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;"><input type="button" name="AddBtn" id="AddBtn" value="Add Banner" onclick="ShowAddAdminBox();" class="btn-large btn-primary"/></td>
  </tr>
<script language="javascript">
function ShowAddAdminBox(){
	$('#MessageBox').html("");
	$('#EditBox').hide();
	$('#AddBtn').hide();
	$('#PageHeading').hide();
	$('#ListBox').fadeOut(500);
	$('#AddBox').fadeIn(3500);
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
        location.href='<?php echo base_url()?>webadmin/banner/delete/'+id;
    }
    return false;
}
 </script>
  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" id="ListBox"  class="alt_row">
  <tr class="ListHeadingLable" bgcolor="#DFDFDF" height="25px;">
    <td width="5%">Sl No </td>
    <td width="15%">image</td>
    <td width="15%">Page Name</td>
    <td width="5%">Banner Type</td>
    <td width="5%">Banner No</td>
    <td width="15%">URL</td>
    <td width="15">Status</td>
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
    <td><img src="<?php echo $SiteResourcesURL.'banner/admin/'.$InerArr->image;?>" alt="" /></td>
    <td><?php echo $PageArr[$InerArr->pageId]; ?></td>
    <td><?php echo $InerArr->bannerType; ?></td>
    <td><?php echo $InerArr->sliderSlNo; ?></td>
    <td><?php echo $InerArr->url; ?></td>
    <td><?php echo ($InerArr->status=='1')?'Active':'Inactive';?></td>
    <td>
        
	<?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo base_url().'webadmin/banner/change_status/'.$InerArr->bannerId.'/'.$action;?>" class="AdminDashBoardLinkText"><?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="Inactive" title="Active" /><?php }else{?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="Inactive" title="Inactive" /><?php }?></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->bannerId;?>');" class="AdminDashBoardLinkText delete"></a>
	</td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->bannerId?>]=new Array();
  DataArr[<?php echo $InerArr->bannerId?>]['bannerId']='<?php echo $InerArr->bannerId?>';
  DataArr[<?php echo $InerArr->bannerId?>]['image']='<?php echo $InerArr->image?>';
  DataArr[<?php echo $InerArr->bannerId?>]['pageId']='<?php echo $InerArr->pageId?>';
  DataArr[<?php echo $InerArr->bannerId?>]['bannerType']='<?php echo $InerArr->bannerType?>';
  DataArr[<?php echo $InerArr->bannerId?>]['sliderSlNo']='<?php echo $InerArr->sliderSlNo?>';
  DataArr[<?php echo $InerArr->bannerId?>]['url']='<?php echo $InerArr->url?>';
  DataArr[<?php echo $InerArr->bannerId?>]['status']='<?php echo $InerArr->status?>';
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
    <td><form name="AdminAdd" id="AdminAdd" method="post" action="<?=base_url()?>webadmin/banner/add"  enctype="multipart/form-data">
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="18%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="66%" align="left" valign="top" scope="col"><span class="PageHeading">Banner Add From</span></th>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Page Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="pageId" id="pageId" class="required">
            <option value="">Select Page</option>
            <?php foreach($PageArr As $k => $v){?>
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
    <td align="left" valign="top" class="ListHeadingLable">Banner Type </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="bannerType" id="bannerType" class="required">
            <option value="">Select Type</option>
            <?php foreach($bannerTypeArr As $k => $v){?>
            <option value="<?php echo $k;?>"><?php echo $v;?></option>
            <?php }?>
        </select>
    </td>
  </tr>
  <tr id="HomePageBannerSerialNo" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr id="HomePageBannerSerialNo1" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Home Page Banner Serial No </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="bannerType" id="bannerType" class="required">
            <option value="">Select Type</option>
            <?php foreach($sliderSlNoArr As $k => $v){?>
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
    <td align="left" valign="top" class="ListHeadingLable">Banner URL </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="url" name="url" id="url" value=""></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Banner image </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input  type="file" name="Banner" id="Banner" class="required"/></td>
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
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-primary btn-large"/></td>
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
    $('#pageId').on('change',function(){
        if($(this).val()=='1'){
            $('#HomePageBannerSerialNo').show();
            $('#HomePageBannerSerialNo1').show();
        }else{
            $('#HomePageBannerSerialNo').hide();
            $('#HomePageBannerSerialNo1').hide();
        }
    });
});
</script>