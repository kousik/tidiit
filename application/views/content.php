<?php echo $html_heading; echo $header;?>
</div>
</header>

<article>
        	<div class="container inner_page">
              <div class="row">
                 <div class="col-md-3 col-sm-3 padng_right_none">
                     <div class="cate_cont" style="display:none;">
                 	<?php echo $main_menu;?>
                     </div>     
                     <div class="left_nav">
                         <h4><?php echo $contentDetails[0]->title;?></h4>
                         <ul>
                             <li><a href="#">Press Releases</a></li>
                             <li><a href="#">Careers</a></li>
                             <li><a href="#">Security, Privacy &amp; Accessibility</a></li>
                             <li><a href="#">Delivery Speeds &amp; Rates</a></li>
                             <li><a href="#">Returns Are Easy</a></li>
                         </ul>
                     </div>
                     
                 </div>
                 
                  <div class="col-md-9 col-sm-9">
                      <div class="page_content">
                        <h2><?php echo $contentDetails[0]->title;?></h2>
                        <div>
                            <?php echo $contentDetails[0]->body;?>
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