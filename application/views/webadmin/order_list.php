<?php echo $AdminHomeLeftPanel;
$OrderTypeArr=array('1'=>'Website','2'=>'Mobile Web','3'=>"Mobile Apps");
//print_r($UserDataArr);die;?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/JavaScript" src="<?=$SiteJSURL?>jzaefferer-jquery-form-validatation.js"></script>
<script src="<?php echo SiteJSURL ?>jq_datepicker/js/jquery.ui.core.js"></script>
<script src="<?php echo SiteJSURL ?>jq_datepicker/js/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="<?php echo SiteJSURL ?>jq_datepicker/themes/ui-lightness/jquery.ui.all.css">
<link rel="stylesheet" href="<?php echo SiteJSURL ?>jq_datepicker/css/datepicker_ui.css">

<table cellspacing=5 cellpadding=5 width=100% border=0 >
  
  <tr id="PageHeading">
    <td class="PageHeading" >Order Manager</td>
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
  <tr id="BatchActionRow">
  	<td>
            <table width="100%">
                <tr>
                    <!--<td width="25%"><input type="button" name="BatchActive" id="BatchActive" value="Batch Active" class="batch_action_button"/>
		<input type="button" name="BatchInActive" id="BatchInActive" value="Batch InActive" class="batch_action_button"/><div style="height: 7px;"></div> -->
		<input type="button" name="GoUncompletedOrders" id="GoUncompletedOrders" value="View Uncommpleted Orders" class="btn-primary btn-large"/>
                    </td>
                    <td width="75%">
                        <b>Order From Date</b> : <input type="text" name="FilterOrderFromDate" id="FilterOrderFromDate" style="min-width:120px;"/>
                        <b>Order to Date</b> <input type="text" name="FilterOrderToDate" id="FilterOrderToDate" style="min-width:120px;"/>
                        <div style="height: 5px;"></div>
                        <b>Order Status</b> : <select name="FilterOrderStatus" id="FilterOrderStatus" style="min-width:100px;">
                            <option value="">*select*</option>
                            <?php foreach($OrderStateDataArr AS $k){?>
                            <option value="<?php echo $k->OrderStateID;?>"><?php echo $k->Name;?></option>
                            <?php }?>
                        </select>
                       <b>Enter the user name :</b><input type="text" name="FilterUserName" id="FilterUserName" style="min-width:120px;" />
                       <input type="button" name="FilterOrder" id="FilterOrder" value="Filter" class="btn-primary btn-large"/>
                       <input type="button" name="ShowAllOrder" id="ShowAllOrder" value="Show All" class="btn-primary btn-large"/>
                    </td>
                </tr>
            </table>
		
	</td>
  </tr>

  <tr>
  <td valign="top">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
	<form name="order_list_form" id="order_list_form" method="POST" action="<?php echo base_url().'admin/product/batchaction/'?>">
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="1" id="ListBox" class="alt_row">
  <tr class="ListHeadingLable" bgcolor="#DFDFDF" height="25px;" style="font-size:11px !important">
    <td width="3%">Sl No
	<!--<a href="#" id="CheckAll" style="text-decoration: underline;">CheckAll</a>
	<a href="#" id="UnCheckAll" style="display: none;text-decoration: underline;">UnCheckAll</a> -->
	</td>
    <td width="7%">Order No </td>
    <td width="8%">Order Date</td>
    <td width="8%">Order Action Date</td>
	<td width="7%">Order Amount</td>
	<td width="15%">User Full Name</td>
	<td width="10%">User  Email</td>
	<td width="6%">Transction Type</td>
	<td width="8%">Transaction ID</td>
	<td width="8%">Order Status</td>
	<td width="8%">Order Device</td>
    <td width="10%">Action</td>
  </tr>
  <?php $val=0; 
  if(count($DataArr)>0){
  foreach($DataArr as $InerArr){?>
  <tr class="ListTestLable <?php if($val%2 == 0){ echo 'oddtbl'; } else { echo 'eventbl'; } ?>" height="20px;" style="font-size:11px !important">
    <td><?php echo $val+1;?><!--<input  type="checkbox" name="OrderID[]" value="<?php //echo $InerArr->OrderID;?>"/> --></td>
    <td><?php echo $InerArr->OrderID;?></td>
    <td><?php echo date('d-m-Y',strtotime($InerArr->OrderDate));?></td>
    <td><?php if($InerArr->ActionDate!=""){ echo date('d-m-Y',strtotime($InerArr->ActionDate));}?></td>
    <td><?php echo number_format($InerArr->OrderAmount,2);?></td>
	
	<td><?php echo $InerArr->FirstName.' '.$InerArr->LastName;?></td>
	<td><?php echo $InerArr->Email;?></td>
	<td><?php echo ($InerArr->PaymentType=='1')?'Paypal':'ccAvenue';?></td>
	<td><?php echo ($InerArr->PaymentType=='1')? $InerArr->PayPalTransactionID:$InerArr->TransactionID;?></td>
	<td><?php echo $InerArr->OrderStateName;?></td>
	<td><?php echo $OrderTypeArr[$InerArr->Type];?></td>
    <td>
	<?php /*<div style="float: left; width: 80px;padding-left: 10px; text-decoration: underline;" alt="<?php echo $InerArr->OrderID;?>" class="ViewInvoice"><a href="javascript:void(0);">View Invoice</a></div> */?>
	<?php if($InerArr->OrderStateID<5){?>
	<div style="float: left; width: 120px;padding-left: 10px; text-decoration: underline;" class="OrderStateChangetLink" alt="<?php echo $InerArr->OrderID.'^'.$InerArr->OrderStateID;?>"><a href="javascript:void(0);">Change Order Status</a></div>
	<?php }?>
	</td> 
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
</table>
	<input  type="hidden" name="batchaction_fun" id="batchaction_fun" value=""/>
	<input  type="hidden" name="batchaction_id" id="batchaction_id" value=""/>
	</form></td>
  </tr>
 
  <tr>
    <td></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
		<form name="AdminOrderStateChaneForm" id="AdminOrderStateChaneForm" method="post" action="<?=base_url()?>admin/order/update_state" enctype="multipart/form-data">
		<div id="OrderStateChangeDiv" style="display: none;">
			<table width="700px" border="0">
				<tr>
					<td colspan="3" height="40px"></td>
				</tr>
				<tr>
					<td colspan="3" height="20px"><div id="OrderItemDetails"></div></td>
				</tr>
				<tr>
					<td colspan="3" height="20px"></td>
				</tr>
				<tr>
					<th colspan="3" style="padding-left: 150px; font-size: 15px;"> Order Status Change </th>
				</tr>
				<tr>
					<td colspan="3" height="20px"></td>
				</tr>
				
				<tr>
					<td>Select State</td>
					<td>:</td>
					<td><select name="OrderStateID" id="OrderStateID" class="required">
						<option>* Select *</option>
						<?php foreach($OrderStateDataArr AS $k){?>
						<option value="<?php echo $k->OrderStateID;?>"><?php echo $k->Name;?></option>
						<?php }?>
					</select></td>
				</tr>
				<tr>
					<td colspan="3" height="20px">&nbsp;</td>
				</tr>
				<tr>
					<td>Short Message</td>
					<td>:</td>
					<td><textarea name="ShortMessge" id="ShortMessage" cols="50" rows="5"></textarea></td>
				</tr>
				<tr id="UrlTrackSpaceTR" style="display: none;">
					<td colspan="3" height="20px">&nbsp;</td>
				</tr>
				<tr id="UrlTrackTR" style="display: none;">
					<td>Enter the URL with TRacting No</td>	
					<td>:</td>
					<td><input  type="text" name="TrackingURL" id="TrackingURL" value="" class="required"/><br>
					Example :: www.fedex.com?tracking_id=1232321rdw221
					</td>
				</tr>
				<tr>
					<td colspan="3" height="20px"><input type="hidden" name="OrderID" id="OrderID"/></td>
				</tr>
				<tr>
					<td colspan="3" height="30px" style="padding-left: 200px;"><input type="submit" name="OrderStateChangeButton" id="OrderStateChangeButton" value="Submit" class="common_button"/> &nbsp; <input  type="button" name="OrderStateChangeCancelButton" id="OrderStateChangeCancelButton" value="Cancel" class="common_button"/></td>
				</tr>
			</table>
		</div>
		</form>
	</td>
  </tr>
  <tr>
    <td>
        <form name="order_filter" id="order_filter" action="<?php echo base_url();?>admin/order/filter" method="post">
            <input type="hidden" name="HiddenFilterUserName" id="HiddenFilterUserName" value="" />
            <input type="hidden" name="HiddenFilterFromDate" id="HiddenFilterFromDate" value="" />
            <input type="hidden" name="HiddenFilterToDate" id="HiddenFilterToDate" value="" />
            <input type="hidden" name="HiddenFilterOrderStatus" id="HiddenFilterOrderStatus" value="" />
        </form>
    </td>
  </tr>
  <tr>
  	<td>
		<div id="ViewInvoiceDiv" style="width: 80%; display: none;">
			<div id="ViewInvoiceDataDiv">
			</div>
			<div style="text-align: center">
			<input  type="button" name="ViewInvoiceDivCloseButton" id="ViewInvoiceDivCloseButton" value="Close" class="common_button"/>
			</div>
		</div>
	</td>
  </tr>
</table></td>
  </tr>

</table>
<?php echo $AdminHomeRest;?>
<script>
$(document).ready(function(){
	$("#AdminOrderStateChaneForm").validate();	
	
        $("#FilterOrderFromDate").datepicker({
            dateFormat:"yy-mm-dd",
            //changeMonth: true,
            //changeYear: true,
            yearRange: '1900:' + new Date().getFullYear(),
            onSelect: function(dateText) {
                $('#HiddenFilterFromDate').val(dateText);
            }
        });
        
        $("#FilterOrderToDate").datepicker({
            dateFormat:"yy-mm-dd",
            //changeMonth: true,
            //changeYear: true,
            yearRange: '1900:' + new Date().getFullYear(),
            onSelect: function(dateText) {
                $('#HiddenFilterToDate').val(dateText);
            }
        });
        
        $('#FilterOrder').on('click',function(){
           $('#HiddenFilterOrderStatus').val($('#FilterOrderStatus').val());
           $('#HiddenFilterUserName').val($('#FilterUserName').val());
           //alert('Order filter is under construction');return false;
           $('#order_filter').submit();
        });
	
	$('#CheckAll').on('click',function(){
		$('input[name="OrderID[]"]').each(function(){
                jQuery(this).prop( "checked", true );
        });	
		$('#UnCheckAll').show();
		$(this).hide();
	});
	
	$('#UnCheckAll').on('click',function(){
		$('input[name="OrderID[]"]:checked').each(function(){
                jQuery(this).prop( "checked", false );
        });	
		$('#CheckAll').show();
		$(this).hide();
	});
	
	$('#BatchActive').on('click',function(){
		var itemChecked=false;
		var productIds= new Array();
		$('input[name="ProductID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				productIds.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch active');
			return false;
		}else{
			$('#batchaction_fun').val('batchactive');
			$('#batchaction_id').val(productIds);
			$('#order_list_form').submit();
		}
	});
	
	$('#BatchInActive').on('click',function(){
		var itemChecked=false;
		var productIds= new Array();
		$('input[name="ProductID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				productIds.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch active');
			return false;
		}else{
			$('#batchaction_fun').val('batchinactive');
			$('#batchaction_id').val(productIds);
			$('#order_list_form').submit();
		}
	});
	
	$('#BatchDelete').on('click',function(){
		var itemChecked=false;
		var productIds= new Array();
		$('input[name="ProductID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				productIds.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch active');
			return false;
		}else{
			$('#batchaction_fun').val('batchdelete');
			$('#batchaction_id').val(productIds);
			$('#order_list_form').submit();
		}
	});
	
	
	
	$('#OrderStateChangeCancelButton').on('click',function(){
		$('#MessaeBox').html("");
		$('#PageHeading').show();
		$('#BatchActionRow').show();
		$('#ListBox').fadeIn(3500);
		//$('#AddBox').fadeIn(3500);
		$('#OrderStateChangeDiv').fadeOut(500);
	});
	
	$('#OrderStateID').on('change',function(){
		if($(this).val()==3){
			$('#UrlTrackSpaceTR').show();
			$('#UrlTrackTR').show();
		}else{
			$('#UrlTrackSpaceTR').hide();
			$('#UrlTrackTR').hide();
		}
	});
	
	$('.OrderStateChangetLink').on('click',function(){
		var OrderIDAndStateArr=$(this).attr('alt').split('^');
		$('#OrderID').val(OrderIDAndStateArr[0]);
		$('#OrderStateID').val(OrderIDAndStateArr[1]);
		
		$('#MessaeBox').html("");
		$('#PageHeading').hide();
		$('#BatchActionRow').hide();
		$('#ListBox').fadeOut(1500);
		//$('#AddBox').fadeIn(3500);
		$('#OrderStateChangeDiv').fadeIn(1500);
		var ajaxLoader='<img src="<?php echo $SiteImagesURL.'admin/ajax_img.gif';?>" alt=""/>';
		$('#OrderItemDetails').html(ajaxLoader);
		var OrderItemDetailsAjaxURL='<?php echo base_url()."admin/ajax/order_items"?>';
		var OrderItemDetailsAjaxData='OrderID='+OrderIDAndStateArr[0];
		$.ajax({
			type:"POST",
			url:OrderItemDetailsAjaxURL,
			data:OrderItemDetailsAjaxData,
			success:function(msg){
				$('#OrderItemDetails').html(msg);
			}
		});
	});
	
	$('.ViewInvoice').on('click',function(){
		var img='<img src="<?php echo $SiteImagesURL.'admin/ajax_img.gif';?>" alt=""/>';
		$('#ViewInvoiceDataDiv').html(img);
		
		var ViewImvoiceURL='<?php echo base_url().'admin/ajax/show_invoice';?>';
		var ViewImvoiceData='OrderID='+$(this).attr('alt');
		$.ajax({
			type:"POST",
			url:ViewImvoiceURL,
			data:ViewImvoiceData,
			success:function(msg){
				$('#ViewInvoiceDataDiv').html(msg);
				
				$('#MessaeBox').html("");
				$('#PageHeading').hide();
				$('#BatchActionRow').hide();
				$('#ListBox').fadeOut(1500);
				//$('#AddBox').fadeIn(3500);
				$('#ViewInvoiceDiv').fadeIn(1500);
			}
		});
		
	});
	
	$('#ViewInvoiceDivCloseButton').on('click',function(){
		$('#MessaeBox').html("");
		$('#PageHeading').show();
		$('#BatchActionRow').show();
		$('#ListBox').fadeIn(1500);
		//$('#AddBox').fadeIn(3500);
		$('#ViewInvoiceDataDiv').html('');
		$('#ViewInvoiceDiv').fadeOut(1500);
	});
        $('#GoUncompletedOrders').on('click',function(){
           location.href='<?php echo base_url().'admin/order/viewUncompleteList';?>'; 
        });
        
        $('#ShowAllOrder').on('click',function(){
           location.href='<?php echo base_url().'admin/order/viewlist';?>' 
        });
        
});
</script>