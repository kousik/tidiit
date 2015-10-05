<?php echo $html_heading;
$productPageTypeArr=$this->config->item('productPageTypeArr');
$mobileBoxContents=$this->config->item('mobileBoxContents');
$mobileColor=$this->config->item('mobileColor');
$mobileDisplayResolution=$this->config->item('mobileDisplayResolution');
$mobileConnectivity=$this->config->item('mobileConnectivity');
$mobileOS=$this->config->item('mobileOS');
$mobileProcessorCores=$this->config->item('mobileProcessorCores');
$mobileBatteryType=$this->config->item('mobileBatteryType');
$mobileProcessorBrand=$this->config->item('mobileProcessorBrand');
$priceRangeSettingsArr=$this->config->item('priceRangeSettings');
$priceRangeSettingsDataArr=$priceRangeSettingsArr[$productPageType];
?>
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>normalize.css"> 
<!--<link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css">-->
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>jquery.idealforms.css">
<script type="text/javascript">
    priceOption='';
    <?php for($i=$priceRangeSettingsDataArr['start'];$i<$priceRangeSettingsDataArr['end'];$i=$i+$priceRangeSettingsDataArr['consistencyNo']){?>
        priceOption=priceOption+'<option value="<?php echo $i;?>"><?php echo $i;?></option>';
    <?php }?>    
