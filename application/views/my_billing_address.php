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
                                    <form action="#" method="post" name="my_billing_address" id="my_billing_address">
                                    <div class="gen_infmtn">
                                        <h6>Billing Address <span class="pull-right"><input type="submit" name="billingAddessSubmit" id="billingAddessSubmit" value="Update" /></span></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>First Name</label>
                                                        <input type="text" name="firstName" id="firstName" class="form-control" value="<?php echo $userBillingDataDetails[0]->firstName;?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Last Name</label>
                                                        <input type="text" name="lastName" id="lastName" class="form-control" value="<?php echo $userBillingDataDetails[0]->lastName;?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Phone</label>
                                                        <input type="phone" name="phone" id="phone" class="form-control" value="<?php echo $userBillingDataDetails[0]->contactNo;?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="<?php echo $userBillingDataDetails[0]->email;?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label>Address</label>
                                                <input type="text" name="address" class="form-control" value="<?php echo $userBillingDataDetails[0]->address;?>" required>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-3 col-sm-3">
                                                        <label>Country</label>
                                                        <p style="position:relative;" class="countryElementPara">
                                                            <select class="form-control nova heght_cntrl" name="countryId" id="countryId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                                <?php foreach($countryDataArr AS $k){ ?>
                                                                <option value="<?php echo $k->countryId;?>" <?php if($k->countryId==$userBillingDataDetails[0]->countryId){?>selected<?php }?>><?php echo $k->countryName;?></option>
                                                                <?php }?>
                                                            </select>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3 pad_rit_none">
                                                        <label>City</label>
                                                        <p style="position:relative;" class="cityElementPara">
                                                            <?php if($userBillingDataDetails[0]->cityId==""){?>
                                                            <select class="form-control nova heght_cntrl" name="cityId" id="cityId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                            </select>
                                                            <?php }else{?> 
                                                            <select class="form-control nova heght_cntrl" name="cityId" id="cityId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                                <?php foreach($cityDataArr As $k){?>
                                                                <option value="<?php echo $k->cityId;?>" <?php if($k->cityId==$userBillingDataDetails[0]->cityId){?>selected<?php }?>><?php echo $k->city;?></option>
                                                                <?php }?> 
                                                            </select>
                                                            <?php }?>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3 pad_rit_none">
                                                        <label>Zip</label>
                                                        <p style="position:relative;" class="zipElementPara">
                                                            <?php if($userBillingDataDetails[0]->zipId==""){?>
                                                            <select class="form-control nova heght_cntrl" name="zipId" id="zipId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                            </select>
                                                            <?php }else{ ?>
                                                            <select class="form-control nova heght_cntrl" name="zipId" id="zipId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                                <?php foreach($zipDataArr AS $k){?>
                                                                <option value="<?php echo $k->zipId;?>" <?php if($k->zipId==$userBillingDataDetails[0]->zipId){?>selected<?php }?>><?php echo $k->zip; ?></option>
                                                                <?php }?>
                                                            </select>
                                                            <?php }?>
                                                        </p>
                                                    </div>
                                                    
                                                    <div class="col-md-3 col-sm-3 pad_rit_none">
                                                        <label>Locality</label>
                                                        <p style="position:relative;" class="localityElementPara">
                                                            <?php if($userBillingDataDetails[0]->localityId==""){?>
                                                            <select class="form-control nova heght_cntrl" name="localityId" id="localityId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                            </select>
                                                            <?php }else{?>
                                                            <select class="form-control nova heght_cntrl" name="localityId" id="localityId" value=""  tabindex="1">
                                                                <option value="">Select</option>
                                                                <?php foreach($localityDataArr AS $k){?>
                                                                <option value="<?php echo $k->localityId;?>" <?php if($k->localityId==$userBillingDataDetails[0]->localityId){?>selected<?php }?>><?php echo $k->locality;?></option>
                                                                <?php }?>
                                                            </select>
                                                            <?php }?>
                                                        </p>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-12 rootShowInerCategoryData">
                                                <label id="productTypeId[]-error" class="error" for="productTypeId[]" style="display: none;"></label>
                                                <?php 
                                                if(empty($userProductTypeArr)):
                                                    foreach($topCategoryDataArr AS $k):?>
                                                <div class="col-md-12">
                                                    <a class="showInerCategoryData" href="javascript://" data-catdivid="<?php echo $k->categoryId;?>" data-isRoot="yes"><?php echo $k->categoryName;?></a>
                                                </div>
                                                <div class="col-md-12" style="height: 10px;"></div>
                                                <?php endforeach;
                                                else :
                                                echo $billingAddressProductTypeHtml;    
                                                endif;?>
                                            </div>
                                        </div>
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
<?php echo $footer;?>
<script type="text/javascript">
    var zipDynEle='<select class="form-control nova heght_cntrl" name="zipId" id="zipId" value=""  tabindex="1"><option value="">Select</option></select>';
    var localityDynEle='<select class="form-control nova heght_cntrl" name="localityId" id="localityId" value=""  tabindex="1"><option value="">Select</option></select>';
    var cityDynEle='<select class="form-control nova heght_cntrl" name="cityId" id="cityId" value=""  tabindex="1"><option value="">Select</option></select>';
    myJsMain.my_billing_address();
    jQuery(document).ready(function(){
        //jQuery('.countryElementPara').on('change','#countryId',function(){
        jQuery('#countryId').on('change',function(){
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery.ajax({
                   type:"POST",
                   url:myJsMain.baseURL+'ajax/show_city_by_country/',
                   data:'countryId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                           jQuery('.cityElementPara').html(msg);
                            jQuery('.zipElementPara').html(zipDynEle);
                            jQuery('.localityElementPara').html(localityDynEle);
                       }
                   }
               });
           }
        });
        
        jQuery('.cityElementPara').on('change','#cityId',function(){
        //jQuery('#cityId').on('change',function(){ alert('rr');
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery.ajax({
                   type:"POST",
                   url:myJsMain.baseURL+'ajax/show_zip_by_city/',
                   data:'cityId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                           jQuery('.zipElementPara').html(msg);
                           jQuery('.localityElementPara').html(localityDynEle);
                       }
                   }
               });
           }
        });
        
        jQuery('.zipElementPara').on('change','#zipId',function(){
        //jQuery('#cityId').on('change',function(){ alert('rr');
           if(jQuery(this).val()==""){
               return false;
           }else{
               jQuery.ajax({
                   type:"POST",
                   url:myJsMain.baseURL+'ajax/show_locality_by_zip/',
                   data:'zipId='+jQuery(this).val(),
                   success:function(msg){
                       if(msg!=""){
                           jQuery('.localityElementPara').html(msg);
                       }
                   }
               });
           }
        });
        
        jQuery("body").delegate('a.showInerCategoryData', "click", function(e){
            e.preventDefault();
            var oldHtmlContent=$(this).html();
            //return false;
            var catId = $(this).data('catdivid');
            var isRoot = $(this).data('isroot');
            var jqout = $(this);
            if($(this).parent().children().length>1 && isRoot=='yes'){
                return false;
            }
            if($(this).parent().children().length>2){
                return false;
            }
            $(this).prev().attr('checked', false); 
            $.post( myJsMain.baseURL+'ajax/get_subcategory_for_user_product_type/', {
                categoryId: catId
            },
            function(data){
                //jqout.parent('div').empty();
                //jqout.parent('div').html(oldHtmlContent);
                jqout.parent('div').append(data.content);
            }, 'json' );
        });
        
        jQuery("body").delegate('.productTypeCategorySelection', "click", function(e){
            $(this).parent().children().each(function(idx,ele){
                //if(ele.attr('type'))
                if(idx>1){
                    ele.remove();
                }
            });
        });
        
    });
</script>