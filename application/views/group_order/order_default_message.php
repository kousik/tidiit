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
                                        
                                        <div class="list-group gen_infmtn">
                                            <h6>Tidiit Information for you</h6>
                                            <div class="active row grouporder_id">
                                                
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

<?php echo $footer;?>