</script>
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
                <input type="hidden" name="productPageType" value="<?php echo $productPageType;?>" />
                
                
                <div class="idealsteps-container">

      <nav class="idealsteps-nav"></nav>

      <form action="<?php echo BASE_URL.'product/add_mobile/';?>" autocomplete="off" class="idealforms" style="margin-bottom:40px;" method="POST" enctype="multipart/form-data" name="product_add_form" id="product_add_form">

        <div class="idealsteps-wrap">

          <!-- Step ppp -->

          <section class="idealsteps-step">
          
          <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Product Name</label>                     
                    </div>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" id="title" placeholder="Product Name" value="" name="title" >
              <span class="error"></span>
                      </div>
                      </div>
                    </div>

           <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Product Short Description:</label>                     
                    </div>
                      <div class="col-sm-8">
                         <textarea class="form-control" id="shortDescription" placeholder="Meta Tag Description" rows="5" name="shortDescription"></textarea>
              <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    
           
            <div class="field">
              <div class="box">
                          <header>
                            <div class="icons"> <i class="fa fa-th-large"></i> </div>
                            <h5>Product Description</h5>
                            
                            <!-- .toolbar -->
                            <div class="toolbar">
                              <nav style="padding: 8px;"> <a href="javascript:;" class="btn btn-default btn-xs collapse-box"> <i class="fa fa-minus"></i> </a> <a href="javascript:;" class="btn btn-default btn-xs full-box"> <i class="fa fa-expand"></i> </a> </nav>
                            </div>
                            <!-- /.toolbar --> 
                          </header>
                          <div class="body">
                            <textarea class="ckeditor" cols="80" id="description" name="description" rows="10"></textarea>
                          </div>
                        </div>
            </div>


			<div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Meta Tag Title:</label>                     
                    </div>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" id="metaTitle" placeholder="Meta Tag Title" value="" name="metaTitle" >
              <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    
          <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Meta Tag Description:</label>                     
                    </div>
                      <div class="col-sm-8">
                          <textarea class="form-control" id="metaDescription" placeholder="Meta Tag Description" rows="5" name="metaDescription"></textarea>
              <span class="error"></span>
                      </div>
                      </div>
                    </div>

           
			<div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Meta Tag Keywords:</label>                     
                    </div>
                      <div class="col-sm-8">
                           <textarea class="form-control" id="metaKeyword" placeholder="Meta Tag Keywords" rows="5" name="metaKeyword"></textarea>
              <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    
          
			<div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Product Tags:</label>                     
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="tag" placeholder="Product Tags" value="" name="tag">
              <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    
          
          
            <div class="field buttons">
              <label class="main">&nbsp;</label>
              <button type="button" class="next">Next &raquo;</button>
            </div>

          </section>
          
          <!-- Step 2 -->
          <section class="idealsteps-step">
            
                    <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">In The Box</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Box Contents</label>
                    </div>
                      <div class="col-sm-8">
                          <?php foreach ($mobileBoxContents AS $k=>$v){?>
                          <div class="checkbox">
                            <label>
                                <input type="checkbox" value="<?php echo $k;?>" class="uniform" name="mobileBoxContent[]"><?php echo $v;?> 
                            </label>
                          </div>
                          <?php }?>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Brand</label>
                    </div>
                      <div class="col-sm-8">
                        <select class="form-control" name="brandId" id="brandId">
                            <option value="default">Select</option>  
                            <?php foreach ($brandArr AS $k){?>
                            <option value="<?php echo $k->brandId;?>"><?php echo $k->title;?></option>  
                            <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
              
                  <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Model</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="model" placeholder="Model" value="" name="model">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                 
              
              <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">No Of SIMs</label>
                    </div>
                      <div class="col-sm-8">
                        <select class="form-control" name="noOfSims" id="noOfSims">
                            <option value="default">Select</option>  
                            <option value="1">1</option>  
                            <option value="2">2</option>  
                            <option value="3">3</option>  
                            <option value="4">4</option>  
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="field idealforms-field idealforms-field-checkbox">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Color</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control" name="color" id="color">
                          <?php foreach ($mobileColor AS $k=>$v){?>   
                          <option value="<?php echo $k;?>"><?php echo $v; ?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Other Features</label>
                    </div>
                      <div class="col-sm-8">
                          <textarea class="form-control" id="mobileOtherFeatures" placeholder="Other Features" rows="5" name="mobileOtherFeatures"></textarea>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>                    
                    
                  <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Display</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Screen Size (in cm)</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" placeholder="Screen Size" value="" name="screenSize" id="screenSize">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Display Resolution</label>
                    </div>
                      <div class="col-sm-8">
                         <select class="form-control required" id="displayResolution" name="displayResolution">
                         <option value="">Select</option>
                         <?php foreach ($mobileDisplayResolution AS $k=>$v){?>
                          <option value="<?php echo $k;?>"><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Display Type</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="displayType" placeholder="Display Type" value="" name="displayType">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Pixel Density</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="pixelDensity" placeholder="Pixel Density" value="" name="pixelDensity">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Software</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Operating System</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control required" id="os" name="os">
                         <option value="default">Select</option>  
                         <?php foreach ($mobileOS AS $k=>$v){?>
                          <option value="<?php echo $k;?>"><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                     
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">OS Version</label>
                    </div>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" id="osVersion" placeholder="OS Version" value="" name="osVersion">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                     
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Multi-languages Supported</label>
                    </div>
                      <div class="col-sm-8">
                          <label class="ideal-radiocheck-label" onClick="">
<input type="radio" value="Yes" name="multiLanguages" style="position: absolute; left: -9999px;">
Yes</label>
                         <label class="ideal-radiocheck-label" onClick="">
<input type="radio" value="No" name="multiLanguages" style="position: absolute; left: -9999px;">
No</label>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                 <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Camera</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Rear Camera</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="mobileRearCamera" placeholder="Rear Camera" value="" name="mobileRearCamera">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Flash</label>
                    </div>
                      <div class="col-sm-8">
                          <label class="ideal-radiocheck-label" onClick="">
<input type="radio" value="Yes" name="mobileFlash" style="position: absolute; left: -9999px;">
Yes</label>
                         <label class="ideal-radiocheck-label" onClick="">
<input type="radio" value="No" name="mobileFlash" style="position: absolute; left: -9999px;">
No</label>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Front Camera</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="frontCamera" placeholder="Front Camera" value="" name="frontCamera">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                     
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Other Camera Features</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="mobileOtherCameraFeatures" placeholder="mobile Other Camera Features" value="" name="mobileOtherCameraFeatures">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Connectivity</p>
                    <div class="col-sm-4">
                        <label for="input-model" class="control-label main">&nbsp;</label>
                    </div>
                      <div class="col-sm-8">
                          <?php foreach ($mobileConnectivity AS $k=>$v){?>
                          <div class="checkbox">
                            <label>
                                <input type="checkbox" value="<?php echo $k;?>" class="uniform" name="mobileConnectivity[]"><?php echo $v;?> 
                            </label>
                          </div>
                          <?php }?>
                        <span class="error"></span>
                      </div>
                    
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Processor</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Processor Speed</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="processorSpeed" placeholder="Processor Speed" value="" name="processorSpeed">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Processor Cores</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control required" id="processorCores" name="processorCores">
                         <option value="default">Select</option>
                         <?php foreach ($mobileProcessorCores AS $k=>$v){?>
                          <option value="<?php echo $k;?>"><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Processor Brand</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control required" id="processorBrand" name="processorBrand">
                         <option value="">Select</option>
                         <?php foreach ($mobileProcessorBrand AS $k=>$v){?>
                          <option value="<?php echo $k;?>"><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Memory & Storage</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">RAM</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="ram" placeholder="RAM" value="" name="ram">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Internal Memory</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="internalMemory" placeholder="PInternal Memory" value="" name="internalMemory">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Expandable Memory</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="expandableMemory" placeholder="Expandable Memory" value="" name="expandableMemory">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Memory Card Slot</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="memoryCardSlot" placeholder="Memory Card Slot" value="" name="memoryCardSlot">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Battery & Power</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Battery Capacity</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="batteryCapacity" placeholder="Battery Capacity" value="" name="batteryCapacity">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Battery Type</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control required" id="batteryType" name="batteryType">
                         <option value="">Select</option>
                         <?php foreach ($mobileBatteryType AS $k=>$v){?>
                          <option value="<?php echo $k;?>"><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Talk Time</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="talkTime" placeholder="Talk Time" value="" name="talkTime">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Standby Time</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="standbyTime" placeholder="Standby Time" value="" name="standbyTime">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Warranty</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Warranty Type</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="warrantyType" placeholder="Warranty Type" value="" name="warrantyType">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Warranty Duration(In Month)</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="warrantyDuration" placeholder="Warranty Duration(In Month)" value="" name="warrantyDuration">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>  

                    
           <div class="field buttons">
              <label class="main">&nbsp;</label>
              <button type="button" class="prev">&laquo; Prev</button>
              <button type="button" class="next">Next &raquo;</button>
            </div>

          </section>
          
          <!-- Step 3 -->
          <section class="idealsteps-step">
                    <!--<div class="form-group field">
                      <label for="input-category" class=" control-label main"><span title="" data-toggle="tooltip" data-original-title="(Autocomplete)">Categories</span></label>
                      <div class="col-sm-10">
                        <!--<input type="text" class="form-control" id="input-category" placeholder="Categories" value="" name="category" autocomplete="off"><span class="error"></span>
                        <ul class="dropdown-menu">
                        </ul>
                          
                      </div>
                    </div>-->
                    <input type="hidden" name="categoryId" id="categoryId" value="<?php echo $categoryId;?>">
                                                         
                   <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Other Informations</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Price</label>
                    </div>
                    <div class="col-sm-8">
                        <div class="col-sm-12"  style="padding:0;">
                            <div class="col-sm-3" style="padding:0;"><label style="margin-top:8px;" for="bulkQty">Quantity</label></div>
                            <div class="col-sm-3"  style="padding:0;">
                                <select class="" id="bulkQty" name="bulkQty" style="width:auto; float:right;">
                                      <option value=""> -- Select -- </option>
                                      <?php for($i=$priceRangeSettingsDataArr['start'];$i<$priceRangeSettingsDataArr['end'];$i+=5){?>
                                      <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                      <?php }?>
                                </select>
                            </div>
                            <div class="col-sm-2" style="padding:0;"><label style="margin:8px 0 0 10px;" for="price">Price</label></div>
                            <div class="col-sm-3" style="padding:0;"><input type="text" class="form-control" id="price" placeholder="Price" value="" name="price"  style="width:auto;"></div>
                            <div class="col-sm-2" style="padding:0;">&nbsp;</div>
                        </div>
                        <div class="col-sm-12 more_price_quantity_for_product"  style="padding:0;"></div>
                        <div class="col-sm-12"  style="padding:0;"><button class="addRow" type="button" onClick="addPrice();">Add Another Row</button>
                            <input type="hidden" name="total_price_row_added" id="total_price_row_added" value="">
                        </div>
                    </div>
                    </div>
                    </div>
                   
                   <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                     <label for="input-tax-class" class="control-label main">Tax Class</label>
                      </div>
                      <div class="col-sm-8">
                          <select class="form-control" id="taxable" name="taxable" >
                          <option value=""> --- Select --- </option>
                          <option value="1" selected>Taxable Goods</option>
                          <option value="2">Non-taxable Products</option>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div> 
                    
                   <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                     <label for="input-minimum" class="control-label main"><span title="" data-toggle="tooltip" data-original-title="Force a minimum ordered amount">Minimum Quantity</span></label> 
                       </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control required" id="minQty" placeholder="Minimum Quantity" value="" name="minQty"><span class="error"></span>
                      </div>
                      </div>
                    </div> 
                 
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-subtract" class="control-label main">Quantity</label>
                      </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="qty" placeholder="Minimum Quantity" value="" name="qty">
                        <span class="error"></span>
                       </div>
                      </div>
                    </div>
                    
                   <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-length" class=" control-label main">Dimensions (L x W x H)</label>
                     </div>
                      <div class="col-sm-8">
                        <div class="row">
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="length" placeholder="Length" value="" name="length" style="width:100px;">
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="width" placeholder="Width" value="" name="width"  style="width:100px;">
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="height" placeholder="Height" value="" name="height"  style="width:100px;">
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-length-class" class=" control-label main">Length Class</label>
                      </div>
                      <div class="col-sm-8">
                        <select class="form-control" id="lengthClass" name="lengthClass">
                          <option selected="selected" value="1">Centimeter</option>
                          <option value="2">Millimeter</option>
                          <option value="3">Inch</option>
                        </select>
                        
                        <span class="error"></span>
                      </div>
                    </div>
                    </div>
                    
                   <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-weight" class=" control-label main">Weight</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="weight" placeholder="Weight" value="" name="weight"><span class="error"></span>
                      </div>
                    </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-weight-class" class=" control-label main">Weight Class</label>
                     </div>
                      <div class="col-sm-8">
                        <select class="form-control" id="weightClass" name="weightClass">
                          <option value="1">Kilogram</option>
                          <option selected value="2">Gram</option>
                          <option value="3">Pound </option>
                          <option value="3">Quintal </option>
                        </select>
                        <span class="error"></span>
                      </div>
                    </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-status" class=" control-label main">Status</label>
                       </div>
                      <div class="col-sm-8">
                        <select class="form-control" id="status" name="status">
                          <option selected="selected" value="1">Enabled</option>
                          <option value="0">Disabled</option>
                        </select>
                        <span class="error"></span>
                      </div>
                    </div>
                    </div>
                    
            <div class="field buttons">
              <label class="main">&nbsp;</label>
              <button type="button" class="prev">&laquo; Prev</button>
              <button type="button" class="next">Next &raquo;</button>
            </div>

          </section>
          
           <section class="idealsteps-step">
         	 <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover" id="images">
                        <thead>
                          <tr>
                            <td class="text-left">Image</td>
                          </tr>
                        </thead>
                        <tbody>
                         <tr>
                            <td class="text-left">
                          <div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>                            
                                <input id="img1" name="img1" type="file" multiple>                            
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                          <tr>
                            <td class="text-left">
                            	<div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>                            
                                <input id="img2" name="img2" type="file" multiple>                            
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                          
                          <tr>
                            <td class="text-left">
                            	<div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>                            
                                <input id="img3" name="img3" type="file" multiple>                            
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                          
                          <tr>
                            <td class="text-left">
                            
                            <div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>                            
                                <input id="img4" name="img4" type="file" multiple>                            
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                          
                          <tr>
                            <td class="text-left">
                            
                            <div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>                            
                                <input id="img5" name="img5" type="file" multiple>                            
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                         
                        </tbody>
                        
                      </table>
                    </div>
                    
                    <div class="field buttons">
              <label class="main">&nbsp;</label>
              <button type="button" class="prev">&laquo; Prev</button>
              <button type="submit" class="submit">Submit</button>
            </div>
 			</section>
        </div>
        <span id="invalid"></span>
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
        jQuery('#div-2').show();
	jQuery('.changed').css('display','inline'); 
        
        jQuery('#productPageType').on('change',function(){
            if(jQuery(this).val()!=""){
                location.href='<?php echo BASE_URL .'product/add_product/'?>'+jQuery(this).val();
            }
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
        'brandId': 'select:default',
        'model': 'required',
        'noOfSims': 'select:default',
        'screenSize': 'required',
        'color': 'select:default',
        'os': 'select:default',
        'ram': 'required',
        'internalMemory': 'required',
        'talkTime':'required',
        'standbyTime':'required',
        'WarrantyDuration': 'required',
        'qty': 'required',
        'minQty': 'required',
        'mobileRearCamera': 'required',
        'frontCamera': 'required',
        'processorSpeed': 'required',
        'processorCores':'select:default',
        'img1':'required extension:jpg:png',
        'img2':'required extension:jpg:png',
		'img3':'required extension:jpg:png',
        'bulkQty': 'required',
        'price': 'required',
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
      },
      
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