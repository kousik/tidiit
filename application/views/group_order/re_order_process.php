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
                                            <h6>Buying Club Re-order Process [TIDIIT-OD-<?=$order->orderId?>]</h6>
                                            <div class="active row grouporder_id">
                                                <h4> You can choose one of the below operation for this order.</h4>
                                                <div class="col-xs-6 col-md-3"></div>
                                                <div class="col-xs-6 col-md-6">
                                                    
                                                    <a href="<?=BASE_URL."shopping/group-re-order-accept-process/".base64_encode($order->orderId*226201)."/".base64_encode(100)?>" class="btn btn-info btn-lg  btn-block">Re-order by me</a>
                                                    <a href="<?=BASE_URL."edit_groups_re_order/".base64_encode($group->groupId*987654321)."/".base64_encode($order->orderId)?>" class="btn btn-success btn-lg btn-block">Send Buyer Club member for Re-order</a>
                                                </div>
                                                <div class="col-xs-6 col-md-3"></div>
                                                
                                                

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