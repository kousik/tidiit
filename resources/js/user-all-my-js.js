myJsMain.my_billing_address=function(){ 
    var loginValidationRules = {
        firstName: {required: true},
        lastName: {required: true},
        phone: {required: true},
        address: {required: true},
        email: {required: true,email:true},
        countryId: {required: true},
        cityId: {required: true},
        zipId: {required: true},
        localityId: {required: true},
        productTypeId: {required: true}
    };
    $('#my_billing_address').validate({rules: loginValidationRules,onsubmit: true});
    $('#my_billing_address').submit(function(e) { 
        e.preventDefault();
        if ($(this).valid()) { 
            $('#billingAddessSubmit').prop('disabled',true);
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/submit_my_billing_address', billingAddressFormCallback);
        }
    });
        
        // this is just to show product list page
    function billingAddressFormCallback(resultData){
        $('#billingAddessSubmit').prop('disabled',false);
        //$('#fade_background').fadeOut();
        //$('#LoadingDiv').fadeOut();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',"Billing address updated successfully.",200);
        }
    }
};



myJsMain.my_profile=function(){ 
    var myProfileValidationRules = {
        firstName: {required: true},
        lastName: {required: true},
        contactNo: {required: true},
        email: {required: true,email:true},
        DOB: {required: true},
        aboutMe: {required: true}
    };
    $('#my_profile').validate({rules: myProfileValidationRules,onsubmit: true});
    $('#my_profile').submit(function(e) { 
        e.preventDefault();
        if ($(this).valid()) { 
            $('#profileSubmit').prop('disabled',true);
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/submit_my_profile', profileFormCallback);
        }
    });
        
        // this is just to show product list page
    function profileFormCallback(resultData){
        $('#profileSubmit').prop('disabled',false);
        //$('#fade_background').fadeOut();
        //$('#LoadingDiv').fadeOut();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',"Profile updated successfully.",200);
        }
    }
}

myJsMain.my_shipping_address=function(){ 
    var shippingAddressValidationRules = {
        firstName: {required: true},
        lastName: {required: true},
        phone: {required: true},
        address: {required: true},
        countryId: {required: true},
        cityId: {required: true},
        zipId: {required: true},
        localityId: {required: true}
    };
    $('#my_shipping_address').validate({rules: shippingAddressValidationRules,onsubmit: true});
    $('#my_shipping_address').submit(function(e) { 
        e.preventDefault();
        if ($(this).valid()) { 
            $('#shippingAddessSubmit').prop('disabled',true);
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/submit_shippiing_address', shippingAddressFormCallback);
        }
    });
        
        // this is just to show product list page
    function shippingAddressFormCallback(resultData){
        $('#shippingAddessSubmit').prop('disabled',false);
        //$('#fade_background').fadeOut();
        //$('#LoadingDiv').fadeOut();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',"Shipping address updated successfully.",200);
        }
    }
}


myJsMain.my_finance=function(){ 
    var financeValidationRules = {
        mpesaFullName: {required: true},
        mpesaAccount: {required: true}
    };
    $('#my_finance').validate({rules: financeValidationRules,onsubmit: true});
    $('#my_finance').submit(function(e) { 
        e.preventDefault();
        if ($(this).valid()) { 
            $('#financeSubmit').prop('disabled',true);
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/submit_finance', financeFormCallback);
        }
    });
        
        // this is just to show product list page
    function financeFormCallback(resultData){
        $('#financeSubmit').prop('disabled',false);
        //$('#fade_background').fadeOut();
        //$('#LoadingDiv').fadeOut();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',"Finance data updated successfully.",200);
        }
    }
};



