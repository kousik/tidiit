<?php echo $html_heading; echo $header;?>
</div>
</header>
<?php echo $common_how_it_works;?>
<?php echo $feedback;?>
<article>
        	<div class="container inner_page">
              <div class="row">
                 <div class="col-md-3 col-sm-3 padng_right_none">
                     <div class="cate_cont" style="display:none;">
                 	<?php echo $main_menu;?>
                     </div>     
                     <div class="left_nav">
                         <h4>Contact Us</h4>
                         <ul>
                             <li><a href="<?php echo BASE_URL.'content/press/Nw==/';?>">Press Releases</a></li>
                             <li><a href="<?php echo BASE_URL.'content/careers/Ng==/';?>">Careers</a></li>
                             <li><a href="<?php echo BASE_URL.'content/privacy-policy/Mw==/';?>">Security, Privacy &amp; Accessibility</a></li>
                             <!--<li><a href="#">Delivery Speeds &amp; Rates</a></li>
                             <li><a href="#">Returns Are Easy</a></li>-->
                         </ul>
                     </div>
                     
                 </div>
                 
                  <div class="col-md-9 col-sm-9">
                      <div class="page_content">
                        <h2>Contact Us</h2>
                        <div>
                            <h4>Business Address</h4>
                            <div>
                                <b>Tidiit Internet Pvt Ltd</b><br>
                                2nd Fllor Prestige Omega No. 104 EPIP Zone<br>
                                Bangalore 560066
                            </div>
                        </div>

                      </div>
                  </div>
                  
                </div>
              </div>
            </div>
        </article>
<?php echo $footer;?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        //Examples of how to assign the Colorbox event to elements
        jQuery(".category_inner").click(function(){
            jQuery('.cate_cont').toggle();
            jQuery('.left_nav').toggle();
        });

    });
</script>