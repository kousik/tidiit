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
                <div class="tab_dashbord">
                    <div class="active row">

                        <div class="col-md-12 col-sm-12">

                            <div class="list-group">
                                <div class="active">

                                    <?php if($this->session->flashdata('error')): ?>
                                    <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> <?=$this->session->flashdata('error')?></div>
                                    <?php elseif($this->session->flashdata('msg')):?>
                                    <div class="alert alert-success" role="alert"><i class="fa fa-check"></i> <?=$this->session->flashdata('msg')?></div>
                                    <?php endif;?>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
          </div>
        </div>
      </div>
    </div>    
  </div>
</article>


<script type="text/javascript" src="<?php echo SiteJSURL;?>jquery.slider.min.js"></script>
<script type="text/javascript" src="<?php echo SiteJSURL;?>draggable-0.1.js"></script>
<script type="text/javascript" src="<?php echo SiteJSURL;?>jquery.slider.js"></script>
<script type="text/javascript" charset="utf-8">
jQuery("#Slider1").slider({ from: 0, to: 100000, step: 500, smooth: true, round: 0, dimension: "&nbsp;Rs", skin: "plastic" });
</script>
<!-- end -->
<?php echo $footer;?>