myJsMain.my_create_groups=function(){ 
    var createGroupValidationRules = {
        groupTitle: {required: true},
        productType: {required: true}
    };
    $('#add_groups').validate({
        rules: createGroupValidationRules,
        submitHandler: function (form) {
            
            $('#grpButton').prop('disabled',true);            
            myJsMain.commonFunction.ajaxSubmit($(form),myJsMain.baseURL+'ajax/add_new_group', groupCreateCallback);
    //});
        }
    });
    
    $('#add_groups_for_order').validate({
        rules: createGroupValidationRules,
        submitHandler: function (form) {
            
            $('#grpButton').prop('disabled',true);            
            myJsMain.commonFunction.ajaxSubmit($(form),myJsMain.baseURL+'ajax/add_new_group', groupCreateForOrderCallback);
    //});
        }
    });
    
    $('#update_groups').validate({
        rules: createGroupValidationRules,
        submitHandler: function (form) {
            //form.submit(function(e) {
            $('#grpButton').prop('disabled',true);            
            myJsMain.commonFunction.ajaxSubmit($(form),myJsMain.baseURL+'ajax/update_group', groupUpdateCallback);
    //});
        }
    });
    
    
    // this is just to show product list page
    function groupCreateCallback(resultData){
        $('#grpButton').prop('disabled',false);
        if(resultData.result=='bad'){
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
            $('div.js-message').html('<div class="alert alert-danger" role="alert">'+resultData.msg+'</div>');
            $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
        } else if(resultData.result=='good') {
            $( "#myModalLogin .close" ).trigger( "click" );
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',"Group has been added successfully.",200);
            window.setTimeout(function(){location.reload();},3000);
        }
    }
    
    // this is just to show product list page
    function groupCreateForOrderCallback(resultData){
        $('#grpButton').prop('disabled',false);
        if(resultData.result=='bad'){
           // myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
            $('div.js-message').html('<div class="alert alert-danger" role="alert">'+resultData.msg+'</div>');
            $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
        } else if(resultData.result=='good') {
            $('input[id="js-order-info"]').attr('data-groupid', resultData.gid);
            $('input[id="js-order-info"]').trigger( "click" );
            $( "#createGroupModalLogin .close" ).trigger( "click" );
            
        }
    }
    
    
    // this is just to show product list page
    function groupUpdateCallback(resultData){
        $('#grpButton').prop('disabled',false);
        if(resultData.result == 'bad'){
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
            $('div.js-message').html('<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle"></i> '+resultData.msg+'</div>');
            $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
        } else if(resultData.result == 'good') {
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',"Group has been updated successfully.",200);
            $('div.js-message').html('<div class="alert alert-success" role="alert"><i class="fa fa-check"></i> Group has been updated successfully.</div>');
            $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
        }
    };
    
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
                url:myJsMain.baseURL+'ajax/show_zip_by_city/',
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
                url:myJsMain.baseURL+'ajax/show_locality_by_zip/',
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
                url:myJsMain.baseURL+'ajax/show_locality_all_users/',
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
                url:myJsMain.baseURL+'ajax/delete_group/',
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
             var html = "<input type=\"hidden\" name=\"groupUsers[]\" value=\""+gid+"\" class=\"checkbox-close-"+gid+"\">  <button type=\"button\" class=\"btn btn-info btn-xs checkbox-close-"+gid+"\"><i class=\"fa fa-user\"></i>"+gname+" | <i class=\"fa fa-times-circle checkbox-close\" data-id=\""+gid+"\"></i></button>";
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

     jQuery('#productType').on('change',function(){
         if(jQuery(this).val()==""){
            jQuery('.js-show-group-locality-users').empty();
            jQuery.ajax({
                type:"POST",
                url:myJsMain.baseURL+'ajax/show_locality_all_users/',
                data:'localityId='+jQuery('#localityId').val(),
                success:function(msg){
                    if(msg!=""){
                        jQuery('.js-show-group-locality-users').html(msg);
                    }
                }
            });
         }else{
             var ajaxData='localityId='+jQuery('#localityId').val()+'&productType='+jQuery(this).val();
            //console.log(ajaxData);
            jQuery.ajax({
                type:"POST",
                url:myJsMain.baseURL+'ajax/show_locality_all_users_with_product_type/',
                data:ajaxData,
                success:function(msg){
                    if(msg!=""){
                        jQuery('.js-show-group-locality-users').html(msg);
                    }else{
                        myJsMain.commonFunction.tidiitAlert('Tidiit System Message',"There is no user match with selected product type.",200);
                    }
                }
            });
         }
     });
     
    jQuery(function () {$('.js-group-popover').popover({html:true,container: 'body'});});
    
    jQuery('#myModalLogin').on('hidden.bs.modal', function () {jQuery('#add_groups')[0].reset();jQuery('.js-show-group-locality-users').html('')});
};



myJsMain.my_checkout_shipping_address=function(){ 
    var shippingValidationRules = {
        firstName: {required: true},
        lastName: {required: true},
        phone: {required: true},
        address: {required: true},
        countryId: {required: true},
        cityId: {required: true},
        zipId: {required: true},
        localityId: {required: true}
    };
    $('#my_checkout_shipping_address').validate({rules: shippingValidationRules,onsubmit: true});
    $('#my_checkout_shipping_address').submit(function(e) {
        myJsMain.commonFunction.showPleaseWait();
        e.preventDefault();
        if ($(this).valid()) { 
            $('#shippingCheckoutAddress').prop('disabled',true);
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/submit_my_checkout_shipping_address', shippingCheckAddressFormCallback);
        }
    });
        
        // this is just to show product list page
    function shippingCheckAddressFormCallback(resultData){
        myJsMain.commonFunction.hidePleaseWait();
        $('#shippingCheckoutAddress').prop('disabled',false);
        if(resultData.result=='bad'){
            //alert alert-danger 
            $('div.js-message').html('<div class="alert alert-danger">Shipping address not updated. Please try again!</div>');
             $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
        }else if(resultData.result=='good'){
            $('.js-right-panel').html(resultData.html);
            $('div.js-message').html('<div class="alert alert-success">Shipping address has been updated successfully. Please continue!</div>');
             $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
             $('.shipping-continue').attr('data-shipping', 'true');
             $('a.js-shipping').attr('data-shipping', 'true');
        }
    }
};