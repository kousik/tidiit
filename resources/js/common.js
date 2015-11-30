
// add new validate method for phone number validation.
jQuery.validator.addMethod("phoneno", function(value, element) {
	return this.optional(element) || /^[0-9?=.\+\-\ ]+$/.test(value);
}, "Phone must contain only numbers, or special characters.");

// add new validate method for alphabets and space validation.
jQuery.validator.addMethod("tiditAlphaSpace", function(value, element) {
	return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
}, "Phone must contain only alphabet and space.");

// add new validate method for alphabets validation.
jQuery.validator.addMethod("tiditAlpha", function(value, element) {
	return this.optional(element) || /^[a-zA-Z]+$/.test(value);
}, "Phone must contain only alphabets.");

jQuery.validator.addMethod("notEqual", function(value, element, param) {
  return this.optional(element) || value != param;
}, "Please specify a different (non-default) value");

pleaseWaitDiv = $('<div class="modal" id="myLoadingModal" tabindex="-1" role="dialog" aria-labelledby="myLoadingModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h1>Processing...</h1></div><div class="modal-body"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div><div class="modal-footer"></div></div></div></div>');

// js utility function to submit formm using ajax 
myJsMain.commonFunction = {
    ajaxSubmit: function($this, url, callback) {
        //alert(url);return false;
        var ajaxUrl =url,
        //data = $this.serialize().replace(/%5B%5D/g, '[]');
        data = $this.serialize();
        //data = new FormData($this[0]);
        //alert(data);return false;
        //alert(callback);return false;
        jQuery.post(ajaxUrl, data, function(resultData) {
            resultData['thisVar'] = $this;
            
            myJsMain.commonFunction.callBackFuction(callback, resultData);
            //$('body,html').animate({scrollTop: 0}, 'slow');
        }, 'json');
    },
    callBackFuction: function(callback, data) { 
        // Call our callback, but using our own instance as the context
        callback.call(this, data);
    },
    js_dynamic_text:function(length){
        var randomStuff =["a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","1","2","3","4","5","6","7","8","9","0","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
        var sl=0;
        var index;
        var char;
        var str='';
        for(sl=0;sl<length;sl++){
                index=Math.floor((Math.random()*61)+1);
                char=randomStuff[index];
                str=str+char;
        }
        //document.cookie= myJsMain.CaptchaCookeName+'='+str;
        var SecretTextSetAjaxData='secret='+str;
        jQuery.ajax({
           type: "POST",
           url: myJsMain.baseURL+'ajax/reset_secret/',
           data: SecretTextSetAjaxData,
           success: function(){
               myJsMain.commonFunction.showHowItWorksBox();
           }
         });
        return str;
    },
    showHowItWorksBox:function(){
        if(myJsMain.isLogedIn==false){
            myJsMain.commonFunction.showPleaseWait();
        }
        jQuery.ajax({
           type: "POST",
           url: myJsMain.baseURL+'ajax/show_how_it_works/',
           success: function(msg){
               myJsMain.commonFunction.hidePleaseWait();
               if(msg!=''){
                   jQuery('#autoLoadHowItWorks').html(msg)
               }
           }
         });
    },
    GeneratNewImage:function(){
        jQuery('#secret_img').html("");
        var c=document.getElementById("secret_img");
        c.width = c.width;
        var ctx=c.getContext("2d");
        var str='';
        ctx.font="20px cursive"; //monotype corsiva  Helvetica  sans-serif
        str=myJsMain.commonFunction.js_dynamic_text(8);
        ctx.fillText(str,5,15);
        var SecretTextSetAjaxData='secret='+str;
        jQuery.ajax({
           type: "POST",
           url: myJsMain.SecretTextSetAjaxURL,
           data: SecretTextSetAjaxData,
           success: function(msg){ //alert(msg);
           }
         });
    },
    tidiitAlert:function(boxTitle,alertMessaage,height){
        if(height==0){
            height=175;
        }
        jQuery('#dialog-confirm-message-text').text(alertMessaage);
        //alert(alertMessaage);
        jQuery( "#dialog-confirm" ).dialog({
            resizable: false,
            height:height,
            width:450,
            modal: true,
            title:boxTitle,
            dialogClass: 'success-dialog',
            buttons: {
                OK: function() {
                    $( this ).dialog( "close" );
                }
            }
        });//alert(alertMessaage);
    },
    tidiitConfirm:function(boxTitle,confirmMessaage,actionUrlWithData,height){
        if(height==0){
            height=175;
        }
        $('#dialog-confirm-message-text').text(confirmMessaage);
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:height,
            width:450,
            modal: true,
            title:boxTitle,
            dialogClass: 'success-dialog',
            buttons: {
                "OK": function() {
                    location.href=actionUrlWithData;
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    },
    tidiitConfirm1:function(boxTitle,confirmMessaage,height,formId,oldSubmitStatus){
        if(height==0){
            height=175;
        }
        $('#dialog-confirm-message-text').text(confirmMessaage);
        $( "#dialog-confirm" ).dialog({
            resizable: false,
            height:height,
            width:450,
            modal: true,
            title:boxTitle,
            dialogClass: 'success-dialog',
            buttons: {
                "OK": function() {
                    oldSubmitStatus=true;
                    $(formId).submit();
                    $( this ).dialog( "close" );
                },
                Cancel: function() {
                    $( this ).dialog( "close" );
                }
            }
        });
    },
    showPleaseWait: function() {
        pleaseWaitDiv.modal('show');
    },
    hidePleaseWait: function () {
        pleaseWaitDiv.modal('hide');
    }
}
    
    


