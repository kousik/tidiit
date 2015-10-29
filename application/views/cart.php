<div class="modal fade shoppingcart-popup" id="shoppingcart" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg">
    <div class="modal-content cart-container">
    
    	<div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="cart-heading clearfix">
                        <h3>
                            Shopping Cart <span>(<?=$this->cart->total_items()?> Item<?php if($this->cart->total_items() > 1): echo 's';endif;?>)</span>
                        </h3>
                        
                    </div>
          </div>
<?php 
//print_r($this->cart->contents());
$cart = $this->cart->contents();
$is_group = false;
foreach($cart as $item):
    if(isset($item['options']['orderType']) && $item['options']['orderType'] == 'GROUP'):
        $is_group = true; continue;
    endif;
endforeach;
?>
    
                    
        <div class="cart-container-table">
            <table id="cart" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th style="width:55%">Product</th>
                        <th style="width:12%">Price</th>
                        <th style="width:8%">Quantity</th>
                        <th style="width:15%" class="text-center">Subtotal</th>
                        <th style="width:10%" align="right"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-th="Product">
                            <div class="row">
                                <div class="col-sm-3 product-img"><img src="http://placehold.it/100x100" alt="..." class="img-responsive"/></div>
                                <div class="col-sm-9 product-details">
                                    <h4 class="nomargin">Product 1</h4>
                                    <p>Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Lorem ipsum dolor sit amet.</p>
                                </div>
                            </div>
                        </td>
                        <td data-th="Price">$1.99</td>

                        <td data-th="Quantity">
                            <input type="number" class="form-control text-center" value="1">
                        </td>
                        <td data-th="Subtotal" class="text-center">1.99</td>
                        <td class="actions" data-th="" align="right">
                            <button class="btn btn-info btn-sm"><i class="fa fa-refresh"></i></button>
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>								
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr class="visible-xs">
                        <td class="text-center"><strong>Total 1.99</strong></td>
                    </tr>
                    <tr>
                        <td><a href="#" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
                        <td colspan="2" class="hidden-xs"></td>
                        <td class="hidden-xs text-center"><strong>Total $1.99</strong></td>
                        <td><a href="#" class="btn btn-success btn-block">Checkout <i class="fa fa-angle-right"></i></a></td>
                    </tr>
                </tfoot>
            </table>
            <?php if($is_group):?>
            <h4>Group Order</h4>
            <table id="cart" class="table table-hover table-condensed">
                <thead>
                    <tr>
                        <th style="width:55%">Product</th>
                        <th style="width:12%">Price</th>
                        <th style="width:8%">Quantity</th>
                        <th style="width:15%" class="text-center">Subtotal</th>
                        <th style="width:10%" align="right"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                foreach($cart as $item):
                    if(isset($item['options']['orderType']) && $item['options']['orderType'] == 'GROUP'):?>
                    <tr>
                        <td data-th="Product">
                            <div class="row">
                                <div class="col-sm-3 product-img"><img src="http://placehold.it/100x100" alt="..." class="img-responsive"/></div>
                                <div class="col-sm-9 product-details">
                                    <h4 class="nomargin"><?=$item['name']?></h4>
                                </div>
                            </div>
                        </td>
                        <td data-th="Price"><?=$item['price']?></td>

                        <td data-th="Quantity">
                            <?=$item['qty']?>
                        </td>
                        <td data-th="Subtotal" class="text-center"><?=$item['subtotal']?></td>
                        <td class="actions" data-th="" align="right">
                            <button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>
                            <button class="btn btn-success btn-sm">Checkout <i class="fa fa-angle-right"></i></button>
                        </td>
                    </tr>
                    <?php endif;
                    endforeach;?>
                </tbody>

                <tfoot>
                    <tr class="visible-xs">
                    </tr>
                    <tr>
                        <td><a href="#" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
                        <td colspan="2" class="hidden-xs"></td>
                        <td class="hidden-xs text-center"></td>
                        <td></td>
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
    });
</script>