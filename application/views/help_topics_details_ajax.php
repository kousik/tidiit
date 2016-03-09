<?php $tabTitle=  str_replace(" ","",$help_topics_details[0]->helpTopics);?>
<div role="tabpanel" class="tab-pane active" id="<?php echo $tabTitle;?>">
    <ul class="category-list">
        <li><h3 class="a-spacing-small"> <?php echo $help_topics_details[0]->helpTopics;?></h3></li>
        <li>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <?php foreach($helpDataArr AS $key => $k){?>
            <div class="panel panel-default">
              <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$key;?>" aria-expanded="true" aria-controls="collapseOne">
                    <?php echo $k->question;?>
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
<script type="text/javascript">
    jQuery(document).ready(function(){
        /*jQuery( "#accordion" ).accordion({
            heightStyle: "content"
        });*/
    });
</script>    