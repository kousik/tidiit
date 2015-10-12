<body>
<?php //$StyleArr=;?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="right" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="right" valign="top"><?php echo anchor('admin/index', 'Home',array('title'=>'Home','class'=>'AdminHeaderMenuTextLink'));?></td>
            <td width="70" align="center" valign="top"><?php echo anchor('admin/index/logout', 'Logout',array('title'=>'Logout','class'=>'AdminHeaderMenuTextLink'));?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td align="left" valign="top"><img src="<?php echo $SiteImagesURL.'admin/logo.gif';?>" border="0" alt="logo"></td>
      </tr>
    </table></td
  ></tr>