<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo $html_heading; echo $header;
$CI =& get_instance();
$CI->load->model('Product_model');
$currencySymbol=($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')=="IN") ? '<i class="fa fa-rupee"></i>' :'KSh';
?>
<script src="<?php echo SiteJSURL;?>user-all-my-js.js" type="text/javascript"></script>
</div>
</header>

<div class="container">

    <div class="row">

        <div class="col-md-12 col-sm-12 productInner">

            <div class="page_content">

                <div class="row">

                    <div class="CheckoutPage_container"> 
                        <div class="container">

                            <ul id="myTab" class="nav nav-tabs col-md-2 col-sm-4"> 
                                <li  class="active"><a href="#Confirm" data-toggle="tab" class="js-active-shipping js-shipping"  data-shipping="<?php if ($userShippingDataDetails->address): ?>true<?php endif; ?>"><span class="number">1 </span>Confirm Address</a></li>
                                <li><a href="#Review" data-toggle="tab" data-shipping="<?php if ($userShippingDataDetails->address): ?>true<?php endif; ?>" class="js-shipping"><span class="number">2 </span>Review Order</a></li>
                                <li><a href="#Payment" data-toggle="tab" data-shipping="<?php if ($userShippingDataDetails->address): ?>true<?php endif; ?>" class="js-shipping"><span class="number">3 </span>Make Payment</a></li>
                            </ul>
                            <div id="myTabContent" class="tab-content col-md-10 col-sm-8" >
                                <div class="tab-pane fade in active" id="Confirm">
                                    <div class="col-md-7">

                                        <h3 class="log-title">Confirm Shipping Address</h3>
                                        <div class="small-font-text form-group">We will deliver your order here</div>

                                        <form class="form-horizontal" action="#" method="post" name="my_checkout_shipping_address" id="my_checkout_shipping_address">
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">First Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="firstName" placeholder="" name="firstName" value="<?= $userShippingDataDetails->firstName; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Last Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="lastName" placeholder="" name="lastName" value="<?= $userShippingDataDetails->lastName; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Phone</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="phone" placeholder="" name="phone" value="<?= $userShippingDataDetails->contactNo; ?>">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Address</label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control" rows="3" name="address" id="address"><?= $userShippingDataDetails->address; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Country</label>
                                                <div class="col-sm-9 countryElementPara">
                                                    <select class="form-control" name="countryId" id="countryId" value="">
                                                        <option value="">Select</option>
                                                        <?php foreach ($countryDataArr AS $k) { ?>
                                                            <option value="<?php echo $k->countryId; ?>" <?php if ($k->countryId == $userShippingDataDetails->countryId) { ?>selected<?php } ?>><?php echo $k->countryName; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">
                                                    <?php if($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')=='IN'):?>
                                                    City<?php else:?>
                                                    County<?php endif;?>
                                                </label>
                                                <div class="col-sm-9 cityElementPara">
                                                    <?php if ($userShippingDataDetails->cityId == "") { ?>
                                                        <select class="form-control" name="cityId" id="cityId" value=""  tabindex="1">
                                                            <option value="">Select</option>
                                                        </select>
                                                    <?php } else { ?> 
                                                        <select class="form-control" name="cityId" id="cityId" value=""  tabindex="1">
                                                            <option value="">Select</option>
                                                            <?php foreach ($cityDataArr As $k) { ?>
                                                                <option value="<?php echo $k->cityId; ?>" <?php if ($k->cityId == $userShippingDataDetails->cityId) { ?>selected<?php } ?>><?php echo $k->city; ?></option>
                                                            <?php } ?> 
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">
                                                    <?php if($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')=='IN'):?>
                                                    Zip<?php else:?>
                                                    Postal Box Zip Code<?php endif;?>
                                                </label>
                                                <div class="col-sm-9 zipElementPara">
                                                    <?php if ($userShippingDataDetails->zipId == "") { ?>
                                                        <select class="form-control" name="zipId" id="zipId" value=""  tabindex="1">
                                                            <option value="">Select</option>
                                                        </select>
                                                    <?php } else { ?>
                                                        <select class="form-control" name="zipId" id="zipId" value=""  tabindex="1">
                                                            <option value="">Select</option>
                                                            <?php foreach ($zipDataArr AS $k) { ?>
                                                                <option value="<?php echo $k->zipId; ?>" <?php if ($k->zipId == $userShippingDataDetails->zipId) { ?>selected<?php } ?>><?php echo $k->zip; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Locality</label>
                                                <div class="col-sm-9 localityElementPara">
                                                    <?php if ($userShippingDataDetails->localityId == "") { ?>
                                                        <select class="form-control" name="localityId" id="localityId" value=""  tabindex="1">
                                                            <option value="">Select</option>
                                                        </select>
                                                    <?php } else { ?>
                                                        <select class="form-control" name="localityId" id="localityId" value=""  tabindex="1">
                                                            <option value="">Select</option>
                                                            <?php foreach ($localityDataArr AS $k) { ?>
                                                                <option value="<?php echo $k->localityId; ?>" <?php if ($k->localityId == $userShippingDataDetails->localityId) { ?>selected<?php } ?>><?php echo $k->locality; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Landmark</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="landmark" name="landmark" placeholder="" value="<?= $userShippingDataDetails->landmark; ?>">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">&nbsp;</label>
                                                <div class="col-sm-9">
                                                    <input type="submit" name="shippingCheckoutAddress" id="shippingCheckoutAddress" class="btn btn-default" value="Update" />
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                    <div class="col-md-5 Existing-Address">
                                        <h3 class="log-title">Shipping Address</h3>
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <h3 class="panel-title pull-left">My Shipping Address</h3>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body">
                                                <div class="marg_btm js-right-panel">
                                                    <?php if ($userShippingDataDetails->address): ?>
                                                        <?= $userShippingDataDetails->firstName; ?> <?= $userShippingDataDetails->lastName; ?><br>
                                                        <?= $userShippingDataDetails->address; ?><br>
                                                        <?= $userShippingDataDetails->contactNo; ?><br>
                                                        <?php if ($userShippingDataDetails->landmark): ?>
                                                            <b>Landmark:</b><?= $userShippingDataDetails->landmark; ?>
                                                        <?php endif; ?><br>
                                                    <?php endif; ?>    
                                                </div>
                                                <div class="clearfix"></div>

                                            </div>
                                            <div class="panel-footer">
                                                <button type="button" class="btn btn-default shipping-continue" data-shipping="<?php if ($userShippingDataDetails->address): ?>true<?php endif; ?>">Continue</button>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="js-message" role="alert" style="display: none;"></div>
                                </div>

                                <div class="tab-pane fade" id="Review">


                                    <h3 class="log-title">Review Order</h3>
                                    <div class="small-font-text form-group">Our delivery time depends on your selected delivery option</div>


                                    <div class="cart-container-table">
                                        <table id="cart" class="table table-hover table-condensed <?=isset($order->orderId)?$order->orderId:""?>">
                                            <thead>
                                                <tr>
                                                    <th style="width:50%">Product</th>
                                                    <th style="width:12%">Price</th>
                                                    <th style="width:8%">Quantity</th>
                                                    <th style="width:10%" class="text-center">Subtotal</th>
                                                    <th style="width:5%" align="right">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $total = 0;
                                                //foreach ($cart as $item):
                                                    if ($order):
                                                        $total += $order->orderAmount;
                                                        $productDetailsArr = $this->Product_model->details($order->productId);
                                                        $productImageArr = $this->Product_model->get_products_images($order->productId);
                                                        ?>
                                                        <tr id="<?=$order->orderId?>">
                                                            <td data-th="Product">
                                                                <div class="row">
                                                                    <div class="col-sm-3 product-img"><img src="<?= PRODUCT_DEAILS_SMALL . $productImageArr[0]->image ?>" alt="..." class="img-responsive"></div>
                                                                    <div class="col-sm-9 product-details">
                                                                        <h4 class="nomargin"><?= $productDetailsArr[0]->title ?></h4>
                                                                        <p><?=$productDetailsArr[0]->shortDescription ?></p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <?php
                                                            $single_price = ($order->subTotalAmount/$order->productQty);
                                                            $price = number_format($single_price, 2, '.', '');?>
                                                            <td data-th="Price"><?php echo $currencySymbol;?> <?= $price?></td>

                                                            <td data-th="Quantity"><?=$order->productQty?></td>
                                                            <td data-th="Subtotal" class="text-center"><i class="fa fa-rupee"></i> <?= number_format($order->subTotalAmount) ?>.00</td>
                                                            <td class="actions" data-th="" align="right">

                                                                <button class="btn btn-danger btn-sm js-group-cart-remove"  data-orderid="<?=$order->orderId?>"><i class="fa fa-trash-o"></i></button>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    endif;
                                                //endforeach;
                                                ?>

                                                <tr>
                                                    <td colspan="5" valign="middle">
                                                        <div class="pincode-check-enable row">
                                                            <div class="col-sm-5 col-xs-12"><span class="pull-left pincode-TEXT"> Have a promo code? Apply </span><input type="text" id="order-coupon" data-order="<?=$order->orderId?>" name="coupon" value=""></div> 
                                                            <div class="col-sm-1 col-xs-12">
                                                                <a href="javascript://" class="js-apply-coupon">Apply</a>
                                                            </div>
                                                            <div id="pincode-error-tooltip">
                                                                <p class="pincode-error-text"><i class="fa fa-gift"></i> Gift Wrap Not Available</p>
                                                            </div>
                                                        </div>							
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <tfoot>

                                                <tr>
                                                    <td colspan="3">Delivery and payment options can be selected later</td>
                                                    <td class="text-center">Free</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"  class="text-right">Tax :  </td>
                                                    <td class="text-center js-show-disc-amt"><?php echo $currencySymbol;?> <?=$order->taxAmount?></td>
                                                    <td></td>
                                                </tr>
<?php 
if($coupon):
    $total -= $coupon->amount;
    $disc = $coupon->amount;
else:
    $disc = '0.00';
endif;?>                                                
                                                <tr class="js-show-disc" <?php if(!$coupon):?>style="display: none;"<?php endif;?>>
                                                    <td colspan="3"  class="text-right">Discount :  -</td>
                                                    <td class="text-center js-show-disc-amt"><?php echo $currencySymbol;?> <?=$disc?></td>
                                                    <td></td>
                                                </tr>

                                                <tr>
                                                    <td></td>
                                                    <td class="hidden-xs"></td>
                                                    <td colspan="2" class="hidden-xs text-center js-sub-total"><strong>Total <?php echo $currencySymbol;?> <?=$total?></strong></td>
                                                    <td colspan="2"><a href="javascript://" class="btn btn-success btn-block js-proceed-payment">PROCEED TO PAYMENT <i class="fa fa-angle-right"></i></a></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="js-message" role="alert" style="display: none;"></div>                                        
                                    <div class="clearfix"></div>
                                </div>

                                <div class="tab-pane fade" id="Payment">
                                    <div class="js-payment-message" role="alert" style="display: none;"></div>                                        
                                    <div class="clearfix"></div>

                                    <h3 class="log-title">Select Payment Option</h3>
                                    <form name="group_order_payment_option" id="group_order_payment_option" method="post" action="<?php echo BASE_URL.'shopping/ajax-process-group-payment';?>">
                                    <div class="small-font-text form-group">
                                            <?php foreach($paymentGatewayData AS $k):?> 
                                            <div class="input-group form-group order-labl">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="paymentoption" value="<?php echo $k->gatewayCode;?>">
                                                </span>
                                                <label for="<?php echo $k->gatewayCode;?>"><?php echo $k->gatewayTitle;?></label>
                                            </div><!-- /input-group -->
                                            
                                            <!--<div class="input-group order-labl form-group">
                                                <span class="input-group-addon">
                                                  <input type="radio" name="paymentoption" value="razorpay">
                                                </span>
                                                <label for="sin">Razorpay</label>
                                            </div><!-- /input-group -->
                                            <?php endforeach;?>
                                            <div class="input-group order-labl form-group">
                                                <span class="input-group-addon">
                                                  <input type="radio" name="paymentoption" value="sod" checked>
                                                </span>
                                                <label for="sin">Settlement on Delivery</label>
                                            </div><!-- /input-group -->
                                            <input type="hidden" name="orderId" value="<?=$order->orderId?>"/>

                                    </div>


                                    <div class="cart-container-table">
                                        <button type="submit" class="btn btn-info btn-block">Pay Now <i class="fa fa-angle-right"></i></button>
                                    </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>


<script type="text/javascript">
    jQuery(document).ready(function(){
    myJsMain.my_checkout_shipping_address();
    jQuery('#countryId').on('change',function(){
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery.ajax({
                   type:"POST",
                   url:myJsMain.baseURL+'ajax/show_city_by_country/',
                   data:'countryId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                           jQuery('.cityElementPara').html(msg);
                            //jQuery('.zipElementPara').html(zipDynEle);
                            //jQuery('.localityElementPara').html(localityDynEle);
                       }
                   }
               });
           }
        });
        
        jQuery('.cityElementPara').on('change','#cityId',function(){
        //jQuery('#cityId').on('change',function(){ alert('rr');
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery.ajax({
                   type:"POST",
                   url:myJsMain.baseURL+'ajax/show_zip_by_city/',
                   data:'cityId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                           jQuery('.zipElementPara').html(msg);
                           //jQuery('.localityElementPara').html(localityDynEle);
                       }
                   }
               });
           }
        });
        
        jQuery('.zipElementPara').on('change','#zipId',function(){
        //jQuery('#cityId').on('change',function(){ alert('rr');
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery.ajax({
                   type:"POST",
                   url:myJsMain.baseURL+'ajax/show_locality_by_zip/',
                   data:'zipId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                           jQuery('.localityElementPara').html(msg);
                       }
                   }
               });
           }
        });
        
        jQuery("body").delegate('.shipping-continue', "click", function(e){
            e.preventDefault();
            var shipping = $(this).attr('data-shipping');
            if(!shipping){ 
                $('div.js-message').html('<div class="alert alert-danger">Please update shipping address!</div>');
             $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
            } else {                
                $('.nav-tabs a[href="#Review"]').trigger( "click" ); 
            }
        });
        
       jQuery('a[data-toggle="tab"]').on('hidden.bs.tab', function (e) {
            e.target // newly activated tab
            e.relatedTarget // previous active tab
            if(!$(e.relatedTarget).attr('data-shipping')){
                $('.nav-tabs a[href="#Confirm"]').trigger( "click" );
            }
      });
      
      jQuery("body").delegate('a.js-apply-coupon', "click", function(e){
            e.preventDefault();
            var obj = $('input[id="order-coupon"]');            
            var cpn = obj.val();
            if(!cpn){ 
                $('div.js-message').html('<div class="alert alert-danger">Please enter your promo code!</div>');
             $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
            } else {                
                myJsMain.commonFunction.showPleaseWait();
                $.post( myJsMain.baseURL+'shopping/ajax_order_set_promo/', {
                    orderId: obj.attr('data-order'),
                    promocode: cpn
                },
                function(data){
                    myJsMain.commonFunction.hidePleaseWait();
                    if(data.error){
                        $('div.js-message').html('<div class="alert alert-danger">'+data.error+'</div>');
                        $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
                    }
                    
                    if(data.msg){
                        $('div.js-message').html('<div class="alert alert-success">'+data.msg+'</div>');
                        $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
                    }
                    
                    if(data.content){
                        $('tr.js-show-disc').show();
                        $('td.js-show-disc-amt').text(data.content.amount);
                        $('.js-sub-total').html('<strong>Total $'+data.content.orderAmount+'</strong>');
                        obj.val('');
                    }
                    
                }, 'json' ); 
            }
        });
        
        
        jQuery("body").delegate('a.js-proceed-payment', "click", function(e){
            e.preventDefault();
            $('.nav-tabs a[href="#Payment"]').trigger( "click" ); 
        });
        
        
        jQuery("body").delegate('.js-group-cart-remove', "click", function(e){
            e.preventDefault();
            var cartId = jQuery(this).attr('data-orderid');
            jQuery.post( myJsMain.baseURL+'shopping/remove_group_cart/', {
                cartId: cartId
            },
            function(data){ 
                if(data.contents){
                    //jQuery('tr#'+cartId).remove();
                    var item = "(<?=count($this->cart->contents())-1?> Item<?php if(count($this->cart->contents())-1 > 1): echo 's';endif;?>)";
                    jQuery('span.js-cart-item').text(item);
                    jQuery('.'+cartId).hide();
                    window.location.href = myJsMain.baseURL+'shopping/my-cart/';
                }
            }, 'json' );
        }); 
        
        jQuery("body").delegate('a.js-order-payment', "click", function(e){
            myJsMain.commonFunction.showPleaseWait();
            $('#group_order_payment_option')[0].submit();
            /*e.preventDefault();
            
            var orderId = jQuery(this).attr('data-orderid');
            var cartId = jQuery(this).attr('data-cartid');
            jQuery.post( myJsMain.baseURL+'shopping/ajax_process_group_payment/', {
                orderId: orderId,
                cartId: cartId
            },
            function(data){ 
                if(data.url){                    
                    window.location.href = data.url;
                }
                myJsMain.commonFunction.hidePleaseWait();
                if(data.error){
                    $('div.js-payment-message').html(data.error);
                    $('div.js-payment-message').fadeIn(300,function() { setTimeout( '$("div.js-payment-message").fadeOut(300)', 15000 ); });
                }
            }, 'json' );*/
        });
    });
</script>

<?php echo $footer;?>