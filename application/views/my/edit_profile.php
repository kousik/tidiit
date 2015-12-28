<?php echo $html_heading; echo $header; //pre($userDataArr);?>
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
                                <form action="#" method="post" name="my_profile" id="my_profile">
                                    <div class="gen_infmtn">
                                        <h6>Update Profile <span class="pull-right"><input type="submit" name="profileSubmit" id="profileSubmit" value="Update" /></span></h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>First Name</label>
                                                        <input type="text" name="firstName" id="firstName" class="form-control" value="<?php echo $userDataArr[0]->firstName;?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Last Name</label>
                                                        <input type="text" name="lastName" id="lastName" class="form-control" value="<?php echo $userDataArr[0]->lastName;?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label>Phone</label>
                                                        <input type="text" name="contactNo" id="contactNo" class="form-control" value="<?php echo $userDataArr[0]->contactNo;?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Email</label>
                                                        <input type="email" name="email" id="email" class="form-control email" value="<?php echo $userDataArr[0]->email;?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                            <label>mobile</label>
                                                            <input type="text" name="mobile" id="mobile" class="form-control" value="<?php echo $userDataArr[0]->mobile?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Fax</label>
                                                        <input type="text" name="fax" id="fax" class="form-control" value="<?php echo $userDataArr[0]->fax;?>">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Date of Birth</label>
                                                        <input type="text" name="DOB" id="DOB" class="form-control" value="<?php echo ($userDataArr[0]->DOB!="") ? date('d-m-Y',strtotime($userDataArr[0]->DOB)) :"";?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <label>About Me</label>
                                                <textarea class="form-control" name="aboutMe" id="aboutMe" cols="35" rows="5"><?php echo $userDataArr[0]->aboutMe;?></textarea>
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
    myJsMain.my_profile();
    jQuery(document).ready(function(){
        jQuery('#DOB').datepicker({changeMonth: true,changeYear: true,dateFormat:"dd-mm-yy"});
    });
</script>