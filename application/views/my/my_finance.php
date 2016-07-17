<?php echo $html_heading; echo $header;?>
<script src="<?php echo SiteJSURL;?>user-all-my-js.js" type="text/javascript"></script>
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
                                <form action="#" method="post" name="my_finance" id="my_finance">
                                    <div class="gen_infmtn">
                                        <h6>Finance Information<span class="pull-right"><input type="submit" name="financeSubmit" id="financeSubmit" value="Update" /></span></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>m-Pesa Account Mobile Number</label>
                                                        <input type="text" name="mPesaMobileNumber" id="mPesaMobileNumber" class="form-control" value="<?php echo $financeDataArr[0]->mpesaFullName;?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>m-Pesa Account Number mPIN</label>
                                                        <input type="text" name="mPIN" id="mPIN" class="form-control" value="<?php echo $financeDataArr[0]->mpesaAccount;?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
<script type="text/javascript">
    myJsMain.my_finance();
</script>