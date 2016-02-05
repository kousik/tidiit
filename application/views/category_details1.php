<?php echo $html_heading; echo $header;?>
</div>
<!-- bin/jquery.slider.min.css -->
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>jquery.slider.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>jslider.css" type="text/css">
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>jslider.plastic.css" type="text/css">
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>ion.rangeSlider.skinHTML5.css" />
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>ion.rangeSlider.css" />
<!-- end -->
</header>
<script type="text/javascript">
    jQuery(document).ready(function(){});
</script>

<article>
  <div class="container">
    <div class="categrs_bannr">
      <div class="row">
        <div class="col-md-3 col-sm-12 padng_right_none">
          <?php echo $main_menu;?>
          <!--<div class="shop_by">
            <h2>Shop By</h2>
            <h5>Price</h5>
            <form method="get">
              <input id="range_03" class="irs-hidden-input" readonly style="display:none;">
              <input type="submit" class="button-search" value="Sreach" />
            </form>
          </div>-->
          
          <div class="block block-layered-nav block-layered-nav--no-filters">
            <div class="block-title all_catgrs"> <strong><span>Shop By</span></strong> </div>
            <div class="block-content toggle-content">
              
               <dl id="narrow-by-list">
                <dt class="odd">Price</dt>
                <dd class="odd">
                  <ol>
                    <li> <a href="price=-100"> <span class="price">$0.00</span> - <span class="price">$99.99</span> <span class="count">(6)</span> </a> </li>
                    <li> <a href="price=100-"> <span class="price">$100.00</span> and above <span class="count">(5)</span> </a> </li>
                  </ol>
                </dd>
                <dt class="even">Manufacturer</dt>
                <dd class="even">
                  <ol>
                    <li> <a href=""> TheBrand <span class="count">(8)</span> </a> </li>
                    <li> <a href=""> Company <span class="count">(4)</span> </a> </li>
                    <li> <a href=""> LogoFashion <span class="count">(1)</span> </a> </li>
                  </ol>
                </dd>
                <!--<dt class="odd">Color</dt>
                <dd class="odd">
                  <ol class="configurable-swatch-list">
                    <li style="line-height: 19px;"> <a class="swatch-link has-image" href=""> <span style="height:17px; width:17px;" class="swatch-label"> <img width="15" height="15" title="White" alt="White" src="http://ultimo.infortis-themes.com/demo/media/catalog/swatches/1/15x15/media/white.png"> </span> <span class="count">(5)</span> </a> </li>
                    <li style="line-height: 19px;"> <a class="swatch-link has-image" href=""> <span style="height:17px; width:17px;" class="swatch-label"> <img width="15" height="15" title="Gray" alt="Gray" src="http://ultimo.infortis-themes.com/demo/media/catalog/swatches/1/15x15/media/gray.png"> </span> <span class="count">(3)</span> </a> </li>
                    <li style="line-height: 19px;"> <a class="swatch-link has-image" href=""> <span style="height:17px; width:17px;" class="swatch-label"> <img width="15" height="15" title="Dark Gray" alt="Dark Gray" src="http://ultimo.infortis-themes.com/demo/media/catalog/swatches/1/15x15/media/dark-gray.png"> </span> <span class="count">(3)</span> </a> </li>
                    <li style="line-height: 19px;"> <a class="swatch-link has-image" href=""> <span style="height:17px; width:17px;" class="swatch-label"> <img width="15" height="15" title="Black" alt="Black" src="http://ultimo.infortis-themes.com/demo/media/catalog/swatches/1/15x15/media/black.png"> </span> <span class="count">(5)</span> </a> </li>
                    <li style="line-height: 19px;"> <a class="swatch-link has-image" href=""> <span style="height:17px; width:17px;" class="swatch-label"> <img width="15" height="15" title="Dark Blue" alt="Dark Blue" src="http://ultimo.infortis-themes.com/demo/media/catalog/swatches/1/15x15/media/dark-blue.png"> </span> <span class="count">(1)</span> </a> </li>
                    <li style="line-height: 19px;"> <a class="swatch-link has-image" href=""> <span style="height:17px; width:17px;" class="swatch-label"> <img width="15" height="15" title="Red" alt="Red" src="http://ultimo.infortis-themes.com/demo/media/catalog/swatches/1/15x15/media/red.png"> </span> <span class="count">(1)</span> </a> </li>
                    <li style="line-height: 19px;"> <a class="swatch-link has-image" href=""> <span style="height:17px; width:17px;" class="swatch-label"> <img width="15" height="15" title="Blue" alt="Blue" src="http://ultimo.infortis-themes.com/demo/media/catalog/swatches/1/15x15/media/blue.png"> </span> <span class="count">(2)</span> </a> </li>
                  </ol>
                </dd>-->
                <dt class="odd">Size</dt>
                <dd class="odd">
                  <ol class="configurable-swatch-list">
                    <li> <a class="swatch-link" href="size=3"> <span class="swatch-label"> S </span> <span class="count">(5)</span> </a> </li>
                    <li> <a class="swatch-link" href="size=4"> <span class="swatch-label"> M </span> <span class="count">(6)</span> </a> </li>
                    <li> <a class="swatch-link" href="size=5"> <span class="swatch-label"> L </span> <span class="count">(5)</span> </a> </li>
                    <li> <a class="swatch-link" href="size=6"> <span class="swatch-label"> XL </span> <span class="count">(4)</span> </a> </li>
                  </ol>
                </dd>
              </dl>
              
            </div>
          </div>
          
        </div>
        
        <!---- Product Listing right  --->
        
        <div class="col-md-9 col-sm-12 padng_left_none">
        
          <div class="img_cont"> 
            
          <img src="<?php echo SiteImagesURL;?>long01.jpg" class="img-responsive" />
            <!--<h3>Best Deals on Fasion</h3>
            <!--#endregion ThumbnailNavigator Skin End --> 
            
          </div>
          <div class="clearfix"></div>
          <div class="blank_padding">&nbsp;</div>
          <div class="topseller_zne">
            <div id="demo">
              <div id="owl-demo4" class="owl-carousel">
                <div class="item">
                  <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" />
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
                  <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img1.png" class="img-responsive" /> 
                  	<h4>New Arrival</h4>
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
                  <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img2.png" class="img-responsive" />
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
                  <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" />
                  	<h4>On Sale</h4>
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
          
          <!------------  SORT SECTION ---->
          <div class="toolbar">
            <div class="sorter">
              <p class="amount"> Items 1 to 8 of 11 total </p>
              <div class="sort-by">
                <label>Sort By</label>
                <select onchange="<!--setLocation(this.value)-->">
                  <option selected="selected" value="#"> Position </option>
                  <option value="#"> Name </option>
                  <option value="#"> Price </option>
                </select>
                <a title="Set Descending Direction" href="#" class="category-asc ic ic-arrow-down"></a> </div>
              <div class="limiter">
                <label>Show</label>
                <select onchange="<!--setLocation(this.value)-->">
                  <option value="#"> 4 </option>
                  <option selected="selected" value="#"> 8 </option>
                  <option value="#"> 12 </option>
                  <option value="#"> 15 </option>
                  <option value="#"> 30 </option>
                  <option value="#"> 80 </option>
                  <option value="#"> All </option>
                </select>
                <span class="per-page"> per page</span> </div>
            </div>
            <!-- end: sorter -->
            
            <div class="pager">
              <div class="pages"> <strong>Page:</strong>
                <ol>
                  <li class="current">1</li>
                  <li><a href="#">2</a></li>
                </ol>
              </div>
            </div>
          </div>
          <!------  Product Section  --->
          
          <div class="bst_sllng cat_list">
            <h2>Category Name</h2>
            <div class="row">
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img1.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img2.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img1.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img2.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img1.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img2.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img1.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
              <div class="item col-md-4 col-sm-4 col-xs-6">
                <div class="prodct_box"> <a href="#"> <img src="<?php echo SiteImagesURL;?>prdct_img2.png" class="img-responsive" />
                  <div class="ch-info">
                    <h3><img src="<?php echo SiteImagesURL;?>add_crt.png" /> &nbsp;Add to cart</h3>
                  </div>
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
                  <p><a href="#">View Details &nbsp;<i class="fa fa-caret-right"></i></a></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>    
  </div>
