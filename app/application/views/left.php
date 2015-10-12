<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> iReport Management Section</title>
<?php //echo link_tag($link);?>
<link href="<?=$SiteCSSURL?>style.css" rel="stylesheet" type="text/css">
<?php if($this->uri->segment(2)!='product' && $this->uri->segment(2)!='order'){?>
<script type="text/JavaScript" src="<?php echo $SiteJSURL?>jquery.min.js"></script>
<script type="text/JavaScript" src="<?=$SiteJSURL?>jzaefferer-jquery-form-validatation.js"></script>
<?php }?>

<style>
    .error{color: red;font-weight: bold;}
</style>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" height="100%">
  <tr>
    <td  align="left" valign="top" class="leftmenubg">
<div class="menulft">
        <ul>
          <li><a href="<?php echo base_url().'welcome/';?>" <?php if($CurrentCont=='' || $CurrentCont=='welcome') echo 'class="active"';?>>Home</a></li>
          <li><a href="<?php echo base_url().'site_config'?>" <?php if($CurrentCont=='site_config') echo 'class="active"';?>>Site Config</a></li>
          <li><a href="<?php echo base_url().'welcome/change_pass';?>" <?php //if($p=='admin_edit') echo 'class="active"';?>>Change Password</a></li>
          <li><a href="<?php echo base_url().'event/viewlist/'?>" <?php if($CurrentCont=='event' && $this->uri->segment(3)=='viewlist') echo 'class="active"';?>>Event Management</a></li>
          <li><a href="<?php echo base_url().'welcome/showLogedList/'?>" <?php if($CurrentCont=='index' && $this->uri->segment(3)=='showLogedList') echo 'class="active"';?>>Loged Data Mgt.</a></li>
          <li><a href="<?php echo base_url().'welcome/logout/'?>">Logout</a></li>
        </ul>
      </div>
</td>
    <td  align="left" valign="top" class="pad1">
    <!-- Body  with header -- stated here-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
    <!-- header started here-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
				<td width="315px;"><a href="./"><img src="<?=$SiteImagesURL?>logo.png" border="0" height="100px"   align="left"/></a>
				</td>
				<td>&nbsp;</td>
		</tr>
</table>
	<!-- header ended here-->
</td>
  </tr>
  <tr>
    <td>