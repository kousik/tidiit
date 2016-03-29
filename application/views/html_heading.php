<!doctype html><html><head>
       <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="<?php echo SiteImagesURL;?>favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo SiteImagesURL;?>favicon.ico" type="image/x-icon">
        <title>Tidiit</title>
        
        <!-- Bootstrap core CSS -->
    	<link href="<?php echo SiteCSSURL;?>bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo SiteCSSURL?>jquery-ui.css" rel="stylesheet" type="text/css">
        <style type="text/css">.ui-dialog { z-index: 10000 !important ;}label.error{color: red !important;}</style>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <!-- Custom CSS -->
    	<link href="<?php echo SiteCSSURL;?>custom.css" rel="stylesheet">
    	<link href="<?php echo SiteCSSURL;?>new.css" rel="stylesheet">
    	<link href="<?php echo SiteCSSURL;?>custom_new.css" rel="stylesheet">
        
        <!-- font awesome CSS -->
    	<link href="<?php echo SiteCSSURL;?>font-awesome.min.css" rel="stylesheet">
        
        <!-- Skdslider styles for this template -->
    	<link href="<?php echo SiteCSSURL;?>skdslider.css" rel="stylesheet">
        
        
        <!-- owl carousel CSS -->
    	<link href="<?php echo SiteCSSURL;?>owl.carousel.css" rel="stylesheet">
        <link href="<?php echo SiteCSSURL;?>owl.theme.css" rel="stylesheet">
        <link href="<?php echo SiteCSSURL;?>community.css" rel="stylesheet">
        <?php /*<link href="<?php echo SiteCSSURL;?>login.css" rel="stylesheet" type="text/css" media="all" /> */?>
        <script type="text/javascript">
        //<![CDATA[
            // URL for access ajax data
            myJsMain = window.myJsMain || {};
            myJsMain.baseURL = '<?php echo BASE_URL;?>';
            myJsMain.showHowItWorksBoxLoaded =0;
            myJsMain.securityCode='<?php echo $this->session->userdata("secret");?>';
            <?php if($this->session->userdata('FE_SESSION_VAR')==''):?>
            myJsMain.isLogedIn=false;
            <?php else:?>
            myJsMain.isLogedIn=true;
            <?php endif;?>
            <?php if($isMobile=='yes'):?>
                myJsMain.isMobile='yes';
            <?php else: ?>
                myJsMain.isMobile='no';
            <?php endif;?>    
            myJsMain.SecretTextSetAjaxURL='<?php echo BASE_URL.'ajax/reset_secret/'?>';
            myJsMain.CaptchaCookeName='<?php echo $this->config->item('CAPTCHA_COOKIE_NAME');?>';     
        //]]>
        manualClick=false;
            var searchurl = '<?php echo BASE_URL;?>';
        </script>
        <!-- General JS fiies start here
        <script src="<?php //echo SiteJSURL;?>1.11.2_jquery.min.js"></script> -->
        <script src="<?php echo SiteJSURL;?>jquery.min.js"></script>
        <script src="<?php echo SiteJSURL;?>bootstrap.min.js"></script>
        <script src="<?php echo SiteJSURL;?>jquery-ui.min.js"></script>
        <script src="<?php echo SiteJSURL;?>jQuery.print.js" type="text/javascript"></script>
        <script src="<?php echo SiteResourcesURL;?>third-party/forms/jquery.form.js"></script>
        
        <!-- General JS fiies end here-->
        
        <!-- Skd Slider Assets -->
        <script src="<?php echo SiteJSURL;?>skdslider.min.js"></script>
        
        <!-- Owl Carousel Assets -->
	<script src="<?php echo SiteJSURL;?>owl.carousel.js"></script> 
        
        <script src="<?php echo SiteJSURL;?>jquery.validate.min.js"></script>
        <?php /*<script src="<?php echo SiteJSURL;?>jzaefferer-jquery-form-validatation.js"></script>*/?>
        <script src="<?php echo SiteJSURL;?>common.js"></script>
        <script src="<?php echo SiteJSURL;?>forgot-password-login-registration.js"></script>
        <script src="<?php echo SiteJSURL;?>form.js"></script>
        <script src="<?php echo SiteJSURL;?>jquery.ui.autocomplete.html.js"></script>
    </head>