<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo $html_heading; echo $header;?>
<script src="<?php echo SiteJSURL;?>user-all-my-js.js" type="text/javascript"></script>
</div>
</header>
<article>

    <div class="container">

        <div class="row">

            <div class="col-md-12 col-sm-12 productInner">

                <div class="page_content">

                    <div class="row">

                        <?= $userMenu ?>

                        <div class="col-md-9 wht_bg">

                            <!-- Tab panes -->

                            <div class="tab_dashbord">

                                <div class="active row">
                                    
                                    <div class="col-md-12 col-sm-12">
                                        <h3 class="log-title">My Notifications</h3>
                                        <div class="list-group">
                                            <?php if($notifications):?>
                                            <?php foreach($notifications as $key => $nfn): ?>
                                            <div class="list-group-item <?php if($nfn->isRead == 0):?>list-group-item-warning<?php endif;?>" id="not-<?=$nfn->id;?>">
                                                <div class="col-md-2"><span class="label label-primary"><?=$nfn->nType;?></span></div>
                                                <a href="javascript://" class="js-notfy-view" data-nd="<?=base64_encode($nfn->id*226201);?>" data-tp="<?=$nfn->nType;?>"><?=$nfn->nTitle;?></a>
                                                <div class="col-md-offset-1 pull-right">
                                                <a type="button" class="btn btn-info btn-xs js-notfy-view" data-nd="<?=base64_encode($nfn->id*226201);?>" data-tp="<?=$nfn->nType;?>"><i class="fa fa-info-circle"></i></a>
                                                <a type="button" class="btn btn-danger btn-xs js-notfy-delete" data-nd="<?=base64_encode($nfn->id*226201);?>"><i class="fa fa-trash"></i></a>
                                                </div>
                                            </div>
                                            <?php endforeach;?>
                                            <?php else:?>
                                            <div class="list-group-item list-group-item-info">
                                                <h4>You have no any notification!</h4>
                                            </div> 
                                            <?php endif;?>
                                        </div>
                                        <?php	if($this->pagination->create_links()):?>
                                            <div class="row">
                                                <div class="col-md-12 text-center"><?=$this->pagination->create_links();?></div></div>
                                        <?php 	endif;  ?>
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



<script type="text/javascript">

    jQuery(document).ready( function() {
        
    });
</script>
<?php echo $footer;?>