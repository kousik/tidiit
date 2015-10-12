<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title> :: Admin Panel</title>
<?php //echo link_tag($link);?>
<link href="<?=$SiteCSSURL?>style.css" rel="stylesheet" type="text/css">
<script type="text/JavaScript" src="<?=$SiteJSURL?>jquery.js"></script>

</head>
<body>
<div id="maincontainer">
<div id="header">
<div class="logo"> <h1 align="center" style="color:#fff; font-size:20px; line-height:70px;"><img src="<?=$SiteImagesURL?>logo.png" alt="" style="padding-top:30px;" />.COM Administrator Panel</h1></div>
</div>
<div class="clear"></div>

<form name="logFrm" action="<?php echo base_url().'welcome/check_login/';?>" method="post">

<div class="loginbox">
<h1>Admin login</h1>
<div class="b1"><label><span style="color:#FF0000"><?php echo $this->session->flashdata('Message');?></span></label></div>
<div class="b1"><label>User Name</label></div>
<div class="b1"><input name="UserName" type="text" id="UserName"    class="inputb"/></div>
<div class="b1"><label>Password</label></div>
<div class="b1"><input name="Password" id="Password" type="password"   class="inputb"/></div>
<div class="b1">
<input name="login" type="submit" value="Login" class="btn" />
<input name="Reset" type="reset" value="Reset" class="btn"/></div>
</div>
</form>

</div>
</body>
</html>