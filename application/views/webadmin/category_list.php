<?php echo $AdminHomeLeftPanel;
$categoryImagePath=ResourcesPath.'category/admin/';
$categoryImageURL=SiteResourcesURL.'category/admin/';
$categoryTemplateNewArr=array();
foreach($categoryTemplateArr AS $k){
    $categoryTemplateNewArr[$k->categoryViewTemplateID]=$k->templateName;
}
$categoryTemplateArr=$categoryTemplateNewArr;


$productPageTypeNewArr=array();
foreach($productPageTypeArr AS $k){
    $productPageTypeNewArr[$k->productViewTemplateID]=$k->templateName;
}
$productPageTypeArr=$productPageTypeNewArr;
//pre($categoryTemplateArr);
//pre($productPageTypeArr);die;
$parrentCategoryId=$this->uri->segment(4);
if($parrentCategoryId=="")
    $parrentCategoryId=0;
?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  <tr id="PageHeading">
    <td class="PageHeading" >Category Manager</td>
  </tr>  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;"><input type="button" name="AddBtn" id="AddBtn" value="Add Category" onclick="ShowAddAdminBox();" class="btn-primary btn-large"/></td>
  </tr>
<script language="javascript">
function ShowAddAdminBox(){
	$('#MessaeBox').html("");
	$('#EditBox').hide();
	$('#AddBtn').hide();
	$('#BatchActionRow').hide();
	$('#PageHeading').hide();
	$('#ManageSEOTable').hide();
	$('#ListBox').fadeOut(500);
	$('#AddBox').fadeIn(3500);
    $("#last_no_add").click();
    $('input:checkbox[name="options[]"]').each(function(){
        $(this).prop('checked', false);
    });
}
 function ShowEditBox(id){
     $('input:checkbox[name="options[]"]').each(function(){
         $(this).prop('checked', false);
     });

     var options = DataArr[id]['option_ids'].split(",");

     if (options.length > 0 && options[0] != '' ) {
         reorder(options);
         $('input:checkbox[name="options[]"]').each(function(){
             if(jQuery.inArray( $(this).val(), options )!=-1){
                 $(this).prop('checked', true);
             }
         });
     }

 	$('#MessaeBox').html("");
	$('#AddBtn').fadeOut();
	$('#BatchActionRow').fadeOut();
	$('#PageHeading').fadeOut();
	$('#AddBox').fadeOut();
	$('#ManageSEOTable').fadeOut();
	$('#ListBox').fadeOut(400);
	
	$('#EditBox').fadeIn(2500);
	$('#EditBoxTitle').text('"'+DataArr[id]['categoryName']+'"');
	$('#EditcategoryName').val(DataArr[id]['categoryName']);
	$('#EditshortDescription').val(DataArr[id]['shortDescription']);
	$('#EditmetaTitle').val(DataArr[id]['metaTitle']);
	$('#EditmetaKeyWord').val(DataArr[id]['metaKeyWord']);
	$('#EditmetaDescription').val(DataArr[id]['metaDescription']);
        <?php //if($parrentCategoryId>0){?>
        $('#Editview').val(DataArr[id]['view']);
        $('#EdituserCategoryView').val(DataArr[id]['userCategoryView']);
        //$("input[type='radio'][name='Editview'][value='"+DataArr[id]['view']+"']").prop("checked",true);
        //$("input[type='radio'][name='EdituserCategoryView'][value='"+DataArr[id]['userCategoryView']+"']").prop("checked",true);
        if(DataArr[id]['image']==""){
            var srcData='<?php echo SiteImagesURL.'no-image.png';?>';
        }else{
            var srcData='<?php echo $categoryImageURL;?>'+DataArr[id]['image'];
        }
        $('#EditEditcategoryImageImg').attr('height','100').attr('width','100').attr('src',srcData);	
        <?php //}?>
	    $('#categoryId').val(DataArr[id]['categoryId']);

         if(DataArr[id]['is_last'] == 1){
             $("#last_yes").click();
         } else {
             $("#last_no").click();
         }
	
 }

 function CancelEdit(){
	$('#AddBox').hide();
	$('#ManageSEOTable').hide();
	$('#PageHeading').fadeIn(3000); 
	$('#ListBox').fadeIn(3000);
	$('#AddBtn').fadeIn(3000);
	$('#BatchActionRow').fadeIn(3000);
	$('#EditBox').fadeOut(3500);
	return false;
 }
 function CancelAdd(){
 	$('#AddBox').fadeOut('fast');
 	$('#EditBox').hide();
 	$('#ManageSEOTable').hide();
	$('#PageHeading').fadeIn(3000);
	$('#ListBox').fadeIn(3000);
	$('#AddBtn').fadeIn(3000);
	$('#BatchActionRow').fadeIn(3000);
	return false;
 }
 
