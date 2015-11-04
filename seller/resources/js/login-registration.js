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
    $(document).ready(function(){
       var str='';
        //str=myJsMain.securityCode;
        var c=document.getElementById("secret_img");
        var ctx=c.getContext("2d");
        ctx.font="20px cursive"; //monotype corsiva  Helvetica  sans-serif
        if(str==""){
            str=myJsMain.commonFunction.js_dynamic_text(6);	
        }
        ctx.fillText(str,5,15); 
    });
    
    var validationRules = {
        userName: {
            required: true,
            email:true
        },
        password: {
            required: true
        },
        confirmPassword: {
            required: true
        },
        contactNo:{
            required:true
        },
        email: {
            required: true,
            email:true
        },
        firstName: {
            required: true
        },
        lastName: {
            required: true
        },
        address: {
            required: true
        },
        city: {
            required: true
        },
        stateId : {
            required:true
        },
        countryId:{
            required:true
        },
        zip:{
            required:true
        },
        secret:{
            required:true
        }
    };
    $('#registration_form').validate({
        rules: validationRules,
        onsubmit: true,
        errorElement: "nothing",
        errorClass: "invalid",
        validClass: "success"
    });
    $('#registration_form').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            //alert(myJsMain.registrationRequestURL);return false;
            $('#fade_background').fadeIn();
            $('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.registrationRequestURL, registrationFormCallback);
        }
    });
        
        // this is just to show product list page
    function registrationFormCallback(resultData){
        $('#fade_background').fadeOut();
        $('#LoadingDiv').fadeOut();
        //alert(resultData.result);
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
            location.href=resultData.url;
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.url,200);
        }
    }
}