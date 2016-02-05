<?php
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
                                   <form action="#" method="post" name="start_groups_order" id="start_groups_order"> 
                                    <input type="hidden" id="js-order-info" name="orderId" value="<?=$orderId?>" data-groupid="<?=$groupId?>" data-userid="<?=$user->userId?>" >
                                    
                                    <div class="col-md-12 col-sm-12">

                                        <div class="gen_infmtn">

                                            <h6>Buying Club order ID <span class="label label-success"><?=$orderId?></span> </h6>

                                        </div>


                                        <div class="group-selection js-display-selected-group">
                                            <?php if($group):?>
                                            <div class="container-fluid">
                                                <div class="col-md-3 col-sm-3 grp_dashboard" style="margin:0;">
                                                <div class="<?= $group->groupColor ?>">
                                                    <span><i class="fa  fa-group fa-5x"></i></span>
                                                </div>
                                                <div class="grp_title"><?= $group->groupTitle ?></div>
                                            </div>        
                                            <div class="col-md-6">
                                                <h5><strong>Buying Club Leader</strong></h5>
                                                <p class="text-left"><?= $group->admin->firstName ?> <?= $group->admin->lastName ?></p>
                                                <?php if ($group->users): ?>
                                                    <h5><strong>Buying Club Users</strong></h5><?php foreach ($group->users as $ukey => $usr): ?>
                                                        <p class="text-left"><?= $usr->firstName ?> <?= $usr->lastName ?></p>
                                                    <?php endforeach;
                                                endif;
                                                ?>
                                            </div>
                                            </div>
                                            
                                            <div class="clearfix"></div>
                                            <label class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio"  name="selectgroup" id="optionsRadios1" class="js-order-group" value="exists">
                                                </span>
                                                <span class="group-label">Want to change above Buying Club from your existing Buying Club.</span>
                                            </label><!-- /input-group -->


                                            <label class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio"  name="selectgroup" id="optionsRadios2" class="js-order-group" value="new">
                                                </span>
                                                <span class="group-label">Want to create a new Buying Club.
                                                </span>
                                            </label><!-- /input-group -->
                                            <?php else: ?>
                                            <label class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio"  name="selectgroup" id="optionsRadios1" class="js-order-group" value="exists">
                                                </span>
                                                <span class="group-label">Please select a Buying Club from your existing Buying Club.</span>
                                            </label><!-- /input-group -->


                                            <label class="input-group">
                                                <span class="input-group-addon">
                                                    <input type="radio"  name="selectgroup" id="optionsRadios2" class="js-order-group" value="new">
                                                </span>
                                                <span class="group-label">Please create a new Buying Club.
                                                </span>
                                            </label><!-- /input-group -->
                                            <?php endif;?>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="panel js-display-exisit-group"></div>
                                        <div class="panel panel-default">
                                            <div class="panel-body">

                                                <div class="form-horizontal">
                                                    <label for="input3" class="col-sm-7 control-label">Please enter your no of Quantity of this Order<br>Available Qty : ( <?=$availQty?> ). Total Qty : ( <?=$totalQty?>) </label>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" id="js-estd-qty" placeholder="<?=$dftQty?>" value="">
                                                        <input type="hidden" class="form-control" id="js-avail-qty" value="<?=$availQty?>">
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-danger js-message" role="alert" style="display: none;"></div>
                                        
                                        <div class="clearfix"></div>

                                        <button type="button" class="btn btn-primary pull-right js-group-order-process">Invite Buying Club User to Process the Order</button>
                                        <div class="clearfix"></div>
                                    </div>                            

                                   </form>

                                </div>

                            </div>

                        </div>



                    </div>



                </div>

            </div>

        </div>

    </div>

</article> 

<!-- Modal -->
<div class="modal fade" id="createGroupModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create New Buying Club</h4>
        
      </div>
      <form action="#" method="post" name="add_groups_for_order" class="form-horizontal" id="add_groups_for_order"> 
          <input type="hidden" name="groupAdminId" value="<?=$user->userId?>">
      <div class="modal-body">      
          <div class="js-message" style="display:none;"></div>
              <div class="form-group">
                  <label for="groupTitle" class="col-sm-3 control-label">Buying Club Name</label>
                  <div class="col-sm-7">
                  <input type="text" class="form-control" id="groupTitle" name="groupTitle" placeholder="Jane Doe" required>
                  </div>
              </div>
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border">Search Buying Club member by below filters </legend>
                    <div class="form-group">
                        <label for="locality" class="col-sm-3 control-label">Select Country :</label>
                        <div class="col-sm-7">
                            <select name="countryId" class="form-control nova heght_cntrl" id="countryId">
                                <option value="">Select</option>
                                <?php foreach ($countryDataArr AS $k): ?>
                                    <option value="<?= $k->countryId ?>"><?= $k->countryName ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="locality" class="col-sm-3 control-label">Select City :</label>
                        <div class="cityElementPara col-sm-7">
                            <select name="cityId" class="form-control nova heght_cntrl" id="cityId">
                                <option>Select City</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="locality" class="col-sm-3 control-label">Select Zip :</label>
                        <div class="zipElementPara col-sm-7">
                            <select name="zipId" class="form-control nova heght_cntrl" id="zipId">
                                <option>Select Zip</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="locality" class="col-sm-3 control-label">Select Locality :</label>
                        <div class="localityElementPara col-sm-7">
                            <select name="localityId" class="form-control nova heght_cntrl" id="localityId">
                                <option>Select Locality</option>
                            </select>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="locality" class="col-sm-3 control-label">Select Product Category :</label>
                        <div class="col-sm-7">
                            <select name="productType" class="form-control nova heght_cntrl" id="productType">
                                <option value="">Select</option>
                                <?php foreach ($CatArr AS $categoryId=>$categoryName): ?>
                                    <option value="<?= $categoryId ?>"><?= $categoryName ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </fieldset>
              <div class="form-group js-show-group-locality-users">
              </div>
              <div class="js-show-group-users-tags">
                  <div class="form-group"><label class="col-sm-3 control-label">Selected Club Member :</label></div>
              </div>
          
      </div>
      <div class="modal-footer">
        <input type="submit"  class="grpButton" name="creatGrp" id="grpButton" value="Create New Buyer Club" />
      </div>
      </form>    
    </div>
  </div>
