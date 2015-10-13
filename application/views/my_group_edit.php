<?php echo $html_heading; echo $header;?>
<script src="<?php echo SiteJSURL;?>user-all-my-js.js" type="text/javascript"></script>
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
                                    
                                <form action="#" method="post" name="update_groups" class="form-inline" id="update_groups">
                 <div class="row">
                     <div class="col-md-12 col-sm-12">
                         <div class="gen_infmtn">
                            	<h6>Edit Group: <?=$group->groupTitle?> </h6>
                                </div>
                         <div class="create_grp">
                             <div id="login_form">
                                 <form class="contact_form" action="#" method="post" name="contact_form"  novalidate="novalidate">
                                     <input type="hidden" name="groupId" value="<?=$group->groupId?>">
                                     <div class="row">
                                         <div class="col-md-12">
                                            <label for="groupTitle">Group Title</label>
                                            <input type="text" class="form-control" id="groupTitle" name="groupTitle" placeholder="Jane Doe" value='<?=$group->groupTitle?>' required>
                                          </div>
                                         <div class="col-md-4">
                                             <label for="locality" class="col-md-6 pad_none">Select Country :</label>
                                             <div class="col-md-6 pad_none">
                                                 <select name="countryId" class="form-control nova heght_cntrl" id="countryId">
                                                     <option value="">Select</option>
                                                     <?php foreach ($countryDataArr AS $k): ?>
                                                         <option value="<?= $k->countryId ?>"><?= $k->countryName ?></option>
                                                     <?php endforeach; ?>
                                                 </select>
                                             </div>
                                         </div>
                                         <div class="col-md-4">
                                             <label for="locality" class="col-md-6 pad_none">Select City :</label>
                                             <div class="col-md-6 pad_none cityElementPara">
                                                 <select name="cityId" class="form-control nova heght_cntrl" id="cityId">
                                                     <option>Select City</option>
                                                 </select>
                                             </div>
                                         </div>
                                         <div class="col-md-4">
                                             <label for="locality" class="col-md-6 pad_none">Select Zip :</label>
                                             <div class="col-md-6 pad_none zipElementPara">
                                                 <select name="zipId" class="form-control nova heght_cntrl" id="zipId">
                                                     <option>Select Zip</option>
                                                 </select>
                                             </div>
                                         </div> 
                                         <div class="col-md-4">
                                             <label for="locality" class="col-md-6 pad_none">Select Locality :</label>
                                             <div class="col-md-6 pad_none localityElementPara">
                                                 <select name="localityId" class="form-control nova heght_cntrl" id="localityId">
                                                     <option>Select Locality</option>
                                                 </select>
                                             </div>
                                         </div> 
                                         <div class="col-md-4">
                                             <label for="locality" class="col-md-6 pad_none">Select Product Type :</label>
                                             <div class="col-md-6 pad_none">
                                                 <select name="productType" class="form-control nova heght_cntrl" id="productType" required>
                                                     <option value="">Select</option>
                                                     <?php foreach ($CatArr AS $cat): ?>
                                                         <option value="<?= $cat->categoryId ?>" <?php if($cat->categoryId == $group->productType):?> selected<?php endif;?>><?= $cat->categoryName ?></option>
                                                     <?php endforeach; ?>
                                                 </select>
                                             </div>
                                         </div>

                                     </div>
                                     <div class="row">
                                         <div class="col-xs-12 col-sm-6 col-md-3 js-show-group-locality-users">
                                         </div>
                                     </div>
                                     <div class="row">
                                         <div class="col-xs-12 col-sm-6 col-md-6 js-show-group-users-tags">
                                             <div class="form-group"><label>Selected Users :</label></div>
                                             <?php foreach($group->users as $ukey => $usr):?>
                                             <input type="hidden" name="groupUsers[]" value="<?=$usr->userId?>" class="checkbox-close-<?=$usr->userId?>">
                                             <button type="button" class="btn btn-info btn-xs checkbox-close-<?=$usr->userId?>"><?=$usr->firstName?> <?=$usr->lastName?> | <span aria-hidden="true" class="checkbox-close" data-id="<?=$usr->userId?>">&times;</span></button>
                                             <?php endforeach;?>
                                         </div>
                                     </div>
                                     <div class="row">
                                         <div class="col-md-3 pull-right">
                                             <input type="submit"  class="grpButton" name="creatGrp" id="grpButton" value="Modify Group" />
                                         </div>
                                     </div>
                                     <div class="clear"></div>	
                                 </form>

                                 <div class="contact_form">
                                     <hr />
                                 </div> 

                             </div>
                         </div>
                     </div>


                     <!-- start-form -->


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
  </div>
</article>

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
</script>
<?php echo $footer;?>
