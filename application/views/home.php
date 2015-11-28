<?php echo $html_heading; echo $header;?>
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

<article>
    <div class="container">
        <div class="bst_sllng">
            <h2>Best Selling Item</h2>
            <div class="row">
                <div id="demo">
                    <div id="owl-demo" class="owl-carousel">
                        <?php foreach($bestSelllingItem AS $k){ //pre(HOME_LISTING.$k->image);die;?>
                        <div class="item">
                            <div class="prodct_box">
                                <a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">
                                    <img src="<?php echo HOME_LISTING.$k->image;?>" class="img-responsive" />
                                    <div class="ch-info">
                                        <h3><i class="fa fa-truck"></i> &nbsp;Add to Truck</h3>
                                    </div>
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
                                <p><a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
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
                            <div class="prodct_box">
                                <a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">
                                    <img src="<?php echo HOME_LISTING.$k->image;?>" class="img-responsive" />
                                    <div class="ch-info">
                                        <h3><i class="fa fa-truck"></i> &nbsp;Add to Truck</h3>
                                    </div>
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
                                <p><a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
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
                            <div class="prodct_box">
                                <a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">
                                    <img src="<?php echo HOME_LISTING.$k->image;?>" class="img-responsive" />
                                    <div class="ch-info">
                                        <h3><i class="fa fa-truck"></i> &nbsp;Add to Truck</h3>
                                    </div>
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
                                <p><a href="<?php echo BASE_URL.'product/details/'.base64_encode($k->productId);?>">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
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