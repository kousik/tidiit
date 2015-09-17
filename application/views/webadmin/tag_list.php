<?php echo $AdminHomeLeftPanel;

//print_r($parrentData);die;?>
<script src="//code.jquery.com/jquery-1.8.0.js"></script>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Product Tag Manager</td>
  </tr>

  
  <tr>
    <td style="padding-left:50px;">&nbsp;</td>
  </tr>
  <tr>
    <td style="padding-left:50px;"><div id="MessaeBox" style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; color:#009900; text-decoration:blink; text-align:center;"><?php echo $this->session->flashdata('Message');?></div></td>
  </tr>
  <tr>
    <td style="padding-left:10px;">&nbsp</td>
  </tr>
<script language="javascript">


 </script>
 <tr id="BatchActionRow">
  	<td>
		<input type="button" name="BatchAddLanding" id="BatchAddLanding" value="Batch Add to Landing Page" class="btn-primary btn-large"/>
		<input type="button" name="BatchAddHome" id="BatchAddHome" value="Batch Add to Home Page" class="btn-primary btn-large"/> <div style="height:5px;"></div>
                <input type="button" name="BatchRemoveLanding" id="BatchRemoveLanding" value="Batch Remove From Landing Page" class="btn-primary btn-large"/>
		<input type="button" name="BatchRemoveHome" id="BatchRemoveHome" value="Batch Remove From Home Page" class="btn-primary btn-large"/>
	</td>
  </tr>
  <tr>
  <td valign="top"> 
  
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" id="ListBox" class="alt_row">
  <form name="cateory_list_form" id="cateory_list_form" method="POST" action="<?php echo base_url().'admin/product/tag_batchaction/'?>">
  <tr class="ListHeadingLable" bgcolor="#DFDFDF" height="25px;">
    <td width="10%">Sl No 
	<a href="#" id="CheckAll" style="text-decoration: underline;">CheckAll</a>
	<a href="#" id="UnCheckAll" style="display: none;text-decoration: underline;">UnCheckAll</a></td>
    <td width="30%">Tag </td>
    <td width="10%">Home</td>
    <td width="10%">Landing</td>
    
  </tr>
  <script language="javascript">
  var DataArr=new Array(<?=count($DataArr)?>);
  </script>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;">
    <td><?php echo $val+1;?><input  type="checkbox" name="TagID[]" value="<?php echo $InerArr->TagID;?>"/></td>
    <td><?php echo $InerArr->name;?></td>
	<td><?php echo ($InerArr->Home=='1')?'Yes':'No';?></td>
    <td><?php echo ($InerArr->Landing=='1')?'Yes':'No';?></td>
    
  </tr>
  <?php $val++;}
  }else{?>
  <tr class="ListHeadingLable">
    <td colspan="6" style="text-align: center; height: 40px;"> No Report Found</td>
  </tr>
  
  <?php }?>
  <input  type="hidden" name="batchaction_fun" id="batchaction_fun" value=""/>
	<input  type="hidden" name="batchaction_id" id="batchaction_id" value=""/>
  </form>
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
<script>
$(document).ready(function(){
	
	$('#CheckAll').live('click',function(){
		$('input[name="TagID[]"]').each(function(){
                jQuery(this).prop( "checked", true );
        });	
		$('#UnCheckAll').show();
		$(this).hide();
	});
	
	$('#UnCheckAll').live('click',function(){
		$('input[name="TagID[]"]:checked').each(function(){
                jQuery(this).prop( "checked", false );
        });	
		$('#CheckAll').show();
		$(this).hide();
	});
	
	$('#BatchAddLanding').live('click',function(){
		var itemChecked=false;
		var TagIDs= new Array();
		$('input[name="TagID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				TagIDs.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch add to landing page');
			return false;
		}else{
			$('#batchaction_fun').val('batchaddlanding');
			$('#batchaction_id').val(TagIDs);
			$('#cateory_list_form').submit();
		}
	});
        
        
        $('#BatchAddHome').live('click',function(){
		var itemChecked=false;
		var TagIDs= new Array();
		$('input[name="TagID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				TagIDs.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch add to home page');
			return false;
		}else{
			$('#batchaction_fun').val('batchaddhome');
			$('#batchaction_id').val(TagIDs);
			$('#cateory_list_form').submit();
		}
	});
        
	
	$('#BatchRemoveLanding').live('click',function(){
		var itemChecked=false;
		var TagIDs= new Array();
		$('input[name="TagID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				TagIDs.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item to remove from landign page');
			return false;
		}else{
			$('#batchaction_fun').val('batchremovelanding');
			$('#batchaction_id').val(TagIDs);
			$('#cateory_list_form').submit();
		}
	});
	
	$('#BatchRemoveHome').live('click',function(){
		var itemChecked=false;
		var TagIDs= new Array();
		$('input[name="TagID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				TagIDs.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item to remove from home page.');
			return false;
		}else{
			$('#batchaction_fun').val('batchremovehome');
			$('#batchaction_id').val(TagIDs);
			$('#cateory_list_form').submit();
		}
	});
	
	
});
</script>