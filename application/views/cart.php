<div class="modal fade shoppingcart-popup" id="shoppingcart" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content cart-container">
    
    	<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="cart-heading clearfix">
                        <h3>
                            Shopping Truck <span class="js-cart-item">(<?=count($allItemArr)?> Item<?php if(count($allItemArr) > 1): echo 's';endif;?>)</span>
                        </h3>
                        
                    </div>
          </div>
<?php 
//print_r($this->cart->contents());
//$cart = $this->cart->contents();
$is_single = false;
foreach($allItemArr as $k):
    if($k['orderType'] == 'SINGLE'):
        $is_single = true; continue;
    endif;
endforeach;

$is_group = false;
foreach($allItemArr as $k):
    if($k['orderType'] == 'GROUP'):
        $is_group = true; continue;
    endif;
endforeach;
?>
    
                    
        <div class="cart-container-table">
            <?php if($is_single):?>
            <br>
            <h4>Single Order</h4><br>
            <table id="cart" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th style="width:45%">Product</th>
                        <th style="width:12%">Price</th>
                        <th style="width:8%">Quantity</th>
                        <th style="width:25%" class="text-center">Subtotal</th>
                        <th style="width:10%" align="right"></th>
                    </tr>
                </thead>
                <tbody>
                     <?php 
                     $subtotal = 0;
                     $tax=0;
                foreach($allItemArr as $k):
                    /*if(isset($item['options']['orderType']) && $item['options']['orderType'] == 'SINGLE'):
                        $productDetailsArr =  $this->Product_model->details($item['options']['productId']);
                        $productImageArr =$this->Product_model->get_products_images($item['options']['productId']);*/
                    ?>
                    <tr id="<?=$k['orderId']?>">
                        <td data-th="Product">
                            <div class="row">
                                <div class="col-sm-3 product-img"><img src="<?=PRODUCT_DEAILS_SMALL.$k['pimage']?>" alt="<?=$k['productTitle']?>" class="img-responsive"/></div>
                                <div class="col-sm-9 product-details">
                                    <h4 class="nomargin"><?=$k['productTitle']?></h4>
                                    <p><?php //$productDetailsArr[0]->shortDescription?></p>
                                </div>
                            </div>
                        </td>
                        <td data-th="Price"><i class="fa fa-rupee"></i> <?php echo $k['subTotalAmount']/$k['qty'];?></td>

                        <td data-th="Quantity">
                            <?=$k['qty']?>
                        </td>
                        <td data-th="Subtotal" class="text-right"><i class="fa fa-rupee"></i> <?=number_format($k['subTotalAmount'])?></td>
                        <td class="actions" data-th="" align="right">
                            <button class="btn btn-danger btn-sm js-single-cart-remove" data-cartid="<?=$k['orderId']?>"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                    <?php 
                    $subtotal += $k['subTotalAmount'];
                    $tax +=$k['taxAmount'];
                    //endif;
                    endforeach; //echo 'grand total '.$subtotal+$tax;?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3" class="hidden-xs"></td>
                        <td class="hidden-xs text-right"><strong>Sub Total <i class="fa fa-rupee"></i> <?=number_format($subtotal)?>.00</strong></td>
                        <td class="hidden-xs">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="hidden-xs"></td>
                        <td class="hidden-xs text-right"><strong>Tax <i class="fa fa-rupee"></i> <?=number_format($tax)?>.00</strong></td>
                        <td class="hidden-xs">&nbsp;</td>
                    </tr>
                    <tr>
                        <td><a href="<?=BASE_URL;?>" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
                        <td colspan="2" class="hidden-xs"></td>
                        <td class="hidden-xs text-right"><strong>Total <i class="fa fa-rupee"></i> <?=number_format($subtotal+$tax)?>.00</strong></td>
                        <td>
                            <?php if($subtotal > 1):?><a href="<?=BASE_URL;?>shopping/single-checkout/" class="btn btn-success btn-block">Checkout <i class="fa fa-angle-right"></i></a><?php endif;?></td>
                    </tr>
                </tfoot>
            </table>
            <?php endif;?>
            <?php if($is_group):?>
            <h4>Buying Club Order</h4><br>
            <table id="cart" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th style="width:55%">Product</th>
                        <th style="width:12%">Price</th>
                        <th style="width:8%">Quantity</th>
                        <th style="width:15%" class="text-right">Subtotal</th>
                        <th style="width:10%" align="right"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                foreach($allItemArr as $k):
                    /*if(isset($item['options']['orderType']) && $item['options']['orderType'] == 'GROUP'):
                        $productDetailsArr =  $this->Product_model->details($item['options']['productId']);
                        $productImageArr =$this->Product_model->get_products_images($item['options']['productId']);*/
                    ?>
                    <tr id="<?=$k['orderId']?>">
                        <td data-th="Product">
                            <div class="row">
                                <div class="col-sm-3 product-img"><img src="<?=PRODUCT_DEAILS_SMALL.$k['pimage']?>" alt="<?=$k['productTitle']?>" class="img-responsive"/></div>
                                <div class="col-sm-9 product-details">
                                    <h4 class="nomargin"><?=$k['productTitle']?></h4>
                                    <p><?php //$productDetailsArr[0]->shortDescription?></p>
                                </div>
                            </div>
                        </td>
                        <td data-th="Price"><i class="fa fa-rupee"></i> <?=$k['subTotalAmount']/$k['qty']?></td>

                        <td data-th="Quantity">
                            <?=$k['qty']?>
                        </td>
                        <td data-th="Subtotal" class="text-center"><i class="fa fa-rupee"></i> <?=number_format($k['subTotalAmount'])?></td>
                        <td class="actions" data-th="" align="right">
                            <button class="btn btn-danger btn-sm js-group-cart-remove" data-cartid="<?=$k['orderId']?>" data-orderid="<?=$k['orderId']?>"><i class="fa fa-trash-o"></i></button>
                            <a href="<?=BASE_URL;?>shopping/checkout/<?=base64_encode($k['orderId']*226201)?>" class="btn btn-success btn-sm"> Checkout <i class="fa fa-angle-right"></i></a>
                        </td>
                    </tr>
                    <?php //endif;
                    endforeach;?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="3" class="hidden-xs"></td>
                        <td class="hidden-xs text-right"><strong>Tax <i class="fa fa-rupee"></i> <?=number_format($k['taxAmount'])?>.00</strong></td>
                        <td class="hidden-xs">&nbsp;</td>
                    </tr>
                    <tr>
                        <td><a href="<?=BASE_URL;?>" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
                        <td colspan="2" class="hidden-xs"></td>
                        <td class="text-right"><strong>Total <i class="fa fa-rupee"></i> <?=number_format($k['subTotalAmount']+$k['tax'])?>.00</strong></td>
                        <td class="hidden-xs">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
            <?php endif; ?>
        </div>
                    
                    
    </div>
  </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#shoppingcart').modal('show');
        
        jQuery("body").delegate('.js-group-cart-remove', "click", function(e){
            e.preventDefault();
            myJsMain.commonFunction.showPleaseWait();
            var cartId = jQuery(this).attr('data-cartid');
            var orderId = jQuery(this).attr('data-orderid');
            jQuery.post( myJsMain.baseURL+'shopping/remove_group_cart/', {
                cartId: cartId,
                orderId: orderId
            },
            function(data){ 
                myJsMain.commonFunction.hidePleaseWait();
                if(data.contents){
                    jQuery('tr#'+cartId).remove();
                    var item = "(<?=count($allItemArr)-1?> Item<?php if(count($allItemArr)-1 > 1): echo 's';endif;?>)";
                    jQuery('span.js-cart-item').text(item);
                    jQuery('#shoppingcart').modal('hide');
                    //jQuery('.showCartDetails').trigger( "click" ); 
                    window.location.href = myJsMain.baseURL+'shopping/my-cart/';
                }
            }, 'json' );
        }); 
        
        jQuery("body").delegate('.js-single-cart-remove', "click", function(e){
            e.preventDefault();
                         
            var cartId = jQuery(this).attr('data-cartid');
            jQuery.post( myJsMain.baseURL+'shopping/remove-single-cart/', {
                cartId: cartId
            },
            function(data){ 
                if(data.contents){
                    jQuery('tr#'+cartId).remove();
                    /*if(data.reload){
                        window.location.href = myJsMain.baseURL;
                    } else {
                        var item = "(<?php //count($this->cart->contents())-1?> Item<?php //if(count($this->cart->contents())-1 > 1): echo 's';endif;?>)";
                        jQuery('span.js-cart-item').text(item);
                        jQuery('#shoppingcart').modal('hide');
                        jQuery('.showCartDetails').trigger( "click" ); 
                    }*/
                    window.location.href = myJsMain.baseURL+'shopping/my-cart/';
                }
            }, 'json' );
        }); 
        
    });
</script>