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
                                   <form action="#" method="post" name="start_groups_order" id="start_groups_order"> 
                                    <input type="hidden" id="js-order-info" name="orderId" value="<?=$orderId?>" data-groupid="" data-userid="<?=$user->userId?>" >
                                    
                                    <div class="col-md-12 col-sm-12">

                                        <div class="gen_infmtn">

                                            <h6>Groups order ID <span class="label label-success"><?=$orderId?></span> </h6>

                                        </div>


                                        <div class="group-selection js-display-selected-group">
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
                                        <div class="panel js-display-exisit-group"></div>
                                        <div class="panel panel-default">
                                            <div class="panel-body">

                                                <div class="form-horizontal">
                                                    <label for="input3" class="col-sm-7 control-label">Please enter your no of Quantity of this Order<br>Available quantity : ( <?=$priceInfo->qty?> ) </label>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" id="js-estd-qty" placeholder="">
                                                        <input type="hidden" class="form-control" id="js-avail-qty" value="<?=$priceInfo->qty?>">
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-danger js-message" role="alert" style="display: none;"></div>
                                        <div class="clearfix"></div>

                                        <button type="button" class="btn btn-primary pull-right js-group-order-process">Invite Group User to Process the Order</button>
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
        <h4 class="modal-title" id="myModalLabel">Create New Group</h4>
      </div>
      <form action="#" method="post" name="add_groups_for_order" class="form-horizontal" id="add_groups_for_order"> 
          <input type="hidden" name="groupAdminId" value="<?=$user->userId?>">
      <div class="modal-body">          
              <div class="form-group">
                  <label for="groupTitle" class="col-sm-3 control-label">Group Title</label>
                  <div class="col-sm-7">
                  <input type="text" class="form-control" id="groupTitle" name="groupTitle" placeholder="Jane Doe" required>
                  </div>
              </div>
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
                  <label for="locality" class="col-sm-3 control-label">Select Product Type :</label>
                  <div class="col-sm-7">
                      <select name="productType" class="form-control nova heght_cntrl" id="productType" required>
                          <option value="">Select</option>
                          <?php foreach ($CatArr AS $categoryId=>$categoryName): ?>
                              <option value="<?= $categoryId ?>"><?= $categoryName ?></option>
                          <?php endforeach; ?>
                      </select>
                  </div>
              </div>
          
              <div class="form-group js-show-group-locality-users">
              </div>
              <div class="js-show-group-users-tags">
                  <div class="form-group"><label class="col-sm-3 control-label">Selected Users :</label></div>
              </div>
          
      </div>
      <div class="modal-footer">
        <input type="submit"  class="grpButton" name="creatGrp" id="grpButton" value="Create New Group" />
      </div>
      </form>    
    </div>
  </div>
</div>
<!-- /.modal -->


