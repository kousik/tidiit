<?php echo $html_heading;?>
<body class="  ">
    <div class="bg-dark dk" id="wrap">
        <?php echo $top.$left;?>
        <div id="content">
        <div class="outer">
          <div class="inner bg-light lter">
              <div style="color: red; text-align: center; margin: 0 auto;padding-top: 10px;font-weight: bold;"><?php echo $this->session->flashdata('Message');?></div>
            <!--Begin Datatables
            <h4 class="reg_header">&nbsp;</h4>-->
            
            <br style="clear:both;" />
            <div class="form-group field required">
                <h2>Order List</h2>
            <!--<div class="row dataTableBatchAction">
                 <div class="col-md-1">
                 	<label>Action</label>
                 </div>
                    
                <div class="col-md-4">
                    <select name="actionDrop" class="form-control input" id="actionDrop" >
                        <option value="0" >Please Select</option>
                        <option value="1">Bulk Delete</option>
                        <option value="2">Bulk Active</option>
                        <option value="3">Bulk Inactive</option>
                      </select>
                <!--<input type="hidden" autocomplete="off" /> 
                      
                 </div>
            </div>--->
                 </div>
               <br style="clear:both;" />
               
               
                  <div  id="div-2" class="body table-responsive">                   
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                      <thead>
                        <tr>
                          <th width="15"></th>
                          <th width="150">Action</th>
                          <th>Status</th>
                          <th>Order ID</th>
                          <th>Order Data</th>
                          <th>Order Owner Username</th>
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
                              <a href="javascript:void(0);" class="viewOrderDetails"  title="Cancel" data-productid="<?php echo $k->orderId;?>">View Details</a> <br />
                              <a href="javascript:void(0);" class="cancelOrder"  title="Cancel" data-productid="<?php echo $k->orderId;?>">Cancel</a> <br />
                              <a href="javascript:void(0);" class="changeOrderStatus"  title="Delete" data-productid="<?php echo $k->orderId;?>" data-productstatus="<?php echo $k->status;?>">Update Status</a>
                          </td>
                          <td><?php foreach($OrderStateDataArr AS $kk){ if($kk->orderStateId==$k->status){echo $kk->name ;break;}}?></td>
                          <td><?php echo $k->orderId;?></td>
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
</body>
</html>
<script src="<?php echo SiteJSURL;?>jquery.dataTables.min.js"></script>
<script src="<?php echo SiteJSURL;?>dataTables.bootstrap.js"></script>
<script src="<?php echo SiteJSURL;?>jquery.tablesorter.min.js"></script>
<script src="<?php echo SiteJSURL;?>jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo SiteJSURL;?>metis-file-map-sortable-datatable-pricing-progress.js"></script>
<script type="text/javascript">
 $(function() {
    $("#actionDrop").sortable(); $("#actionDrop").disableSelection();
    $('.deleteProduct').on('click',function(){
        var productid = $(this).data('productid');
        myJsMain.commonFunction.tidiitConfirm("Tidiit Product Delete Confirmation",'Are you sure to delete this product ?',myJsMain.baseURL+'product/delete/'+productid,0);
    });
    
    $('.changeProductStatus').on('click',function(){
        var productid = $(this).data('productid');
        var productstatus = $(this).data('productstatus');
       location.href= myJsMain.baseURL+'product/change_status/'+productid+'/'+productstatus;
    });
}); 
    $(function() {
      Metis.MetisTable();
      Metis.metisSortable();
      
        /*$('.dataTableBatchAction').find('.ui-sortable').each(function(){
             $(this).removeClass('ui-sortable');
             $(this).children().removeAttr('class');
             /*if($(this).children().is("select")){
                 $($(this).children()+' option').each(function(){
                     $(this).removeClass();
                 });
             }
        });*/
    });
	
</script>
