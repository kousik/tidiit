<?php echo $html_heading; echo $header;?>
<style type="text/css">
    @media screen and (min-width: 768px) {.modal-dialog {width: 700px; /* New width for default modal */}.modal-sm {width: 350px; /* New width for small modal */}}
    @media screen and (min-width: 992px) {.modal-lg {width: 750px; /* New width for large modal */}}
    .modal {overflow-y: scroll;}
</style>
        <div class="categrs_bannr">
            <div class="row">
                <div class="col-md-3 padng_right_none">
                        <?php echo $main_menu;?>
                </div>
                <div class="col-md-6 col-sm-7 padng_left_none">
                    <?php if(!empty($slider1)):?>
                <!-- start banner section -->
                    <div class="banner_sec">
                        <ul id="demo1">
                            <?php foreach ($slider1 AS $k): ?>
                            <li>
                                <img src="<?php echo SiteResourcesURL.'banner/landing/'.$k->image;?>" />
                                <span>&nbsp;</span>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <!-- end banner section -->
                    <?php endif;
                    if(!empty($slider2)):?>
                    <!-- start banner section -->
                    <div class="banner_sec">
                        <ul id="demo2">
                            <?php foreach ($slider2 AS $k): ?>
                            <li>
                                <img src="<?php echo SiteResourcesURL.'banner/landing/'.$k->image;?>" />
                                <span>&nbsp;</span>
                            </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <?php endif;?>
                    <!-- end banner section -->
                </div>
                <div class="col-md-3 col-sm-5">
                    <div class="view second-effect">
                        <img src="<?php echo SiteImagesURL;?>daily_deals.png" class="img-responsive" />
                        <div class="mask">
                                <p class="cntnt">
                                UP TO 70% OFF
                                <a href="#">Shop Now &nbsp;<i class="fa fa-caret-right"></i></a>
                            </p>
                        </div>
                    </div>

                    <div class="view second-effect">
                        <img src="<?php echo SiteImagesURL;?>offer_img.png" class="img-responsive" />
                        <div class="mask">
                                <p class="cntnt">
                                UP TO 70% OFF
                                <a href="#">Shop Now &nbsp;<i class="fa fa-caret-right"></i></a>
                            </p>
                        </div>
                    </div>

                    <div class="view second-effect">
                        <img src="<?php echo SiteImagesURL;?>offer_img1.png" class="img-responsive" />
                        <div class="mask">
                                <p class="cntnt">
                                UP TO 70% OFF
                                <a href="#">Shop Now &nbsp;<i class="fa fa-caret-right"></i></a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
        
<button class="inline btn btn-primary howItWork" data-toggle="modal" data-target=".bs-example-modal-lg" href="#inline_content">HOW IT WORKS</button>

        
<!-- This contains the hidden content for inline calls -->
<div class="modal fade bs-example-modal-lg2" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg opn_box" style='padding:10px; background:#fff;  border-radius:10px;'>
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <div class="container">
            <div class="banner">
                <img src="<?php echo SiteImagesURL;?>works_img.jpg" class="img-responsive" />
                <h4>A Better Way to Purchase For Your Business</h4>
            </div>
            <h2>HOW IT WORKS</h2>
            <div class="row">
                <div class="col-md-4 col-sm-4">
                        <div class="work_box">
                        <img src="<?php echo SiteImagesURL;?>work_img.png" />
                        <p>Tell us what you need and we will send your request to all relevant suppliers </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                        <div class="work_box">
                        <img src="<?php echo SiteImagesURL;?>work_img1.png" />
                        <p>Receive and compare multiple customized quotes within 48 hours </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                        <div class="work_box">
                        <img src="<?php echo SiteImagesURL;?>work_img2.png" />
                        <p>Negotiate and finalize the deal directly with the suppliers, knowing that you have made the right choice </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="autoLoadHowItWorks"></div>
<a id="button1" class="quick_cntct" href="javascript:void(0)">FEEDBACK</a>
        
<div class="side_contact" id="t1">
    <h3>Feedback</h3>
    <form method="post" action="" name="#" id="#" class="feed_back">
        <input type="text" class="required" placeholder="Enter Your Name" id="myname" name="myname">
        <input type="text" class="required" placeholder="Enter Your Phone" id="myphone" name="myphone">
        <input type="text" class="required email" placeholder="Enter Your Email Id" id="myemail" name="myemail">
        <textarea class="required" placeholder="Enter Your Message" rows="" cols="" name="message"></textarea>
        <input type="submit" value="Submit" name="sbmt">
    </form>
