<?php echo $html_heading; //pre($me);die;?>
<style type="text/css">
    .error{font-size: 10px; color: red;margin-bottom: 0px;font-weight: normal;}
</style>
<!--<link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css">
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>jquery.idealforms.css">-->
<body class="  ">
    <div class="bg-dark dk" id="wrap">
        <?php echo $top.$left;?>
        <!-- /#left -->
  <div id="content">
    <div class="outer">
      <div class="inner bg-light lter">
        <div class="row">
          <div class="col-lg-12">
            <div class="box changed" style="display:block">
                <header>
                  <h5><i class="fa fa-pencil"></i> Edit Profile</h5>
                  <span style="padding-left: 150px;color: red;"><?php echo $this->session->flashdata('Message');?></span>
                </header>
                <form name="addForm" id="addForm" method="post" action="<?php echo BASE_URL . 'index/update_profile'; ?>" autocomplete="off" class="idealforms" style="margin-bottom:40px;" method="POST">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="categoryMainTable">                       
                      <tbody>
                        <tr>
                          <td class="text-left" width="10%">First Name</td>
                          <td class="text-left" width="28%">
                              <input type="text" name="firstName" id="firstName" placeholder="<?php echo $me->firstName;?>" value="<?php echo $me->firstName;?>" class="form-control required">
                              <span class="error"></span>
                          </td>
                          <td class="text-left" width="1%">&nbsp;</td>
                          <td class="text-left" width="10%">Last Name</td>
                          <td class="text-left" width="51%">
                              <input type="text" name="lastName" id="lastName" placeholder="<?php echo $me->lastName;?>" value="<?php echo $me->lastName;?>" class="form-control required">
                              <span class="error"></span>
                          </td>
                        </tr>
                        <tr>
                            <td class="text-left">Email</td>
                            <td class="text-left"><input type="email" name="email" id="email" placeholder="<?php echo $me->email;?>" value="<?php echo $me->email;?>" class="form-control required email"></td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">Mobile</td>
                            <td class="text-left"><input type="phone" name="mobile" id="mobile" placeholder="<?php echo $me->mobile;?>" value="<?php echo $me->mobile;?>" class="form-control required"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Contact Number</td>
                            <td class="text-left"><input type="phone" name="contactNo" id="contactNo" placeholder="<?php echo $me->contactNo;?>" value="<?php echo $me->contactNo;?>" class="form-control required"></td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">Fax</td>
                            <td class="text-left"><input type="phone" name="fax" id="fax" placeholder="<?php echo $me->fax;?>" value="<?php echo $me->fax;?>" class="form-control"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Address</td>
                            <td class="text-left"><input type="text" name="address" id="address" placeholder="<?php echo $billAddressDataArr[0]->address;?>" value="<?php echo $billAddressDataArr[0]->address;?>" class="form-control required"></td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">City</td>
                            <td class="text-left"><input type="text" name="city" id="city" placeholder="<?php echo $billAddressDataArr[0]->city;?>" value="<?php echo $billAddressDataArr[0]->city;?>" class="form-control required"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Country</td>
                            <td class="text-left">
                                <select class="form-control required middle" name="countryId" id="countryId">
                                  <option value="">Select</option>
                                  <?php foreach($CountryDataArr AS $k):?>
                                  <option value="<?php echo $k->countryId;?>" <?php if($k->countryId==$billAddressDataArr[0]->countryId){?>selected<?php }?>><?php echo $k->countryName;?></option>
                                  <?php endforeach;?>
                              </select><span class="error"></span></td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">State</td>
                            <td class="text-left">
                                <span id="showStateData">
                                    <select name="stateId" id="stateId" class="form-control middle" required>
                                        <option value="">Select</option>
                                        <?php foreach($stateDataArr AS $k):?>
                                        <option value="<?php echo $k->stateId;?>" <?php if($k->stateId==$billAddressDataArr[0]->stateId){?>selected<?php }?>><?php echo $k->stateName;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr>
                          <td class="text-left" width="33%">About Me</td>
                          <td class="text-left" width="33%">
                              <textarea class="form-control" name="aboutMe" id="aboutMe" placeholder="<?php echo $me->aboutMe;?>"><?php echo $me->aboutMe;?></textarea>
                          </td>
                          <td></td>
                          <td>Zip</td>
                          <td><input type="text" name="zip" id="zip" placeholder="<?php echo $billAddressDataArr[0]->zip;?>" value="<?php echo $billAddressDataArr[0]->zip;?>" class="form-control required"></td>
                        </tr>
                        <tr>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left"><div class="field buttons">
              <label class="main">&nbsp;</label>
              <button type="submit" class="next">Submit &raquo;</button>
            </div></td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">&nbsp;</td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                
                </form>    
            </div>
          </div>
          <!-- /.col-lg-12 --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.inner --> 
    </div>
    <!-- /.outer --> 
  </div>
        
    </div>
    <?php echo $footer;?>
</body>
</html>
<script type="text/javascript">
    jQuery(document).ready(function(){
       /* jQuery('#addForm').validate();
        jQuery('#addForm').submit(function(e) {
            //e.preventDefault();
            alert($(this).valid());
            if ($(this).valid()==true) {

            }
        });*/

        $("#addForm").validate({
            submitHandler: function(form) {
                form.submit();
            }
        });
        //jQuery('#categoryMainTable').on('')
        jQuery('#countryId').on('change',function(){
            jQuery.ajax({
                type:"POST",
                url:'<?php echo BASE_URL.'ajax/show_state/';?>',
                data:'countryId='+$(this).val(),
                success:function(msg){
                    if(msg!=""){
                        jQuery('#showStateData').html(msg);
                    }
                }
            });
        });
    });
    



</script>