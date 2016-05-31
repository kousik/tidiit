<?php
echo $html_heading; echo $header;
//$CI =& get_instance();
//$CI->load->model('Product_model');
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

                        <?= $userMenu ?>

                        <div class="col-md-9 wht_bg">

                            <!-- Tab panes -->

                            <div class="tab_dashbord">
                                <div class="active row">
                                    <div class="col-md-12 col-sm-12">

                                        <div class="grouporder_id">
                                    <div class="list-group gen_infmtn">
                                   <?php 
                                    //print_r($this->cart->contents());
                                    //$cart = $this->cart->contents();
                                    $is_single = false;
                                    foreach($allItemArr as $k):
                                        if(isset($k['orderType']) && $k['orderType'] == 'SINGLE'):
                                            $is_single = true; continue;
                                        endif;
                                    endforeach;
                                    $is_group = false;
                                    foreach($allItemArr as $k):
                                        if(isset($k['orderType']) && $k['orderType'] == 'GROUP'):
                                            $is_group = true; continue;
                                        endif;
                                    endforeach;
                                    ?>
                                    <h4>MY TRUCK</h4><br>
                                        <div class="">
                                            <div class="clearfix"></div>
                                            <div class="js-message" role="alert" style="display: none;"></div>
                                            <div class="clearfix"></div>
                                            <?php if($is_single):?>
                                            <h4>Single Order</h4><br>
                                            <table id="cart" class="table table-hover table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th style="width:40%">Product</th>
                                                        <th style="width:12%">Price</th>
                                                        <th style="width:8%">Quantity</th>
                                                        <th style="width:20%" class="text-right">Subtotal</th>
                                                        <th style="width:20%" align="right"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <?php 
                                                     $total = 0;
                                                     $tax=0;
                                                foreach($allItemArr as $k):
                                                    if(isset($k['orderType']) && $k['orderType'] == 'SINGLE'):
                                                        //$productDetailsArr =  $this->Product_model->details($item['options']['productId']);
                                                        //$productImageArr =$this->Product_model->get_products_images($item['options']['productId']);*/
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
                                                        <td data-th="Price"><?php echo $currencySymbol; ?> <?=$k['subTotalAmount']/$k['qty']?></td>

                                                        <td data-th="Quantity">
                                                            <?=$k['qty']?>
                                                        </td>
                                                        <td data-th="Subtotal" class="text-right"><?php echo $currencySymbol;?> <?=number_format($k['subTotalAmount'])?></td>
                                                        <td class="actions" data-th="" align="right">
                                                            <button class="btn btn-danger btn-sm js-single-cart-remove" data-cartid="<?=$k['orderId']?>"><i class="fa fa-trash-o"></i></button>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                    $total += $k['subTotalAmount'];
                                                    $tax += $k['taxAmount'];
                                                    endif;
                                                    endforeach; ?>
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="hidden-xs"></td>
                                                        <td class="hidden-xs text-right"><strong>Sub Total <?php echo $currencySymbol;?> <?=number_format($total)?>.00</strong></td>
                                                        <td class="hidden-xs">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="hidden-xs"></td>
                                                        <td class="hidden-xs text-right"><strong>Tax <?php echo $currencySymbol;?> <?=number_format($tax)?>.00</strong></td>
                                                        <td class="hidden-xs">&nbsp;</td>
                                                    </tr>

                                                    <tr>
                                                        <td><a href="<?=BASE_URL;?>" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
                                                        <td colspan="2" class="hidden-xs"></td>
                                                        <td class="hidden-xs text-right"><strong>Total <?php echo $currencySymbol;?> <?=number_format($total+$tax)?>.00</strong></td>
                                                        <td>
                                                            <?php if($total > 1):?><a href="<?=BASE_URL;?>shopping/single-checkout/" class="btn btn-success btn-block">Checkout <i class="fa fa-angle-right"></i></a><?php endif;?></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <?php endif;?>
                                            <?php if($is_group):?>
                                            <h4>Buying Club Order</h4>
                                            <table id="cart" class="table table-hover table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th style="width:45%">Product</th>
                                                        <th style="width:12%">Price</th>
                                                        <th style="width:8%">Quantity</th>
                                                        <th style="width:15%" class="text-center">Subtotal</th>
                                                        <th style="width:20%" align="right"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $total = 0;
                                                $tax=0;
                                                foreach($allItemArr as $k):
                                                    if(isset($k['orderType']) && $k['orderType'] == 'GROUP'):
                                                        /*$productDetailsArr =  $this->Product_model->details($item['options']['productId']);
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
                                                        <td data-th="Price"><?php echo $currencySymbol;?> <?=$k['subTotalAmount']?></td>

                                                        <td data-th="Quantity">
                                                            <?=$k['qty']?>
                                                        </td>
                                                        <td data-th="Subtotal" class="text-center"><?php echo $currencySymbol;?> <?=number_format($k['subTotalAmount'])?></td>
                                                        <td class="actions" data-th="" align="right">
                                                            <button class="btn btn-danger btn-sm js-group-cart-remove" data-cartid="<?=$k['orderId']?>" data-orderid="<?=$k['orderId']?>"><i class="fa fa-trash-o"></i></button>
                                                            <a href="<?=BASE_URL;?>shopping/checkout/<?=base64_encode($k['orderId']*226201)?>" class="btn btn-success btn-sm"> Checkout <i class="fa fa-angle-right"></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                        $total += $k['subTotalAmount'];
                                                        $tax += $k['taxAmount'];
                                                    endif;
                                                    endforeach;?>
                                                </tbody>

                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3" class="hidden-xs"></td>
                                                        <td class="hidden-xs text-right"><strong>Tax <?php echo $currencySymbol;?> <?=number_format($tax)?>.00</strong></td>
                                                        <td class="hidden-xs">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td><a href="<?=BASE_URL;?>" class="btn btn-warning"><i class="fa fa-angle-left"></i> Continue Shopping</a></td>
                                                        <td colspan="2" class="hidden-xs"></td>
                                                        <td class="text-right"><strong>Total <?php echo $currencySymbol;?> <?=number_format($total+$tax)?>.00</strong></td>
                                                        <td class="hidden-xs">&nbsp;</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                            <?php endif; ?>
                                            
                                            <div class="js-message"></div>
                                            <?php if(!$is_single && !$is_group):?>
                                            <div class="alert alert-success" role="alert"><i class="fa fa-truck"></i>  Sorry! no truck item's available.</div>
                                            <?php endif;?>
                                        </div>
                                        <div class="clearfix"></div>
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

    </div>

</article> 
<script type="text/javascript">
    jQuery(document).ready(function(){
        var message='<?php  echo $this->session->flashdata('message');?>';
        if(message!=''){
            $('div.js-message').html('<div class="alert alert-danger">'+message+'</div>');
            $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
        }
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
<?php echo $footer;?>