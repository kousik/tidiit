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
                                <form action="#" method="get">
                                    <div class="gen_infmtn">
                                        <h6>Shipping Address <span class="pull-right"><input type="submit" name="" value="Update" /></span></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Name</label>
                                                        <input type="text" name="" class="form-control" value="" required>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Phone</label>
                                                        <input type="text" name="" class="form-control" value="" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Email</label>
                                                        <input type="email" name="" class="form-control" value="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label>Address</label>
                                                <input type="text" name="" class="form-control" value="" required>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-2 col-sm-2 pad_rit_none">
                                                        <label>City</label>
                                                        <input type="text" name="" class="form-control" value="" required>
                                                    </div>
                                                    <div class="col-md-2 col-sm-2 pad_rit_none">
                                                        <label>State</label>
                                                        <p style="position:relative;">
                                                            <select class="form-control nova heght_cntrl" name="country_id" value=""  tabindex="1">
                                                                <option value="">&nbsp;</option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                            </select>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-2 col-sm-2 pad_rit_none">
                                                        <label>Zip Code</label>
                                                        <input type="text" name="" class="form-control" value="" required>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <label>Country</label>
                                                        <p style="position:relative;">
                                                            <select class="form-control nova heght_cntrl" name="country_id" value=""  tabindex="1">
                                                                <option value="">&nbsp;</option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                                <option value=""></option>
                                                            </select>
                                                        </p>
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