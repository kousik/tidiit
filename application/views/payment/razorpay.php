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
                                        //myJsMain.commonFunction.showPleaseWait();
                                        //jQuery('#mpesaSubmitForm')[0].submit();
                                    });
                                </script>
                                <form action="<?php echo BASE_URL.'shopping/razorpay_return/';?>" method="POST">
                                <!-- Note that the amount is in paise = 50 INR -->
                                <script
                                    src="https://checkout.razorpay.com/v1/checkout-new.js"
                                    data-key="rzp_test_yfWhsaZcULj1KQ"
                                    data-amount="200"
                                    data-buttontext="Pay with Razorpay"
                                    data-name="Tidiit Internet Pvt Ltd"
                                    data-description="Purchase Description"
                                    data-image="http://www.tidiit.com/resources/images/logo.png"
                                    data-prefill.name="Harshil Mathur"
                                    data-prefill.email="support@razorpay.com"
                                    data-theme.color="#F37254"
                                ></script>
                                <input type="hidden" value="<?php echo $orderIds;?>" name="orderIds">
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