function AskDelete(id){
	if(confirm('It will delete the current and its subcategories.Are you sure to delete(Y/N) ?')){
		location.href='<?php echo base_url()?>webadmin/category/delete/'+id;
	}
	return false;
}
$(document).ready(function() {
    $('#last_yes, #last_yes_add').on('change', function () {
        $('tr.option-list').show();
    });

    $('#last_no, #last_no_add').on('change', function () {
        $('tr.option-list').hide();
    });

    $( ".sortable" ).sortable({
        revert: true
    });
});

function reorder(orderedArray) {
    var el, pre,
        p = document.getElementById(orderedArray[0]).parentNode;
    orderedArray.forEach(function (a, b, c) {
        if (b > 0) {
            el = document.getElementById(a);
            pre = document.getElementById(c[b - 1]);

            p.insertBefore(el, pre.nextSibling);
        }
    });
}


 </script>
    <style type="text/css">
        ul.sortable li {
            height: 25px;
            margin-bottom: 5px;
            padding-left: 5px;
            list-style-type: none;
            cursor: move;
        }
    </style>
 <tr id="BatchActionRow">
  	<td>
		<input type="button" name="BatchActive" id="BatchActive" value="Batch Active" class="btn-primary btn-large"/>
		<input type="button" name="BatchInActive" id="BatchInActive" value="Batch InActive" class="btn-primary btn-large"/>
                <!--<input type="button" name="BatchPupularStores" id="BatchPupularStores" value="Batch Popular" class="btn-primary btn-large"/>
		<input type="button" name="BatchDelete" id="BatchDelete" value="Batch Delete" class="batch_action_button"/>
                <input type="button" name="Export" id="Export" value="Export" class="btn-primary btn-large"/>
                <input type="button" name="SampleFormateDownload" id="SampleFormateDownload" value="Download Sample Formate" class="btn-primary btn-large"/>-->
	</td>
  </tr>
  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
        <form name="cateory_list_form" id="cateory_list_form" method="POST" action="<?php echo base_url().'webadmin/category/batchaction/'?>">
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" id="ListBox" class="alt_row">
	<?php if($parrentData[0]->categoryId>0){?>
	<tr>
		<td colspan="4" style="font-size: 16px;"><a href='<?php echo base_url().'webadmin/category/viewlist/'.$parrentData[0]->parrentCategoryId;?>'><?php echo $parrentData[0]->categoryName;?></a></td>
	</tr>
  	<?php }?>
  <tr class="ListHeadingLable" bgcolor="#DFDFDF" height="25px;">
    <td width="10%">Sl No 
	<a href="#" id="CheckAll" style="text-decoration: underline;">CheckAll</a>
	<a href="#" id="UnCheckAll" style="display: none;text-decoration: underline;">UnCheckAll</a></td>
    <td width="25%">Category Name </td>
    <td width="10%">Image </td>
    <td width="5%">status</td>
    <td width="17%">Category Page Template</td>
    <td width="23%">Product Page Template</td>
    <td width="10%">Action</td>
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){ //pre($DataArr);die;
  foreach($DataArr as $InerArr){ ?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?><input  type="checkbox" name="categoryId[]" value="<?php echo $InerArr->categoryId;?>"/></td>
    <td>
	<a href='<?php echo base_url().'webadmin/category/viewlist/'.$InerArr->categoryId;?>' style="text-decoration: underline;"> <?php echo $InerArr->categoryName;?></a>
	</td>
        <td><?php if($InerArr->image==""){?>
            <img src="<?php echo SiteImagesURL.'no-image.png'?>" height="50" width="50">    
        <?php }else{
            if(!file_exists($categoryImagePath.$InerArr->image)){
            ?>
            <img src="<?php echo SiteImagesURL.'no-image.png'?>" height="50" width="50">
        <?php }else{?>
            <img src="<?php echo $categoryImageURL.$InerArr->image;?>">
        <?php }
        }?></td>
	<?php /*<td><?php echo ($InerArr->PopularStore=='1')?'Yes':'No';?></td>*/?>
    <td><?php echo ($InerArr->status=='1')?'Active':'Inactive';?></td>
    <td><?php if($InerArr->userCategoryView!='' && $InerArr->userCategoryView>0){echo ($InerArr->userCategoryView==1) ? 'Parrent Level Template' : 'Last Level Template';}?></td>
    <td><?php if($InerArr->view!='' && $InerArr->view>0){echo $productPageTypeArr[$InerArr->view];}?></td>
    <td>
	<?php if($InerArr->status=='1'){$action=0;}else{$action=1;}?>
	<a href="<?php echo base_url().'webadmin/category/change_status/'.$InerArr->categoryId.'/'.$action;?>" class="AdminDashBoardLinkText categoryActiveInactive"><?php if($InerArr->status=='1'){?><img src="<?php echo $SiteImagesURL.'webadmin/';?>active1.png" alt="Inactive" title="Active" /><?php }else{?><img src="<?php echo $SiteImagesURL.'webadmin/';?>inactive1.png" alt="Inactive" title="Inactive" /><?php }?></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="ShowEditBox('<?php echo $InerArr->categoryId;?>');" class="AdminDashBoardLinkText"><img src="<?php echo $SiteImagesURL.'webadmin/';?>edit.png" width="15" height="15" title="Edit"/></a>
	&nbsp;&nbsp;
	<a href="javascript:void(0);" onclick="AskDelete('<?php echo $InerArr->categoryId;?>');" class="AdminDashBoardLinkText">
            <img src="<?php echo $SiteImagesURL.'webadmin/';?>delete.png" width="15" height="15" title="Delete"/></a>
    </td> 
  </tr>
  <script language="javascript">
  DataArr[<?php echo $InerArr->categoryId?>]=new Array();
  DataArr[<?php echo $InerArr->categoryId?>]['categoryId']='<?php echo $InerArr->categoryId?>';
  DataArr[<?php echo $InerArr->categoryId?>]['categoryName']="<?php echo str_replace(array("\r\n","\n\r","\r", "\n"), '', stripslashes($InerArr->categoryName));?>";
  DataArr[<?php echo $InerArr->categoryId?>]['shortDescription']="<?php echo $InerArr->shortDescription?>";
  DataArr[<?php echo $InerArr->categoryId?>]['metaTitle']="<?php echo $InerArr->metaTitle?>";
  DataArr[<?php echo $InerArr->categoryId?>]['metaKeyWord']="<?php echo $InerArr->metaKeyWord?>";
  DataArr[<?php echo $InerArr->categoryId?>]['metaDescription']="<?php echo $InerArr->metaDescription?>";
  DataArr[<?php echo $InerArr->categoryId?>]['view']='<?php echo $InerArr->view?>';
  DataArr[<?php echo $InerArr->categoryId?>]['image']='<?php echo $InerArr->image?>';
  DataArr[<?php echo $InerArr->categoryId?>]['userCategoryView']='<?php echo $InerArr->userCategoryView?>';
  DataArr[<?php echo $InerArr->categoryId?>]['status']='<?php echo $InerArr->status?>';
  DataArr[<?php echo $InerArr->categoryId?>]['is_last']='<?php echo $InerArr->is_last?>';
  DataArr[<?php echo $InerArr->categoryId?>]['option_ids']="<?php echo $InerArr->option_ids?>";
  </script>
  <?php $val++;}
  }else{?>
  <tr class="ListHeadingLable">
    <td colspan="8" style="text-align: center; height: 40px;"> No Report Found</td>
  </tr>
  
  <?php }?>
  <input  type="hidden" name="batchaction_fun" id="batchaction_fun" value=""/>
	<input  type="hidden" name="batchaction_id" id="batchaction_id" value=""/>
  </form>
