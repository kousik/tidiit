<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TIDIIT Seller Site</title>
    <!--<meta name="msapplication-TileColor" content="#5bc0de" />
    <meta name="msapplication-TileImage" content="assets/images/metis-tile.png" />-->
    <link href="<?php echo SiteCSSURL?>jquery-ui.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo SiteCSSURL;?>bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo SiteCSSURL;?>font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo SiteCSSURL;?>animate.min.css">
    
    <!-- metisMenu stylesheet -->
    <link rel="stylesheet" href="<?php echo SiteCSSURL;?>metisMenu.min.css">
    <link rel="stylesheet" href="<?php echo SiteCSSURL;?>fullcalendar.min.css">

    <!-- Metis core stylesheet
    <link rel="stylesheet" href="<?php //echo SiteCSSURL;?>main.min.css"> -->
    <link rel="stylesheet" href="<?php echo SiteCSSURL;?>main.css">
    <script type="text/javascript">
//<![CDATA[
    // URL for access ajax data
    myJsMain = window.myJsMain || {};
    
    myJsMain.baseURL = '<?php echo BASE_URL;?>';
    myJsMain.loginRequestURL='<?php echo BASE_URL.'ajax/check_login/'?>';
    myJsMain.registrationRequestURL='<?php echo BASE_URL.'ajax/check_registration/'?>';
    myJsMain.securityCode='<?php echo $this->session->userdata("secret");?>';
    myJsMain.SecretTextSetAjaxURL='<?php echo BASE_URL.'ajax/reset_secret/'?>';
    myJsMain.CaptchaCookeName='<?php echo $this->config->item('CAPTCHA_COOKIE_NAME');?>';
    
//]]>
</script>
<!--jQuery -->
    <script src="<?php echo SiteJSURL;?>jquery.min.js"></script>
    <script src="<?php echo SiteJSURL;?>jquery-ui.min.js"></script>
    <!--Bootstrap -->
    <script src="<?php echo SiteJSURL;?>bootstrap.min.js"></script>
    <script src="<?php echo SiteJSURL;?>jquery.validate.min.js"></script>
    <script src="<?php echo SiteJSURL;?>common.js"></script>
    
  </head>
  <script>
      $(document).ready(function(){
          var message='';
        <?php if($this->uri->segment(1)!='product'){?>  
            message='<?php echo $this->session->flashdata('Message');?>';
        <?php }?>
        if(message!=""){myJsMain.commonFunction.tidiitAlert('Tidiit System Message',message,200);}
      });
  </script>