</div>
<!-- /.modal -->

<script type="text/javascript">
    var zipDynEle='<select class="form-control nova heght_cntrl" name="zipId" id="zipId" value=""  tabindex="1"><option value="">Select Zip</option></select>';
    var localityDynEle='<select class="form-control nova heght_cntrl" name="localityId" id="localityId" value=""  tabindex="1"><option value="">Select Locality</option></select>';
    var cityDynEle='<select class="form-control nova heght_cntrl" name="cityId" id="cityId" value=""  tabindex="1"><option value="">Select City</option></select>';
    var selected_user_tag_div_content='<div class="form-group"><label class="col-sm-3 control-label">Selected Users :</label></div>';
    //myJsMain.my_billing_address();
    myJsMain.my_create_groups();
    function test1(str) {
        return /^ *[0-9]+ *$/.test(str);
    }
    jQuery(document).ready(function(){
        <?php if($this->session->flashdata('error')): ?>
                $('div.js-message').html('<?=$this->session->flashdata('error')?>');
                $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });                         
        <?php endif;?>
    });
    jQuery(document).ready( function() {
        $('.js-group-popover').popover({html:true,container: 'body'});
        
        jQuery("body").delegate('.js-order-group', "click", function(e){
            e.preventDefault();
            var grp = jQuery(this).val();
            var obj = jQuery(this);
            jQuery(".js-order-group").prop('checked', false);
            if(grp == 'new'){
                jQuery('div.js-display-exisit-group').empty();
                jQuery('#createGroupModalLogin').modal('show');
            } else if( grp == 'exists'){
                myJsMain.commonFunction.showPleaseWait();
                var userId = jQuery('input[id="js-order-info"]').data('userid');
                jQuery.post( myJsMain.baseURL+'ajax/get_my_groups/', {
                    userId: userId
                },
                function(data){ 
                    myJsMain.commonFunction.hidePleaseWait();
                    if(data.contents){
                        jQuery('div.js-display-exisit-group').html(data.contents);                        
                        obj.prop('checked', true);
                    }
                }, 'json' );
            }
        }); 
        
        jQuery("body").delegate('input[id="js-order-info"]', "click", function(e){
            myJsMain.commonFunction.showPleaseWait();
            e.preventDefault();//console.log($(this).data('groupid'));
            var groupId = $(this).attr('data-groupid');
            var jqout = $(this);
            $.post( myJsMain.baseURL+'ajax/get_single_group/', {
                groupId: groupId,
                orderId: $(this).val()
            },
            function(data){ 
                myJsMain.commonFunction.hidePleaseWait();
                if(data.contents){
                    $('div.js-display-exisit-group').empty();
                    $('div.js-display-selected-group').html(data.contents);
                }
            }, 'json' );
        }); 
        
        jQuery("body").delegate('input[class="js-select-group"]', "click", function(e){
            var gid = $(this).val();
            $('input[id="js-order-info"]').attr('data-groupid', gid);
            $('input[id="js-order-info"]').trigger( "click" );            
        });   
        
        jQuery("body").delegate('button.js-group-order-process', "click", function(e){
            var obj = $('input[id="js-order-info"]'); 
            if(!obj.attr('data-groupid')){
                $('div.js-message').html('Please add a Buyer Club!');
                //$('div.js-message').show();
                $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
                return;
            }
            
            if(!$('#js-estd-qty').val()){
                $('div.js-message').html('Please enter your quantity!');
                //$('div.js-message').show();
                $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
                return;
            }
            //console.log($('#js-estd-qty').val()+'==='+$('#js-avail-qty').val());
            var estd = $('#js-estd-qty').val();
            var avail = $('#js-avail-qty').val();
            
            
            if(parseInt(estd) > parseInt(avail) || parseInt(estd) == 0 || !test1(estd)){
                $('div.js-message').html("Please enter valid quantity!");
                //$('div.js-message').show();
                $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
                  
                return;
            }
            
            
            
            $.post( myJsMain.baseURL+'shopping/ajax_update_group_order/', {
                orderId: obj.val(),
                qty: estd
            },
            function(data){ 
                if(data.contents){
                    window.location.href = data.contents;
                }
            }, 'json' );
            
            
        });
    });
    function isInteger(n) {
        return (typeof n == 'number' && /^[0-9]+$/.test());
    }
</script>
<?php echo $footer;?>