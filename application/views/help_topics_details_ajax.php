<?php $tabTitle=  str_replace(" ","",$help_topics_details[0]->helpTopics);?>
<div role="tabpanel" class="tab-pane active" id="<?php echo $tabTitle;?>">
    <ul class="category-list">
        <li><h3 class="a-spacing-small"> <?php echo $help_topics_details[0]->helpTopics;?></h3></li>
        <li>
            <div id="accordion" class="faq-sec">
                <?php foreach($helpDataArr AS $k){?>
                    <h3><?php echo $k->question;?></h3>
                    <div>
                      <p><?php echo $k->answer;?></p>
                    </div>
                <?php }?>
            </div>
        </li>              
    </ul>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery( "#accordion" ).accordion({
            heightStyle: "content"
        });
    });
</script>    