</table>
        </form></td>
  </tr>
 
  <tr>
    <td><form name="AdminEdit" id="AdminEdit" method="post" action="<?php echo base_url()?>webadmin/category/edit/"  enctype="multipart/form-data">
<table width="70%" border="0" align="center" cellpadding="0" cellspacing="0" id="EditBox" style="display:none;">
  <tr>
    <th colspan="4"><span class="PageHeading">Category Edit of <span id="EditBoxTitle"></span></span></th>
  </tr>
  <tr>
    <td align="left" valign="top" height="40px;">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Category Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="EditcategoryName" type="text" id="EditcategoryName"  class="required" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Short Description </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><textarea name="EditshortDescription" type="text" id="EditshortDescription"  class="required"></textarea> </td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Meta Title </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="text" name="EditmetaTitle" type="text" id="EditmetaTitle"  class="required" /></textarea> </td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Meta Keywords </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><textarea name="EditmetaKeyWord" type="text" id="EditmetaKeyWord"  class="required"></textarea> </td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Meta Description </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><textarea name="EditmetaDescription" type="text" id="EditmetaDescription"  class="required"></textarea> </td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <?php if($parrentCategoryId>0){?>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Select Category Page Template </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="EdituserCategoryView" id="EdituserCategoryView" required>
        <option value="">Select</option>
        <?php foreach($categoryTemplateArr as $k=>$v){?>
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
    <td align="left" valign="top" class="ListHeadingLable"> Select Seller and Buyer Product Page Template </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="Editview" id="Editview" required><option value="">Select</option>
        <?php foreach($productPageTypeArr as $k=>$v){?>
            <option value="<?php echo $k;?>"><?php echo $v;?></option>
        <?php }?>
        </select>
    </td>
  </tr>
  <?php }?>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable">Browse image</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <img src="" height="1" width="1" id="EditEditcategoryImageImg" title="Click here to change this image" alt="Click here to change this image">
        <input type="file" name="EditcategoryImage" id="EditcategoryImage" style="display:none;">
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
        <td align="left" valign="top">Want to add Product Option Groups?</td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top">Yes
            <input name="is_last" type="radio" value="1" id="last_yes"/>
            &nbsp;No
            <input name="is_last" type="radio" value="0" id="last_no"/></td>
    </tr>

    <tr class="ListHeadingLable option-list" style="display: none;">
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">Select Option Group</td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top">
            <div>
                <ul class="sortable">
            <?php foreach($options as $kop => $opt){?>
                    <li class="ui-state-default" id="<?=$opt->id?>"><input name="options[]" type="checkbox" value="<?=$opt->id?>" class="options"/> <?=$opt->name?> -- [ <?=$opt->display_name?> ]</li>
            <?php }?>
                </ul>
            </div>
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
  <input  type="hidden" name="parrentCategoryId" value="<?php echo $parrentData[0]->categoryId;?>"/>
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
      <input type="button" name="Submit22" value="Cancel" onclick="return CancelAdd();" class="btn-primary btn-large"/>
	  <input  type="hidden" name="categoryId"  id="categoryId" value=""/></td>
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
    <td><form name="AdminAdd" id="AdminAdd" method="post" action="<?php echo base_url()?>webadmin/category/add"  enctype="multipart/form-data">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" id="AddBox" style="display:none;">
  <tr>
    <th width="13%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="28%" align="left" valign="top" scope="col">&nbsp;</th>
    <th width="3%" align="left" valign="top" scope="col" class="PageHeading">&nbsp;</th>
    <th width="56%" align="left" valign="top" scope="col"><span class="PageHeading">Category Add</span></th>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Category Name </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input name="categoryName" type="text" id="categoryName"  class="required" /></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Short Description </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><textarea name="shortDescription" type="text" id="shortDescription"  class="required"></textarea></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Meta title </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><textarea name="metaTitle" type="text" id="metaTitle"  class="required"></textarea></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Meta Keywords </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><textarea name="metaKeyWord" type="text" id="metaKeyWord"  class="required"></textarea></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Meta Descriptions </td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><textarea name="metaDescription" type="text" id="metaDescription"  class="required"></textarea></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Select Category Image</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top"><input type="file" name="categoryImage" id="categoryImage"></td>
  </tr>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>
  <?php if($parrentCategoryId>0){?>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top" class="ListHeadingLable"> Select Category Page Template</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="userCategoryView" id="userCategoryView" required><option value="">Select</option>
        <?php foreach($categoryTemplateArr as $k => $v){?>
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
    <td align="left" valign="top" class="ListHeadingLable"> Select Seller and Buyer Product Page Template</td>
    <td align="left" valign="top"><label><strong>:</strong></label></td>
    <td align="left" valign="top">
        <select name="view" id="view" required><option value="">Select</option>
        <?php foreach($productPageTypeArr as $k=>$v){?>
            <option value="<?php echo $k;?>"><?php echo $v;?></option>
        <?php }?>
        </select>
    </td>
  </tr>
  <?php }?>
  <tr>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top">&nbsp;</td>
  </tr>

    <tr class="ListHeadingLable">
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">Want to add Product Option Groups?</td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top">Yes
            <input name="is_last" type="radio" value="1" id="last_yes_add"/>
            &nbsp;No
            <input name="is_last" type="radio" value="0" id="last_no_add"/></td>
    </tr>

    <tr class="ListHeadingLable option-list" style="display: none;">
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">Select Option Group</td>
        <td align="left" valign="top"><label><strong>:</strong></label></td>
        <td align="left" valign="top">
            <div>
                <ul class="sortable">
                    <?php foreach($options as $kop => $opt){?>
                        <li class="ui-state-default"><input name="options[]" type="checkbox" value="<?=$opt->id?>" class="options"/> <?=$opt->name?> -- [ <?=$opt->display_name?> ]</li>
                    <?php }?>
                </ul>
            </div>
        </td>
    </tr>
    <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
    </tr>
  <tr class="ListHeadingLable">
    <td align="left" valign="top"><input  type="hidden" name="parrentCategoryId" value="<?php echo $parrentData[0]->categoryId;?>"/></td>
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
    <td></td>
  </tr>
</table></td>
  </tr>

</table>
<?php echo $AdminHomeRest;?>
<script type="text/javascript">
$(document).ready(function(){
	$("#AdminAdd").validate();	
	$("#AdminAdd1").validate();	
	
        $('#EditEditcategoryImageImg').on('click',function(){
            $('#EditcategoryImage').fadeIn('slow');
            $(this).fadeOut();
        });
	$('#categoryName').on('blur',function(){
            var CheckUserNameAjaxURL='<?php echo ADMIN_BASE_URL.'ajax/check_category_name/'?>';
            var CheckUserNameAjaxData='categoryName='+$(this).val();
            $.ajax({
               type: "POST",
               url: CheckUserNameAjaxURL,
               data: CheckUserNameAjaxData,
               success: function(msg){
                    if(msg=='1'){
                        alert($('#categoryName').val()+' has already used,Please enter a new one.');
                        $('#categoryName').val('');
                        return false;
                    }else{
                        return true;
                    }
               }
             });
	});
	
	
	
	$('#EditcategoryName').on('blur',function(){
            var CheckEditUserNameAjaxURL='<?php echo ADMIN_BASE_URL.'ajax/check_edit_category_name/'?>';
            var CheckEditUserNameAjaxData='categoryName='+$(this).val()+'&categoryId='+$('#categoryId').val();
            $.ajax({
               type: "POST",
               url: CheckEditUserNameAjaxURL,
               data: CheckEditUserNameAjaxData,
               success: function(msg){ 
                    if(msg=='1'){
                        alert($('#EditcategoryName').val()+' has already used,Please enter a new one.');
                        $('#EditcategoryName').val('');
                        return false;
                    }else{
                        return true;
                    }
               }
             });
	}); 
	
	
	$('#CheckAll').on('click',function(){
		$('input[name="categoryId[]"]').each(function(){
                jQuery(this).prop( "checked", true );
        });	
		$('#UnCheckAll').show();
		$(this).hide();
	});
	
	$('#UnCheckAll').on('click',function(){
		$('input[name="categoryId[]"]:checked').each(function(){
                jQuery(this).prop( "checked", false );
        });	
		$('#CheckAll').show();
		$(this).hide();
	});
	
	$('#BatchActive').on('click',function(){
		var itemChecked=false;
		var categoryIds= new Array();
		$('input[name="categoryId[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				categoryIds.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch active');
			return false;
		}else{
                    if(confirm('Are you sure to active the selected cateory ? Y/N')){
			$('#batchaction_fun').val('batchactive');
			$('#batchaction_id').val(categoryIds);
			$('#cateory_list_form').submit();
                    }    
		}
	});
	
	$('#BatchInActive').on('click',function(){
		var itemChecked=false;
		var categoryIds= new Array();
		$('input[name="categoryId[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				categoryIds.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch active');
			return false;
		}else{
                    if(confirm('Are you sure to inactive the selected cateory ? Y/N')){
			$('#batchaction_fun').val('batchinactive');
			$('#batchaction_id').val(categoryIds);
			$('#cateory_list_form').submit();
                    }    
		}
	});
	
	$('#BatchDelete').on('click',function(){
		var itemChecked=false;
		var categoryIds= new Array();
		$('input[name="categoryId[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				categoryIds.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch active');
			return false;
		}else{
			$('#batchaction_fun').val('batchdelete');
			$('#batchaction_id').val(categoryIds);
			$('#product_list_form').submit();
		}
	});
	
	
        
        $('#Export').on('click',function(){
            location.href='<?php echo ADMIN_BASE_URL.'ajax/export_cartegory';?>';
        });
        
        $('#SampleFormateDownload').on('click',function(){
            location.href='<?php echo ADMIN_BASE_URL.'ajax/csv_sample_format/category';?>';
        });
        
        $('.ActiveCategoryLinkClass').on('click',function(){
            //alert('rr');
            var cat_id=$(this).attr('alt');
            var ActiveCategoryLink=$(this).attr('ActiveCategoryLink');
            location.href='<?php echo base_url().'webadmin/category/manage_category_link/'?>'+cat_id+'/'+ActiveCategoryLink;
        });
        
        $('.ActiveAddTOCartLinkClass').on('click',function(){
            //alert('rr');
            var cat_id=$(this).attr('alt');
            var ActiveAddTOCartLink=$(this).attr('ActiveAddTOCartLink');
            location.href='<?php echo base_url().'webadmin/category/manage_category_add_to_cart_link/'?>'+cat_id+'/'+ActiveAddTOCartLink;
        });
        
        $('.categoryActiveInactive').click(function(){
            if(confirm('Are you sure to change the status ? Y/N')){
                return true;
            }else{
                return false;
            }
        });
});
</script>