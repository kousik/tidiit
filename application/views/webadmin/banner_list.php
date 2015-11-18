<?php echo $AdminHomeLeftPanel;
//print_r($UserDataArr);die;
$PageArr=array('1'=>'Home','2'=>'Category');
$bannerTypeArr=array('slider'=>'Slider','normal'=>'Normal');
$sliderSlNoArr=array(1=>1,2=>2);
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
 
 function showEdit(id){
     var imgSrc='<?php echo SiteResourcesURL.'banner/admin/';?>';
     if(DataArr[id]['pageId']=='1'){
            $('#EditHomePageBannerSerialNo').show();
            $('#EditHomePageBannerSerialNo1').show();
            $('#EditbannerCategoryDataRow').hide();
            $('#EditbannerCategoryDataRow1').hide();
            $('#EditbannerCategoryDataRow2').hide();
            $('#EditbannerCategoryDataRow3').hide();
            $('#EditbannerCategoryDataRow4').hide();
            $('#EditbannerCategoryDataRow5').hide();
     }else{
            $('#EditHomePageBannerSerialNo').hide();
            $('#EditHomePageBannerSerialNo1').hide();
            $('#EditbannerCategoryDataRow').show();
            $('#EditbannerCategoryDataRow1').show();
            $('#EditbannerCategoryDataRow2').show();
            $('#EditbannerCategoryDataRow3').show();
            $('#EditbannerCategoryDataRow4').show();
            $('#EditbannerCategoryDataRow5').show();
            $('#EditcategoryImageTitle').val(DataArr[id]['categoryImageTitle']);
            $('#EditcategoryImageDetails').val(DataArr[id]['categoryImageDetails']);
     }
     $('#EditpageId').val(DataArr[id]['pageId']);
     $('#EditbannerType').val(DataArr[id]['bannerType']);
     $('#EditsliderSlNo').val(DataArr[id]['sliderSlNo']);
     $('#Editurl').val(DataArr[id]['url']);
     $('#EditcategoryId').val(DataArr[id]['categoryId']);
     //$('#EditpageId').val(DataArr[id][pageId]);
     $('#EditbannerImg').attr('src',imgSrc+DataArr[id]['image']);
     $('#bannerId').val(id);
    $('#MessageBox').html("");
    $('#AddBox').hide();
    $('#AddBtn').hide();
    $('#PageHeading').hide();
    $('#ListBox').fadeOut(500);
    $('#EditBox').fadeIn(3500);
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
    <td width="8%">Page Name</td>
    <td width="5%">Banner Type</td>
    <td width="5%">Banner No</td>
    <td width="10%">Category Name</td>
    <td width="30%">URL</td>
    <td width="5%">Status</td>
    <td width="8%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?></td>
    <td><img src="<?php echo $SiteResourcesURL.'banner/admin/'.$InerArr->image;?>" alt=""  title="<?php echo $InerArr->categoryImageTitle;?>"/></td>
    <td><?php echo $PageArr[$InerArr->pageId]; ?></td>
    <td><?php echo $InerArr->bannerType; ?></td>
    <td><?php echo $InerArr->sliderSlNo; ?></td>
    <td><?php echo $InerArr->categoryName; ?></td>
    <td><?php echo $InerArr->url; ?></td>
    <td><?php echo ($InerArr->status=='1')?'Active':'Inactive';?></td>
    <td><?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
        <a href="<?php echo base_url().'webadmin/banner/change_status/'.$InerArr->bannerId.'/'.$action;?>" class="AdminDashBoardLinkText"><?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="Inactive" title="Active" /><?php }else{?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="Inactive" title="Inactive" /><?php }?></a>&nbsp;<a href="javascript:void(0);" onclick="showEdit('<?php echo $InerArr->bannerId;?>');" class="AdminDashBoardLinkText edit"></a>&nbsp;<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->bannerId;?>');" class="AdminDashBoardLinkText delete"></a>
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
  DataArr[<?php echo $InerArr->bannerId?>]['categoryId']='<?php echo $InerArr->categoryId?>';
  DataArr[<?php echo $InerArr->bannerId?>]['categoryImageTitle']='<?php echo $InerArr->categoryImageTitle?>';
  DataArr[<?php echo $InerArr->bannerId?>]['categoryImageDetails']='<?php echo $InerArr->categoryImageDetails?>';
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
    <th width="10%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="25%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="62%" align="left" valign="top" scope="col"><span class="PageHeading">Banner Add From</span></th>
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
        <select name="sliderSlNo" id="sliderSlNo" class="required">
            <option value="">Select Type</option>
            <?php foreach($sliderSlNoArr As $k => $v){?>
            <option value="<?php echo $k;?>"><?php echo $v;?></option>
            <?php }?>
        </select>
    </td>
  </tr>
  <tr id="bannerCategoryDataRow" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr id="bannerCategoryDataRow1" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Select Category </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="categoryId" class="required" id="categoryId">
            <option value="">Select</option>
            <?php foreach ($CategoryMenuArr AS $categoryId=>$categoryName): ?>
                <option value="<?= $categoryId ?>"><?= $categoryName ?></option>
            <?php endforeach; ?>
        </select>
    </td>
  </tr>
  <tr id="bannerCategoryDataRow2" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr id="bannerCategoryDataRow3" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Category Image Title </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="categoryImageTitle" id="categoryImageTitle" class="required" maxlength="15" /></td>
  </tr>
  <tr id="bannerCategoryDataRow4" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr id="bannerCategoryDataRow5" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Category Image Details</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="categoryImageDetails" id="categoryImageDetails" class="required" maxlength="30" /></td>
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
    <td align="left" valign="top"><input  type="file" name="banner" id="banner" class="required"/></td>
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
      <td>
          <form name="AdminEdit" id="AdminEdit" method="post" action="<?=base_url()?>webadmin/banner/edit"  enctype="multipart/form-data">
              <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th width="10%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="25%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="62%" align="left" valign="top" scope="col"><span class="PageHeading">Banner Add From</span></th>
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
        <select name="EditpageId" id="EditpageId" class="required">
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
        <select name="EditbannerType" id="EditbannerType" class="required">
            <option value="">Select Type</option>
            <?php foreach($bannerTypeArr As $k => $v){?>
            <option value="<?php echo $k;?>"><?php echo $v;?></option>
            <?php }?>
        </select>
    </td>
  </tr>
  <tr id="EditHomePageBannerSerialNo" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr id="EditHomePageBannerSerialNo1" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Home Page Banner Serial No </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="EditsliderSlNo" id="EditsliderSlNo" class="required">
            <option value="">Select Type</option>
            <?php foreach($sliderSlNoArr As $k => $v){?>
            <option value="<?php echo $k;?>"><?php echo $v;?></option>
            <?php }?>
        </select>
    </td>
  </tr>
  <tr id="EditbannerCategoryDataRow" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr id="EditbannerCategoryDataRow1" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Select Category </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="EditcategoryId" class="required" id="EditcategoryId">
            <option value="">Select</option>
            <?php foreach ($CategoryMenuArr AS $categoryId=>$categoryName): ?>
                <option value="<?= $categoryId ?>"><?= $categoryName ?></option>
            <?php endforeach; ?>
        </select>
    </td>
  </tr>
  <tr id="EditbannerCategoryDataRow2" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr id="EditbannerCategoryDataRow3" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Category Image Title </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="EditcategoryImageTitle" id="EditcategoryImageTitle" class="required" maxlength="15" /></td>
  </tr>
  <tr id="EditbannerCategoryDataRow4" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr id="EditbannerCategoryDataRow5" style="display:none;">
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Category Image Details</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="EditcategoryImageDetails" id="EditcategoryImageDetails" class="required" maxlength="30" /></td>
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
    <td align="left" valign="top"><input type="Editurl" name="Editurl" id="url" value=""></td>
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
    <td align="left" valign="top">
        <input  type="file" name="Editbanner" id="Editbanner" class="required" style="display:none;"/>
        <img src="" id="EditbannerImg">
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
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-primary btn-large"/>
      <input type="hidden" name="bannerId" id="bannerId" value=""></td>
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
    $("#AdminAdd").validate();
    $("#AdminEdit").validate();
    $('#pageId').on('change',function(){
        if($(this).val()=='1'){
            $('#HomePageBannerSerialNo').show();
            $('#HomePageBannerSerialNo1').show();
            $('#bannerCategoryDataRow').hide();
            $('#bannerCategoryDataRow1').hide();
            $('#bannerCategoryDataRow2').hide();
            $('#bannerCategoryDataRow3').hide();
            $('#bannerCategoryDataRow4').hide();
            $('#bannerCategoryDataRow5').hide();
        }else{
            $('#HomePageBannerSerialNo').hide();
            $('#HomePageBannerSerialNo1').hide();
            $('#bannerCategoryDataRow').show();
            $('#bannerCategoryDataRow1').show();
            $('#bannerCategoryDataRow2').show();
            $('#bannerCategoryDataRow3').show();
            $('#bannerCategoryDataRow4').show();
            $('#bannerCategoryDataRow5').show();
        }
    });
    
    $('#EditpageId').on('change',function(){
        if($(this).val()=='1'){
            $('#EditHomePageBannerSerialNo').show();
            $('#EditHomePageBannerSerialNo1').show();
            $('#EditbannerCategoryDataRow').hide();
            $('#EditbannerCategoryDataRow1').hide();
            $('#EditbannerCategoryDataRow2').hide();
            $('#EditbannerCategoryDataRow3').hide();
            $('#EditbannerCategoryDataRow4').hide();
            $('#EditbannerCategoryDataRow5').hide();
        }else{
            $('#EditHomePageBannerSerialNo').hide();
            $('#EditHomePageBannerSerialNo1').hide();
            $('#EditbannerCategoryDataRow').show();
            $('#EditbannerCategoryDataRow1').show();
            $('#EditbannerCategoryDataRow2').show();
            $('#EditbannerCategoryDataRow3').show();
            $('#EditbannerCategoryDataRow4').show();
            $('#EditbannerCategoryDataRow5').show();
        }
    });
    
    $('#EditbannerImg').on('click',function(){$('#Editbanner').show();$(this).hide();});
});
</script>