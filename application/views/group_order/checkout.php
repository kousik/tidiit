<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo $html_heading; echo $header;?>
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
                            <li  class="active"><a href="#Confirm" data-toggle="tab" class="js-active-shipping"><span class="number">1 </span>Confirm Address</a></li>
                            <li><a href="#Review" data-toggle="tab" data-shipping="<?php if($userShippingDataDetails->address):?>true<?php endif;?>" class="js-shipping"><span class="number">2 </span>Review Order</a></li>
                            <li><a href="#Payment" data-toggle="tab" data-shipping="<?php if($userShippingDataDetails->address):?>true<?php endif;?>" class="js-shipping"><span class="number">3 </span>Make Payment</a></li>
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
                                            <input type="text" class="form-control" id="firstName" placeholder="" name="firstName" value="<?=$userShippingDataDetails->firstName;?>">
                                        </div>
                                      </div>
                                      <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Last Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="lastName" placeholder="" name="lastName" value="<?=$userShippingDataDetails->lastName;?>">
                                        </div>
                                      </div>
                                        <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Phone</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="phone" placeholder="" name="phone" value="<?=$userShippingDataDetails->contactNo;?>">
                                        </div>
                                      </div>
                                      
                                      <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Address</label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" rows="3" name="address" id="address"><?=$userShippingDataDetails->address;?></textarea>
                                        </div>
                                      </div>
                                      
                                      <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Country</label>
                                        <div class="col-sm-9 countryElementPara">
                                          <select class="form-control" name="countryId" id="countryId" value="">
                                              <option value="">Select</option>
                                              <?php foreach($countryDataArr AS $k) { ?>
                                                  <option value="<?php echo $k->countryId; ?>" <?php if ($k->countryId == $userShippingDataDetails->countryId) { ?>selected<?php } ?>><?php echo $k->countryName; ?></option>
                                                  <?php }?>
                                            </select>
                                        </div>
                                      </div>
                                        <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">City</label>
                                        <div class="col-sm-9 cityElementPara">
                                            <?php if($userShippingDataDetails->cityId==""){?>
                                                            <select class="form-control" name="cityId" id="cityId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                            </select>
                                                            <?php }else{?> 
                                                            <select class="form-control" name="cityId" id="cityId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                                <?php foreach($cityDataArr As $k){?>
                                                                <option value="<?php echo $k->cityId;?>" <?php if($k->cityId==$userShippingDataDetails->cityId){?>selected<?php }?>><?php echo $k->city;?></option>
                                                                <?php }?> 
                                                            </select>
                                                            <?php }?>
                                        </div>
                                      </div>
                                        <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Zip</label>
                                        <div class="col-sm-9 zipElementPara">
                                          <?php if($userShippingDataDetails->zipId==""){?>
                                                            <select class="form-control" name="zipId" id="zipId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                            </select>
                                                            <?php }else{ ?>
                                                            <select class="form-control" name="zipId" id="zipId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                                <?php foreach($zipDataArr AS $k){?>
                                                                <option value="<?php echo $k->zipId;?>" <?php if($k->zipId==$userShippingDataDetails->zipId){?>selected<?php }?>><?php echo $k->zip; ?></option>
                                                                <?php }?>
                                                            </select>
                                                            <?php }?>
                                        </div>
                                      </div>
                                        <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Locality</label>
                                        <div class="col-sm-9 localityElementPara">
                                          <?php if($userShippingDataDetails->localityId==""){?>
                                                            <select class="form-control" name="localityId" id="localityId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                            </select>
                                                            <?php }else{?>
                                                            <select class="form-control" name="localityId" id="localityId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                                <?php foreach($localityDataArr AS $k){?>
                                                                <option value="<?php echo $k->localityId;?>" <?php if($k->localityId==$userShippingDataDetails->localityId){?>selected<?php }?>><?php echo $k->locality;?></option>
                                                                <?php }?>
                                                            </select>
                                                            <?php }?>
                                        </div>
                                      </div>
                                     <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Landmark</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="landmark" name="landmark" placeholder="" value="<?=$userShippingDataDetails->landmark;?>">
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
                                        <?php if($userShippingDataDetails->address):?>
                                            <?=$userShippingDataDetails->firstName;?> <?=$userShippingDataDetails->lastName;?><br>
                                            <?=$userShippingDataDetails->address;?><br>
                                            <?=$userShippingDataDetails->contactNo;?><br>
                                            <?php if($userShippingDataDetails->landmark):?>
                                                <b>Landmark:</b><?=$userShippingDataDetails->landmark;?>
                                                    <?php endif;?><br>
                                        <?php endif;?>    
                                        </div>
                                        <div class="clearfix"></div>
                                        
                                      </div>
                                      <div class="panel-footer">
                                        <button type="button" class="btn btn-default shipping-continue" data-shipping="<?php if($userShippingDataDetails->address):?>true<?php endif;?>">Continue</button>
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
                                                <th style="width:40%">Product</th>
                                                <th style="width:12%">Price</th>
                                                <th style="width:8%">Quantity</th>
                                                <th style="width:15%" class="text-center">Subtotal</th>
                                                <th style="width:10%" align="right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td data-th="Product">
                                                    <div class="row">
                                                        <div class="col-sm-3 product-img"><img src="http://placehold.it/100x100" alt="..." class="img-responsive"></div>
                                                        <div class="col-sm-9 product-details">
                                                            <h4 class="nomargin">Product 1</h4>
                                                            <p>Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Lorem ipsum dolor sit amet.</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td data-th="Price">$1.99</td>
                                                
                                                <td data-th="Quantity">
                                                    4545454
                                                </td>
                                                <td data-th="Subtotal" class="text-center">1.99</td>
                                                <td class="actions" data-th="" align="right">
                                                    
                                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></button>								
                                                </td>
                                            </tr>
                                      
                                        	
                                            
                                            
                                        
                                            <tr>
                                                <td colspan="6" valign="middle">
                                                   <div class="pincode-check-enable row">
                                                    <div class="col-sm-3 col-xs-12"><span class="pull-left pincode-TEXT"> Have a promo code? Apply </span></div> 
                                                    <div class="col-sm-3 col-xs-12">
                                                    	<a href="#">Apply</a>
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
                                                <td colspan="4">Delivery and payment options can be selected later</td>
                                                <td class="text-center">Free</td>
                                                <td></td>
                                            </tr>
                                            <tr class="visible-xs">
                                                <td class="text-center"><strong>Total 1.99</strong></td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td colspan="2" class="hidden-xs"></td>
                                                <td class="hidden-xs text-center"><strong>Total $1.99</strong></td>
                                                <td colspan="2"><a href="#" class="btn btn-success btn-block">PROCEED TO PAYMENT <i class="fa fa-angle-right"></i></a></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                    </div>
                                    
                                    
                              
                            </div>
                            
                            <div class="tab-pane fade" id="Payment">
                            	
                            
                                   <h3 class="log-title">Make Payment</h3>
                                   <div class="small-font-text form-group">We have a host of payment options to select from</div>
                                   
                                    
                                    <div class="cart-container-table">
                                        Payment Blank Option
                                    </div>
                                    
                                    
                              
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
                console.log('dgdgd');
                $('.nav-tabs a[href="#Review"]').trigger( "click" ); 
            }
        });
        
       jQuery('a[data-toggle="tab"]').on('hidden.bs.tab', function (e) {
        e.target // newly activated tab
        e.relatedTarget // previous active tab
        if(!$(e.relatedTarget).data('shipping')){
            $('.nav-tabs a[href="#Confirm"]').trigger( "click" );
        }
      });
    });
</script>

<?php echo $footer;?>