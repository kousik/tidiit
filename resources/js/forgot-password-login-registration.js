// here i am handle product data selected by the user by submit event and handle show product list in Prackage details page
myJsMain.login=function(){
    var loginValidationRules = {
        userName: {
            required: true,
            email:true
        },
        loginPassword: {
            required: true
        }
    };
    $('#tidiit_user_login_form').validate({rules: loginValidationRules,onsubmit: true});
    $('#tidiit_user_login_form').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            myJsMain.commonFunction.showPleaseWait();
            $('#loginInSubmit').prop('disabled',true);
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/check_login', loginFormCallback);
        }
    });
        
        // this is just to show product list page
    function loginFormCallback(resultData){
        myJsMain.commonFunction.hidePleaseWait();
        $('#loginInSubmit').prop('disabled',false);
        //$('#fade_background').fadeOut();
        //$('#LoadingDiv').fadeOut();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            window.location.href = resultData.url;
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.url,200);
        }
    }
    
    
        
}

myJsMain.registration=function(){
    var registerValidationRules = {
        password: {
            required: true
        },
        confirmPassword: {
            required: true,
            equalTo:'#password'
        },
        email: {
            required: true,
            email:true,
            remote: {
                url: myJsMain.baseURL+'ajax/username_check1/',
                type: "post",
                data: {
                   email: function() {
                    return $( "#email" ).val();
                    }
                }
           }
        },
        firstName: {
            required: true,
            tiditAlpha:true,
            minlength:3,
            maxlength:20
        },
        lastName: {
            required: true,
            tiditAlpha:true,
            minlength:3,
            maxlength:20
        }
    };
    
    /**
     * 
     */
    $('#tidiit_user_register_form').validate({
        rules: registerValidationRules,
        messages:{
            email:{
                remote:"This email is already exists."
            }
        },
        submitHandler: function (form) {
            myJsMain.commonFunction.showPleaseWait();
            //form.submit(function(e) {
            $('#SignIn').prop('disabled',true);            
            myJsMain.commonFunction.ajaxSubmit($(form),myJsMain.baseURL+'ajax/check_registration', registrationFormCallback);
    //});
        }
    });   
    
     // this is just to show product list page
    function registrationFormCallback(resultData){ //alert('rr');
        myJsMain.commonFunction.hidePleaseWait();
        $('#SignIn').prop('disabled',false);
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            check_profile_completion_for_start_order(resultData.profile_common_message);
            window.location.href = resultData.url;
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.url,200);
        }
    }
}

myJsMain.forgot_password=function(){
    var forgotPasswrodValidationRules = {
        userForgotPasswordEmail: {
            required: true,
            email:true
        }
    };
    $('#tidiit_user_forgot_form').validate({rules: forgotPasswrodValidationRules,onsubmit: true});
    $('#tidiit_user_forgot_form').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            myJsMain.commonFunction.showPleaseWait();
            $('#forgotPasswrod').prop('disabled',true);
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/retribe_forgot_password/', forgotPasswordFormCallback);
        }
    });
        
        // this is just to show product list page
    function forgotPasswordFormCallback(resultData){
        $('#forgotPasswrod').prop('disabled',false);
        myJsMain.commonFunction.hidePleaseWait();
        myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
    }
    
    
        
}

function check_profile_completion_for_start_order(msg){
    if(msg!=""){
        myJsMain.commonFunction.tidiitAlert('Tidiit System Message',msg,200);
    }else{
        return false
    }
}