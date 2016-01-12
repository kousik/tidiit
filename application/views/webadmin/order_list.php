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
                            <?php if($InerArr->orderDeliveredRequestId!="" && $InerArr->status>4):?>  
                              <a href="javascript:void(0);" class="viewOrderDeliveryDetails"  title="Delivered" data-orderid="<?php echo $InerArr->orderId;?>">
                                  <?php if($InerArr->status==5){ echo 'View and Update Delivery Details';}else{ echo 'View Delivery Details';}?></a>
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
jQuery('body').on("click",'.viewOrderDetails',function(){
    var orderid = jQuery(this).data('orderid'); 
    jQuery.post( myJsMain.baseURL+'ajax/show_order_details/', {
         orderId: orderid
     },
     function(data){
         jQuery('#modal_order_details').html(data.content);
     }, 'json' );
 });
 jQuery('body').on("click",'.viewOrderDeliveryDetails',function(){
     myJsMain.commonFunction.showWebAdminPleaseWait();
    var orderid = jQuery(this).data('orderid'); 
    jqout=jQuery(this);
    jQuery.post( myJsMain.baseURL+'ajax/show_order_delivery_details/', {
         orderId: orderid
     },
     function(data){
         myJsMain.commonFunction.hideWebAdminPleaseWait();
         jQuery('#modal_order_details').html(data.content);
         /*if(data.result=='good'){
             jqout.html("");
             myJsMain.commonFunction.tidiitAlert('Tidiit Order Update System','Select order update as delivered successfully.',200);
         }*/
     }, 'json' );
 });
