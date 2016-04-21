<?php echo $AdminHomeLeftPanel;?>
<style>
    .deal_text_box{min-width: 50px !important;float: left;}.lightbox_fourground{top:40% !important;}
</style>
<link href="<?=$SiteCSSURL?>webadmin/lightbox.css" rel="stylesheet" type="text/css">
<table cellspacing=5 cellpadding=5 width=90% border=0 >
  
  <tr>
    <td colspan="3" style="color: red;"><b><?php echo $this->session->flashdata('Message');?></b></td>
  </tr>  
  <tr>
  <td width="375" valign="top">
  <table cellpadding="1" cellspacing="1" border="0" width="100%" style="border:#DFDFDF 1px solid;">
            <tr bgcolor="#DFDFDF" height="20">
              <td style="padding-left:10px; font-weight:bold;"> Site Data Management</td>
            </tr>
            <tr bgcolor="#DFDFDF" height="20">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/site_config'?>">Site Configuration</a> </td>
            </tr>
			<tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/index/change_pass';?>">Change Password</a> </td>
            </tr>
			<tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/cms/viewlist/'?>">Manage Static Pages Content</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/country_controller/viewlist/'?>">country Manager</a> </td>
            </tr>            
            <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/banner/viewlist/'?>">Banner Manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/faq/viewlist/'?>">FAQ Manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/help/viewlist/'?>">Help Manager</a> </td>
            </tr>
            <?php /* ?>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/zeozone/viewlist';?>">Zeo Zone manager</a> </td>
            </tr> */?>
            <?php ?>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/tax/viewlist';?>">Tax manager</a> </td>
            </tr>
            
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/shipping/viewlist';?>">Shipping manager</a> </td>
            </tr>
            <?php /*?><tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/home_banner/viewlist';?>">Home Page Banner manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/discount/viewlist';?>">Discount manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/custom_page_seo/viewlist';?>">Landing and Home Page SEO Tag manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/custom_page_seo/viewlist1';?>">SEO Data Manage for Popular Store,DP Deals,Customer Review FAQ</a> </td>
            </tr>*/?>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/brand/viewlist';?>">Brand manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/coupon/viewlist';?>">Coupon manager</a> </td>
            </tr>
              <tr height="20" bgcolor="#DFDFDF">
                  <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/options/viewlist';?>">Options manager</a> </td>
              </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/logistics/viewlist';?>">Logistics manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/faq_topics/viewlist';?>">FAQ Topics manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/help_topics/viewlist';?>">Help Topics manager</a> </td>
            </tr>
            <tr height="20" bgcolor="#DFDFDF">
              <td  style="padding-left:10px;"><img src="<?=$SiteImagesURL?>webadmin/arrow.gif" border="0" align="absmiddle"/>&nbsp;<a href="<?php echo base_url().'webadmin/tidiit_commission/viewlist';?>">Tidiit Commission manager</a> </td>
            </tr>
  </table>
      
      </td>
  </tr>
</table>
<?php echo $AdminHomeRest;?>