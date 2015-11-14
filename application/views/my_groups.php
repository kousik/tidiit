<?php echo $html_heading; echo $header;?>
<script src="<?php echo SiteJSURL;?>user-all-my-js.js" type="text/javascript"></script>
<style type="text/css">
    .popover{
        width: 100%;
    }
</style>
</div>
</header>
<article>
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12 productInner">
        <div class="page_content">
            <div class="row">
                <?php echo $userMenu;?>
                <div class="col-md-9 wht_bg">
                    <!-- Tab panes -->
                    <div class="tab_dashbord">
                    	<div class="active row">
                        	<div class="col-md-12 col-sm-12">
                             <div class="gen_infmtn">
                            	<h6>My Groups <span class="pull-right"><input type="button" data-toggle="modal" data-target="#myModalLogin" name="" value="Creat new Group" /></span></h6>
                                </div>
                                    <div class="js-my-groups">    
                                        <?php if(!empty($myGroups)):
                                        foreach($myGroups as $gkey => $group):?>        
                                        <div class="col-md-3 col-sm-3 grp_dashboard js-group-popover " title="Group : <?=$group->groupTitle?>"  data-container="body" data-toggle="popover" data-placement="left"  id="group-id-<?=$group->groupId?>" data-content='<div class="row">
                                             <div class="col-md-12">
                                                 <h5><strong>Group Admin</strong></h5>
                                                 <p class="text-left"><?=$group->admin->firstName?> <?=$group->admin->lastName?></p>
                                                 <?php if($group->users):?>
                                             <h5><strong>Group Users</strong></h5><?php
                                                foreach($group->users as $ukey => $usr):?>
                                             <p class="text-left"><?=$usr->firstName?> <?=$usr->lastName?></p>
                                             <?php endforeach; endif;?>
                                              <?php if(!$group->hide):?>
                                             <a class="btn btn-primary js-group-edit" href="<?=BASE_URL?>edit_groups/<?=base64_encode($group->groupId*987654321)?>" data-id="<?=$group->groupId?>">Modify</a>
                                             <button type="button" class="btn btn-danger pull-right js-group-delete" data-id="<?=$group->groupId?>">Delete</button>
                                              <?php endif;?>
                                             </div></div>'>
                                                <div class="<?=$group->groupColor?>">
                                                        <span><i class="fa  fa-group fa-5x"></i></span>
                                            </div>
                                            <div class="grp_title"><?=$group->groupTitle?></div>
                                        </div>
                                        <?php endforeach;?>    
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
</article>

<!-- Modal -->
<div class="modal fade" id="myModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create New Group</h4>
      </div>
      <form action="#" method="post" name="add_groups" class="form-horizontal" id="add_groups"> 
          <input type="hidden" name="groupAdminId" value="<?=$user->userId?>">
      <div class="modal-body">  
          <div class="js-message" style="display:none;"></div>
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
                          <?php //foreach ($CatArr AS $cat): ?>
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
        //jQuery('div.grp_dashboard').on('click','button.js-group-delete',function(){
        jQuery("body").delegate('button.js-group-delete', "click", function(e){
		e.preventDefault();
            if ( confirm( 'Are you sure you want to delete this group?' ) ) { 
                var gid = jQuery(this).data('id');
                jQuery.ajax({
                   type:"POST",
                   url:'<?php echo BASE_URL.'ajax/delete_group/'?>',
                   data:'groupId='+jQuery(this).data('id'),
                   success:function(msg){
                       if(msg!=""){
                           $('.js-group-popover').popover('hide');
                           jQuery('div#group-id-'+gid).remove();
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
    jQuery(function () {
        $('.js-group-popover').popover({html:true,container: 'body'});
    });
    jQuery('#productType').on('change',function(){
        
    });
    jQuery('#myModalLogin').on('hidden.bs.modal', function () {
        jQuery('#add_groups')[0].reset();
    })
</script>
<?php echo $footer;?>