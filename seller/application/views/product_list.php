<?php echo $html_heading;?>
<body class="  ">
    <div class="bg-dark dk" id="wrap">
        <?php echo $top.$left;?>
        <div id="content">
        <div class="outer">
          <div class="inner bg-light lter">
              <div style="color: red; text-align: center; margin: 0 auto;padding-top: 10px;font-weight: bold;">
                  <div class="alert alert-success text-left" role="alert"><i class="fa fa-check"></i> <span class="text-center"><?php echo $this->session->flashdata('Message');?><span></div>
              </div>
            <!--Begin Datatables
            <h4 class="reg_header">&nbsp;</h4>-->
            
            <br style="clear:both;" />
            <div class="form-group field required text-center float-lt">
                <div class="row dataTableBatchAction">
                    <div class="col-md-2">
                        <select name="actionDropMultiple" class="form-control input" id="actionDropMultiple" >
                            <option value="0" >View As Product Status</option>
                            <option value="3">Active</option>
                            <option value="3">Inactive</option>
                          </select>
                    <!--<input type="hidden" autocomplete="off" />  -->

                     </div>
                    <div class="col-md-1">
                            <label>Action</label>
                     </div>

                    <div class="col-md-2">
                        <select name="actionDropMultiple" class="form-control input" id="actionDropMultiple" >
                            <option value="0" >Please Select</option>
                            <option value="1">Bulk Delete</option>
                            <option value="2">Bulk Active</option>
                            <option value="3">Bulk Inactive</option>
                          </select>
                    <!--<input type="hidden" autocomplete="off" />  -->

                     </div>
                </div>
            </div>
            <br style="clear:both;" />
               
               
                  <div  id="div-2" class="body table-responsive">                   
                    <table id="dataTable" class="table table-bordered table-condensed table-hover table-striped">
                      <thead>
                        <tr>
                          <th width="15"></th>
                          <th width="200">Action</th>
                          <th>Status</th>
                          <th>Image</th>
                          <th>Model</th>
                          <th width="90">Product Name</th>
                          <th width="30">In stock</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(count($DataArr)>0){
                            foreach($DataArr AS $k){?>  
                        <tr>
                          <td><input type="checkbox" name="sel_pro"></td>
                          <td><a href="<?php echo BASE_URL.'product/edit_product/'.$k->productId*999999;?>">Edit</a> / 
                              <a href="javascript:void(0);" class="deleteProduct"  title="Delete" data-productid="<?php echo $k->productId;?>">Delete</a> / 
                              <?php if($k->status==1){$action="Inactivate";}else{$action="Activate";}?>
                              <a href="javascript:void(0);" class="changeProductStatus"  title="Delete" data-productid="<?php echo $k->productId;?>" data-productstatus="<?php echo $k->status;?>">Make <?php echo $action;?></a>
                          </td>
                          <td><?php echo ($k->status==0) ?'Inactive' : 'Active';?></td>
                          <td><img src="<?php echo SiteResourcesURL.'product/100X100/'.$k->image;?>" alt="<?php echo $k->title;?>"></td>
                          <td><?php echo $k->model;?></td>
                          <td><?php echo $k->title;?></td>
                          <td><?php 
                          if($k->qty>$k->minQty):
                              echo $k->qty; 
                          else: ?><span><a href="javascript:void(0);" class="updateStock" data-productid="<?php echo $k->productId;?>">Update Stock</a></span>
                          <?php endif;?></td>
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

<!-- Modal -->
<div class="modal fade" id="myModalLogin123" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Updating Stock</h4>
      </div>
      <form action="<?php echo BASE_URL.'product/update_stock/'?>" method="post" name="updateStockForm" class="form-horizontal" id="updateStockForm"> 
          <input type="hidden" name="updateQuantityProductId" id="updateQuantityProductId" value="">
      <div class="modal-body">  
          
              <div class="form-group">
                  <label for="groupTitle" class="col-sm-3 control-label">New Quantity</label>
                  <div class="col-sm-7">
                  <input type="text" class="form-control" id="newQty" name="newQty" placeholder="1" required>
                  </div>
              </div>
            <div class="js-message" style="display:none;"></div>
      </div>
      <div class="modal-footer">
        <input type="submit"  class="grpButton" name="updateStockBtn" id="updateStockBtn" value="submit" />
      </div>
      </form>    
    </div>
  </div>
</div>
<!-- /.modal -->
<script src="<?php echo SiteJSURL;?>jquery.dataTables.min.js"></script>
<script src="<?php echo SiteJSURL;?>dataTables.bootstrap.js"></script>
<script src="<?php echo SiteJSURL;?>jquery.tablesorter.min.js"></script>
<script src="<?php echo SiteJSURL;?>jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo SiteJSURL;?>metis-file-map-sortable-datatable-pricing-progress.js"></script>
<script type="text/javascript">
 $(function() {
    //$("#actionDrop").sortable(); 
    //$("#actionDrop").disableSelection();
    $('.deleteProduct').on('click',function(){
        var productid = $(this).data('productid');
        myJsMain.commonFunction.tidiitConfirm("Tidiit Product Delete Confirmation",'Are you sure to delete this product ?',myJsMain.baseURL+'product/delete/'+productid,0);
    });
    
    $('.changeProductStatus').on('click',function(){
        var productid = $(this).data('productid');
        var productstatus = $(this).data('productstatus');
       location.href= myJsMain.baseURL+'product/change_status/'+productid+'/'+productstatus;
    });
    
    $('.updateStock').on('click',function(){
        var productid = $(this).data('productid');
        $('#updateQuantityProductId').val(productid);
        $('#myModalLogin123').modal('show');
    });
    
    
}); 
    $(function() {
      Metis.MetisTable();
      //Metis.metisSortable();
      //$('#actionDrop').removeClass('ui-sortable-handle');
      //$('.dataTableBatchAction').find('div').removeClass('ui-sortable');
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
</body>
</html>