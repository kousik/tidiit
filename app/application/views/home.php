<?php echo $AdminHomeLeftPanel;?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr>
      <td colspan="3" style="color: red;"><b><?php echo $this->session->flashdata('AdminHeaderMessage'); if(isset($AdminHeaderMessage)){echo $AdminHeaderMessage;}?></b></td>
  </tr>  
  <tr>
  <td width="375" valign="top">
  <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border:#FBE554 1px solid;">
            <tr bgcolor="#FEF191" height="20">
              <td style="padding-left:10px; font-weight:bold;"> Site Management</td>
            </tr>
            <tr height="20">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'site_config'?>">Site Configuration</a> </td>
            </tr>
            
            <tr height="20">
              <td  style="padding-left:10px;">&nbsp; </td>
            </tr>
            
			<tr height="20">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'welcome/change_pass';?>">Change Password</a> </td>
            </tr>
      </table>
    	
    

      
      
      </td>
  <td width="20">&nbsp;		  </td>
      <td width="450">
	
          <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border:#FBE554 1px solid;">
            <tr bgcolor="#FEF191" height="20">
              <td style="padding-left:10px; font-weight:bold;"> Event Managment </td>
            </tr>
            <tr height="20">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'event/viewlist/'?>">Event Manager</a> </td>
            </tr>
      </table>
    <br />
	
  	<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border:#FBE554 1px solid;">
          <tr bgcolor="#FEF191" height="20">
            <td style="padding-left:10px; font-weight:bold;">User Loged Data Management</td>
          </tr>
          <tr height="20">
            <td height="20" style="padding-left:10px;"><img src="<?=$SiteImagesURL?>arrow.gif" border="0" align="absmiddle"/><a href="<?php echo base_url().'welcome/showLogedList/'?>">User Loged Data Manager </a> </td>
          </tr>
         
      </table>
      <br />
    
    
    </td>
  </tr>
</table>
<?php echo $AdminHomeRest;?>