</div>

<article>
    <div class="container">
        <div class="bst_sllng">
            <h2>Best Selling Item</h2>
            <div class="row">
                <div id="demo">
                    <div id="owl-demo" class="owl-carousel">
                        <?php foreach($bestSelllingItem AS $k){ //pre($k);die;?>
                        <div class="item">
                            <div class="prodct_box" <?php if($k->qty<$k->minQty){ echo 'style="margin:0 !important;margin-bottom:30px !important;"';}?>>
                                <?php if($k->qty<$k->minQty):?>
                                <a href="javascript:void(0);">
                                <?php else :?>
                                <a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">
                                <?php endif;?>    
                                    <img src="<?php echo HOME_LISTING.$k->image;?>" class="img-responsive" />
                                    <?php if($k->qty<$k->minQty):?>
                                    <h4>Out Of Stock</h4>
                                    <?php else:?>
                                    <div class="ch-info">
                                        <h3><i class="fa fa-truck"></i> &nbsp;Add to Truck</h3>
                                    </div>
                                    <?php endif;?>
                                </a>
                                <p><?php echo $k->title;?></p>
                                <ul class="star">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                </ul>
                                <p><?php echo $k->lowestPrice.' - '.$k->heighestPrice;?></p>
                                <?php /*<p><a href="<?php echo BASE_URL.str_replace('+','-',urlencode(my_seo_freindly_url($k->title))).'+'. base64_encode($k->productId);?>">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p> */?>
                                <p>
                                    <?php if($k->qty<$k->minQty):?>
                                    <a href="javascript:void(0);">View Details &nbsp;<i class="fa fa-caret-right"></i></a>
                                    <?php else:?>
                                    <a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">View Details &nbsp;<i class="fa fa-caret-right"></i></a>
                                    <?php endif;?>
                                </p>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bst_sllng">
            <h2>New Arrivals</h2>
            <div class="row">
                <div id="demo">
                    <div id="owl-demo1" class="owl-carousel">
                        <?php foreach($bestSelllingItem AS $k){ //pre($k);die;?>
                        <div class="item">
                            <div class="prodct_box" <?php if($k->qty<$k->minQty){ echo 'style="margin:0 !important;margin-bottom:30px !important;"';}?>>
                                <?php if($k->qty<$k->minQty):?>
                                <a href="javascript:void(0);">
                                <?php else :?>
                                <a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">
                                <?php endif;?>    
                                    <img src="<?php echo HOME_LISTING.$k->image;?>" class="img-responsive" />
                                    <?php if($k->qty<$k->minQty):?>
                                    <h4>Out Of Stock</h4>
                                    <?php else:?>
                                    <div class="ch-info">
                                        <h3><i class="fa fa-truck"></i> &nbsp;Add to Truck</h3>
                                    </div>
                                    <?php endif;?>
                                </a>
                                <p><?php echo $k->title;?></p>
                                <ul class="star">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                </ul>
                                <p><?php echo $k->lowestPrice.' - '.$k->heighestPrice;?></p>
                                <p>
                                    <?php if($k->qty<$k->minQty):?>
                                    <a href="javascript:void(0);">View Details &nbsp;<i class="fa fa-caret-right"></i></a>
                                    <?php else:?>
                                    <a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">View Details &nbsp;<i class="fa fa-caret-right"></i></a>
                                    <?php endif;?>
                                </p>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bst_sllng">
            <h2>Featured Products</h2>
            <div class="row">
                <div id="demo">
                    <div id="owl-demo2" class="owl-carousel">
                        <?php foreach($bestSelllingItem AS $k){ //pre($k);die;?>
                        <div class="item">
                            <div class="prodct_box" <?php if($k->qty<$k->minQty){ echo 'style="margin:0 !important;margin-bottom:30px !important;"';}?>>
                                <?php if($k->qty<$k->minQty):?>
                                <a href="javascript:void(0);">
                                <?php else :?>
                                <a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">
                                <?php endif;?>    
                                    <img src="<?php echo HOME_LISTING.$k->image;?>" class="img-responsive" />
                                    <?php if($k->qty<$k->minQty):?>
                                    <h4>Out Of Stock</h4>
                                    <?php else:?>
                                    <div class="ch-info">
                                        <h3><i class="fa fa-truck"></i> &nbsp;Add to Truck</h3>
                                    </div>
                                    <?php endif;?>
                                </a>
                                <p><?php echo $k->title;?></p>
                                <ul class="star">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                </ul>
                                <p><?php echo $k->lowestPrice.' - '.$k->heighestPrice;?></p>
                                <p>
                                    <?php if($k->qty<$k->minQty):?>
                                    <a href="javascript:void(0);">View Details &nbsp;<i class="fa fa-caret-right"></i></a>
                                    <?php else:?>
                                    <a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">View Details &nbsp;<i class="fa fa-caret-right"></i></a>
                                    <?php endif;?>
                                </p>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bst_sllng">
            <h2>Brand Zone</h2>
            <div class="brand_zne">
                <div id="demo">
                    <div id="owl-demo3" class="owl-carousel">
                        <?php foreach($brandZoneArr AS $k){
                            if($k->brandImage==""){$src=SiteImagesURL.'no-image.png';}else{$src=SiteResourcesURL.'brand/original/'.$k->brandImage;}?>
                        <div class="item"><a href="#"><img src="<?php echo $src;?>" alt="<?php echo $k->title;?>" title="<?php echo $k->title;?>" /></a></div>
                        <?php }?>
                    </div>
                    <div class="customNavigation">
                        <a class="btn prev"><i class="fa fa-angle-left"></i></a>
                        <a class="btn next"><i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="thre_box">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="abot_box">
                        <img src="<?php echo SiteImagesURL;?>about_img.png" />
                        <a href="<?php echo BASE_URL.'content/about-us/'.  base64_encode(1);?>"><h3>About Tidiit</h3></a>
                        <p>Lorem Ipsum is simply dummy text of the printing and type setting industry.</p>
                        <p><a href="<?php echo BASE_URL.'content/about-us/'.  base64_encode(1);?>">Learn More..</a></p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                        <div class="abot_box">
                        <img src="<?php echo SiteImagesURL;?>faq_img.png" />
                        <a href="<?php echo BASE_URL.'seller-faq';?>"><h3>New Sellers FAQ</h3></a>
                        <p><?php if(!empty($sellerDataArr)){echo $sellerDataArr[0]->question;}?></p>
                        <p><a href="<?php echo BASE_URL.'seller-faq';?>">Learn More..</a></p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                        <div class="abot_box brdr_rit_none">
                        <img src="<?php echo SiteImagesURL;?>prtctn_img.png" />
                        <a href="<?php echo BASE_URL.'buyer-faq';?>"><h3>New Buyers FAQ</h3></a>
                        <p><?php if(!empty($buyerDataArr)){echo $buyerDataArr[0]->question;}?></p>
                        <p><a href="<?php echo BASE_URL.'buyer-faq';?>">Learn More..</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
<?php echo $footer;?>
<!-- Modal -->
<div class="modal fade" id="myModalOutForDelivery" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Out For Delivery / Pre-Alert</h4>
      </div>
        <form action="#" method="post" name="outForDeliveryForm" class="form-horizontal" id="outForDeliveryForm"> 
            <div class="modal-body">
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
                                        <td>Select out for delivery type</td>
                                        <td>:</td>
                                        <td><select name="outForDeliveryType" id="outForDeliveryType">
                                                <option value="">Select</option>
                                                <option value="preAlert">Pre-alert</option>
                                                <option value="outForDelivery">Out for delivery</option>
                                            </select>
                                            <div>
                                                <label id="outForDeliveryType-error" class="error" for="outForDeliveryType"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="pre_alert" style="display:none;">
                                        <td>Delivery days</td>
                                        <td>:</td>
                                        <td><select name="outForDeliveryDays" id="outForDeliveryDays">
                                                <option value="">Select</option>
                                                <!--<option value="1">Today</option> -->
                                                <option value="2">Tomorrow</option>
                                                <option value="3">After 2 days</option>
                                                <option value="4">After 3 days</option>
                                            </select>
                                            <div>
                                                <label id="outForDeliveryDays-error" class="error" for="outForDeliveryDays"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Enter the OrderID</td>
                                        <td>:</td>
                                        <td><input id="orderId" name="orderId" type="text" class="form-control" required >
                                            <div>
                                                <label id="orderId-error" class="error" for="orderId"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Logistics Tidiit Sign ID</td>
                                        <td>:</td>
                                        <td><input id="logisticsId" name="logisticsId" type="text" class="form-control" required >
                                            <div>
                                                <label id="logisticsId-error" class="error" for="logisticsId"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Staff Name</td>
                                        <td>:</td>
                                        <td><input id="deliveryStaffName" name="deliveryStaffName" type="text" class="form-control" required >
                                            <div>
                                                <label id="deliveryStaffName-error" class="error" for="deliveryStaffName"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Staff Contact No</td>
                                        <td>:</td>
                                        <td><input id="deliveryStaffContactNo" name="deliveryStaffContactNo" type="text" class="form-control" required >
                                            <div>
                                                <label id="deliveryStaffContactNo-error" class="error" for="deliveryStaffContactNo"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Delivery Staff Email</td>
                                        <td>:</td>
                                        <td><input id="deliveryStaffEmail" name="deliveryStaffEmail" type="email" class="form-control" required >
                                            <div>
                                                <label id="deliveryStaffEmail-error" class="error" for="deliveryStaffEmail"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                         <td style="width: 35%;">&nbsp;</td>
                                         <td style="width: 5%;">&nbsp;</td>
                                         <td style="width: 60%;">
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
        <div class="modal-footer">&nbsp;</div>
      </form>    
    </div>
  </div>
</div>
<!-- /.modal -->
<script type="text/javascript">
    jQuery(document).ready(function(){
        var oldSubmitStatusTidiitConfirm1=false;
        jQuery('#outForDeliveryForm').validate();
        jQuery('#myModalOutForDelivery').modal('show');
        jQuery('#outForDeliveryType').on('change',function(){
            if(jQuery(this).val()=='preAlert'){
                jQuery('#pre_alert').fadeIn('show');
            }else{
                jQuery('#pre_alert').fadeOut('show');
            }
        });
        
        jQuery('#outForDeliveryForm').submit(function(e) { 
            e.preventDefault();
            if (jQuery(this).valid()) {
                askBeforeSubmitOutForDelivery('Tidiit out for deliery','Are you sure to submit the data ?',200,jQuery(this),myJsMain.baseURL+'ajax/submit_out_for_delivery',outForDeliveryFormCallback);;
            }
        });
        
        var myCartId='<?php echo time();?>';
        jQuery('#orderId').on('blur',function(){
            jqOut=jQuery(this);
            if(jqOut.val().trim()==''){
                myJsMain.commonFunction.tidiitAlert('Tidiit Out For Delivery','Please enter the value for order id',200);
                return false;
            }
            myJsMain.commonFunction.showPleaseWait();
            $.post( myJsMain.baseURL+'ajax/check_order_id_for_logistic/', {
                orderId: jqOut.val(),cartId:myCartId
            },
            function(data){
                myJsMain.commonFunction.hidePleaseWait();
                if(data.result=='bad'){
                    myJsMain.commonFunction.tidiitAlert('Tidiit Out For Delivery',data.msg,200);
                    jqOut.val('');
                }
            }, 'json' );
        });
		jQuery('#logisticsId').on('blur',function(){
            jqOut=jQuery(this);
            if(jqOut.val().trim()==''){
                myJsMain.commonFunction.tidiitAlert('Tidiit Out For Delivery','Please enter the value for Logistics Tidiit Sign ID',200);
                return false;
            }
            myJsMain.commonFunction.showPleaseWait();
            $.post( myJsMain.baseURL+'ajax/check_logistics_id_for_logistic/', {
                logisticsId: jqOut.val(),cartId:myCartId
            },
            function(data){
                myJsMain.commonFunction.hidePleaseWait();
                if(data.result=='bad'){
                    myJsMain.commonFunction.tidiitAlert('Tidiit Out For Delivery',data.msg,200);
                    jqOut.val('');
                }
            }, 'json' );
        });
    });
    
    function askBeforeSubmitOutForDelivery(boxTitle,confirmMessaage,height,$this,url,calBackFun){
        if(height==0){
            height=175;
        }
        jQuery('#dialog-confirm-message-text').text(confirmMessaage);
        jQuery( "#dialog-confirm" ).dialog({
            resizable: false,
            height:height,
            width:450,
            modal: true,
            title:boxTitle,
            dialogClass: 'success-dialog',
            buttons: {
                "OK": function() {
                    jQuery( this ).dialog( "close" );
                    myJsMain.commonFunction.showPleaseWait();
                    myJsMain.commonFunction.ajaxSubmit($this,url,calBackFun);
                },
                Cancel: function() {
                    jQuery( this ).dialog( "close" );
                }
            }
        });
    }
    
    function outForDeliveryFormCallback(resultData){
        myJsMain.commonFunction.hidePleaseWait();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            userMsg=resultData.msg;
            if(resultData.paymentType=='settlementOnDelivery'){
                userMsg=userMsg+" Please confirm payment before delivery.";
            }
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',userMsg,200);
            location.href='<?php echo BASE_URL;?>';
        }
    }
</script>