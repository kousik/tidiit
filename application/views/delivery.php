<?php echo $html_heading; echo $header;?>
<link href="<?php echo SiteCSSURL;?>jquery.datetimepicker.css" rel="stylesheet">
<script src="<?php echo SiteJSURL;?>jquery.datetimepicker.full.min.js"></script>
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
<div id="autoLoadHowItWorks"></div>
<?php echo $common_how_it_works;?>
<?php echo $feedback;?>
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
        <h4 class="modal-title" id="myModalLabel">Delivery Update</h4>
      </div>
        <form action="#" method="post" name="outForDeliveryForm" class="form-horizontal" id="outForDeliveryForm" enctype="multipart/form-data"> 
            <div class="modal-body">
                <div class=""> 
                    <div class="">
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
                                            <td>Receiver Name</td>
                                            <td>:</td>
                                            <td><input id="receiveStaffName" name="receiveStaffName" type="text" class="form-control" required >
                                                <div>
                                                    <label id="receiveStaffName-error" class="error" for="receiveStaffName"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Receiver Contact No</td>
                                            <td>:</td>
                                            <td><input id="receiveStaffContactNo" name="receiveStaffContactNo" type="text" class="form-control" required >
                                                <div>
                                                    <label id="receiveStaffContactNo-error" class="error" for="receiveStaffContactNo"></label>
                                                </div>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Receive Date Time</td>
                                            <td>:</td>
                                            <td><input id="receiveDateTime" name="receiveDateTime" type="text" class="form-control" required >
                                                <div>
                                                    <label id="receiveDateTime-error" class="error" for="receiveDateTime"></label>
                                                </div>
                                            </td>
                                        </tr>
					<tr>
                                            <td>Photo of time of delivery1</td>
                                            <td>:</td>
                                            <td><input id="photo1" name="photo1" type="file" class="form-control" required style="padding:0px;">
                                                <div>
                                                    <label id="photo1-error" class="error" for="photo1"></label>
                                                </div>
                                            </td>
                                        </tr>
										<tr>
                                            <td>Photo of time of delivery2</td>
                                            <td>:</td>
                                            <td><input id="photo2" name="photo2" type="file" class="form-control" required style="padding:0px;" >
                                                <div>
                                                    <label id="photo2-error" class="error" for="photo2"></label>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                             <td style="width: 35%;">&nbsp;</td>
                                             <td style="width: 5%;">&nbsp;</td>
                                             <td style="width: 60%;">
                                                 <input type="submit" name="deliverySubmit" id="deliverySubmit" value="Submit" class="btn btn-default col-md-5"/>	
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
        jQuery('#receiveDateTime').datetimepicker({format:'d-m-Y H:i',mask:true});
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
                askBeforeSubmitOutForDelivery('Tidiit Order Delivery','Are you sure to submit the data ?',200,jQuery(this),myJsMain.baseURL+'ajax/submit_delivery',outForDeliveryFormCallback);;
            }
        });
        
        var myCartId='<?php echo time();?>';
        jQuery('#orderId').on('blur',function(){
            jqOut=jQuery(this);
            if(jqOut.val().trim()==''){
                myJsMain.commonFunction.tidiitAlert('Tidiit Order Delivery','Please enter the value for order id',200);
                return false;
            }
            myJsMain.commonFunction.showPleaseWait();
            $.post( myJsMain.baseURL+'ajax/check_order_id_for_logistic_delivery/', {
                orderId: jqOut.val(),cartId:myCartId
            },
            function(data){
                myJsMain.commonFunction.hidePleaseWait();
                if(data.result=='bad'){
                    myJsMain.commonFunction.tidiitAlert('Tidiit Order Delivery',data.msg,200);
                    jqOut.val('');
                }else{
                    if(data.msg!='')
                        myJsMain.commonFunction.tidiitAlert('Tidiit Order Delivery',data.msg,200);
                }
            }, 'json' );
        });
		
		jQuery('#logisticsId').on('blur',function(){
            jqOut=jQuery(this);
            if(jqOut.val().trim()==''){
                myJsMain.commonFunction.tidiitAlert('Tidiit Order Delivery','Please enter the value for Logistics Tidiit Sign ID',200);
                return false;
            }
            myJsMain.commonFunction.showPleaseWait();
            $.post( myJsMain.baseURL+'ajax/check_logistics_id_for_logistic/', {
                logisticsId: jqOut.val(),cartId:myCartId
            },
            function(data){
                myJsMain.commonFunction.hidePleaseWait();
                if(data.result=='bad'){
                    myJsMain.commonFunction.tidiitAlert('Tidiit Order Delivery',data.msg,200);
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
                    //jQuery("#outForDeliveryForm").submit(function(e){
                        //var formData = new FormData(jQuery('#outForDeliveryForm')[0]);
                        //fData=jQuery('#outForDeliveryForm').serialize();
                        //console.log(formData);
                        myJsMain.commonFunction.ajaxSubmit($this,url,calBackFun);
                        //$.post( url, fData,calBackFun, 'json' );
                        /*jQuery.ajax({
                            url: url,
                            type: 'POST',
                            data: formData,
                            dataType:'json',
                            mimeType:"multipart/form-data",
                            contentType: false,
                            cache: false,
                            processData:false,
                            success: calBackFun
                        });*/
                    //});

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
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
            location.href='<?php echo BASE_URL;?>';
        }
    }
</script>