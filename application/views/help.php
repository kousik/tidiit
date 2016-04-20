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
                            
                           <div class="cs-tabs-table clearfix" style="border-radius: 4px;">
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
                                                        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                                            <?php foreach($helpDataArr AS $key => $k){?>
                                                            <div class="panel panel-default">
                                                              <div class="panel-heading" role="tab" id="headingOne">
                                                                <h4 class="panel-title">
                                                                    <a role="button" data-toggle="collapse" style="text-decoration: none;cursor: pointer;" data-parent="#accordion" href="#collapse<?=$key;?>" aria-expanded="true" aria-controls="collapseOne">
                                                                        <i class="fa fa-info-circle"></i> &nbsp; <?php echo $k->question;?>
                                                                  </a>
                                                                </h4>
                                                              </div>
                                                              <div id="collapse<?=$key;?>" class="panel-collapse collapse <?php if($key==0){ echo 'in';}?>" role="tabpanel" aria-labelledby="headingOne">
                                                                <div class="panel-body">
                                                                  <?php echo $k->answer;?>
                                                                </div>
                                                              </div>
                                                            </div>
                                                            <?php }?>
                                                          </div>
                                                    </li>              
                                                </ul>
                                            </div>
                                      </div>
                                    <p> Are you want more help ?</p>
                                    <button class="btn btn-info" data-toggle="modal" data-target="#exampleModal">Submit your query </button>
                                </div>
                            </div>
                      </div>
    </div>
  </div>
</article>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo BASE_URL.'index/submit_help_query/';?>" name="help-form" class="help-form" id="wtil-cms-form" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Want to get more info ?</h4>
            </div>
            <div class="modal-body">

                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Choose your topic:</label>
                        <div class="js-replace">
                        <?php if($questions):?>
                            <select name="help_subject" class="form-control" id="help-subject">
                                <?php foreach($questions as $q):?>
                                <option value="<?=$q?>"><?=$q?></option>
                                <?php endforeach;?>
                            </select>
                        <?php else:?>
                            <input class="form-control" name="help_subject" id="help_subject">
                        <?php endif;?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Name:</label>
                        <input class="form-control" name="name" id="help_name">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Email:</label>
                        <input class="form-control" name="email" id="help_email">
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Phone Number:</label>
                        <input class="form-control" name="phone" id="help_phone">
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Type your query here:</label>
                        <textarea class="form-control" name="help_message" id="message-text"></textarea>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" name="wtil-post-submit" value="Send us now!">
                <div class="et-ajax-loader-global cms-module-loader pull-left"><span>Processing...</span></div>
                <div class="cms-ajax-feedback"></div>
                <p class="text-info pull-left">Our customer care executive with you for 24X7 . Happy visiting! </p>
            </div>
            </form>
        </div>
    </div>
</div>
<?php echo $footer;?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        //Examples of how to assign the Colorbox event to elements
        jQuery(".category_inner").click(function(){
            jQuery('.cate_cont').toggle();
            jQuery('.left_nav').toggle();
        });
        
        /*jQuery( "#accordion" ).accordion({
            heightStyle: "content"
        });*/
        
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
                    var subject = '<input class="form-control" name="help_subject" id="help_subject">';
                    if(data.qts && data.qts.length > 0){
                        var subject = '<select name="help_subject" class="form-control" id="help-subject">';
                        for (i = 0; i < data.qts.length; i++) {
                            subject += "<option value='"+data.qts[i] + "'>"+data.qts[i] + "</option>";
                        }
                        subject += "</select>";
                    }
                    jQuery('div.js-replace').html(subject);
                    myJsMain.commonFunction.hidePleaseWait();

                }
            });
        });
    });
</script>