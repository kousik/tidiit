// here i am handle product data selected by the user by submit event and handle show product list in Prackage details page
myJsMain.login=function(){
    var validationRules = {
        userName: {
            required: true,
            email:true
        },
        password: {
            required: true
        }
    };
    $('#login_form').validate({
        rules: validationRules,
        onsubmit: true,
        errorPlacement: function(error, element) {
            return true;
        },
        errorElement: "nothing",
        errorClass: "invalid",
        validClass: "success"
    });
    $('#login_form').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            $('#fade_background').fadeIn();
            $('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.loginRequestURL, loginFormCallback);
        }
    });
        
        // this is just to show product list page
    function loginFormCallback(resultData){
        $('#fade_background').fadeOut();
        $('#LoadingDiv').fadeOut();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            location.href=resultData.url;
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.url,200);
        }
    }
    
    
        
}

myJsMain.registration=function(){
    /*$(document).ready(function(){
       var str='';
        str=myJsMain.securityCode;
        var c=document.getElementById("secret_img");
        var ctx=c.getContext("2d");
        ctx.font="20px cursive"; //monotype corsiva  Helvetica  sans-serif
        if(str==""){
            str=myJsMain.commonFunction.js_dynamic_text(8);	
        }
        ctx.fillText(str,5,15); 
    });*/
    
    var registrationValidationRules = {
        regUserName: {
            required: true,
            email:true
        }
    };
    $('#registration_form').validate({
        rules: registrationValidationRules,
        onsubmit: true,
        errorPlacement: function(error, element) {
            return true;
        },
        errorElement: "<div></div>",
        errorClass: "invalid",
        validClass: "success"
    });
    $('#registration_form').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            alert('rrr');return false;
            $('#fade_background').fadeIn();
            $('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.registrationRequestURL, registrationFormCallback);
        }
    });
        
        // this is just to show product list page
    function registrationFormCallback(resultData){
        $('#fade_background').fadeOut();
        $('#LoadingDiv').fadeOut();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            location.href=resultData.url;
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.url,200);
        }
    }
}