jQuery(document).ready(function(){
        jQuery("#example").dataTable({
            //"aLengthMenu": [2,5,10,15,20,25,50],"bSort": true
            "aLengthMenu": [10,15,20,25,50],"bSort": true
        });
        

         //jQuery('body .showGroupDetails').on("click",function(){
        jQuery("body").delegate('.showGroupDetails', "click", function(e){
            var groupId = $(this).data('groupid'); 
            jQuery.post( myJsMain.baseURL+'ajax/show_order_group_details/', {
                 groupId: groupId
             },
             function(data){
                 jQuery('#modal_order_details').html(data.content);
             }, 'json' );
         });
	/*jQuery("#AdminOrderStateChaneForm").validate();	
	
        jQuery("#FilterOrderFromDate").datepicker({
            dateFormat:"yy-mm-dd",
            //changeMonth: true,
            //changeYear: true,
            yearRange: '1900:' + new Date().getFullYear(),
            onSelect: function(dateText) {
                jQuery('#HiddenFilterFromDate').val(dateText);
            }
        });
        
        jQuery("#FilterOrderToDate").datepicker({
            dateFormat:"yy-mm-dd",
            //changeMonth: true,
            //changeYear: true,
            yearRange: '1900:' + new Date().getFullYear(),
            onSelect: function(dateText) {
                jQuery('#HiddenFilterToDate').val(dateText);
            }
        });
        
        jQuery('#FilterOrder').on('click',function(){
           jQuery('#HiddenFilterOrderStatus').val(jQuery('#FilterOrderStatus').val());
           jQuery('#HiddenFilterUserName').val(jQuery('#FilterUserName').val());
           //alert('Order filter is under construction');return false;
           jQuery('#order_filter').submit();
        });
	
	jQuery('#CheckAll').on('click',function(){
		jQuery('input[name="OrderID[]"]').each(function(){
                jQuery(this).prop( "checked", true );
        });	
		jQuery('#UnCheckAll').show();
		$(this).hide();
	});
	
	jQuery('#UnCheckAll').on('click',function(){
		jQuery('input[name="OrderID[]"]:checked').each(function(){
                jQuery(this).prop( "checked", false );
        });	
		jQuery('#CheckAll').show();
		$(this).hide();
	});
	
	jQuery('#BatchActive').on('click',function(){
		var itemChecked=false;
		var productIds= new Array();
		jQuery('input[name="ProductID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				productIds.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch active');
			return false;
		}else{
			jQuery('#batchaction_fun').val('batchactive');
			jQuery('#batchaction_id').val(productIds);
			jQuery('#order_list_form').submit();
		}
	});
	
	jQuery('#BatchInActive').on('click',function(){
		var itemChecked=false;
		var productIds= new Array();
		jQuery('input[name="ProductID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				productIds.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch active');
			return false;
		}else{
			jQuery('#batchaction_fun').val('batchinactive');
			jQuery('#batchaction_id').val(productIds);
			jQuery('#order_list_form').submit();
		}
	});
	
	jQuery('#BatchDelete').on('click',function(){
		var itemChecked=false;
		var productIds= new Array();
		jQuery('input[name="ProductID[]"]').each(function(){
			if ($(this).prop( "checked" )) {
				productIds.push($(this).val());
				itemChecked=true;
			}
		});
		if(itemChecked==false){
			alert('Please select item for batch active');
			return false;
		}else{
			jQuery('#batchaction_fun').val('batchdelete');
			jQuery('#batchaction_id').val(productIds);
			jQuery('#order_list_form').submit();
		}
	});
	
	
	
	jQuery('#OrderStateChangeCancelButton').on('click',function(){
		jQuery('#MessaeBox').html("");
		jQuery('#PageHeading').show();
		jQuery('#BatchActionRow').show();
		jQuery('#ListBox').fadeIn(3500);
		//jQuery('#AddBox').fadeIn(3500);
		jQuery('#OrderStateChangeDiv').fadeOut(500);
	});
	
	jQuery('#OrderStateID').on('change',function(){
		if($(this).val()==3){
			jQuery('#UrlTrackSpaceTR').show();
			jQuery('#UrlTrackTR').show();
		}else{
			jQuery('#UrlTrackSpaceTR').hide();
			jQuery('#UrlTrackTR').hide();
		}
	});
	
	jQuery('.OrderStateChangetLink').on('click',function(){
		var OrderIDAndStateArr=$(this).attr('alt').split('^');
		jQuery('#OrderID').val(OrderIDAndStateArr[0]);
		jQuery('#OrderStateID').val(OrderIDAndStateArr[1]);
		
		jQuery('#MessaeBox').html("");
		jQuery('#PageHeading').hide();
		jQuery('#BatchActionRow').hide();
		jQuery('#ListBox').fadeOut(1500);
		//jQuery('#AddBox').fadeIn(3500);
		jQuery('#OrderStateChangeDiv').fadeIn(1500);
		var ajaxLoader='<img src="<?php //echo $SiteImagesURL.'admin/ajax_img.gif';?>" alt=""/>';
		jQuery('#OrderItemDetails').html(ajaxLoader);
		var OrderItemDetailsAjaxURL='<?php ///echo base_url()."admin/ajax/order_items"?>';
		var OrderItemDetailsAjaxData='OrderID='+OrderIDAndStateArr[0];
		$.ajax({
			type:"POST",
			url:OrderItemDetailsAjaxURL,
			data:OrderItemDetailsAjaxData,
			success:function(msg){
				jQuery('#OrderItemDetails').html(msg);
			}
		});
	});
	
	jQuery('.ViewInvoice').on('click',function(){
		var img='<img src="<?php //echo $SiteImagesURL.'admin/ajax_img.gif';?>" alt=""/>';
		jQuery('#ViewInvoiceDataDiv').html(img);
		
		var ViewImvoiceURL='<?php //echo base_url().'admin/ajax/show_invoice';?>';
		var ViewImvoiceData='OrderID='+$(this).attr('alt');
		$.ajax({
			type:"POST",
			url:ViewImvoiceURL,
			data:ViewImvoiceData,
			success:function(msg){
				jQuery('#ViewInvoiceDataDiv').html(msg);
				
				jQuery('#MessaeBox').html("");
				jQuery('#PageHeading').hide();
				jQuery('#BatchActionRow').hide();
				jQuery('#ListBox').fadeOut(1500);
				//jQuery('#AddBox').fadeIn(3500);
				jQuery('#ViewInvoiceDiv').fadeIn(1500);
			}
		});
		
	});
	
	jQuery('#ViewInvoiceDivCloseButton').on('click',function(){
		jQuery('#MessaeBox').html("");
		jQuery('#PageHeading').show();
		jQuery('#BatchActionRow').show();
		jQuery('#ListBox').fadeIn(1500);
		//jQuery('#AddBox').fadeIn(3500);
		jQuery('#ViewInvoiceDataDiv').html('');
		jQuery('#ViewInvoiceDiv').fadeOut(1500);
	});*/
        
        jQuery('#ShowAllOrder').on('click',function(){
           location.href='<?php echo base_url().'webadmin/order/viewlist';?>' 
        });
        
});
</script>