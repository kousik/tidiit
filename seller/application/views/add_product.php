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
                <h5><i class="fa fa-pencil"></i> Add Product</h5>
              </header>
              <div class="table-responsive">
                      <table class="table table-bordered table-hover">                       
                        <tbody>
                          <tr>
                            <td class="text-left">Select Category</td>
                            <td class="text-left">
                                <select class="form-control" name="productPageType" id="productPageType">
                                <option value="">Select</option>
                                <?php foreach($productPageTypeArr as $k => $v){?>
                                <option value="<?php echo $k;?>" <?php echo ($k==$productPageType)?'selected' : "";?>><?php echo $v;?></option>
                                <?php }?>
                                </select>
                            </td>
                          </tr>
                        </tbody>
                      </table>
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
    $('#productPageType').on('change',function(){
        if($(this).val()!=""){
            location.href='<?php echo BASE_URL .'product/add_product/'?>'+$(this).val();
        }
    });
</script>