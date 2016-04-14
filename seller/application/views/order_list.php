<?php echo $html_heading;?>
<body class="  ">
    <div class="bg-dark dk" id="wrap">
        <?php echo $top.$left;?>
        <div id="content">
        <div class="outer">
          <div class="inner bg-light lter">
            <div style="color: red; text-align: center; margin: 0 auto;padding-top: 10px;font-weight: bold;">
                <?php if($this->session->flashdata('Message')):?>
                <div class="alert alert-success text-left" role="alert">
                      <i class="fa fa-check"></i> <span class="text-center"><?php echo $this->session->flashdata('Message');?><span>
                </div>
                <?php endif;?>
                <div class="alert alert-danger text-left" role="alert">
                    <i class="fa fa-star"></i> Befor change the order status, Please update the order warehose where products will be dispatch & update the products Serial Number or <br>IMEI Number or Manufacture Number. It is mandatory, otherwaise we will be not liable to assurance or guarantee of your products.
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
                             </div>
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
                          <th>Order Quantity</th>
                          <th>Order Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($DataArr)>0){
                            foreach($DataArr AS $k){
                                $prod = unserialize(base64_decode($k->orderInfo));
                                $title = $prod['pdetail']->title?>
                        <tr>
                          <td><input type="checkbox" name="sel_pro"></td>
                          <td>
                              <a href="javascript:void(0);" class="viewOrderDetails"  title="Cancel" data-orderid="<?php echo $k->orderId;?>">View Details</a>

                              <?php
                              $style = 'style="display:none;"';
                              $styleo = '';
                              if( $k->productsSerial && $k->setWarehouse):
                                    $style = '';
                                    $styleo = 'style="display:none;"';
                              endif;?>
                               <span class="order-options-<?=$k->orderId?>" <?=$style?>>
                               <?php if($k->status!=0):?>|
                                   <a href="<?=site_url('order/order_invoice/'.$k->orderId)?>" target="_blank">Invoice</a> |
                              <a href="<?=site_url('order/packing_slip/'.$k->orderId)?>" target="_blank">Pckg. Slip</a> |<?php endif;?>
                              <?php if($k->orderType == 'SINGLE' && $k->status < 4 && $k->status!=0):?>
                              <a href="javascript:void(0);" class="changeOrderStateCancel"  title="Cancel" data-orderid="<?php echo $k->orderId;?>">Cancel</a> |
                              <?php endif;
                              if($k->status>0 && $k->status<4):?>
                              <a href="javascript:void(0);" class="changeOrderState"  title="Delete" data-orderid="<?php echo $k->orderId;?>" data-productstatus="<?php echo $k->status;?>">Update Status</a>
                              <?php endif;?>
                               </span>

                              <span class="sw-options-<?=$k->orderId?>" <?=$styleo?>>
                                  | <a href="#" data-toggle="modal" data-target="#wpop-<?=$k->orderId?>">Add Products No. & Warehouse</a>
                              </span>

                          </td>
                          <td><?php echo $status[$k->status];?></td>
                          <td>TIDIIT-OD-<?php echo $k->orderId;?></td>
                          <td><?php if($k->orderType=='GROUP'){?><button class="btn btn-success showGroupDetails" data-groupid="<?php echo $k->groupId;?>"><i class="fa fa-arrow-left"></i>Buying Club</button><?php }else{echo 'Single';}?></td>
                          <td><?php if($k->orderType=='GROUP'){if($k->parrentOrderID==0){echo $k->orderId;}else{echo $k->parrentOrderID;}}?></td>
                          <td><?php echo date('d-m-Y',strtotime($k->orderDate));?></td>
                          <td><?php echo $k->productQty; ?></td>
                          <td><?php echo $k->orderAmount; ?></td>
                        </tr>

                        <?php if( !$k->productsSerial && !$k->setWarehouse):?>
                        <div class="modal fade" id="wpop-<?=$k->orderId?>" tabindex="-1" role="dialog" aria-labelledby="wpop-<?=$k->orderId?>Label">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Add Products Serial Number & Ware House Details</h4>
                                    </div>
                                    <form class="js-seller-info form-inline">
                                        <input type="hidden" name="total" value="<?=$k->productQty?>">
                                        <input type="hidden" name="orderId" value="<?=$k->orderId?>">

                                        <div class="modal-body">
                                        <div class="ws-msg-<?=$k->orderId?>" style="display: none;"></div>
                                            <?php for($i = 0; $i < $k->productQty; $i++): ?>
                                            <div class="form-group">
                                                <label for="exampleInputEmail1"><?=$title?> - <?=$i+1?></label>
                                                <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Serial/IMEI/Manufacture Number" name="serial[]">
                                            </div>
                                            <?php endfor;?>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Which Warehouse you want dispatch products?</label>
                                                <select name="setWarehouse" class="form-control">
                                                    <option value="">Select your warehouse</option>
                                                    <?php foreach($warehouses as $wh):?>
                                                        <option value="<?=$wh->id?>"><?=$wh->companyName?> in <?=$wh->city?>, <?=$wh->zip?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>

                                    </div>
                                    </form>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->
                        <?php endif;?>

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

     $(".js-seller-info").submit(function(e) {
         if( confirm("Are you sure you want to submit this entry? After submit, you can not update this entry!") ) {
             $.ajax({
                 type: "POST",
                 url: myJsMain.baseURL + 'ajax/update_order_seller_info/',
                 data: $(this).serialize(), // serializes the form's elements.
                 dataType: "json",
                 success: function (data) {
                     if (data.error) {
                         $('div.ws-msg-' + data.orderid).html(data.error);
                         $('div.ws-msg-' + data.orderid).fadeIn(300, function () {
                             setTimeout('$("div.ws-msg-' + data.orderid + '").fadeOut(300)', 6000);
                         });
                     }


                     if (data.success) {
                         $('span.order-options-' + data.orderid).show();
                         $('span.sw-options-' + data.orderid).hide();
                         $('wpop-' + data.orderid).modal('hide')
                     }
                 }
             });
         }
         e.preventDefault(); // avoid to execute the actual submit of the form.
     });
}); 
    $(function() {
      Metis.MetisTable();
    });
	
</script>
</body>
</html>