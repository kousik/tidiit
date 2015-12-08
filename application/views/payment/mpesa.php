<?php echo $html_heading; echo $header;?>
<article>
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12 productInner">
        <div class="page_content">
            <div class="row">
                <?php echo $userMenu;?>
                <div class="col-md-9 wht_bg">
                    <!-- Tab panes -->
                    <div class="tab_dashbord">
                    	<div class="active row">
                            <div class="col-md-12 col-sm-12" style="height: 100px;">
                                <script type="text/javascript">
                                    jQuery(document).ready(function(){
                                        myJsMain.commonFunction.showPleaseWait();
                                        jQuery('#mpesaSubmitForm')[0].submit();
                                    });
                                </script>
                                <?php if($_SERVER['HTTP_HOST']=='tidiit-local.com'):?>
                                <form name="mpesaSubmitForm" id="mpesaSubmitForm" action="<?php echo 'http://demosandbox.tidiit-local.com/index.php';?>" method="post">
                                <?php else:?>    
                                <form name="mpesaSubmitForm" id="mpesaSubmitForm" action="<?php echo 'http://demosandbox.tidiit.com/index.php';?>" method="post">
                                <?php endif;?>
                                    <input type="hidden" name="custom" value="<?php echo $orderId;?>" />
                                    <input type="hidden" name="cartId" value="<?php echo md5('tidiit123');?>" />
                                    <input name="return_url" value="<?php echo BASE_URL.'shopping/mpesa_return/';?>">
                                </form>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</article>
<?php echo $footer;?>