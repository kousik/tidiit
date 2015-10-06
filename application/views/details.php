<?php echo $html_heading; echo $header;
//$productPageTypeArr=$this->config->item('productPageTypeArr');
$mobileBoxContents=$this->config->item('mobileBoxContents');
$mobileColor=$this->config->item('mobileColor');
$mobileDisplayResolution=$this->config->item('mobileDisplayResolution');
$mobileConnectivity=$this->config->item('mobileConnectivity');
$mobileOS=$this->config->item('mobileOS');
$mobileProcessorCores=$this->config->item('mobileProcessorCores');
$mobileBatteryType=$this->config->item('mobileBatteryType');
$mobileProcessorBrand=$this->config->item('mobileProcessorBrand');
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
          navigation : false,

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
    
    jQuery("#add-cart-button-id").click(function(){
        jQuery("#orderTypeId").show( "slow" );
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
    <div class="bread-crumb" id="breadCrumbWrapper"> <a href="#">Home</a> >> <a href="#">Mobiles & Tablets</a> >> <a href="#">Mobile Phones</a> >> Lava Flair Z1 8 GB
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
            <a href="<?php echo PRODUCT_DEAILS_EXTRA_BIG.$productImageArr[0]->image;?>" class="MagicZoom" id="Zoomer" rel="selectors-effect-speed: 600; disable-zoom: false;" title="<?php echo $productDetailsArr[0]->title;?>"><img src="<?php echo PRODUCT_DEAILS_EXTRA_BIG.$productImageArr[0]->image;?>"/></a> <br/>
            <?php foreach ($productImageArr As $k){?>
            <!-- selector with own title --> 
            <a href="<?php echo PRODUCT_DEAILS_EXTRA_BIG.$k->image;?>" rel="zoom-id: Zoomer;" rev="<?php echo PRODUCT_DEAILS_EXTRA_BIG.$k->image;?>" title="<?php echo $productDetailsArr[0]->title;?>"><img src="<?php echo PRODUCT_DEAILS_SMALL.$k->image;?>" width="75"/></a> 
            <?php }?> 
         </div>
            
          <div class="col-md-7 img-cont">
            <h2><?php echo $productDetailsArr[0]->title;?></h2>
            <div class="rating_cont"> <span>
              <input name="my_input" value="4" id="rating_simple2" type="hidden">
              </span> <span class="ratings-wrapper"> <a class="showRatingTooltip"> 7 Ratings</a> </span> <span>Be the first to review</span> <!--<span>Q&A </span>--> </div>
            <div class="pdp-e-i-keyfeatures">
              <ul>
                <li title="<?php echo $productDetailsArr[0]->warrantyDuration.' months';?> Brand Warranty">-&nbsp; <?php echo $productDetailsArr[0]->warrantyDuration.' months';?> Brand Warranty</li>
                <li title="<?php echo $productDetailsArr[0]->screenSize;?> Display">-&nbsp;<?php echo $productDetailsArr[0]->screenSize;?> Display</li>
                <li title="<?php echo $productDetailsArr[0]->ram;?> RAM &amp; <?php echo $productDetailsArr[0]->internalMemory;?> ROM">-&nbsp;<?php echo $productDetailsArr[0]->ram;?> RAM &amp; <?php echo $productDetailsArr[0]->internalMemory;?> ROM</li>
                <li title="<?php echo $productDetailsArr[0]->mobileRearCamera;?> Rear &amp;  <?php echo $productDetailsArr[0]->frontCamera;?> Front Camera">-&nbsp;<?php echo $productDetailsArr[0]->mobileRearCamera;?> Rear &amp;  <?php echo $productDetailsArr[0]->frontCamera;?> Front Camera</li>
                <?php 
                $processorType="";if(array_key_exists($productDetailsArr[0]->processorCores, $mobileProcessorCores)){$processorType=$mobileProcessorCores[$productDetailsArr[0]->processorCores];}
                ?>
                <li title="<?php echo $productDetailsArr[0]->processorSpeed;?> <?php echo $processorType;?> processor">-&nbsp;<?php echo $productDetailsArr[0]->processorSpeed;?> <?php echo $processorType;?> processor</li>
                <li class="pdp-e-i-keyspecs"><i style="visibility:hidden">-&nbsp;</i><span class="viewSpecs"><a href="#allDetails" onClick="ScrollMe('allDetails'); return false;">View all item details</a></span></li>
              </ul>
            </div>
         <hr class="divider-horizontal">
             <?php foreach ($productPriceArr As $k){ //pre($k);die;?>
            <div class="row pdp-e-i-MRP  ">            	
                <div class="col-xs-3  pdp-e-i-MRP-l"><input type="radio" name="selectPackege" value="<?php echo $k->productPriceId;?>" > Packege of <?php echo $k->qty;?> </div>
                <!--<div class="col-xs-3  pdp-e-i-MRP-r reset-padding"><span class="rsDiv">Rs</span>&nbsp;<s><span>12,290</span></s> [<span class="pdp-e-i-MRP-r-dis">27</span>% OFF]</div> -->
                <div class="col-xs-3  pdp-e-i-PAY-r reset-padding"><span>Rs&nbsp;<span itemprop="price" class="payBlkBig"><?php echo $k->price;?></span></span></div>            
            </div>
             <?php }?>
            
                        
            <hr class="divider-horizontal">
            <div id="pdp-buynow-rp" class="container-fluid buy-button-container reset-padding">
              <div class="row-fluid">               
                
                <div id="add-cart-button-id" class="col-xs-8 btn btn-xl rippleWhite buyLink marR15"> <span class="intialtext">add to truck</span> </div>
                <div class="orderType " id="orderTypeId" style="display:none;">
                	<div id="buy-button-id" class="col-xs-9 btn btn-xl rippleWhite buyLink buyNow marR15">
                	<input type="radio" name="ordertype" id="grp" value="Group" checked><label for="grp">Group Order</label>
                    </div>
                    <div id="buy-button-id" class="col-xs-9 btn btn-xl rippleWhite buyLink buyNow ">
                	<input type="radio" name="ordertype" id="sin" value="Single" checked><label for="sin">Single Order</label>
                    </div>
                </div>              
                                
              </div>
            </div>
          </div>
          <div class="col-md-1 product_right reset-padding">
            <h4>Tranding<br />
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
          <!-- New --> 
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
            <div class="spec-section expanded">
              <div class="spec-title-wrp">
                <h3 class="spec-title"> <i class="sd-icon sd-icon-plus "></i> <i class="sd-icon sd-icon-minus"></i> Highlights </h3>
              </div>
              <div class="spec-body">
                <ul class="dtls-list clear">
                  <li class="col-xs-6 dtls-li"> <?php echo $productDetailsArr[0]->warrantyDuration.' months';?> Brand Warranty</li>
                  <li class="col-xs-6 dtls-li"> <?php echo $productDetailsArr[0]->screenSize;?> Display</li>
                  <li class="col-xs-6 dtls-li"> <?php echo $productDetailsArr[0]->ram;?> RAM &amp; <?php echo $productDetailsArr[0]->internalMemory;?> ROM</li>
                  <li class="col-xs-6 dtls-li"> <?php echo $productDetailsArr[0]->mobileRearCamera;?> Rear &amp;  <?php echo $productDetailsArr[0]->frontCamera;?> Front Camera</li>
                  <li class="col-xs-6 dtls-li"> <?php echo $productDetailsArr[0]->processorSpeed;?> <?php echo $processorType;?> processor</li>
                  <li class="col-xs-6 dtls-li"> Expandable upto <?php echo $productDetailsArr[0]->expandableMemory;?></li>
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
              <ul id="accordion">
                    <li><div class="product-spec">In The Box</div>
                        <ul>
                          <table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                              
                              <tr>
                                <td width="20%">Box Contents</td>
                                <td>Handset, Charger, USB Cable, Earphones, User Manual </td>
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
                                <td>InFocus </td>
                              </tr>
                              <tr>
                                <td width="20%">Model</td>
                                <td>M330 </td>
                              </tr>
                              <tr>
                                <td width="20%">Form</td>
                                <td>Touch </td>
                              </tr>
                              <tr>
                                <td width="20%">SIMs</td>
                                <td>Dual SIM (GSM+GSM) </td>
                              </tr>
                              <tr>
                                <td width="20%">SIM Size</td>
                                <td>Mini Sim </td>
                              </tr>
                              <tr>
                                <td width="20%">Colour</td>
                                <td>White </td>
                              </tr>
                              <tr>
                                <td width="20%">Other Features</td>
                                <td>Email, Document Viewer </td>
                              </tr>
                              <tr>
                                <td width="20%">Call Features</td>
                                <td>Call Waiting, Call Forwarding, Loudspeaker </td>
                              </tr>
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
                                <td>13.97 cm (5.5) </td>
                              </tr>
                              <tr>
                                <td width="20%">Display Resolution</td>
                                <td>1280 X 720 pixels HD </td>
                              </tr>
                              <tr>
                                <td width="20%">Display Type</td>
                                <td>Capacitive </td>
                              </tr>
                              <tr>
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
                              </tr>
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
                                <td>Android KitKat 4.4 with InLife UI </td>
                              </tr>
                              <tr>
                                <td width="20%">Preinstalled Apps</td>
                                <td>- </td>
                              </tr>
                              <tr>
                                <td width="20%">Multi-languages Supported</td>
                                <td>Yes </td>
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
                                <td>13 MP </td>
                              </tr>
                              <tr>
                                <td width="20%">Auto Focus</td>
                                <td>Yes </td>
                              </tr>
                              <tr>
                                <td width="20%">Flash</td>
                                <td>Yes </td>
                              </tr>
                              <tr>
                                <td width="20%">Front Camera</td>
                                <td>8 MP </td>
                              </tr>
                              <tr>
                                <td width="20%">Other Camera Features</td>
                                <td>Sony Exmor R Sensor, Blue Glass Filter, f2.2 apperture speed </td>
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
                                <td width="20%">GSM</td>
                                <td>900/1800/1900 </td>
                              </tr>
                              <tr>
                                <td width="20%">CDMA</td>
                                <td>No </td>
                              </tr>
                              <tr>
                                <td width="20%">3G/WCDMA</td>
                                <td>WCDMA:900/2100MHz </td>
                              </tr>
                              <tr>
                                <td width="20%">4G/LTE</td>
                                <td>- </td>
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
                                <td>1.7 GHz </td>
                              </tr>
                              <tr>
                                <td width="20%">Processor Cores</td>
                                <td>Octa Core </td>
                              </tr>
                              <tr>
                                <td width="20%">Processor Brand</td>
                                <td>MTK MT6592 </td>
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
                                <td>2 GB </td>
                              </tr>
                              <tr>
                                <td width="20%">Internal Memory</td>
                                <td>16 GB </td>
                              </tr>
                              <tr>
                                <td width="20%">User Memory</td>
                                <td>Approx 12 GB </td>
                              </tr>
                              <tr>
                                <td width="20%">Expandable Memory</td>
                                <td>upto 64 GB </td>
                              </tr>
                              <tr>
                                <td width="20%">Memory Card Slot</td>
                                <td>microSD </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>
                     
                     <li><div class="product-spec">Hardware</div>
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
                     </li>
                     
                     <li><div class="product-spec">Battery &amp; Power</div>
                        <ul>
                        	<table width="100%" cellspacing="2" cellpadding="0" border="0">
                            <tbody>                             
                              <tr>
                                <td width="20%">Battery Capacity</td>
                                <td>3100 mAh </td>
                              </tr>
                              <tr>
                                <td width="20%">Battery Type</td>
                                <td>Li-Polymer </td>
                              </tr>
                              <tr>
                                <td width="20%">Replaceable Battery</td>
                                <td>No </td>
                              </tr>
                              <tr>
                                <td width="20%">Talk Time</td>
                                <td>Up to 18 hours </td>
                              </tr>
                              <tr>
                                <td width="20%">Standby Time</td>
                                <td>Up to 550 hours </td>
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
                                <td>7.81 X 15.34 X 0.93 cm (78.1 X 153.4 X 9.3) </td>
                              </tr>
                              <tr>
                                <td width="20%">Weight</td>
                                <td>167 gm </td>
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
                                <td>Brand Warranty </td>
                              </tr>
                              <tr>
                                <td width="20%">Warranty Duration</td>
                                <td>12 Months </td>
                              </tr>
                            </tbody>
                          </table>
                        </ul>
                     </li>                   
                    
                    
              	</ul>
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