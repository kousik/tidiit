<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo $html_heading; echo $header;?>
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

                                    <div class="col-md-12 col-sm-12">

                                        <div class="gen_infmtn">

                                            <h6>Groups order Id <strong>53432424</strong> </h6>

                                        </div>


                                        <div class="group-selection">
                                            <label class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio"  name="selectgroup" id="optionsRadios1" class="js-order-group" value="exists">
                                                </span>
                                                <span class="group-label">Please select a group from your existing groups.</span>
                                            </label><!-- /input-group -->


                                            <label class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio"  name="selectgroup" id="optionsRadios2" class="js-order-group" value="new">
                                                </span>
                                                <span class="group-label">Please create a new group.
                                                </span>
                                            </label><!-- /input-group -->

                                        </div>
                                        <div class="clearfix"></div>

                                        <div class="panel panel-default">
                                            <div class="panel-body">

                                                <div class="form-horizontal">
                                                    <label for="input3" class="col-sm-7 control-label">Please enter your no of Quantity of this Order</label>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" id="input3" placeholder="">
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <button type="button" class="btn btn-primary pull-right">Invite Group User to Process the Order</button>
                                        <div class="clearfix"></div>








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