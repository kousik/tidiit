<?php echo $html_heading;
echo $header; 
$currencySymbol=($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')=="IN") ? '<i class="fa fa-rupee"></i>' :'KSh';?>
</div>
<!-- bin/jquery.slider.min.css -->
<link rel="stylesheet" href="<?php echo SiteCSSURL; ?>jquery.slider.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo SiteCSSURL; ?>jslider.css" type="text/css">
<link rel="stylesheet" href="<?php echo SiteCSSURL; ?>jslider.plastic.css" type="text/css">
<link rel="stylesheet" href="css/ion.rangeSlider.skinHTML5.css" />
<link rel="stylesheet" href="css/ion.rangeSlider.css" />
<!-- end -->
</header>
<script type="text/javascript">
    //jQuery(document).ready(function(){});
</script>
<?php echo $common_how_it_works; ?>
<?php echo $feedback; ?>
<article>
  <div class="container">
    <div class="categrs_bannr">
      <div class="row">
        <div class="col-md-3 col-sm-12 padng_right_none">
            <div class="cate_cont">
            <?php echo $main_menu;?>
            </div>
            <div class="block block-layered-nav block-layered-nav--no-filters">
              <!--<div class="block-title"> <strong><span>Shop By</span></strong> </div>-->
              <div class="block-content toggle-content js-add-widget">
                  <div class="brand_sec">
                      <div class="sub_hdng">
                          <h3>Price</h3>
                      </div>
                      <div class="layout-slider" style="width: 100%">
                         <span style="display: inline-block; width: 100%; padding: 0 5px;"><input id="Slider1" type="slider" name="price" value="<?= $range[0] ?>;<?= $range[1] ?>" /></span>
                      </div>
                  </div>
                    <?php if (isset($products['brands']) && $products['brands']): ?>
                    <div class="brand_sec">
                        <div class="sub_hdng">
                            <h3>Brand</h3>
                        </div>
                        <ul id="brand" class="rand_list">
                            <?php
                            foreach ($products['brands'] as $bkey => $brnd):?>
                                <li>
                                    <input type="checkbox" class="brandsort" value="<?= $brnd ?>"
                                           <?php if (in_array($brnd, $brand)): ?>checked="checked" <?php endif;
                                    ?> />
                                    <span><?= $brnd ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($products['brands']);endif; ?>
                    <div class="js-option-list">
                      <?php if(isset($options) && $options): //print_r($options);
                          foreach($options as $opkey => $opval):?>
                              <div class="brand_sec">
                                  <div class="sub_hdng">
                                      <h3><?=$opval['name']?></h3>
                                  </div>
                                  <ul id="options" class="rand_list">
                                      <?php
                                      foreach($opval['value'] as $ovkey => $oval):
                                          $listval = $oval;
                                          if($opval['name'] == "Color"):
                                              $colors = explode("||", $oval);
                                              $listval = $colors[0];
                                          endif;
                                          $checked = "";
                                          $qc = $opval['id'].'@'.trim($listval);
                                          if(in_array($qc, $query)):
                                              $checked = " checked";
                                          endif;
                                          ?>
                                          <li style="padding: 5px 5px;float: left;width: 45%;font-size: 12px;">
                                              <input type="checkbox" name="<?=$opval['type']?>" class="optionsort" data-name="<?=$opkey?>" value="<?=$opval['id']?>@<?=trim($listval)?>" <?=$checked?> />
                                              <span style="margin-left: 2px;"><?=$listval?></span>
                                          </li>
                                      <?php endforeach;?>
                                  </ul>
                              </div>
                          <?php endforeach;
                      endif; ?>
                    </div>
              </div>
            </div>
        </div>
        
        <!---- Product Listing right  --->
        
        <div class="col-md-9 col-sm-12 padng_left_none">


    <div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 810px; height: 300px; background: #000; overflow: hidden; ">

        <!-- Loading Screen -->
        <div u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
                background-color: #000000; top: 0px; left: 0px;width: 100%;height:100%;"> </div>
            <div style="position: absolute; display: block; background: url(<?php echo SiteCSSURL . 'images/'; ?>loading.gif) no-repeat center center;
                top: 0px; left: 0px;width: 100%;height:100%;"> </div>
        </div>

        <!-- Slides Container -->
        <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 600px; height: 300px;overflow: hidden;">
            <div> <img u="image" src="<?php echo SiteImagesURL; ?>02.jpg" />
                <div u="thumb"> <img class="i" src="<?php echo SiteImagesURL; ?>thumb-02.jpg" />
                    <div class="t">Banner Rotator</div>
                    <div class="c">360+ touch swipe slideshow effects</div>
                </div>
            </div>
            <div> <img u="image" src="<?php echo SiteImagesURL; ?>03.jpg" />
                <div u="thumb"> <img class="i" src="<?php echo SiteImagesURL; ?>thumb-03.jpg" />
                    <div class="t">Image Gallery</div>
                    <div class="c">Image gallery with thumbnail navigation</div>
                </div>
            </div>
            <div> <img u="image" src="<?php echo SiteImagesURL; ?>01.jpg" />
                <div u="thumb"> <img class="i" src="<?php echo SiteImagesURL; ?>thumb-01.jpg" />
                    <div class="t">Carousel</div>
                    <div class="c">Touch swipe, mobile device optimized</div>
                </div>
            </div>
            <div> <img u="image" src="<?php echo SiteImagesURL; ?>02.jpg" />
                <div u="thumb"> <img class="i" src="<?php echo SiteImagesURL; ?>thumb-02.jpg" />
                    <div class="t">Themes</div>
                    <div class="c">30+ professional themems + growing</div>
                </div>
            </div>
            <div> <img u="image" src="<?php echo SiteImagesURL; ?>01.jpg" />
                <div u="thumb"> <img class="i" src="<?php echo SiteImagesURL; ?>thumb-01.jpg" />
                    <div class="t">Tab Slider</div>
                    <div class="c">Tab slider with auto play options</div>
                </div>
            </div>
        </div>

        <!--#region ThumbnailNavigator Skin Begin -->

        <div u="thumbnavigator" class="jssort11" style="left: 605px; top:0px;">
            <!-- Thumbnail Item Skin Begin -->
            <div u="slides" style="cursor: default;">
                <div u="prototype" class="p" style="top: 0; left: 0;">
                    <div u="thumbnailtemplate" class="tp"></div>
                </div>
            </div>
            <!-- Thumbnail Item Skin End -->
        </div>
        <!--#endregion ThumbnailNavigator Skin End -->
    </div>

            <div class="clearfix"></div>
            <div class="blank_padding">&nbsp;</div>

    <div class="topseller_zne">
        <h2>Shop by brand</h2>

    </div>

          <!------------  SORT SECTION ---->
          <div class="padng_fftn">
              
              <div class="toolbar">
                <div class="sorter">                  
                  <div class="sort-by">
                    
                    <ul class="list-inline js-p-sort">
                        <li><label>Sort By :</label></li>
                        <li><a href="javascript://" data-content="popular" <?php if ($sort == "popular"): ?>class="active" <?php endif; ?>>Popularity</a></li>
                        <li><a href="javascript://" data-content="lowestPrice" <?php if ($sort == "lowestPrice"): ?>class="active" <?php endif; ?>>Price</a></li>
                        <li><a href="javascript://" data-content="isNew" <?php if ($sort == "isNew"): ?>class="active" <?php endif; ?>>New</a></li>
                    </ul>
                    <a title="Set Descending Direction" href="#" class="category-asc ic ic-arrow-down"></a> </div>
                </div>
                
              </div>
              <!------  Product Section  --->
              
              <div class="bst_sllng">
                <h2 class="js-header-title"><?=$bheader?$bheader:$branddetails->title?></h2>
                <div class="row js-product-area t-product-carousel-row">
                    <?php if (isset($products['products']) && $products['products']): ?>
    <?php foreach ($products['products'] as $pkey => $pro): ?>
        <div class="item col-md-3 col-sm-3 col-xs-6">
            <div class="prodct_box prodct_box1">
                <?php if ($pro->qty < $pro->minQty): ?>
                <a href="javascript://">
                    <?php else : ?>
                    <a href="<?= BASE_URL . 'product/details/' . base64_encode($pro->productId); ?>">
                        <?php endif; ?>
                        <img src="<?= HOME_LISTING . $pro->pImage; ?>" class="img-responsive"/>
                        <?php if ($pro->qty < $pro->minQty): ?>
                            <!--<div class="ch-info">
                                <h3><i class="fa fa-thumbs-o-down"></i> &nbsp;Out of Stock</h3>
                            </div>-->
                            <h4>Out Of Stock</h4>
                        <?php else : ?>
                            <div class="ch-info">
                                <h3><i class="fa fa-truck"></i> &nbsp;Add to Truck</h3>
                            </div>
                        <?php endif; ?>
                    </a>

                    <p><?= $pro->title; ?></p>
                    <!--<ul class="star">
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star"></i></li>
                        <li><i class="fa fa-star-o"></i></li>
                    </ul>-->
                    <p><?php echo $currencySymbol.' '.$pro->lowestPrice . ' - ' .$currencySymbol.' '. $pro->heighestPrice; ?></p>

                    <p>
                        <?php if ($pro->qty < $pro->minQty): ?>
                            <a href="javascript://">View Details &nbsp;<i class="fa fa-caret-right"></i></a>
                        <?php else: ?>
                            <a href="<?= BASE_URL . 'product/details/' . base64_encode($pro->productId); ?>">View
                                Details &nbsp;<i class="fa fa-caret-right"></i></a>
                        <?php endif; ?>
                    </p>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-danger" role="alert"><h3><i class="fa fa-exclamation-triangle"></i> &nbsp; &nbsp; Oops! No
            products found as per your wish!</h3></div>
<?php endif; ?>
                </div>
                
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <button class="btn btn-info btn-lg btn-block load_more" <?php if ($show_loads): ?>style="display: none;"<?php endif; ?>>Load more...</button>
                        <div class="animation_image" style="display:none;"><img src="<?php echo SiteImagesURL; ?>ajax-loader.gif"> Loading...</div>
                    </div>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>    
  </div>
</article>
<script type="text/javascript" src="<?php echo SiteJSURL; ?>jassor/jssor.js"></script>
<script type="text/javascript" src="<?php echo SiteJSURL; ?>jassor/jssor.slider.js"></script>
<script src="<?php echo SiteJSURL; ?>ion.rangeSlider.js"></script>
<script type="text/javascript">
    total_pages = <?= $total_pages ?>;
jQuery(document).ready(function () {
    /*jQuery(".category_inner").click(function(){
            jQuery('.cate_cont').toggle();
            jQuery('.left_nav').toggle();
        });*/
    var owl = $("#owl-demo4");		
    owl.owlCarousel({		
          items : 3, //10 items above 1000px browser width
          itemsDesktop : [1024,4], //5 items between 1000px and 901px
          itemsDesktopSmall : [900,3], // 3 items betweem 900px and 601px
          itemsTablet: [600,2], //2 items between 600 and 0;
          itemsMobile : 2, // itemsMobile disabled - inherit from itemsTablet option
          pagination : false,
          autoPlay: true,
          navigation : false,

    });
    /*$("#range_03").ionRangeSlider({
        type: "double",
        grid: true,
        min: 0,
        max: 1000,
        from: 200,
        to: 800,
        prefix: "$"
    });*/
    
    var options = {
        $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
        $AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
        $AutoPlayInterval: 3000,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
        $PauseOnHover: 1,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, 4 freeze for desktop, 8 freeze for touch device, 12 freeze for desktop and touch device, default value is 1
        $Loop: 0,                                       //[Optional] Enable loop(circular) of carousel or not, 0: stop, 1: loop, 2 rewind, default value is 1

        $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
        $SlideDuration: 500,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
        $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
        //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
        //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
        $SlideSpacing: 5, 					                //[Optional] Space between each slide in pixels, default value is 0
        $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
        $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
        $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
        $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
        $DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

        $ThumbnailNavigatorOptions: {
            $Class: $JssorThumbnailNavigator$,              //[Required] Class to create thumbnail navigator instance
            $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always

            $Loop: 2,                                       //[Optional] Enable loop(circular) of carousel or not, 0: stop, 1: loop, 2 rewind, default value is 1
            $AutoCenter: 3,                                 //[Optional] Auto center thumbnail items in the thumbnail navigator container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 3
            $Lanes: 1,                                      //[Optional] Specify lanes to arrange thumbnails, default value is 1
            $SpacingX: 4,                                   //[Optional] Horizontal space between each thumbnail in pixel, default value is 0
            $SpacingY: 4,                                   //[Optional] Vertical space between each thumbnail in pixel, default value is 0
            $DisplayPieces: 4,                              //[Optional] Number of pieces to display, default value is 1
            $ParkingPosition: 0,                            //[Optional] The offset position to park thumbnail
            $Orientation: 2,                                //[Optional] Orientation to arrange thumbnails, 1 horizental, 2 vertical, default value is 1
            $DisableDrag: false                             //[Optional] Disable drag or not, default value is false
        }
    };
<?php //if (!$is_last): ?>
    var jssor_slider1 = new $JssorSlider$("slider1_container", options);

    //responsive code begin
    //you can remove responsive code if you don't want the slider scales while window resizes
    function ScaleSlider() {
        var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
        if (parentWidth) {
            var sliderWidth = parentWidth;
            //console.log(sliderWidth);
            if (sliderWidth < 720) {
                sliderWidth = sliderWidth - 30;
            }
            else if (sliderWidth == 750) {
                sliderWidth = sliderWidth - 30;
            }
            else if (sliderWidth > 721) {
                sliderWidth = sliderWidth - 15;
            }

            //keep the slider width no more than 810
            //sliderWidth = Math.min(sliderWidth, 810);

            jssor_slider1.$ScaleWidth(sliderWidth);
        }
        else
            window.setTimeout(ScaleSlider, 30);
    }
    ScaleSlider();

    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);
    //responsive code end
<?php //endif; ?>
});
</script> 
<!-- bin/jquery.slider.min.js -->
<script type="text/javascript" src="<?php echo SiteJSURL; ?>jquery.slider.min.js"></script>
<script type="text/javascript" src="<?php echo SiteJSURL; ?>draggable-0.1.js"></script>
<script type="text/javascript" src="<?php echo SiteJSURL; ?>jquery.slider.js"></script>
<script type="text/javascript" charset="utf-8">
jQuery("#Slider1").slider({ from: 0, to: 100000, step: 100, smooth: true, round: 0, dimension: "&nbsp;<i class=\"fa fa-inr\"></i>", skin: "plastic" });
</script>
<!-- end -->
<?php echo $footer; ?>