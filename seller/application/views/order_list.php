<?php echo $html_heading;?>
<body class="  ">
    <div class="bg-dark dk" id="wrap">
        <?php echo $top.$left;?>
        <div id="content">
        <div class="outer">
          <div class="inner bg-light lter">
            <div style="color: red; text-align: center; margin: 0 auto;padding-top: 10px;font-weight: bold;">
                <div class="alert alert-success text-left" role="alert">
                      <i class="fa fa-check"></i> <span class="text-center"><?php echo $this->session->flashdata('Message');?><span>
                </div>
            </div>
            <!--Begin Datatables
            <h4 class="reg_header">&nbsp;</h4>-->
            
            <div class="form-group field required">
                <h2>Order List <?php echo ($OrderListType!="") ? $OrderListType : '' ;?></h2>
                    <br style="clear:both;" />
                    <div class="form-group field required text-center float-lt">
                        <div class="row dataTableBatchAction">
                            <div class="col-md-3">
                                <select name="actionDropMultiple" class="form-control input" id="actionDropMultiple" >
                                    <option value="0" >View As Order Status</option>
                                    <option value="2">Process Orders</option>
                                    <option value="3">Confirm Orders</option>
                                    <option value="4">Shipped Orders</option>
                                    <option value="5">Out For Delivery Orders</option>
                                    <option value="6">Delivered Orders</option>
                                    <option value="7">Canceled Orders</option>
                                  </select>
                            <!--<input type="hidden" autocomplete="off" />  -->

                             </div>
                            <!--<div class="col-md-1">
                                    <label>Action</label>
                             </div>

                            <div class="col-md-2">
                                <select name="actionDropMultiple" class="form-control input" id="actionDropMultiple" >
                                    <option value="0" >Please Select</option>
                                    <option value="1">Bulk Delete</option>
                                    <option value="2">Bulk Active</option>
                                    <option value="3">Bulk Inactive</option>
                                  </select>
                            <!--<input type="hidden" autocomplete="off" />  

                             </div>-->
                        </div>
                    </div>
                 </div>
                  <div  id="div-2" class="body table-responsive">                   
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped order-list">
                      <thead>
                        <tr>
                          <th width="15"></th>
                          <th width="300">Action</th>
                          <th>Status</th>
                          <th>Order ID</th>
                          <th>Order Type</th>
                          <th width="50">Parrent Order Id</th>
                          <th>Order Data</th>
                          <th>Order Owner Email</th>
                          <th>Order Quantity</th>
                          <th>Order Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($DataArr)>0){
                            foreach($DataArr AS $k){?>  
                        <tr>
                          <td><input type="checkbox" name="sel_pro"></td>
                          <td>
                              <a href="javascript:void(0);" class="viewOrderDetails"  title="Cancel" data-orderid="<?php echo $k->orderId;?>">View Details</a> |
                              <a>Invoice</a> |
                              <a href="<?=site_url('order/packing_slip/'.$k->orderId)?>" target="_blank">Pckg. Slip</a> |
                              <?php if($k->orderType == 'SINGLE' && $k->status < 4 && $k->status!=0):?>
                              <a href="javascript:void(0);" class="changeOrderStateCancel"  title="Cancel" data-orderid="<?php echo $k->orderId;?>">Cancel</a> |
                              <?php endif;
                              if($k->status>0 && $k->status<4):?>
                              <a href="javascript:void(0);" class="changeOrderState"  title="Delete" data-orderid="<?php echo $k->orderId;?>" data-productstatus="<?php echo $k->status;?>">Update Status</a>
                              <?php endif;?>
                          </td>
                          <td><?php echo $status[$k->status];?></td>
                          <td><?php echo $k->orderId;?></td>
                          <td><?php if($k->orderType=='GROUP'){?><button class="btn btn-success showGroupDetails" data-groupid="<?php echo $k->groupId;?>"><i class="fa fa-arrow-left"></i>Buying Club</button><?php }else{echo 'Single';}?></td>
                          <td><?php if($k->orderType=='GROUP'){if($k->parrentOrderID==0){echo $k->orderId;}else{echo $k->parrentOrderID;}}?></td>
                          <td><?php echo date('d-m-Y',strtotime($k->orderDate));?></td>
                          <td><?php echo $k->email;?></td>
                          <td><?php echo $k->productQty; ?></td>
                          <td><?php echo $k->orderAmount; ?></td>
                        </tr>
                        <?php }
                        }?> 
                        
                        
                      </tbody>
                    </table>
                  </div>
               <!-- /.row -->

            <!--End Datatables-->
           
          </div><!-- /.inner -->
        </div><!-- /.outer -->
      </div><!-- /#content -->
    </div>
    <?php echo $footer;?>
    <div id="modal_order_details"></div>

<script src="<?php echo SiteJSURL;?>jquery.dataTables.min.js"></script>
<script src="<?php echo SiteJSURL;?>dataTables.bootstrap.js"></script>
<script src="<?php echo SiteJSURL;?>jquery.tablesorter.min.js"></script>
<script src="<?php echo SiteJSURL;?>jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo SiteJSURL;?>metis-file-map-sortable-datatable-pricing-progress.js"></script>
<script type="text/javascript">
 $(function() {
    $("#actionDrop").sortable();
    jQuery('#actionDropMultiple').on('change',function(){
        location.href='<?php echo BASE_URL.'order/viewlist/?HiddenFilterOrderStatus=';?>'+jQuery(this).val();
    });
    $('.deleteProduct').on('click',function(){
        var productid = $(this).data('productid');
        myJsMain.commonFunction.tidiitConfirm("Tidiit Product Delete Confirmation",'Are you sure to delete this product ?',myJsMain.baseURL+'product/delete/'+productid,0);
    });
    
    $('.changeProductStatus').on('click',function(){
        var productid = $(this).data('productid');
        var productstatus = $(this).data('productstatus');
       location.href= myJsMain.baseURL+'product/change_status/'+productid+'/'+productstatus;
    });
    
    $('.viewOrderDetails').on("click",function(){
       var orderid = $(this).data('orderid'); 
       $.post( myJsMain.baseURL+'ajax/show_order_details/', {
            orderId: orderid
        },
        function(data){
            $('#modal_order_details').html(data.content);
        }, 'json' );
    });
    
    $('.showGroupDetails').on("click",function(){
       var groupId = $(this).data('groupid'); 
       $.post( myJsMain.baseURL+'ajax/show_order_group_details/', {
            groupId: groupId
        },
        function(data){
            $('#modal_order_details').html(data.content);
        }, 'json' );
    });
    
    $('.changeOrderState').on("click",function(){
       var orderid = $(this).data('orderid'); 
       $.post( myJsMain.baseURL+'ajax/change_order_state/', {
            orderId: orderid
        },
        function(data){
            $('#modal_order_details').html(data.content);
        }, 'json' );
    });
    
    $('.changeOrderStateCancel').on("click",function(){
       var orderid = $(this).data('orderid'); 
       $.post( myJsMain.baseURL+'ajax/change_order_state_cancel/', {
            orderId: orderid
        },
        function(data){
            $('#modal_order_details').html(data.content);
        }, 'json' );
    });
}); 
    $(function() {
      Metis.MetisTable();
      
    });
	
</script>
</body>
</html>