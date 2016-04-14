<?php echo $html_heading; //pre($me);die;?>
<style type="text/css">
    .error{font-size: 10px; color: red;margin-bottom: 0px;font-weight: normal;}
</style>
<!--<link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css">-->

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
                      <?php if($warehouses):?>
                          <?php foreach($warehouses as $wh):?>
                              <div class="well w-<?=$wh->id?>" >
                                  <p class="text-left pull-left">
                                      <strong><?=$wh->companyName?></strong><br>
                                      <?=$wh->address1?>, <?=$wh->address2?><br>
                                      <?=$wh->city?>, <?=$wh->stateName?>, <?=$wh->countryName?> <?=$wh->zip?><br>
                                      <abbr title="Phone">Mobile:</abbr> <?=$wh->mobile?><br>
                                      <abbr title="Phone">C:</abbr> <?=$wh->contactNo?>
                                  </p>
                                  <p class="text-right">
                                      <button type="button" data-id="<?=$wh->id?>" class="btn btn-danger btn-xs delete-warehouse"><i class="fa fa-times" aria-hidden="true" alt="delete" title="delete"></i></button><br>
                                      <strong>Tax Invoice</strong><br>
                                      <a href="mailto:#"><?=$wh->taxInvoice?></a><br>
                                      <strong>VAT Number</strong><br>
                                      <a href="mailto:#"><?=$wh->vatNumber?></a><br>
                                  </p>
                              </div>
                          <?php endforeach;?>
                      <?php endif?>
                  </div>
              </div>
          </div>


        <div class="row">
          <div class="col-lg-12">
            <div class="box changed" style="display:block">
                <header>
                  <h5><i class="fa fa-pencil"></i> Add Warehouse</h5>
                  <span style="padding-left: 150px;color: red;"><?php echo $this->session->flashdata('Message');?></span>
                </header>
                <form name="addForm" id="addForm" method="post" action="<?php echo BASE_URL . 'index/update_warehouse'; ?>" autocomplete="off" class="idealforms" style="margin-bottom:40px;" method="POST">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="categoryMainTable">                       
                      <tbody>
                        <tr>
                          <td class="text-left" width="10%">Company Name</td>
                          <td class="text-left" width="28%">
                              <input type="text" name="companyName" id="companyName" placeholder="" value="" class="form-control required">
                              <span class="error"></span>
                          </td>
                          <td class="text-left" width="1%">&nbsp;</td>
                          <td class="text-left" width="10%"></td>
                          <td class="text-left" width="51%"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Tax Invoice</td>
                            <td class="text-left"><input type="text" name="taxInvoice" id="taxInvoice" placeholder="" value="" class="form-control required"></td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">VAT Number</td>
                            <td class="text-left"><input type="text" name="vatNumber" id="vatNumber" placeholder="" value="" class="form-control required"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Mobile</td>
                            <td class="text-left"><input type="text" name="mobile" id="mobile" placeholder="" value="" class="form-control required"></td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">Contact Number</td>
                            <td class="text-left"><input type="text" name="contactNo" id="contactNo" placeholder="" value="" class="form-control required"></td>
                        </tr>
                        <tr>
                            <td class="text-left">Address 1</td>
                            <td class="text-left"><input type="text" name="address1" id="address1" placeholder="" value="" class="form-control required"></td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">Address 2</td>
                            <td class="text-left"><input type="text" name="address2" id="address2" placeholder="" value="" class="form-control required"></td>
                        </tr>

                        <tr>
                            <td class="text-left">Country</td>
                            <td class="text-left">
                                <select class="form-control required middle" name="countryId" id="countryId">
                                  <option value="">Select</option>
                                  <?php foreach($CountryDataArr AS $k):?>
                                  <option value="<?php echo $k->countryId;?>" <?php /*if($k->countryId==$billAddressDataArr[0]->countryId){?>selected<?php }*/?>><?php echo $k->countryName;?></option>
                                  <?php endforeach;?>
                              </select><span class="error"></span></td>
                            <td class="text-left">&nbsp;</td>
                            <td class="text-left">State</td>
                            <td class="text-left">
                                <span id="showStateData">
                                    <select name="stateId" id="stateId" class="form-control middle" required>
                                        <option value="">Select</option>
                                        <?php foreach($stateDataArr AS $k):?>
                                        <option value="<?php echo $k->stateId;?>" ><?php echo $k->stateName;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </span>
                            </td>
                        </tr>
                        <tr>
                          <td class="text-left" width="33%">City</td>
                          <td class="text-left" width="33%">
                              <input type="text" name="city" id="city" placeholder="" value="" class="form-control required">
                          </td>
                          <td></td>
                          <td>Zip</td>
                          <td><input type="text" name="zip" id="zip" placeholder="" value="" class="form-control required"></td>
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

        jQuery("body").delegate('button.delete-warehouse', "click", function(e){
            if( confirm("Are you sure you want to delete this entry?") ) {
                var id = jQuery(this).attr("data-id");
                jQuery.ajax({
                    type: "POST",
                    url: '<?php echo BASE_URL . 'ajax/delete_warehouse/';?>',
                    data: 'id=' + id,
                    success: function (msg) {
                        jQuery('div.w-' + id).remove();
                    }
                });
            }
        });
    });
    



</script>