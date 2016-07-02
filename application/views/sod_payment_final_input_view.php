<style type="text/css">
    @media screen and (min-width: 768px) {
        .modal-dialog {
          width: 700px; /* New width for default modal */
        }
        .modal-sm {
          width: 350px; /* New width for small modal */
        }
    }
    @media screen and (min-width: 992px) {
        .modal-lg {
          width: 750px; /* New width for large modal */
        }
    }
</style><!-- Modal -->
<div class="modal fade" id="myModalOutForDelivery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel" style="text-align: center">Complete Payment</h4>
      </div>
        <form action="<?php echo BASE_URL.'shopping/complete_payment/'?>" method="post" name="outForDeliveryForm" class="form-horizontal" id="outForDeliveryForm"> 
            <div class="modal-body">
                <div class="gen_infmtn">
                        <div class="table-responsive">
                            <div class="panel panel-default">
                            <table class="table table-striped" id='js-print-container'>
                                <tr>
                                    <td>
                                     <table class="table">
                                         <tr>
                                             <td style="width: 35%;">&nbsp;</td>
                                             <td style="width: 5%;">&nbsp;</td>
                                             <td style="width: 60%;">&nbsp;</td>
                                         </tr>
                                         <tr>
                                            <td>Delivery Staff Name</td>
                                            <td>:</td>
                                            <td><input id="deliveryStaffName" name="deliveryStaffName" type="text" class="form-control">
                                                <div>
                                                    <label id="deliveryStaffName-error" class="error" for="deliveryStaffName"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Delivery Staff Contact No</td>
                                            <td>:</td>
                                            <td><input id="deliveryStaffContactNo" name="deliveryStaffContactNo" type="text" class="form-control">
                                                <div>
                                                    <label id="deliveryStaffContactNo-error" class="error" for="deliveryStaffContactNo"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Delivery Staff Email</td>
                                            <td>:</td>
                                            <td><input id="deliveryStaffEmail" name="deliveryStaffEmail" type="email" class="form-control">
                                                <div>
                                                    <label id="deliveryStaffEmail-error" class="error" for="deliveryStaffEmail"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Amount</td>
                                            <td>:</td>
                                            <td><strong><?php echo $payAmount;?></strong></td>
                                        </tr>
                                        <tr>
                                            <td>Select Payment Method</td>
                                            <td>:</td>
                                            <td><?php foreach($paymentGatewayData AS $k):?> 
                                                <label><input type="radio" name="paymentoption" value="<?php echo $k->gatewayCode;?>" required> <?php echo $k->gatewayTitle;?></label>
                                                <?php endforeach;?>
                                            </td>
                                        </tr>
                                        <tr>
                                             <td style="width: 35%;">&nbsp;</td>
                                             <td style="width: 5%;">&nbsp;</td>
                                             <td style="width: 60%;">
                                                 <input id="orderId" name="orderId" type="hidden" value="<?php echo $orderId;?>">
                                                 <input type="submit" name="outForDeliverySubmit" id="outForDeliverySubmit" value="Submit" class="btn btn-default col-md-5"/>	
                                             </td>
                                         </tr>
                                    </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>  
                            </div>
                        </div> 
                        
                    </div>
            </div>
        <div class="modal-footer">&nbsp;</div>
      </form>    
    </div>
  </div>
</div>
<!-- /.modal -->
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#outForDeliveryForm').validate();
    jQuery('#myModalOutForDelivery').modal('show');
    jQuery('#outForDeliveryForm').submit(function(e) { 
        //e.preventDefault();
        if (jQuery(this).valid()) {
            myJsMain.commonFunction.showPleaseWait();
            //myJsMain.commonFunction.tidiitConfirm1('Tidiit out for deliery','Are you sure to submit the data ?',200,jQuery(this),true);
        }
    });
});
</script>    