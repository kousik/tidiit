myJsMain.my_billing_address=function(){ 
    var loginValidationRules = {
        firstName: {required: true},
        lastName: {required: true},
        phone: {required: true},
        email: {required: true,email:true},
        countryId: {required: true},
        cityId: {required: true},
        zipId: {required: true},
        localityId: {required: true}
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
}



myJsMain.my_create_groups=function(){ 
    var createGroupValidationRules = {
        groupTitle: {required: true},
        productType: {required: true}
    };
    $('#add_groups').validate({
        rules: createGroupValidationRules,
        submitHandler: function (form) {
            //form.submit(function(e) {
            $('#grpButton').prop('disabled',true);            
            myJsMain.commonFunction.ajaxSubmit($(form),myJsMain.baseURL+'ajax/add_new_group', groupCreateCallback);
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
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        } else if(resultData.result=='good') {
            $( "#myModalLogin .close" ).trigger( "click" );
            $( "div.js-my-groups" ).append(resultData.msg);
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',"Group has been added successfully.",200);
            window.setTimeout(function(){location.reload()},3000);
        }
    }
    
    
    // this is just to show product list page
    function groupUpdateCallback(resultData){
        $('#grpButton').prop('disabled',false);
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        } else if(resultData.result=='good') {
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',"Group has been updated successfully.",200);
        }
    }
}