<?php echo $AdminHomeLeftPanel;
$OrderTypeArr=array('1'=>'Website','2'=>'Mobile Web','3'=>"Mobile Apps");
//print_r($UserDataArr);die;?>
<!--<script src="//code.jquery.com/jquery-1.10.2.js"></script>-->
<script src="<?php echo SiteJSURL ?>jq_datepicker/js/jquery.ui.core.js"></script>
<script src="<?php echo SiteJSURL ?>jq_datepicker/js/jquery.ui.datepicker.js"></script>
<script src="<?php echo SiteJSURL ?>jquery.dataTables.js"></script>
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
                    </td>
                    <td width="75%">
                        <b>Order From Date</b> : <input type="text" name="FilterOrderFromDate" id="FilterOrderFromDate" style="min-width:120px;"/>
                        <b>Order to Date</b> <input type="text" name="FilterOrderToDate" id="FilterOrderToDate" style="min-width:120px;"/>
                        <div style="height: 5px;"></div>
                        <b>Order Status</b> : <select name="FilterOrderStatus" id="FilterOrderStatus" style="min-width:100px;">
                            <option value="">*select*</option>
                            <?php foreach($OrderStateDataArr AS $k){ if($k->orderStateId<3){continue;}?>
                            <option value="<?php echo $k->orderStateId;?>"><?php echo $k->name;?></option>
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
          <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="width:150px;">Action</th>
                        <th>Order Id</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Order Amount</th>
                        <th>Order Type</th>
                        <th>Seller Email</th>
                        <th>Buyer Email</th>
                        <th>Payment Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $val=0; 
                    if(count($DataArr)>0):
                        foreach($DataArr as $InerArr):?>
                    <tr>
                        <td>
                            <a href="javascript:void(0);" class="viewOrderDetails"  title="Cancel" data-orderid="<?php echo $InerArr->orderId;?>">View Details</a>
                              <?php if($InerArr->orderType == 'SINGLE' && $InerArr->status < 4):?> &nbsp; 
                              <a href="javascript:void(0);" class="changeOrderStateCancel"  title="Cancel" data-orderid="<?php echo $InerArr->orderId;?>">Cancel</a>
                              <?php endif;?>
                            <?php if($InerArr->orderDeliveredRequestId!=""):?>  
                              <a href="javascript:void(0);" class="changeOrderStateDelivered"  title="Delivered" data-orderid="<?php echo $InerArr->orderId;?>">Set Delivered</a>
                            <?php endif;?>  
                        </td>
                        <td><?php echo $InerArr->orderId;?></td>
                        <td><?php echo date('d-m-Y',strtotime($InerArr->orderDate));?></td>
                        <td><?php echo $InerArr->orderStatusType;?></td>
                        <td><?php echo number_format($InerArr->orderAmount,2);?></td>
                        <td><?php if($InerArr->orderType=='GROUP'){?><button class="btn btn-success showGroupDetails" data-groupid="<?php echo $InerArr->groupId;?>"><i class="fa fa-arrow-left"></i>Buying Club</button><?php }else{echo 'Single';}?></td>
                        <td><?php echo $InerArr->SellerEmail;?></td>
                        <td><?php echo $InerArr->email;?></td>
                        <td><?php if($InerArr->paymentType=='settlementOnDelivery'){ echo 'Settlement On Delivery';}else if($InerArr->paymentType=='mPesa'){echo 'mPesa';}?></td>
                    </tr>
                    <?php endforeach;
                    else: ?>
                    <tr><td colspan="9">No Record Found</td></tr>
                    <?php endif;?>
                </tbody>
        </table>
      </td>
  </tr>

</table>
<div id="modal_order_details"></div>
<?php echo $AdminHomeRest;?>
<script type="text/javascript">
$('body').on("click",'.viewOrderDetails',function(){ alert('rr');
    var orderid = $(this).data('orderid'); 
    $.post( myJsMain.baseURL+'ajax/show_order_details/', {
         orderId: orderid
     },
     function(data){
         $('#modal_order_details').html(data.content);
     }, 'json' );
 });
 $('body').on("click",'.changeOrderStateDelivered',function(){
    var orderid = $(this).data('orderid'); 
    $.post( myJsMain.baseURL+'ajax/update_order_delivered/', {
         orderId: orderid
     },
     function(data){
         
     }, 'json' );
 });
$(document).ready(function(){
        $("#example").dataTable({
            //"aLengthMenu": [2,5,10,15,20,25,50],"bSort": true
            "aLengthMenu": [10,15,20,25,50],"bSort": true
        });
        

         //$('body .showGroupDetails').on("click",function(){
        jQuery("body").delegate('.showGroupDetails', "click", function(e){
            var groupId = $(this).data('groupid'); 
            $.post( myJsMain.baseURL+'ajax/show_order_group_details/', {
                 groupId: groupId
             },
             function(data){
                 $('#modal_order_details').html(data.content);
             }, 'json' );
         });
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
        
        $('#ShowAllOrder').on('click',function(){
           location.href='<?php echo base_url().'admin/order/viewlist';?>' 
        });
        
});
</script>