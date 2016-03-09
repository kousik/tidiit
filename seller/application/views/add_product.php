<?php echo $html_heading;?>
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>normalize.css"> 
<!--<link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css">-->
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>jquery.idealforms.css">
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
                  <h5><i class="fa fa-pencil"></i> Add Product</h5>
                  <span style="padding-left: 150px;color: red;"><?php echo $this->session->flashdata('Message');?></span>
                </header>
                <form name="addForm" id="addForm" method="post" action="">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="categoryMainTable">                       
                      <tbody>
                        <tr>
                          <td class="text-left" width="33%">Select Category</td>
                          <td class="text-left" width="33%">
                              <select class="form-control" name="topCategory" id="topCategory">
                              <option value="">Select</option>
                              <?php foreach($categoryData as $k){?>
                              <option value="<?php echo $k->categoryId;?>"><?php echo $k->categoryName;?></option>
                              <?php }?>
                              </select>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">                       
                      <tbody>
                        <tr>
                            <td class="text-left">
                                <div class="field buttons">
                                    <button type="submit" name="submitForProduct" id="submitForProduct" class="btn btn-info">Show for Product Upload</button>
                                </div>
                            </td>
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
    <script src="<?php echo SiteJSURL;?>jquery.form.min.js" type="text/javascript"></script> 
    <script src="<?php echo SiteJSURL;?>jquery.idealforms.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.4.5/ckeditor.js"></script>
    <?php echo $footer;?>
    
    <script src="<?php echo SiteJSURL;?>metis-form-general.js" type="text/javascript"></script>
    <script src="<?php echo SiteJSURL;?>metis-form-validation.js" type="text/javascript"></script>
    <script src="<?php echo SiteJSURL;?>metis-form-wizard.js" type="text/javascript"></script>
    <script src="<?php echo SiteJSURL;?>metis-form-wysiwyg.js" type="text/javascript"></script>
    
</body>
</html>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#addForm').validate();
        jQuery('#addForm').submit(function(e) {
            e.preventDefault();
            if ($(this).valid()==true) {
                var cId="";
                jQuery("#categoryMainTable select").each(function(){
                    if(jQuery(this).val()==""){
                        if(cId==""){
                            alert("please select");
                        }else{
                            location.href='<?php echo base_url().'product/add_product/'?>'+cId;
                        }
                    }else{
                        cId=jQuery(this).val();
                    }
                });
                location.href='<?php echo base_url().'product/add_product/'?>'+cId;
            }
            
        });
        //jQuery('#categoryMainTable').on('')
        jQuery('#topCategory').on('change',function(){
            var firstRow=0;
            jQuery("#categoryMainTable tr").each(function(){
                if(firstRow>0){
                    jQuery(this).remove();
                }else{
                    firstRow=1;
                }
            });
            if(jQuery(this).val()==""){
                return false;
            }
           var ajaxURL="<?php echo base_url()."ajax/next_category_dropdown/"?>"; 
           var ajaxData="categoryId="+jQuery(this).val()+'&required=yes';
           jQuery.ajax({
               type:"POST",
               url:ajaxURL,
               data:ajaxData,
               success:function(msg){
                   jQuery("#categoryMainTable tr:last").after(msg);
               }
           });
        });
        jQuery('#div-2').show();
	jQuery('.changed').css('display','inline'); 
        
        jQuery('#topCategory').on('change',function(){
            
        });
        
        //Metis.formWysiwyg();
        
          jQuery('form.idealforms').find('input, select, textarea').on('change keyup', function() {
            jQuery('#invalid').hide();
          });

          /*jQuery('form.idealforms').idealforms('addRules', {
            'comments': 'required minmax:50:200'
          });*/

          jQuery('.prev').click(function(){
            jQuery('.prev').show();
            jQuery('form.idealforms').idealforms('prevStep');
          });
          jQuery('.next').click(function(){
            jQuery('.next').show();
            jQuery('form.idealforms').idealforms('nextStep');
          });
          
          jQuery('.more_price_quantity_for_product').on('click','.removePriceRow',function(){
              var currentPriceRow=jQuery(this).attr('alt');
              jQuery('#remove_price_quantity_for_product_'+jQuery(this).attr('alt')).remove();
          });
    });
    
