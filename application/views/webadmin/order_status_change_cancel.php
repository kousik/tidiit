<?php $orderinfo = unserialize(base64_decode($order->orderInfo));?>
<!-- Modal -->
<div class="modal fade" id="myModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Order Cancel for :<?php echo $orderId;?></h4>
      </div>
        <form action="#" method="post" name="add_groups" class="form-horizontal" id="add_groups"> 
            <div class="modal-body">
                <div class="col-md-12 col-sm-12"> 
                    <div class="gen_infmtn">
                        <div class="table-responsive">
                            <div class="panel panel-default">
                            <table class="table table-striped" id='js-print-container'>
                                <thead>
                                <tr class="active">
                                    <th><a href="javascript://">Order #TIDIIT-OD-<?=$order->orderId?></a> <?php if($order->status == 5):?><a class="btn btn-info btn-xs pull-right no-print" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-file-text-o"></i> View Invoice</a><?php endif;?></th>
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
                                        <form name="orderStatesChange" id="orderStatesChange" action="<?php echo BASE_URL.'order/state_change_cancel/';?>" method="post">
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
                                            <td style="width:35%">Enter the reason to cancel the order</td>
                                            <td style="width:5%">:</td>
                                            <td style="width:60%"><textarea name="note" id="note" class="required"></textarea></td>
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
            </div>
        <div class="modal-footer">&nbsp;</div>
      </form>    
    </div>
  </div>
</div>
<!-- /.modal -->
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#myModalLogin').modal('show');
        jQuery('#status').on('change',function(){
            if($(this).val()==4){
                $('#showHideShippedElement').show();
                $('#showHideShippedElement1').show();
            }else{
                $('#showHideShippedElement').hide();
                $('#showHideShippedElement1').hide();
            }
        });
    });
</script>