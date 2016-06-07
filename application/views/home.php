<?php echo $html_heading; echo $header; $currencySymbol=($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')=="IN") ? '<i class="fa fa-rupee"></i>' :'KSh';?>
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
                    <div class="banner_sec" style="display: none;">
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
                    <div class="col-md-6">
                        <div class="view second-effect">
                            <img src="<?php echo SiteImagesURL;?>daily_deals.png" class="img-responsive" height="100" width="100"/>
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
                    <div class="col-md-6">
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
    </div>
</header>
<div id="autoLoadHowItWorks"></div>
<?php echo $common_how_it_works;?>
<?php echo $feedback;?>

<article>
    <div class="container">
        <div class="bst_sllng">
            <h2>Best Selling Item</h2>
            <div class="row t-product-carousel-row">
                <div id="demo">
                    <div id="owl-demo" class="owl-carousel">
                        <?php foreach($bestSelllingItem AS $k){ //pre($k);die;?>
                        <div class="item">
                            <div class="prodct_box">
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
                                <!--<ul class="star">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                </ul> -->
                                <p><?php echo $currencySymbol.' '.$k->lowestPrice.' - '.$currencySymbol.' '.$k->heighestPrice;?></p>
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
            <div class="row t-product-carousel-row">
                <div id="demo">
                    <div id="owl-demo1" class="owl-carousel">
                        <?php foreach($newArrivals AS $k){ //pre($k);die;?>
                        <div class="item">
                            <div class="prodct_box">
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
                                <!--<ul class="star">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                </ul>-->
                                <p><?php echo $currencySymbol.' '.$k->lowestPrice.' - '.$currencySymbol.' '.$k->heighestPrice;?></p>
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
            <div class="row t-product-carousel-row">
                <div id="demo">
                    <div id="owl-demo2" class="owl-carousel">
                        <?php foreach($bestSelllingItem AS $k){ //pre($k);die;?>
                        <div class="item">
                            <div class="prodct_box">
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
                                <!--<ul class="star">
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star"></i></li>
                                    <li><i class="fa fa-star-o"></i></li>
                                </ul>-->
                                <p><?php echo $currencySymbol.' '.$k->lowestPrice.' - '.$currencySymbol.' '.$k->heighestPrice;?></p>
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
                        <div class="item"><a href="<?php echo BASE_URL.'brand/'.base64_encode($k->brandId*226201).'/?';?>&brand=<?php echo $k->title;?>"><img src="<?php echo $src;?>" alt="<?php echo $k->title;?>" title="<?php echo $k->title;?>" /></a></div>
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
                        <?php /*<p>&nbsp;</p>
                        <p><a href="<?php echo BASE_URL.'content/about-us/'.  base64_encode(1);?>">Learn More..</a></p> */?>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                        <div class="abot_box">
                        <img src="<?php echo SiteImagesURL;?>faq_img.png" />
                        <a href="<?php echo BASE_URL.'seller-faq';?>"><h3>New Sellers FAQ</h3></a>
                        <?php /*<p>&nbsp;</p>
                        <p><a href="<?php echo BASE_URL.'seller-faq';?>">Learn More..</a></p> */?>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                        <div class="abot_box brdr_rit_none">
                        <img src="<?php echo SiteImagesURL;?>prtctn_img.png" />
                        <a href="<?php echo BASE_URL.'buyer-faq';?>"><h3>New Buyers FAQ</h3></a>
                        <?php /*<p>&nbsp;</p>
                        <p><a href="<?php echo BASE_URL.'buyer-faq';?>">Learn More..</a></p> */?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
<?php echo $footer;?>
