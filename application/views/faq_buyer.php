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
                         <h4>Buyer FAQ</h4>
                     </div>
                     
                 </div>
                 
                  <div class="col-md-9 col-sm-9">
                      <div class="page_content">
                          <div id="accordion" class="faq-sec">
                            <?php foreach($faqDataArr AS $k){?>
                                <h3><?php echo $k->question;?></h3>
                                <div>
                                  <p><?php echo $k->answer;?></p>
                                </div>
                            <?php }?>
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
        $( "#accordion" ).accordion({
            heightStyle: "content"
        });
    });
</script>