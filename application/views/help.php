<?php echo $html_heading; echo $header;?>
</div>
</header>
<?php echo $common_how_it_works;?>
<?php echo $feedback;?>
<article>

  <div class="container">
        <div class="page_content customer-service-container">
                        <?php /*<h2>Hello. What can we help you with? </h2>
                        <div class="cs-container">
                            <div class="row cs-container-row">
                                <div class="col-sm-4">
                                    <div class="col-sm-4 text-center">
                                        <a href="#">
                                            <img src="https://images-eu.ssl-images-amazon.com/images/G/31/x-locale/cs/help/images/gateway/Box-t3._CB313381664_.png" alt="">
                                        </a>
                                    </div><!-- end column -->
                                    <div class="col-sm-8">
                                        <a href="#" class="text-header">
                                            Your Orders
                                        </a>
                                        <ul class="">
                                            <li><span class="list-text">Track your packages  </span></li>
                                            <li><span class="list-text">Edit or cancel orders  </span></li>
                                        </ul><!-- end list -->
                                    </div><!-- end column -->
                                 </div><!-- end column -->
    
                                 <div class="col-sm-4">
                                    <div class="col-sm-4 text-center">
                                        <a href="#">
                                            <img src="https://images-eu.ssl-images-amazon.com/images/G/31/x-locale/cs/help/images/gateway/returns-box-blue._CB292186245_.png" alt="">
                                        </a>
                                    </div><!-- end column -->
                                    <div class="col-sm-8">
                                        <a href="#" class="text-header">
                                            Returns and refunds
                                        </a>
                                        <ul class="">
                                            <li><span class="list-text">Track your packages  </span></li>
                                            <li><span class="list-text">Edit or cancel orders  </span></li>
                                        </ul><!-- end list -->
                                    </div><!-- end column -->
                                 </div><!-- end column -->
    
                                 <div class="col-sm-4">
                                    <div class="col-sm-4 text-center">
                                        <a href="#">
                                            <img src="https://images-eu.ssl-images-amazon.com/images/G/31/x-locale/cs/help/images/gateway/manage-address._CB292186245_.png" alt="">
                                        </a>
                                    </div><!-- end column -->
                                    <div class="col-sm-8">
                                        <a href="#" class="text-header">
                                            Your Orders
                                        </a>
                                        <ul class="">
                                            <li><span class="list-text">Track your packages  </span></li>
                                            <li><span class="list-text">Edit or cancel orders  </span></li>
                                        </ul><!-- end list -->
                                    </div><!-- end column -->
                                 </div><!-- end column -->
                            </div><!-- end row -->
    
                            <div class="row cs-container-row">
                                <div class="col-sm-4">
                                    <div class="col-sm-4 text-center">
                                        <a href="#">
                                            <img src="https://images-eu.ssl-images-amazon.com/images/G/31/x-locale/cs/help/images/gateway/Payments_clear-bg-t3._CB313381665_.png" alt="">
                                        </a>
                                    </div><!-- end column -->
                                    <div class="col-sm-8">
                                        <a href="#" class="text-header">
                                            Your Orders
                                        </a>
                                        <ul class="">
                                            <li><span class="list-text">Track your packages  </span></li>
                                            <li><span class="list-text">Edit or cancel orders  </span></li>
                                        </ul><!-- end list -->
                                    </div><!-- end column -->
                                 </div><!-- end column -->
    
                                 <div class="col-sm-4">
                                    <div class="col-sm-4 text-center">
                                        <a href="#">
                                            <img src="https://images-eu.ssl-images-amazon.com/images/G/31/x-locale/cs/help/images/gateway/IN-gift-card-new._CB292186704_.png" alt="">
                                        </a>
                                    </div><!-- end column -->
                                    <div class="col-sm-8">
                                        <a href="#" class="text-header">
                                            Your Orders
                                        </a>
                                        <ul class="">
                                            <li><span class="list-text">Track your packages  </span></li>
                                            <li><span class="list-text">Edit or cancel orders  </span></li>
                                        </ul><!-- end list -->
                                    </div><!-- end column -->
                                 </div><!-- end column -->
    
                                 <div class="col-sm-4">
                                    <div class="col-sm-4 text-center">
                                        <a href="#">
                                            <img src="https://images-eu.ssl-images-amazon.com/images/G/31/x-locale/cs/help/images/gateway/IN-your-account._CB292186245_.png" alt="">
                                        </a>
                                    </div><!-- end column -->
                                    <div class="col-sm-8">
                                        <a href="#" class="text-header">
                                            Your Orders
                                        </a>
                                        <ul class="">
                                            <li><span class="list-text">Track your packages  </span></li>
                                            <li><span class="list-text">Edit or cancel orders  </span></li>
                                        </ul><!-- end list -->
                                    </div><!-- end column -->
                                 </div><!-- end column -->
                            </div><!-- end row -->
            			</div>
                        */ /* ?>
                        
                       <div class="list-group cs-search">
                       		<div class="list-group-item disabled">
                                <h6>Can't find what you need?</h6>
                                <div class="input-group">
                                  <input type="text" class="form-control" placeholder="Search for solutions...">
                                  <span class="input-group-addon btn-primary btn">Go</span>
                                </div>
                            </div>
                       </div>
                        */?>
                       <div class="cs-tabs">
                            <div class="page-header">
                              <h3>Browse Help topics</h3>
                            </div>
                            
                            <div class="cs-tabs-table clearfix">
                                  <div class="col-sm-3 cs-tabs-table-left">
                                      <!-- Nav tabs -->
                                      <ul class="nav nav-tabs" role="tablist">
                                        <?php $i=0;
                                        foreach ($helpTopicsArr As $k):
                                            $tabTitle=  str_replace(" ","", $k->helpTopics);?>  
                                          <li role="presentation" <?php if($i==0){?>class="active"<?php }?>>
                                              <a href="#<?php echo $tabTitle;?>" aria-controls="<?php echo $tabTitle;?>" role="tab" data-toggle="tab" data-topicsid="<?php echo $k->helpTopicsId;?>" class="showHelpTopicContent"><?php echo $k->helpTopics;?></a>
                                          </li>
                                        <?php $i++; endforeach;?>  
                                      </ul>
                                  </div>
                                <div class="col-sm-9 cs-tabs-table-right">
                                  <!-- Tab panes -->
                                      <div class="tab-content helpTopicsContainer">
                                            <?php $k=$helpTopicsArr[0];
                                                $tabTitle=  str_replace(" ","", $k->helpTopics);?>
                                            <div role="tabpanel" class="tab-pane active" id="<?php echo $tabTitle;?>">
                                                <ul class="category-list">
                                                    <li><h3 class="a-spacing-small"> <?php echo $k->helpTopics;?></h3></li>
                                                    <li>
                                                        <div id="accordion" class="faq-sec">
                                                            <?php
                                                            foreach($helpDataArr AS $k){?>
                                                                <h3><?php echo $k->question;?></h3>
                                                                <div>
                                                                  <p><?php echo $k->answer;?></p>
                                                                </div>
                                                            <?php }?>
                                                        </div>
                                                    </li>              
                                                </ul>
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
        
        jQuery( "#accordion" ).accordion({
            heightStyle: "content"
        });
        
        jQuery(".showHelpTopicContent").on("click",function(){
            var topicsid=jQuery(this).data('topicsid');
            myJsMain.commonFunction.showPleaseWait();
            jQuery.ajax({
                type: "POST",
                url: myJsMain.baseURL+'<?php echo 'ajax/show_help_topics_by_id/'?>',
                data:"topicsid="+topicsid,
                dataType:'json',
                success:function(data){
                    jQuery('.helpTopicsContainer').html(data.content);
                    myJsMain.commonFunction.hidePleaseWait();
                }
            });
        });
    });
</script>