var price_row = 0;
function addPrice() {
	
	price_row++;
        
	tabRow = '';
	tabRow += '<div class="col-sm-12"  style="padding:0;"></div>';
	tabRow += '<div class="col-sm-12"  style="padding:0;" id="remove_price_quantity_for_product_'+price_row+'">';
	tabRow += '<div class="col-sm-3" style="padding:0;"><label style="margin-top:8px;" for="bulkQty_' + price_row + '">Quantity ' + price_row + '</label></div>';
	tabRow += '<div class="col-sm-3"  style="padding:0;">';
	tabRow += '<select class="required" id="bulkQty_' + price_row + '" name="bulkQty_' + price_row + '" style="width:auto; float:right;">';
        tabRow += '<option value="">-- Select --</option>';
        tabRow += priceOption;
        tabRow += '</select></div>';
	tabRow += '<div class="col-sm-2" style="padding:0;"><label style="margin:8px 0 0 10px;" for="price">Price ' + price_row + '</label></div>';
        tabRow += '<div class="col-sm-3" style="padding:0;"><input type="text" class="form-control required" id="price_' + price_row + '" placeholder="Price" value="" name="price_' + price_row + '"  style="width:auto;"></div>';
        tabRow += '<div class="col-sm-2" style="padding:0;"><button class="removePriceRow" type="button" alt="' + price_row + '">Remove Row</button></div>';
	tabRow += '</div>';
	tabRow += '</div>';
        //alert(tabRow);
        jQuery('#total_price_row_added').val(price_row+1);
        //alert(tabRow);
	jQuery(".more_price_quantity_for_product").append(tabRow);
    var qty='bulkQty_' + price_row ;
    var prc='price_' + price_row ;
    jQuery('form.idealforms').idealforms('toggleFields', qty+' '+prc);
}

jQuery(document).ready(function(){
    
    jQuery('form.idealforms').idealforms({
      silentLoad: false,
      rules: {
        'title': 'required',
        'shortDescription':'required minmax:10:200',
        'metaTitle': 'required',
        'metaDescription': 'required',
        'metaKeyword': 'required',
        'tag': 'required',
		'mobileBoxContent[]': 'minoption:2 maxoption:3',
        'model': 'required',
        'noOfSims': 'select:default',
        'color': 'select:default',
        'os': 'select:default',
        'ram': 'required',
        'internalMemory': 'required',
        'talkTime':'required',
        'standbyTime':'required',
        'WarrantyDuration': 'required',
        'qty': 'required',
        'minQty': 'required',
        'categoryId':'select:default',
        'img1':'required extension:jpg:png',
        'img2':'required extension:jpg:png',
		'img3':'required extension:jpg:png',
        'bulkQty': 'required',
        'price': 'required'
      },

      onSubmit: function(invalid, e) {
        jQuery('#invalid')
          .show()
          .toggleClass('valid', ! invalid)
          .text(invalid ? (invalid +' invalid fields') : 'All good!');
          if(invalid==0){
              //alert('submiting form');
                /*for ( instance in CKEDITOR.instances )
                    CKEDITOR.instances[instance].updateElement();
              //alert('lets us submit the form by ajax');
              myJsMain.commonFunction.ajaxSubmit($('#product_add_form'),myJsMain.baseURL+'product/add_mobile' , productAddFormCallback);*/
          }else{
              e.preventDefault();
          }
      }
      
    });
});

function productAddFormCallback(){
    myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
    if(resultData.result=='good'){
        location.href=myJsMain.baseURL+'product/viewlist';
        //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.url,200);
    }
}
</script>