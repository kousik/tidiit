<?php $orderinfo = unserialize(base64_decode($order->orderInfo));
$logisticId='';$trackingURL='';$awbNo='';$note='';
if(!empty($latestOrderState)){
    $logisticId=$latestOrderState['logisticsId'];
    $trackingURL=$latestOrderState['trackingURL'];
    $awbNo=$latestOrderState['awbNo'];
    $note=$latestOrderState['note'];
}?>
<!-- Modal --><style>label.error{color: red;padding-left: 5px;}</style>
<div class="modal fade" id="myModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Order Status Change for :<?php echo $orderId;?></h4>
      </div>
        
            <div class="modal-body">
                <div class="gen_infmtn">
                        <div class="table-responsive">
                            <div class="panel panel-default">
                            <table class="table table-striped" id='js-print-container'>
                                <thead>
                                    <tr class="active">
                                        <th><a href="javascript://">Order #TIDIIT-OD-<?=$order->orderId?></a> <?php if($order->orderType == 'SINGLE' && $order->status < 4):?><a class="btn btn-danger btn-xs pull-right" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-times"></i> Cancel Order</a><?php endif;?><?php if($order->status == 5):?><a class="btn btn-info btn-xs pull-right no-print" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-file-text-o"></i> View Invoice</a><?php endif;?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <p><i class="fa fa-sort-desc"></i> Order Details</p>
                                     <table class="table">
                                        <thead>
                                        <tr class="info">
                                            <th>Order ID</th>
                                            <th>Member</th>
                                            <th>Order Total</th>
                                            <th>Payment</th>
                                            <th>Paid?</th>
                                            <th>Order Status</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tr>
                                            <td>Order #TIDIIT-OD-<?=$order->orderId?></td>
                                            <td><?=$order->orderType=='GROUP'?'Buyer Club':'Self'?></td>
                                            <td><i class="fa fa-rupee"></i><?=$order->subTotalAmount?></td>
                                            <td><?php echo ($order->paymentType=='settlementOnDelivery')?'Settlement On Delivery':'Paid';?></td>
                                            <td><?php echo ($order->paymentType=='settlementOnDelivery')?'<span style="color: #009900;">No</span>':'<span style="color: #009900;">Yes</span>';?></td>
                                            <td><span class="label label-info"><?=$status[$order->status];?></span> (<?=$order->orderDate;?>)</td>
                                        </tr>
                                    </table>

                                     <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr class="success">
                                            <?php /*<th>Billing Name</th>
                                            <th style="width: 35%;"><?=isset($orderinfo['shipping']->firstName)?$orderinfo['shipping']->firstName:''?> <?=isset($orderinfo['shipping']->lastName)?$orderinfo['shipping']->lastName:''?></th>*/?>
                                            <th>Shipping Name</th>
                                            <th style="width: 70%;"><?=isset($orderinfo['shipping']->firstName)?$orderinfo['shipping']->firstName:''?> <?=isset($orderinfo['shipping']->lastName)?$orderinfo['shipping']->lastName:''?></th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tr>
                                            <?php /*<td>Billing Address</td>
                                            <td><?=isset($orderinfo['shipping']->address)?$orderinfo['shipping']->address:''?><br>
                                                <?=isset($orderinfo['shipping']->locality)?$orderinfo['shipping']->locality:''?><br>
                                            City: <?=isset($orderinfo['shipping']->city)?$orderinfo['shipping']->city:''?><br>
                                            State :<?=isset($orderinfo['shipping']->stateName)?$orderinfo['shipping']->stateName:''?><br>
                                            PIN :<?=isset($orderinfo['shipping']->zip)?$orderinfo['shipping']->zip:''?><br>
                                            Country :<?=isset($orderinfo['shipping']->countryName)?$orderinfo['shipping']->countryName:''?> 
                                            </td>*/?>
                                            <td>Shipping Address</td>
                                            <td><?=isset($orderinfo['shipping']->address)?$orderinfo['shipping']->address:''?><br>
                                                <?=isset($orderinfo['shipping']->locality)?$orderinfo['shipping']->locality:''?><br>
                                            City: <?=isset($orderinfo['shipping']->city)?$orderinfo['shipping']->city:''?><br>
                                            State :<?=isset($orderinfo['shipping']->stateName)?$orderinfo['shipping']->stateName:''?><br>
                                            PIN :<?=isset($orderinfo['shipping']->zip)?$orderinfo['shipping']->zip:''?><br>
                                            Country :<?=isset($orderinfo['shipping']->countryName)?$orderinfo['shipping']->countryName:''?><br>
                                            <b>Landmark</b> :<?=isset($orderinfo['shipping']->landmark)?$orderinfo['shipping']->landmark:''?></td>
                                        </tr>
                                        <tr>
                                            <?php /*<td>Billing Phone</td>
                                            <td><?=isset($orderinfo['billing']->contactNo)?$orderinfo['billing']->contactNo:''?></td>*/?>
                                            <td>Shipping Phone</td>
                                            <td><?=isset($orderinfo['shipping']->contactNo)?$orderinfo['shipping']->contactNo:''?></td>
                                        </tr>
                                        <tr>
                                            <?php /*<td>E-Mail</td>
                                            <td><?=isset($orderinfo['billing']->email)?$orderinfo['billing']->email:''?></td> */?>
                                            <td>E-Mail</td>
                                            <td><?=isset($orderinfo['billing']->email)?$orderinfo['billing']->email:''?></td>
                                        </tr>
                                    </table> 

                                        <p><i class="fa fa-sort-desc"></i> Items</p>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr class="danger">
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Model</th>
                                                <th>Brand</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                            <tr>
                                                <td><?=isset($orderinfo['pdetail']->productId)?$orderinfo['pdetail']->productId:''?></td>
                                                <td>
                                                    <?php if(isset($orderinfo['pimage']->image)):?>
                                                    <a href="<?php echo $MainSiteBaseURL.'product/details/'.base64_encode($order->productId);?>" class="" target="_blank"><img src="<?=SiteResourcesURL.'product/100X100/'.$orderinfo['pimage']->image?>" alt="..." class="img-thumbnail img-responsive"/></a>
                                                <?php endif;?>
                                                    <a href="<?php echo $MainSiteBaseURL.'product/details/'.base64_encode($order->productId);?>" class="" target="_blank"><?=isset($orderinfo['pdetail']->title)?$orderinfo['pdetail']->title:''?></a></td>
                                                <td><?=isset($orderinfo['pdetail']->model)?$orderinfo['pdetail']->model:''?></td>
                                                <td><?=isset($orderinfo['pdetail']->brandTitle)?$orderinfo['pdetail']->brandTitle:''?></td>
                                                <td><i class="fa fa-rupee"></i><?=isset($order->orderAmount)?$order->orderAmount/$order->productQty:'0.00'?></td>
                                                <td><?=isset($order->productQty)?$order->productQty:'0'?></td>
                                                <td><i class="fa fa-rupee"></i><?=isset($order->subTotalAmount)?$order->subTotalAmount:''?></td>
                                            </tr>
                                        </table>  
                                        <form name="orderStatesChange" id="orderStatesChange" action="<?php echo BASE_URL.'order/state_change/';?>" method="post">
                                        <table class="table no-print">
                                        <thead>
                                        <tr class="info">
                                            <th>Update  Order Status</th>
                                            <th></th>
                                            <th></th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tr>
                                            <td style="width:30%">Select Order Status</td>
                                            <td style="width:5%">:</td>
                                            <td style="width:65%">
                                                <select name="status" id="status" class="required">
                                                    <option value="">Select</option>
                                                    <option value="3" <?php if($order->status==3){?>selected<?php }?>>Confirm</option>
                                                    <option value="4" <?php if($order->status==4){?>selected<?php }?>>Shipped</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Enter your comment</td>
                                            <td>:</td>
                                            <td><textarea name="note" id="note"><?php echo $note;?></textarea></td>
                                        </tr>
                                        <tr id="showHideShippedElement2" style="display:none;">
                                            <td>Select Logistics Partner</td>
                                            <td>:</td>
                                            <td>
                                                <select name="logisticsId" id="logisticsId" class="logisticsId">
                                                    <option value="">Select</option>
                                                    <?php foreach($logisticsData As $k): ?>
                                                    <option value="<?php echo $k->logisticsId;?>" <?php if($logisticId==$k->logisticsId){?>selected<?php }?>><?php echo $k->title;?></option>
                                                    <?php endforeach;?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr id="showHideShippedElement" style="display:none;">
                                            <td>Enter your Air Way Bill Number</td>
                                            <td>:</td>
                                            <td><input type="text" name="awbNo" id="awbNo" class="required" value="<?php echo $awbNo;?>"/></td>
                                        </tr>
                                        <tr id="showHideShippedElement1" style="display:none;">
                                            <td>Enter your tracking URL</td>
                                            <td>:</td>
                                            <td><input type="text" name="trackingURL" id="trackingURL" class="required" value="<?php echo $trackingURL;?>"/></td>
                                        </tr>
                                        <tr><td colspan="3">&nbsp; <input type="hidden" name="orderId" value="<?php echo $orderId;?>"></td></tr>
                                        <tr><td>&nbsp;</td><td>&nbsp;</td><td><button class="btn btn-warning" type="submit"><i class="fa fa-arrow-left"></i> Submit</button></td></tr>

                                    </table>
                                        </form>        
                                    </td>
                                </tr>
                                </tbody>
                            </table>  
                            </div>
                        </div> 
                        <?php /*<a href="<?=BASE_URL?>my-orders"><button class="btn btn-warning"><i class="fa fa-arrow-left"></i> Back</button> </a>
                        <?php if($order->status == 5):?><a class="btn btn-info btn-xs pull-right" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-file-text-o"></i> View Invoice</a><?php endif; */?>
                    </div>
            </div>
        <div class="modal-footer">&nbsp;</div>
    
    </div>
  </div>
</div>
<!-- /.modal -->
<div id="dialog-confirm" title="Alert" style="display: none;">
    <p><span id="dialog-confirm-message-text"></span></p>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#myModalLogin').modal('show');
        jQuery("#orderStatesChange").validate();
        jQuery('#status').on('change',function(){
            if($(this).val()==4){
                $('#showHideShippedElement').show();
                $('#showHideShippedElement1').show();
                $('#showHideShippedElement2').show();
            }else{
                $('#showHideShippedElement').hide();
                $('#showHideShippedElement1').hide();
                $('#showHideShippedElement2').hide();
            }
        });
        <?php if($order->status==4){?>
                $('#showHideShippedElement').show();
                $('#showHideShippedElement1').show();
                $('#showHideShippedElement2').show();
        <?php }?>
        $('#orderStatesChange').submit(function(e) {
            if ($(this).valid()) {
                if ($(this).valid()) {
                    if(confirm("Are you sure to update selected order status ?")){
                        myJsMain.commonFunction.showPleaseWait();
                    }else{
                        return false;
                    }
                }
            }
        });   
    });
</script>