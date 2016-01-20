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
                            	<h6>My Buying Clubs <span class="pull-right"><input type="button" data-toggle="modal" data-target="#myModalLogin" name="" value="Creat new Buying Club" /></span></h6>
                                </div>
                                    <div class="js-my-groups">    
                                        <?php if(!empty($myGroups)):
                                        foreach($myGroups as $gkey => $group):?>        
                                        <div class="col-md-3 col-sm-3 grp_dashboard js-group-popover" title="<i class='fa fa-group'></i> Buying Club : <?=$group->groupTitle?>"  data-container="body" data-toggle="popover" data-placement="top" data-color="<?=$group->groupColor?>" id="group-id-<?=$group->groupId?>" data-content='<div class="row">
                                             <div class="col-md-12">
                                                 <h5><strong>Buying Club Leader</strong></h5>
                                                 <p class="text-left"><?=$group->admin->firstName?> <?=$group->admin->lastName?></p>
                                                 <?php if($group->users):?>
                                             <h5><strong>Buying Club Member</strong></h5><?php
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
        <h4 class="modal-title" id="myModalLabel">Create New Buying Club</h4>
      </div>
      <form action="#" method="post" name="add_groups" class="form-horizontal" id="add_groups"> 
          <input type="hidden" name="groupAdminId" value="<?=$user->userId?>">
      <div class="modal-body">  
          
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
                                <?php //foreach ($CatArr AS $cat): ?>
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
                        <div class="form-group"><label class="col-sm-3 control-label">Selected Users :</label></div>
                    </div>
                
            <div class="js-message" style="display:none;"></div>
      </div>
      <div class="modal-footer">
        <input type="submit"  class="grpButton" name="creatGrp" id="grpButton" value="Create New Buying Club" />
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
    
    $('.js-group-popover').on('shown.bs.popover', function () {
        // do something…
      });
</script>
<?php echo $footer;?>