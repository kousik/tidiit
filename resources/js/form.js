var jq = jQuery;
jq(document).ready( function() {
	 var fb = true;
	
	jq("body").find("form#wtil-cms-form").each(function(e){				
		jq(this).delegate("input[name='wtil-post-submit']", "click", function(e){
			// submit the form 
			var form = jq(this);
			do{
				form = form.parent();
			}
			while( !form.is("form") );
			wtil_cms_form(form);
			// return false to prevent normal browser submit and page navigation 
			return false; 
		});
	});
	
});

function wtil_cms_form(obj){
	jq(obj).ajaxSubmit({
		beforeSubmit: function(d,f,o) {
			o.dataType = 'html';
			var form_class = f.attr("class");
			var form = (form_class?"form[class='"+form_class+"']":"");
			jq(form+' div.cms-ajax-feedback').empty();
			jq(form+' div.cms-ajax-feedback').hide();
			jq(form+" input[name='wtil-post-submit']").attr('disabled', 'disabled');
			jq(form+' div.cms-module-loader').show();
		},
		success: function(data,s,x,f) {
			data = jq.trim(data);
			//alert(data);
			var error = false;
			var show_message = true;
			var form_class = f.attr("class");
			var form = (form_class?"form[class='"+form_class+"']":"");
	
			var jqout = jq(form+' div.cms-ajax-feedback');
			if (typeof data == 'object' && data.nodeType)
				data = elementToString(data.documentElement, true);
			else if (typeof data == 'object')
				data = objToString(data);
				
			if ( data.charAt(0) + data.charAt(1) == '-1' ) {
                            jq(form+" div.cms-ajax-feedback").html(data.substr( 2, data.length ));
                            jq(form+" div.cms-ajax-feedback").fadeIn(300,function() { setTimeout( 'jq("'+form+' div.cms-ajax-feedback").fadeOut(300)', 15000 ); });				
				error = true;
			}
			
                        
			if( !error ){
                            if( !jq(form+" input[name='noreset']").val() ){
                                jq(form).clearForm();
                                jq(form).resetForm();
                            }
                            
                            if( jq(form+" input[name='redirect']").val() && ( (data.charAt(0) + data.charAt(1)) != '-1' ) ){
                                window.location.href = jq(form+" input[name='redirect']").val();
                                return true;
                            }


                            if( show_message ) {
                                    var jqout = jq(form+' div.cms-ajax-feedback');
                                    jqout.html(data);
                            }

                            if( !jq("input[name='nohide']").val() ){
                                    jq(form+" div.cms-ajax-feedback").fadeIn(300,function() { setTimeout( 'jq("'+form+' div.cms-ajax-feedback").fadeOut(300)', 15000 ); });
                            }
                            else{
                                    jq(form+" div.cms-ajax-feedback").fadeIn(300);
                            }
			}
			jq(form+' div.cms-module-loader').hide();
			jq(form+" div.cms-ajax-feedback").fadeIn(300);
			jq(form+" input[name='wtil-post-submit']").removeAttr('disabled');
		}
	});
};