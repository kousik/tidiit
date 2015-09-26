<?php echo $html_heading;?>
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>jquery.idealforms.css">
<body class="  ">
    <div class="bg-dark dk" id="wrap">
        <?php echo $top.$left;
        $productPageTypeArr=$this->config->item('productPageTypeArr');?>
        <!-- /#left -->
  <div id="content">
    <div class="outer">
      <div class="inner bg-light lter">
        <div class="row">
          <div class="col-lg-12">
            <div class="box changed" style="display:inline;">
              <header>
                <h5><i class="fa fa-pencil"></i> Update your page type and brand name</h5>
              </header>
              <div class="idealsteps-wrap" >
                  <form action="<?php echo BASE_URL.'index/a_update_brand_page_type';?>" autocomplete="off" class="idealforms" style="margin:20px 0; " method="POST">
                  
                  <div class="form-group field field">
                      <label for="brandName" class="col-sm-4 control-label main"><span title="" data-toggle="tooltip" data-original-title="European Article Number">Enter your brand name : </span></label>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="brandName" placeholder="Brand Name" value="" name="brandName">
                      </div>
                    </div>                    
                    
                    
                    <div class="form-group field">
                      <label for="input-tax-class" class="col-sm-4 control-label main">Select your page type :</label>
                      <div class="col-sm-8">
                        <select class="form-control" id="productPageType" name="productPageType">
                          <option value=""> --- None --- </option>
                          <?php foreach($productPageTypeArr as $k => $v){?>
                                <option value="<?php echo $k;?>" <?php //echo ($k==$productPageType)?'selected' : "";?>><?php echo $v;?></option>
                                <?php }?>
                        </select>
                      </div>
                    </div>
                    
                     <div class="form-group field">
                         <div class="col-sm-6">
                             <div class="field buttons">
                              <label class="main">&nbsp;</label>
                              <button type="submit" class="frm_submit">Submit</button>
                            </div>
                         </div>
                       <div class="col-sm-6">
                            
                        </div>
                  	</div>
                  </form>
                  </div>
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
    <script src="<?php echo SiteJSURL;?>jquery.form.min.js" type="text/javascript"></script> 
    <script src="<?php echo SiteJSURL;?>jquery.idealforms.js" type="text/javascript"></script>
</body>
</html>

<script>
jQuery(document).ready(function(){    
    jQuery('form.idealforms').idealforms({
      silentLoad: false,
      rules: {
        'brandName': 'required'
      },
      onSubmit: function(invalid, e) {
        //e.preventDefault();
        if(confirm('Are you sure to sbmit the data Y/N?')==false){
            return false;
        }
        jQuery('#invalid')
          .show()
          .toggleClass('valid', ! invalid)
          .text(invalid ? (invalid +' invalid fields') : 'All good!');
      }
    });
});
</script>