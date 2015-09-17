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
            $('#loginInSubmit').prop('disabled',true);
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/check_login', loginFormCallback);
        }
    });
        
        // this is just to show product list page
    function loginFormCallback(resultData){
        $('#loginInSubmit').prop('disabled',false);
        //$('#fade_background').fadeOut();
        //$('#LoadingDiv').fadeOut();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            location.href=$(location).attr('href'); //resultData.url;
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.url,200);
        }
    }
    
    
        
}

myJsMain.registration=function(){
    /*$(document).ready(function(){
       var str='';
        //str=myJsMain.securityCode;
        var c=document.getElementById("secret_img");
        var ctx=c.getContext("2d");
        ctx.font="20px cursive"; //monotype corsiva  Helvetica  sans-serif
        if(str==""){
            str=myJsMain.commonFunction.js_dynamic_text(6);	
        }
        ctx.fillText(str,5,15); 
    });*/
    
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
            maxlength:12
        },
        lastName: {
            required: true,
            tiditAlpha:true,
            minlength:3,
            maxlength:12
        }
    };
    $('#tidiit_user_register_form').validate({
        rules: registerValidationRules,
        onsubmit: true,
        messages:{
            email:{
                remote:"Pleas provide valid username"
            }
        }
    });
    $('#tidiit_user_register_form').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            $('#SignIn').prop('disabled',true);
            myJsMain.commonFunction.tidiitAlert('Tidiit System','returning',180);
            
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/check_registration', registrationFormCallback);
        }
    });
    
    
     // this is just to show product list page
    function registrationFormCallback(resultData){ //alert('rr');
        $('#SignIn').prop('disabled',false);
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            location.href=$(location).attr('href'); //resultData.url;
            //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.url,200);
        }
    }
}