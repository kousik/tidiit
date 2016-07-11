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
    <td class="PageHeading" >Product Manager</td>
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
                        <div style="height: 5px;"></div>
                        <b>Product Status</b> : <select name="FilterProductStatus" id="FilterProductStatus" style="min-width:100px;">
                            <option value="">*select*</option>
                            <option value="0">In Active</option>
                            <option value="1">Active</option>
                        </select>
                       <b>Enter the Product name :</b><input type="text" name="FilterProductName" id="FilterProductName" style="min-width:120px;" />
                       <input type="button" name="FilterProduct" id="FilterProduct" value="Filter" class="btn-primary btn-large"/>
                       <input type="button" name="ShowAllProduct" id="ShowAllProduct" value="Show All" class="btn-primary btn-large"/>
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
                        <th>Product Id</th>
                        <th>Product Category</th>
                        <th>Product Title</th>
                        <th>Product Model</th>
                        <th>Product Image</th>
                        <th>Seller Name</th>
                        <th>Seller Email</th>
                        <th>Seller Location</th>
                        <th>Product Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php $val=0; 
                    if(count($DataArr)>0):
                        foreach($DataArr as $InerArr):?>
                    <tr>
                        <td>
                            <button class="btn btn-success showPriceDetails" data-groupid="<?php echo $InerArr->productId;?>"><i class="fa fa-arrow-left"></i>Show Prices</button>
                            <button class="btn btn-success makeFeatured" data-groupid="<?php echo $InerArr->productId;?>"><i class="fa fa-arrow-left"></i>Set Featured</button>
                        </td>
                        <td><?php echo $InerArr->productId;?></td>
                        <td><?php echo $InerArr->categoryName;?></td>
                        <td><?php echo $InerArr->title;?></td>
                        <td><?php echo $InerArr->model;?></td>
                        <td><?php if($InerArr->image!=""):?>
                            <img src="<?php echo PRODUCT_DEAILS_SMALL.$InerArr->image;?>" alt="" board="0" title="<?php echo $InerArr->title;?>">    
                        <?php else: echo 'Image Issue for product id :"'.$InerArr->productId.'" inform to Tech Team';
                            endif;?></td>
                        <td><?php echo $InerArr->sellerFirstName.' '.$InerArr->sellerLastName;?></td>
                        <td><?php echo $InerArr->sellerEmail;?></td>
                        <td><?php echo $InerArr->city.', '.$InerArr->countryName;?></td>
                        <td><?php echo $InerArr->status;?></td>
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
jQuery(document).ready(function(){
        jQuery("#example").dataTable({
            //"aLengthMenu": [2,5,10,15,20,25,50],"bSort": true
            "aLengthMenu": [10,15,20,25,50],"bSort": true
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
           location.href='<?php echo base_url().'webadmin/product/viewlist';?>' 
        });
        
});
</script>