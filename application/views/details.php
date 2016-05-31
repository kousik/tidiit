<?php echo $html_heading; echo $header; $currencySymbol=($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')=="IN") ? '<i class="fa fa-rupee"></i>' :'KSh';
//$productPageTypeArr=$this->config->item('productPageTypeArr');
$mobileBoxContents=$this->config->item('mobileBoxContents');
$mobileColor=$this->config->item('mobileColor');
$mobileDisplayResolution=$this->config->item('mobileDisplayResolution');
$mobileConnectivity=$this->config->item('mobileConnectivity');
$mobileOS=$this->config->item('mobileOS');
$mobileProcessorCores=$this->config->item('mobileProcessorCores');
$mobileBatteryType=$this->config->item('mobileBatteryType');
$mobileProcessorBrand=$this->config->item('mobileProcessorBrand');

$mobileCamera=$this->config->item('mobileCamera');
$ramData=$this->config->item('ramData');
$internalMemory=$this->config->item('internalMemory');
$expandableMemory=$this->config->item('expandableMemory');
$warrantyDuration=$this->config->item('warrantyDuration');
//$priceRangeSettingsArr=$this->config->item('priceRangeSettings');
//$priceRangeSettingsDataArr=$priceRangeSettingsArr[$productPageType];
?>
    </div>
</header>
<link href="<?php echo SiteCSSURL;?>rating_simple.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SiteJSURL;?>rating_simple.js"></script> 
<!-- link to magiczoom.css file -->
<link href="<?php echo SiteCSSURL;?>magiczoom.css" rel="stylesheet" type="text/css" media="screen"/>
<!-- link to magiczoom.js file --> 
<script src="<?php echo SiteJSURL;?>magiczoom.js" type="text/javascript"></script>

<script type="text/javascript">
jQuery(document).ready(function(){
    var is_loged_in = '<?=$is_loged_in?>';
  jQuery("#rating_simple2").webwidget_rating_simple({
        rating_star_length: '5',
        rating_initial_value: '',
        rating_function_name: '',//this is function name for click
        directory: '<?php echo SiteJSURL;?>'
    });  
    
    jQuery('.price2').hide();
    //Examples of how to assign the Colorbox event to elements
    jQuery('.category_inner').hover(function(){
                jQuery('.cate_cont').toggleClass('seenMenu','hiddenMenu'); //Adds 'a', removes 'b' and vice versa
       });

    jQuery("#accordion > li > div").click(function(){
        if(false == $(this).next().is(':visible')) {
            jQuery('#accordion ul').slideUp(300);
        }
        jQuery(this).next().slideToggle(300);
    });

    jQuery('#accordion ul:eq(0)').show();
    
    jQuery('#orderTypeId').hide();		
    var owl = $("#owl-demo4");		
    owl.owlCarousel({		
          items : 5, //10 items above 1000px browser width
          itemsDesktop : [1024,4], //5 items between 1000px and 901px
          itemsDesktopSmall : [900,3], // 3 items betweem 900px and 601px
          itemsTablet: [600,2], //2 items between 600 and 0;
          itemsMobile : 2, // itemsMobile disabled - inherit from itemsTablet option
          pagination : false,
          autoPlay: true,
          navigation : false
    });

    // Custom Navigation Events
    jQuery(".topseller_next").click(function(){
          owl.trigger('owl.next');
    });
    jQuery(".topseller_prev").click(function(){
          owl.trigger('owl.prev');
    });

    jQuery(".spec-title" ).click(function() {
        //console.log('i am hit');
        $(this).closest('div').next('.spec-body').toggle("slow");
    });
    
    jQuery('#add-cart-button-id').click(function(){
        var productPriceIdData=jQuery('input:radio[name=selectPackege]:checked').val();
        if(productPriceIdData==undefined){
            myJsMain.commonFunction.tidiitAlert('Tidiit Validate System',"Please select price for the product.",140);
            $('.multiselect-modal-sm').modal('hide');
        }else{
            if(!is_loged_in){
                myJsMain.commonFunction.tidiitAlert('Tidiit Validate System',"Please sign in or sign up first for buy this product.",140);
                $('.multiselect-modal-sm').modal('hide');
                $( "a.signIn" ).trigger( "click" );
                return false;
            }
            $('.multiselect-modal-sm').modal('show');
        }
    });
    
    jQuery('.add-to-truck-process-btn').click(function(){
        var productPriceIdData=jQuery('input:radio[name=selectPackege]:checked').val();
        jQuery('#prorductPriceId').val(productPriceIdData);
        var order_type=jQuery('input:radio[name=ordertype]:checked').val();
        myJsMain.commonFunction.showPleaseWait();
        jQuery.post( myJsMain.baseURL+'shopping/check_old_order_type/', {
                orderType: order_type
            },
            function(data){ 
                myJsMain.commonFunction.hidePleaseWait();
                if(data.contents=="1"){
                    /// allow to process the product to cart
                    if(order_type=='group'){
                        jQuery('#add_to_truck_process_form').attr('action','<?php echo BASE_URL.'shopping/add-group-order/';?>');
                    }else{
                        jQuery('#add_to_truck_process_form').attr('action','<?php echo  BASE_URL.'shopping/add-order/';?>');
                    }
                    jQuery('#add_to_truck_process_form').submit();
                }
                /*else if(data.contents=="0"){
                    /// show error message
                    if(order_type=='single'){
                        myJsMain.commonFunction.tidiitAlert('Tidiit Validate System','Your had selected last uncompleted order is "Buying Club Order".So you can not process "Single Order" Now.Set the item to your wish list and process teh order latter.',140);
                    }else{
                        myJsMain.commonFunction.tidiitAlert('Tidiit Validate System','your last "Buying Club Order" is yet not completd.So you can not process "Buying Club Order" Now.Set the item to your wish list and process teh order latter.',140);
                    }
                }else if(data.contents=="2"){ 
                    /// show error message
                    myJsMain.commonFunction.tidiitAlert('Tidiit Validate System','Your "Single Order" yet not completed.So you can not process "Buying Club Order" Now.Set the item to your wish list and process teh order latter.',140);
                }*/
                else if(data.contents=="-1"){
                    myJsMain.commonFunction.tidiitAlert('Tidiit Validate System',"Please sign in or sign up first for buy this product.",140);
                    $('.multiselect-modal-sm').modal('hide');
                    $( "a.signIn" ).trigger( "click" );
                    return false;
                }
                
            }, 'json' );
            
        
    });
});  
</script>
<button class="inline btn btn-primary howItWork" data-toggle="modal" data-target=".bs-example-modal-lg" href="#inline_content">HOW IT WORKS</button>

        
<!-- This contains the hidden content for inline calls -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
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
<div class="container">
  <div class="comp-breadcrumbs">
    <div class="bread-crumb" id="breadCrumbWrapper"> 
        <?php echo $breadCrumbStr;?>
      <div class="clear"></div>
    </div>
  </div>
</div>
<article>
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12 productInner">
        <div class="page_content"> 
          
          <!--- New -->
          <div class="col-md-4"> 
            <a href="<?php echo PRODUCT_ORIGINAL.$productImageArr[0]->image;?>" class="MagicZoom" id="Zoomer" rel="selectors-effect-speed: 600; disable-zoom: false;" title="<?php echo $productDetailsArr[0]->title;?>"><img src="<?php echo PRODUCT_DEAILS_EXTRA_BIG.$productImageArr[0]->image;?>"/></a> <br/>
            <?php foreach ($productImageArr As $k){?>
            <!-- selector with own title --> 
            <a href="<?php echo PRODUCT_DEAILS_EXTRA_BIG.$k->image;?>" rel="zoom-id: Zoomer;" rev="<?php echo PRODUCT_DEAILS_EXTRA_BIG.$k->image;?>" title="<?php echo $productDetailsArr[0]->title;?>"><img src="<?php echo PRODUCT_DEAILS_SMALL.$k->image;?>" width="75"/></a> 
            <?php }?> 
         </div>
            
          <div class="col-md-7 img-cont">
            <h2><?php echo $productDetailsArr[0]->title;?></h2>
            <?php /*<div class="rating_cont"> <span>
              <input name="my_input" value="4" id="rating_simple2" type="hidden">
              </span> <span class="ratings-wrapper"> <a class="showRatingTooltip"> 7 Ratings</a> </span> <span>Be the first to review</span> <!--<span>Q&A </span>--> 
            </div>*/?>
            <div class="pdp-e-i-keyfeatures">
            <?php if(!$productDetailsArr[0]->isOptionsAdded):?>
                <ul>
                <?php $warrantyDurationData='';if(array_key_exists($productDetailsArr[0]->warrantyDuration, $warrantyDuration)){$warrantyDurationData=$warrantyDuration[$productDetailsArr[0]->warrantyDuration];}?>  
                <li title="<?php echo $warrantyDurationData;?> Brand Warranty">-&nbsp; <?php echo $warrantyDurationData;?> Brand Warranty</li>
                <li title="<?php echo $productDetailsArr[0]->screenSize;?> Display">-&nbsp;<?php echo $productDetailsArr[0]->screenSize;?> Display</li>
                <?php $ram='';if(array_key_exists($productDetailsArr[0]->ram, $ramData)){$ram=$ramData[$productDetailsArr[0]->ram];}
                    $internalMemoryData='';if(array_key_exists($productDetailsArr[0]->internalMemory, $internalMemory)){$internalMemoryData=$internalMemory[$productDetailsArr[0]->internalMemory];}?>  
                <li title="<?php echo $ram;?> RAM &amp; <?php echo $internalMemoryData;?> ROM">-&nbsp;<?php echo $ram;?> RAM &amp; <?php echo $internalMemoryData;?> ROM</li>
                <?php $rearCamera='';if(array_key_exists($productDetailsArr[0]->mobileRearCamera, $mobileCamera)){$rearCamera=$mobileCamera[$productDetailsArr[0]->mobileRearCamera];}
                    $frontCamera='';if(array_key_exists($productDetailsArr[0]->frontCamera, $mobileCamera)){$frontCamera=$mobileCamera[$productDetailsArr[0]->frontCamera];}?>  
                <li title="<?php echo $rearCamera;?> Rear &amp;  <?php echo $frontCamera;?> Front Camera">-&nbsp;<?php echo $rearCamera;?> Rear &amp;  <?php echo $frontCamera;?> Front Camera</li>
                <?php 
                $processorType="";if(array_key_exists($productDetailsArr[0]->processorCores, $mobileProcessorCores)){$processorType=$mobileProcessorCores[$productDetailsArr[0]->processorCores];}
                ?>
                <li title="<?php echo $productDetailsArr[0]->processorSpeed;?> <?php echo $processorType;?> processor">-&nbsp;<?php echo $productDetailsArr[0]->processorSpeed;?> <?php echo $processorType;?> processor</li>
                <li class="pdp-e-i-keyspecs"><i style="visibility:hidden">-&nbsp;</i><span class="viewSpecs"><a href="#allDetails" onClick="ScrollMe('allDetails'); return false;">View all item details</a></span></li>
              </ul>
            <?php else: ?>

            <?php if($topoptions):?>
                        <ul>
                            <?php foreach($topoptions as $topkey => $topval): $tkeytitle = $topval;  ?>
                                <li>
                                    -&nbsp; <?=implode(", ", $topval[key($tkeytitle)])?>  <?=key($tkeytitle)?>
                                </li>
                            <?php endforeach;?>
                            <li class="pdp-e-i-keyspecs"><i style="visibility:hidden">-&nbsp;</i><span class="viewSpecs"><a href="#allDetails" onClick="ScrollMe('allDetails'); return false;">View all item details</a></span></li>
                        </ul>
                <?php else:?>
                    <div class="detailssubbox"><?php echo $productDetailsArr[0]->shortDescription?></div>
                    <p class="pdp-e-i-keyspecs pull-right"><i style="visibility:hidden">-&nbsp;</i><span class="viewSpecs"><a href="#allDetails" onClick="ScrollMe('allDetails'); return false;">View all item details</a></span></p>
                <?php endif;?>
            <?php endif;?>
            </div>
         <hr class="divider-horizontal">
             <?php foreach ($productPriceArr As $k){?>
            <div class="row pdp-e-i-MRP  ">            	
                <div class="col-xs-3  pdp-e-i-MRP-l"><input type="radio" name="selectPackege" value="<?php echo $k->productPriceId;?>" > Packege of <?php echo $k->qty;?> </div>
                <!--<div class="col-xs-3  pdp-e-i-MRP-r reset-padding"><span class="rsDiv">Rs</span>&nbsp;<s><span>12,290</span></s> [<span class="pdp-e-i-MRP-r-dis">27</span>% OFF]</div> -->
                <div class="col-xs-3  pdp-e-i-PAY-r reset-padding"><span><?php echo $currencySymbol;?>&nbsp;<span itemprop="price" class="payBlkBig"><?php echo $k->price;?></span></span></div>            
            </div>
             <?php }?>
            
                        
            <hr class="divider-horizontal">
            <div id="pdp-buynow-rp" class="container-fluid buy-button-container reset-padding">
              <div class="row-fluid">               
                <div id="add-cart-button-id" class="col-xs-8 btn btn-primary btn-xl rippleWhite buyLink marR15"> <span class="intialtext">Add to Truck</span> </div>                
              </div>
            </div>
          </div>
          <?php /*<div class="col-md-1 product_right reset-padding">
            <h4>Trending<br />
              Now</h4>
              
               <marquee scrollamount="2" direction="down" loop="true" height="500px;"> 
                	<br />
                    <div class="item col-md-12 col-sm-12 col-xs-12 reset-padding">
                        <div class="prodct_box prodct_box1 product_right"> 
                            <a href="#"> 
                                <img class="img-responsive" src="<?php echo SiteImagesURL;?>prdct_img.png">                                
                            </a>                                                      
                            <p>Rs-600.00/12 PC</p>
                            <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                        </div>
                    </div>
                    <br />
                    <div class="item col-md-12 col-sm-12 col-xs-12 reset-padding">
                        <div class="prodct_box prodct_box1 product_right"> 
                            <a href="#"> 
                                <img class="img-responsive" src="<?php echo SiteImagesURL;?>prdct_img.png">                                
                            </a>                                                      
                            <p>Rs-600.00 /12 PC</p>
                            <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                        </div>
                    </div>
                    <br />
                    <div class="item col-md-12 col-sm-12 col-xs-12 reset-padding">
                        <div class="prodct_box prodct_box1 product_right"> 
                            <a href="#"> 
                                <img class="img-responsive" src="<?php echo SiteImagesURL;?>prdct_img.png">                                
                            </a>                                                      
                            <p>Rs-600.00/12 PC</p>
                            <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                        </div>
                    </div>
                    <br />
                    <div class="item col-md-12 col-sm-12 col-xs-12 reset-padding">
                        <div class="prodct_box prodct_box1 product_right"> 
                            <a href="#"> 
                                <img class="img-responsive" src="<?php echo SiteImagesURL;?>prdct_img.png">                                
                            </a>                                                      
                            <p>Rs-600.00/12 PC</p>
                            <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                        </div>
                    </div>
              </marquee>
              
          </div>
          <!-- New --> */?>
        </div>
      </div>
    </div>
    <hr class="divider-horizontal">
    <div class="row">
      <div class="col-md-12 col-sm-12 productInner">
        <div class="topseller_zne">
          <h3 id="similiarProduct" class="section-head"> Similar Products </h3>
          <div id="demo">
            <div id="owl-demo4" class="owl-carousel">
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" />
                  <h4>Sale</h4>
                  </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img1.png" class="img-responsive" />
                  <h4>New Arrivals</h4>
                  </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img2.png" class="img-responsive" /> </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" />
                  <h4>Best Selling</h4>
                  </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img1.png" class="img-responsive" /> </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img2.png" class="img-responsive" />
                  <h4>Hot Deal</h4>
                  </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" /> </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img1.png" class="img-responsive" /> </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img2.png" class="img-responsive" /> </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" /> </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img1.png" class="img-responsive" /> </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
              <div class="item">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img2.png" class="img-responsive" /> </a>
                  <p>Aidalane Gold Plated studded</p>
                  <ul class="star">
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star"></i></li>
                    <li><i class="fa fa-star-o"></i></li>
                  </ul>
                  <p>20000 - 60000</p>
                </div>
              </div>
            </div>
            <!--<div class="customNavigation">
                                        <a class="btn topseller_prev"><i class="fa fa-angle-left"></i></a>
                                        <a class="btn topseller_next"><i class="fa fa-angle-right"></i></a>
                                    </div>--> 
          </div>
        </div>
      </div>
    </div>
    <hr class="divider-horizontal">
    <div class="row">
      <div id="allDetails" class="col-md-12 col-sm-12 productInner">
        <div class="topseller_zne">
          <h3 id="similiarProduct" class="section-head"> Item Details </h3>
          <div class="comp comp-product-specs">
            <?php if(!$productDetailsArr[0]->isOptionsAdded):?>
            <div class="spec-section expanded">
              <div class="spec-title-wrp">
                <h3 class="spec-title"> <i class="sd-icon sd-icon-plus "></i> <i class="sd-icon sd-icon-minus"></i> Highlights </h3>
              </div>
              <div class="spec-body">
                <ul class="dtls-list clear">
                  <li class="col-xs-6 dtls-li"> <?php echo $warrantyDurationData;?> Brand Warranty</li>
                  <li class="col-xs-6 dtls-li"> <?php echo $productDetailsArr[0]->screenSize;?> Display</li>
                  <li class="col-xs-6 dtls-li"> <?php echo $ram;?> RAM &amp; <?php echo $internalMemoryData;?> ROM</li>
                  <li class="col-xs-6 dtls-li"> <?php echo $rearCamera;?> Rear &amp;  <?php echo $frontCamera;?> Front Camera</li>
                  <li class="col-xs-6 dtls-li"> <?php echo $productDetailsArr[0]->processorSpeed;?> <?php echo $processorType;?> processor</li>
                  <?php $expandableMemoryData='';if(array_key_exists($productDetailsArr[0]->expandableMemory,$expandableMemory)){$expandableMemoryData=$expandableMemory[$productDetailsArr[0]->expandableMemory];}?>
                  <li class="col-xs-6 dtls-li"> Expandable upto <?php echo $expandableMemoryData;?></li>
                  <?php $connectivityType='';if(array_key_exists($productDetailsArr[0]->mobileConnectivity,$mobileConnectivity)){$connectivityType=$mobileConnectivity[$productDetailsArr[0]->mobileConnectivity];}?>
                  <li class="col-xs-6 dtls-li"> <?php echo $connectivityType;?></li>
                  <?php $mobileDisplayResolutiontype='';if(array_key_exists($productDetailsArr[0]->displayResolution, $mobileDisplayResolution)){$mobileDisplayResolutiontype=$mobileDisplayResolution[$productDetailsArr[0]->displayResolution];}?>
                  <li class="col-xs-6 dtls-li"> <?php echo $mobileDisplayResolutiontype;?> pixels screen resolution</li>
                  <li class="col-xs-6 dtls-li"> No of SIM <?php echo $productDetailsArr[0]->noOfSims;?></li>
                  <?php $mobileBatteryName='';if(array_key_exists($productDetailsArr[0]->batteryType, $mobileBatteryType)){$mobileBatteryName=$mobileBatteryType[$productDetailsArr[0]->batteryType];}?>
                  <li class="col-xs-6 dtls-li"> <?php echo $productDetailsArr[0]->batteryCapacity.' '.$mobileBatteryName;?> Battery (removable)</li>
                  <?php $mobileColorName='';if(array_key_exists($productDetailsArr[0]->color, $mobileColor)){$mobileColorName=$mobileColor[$productDetailsArr[0]->color];}?>
                  <li class="col-xs-6 dtls-li"> Colour Variants: <?php echo $mobileColorName;?></li>
                </ul>
              </div>
            </div>
            <div class="spec-section expanded">
              <div class="spec-title-wrp">
                <h3 class="spec-title"> <i class="sd-icon sd-icon-plus "></i> <i class="sd-icon sd-icon-minus"></i> Technical Specification </h3>
              </div>
              <div itemprop="description" class="spec-body">
              <div class="detailssubbox">
                  <ul id="accordion" style="width:100%;">
                    <li><div class="product-spec">In The Box</div>
                        <ul>
                          <table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                              
                              <tr>
                                <td width="20%">Box Contents</td>
                                <td>
                                    <?php $mobileContentArr=  explode(',', $productDetailsArr[0]->mobileBoxContent);
                                    if(!empty($mobileContentArr)){
                                        $inBox='';
                                    foreach($mobileContentArr AS $k){
                                        if($inBox=='')
                                            $inBox=$mobileBoxContents[$k];
                                        else
                                            $inBox.= ', '.$mobileBoxContents[$k];
                                    }
                                    echo $inBox;
                                    ?>
                                    <?php }else{ echo 'No thing inside';}?>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                    </li>
                    <li><div class="product-spec">General</div>
                        <ul>
                           <table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                             
                              <tr>
                                <td width="20%">Brand</td>
                                <td><?php echo $productDetailsArr[0]->brandTitle;?></td>
                              </tr>
                              <tr>
                                <td width="20%">Model</td>
                                <td><?php echo $productDetailsArr[0]->model;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">SIMs</td>
                                <td><?php if($productDetailsArr[0]->noOfSims==1){echo 'Single Sim';}else{ echo 'Dual Sims';}?></td>
                              </tr>
                              <!--<tr>
                                <td width="20%">SIM Size</td>
                                <td>Mini Sim </td>
                              </tr>-->
                              <tr>
                                <td width="20%">Colour</td>
                                <td><?php echo $mobileColorName;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Other Features</td>
                                <td><?php echo $productDetailsArr[0]->mobileOtherFeatures;?></td>
                              </tr>
                              <!--<tr>
                                <td width="20%">Call Features</td>
                                <td>Call Waiting, Call Forwarding, Loudspeaker </td>
                              </tr>-->
                            </tbody>
                          </table>
                        </ul>
                    </li>
                    <li><div class="product-spec">Display</div>
                        <ul>
                        <table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                             
                              <tr>
                                <td width="20%">Screen Size (in cm)</td>
                                <td><?php echo $productDetailsArr[0]->screenSize;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Display Resolution</td>
                                <td><?php echo $mobileDisplayResolutiontype;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Display Type</td>
                                <td><?php echo $productDetailsArr[0]->displayType;?> </td>
                              </tr>
                              <!--<tr>
                                <td width="20%">Screen Protection</td>
                                <td>Gorilla Glass 2 </td>
                              </tr>
                              <tr>
                                <td width="20%">Pixel Density</td>
                                <td>267.02 ppi </td>
                              </tr>
                              <tr>
                                <td width="20%">Multitouch</td>
                                <td>Yes </td>
                              </tr>
                              <tr>
                                <td width="20%">Other Screen Features</td>
                                <td>HD Display </td>
                              </tr>-->
                            </tbody>
                          </table>
                        </ul>
                     </li>                    
                    <li><div class="product-spec">Software</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                             
                              <tr>
                                <td width="20%">OS Version</td>
                                <td><?php echo $mobileOS[$productDetailsArr[0]->os].' '.$productDetailsArr[0]->osVersion;?></td>
                              </tr>
                              <!--<tr>
                                <td width="20%">Preinstalled Apps</td>
                                <td>- </td>
                              </tr>-->
                              <tr>
                                <td width="20%">Multi-languages Supported</td>
                                <td><?php echo ($productDetailsArr[0]->multiLanguages==1) ? 'Yes' : 'No';?> </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>
                     
                     <li><div class="product-spec">Camera</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>
                             
                              <tr>
                                <td width="20%">Rear Camera</td>
                                <td><?php echo $rearCamera;?> </td>
                              </tr>
                              <!--<tr>
                                <td width="20%">Auto Focus</td>
                                <td>Yes </td>
                              </tr>-->
                              <tr>
                                <td width="20%">Flash</td>
                                <td><?php echo ($productDetailsArr[0]->mobileFlash==1) ? 'Yes' : "No";?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Front Camera</td>
                                <td><?php echo $frontCamera;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Other Camera Features</td>
                                <td><?php echo $productDetailsArr[0]->mobileOtherCameraFeatures;?></td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>
                     
                     <li><div class="product-spec">Connectivity</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                            
                              <tr>
                                <td width="20%">By</td>
                                <td><?php $connectivityArr= explode(',', $productDetailsArr[0]->mobileConnectivity);
                                $connectionStr='';
                                foreach($connectivityArr AS $k =>$v){
                                    $connectionStr.=$mobileConnectivity[$v].', ';
                                }
                                echo substr($connectionStr,0,-1);?></td>
                              </tr>
                              
                            </tbody>
                          </table>
                        </ul>
                     </li>
                     
                     <li><div class="product-spec">Processor</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                              
                              <tr>
                                <td width="20%">Processor Speed</td>
                                <td><?php echo $productDetailsArr[0]->processorSpeed;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Processor Cores</td>
                                <td><?php echo $processorType;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Processor Brand</td>
                                <td><?php echo $mobileProcessorBrand[$productDetailsArr[0]->processorBrand];?></td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>
                     
                     <li><div class="product-spec">Memory &amp; Storage</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>
                             
                              <tr>
                                <td width="20%">RAM</td>
                                <td><?php echo $ram;?></td>
                              </tr>
                              <tr>
                                <td width="20%">Internal Memory</td>
                                <td><?php echo $internalMemoryData;?> </td>
                              </tr>
                              <!--<tr>
                                <td width="20%">User Memory</td>
                                <td>Approx 12 GB </td>
                              </tr>-->
                              <tr>
                                <td width="20%">Expandable Memory</td>
                                <td><?php echo $expandableMemoryData;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Memory Card Slot</td>
                                <td><?php echo $productDetailsArr[0]->memoryCardSlot;?> </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>
                     
                     <!--<li><idv class="product-spec">Hardware</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>
                            
                              <tr>
                                <td width="20%">Accelerometer</td>
                                <td>Yes </td>
                              </tr>
                              <tr>
                                <td width="20%">Compass</td>
                                <td>Yes </td>
                              </tr>
                              <tr>
                                <td width="20%">Proximity</td>
                                <td>Yes </td>
                              </tr>
                              <tr>
                                <td width="20%">Gyro-sensor</td>
                                <td>Yes </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>
                     
                     <li><div class="product-spec">Hardware Connectivity</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>
                             
                              <tr>
                                <td width="20%">Bluetooth A2DP</td>
                                <td>Yes, V2.1+EDR/V3.0+HS compliance/4.0 LE </td>
                              </tr>
                              <tr>
                                <td width="20%">Audio Jack</td>
                                <td>3.5mm </td>
                              </tr>
                              <tr>
                                <td width="20%">SAR Value</td>
                                <td> - </td>
                              </tr>
                              <tr>
                                <td width="20%">FM Radio</td>
                                <td>Yes </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>-->
                     
                     <li><div class="product-spec">Battery &amp; Power</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                             
                              <tr>
                                <td width="20%">Battery Capacity</td>
                                <td><?php echo $productDetailsArr[0]->batteryCapacity;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Battery Type</td>
                                <td><?php echo $mobileBatteryName?></td>
                              </tr>
                              <!--<tr>
                                <td width="20%">Replaceable Battery</td>
                                <td>No </td>
                              </tr>-->
                              <tr>
                                <td width="20%">Talk Time</td>
                                <td><?php echo $productDetailsArr[0]->talkTime;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Standby Time</td>
                                <td><?php echo $productDetailsArr[0]->standbyTime;?> </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>
                     
                      <li><div class="product-spec">Dimensions</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                              
                              <tr>
                                <td width="20%">HxWxD</td>
                                <td><?php echo $productDetailsArr[0]->height.' X '.$productDetailsArr[0]->width.' X '.$productDetailsArr[0]->length;?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Weight</td>
                                <td><?php echo $productDetailsArr[0]->weight?> </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>
                     
                      <li><div class="product-spec">Warranty</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>
                             
                              <tr>
                                <td width="20%">Warranty Type</td>
                                <td><?php echo $productDetailsArr[0]->warrantyType?> </td>
                              </tr>
                              <tr>
                                <td width="20%">Warranty Duration(in Month)</td>
                                <td><?php echo $warrantyDurationData;?> </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>                   
                    
                    
              	</ul>
              </div>               
                
              </div>
            </div>
            <?php else :?>
                <?php $expand = false;if($options):?>
                <div class="spec-section expanded">
                    <div class="spec-title-wrp">
                        <h3 class="spec-title"> <i class="sd-icon sd-icon-plus "></i> <i class="sd-icon sd-icon-minus"></i> Highlights </h3>
                    </div>
                    <div class="spec-body">
                        <ul class="dtls-list clear">
                        <?php foreach($options as $opkey => $opval): $keytitle = $opval;  ?>
                            <li class="col-xs-12 dtls-li">
                                <span class="col-md-2" style="padding-right: 1px;padding-left: 1px;"><?=key($keytitle)?></span><span class="col-md-1">:</span><span class="col-md-9" style="padding-right: 1px;padding-left: 1px;"><?=implode(", ", $opval[key($keytitle)])?></span>
                            </li>
                        <?php endforeach;?>
                        </ul>
                    </div>
                </div>
                <?php $expand = true; endif;?>

                <div class="spec-section expanded">
                    <div class="spec-title-wrp">
                        <h3 class="spec-title"> <i class="sd-icon sd-icon-plus "></i> <i class="sd-icon sd-icon-minus"></i>Description </h3>
                    </div>
                    <div itemprop="description" class="spec-body">
                        <div class="detailssubbox"><?php echo $productDetailsArr[0]->description?></div>
                    </div>
                </div>

                <div class="spec-section expanded">
                    <div class="spec-title-wrp">
                        <h3 class="spec-title"> <i class="sd-icon sd-icon-plus "></i> <i class="sd-icon sd-icon-minus"></i>Others Specification </h3>
                    </div>
                    <div itemprop="description" class="spec-body">
                        <div class="detailssubbox">
                            <ul id="accordion" style="width:100%;">
                                <li><div class="product-spec">Dimensions</div>
                                    <ul>
                                        <table width="100%" cellspacing="2" cellpadding="0" border="0">
                                            <tbody>
                                            <?php if( $productDetailsArr[0]->height || $productDetailsArr[0]->width || $productDetailsArr[0]->length ): ?>
                                                <tr>
                                                    <td width="20%">Height/Width/Length</td>
                                                    <td><?=$productDetailsArr[0]->height?"Height : ".$productDetailsArr[0]->height." ":""?><?=$productDetailsArr[0]->width?"Width : ".$productDetailsArr[0]->width." ":""?><?=$productDetailsArr[0]->length?"Length : ".$productDetailsArr[0]->length." ":""?> </td>
                                                </tr>
                                            <?php endif;?>
                                            <?php if( $productDetailsArr[0]->weight ): ?>
                                                <tr>
                                                    <td width="20%">Weight</td>
                                                    <td><?php echo $productDetailsArr[0]->weight?> </td>
                                                </tr>
                                            <?php endif;?>
                                            </tbody>
                                        </table>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif;?>
          </div>
        </div>
      </div>
    </div>
  </div>
</article>
<div class="modal fade multiselect-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content orderType-content">
      <div class="modal-body">
      	<div class="orderType">
         
            <div class="input-group form-group order-labl">
              <span class="input-group-addon">
                <input type="radio" name="ordertype" value="group">
              </span>
              <label for="grp">Buying Club Order</label>
            </div><!-- /input-group -->
            
            <div class="input-group order-labl form-group">
              <span class="input-group-addon">
                <input type="radio" name="ordertype" value="single" checked>
              </span>
              <label for="sin">Single Order</label>
            </div><!-- /input-group -->
            
            <div class="text-center">
                <button type="button" class="btn btn-default add-to-truck-process-btn">Process</button>
            </div>
            <form name="add_to_truck_process_form" id="add_to_truck_process_form" method="post">
                <input type="hidden" name="productId" id="productId" value="<?php echo $productDetailsArr[0]->productId;?>">
                <input type="hidden" name="productPriceId" id="prorductPriceId" value="">
            </form>
        </div>
                <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer;?>