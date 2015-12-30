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

                                <div class="active row grouporder_id">
                                    <div class="alert alert-success" role="alert">
                                        Congratulations! Your order successfully placed.
                                    </div>
                                    <br /><br />
                                    <br /><br />
                                    <a href="<?=BASE_URL?>my-orders" class="btn btn-success"> View your orders</a>
                                    <br /><br />
                                    Please follow up with your Buying Club Member to complete the order by completing the full payment.<br />
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