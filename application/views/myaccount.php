<?php echo $html_heading; echo $header;?>
</div>
</header>
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
                        	<div class="col-md-12 col-sm-12">
                                    <div class="col-md-3 col-sm-3 dashboard red" title="Update Profile">
                                    <span><i class="fa  fa-dashboard fa-5x" onclick="location.href=myJsMain.baseURL+'my-profile';"></i></span>
                            	</div>
                                
                                <div class="col-md-3 col-sm-3 dashboard blue" title="Shipping Address">
                                	<span><i class="fa fa-truck fa-5x" onclick="location.href=myJsMain.baseURL+'my-shipping-address';"></i></span>
                            	</div>
                                
                                <div class="col-md-3 col-sm-3 dashboard green" title="Billing Address">
                                	<span><i class="fa   fa-ship fa-5x" onclick="location.href=myJsMain.baseURL+'my-billing-address';"></i></span>
                            	</div>
                                
                                <div class="col-md-3 col-sm-3 dashboard yellow" title="My Groups">
                                	<span><i class="fa   fa-group fa-5x" onclick="location.href=myJsMain.baseURL+'my-groups';"></i></span>
                            	</div>
                                
                                <div class="col-md-3 col-sm-3 dashboard purple" title="My Orders">
                                	<span><i class="fa fa-server fa-5x" onclick="location.href=myJsMain.baseURL+'my-orders';"></i></span>
                            	</div>
                                
                                <div class="col-md-3 col-sm-3 dashboard maroon" title="Finance Info">
                                	<span><i class="fa  fa-credit-card fa-5x" onclick="location.href=myJsMain.baseURL+'my-finance-info';"></i></span>
                            	</div> 
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