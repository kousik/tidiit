var ErrMsg = "";
var ok;

function ValidateForm(theForm)
{
	
	ErrMsg = "The following errors are occured\n\n";
	ok = true;

	var Validations = new Array()			
	var FieldsToValidate = new Array();
	FieldsToValidate = theForm.Validation.value.split("^");
	
	for(i=0;i<FieldsToValidate.length;i++)
	{
		var FieldAttribs = new Array();
		FieldAttribs=FieldsToValidate[i].split("|");
		Validations[i] = new Array(FieldAttribs.length);
		for(j=0;j<FieldAttribs.length;j++)
		{
			var values = new Array();
			values = FieldAttribs[j].split("=");
			Attrib = trim(values[0]);
			AttribValue = trim(values[1]);
			Validations[i][Attrib]=AttribValue;
		}
	}
	
	
	
	for(i=0; i<Validations.length;i++)
	{
		switch (Validations[i]["Validate"].toUpperCase())
		{
			case "COMPARE":		
				ValidateCompare(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "BLANK":		
				ValidateBlank(theForm[Validations[i]["Field"]], Validations[i]);				
			break;
			case "CONFIRMPASSWORD":
				ValidateConfirmPassword(theForm, theForm[Validations[i]["Field"]], Validations[i]);	
			break;x
			case "EMAIL":
				ValidateEmail(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "SELECT":
				ValidateRadio(theForm, Validations[i]["Field"], Validations[i]);
			break;
			case "MULTISELECT":
				ValidateCheckBox(theForm, Validations[i]["Field"], Validations[i]);
			break;
			case "COMBO":
			    ValidateComboBox(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "LISTBOX":
			    ValidateListBox(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "NUMERIC":
				ValidateNumeric(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "DECIMAL":
				ValidateDecimal(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "ALPHA":
				ValidateAlpha(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "ALPHANUMERIC":
				ValidateAlphaNumeric(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "FILEEXTENSION":
				ValidateFileExtension(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "STRINGMATCH":
				ValidateString(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "REGULAREXPRESSION":
				ValidateRegExp(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "DATE":
				ValidateDate(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "USSTATE":
				ValidateUSState(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "USZIP":
				ValidateUSZIP(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "ZIP":
				ValidateZIP(theForm[Validations[i]["Field"]], Validations[i]);
			break;
			case "PHONE":		
				ValidatePhone(theForm[Validations[i]["Field"]], Validations[i]);
			break
			case "TEXTAREALIMIT":		
				ValidateTextAreaLimit(theForm[Validations[i]["Field"]], Validations[i]);
			break	
		}
	}
	
	if(!ok)
		alert(ErrMsg);
	
	return ok;
}


function rightTrim(strValue) {
var objRegExp = /^([\w\W]*)(\b\s*)$/;
      if(objRegExp.test(strValue)) {
       strValue = strValue.replace(objRegExp, '$1');
    }
  return strValue;
}

function leftTrim(strValue) {
var objRegExp = /^(\s*)(\b[\w\W]*)$/;
 
      if(objRegExp.test(strValue)) {
       strValue = strValue.replace(objRegExp, '$2');
    }
  return strValue;
}

function trim(strValue) {
 var objRegExp = /^(\s*)$/;
    if(objRegExp.test(strValue)) {
       strValue = strValue.replace(objRegExp, '');
       if( strValue.length == 0)
          return strValue;
    }
   objRegExp = /^(\s*)([\W\w]*)(\b\s*$)/;
   if(objRegExp.test(strValue)) {
       strValue = strValue.replace(objRegExp, '$2');
    }
  return strValue;
}


function checkBlank(Element, FieldAttrib)
{
	if(trim(Element.value)=="")
	{
		ErrMsg+="- Please enter the value of "+FieldAttrib["Alias"]+"\n";
		ok=false;
		return false;
	}
	else
		return true;
}

function checkLength(Element, FieldAttrib)
{
	
	if(FieldAttrib["Length"])
	{
		if(Element.value.length>FieldAttrib["Length"])
		{
			ErrMsg+="- "+FieldAttrib["Alias"]+" should be less than or equals to "+FieldAttrib["Length"]+" character\n";
			ok=false;
			return false;
		}
		else
			return true;
	}
}

function CompareTo(Element, FieldAttrib)
{
	if(FieldAttrib["CompareTo"])
	{
		if(Element.value!=FieldAttrib["CompareTo"])
		{
			ErrMsg+="- "+FieldAttrib["Alias"]+" should be same as "+FieldAttrib["Alias1"]+"\n";
			ok=false;
			return false;
		}
		else
			return true;
		
	}
}

function checkPhone(Element, FieldAttrib)
{
	StringToMatch = FieldAttrib["Characters"];
	var totaldigits = 0;
	for(var i=0;i<Element.value.length;i++)
	{
		if(StringToMatch.indexOf(Element.value.charAt(i))<0)
		{
			ErrMsg+="- Please enter a valid value for "+FieldAttrib["Alias"]+"\n";
			ok=false;
			return false;
			break;
		}
		if(!isNaN(Element.value.charAt(i)))
			totaldigits++
	}
	if(totaldigits<FieldAttrib["MinDigits"])
	{
		ErrMsg+="- "+FieldAttrib["Alias"]+" should contain at least "+ FieldAttrib["MinDigits"] +" digits\n";
		ok=false;
		return false;
	}
	else
		return true;
}

function checkRegExp(Element, RegExpString, FieldAttrib)
{
	if(!RegExpString.test(Element.value))
	{
		ErrMsg+="- Please enter a proper value of "+FieldAttrib["Alias"]+"\n";
		ok=false;
		return false;
	}
	else
		return true;
}


function checkDate(strValue, Format, FieldAttrib)
{
	Format = Format.toUpperCase();
	if(FieldAttrib["FullYear"]){
		if(FieldAttrib["FullYear"]=="YES"){
			var objRegExp = /^\d{1,4}(\/|-)\d{1,4}(\/|-)\d{1,4}$/;
		}else{
			var objRegExp = /^\d{1,4}(\/|-)\d{1,4}(\/|-)\d{1,4}$/;
		}
	}else{
		var objRegExp = /^\d{1,4}(\/)\d{1,4}(\/)\d{1,4}$/;
	}
  	if(!objRegExp.test(strValue))
	{
		ErrMsg+="- Please enter a valid date for "+FieldAttrib["Alias"]+"\n";
		ok=false;
		return ok;
	}
  	else
	{
		if(strValue.indexOf("/"))
		    var seperator = "/"; //split date into month, day, year
		else
		    var seperator = "-"; //split date into month, day, year 
		
		var arrayDate = strValue.split(seperator);
		var arrayFormat = Format.split(seperator);

		for(var i=0;i<3;i++){
		    if(arrayFormat[i].indexOf("M")>=0){
		        var intMonth = parseFloat(arrayDate[i],10);
		    }else if(arrayFormat[i].indexOf("Y")>=0){
		        var intYear = parseFloat(arrayDate[i],10);
		    }else if(arrayFormat[i].indexOf("D")>=0){
		        var intDay = parseFloat(arrayDate[i],10);
		    }
		}
		if(intMonth > 12 || intMonth < 1) {
			ErrMsg+="- Please enter a valid date for "+FieldAttrib["Alias"]+"\n";
			ok=false;
			return ok;
		}
		else
		{
			var arrayLookup = { '1' : 31,'3' : 31, '4' : 30,'5' : 31,'6' : 30,'7' : 31,
								'8' : 31,'9' : 30,'10' : 31,'11' : 30,'12' : 31}
			if(intMonth != 2) {
			  if(intDay <= arrayLookup[intMonth] && intDay != 0)
				return true;
			}
			else
			{
				if(FieldAttrib["FullYear"]=="Yes"){
					var booLeapYear = (intYear % 4 == 0 && (intYear % 100 != 0 || intYear % 400 == 0));
					if( ((booLeapYear && intDay <= 29) || (!booLeapYear && intDay <=28)) && intDay !=0)
					  return true;
				}else{
					return true;
				}
			}
		}
	}
	ErrMsg+="- Please enter a valid date for "+FieldAttrib["Alias"]+"\n";
	ok=false;
  	return false;
}

function checkStringMatch(Element, FieldAttrib)
{
	StringToMatch = FieldAttrib["StringToMatch"];
	for(var i=0;i<Element.value.length;i++)
	{
		if(StringToMatch.indexOf(Element.value.charAt(i))<0)
		{
			ErrMsg+="- Please enter a valid value for "+FieldAttrib["Alias"]+"\n";
			ok=false;
			return false;
			break;
		}
	}
	return true;
}


function ValidateCompare(Element, FieldAttrib)
{
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			CompareTo(Element, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			CompareTo(Element, FieldAttrib);
	}
}


function ValidatePhone(Element, FieldAttrib)
{
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkPhone(Element, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkPhone(Element, FieldAttrib);
	}
}

function ValidateBlank(Element, FieldAttrib)
{
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkLength(Element, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkLength(Element, FieldAttrib);
	}
		
}


function ValidateZIP(Element, FieldAttrib)
{
	var objRegExp  =  /^[a-zA-Z0-9]+$/;
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
}


function ValidateUSState(Element, FieldAttrib)
{
	var objRegExp = /^(AK|AL|AR|AZ|CA|CO|CT|DC|DE|FL|GA|HI|IA|ID|IL|IN|KS|KY|LA|MA|MD|ME|MI|MN|MO|MS|MT|NB|NC|ND|NH|NJ|NM|NV|NY|OH|OK|OR|PA|RI|SC|SD|TN|TX|UT|VA|VT|WA|WI|WV|WY)$/i; 
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
}


function ValidateUSZIP(Element, FieldAttrib)
{
	var objRegExp  = /(^\d{5}$)|(^\d{5}-\d{4}$)/;
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
}

function ValidateEmail(Element, FieldAttrib)
{
	var objRegExp  = /^[a-z0-9]([a-z0-9_\-\.]*)@([a-z0-9_\-\.]*)(\.[a-z]{2,3}(\.[a-z]{2}){0,2})$/i;
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
}


function ValidateNumeric(Element, FieldAttrib)
{
	var objRegExp  = /(^-?\d\d*$)/;
	if(FieldAttrib["Optional"])
	{	
		if(trim(Element.value)!="")
			if(checkRegExp(Element, objRegExp, FieldAttrib)){
				if(FieldAttrib["Minimum"] && !isNaN(FieldAttrib["Minimum"])){
					if(Element.value<FieldAttrib["Minimum"]){
						ErrMsg+="- Value of "+FieldAttrib["Alias"]+" should be at least "+FieldAttrib["Minimum"]+"\n";
						ok=false;
						return false;
					}
				}
				if(FieldAttrib["Maximum"] && !isNaN(FieldAttrib["Maximum"])){
					if(Element.value>FieldAttrib["Maximum"]){
						ErrMsg+="- Value of "+FieldAttrib["Alias"]+" should be less than "+FieldAttrib["Maximum"]+"\n";
						ok=false;
						return false;
					}
				}
			}
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			if(checkRegExp(Element, objRegExp, FieldAttrib)){
				if(FieldAttrib["Minimum"] && !isNaN(FieldAttrib["Minimum"])){
					if(Element.value<FieldAttrib["Minimum"]){
						ErrMsg+="- Value of "+FieldAttrib["Alias"]+" should be at least "+FieldAttrib["Minimum"]+"\n";
						ok=false;
						return false;
					}
				}
				if(FieldAttrib["Maximum"] && !isNaN(FieldAttrib["Maximum"])){
					if(Element.value>FieldAttrib["Maximum"]){
						ErrMsg+="- Value of "+FieldAttrib["Alias"]+" should be less than "+FieldAttrib["Maximum"]+"\n";
						ok=false;
						return false;
					}
				}
			}
	}
}

function ValidateDecimal(Element, FieldAttrib)
{
	var objRegExp  =  /(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/; 
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			if(checkRegExp(Element, objRegExp, FieldAttrib)){
				if(FieldAttrib["Minimum"] && !isNaN(FieldAttrib["Minimum"])){
					if(Element.value<FieldAttrib["Minimum"]){
						ErrMsg+="- Value of "+FieldAttrib["Alias"]+" should be at least "+FieldAttrib["Minimum"]+"\n";
						ok=false;
						return false;
					}
				}
				if(FieldAttrib["Maximum"] && !isNaN(FieldAttrib["Maximum"])){
					if(Element.value>FieldAttrib["Maximum"]){
						ErrMsg+="- Value of "+FieldAttrib["Alias"]+" should be less than "+FieldAttrib["Maximum"]+"\n";
						ok=false;
						return false;
					}
				}
			}
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			if(checkRegExp(Element, objRegExp, FieldAttrib)){
				if(FieldAttrib["Minimum"] && !isNaN(FieldAttrib["Minimum"])){
					if(Element.value<FieldAttrib["Minimum"]){
						ErrMsg+="- Value of "+FieldAttrib["Alias"]+" should be at least "+FieldAttrib["Minimum"]+"\n";
						ok=false;
						return false;
					}
				}
				if(FieldAttrib["Maximum"] && !isNaN(FieldAttrib["Maximum"])){
					if(Element.value>FieldAttrib["Maximum"]){
						ErrMsg+="- Value of "+FieldAttrib["Alias"]+" should be less than "+FieldAttrib["Maximum"]+"\n";
						ok=false;
						return false;
					}
				}
			}
	}
}

function ValidateAlphaNumeric(Element, FieldAttrib)
{
	var objRegExp  =  /^[a-zA-Z0-9]+$/;
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
}

function ValidateAlpha(Element, FieldAttrib)
{
	var objRegExp  =  /^[a-zA-Z]+$/;
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkRegExp(Element, objRegExp, FieldAttrib);
	}
}

function ValidateDate(Element, FieldAttrib)
{
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkDate(Element.value, FieldAttrib["Format"], FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkDate(Element.value, FieldAttrib["Format"], FieldAttrib);
	}
}

function ValidateRegExp(Element, FieldAttrib)
{
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkRegExp(Element.value, FieldAttrib["Format"], FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkRegExp(Element.value, FieldAttrib["Format"], FieldAttrib);
	}
}

function ValidateString(Element, FieldAttrib)
{
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
			checkStringMatch(Element, FieldAttrib);
	}
	else
	{
		if(checkBlank(Element, FieldAttrib))
			checkStringMatch(Element, FieldAttrib);
	}	
}

function ValidateRadio(theForm, Element, FieldAttrib)
{
	for(var i=0;i<theForm[Element].length;i++)
	{
		if(theForm[Element][i].checked==true)
		{
			return true;
			break;
		}
	}
	ErrMsg+="- Please select a value for "+FieldAttrib["Alias"]+"\n";
	ok=false;
	return false;
}

function ValidateCheckBox(theForm, Element, FieldAttrib)
{
	ElementName=Element;
	var NoOfChecked=0;
	if(FieldAttrib["Optional"])
	{
		if(FieldAttrib["Max"])
		{
			for(var i=0;i<theForm[ElementName].length;i++)
			{
				if(theForm[ElementName][i].checked==true)
					NoOfChecked++;
			}
			if(NoOfChecked!=0)
			{
				if(NoOfChecked>FieldAttrib["Max"])
				{
					ErrMsg+="- You can select maximum "+FieldAttrib["Max"]+" value(s) for "+FieldAttrib["Alias"]+"\n";
					ok=false;
					return false;
				}
			}
		}
	}
	else
	{
		if(theForm[ElementName].length){
			for(var i=0;i<theForm[ElementName].length;i++)
			{
				if(theForm[ElementName][i].checked==true)
					NoOfChecked++;
			}
		}
		else{
				if(theForm[ElementName].checked==true)
					NoOfChecked++;
		}
		if(NoOfChecked==0)
		{
			ErrMsg+="- Please select a value for "+FieldAttrib["Alias"]+"\n";
			ok=false;
			return false;
		}
		else if(FieldAttrib["Max"])
		{
			if(NoOfChecked>FieldAttrib["Max"])
			{
				ErrMsg+="- You can select maximum "+FieldAttrib["Max"]+" value(s) for "+FieldAttrib["Alias"]+"\n";
				ok=false;
				return false;
			}
		}
	}
}

function ValidateComboBox(Element, FieldAttrib)
{
	
	if(Element.selectedIndex<0 || Element.selectedIndex==0)
	{
		ErrMsg+="- Please select a value for "+FieldAttrib["Alias"]+"\n";
		ok=false;
		return false;
	}
}

function ValidateListBox(Element, FieldAttrib)
{
	var b=0;
    for (i = 0; i < Element.options.length; i++)
    {
        if (Element.options[i].selected) 
        { 
            var b = 1 
        }
    }
    if (!b)
    {
        ErrMsg+="- Please select a value for "+FieldAttrib["Alias"]+"\n";
		ok=false;
		return false;
    }
}

function ValidateConfirmPassword(theForm, Element, FieldAttrib)
{
	if(checkBlank(Element, FieldAttrib))
	{
		if(Element.value!=theForm[FieldAttrib["CompareTo"]].value)
		{
			ErrMsg+="- Password and Confirm Password should be same\n";
			ok=false;
			return false;
		}
	}
}

function ValidateFileExtension(Element, FieldAttrib)
{
	if(FieldAttrib["Optional"])
	{
		if(trim(Element.value)!="")
		{
			if(FieldAttrib["Extensions"])
			{
				ArrExt = new Array();
				ArrExt = FieldAttrib["Extensions"].split(",");
				Ext = Element.value;
                ArrFilename=Ext.split(".");
                for(var i=0;i<ArrExt.length;i++)
                {
                    if(ArrExt[i].toUpperCase()==ArrFilename[ArrFilename.length-1].toUpperCase())
                    return true;
                }
				
				ErrMsg+="- Only";
				for(var i=0;i<ArrExt.length;i++)
				{
					ErrMsg+=" "+ArrExt[i].toUpperCase();
				}
				ErrMsg+=" files are allowed for "+FieldAttrib["Alias"]+"\n";
				ok=false;
			}
		}
	}
	else
	{
		if(checkBlank(Element, FieldAttrib)){
			if(FieldAttrib["Extensions"])
			{
				ArrExt = new Array();
				ArrExt = FieldAttrib["Extensions"].split(",");
				Ext = Element.value;
                ArrFilename=Ext.split(".");
                for(var i=0;i<ArrExt.length;i++)
                {
                    if(ArrExt[i].toUpperCase()==ArrFilename[ArrFilename.length-1].toUpperCase())
                    return true;
                }
				
				ErrMsg+="- Only";
				for(var i=0;i<ArrExt.length;i++)
				{
					ErrMsg+=" "+ArrExt[i].toUpperCase();
				}
				ErrMsg+=" files are allowed for "+FieldAttrib["Alias"]+"\n";
				ok=false;
			}
		}
	}
}

function ValidateTextAreaLimit(Element, FieldAttrib)
{
	var len=FieldAttrib["CharLength"];
	if(Element.value.length>0)
	{
			if (Element.value.length>len)
			{
				ErrMsg+="- "+ FieldAttrib["Alias"] + " length cannot exceeds " + len + " characters\n";
			ok=false;
			return false;
			}
	}
	
		
}