<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo $html_heading; echo $header;
//$CI =& get_instance();
//$CI->load->model('Product_model');
//$cart = $this->cart->contents();
//pre($cart);
//echo key($cart);die;
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
                                        <table id="cart" class="table table-hover table-condensed">
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
                                                $tax=0;
                                                $disc='';
                                                foreach ($allItemArr as $k): //pre($k);
                                                    $total += $k['subTotalAmount'];
                                                    $tax +=$k['taxAmount'];
                                                    $disc +=$k['discountAmount'];
                                                    //$productDetailsArr = $this->Product_model->details($item['options']['productId']);
                                                    //$productImageArr = $this->Product_model->get_products_images($item['options']['productId']);
                                                    ?>
                                                    <tr id="<?=$k['orderId']?>">
                                                        <td data-th="Product">
                                                            <div class="row">
                                                                <div class="col-sm-3 product-img"><img src="<?=PRODUCT_DEAILS_SMALL.$k['pimage']?>" alt="<?=$k['productTitle']?>" class="img-responsive"></div>
                                                                <div class="col-sm-9 product-details">
                                                                    <h4 class="nomargin"><?= $k['productTitle'] ?></h4>
                                                                    <p>
                                                                    <div class="col-sm-12 col-xs-12 float-lt padng-lft-none">
                                                                        <div class="col-sm-8 col-xs-12 padng-lft-none">
                                                                            <span class="pull-left pincode-TEXT"> Have a promo code? </span>
                                                                            <span class="showPromocodeSection" data-eleid="<?=$k['orderId']?>">
                                                                                <a href="javascript://">Use those</a></span>
                                                                            <input type="text" id="order-coupon<?=$k['orderId']?>" name="coupon" value="" style="width:55%;<?php if($k['discountAmount']<1){?>display: none;<?php }?>">
                                                                        </div> 
                                                                        <div class="col-sm-4 col-xs-12 padng-lft-none padng_right_none ApplyCouponActionSection<?=$k['orderId']?>" <?php if($k['discountAmount']<1){?>style="display: none;"<?php }?>>
                                                                            <a href="javascript://" class="js-apply-coupon" alt="<?=$k['orderId']?>">Apply</a>
                                                                            <span class="applyCouponElemtnForRemove<?=$k['orderId']?>">
                                                                                <?php if($k['discountAmount']>0){?>
                                                                                <button type="button" class="btn btn-info btn-xs remove-coupon-from-order-<?=$k["orderId"]?>" style="width:75px"><i class="fa fa-tags"></i>COUPON</button><button type="button" class="btn btn-danger btn-xs remove-coupon-from-order remove-coupon-from-order-<?=$k["orderId"]?>" data-id="<?=$k["orderId"]?>"><i class="fa fa-times-circle"></i></button>
                                                                                <?php }?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                        
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td data-th="Price"><?php echo $currencySymbol;?> <?= $k['subTotalAmount']/$k['qty'] ?></td>

                                                        <td data-th="Quantity"><?= $k['qty'] ?></td>
                                                        <td data-th="Subtotal" class="text-center"><?php echo $currencySymbol;?> <?= number_format($k['subTotalAmount']) ?>.00</td>
                                                        <td class="actions" data-th="" align="right">

                                                            <button class="btn btn-danger btn-sm js-single-cart-remove" data-cartid="<?=$k['orderId']?>"><i class="fa fa-trash-o"></i></button>								
                                                        </td>
                                                    </tr>
                                                    <?php
                                                endforeach;
                                                ?>

                                                <tr>
                                                    <td colspan="5" valign="middle">
                                                        <div class="pincode-check-enable row">
                                                            <!--<div class="col-sm-5 col-xs-12"><span class="pull-left pincode-TEXT"> Have a promo code? Apply </span><input type="text" id="order-coupon" name="coupon" value=""></div> 
                                                            <div class="col-sm-1 col-xs-12">
                                                                <a href="javascript://" class="js-apply-coupon">Apply</a>
                                                            </div>-->
                                                            <div id="pincode-error-tooltip" class=" padng_fftn">
                                                                <p class="pincode-error-text"><i class="fa fa-gift"></i> Gift Wrap Not Available</p>
                                                            </div>
                                                        </div>							
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <tfoot>
                                                <tr class="js-show-disc" <?php if($disc==""):?>style="display: none;"<?php endif;?>>
                                                    <td colspan="3"  class="text-right"><strong>Discount :  -</strong></td>
                                                    <td class="text-right  js-show-disc-amt"><strong><?php echo $currencySymbol;?> 
                                                        <?php 
                                                        echo  number_format(round($disc,0,PHP_ROUND_HALF_UP),2);
                                                        ?>
                                                        </strong></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-right"><strong>Tax : -</strong></td>
                                                    <td class="hidden-xs text-right js-tax-total"><strong><?php echo $currencySymbol;?> <?= number_format(round($tax,0,PHP_ROUND_HALF_UP),2)?></strong></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-right"><strong>Total : -</strong></td>
                                                    <td class="hidden-xs text-right js-sub-total"><strong> <?php echo $currencySymbol;?> <?=number_format(round(($total-$disc)+$tax,0,PHP_ROUND_HALF_UP),2)?></strong></td>
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
                                    <form name="single_order_payment_option_pr" id="single_order_payment_option_pr" method="post" action="<?php echo BASE_URL.'shopping/ajax_process_single_payment_start';?>">
                                        <div class="small-font-text form-group">
                                            <?php foreach($paymentGatewayData AS $k):?> 
                                            
                                            <div class="input-group form-group order-labl">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="paymentoption" value="<?php echo $k->gatewayCode;?>">
                                                </span>
                                                <label for="<?php echo $k->gatewayCode;?>"><?php echo $k->gatewayTitle;?></label>
                                            </div><!-- /input-group -->
                                            <!--<div class="input-group form-group order-labl">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="paymentoption" value="ebs">
                                                </span>
                                                <label for="ebs">EBS</label>
                                            </div><!-- /input-group -->

                                            <!--<div class="input-group form-group order-labl">
                                                <span class="input-group-addon">
                                                    <input type="radio" name="paymentoption" value="mpesa">
                                                </span>
                                                <label for="mpesa">mPesa</label>
                                            </div><!-- /input-group -->
                                            <?php endforeach;?>
                                            <div class="input-group order-labl form-group">
                                                <span class="input-group-addon">
                                                  <input type="radio" name="paymentoption" value="sod" checked>
                                                </span>
                                                <label for="sod">Settlement on Delivery</label>
                                            </div><!-- /input-group -->
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
        jQuery('.showPromocodeSection').click(function(){
            var eleID=jQuery(this).attr('data-eleid');
            
            jQuery('#order-coupon'+eleID).show();
            jQuery('.ApplyCouponActionSection'+eleID).show();
            jQuery(this).html('');
        });
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
            myJsMain.commonFunction.showPleaseWait();
            var orderId=jQuery(this).attr('alt');
            var obj = $('input[id="order-coupon'+orderId+'"]');            
            var cpn = obj.val();
            if(!cpn){ 
                myJsMain.commonFunction.hidePleaseWait();
                $('div.js-message').html('<div class="alert alert-danger">Please enter your promo code!</div>');
             $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
            } else {                
                $.post( myJsMain.baseURL+'shopping/ajax_single_order_set_promo/', {
                    orderId: obj.attr('data-order'),
                    promocode: cpn,
                    orderId: orderId
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
                        $('td.js-show-disc-amt').html('<strong><?php echo $currencySymbol;?> '+data.content.couponAmount+'</strong>');
                        $('.js-tax-total').html('<strong><?php echo $currencySymbol;?> '+data.content.tax+'</strong>');
                        $('.js-sub-total').html('<strong><?php echo $currencySymbol;?> '+data.content.grandTotal+'</strong>');
                        var htmlCoupon = "<button type=\"button\" class=\"btn btn-info btn-xs remove-coupon-from-order-"+orderId+"\" style=\"width:75px\"><i class=\"fa fa-tags\"></i>"+cpn+"</button><button type=\"button\" class=\"btn btn-danger btn-xs remove-coupon-from-order remove-coupon-from-order-"+orderId+"\" data-id=\""+orderId+"\"><i class=\"fa fa-times-circle\"></i></button>";
                        jQuery('.applyCouponElemtnForRemove'+orderId).html(htmlCoupon);
                        obj.val('');
                    }
                    
                }, 'json' ); 
            }
        });
        
        jQuery("body").delegate('.remove-coupon-from-order', "click", function(e){
            orderId= jQuery(this).attr('data-id');
            e.preventDefault();
            myJsMain.commonFunction.showPleaseWait();
            $.post( myJsMain.baseURL+'shopping/ajax_single_order_remove_promo/', {
                    orderId: orderId
                },
                function(data){ 
                    myJsMain.commonFunction.hidePleaseWait();
                    if(data.msg=='ok'){
                        jQuery('.applyCouponElemtnForRemove'+orderId).html('');
                        location.href=myJsMain.baseURL+'shopping/single-checkout/';
                    }else{
                            $('div.js-message').html('<div class="alert alert-danger">Unknown error arises to remove the Coupon Code.</div>');
                            $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
                    }
                }, 'json' ); 
        });
        
        jQuery("body").delegate('a.js-proceed-payment', "click", function(e){
            e.preventDefault();
            $('.nav-tabs a[href="#Payment"]').trigger( "click" ); 
        });
        
        
        jQuery("body").delegate('.js-single-cart-remove', "click", function(e){
            e.preventDefault();
                         
            var cartId = jQuery(this).attr('data-cartid');
            jQuery.post( myJsMain.baseURL+'shopping/remove-single-cart/', {
                cartId: cartId
            },
            function(data){ 
                if(data.contents){
                    //jQuery('tr#'+cartId).remove();
                    var item = "(<?=count($this->cart->contents())-1?> Item<?php if(count($this->cart->contents())-1 > 1): echo 's';endif;?>)";
                    jQuery('span.js-cart-item').text(item);                    
                    jQuery('tr#'+cartId).remove();  
                    //if(data.reload){
                       // window.location.href = myJsMain.baseURL+'shopping/single-checkout/';
                   // } else {
                        window.location.href = myJsMain.baseURL+'shopping/my-cart/';
                   // }
                }
            }, 'json' );
        }); 
        
        jQuery("body").delegate('a.js-order-payment', "click", function(e){
            myJsMain.commonFunction.showPleaseWait();
            $('#single_order_payment_option')[0].submit();
            /*e.preventDefault();
            var cartId = jQuery(this).attr('data-cartid');
            jQuery.post( myJsMain.baseURL+'shopping/ajax_process_single_payment/', {
                cartId: cartId
            },
            function(data){
                myJsMain.commonFunction.hidePleaseWait();
                if(data.url){
                    window.location.href = data.url;
                }

                if(data.error){
                    $('div.js-payment-message').html(data.error);
                    $('div.js-payment-message').fadeIn(300,function() { setTimeout( '$("div.js-payment-message").fadeOut(300)', 15000 ); });
                }
            }, 'json' );*/
        });
    });
</script>

<?php echo $footer;?>