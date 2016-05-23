<?php echo $AdminHomeLeftPanel;?>
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr>
      <td colspan="3" style="color: red;"><b><?php echo $this->session->flashdata('AdminHeaderMessage'); if(isset($AdminHeaderMessage)){echo $AdminHeaderMessage;}?></b></td>
  </tr>  
  <tr>
      
  <td width="375" valign="top">
    
  <table cellpadding="1" cellspacing="1" border="0" width="100%" style="border:#DFDFDF 1px solid;">
    <?php if(user_role_check('user','viewlist')==TRUE){?>
            <tr bgcolor="#DFDFDF" height="20">
              <td style="padding-left:10px; font-weight:bold;"> Site Management</td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/site_config'?>">Site Configuration</a> </td>
            </tr>
    <?php }?>    
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/index/change_pass';?>">Change Password</a> </td>
            </tr>
    <?php if(user_role_check('role','group')==TRUE){?>        
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/role/group';?>">Role Manager</a> </td>
            </tr>
    <?php }?>        
      </table>
    <?php if(user_role_check('user','viewlist')==TRUE){?>        <br />
          <table cellpadding="1" cellspacing="1" border="0" width="100%" style="border:#DFDFDF 1px solid;">
            <tr bgcolor="#DFDFDF" height="20">
              <td style="padding-left:10px; font-weight:bold;"> User Managment </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/user/viewlist/'?>">Users Manager</a> </td>
            </tr>
            
    </table><?php }  if(user_role_check('category','viewlist')==TRUE){?>
    <br />
	<table cellpadding="1" cellspacing="1" border="0" width="100%" style="border:#DFDFDF 1px solid;">
          <tr bgcolor="#DFDFDF" height="20">
            <td style="padding-left:10px; font-weight:bold;">Category and Product Management</td>
          </tr>
          <tr height="20" bgcolor="#DFDFDF">
            <td height="20" style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/><a href="<?php echo base_url().'webadmin/category/viewlist/'?>">Category Manager </a> </td>
          </tr>
         <?php /*<tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/product/viewlist/'?>">Product Manager</a> </td>
            </tr>
         <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/product/tag_list/'?>">Product Tag Manager</a> </td>
            </tr>*/?>
      </table>
      <br />
    <?php }?>

      </td>
  <td width="20">&nbsp;		  </td>
      <td width="450">
	<?php   if(user_role_check('order','viewlist')==TRUE){?>	
  	<table cellpadding="1" cellspacing="1" border="0" width="100%" style="border:#DFDFDF 1px solid;">
            <tr bgcolor="#DFDFDF" height="20">
              <td height="21" style="padding-left:10px; font-weight:bold;">  Order Management </td>
        </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/order/viewlist/'?>">Order Manager</a></td>
        </tr>
        <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/user/reviews_list/'?>">User Order Review List</a></td>
        </tr>
        </table> <?php }  
        if(user_role_check('index','viewsitedata')==TRUE){?>
       <br />
       <table cellpadding="1" cellspacing="1" border="0" width="100%" style="border:#DFDFDF 1px solid;">
            <tr bgcolor="#DFDFDF" height="20">
              <td style="padding-left:10px; font-weight:bold;"> Site Data </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/cms/viewlist/'?>">Manage Static Pages Content</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/banner/viewlist/'?>">Banner Manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/faq/viewlist/'?>">FAQ Manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/help/viewlist/'?>">Help Manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/country_controller/viewlist';?>">Country manager</a> </td>
            </tr>
            <?php /*
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/zeozone/viewlist';?>">Zeo Zone manager</a> </td>
            </tr>
            
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/shipping/viewlist';?>">Shipping manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/home_banner/viewlist';?>">Home Page Banner manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/discount/viewlist';?>">Discount manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/custom_page_seo/viewlist';?>">Landing and Home Page SEO Tag manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/custom_page_seo/viewlist1';?>">SEO Data Manage for Customer Review, FAQ</a> </td>
            </tr>*/?>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/tax/viewlist';?>">Tax manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/brand/viewlist';?>">Brand manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/logistics/viewlist';?>">Logistics manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/coupon/viewlist';?>">Coupon manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/shipping/viewlist';?>">Shipping manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/faq_topics/viewlist';?>">FAQ Topics manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/help_topics/viewlist';?>">Help Topics manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/options/viewlist';?>">Options manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/index/view_push_notification_test';?>">Push Notification Test</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;">
                  <img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>
                  &nbsp;<a href="<?php echo base_url().'webadmin/index/viewsitedata/';?>">Other Site Data</a> </td>
            </tr>
		</table>
        <br />
        <?php }?>
    
    </td>
      
  </tr>
</table>
<?php echo $AdminHomeRest;?>