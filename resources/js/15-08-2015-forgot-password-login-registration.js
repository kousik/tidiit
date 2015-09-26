// here i am handle product data selected by the user by submit event and handle show product list in Prackage details page
myJsMain.login=function(){
    var validationRules = {
        userName: {
            required: true,
            email:true
        },
        loginPassword: {
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
    $('#tidiit_user_login_form').submit(function(e) {
        $('#loginInSubmit').prop('disabled',true);
        e.preventDefault();
        if ($(this).valid()) {
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.loginRequestURL, myJsMain.login.loginFormCallback);
        }
    });
        
        // this is just to show product list page
    function loginFormCallback(resultData){
        //$('#fade_background').fadeOut();
        //$('#LoadingDiv').fadeOut();
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
            $('#loginInSubmit').prop('disabled',false);
        }else if(resultData.result=='good'){
            location.href=resultData.url;
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
    
    var validationRules = {
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
            /*remote: {
                url: myJsMain.baseURL+'ajax/username_check/',
                type: "post",
                data: {
                   email: function() {
                    return $( "#email" ).val();
                    }
                }
           }*/
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
        rules: validationRules,
        /*messages:{
            email:{
                remote:"Pleas provide valid username"
            }
        },*/
        onsubmit: true,
        errorPlacement: function(error, element) {
            return true;
        },
        //errorElement: "nothing",
        errorClass: "invalid",
        validClass: "success"
    });
    $('#tidiit_user_register_form').submit(function(e) {
		//console.log('i ma in');
        e.preventDefault();
        if ($(this).valid()) {
            //var ret=myJsMain.commonFunction.tidiitConfirm('Tidiit System','Are you sure  ?','silent','',180);
            //myJsMain.commonFunction.tidiitAlert('Tidiit System','returning '+ret,180);
            
            //alert(myJsMain.registrationRequestURL);return false;
            //$('#fade_background').fadeIn();
            //$('#LoadingDiv').fadeIn();
            //alert(myJsMain.registrationRequestURL);
            //myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.registrationRequestURL, judhiTest);
            //myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.registrationRequestURL, registrationFormCallback);
            tidiitAlert('Tidiit System','returning ',180);
        }
    });
    
    
     // this is just to show product list page
    function registrationFormCallback(resultData){ //alert('rr');
        //myJsMain.commonFunction.tidiitAlert('Tidiit Alert',resultData.msg,150);
        alert(resultData.msg);
        //myJsMain.commonFunction.tidiitAlert('Tidiit Alert',resultData.msg,150);
    }
}