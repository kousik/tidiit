<?php echo $html_heading; echo $header;
$CI =& get_instance();
$CI->load->model('Product_model');
$CI->load->model('Order_model');
$orderinfo = unserialize(base64_decode($order->orderInfo));
$currencySymbol=($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')=="IN") ? '<i class="fa fa-rupee"></i>' :'KSh';
?>
<script src="<?php echo SiteJSURL;?>user-all-my-js.js" type="text/javascript"></script>
</div>
</header>
<article>
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12 productInner">
        <div class="page_content">
            <div class="row">
                <?php echo $userMenu;?>
                <div class="col-md-9 wht_bg">
                    <!-- Tab panes -->
                    <div class="tab_dashbord">
                    	<div class="active row">
                            <div class="col-md-12 col-sm-12"> 
                                
                                
                                    <div class="gen_infmtn">
                                    <div class="table-responsive">
                                        <div class="panel panel-default">
                                        <table class="table table-striped" id='js-print-container'>
                                            <thead>
                                            <tr class="active">
                                                <th><a href="javascript://">Order #TIDIIT-OD-<?=$order->orderId?></a> <?php if($order->orderType == 'SINGLE' && $order->status < 4):?><a class="btn btn-danger btn-xs pull-right" href="<?=BASE_URL?>order/cancellation/<?=base64_encode($order->orderId*226201);?>" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-times"></i> Cancel Order</a><?php endif;?><?php if($order->status == 5):?><a class="btn btn-info btn-xs pull-right no-print" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-file-text-o"></i> View Invoice</a><?php endif;?></th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
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
                                                        <td><?=$order->orderType=='GROUP'?'Buying Club':'Self'?></td>
                                                        <td><?php echo $currencySymbol;?><?=$order->subTotalAmount?></td>
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
                                                        <td><?=isset($orderinfo['billing']->contactNo)?$orderinfo['billing']->contactNo:''?></td>*/ ?>
                                                        <td>Shipping Phone</td>
                                                        <td><?=isset($orderinfo['shipping']->contactNo)?$orderinfo['shipping']->contactNo:''?></td>
                                                    </tr>
                                                    <tr>
                                                        <?php /*<td>E-Mail</td>
                                                        <td><?=isset($orderinfo['billing']->email)?$orderinfo['billing']->email:''?></td>*/?>
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
                                                                <a href="<?php echo BASE_URL.'product/details/'.base64_encode($order->productId);?>" class="" target="_blank"><img src="<?=PRODUCT_DEAILS_SMALL.$orderinfo['pimage']->image?>" alt="..." class="img-thumbnail img-responsive"/></a>
                                                            <?php endif;?>
                                                                <a href="<?php echo BASE_URL.'product/details/'.base64_encode($order->productId);?>" class="" target="_blank"><?=isset($orderinfo['pdetail']->title)?$orderinfo['pdetail']->title:''?></a><br>
                                                          <a class="btn btn-info btn-xs pull-left no-print" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-thumbs-up"></i> Review Product</a></td>
                                                            <td><?=isset($orderinfo['pdetail']->model)?$orderinfo['pdetail']->model:''?></td>
                                                            <td><?=isset($orderinfo['pdetail']->brandTitle)?$orderinfo['pdetail']->brandTitle:''?></td>
                                                            <td><?php echo $currencySymbol;?><?=isset($order->orderAmount)?$order->orderAmount/$order->productQty:'0.00'?></td>
                                                            <td><?=isset($order->productQty)?$order->productQty:'0'?></td>
                                                            <td><?php echo $currencySymbol;?><?=isset($order->subTotalAmount)?$order->subTotalAmount:''?></td>
                                                        </tr>
                                                        <tr> 
                                                            <td colspan="5"></td>
                                                            <td>Order Sub Total</td>
                                                            <td><?php echo $currencySymbol;?><?=isset($order->subTotalAmount)?$order->subTotalAmount:''?></td>
                                                        </tr>
                                                        <tr> 
                                                            <td colspan="5"></td>
                                                            <td>Order Discount</td>
                                                            <td><?php echo $currencySymbol;?><?=isset($order->discountAmount)?$order->discountAmount:''?></td>
                                                        </tr>
                                                        <tr> 
                                                            <td colspan="5"></td>
                                                            <td>Shipping ()</td>
                                                            <td><?php echo $currencySymbol;?><?=isset($order->shippingamount)?$order->shippingamount:'0.00'?></td>
                                                        </tr>
                                                        <tr> 
                                                            <td colspan="5"></td>
                                                            <td>Tax</td>
                                                            <td><?php echo $currencySymbol;?><?=isset($order->taxAmount)?$order->taxAmount:'0.00'?></td>
                                                        </tr>
                                                        <tr> 
                                                            <td colspan="5"></td>
                                                            <td>Order Total</td>
                                                            <td><?php echo $currencySymbol;?><?=isset($order->orderAmount)?$order->orderAmount:''?></td>
                                                        </tr>
                                                        <tr> 
                                                            <td colspan="5"></td>
                                                            <td>Paid</td>
                                                            <td><?php echo $currencySymbol;?><?=isset($order->orderAmount)?$order->orderAmount:''?></td>
                                                        </tr>
                                                    </table>  
                                                    
                                                    <table class="table no-print">
                                                    <thead>
                                                    <tr class="info">
                                                        <th>Manage Order</th>
                                                        <th></th>
                                                        <th></th>
                                                      </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tr>
                                                        <td align='middle'>
                                                            <span id='btnPrint' style='cursor: pointer;' data-text="Tidiit.com - Order Information - TIDIIT-OD-<?=$order->orderId;?>"><i class="fa fa-print"></i><br>
                                                                PRINT ORDER</span>
                                                        </td>
                                                        <td align='middle'><?php if($order->status == 5):?><a data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-envelope"></i><br>EMAIL INVOICE</a><?php endif;?></td>
                                                        <td align='middle'><span><a href="<?=BASE_URL?>contact-us"><i class="fa fa-phone-square"></i><br>
                                                                    CONTACT US</a></span></td>
                                                    </tr>
                                                </table>
                                                    
                                                </td>
                                            </tr>
                                        </table>  
                                        </div>
                                    </div> 
                                        <a href="<?=BASE_URL?>my-orders"><button class="btn btn-warning"><i class="fa fa-arrow-left"></i> Back</button> </a>
                                        <?php if($order->status == 5):?><a class="btn btn-info btn-xs pull-right" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-file-text-o"></i> View Invoice</a><?php endif;?>
                                    </div>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</article>
<?php echo $footer;?>