</article>
<script src="<?php echo SiteJSURL;?>ion.rangeSlider.js"></script> 
<script src="<?php echo SiteJSURL;?>jquery.colorbox.js"></script> 
<script type="text/javascript">
$(document).ready(function() {		
    var owl = $("#owl-demo4");		
    owl.owlCarousel({		
          items : 3, //10 items above 1000px browser width
          itemsDesktop : [1024,3], //5 items between 1000px and 901px
          itemsDesktopSmall : [900,3], // 3 items betweem 900px and 601px
          itemsTablet: [600,2], //2 items between 600 and 0;
          itemsMobile : 2, // itemsMobile disabled - inherit from itemsTablet option
          pagination : false,
          autoPlay: true,
          navigation : false,

    });

    // Custom Navigation Events
    $(".topseller_next").click(function(){
          owl.trigger('owl.next');
    })
    $(".topseller_prev").click(function(){
          owl.trigger('owl.prev');
    })




    /* color_box JS  */
    //Examples of how to assign the Colorbox event to elements
          $(".inline").colorbox({inline:true, width:"96%"});
    /* color_box JS  */
    $( "#button1" ).click(function() {

    $( "#t1" ).toggle( "slow" );

    });

    $("#range_03").ionRangeSlider({
          type: "double",
          grid: true,
          min: 0,
          max: 1000,
          from: 200,
          to: 800,
          prefix: "$"
    });
});        
</script>
<!-- bin/jquery.slider.min.js -->
<script type="text/javascript" src="<?php echo SiteJSURL;?>jquery.slider.min.js"></script>
<script type="text/javascript" src="<?php echo SiteJSURL;?>draggable-0.1.js"></script>
<script type="text/javascript" src="<?php echo SiteJSURL;?>jquery.slider.js"></script>
<script type="text/javascript" charset="utf-8">
jQuery("#Slider1").slider({ from: 0, to: 100000, step: 500, smooth: true, round: 0, dimension: "&nbsp;Rs", skin: "plastic" });
</script>
<!-- end -->
<?php echo $footer;?>