<script type="text/javascript">
    var zipDynEle='<select class="form-control nova heght_cntrl" name="zipId" id="zipId" value=""  tabindex="1"><option value="">Select</option></select>';
    var localityDynEle='<select class="form-control nova heght_cntrl" name="localityId" id="localityId" value=""  tabindex="1"><option value="">Select</option></select>';
    var cityDynEle='<select class="form-control nova heght_cntrl" name="cityId" id="cityId" value=""  tabindex="1"><option value="">Select</option></select>';
    myJsMain.my_billing_address();
    myJsMain.my_create_groups();
    jQuery(document).ready(function(){
        jQuery('#countryId').on('change',function(){
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery.ajax({
                   type:"POST",
                   url:'<?php echo BASE_URL.'ajax/show_city_by_country/'?>',
                   data:'countryId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                            jQuery('.js-show-group-locality-users').empty();
                            jQuery('.cityElementPara').html(msg);
                            jQuery('.zipElementPara').html(zipDynEle);
                            jQuery('.localityElementPara').html(localityDynEle);
                       }
                   }
               });
           }
        });
        
        jQuery('.cityElementPara').on('change','#cityId',function(){
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery.ajax({
                   type:"POST",
                   url:'<?php echo BASE_URL.'ajax/show_zip_by_city/'?>',
                   data:'cityId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                           jQuery('.js-show-group-locality-users').empty();
                           jQuery('.zipElementPara').html(msg);
                           jQuery('.localityElementPara').html(localityDynEle);
                       }
                   }
               });
           }
        });
        
        jQuery('.zipElementPara').on('change','#zipId',function(){
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery.ajax({
                   type:"POST",
                   url:'<?php echo BASE_URL.'ajax/show_locality_by_zip/'?>',
                   data:'zipId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                           jQuery('.js-show-group-locality-users').empty();
                           jQuery('.localityElementPara').html(msg);
                       }
                   }
               });
           }
        });
        
        jQuery('.localityElementPara').on('change','#localityId',function(){
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery('.js-show-group-locality-users').empty();
               jQuery.ajax({
                   type:"POST",
                   url:'<?php echo BASE_URL.'ajax/show_locality_all_users/'?>',
                   data:'localityId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                           jQuery('.js-show-group-locality-users').html(msg);
                       }
                   }
               });
           }
        });
        
        jQuery("body").delegate('.tags-group', "click", function(e){
		e.preventDefault();
                var gname = jQuery(this).data('name');
                var gid = jQuery(this).val();
                var existGid = false;
                jQuery( ".js-show-group-users-tags input" ).each(function() {
                    var dGid = jQuery(this).val();
                    if(dGid == gid){
                        existGid = true;
                    }
                });
                
                if(!existGid){
                var html = "<input type=\"hidden\" name=\"groupUsers[]\" value=\""+gid+"\" class=\"checkbox-close-"+gid+"\">  <button type=\"button\" class=\"btn btn-info btn-xs checkbox-close-"+gid+"\">"+gname+" | <span aria-hidden=\"true\" class=\"checkbox-close\" data-id=\""+gid+"\">&times;</span></button>";
                $('.js-show-group-users-tags').append(html);
                $('.checkbox-'+gid).hide();
            } else {
                $('.checkbox-'+gid).hide();
            }
                
        }); 
        
        
        jQuery("body").delegate('.checkbox-close', "click", function(e){
		e.preventDefault();
                var gid = jQuery(this).data('id');
                $('.checkbox-close-'+gid).remove();
                $('.checkbox-'+gid).show();
        }); 
        
        
        
    });
    jQuery(document).ready( function() {
        $('.js-group-popover').popover({html:true,container: 'body'});
        
        jQuery("body").delegate('.js-order-group', "click", function(e){
            e.preventDefault();
            var grp = jQuery(this).val();
            if(grp == 'new'){
                $('div.js-display-exisit-group').empty();
                $('#createGroupModalLogin').modal('show');
            } else if( grp == 'exists'){                
                var userId = $('input[id="js-order-info"]').data('userid');
                $.post( myJsMain.baseURL+'ajax/get_my_groups/', {
                    userId: userId
                },
                function(data){ 
                    if(data.contents){
                        $('div.js-display-exisit-group').html(data.contents);
                    }
                }, 'json' );
            }
        }); 
        
        jQuery("body").delegate('input[id="js-order-info"]', "click", function(e){
            e.preventDefault();//console.log($(this).data('groupid'));
            var groupId = $(this).attr('data-groupid');
            var jqout = $(this);
            $.post( myJsMain.baseURL+'ajax/get_single_group/', {
                groupId: groupId
            },
            function(data){ 
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
                $('div.js-message').html('Please add a group!');
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
            console.log($('#js-estd-qty').val()+'==='+$('#js-avail-qty').val());
            var estd = $('#js-estd-qty').val();
            var avail = $('#js-avail-qty').val();
            if(parseInt(estd) > parseInt(avail)){
                $('div.js-message').html("Quantity can't exceed from available quantity!");
                //$('div.js-message').show();
                $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
                  
                return;
            }
        });
    });
</script>
<?php echo $footer;?>